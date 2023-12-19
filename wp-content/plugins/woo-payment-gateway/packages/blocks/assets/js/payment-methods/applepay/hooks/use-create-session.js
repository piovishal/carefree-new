import {useEffect, useState, useRef, useCallback} from '@wordpress/element';
import {__} from '@wordpress/i18n';
import {range} from 'lodash';
import {usePaymentMethodDataContext} from "../../context";
import {usePaymentEventsHandler} from "../../hooks";
import apiFetch from '@wordpress/api-fetch';
import {isEqual, isEmpty} from 'lodash';
import {getSetting} from '@woocommerce/settings';
import {formatLineItems, formatShippingMethods} from "../utils";
import {
    removeNumberPrecision,
    extractSelectedShippingOption,
    getSelectedShippingOptionId,
    extractAddressLines,
    DEFAULT_BILLING_ADDRESS,
    DEFAULT_SHIPPING_ADDRESS
} from "../../utils";

const isOlderVersion = getSetting('wcBraintreeData').isOlderVersion;

export const useCreateSession = (
    {
        applePayInstance,
        billing,
        shippingData,
        paymentRequest,
        eventRegistration,
        onClick,
        onClose,
        getData
    }) => {
    const {addShippingHandler} = usePaymentEventsHandler({
        billing,
        shippingData,
        eventRegistration
    });
    const {setPaymentMethodNonce, onPaymentDataFilter, notice: {addNotice}} = usePaymentMethodDataContext();
    const applePayData = useRef({});
    const currentData = useRef({
        billing,
        shippingData,
        onClick,
        onClose,
        addNotice,
        addShippingHandler,
        setPaymentMethodNonce,
        onPaymentDataFilter
    });

    const getApplePayVersion = useCallback(() => {
        // always check support from highest version first
        for (let version of range(10, 2, -1)) {
            if (ApplePaySession.supportsVersion(version)) {
                return version;
            }
        }
        return 3;
    }, []);

    const onSessionCancel = useCallback(() => {
        const {onClose} = currentData.current;
        onClose();
    }, []);

    const onValidateMerchant = useCallback((event) => {
        const {session} = currentData.current;
        applePayInstance.performValidation({
            validationURL: event.validationURL,
            displayName: getData('displayName')
        }).then(merchantSession => {
            session.completeMerchantValidation(merchantSession);
        }).catch(error => {
            session.abort();
            addNotice(error);
        })
    }, [applePayInstance, addNotice]);

    const onPaymentMethodSelected = useCallback((event) => {
        // update the cart billing address info
        const {paymentMethod} = event;
        const {session, addNotice} = currentData.current;
        let address = null;
        if (paymentMethod.billingContact) {
            address = {
                country: paymentMethod.billingContact?.countryCode || '',
                state: paymentMethod.billingContact?.administrativeArea || '',
                postcode: paymentMethod.billingContact?.postalCode || '',
                city: paymentMethod.billingContact?.locality || ''
            }
        }
        apiFetch({
            url: getData('routes').paymentMethod,
            method: 'POST',
            data: {
                address
            }
        }).then(response => {
            if (response.code) {
                session.abort();
                addNotice(response.messages);
            } else {
                session.completePaymentMethodSelection(response.data);
            }
        }).catch((xhr) => {

        });
    }, []);

    const onShippingContactSelected = useCallback((event) => {
        const {shippingContact} = event;
        const {shippingData, addShippingHandler} = currentData.current;
        const {shippingAddress, setShippingAddress} = shippingData;
        const {country, state, city, postcode} = shippingAddress;
        const newAddress = {
            country: shippingContact?.countryCode.toUpperCase(),
            state: shippingContact?.administrativeArea.toUpperCase(),
            city: shippingContact.locality,
            postcode: shippingContact.postalCode
        }
        const addressEqual = isEqual({country, state, city, postcode}, newAddress);
        addShippingHandler((success, {billing, shippingData}) => {
            const {session} = currentData.current;
            const {cartTotal, cartTotalItems, currency} = billing;
            const {shippingRates, selectedRates} = shippingData;
            const updateData = {
                newTotal: {
                    label: getData('displayName'),
                    type: 'final',
                    amount: removeNumberPrecision(cartTotal.value, currency.minorUnit).toString()
                },
                newLineItems: formatLineItems(cartTotalItems, currency.minorUnit),
                newShippingMethods: formatShippingMethods(shippingRates)
            };
            currentData.current.errors = [];
            if (!success) {
                updateData.newShippingMethods = [{
                    identifier: '0:error:1',
                    label: __('No shipping methods', 'woo-payment-gateway'),
                    detail: __('There are no shipping options available for the selected address.', 'woo-payment-gateway'),
                    amount: '0.00'
                }];
                currentData.current.errors = [new ApplePayError('addressUnserviceable')];
            }
            session.completeShippingContactSelection(updateData);
        }, addressEqual);
        setShippingAddress({...shippingAddress, ...newAddress});
    }, []);

    const onShippingMethodSelected = useCallback((event) => {
        const {shippingMethod} = event;
        const {session, billing, shippingData, addNotice} = currentData.current;
        const {cartTotal} = billing;
        const {setSelectedRates, selectedRates} = shippingData;
        const selectedRate = getSelectedShippingOptionId(selectedRates);
        const isRateEqual = selectedRate == shippingMethod.identifier;
        setSelectedRates(...extractSelectedShippingOption(shippingMethod.identifier));
        addShippingHandler((success, {billing}) => {
            const {currency, cartTotalItems, cartTotal} = billing;
            if (success) {
                session.completeShippingMethodSelection({
                    newTotal: {
                        label: getData('displayName'),
                        type: 'final',
                        amount: removeNumberPrecision(cartTotal.value, currency.minorUnit).toString()
                    },
                    newLineItems: formatLineItems(cartTotalItems, currency.minorUnit)
                });
            } else {
                session.abort();
                addNotice(__('There was an error updating your cart totals.', 'woo-payment-gateway'));
            }
        }, isRateEqual);
    }, []);

    const onPaymentAuthorized = useCallback((event) => {
        const {session, setPaymentMethodNonce, errors = []} = currentData.current;
        if (errors?.length > 0) {
            session.completePayment({
                status: ApplePaySession.STATUS_FAILURE,
                errors
            });
        } else {
            applePayInstance.tokenize({
                token: event?.payment?.token
            }).then(response => {
                const {shippingContact = null, billingContact = null} = event.payment;
                applePayData.current.billingData = {};
                if (shippingContact) {
                    if (currentData.current.shippingData.needsShipping) {
                        applePayData.current.shippingAddress = {
                            first_name: shippingContact?.givenName,
                            last_name: shippingContact?.familyName,
                            city: shippingContact?.locality,
                            state: shippingContact?.administrativeArea,
                            postcode: shippingContact?.postalCode,
                            country: shippingContact?.countryCode,
                            ...extractAddressLines(shippingContact?.addressLines || [])
                        }
                    }
                    if (shippingContact?.phoneNumber) {
                        applePayData.current.billingData.phone = shippingContact?.phoneNumber;
                    }
                    if (shippingContact?.emailAddress) {
                        applePayData.current.billingData.email = shippingContact?.emailAddress;
                    }
                }
                if (billingContact) {
                    applePayData.current.billingData = {
                        first_name: billingContact?.givenName,
                        last_name: billingContact?.familyName,
                        city: billingContact?.locality,
                        state: billingContact?.administrativeArea,
                        postcode: billingContact?.postalCode,
                        country: billingContact?.countryCode,
                        ...extractAddressLines(billingContact?.addressLines || ''),
                        ...applePayData.current.billingData
                    }
                }
                setPaymentMethodNonce(response.nonce);
                session.completePayment(ApplePaySession.STATUS_SUCCESS);
            }).catch(error => {
                session.completePayment(ApplePaySession.STATUS_FAILURE);
            })
        }
    }, [applePayInstance]);

    useEffect(() => {
        currentData.current = {
            ...currentData.current,
            billing,
            shippingData,
            onClick,
            onClose,
            addNotice,
            addShippingHandler,
            setPaymentMethodNonce
        };
    });

    useEffect(() => {
        const unsubscribe = onPaymentDataFilter((data, {billing, shippingData}) => {
            if (!isEmpty(applePayData.current?.billingData)) {
                if (isOlderVersion) {
                    data.meta.billingData = {...billing.billingData, ...applePayData.current.billingData};
                } else {
                    data.meta.billingAddress = {
                        ...DEFAULT_BILLING_ADDRESS,
                        ...billing.billingData,
                        ...applePayData.current.billingData
                    };
                }
            }
            if (!isEmpty(applePayData.current?.shippingAddress)) {
                if (isOlderVersion) {
                    data.meta.shippingData = {
                        address: {...shippingData.shippingAddress, ...applePayData.current.shippingAddress}
                    }
                } else {
                    data.meta.shippingAddress = {
                        ...DEFAULT_SHIPPING_ADDRESS,
                        ...shippingData.shippingAddress,
                        ...applePayData.current.shippingAddress
                    };
                }
            }
            return data;
        });
        return () => unsubscribe();
    }, [onPaymentDataFilter]);

    const createSession = useCallback(() => {
        const {shippingData} = currentData.current;
        const session = new ApplePaySession(getApplePayVersion(), paymentRequest);
        session.onvalidatemerchant = onValidateMerchant;
        session.onpaymentmethodselected = onPaymentMethodSelected;
        session.onpaymentauthorized = onPaymentAuthorized;
        session.oncancel = onSessionCancel;
        if (shippingData.needsShipping) {
            session.onshippingcontactselected = onShippingContactSelected;
            session.onshippingmethodselected = onShippingMethodSelected;
        }
        currentData.current.session = session;
        return session;
    }, [
        applePayInstance,
        paymentRequest,
        getApplePayVersion,
        onValidateMerchant,
        onPaymentMethodSelected,
        onPaymentAuthorized,
        onShippingContactSelected,
        onShippingMethodSelected
    ]);

    const handleClick = useCallback((e) => {
        e.preventDefault();
        currentData.current.onClick();
        try {
            const session = createSession();
            session.begin();
        } catch (error) {
            alert(error);
        }
    }, [createSession]);

    return {handleClick};
}

export default useCreateSession;
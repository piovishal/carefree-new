import {useState, useRef, useCallback, useEffect} from '@wordpress/element';
import {getSetting} from '@woocommerce/settings';
import {getDisplayItems, getShippingOptions, getSelectedShippingOptionId} from "../helpers";
import {
    removeNumberPrecision,
    extractFullName,
    DEFAULT_BILLING_ADDRESS,
    DEFAULT_SHIPPING_ADDRESS
} from "../../utils";
import {usePaymentMethodDataContext} from "../../context";

const isOlderVersion = getSetting('wcBraintreeData').isOlderVersion;

export const useHandleTokenization = (
    {
        getData,
        onClick,
        onClose,
        googlePayInstance,
        paymentsClient,
        billing,
        shippingData
    }) => {
    const currentData = useRef({billing, shippingData});
    const payloadData = useRef({});
    const {setPaymentMethodNonce, onPaymentDataFilter} = usePaymentMethodDataContext();
    useEffect(() => {
        currentData.current = {billing, shippingData, onClick, onClose};
    }, [
        onClick,
        onClose,
        shippingData,
        billing
    ]);

    useEffect(() => {
        const unsubscribe = onPaymentDataFilter((data, {billing, shippingData}) => {
            if (payloadData.current?.billingData) {
                let key = 'billingAddress';
                if (isOlderVersion) {
                    key = 'billingData';
                    data.meta.billingData = {
                        ...billing.billingData,
                        ...payloadData.current.billingData
                    };
                } else {
                    data.meta.billingAddress = {
                        ...DEFAULT_BILLING_ADDRESS,
                        ...payloadData.current.billingData
                    };
                }
                if (payloadData.current.email) {
                    data.meta[key].email = payloadData.current.email;
                }
            }
            if (payloadData.current?.shippingAddress) {
                if (isOlderVersion) {
                    data.meta.shippingData = {
                        address: {...shippingData.shippingAddress, ...payloadData.current.shippingAddress}
                    }
                } else {
                    data.meta.shippingAddress = {
                        ...DEFAULT_SHIPPING_ADDRESS,
                        ...shippingData.shippingAddress,
                        ...payloadData.current.shippingAddress
                    }
                }
            }
            return data;
        });
        return () => unsubscribe();
    }, [onPaymentDataFilter]);

    const handleClick = useCallback(() => {
        const {billing, shippingData} = currentData.current;
        const {billingData, currency, cartTotal, cartTotalItems} = billing;
        const {needsShipping, shippingRates} = shippingData;
        currentData.current.onClick();
        const paymentDataRequestArgs = {
            merchantInfo: {
                merchantName: getData('googleMerchantName'),
                merchantId: getData('googleMerchantId')
            },
            transactionInfo: {
                countryCode: getData('countryCode'),
                currencyCode: currency.code,
                totalPriceStatus: 'FINAL',
                totalPrice: removeNumberPrecision(cartTotal.value, currency.minorUnit).toString(),
                totalPriceLabel: getData('totalPriceLabel'),
                displayItems: getDisplayItems(cartTotalItems, currency.minorUnit)
            },
            emailRequired: !billingData.email,
            callbackIntents: ['PAYMENT_AUTHORIZATION'],
            shippingAddressRequired: needsShipping
        };
        if (needsShipping) {
            paymentDataRequestArgs.shippingAddressParameters = {};
            paymentDataRequestArgs.shippingOptionRequired = true;
            paymentDataRequestArgs.callbackIntents = [...paymentDataRequestArgs.callbackIntents, 'SHIPPING_ADDRESS', 'SHIPPING_OPTION'];
            const shippingOptions = getShippingOptions(shippingRates);
            if (shippingOptions.length) {
                paymentDataRequestArgs.shippingOptionParameters = {
                    shippingOptions,
                    defaultSelectedOptionId: getSelectedShippingOptionId(shippingRates)
                }
            }
        }
        const paymentDataRequest = googlePayInstance.createPaymentDataRequest(paymentDataRequestArgs);
        paymentDataRequest.allowedPaymentMethods[0].parameters.assuranceDetailsRequired = true;
        paymentDataRequest.allowedPaymentMethods[0].parameters.billingAddressRequired = true;
        paymentDataRequest.allowedPaymentMethods[0].parameters.billingAddressParameters = {
            format: 'FULL',
            phoneNumberRequired: true
        }
        paymentsClient.loadPaymentData(paymentDataRequest).then(async (payload) => {
            // parse the payload
            if (payload?.paymentMethodData?.info?.billingAddress) {
                const address = payload.paymentMethodData.info.billingAddress;
                const [first_name, last_name] = extractFullName(address.name);
                payloadData.current.billingData = {
                    first_name,
                    last_name,
                    address_1: address.address1,
                    address_2: address.address2,
                    city: address.locality,
                    state: address.administrativeArea,
                    postcode: address.postalCode,
                    country: address.countryCode
                }
            }
            if (payload.shippingAddress) {
                const address = payload.shippingAddress;
                const [first_name, last_name] = extractFullName(address.name);
                payloadData.current.shippingAddress = {
                    first_name,
                    last_name,
                    address_1: address.address1,
                    address_2: address.address2,
                    city: address.locality,
                    state: address.administrativeArea,
                    postcode: address.postalCode,
                    country: address.countryCode
                };
            }
            if (payload.email) {
                payloadData.current.email = payload.email;
            }
            const data = await googlePayInstance.parseResponse(payload);
            // set payment method
            payloadData.current.paymentMethodNonce = data.nonce;
            setPaymentMethodNonce(data.nonce);
        }).catch(error => {
            if (error?.statusCode === 'CANCELED') {
                currentData.current.onClose();
                return;
            }
            console.log(error);
        });
    }, [
        paymentsClient,
        googlePayInstance,
        setPaymentMethodNonce
    ]);
    return handleClick;
}

export default useHandleTokenization;
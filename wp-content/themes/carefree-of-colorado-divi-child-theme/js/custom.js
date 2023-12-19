TODO:

    jQuery(document).ready(function ($) {

    /* =UNIQUE(E2:E885)
       =COUNTIF(E2:E885, F2)

         when .fabric-color radio is clicked */

        $('body').on('updated_checkout', function () {
            $('.cart_item').each(function () {
                if ($(this).find('.variation-PartNumber').length) {

                    let part_number = $(this).find('.variation-PartNumber p').text();
                    $(this).find('td:first').append('<p class="part-number">' + part_number + '</p>');


                }
            });

        });


        $('.configurator-wrap .single_add_to_cart_button').on('click', function (event) {
            console.log('single_add_to_cart_button clicked');
             /* this is the selector used by the paypal checkout plugin */
            var form = $('.cart');
            var validator;

            if (form.tc_valid() == false) {

                 /* create div with class .tc-epo-errors */
                let error_div = '<div class="tc-epo-errors">The configuration contains errors that must be corrected before you can add your product to the shopping cart.</div>';
                 /* append error_div to .tc-epo-errors */

                 /* if error_div doesnt exist yet */
                if ($('.tc-epo-errors').length == 0) {


                    if ($('.single_add_to_cart_button').closest('.review-config-div').length) {
                        $(error_div).insertBefore('.single_add_to_cart_button');
                    } else {
                        $(".review-config-div .tc-element-inner-wrap > .tc-row > .tc-cell").append(error_div);
                    }

                     /* append error_div before add to cart button */


                }

            } else {
                console.log('no errors');
                $('.tc-epo-errors').remove();
            }
        });


         /*  if ($('.selected-fabric').length) {

            $('body').on('change', 'ul[data-tm-connector="fabric-options"] input', function () {

           if .selected-fabric div exists
          let fabric_type = getFabricType();
          console.log(fabric_type);

              $('.selected-fabric').val("Marquee" + fabric_type);

        });

        }

        if page contains class feet-inches-custom anywhere */


        function cartPartIdUpdate() {
            $('.cart_item').each(function () {

                 /* if dd exists with classs variation-PartNumber */
                if ($(this).find('.variation-PartNumber').length) {
                    console.log('update cart column');

                    let part_number = $(this).find('.variation-PartNumber p').text();

                     /* move part_number to .shop_table tbody first td*/
                    $(this).find('.product-partnum').text(part_number);


                }
            });

        }


         /* if is cart page or checkout */
        if ($('body').hasClass('woocommerce-cart')) {
            /* for each row in the cart */
            cartPartIdUpdate();

            $(document.body).on('removed_from_cart updated_cart_totals added_to_cart', function () {
                cartPartIdUpdate();
            });


        }
        ;


        $('body').on('change', '.ascent-length select, ul.roof-flange-ul li input', function () {
                let curr_selection = $('body .ascent-bracket').filter(':checked');
                 /* if ascent bracket is checked */

                if (curr_selection.is(":checked")) {
                    $('.ascent-bracket').click();
                }

            }
        );

         /* everytime input with class .guard-color or radio with class .deselect-other-radios is changed */
        $('body').on('change', '.case-color, .check-color-match input[type="radio"]', function () {


            if ($(this).hasClass("skip-color-check")) {
            } else {

                let guard_color = $('.case-color-div input').filter(':checked').val();


                guard_color = guard_color.split('(')[0];
                console.log(guard_color);

                let arm_type = $('.check-color-match input.tc-epo-field-product[type="radio"]').filter(':checked');
                /*  get the label of the selected radio button */
                arm_type_text = $(arm_type).closest('li').find('.tc-label.tm-label').text();
                guard_color = guard_color.toLowerCase().replace(/\s/g, '');
                ;
                 /* strip whitespace from guard_color */

                arm_type_text = arm_type_text.toLowerCase();


                 /* if guard_color isnt a substring of arm_type_text */
                console.log('arm_type_text o: ' + arm_type_text);

                 /* if both arm_Type and guard_color are not undefined and guard_color is not "matching canopy" */
                if (arm_type_text !== undefined && guard_color !== undefined && guard_color.toLowerCase().indexOf('matching') == -1) {

                    console.log('arm_type_text: ' + arm_type_text);
                    if (arm_type_text.indexOf(guard_color) == -1) {

                        if ((guard_color == 'satin' || guard_color == 'carbon') && (arm_type_text == 'satin' || arm_type_text == 'black')) {
                            console.log('correct color');
                            $('#error_ARM_WGColor').remove();
                            $('.color-match-error-div').hide();
                        } else {


                            console.log(guard_color + " is not a substring of " + arm_type_text);

                            if ($('.color-match-error-div').length > 0) {
                                $('.color-match-error-div').show();
                            } else if ($('#error_ARM_WGColor').length == 0) {
                                var error_message = '<div class="alert displayPanel infoColor" role="alert" id="error_ARM_WGColor" style="display: block;"><p>The Guard color and the Arm color you\'ve selected don\'t match. You can order it this way. Just make sure this is what you intended.</p></div>';

                                let arm_type_ul = $(arm_type).closest('ul');
                                $(arm_type_ul).append(error_message);
                            }
                        }

                    } else {
                        console.log('correct color');
                        $('#error_ARM_WGColor').remove();
                        $('.color-match-error-div').hide();
                    }
                }
            }


        });


         /* everytime input with class .guard-color or radio with class .deselect-other-radios is changed */
        $('body').on('change', '.guard-color, .deselect-other-radios input[type="radio"]', function () {

            if ($(".deselect-other-radios").hasClass("skip-color-check")) {
            } else {

                let guard_color = $('.guard-color-div input').filter(':checked').val();


                guard_color = guard_color.split('(')[0];
                console.log(guard_color);

                let arm_type = $('.deselect-other-radios input.tc-epo-field-product[type="radio"]').filter(':checked');

                arm_type_text = $(arm_type).closest('li').find('.tc-label.tm-label').text();
                guard_color = guard_color.toLowerCase().replace(/\s/g, '');
                ;
                arm_type_text = arm_type_text.toLowerCase();


                if (arm_type_text !== undefined && guard_color !== undefined && guard_color.toLowerCase().indexOf('matching') == -1) {


                    if (arm_type_text.indexOf(guard_color) == -1) {

                        if ((guard_color == 'satin' || guard_color == 'carbon' || guard_color == 'black') && (arm_type_text == 'satin' || arm_type_text == 'black')) {
                            console.log('correct color');
                            $('#error_ARM_WGColor').remove();
                            $('.color-match-error-div').hide();
                        } else {


                            console.log(guard_color + " is not a substring of " + arm_type_text);
                            let arm_type_ul = $(arm_type).closest('ul');


                            if ($(arm_type_ul).find('#error_ARM_WGColor') !== 0) {
                                console.log('remove error message');
                                $('#error_ARM_WGColor').remove();
                            }


                            if ($('.color-match-error-div').length > 0) {
                                $('.color-match-error-div').show();
                            } else if ($('#error_ARM_WGColor').length == 0) {
                                var error_message = '<div class="alert displayPanel infoColor" role="alert" id="error_ARM_WGColor" style="display: block;"><p>The Guard color and the Arm color you\'ve selected don\'t match. You can order it this way. Just make sure this is what you intended.</p></div>';


                                console.log(arm_type_ul);
                                $(arm_type_ul).append(error_message);
                            }
                        }

                    } else {
                        console.log('correct color');
                        $('.color-match-error-div').hide();
                        $('#error_ARM_WGColor').remove();
                    }
                }
            }

        });


        let canopy_fabric;
        let guard_color;
        let guard_value;
        let reset_radio;
        $('body .deselect-other-radios .tm-element-ul-product input[type="radio"]').on('change', function () {

            $('.alpine-conditional-check').prop('checked', true);
            $('.deselect-other-radios .tm-element-ul-product input[type="radio"]').not(this).prop('checked', false);
            reset_radio = $('body .deselect-other-radios  .tm-element-ul-product input[type="radio"]').not(this);
            $.each(reset_radio, function (index, value) {

                 /* $(value).closest('li').find('.tm-epo-reset-radio').click(); */

            });


            


        });


         /* when one guard color radio is selected, the other guard color radio is deselected */
        $('.guard-color-div input').click(function () {
            $('.guard-color-div input').not(this).prop('checked', false);
        });


         /* when a child radio button of .deselect-parent is changed, deselect all other child radio buttons with class .deselect-child */

        $('.deselect-parent-div input').on('change', function () {
            $('.deselect-child-div input').not(this).prop('checked', false);
        });

        /* function to get selected fabric */
        function getGuardColorCode() {
            let curr_selection = $('body .guard-color-div input').filter(':checked');

            if (curr_selection.is(":checked")) {

                 /* uncheck all other radio buttons */
                $(".guard-color").not(curr_selection).prop("checked", false);
                 /* get the value of the radio button */
                guard_color = curr_selection.val();

                console.log(guard_color);

                 /* if guard color is white, set guard color to 00
                 else if guard color contains  "matching canopy" in lower case, set guard color to canopy_fabric */
                if (guard_color.toLowerCase().indexOf('white_') > -1) {
                    guard_color = '00';
                } else if (guard_color.toLowerCase().indexOf('matching') > -1) {
                    guard_color = getCanopyColorCode();
                } else {
                    guard_color = guard_color.match(/\((.*?)\)/)[1];
                }

                return guard_color;

            }
        }


        function getAscentPartNumber() {
            let part_number = 'KB';
            let ascent_length_int;
            let ascent_length = getFinalLengthValue();
             /* if ascent length starts with 0, remove the 0 */
            if (ascent_length.charAt(0) === '0') {
                ascent_length_int = parseInt(ascent_length.slice(1));
            } else {
                ascent_length_int = parseInt(ascent_length);
            }

            ascent_length_int = getLengthInt(ascent_length_int, '100');
             /* get selected option from .roof-flange radio buttons
            let awning_type = $('body .roof-flange input').filter(':checked').val(); */
            let guard_color = getCanopyColorCode();
            let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());

             /* if ascent length is greater than 196, set part number to KC */
            if (ascent_length_int > 196) {
                part_number = 'KC';
            }
            part_number += ascent_length_int + guard_color + case_color + '42';
            return part_number;

        }

        function getPowerSmartPartNumber() {
            /* 78278 */

            let canopy_length_int = getFinalLengthValue();
             /* get selected radio button with class .motor-location, extract only the first character */
            let motor_location = $('.motor-location').filter(':checked').val().charAt(0);

            let partNumber = 'YS0' + canopy_length_int + 'QA36' + motor_location + '-RP';

            return partNumber;

             /* g */

        }


        function getApexTwoStagePartNumber() {

            let partNumber = 'MZJ';


            let selected_fabric = getCanopyColorCode();

            let length = getFinalLengthValue();

            return partNumber + length + selected_fabric + 'JV' + 'XX' + 'H';


             /* Apex 2-Stage Replacement Canopy = MZJXXYYZZAA(H) The Apex 2-Stage was cut the same as the Apex so we support this canopy using MZA, where XX is awning length, YY is fabric color code, ZZ is case color (doesn’t matter for CO) We default the case color to “25” for consistency and to minimize the number of part numbers to set up, AA is extension, and H is present for horizontal seams (2018 and newer) or not present for vertical seems (pre-2018) */
        }

        function getMirageReplacementPartNumber() {

             /* • Mirage Canopy Replacement = MWPXXYYZZAABB(H), where XX is awning length, YY is fabric color code, ZZ is case color (doesn’t matter for CO) We default the case color to “25” for consistency and to minimize the number of part numbers to set up., AA is extension, BB is Direct Response status, and H is present for horizontal seams (2018 and newer) or not present for vertical seems (pre-2018) */

            let partNumber = 'MWP';
            let length = getFinalLengthValue();
            let selected_fabric = getCanopyColorCode();

             /* get selected radio button with class .panel-direction */
            let panel_direction = $('.panel-direction').filter(':checked').val();
             /* if panel direction contains horizontal lower case, add H to part number */
            partNumber += length + selected_fabric + '2510DR';
            if (panel_direction.toLowerCase().indexOf('horizontal') > -1) {
                partNumber += 'H';
            }
            return partNumber;

        }


        function getMirage2StageReplacementPartNumber() {

             /* • Mirage 2-Stage Canopy Replacement = MDLXXYYZZAA(H), where XX is awning length, YY is fabric color code, ZZ is case color (doesn’t matter for CO) We default the case color to “25” for consistency and to minimize the number of part numbers to set up., AA is extension, and H is present for horizontal seams (2018 and newer) or not present for vertical seems (pre-2018) */


            let partNumber = 'MDL';
            let length = getFinalLengthValue();
            let selected_fabric = getCanopyColorCode();

             /* get selected radio button with class .panel-direction */
            let panel_direction = $('.panel-direction').filter(':checked').val();
             /* if panel direction contains horizontal lower case, add H to part number */
            partNumber += length + selected_fabric + '2510';
            if (panel_direction.toLowerCase().indexOf('horizontal') > -1) {
                partNumber += 'H';
            }
            return partNumber;

        }

        function getParamountReplacementPartNumber() {

           /* M42QEBAJV10DR - vertical
              M42QEBAJV10DRH - horizontal */

            let partNumber = 'M42';
            let length = getFinalLengthValue();
            let selected_fabric = getCanopyColorCode();

             /* get selected radio button with class .panel-direction */
            let panel_direction = $('.panel-direction').filter(':checked').val();
             /* if panel direction contains horizontal lower case, add H to part number */
            partNumber += length + selected_fabric + 'JV' + '10' + 'DR';
            if (panel_direction.toLowerCase().indexOf('horizontal') > -1) {
                partNumber += 'H';
            }
            return partNumber;


         /* Paramount Canopy Replacement = M42XXYYZZAABB(H), where XX is awning length, YY is fabric color code, ZZ is case color (doesn’t matter for CO) We default the case color to “JV” for consistency and to minimize the number of part numbers to set up, AA is extension, BB is Direct Response status, and H is present for horizontal seams (2018 and newer) or not present for vertical seems (pre-2018) */

        }

        /* Freedom Box Awning Canopy Replacement */

        function getFreedomBoxCanopyReplacementPartNumber() {
             /* • Freedom Box Awning Canopy Replacement = MXXLLLYYZZAA, where XX is the style code of the awning, LLL is awning length, YY is fabric color code, ZZ is case color (doesn’t matter for CO) We default the case color to “25” for consistency and to minimize the number of part numbers to set up., AA is lights and motor info (doesn’t matter for CO) The only exception is the IJ style.  I had the time to develop a model to support these.  They would be ordered as XMIJ, then answer the questions. */


            let partNumber = 'M';
            let length = getFinalLengthValue();
            length = getLengthInt(length, '100');
            let selected_fabric = getCanopyColorCode();
            partNumber += length + selected_fabric + '25';
            let motor_option = $('.motor-option').filter(':checked').val();
            if (motor_option.toLowerCase().indexOf('yes') > -1) {
                partNumber += 'TM';
            }

            return partNumber;


        }


        function getCompassReplacementPartNumber() {


             /* Compass (the part numbers in the pricebook don't match what's currently online) = MGZLLLYYZZAA(AH), where LLL is awning length, YY is fabric color of valence, ZZ is fabric color at awning rail, AA is lights information (RB would add wire to hem, NL nothing added, AW adds lights and extrusions sewn to canopy, etc), AH is present when accessory harness is included */

            let partNumber = "MGZ";
            let length = getFinalLengthValue();
            let selected_fabric = getCanopyColorCode();
            let part_guard_color = getGuardColorCode();

            if (part_guard_color.toLowerCase().indexOf('white_') > -1) {
                part_guard_color = '00';
            } else if (part_guard_color.toLowerCase().indexOf('matching') > -1) {
                part_guard_color = selected_fabric;
            }

            let length_inches = length * 12;
            length = getLengthInt(length_inches, '100');
            let light_value;

             /* get selected radio button with class .light-option */
            let light_option = $('.light-option').filter(':checked').val();
            /*  if light_option contains "rail" in lower case, set light_option to 00 */
            if (light_option.toLowerCase().indexOf('rail') > -1) {
                light_value = 'AW';

                if (light_option.toLowerCase().indexOf('rgb') > -1) {
                    light_value = 'AR';
                }
            } else if (light_option.toLowerCase().indexOf('roll') > -1) {
                light_value = 'RB';

                if (light_option.toLowerCase().indexOf('rgb') > -1) {
                    light_value = 'RG';
                }
            }
             /* else if light options contains both "rail" and "roll" in lower case, set light_option to 00 */
            else if (light_option.toLowerCase().indexOf('rail') > -1 && light_option.toLowerCase().indexOf('roll') > -1) {
                light_value = 'WW';
                if (light_option.toLowerCase().indexOf('rgb') > -1) {
                    light_value = 'RR';
                }

            } else if (light_option.toLowerCase() === "None") {
                light_value = 'NL';
            }
            console.log(partNumber + length + selected_fabric + part_guard_color + light_value);
            return partNumber + length + selected_fabric + part_guard_color + light_value;

        }


        function getTruckinReplacementPartNumber() {
             /* Truckin Awn Canopy Replacement = MXXLLLYYZZ, where XX is the style code of the awning, LLL is awning length, YY is fabric color of valence, ZZ is fabric color at awning rail */

            let partNumber = 'M';
            let length = getFinalLengthValue();
            length = parseInt(length) + 6;
            length = getLengthInt(length, '100');
            let selected_fabric = getCanopyColorCode();
            let part_guard_color = getGuardColorCode();
            let extension_code;
             /* get selected radio button with class .extension-style  and extract the number only */
            let extension_style = $('.extension-style').filter(':checked').val();
        /*  if extension_style lowecase contains Standard */

            if (extension_style.toLowerCase().indexOf('standard') > -1) {
                extension_code = '38';
            } else if (extension_style.toLowerCase().indexOf('auto-rafter') > -1) {
                extension_code = 'TR';
            } else if (extension_style.toLowerCase().indexOf('oem') > -1) {
                extension_code = 'SI';
            }

            extension_style = extension_style.replace(/\D/g, '');
            extension_style = parseInt(extension_style) + 1;


            return partNumber + extension_code + length + selected_fabric + part_guard_color;
        }


        function getSlideOutCoverPartNumber() {
            let partNumber = 'LH';
            let selected_fabric = getCanopyColorCode();
            let part_guard_color;

            /* get selected radio button with class guard-option */
            let guard_option = $('.guard-option').filter(':checked').val();
             /* if guard_option contains "rail" in lower case, set guard_option to 00 */
            if (guard_option.toLowerCase().indexOf('flxguard') > -1) {
                partNumber = 'LI';

                /*  get selected radio button with class .guard-color */
                part_guard_color = $('.guard-color').filter(':checked').val();
                part_guard_color = getValueBetweenBrackets(part_guard_color);


            }
            let length = getFinalLengthValue();
            length = length - 4;
            length = Math.floor(length);
            length = getLengthInt(length, '100');
            console.log(part_guard_color);
            return partNumber + length + selected_fabric + (part_guard_color ? part_guard_color : '') + '42';


        }

        function getKoverIIIPartNumber() {
            let partNumber = 'LH';
            let selected_fabric = getCanopyColorCode();
            let part_guard_color;

           /*  get selected radio button with class guard-option */
            let guard_option = $('.deflector-type').filter(':checked').val();
             /* if guard_option contains "rail" in lower case, set guard_option to 00 */
            let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());
            if (guard_option.toLowerCase().indexOf('deflector only') > -1) {
                partNumber = 'UQ';
            } else {
                partNumber = 'UP';
            }
            let length = getFinalLengthValue();
            length = length - 4;
            length = Math.floor(length);
            length = getLengthInt(length, '100');
            console.log(part_guard_color);
            return partNumber + length + selected_fabric + case_color;


        }

        function getSLWindowAwningPartNumber() {
            let fabric_value = $('ul[data-tm-connector="fabric-options"] input').filter(':checked').val().replace(/_\d+$/, '');
            let selected_fabric = getCanopyColorCode();
            let length_value = $('.part-number-length').val();


            let guard_type = $('#chosen-guard input[type="text"]').val();

            let guard_color = getGuardColorCode();
            let spring_color = $('.spring-color').filter(':checked').val();
            let awning_style = $('.sl-awning-style').filter(':checked').val();
            let partNumber;

            // if spring_color is white
            if (spring_color.toLowerCase().indexOf('white') > -1) {

                // if awning_style contains "XL" in lower case, set partNumber to "IN"
                if (awning_style.toLowerCase().indexOf('xl') > -1) {

                    //if guard type contains uniguard or flexguard, set partNumber to "ID"
                    if (guard_type.toLowerCase().indexOf('uniguard') > -1 || guard_type.toLowerCase().indexOf('flxguard') > -1) {
                        partNumber = 'ID';
                    }
                    else{
                        partNumber = 'IN'
                    }

                }
                else{
                     if (guard_type.toLowerCase().indexOf('uniguard') > -1 || guard_type.toLowerCase().indexOf('flxguard') > -1) {
                        partNumber = 'IG';
                    }
                    else{
                        partNumber = 'IE'
                    }

                }

            }
            // if spring_color is black
            else if (spring_color.toLowerCase().indexOf('black') > -1) {
                  if (awning_style.toLowerCase().indexOf('xl') > -1) {

                    //if guard type contains uniguard or flexguard, set partNumber to "ID"
                    if (guard_type.toLowerCase().indexOf('uniguard') > -1 || guard_type.toLowerCase().indexOf('flxguard') > -1) {
                        partNumber = 'IK';
                    }
                    else{
                        partNumber = 'IP'
                    }

                }
                else{
                     if (guard_type.toLowerCase().indexOf('uniguard') > -1 || guard_type.toLowerCase().indexOf('flxguard') > -1) {
                        partNumber = 'IB';
                    }
                    else{
                        partNumber = 'IF'
                    }


                }

            }

            let finalPartNumber = partNumber + length_value + selected_fabric + guard_color;
            return finalPartNumber;


        }


        function getAlpinePartNumber() {
            let partNumber = 'HI';
            let selected_fabric = getCanopyColorCode();
            let part_guard_color = getGuardColorCode();
            let canopy_length_int = getFinalLengthValue();
            canopy_length_int = parseInt(canopy_length_int) - 4;
            canopy_length = getLengthInt(canopy_length_int, '100');
            // get radio button with class .rail-option
            let rail_option = $('.rail-option').filter(':checked').val();
            final_part_number = partNumber + canopy_length + selected_fabric + part_guard_color;

            if (rail_option.toLowerCase().indexOf('with rail') > -1) {
                final_part_number = final_part_number + 'TR';
            } else {
                final_part_number = final_part_number + 'TN';
            }
            return final_part_number;

        }

        function getOTDPartNumber(){


            let length = getFinalLengthValue();
            length = getLengthInt(length, '100');
            let spring_color = $('.spring-color').filter(':checked').val();
            let fabric_type = getFabricType();

            let guard_type = getGuardType();



            let arm_color = $('ul[data-tm-connector="arm-color-options"] input').filter(':checked').val();
            // if arm color contains white
            if(arm_color.toLowerCase().indexOf('white') > -1){
                arm_color = 'W';
            }
            else if(arm_color.toLowerCase().indexOf('black') > -1){
                arm_color = '6';
            }
            else if (arm_color.toLowerCase().indexOf('satin') > -1){
                arm_color = 'S';
            }

            let selected_fabric = getCanopyColorCode();
            let part_guard_color = getGuardColorCode();
        let partNumber;
            // if guard_type contains vinyl
            if(guard_type.toLowerCase().indexOf('vinyl') > -1 || guard_type.toLowerCase().indexOf('polyweave') > -1 || guard_type.toLowerCase().indexOf('fullview') > -1){

                // if spring_color contains white
                if(spring_color.toLowerCase().indexOf('white') > -1){
                     partNumber = "FG";

                }
                else{
                     partNumber = "68";

                }
            }
            else{
                if(spring_color.toLowerCase().indexOf('white') > -1){
                  partNumber = "FE";

                }
                else{
                    partNumber = "67";

                }

            }

            return partNumber + length + selected_fabric + part_guard_color + arm_color;




        }
        function getAltitudePartNumber() {
            let partNumber = "QZ";
            let length = getFinalLengthValue();
            let selected_fabric = getCanopyColorCode();
            let part_guard_color = getGuardColorCode();

            if (part_guard_color.toLowerCase().indexOf('white_') > -1) {
                part_guard_color = '00';
            } else if (part_guard_color.toLowerCase().indexOf('matching') > -1) {
                part_guard_color = selected_fabric;
            }

            let length_inches = length * 12;
            length = getLengthInt(length_inches, '100');
            let light_value;

            // get selected radio button with class .light-option
            let light_option = $('.light-option').filter(':checked').val();
            // if light_option contains "rail" in lower case, set light_option to 00
            if (light_option.toLowerCase().indexOf('rail') > -1) {
                light_value = 'AW';

                if (light_option.toLowerCase().indexOf('rgb') > -1) {
                    light_value = 'AR';
                }
            } else if (light_option.toLowerCase().indexOf('roll') > -1) {
                light_value = 'RB';

                if (light_option.toLowerCase().indexOf('rgb') > -1) {
                    light_value = 'RG';
                }
            }
            // else if light options contains both "rail" and "roll" in lower case, set light_option to 00
            else if (light_option.toLowerCase().indexOf('rail') > -1 && light_option.toLowerCase().indexOf('roll') > -1) {
                light_value = 'WW';
                if (light_option.toLowerCase().indexOf('rgb') > -1) {
                    light_value = 'RR';
                }

            } else if (light_option.toLowerCase().indexOf('none') > -1) {
                light_value = 'NL';
            }
            console.log(partNumber + length + selected_fabric + part_guard_color + light_value);
            return partNumber + length + selected_fabric + part_guard_color + light_value;

        }


        function getMaxiSideVisorPartNumber() {
            let canopy_length_int = getFinalLengthValue();
            // get selected radio button with class .motor-location, extract only the first character
            let cord_location = $('.motor-location').filter(':checked').val().charAt(0);

            let partNumber = '120' + canopy_length_int + 'ZA36' + cord_location + '-RP';

            return partNumber;
        }

        function getMaxiSmartVisorPartNumber() {
            let canopy_length_int = getFinalLengthValue();
            // get selected radio button with class .motor-location, extract only the first character
            let partNumber = 'JD0' + canopy_length_int + 'MA36-RP';

            return partNumber;
        }

        function getPowerSideVisorPartNumber() {
            let canopy_length_int = getFinalLengthValue();
            // get selected radio button with class .motor-location, extract only the first character
            let motor_location = $('.motor-location').filter(':checked').val().charAt(0);

            let partNumber = 'YR0' + canopy_length_int + 'ZD34' + motor_location + '-RP';

            return partNumber;
        }


        function getSoftAscentSideVisorPartNumber() {
            let canopy_length_int = getFinalLengthValue();
            // get selected radio button with class .motor-location, extract only the first character

            let partNumber = 'ZC0' + canopy_length_int + 'ZD36-RP';

            return partNumber;
        }

        function getFreedomWallPartNumber() {


            let lightbar = $('.lightbar').filter(':checked');


            // get radio option thats selected with class .spring-color
            let selected_fabric = getCanopyColorCode();
            // get selected radio option with class .radio-lengths
            let radio_length = $('.radio-lengths').filter(':checked').val();

            let roof_length = radio_length.match(/\d*\.?\d*/)[0];
            roof_length = radio_length;

            $('.length-final-number').val(roof_length + "");
            radio_length = getValueBetweenBrackets(radio_length);


            let final_feet = getLengthInt(radio_length, '100');


            let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());
            var suffix = '';


            // get selected radio button with class .motor-option
            let motor_option = $('.motor-option').filter(':checked').val();

            // if spring_color lower case contains "white", set partNumber to 00
            if (motor_option.toLowerCase().indexOf('motorized') > -1) {
                suffix = 'TM';

                if (lightbar.is(':checked')) {
                    suffix = 'LM';
                }

            } else {

                if (lightbar.is(':checked')) {
                    suffix = 'LL';

                }

            }


            let partNumber = "35" + final_feet + selected_fabric + case_color + suffix;

            return partNumber;


        }


        function getFreedomRoofPartNumber() {
            let lightbar = $('.lightbar').filter(':checked');


            // get radio option thats selected with class .spring-color
            let selected_fabric = getCanopyColorCode();
            // get selected radio option with class .radio-lengths
            let radio_length = $('.radio-lengths').filter(':checked').val();
            let roof_length = radio_length.match(/\d*\.?\d*/)[0];


            roof_length = parseFloat(roof_length);
            roof_length = parseFloat(roof_length.toFixed(2));


            radio_length = getValueBetweenBrackets(radio_length);
            $('.length-final-number').val(radio_length + "");

            let final_feet = getLengthInt(radio_length, '100');

            let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());
            let suffix = '';


            // get selected radio button with class .motor-option
            let motor_option = $('.motor-option').filter(':checked').val();

            // if spring_color lower case contains "white", set partNumber to 00
            if (motor_option.toLowerCase().indexOf('motorized') > -1) {
                suffix = 'TM';

                if (lightbar.is(':checked')) {
                    suffix = 'LM';
                }

            } else {
                if (lightbar.is(':checked')) {
                    suffix = 'LL';
                }
            }

            let partNumber = "BY" + final_feet + selected_fabric + case_color + suffix;
            return partNumber;


        }


        function getFiestaPartNumber() {
            let lightbar = $('.lightbar').filter(':checked');
            let guard_value = $('ul[data-tm-connector="guard-type"] input').filter(":checked").val().replace(/_\d+$/, '');
            let fabric_value = $('ul[data-tm-connector="fabric-options"] input').filter(':checked').val().replace(/_\d+$/, '');



            // get radio option thats selected with class .spring-color
            let spring_color = $('.spring-color').filter(':checked').val();
            let canopy_length_int = getFinalLengthValue();
            let part_guard_color = getGuardColorCode();
            let selected_fabric = getCanopyColorCode();
            let partNumber;

            // if spring_color lower case contains "white", set spring_color to 00
            if (spring_color.toLowerCase().indexOf('white') > -1) {

                if (guard_value.indexOf('Weatherguard') > -1) {

                    if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                        partNumber = 'EA';

                        if (canopy_length_int > 21) {
                            partNumber = 'ER';
                        }

                        // if .led-light-bar is checked, change 1st 2 digits of part number to CW
                        if (lightbar.is(':checked')) {
                            partNumber = 'KR';
                        }
                    }
                } else if (guard_value === 'FLXguard') {
                    // if fabric_value contains 'Vinyl", change 1st 2 digits of part number to CW
                    if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                        partNumber = 'SE';

                        if (canopy_length_int > 21) {
                            partNumber = 'SF';
                        }
                        // if .led-light-bar is checked, change 1st 2 digits of part number to CW
                        if (lightbar.is(':checked')) {
                            partNumber = 'HC';
                        }
                    } else if (fabric_value.indexOf('Acrylic') > -1) {
                        partNumber = 'EB';
                        if (canopy_length_int > 21) {
                            partNumber = 'EU';
                        }

                        if (lightbar.is(':checked')) {
                            partNumber = 'HC';
                        }

                    }

                } else if (guard_value === 'Alumaguard') {
                    if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                        partNumber = 'SE';

                        // if .led-light-bar is checked, change 1st 2 digits of part number to HC
                        if (lightbar.is(':checked')) {
                            partNumber = 'HC';
                        }
                    } else if (fabric_value.indexOf('Acrylic') > -1) {
                        partNumber = 'EB';

                        if (lightbar.is(':checked')) {
                            partNumber = 'HC';
                        }

                    }
                }

            } else if (spring_color.toLowerCase().indexOf('black') > -1) {

                if (guard_value.indexOf('Weatherguard') > -1) {

                    if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                        partNumber = '86';

                        if (canopy_length_int > 21) {
                            partNumber = 'EP';
                        }

                        if (lightbar.is(':checked')) {
                            partNumber = 'KS';
                        }
                    }
                } else if (guard_value === 'FLXguard') {

                    if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                        partNumber = 'SA';

                        if (canopy_length_int > 21) {
                            partNumber = 'SB';
                        }
                        // if .led-light-bar is checked, change 1st 2 digits of part number to CW
                        if (lightbar.is(':checked')) {
                            partNumber = 'HD';
                        }
                    } else if (fabric_value.indexOf('Acrylic') > -1) {
                        partNumber = 'AB';
                        if (canopy_length_int > 21) {
                            partNumber = 'ET';
                        }

                        if (lightbar.is(':checked')) {
                            partNumber = 'HD';
                        }

                    }

                } else if (guard_value == 'Alumaguard') {
                    if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                        partNumber = 'SE';

                        if (lightbar.is(':checked')) {
                            partNumber = 'HC';
                        }
                    } else if (fabric_value.indexOf('Acrylic') > -1) {
                        partNumber = 'AB';

                        if (lightbar.is(':checked')) {
                            partNumber = 'HD';
                        }

                    }
                }

            }

            return partNumber + canopy_length_int + selected_fabric + part_guard_color;


        }

        function getMarqueePartNumber() {

            let part_number = '43';
            let ascent_length_int;
            let canopy_length_int = getFinalLengthValue();
            let lightbar = $('.lightbar').filter(':checked');
            let canopy_length = getLengthInt(canopy_length_int, '100');
            console.log("canopy length: " + canopy_length);

            let pitch_setting;
            // if ascent length starts with 0, remove the 0


            // if radio button with parent class .marquee-type contains "Steep", set part number to 430
            if ($('.marquee-type').filter(':checked').val().toLowerCase().indexOf('steep') > -1) {
                pitch_setting = 'WP';
            } else {
                pitch_setting = 'DP';
            }

            let guard_color = getCanopyColorCode();
            let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());
            let fabric_type = getFabricType();

            if ($('.selected-fabric').length) {

                $('.selected-fabric').val("Marquee" + fabric_type);
            }


            part_number += canopy_length + guard_color + case_color + pitch_setting + (lightbar.is(':checked') ? 'LL' : '');
            return part_number;
        }

        function getSLWindowLengthPartNumber() {


        }

        function getFinalLengthValue() {

            return $('.length-final-number').val();
        }

        function getFinalLengthFloatValue() {
            console.log($('.length-final-number-float').val());
            return parseFloat($('.length-final-number-float').val());

        }


        function getLengthInt(len, tens) {

            let int_length;

            if (len) {
                int_length = len;
            } else {
                // get only the number from the length value
                int_length = parseInt($('.length-final-number').val().match(/\d+/)[0]);
            }


            let final_feet = parseInt(int_length);

            // if tens is empty
            if (tens === '') {
                if (final_feet < 10) {
                    final_feet = '0' + final_feet;
                }
            } else if (tens === '100') {
                if (final_feet < 100) {
                    final_feet = '0' + final_feet;
                }
            }

            console.log("final feet: " + final_feet);


            return final_feet;
        }


        function getCanopyColorCode() {

            //get the canopy color code between the parentheses
            let selected_fabric = $('body .fabric-color-ul .tc-mode-images .fabric-color').filter(':checked');
            selected_fabric = selected_fabric.val().match(/\((.*?)\)/)[1];
            return selected_fabric;

        }


        function getValueBetweenSquareBrackets(val) {
            return val.match(/\[(.*?)\]/)[1];
        }

        function getValueBetweenBrackets(val) {
            // if val contains left and right brackets
            if (val.indexOf('[') > -1 && val.indexOf(']') > -1) {
                return val.match(/\[(.*?)\]/)[1];
            } else if (val.indexOf('(') > -1 && val.indexOf(')') > -1) {
                return val.match(/\((.*?)\)/)[1];
            } else {
                return val;
            }
        }


        function getTruckinAwnPartNumber() {

            let truckinAwnPartNumber;
            let truckinAwn_length = getFinalLengthValue();
            let canopy_color = getCanopyColorCode();
            let guard_type = getGuardType();
            let weatherguard_color = getGuardColorCode()
            // get selected radio button value with class .awning-style

            let awning_style = $('.awning-style').filter(':checked').val();

            // if awning style contains "standard" in lowercase, set part number to TT
            if (awning_style.toLowerCase().indexOf('standard') > -1) {
                truckinAwnPartNumber = '380';
            } else if (awning_style.toLowerCase().indexOf('auto') > -1) {
                truckinAwnPartNumber = 'TR0';
            }

            // if lightbar is checked, append LL to the end of the part number
            return truckinAwnPartNumber + truckinAwn_length + canopy_color + weatherguard_color + "W";
        }


        function getBuenaVerticalPartNumber() {

            let canopy_length = $('.length-feet').val().replace('_0', '');

            // let x equal the value before  x in the string, keep only the number
            let x = canopy_length.match(/\d+/)[0];


            return '21' + x + '00A';

        }

        function getAwningExtendrPartNumber() {

            let canopy_length = getFinalLengthValue();

            return 'UU' + canopy_length + '08';

        }

        function getBuenaBagPartNumber() {

            let canopy_length = $('.length-feet').val().replace('_0', '');

            // let x equal the value before the letter M in the string, keep only the number and decimal but remove the decimal
            let x = canopy_length.match(/\d+\.\d+/)[0].replace('.', '');
            console.log(x);
            return '22' + x + '00A';

        }


        function getZipBlockerPartNumber() {

            let canopy_length = $('.length-feet-xy').val().replace('_0', '');

            // let x equal the value before  x in the string, keep only the number
            let x = canopy_length.match(/\d+/)[0];

            let y = canopy_length.split('x')[1].match(/\d+/)[0];


            $('.length-final-number').val("" + x + "." + y);

            y = getLengthInt(y, '');
            return '70' + x + y;

        }


        function getDuraMatPartNumber() {


            let canopy_length = $('.length-feet').val().replace('_0', '');
            let fabric_color = getCanopyColorCode();

            // get only last digit of fabric color if it is a 2 digit number
            if (fabric_color.length > 1) {
                fabric_color = fabric_color.substr(fabric_color.length - 1);
            }

            // let x equal the value before  x in the string, keep only the number
            let x = canopy_length.match(/\d+/)[0];

            let y = canopy_length.split('x')[1].match(/\d+/)[0];

            y = getLengthInt(y, '');


            return '1' + x + y + '7' + fabric_color;


        }


        function getRadioLength() {
        // get selected radio button with class .radio-length
            let selected_length = $('body .radio-length').filter(':checked').val();
            selected_length = getValueBetweenSquareBrackets(selected_length);
            // get only the number from the string
            selected_length = parseInt(selected_length.match(/\d+/)[0]);
            return selected_length;
        }


        function getSideBlockerPartNumber() {
            let part_number = '8800XX02';

            let fabric_color = getCanopyColorCode();

            // replace XX with fabric color
            return part_number.replace('XX', fabric_color);

        }


        function getSunBlockerPartNumber() {
            let part_number = '8800XX02';

            let fabric_color = getCanopyColorCode();

            let length_final = getFinalLengthValue();

            return '82' + length_final + fabric_color + '02';
        }

        function getVacationrPartNumber() {


            // get data-tm-tooltip-html from select with class .length-feet
            let part_number = $('.length-feet').find(':selected').data('tm-tooltip-html');

            return part_number;


        }


        function getCampoutPartNumber() {

            let campout_length = getRadioLength();
            // if campout_length is less than 101, make it 101
            if (campout_length < 101) {
                campout_length = 101;
            }

            return '98' + campout_length + getCanopyColorCode() + '00';
        }

        function getTravelPartNumber() {

            console.log('getTravelPartNumber');
            let partNumber = 'XXXXXXXX';
            let selected_fabric = getCanopyColorCode();
            let part_guard_color = getGuardColorCode();
            partNumber = getEclipseCode() + partNumber.substring(2);
            partNumber = partNumber.substring(0, 2) + getLengthInt() + partNumber.substring(4);
            partNumber = partNumber.substring(0, 4) + selected_fabric + partNumber.substring(6);
            partNumber = partNumber.substring(0, 6) + part_guard_color + partNumber.substring(8);
            return partNumber;
        }

        function getMiragePartNumber() {

            let miragePartNumber = 'VW';
            let mirage_length = getLengthInt();
            let canopy_color = getCanopyColorCode();
            let lightbar = $('.lightbar').filter(':checked');
            let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());
            // if lightbar is checked, append LL to the end of the part number
            return miragePartNumber + mirage_length + canopy_color + case_color + "10BTR" + (lightbar.is(':checked') ? 'LL' : '');
        }


        function getFabricType() {
            return $('ul[data-tm-connector="fabric-options"] input').filter(':checked').val().replace(/_\d+$/, '');

        }


        // when data-tm-connector="guard-type" is clicked or changed, get the value of the selected radio button
        $('ul[data-tm-connector="guard-type"], ul[data-tm-connector="fabric-options"]').on('click change', function () {
            let guard_value = $('ul[data-tm-connector="guard-type"] input').filter(":checked").val().replace(/_\d+$/, '');
            // if guard_value is undefined, set it to an empty string
            guard_value = guard_value === undefined ? '' : guard_value;
            guard_value = guard_value.replace(/weatherguard/i, '').replace(/\s/g, '');

            $("#chosen-guard input").val(guard_value);
            let fabric_value = $('ul[data-tm-connector="fabric-options"] input').filter(':checked').val().replace(/_\d+$/, '');
            // if fabric_value is undefined, set it to an empty string
            fabric_value = fabric_value === undefined ? '' : fabric_value;

            // set guard_value to the text field with id #chosen-guard-type
            $('#chosen-guard-type textarea').val(fabric_value + guard_value);

        });


        function getEclipseXLCode() {
            let lightbar = $('.lightbar').filter(':checked');
            let guard_value = $('ul[data-tm-connector="guard-type"] input').filter(":checked").val().replace(/_\d+$/, '');
            let fabric_value = $('ul[data-tm-connector="fabric-options"] input').filter(':checked').val().replace(/_\d+$/, '');
            let partNumber;
            if (guard_value === 'FLXguard') {
                // if fabric_value contains 'Vinyl", change 1st 2 digits of part number to CW
                if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Acrylic') > -1) {
                    partNumber = 'WC';


                } else {
                    partNumber = 'ERR';
                }
            }


            return partNumber;
        }


        function getGuardType() {

            let guard_value = $('ul[data-tm-connector="guard-type"] input').filter(":checked").val().replace(/_\d+$/, '');

            return guard_value;
        }

        function getEclipseCode() {
            let lightbar = $('.lightbar').filter(':checked');
            let guard_value = $('ul[data-tm-connector="guard-type"] input').filter(":checked").val().replace(/_\d+$/, '');
            let fabric_value = $('ul[data-tm-connector="fabric-options"] input').filter(':checked').val().replace(/_\d+$/, '');
            let partNumber;
            if (guard_value === 'FLXguard') {
                // if fabric_value contains 'Vinyl", change 1st 2 digits of part number to CW
                if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                    partNumber = 'QK';

                    // if .led-light-bar is checked, change 1st 2 digits of part number to CW
                    if (lightbar.is(':checked')) {
                        partNumber = 'CW';
                    }
                } else if (fabric_value.indexOf('Acrylic') > -1) {
                    partNumber = 'QL';
                    if (lightbar.is(':checked')) {
                        partNumber = 'CW';
                    }
                }

            } else if (guard_value === 'Uniguard') {
                if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                    partNumber = 'OT';

                    // if .led-light-bar is checked, change 1st 2 digits of part number to CW
                    if (lightbar.is(':checked')) {
                        partNumber = 'AN';
                    }
                } else if (fabric_value.indexOf('Acrylic') > -1) {
                    partNumber = 'OU';
                    if (lightbar.is(':checked')) {
                        partNumber = 'AN';
                    }

                }

            } else if (guard_value === 'Alumaguard') {


                if (fabric_value.indexOf('Acrylic') > -1) {
                    partNumber = 'QL';

                    if (lightbar.is(':checked')) {
                        partNumber = 'CW';
                    }
                } else if (fabric_value.indexOf('Vinyl') > -1 || fabric_value.indexOf('Polyweave') > -1) {
                    partNumber = 'QK';

                    // if .led-light-bar is checked, change 1st 2 digits of part number to CW
                    if (lightbar.is(':checked')) {
                        partNumber = 'CW';
                    }
                }


            } else if (guard_value.indexOf('Weatherguard') > -1) {
                partNumber = 'QJ';
                if (lightbar.is(':checked')) {
                    partNumber = 'AL';
                }

            }
            return partNumber;
        }


        if ($('.guard-color').length && $('.spring-color').length) {
            // when .guard-color is changed, uncheck all .spring-color
            $('.single .guard-color').on('change', function () {
                $('.spring-color').prop('checked', false);
            });
        }


        function calculateLength() {
            let start = 46;
            // starting at 46, add 4 until the length is = to 382
            for (let i = 0; i < 85; i++) {
                start += 4;
                if (start === 382) {
                    break;
                }
                //output the length
                console.log(start);
            }

        }


        //function that accepts a class and a function
        function applyPartNumber(postID, partNumberFunction) {


            if ($('body').hasClass(postID)) {

                $('form.cart').submit(function (e) {

                    //$('.single .length-feet').trigger('change');

                    if ($('.feet-inches-combine').length) {

                    // get data-text of selected option
                        let length_feet = $('.length-feet-custom-div.tc-container-enabled .length-feet-custom').find(':selected').data('text');

                        let length_inches = $('.length-inches-custom-div.tc-container-enabled .length-inches-custom').find(':selected').data('text');
                        console.log(length_feet);
                        console.log(length_inches);
                        let feet_inches = length_feet + ' - ' + length_inches;
                        $('.feet-inches-combine').val(feet_inches);


                        // if postID is postid-78227
                        if (postID === 'postid-78227') {


                            // if length_feet and length_inches are not empty
                            if (length_feet && length_inches) {
                                console.log('length_feet and length_inches are not empty');
                                let part_letter = ['A', 'B', 'C', 'D', 'E', '5', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];

                                length_inches = parseInt(length_inches.replace(/\D/g, ''));

                                length_feet = parseInt(length_feet.replace(/\D/g, ''));
                                let length_inches_letter = part_letter[parseInt(length_inches) - 1];

                                if (length_feet < 10) {


                                    $('.part-number-length').val('0' + length_feet + length_inches_letter);

                                } else {

                                    $('.part-number-length').val(length_feet + length_inches_letter);

                                }
                            } else {
                                                                                        
                                if (length_final_number < 10) {

                                    // if length is float with 0.5
                                    if (length_final_number % 1 === 0.5) {
                                        length_final_number = parseInt(length_final_number.toString().replace('.', ''));
                                        $('.part-number-length').val('0' + length_final_number);
                                    } else {
                                        $('.part-number-length').val('0' + length_final_number + '0');
                                    }

                                } else {

                                    $('.part-number-length').val(length_final_number + '0');

                                }
                            }
                        }

                    }
                               let partNumber = partNumberFunction();
                    $('.product-part-number').val(partNumber);
                });
            }
        }
        applyPartNumber('postid-78220', getOTDPartNumber);
        applyPartNumber('postid-84748', getAltitudePartNumber);
        applyPartNumber('postid-78172', getAltitudePartNumber);
        applyPartNumber('postid-78225', getSlideOutCoverPartNumber);
        applyPartNumber('postid-78275', getApexTwoStagePartNumber);
        applyPartNumber('postid-78288', getParamountReplacementPartNumber);
        applyPartNumber('postid-78191', getCompassReplacementPartNumber);
        applyPartNumber('postid-78252', getTruckinReplacementPartNumber);
        applyPartNumber('postid-78272', getMirageReplacementPartNumber);
        applyPartNumber('postid-78264', getMirage2StageReplacementPartNumber);
        applyPartNumber('postid-78273', getFreedomBoxCanopyReplacementPartNumber);
        applyPartNumber('postid-78223', getKoverIIIPartNumber);
        applyPartNumber('postid-78150', getAlpinePartNumber);

        applyPartNumber('postid-78148', getFreedomRoofPartNumber);
        applyPartNumber('postid-78289', getFreedomWallPartNumber);
        applyPartNumber('postid-78258', getDuraMatPartNumber);

        applyPartNumber('postid-78291', getPowerSideVisorPartNumber);
        applyPartNumber('postid-78147', getMaxiSmartVisorPartNumber);
        applyPartNumber('postid-78243', getTruckinAwnPartNumber);

        applyPartNumber('postid-78249', getSoftAscentSideVisorPartNumber);

        applyPartNumber('postid-78005', getAwningExtendrPartNumber);

        applyPartNumber('postid-78003', getBuenaBagPartNumber);

        applyPartNumber('postid-78003', getBuenaBagPartNumber);


        applyPartNumber('postid-78001', getBuenaVerticalPartNumber);


        applyPartNumber('postid-77999', getZipBlockerPartNumber);

        applyPartNumber('postid-78285', getMaxiSideVisorPartNumber);
        applyPartNumber('postid-78278', getPowerSmartPartNumber);
        applyPartNumber('postid-78201', getFiestaPartNumber);
        applyPartNumber('postid-77993', getMarqueePartNumber);
        applyPartNumber('postid-77997', getVacationrPartNumber);
        applyPartNumber('postid-78142', getSunBlockerPartNumber);
        applyPartNumber('postid-78006', getSideBlockerPartNumber);
        applyPartNumber('postid-77995', getMiragePartNumber);
        applyPartNumber('postid-77881', getAscentPartNumber);
        applyPartNumber('postid-77926', getCampoutPartNumber);

        applyPartNumber('postid-77793', getTravelPartNumber);
        applyPartNumber('postid-78238', getTravelPartNumber);
        applyPartNumber('postid-78222', getTravelPartNumber);
        applyPartNumber('postid-78227', getSLWindowAwningPartNumber);


        // if ($('body').hasClass('postid-77793') || $('body').hasClass('postid-78238') || $('body').hasClass('postid-78222')) {
        //     $('form.cart').submit(function (e) {
        //         e.preventDefault();
        //         $('.length-feet').trigger('change');
        //         let partNumber = 'XXXXXXXX';
        //         let selected_fabric = getCanopyColorCode();
        //         let part_guard_color = getGuardColorCode();
        //         partNumber = getEclipseCode() + partNumber.substring(2);
        //         partNumber = partNumber.substring(0, 2) + getLengthInt() + partNumber.substring(4);
        //         partNumber = partNumber.substring(0, 4) + selected_fabric + partNumber.substring(6);
        //         partNumber = partNumber.substring(0, 6) + part_guard_color + partNumber.substring(8);

        //         $('.product-part-number').val(partNumber);
        //     });
        // }
        // get eclipse XL part number
        if ($('body').hasClass('postid-78192')) {
            $('form.cart').submit(function (e) {

                $('.length-feet').trigger('change');
                let partNumber = 'XXXXXXXX';
                let selected_fabric = getCanopyColorCode();
                let part_guard_color = getGuardColorCode();
                //   partNumber = getEclipseXLCode() + partNumber.substring(2);
                //   partNumber = partNumber.substring(0, 2) + getLengthInt() + partNumber.substring(4);
                let lightbar = $('.lightbar').filter(':checked');

                partNumber = getEclipseXLCode() + getLengthInt() + selected_fabric + part_guard_color + (lightbar.is(':checked') ? 'RB' : '');


                $('.product-part-number').val(partNumber);
            });
        }

        // if product is campout, run this function


        setTimeout(function () {
            $(".review-config-div .tc-element-inner-wrap > .tc-row > .tc-cell").append($(".single_add_to_cart_button"));
        }, 111);


        let selected_feet;
        let selected_inches;


        function checkLength() {


            $('.single .radio-lengths').change(function () {
                let radio_length = $('.radio-lengths').filter(':checked').val();
                let roof_length = radio_length.match(/\d*\.?\d*/)[0];


                roof_length = parseFloat(roof_length);
                roof_length = parseFloat(roof_length.toFixed(2));
                $('.length-final-number').val(roof_length + "");

            });


            $(".single .length_options").change(function () {
                let length_option = $(this).val();
                if (length_option.indexOf('Custom') > -1) {
                    selected_feet = parseInt($('.length-feet-custom').find('option:selected').text());
                    selected_inches = $('.length-inches-custom').find('option:selected').text();
                    console.log(selected_inches);

                    if (selected_inches.length !== 0) {

                        if (selected_inches === 'Select' || parseInt(selected_inches) === 0) {
                            selected_inches = '';
                        } else {
                            selected_feet = parseInt(selected_feet) + 1;
                        }
                    }
                } else {
                    selected_feet = parseInt($('.length-feet').find('option:selected').text());
                }

                $('.length-final-number').val(selected_feet + "");

            });

            // when select is changed
            $('.length-feet-custom, .length-inches-custom').change(function () {
                console.log('feet changed!!');
                // get the value of the selected option

                selected_feet = parseInt($('.length-feet-custom.tcenabled').find('option:selected').text());
                selected_inches = $('.length-inches-custom.tcenabled').find('option:selected').text();
                console.log(selected_inches);
                console.log(selected_feet);
                if (selected_inches.length !== 0) {

                    if (selected_inches === 'Select' || parseInt(selected_inches) === 0) {
                        selected_inches = '';
                    } else {
                        selected_feet = parseInt(selected_feet) + 1;
                    }
                }

                $('.length-final-number').val(selected_feet + "");

                // if .length-final-number-float exists
                if ($('.length-final-number-float').length) {
                    console.log('change value to ' + selected_feet + "");
                    $('.length-final-number-float').val(selected_feet + "");

                }

            });


            $('.length-ascent').change(function () {

                let selected_length = $(this).find('option:selected').text();
                selected_length = getValueBetweenBrackets(selected_length);


                selected_length = Number(selected_length.replace(/[^0-9\.]+/g, ""));
                console.log(selected_length);

                selected_length = Math.floor(selected_length) - 4;
                $('.length-final-number').val(selected_length + "");

            });

            $('.length-alpine').change(function () {

                // get the value of this selected option
                let selected_length = $(this).find('option:selected').text();
                selected_length = getValueBetweenBrackets(selected_length);


                selected_length = Number(selected_length.replace(/[^0-9\.]+/g, ""));
                console.log(selected_length);

                selected_length = Math.floor(selected_length) - 4;
                $('.length-final-number').val(selected_length + "");

            });


            $('.single .length-kover, .single .length-cover').change(function () {


                let selected_length;

                if ($(this).hasClass('tm-valid') || $(this).hasClass('tcenabled')) {

                    let selected_length = $(this).find('option:selected').text();
                    selected_length = getValueBetweenBrackets(selected_length);
                    selected_length = Number(selected_length.replace(/[^0-9\.]+/g, ""));
                    selected_length = Math.floor(selected_length);


                    $('.length-final-number').val(selected_length + "");


                }


            });


            $('.single .length-feet').change(function () {


                let selected_length;

                if ($(this).hasClass('tm-valid') || $(this).hasClass('tcenabled')) {
                    console.log('VALID');
                    selected_length = parseInt($(this).find('option:selected').text());
                    selected_length_float = parseFloat($(this).find('option:selected').text());
                    if ($(this).hasClass('length-convert')) {
                        selected_length = selected_length * 12;
                    }


                    if ($('.length-final-number-float').length) {
                        $('.length-final-number-float').val(selected_length_float + "");
                    }
                    if ($('.length-final-number').length) {
                        $('.length-final-number').val(selected_length + "");
                    }

                }


            });


        }

        checkLength();
            /* remove link from pdf so that user cannot visit pdf detail page */
            $('.w3eden .package-title a').removeAttr('href');
            $(".wpdm-all-packages-table tbody tr td .package-title").removeAttr("href");
            /* if terms and condition if not agrred by user then it will give error on checkout page */
             /* $('#place_order').on('click', function(e) { */
            $(document).on('click','#place_order', function(e) {
                var customFieldChecked = $('#terms_and_conditions').is(':checked');
                if (!customFieldChecked) {
                    e.preventDefault();
                    alert('Please acknowledge the terms and conditions.');
                }
            });

            /* this is for ajax rqst loader */
            $('#ajax-rqst-loder').hide();
            var img = $("#ajax-rqst-loder img");
            // Set the src URL
            img.attr("src", MyAjax.ajax_loder);
            /* ajax rqst loder end */
            
                    /* Store the dropdown elements */
            var distributor_list = $("#distributor_list");
            var distributor_location = $("#distributor_info");
            $("#distributor_info").hide();
            /* Event listener for the change event on the first dropdown */
            distributor_list.on("change", function () {
                var selectedValue = $(this).val();
                if(selectedValue){
                    $('#ajax-rqst-loder').show();
                    /* Make an AJAX request to fetch the options for the second dropdown */
                    $.ajax({
                    type: "POST",
                    url: MyAjax.ajaxurl,  /* WordPress AJAX URL */
                    data: {
                        action: "get_selected_distributor_id",
                        selected_value: selectedValue,
                    },
                    success: function (response) {
                        $('#ajax-rqst-loder').hide();
                        $("#distributor_info").show();
                        /* Clear the options in the second dropdown */
                        distributor_location.empty();

                        /* Add the new options based on the response */
                        $.each(response, function (index, value) {
                        distributor_location.append(
                            '<option value>Select Location</option><option value="' + value + '">' + value + "</option>"
                        );
                        });
                    },
                    error: function () {
                        $('#ajax-rqst-loder').hide();
                        console.log("Error occurred during AJAX request.");
                    },
                    });
                }else{
                    // alert("Please Select a distributor");
                    $("#distributor_info").hide();
                    $('#ajax-rqst-loder').hide();
                }
                
            });
            
                /* Event listener for the submit button click */
            $('#submit-distributor').on('click', function(e) {
                e.preventDefault();
                $('#ajax-rqst-loder').show();
                /* Validate the selected values in the dropdowns */
                var distributorListValue = distributor_list.val();
                var distributorInfoData = distributor_location.val();

                /* Perform your validation logic here */
                if (distributorListValue === '') {
                    alert('Please select your preferred distributor.');
                    $('#ajax-rqst-loder').hide();
                    return;
                }

                if (distributorInfoData === '') {
                    alert('Please select location for the distributor.');
                    $('#ajax-rqst-loder').hide();
                    return;
                }
            
                /* Make an AJAX request to fetch the options for the second dropdown */
                $.ajax({
                type: "POST",
                url: MyAjax.ajaxurl, /* WordPress AJAX URL */
                data: {
                    action: "show_selected_distributor",
                    distributorListValue: distributorListValue,
                    distributorInfoData: distributorInfoData,
                },
                success: function (response) {
                    if (response.success) {
                        $('#ajax-rqst-loder').hide();
                    var html = response.data.html;
                    $('.preferred-distributors-list').empty();
                    $('.preferred-distributors-list').append(html);
                    $('#custom_dropdown').html(response.data.distributor_chckout_dropdown_list
                        );
                    /* Disable the selected option in the dropdown */
                    distributor_list.find('option[value="' + distributorListValue + '"]').prop('disabled', true);
                        $('.distributor-list-link').text(response.data.link_text);
                    /* Reset the dropdown value */
                    distributor_list.val('');

                    /* Reset the distributor location dropdown */
                    distributor_location.val('');
                } else {
                    $('#ajax-rqst-loder').hide();
                    console.log("Error: " + response.data); /* Log the error message if needed */
                }
                },
                error: function () {
                    $('#ajax-rqst-loder').hide();
                    console.log("Error occurred during AJAX request.");
                },
                });
            });
        
            $(document).on('click','.distributor-remove-button', function(e) {
                $('#ajax-rqst-loder').show();
            var distributor_remove = document.getElementsByClassName('distributor-remove-button');
            var remove_distributor_id = $(this).data('distributor_id');
            var distributor_remove_textbox = document.getElementsByClassName('distributor-remove-trxtbox');
            /* Make an AJAX request to fetch the options for the second dropdown */
            $.ajax({
                type: "POST",
                url: MyAjax.ajaxurl,  /* WordPress AJAX URL */
                data: {
                action: "remove_preferred_distributor",
                remove_distributor_id: remove_distributor_id,
                },  
                success: function (response) {
                    $('#ajax-rqst-loder').hide();
                var field = $('[data-distributor_id="' + remove_distributor_id + '"]');
                field.remove();
                if (response.success) {
                    var distributor_name = response.data.distributor_name;
                    $('#custom_dropdown').html(response.data.distributor_chckout_dropdown_list
                        );
                    /* Append the removed option back to the distributor list dropdown */
                    $('#distributor_list').find('option[value="' + response.data.distributor_id + '"]').remove();
                    $('#distributor_list').append('<option value="' +response.data.distributor_id + '">' +response.data.distibutors_name_title + '</option>');
                    $('.distributor-list-link').text(response.data.link_text);
                    }
                },
                error: function () {
                    $('#ajax-rqst-loder').hide();
                    console.log("Error occurred during AJAX request.");
                },
            });
            });
        if (window.location.href.includes('checkout') || window.location.href.includes('my-account') ) {
            $.ajax({
                type: "POST",
                url: MyAjax.ajaxurl,  /*  WordPress AJAX URL */
                data: {
                    action: "show_selected_distributor",
                    only_show_selected_distributor:true
                },
                success: function (response) {
                    $('#ajax-rqst-loder').hide();
                    if (response.success) {
                    var html = response.data.html;
                    $('.preferred-distributors-list').empty();
                    $('.preferred-distributors-list').append(html);
                } else {     
                    console.log("Error: " + response.data);  /* Log the error message if needed */
                }
                },
                error: function () {

                    console.log("Error occurred during AJAX request.");
                },
            });
        }
            /* add required attribute on reset password field on forgot password page */
            $("#somfrp_user_info").prop("required", true);

            /* add woocommerce class on saved cart detail page */
            $(".wcssc-cart-template-default #left-area .entry-content").addClass("woocommerce");

             /* add class and data title attribute on save cart detail page*/
              // Find the last tr element in the table
            var lastTr = $('.shop_table_responsive tr:last');


            // Find the first td element in the last tr and add the class
            lastTr.find('td:first').addClass('blank-td');

            // Find the second td element in the last tr and add the data-title attribute
            lastTr.find('td:eq(1)').attr('data-title', 'Total Quantity');

            // Find the third td element in the last tr and add the data-title attribute
            lastTr.find('td:eq(2)').attr('data-title', 'Total Price');

            /* apply readonly and disabled attributes on customer number, customer name, total labor in new warranty claim form */
            $('.CN-customer-number, .CN-customer-name, .LRR-total-labor').prop('readonly', true);

            /* hide 2 fields owner name field and original owner field if user is dealer stock in new warranty claim form */
            $('.OI-dealer-stock-div').hide();
            $('.OI-original-owner').on('change', function () {
                var selectedValue = jQuery('input[name="OI-original-owner"]:checked').val();
                if (selectedValue === "No") {
                        $('.OI-owner-name-div').hide();
                        $('.OI-dealer-stock-div').show();
                    } else {
                        // If "Yes" is selected, show the radio button and the other field
                        $('.OI-dealer-stock-div').hide();
                        $('.OI-owner-name-div').show();
                    }
             });

            
            /* Call the function to calculate and update the total labor on change on new warranty claim form */
            $('.LRR-labor-rate, .LRR-labor-hours').on('change', function () {
                 /* Get the values of labor rate and labor hours */
                var laborRate = parseFloat($('.LRR-labor-rate').val()) || 0;
                var laborHours = parseFloat($('.LRR-labor-hours').val()) || 0;

                 /* Calculate the total labor by multiplying rate and hours */
                var totalLabor = laborRate * laborHours;

                 /* Update the value of the third field with the result */
                $('.LRR-total-labor').val(totalLabor.toFixed(2));
            });

            /* updateAddButtonVisibility();

            // Function to update the "+" button visibility
            function updateAddButtonVisibility() {
                $('.wpcf7-field-group .wpcf7-field-group-add').hide(); // Hide all "+" buttons
                $('.wpcf7-field-group:last-child .wpcf7-field-group-add').show(); // Show last "+" button
            }
            alert("test");
            // Attach click event to "+" buttons
            jQuery("body").on("click",".wpcf7-field-group-add", function(){
                alert('asddsadasd');
            })
            $(document).on('click', '.wpcf7-field-group-add', function() {
                // Clone the last field group and append it after the last one
                // var newFieldGroup = $(this).closest('.wpcf7-field-group').clone();
                console.log("add button working");
                // $(this).closest('.wpcf7-field-group').after(newFieldGroup);
        
                // updateAddButtonVisibility(); // Update "+" button visibility
            });
        
            // Attach click event to delete buttons (assuming they have a class of ".delete-button")
            $(document).on('click', '.wpcf7-field-group-remove ', function() {
                $(this).closest('.wpcf7-field-group').remove();
                updateAddButtonVisibility(); // Update "+" button visibility
            }); */

            $('.remove-from-fav_list').on('click', function(e) {
                e.preventDefault(); // Prevent the default behavior of the link/button
                const product_id = $(this).data('product-id');
                const row = $(this).closest('tr');
            
                // Display a confirmation dialog
                const confirmDelete = confirm('Are you sure you want to remove this product from your favorites?');
                
                if (confirmDelete) {
                    $.ajax({
                        type: 'POST',
                        url: MyAjax.ajaxurl,
                        data: {
                            action: 'remove_fav_product_from_list',
                            product_id: product_id
                        },
                        success: function(response) {
                           /*  Display a success alert
                            const successAlert = $('<div class="alert alert-success" role="alert">Product removed successfully!</div>');
                            $('.your-alert-container').append(successAlert); // Replace '.your-alert-container' with the actual container where you want to display the alert
                            setTimeout(function() {
                                successAlert.remove(); // Remove the alert after a few seconds
                            }, 5000); // Change the time as needed */
                             /* Product removed successfully, remove the row from the table and frontend */
                             row.remove();
                             alert(response.data);
                             // console.log(response);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });
         /* apply readonly and disabled attributes on email, username, phone in account detail update form */
         $('.UAI-email, .UAI-username, .UAI-phone').prop({
            readonly: true,
            disabled: true
          });

          /* Product Library class add for the design realted issue fixed */
          var $ProdLibraryTitle = jQuery('#main-content .entry-content').find('.et_builder_inner_content .et_pb_section:eq(0)').find('.et_pb_text_inner h3').html();
        if($ProdLibraryTitle === "Product Library" ){
           jQuery('#main-content').addClass('ProductWrapper');
        }

        $('#buttonEye-UAI_cnfrm_password').removeAttr('onclick');

        jQuery('#buttonEye-UAI_password').on('click', function() {
            var passwordField = jQuery('.UAI-password');
            var fieldType = passwordField.attr('type');
            
            if (fieldType === 'password') {
                passwordField.attr('type', 'text');
            } else {
                passwordField.attr('type', 'password');
            }
        });

         /* Trigger the submit button when the page loads for order inquiry page */
       /*  $(".OI-search").click();


        $('.OI-search').on('click', function(e) {
            var fromDate = jQuery('.OI-from-date').val();
            var toDate = jQuery('.OI-to-date').val();
            var filterType = jQuery('.OI-filter-type').val();
            var filterValue = jQuery('.OI-filter-value').val();
            var orderStatus = jQuery('.OI-order-status').val();
            var all_data = 0;
                $.ajax({
                    type: 'GET',
                    url: MyAjax.ajaxurl,
                    data: {
                        action: 'fetch_sale_order_details',
                        from_date: fromDate,
                        to_date: toDate,
                        filter_type: filterType,
                        filter_value: filterValue,
                        all_data: all_data,
                        order_status: orderStatus
                    },
                    success: function(response) {
                       $('.order-inquiry-rslt').html(response.data.order_inquiry_data);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            
        }); */

        
        function orderformSearch() {
            var fromDate = jQuery('.OI-from-date').val();
            var toDate = jQuery('.OI-to-date').val();
            var filterType = jQuery('.OI-filter-type').val();
            var filterValue = jQuery('.OI-filter-value').val();
            var orderStatus = jQuery('.OI-order-status').val();
            var all_data = 0;
            $('#ajax-rqst-loder').show();
            $.ajax({
                type: 'GET',
                url: MyAjax.ajaxurl,
                data: {
                    action: 'fetch_sale_order_details',
                    from_date: fromDate,
                    to_date: toDate,
                    filter_type: filterType,
                    filter_value: filterValue,
                    all_data: all_data,
                    order_status: orderStatus
                },
                success: function(response) {
                    $('#ajax-rqst-loder').hide();
                    /* if(response.data.download_link == "No")
                    {
                        $(".order-download-link").hide();
                    }else{
                        $(".order-download-link").show();
                    } */
                    $('.order-inquiry-rslt').html(response.data.order_inquiry_data);
                },
                error: function(error) {
                    $('#ajax-rqst-loder').hide();
                    $('.order-inquiry-rslt').html("Login limit reached in acumatica");
                    console.log(error);
                }
            });
        }
        
        if (window.location.href.includes('order-inquiry')) {
            /* Call the function on page load */       
            orderformSearch();
            
            /* Attach the click event handler to .OI-search on order inquiry */
            $('#order-search').on('click', function(e) {
                orderformSearch();  /* Call the function when .OI-search is clicked */
            });
        }    
        function quoteformSearch() {
            var fromDate = jQuery('.OI-from-date').val();
            var toDate = jQuery('.OI-to-date').val();
            $('#ajax-rqst-loder').show();
            $.ajax({
                type: 'GET',
                url: MyAjax.ajaxurl,
                data: {
                    action: 'fetch_quote_inquiry_details',
                    from_date: fromDate,
                    to_date: toDate,
                },
                success: function(response) {
                    // console.log(response);
                    $('#ajax-rqst-loder').hide();
                    $('.quote-inquiry-rslt').html(response.data.quote_inquiry_data);
                },
                error: function(error) {
                    $('#ajax-rqst-loder').hide();
                    $('.quote-inquiry-rslt').html("Login limit reached in acumatica");
                    console.log(error);
                }
            });
        }
        if (window.location.href.includes('quote-inquiry')) {
            /* Call the function on page load */  
            quoteformSearch()
            /* Attach the click event handler to #OI-search on quote inquiry */
            $('#quote-search').on('click', function(e) {
                quoteformSearch();  /* Call the function when .OI-search is clicked */
            });
        }
          /* Attach the click event handler to #PN-search on part number search */
          $('#PN-search').on('click', function(e) {
            var PN_search = jQuery('.PN-part-number').val();
            var PN_search_by_desc = jQuery('.PN-part-number-desc').val();
            if(PN_search== "" && PN_search_by_desc =="" ){
                alert("Please provide criteria to fetch results");
            }else{
                $('#ajax-rqst-loder').show();
                $.ajax({
                    type: 'GET',
                    url: MyAjax.ajaxurl,
                    data: {
                        action: 'fetch_part_number_details',
                        PN_search: PN_search,
                        PN_search_by_desc: PN_search_by_desc,
                    },
                    success: function(response) {
                        console.log(response);
                        $('#ajax-rqst-loder').hide();
                        $('.PN-rslt').html(response.data.PN_rslt_data);
                    },
                    error: function(error) {
                        console.log("error "+ error);
                        $('#ajax-rqst-loder').hide();
                        $('.PN-rslt').html("Login limit reached in acumatica");
                        console.log(error);
                    }
                });
                
            }
          });
          /* Attach the click event handler to .add-to-fav_list on part number search */
          $(document).on('click', '.add-to-fav_list', function() {
            var PN_product_id = $(this).data('product-id');
            // $('#ajax-rqst-loder').show();
            $.ajax({
                type: 'GET',
                url: MyAjax.ajaxurl,
                data: {
                    action: 'Pn_add_to_list',
                    PN_product_id: PN_product_id,
                },
                success: function(response) {
                    // $('#ajax-rqst-loder').hide();
                    $('.fav_ad_btn').empty();
                    $('.fav_ad_btn').html(response.data.PN_rslt_data);
                },
                error: function(error) {
                    console.log("error "+ error);
                    // $('#ajax-rqst-loder').hide();
                    $('.PN-rslt').html("Login limit reached in acumatica");
                    console.log(error);
                }
            });
        });

        jQuery(document).on('input', 'input[name=\"quantity[]\"]', function() {
            var qty = jQuery(this).val();
            var atc = jQuery(this).closest('.ordr-inq-table').find('.add_to_cart_button');
            
            if (atc.length > 0) {
                atc.attr('data-quantity', qty);
            }
        });

        /* $('input[name="address_freight"]').change(function() {
            var selectedOption = $(this).val();
            var additionalPrice = parseFloat($('input[name="address_freight"]:checked').data('price')) || 0;
    
            // Make AJAX request to update checkout total
            $.ajax({
                type: 'POST',
                url: MyAjax.ajaxurl,
                data: {
                    action: 'update_checkout_total',
                    selected_option: selectedOption,
                    additional_price: additionalPrice
                },
                success: function(response) {
                    // Update the checkout total
                    $('body').trigger('update_checkout');
                }
            });
        }); */

        /* this function is use for USA dealer page */
        // function USDealerFormSearch() {
        //     $('#ajax-rqst-loder').show();
        //         $.ajax({
        //             type: 'GET',
        //             url: MyAjax.ajaxurl,
        //             data: {
        //                 action: 'US_dealer_locator',

        //                   /*   US_zipcode: US_zipcode,
        //                     US_city: US_city,
        //                     US_state: US_state,
        //                     US_aprox_range: US_aprox_range,
        //                     US_authorised_store: US_authorised_store,
        //                     US_rdva_member: US_rdva_member,
        //                     US_crtified_technician: US_crtified_technician,
        //                     US_crtified_personnel: US_crtified_personnel,
        //                     US_go_ring: US_go_ring,
        //                     US_mobile_service: US_mobile_service, */
        //             },
        //             success: function(response) {
        //                 console.log(response);
        //                 $('#ajax-rqst-loder').hide();
        //                 /* $('.US_dealer-rslt').html(response.data.US_rslt_data); */
        //                 var resultCountText = response.data.rslt_count + " dealers found in your search criteria.";
        //                 $('.US_dealer-rslt').html('<p class="us-rslt-count-text">' + resultCountText + '</p>' + response.data.US_rslt_data);
        //             },
        //             error: function(error) {
        //                 console.log("error "+ error);
        //                 $('#ajax-rqst-loder').hide();
        //                 $('.US_dealer-rslt').html("Login limit reached in acumatica");
        //                 console.log(error);
        //             }
        //         });
        // }
        // if (window.location.href.includes('dealer-search')) {
        //     /* call this function to show the results once page US Dealer page is loaded */
        //     USDealerFormSearch();
            
        //     $('#us-dealer').on('click', function(e) {
        //         var US_zipcode = jQuery('.US-zipcode').val();
        //         var US_city = jQuery('.US-city').val();
        //         var US_state = jQuery('.US-state').val();
        //         var US_aprox_range = jQuery('.US-approx-range').val();
        //         var US_authorised_store = jQuery('input[name="US_authorised_store[]"]:checked').val();
        //         var US_rdva_member = jQuery('input[name="US_rdva_member[]"]:checked').val();
        //         var US_crtified_technician = jQuery('input[name="US_rv_crtified[]"]:checked').val();
        //         var US_crtified_personnel = jQuery('input[name="US_rdva_crtified[]"]:checked').val();
        //         var US_go_ring = jQuery('input[name="US_go_ring[]"]:checked').val();
        //         var US_mobile_service = jQuery('input[name="US_mobile_service[]"]:checked').val();
        //         /* if(US_zipcode== "" && US_city =="" && US_state== "" && US_aprox_range =="" ){
        //             alert("Please provide criteria to fetch results");
        //         }else{ */
        //             $('#ajax-rqst-loder').show();
        //             $.ajax({
        //                 type: 'GET',
        //                 url: MyAjax.ajaxurl,
        //                 data: {
        //                     action: 'US_dealer_locator',
        //                     US_zipcode: US_zipcode,
        //                     US_city: US_city,
        //                     US_state: US_state,
        //                     US_aprox_range: US_aprox_range,
        //                     US_authorised_store: US_authorised_store,
        //                     US_rdva_member: US_rdva_member,
        //                     US_crtified_technician: US_crtified_technician,
        //                     US_crtified_personnel: US_crtified_personnel,
        //                     US_go_ring: US_go_ring,
        //                     US_mobile_service: US_mobile_service,
        //                 },
        //                 success: function(response) {
        //                     console.log(response);
        //                     $('#ajax-rqst-loder').hide();
        //                     /* $('.US_dealer-rslt').html(response.data.US_rslt_data); */
        //                     var resultCountText = response.data.rslt_count + " dealers found in your search criteria.";
        //                      $('.US_dealer-rslt').html('<p class="us-rslt-count-text">' + resultCountText + '</p>' + response.data.US_rslt_data);
        //                 },
        //                 error: function(error) {
        //                     console.log("error "+ error);
        //                     $('#ajax-rqst-loder').hide();
        //                     $('.US_dealer-rslt').html("Login limit reached in acumatica");
        //                     console.log(error);
        //                 }
        //             });
                    
        //         /* } */
        //     });
        // }
            
        /* if (window.location.href.includes('dealer-search-canada')) {
            $('#ajax-rqst-loder').show();
            $.ajax({
                type: 'GET',
                url: MyAjax.ajaxurl,
                data: {
                    action: 'CA_dealer_locator',
                },
                success: function(response) {
                    console.log(response);
                    $('#ajax-rqst-loder').hide();
                    $('.CA_dealer-rslt').html(response.data.CA_rslt_data);
                },
                error: function(error) {
                    console.log("error "+ error);
                    $('#ajax-rqst-loder').hide();
                    $('.CA_dealer-rslt').html("Login limit reached in acumatica");
                    console.log(error);
                }
            });
        }    
        $('#ca-dealer').on('click', function(e) {
            var CA_state = jQuery('.CA-state').val();
            if (CA_state== "" ){
                alert("Please provide criteria to fetch results");
            }else{
                $('#ajax-rqst-loder').show();
                $.ajax({
                    type: 'GET',
                    url: MyAjax.ajaxurl,
                    data: {
                        action: 'CA_dealer_locator',
                        CA_state: CA_state,
                    },
                    success: function(response) {
                        console.log(response);
                        $('#ajax-rqst-loder').hide();
                        $('.CA_dealer-rslt').html(response.data.CA_rslt_data);
                    },
                    error: function(error) {
                        console.log("error "+ error);
                        $('#ajax-rqst-loder').hide();
                        $('.CA_dealer-rslt').html("Login limit reached in acumatica");
                        console.log(error);
                    }
                });
                
            }
        }); */
        
        /* dealer search checkbox add class or remove */
        jQuery(document).on("change",'li.checkbox input[type="checkbox"]', function () {
            if (jQuery(this).is(":checked") ) {
                jQuery(this).closest('.checkbox').addClass('act');
            } 
            else  {
                jQuery(this).closest('.checkbox').removeClass('act');
            };
        });

        $('#configure-now').on('click', function(e) {
            var session_id = jQuery('.configure-now-btn').data("session_id");
            var href = $("#configure-now").attr("href");
            event.preventDefault();
                $(this).parent().find(".ajax-rqst-loder").show();
                $.ajax({
                    type: 'POST',
                    url: MyAjax.ajaxurl,
                    data: {
                        action: 'uniqueSessionSave',
                        session_id: session_id,
                    },
                    success: function(response) {
                        $("#configure-now").attr("href", href);
                        $(this).parent().find(".ajax-rqst-loder").hide();
                        if(response.data.message == "Success!"){
                            window.location.href = href;
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
                
            
        });
    
     
    });
    function printDiv(divName) {
        var printContents = jQuery("#"+divName).html();
        var printWindow = window.open('', '', 'width=600,height=600');
        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print</title></head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
        
   }

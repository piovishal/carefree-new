TODO:   

jQuery(document).ready(function ($) {

//=UNIQUE(E2:E885)
    //=COUNTIF(E2:E885, F2)

    // when .fabric-color radio is clicked


    let canopy_fabric;
    let guard_color;
    let guard_value;

    // if one radio in the div .deselect-other-radios is selected, unselect the others
    $('.deselect-other-radios input[type="radio"]').on('change', function () {
        $('.deselect-other-radios input[type="radio"]').not(this).prop('checked', false);
        $('.alpine-conditional-check').prop('checked', true);

    });


// when one guard color radio is selected, the other guard color radio is deselected
    $('.guard-color-div input').click(function () {
        $('.guard-color-div input').not(this).prop('checked', false);
    });


    //function to get selected fabric
    function getGuardColorCode() {
        let curr_selection = $('body .guard-color-div input').filter(':checked');

        if (curr_selection.is(":checked")) {

            // uncheck all other radio buttons
            $(".guard-color").not(curr_selection).prop("checked", false);
            // get the value of the radio button
            guard_color = curr_selection.val();

            console.log(guard_color);

            // if guard color is white, set guard color to 00
            // else if guard color contains  "matching canopy" in lower case, set guard color to canopy_fabric
            if (guard_color.toLowerCase().indexOf('white_') > -1) {
                guard_color = '00';
            } else if (guard_color.toLowerCase().indexOf('matching canopy') > -1) {
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
        // if ascent length starts with 0, remove the 0
        if (ascent_length.charAt(0) === '0') {
            ascent_length_int = parseInt(ascent_length.slice(1));
        } else {
            ascent_length_int = parseInt(ascent_length);
        }
        // get selected option from .roof-flange radio buttons
        //let awning_type = $('body .roof-flange input').filter(':checked').val();
        let guard_color = getCanopyColorCode();
        let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());

// if ascent length is greater than 196, set part number to KC
        if (ascent_length_int > 196) {
            part_number = 'KC';
        }
        part_number += ascent_length + guard_color + case_color + '42';
        return part_number;

    }

function getPowerSmartPartNumber(){
        //78278

        let canopy_length_int = getFinalLengthValue();
// get selected radio button with class .motor-location, extract only the first character
        let motor_location = $('.motor-location').filter(':checked').val().charAt(0);

        let partNumber = 'YS0' + canopy_length_int + 'QA36' + motor_location + '-RP';

return partNumber;

        // g

}


function getApexTwoStagePartNumber(){


    // Apex 2-Stage Replacement Canopy = MZJXXYYZZAA(H) The Apex 2-Stage was cut the same as the Apex so we support this canopy using MZA, where XX is awning length, YY is fabric color code, ZZ is case color (doesn’t matter for CO) We default the case color to “25” for consistency and to minimize the number of part numbers to set up, AA is extension, and H is present for horizontal seams (2018 and newer) or not present for vertical seems (pre-2018)
}

function getParamountReplacementPartNumber(){



// Paramount Canopy Replacement = M42XXYYZZAABB(H), where XX is awning length, YY is fabric color code, ZZ is case color (doesn’t matter for CO) We default the case color to “JV” for consistency and to minimize the number of part numbers to set up, AA is extension, BB is Direct Response status, and H is present for horizontal seams (2018 and newer) or not present for vertical seems (pre-2018)

}

function getCompassReplacementPartNumber(){

    // Compass (the part numbers in the pricebook don't match what's currently online) = MGZLLLYYZZAA(AH), where LLL is awning length, YY is fabric color of valence, ZZ is fabric color at awning rail, AA is lights information (RB would add wire to hem, NL nothing added, AW adds lights and extrusions sewn to canopy, etc), AH is present when accessory harness is included

}


function getTruckinReplacementPartNumber(){
    // Truckin Awn Canopy Replacement = MXXLLLYYZZ, where XX is the style code of the awning, LLL is awning length, YY is fabric color of valence, ZZ is fabric color at awning rail
}


function getSlideOutCoverPartNumber(){
    let partNumber = 'LH';
    let selected_fabric = getCanopyColorCode();
    let part_guard_color;

    //get selected radio button with class guard-option 
    let guard_option = $('.guard-option').filter(':checked').val();
    // if guard_option contains "rail" in lower case, set guard_option to 00
    if (guard_option.toLowerCase().indexOf('flxguard') > -1) {
        partNumber = 'LI';

        // get selected radio button with class .guard-color 
part_guard_color = $('.guard-color').filter(':checked').val();
part_guard_color = getValueBetweenBrackets(part_guard_color);


    }
    let length = getFinalLengthValue();
    length = getLengthInt(length, '100');
    console.log(part_guard_color);

    return partNumber + length + selected_fabric + (part_guard_color ? part_guard_color : '') + '42';



}

function getAltitudePartNumber(){
    let partNumber = "QZ";
    let length = getFinalLengthValue();
    let selected_fabric = getCanopyColorCode();
    let part_guard_color = getGuardColorCode();

    if (part_guard_color.toLowerCase().indexOf('white_') > -1) {
        part_guard_color = '00';
    } else if (part_guard_color.toLowerCase().indexOf('matching canopy') > -1) {
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
    }
   else if (light_option.toLowerCase().indexOf('roll') > -1) {
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

    }
    else if (light_option.toLowerCase() === "None") {
        light_value = 'NL';
    }
    console.log(partNumber + length + selected_fabric + part_guard_color + light_value);
    return partNumber + length + selected_fabric + part_guard_color + light_value;

}



function getMaxiSideVisorPartNumber(){
    let canopy_length_int = getFinalLengthValue();
    // get selected radio button with class .motor-location, extract only the first character
    let cord_location = $('.motor-location').filter(':checked').val().charAt(0);

    let partNumber = '120' + canopy_length_int + 'ZA36' + cord_location + '-RP';

    return partNumber;
}

function getMaxiSmartVisorPartNumber(){
    let canopy_length_int = getFinalLengthValue();
    // get selected radio button with class .motor-location, extract only the first character
    let partNumber = 'JD0' + canopy_length_int + 'MA36-RP';

    return partNumber;
}

function getPowerSideVisorPartNumber(){
    let canopy_length_int = getFinalLengthValue();
    // get selected radio button with class .motor-location, extract only the first character
    let motor_location = $('.motor-location').filter(':checked').val().charAt(0);

    let partNumber = 'YR0' + canopy_length_int + 'ZD34' + motor_location + '-RP';

    return partNumber;
}





function getSoftAscentSideVisorPartNumber(){
    let canopy_length_int = getFinalLengthValue();
    // get selected radio button with class .motor-location, extract only the first character
    
            let partNumber = 'ZC0' + canopy_length_int + 'ZD36-RP';

    return partNumber;
}

function getFreedomWallPartNumber(){
    let lightbar = $('.lightbar').filter(':checked');


    // get radio option thats selected with class .spring-color
    let selected_fabric = getCanopyColorCode();
    // get selected radio option with class .radio-lengths
    let radio_length = $('.radio-lengths').filter(':checked').val();
    let roof_length = radio_length.match(/\d*\.?\d*/)[0];

    $('.length-final-number').val(roof_length + "");
    radio_length = getValueBetweenBrackets(radio_length);


    let final_feet = getLengthInt(radio_length, '100')
    
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
            
    }
    else{
        if (lightbar.is(':checked')) {
            suffix = 'LL';
        }
    }

    let partNumber = "35" + final_feet + selected_fabric + case_color + suffix;
    return partNumber;


}


function getFreedomRoofPartNumber(){
    let lightbar = $('.lightbar').filter(':checked');


    // get radio option thats selected with class .spring-color
    let selected_fabric = getCanopyColorCode();
    // get selected radio option with class .radio-lengths
    let radio_length = $('.radio-lengths').filter(':checked').val();
    let roof_length = radio_length.match(/\d*\.?\d*/)[0];

    $('.length-final-number').val(roof_length + "");

    radio_length = getValueBetweenBrackets(radio_length);
    
    let final_feet = getLengthInt(radio_length, '100')
    
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
            
    }
    else{
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

        return partNumber + canopy_length_int +  selected_fabric + part_guard_color;


    }

    function getMarqueePartNumber() {

        let part_number = '43';
        let ascent_length_int;
        let canopy_length_int = getFinalLengthValue();

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


        part_number += canopy_length + guard_color + case_color + pitch_setting;
        return part_number;
    }

    function getFinalLengthValue() {

        return $('.length-final-number').val();
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




    function getZipBlockerPartNumber(){

        let canopy_length = $('.length-feet-xy').val().replace('_0', '');

        // let x equal the value before  x in the string, keep only the number
        let x = canopy_length.match(/\d+/)[0];

        let y = canopy_length.split('x')[1].match(/\d+/)[0];


        $('.length-final-number').val("" + x + "." + y );

        y = getLengthInt(y, '');
        return '70' + x + y;

    }

    


    function getDuraMatPartNumber(){


            let canopy_length = $('.length-feet').val().replace('_0', '');
            let fabric_color = getCanopyColorCode();

         // get only last digit of fabric color if it is a 2 digit number
            if (fabric_color.length > 1){
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

    function getMiragePartNumber() {

        let miragePartNumber = 'VW';
        let mirage_length = getLengthInt();
        let canopy_color = getCanopyColorCode();
        let lightbar = $('.lightbar').filter(':checked');
        let case_color = getValueBetweenBrackets($('.case-color').filter(':checked').val());
        // if lightbar is checked, append LL to the end of the part number
        return miragePartNumber + mirage_length + canopy_color + case_color + "10BT" + (lightbar.is(':checked') ? 'LL' : '');
    }


    function getFabricType() {
        return $('ul[data-tm-connector="fabric-options"] input').filter(':checked').val().replace(/_\d+$/, '');

    }

   

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



    function getGuardType(){

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
                e.preventDefault();
                //$('.single .length-feet').trigger('change');
                let partNumber = partNumberFunction();
                $('.product-part-number').val(partNumber);
            });
        }
    }

    
    applyPartNumber('postid-78172', getAltitudePartNumber);
    applyPartNumber('postid-78225', getSlideOutCoverPartNumber);

    
    if ($('body').hasClass('postid-78148')) {
        $('form.cart').submit(function (e) {
            //e.preventDefault();
            //$('.single .length-feet').trigger('change');
            let partNumber = getFreedomRoofPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    if ($('body').hasClass('postid-78289')) {
        $('form.cart').submit(function (e) {

            let partNumber = getFreedomWallPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    


    if ($('body').hasClass('postid-78258')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getDuraMatPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    if ($('body').hasClass('postid-78291')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getPowerSideVisorPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    if ($('body').hasClass('postid-78147')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getMaxiSmartVisorPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    
    if ($('body').hasClass('postid-78243')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getTruckinAwnPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    



    

    if ($('body').hasClass('postid-78249')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getSoftAscentSideVisorPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

      if ($('body').hasClass('postid-78005')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getAwningExtendrPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    if ($('body').hasClass('postid-78003')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getBuenaBagPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    if ($('body').hasClass('postid-78001')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getBuenaVerticalPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }



if ($('body').hasClass('postid-77999')) {
        $('form.cart').submit(function (e) {
//            e.preventDefault();

            let partNumber = getZipBlockerPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    if ($('body').hasClass('postid-78285')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getMaxiSideVisorPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    

        if ($('body').hasClass('postid-78278')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getPowerSmartPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    if ($('body').hasClass('postid-78201')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getFiestaPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    if ($('body').hasClass('postid-77993')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.single .length-feet').trigger('change');
            let partNumber = getMarqueePartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    if ($('body').hasClass('postid-77997')) {
        $('form.cart').submit(function (e) {
            $('.length-feet').trigger('change');
            let partNumber = getVacationrPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    if ($('body').hasClass('postid-78142')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.length-feet').trigger('change');
            let partNumber = getSunBlockerPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    // if product is mirage, get part #
    if ($('body').hasClass('postid-78006')) {
        $('form.cart').submit(function (e) {
            $('.length-feet').trigger('change');
            let partNumber = getSideBlockerPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    // if product is mirage, get part #
    if ($('body').hasClass('postid-77995')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.length-feet').trigger('change');
            let partNumber = getMiragePartNumber();
            $('.product-part-number').val(partNumber);
        });
    }

    // if product is ascent, run this function

    if ($('body').hasClass('postid-77881')) {

        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.length-feet').trigger('change');
            let partNumber = getAscentPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    

    if ($('body').hasClass('postid-77793') || $('body').hasClass('postid-78238') || $('body').hasClass('postid-78222')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
            $('.length-feet').trigger('change');
            let partNumber = 'XXXXXXXX';
            let selected_fabric = getCanopyColorCode();
            let part_guard_color = getGuardColorCode();
            partNumber = getEclipseCode() + partNumber.substring(2);
            partNumber = partNumber.substring(0, 2) + getLengthInt() + partNumber.substring(4);
            partNumber = partNumber.substring(0, 4) + selected_fabric + partNumber.substring(6);
            partNumber = partNumber.substring(0, 6) + part_guard_color + partNumber.substring(8);

            $('.product-part-number').val(partNumber);
        });
    }
// get eclipse XL part number
    if ($('body').hasClass('postid-78192')) {
        $('form.cart').submit(function (e) {
            e.preventDefault();
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
    if ($('body').hasClass('postid-77926')) {
        $('form.cart').submit(function (e) {
            $('.length-feet').trigger('change');
            e.preventDefault();
            let partNumber = getCampoutPartNumber();
            $('.product-part-number').val(partNumber);
        });
    }


    setTimeout(function () {
        $(".review-config-div").append($(".single_add_to_cart_button"));
    }, 111);


    let selected_feet;
    let selected_inches;


    function checkLength() {

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

            $('.length-final-number').val(selected_feet + "");

        });


        $('.length-ascent').change(function () {

            // get the value of this selected option
            let selected_length = $(this).find('option:selected').text();
            selected_length = getValueBetweenBrackets(selected_length);
            // if selected_length is a decimal, round down
            // get only the number from the string

            console.log(selected_length);
        

            selected_length = Number(selected_length.replace(/[^0-9\.]+/g,""));
            console.log(selected_length);

            selected_length = Math.floor(selected_length) - 4;
            $('.length-final-number').val(selected_length + "");

        });

        $('.length-cover, .length-alpine').change(function () {

            // get the value of this selected option
            let selected_length = $(this).find('option:selected').text();
            selected_length = getValueBetweenBrackets(selected_length);
            // if selected_length is a decimal, round down
            // get only the number from the string

            console.log(selected_length);
        

            selected_length = Number(selected_length.replace(/[^0-9\.]+/g,""));
            console.log(selected_length);

            selected_length = Math.floor(selected_length)-4;
            $('.length-final-number').val(selected_length + "");

        });



        // $('.length-alpine').change(function () {

        //     // get the value of this selected option
        //     let selected_length = $(this).find('option:selected').text();
        //     selected_length = getValueBetweenBrackets(selected_length);
        //     selected_length = selected_length.match(/\d+/g).map(Number) - 4;

        //     let final_feet = getLengthInt(selected_length, '100');

        //     $('.length-final-number').val(final_feet + "");

        // });


        // $('.length-alpine').change(function () {

        //     // get the value of this selected option
        //     let selected_length = $(this).find('option:selected').text();
        //     selected_length = getValueBetweenBrackets(selected_length);
        //     selected_length = selected_length.match(/\d+/g).map(Number) - 4;

        //     let final_feet = getLengthInt(selected_length, '100');

        //     $('.length-final-number').val(final_feet + "");

        // });


        // trigger change event on load


        // when any element with the class length-options is changed







        
        $('.single .length-feet').change(function () {


            // get the value of the selected option
            let selected_length;
            // get the value of this selected option if a parent does not have the class .tc-hidden

            // if ($(this).parents('.length-feet-div.tc-container-disabled').length === 0) {
            //     selected_length = parseInt($(this).find('option:selected').text());
            //     $('.length-final-number').val(selected_length + "");
            // }

            // if this element has class tm-valid
            if ($(this).hasClass('tm-valid') || $(this).hasClass('tcenabled')) {
                console.log('VALID');
                selected_length = parseInt($(this).find('option:selected').text());
                $('.length-final-number').val(selected_length + "");
            }

            //if it has a parent with the class length-options
            // if ($(this).parents('.hundred-length').length) {
            //     selected_length = getLengthInt(selected_length, '100');
            // }
            //selected_feet = parseInt($('.length-feet').find('option:selected').text());


        });


    }

    checkLength();


});


//
//
// // when jquery is loaded
jQuery(document).ready(function ($) {
    // move only the first .woocommerce-pagination div before the .et_pb_image_0_tb_body div


    // get the first instance of the .woocommerce-pagination div
    var pagination = $('.woocommerce .woocommerce-pagination').first();

    // add class to the pagination div
    pagination.addClass('top-pagination');

    // move pagination inside the .et_pb_image_0_tb_body div
    pagination.appendTo('.breadcrumbs-header .et-last-child');

    // if .ns-ss-slide div exists
    if ($('.ns-ss-slide').length) {


        // get list of all list items in the .woocommerce-pagination div
        var slideList = pagination.find('.n2-ss-slide');

        // for each list item, add a class
        slideList.each(function () {
            $(this).addClass('show-front-end');
        });

        // hide all pagination list items
        slideList.hide();

        // show different list item for each day of the week
        switch (new Date().getDay()) {
            case 0:
                slideList.eq(0).show();
                break;
            case 1:
                slideList.eq(1).show();
                break;
            case 2:
                slideList.eq(2).show();
                break;
            case 3:
                slideList.eq(3).show();
                break;
            case 4:
                slideList.eq(4).show();
                break;
            case 5:
                slideList.eq(5).show();
                break;
            case 6:
                slideList.eq(6).show();
                break;
        }

        slideList.eq(5).show();

    }


});
    
    
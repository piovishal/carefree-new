<?php 
$user_detial['ID'] = 47;
$user_detial['first_name'] = 'akshay';
$user_detial['last_name'] = 'tak';
$user_detial['display_name'] = $user_detial['first_name'] . ' ' . $user_detial['last_name'];
$user_detial['meta_input'] = [
    "company_name" => 'qwer PVT LTD',
    "user_name" => 'demo-tak',
    "comment" => ' test purpose',
    "customer_number" => '12CN23',
    "address" => 'laal khoti',
    "city" => 'jodhpur',
    "state" => 'rajasthan',
    "postal" => '342001',
    "phone_number" => '9778454581',
    "website" => 'http://googe.',
];

        function validate_user_details($user_detail) {
            $errors = array();
        
            // Validate ID (Assuming ID should be an integer greater than zero)
            if (!is_int($user_detail['ID']) || $user_detail['ID'] <= 0) {
                $errors[] = 'Invalid user ID.';
            }
        
            // Validate first_name and last_name (Sanitize and check if not empty)
            $user_detail['first_name'] = sanitize_text_field($user_detail['first_name']);
            $user_detail['last_name'] = sanitize_text_field($user_detail['last_name']);
            if (empty($user_detail['first_name']) || empty($user_detail['last_name'])) {
                $errors[] = 'First name and last name are required.';
            }
        
            // Validate display_name (Sanitize and check if not empty)
            $user_detail['display_name'] = sanitize_text_field($user_detail['display_name']);
            if (empty($user_detail['display_name'])) {
                $errors[] = 'Display name is required.';
            }
        
            // Validate other fields (Use appropriate WordPress validation functions based on your requirements)
            if(!empty($user_detail['meta_input']['company_name'])){

                $user_detail['meta_input']['company_name'] = sanitize_text_field($user_detail['meta_input']['company_name']);
            }else{
                $errors[] = 'Company Name is Required.';
            }
            if(!empty($user_detail['meta_input']['user_name'])){

                $user_detail['meta_input']['user_name'] = sanitize_user($user_detail['meta_input']['user_name']);
            }else{
                $errors[] = 'User Name is required.';
            }
            
            $user_detail['meta_input']['comment'] = sanitize_textarea_field($user_detail['meta_input']['comment']);
            $user_detail['meta_input']['customer_number'] = sanitize_text_field($user_detail['meta_input']['customer_number']);
            if(!empty($user_detail['meta_input']['address'])){
                $user_detail['meta_input']['address'] = sanitize_text_field($user_detail['meta_input']['address']);
            }else{
                $errors[] = 'Address is required.';
            }
            if(!empty($user_detail['meta_input']['city'])){
                $user_detail['meta_input']['city'] = sanitize_text_field($user_detail['meta_input']['city']);
            }else{
                $errors[] = 'City is required.';
            }
            if(!empty($user_detail['meta_input']['state'])){
                $user_detail['meta_input']['state'] = sanitize_text_field($user_detail['meta_input']['state']);
            }else{
                $errors[] = 'State is required.';
            }
            if(!empty( $user_detail['meta_input']['postal'])){
                $user_detail['meta_input']['postal'] = sanitize_text_field($user_detail['meta_input']['postal']);
            }else{
                $errors[] = 'Postal Number is required.';
            }
            $user_detail['meta_input']['phone_number'] = sanitize_text_field($user_detail['meta_input']['phone_number']);
            $user_detail['meta_input']['website'] = esc_url_raw($user_detail['meta_input']['website']);
        
            // Validate phone_number
            if (!empty($user_detail['meta_input']['phone_number'])) {
                // Remove all non-numeric characters from the phone number
                $phone_number = preg_replace('/[^0-9]/', '', $user_detail['meta_input']['phone_number']);
        
                // Check if the phone number has exactly 10 digits
                if (strlen($phone_number) !== 10) {
                    $errors[] = 'Invalid phone number. Phone number should have exactly 10 digits.';
                }
            }else{
                $errors[] = ' Phone number is required.';
            }
        
            // Validate website
            if (!empty($user_detail['meta_input']['website']) && !filter_var($user_detail['meta_input']['website'], FILTER_VALIDATE_URL)) {
                $errors[] = 'Invalid website URL.';
            }
        
			// If there are any errors, return the error messages
            if (!empty($errors)) {
                return $errors;
            }
        
            // If all fields are valid, return true
            return true;
        }
        
        // Usage:
        $validation_result = validate_user_details($user_detial);
        if ($validation_result === true) {
            $userId = wp_update_user($user_detial);
        } else {
            // Validation failed, $validation_result contains the array of error messages
            foreach ($validation_result as $error_message) {
                echo '<p>' . $error_message . '</p>';
            }
            die;
        }
        



?>
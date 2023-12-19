<?php
function distributors_add_edit(){
    $args = array(
        'post_type' => 'distributors',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    $distributors= get_posts($args);
    $validationErrors = array();
    $prefered_distributor_list = get_user_meta(get_current_user_id(), 'selected_distributors', true);
    if (!empty($prefered_distributor_list)) {
        $prefered_distributor_list = json_decode($prefered_distributor_list,true);
        $selected_distributors_id= array_column($prefered_distributor_list,'distributor_id');
    }else{
        $selected_distributors_id = false;
    }
    if ( ! empty( $distributors ) && ! is_wp_error( $distributors ) ) {
        $distributor_form ='<div class="preferred-distributors-list"></div>';
        $distributor_form .='<div id="ajax-rqst-loder">
                                  <img src="" width="100" height="65">
                             </div>';
       $distributor_form .= '<form action="#" method="POST" id="distributor-add-form">
       <select name="distributor_list" id="distributor_list" required>
       <option value="">Select a Distributor</option>';
       $counter=0;
        foreach ( $distributors as $distributor ) {
            if(!empty($selected_distributors_id) && in_array($distributor->ID, $selected_distributors_id)){
            }else{
                $distributor_form .='<option value="'.$distributor->ID.'">'.$distributor->post_title.'</option>';
            }
            $counter++;
        }
        $distributor_form .= '</select>';
        $distributor_form .= ' <select name="distributor_info" id="distributor_info" required>
                                    <option value>Please Select Distributor First</option>
                                </select>';
        $distributor_form .= '&nbsp;&nbsp;<input type="submit" name="submit_distributor" id="submit-distributor" value="Add Distributor"/>
        </form> <br><br>';
        return $distributor_form;
    }
}
add_shortcode( 'distributor_list_add_edit', 'distributors_add_edit');

/* AJAX callback to fetch the options for the second dropdown */
function get_selected_distributor_id() {
    $selected_distributor = $_POST['selected_value'];
    $distributor_info = array();
    $taxonomy = 'distributor_locations';
    if(isset($selected_distributor) && !empty($selected_distributor)){
            /* Get the ACF field value. */
            $distributor_cmpny_name = get_field('company_name', $selected_distributor);
            $terms = wp_get_post_terms($selected_distributor, $taxonomy);
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    $distributor_info[$distributor_cmpny_name.'_'.$term->slug] = $distributor_cmpny_name.' ('.$term->name.')';
                }
            }
    }
     /* Return the options as a JSON response */
    wp_send_json($distributor_info);
}
add_action('wp_ajax_get_selected_distributor_id', 'get_selected_distributor_id');
add_action('wp_ajax_nopriv_get_selected_distributor_id', 'get_selected_distributor_id');


function show_selected_distributor() {
     /* Check if form is submitted */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         /* Get selected values from the form */
        if(!$_POST['only_show_selected_distributor']){
            $selectedDistributor = isset($_POST['distributorListValue']) ? intval($_POST['distributorListValue']) : 0;
            $selectedDistributorInfo = isset($_POST['distributorInfoData']) ? sanitize_text_field($_POST['distributorInfoData']) : '';
            $user = wp_get_current_user();
            $userMetaKey = 'selected_distributors';
            $userMeta = get_user_meta($user->ID, $userMetaKey, true);
             /* If the meta value is empty, initialize an empty array */
            if (empty($userMeta)) {
                $userMeta = array();
                $userMeta[] = array(
                    'distributor_id' => $selectedDistributor,
                    'distributor_info' => $selectedDistributorInfo
                );
                 /* Convert the user meta value to JSON format */
                $userMetaJSON = json_encode( $userMeta);
                /* Update the user meta value */
                update_user_meta($user->ID, $userMetaKey, $userMetaJSON);
            }else{
                $userMetaArray = (array) json_decode($userMeta);
                /* Add the selected distributor and location as a new entry in the user meta */
                $userMetaArray[] = array(
                    'distributor_id' => $selectedDistributor,
                    'distributor_info' => $selectedDistributorInfo
                );
                /* Convert the user meta value to JSON format */
                $userMetaJSON = json_encode( $userMetaArray);
    
                /* Update the user meta value */
                update_user_meta($user->ID, $userMetaKey, $userMetaJSON);
            }
        }
        $meta_key = 'selected_distributors';
         /* Get the JSON data from the user meta key. */
        $json_data = get_user_meta(get_current_user_id(), $meta_key, true);
         /* Decode the JSON data. */
        $SelectedDistributors = (array) json_decode($json_data);      
         /* Loop through the data and echo it out in a repeated text field. */
        foreach ($SelectedDistributors as $key => $SelectedDistributor) {
            $preferredDistributorList .= '<input type="text" name="" class="distributor-remove-trxtbox" value="'.$SelectedDistributor->distributor_info.  '" readonly data-distributor_id="'.$SelectedDistributor->distributor_id.'" /><button type="button" data-distributor_id="'.$SelectedDistributor->distributor_id.'" class="distributor-remove-button">Remove</button><br><br>';
            $option.="<option value='".$SelectedDistributor->distributor_id."'>".get_the_title($SelectedDistributor->distributor_id)."</option>";
        }
        if(empty($SelectedDistributors)){
            $link_text_chkout_page = "Add Preferred Distributor List";
        }else{
            $link_text_chkout_page = "Edit Preferred Distributor List";
        }
        wp_send_json_success(array('html'=>$preferredDistributorList,'distributor_chckout_dropdown_list'=>$option,'link_text'=>$link_text_chkout_page));
    }
}
add_action('wp_ajax_show_selected_distributor', 'show_selected_distributor');
add_action('wp_ajax_nopriv_show_selected_distributor', 'show_selected_distributor');

function remove_preferred_distributor() {
     /* Check if form is submitted */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
         /* Get selected values from the form */
        $remove_distributor_id = isset($_POST['remove_distributor_id']) ? intval($_POST['remove_distributor_id']) : 0;
        $meta_key = 'selected_distributors';
         /* Get the JSON data from the user meta key. */
        $json_data = get_user_meta(get_current_user_id(), $meta_key, true);
         /* Decode the JSON data. */
        $SelectedDistributors = json_decode($json_data,true);
        $SelectedDistributors =array_values($SelectedDistributors);
        if(!empty($SelectedDistributors)){
             /* Find the key of the value that you want to search for. */
            $key = array_search($remove_distributor_id, array_column($SelectedDistributors,'distributor_id'));
             /* Remove the key from the array. */
            unset($SelectedDistributors[$key]);
            if(empty($SelectedDistributors)){
                $SelectedDistributorsEnc = null;
            }
            else{
                $SelectedDistributorsEnc = json_encode($SelectedDistributors);
            }
        }
         /* Update the user meta value */
        update_user_meta(get_current_user_id(), $meta_key,   $SelectedDistributorsEnc);
        if(!empty($SelectedDistributorsEnc)){
             /* Decode the JSON data. */
            $SelectedDistributors = (array) json_decode($SelectedDistributorsEnc);
            foreach($SelectedDistributors as $SelectedDistributor){
                $option.="<option value='".$SelectedDistributor->distributor_id."'>".get_the_title($SelectedDistributor->distributor_id)."</option>";
            }
            $link_text_chkout_page = "Edit Preferred Distributor List";
        }else{
            $args = array(
                'post_type' => 'distributors',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            );
            $SelectedDistributors = get_posts($args);
            foreach($SelectedDistributors as $SelectedDistributor){
                $option.="<option value='".$SelectedDistributor->ID."'>".$SelectedDistributor->post_title."</option>";
            }
            $link_text_chkout_page = "Add Preferred Distributor List";
        }
        wp_send_json_success(array('distributor_name'=>get_post_field('post_name', $remove_distributor_id),'distributor_id'=> $remove_distributor_id,'distibutors_name_title'=>get_the_title($remove_distributor_id),'distributor_chckout_dropdown_list'=>$option,'link_text'=>$link_text_chkout_page));
    }
}
add_action('wp_ajax_remove_preferred_distributor', 'remove_preferred_distributor');
add_action('wp_ajax_nopriv_remove_preferred_distributor', 'remove_preferred_distributor');

if(is_user_logged_in()){
    add_action( 'woocommerce_after_checkout_billing_form', 'add_custom_heading' );
    function add_custom_heading( $checkout ) {
        $prefered_distributor_list = get_user_meta(get_current_user_id(), 'selected_distributors', true);
        if(!empty($prefered_distributor_list)){
            echo '<a class="popmake-85072 distributor-list-link" href="#">Edit Preferred Distributor List</a>' ;
        }else{
            echo '<a class="popmake-85072 distributor-list-link" href="#">Create Preferred Distributor List</a>' ;
        }
    }
}
<?php

    /* print data for testing */
    function prd($key)
    {
        echo '<pre>';
        print_r($key);
        echo '</pre>';
    }

    /* all ids of contact form 7 is here */
    global $contactFormIDs;
    $contactFormIDs = array(
        'CANADA_dealer_locator_form' => 86096,
        'Login_form' => 84833,
        'New_Warranty_Claim' => 85354,
        'Order_Inquiry_Form' => 85893,
        'Part_Number_form' => 85973,
        'Part_Number_Product_Inquiry' => 86021,
        'Quote_Inquiry_Form' => 85911,
        'Registration_Form' => 84835,
        'Request_for_account_detail_updating'=> 85474,
        'Search_Number_Form' => 86009,
        'Update_Account_Detail' => 85460,
        'US_dealer_locator_form' => 86025,
    );

    global $headers;
    $headers = array(   
        'Content-type: text/html',
    );

    global $accu_headers;
    $accu_headers = array(
        'Content-Type: application/json'
    );
    global $currentUserID;
    $currentUserID = get_current_user_id();

    global $currentUserID;
    $pageIDs = array(
        'dealer_search_USA' => 78381,
    );

remove_filter ('acf_the_content', 'wpautop');
remove_filter ('the_content', 'wpautop');
function clear_br($content) { 
    return str_replace("<br/>","<br clear='none'/>", $content);
    } 
    add_filter('the_content','clear_br');


add_filter( 'wc_epo_builder_element_start_args ', 'wc_epo_builder_element_start_args ', 10, 3 );
function wc_epo_builder_element_start_args ( $data, $element, $element_counter ) {
	$data['label'] = $data['label'] . '--';
	return $data;
}

	   add_theme_support( 'post-thumbnails' );
add_image_size( 'image-swatch', 72, 110, true );

add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
function wpdocs_theme_setup() {
	   add_theme_support( 'post-thumbnails' );
add_image_size( 'image-swatch', 72, 110, true );
}



add_filter( 'image_size_names_choose', 'my_custom_sizes' );

function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'image-swatch' => __( 'Color Swatch' ),
    ) );
}



function wc_epo_associated_product_name( $name, $product, $product_id ) {


//get current product id
#global $product;
#$current_product_id = $product->get_id();

$variation = wc_get_product($product_id);
$parent_product_id = $variation->get_parent_id();


if (strpos($name, 'Fiesta Arms') !== false || strpos($name, 'Fiesta HD')!== false) {
    if ( strpos( $name, 'White' ) !== false ) {
        $name = 'White';
    } elseif ( strpos( $name, 'Satin' ) !== false ) {
        $name = 'Satin';
    } elseif ( strpos( $name, 'Black' ) !== false ) {
        $name = 'Black';
    }

}






if ($product_id == '59873'){
    $name = 'Flat Roof ';
}
else if ($parent_product_id == '83362' || $parent_product_id == '84730' || $parent_product_id == '84736'){

// get variation sku
$var_sku = $variation->get_sku();
    if ( strpos( $name, 'White' ) !== false ) {
        $name = 'White';
    } elseif ( strpos( $name, 'Satin' ) !== false ) {
        $name = 'Satin';
    } elseif ( strpos( $name, 'Black' ) !== false ) 
    
    if(strpos( $name, 'Speakers' ) !== false){
        $name = 'Black w/ Speakers';
    }
    else{
        $name = 'Black';
    }



    $name =  $name . ' (' . $var_sku . ')';


    }
else if ($product_id == '59871'){
$name = 'Sprinter Van';
}
else if ($product_id == '78070'){
    $name = 'Additional key fob (Wireless remote kit required) (SR0015)';
}
// if current product is pioneer, change arm name to color only
else if ( $parent_product_id == '60389' || $parent_product_id == '78328' || $parent_product_id == '78162' || $parent_product_id == '78234' || $parent_product_id == '78229') {

    // if name contains white, satin or black, return only the color
    if ( strpos( $name, 'White' ) !== false ) {
        $name = 'White';
    } elseif ( strpos( $name, 'Satin' ) !== false ) {
        $name = 'Satin';
    } elseif ( strpos( $name, 'Black' ) !== false ) {
        $name = 'Black';
    }


}

return $name;

}
add_action( 'wc_epo_associated_product_name', 'wc_epo_associated_product_name', 10, 3 );



/*** 
function so_validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {

    $json_string = json_encode($_POST['wapf']);
	

	

	
	

$file_handle = fopen('post_data1.json', 'w');
fwrite($file_handle, $json_string);
fclose($file_handle);
	
	return $passed;

}
add_filter( 'woocommerce_add_to_cart_validation', 'so_validate_add_cart_item', 10, 5 );



add_action('woocommerce_add_to_cart', 'custom_add_to_cart');
function custom_add_to_cart() {
    global $woocommerce;

    $product_ids = $_POST;
echo "<pre>";
print_r($_POST);
	echo "</pre>";
}

add_filter( 'woocommerce_add_to_cart_fragments', 'wd_woocommerce_footer_bar_fragment', 999 , 1 );

if( !function_exists( 'wd_woocommerce_footer_bar_fragment' ) ) {
	function wd_woocommerce_footer_bar_fragment($fragments)
	{
		ob_start();
		

		$cart_total = WC()->cart->cart_contents_total;

		?>

<div class="footer-bar">
	<?= var_dump($_POST); ?>
</div>
<?php
		$fragments['.footer-bar'] = ob_get_clean();

		return $fragments;
	}
}

***/

add_action( 'wp_enqueue_scripts', 'my_enqueue_assets_new' ); 

function my_enqueue_assets_new() { 

wp_enqueue_style( 'parent-style', get_stylesheet_directory_uri().'/style.css', array(), '4.3.0', 'all'  ); 
wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri().'/css/custom.css', array()); 
wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri().'/css/font-awesome.min.css', array(), ' 4.7.0', 'all'  ); 
// wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri().'/css/bootstrap.min.css', array(), ' 4.0.0', 'all'  ); 
wp_enqueue_style( 'custom-colorado', get_stylesheet_directory_uri().'/custom.css', array(), rand(111,9999), 'all'  ); 
wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery' ),rand(111,9999),'all' );

if (is_page('order-inquiry') || is_page('quote-inquiry')) {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array(), '4.0.0', 'all');
    
    // Enqueue Bootstrap JavaScript with jQuery as a dependency
    wp_enqueue_script('bootstrap-min', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array('jquery'), rand(111, 9999), 'all');
}
/*this id is for part number search page */
if(is_page(78401)){
    wp_enqueue_style('dataTables-min-css', get_stylesheet_directory_uri() . '/css/dataTables.min.css', array(), '4.0.0', 'all');
    wp_enqueue_script('dataTables-min-js', get_stylesheet_directory_uri() . '/js/dataTables.min.js', array('jquery'), rand(111, 9999), 'all');
    
}
if(is_page(get_page_by_path( 'dealer-search' )->ID)){
    wp_enqueue_script( 'dealer-locator-US', get_stylesheet_directory_uri() . '/js/dealer-locator-US.js', array( 'jquery' ),rand(111,9999),'all' ); 
}
if(is_page(get_page_by_path( 'dealer-search-canada' )->ID)){
    wp_enqueue_script( 'dealer-locator-canada', get_stylesheet_directory_uri() . '/js/dealer-locator-canada.js', array( 'jquery' ),rand(111,9999),'all' ); 
}

 // Enqueue the Google Maps JavaScript API with your API key
//  wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyB-XRhR-FuDDrkTbY2Ze6R2TrMcxqHmj2Q&callback=initializeMap', array(), null, true);
// wp_enqueue_script( 'jquery-slim', get_stylesheet_directory_uri() . '/js/query-3.2.1.slim.min.js', array( 'jquery' ),rand(111,9999),'all' );
// wp_enqueue_script( 'popper-bootstrap-js', get_stylesheet_directory_uri() . '/js/popper.min.js', array( 'jquery' ),rand(111,9999),'all' );
// wp_enqueue_script( 'bootstrap-min', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ),rand(111,9999),'all' );

wp_enqueue_script( 'fitvids', ET_BUILDER_URI . '/feature/dynamic-assets/assets/js/jquery.fitvids.js', array( 'jquery' ), ET_CORE_VERSION, true );

// Get the OptionTree object
$optiontree = get_option('option_tree');
// Get the value of the loader_image field
$loader_image = $optiontree['loder_image'];
wp_localize_script('custom-js', 'MyAjax', array('ajaxurl' => admin_url('admin-ajax.php'),'acumatica_path' =>'acumatica/sales-order.php','ajax_loder'=>$loader_image));
} 

function enqueue_admin_js_and_css() {
    // Enqueue your JavaScript file
    wp_enqueue_script( 'custom-admin-js', get_stylesheet_directory_uri() . '/js/custom-admin.js', array( 'jquery' ),rand(111,9999),'all' );
    wp_localize_script('custom-admin-js', 'MyAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'enqueue_admin_js_and_css');

function my_added_social_icons($kkoptions) {
	global $themename, $shortname;
	
	$open_social_new_tab = array( "name" =>esc_html__( "Open Social URLs in New Tab", $themename ),
                   "id" => $shortname . "_show_in_newtab",
                   "type" => "checkbox",
                   "std" => "off",
                   "desc" =>esc_html__( "Set to ON to have social URLs open in new tab. ", $themename ) );
				   
	$replace_array_newtab = array ( $open_social_new_tab );
	
	$show_instagram_icon = array( "name" =>esc_html__( "Show Instagram Icon", $themename ),
                   "id" => $shortname . "_show_instagram_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Instagram Icon on your header or footer. ", $themename ) );
	$show_pinterest_icon = array( "name" =>esc_html__( "Show Pinterest Icon", $themename ),
                   "id" => $shortname . "_show_pinterest_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Pinterest Icon on your header or footer. ", $themename ) );
	$show_tumblr_icon = array( "name" =>esc_html__( "Show Tumblr Icon", $themename ),
                   "id" => $shortname . "_show_tumblr_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Tumblr Icon on your header or footer. ", $themename ) );
	$show_dribbble_icon = array( "name" =>esc_html__( "Show Dribbble Icon", $themename ),
                   "id" => $shortname . "_show_dribbble_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Dribbble Icon on your header or footer. ", $themename ) );
	$show_vimeo_icon = array( "name" =>esc_html__( "Show Vimeo Icon", $themename ),
                   "id" => $shortname . "_show_vimeo_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Vimeo Icon on your header or footer. ", $themename ) );
	$show_linkedin_icon = array( "name" =>esc_html__( "Show LinkedIn Icon", $themename ),
                   "id" => $shortname . "_show_linkedin_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the LinkedIn Icon on your header or footer. ", $themename ) );
	$show_myspace_icon = array( "name" =>esc_html__( "Show MySpace Icon", $themename ),
                   "id" => $shortname . "_show_myspace_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the MySpace Icon on your header or footer. ", $themename ) );
	$show_skype_icon = array( "name" =>esc_html__( "Show Skype Icon", $themename ),
                   "id" => $shortname . "_show_skype_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Skype Icon on your header or footer. ", $themename ) );
	$show_youtube_icon = array( "name" =>esc_html__( "Show Youtube Icon", $themename ),
                   "id" => $shortname . "_show_youtube_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Youtube Icon on your header or footer. ", $themename ) );
	$show_flickr_icon = array( "name" =>esc_html__( "Show Flickr Icon", $themename ),
                   "id" => $shortname . "_show_flickr_icon",
                   "type" => "checkbox2",
                   "std" => "on",
                   "desc" =>esc_html__( "Here you can choose to display the Flickr Icon on your header or footer. ", $themename ) );
				   
	$repl_array_opt = array( $show_instagram_icon,
							$show_pinterest_icon,
							$show_tumblr_icon,
							$show_dribbble_icon,
							$show_vimeo_icon,
							$show_linkedin_icon,
							$show_myspace_icon,
							$show_skype_icon,
							$show_youtube_icon,
							$show_flickr_icon,
							);
	
	$show_instagram_url =array( "name" =>esc_html__( "Instagram Profile Url", $themename ),
                   "id" => $shortname . "_instagram_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Instagram Profile. ", $themename ) );
	$show_pinterest_url =array( "name" =>esc_html__( "Pinterest Profile Url", $themename ),
                   "id" => $shortname . "_pinterest_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Pinterest Profile. ", $themename ) );
	$show_tumblr_url =array( "name" =>esc_html__( "Tumblr Profile Url", $themename ),
                   "id" => $shortname . "_tumblr_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Tumblr Profile. ", $themename ) );
	$show_dribble_url =array( "name" =>esc_html__( "Dribbble Profile Url", $themename ),
                   "id" => $shortname . "_dribble_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Dribbble Profile. ", $themename ) );
	$show_vimeo_url =array( "name" =>esc_html__( "Vimeo Profile Url", $themename ),
                   "id" => $shortname . "_vimeo_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Vimeo Profile. ", $themename ) );
	$show_linkedin_url =array( "name" =>esc_html__( "LinkedIn Profile Url", $themename ),
                   "id" => $shortname . "_linkedin_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your LinkedIn Profile. ", $themename ) );
	$show_myspace_url =array( "name" =>esc_html__( "MySpace Profile Url", $themename ),
                   "id" => $shortname . "_mysapce_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your MySpace Profile. ", $themename ) );
	$show_skype_url =array( "name" =>esc_html__( "Skype Profile Url", $themename ),
                   "id" => $shortname . "_skype_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Skype Profile. ", $themename ) );
	$show_youtube_url =array( "name" =>esc_html__( "Youtube Profile Url", $themename ),
                   "id" => $shortname . "_youtube_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Youtube Profile. ", $themename ) );
	$show_flickr_url =array( "name" =>esc_html__( "Flickr Profile Url", $themename ),
                   "id" => $shortname . "_flickr_url",
                   "std" => "#",
                   "type" => "text",
                   "validation_type" => "url",
				   "desc" =>esc_html__( "Enter the URL of your Flickr Profile. ", $themename ) );
				   
	$repl_array_url = array( $show_instagram_url,
							$show_pinterest_url,
							$show_tumblr_url,
							$show_dribble_url,
							$show_vimeo_url,
							$show_linkedin_url,
							$show_myspace_url,
							$show_skype_url,
							$show_youtube_url,
							$show_flickr_url,
							);


	$srch_key = array_column($kkoptions, 'id');
	
	$key = array_search('divi_show_facebook_icon', $srch_key);
	array_splice($kkoptions, $key + 6, 0, $replace_array_newtab);
	
	$key = array_search('divi_show_google_icon', $srch_key);
	array_splice($kkoptions, $key + 8, 0, $repl_array_opt);

	$key = array_search('divi_rss_url', $srch_key);
	array_splice($kkoptions, $key + 17, 0, $repl_array_url);
	
	//print_r($kkoptions);

	return $kkoptions;
}
add_filter('et_epanel_layout_data', 'my_added_social_icons', 99);

define( 'DDPL_DOMAIN', 'my-domain' ); // translation domain
require_once( 'vendor/divi-disable-premade-layouts/divi-disable-premade-layouts.php' );

function bhc_customize_register($wp_customize)
{
    $wp_customize->add_setting("bhc_hamburger_color",[
        'default' => et_builder_accent_color(),
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'bhc_hamburger_color', array(
        'label' => __('Hamburger Color', 'bloody-hamburger-color'),
        'section' => 'et_divi_mobile_menu',
        'settings' => 'bhc_hamburger_color',
    )));
}
add_action('customize_register', 'bhc_customize_register');
function bhc_customize_css()
{
    ?>
         <style type="text/css" id="bloody-hamburger-color">
             .mobile_menu_bar:before { color: <?php echo get_theme_mod('bhc_hamburger_color', et_builder_accent_color()); ?> !important; }
         </style>
    <?php
}
add_action('wp_head', 'bhc_customize_css');

/*
add_filter( 'wp_nav_menu_items', 'add_extra_item_to_nav_menu', 10, 2 );
function add_extra_item_to_nav_menu( $items, $args ) {
    if ($args->menu->term_id == 4) {
		$top_menu_items = wp_get_nav_menu_items(5);
		_wp_menu_item_classes_by_context($top_menu_items);
 		$top_nav_menu = '<li class="top-menu"><ul>';
		foreach ( (array) $top_menu_items as $menu_item ) {
			$top_nav_menu .= '<li class="'.implode(' ',$menu_item->classes).'"><a href="'.$menu_item->url.'">'.$menu_item->title.'</a>';
		}
		$top_nav_menu .= '</ul></li>';
        $items = $top_nav_menu . $items;
    }
    return $items;
}
*/

// // Add tags to individual post pages
// function tags_after_single_post_content($content) {
//   $posttags = get_the_tags();
//   if ($posttags) {
//     $array = [];
//     foreach($posttags as $tag) {
//       $array[] = '<a href="/tag/' . $tag->slug . '/">' . $tag->name . '</a>';
//     }
//     $content .= 'Tags: ' . implode(', ', $array) . '<br>';
//   }

//   return $content;
// }
// add_filter( 'the_content', 'tags_after_single_post_content' );




function important_considerations_callback() {

	// The new tab content
	echo '<h2>Important Considerations</h2>';

}

function manuals_tab_callback() {

	// The new tab content
	echo '<h2>Manuals</h2>';

}


function output_tab($param, $args){

	
$content = end($args); 
	if ($content){
	echo $content;
	}

	return;

}








add_filter( 'woocommerce_product_tabs', 'manuals_tab' );
function manuals_tab( $tabs ) {
// Adds the new tab
// 
  	global $post;
	
		
	$video_tab = get_post_meta( $post->ID,'product_tabs_tab_video',true);
	$considerations_tab = get_post_meta( $post->ID,'product_tabs_tab_considerations',true ); 
	$specs_tab = get_post_meta( $post->ID,'product_tabs_tab_specifications',true );  
	$manuals_tab = get_post_meta( $post->ID,'product_tabs_tab_manuals',true);
		$feat_tab = get_post_meta( $post->ID,'product_tabs_tab_features',true);
	$upgrade_tab = get_post_meta( $post->ID,'product_tabs_tab_upgrade',true);
		$details_tab = get_post_meta( $post->ID,'product_tabs_tab_product_details',true);
	
	
//  $acf_tabs  = true;
// 	$video_tab =true;
// 	$considerations_tab = true;
// 	$specs_tab = true;
// 	$manuals_tab = true;
// 		$feat_tab = true;

	
	
	if ($manuals_tab){
    $tabs['manuals'] = array(
        'title'     => __( 'Manuals', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'output_tab',
		'args' => $manuals_tab
    );
	
}
	if ($video_tab){

		 $tabs['videos'] = array(
        'title'     => __( 'Videos', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'output_tab',
		'args' => $video_tab
    );
}
if ($feat_tab){
	

	  $tabs['features'] = array(
        'title'     => __( 'Features', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'output_tab',
		  'args' => $feat_tab
    );
}
if ($specs_tab){
    $tabs['specs'] = array(
        'title'     => __( 'Specifications', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'output_tab',
		'args' => $specs_tab
    );

	}
if ($considerations_tab){

	
	  $tabs['considerations'] = array(
        'title'     => __( 'Important Considerations', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'output_tab',
		  'args' => $considerations_tab
    );
}
	
	
	if ($details_tab){

		 $tabs['details'] = array(
        'title'     => __( 'Product Details', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'output_tab',
		'args' => $details_tab
    );
}
	
	

	if ($upgrade_tab){

		 $tabs['crank_upgrade'] = array(
        'title'     => __( 'Manual Crank Upgrade', 'woocommerce' ),
        'priority'  => 50,
        'callback'  => 'output_tab',
		'args' => $upgrade_tab
    );
}
		
	



	
	return $tabs;
}


add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 10 );

add_action('woocommerce_after_shop_loop_item', 'show_view_details_btn', 5 );
function show_view_details_btn() {
    global $product;
	
	echo '<div class="sku-wrap">' . $product->get_sku() . '</div>';
	
    // Display the custom button
    echo '<div class="view-details-wrap"><a style="margin-right:5px" class="button view-details-btn" href="' . esc_attr( $product->get_permalink() ) . '">' . __('View Details') . '</a></div>';
}

add_action( 'woocommerce_after_add_to_cart_quantity', 'ts_quantity_plus_sign' );
 
function ts_quantity_plus_sign() {
   echo '<button type="button" class="plus">+</button>';
}
 
add_action( 'woocommerce_before_add_to_cart_quantity', 'ts_quantity_minus_sign' );
function ts_quantity_minus_sign() {
   echo '<button type="button" class="qty-minus minus">-</button>';
}
 
add_action( 'wp_footer', 'ts_quantity_plus_minus' );
 
function ts_quantity_plus_minus() {
   // To run this on the single product page
   if ( ! is_product() ) return;
   ?>
   <script type="text/javascript">
          
      jQuery(document).ready(function($){   
          
            $('form.cart').on( 'click', 'button.plus, button.minus', function() {
 
            // Get current quantity values
            var qty = $( this ).closest( 'form.cart' ).find( '.qty' );
            var val   = parseFloat(qty.val());
            var max = parseFloat(qty.attr( 'max' ));
            var min = parseFloat(qty.attr( 'min' ));
            var step = parseFloat(qty.attr( 'step' ));
 
            // Change the value if plus or minus
            if ( $( this ).is( '.plus' ) ) {
               if ( max && ( max <= val ) ) {
                  qty.val( max );
               } 
            else {
               qty.val( val + step );
                 }
            } 
            else {
               if ( min && ( min >= val ) ) {
                  qty.val( min );
               } 
               else if ( val > 1 ) {
                  qty.val( val - step );
               }
            }
             
         });
          
      });
          
   </script>
   <?php
}


function displayTodaysDate( $atts )
 
{
 
return date(get_option('date_format'));
 
}
 
add_shortcode( 'datetoday', 'displayTodaysDate');

/* dev team customizations*/

add_action('wpcf7_before_send_mail', 'wpcf7_user_registration', 10, 3);
function wpcf7_user_registration($contact_form, &$abort, $object){   
    global $contactFormIDs;
    global $headers;
    $error = new WP_Error();
    wp_set_current_user(apply_filters('determine_current_user', false));
	$error = false;
	if ($_POST['_wpcf7'] == $contactFormIDs['Registration_Form']) {
        if ($_POST['CF-password'] != $_POST['CF-cnfrm-password']) {
			$error = true;
			$err_msg = "Passwords do not match.";
		}else{
            $data['first_name'] = $_POST['CF-name'];
			$data['user_email'] = $_POST['CF-email'];
			$data['user_pass'] = $_POST['CF-password'];
			$data['user_nicename'] = $_POST['CF-name'];
			$data['display_name'] = $_POST['CF-name'];
			$data['user_login'] = $_POST['CF-user-name'];

			$data['meta_input'] = [
				"company_name" => $_POST['CF-company-name'],
				"comment" => $_POST['CF-comment'],
				"customer_number" => $_POST['CF-customer-number'],
				"address" => $_POST['CF-address'],
				"city" => $_POST['CF-city'],
				"state" => $_POST['CF-state'],
				"postal" => $_POST['CF-postal'],
				"phone_number" => $_POST['CF-phone-number'],
                "website" => $_POST['CF-website'],
			];
            if (username_exists($data['user_login']) || email_exists($data['user_email'])) {  
				$error = true;
				$err_msg = "User already exists";
			}else {
				$userId = wp_insert_user($data);
				if (!is_wp_error($userId)) {
					$code = md5(time());
					update_user_meta($userId, 'is_activated', 0);
					update_user_meta($userId, 'activation_code', $code);
					update_user_meta($userId, 'account_status', 'awaiting_email_confirmation');
                    $expiry_time = UM()->options()->get( 'activation_link_expiry_time' );
                    if ( ! empty( $expiry_time ) && is_numeric( $expiry_time ) ) {
                        $hash = time() + $expiry_time;
                        update_user_meta($userId, 'account_secret_hash_expiry', $hash);
                    }
                    update_user_meta($userId, 'account_secret_hash', wp_generate_password(40,false));
					$string = array('id' => $userId, 'code' => $code);
					$url = get_site_url() . '/approve-account/?act=' . base64_encode(serialize($string));
                    $emailData = getEmailTemplateBySlug('user-verification-email');
                    $emailSubject = $emailData['Email_subject'];
                    $emailBody = str_replace('{email_verification_link}', $url, $emailData['Email_content']);
					$emailBody = str_replace('{user_display_name}',$data['first_name'], $emailBody);
					wp_mail($data['user_email'], $emailSubject, $emailBody,$headers);
				} else {
					$error = true;
					$err_msg = WP_Error::get_error_message();
				}
			}

        }
       
    }
    if($_POST['_wpcf7'] == $contactFormIDs['Login_form']){
        $creds = array(
			'user_login' => $_POST['your-name'],
			'user_password' => $_POST['Password'],
		);
		$user = wp_signon($creds, false);
		if (!is_wp_error($user)) {
			if (get_user_meta($user->ID, 'account_status', true) == 'approved') {
				/*
				handled redirection with plugin
				*/
			} else {
				wp_logout();
				$error = true;
				$err_msg = " Account is not Approved by Admin Yet. ";
			}
		}else{
            $error = true;
			$err_msg = str_replace("Lost your password?", "", strip_tags($user->get_error_message()));
        }
    }
    if (isset($error) && $error == true) {
		$msgs = $contact_form->prop('messages');
		$msgs['mail_sent_ng'] = $err_msg;
		$contact_form->set_properties(array('messages' => $msgs));
		$abort = true;
		$object->set_response($err_msg);
	}
	return $contact_form;
}

add_shortcode('account_approval', 'verify_user_code');
function verify_user_code(){
    global $headers;
	ob_start();
	if (isset($_GET['act'])) {
		$data = unserialize(base64_decode($_GET['act']));
		if(!isset($data['id'])){
			?>
			<div class="msgWrapper graySection">
				<p>Activation code is expired or invalid!</p>
				<p><a class="et_pb_button" href="<?php echo home_url();?>">Go to homepage</a></p>
			</div>
			<?php
			return ob_get_clean();
		}
		$code = get_user_meta($data['id'], 'activation_code', true);
		 /* verify whether the code given is the same as ours */ 
		if ( $code === $data['code']) {
			if (get_user_meta($data['id'], 'account_status', true) == 'awaiting_email_confirmation') {
                $user_id = absint( $data['id'] );
				delete_option( "um_cache_userdata_{$user_id}" );
				/*  update the user meta */ 
                um_fetch_user( $user_id );
				UM()->user()->approve();
				um_reset_user();
                $user = get_user_by('ID', $data['id']);
				update_user_meta($data['id'], 'is_activated', 1); 
                $emailData = getEmailTemplateBySlug('verify-new-account-to-admin');
                $emailSubject = $emailData['Email_subject'];
                $emailBody = str_replace('{user name}',get_user_meta($data['id'], 'first_name', true), $emailData['Email_content']);
                $emailBody = str_replace('{user_name}',get_user_meta($data['id'], 'user_name', true), $emailBody);
                $emailBody = str_replace('{user email}',$user->user_email, $emailBody);
                $emailBody = str_replace('{user phone number}',get_user_meta($data['id'], 'phone_number', true), $emailBody);
                $emailBody = str_replace('{user company name}',get_user_meta($data['id'], 'company_name', true), $emailBody);
                $emailBody = str_replace('{user comment}',get_user_meta($data['id'], 'comment', true), $emailBody);
                $emailBody = str_replace('{user customer number}',get_user_meta($data['id'], 'customer_number', true), $emailBody);
                $emailBody = str_replace('{user address}',get_user_meta($data['id'], 'address', true), $emailBody);
                $emailBody = str_replace('{user city}',get_user_meta($data['id'], 'city', true), $emailBody);
                $emailBody = str_replace('{user state}',get_user_meta($data['id'], 'state', true), $emailBody);
                $emailBody = str_replace('{user postal}',get_user_meta($data['id'], 'postal', true), $emailBody);
                $emailBody = str_replace('{user website}',get_user_meta($data['id'], 'website', true), $emailBody);
                $admin_email = get_option('admin_email');
				wp_mail($admin_email, $emailSubject, $emailBody, $headers);
                ?>
				<div class="msgWrapper graySection">
					<p><strong>Success:</strong> Your Email address has been verified. We'll get in touch soon.</p>
				</div>
				<?php update_user_meta($data['id'], 'account_status', 'awaiting_admin_review');
			} else if (get_user_meta($data['id'], 'account_status', true) == 'awaiting_admin_review') {
					?>
					<div class="msgWrapper graySection">
						<p>Your Account is under review process.</p>
					</div>
					<?php
			}else if (get_user_meta($data['id'], 'account_status', true) == 'approved') {
                ?>
                <div class="msgWrapper graySection">
                    <p>Your Account is already approved.</p>
                </div>
                <?php
            }else{
				?>
				<div class="msgWrapper graySection">
					<p>Something went wrong. Please contact with website Administrator</p>
					<p><a class="et_pb_button" href="<?php echo home_url();?>">Go to homepage</a></p>
				</div>
				<?php
			}
		} else {
			
			?>
			<div class="msgWrapper graySection">
				<p>Activation code is expired or invalid!</p>
				<p><a class="et_pb_button" href="<?php echo home_url();?>">Go to homepage</a></p>
			</div>
			<?php
			}
	}
	return ob_get_clean();
}


/* 
* return $emailPostData (array)
* It rturn email body and email subject
*/
function getEmailTemplateBySlug($slug){
    $emailTemplate = get_page_by_path($slug, OBJECT, 'emails');
    $emailPostData = array();
    $emailPostData['Email_content'] = $emailTemplate->post_content;
    $emailPostData['Email_subject'] = $emailTemplate->post_title;
	return $emailPostData;
}
function getEmailTitleBySlug($slug){
	$emailTemplate = get_page_by_path($slug, OBJECT, 'emails');
	return $emailTemplate->post_title;
}


add_shortcode('admin_account_approval', 'account_verify_by_admin');
function account_verify_by_admin(){
    global $headers;
    $account_status = unserialize(base64_decode($_GET['act']));
      $user = get_user_by('ID', $account_status['id']);
    if($account_status['account_status'] == 'Approved'){ 
        update_user_meta($account_status['id'], 'account_status', 'approved');
        $emailData = getEmailTemplateBySlug('approved-account-by-admin');
        $emailSubject = $emailData['Email_subject'];
        $emailBody = str_replace('{user_display_name}',get_user_meta($account_status['id'], 'first_name', true), $emailData['Email_content']);
        $emailBody = str_replace('{login link}',home_url('dealer-login'), $emailBody);
        wp_mail($user->user_email, $emailSubject, $emailBody, $headers);
        $htmlContainer='<div class="msgWrapper text-success">
                            <p class="text-success"><strong>Success:</strong> Account Has been Approved Now!</p>
                            <p>Account Approval Mail sent to User</p>
                        </div>';
        return $htmlContainer;                    
    }else{ 
        update_user_meta($account_status['id'], 'account_status', 'rejected');
        $emailData = getEmailTemplateBySlug('account-rejected-by-admin');
        $emailSubject = $emailData['Email_subject'];
        $emailBody = str_replace('{user_display_name}',get_user_meta($account_status['id'], 'first_name', true), $emailData['Email_content']);
        $emailBody = str_replace('{website link}',home_url(), $emailBody);
        wp_mail($user->user_email, $emailSubject, $emailBody, $headers);
        $htmlContainer='<div class="msgWrapper">
                            <p class="text-danger"><strong>Rejected:</strong> Account Has been Rejected Now!</p>
                            <p>Account Rejected Mail sent to User</p>
                        </div>';
        return $htmlContainer;
    }
}
      
add_action( 'woocommerce_review_order_before_submit', 'custom_checkbox_field' );
function custom_checkbox_field( ) {
    echo '<div class="terms-condition"><input type="checkbox" name="terms_and_conditions" required id="terms_and_conditions" value="1" />';
    echo '<label for="terms_and_conditions">I acknowledge Carefree\'s Standard Terms And Conditions.</label></span>';
}
add_action('woocommerce_checkout_process', 'validate_terms_and_conditions_checkbox');

function validate_terms_and_conditions_checkbox() {
  if (!isset($_POST['terms_and_conditions']) || !$_POST['terms_and_conditions']) {
    wc_add_notice(__('You must agree to the terms and conditions before placing your order.', 'woocommerce'), 'error');
    wc_print_notices();
    return false;
  }
}
if(is_user_logged_in()){
    /* create custom fields in billing details on checkout page */
    function custom_override_checkout_fields( $fields ) {
        global $currentUserID;
         /* Add a new field to the billing section. */
        $args = array(
            'post_type' => 'distributors',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $distributors= get_posts($args);
        $prefered_distributor_list = get_user_meta($currentUserID, 'selected_distributors', true);
        if(empty($prefered_distributor_list)){
            if ( ! empty( $distributors ) && ! is_wp_error( $distributors ) ) {
                $options[''] = 'Please Select Distributor';
                foreach ( $distributors as $distributor ) {
                    $options[$distributor->ID] = $distributor->post_title ;
                }
            }
        }else{
            $prefered_distributor_list = (array) json_decode($prefered_distributor_list);
            foreach ( $prefered_distributor_list as $distributor ) {
                $options[$distributor->distributor_id] = get_the_title($distributor->distributor_id) ;
            }
        }
        $fields['billing']['custom_dropdown'] = array(
            'id' => 'custom_dropdown',
            'label' => 'Distributor',
            'options' => $options,
            'type' => 'select',
            'required' => false,
            'class' => 'distributor-select',
            'placeholder' => _x('', 'placeholder', 'woocommerce'),
        
            
        );
		
		return $fields;
    }
    add_filter( 'woocommerce_checkout_fields', 'custom_override_checkout_fields' );

    /* reorder the fields of billing details */
    function reorder_billing_fields( $fields ) {
         /* Define the desired order of the fields */
        $new_order = array(
            'billing_first_name',
            'billing_last_name',
            'custom_dropdown',
            'billing_company',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_postcode',
            'billing_country',
            'billing_email',
            'billing_phone',
        );
         /* Reorder the fields based on the new order */
        $reordered_fields = array();
        foreach ( $new_order as $field_name ) {
            if ( isset( $fields['billing'][ $field_name ] ) ) {
                $reordered_fields['billing'][ $field_name ] = $fields['billing'][ $field_name ];
            }
        }
        /* Return the reordered fields */
        return $reordered_fields;
    }
    /* add_filter( 'woocommerce_checkout_fields', 'reorder_billing_fields' ); */

    /*  Save custom fields' values */
    function save_custom_fields_values( $order_id ){ 
        if ( ! empty( $_POST['custom_dropdown'] ) ) {
            update_post_meta( $order_id, 'distributor', sanitize_text_field( $_POST['custom_dropdown'] ) );
        }
    }
    add_action( 'woocommerce_checkout_update_order_meta', 'save_custom_fields_values' );
}
 /* include work disrtibutor list owrk for checkout page and my account page */
 include "function-includes/distributor-list.php";
 /* include toolbar section */
 include "function-includes/toolbar.php";
 /* include email template  header and footer */
include "function-includes/email-header-and-footer.php";



/* remove edit account tab so that user cannot access it */
add_filter( 'woocommerce_account_menu_items', 'myaccount_remove_account_details' );

function myaccount_remove_account_details( $items ) {
    unset( $items['edit-account'] );
    return $items;

}

/* remove url /my-account/edit-account/ */
function redirect_account_details_to_home() {
    $account_edit_url = home_url('/my-account/edit-account/');
    $current_url = home_url( $_SERVER['REQUEST_URI'] );  /* Get the current URL */

    if ( is_user_logged_in() && $current_url === $account_edit_url ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action( 'template_redirect', 'redirect_account_details_to_home' );

function add_preferred_distributors_list_tab( $menu_links ) {
     /* Add the new tab to the menu */
    $menu_links['preferred_distributors'] = 'Preferred Distributors List';
    return $menu_links;
}
add_filter( 'woocommerce_account_menu_items', 'add_preferred_distributors_list_tab' );

function preferred_distributors_list_content() {
    global $currentUserID;
    $prefered_distributor_list = get_user_meta($currentUserID, 'selected_distributors', true);
    $prefered_distributor_list = (array) json_decode($prefered_distributor_list);
     /* Add a new field to the billing section. */
     $args = array(
        'post_type' => 'distributors',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    );
    $distributors= get_posts($args);
    ?> 
        <label for="custom_dropdown">Distributor Preferred List</label><br>
        <select id="custom_dropdown" name="custom_dropdown" class="distributor-select" required>
            <option value="" selected disabled>Select a distributor</option>
            <?php
            if(empty($prefered_distributor_list)){
                if ( ! empty( $distributors ) && ! is_wp_error( $distributors ) ) {
                    
                    foreach ( $distributors as $distributor ) { ?>
                        <option value="<?php echo $distributor->ID; ?>"><?php echo $distributor->post_title; ?></option>
                        
                        <?php }?>
                    </select><?php
               }
            }else{
                foreach ($prefered_distributor_list as $prefered_distributor){ ?>
                    <option value="<?php echo $prefered_distributor->distributor_id; ?>"><?php echo get_the_title($prefered_distributor->distributor_id); ?></option>
                    <?php
                } 
            }  
            ?>
        </select><br><br>
    <?php
    if(!empty($prefered_distributor_list)){
        echo '<a class="popmake-85072 distributor-list-link" href="#">Edit Preferred Distributor List</a>' ;    
    }else{
        echo '<a class="popmake-85072 distributor-list-link" href="#">Add Preferred Distributor List</a>' ;
    }
}
add_action( 'woocommerce_account_preferred_distributors_endpoint', 'preferred_distributors_list_content' );

 /* Add the endpoint rewrite rule */
function add_preferred_distributors_endpoint() {
    add_rewrite_endpoint( 'preferred_distributors', EP_PAGES );
}
add_action( 'init', 'add_preferred_distributors_endpoint' );

/* fetch year and use in year dropdown onnew warranty claim form  */
function years_fetch ($values, $options, $args) {
    if ( in_array('WI-purchase-year', $options) ){
         $years = [];
         $i = 0;
         for( $i = 0; $i < 15; $i++){  /* Get 15 most recent years after current year */
              $years[] = (int) date("Y") - $i;
          }
          return $years ;
    }
   return $values;
 }
add_filter('wpcf7_form_tag_data_option', 'years_fetch', 10, 3);

/* custom  validation on owner fields in new warranty claim form */
function alter_wpcf7_posted_data( $data ) {
    global $contactFormIDs;
    if($_POST['_wpcf7'] == $contactFormIDs['New_Warranty_Claim']){

        if($_POST['OI-original-owner'] == 'Yes'){
            if (empty($_POST['OI-dealer-stock'])) {
                $_POST['OI-dealer-stock'] ="N/A";
            }
        }else{
            if (empty($_POST['OI-owner-name'])) {
                $_POST['OI-owner-name'] ="N/A";
            }
        }
        return $data;
    }
    return $data;
}
add_filter("wpcf7_posted_data", "alter_wpcf7_posted_data");

add_action( 'manage_users_extra_tablenav', 'admin_order_list_top_bar_button', 20, 1 );
function admin_order_list_top_bar_button( $which ) {
    global $typenow;
	?>
	<div class="alignleft actions custom">
		<a class="button" href="<?php bloginfo('url')?>/acumatica/fetch-customer-by-id.php?manual=1"><?php
			echo __( 'Sync with acumatica', 'woocommerce' ); ?></a>
	</div>
	<?php
}	

function showMessage($message, $errormsg = false){
    if ($errormsg) {
        echo '<div id="message" class="error">';
    }
    else {
        echo '<div id="message" class="updated fade">';
    }
    echo "<p><strong>$message</strong></p></div>";
} 

function showAdminMessages()
{
	if(isset($_GET['acumaticaSyncDone'])){
		if($_GET['acumaticaSyncDone'] == 1){
			showMessage("Users are successfully synchronized with Acumatica.", false);	
		}else{
			showMessage("Something went wrong please contact website administrator.", false);	
		}	
	}
	
}
add_action('admin_notices', 'showAdminMessages');

add_action('wpcf7_before_send_mail', 'wpcf7_user_warranty_claim', 10, 3);
function wpcf7_user_warranty_claim($contact_form, &$abort, $object)
{  
    global $headers;
    global $contactFormIDs;
    if($_POST['_wpcf7'] == $contactFormIDs['New_Warranty_Claim']){
        if($_POST['OI-original-owner'] == 'Yes'){
            if (empty($_POST['OI-dealer-stock'])) {
                $_POST['OI-dealer-stock'] ="";
            }else{
                $_POST['OI-dealer-stock'] ="";
            }
        }else{
            if (empty($_POST['OI-owner-name'])) {
                $_POST['OI-owner-name'] ="";
            }else{
                $_POST['OI-owner-name'] ="";
            }
        }
        $orp_component=array();
        if($_POST['_wpcf7_groups_count']['ORP-components']>0){
            for($ORP_component_count=1;$ORP_component_count<=$_POST['_wpcf7_groups_count']['ORP-components'];$ORP_component_count++){
                $orp_component[$ORP_component_count]['ORP_carefree_part'] = $_POST['ORP-carefree-part__'.$ORP_component_count];
                $orp_component[$ORP_component_count]['ORP_customer_part'] = $_POST['ORP-customer-part__'.$ORP_component_count];
                $orp_component[$ORP_component_count]['ORP_description'] = $_POST['ORP-description__'.$ORP_component_count];
                $orp_component[$ORP_component_count]['ORP_quantity'] = $_POST['ORP-quantity__'.$ORP_component_count];
            }
        }
        $emailData = getEmailTemplateBySlug('new-warranty-claim-email');
        $emailSubject = $emailData['Email_subject'];
        $emailBody = str_replace('{CN_customer_number}',$_POST['CN-customer-number'], $emailData['Email_content']);
        $emailBody = str_replace('{CN_customer_name}',$_POST['CN-business-name'], $emailBody);
        $emailBody = str_replace('{CN_contact_name}',$_POST['CN-contact-name'], $emailBody);
        $emailBody = str_replace('{CP_contact_phone}',$_POST['CP-contact-phone'], $emailBody);
        $emailBody = str_replace('{CE_contact_email}',$_POST['CE-contact-email'], $emailBody);
        $emailBody = str_replace('{ACE_additional_contact_email}',$_POST['ACE-additional-contact-email'], $emailBody);
        $emailBody = str_replace('{OI_owner_name}',$_POST['OI-owner-name'], $emailBody);
        $emailBody = str_replace('{OI_original_owner}',$_POST['OI-original-owner'], $emailBody);
        $emailBody = str_replace('{OI_dealer_stock}',$_POST['OI-dealer-stock'], $emailBody);
        $emailBody = str_replace('{OI_address_one}',$_POST['OI-address-one'], $emailBody);
        $emailBody = str_replace('{OI_address_two}',$_POST['OI-address-two'], $emailBody);
        $emailBody = str_replace('{OI_city}',$_POST['OI-city'], $emailBody);
        $emailBody = str_replace('{OI_state}',$_POST['OI-state'], $emailBody);
        $emailBody = str_replace('{OI_zip_code}',$_POST['OI-zip-code'], $emailBody);
        $emailBody = str_replace('{OI_country}',$_POST['OI-country'], $emailBody);
        $emailBody = str_replace('{OI_phone}',$_POST['OI-phone'], $emailBody);
        $emailBody = str_replace('{OI_email}',$_POST['OI-email'], $emailBody);
        $emailBody = str_replace('{OI_coach_brand}',$_POST['OI-coach-brand'], $emailBody);
        $emailBody = str_replace('{OI_coach_model}',$_POST['OI-coach-model'], $emailBody);
        $emailBody = str_replace('{OI_coach_year}',$_POST['OI-coach-year'], $emailBody);
        $emailBody = str_replace('{OI_unit_number}',$_POST['OI-unit-number'], $emailBody);
        $emailBody = str_replace('{WI_original_order_numb}',$_POST['WI-original-order-numb'], $emailBody);
        $emailBody = str_replace('{WI_carefree_product}',$_POST['WI-carefree-product'], $emailBody);
        $emailBody = str_replace('{WI_serial_number}',$_POST['WI-serial-number'], $emailBody);
        $emailBody = str_replace('{WI_purchase_month}',$_POST['WI-purchase-month'], $emailBody);
        $emailBody = str_replace('{WI_purchase_year}',$_POST['WI-purchase-year'], $emailBody);
        $emailBody = str_replace('{WI_description}',$_POST['WI-description'], $emailBody);
        $emailBody = str_replace('{WI_repair_date}',$_POST['WI-repair-date'], $emailBody);
        $emailBody = str_replace('{WI_labor_task}',$_POST['WI-labor-task'], $emailBody);
        $emailBody = str_replace('{WI_labor_task_2}',$_POST['WI-labor-task-2'], $emailBody);
        $emailBody = str_replace('{WAR_instruction}',$_POST['WAR-instruction'], $emailBody);
        $emailBody = str_replace('{WAR_description}',$_POST['WAR-description'], $emailBody);
        $ORP_carefree_part = array();
        $ORP_customer_part = array();
        $ORP_description = array();
        $ORP_quantity = array();
        if($_POST['_wpcf7_groups_count']['ORP-components']>0){
            $repetetive_fields_vlaue = '<table border="1">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Carefree Part</th>
                        <th scope="col">Customer Part</th>
                        <th scope="col">Description</th>
                        <th scope="col">quantity</th>
                        </tr>
                    </thead>
                    <tbody>';
                    $counter =1;
            foreach($orp_component as $component){
                $repetetive_fields_vlaue .='
                        <tr>
                            <th scope="row">'.$counter.'</th>
                            <td>'.$component['ORP_carefree_part'].'</td>
                            <td>'.$component['ORP_customer_part'].'</td>
                            <td>'.$component['ORP_description'].'</td>
                            <td>'.$component['ORP_quantity'].'</td>
                        </tr>'; 
                        $counter++;
            }
            $repetetive_fields_vlaue .='</tbody></table>';
            $emailBody = str_replace('{ORP_repetetive_fields}',$repetetive_fields_vlaue, $emailBody);
        }
        $emailBody = str_replace('{ORP_certify_eligibility}',$_POST['ORP-certify-eligibility'][0], $emailBody);
        $emailBody = str_replace('{LRR_labor_hours}',$_POST['LRR-labor-hours'], $emailBody);
        $emailBody = str_replace('{LRR_labor_rate}',$_POST['LRR-labor-rate'], $emailBody);
        $emailBody = str_replace('{LRR_total_labor}',$_POST['LRR-total-labor'], $emailBody);
        $emailBody = str_replace('{LRR_additional_comment}',$_POST['LRR-additional-comment'], $emailBody);
        $images = array();
        if(!empty($_POST['UI-images'])){
            foreach($_POST['UI-images'] as $UI_image){
              $images[]=  '<div><img src="'.home_url('wp-content/uploads/wp_dndcf7_uploads/').$UI_image.'" width="300px" height="300px"></div>';
            }
            $images = implode(", ",$images);
            $emailBody = str_replace('{UI_images}',$images , $emailBody);
        }else{
            $emailBody = str_replace('{UI_images}',$_POST['UI-images'], $emailBody);
        }
          $admin_email = get_option('admin_email');
          $headers[] = 'From: Carefree of colorado "<'.$admin_email.'>"' . "\r\n";
        wp_mail($admin_email, $emailSubject, $emailBody,$headers);
    }
    return $contact_form;
}
function custom_cf7_WI_carefree_product($tag){   
    global $currentUserID;
    if ($tag['basetype'] == 'select' && $tag['name'] == 'WI-carefree-product') {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status'=>'publish'
        );
        $products = get_posts( $args );
             /* Modify the options as per your requirement */
            $values =array();
            $labels =array();
            foreach ( $products as $product ) { 
                $tag['raw_values'][]= $product->post_title;
                $values[] = $product->post_title; /*  $product->ID */
                $labels[] = $product->post_title;
            }
            $tag['values'] = $values;
            $tag['labels'] = $labels;
    }
    if ($tag['name'] == 'CP-contact-phone') { 
        $tag['values'][] = get_user_meta($currentUserID, "phone_number", true); 
    }
    if ($tag['name'] == 'ORP-certify-eligibility') { 
        $tag['values'][] = "Yes"; 
    }
    if ($tag['name'] == 'CN-business-name') { 
        $tag['values'][] = get_user_meta($currentUserID, "company_name", true); 
    }
    if ($tag['name'] == 'CN-customer-number') { 
        $tag['values'][] = get_user_meta($currentUserID, "customer_number", true); 
    }
    return $tag;
}
add_filter('wpcf7_form_tag', 'custom_cf7_WI_carefree_product', 10, 3);

/* incldue the favortie product work  */
include "function-includes/favorite-products.php";
 function only_dealer_download_pdf() {
     if (is_page('dealer-lot-surveys')) {  /* Check if the current page is "dealer-lot-surveys" */
          /* Check if the user is logged in */
         if (is_user_logged_in()) {
             /* Get the current user's roles */
             $user = wp_get_current_user();
             $roles = $user->roles;
             /* Check if the user has the "um_dealer" role */
             if (in_array('um_dealer', $roles) || in_array('administrator', $roles)) {
                 /* User has the correct role, allow access */
                 return;
                } else {
                    /* User does not have the correct role, display a message or redirect */
                    $message = "Only Dealer users can access this page.";
                    wp_die($message, "Access Denied", ['response' => 403]);
                }
            } else {
                /* redirect to login page */
                wp_redirect(home_url('dealer-login'));
                exit;
            }
        }
    }
add_action('template_redirect', 'only_dealer_download_pdf');
    
add_filter('wpcf7_form_tag', 'prefill_values_update_account', 10, 3);
 function prefill_values_update_account($tag){   
    global $currentUserID;
    if ($tag['name'] == 'UAI_phone') { 
        $tag['values'][] = get_user_meta($currentUserID, "phone_number", true); 
    }
    if ($tag['name'] == 'UAI_username') { 
        $current_user = wp_get_current_user();
        $user_login = $current_user->user_login;
        $tag['values'][] = $user_login; 
    }
    if ($tag['name'] == 'UAI_name') { 
        $current_user = wp_get_current_user();
        $display_name = $current_user->display_name;
        $tag['values'][] = $display_name; 
    }
    if ($tag['name'] == 'UAI_email') { 
       /* Get the current user object */
        $current_user = wp_get_current_user();
       /* Get the user's email */
        $email = $current_user->user_email;
        $tag['values'][] = $email; 
    }
    return $tag;
}

add_action('wpcf7_before_send_mail', 'wpcf7_update_user_account_detail', 10, 3);
function wpcf7_update_user_account_detail($contact_form, &$abort, $object){  
    wp_set_current_user(apply_filters('determine_current_user', false));
    global $currentUserID;
	$error = false;
    global $contactFormIDs;
    if($_POST['_wpcf7'] == $contactFormIDs['Update_Account_Detail'] && $currentUserID > 0){
        $data['ID'] = $currentUserID;
		$data['first_name'] = $_POST['UAI_name'];
		$data['display_name'] = $_POST['UAI_name'];
        $userId = wp_update_user($data);
		if (is_wp_error($userId)) {
            $error = true;
			$err_msg = WP_Error::get_error_message();
		}else{
            /* Update the user's password */
            wp_set_password($_POST['UAI_password'], $userId);
        }
    }
    return $contact_form;
}

/* 
* this shortcode apply in heading on account detail update 
*/
function shortcode_for_customer_no_and_company_name(){
    global $currentUserID;
   $customer_number = get_user_meta($currentUserID, 'customer_number', true);
   $company_name = get_user_meta($currentUserID, 'company_name',true);
   if($customer_number){
    $output = $customer_number.'-'.$company_name;
   }else{
    $output = $company_name;
   }
    return $output;
}
add_shortcode('customer_no_and_company_name','shortcode_for_customer_no_and_company_name');

add_filter('wpcf7_form_tag', 'prefill_values_rqst_update_account', 10, 3);
 function prefill_values_rqst_update_account($tag)
{   
    global $currentUserID;
    if ($tag['name'] == 'CF-name') { 
        $current_user = wp_get_current_user();
        $display_name = $current_user->display_name;
        $tag['values'][] = $display_name; 
    }
    if ($tag['name'] == 'CF-company-name') { 
        $tag['values'][] = get_user_meta($currentUserID, "company_name", true); 
    }
    if ($tag['name'] == 'CF-comment') { 
        $tag['values'][] = get_user_meta($currentUserID, "comment", true); 
    }
    if ($tag['name'] == 'CF-address') { 
        $tag['values'][] = get_user_meta($currentUserID, "address", true);  
    }
    if ($tag['name'] == 'CF-city') { 
        $tag['values'][] = get_user_meta($currentUserID, "city", true); 
    }
    if ($tag['name'] == 'CF-state') { 
        $tag['values'][] = get_user_meta($currentUserID, "state", true); 
    }
    if ($tag['name'] == 'CF-postal') { 
        $tag['values'][] = get_user_meta($currentUserID, "postal", true);  
    }
    if ($tag['name'] == 'CF-phone-number') { 
        $tag['values'][] = get_user_meta($currentUserID, "phone_number", true); 
    }
    if ($tag['name'] == 'CF-website') { 
        $tag['values'][] = get_user_meta($currentUserID, "website", true); 
    }

    /* Get the current date */
    $current_date = date('Y-m-d');
    /* Get the date 30 days before the current date */
    $past_30_days_date = date('Y-m-d', strtotime($current_date . ' -30 days'));

    /* for order enquiry form */
    if ($tag['name'] == 'OI_from_date') { 
        $tag['values'][] = $past_30_days_date; 
    }
    if ($tag['name'] == 'OI_to_date') { 
        $tag['values'][] =  $current_date; 
    }
   /* end order enquiry form */
     return $tag;
}

add_action('wpcf7_before_send_mail', 'wpcf7_user_request_account_detail', 10, 3);
function wpcf7_user_request_account_detail($contact_form, &$abort, $object)
{  
    global $currentUserID;
	$error = false;
    global $headers;
    global $contactFormIDs;
    if($_POST['_wpcf7'] == $contactFormIDs['Request_for_account_detail_updating'] && $currentUserID > 0){
        $current_user = wp_get_current_user();
        $current_username = $current_user->display_name;
        $current_user_cust_no = get_user_meta($currentUserID, "customer_number", true);
        $args = array(
            'post_type' => 'rqst-acc-update',
            'post_status' => 'publish',
            'post_title' =>$current_username.'-'.$current_user_cust_no,
            'post_author' => $currentUserID
        );
        $postId = wp_insert_post($args);
		if (is_wp_error($postId)) {
            echo "true";
            $error = true;
			$err_msg = WP_Error::get_error_message();
		}
        else{
            update_field('rqst_name',$_POST['CF-name'],$postId);
            update_field('rqst_company_name',$_POST['CF-company-name'],$postId);
            update_field('rqst_postal',$_POST['CF-company-name'],$postId);
            update_field('rqst_city',$_POST['CF-city'],$postId);
            update_field('rqst_state',$_POST['CF-state'],$postId);
            update_field('rqst_website',$_POST['CF-postal'],$postId);
            update_field('rqst_phone_number',$_POST['CF-phone-number'],$postId);
            update_field('rqst_comment',$_POST['CF-comment'],$postId);
            update_field('rqst_address',$_POST['CF-address'],$postId);
            update_user_meta( $currentUserID, "rqst_mail_status",0);
            $emailData = getEmailTemplateBySlug('request-the-admin-to-update-the-account-details');
            $emailSubject = $emailData['Email_subject'];
            $emailBody = str_replace('{user name}',$_POST['CF-name'], $emailData['Email_content']);
            $emailBody = str_replace('{user phone number}',$_POST['CF-phone-number'], $emailBody);
            $emailBody = str_replace('{user company name}',$_POST['CF-company-name'], $emailBody);
            $emailBody = str_replace('{user comment}',$_POST['CF-comment'], $emailBody);
            $emailBody = str_replace('{user address}',$_POST['CF-address'], $emailBody);
            $emailBody = str_replace('{user city}',$_POST['CF-city'], $emailBody);
            $emailBody = str_replace('{user state}',$_POST['CF-state'], $emailBody);
            $emailBody = str_replace('{user postal}',$_POST['CF-postal'], $emailBody);
            $emailBody = str_replace('{user website}',$_POST['CF-website'], $emailBody);

            $admin_email = get_option('admin_email');
            $subject = $emailSubject." | ".$current_username."-".$current_user_cust_no;
            
            wp_mail($admin_email, $subject, $emailBody, $headers);
        }
    }if (isset($error) && $error == true) {
		$msgs = $contact_form->prop('messages');
		$contact_form->set_properties(array('messages' => $msgs));
		$abort = true;
	}
    return $contact_form;
}

function disable_field_update_acc_rqst( $field ) { 
     /* Get the field name to exclude */
  $specific_fields=array('rqst_name','rqst_company_name','rqst_address','rqst_postal','rqst_city','rqst_state','rqst_website','rqst_phone_number','rqst_comment');
     /* If the field group is "Request update account detail" and the field name is not "email", disable the field */
    if (in_array($field['name'],$specific_fields)) {
      $field['disabled'] = true;
    }
    return $field;
  }
  add_filter('acf/load_field', 'disable_field_update_acc_rqst');

 /* update the number '1' to the ID number on your form */
add_filter( 'gform_pre_render_2', 'add_readonly_gravity_form_field' );
function add_readonly_gravity_form_field( $form ){
	?><script type="text/javascript">jQuery(document).ready(function() { jQuery(".gf_readonly .ginput_container input").attr("readonly","readonly");
     });</script><?php
	return $form;
}

add_action( 'save_post', 'check_rqst_status_and_mail', 10,3 );
/* if admin did status as approved for a user request to update account detail then email is hit after approved the changes */
function check_rqst_status_and_mail( $post_id, $post, $update ) {
    global $headers;
    /* $user_id = preg_replace('/[^0-9]/', '', $post->post_title); */
    $user_id = get_post_field('post_author', $post_id);
    if($post->post_type === 'rqst-acc-update'){
        $rqst_status = get_field('request_update_account_status',$post_id);
        $rqst_mail_status = get_user_meta( $user_id, 'rqst_mail_status', true );
        if($rqst_status == 'approved' && $rqst_mail_status == 0){
            update_user_meta( $user_id, "rqst_mail_status",1);
            $user_info = get_user_by('id', $user_id);
            $current_user_email = $user_info->user_email;
            $emailData = getEmailTemplateBySlug('approved-request-for-account-detail-update');
            $emailSubject = $emailData['Email_subject'];
            $emailBody = str_replace('{user_name}',$user_info->display_name,$emailData['Email_content']);    
            wp_mail( $current_user_email, $emailSubject, $emailBody, $headers);
        }
    }
}

add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number' );
function change_woocommerce_order_number( $order_id ) {
    $prefix = 'W/';
    $new_order_id = $prefix . $order_id;
    return $new_order_id;
}

add_action('woocommerce_admin_order_data_after_billing_address', 'my_custom_field_order_detail_page');
/* add distributor field and also show value on order detail page in admin */
function my_custom_field_order_detail_page($order) {
    $custom_field_name = 'Distributor';
    $custom_field_value = get_post_meta($order->id,'distributor', true);
   
    if(!empty($custom_field_value)){

        $taxonomy = 'distributor_locations';
        $args = array(
            'post_type' => 'distributors',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $distributors= get_posts($args);
        $distributor_cmpny_name = get_field('company_name', $custom_field_value);
        $distributor_customer_name = get_field('customer_number', $custom_field_value);
        $location = wp_get_post_terms($custom_field_value, $taxonomy);
        $output= '<p class="O-view-distributor"><strong>' . $custom_field_name . ':</strong> ' .$distributor_customer_name .'-'.$distributor_cmpny_name.' ('.$location[0]->name.')'.'</p>';
        $output .='<div class="form-field order-disrtibuotr-drpdwn"><label for="O-selected-distributor">Distributor</label>
        <select name="choosen_distributor" id="O-selected-distributor">
            <option value="">Select Distributor</option>';
            foreach ( $distributors as $distributor ) {
                    $distributor_cmpny_name = get_field('company_name', $distributor->ID);
                    $distributor_customer_name = get_field('customer_number', $distributor->ID);
                    $location = wp_get_post_terms($distributor->ID, $taxonomy);
    
                    $selected = ($distributor->ID == $custom_field_value) ? 'selected' : ''; // Check if distributor is selected
                    $output .= '<option value="' . $distributor->ID . '" ' . $selected . '>' . $distributor_customer_name .'-'.$distributor_cmpny_name.' ('.$location[0]->name.')'. '</option>';
            }
        $output .='</select></div>';
        echo $output;
    }
}

add_action('wp_ajax_update_distributor', 'update_distributor_ajax');
add_action('wp_ajax_nopriv_update_distributor', 'update_distributor_ajax');
/* save distributor value when update order detail page in admin */
function update_distributor_ajax() {
    if (isset($_POST['order_id']) && isset($_POST['distributor_id'])) {
        $order_id = intval($_POST['order_id']);
        $distributor_id = sanitize_text_field($_POST['distributor_id']);
        update_post_meta($order_id, 'distributor', $distributor_id);
        wp_send_json_success(array('message' => 'success'));
    }
    wp_send_json_error(array('message' => 'Invalid request data'));
}

add_filter( 'woocommerce_available_payment_gateways', 'bbloomer_paypal_disable_manager' );
function bbloomer_paypal_disable_manager( $available_gateways ){   
    if(is_user_logged_in()){
        if ( isset( $available_gateways['braintree'] ) || isset( $available_gateways['braintree_cc']) || isset( $available_gateways['braintree_googlepay']) && wc_current_user_has_role( 'um_dealer' ) ) {
            unset( $available_gateways['braintree'] );
            unset( $available_gateways['braintree_cc'] );
            unset( $available_gateways['braintree_googlepay'] );
        }	 
        return $available_gateways;
    }else{
        if ( isset( $available_gateways['cod'] )){
            unset( $available_gateways['cod'] );
        }
        return $available_gateways;
    }
}
add_action('wpcf7_before_send_mail', 'wpcf7_order_inquiry_form_data', 10, 3);
function wpcf7_order_inquiry_form_data($contact_form, &$abort, $object){  
	$error = false;
    global $currentUserID;
    global $contactFormIDs;
    if($_POST['_wpcf7'] == $contactFormIDs['Order_Inquiry_Form'] && $currentUserID > 0){
        $current_user = wp_get_current_user();
        $current_username = $current_user->display_name;
        $current_user_cust_no = get_user_meta($currentUserID, "customer_number", true);
        $to_date = $_POST['OI_to_date']; 
        $from_date = $_POST['OI_from_date']; 
        $filter_type = $_POST['OI_filter_type'];
        $filter_value = $_POST['OI_filter_value'] ;
        $order_status = $_POST['OI_order_status'];
    }
    if (isset($error) && $error == true) {
		$msgs = $contact_form->prop('messages');
		$contact_form->set_properties(array('messages' => $msgs));
		$abort = true;
	}
    return $contact_form;
}

/* include file for quote inquiry page */
include "function-includes/order-inquiry.php";

/* include file for quote inquiry page */
include "function-includes/quote-inquiry.php";

/* include file for part number search page*/
include "function-includes/part-number-search.php";

function product_price_shortcode( $attr ) {  
    $attr = shortcode_atts( array(    
        'id' => null,   
    ), $attr, 'woocommerce_price' );
    $html = '';    
    if( intval( $attr['id'] ) > 0 && function_exists( 'wc_get_product' ) ){     
        $product = wc_get_product( $attr['id'] );   

        if ( $product->is_type( 'variable' ) ) {
           /* For variable products, get the variation prices and find the minimum price. */
            $variation_prices = $product->get_variation_prices( true );
            $min_variation_price = min( $variation_prices['price'] );
            $html = wc_price( $min_variation_price );
        } else {
            /* For simple products or others, just display the product price. */
            $html = get_woocommerce_currency_symbol() . $product->get_price();
        }
    }    
    return  $html;
}
add_shortcode( 'product_price', 'product_price_shortcode' );

include("shipping-address-type.php");




function custom_US_state_drpdwn($tag){   
    /* for USA page */
    if ($tag['basetype'] == 'select' && $tag['name'] == 'US_state') {

             /* Modify the options as per your requirement */
             $values = [
                'AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN',
                'IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH',
                'NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT',
                'VT','VA','WA','WV','WI','WY','N/A'
            ];
            
            $labels = [
                'Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut',
                'Delaware','District of Columbia','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana',
                'Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan',
                'Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire',
                'New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma',
                'Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas',
                'Utah','Vermont','Virginia','Washington','West Virginia','Wisconsin','Wyoming','N/A'
            ];
            
            $tag['raw_values'][]=  $values;    
            $tag['values'] = $values;
            $tag['labels'] = $labels;
    }  
    /* canada page */  
    if ($tag['basetype'] == 'select' && $tag['name'] == 'CA_state') {

             /* Modify the options as per your requirement */
             $values = [
                'AB','BC','MB','NB','NL','NT','NS','NU','ON','PE','QC','SK','YT'
            ];
                    
            $labels = [
                'Alberta','British Columbia','Manitoba','New Brunswick',
                'Newfoundland and Labrador','Northwest Territories','Nova Scotia',
                'Nunavut','Ontario','Prince Edward Island','Quebec','Saskatchewan','Yukon'
            ];
            
            
            $tag['raw_values'][]=  $values;
            $tag['values'] = $values;
            $tag['labels'] = $labels;
    }    
    return $tag;
}
add_filter('wpcf7_form_tag', 'custom_US_state_drpdwn', 10, 3);

/* $current_page = get_page_by_path($_SERVER['REQUEST_URI']);
if(isset($current_page->ID) && $current_page->ID == $pageIDs['dealer_search_USA'] ) { */
    /* include file for dealer locator US page */
    include "function-includes/dealer-locator-US.php";
/* } */


/* include file for dealer locator Canada page */
include "function-includes/dealer-locator-canada.php";


/* code is use for adding a button on product dteail page */
add_action('woocommerce_after_add_to_cart_button','cmk_additional_button');
function cmk_additional_button() {
    global $product;
    global $wpdb;
    $tableName = $wpdb->prefix."users_sessions_details";
    $productID = $product->get_id();
     /* Generate a random number for session ID */
     
	// Start the session if not already started
    if (!session_id()) {
        session_start();
    }
	 
	$sessionID = session_id();
	$_SESSION['session_id'] = $sessionID; 
     // Store the session ID in the WordPress session
    $_SESSION['cmk_configure_session_id'] = $sessionID;

     $wpdb->insert(
        $tableName,
        array(
            'user_id' => get_current_user_id(),
            'session_id' => $sessionID,
        )
    );
     $configureLink = get_field("configure_link", $productID);
     if(!empty($configureLink)){
         $optiontree = get_option('option_tree');
         // Get the value of the loader_image field
         $loader_image = $optiontree['loder_image'];
         /* Append the session ID to the link */
         $configureLink .= "&I_SESSION_ID=$sessionID";
        echo '<a href="'.$configureLink.'" target="_blank" id="configure-now"><button type="button" class="button alt configure-now-btn" data-session_id="'.$sessionID.'">Configure Now</button></a>
        <span class="ajax-rqst-loder" style="display:none;">
                <img src="'. $loader_image.'" width="100px" height="100">
        </span>';
     }
}
add_action('wp_ajax_uniqueSessionSave', 'uniqueSessionSave');
add_action('wp_ajax_nopriv_uniqueSessionSave', 'uniqueSessionSave');
function uniqueSessionSave(){
    global $wpdb;
    $tableName = $wpdb->prefix."users_sessions_details";
    $sessionID = $_POST['session_id'];
    $wpdb->insert(
        $tableName,
        array(
            'user_id' => get_current_user_id(),
            'session_id' => $sessionID,
        )
    );
    $result = [
        'message' => 'Success!',
        // ... other data to send
      ];
    
      // Send the response
      wp_send_json_success($result);
}

function ao_check_cookie() {
	
	if(get_the_ID() == wc_get_page_id('cart') && isset($_GET['sid'])){
		session_start();
		if($_SESSION['cmk_configure_session_id'] == $_GET['sid']){
			WC()->cart->add_to_cart($_GET['pid'], 1);
			wp_redirect(get_permalink(get_the_ID()));
		}
	}
}
add_action( 'wp', 'ao_check_cookie' );

/* add_action( 'woocommerce_thankyou', function( $order_id ) {
    // Get the order object
    $order = wc_get_order( $order_id );
    $product_ids = [];
    foreach ( $order->get_items() as $item ) {
    $product_ids[] = $item->get_product_id();
    }   
    foreach ( $product_ids as $product_id ) {
        $product = wc_get_product( $product_id );

        $product_description = $product->get_description();
         $product_excerpt = $product->get_excerpt();
      
         // Use the product content in your note text
        $note_text .= "Product ID: $product_id<br>";
        $note_text .= "Title: $product_title<br>";
        // ... Add additional product content as needed ...
      
        $order->add_order_note( $note_text );
      
        // Reset note text for next iteration
        $note_text = ""; 
      }
       prd( $product_description);
  
    // Add the text to the order notes
    $order->add_order_note(  $product_description );
  } ); */

//   add_action( 'woocommerce_new_order', 'my_custom_order_notification', 10, 1 );
add_action('woocommerce_checkout_order_processed', 'my_custom_order_notification', 10, 1);
function my_custom_order_notification( $order_id ) {
    $order = wc_get_order( $order_id );
    $productIds = [];
    foreach ( $order->get_items() as $item ) {
        $productIds[] = $item->get_product_id();
    }   
    foreach ( $productIds as $productID ) {
        $product = wc_get_product( $productID );
        $terms = wp_get_post_terms( $productID, 'product_cat' );
        $has_interest_category = false;
      
        foreach ( $terms as $term ) {
            if ( $term->name === "Delete configure products" ) {
                $has_interest_category = true;
                break;
            }
        }
      
        if ( $has_interest_category ) {
            $note_text = $product->get_description();
            $order->add_order_note( $note_text );
            $note_text = ""; 
            /* wp_delete_post( $productID, true ); */
        }
    }
}

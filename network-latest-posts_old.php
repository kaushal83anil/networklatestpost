<?php
/*
Plugin Name: Latest Network Posts
Plugin URI: http://cityscoop.us/
Description: Display the latest posts from the blogs in your network using it as a function, shortcode.
Version: 1.0
Author: Anil Sharma
Author URI: http://inetbees.com/
 */

// Requires widget class
//require_once dirname( __FILE__ ) . '/network-latest-posts-widget.php';
global $wpdb;
define( 'NP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/network-latest-posts' );
define( 'NP_PLUGIN_URL', WP_CONTENT_URL. '/plugins/network-latest-posts' );
include_once('cronjobs/cronjob.php');

function network_latest_posts( $parameters ) {
    // Global variables
    global $wpdb;
 
}


/* Shortcode function
 *
 * @atts: attributes passed to the main function
 * return @shortcode
 */
function network_latest_posts_shortcode($atts) {
   ob_start(); if( !empty($atts) ) { //print_r($atts); ?>
<div class="ca-container">
<?php //$i++; }?>

<div class="ca-wrapper">
<?php if($atts['title']==1){
	$string = file_get_contents(NP_PLUGIN_DIR.'/cronjobs/'. date('n_j_Y').".json");
						}  
$json_data=json_decode($string,true);
//echo '<pre>';
$output = array_slice($json_data, 0, 30);
//echo count($output);
//echo '</pre>';
/*foreach starts*/
$i=1;
 foreach($output as $post){ ?>
 <div class="ca-item">
	<div class="ca-item-main">
		<?php $feat_image = $post['Post_img'];?>				
		<?php if(!empty($feat_image)){?><img src="<?php echo $feat_image; ?>" alt="<?php echo $post['Post_seoTitle'];?>"><?php }else
		{?> <img src="<?php echo NP_PLUGIN_URL;?>/img/image_place_holder.jpg" alt"<?php echo $post['Post_seoTitle'];?>" />
		<?php }?>
		<h3 class="npl">
			<a href="<?php echo $post['Post_Permalink'];?>" alt="<?php echo $post['Post_SeoDiscription'];?>" title="<?php echo $post['Post_seoTitle'];?>" target="_blank" class="<?php echo $i; ?>">
			<?php  echo $post['Post_title']; ?> 
			</a>
		</h3>
		<hr>
		<?php $text= $post['Post_SeoDiscription']; ?>
		
		<p><span><?php echo $text;?></span></p>
		</div>
	</div>
					
		<?php $i++;}     
		?>
		</div></div>
		
		<?php
/*foreach ends*/

    }
    // Start the output buffer to control the display position
    
    // Get the posts
    network_latest_posts($atts);
    // Output the content
    $shortcode = ob_get_contents();
    $out = ob_get_contents();
    // Clean the output buffer
    ob_end_clean();
    // Put the content where we want
    return $shortcode;
}
// Add the shortcode functionality
add_shortcode('nlposts','network_latest_posts_shortcode');

/* Limit excerpt length
 * @count: excerpt length
 * @content: excerpt content
 * @permalink: link to the post
 * return customized @excerpt
 */




/* Init function
 * Plugin initialization
 */

function network_latest_posts_init() {
    global $wp_locale;
    // Check for the required API functions
    if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
        return;
    // Register functions
    wp_register_sidebar_widget('nlposts-sb-widget',__("Latest Posts From Network",'trans-nlp'),"network_latest_posts_widget");
    wp_register_widget_control('nlposts-control',__("Latest Posts From Network",'trans-nlp'),"network_latest_posts_control");
    wp_register_style('nlpcss-form', plugins_url('/css/form_style.css', __FILE__));
     wp_enqueue_style('nlpcss-form');
    register_uninstall_hook(__FILE__, 'network_latest_posts_uninstall');
    // Load plugins
    wp_enqueue_script('jquery');
}
/* 
 * Load Languages
 */
function nlp_load_languages() {
    // Set the textdomain for translation purposes
    load_plugin_textdomain('trans-nlp', false, basename( dirname( __FILE__ ) ) . '/languages');
}
// Load CSS Styles
function nlp_load_styles($css_style) {
    if( !empty($css_style) ) {
        // Unload default style
        wp_deregister_style('nlpcss');
        // Load custom style
        wp_register_style('nlp-custom',$css_style);
        wp_enqueue_style('nlp-custom');
    } else {
        // Unload custom style
        wp_deregister_style('nlp-custom');
        // Load default style
       // wp_register_style( 'nlpcss', plugins_url('/css/default_style.css', __FILE__) );
        wp_enqueue_style( 'nlpcss' );
    }
    return;
}

/* Load Widget



/* Uninstall function
 * Provides uninstall capabilities
 */
function network_latest_posts_uninstall() {
    // Delete widget options
    delete_option('widget_nlposts_widget');
    // Delete the shortcode hook
    remove_shortcode('nlposts');
}

/*
 * TinyMCE Shortcode Plugin
 * Add a NLPosts button to the TinyMCE editor
 * this will simplify the way it is used
 */
// TinyMCE button settings
function nlp_shortcode_button() {
    if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {
        add_filter('mce_external_plugins', 'nlp_shortcode_plugin');
        add_filter('mce_buttons', 'nlp_register_button');
    }
}
// Hook the button into the TinyMCE editor
function nlp_register_button($buttons) {
    array_push($buttons, "|" , "nlposts");
    return $buttons;
}
// Load the TinyMCE NLposts shortcode plugin
function nlp_shortcode_plugin($plugin_array) {
   $plugin_array['nlposts'] = plugin_dir_url(__FILE__) .'js/nlp_tinymce_button.js';
   return $plugin_array;
}

// Hook the shortcode button into TinyMCE
add_action('init', 'nlp_shortcode_button');
// Load styles
//add_action('wp_head','nlp_load_styles',10,1);
// Run this stuff
add_action("admin_enqueue_scripts","network_latest_posts_init");
// Languages
add_action('plugins_loaded', 'nlp_load_languages');
function crusal(){ 
	echo $instance= network_latest_posts_shortcode($atts['title']);
?>
	<script type="text/javascript">
	<?php for($i=1; $i<=1;){?>
			jQuery('.ca-container').contentcarousel({
				infinite: false,
			    sliderSpeed     : 1000,
			   
			    sliderEasing    : 'easeOutExpo',
		         itemSpeed       : 1500,
			  
			    itemEasing      : 'easeOutExpo',
			     scroll          : 5 });
			 <?php $i++; }?>
		   function itemLoadCallbackFunction(carousel, state)
		   {
		       for (var i = carousel.first; i <= carousel.last; i++) {
		           
		           if (!carousel.has(i)) {
		               
		               carousel.add(i, "I'm item #" + i);
		           }
		       }
		   };
		</script>
<?php } 
add_action('wp_print_scripts', 'crusalscripts');
	function crusalscripts(){
	wp_enqueue_script('contentcarousel', NP_PLUGIN_URL.'/js/jquery.contentcarousel.js');
	wp_enqueue_script('easing', NP_PLUGIN_URL.'/js/jquery.easing.1.3.js');
 
	wp_enqueue_script('jquery-migrate', 'http://code.jquery.com/jquery-migrate-1.2.1.js');
	}

add_action('wp_print_styles', 'crusalcss');
	function crusalcss(){
	
		//wp_enqueue_style('jscrollpane', NP_PLUGIN_URL.'/css/jquery.jscrollpane.css');

		wp_enqueue_style('crusal_style', NP_PLUGIN_URL.'/css/crusal_style.css');
		
		
	}

add_action('wp_footer','crusal');
function limit_words($string, $word_limit)
{
    $words = explode(" ",$string);
    return implode(" ", array_splice($words, 0, $word_limit));
}

//
/*
 *  echo NP_PLUGIN_DIR;
 *  echo ; 
 * */
?>

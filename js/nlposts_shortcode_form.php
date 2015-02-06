<?php
/*
    Latest Network Posts Shortcode Form
    Version 1.0
    Author Anil Sharma
    Author URI http://inetbees.com/
 */

// Retrieve the WordPress root path
function nlp_config_path()
{
    $base = dirname(__FILE__);
    $path = false;
    // Check multiple levels, until find the config file
    if (@file_exists(dirname(dirname($base))."/wp-config.php")){
        $path = dirname(dirname($base));
    } elseif (@file_exists(dirname(dirname(dirname($base)))."/wp-config.php")) {
        $path = dirname(dirname(dirname($base)));
    } elseif (@file_exists(dirname(dirname(dirname(dirname($base))))."/wp-config.php")) {
        $path = dirname(dirname(dirname(dirname($base))));
    } elseif (@file_exists(dirname(dirname(dirname(dirname(dirname($base)))))."/wp-config.php")) {
        $path = dirname(dirname(dirname(dirname(dirname($base)))));
    } else {
        $path = false;
    }
    // Get the path
    if ($path != false){
        $path = str_replace("\\", "/", $path);
    }
    // Return the path
    return $path;
}
$wp_root_path = nlp_config_path();
// Load WordPress functions & NLposts_Widget class
require_once("$wp_root_path/wp-load.php");
require_once("../network-latest-posts-widget.php");
//$thumbnail_w = '80';
//$thumbnail_h = '80';
// Widget object
$widget_obj = new NLposts_Widget();
// Default values
$defaults = array(
    'title'            => NULL,          // Widget title
    'number_posts'     => 10,            // Number of posts to be displayed
    'time_frame'       => 0,             // Time frame to look for posts in days
    'title_only'       => TRUE,          // Display the post title only
  
    'blog_id'          => NULL,          // ID(s) of the blog(s) you want to display the latest posts
 
    'thumbnail'        => FALSE,         // Display the thumbnail
    'thumbnail_wh'     => '80x80',       // Thumbnail Width & Height in pixels
    'thumbnail_url'    => NULL,          // Custom thumbnail URL
    'custom_post_type' => 'post',        // Type of posts to display
    'posts_per_page'   => NULL,          // Number of posts per page (paginate needs to be active)
     'excerpt_length'   => NULL,          // Excerpt's length
   'post_status'      => 'publish',     // Post status (publish, new, pending, draft, auto-draft, future, private, inherit, trash)
);
// Set an array
$settings = array();
// Parse & merge the settings with the default values
$settings = wp_parse_args( $settings, $defaults );
// Extract elements as variables
extract( $settings );
$thumbnail_size = str_replace('x',',',$thumbnail_wh);
$thumbnail_size = explode(',',$thumbnail_size);
$thumbnail_w = $thumbnail_size[0];
$thumbnail_h = $thumbnail_size[1];
// Get blog ids
global $wpdb;
$blog_ids = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs WHERE
    public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0'
        ORDER BY last_updated DESC");
// Basic HTML Tags
$br = "<br />";
$p_o = "<p>";
$p_c = "<p>";
$widget_form = "<form id='nlposts_shortcode' name='nlposts_shortcode' method='POST' action=''>";
$widget_form.= $p_o;
// title
$widget_form.= "<label for='title'>" . __('Title','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' id='title' name='title' value='$title' />";
$widget_form.= $br;
$widget_form.= "<label for='number_posts'>" . __('Number of Posts Per Blog','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' size='3' id='number_posts' name='number_posts' value='$number_posts' />";
$widget_form.= $br;
$widget_form.= "<label for='time_frame'>" . __('Time Frame in Second','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' size='3' id='time_frame' name='time_frame' value='$time_frame' />";
$widget_form.= $br;
// title_only
$widget_form.= "<label for='title_only'>" . __('Titles Only','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<select id='title_only' name='title_only'>";
if( $title_only == 'true' ) {
    $widget_form.= "<option value='true' selected='selected'>" . __('Yes','trans-nlp') . "</option>";
    $widget_form.= "<option value='false'>" . __('No','trans-nlp') . "</option>";
} else {
    $widget_form.= "<option value='true'>" . __('Yes','trans-nlp') . "</option>";
    $widget_form.= "<option value='false' selected='selected'>" . __('No','trans-nlp') . "</option>";
}
$widget_form.= "</select>";
$widget_form.= $br;

// blog_id
$widget_form.= $br;
if( is_rtl() ) {
    $widget_form.= "<label for='blog_id'>" . __('Display Blog','trans-nlp') . " " . __('or','trans-nlp') . " " . __('Blogs','trans-nlp') . "</label>";
} else {
    $widget_form.= "<label for='blog_id'>" . __('Display Blog(s)','trans-nlp') . "</label>";
}
$widget_form.= $br;
$widget_form.= "<select id='blog_id' name='blog_id' multiple='multiple'>";
// Get the blog_id string
if( !is_array($blog_id) ) {
    // Check for multiple values
    if( preg_match('/,/',$blog_id) ) {
        // Set an array
        $blog_id = explode(',',$blog_id);
    } else {
        // Single value
        if( empty($blog_id) ) {
            // Set an empty array
            $blog_id = array('null');
        } else {
            // Set an array
            $blog_id = array($blog_id);
        }
    }
}
if( empty($blog_id) || $blog_id == 'null' || in_array('null',$blog_id) ) {
    $widget_form.= "<option value='null' selected='selected'>" . __('Display All','trans-nlp') . "</option>";
} else {
    $widget_form.= "<option value='null'>" . __('Display All','trans-nlp') . "</option>";
}
// Display the list of blogs
foreach ($blog_ids as $single_id) {
    $blog_details = get_blog_details($single_id->blog_id);
    if( !empty($blog_id) && in_array($single_id->blog_id,$blog_id) ) {
        $widget_form.= "<option value='$single_id->blog_id' selected='selected'>". $blog_details->blogname ." (ID $single_id->blog_id)</option>";
    } else {
        $widget_form.= "<option value='$single_id->blog_id'>". $blog_details->blogname ." (ID $single_id->blog_id)</option>";
    }
}
$widget_form.= "</select>";
// ignore_blog

// thumbnail
$widget_form.= $br;
$widget_form.= "<label for='thumbnail'>" . __('Display Thumbnails','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<select id='thumbnail' name='thumbnail'>";
if( $thumbnail == 'true' ) {
    $widget_form.= "<option value='true' selected='selected'>" . __('Show','trans-nlp') . "</option>";
    $widget_form.= "<option value='false'>" . __('Hide','trans-nlp') . "</option>";
} else {
    $widget_form.= "<option value='true'>" . __('Show','trans-nlp') . "</option>";
    $widget_form.= "<option value='false' selected='selected'>" . __('Hide','trans-nlp') . "</option>";
}
$widget_form.= "</select>";
$widget_form.= $br;
$widget_form.= "<fieldset>";
$widget_form.= "<legend>" . __('Thumbnail Size','trans-nlp') . "</legend>";
$widget_form.= "<label for='thumbnail_w'>" . __('Width','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' size='3' id='thumbnail_w' name='thumbnail_w' value='$thumbnail_w' />";
$widget_form.= $br;
$widget_form.= "<label for='thumbnail_h'>" . __('Height','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' size='3' id='thumbnail_h' name='thumbnail_h' value='$thumbnail_h' />";
$widget_form.= "</fieldset>";
// thumbnail_filler

// custom_post_type
$widget_form.= $br;
$widget_form.= "<label for='custom_post_type'>" . __('Custom Post Type','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' id='custom_post_type' name='custom_post_type' value='$custom_post_type' />";

// posts_per_page
$widget_form.= $br;
$widget_form.= "<label for='posts_per_page'>" . __('Posts per Page','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' id='posts_per_page' name='posts_per_page' value='$posts_per_page' />";

$widget_form.= $br;
$widget_form.= "<label for='excerpt_length'>" . __('Excerpt Length','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' id='excerpt_length' name='excerpt_length' value='$excerpt_length' />";

// post_status
$widget_form.= $br;
$widget_form.= "<label for='post_status'>" . __('Post Status','trans-nlp') . "</label>";
$widget_form.= $br;
$widget_form.= "<input type='text' id='post_status' name='post_status' value='$post_status' />";
// full_meta

$widget_form.= $br;
$widget_form.= "<input type='button' id='nlposts_shortcode_submit' value='".__('Insert Shortcode','trans-nlp')."' />";
$widget_form.= $p_c;
$widget_form.= "</form>";
echo $widget_form;
?>
<script type="text/javascript" charset="utf-8">
    //<![CDATA[
    jQuery('#nlposts_shortcode_submit').click(function(){
        // Count words
        function nlp_countWords(s) {
            return s.split(/[ \t\r\n]/).length;
        }
        // Get the form fields
        var values = {};
        jQuery('#TB_ajaxContent form :input').each(function(index,field) {
            name = '#TB_ajaxContent form #'+field.id;
            values[jQuery(name).attr('id')] = jQuery(name).val();
        });
        // Default values
        var defaults = new Array();
        defaults['title'] = null;
        defaults['number_posts'] = '10';
        defaults['time_frame'] = '0';
        defaults['title_only'] = 'true';
        defaults['display_type'] = 'ulist';
        defaults['blog_id'] = null;
        defaults['ignore_blog'] = null;
        defaults['thumbnail'] = 'false';
        defaults['thumbnail_wh'] = '80x80';
        defaults['thumbnail_class'] = null;
        defaults['thumbnail_filler'] = 'placeholder';
        defaults['thumbnail_custom'] = 'false';
        defaults['thumbnail_field'] = null;
        defaults['custom_post_type'] = 'post';
        defaults['category'] = null;
        defaults['tag'] = null;
        defaults['paginate'] = 'false';
        defaults['posts_per_page'] = null;
        defaults['display_content'] = 'false';
        defaults['excerpt_length'] = null;
        defaults['auto_excerpt'] = 'false';
        defaults['full_meta'] = 'false';
        defaults['sort_by_date'] = 'false';
        defaults['sort_by_blog'] = 'false';
        defaults['sorting_order'] = 'desc';
        defaults['sorting_limit'] = null;
        defaults['post_status'] = 'publish';
        defaults['excerpt_trail'] = 'text';
        defaults['css_style'] = null;
        defaults['wrapper_list_css'] = 'nav nav-tabs nav-stacked';
        defaults['wrapper_block_css'] = 'content';
        defaults['instance'] = null;
        defaults['random'] = 'false';
        defaults['post_ignore'] = null;
        // Set the thumbnail size
        if( values.thumbnail_w && values.thumbnail_h ) {
            var thumbnail_wh = values.thumbnail_w+'x'+values.thumbnail_h;
            values['thumbnail_wh'] = thumbnail_wh;
            values['thumbnail_w'] = 'null';
            values['thumbnail_h'] = 'null';
        }
        // Clear the submit button so the shortcode doesn't take its value
        values['nlposts_shortcode_submit'] = null;
        // Build the shortcode
        var nlp_shortcode = '[nlposts';
        // Get the settings and values
        for( settings in values ) {
            // If they're not empty or null
            if( values[settings] && values[settings] != 'null' ) {
                // And they're not the default values
                if( values[settings] != defaults[settings] ) {
                    // Count words
                    if( nlp_countWords(String(values[settings])) > 1 ) {
                        // If more than 1 or a big single string, add quotes to the key=value
                        nlp_shortcode += ' '+settings +'="'+ values[settings]+'"';
                    } else {
                        // Otherwise, add the key=value
                        nlp_shortcode += ' '+settings +'='+ values[settings];
                    }
                }
            }
        }
        // Close the shortcode
        nlp_shortcode += ']';
        // insert the shortcode into the active editor
        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, nlp_shortcode);
        // close Thickbox
        tb_remove();
    });
    //]]>
</script>
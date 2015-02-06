/*
    Latest Network Posts TinyMCE Plugin
    Version 1.0
    Author Anil Sharma
    Author URI https://inetbees.com/
 */

(function() {
    // Set the plugin
    tinymce.create('tinymce.plugins.nlposts', {
        init : function(ed, url) {
            // Add this button to the TinyMCE editor
            ed.addButton('nlposts', {
                // Button title
                title : 'Latest Posts From Network',
                // Button image
                image : url+'/city.ico',
                onclick : function() {
                    // Window size
                    var width = jQuery(window).width(), height = jQuery(window).height(), W = ( 720 < width ) ? 720 : width, H = ( height > 600 ) ? 600 : height;
                    W = W - 80;
                    H = H - 84;
                    tb_show( 'Latest Network Posts Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=nlposts-form' );
                    // Load form
                    jQuery(function(){
                        // Dynamic load
                        jQuery('#TB_ajaxContent').load(url+'/nlposts_shortcode_form.php');
                    });
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        }
    });
    // Run this stuff
    tinymce.PluginManager.add('nlposts', tinymce.plugins.nlposts);
})();
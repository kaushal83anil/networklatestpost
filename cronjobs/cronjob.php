<?php
function get_network_posts() {
    global $authordata, $post, $wpdb;
	$table= $wpdb->blogs;
	$blog_id = get_current_blog_id();
	$author_id= get_the_author_meta('ID');
	$sql= "select blog_id from ".$table ." where public='1' ORDER BY blog_id DESC" ;
	$blog_query= $wpdb->get_results($sql);
	//print_r($blog_query);
	$bids=array();
				 //echo '<ul class="yellowBullet">';
	foreach($blog_query as $blog){
		 $bids[]= $blog->blog_id;
	}
	//$blogs= rsort($bids);
				
	$output=array();
	foreach($bids as $bid):
				/**/
	switch_to_blog($bid);
	 $author_id= get_the_author_meta('ID');
				
				
	 $authors_posts = get_posts('posts_per_page=1&paged=1');
				
     foreach ( $authors_posts as $authors_post ) {
$d = strtotime($authors_post->post_date_gmt);
$output[$d]=array('Post_title'=>$authors_post->post_Title =  $authors_post->post_title ,'Post_Permalink'=> $authors_post->permalink =  get_permalink($authors_post->ID), 'Blog_url'=> $authors_post->blogUrl =  get_bloginfo('url'), 'Sitename'=> $authors_post->blogName =  get_bloginfo('name'), 'Post_img'=> $authors_post->thumbnail =wp_get_attachment_url( get_post_thumbnail_id($authors_post->ID) ), 'Post_seoTitle'=> $authors_post->seo_Title=get_post_meta($authors_post->ID,'_aiosp_title',true),'Post_SeoDiscription'=> $authors_post->seo_Dis= get_post_meta($authors_post->ID,'_aioseop_description',true));
				//$output[$d]=$authors_post;
				    }
				
	endforeach;     
	switch_to_blog( $blog_id );
	krsort($output);
	return $output;
	}
/*latest posts ends*/
function monday_event_activation() 
{
    if ( !wp_next_scheduled( 'new_daily_event' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'daily', 'new_daily_event');
    }
}
//add_action('wp', 'monday_event_activation');

function monday_event()
{
    // Get the current date time
    $dateTime = new DateTime();

    // Check that the day is Monday
    if($dateTime->format('N') == 1)
    {
     $content=get_network_posts();
	$jsondata= json_encode($content);
$fp = fopen(NP_PLUGIN_DIR.'/cronjobs/npl.json','wb');
fwrite($fp,$jsondata);
fclose($fp);
	
}

}

function jsonfile(){
	 $content=get_network_posts();
	$jsondata= json_encode($content);
	// print_r($jsondata);
$fp = fopen(NP_PLUGIN_DIR.'/cronjobs/npl.json','wb');
//echo NP_PLUGIN_DIR.'/cronjobs/'. date('n_j_Y').".json";//$fp; //die();
fwrite($fp,$jsondata);
fclose($fp);
	
}


add_action("event_callback",'event' );
function event(){
        jsonfile(); 
    }


    if(!wp_next_scheduled("event_callback"))
{
        //first argument is the time after which the event will be fired
        //name of the action hook whose callback will be executed.
        wp_schedule_event(time() + 43200, "event_callback");   
 }

 do_action("event_callback");

//add_action('admin_head','jsonfile');

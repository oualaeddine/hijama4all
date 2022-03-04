<?php

if ( ! function_exists( 'aep_get_contact_form7' ) ) {

  function aep_get_contact_form7(){

  $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);

    $formlist=[];
    
    if( $post = get_posts($args)){
    	foreach ( $post as $posts ) {
    		(int)$formlist[$posts->ID] = $posts->post_title;
    	}
    }
    else{
        (int)$formlist['0'] = esc_html__('No contact Form 7 found', 'aep');
    }
  return $formlist;
  }

}
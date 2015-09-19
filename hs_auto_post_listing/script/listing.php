<?php 

function hs_get_auto_post_url(){
	$url = $_POST[ "link" ];
	return $url;
}

function hs_get_auto_post_title(){
	$title = $_POST[ "title" ];
	return $title;
}

function hs_get_auto_post_photo_url( $size = "" ){
	
	$photo = wp_unique_filename( wp_upload_dir()[ "url" ], $_FILES[ "image" ][ "name" ][ 0 ] );
	$photo = wp_upload_dir()[ "url" ] . "/" . $photo;

	if ( $size == "small" ){
		$extension_position = strrpos( $photo, "." );
		$photo_extension = substr( $photo, $extension_position );
		$photo = substr( $photo, 0, $extension_position ) . "-150x150" . $photo_extension;
	}
	return $photo;
}

function hs_get_auto_post_excerpt(){
	$excerpt = $_POST[ "form" ][ "post_content" ];
	return $excerpt;	
}

function hs_get_auto_post_description(){
	$description = $_POST[ "form" ][ "post_content" ];
	return $description;	
}

function hs_get_auto_post_author(){
	$author = $userdata->user_nicename;
	return $author;
}

?>
<?php 

function hs_get_facebook_client(){
	$facebook = new Facebook\Facebook([
		"app_id" => "WWWWWWWWWWWWWWWWWWWW",						// Place your App ID here.
		"app_secret" => "XXXXXXXXXXXXXXXXXXXXXXX",				// Place your App secret here.
		"default_graph_version" => "v2.4",
		"default_access_token" => "YYYYYYYYYYYYYYYYYYYYYYYYYY", // Place your Access token here.	
	]);	 
	return $facebook;
}

function hs_auto_post_to_facebook(){
	
	$facebook = hs_get_facebook_client();
	
	$listing_data = [
		"link" => hs_get_auto_post_url(),
		"message" => hs_get_auto_post_title() . ". " . hs_get_auto_post_description(),
		"picture" => hs_get_auto_post_photo_url(),
	];

	try {
		// Returns a 'Facebook\FacebookResponse' object
		$response = $facebook->post( "/silverhoneymedia/feed", $listing_data, "YYYYYYYYYYYYYYYYYYYYYYYYY" );	// Place your Facebook Page link and Access token.
	} catch( Facebook\Exceptions\FacebookResponseException $e ) {
		error_log ( "Facebook Graph returned an error: " . $e->getMessage(), 3, "error.log" );
	} catch( Facebook\Exceptions\FacebookSDKException $e ) {
		error_log ( "Facebook SDK returned an error: " . $e->getMessage(), 3, "error.log" );
	} 
}

?>
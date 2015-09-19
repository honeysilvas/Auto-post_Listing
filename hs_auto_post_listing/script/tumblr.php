<?php

function hs_get_tumblr_client(){
	// Authenticate via OAuth.
	$client = new Tumblr\API\Client(
		"WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW",	// Put your consumer key here.
		"XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX",	// Put your consumer secret here.
		"YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY",	// Put your token here.
		"ZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZZ"	// Put your secret here.
	);
	return $client;
}

function hs_log_tumblr_error( $listing_data, $e ){
	$tumblr_error = "\r\n"; 
	$tumblr_error .= "\r\n " . implode( "", $responce ); 
	$tumblr_error .= "\r\n " . implode( "", $listing_data ); 
	$tumblr_error .= "\r\n Type: " . $listing_data[ "type" ]; 
	$tumblr_error .= "\r\n caption: " . $listing_data[ "caption" ]; 
	$tumblr_error .= "\r\n link: " . $listing_data[ "link" ]; 
	$tumblr_error .= "\r\n source: " . $listing_data[ "source" ]; 		
	$tumblr_error .= "\r\n Tumblr returned an error: " . $e->getMessage() . "<br />" . implode( "", $listing_data );
	error_log ( $tumblr_error, 3, "error.log" );
	return $tumblr_error;
}

function hs_create_tumblr_photo_post( $client, $tumblr_blog_name, $post_image = "" ){
	$tumblr_error = "";

	$listing_data = array(
		"type" 			=> "photo",
		"caption"		=> hs_get_auto_post_title() . ". " . hs_get_auto_post_description(),
		"link"			=> hs_get_auto_post_url(),
		"source"		=> hs_get_auto_post_photo_url()
	);
	
	$listing_data[ "source" ] = hs_get_auto_post_photo_url( "small" );

	try {
		$client->createPost( $tumblr_blog_name, $listing_data );
	} catch( Exception $e ) {
		$tumblr_error = hs_log_tumblr_error( $listing_data, $e );
	}
	return $tumblr_error;
}

function hs_auto_post_to_tumblr(){
	$client = hs_get_tumblr_client();

	$tumblr_blog_name = "silverhoneymedia.com";

	$tumblr_error = hs_create_tumblr_photo_post( $client, $tumblr_blog_name );
	
	// Try submitting to Tumblr again using small image instead.
	if ( !empty( $tumblr_error )){
		$tumblr_error = hs_create_tumblr_photo_post( $client, $tumblr_blog_name, "small" );
	}
	
	// Try submitting to Tumblr again using default image instead.
	if ( !empty( $tumblr_error )){
		$tumblr_error = hs_create_tumblr_photo_post( $client, $tumblr_blog_name, "default" );
	}
}

function hs_authenticate_tumblr(){

	$consumerKey = "WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW";	// Put your consumer key here.
	$consumerSecret = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";	// Put your consumer secret here.
	
	$client = new Tumblr\API\Client( $consumerKey, $consumerSecret );
	
	$requestHandler = $client->getRequestHandler();
	$requestHandler->setBaseUrl( "https://www.tumblr.com/" );

	// If we are visiting the first time
	if (!$_GET[ "oauth_verifier" ]) {
		// Grab the oauth token.
		$resp = $requestHandler->request( "POST", "oauth/request_token", array());
		$out = $result = $resp->body;
		$data = array();
		parse_str($out, $data);

		// Tell the user where to go.
		echo '<a href="https://www.tumblr.com/oauth/authorize?oauth_token=' . $data[ "oauth_token" ].'"> GO </a>';
		$_SESSION[ "t" ] = $data[ "oauth_token" ];
		$_SESSION[ "s" ] = $data[ "oauth_token_secret" ];

	} else {
		$verifier = $_GET[ "oauth_verifier" ];

		// Use the stored tokens.
		$client->setToken( $_SESSION[ "t" ], $_SESSION[ "s" ]);

		// To grab the access tokens.
		$resp = $requestHandler->request( "POST", "oauth/access_token", array( "oauth_verifier" => $verifier ));
		$out = $result = $resp->body;
		$data = array();
		parse_str($out, $data);

		// And print out our new keys we got back.
		$token = $data[ "oauth_token" ];
		$secret = $data[ "oauth_token_secret" ];
		echo "token: " . $token . "<br/>secret: " . $secret;
		
		$client = new Tumblr\API\Client( $consumerKey, $consumerSecret, $token, $secret );
		$info = $client->getUserInfo();
		echo "<br/><br/>congrats " . $info->user->name . "!";
	}
}
?>
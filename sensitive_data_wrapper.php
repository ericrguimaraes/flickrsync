<?php

if( file_exists('sensitive_data.php') ){
	require_once('sensitive_data.php');
}else{
	// provide the definitions below in a sensitive_data.php file
	define('FLICKR_API_KEY', '');
	define('FLICKR_API_SECRET', '');
	define('FLICKR_USER_ID', '');	
}

?>
<?php
require_once('./sensitive_data_wrapper.php');
require_once('./lib/phpflickr/phpFlickr.php');

//session_start();
		
		function flickrsync_log($msg){
			flickrsync_log_mesmo($msg);
			flickrsync_vardump($_SESSION);
			flickrsync_vardump($_GET);
		}

		function flickrsync_log_mesmo($msg){
			error_log($msg."\n", 3, "/tmp/flickrsync.err");	
		}

		function flickrsync_vardump($var){
			ob_start();
			var_dump($var);
			flickrsync_log_mesmo(ob_get_clean());
		}

	flickrsync_log('iniciando');

    $f = new phpFlickr(FLICKR_API_KEY,FLICKR_API_SECRET);
    
    flickrsync_log('instanciado');
    $f->setToken(FLICKR_API_AUTH_TOKEN);
    
    flickrsync_log('setado token');
    
    $f->auth();
    flickrsync_log('autenticado');
    //change this to the permissions you will need
   // $f->auth("read");
    
    //echo "Copy this token into your code: " . $_SESSION['phpFlickr_auth_token'];


    
    echo 'listanddo flickr';
		//Parameterless searches have been disabled. Please use flickr.photos.getRecent instead.
		$photos = $f->photos_search(array('user_id'=>FLICKR_USER_ID));
		//$photos = $this->phpFlickr->photos_getRecent();		

		
		
if( false === $photos ){
	print_r($f->getErrorCode());
	echo "\n";
		print_r($f->getErrorMsg());
		echo "\n";
}
		
		var_dump($photos);		
		print_r($photos);
echo ob_get_clean();
		exit;

?>
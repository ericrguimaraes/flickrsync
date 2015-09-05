<?php
    /* Last updated with phpFlickr 1.4
     *
     * If you need your app to always login with the same user (to see your private
     * photos or photosets, for example), you can use this file to login and get a
     * token assigned so that you can hard code the token to be used.  To use this
     * use the phpFlickr::setToken() function whenever you create an instance of 
     * the class.
     */
		
		function flickrsync_log($msg){
			error_log($msg."\n", 3, "/tmp/flickrsync.err");	
		}

		function flickrsync_vardump($var){
			ob_start();
			var_dump($var);
			flickrsync_log(ob_get_clean());
		}
		
flickrsync_log('chamando require_once phpflickr');
    require_once("./lib/phpflickr/phpFlickr.php");
flickrsync_log('require_once phpflickr chamado');  
    require_once("sensitive_data_wrapper.php");
    
    
flickrsync_log('instanciando phpflickr');   
    $f = new phpFlickr(FLICKR_API_KEY,FLICKR_API_SECRET, true);
flickrsync_log('instanciei phpflickr');

flickrsync_vardump($_SESSION);
flickrsync_vardump($_GET);

    if( ! empty( $_GET['frob'])){
flickrsync_log('achei um frob na variavel get:');
flickrsync_vardump($_GET['frob']);
    	flickrsync_log('getting token by frob');
    	$auth_token = $f->auth_getToken($_GET['frob']);
    	//$f->setToken(
    		
    	//);
    }
    
flickrsync_vardump($_SESSION);
flickrsync_vardump($_GET);
    
    //change this to the permissions you will need
    
	if( empty( $_SESSION['phpFlickr_auth_token']) ){
    	$f->auth("read");
	}else{
    	echo "Copy this token into your code: ";
    	var_dump( $_SESSION['phpFlickr_auth_token'] );
	}
?>
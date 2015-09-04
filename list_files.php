<?php
require_once('./sensitive_data_wrapper.php');
require_once('./lib/phpflickr/phpFlickr.php');


    $f = new phpFlickr(FLICKR_API_KEY,FLICKR_API_SECRET);
    
    //change this to the permissions you will need
   // $f->auth("read");
    
    echo "Copy this token into your code: " . $_SESSION['phpFlickr_auth_token'];


    
    echo 'listanddo flickr';
		//Parameterless searches have been disabled. Please use flickr.photos.getRecent instead.
		$photos = $this->phpFlickr->photos_search(array('user_id'=>FLICKR_USER_ID));
		//$photos = $this->phpFlickr->photos_getRecent();		

		
		
if( false === $photos ){
	print_r($this->phpFlickr->getErrorCode());
	echo "\n";
		print_r($this->phpFlickr->getErrorMsg());
		echo "\n";
}
		
		var_dump($photos);		
		print_r($photos);
echo ob_get_clean();
		exit;

?>
<?php
require_once('lib/phpFlickr/phpFlickr.php');

echo 'alou2';

class FlickrSync
{
	var $flickr;
	var $localPath;
	
	public function __construct( Flickr $flickr , LocalFiles $localPath ){
		$this->flickr = $flickr;
		$this->localPath = $localPath;
	}
	
	public function sync(){
		$files = $this->localPath->getIterator();

		foreach($files as $splFileInfo){
			if( ! $splFileInfo->isFile() ){ continue; }
			$localFile = new LocalFile( $splFileInfo );
			if( null === $this->buscarArquivoFlickr( $localFile ) ){
				$this->uploadLocalFile( $localFile );
			}else{
				echo 'o arquivo '.$localFile->getName().' já está no Flickr'."\n";
			}
		}
	}
	
	private function buscarArquivoFlickr( File $file ){
		foreach( $this->flickr->listFiles() as $flickrFile ){
			//echo 'comparing '.$file->getName().' with '.$flickrFile->getName()."\n";
			if( $this->fileEquals( $file, $flickrFile ) ){
				return $flickrFile;
			}
		}
		return null;
	}
	
	private function uploadLocalFile( File $f ){
		echo 'Favor fazer upload do arquivo local '.$f->getName()."\n";
	}
	
	// Only compares content (more expensive) if name and length are the same.
	// Caveat: doesn't find renamed files.
	private function fileEquals( File $file1, File $file2 ){
		return 
			$file1->getName() == $file2->getName() 
			&& $file1->getLength() == $file2->getLength() 
			&& $file1->getPartialContent() == $file2->getPartialContent();
	}
}


require_once('./sensitive_data_wrapper.php');
class Flickr
{
	function __construct(){
		$this->phpFlickr =  new phpFlickr(FLICKR_API_KEY, FLICKR_API_SECRET);
		
		/*from https://github.com/dan-coulter/phpflickr/blob/master/README.md
		 * [AUTHENTICATION]
		 * [...]
		 * This method will allow you to have the app authenticate to one specific account, no matter who 
		 * views your website. This is useful to display private photos or photosets (among other things).
		 * 
		 * First, you'll have to setup a callback script with Flickr. Once you've done that, edit line 12 of
		 * the included getToken.php file to reflect which permissions you'll need for the app. Then browse 
		 * to the page. Once you've authorized the app with Flickr, it'll send you back to that page which 
		 * will give you a token which will look something like this: 1234-567890abcdef1234 Go to the file 
		 * where you are creating an instance of phpFlickr (I suggest an include file) and after you've 
		 * created it set the token to use: $f->setToken(""); This token never expires, so you don't have to
		 * worry about having to login periodically.
		 */
	

		$token = $this->phpFlickr->auth_getToken('72157657586597168-310e08bfaea61a9f-29883364');
if( $token === false ){ echo 'erro buscando token'."\n";
		

	print_r($this->phpFlickr->getErrorCode());
	echo "\n";
		print_r($this->phpFlickr->getErrorMsg());
		echo "\n";


exit;}
echo 'token='.$token."\n";
var_dump($token);
		$this->phpFlickr->setToken($token['token']['_content']);
	}
	
	/** @return array<FlickrFile> */
	public function listFiles(){
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
		exit;
		
		return array(
			/*TODO*/
			new LocalFile( new \SplFileInfo('/cygdrive/d/teste.txt') )
		);
	
	}
}

interface File
{
	public function getName();
	public function getLength();
	public function getPartialContent();
	public function getContent();
}

class FlickrFile implements File
{
	private $url ;
	public function __construct( $url )
	{}
	public function getName(){}
	public function getLength(){}
	public function getEstimatedLength(){
		if( ! $this->isPhoto() ){
			return $this->getLength();
		}
		$pixels = $this->getOriginalHeight() * $this->getOriginalWidth();
		return $pixels * $this->getAverageBytesPerPixel();
	}
	private function getAverageBytesPerPixel(){
		return LocalFiles::getAverageBytesPerPixel();
	}
	private function getOriginalHeight(){
		/*TODO: from flickr API*/
	}
	private function getOriginalWidth(){
		/*TODO: from flickr API*/
	}	
	public function getPartialContent()
	{
		//TODO: all content for photos, 1 MB for video
	}
	public function getContent(){}
	private function requestHTTP_HEAD(){}
	private function requestHTTPContent(){}
}

class LocalFile implements File
{
	public function __construct( \SPLFileInfo $splFileInfo){
		$this->impl = $splFileInfo;
	}
	public function getName(){ return $this->impl->getFilename() ; }
	public function getLength(){ return $this->impl->getSize(); }
	public function getPartialContent()
	{
		//TODO: all content for photos, 1 MB for video
	}	
	public function getContent(){ return file_get_contents( $this->impl->getPath().'/'.$this->getName()); }
}

class LocalFiles
{
	private $localPath;
	private $averageBytesPerPixel = null;
	public function __construct($localPath)
	{
		$this->localPath = $localPath;	
	}
	public function getIterator(){
		return new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $this->localPath ), 
				RecursiveIteratorIterator::SELF_FIRST
			);	
	}
	/** TODO unstaticsize */
	public static function getAverageBytesPerPixel()
	{
		if( null === $this->averageBytesPerPixel ){
			$this->averageBytesPerPixel = $this->calculateAverageBytesPerPixel();
		}
	}
	private function calculateAverageBytesPerPixel()
	{
		$PROB_OF_INSPECTION = 10; /*percent*/
		$MAX_INSPECTED_FILES = 50; /*files*/
		$iterator = $this->getIterator();
		$inspectedFiles = 0;
		$pixels = 0;
		$bytes = 0;
		foreach( $iterator as $splFileInfo ){
			if( ( ! $splFileInfo->isFile() )
				|| $inspectedFiles > $MAX_INSPECTED_FILES 
				|| rand( 1, 100 ) > $PROB_OF_INSPECTION 
				)
			{ 
				continue; 
			}
			$pixels += $this->getPixelsCount($splFileInfo);
			$bytes += $splFileInfo->getSize();
			$inspectedFiles++;
		}
		return $bytes/$pixels;
	}
	private function getPixelsCount(SplFileInfo $file)
	{
		/*TODO*/
	}
}

$path=$argv[1];
//$path=$_GET['path'];
$flickrSync = 
	new FlickrSync(
		new Flickr(),
		new LocalFiles($path)
	);
$flickrSync->sync();
?>
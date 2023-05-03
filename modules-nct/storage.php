<?php

require_once(DIR_URL."image-vendor/vendor/autoload.php");
  
use Google\Cloud\Storage\StorageClient;

class storage
{
	private $projectId;
  	private $storage;

	function __construct()
	{
		putenv('GOOGLE_APPLICATION_CREDENTIALS='.DIR_URL.'credentials/av8db-306220-67e5578f7a63.json');
	    $this->projectId = 'av8db-306220';
        $this->storage = new StorageClient([
            'projectId' => $this->projectId
          ]);
	}

	public function upload_object($bucketName, $objectName, $source)
	{	
	    $storage = new StorageClient();
	    $file = fopen($source, 'r');
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->upload($file, [
	        'name' => $objectName
	    ]);
	    return $object;
	}


	public function getImageUrl($bucketName, $objectName){
	  return 'https://storage.googleapis.com/'.$bucketName.'/'.$objectName;
	  //return 'https://storage.cloud.google.com/'.$bucketName.'/'.$objectName;
	}

	function download_object($bucketName, $objectName, $destination)
	{
	    $storage = new StorageClient();
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->object($objectName);
	    $object->downloadToFile($destination);
	    return $object;
	}
}
?>
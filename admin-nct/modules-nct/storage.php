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
        $this->storage->registerStreamWrapper();
	}

	public function upload_object($bucketName, $objectName, $source,$store_path)
	{	
		$objname = $store_path.$objectName;
	    $storage = new StorageClient();
	    $file = fopen($source, 'r');
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->upload($file, [
	        'name' => $objname
	    ]);
	    return $object;
	}
	public function upload_objectBlob($bucketName, $objectName, $source,$store_path)
	{	
		$objname = $store_path.$objectName;
	    $storage = new StorageClient();
	    //$file = fopen($source, 'r');
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->upload($source, [
	        'name' => $objname
	    ]);
	    return $object;
	}

	public function upload_object1($bucketName, $objectName, $filePath,$crop_path)
	{
		$objname = $crop_path.$objectName;
	    $storage = new StorageClient();
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->upload(file_get_contents($filePath), [
		    'name' => $objname
		]);
	    return $object;
	}

	public function getImageUrl($bucketName, $objectName){
	  return 'https://storage.googleapis.com/'.$bucketName.'/'.$objectName;
	}
	public function getImageUrl1($bucketName, $objectName, $crop_path){
		// echo "string";
		// $objname = $crop_path.$objectName;
	 //    $storage = new StorageClient();
	 //    $bucket = $storage->bucket($bucketName);
	 //    $blob = $bucket.blob($objname)
	 //    if($blob.exists()){
	 //    	echo "yes";
	 //    }else{
	 //    	echo "no";
	 //    }
	  return 'https://storage.googleapis.com/'.$bucketName.'/'.$crop_path.$objectName;
	}
	function download_object($bucketName, $objectName, $destination)
	{
	    $storage = new StorageClient();
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->object($objectName);
	    $object1 = $object->downloadToFile($destination);
	    printf('Downloaded gs://%s/%s to %s' . PHP_EOL,$bucketName, $objectName, basename($destination));
	}

	function delete_object($bucketName, $objectName, $options = [])
	{
	    $storage = new StorageClient();
	    $objname = "temp_files/".$objectName;
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->object($objname);
	    $object->delete();
	    return true;
	}
	function delete_object1($bucketName, $objectName, $options = [],$delete_path)
	{
	    $storage = new StorageClient();
	    $objname = $delete_path.$objectName;
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->object($objname);
	    $object->delete();
	    return true;
	}
	function move_object($bucketName, $objectName, $newBucketName, $newObjectName)
	{
	    $storage = new StorageClient();
	    $bucket = $storage->bucket($bucketName);
	    $object = $bucket->object($objectName);
	    $object->copy($newBucketName, ['name' => $newObjectName]);
	    $object->delete();
	    return $newObjectName;
	}
}
?>
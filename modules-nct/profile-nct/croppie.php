<?php

require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.profile-nct.php");
require_once("storage.php");

if(isset($_POST['image']))
{
	$data = $_POST['image'];
	$image_array_1 = explode(";", $data);


	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);
	$src = "users/";

	$image_name = 'uploads_' . time() . '.png';
	
	$storage = new storage();
	$upload =file_put_contents($image_name, $data);
	
	$result = $storage->upload_object('av8db',$image_name,$upload,$src);
	$res = $storage->upload_object1('av8db','cropped'.$image_name,$upload,$src);
    $res12 = $storage->getImageUrl1('av8db','cropped'.$image_name,$src);
	
	//file_put_contents($image_name, $data);
	echo $image_name;
}
?>
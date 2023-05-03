<?php
$content = NULL;
require_once("../../includes-nct/config-nct.php");
/*include("class.projects-nct.php");
$module = 'projects-nct';


$mainObj = new projects($db, $module, 0);*/
require_once("class.profile-nct.php");
$path=DIR_UPD."temp_files/";
class CropAvatar extends Home {
  private $src;
  private $data;
  private $dst;
  private $type;
  private $extension;
  private $msg;
  public $which_types;
  function __construct($src, $data, $file,$which_types) {
  //parent::__construct();
   $this->path = $path=DIR_UPD."temp_files/";
  //delete_directory($path);
    $this->which_types=$which_types;
    $this -> setSrc($src);
   //$this -> get_which($which_types='123');
    $this -> setData($data);
    $this -> setFile($file);
    $this -> crop($this -> src, $this -> dst, $this -> data);
  }

  private function setSrc($src) {
    if (!empty($src)) {
      $type = exif_imagetype($src);

      if ($type) {
        $this -> src = $src;
        $this -> type = $type;
        $this -> extension = image_type_to_extension($type);
        $this -> setDst();
      }
    }
  }

  private function setData($data) {
    if (!empty($data)) {
      $this -> data = json_decode(stripslashes($data));
    }
  }

  private function setFile($file) {
    $errorCode = $file['error'];

    if ($errorCode === UPLOAD_ERR_OK) {
      $type = exif_imagetype($file['tmp_name']);

      if ($type) {
        $extension = image_type_to_extension($type);

        $path=DIR_UPD."temp_files/";
      //  delete_directory($path);
        if (!file_exists($path)) {
          mkdir($path, 0777, true);
        }
        
        $src = DIR_UPD."temp_files/".date('YmdHis') . '.original' . '.png';

        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

              if (file_exists($src)) {
                unlink($src);
              }

              $result = move_uploaded_file($file['tmp_name'], $src);

              if ($result) {
                $this -> src = $src;
                $this -> type = $type;
                $this -> extension = $extension;
                $this -> setDst();
              } else {
               $this -> msg = 'Failed to save file';
             }
       } else {
        $this -> msg = 'Please upload image with the following types: JPG, PNG, GIF';
      }
    } else {
      $this -> msg = 'Please upload image file';
    }
  } else {
    $this -> msg = $this -> codeToMessage($errorCode);
  }
}

public function setDst() {
  $this-> fname=date('YmdHis') . '.png';
  $this -> dst = DIR_UPD.'temp_files/th1_'.$this-> fname ;

}

private function crop($src, $dst, $data) {
  if (!empty($src) && !empty($dst) && !empty($data)) {
    switch ($this -> type) {
      case IMAGETYPE_GIF:
      $src_img = imagecreatefromgif($src);
      break;

      case IMAGETYPE_JPEG:
      $src_img = imagecreatefromjpeg($src);
      break;

      case IMAGETYPE_PNG:
      $src_img = imagecreatefrompng($src);
      break;
    }

    if (!$src_img) {
      $this -> msg = "Failed to read the image file";
      return;
    }

    $size = getimagesize($src);
      $size_w = $size[0]; // natural width
      $size_h = $size[1]; // natural height

      $src_img_w = $size_w;
      $src_img_h = $size_h;

      $degrees = $data -> rotate;

      // Rotate the source image
      if (is_numeric($degrees) && $degrees != 0) {
        // PHP's degrees is opposite to CSS's degrees
        $new_img = imagerotate( $src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127) );

        imagedestroy($src_img);
        $src_img = $new_img;

        $deg = abs($degrees) % 180;
        $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;

        $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
        $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);

        // Fix rotated image miss 1px issue when degrees < 0
        $src_img_w -= 1;
        $src_img_h -= 1;
      }

      $tmp_img_w = $data -> width;
      $tmp_img_h = $data -> height;
      $dst_img_w = $data -> width;
      $dst_img_h = $data -> height;

      $src_x = $data -> x;
      $src_y = $data -> y;

      if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
        $src_x = $src_w = $dst_x = $dst_w = 0;
      } else if ($src_x <= 0) {
        $dst_x = -$src_x;
        $src_x = 0;
        $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
      } else if ($src_x <= $src_img_w) {
        $dst_x = 0;
        $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
      }

      if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
        $src_y = $src_h = $dst_y = $dst_h = 0;
      } else if ($src_y <= 0) {
        $dst_y = -$src_y;
        $src_y = 0;
        $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
      } else if ($src_y <= $src_img_h) {
        $dst_y = 0;
        $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
      }

      // Scale to destination position and size
      $ratio = $tmp_img_w / $dst_img_w;
      $dst_x /= $ratio;
      $dst_y /= $ratio;
      $dst_w /= $ratio;
      $dst_h /= $ratio;

      $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);

      // Add transparent background to destination image
      imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
      imagesavealpha($dst_img, true);

      $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

      if ($result) {
        if (!imagepng($dst_img, $dst)) {
          $this -> msg = "Failed to save the cropped image file";
        }
      } else {
        $this -> msg = "Failed to crop the image file";
      }

      imagedestroy($src_img);
      imagedestroy($dst_img);
    }
  }

  private function codeToMessage($code) {
    $errors = array(
      UPLOAD_ERR_INI_SIZE =>'The uploaded file exceeds the upload_max_filesize directive in php.ini',
      UPLOAD_ERR_FORM_SIZE =>'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
      UPLOAD_ERR_PARTIAL =>'The uploaded file was only partially uploaded',
      UPLOAD_ERR_NO_FILE =>'No file was uploaded',
      UPLOAD_ERR_NO_TMP_DIR =>'Missing a temporary folder',
      UPLOAD_ERR_CANT_WRITE =>'Failed to write file to disk',
      UPLOAD_ERR_EXTENSION =>'File upload stopped by extension',
      );

    if (array_key_exists($code, $errors)) {
      return $errors[$code];
    }

    return 'Unknown upload error';
  }

  public function getResult() {
    return !empty($this -> data) ? $this -> dst : $this -> src;
  }

  public function getMsg() {
    return $this -> msg;
  }
  public function filename()
  {
    return $this-> fname;
  }


}

$crop = new CropAvatar(
  isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
  isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
  isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null,
  isset($_POST['which_types']) ? $_POST['which_types'] : null
  );
$response = array(
  'state'  => 200,
  'message' => $crop -> getMsg(),
  'result' => $crop -> getResult(),
  'filename' => $crop -> filename() ,
  'id' => $_POST['id'],
  );
//$image1 =  resizeImage($response['result'], $path . 'th1_' . $response['filename'],260,168);

   // echo $this->db."_".$db;
 $pro_pic_name=$crop -> filename();

 if($crop->which_types=='images'){
   // $_SESSION['temp_files']=$pro_pic_name;

    if($pro_pic_name !="" || $pro_pic_name !=NULL){
      $to_path=DIR_UPD."temp_files/th1_$pro_pic_name";
      $file_name= DIR_UPD_USERS;
     
     if(!file_exists($file_name)){
     mkdir($file_name,0777,true);
     } 
     
     //copy($to_path, $file_name.$value);
     $uploadDir = DIR_UPD_USERS.'/'.$sessUserId.'/';
     $image_resize_array = unserialize(USER_PROFILE_PICTURE_RESIZE_ARRAY);
     
     $image_url = $to_path;
   
     $image_name = GenerateThumbnail($image_url, $uploadDir, $image_url, $image_resize_array);
     
     if($image_name != '' && $image_name != 0)
     {
        $user_logo = $image_name;
       $old_profile_picture_name=getTableValue("tbl_users", "profile_picture_name", array("id" => $sessUserId));
          $profile_picture_name = $image_name;
          $affected_rows = $db->update("tbl_users", array("profile_picture_name" => $profile_picture_name,"date_updated" => date("Y-m-d H:i:s")), array("id" => $sessUserId))->affectedRows();
          if ($affected_rows) {if ($old_profile_picture_name != "") { unlink(DIR_UPD_USERS . $old_profile_picture_name);}}
          if ($affected_rows) {
              $response['status'] = true;
              $response['updated_profile_pic_src'] = getImageURL("user_profile_picture", $sessUserId, "th4");
              $response['success'] = LBL_PROFILE_UPDATED;
          }

      }       
        
    }
       
  }else if($crop->which_types=='cover_photo_user'){
    if($pro_pic_name !="" || $pro_pic_name !=NULL){
      $to_path=DIR_UPD."temp_files/th1_$pro_pic_name";
      $file_name= DIR_UPD_USERS_COVER;
     
     if(!file_exists($file_name)){
     mkdir($file_name,0777,true);
     } 
     
     
     $uploadDir = DIR_UPD_USERS_COVER.'/'.$sessUserId.'/';
     $destination=DIR_UPD_USERS_COVER.'/'.$sessUserId.'/'.$pro_pic_name;
     $destination1=DIR_UPD_USERS_COVER.'/'.$sessUserId.'/th1_'.$pro_pic_name;
    
     $image_url = $to_path;

    
     $image_resize_array=array(array("newWidth"=>250,"newHeight"=>80),array("newWidth"=>792,"newHeight"=>198));
    // $image_resize_array[0]=array("newWidth"=>250,"newHeight"=>80);
      $image_name = uploadImagewithResize($uploadDir,$destination,$to_path,$pro_pic_name,$image_resize_array);




     /*if($image_name != '' && $image_name != 0)
     {*/
        $user_logo = $image_name;
       $old_profile_picture_name=getTableValue("tbl_users", "cover_photo", array("id" => $sessUserId));
          $profile_picture_name = $pro_pic_name;
          $affected_rows = $db->update("tbl_users", array("cover_photo" => $profile_picture_name,"date_updated" => date("Y-m-d H:i:s")), array("id" => $sessUserId))->affectedRows();
          if ($affected_rows) {if ($old_profile_picture_name != "") { 
            unlink(DIR_UPD_USERS_COVER .$sessUserId."/".$old_profile_picture_name);
            unlink(DIR_UPD_USERS_COVER .$sessUserId.'/th1_'. $old_profile_picture_name);

          }}
          if ($affected_rows) {
              $response['status'] = true;
              $response['updated_profile_pic_src'] = getImageURL("user_cover_picture", $sessUserId, "th1");
              $response['success'] = LBL_PROFILE_UPDATED;
          }

     // }       
        
    }

  }else{
     $_SESSION['temp_slider']=$pro_pic_name;
  }
echo json_encode($response);
exit;

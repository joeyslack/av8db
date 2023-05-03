<?php
$content = NULL;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.profile-nct.php");
require_once("storage.php");

$path=DIR_UPD."temp_files/";
class CropAvatar extends Home {
  private $src;
  private $data;
  private $dst;
  private $type;
  public $extension;
  private $msg;
  public $new_data;

  public $which_types;  
  function __construct($src, $data, $file,$which_types) {

  //parent::__construct();
   $this->path = $path=DIR_UPD."temp_files/";
    $this->which_types=$which_types;
    $this -> setSrc($src);
    
    $this->new_data = json_decode(stripslashes($data));
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
        if (!file_exists($path)) {
          mkdir($path, 0777, true);
        }

        $img_name = date('YmdHis') . '.original' . '.png';
        $temp_src = "temp_files/".$img_name;
        $temp_src2 = "temp_files/";
        $get_main_img = '';

        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {
        
              $storage = new storage();
              
              if (!extension_loaded('imagick')) {
                  echo "imagick not installed...";
              } else {
                  $filename = $img_name;
                  $tempname = $file['tmp_name'];

                  $main_img = $storage->upload_object('av8db','',$tempname,$temp_src);
                  $get_main_img = $storage->getImageUrl1('av8db',$img_name,$temp_src2);
              }              
              
              if ($get_main_img) {
                $this -> src = $get_main_img;
                $this -> type = $type;
                $this -> extension = $extension;
                $this->  fname = $img_name;
                $this -> dst = $get_main_img;
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
  //$this-> fname=date('YmdHis') . '.png';
  //$this -> dst = 'temp_files/th1_'.$this-> fname ;
}

private function crop($src, $dst, $data) {
  global $photoFolder;
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

    $image_content = file_get_contents($src);
    $image = imagecreatefromstring($image_content);
    $width = imagesx($image);
    $height = imagesy($image);

      $size_w = $width; // natural width
      $size_h = $height; // natural height

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

$pro_pic_name=$crop->filename();
$main_url =$crop->getResult();

if($crop->which_types=='images'){
    if($pro_pic_name !="" || $pro_pic_name !=NULL){
      $storage = new storage();
      $src2 = "users-nct/".$sessUserId.'/';
      $crop_img_res = $resize_img = '';
      if ($sessUserId > 0) {
        $image_resize_array = unserialize(USER_PROFILE_PICTURE_RESIZE_ARRAY);
        $targetdir = $main_url;
        $my_image = $main_url;
        $im = new Imagick($my_image);
        $im->readImage($my_image);
        if ($crop->new_data->rotate > 0) {
            $im->rotateImage(new \ImagickPixel(), $crop->new_data->rotate); // This makes resulting image bigger
            $im->setImagePage($im->getImageWidth(), $im->getImageHeight(), 0, 0);
        }
        $im->cropImage($crop->new_data->width, $crop->new_data->height, $crop->new_data->x, $crop->new_data->y);
        $crop_img = $storage->upload_objectBlob('av8db',$pro_pic_name,$im->getImageBlob(),$src2);
        $crop_img_res = $storage->getImageUrl1('av8db',$pro_pic_name,$src2);

        $length = count($image_resize_array);

        for ($i = 0; $i < $length; $i++) {
            $im1 = new Imagick($crop_img_res);
            $im1->readImage($crop_img_res);
            $im1->resizeImage($image_resize_array[$i]['width'], $image_resize_array[$i]['height'], Imagick::FILTER_LANCZOS, 1);
            $resize_img = $storage->upload_objectBlob('av8db','th'.($i+1).'_'.$pro_pic_name,$im1->getImageBlob(),$src2);
            $im1->clear();
            $im1->destroy();
        }
        $resize_img_res = $storage->getImageUrl1('av8db','th1_'.$pro_pic_name,$src2);

        $im->clear();
        $im->destroy();
        $user_logo = $pro_pic_name;

        $old_profile_picture_name=getTableValue("tbl_users", "profile_picture_name", array("id" => $sessUserId));
        //delete image from bucket if old image is exists in bucket
       
        if ($old_profile_picture_name != '') {
          $main_img = $storage->getImageUrl1('av8db',$old_profile_picture_name,$src2);
          $is_main_img = getimagesize($main_img);
          if(!empty($is_main_img)){
            $del = $storage->delete_object1('av8db',$old_profile_picture_name,'',$src2);
          }
          $main_img_one = $storage->getImageUrl1('av8db','th1_'.$old_profile_picture_name,$src2);
          $is_main_img_one = getimagesize($main_img_one);
          if(!empty($is_main_img_one)){
            $del1 = $storage->delete_object1('av8db','th1_'.$old_profile_picture_name,'',$src2);
          }
          $main_img_two = $storage->getImageUrl1('av8db','th2_'.$old_profile_picture_name,$src2);
          $is_main_img_two = getimagesize($main_img_two);
          if(!empty($is_main_img_two)){
            $del2 = $storage->delete_object1('av8db','th2_'.$old_profile_picture_name,'',$src2);
          }
          $main_img_three = $storage->getImageUrl1('av8db','th3_'.$old_profile_picture_name,$src2);
          $is_main_img_three = getimagesize($main_img_three);
          if(!empty($is_main_img_three)){
            $del3 = $storage->delete_object1('av8db','th3_'.$old_profile_picture_name,'',$src2);
          }
          $main_img_four = $storage->getImageUrl1('av8db','th4_'.$old_profile_picture_name,$src2);
          $is_main_img_four = getimagesize($main_img_four);
          if(!empty($is_main_img_four)){
            $del4 = $storage->delete_object1('av8db','th4_'.$old_profile_picture_name,'',$src2);
          }
          $main_img_five = $storage->getImageUrl1('av8db','th5_'.$old_profile_picture_name,$src2);
          $is_main_img_five = getimagesize($main_img_five);
          if(!empty($is_main_img_five)){
            $del5 = $storage->delete_object1('av8db','th5_'.$old_profile_picture_name,'',$src2);
          }
        }

        $profile_picture_name = $pro_pic_name;

        $affected_rows = $db->update("tbl_users", array("profile_picture_name" => $profile_picture_name,"date_updated" => date("Y-m-d H:i:s")), array("id" => $sessUserId))->affectedRows();
           
        $result1 = $storage->getImageUrl1('av8db','th4_'.$pro_pic_name,$src2);
        if ($affected_rows) {
            $del = $storage->delete_object('av8db',$pro_pic_name,'');
            $first_name = getTableValue('tbl_users','first_name',array('id'=>$sessUserId));
            $last_name = getTableValue('tbl_users','last_name',array('id'=>$sessUserId));
            $user_name = $first_name . ' ' . $last_name;
            $response['status'] = true;
            $response['updated_profile_pic_src'] = $result1;
            $response['user_name'] = $user_name;
            $response['success'] = LBL_PROFILE_UPDATED;
        }
      }else{
        $del = $storage->delete_object('av8db',$pro_pic_name,'');
        $response['status'] = false;
      }       
    }       
}else if($crop->which_types=='cover_photo_user'){
    if($pro_pic_name !="" || $pro_pic_name !=NULL){
        $storage = new storage();
        $src2 = "user_cover-nct/".$sessUserId.'/';
        $crop_img_res = $resize_img = '';
        if ($sessUserId > 0) {
            $image_resize_array=array(array("newWidth"=>250,"newHeight"=>80),array("newWidth"=>792,"newHeight"=>198));
        
            $targetdir = $main_url;
            $my_image = $main_url;
            $im = new Imagick($my_image);
            $im->readImage($my_image);
            $im->cropImage($crop->new_data->width, $crop->new_data->height, $crop->new_data->x, $crop->new_data->y); 

            $crop_img = $storage->upload_objectBlob('av8db',$pro_pic_name,$im->getImageBlob(),$src2);
            $crop_img_res = $storage->getImageUrl1('av8db',$pro_pic_name,$src2);

            if ($crop->new_data->rotate > 0) {
                $im->rotateimage('', $crop->new_data->rotate);
                $crop_img = $storage->upload_objectBlob('av8db',$pro_pic_name,$im->getImageBlob(),$src2);
                $crop_img_res = $storage->getImageUrl1('av8db',$pro_pic_name,$src2);
            }
            $length = count($image_resize_array);
            for ($i = 0; $i < $length; $i++) {
                $im1 = new Imagick($crop_img_res);
                $im1->readImage($crop_img_res);
               
                $im1->resizeImage($image_resize_array[$i]['newWidth'], $image_resize_array[$i]['newHeight'], Imagick::FILTER_LANCZOS, 1);
                $resize_img = $storage->upload_objectBlob('av8db','th'.($i+1).'_'.$pro_pic_name,$im1->getImageBlob(),$src2);
                $im1->clear();
                $im1->destroy();
            }   
             
            $resize_img_res = $storage->getImageUrl1('av8db','th1_'.$pro_pic_name,$src2);
            
            $im->clear();
            $im->destroy();
            $user_logo = $pro_pic_name;

            $old_profile_picture_name=getTableValue("tbl_users", "cover_photo", array("id" => $sessUserId));
            //delete image from bucket if old image is exists in bucket
           
            if ($old_profile_picture_name != '') {
              $main_img = $storage->getImageUrl1('av8db',$old_profile_picture_name,$src2);
              $is_main_img = getimagesize($main_img);
              if(!empty($is_main_img)){
                $del = $storage->delete_object1('av8db',$old_profile_picture_name,'',$src2);
              }
              $main_img_one = $storage->getImageUrl1('av8db','th1_'.$old_profile_picture_name,$src2);
              $is_main_img_one = getimagesize($main_img_one);
              if(!empty($is_main_img_one)){
                $del1 = $storage->delete_object1('av8db','th1_'.$old_profile_picture_name,'',$src2);
              }
              $main_img_two = $storage->getImageUrl1('av8db','th2_'.$old_profile_picture_name,$src2);
              $is_main_img_two = getimagesize($main_img_two);
              if(!empty($is_main_img_two)){
                $del2 = $storage->delete_object1('av8db','th2_'.$old_profile_picture_name,'',$src2);
              }
            }
            $profile_picture_name = $pro_pic_name;

            $affected_rows = $db->update("tbl_users", array("cover_photo" => $profile_picture_name,"date_updated" => date("Y-m-d H:i:s")), array("id" => $sessUserId))->affectedRows();
               
            $result1 = $storage->getImageUrl1('av8db','th1_'.$pro_pic_name,$src2);
            if ($affected_rows) {
                $del = $storage->delete_object('av8db',$pro_pic_name,'');

                $response['status'] = true;
                $response['updated_profile_pic_src'] = $result1;
                $response['success'] = LBL_PROFILE_UPDATED;
            }
        }else{
            $del = $storage->delete_object('av8db',$pro_pic_name,'');
            $response['status'] = false;
        }        
    }
  }else{
     $_SESSION['temp_slider']=$pro_pic_name;
  }
echo json_encode($response);
exit;
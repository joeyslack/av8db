<?php
$content = NULL;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once(DIR_MOD . 'common_storage.php');
$path=DIR_UPD."temp_files/";
class CropAvatar extends Home {
  private $src;
  private $data;
  private $dst;
  private $type;
  private $extension;
  private $msg;
  public $which_types;
  public $new_data;
  function __construct($src, $data, $file,$which_types) {
  
   $this->path = $path=DIR_UPD."temp_files/";
  //delete_directory($path);
    $this->which_types=$which_types;
    $this -> setSrc($src);
   //$this -> get_which($which_types='123');
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
      //  delete_directory($path);
        if (!file_exists($path)) {
          mkdir($path, 0777, true);
        }

        // $src = DIR_UPD."temp_files/".date('YmdHis') . '.original' . '.png';
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
                $this ->src = $get_main_img;
                $this ->type = $type;
                $this ->extension = $extension;
                $this ->fname = $img_name;
                $this ->dst = $get_main_img;
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
  // $this-> fname=date('YmdHis') . '.png';
  // $this -> dst = DIR_UPD.'temp_files/th1_'.$this-> fname ;

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

$main_url =$crop->getResult();
$pro_pic_name=$crop -> filename();

$crop_img_res = '';
$edit_company_logo = new storage();

$company_logo = DIR_NAME_COMPANY_LOGOS."/";
$company_banner_img = DIR_NAME_COMPANY_BANNER_IMAGES."/";

$im = new Imagick($main_url);
$im->readImage($main_url);
$im->cropImage($crop->new_data->width, $crop->new_data->height, $crop->new_data->x, $crop->new_data->y);

if($crop->which_types=='images'){
  $crop_img = $edit_company_logo->upload_objectBlob('av8db',$pro_pic_name,$im->getImageBlob(),$company_logo);
  $crop_img_res = $edit_company_logo->getImageUrl1('av8db',$pro_pic_name,$company_logo);
  if ($crop->new_data->rotate > 0) {
      $im->rotateimage('', $crop->new_data->rotate);
      $crop_img = $edit_company_logo->upload_objectBlob('av8db',$pro_pic_name,$im->getImageBlob(),$company_logo);
      $crop_img_res = $edit_company_logo->getImageUrl1('av8db',$pro_pic_name,$company_logo);
  }
  $_SESSION['temp_files']=$pro_pic_name;
  $_SESSION['main_url']=$crop_img_res;
}else{
  $crop_img = $edit_company_logo->upload_objectBlob('av8db',$pro_pic_name,$im->getImageBlob(),$company_banner_img);
  $crop_img_res = $edit_company_logo->getImageUrl1('av8db',$pro_pic_name,$company_banner_img);
  if ($crop->new_data->rotate > 0) {
      $im->rotateimage('', $crop->new_data->rotate);
      $crop_img = $edit_company_logo->upload_objectBlob('av8db',$pro_pic_name,$im->getImageBlob(),$company_banner_img);
      $crop_img_res = $edit_company_logo->getImageUrl1('av8db',$pro_pic_name,$company_banner_img);
  }
 $_SESSION['temp_slider']=$pro_pic_name;
 $_SESSION['slider_main_url']=$crop_img_res;
}
echo json_encode($response);
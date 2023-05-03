<?php
$content = NULL;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.profile-nct.php");
require_once("storage.php");
$_SESSION['user_id'] = $_POST['user_id'];

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
  function __construct($src, $data, $file,$which_types,$user_id) {
  //parent::__construct();
   $this->path = $path=DIR_UPD."temp_files/";
    $this->which_types=$which_types;
    $this->user_id=$user_id;
    $_SESSION['user_id'] = $user_id;
    $this -> setSrc($src);
    
    $this->new_data = json_decode(stripslashes($data));
    $this -> setData($data);
    $this -> setFile($file);
    $this -> crop($this -> src, $this -> dst, $this -> data);
    $_SESSION['user_id'] = $user_id;
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
                //$del = $storage->delete_object('av8db',$img_name,'');
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
  $_SESSION['user_id'] = $this->user_id;
 
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
    $_SESSION['user_id'] = $this->user_id;
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
  isset($_POST['which_types']) ? $_POST['which_types'] : null,
  isset($_POST['user_id']) ? $_POST['user_id'] : null
  );
$response = array(
  'state'  => 200,
  'message' => $crop -> getMsg(),
  'result' => $crop -> getResult(),
  'filename' => $crop -> filename() ,
  'id' => $_POST['id'],
  'crop_data' => $crop
  );

$pro_pic_name=$crop->filename();
$main_url =$crop->getResult();

$_SESSION['temp_slider']=$pro_pic_name;
$_SESSION['main_url']=$main_url;
echo json_encode($response);
exit;
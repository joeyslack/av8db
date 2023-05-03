<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.sitesetting-nct.php");

$objPost = new stdClass();

$winTitle = 'Site Settings - ' . SITE_NM;
$headTitle = 'Site Settings';

$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    "author" => SITE_NM));

$module = 'sitesetting-nct';
$breadcrumb = array("Site Settings");

if (isset($_FILES) && !empty($_FILES)) {
    //echo "<pre>";print_r($_FILES);exit;
    foreach ($_FILES as $a => $b) {
        $selWhere = array('id' => $a);
        
        $type1Sql = $db->select("tbl_site_settings", "*", $selWhere)->results();
        //echo "<pre>";print_r($type1Sql);exit;
        foreach ($type1Sql as $c => $b) {
            $type1 = $b["type"];
            $constant = $b["constant"];
        }

        if ($type1 == "filebox") {

            $type = $_FILES[$a]["type"];
            $fileName = $_FILES[$a]["name"];
            $TmpName = $_FILES[$a]["tmp_name"];
            if ($type == "image/jpeg" || $type == "image/png" || $type == "image/gif" || $type == "image/x-png" || $type == "image/jpg" || $type == "image/x-png" || $type == "image/x-jpeg" || $type == "image/pjpeg" || $type == "image/x-icon") {
                
                if($constant == "SITE_FAVICON") {
                    $height_width_array = array('height' => 20, 'width' => 20);
                } else {
                    $height_width_array = array('height' => 130, 'width' => 110);
                }

                include 'storage.php';

                $site_logo_storage1 = new storage();
                $img_name = date('YmdHis') . '.original' . '.png';
                $temp_src = "site-images-nct/".$img_name;
                $temp_src2 = "site-images-nct/";
                $get_main_img = '';
                $tempname = $TmpName;
                $main_img = $site_logo_storage1->upload_object('av8db','',$tempname,$temp_src);
                $get_main_img = $site_logo_storage1->getImageUrl1('av8db',$img_name,$temp_src2);
                $length = count($height_width_array);
                
                if (!extension_loaded('imagick')) {
                    echo "imagick not installed...";
                }else{
                    $im1 = new Imagick($get_main_img);
                    for ($i = 0; $i < $length; $i++) {
                        $im1->readImage($get_main_img);
                        $im1->resizeImage($height_width_array['width'], $height_width_array['height'], Imagick::FILTER_LANCZOS, 1);
                        $resize_img = $site_logo_storage1->upload_objectBlob('av8db','th'.($i+1).'_'.$img_name,$im1->getImageBlob(),$temp_src2);
                        $im1->clear();
                        $im1->destroy();
                    }
                }
                $fileName = $img_name;
                // $fileName = GenerateThumbnail($fileName, DIR_THEME_IMG, $TmpName, array($height_width_array));
                
                $dataArr = array("value" => $fileName);
                $dataWhere = array("id" => $a);
                $db->update('tbl_site_settings', $dataArr, $dataWhere);
            }
        }
    }
}
if (isset($_POST["submitSetForm"])) {
    extract($_POST);
    foreach ($_POST as $k => $v) {
        if ((int) $k) {
            //if ($v != "") {
                $v = closetags($v);
                $sData = array("value" => filtering($v, 'input'));
                $sWhere = array("id" => $k);
                $db->update("tbl_site_settings", $sData, $sWhere);
                if ($k == 2) {
                    $data = array("uEmail" => $v);
                    $where = array("id" => "1", "adminType" => "s");
                    $db->update("tbl_admin", $data, $where);
                }
            //}
        }
    }
    $_SESSION["toastr_message"] = disMessage(array('type'=>'suc','var'=>'Site settings has been edited successfully.'));
    redirectPage(SITE_ADM_MOD . $module);
}

chkPermission($module);

$objSiteSetting = new SiteSetting();

$pageContent = $objSiteSetting->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");

?>
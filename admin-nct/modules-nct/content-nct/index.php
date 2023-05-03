<?php

$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");
require_once("class.content-nct.php");
$module = "content-nct";
$table = "tbl_content";

$styles = array(array("data-tables/DT_bootstrap.css", SITE_ADM_PLUGIN),
    array("bootstrap-switch/css/bootstrap-switch.min.css", SITE_ADM_PLUGIN));

$scripts = array("core/datatable.js",
    array("data-tables/jquery.dataTables.js", SITE_ADM_PLUGIN),
    array("data-tables/DT_bootstrap.js", SITE_ADM_PLUGIN),
    array("bootstrap-switch/js/bootstrap-switch.min.js", SITE_ADM_PLUGIN));

chkPermission($module);
$Permission = chkModulePermission($module);

$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    'author' => AUTHOR));
$breadcrumb = array("Content");

$id = isset($_GET["id"]) ? (int) trim($_GET["id"]) : 0;
$postType = isset($_POST["type"]) ? trim($_POST["type"]) : '';
$type = isset($_GET["type"]) ? trim($_GET["type"]) : $postType;

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Content';
$winTitle = $headTitle . ' - ' . SITE_NM;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $insArr = array();
        extract($_POST);
        
        $objPost->id = isset($id) ? $id : '';
        $objPost->pageTitle = isset($pageTitleValue[1]) ? $pageTitleValue[1] : '';
        $objPost->metaKeyword = isset($metaKeywordValue[1]) ? $metaKeywordValue[1] : '';
        $objPost->metaDesc = isset($metaDescValue[1]) ? $metaDescValue[1] : '';
        $linkType=($linkType=="url")?'u':'p';

        $objPost->page_or_url = isset($linkType) ? $linkType : '';
        $objPost->page_url = isset($url) ? $url : '';
        //$objPost->metaDesc = isset($metaDesc) ? $metaDesc : '';
       $objPost->pageDesc = isset($pageDescValue[1]) ? $pageDescValue[1] : '';
        $objPost->isActive = isset($isActive) ? $isActive : 'n';
        $objPost->show_in_navigation = isset($show_in_navigation) ? 'y' : 'n';

     
        if($type == 'edit' && $id > 0){
                if(in_array('status',$Permission)){
                        $insArr['isActive'] = $objPost->isActive;
                }
                $insArr['page_or_url'] = $objPost->page_or_url;
                $insArr['page_url']  = $objPost->page_url;
                $insArr['show_in_navigation']=$objPost->show_in_navigation;
                $insArr['pageTitle'] = $objPost->pageTitle;
                $insArr['metaKeyword'] = $objPost->metaKeyword;
                $insArr['metaDesc'] = $objPost->metaDesc;
                $insArr['pageDesc'] = $objPost->pageDesc;
                if(in_array('edit',$Permission)){
                        $counter = 1;
                        foreach($pageTitleValue as $k=>$v){
                                $insArr['pageTitle_'.$k] = ($v);
                        }
                        foreach($metaKeywordValue as $k=>$v){
                                $insArr['metaKeyword_'.$k] = ($v);
                        }
                        foreach($metaDescValue as $k=>$v){
                                $insArr['metaDesc_'.$k] = ($v);
                        }
                        foreach($pageDescValue as $k=>$v){
                                $insArr['pageDesc_'.$k] = ($v);
                        }
                        $db->update($table,$insArr,array("pId"=>$id));
                        $activity_array = array("id"=>$id,"module"=>$module,"activity"=>'edit');
                        add_admin_activity($activity_array);
                        
                        $_SESSION["toastr_message"] = disMessage(array('type'=>'suc','var'=>'recEdited'));
                }else{
                        $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type'=>'suc','var'=>'NoPermission'));
                }
        }else{

                if(in_array('add',$Permission)){
                        
                        $insArr['pageTitle'] = $objPost->pageTitle;
                        $insArr['metaKeyword'] = $objPost->metaKeyword;
                        $insArr['metaDesc'] = $objPost->metaDesc;
                        $insArr['page_or_url'] = $objPost->page_or_url;
                        $insArr['page_url'] = $objPost->page_url;
                        $insArr['pageDesc'] = $objPost->pageDesc;
                        $insArr['show_in_navigation']=$objPost->show_in_navigation;
                        $insArr['page_slug'] = Slug($objPost->pageTitle);
                        
                        if(in_array('status',$Permission)){
                                $insArr['isActive'] = $objPost->isActive;
                        }
                        $insArr['createdDate']  = date('Y-m-d H:i:s');
                        if((int)getTableValue($table,'pId',array('pageTitle'=>$insArr['pageTitle'])) == ''){

                                foreach($pageTitleValue as $k=>$v){
                                        $insArr['pageTitle_'.$k] = ($v);
                                }
                                foreach($metaKeywordValue as $k=>$v){
                                        $insArr['metaKeyword_'.$k] = ($v);
                                }
                                foreach($metaDescValue as $k=>$v){
                                        $insArr['metaDesc_'.$k] = ($v);
                                }
                                foreach($pageDescValue as $k=>$v){
                                        $insArr['pageDesc_'.$k] = ($v);
                                }
                                 
                                $insertedId = $db->insert($table,$insArr)->getLastInsertId();
                                $activity_array = array("id"=>$insertedId,"module"=>$module,"activity"=>'add');
                                add_admin_activity($activity_array);
                                $_SESSION["toastr_message"] = disMessage(array('type'=>'suc','var'=>'recAdded'));
                        }else{
                                $_SESSION["toastr_message"] = disMessage(array('type'=>'err','var'=>'recExist'));
                        }
                }else{
                        $toastr_message = $_SESSION["toastr_message"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
                }
        }
        redirectPage(SITE_ADM_MOD.$module);
}
$objContent = new Content($module);
$pageContent = $objContent->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");

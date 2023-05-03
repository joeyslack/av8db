<?php
	$reqAuth=true;
	require_once(DIR_URL."includes-nct/config-nct.php");
	require_once("class.homepage_statics-nct.php");
	$module = "homepage_statics-nct";
	$table = "tbl_homepage_statics";
	$styles = array(array("data-tables/DT_bootstrap.css",SITE_ADM_PLUGIN),
					array("bootstrap-switch/css/bootstrap-switch.min.css",SITE_ADM_PLUGIN));
	$scripts= array("core/datatable.js",
					array("data-tables/jquery.dataTables.js",SITE_ADM_PLUGIN),
					array("data-tables/DT_bootstrap.js",SITE_ADM_PLUGIN),
					array("bootstrap-switch/js/bootstrap-switch.min.js",SITE_ADM_PLUGIN));
	chkPermission($module);
	$Permission = chkModulePermission($module);
	$metaTag = getMetaTags(array("description"=>"Admin Panel",
			"keywords"=>'Admin Panel',
			'author'=>AUTHOR));
	$breadcrumb = array("Home Page Statics");
	$id = isset($_GET["id"]) ? (int)trim($_GET["id"]) : 0;
	$postType = isset($_POST["type"])?trim($_POST["type"]):'';
	$type = isset($_GET["type"])?trim($_GET["type"]):$postType;
	$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage').' Home Page Statics';
	$winTitle = $headTitle.' - '.SITE_NM;
	if(isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

		$insArr = array();
		extract($_POST);
		$objPost->id = isset($id) ? $id : '';
		//$objPost->type_name = isset($type_name) ? $type_name : '';
		$objPost->value = isset($value) ? $value : '0';
		$objPost->status = isset($status) ? $status : 'n';
		if($type == 'edit' && $id > 0){
			if(in_array('status',$Permission)){
				$insArr['status']	= $objPost->status;
			}
			$insArr['value']	= $objPost->value;
			$insArr['created_date']	= date('Y-m-d H:i:s');
			if(in_array('edit',$Permission)){
				
				$db->update($table,$insArr,array('id'=>$id));
				$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'edit');
				add_admin_activity($activity_array);
				$_SESSION["msgType"] = disMessage(array('type'=>'suc','var'=>'recEdited'));
			}else{
				$msgType = $_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
			}
		} else {
			if(in_array('add',$Permission)){
				
				$insArr['value']	= $objPost->value;
				if(in_array('status',$Permission)){
					$insArr['status']	= $objPost->status;
				}
				$insArr['created_date']	= date('Y-m-d H:i:s');
				$insArr['ipaddress'] = get_ip_address();
				if((int)getTableValue($table,'id',array('type'=>$insArr['type'])) == ''){
										$insertedId = $db->insert($table,$insArr)->getLastInsertId();
					$activity_array = array("id"=>$insertedId,"module"=>$module,"activity"=>'add');
					add_admin_activity($activity_array);
					$_SESSION["msgType"] = disMessage(array('type'=>'suc','var'=>'recAdded'));
				}
				else{
					$_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'recExist'));
				}
			}else{
				$msgType = $_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
			}
		}
		redirectPage(SITE_ADM_MOD.$module);
	}
	$objContent = new HomePageStatics($module);
	$pageContent = $objContent->getPageContent();
	require_once(DIR_ADMIN_TMPL."parsing-nct.tpl.php");
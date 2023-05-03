<?php
	$reqAuth=true;
	require_once(DIR_URL."includes-nct/config-nct.php");
	require_once("class.constant-nct.php");
	$module = "constant-nct";
	$table = "tbl_constant";
	chkPermission($module);
	$Permission = chkModulePermission($module);
	$styles = array(array("data-tables/DT_bootstrap.css",SITE_ADM_PLUGIN),
					array("bootstrap-switch/css/bootstrap-switch.min.css",SITE_ADM_PLUGIN));
	$scripts= array("core/datatable.js",
					array("data-tables/jquery.dataTables.js",SITE_ADM_PLUGIN),
					array("data-tables/DT_bootstrap.js",SITE_ADM_PLUGIN),
					array("bootstrap-switch/js/bootstrap-switch.min.js",SITE_ADM_PLUGIN));
	$metaTag = getMetaTags(array("description"=>"Admin Panel",
			"keywords"=>'Admin Panel',
			"author"=>SITE_NM));
	$id = isset($_GET["id"]) ? (int)trim($_GET["id"]) : 0;
	$postType = isset($_POST["type"])?trim($_POST["type"]):'';
	$type = isset($_GET["type"])?trim($_GET["type"]):$postType;
	$ctypeTxt = isset($_REQUEST["ctype"])?trim($_REQUEST["ctype"]):"f";
	$ctype = $ctypeTxt == 'pages' ? 't' : ($ctypeTxt == 'messages' ? 'm' : 'f' );
	$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage ').' Language Constants';
	$winTitle = $headTitle.' - '.SITE_NM;

	$breadcrumb = array($headTitle);

	if(isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
		extract($_POST);
		$objPost->constantName = isset($constantName) ? $constantName : '';
		if($type == 'edit' && $id > 0){
			if(in_array('edit',$Permission)){
				$counter = 1;
				foreach($constantValue as $k=>$v){
					$qrySel = 'SELECT id FROM tbl_constant WHERE ((id = ? OR subId = ?) AND languageId = ?)';
					$q = $db->pdoQuery($qrySel,array($id,$id,$k));
					$qrysel1 = $q->result();
					$numRows = $q->affectedRows();
					if($numRows>0){
						$objPost->constantValue = ($v);
						$val=(trim(addslashes($v)));
						$post = mb_convert_encoding($val, 'HTML-ENTITIES', 'UTF-8');
						$db->update($table, array('constantValue'=>$post), array("id"=>$qrysel1['id']));
					} else {
						$objPost->createdDate = date('Y-m-d H:i:s');
						$objPost->subId = ($counter==1) ? '0' : $id;
						$objPost->languageId = $k;
						$objPost->constantValue = ($v);
						$objPost->type = $_GET['ctype'];

						$val=(trim(addslashes($v)));
						$post = mb_convert_encoding($val, 'HTML-ENTITIES', 'UTF-8');
						
						$objPost->constantName = getTableValue("tbl_constant","constantName",array("id"=>$id));
						$valArray = array("languageId"=>$k,"subId"=>$objPost->subId,"constantName"=>$objPost->constantName,"constantValue"=>($post));
						$db->insert($table, $valArray);
					}
					$counter++;
				}
				$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'edit');
				add_admin_activity($activity_array);
				$_SESSION["msgType"] = disMessage(array('type'=>'suc','var'=>'recEdited'));
			}else{
				$msgType = $_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
			}
		} else {
			if(in_array('add',$Permission)){
				if($db->count($table,array("constantName"=>$objPost->constantName))==0){
					$counter = 1;
					foreach($constantValue as $k=>$v){
						$objPost->subId = ($counter==1) ? '0' : $counstantId;
						$objPost->languageId = $k;
						$val=(trim(addslashes($v)));
						$objPost->constantValue = mb_convert_encoding($val, 'HTML-ENTITIES', 'UTF-8');

						if($_GET['ctype']=='labels') {
							$objPost->type = 'f';
						}
						else if($_GET['ctype']=='messages') {
							$objPost->type = 'm';
						}
						else if($_GET['ctype']=='pages') {
							$objPost->type = 't';
						}
						$objPost->createdDate = date('Y-m-d H:i:s');
						$valArray = array("languageId"=>$k,"subId"=>$objPost->subId,"constantName"=>$objPost->constantName,"constantValue"=>$objPost->constantValue,"created_date"=>$objPost->createdDate);
						$insertId = $db->insert($table, $valArray)->getLastInsertId();
						$counstantId = ($counter==1)?$insertId : $counstantId;
						$counter++;
					}
					$activity_array = array("id"=>$insertId,"module"=>$module,"activity"=>'add');
					add_admin_activity($activity_array);
					$_SESSION["msgType"] = disMessage(array('type'=>'suc','var'=>'recAdded'));
				}else{
					$_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'recExist'));
				}
			}else{
				$msgType = $_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
			}
		}
		makeConstantFile();
		redirectPage($_SERVER['REQUEST_URI']);
	}
	$constObj = new Constant($id=0,array(),$type='langArray');
	$pageContent = $constObj->getPageContent();
	require_once(DIR_ADMIN_TMPL."parsing-nct.tpl.php");
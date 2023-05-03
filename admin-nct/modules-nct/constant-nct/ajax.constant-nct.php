<?php
	$content = '';
	require_once(DIR_URL."includes-nct/config-nct.php");
	if($adminUserId == 0){die('Invalid request');}
	include("class.constant-nct.php");
	$module = 'constant-nct';
	$table = 'tbl_constant';
	$action = isset($_GET["action"]) ? trim($_GET["action"]) : (isset($_POST["action"]) ? trim($_POST["action"]) : 'datagrid');
	$id = isset($_GET["id"]) ? trim($_GET["id"]) : (isset($_POST["id"]) ? trim($_POST["id"]) : 0);
	$value = isset($_POST["value"]) ? trim($_POST["value"]) : isset($_GET["value"]) ? trim($_GET["value"]) : '';
	$page = isset($_POST['iDisplayStart']) ? intval($_POST['iDisplayStart']) : 0;
	$rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
	$sort = isset($_POST["iSortTitle_0"]) ? $_POST["iSortTitle_0"] : NULL;
	$order = isset($_POST["sSortDir_0"]) ? $_POST["sSortDir_0"] : NULL;
	$chr = isset($_POST["sSearch"]) ? $_POST["sSearch"] : NULL;
	$sEcho = isset($_POST['sEcho']) ? $_POST['sEcho'] : 1;
	$langId = isset($_POST['langId']) ? $_POST['langId'] : 1;
	$ctype = isset($_POST['ctype']) ? $_POST['ctype'] : 'f';
	extract($_GET);
	$searchArray = array("page"=>$page, "rows"=>$rows, "sort"=>$sort, "order"=>$order, "offset"=>$page, "chr"=>$chr, 'sEcho' =>$sEcho,'langId'=>$langId,'ctype'=>$ctype);
	chkPermission($module);
	$Permission=chkModulePermission($module);
	if($action == "updateStatus"  && in_array('status',$Permission)) {
		$setVal = array('status'=>($value == 'a' ? 'a' : 'd'));
		$db->update($table,$setVal,array("id"=>$id));
		echo json_encode(array('type'=>'success','Record '.($value == 'a' ? 'activated ' : 'deactivated ').'successfully'));
		$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'status',"action"=>$value);
		add_admin_activity($activity_array);
		exit;
	}else if($action == "export_csv") {
		$qryRes=$db->pdoQuery("SELECT * FROM tbl_language WHERE status='a'")->results();
		$language_array = array("Constant Name");
		$constant_array = array();
		$i=0;
		$totalqryRes=$db->pdoQuery("SELECT * FROM tbl_language WHERE status='a'")->affectedRows();
		if($totalqryRes > 0){
			foreach($qryRes as $fetchRes){
				$language_array=array_merge($language_array,array($fetchRes['languageName']));
				$qryRes_constant=$db->pdoQuery("SELECT * FROM tbl_constant as c LEFT JOIN tbl_language as l ON c.languageId=l.id WHERE l.id='".$fetchRes['id']."'")->results();
				//$j=0;
				if($i==0){
					foreach($qryRes_constant as $fetchRes_constant){
						$constant_array = array();
						/*if($i==0){*/
							$constant_array=array($fetchRes_constant['constantName'],stripslashes(trim($fetchRes_constant['constantValue'])));
							$final_constant_array[]=$constant_array;
						/*}else{
							$final_constant_array[$j]=array_merge($final_constant_array[$j],array(stripslashes(trim($fetchRes_constant['constantValue']))));
						}
						$j++;*/
					}
				}else{
					for($j=0;$j<count($final_constant_array);$j++){
						foreach($qryRes_constant as $fetchRes_constant){
							if($final_constant_array[$j][0]==$fetchRes_constant['constantName']){
								$final_constant_array[$j]=array_merge($final_constant_array[$j],array(stripslashes(trim($fetchRes_constant['constantValue']))));
							}
						}
					}
				}
				$i++;
			}
			$final_result = array($language_array);
			foreach($final_constant_array as $k=>$v){
				$final_result=array_merge($final_result,array($v));
			}
			convert_to_csv($final_result, 'constant.csv', ',');
			exit;
		}else{
			$_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'NRF'));
			echo '<script type="text/javascript">
				window.location.href="'.SITE_ADM_MOD.$module.'/";
			</script>';
		}
	}
	else if($action == "export_excel" && in_array('export',$Permission)) {
		$qryRes=$db->pdoQuery("SELECT * FROM tbl_language WHERE status='a'")->results();
		$language_array = array("Constant Name");
		$constant_array = array();
		$i=0;
		$totalqryRes=$db->pdoQuery("SELECT * FROM tbl_language WHERE status='a'")->affectedRows();
		if($totalqryRes > 0){
			foreach($qryRes as $fetchRes){
				$language_array=array_merge($language_array,array($fetchRes['languageName']));
				$qryRes_constant=$db->pdoQuery("SELECT * FROM tbl_constant as c LEFT JOIN tbl_language as l ON c.languageId=l.id WHERE l.id='".$fetchRes['id']."'")->results();
				//$j=0;
				if($i==0){
					foreach($qryRes_constant as $fetchRes_constant){
						$constant_array = array();
						/*if($i==0){*/
							$constant_array=array($fetchRes_constant['constantName'],stripslashes(trim($fetchRes_constant['constantValue'])));
							$final_constant_array[]=$constant_array;
						/*}else{
							$final_constant_array[$j]=array_merge($final_constant_array[$j],array(stripslashes(trim($fetchRes_constant['constantValue']))));
						}
						$j++;*/
					}
				}else{
					for($j=0;$j<count($final_constant_array);$j++){
						foreach($qryRes_constant as $fetchRes_constant){
							if($final_constant_array[$j][0]==$fetchRes_constant['constantName']){
								$final_constant_array[$j]=array_merge($final_constant_array[$j],array(stripslashes(trim($fetchRes_constant['constantValue']))));
							}
						}
					}
				}
				$i++;
			}
			$final_result = array($language_array);
			foreach($final_constant_array as $k=>$v){
				$final_result=array_merge($final_result,array($v));
			}
			$activity_array = array("id"=>0,"module"=>$module,"activity"=>'export');
			add_admin_activity($activity_array);
			convert_to_excel($final_result,'constant.xlsx');
			exit;
		}else{
			$_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'NRF'));
			echo '<script type="text/javascript">
				window.location.href="'.SITE_ADM_MOD.$module.'/";
			</script>';
		}
	}
	else if($action == "download_sample_csv") {
		$qryRes=$db->pdoQuery("SELECT * FROM tbl_language WHERE status='a'")->results();
		$language_array = array("Constant Name");
		$blank_array = array();
		$constant_array = array();
		$i=0;
		$totalqryRes=$db->pdoQuery("SELECT * FROM tbl_language WHERE status='a'")->affectedRows();
		if($totalqryRes > 0){
			foreach($qryRes as $fetchRes){
				$language_array=array_merge($language_array,array($fetchRes['languageName']));
				$blank_array = array_merge($blank_array,array());
				for($j=0;$j<2;$j++){
					$constant_array = array();
					if($i==0){
						$constant_array=array("Constant Name ".($j+1),'Constant Value ('.$fetchRes['languageName'].')');
						$final_constant_array[]=$constant_array;
					}else{
						$final_constant_array[$j]=array_merge($final_constant_array[$j],array('Constant Value ('.$fetchRes['languageName'].')'));
					}
				}
				$i++;
			}
			$final_result = array($language_array,$blank_array);
			foreach($final_constant_array as $k=>$v){
				$final_result=array_merge($final_result,array($v));
			}
			convert_to_csv($final_result, 'sample.csv', ',');
			exit;
		}else{
			$_SESSION["msgType"] = disMessage(array('type'=>'err','var'=>'NRF'));
			echo '<script type="text/javascript">
				window.location.href="'.SITE_ADM_MOD.$module.'/";
			</script>';
		}
	}
	else if($action == "delete"  && in_array('delete',$Permission)) {
		$aWhere=array("id"=>$id);
		$db->delete($table,$aWhere);
		$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'delete');
		add_admin_activity($activity_array);
	}
	else if($action == 'changeLanguage')
	{
		$action="";
	}
	$mainObject = new Constant($id,$searchArray,$action);
	extract($mainObject->data);
	echo ($content);
	exit;
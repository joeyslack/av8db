<?php
function chkPermission($module){
	global $db, $adminUserId;
	$admSl = $db->select("tbl_admin", array("adminType"), array("id ="=>(int)$adminUserId))->result();
	if(!empty($admSl)){
		$adm = $admSl;
		if($adm['adminType'] == 'g'){
			$moduleId = $db->select("tbl_adminrole", array("id"), array("pagenm ="=>(string)$module))->result();
			$chkPermssion = $db->select("tbl_admin_permission", array("permission"), array("admin_id"=>(int)$adminUserId,"page_id"=>$moduleId['id']))->result();
			if(empty($chkPermssion['permission'])){
				$toastr_message = $_SESSION["toastr_message"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
				redirectPage(SITE_ADM_MOD.'home-nct/');
			}
		}
	}
}
function add_admin_activity($activity_array=array()){
	global $db,$adminUserId;
	$admSl = $db->select("tbl_admin", array("adminType"), array("id ="=>(int)$adminUserId))->result();
	if($admSl['adminType'] == 'g'){
		$activity_array['id'] = (isset($activity_array['id']))?$activity_array['id']:0;
		$activity_array['module'] = (isset($activity_array['module']))?getTableValue('tbl_adminrole','id',array("pagenm"=>$activity_array['module'])):0;
		$activity_array['activity'] = (isset($activity_array['activity']))?getTableValue('tbl_subadmin_action','id',array("constant"=>$activity_array['activity'])):0;
		$activity_array['action'] = (isset($activity_array['action']))?$activity_array['action']:'';
		$activity_array['created_date'] = date('Y-m-d H:i:s');
		$activity_array['updated_date'] = date('Y-m-d H:i:s');
		$val_array = array("activity_type"=>$activity_array['activity'],"page_id"=>$activity_array['module'],"admin_id"=>$adminUserId,"entity_id"=>$activity_array['id'],"entity_action"=>$activity_array['action'],"created_date"=>$activity_array['created_date'],"updated_date"=>$activity_array['updated_date']);
		$db->insert('tbl_admin_activity',$val_array);	
	}
}
function chkModulePermission($module){
	global $db, $adminUserId;
	$admSl = $db->select("tbl_admin", array("adminType"), array("id ="=>(int)$adminUserId))->result();
	if(!empty($admSl)){
		$adm = $admSl;
		if($adm['adminType'] == 'g'){
			$moduleId = $db->select("tbl_adminrole", array("id"), array("pagenm ="=>(string)$module))->result();
			$chkPermssion = $db->select("tbl_admin_permission", array("permission"), array("admin_id"=>(int)$adminUserId,"page_id"=>$moduleId['id'],"and permission !="=>""))->result();
			if(!empty($chkPermssion['permission'])){
				$qryRes = $db->pdoQuery("select id,constant from tbl_subadmin_action where id in (".$chkPermssion['permission'].")")->results();
				foreach($qryRes as $fetchRes){
					$permissions[] = $fetchRes["constant"];
				}
			}
		}else{
			$qryRes = $db->select("tbl_subadmin_action", array("id,constant"), array())->results();
			foreach($qryRes as $fetchRes){
				$permissions[] = $fetchRes["constant"];
			}
		}
	}
	return $permissions;
}
/*function makeConstantFile($default_lang=0,$insertId=0){
	global $db, $adminUserId;
	if($default_lang > 0 && $insertId > 0){
		$qrysel1 = $db->select("tbl_language", "*",array("default_lan"=>"y"),"", "", 0)->results();
		foreach($qrysel1 as $fetchSel){
			$fp = fopen(DIR_INC. "language-nct/".$insertId.".php","wb");
			$content = '';
			$qsel1 = $db->select("tbl_constant","*",array("languageId"=>$fetchSel['id']))->results();
			$content.='<?php ';
			foreach($qsel1 as $fetchSel1){
				$content.= ' define("'.$fetchSel1['constantName'].'","'.$fetchSel1['constantValue'].'"); ';
			}
			$content.=' ?>';
			fwrite($fp,$content);
			fclose($fp);
		}
	}else{
		$files = glob(DIR_INC.'language-nct/*');
		foreach($files as $file){
		  if(is_file($file))
			unlink($file);
		}
		$qrysel1 = $db->select("tbl_language", "*",array("status"=>"a"),"", "", 0)->results();
		foreach($qrysel1 as $fetchSel){
			$fp = fopen(DIR_INC. "language-nct/".$fetchSel['id'].".php","wb");
			$content = '';
			$qsel1 = $db->select("tbl_constant","*",array("languageId"=>$fetchSel['id']))->results();
			$content.='<?php ';
			foreach($qsel1 as $fetchSel1){
				$filterd = filtering($fetchSel1['constantValue']);
				$content.= ' define("'.$fetchSel1['constantName'].'","'.$filterd.'"); ';
				//$content.= "\r\n";
			}
			$content.=' ?>';
			fwrite($fp,$content);
			fclose($fp);
		}


	}
}*/

function makeConstantFile()
{
	global $db, $adminUserId;
	
	$files = glob(DIR_INC.'language/*'); // get all file names
	foreach($files as $file){ // iterate files
	  if(is_file($file))
		unlink($file); // delete file
	}
	$qrysel1= $db->select("tbl_language", "*",array("status"=>"a"),"", "", 0)->results();
		
	foreach($qrysel1 as $fetchSel)
	{
		$fp = fopen(DIR_INC. "language-nct/".$fetchSel['id'].".php","wb");
		$content = '';
		
		$qsel1 = $db->select("tbl_constant","*",array("languageId"=>$fetchSel['id']))->results();
		
		$content.='<?php ';
		foreach($qsel1 as $fetchSel1)
		{
			$content.= ' define("'.$fetchSel1['constantName'].'","'.filtering($fetchSel1['constantValue']).'"); ';
		}
		$content.=' ?>';
		fwrite($fp,$content);
		fclose($fp);

		/*for javascript*/

		$js_filePath = DIR_INC. "language-nct/".$fetchSel['id'].".js";
		if(is_file($js_filePath))
			unlink($js_filePath);
		$js_fp = fopen($js_filePath,(file_exists($js_filePath)) ? 'a' : 'wb');
		$js_content = '';
		
		$js_content.='var lang = { ';
		foreach($qsel1 as $fetchSel1)
		{
			$js_content.= $fetchSel1['constantName'].' : "'.trim(filtering( $fetchSel1['constantValue'])).'", ';
		}
		$js_content.=' };';
		fwrite($js_fp,$js_content);
		fclose($js_fp);
	}
}

function get_languages($act = 'all'){
	global $db;
	$whCond = array();
	if($act == 'default')
		$whCond['default_lan'] = 'y';
	else if($act == 'active')
		$whCond['status'] = 'a';


	return $db->select("tbl_language", array('id,languageName,default_lan,status'),$whCond)->results();
}
?> 
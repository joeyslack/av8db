<?php
	$content = '';
	require_once(DIR_URL."includes-nct/config-nct.php");
	if($adminUserId == 0){die('Invalid request');}
	include("class.language-nct.php");
	$module = 'language-nct';
	chkPermission($module);
	$Permission=chkModulePermission($module);
	$table = 'tbl_language';
	$action = isset($_GET["action"]) ? trim($_GET["action"]) : (isset($_POST["action"]) ? trim($_POST["action"]) : 'datagrid');
	$id = isset($_GET["id"]) ? trim($_GET["id"]) : (isset($_POST["id"]) ? trim($_POST["id"]) : 0);
	$value = isset($_POST["value"]) ? trim($_POST["value"]) : isset($_GET["value"]) ? trim($_GET["value"]) : '';
	$page = isset($_POST['iDisplayStart']) ? intval($_POST['iDisplayStart']) : 0;
	$rows = isset($_POST['iDisplayLength']) ? intval($_POST['iDisplayLength']) : 25;
	$sort = isset($_POST["iSortTitle_0"]) ? $_POST["iSortTitle_0"] : NULL;
	$order = isset($_POST["sSortDir_0"]) ? $_POST["sSortDir_0"] : NULL;
	$chr = isset($_POST["sSearch"]) ? $_POST["sSearch"] : NULL;
	$sEcho = isset($_POST['sEcho']) ? $_POST['sEcho'] : 1;
	extract($_GET);
	$searchArray = array("page"=>$page, "rows"=>$rows, "sort"=>$sort, "order"=>$order, "offset"=>$page, "chr"=>$chr, 'sEcho' =>$sEcho);
	if($action == "update_status" && in_array('status',$Permission)) {
		$setVal = array('status'=>($value == 'a' ? 'a' : 'd'));
		$db->update($table,$setVal,array("id"=>$id));
		echo json_encode(array('type'=>'success','Record '.($value == 'a' ? 'activated ' : 'deactivated ').'successfully'));
		$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'status',"action"=>$value);
		add_admin_activity($activity_array);
		exit;
	} else if ($action == 'update_default'){
		
		$select = $db->select('tbl_language',array('id'))->results();
		
		foreach ($select as $f) {
			$db->update($table,array('default_lan'=>'n'),array('id'=>$f['id']));
		}
		
		//$db->pdoQuery('UPDATE `tbl_language` SET default_lan = "n" WHERE id > 0 ');

		$setVal = array('default_lan'=>($value == 'a' ? 'y' : 'n'));
		
		$db->update($table,$setVal,array("id"=>$id));

		echo json_encode(array('type'=>'success','Record '.($value == 'a' ? 'activated ' : 'deactivated ').'successfully'));
		$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'status',"action"=>$value);
		add_admin_activity($activity_array);
		exit;
	} else if($action == "delete" && in_array('delete',$Permission)) {
		$default_lan = getTableValue($table,"default_lan",array("id"=>$id));
		if($default_lan!='y'){
			$aWhere = array("id"=>$id);
			$db->delete($table,$aWhere);
			$aWhere = array("languageId"=>$id);
			$db->delete("tbl_constant",$aWhere);
			unlink(DIR_INC.'language-nct/'.$id.'.php');
			
			
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_content' AND COLUMN_NAME = 'pageTitle_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN pageTitle_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_content' AND COLUMN_NAME = 'metaKeyword_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN metaKeyword_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_content' AND COLUMN_NAME = 'metaDesc_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN metaDesc_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_content' AND COLUMN_NAME = 'pageDesc_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN pageDesc_".$id);
			}

			/*$db->query("ALTER TABLE tbl_content DROP COLUMN metaDesc_".$id);
			$db->query("ALTER TABLE tbl_content DROP COLUMN pageDesc_".$id);*/
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_skills' AND COLUMN_NAME = 'skill_name_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_skills DROP COLUMN skill_name_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_skills' AND COLUMN_NAME = 'skill_description_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_skills DROP COLUMN skill_description_".$id);
			}

			/*$db->query("ALTER TABLE tbl_skills DROP COLUMN skill_name_".$id);			
			$db->query("ALTER TABLE tbl_skills DROP COLUMN skill_description_".$id);*/
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_job_category' AND COLUMN_NAME = 'job_category_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_job_category DROP COLUMN job_category_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_job_category' AND COLUMN_NAME = 'job_category_description_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_job_category DROP COLUMN job_category_description_".$id);
			}

			/*$db->query("ALTER TABLE tbl_job_category DROP COLUMN job_category_".$id);
			$db->query("ALTER TABLE tbl_job_category DROP COLUMN job_category_description_".$id);*/
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_degrees' AND COLUMN_NAME = 'degree_name_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_degrees DROP COLUMN degree_name_".$id);
			}
			/*$db->query("ALTER TABLE tbl_degrees DROP COLUMN degree_name_".$id);*/

			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_industries' AND COLUMN_NAME = 'industry_name_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_industries DROP COLUMN industry_name_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_industries' AND COLUMN_NAME = 'industry_description_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_industries DROP COLUMN industry_description_".$id);
			}
			/*$db->query("ALTER TABLE tbl_industries DROP COLUMN industry_name_".$id);
			$db->query("ALTER TABLE tbl_industries DROP COLUMN industry_description_".$id);*/

			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_company_sizes' AND COLUMN_NAME = 'company_size_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_company_sizes DROP COLUMN company_size_".$id);
			}
			/*$db->query("ALTER TABLE tbl_company_sizes DROP COLUMN company_size_".$id);*/

			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_group_types' AND COLUMN_NAME = 'group_type_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_group_types DROP COLUMN group_type_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_group_types' AND COLUMN_NAME = 'group_type_description_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_group_types DROP COLUMN group_type_description_".$id);
			}

			/*$db->query("ALTER TABLE tbl_group_types DROP COLUMN group_type_".$id);
			$db->query("ALTER TABLE tbl_group_types DROP COLUMN group_type_description_".$id);*/

			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_tariff_plans' AND COLUMN_NAME = 'plan_name_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_tariff_plans DROP COLUMN plan_name_".$id);
			}
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_tariff_plans' AND COLUMN_NAME = 'plan_description_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_tariff_plans DROP COLUMN plan_description_".$id);
			}
			
			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_license_endorsements' AND COLUMN_NAME = 'licenses_endorsements_name_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_license_endorsements DROP COLUMN licenses_endorsements_name_".$id);
			}

			$total = $db->pdoQuery("select * from information_schema.COLUMNS WHERE TABLE_NAME='tbl_airport' AND COLUMN_NAME = 'airport_name_".$id."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_airport DROP COLUMN airport_name_".$id);
			}

			/*$db->query("ALTER TABLE tbl_tariff_plans DROP COLUMN plan_name_".$id);
			$db->query("ALTER TABLE tbl_tariff_plans DROP COLUMN plan_description_".$id);*/

			$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'delete');
			add_admin_activity($activity_array);

		    echo json_encode(array('type' => 'success', 'message' => "Language has been deleted successfully."));
		    exit;
		}
	}

	if($action == 'update_fields'){

		$db->query('update tbl_content cate inner join tbl_content fcate ON (cate.pId = fcate.pId) SET cate.pageTitle_1 = fcate.pageTitle');
		$db->query('update tbl_content cate inner join tbl_content fcate ON (cate.pId = fcate.pId) SET cate.metaKeyword_1 = fcate.metaKeyword');		
		$db->query('update tbl_content cate inner join tbl_content fcate ON (cate.pId = fcate.pId) SET cate.metaDesc_1 = fcate.metaDesc');
		$db->query('update tbl_content cate inner join tbl_content fcate ON (cate.pId = fcate.pId) SET cate.pageDesc_1 = fcate.pageDesc');

		$db->query('update tbl_skills cate inner join tbl_skills fcate ON (cate.id = fcate.id) SET cate.skill_name_1 = fcate.skill_name');
		$db->query('update tbl_skills cate inner join tbl_skills fcate ON (cate.id = fcate.id) SET cate.skill_description_1 = fcate.skill_description');

		$db->query('update tbl_job_category cate inner join tbl_job_category fcate ON (cate.id = fcate.id) SET cate.job_category_1 = fcate.job_category');
		$db->query('update tbl_job_category cate inner join tbl_job_category fcate ON (cate.id = fcate.id) SET cate.job_category_description_1 = fcate.job_category_description');

		$db->query('update tbl_degrees cate inner join tbl_degrees fcate ON (cate.id = fcate.id) SET cate.degree_name_1 = fcate.degree_name');

		$db->query('update tbl_industries cate inner join tbl_industries fcate ON (cate.id = fcate.id) SET cate.industry_name_1 = fcate.industry_name');
		$db->query('update tbl_industries cate inner join tbl_industries fcate ON (cate.id = fcate.id) SET cate.industry_description_1 = fcate.industry_description');

		$db->query('update tbl_company_sizes cate inner join tbl_company_sizes fcate ON (cate.id = fcate.id) SET cate.company_size_1 = fcate.company_size');

		$db->query('update tbl_group_types cate inner join tbl_group_types fcate ON (cate.id = fcate.id) SET cate.group_type_1 = fcate.group_type');
		$db->query('update tbl_group_types cate inner join tbl_group_types fcate ON (cate.id = fcate.id) SET cate.group_type_description_1 = fcate.group_type_description');

		$db->query('update tbl_tariff_plans cate inner join tbl_tariff_plans fcate ON (cate.id = fcate.id) SET cate.plan_name_1 = fcate.plan_name');
		$db->query('update tbl_tariff_plans cate inner join tbl_tariff_plans fcate ON (cate.id = fcate.id) SET cate.plan_description_1 = fcate.plan_description');
		
		$db->query('update tbl_airport cate inner join tbl_airport fcate ON (cate.id = fcate.id) SET cate.airport_name_1 = fcate.airport_name');
		
		$db->query('update tbl_license_endorsements cate inner join tbl_license_endorsements fcate ON (cate.id = fcate.id) SET cate.licenses_endorsements_name_1 = fcate.licenses_endorsements_name');
	}

	if($action == 'deleterows'){
		for ($i=2; $i < 100; $i++) { 

			
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_content' AND COLUMN_NAME = 'pageTitle_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN pageTitle_".$i);
			}

			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_content' AND COLUMN_NAME = 'metaKeyword_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN metaKeyword_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_content' AND COLUMN_NAME = 'metaDesc_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN metaDesc_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_content' AND COLUMN_NAME = 'pageDesc_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_content DROP COLUMN pageDesc_".$i);
			}

			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_skills' AND COLUMN_NAME = 'skill_name_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_skills DROP COLUMN skill_name_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_skills' AND COLUMN_NAME = 'skill_description_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_skills DROP COLUMN skill_description_".$i);
			}

			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_job_category' AND COLUMN_NAME = 'job_category_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_job_category DROP COLUMN job_category_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_job_category' AND COLUMN_NAME = 'job_category_description_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_job_category DROP COLUMN job_category_description_".$i);
			}

			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_degrees' AND COLUMN_NAME = 'degree_name_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_degrees DROP COLUMN degree_name_".$i);
			}

			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_industries' AND COLUMN_NAME = 'industry_name_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_industries DROP COLUMN industry_name_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_industries' AND COLUMN_NAME = 'industry_description_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_industries DROP COLUMN industry_description_".$i);
			}

			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_company_sizes' AND COLUMN_NAME = 'company_size_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_company_sizes DROP COLUMN company_size_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_group_types' AND COLUMN_NAME = 'group_type_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_group_types DROP COLUMN group_type_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_group_types' AND COLUMN_NAME = 'group_type_description_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_group_types DROP COLUMN group_type_description_".$i);
			}
			
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_tariff_plans' AND COLUMN_NAME = 'plan_name_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_tariff_plans DROP COLUMN plan_name_".$i);
			}
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_tariff_plans' AND COLUMN_NAME = 'plan_description_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_tariff_plans DROP COLUMN plan_description_".$i);
			}
			
			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_license_endorsements' AND COLUMN_NAME = 'licenses_endorsements_name_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_license_endorsements DROP COLUMN licenses_endorsements_name_".$i);
			}

			$total = $db->pdoQuery("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = 'db_connectin' AND TABLE_NAME='tbl_airport' AND COLUMN_NAME = 'airport_name_".$i."'")->affectedRows();
			if($total>0){
				$db->query("ALTER TABLE tbl_airport DROP COLUMN airport_name_".$i);
			}			
		}
		echo "YES";
	}
	$mainObject = new Language($id,$searchArray, $action);
	extract($mainObject->data);
	echo ($content);
	exit;
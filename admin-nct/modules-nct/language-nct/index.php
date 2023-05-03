<?php
	$reqAuth=true;
	require_once(DIR_URL."includes-nct/config-nct.php");
	require("class.language-nct.php");
	$module = "language-nct";
	$table = "tbl_language";
	$styles = array(array("data-tables/DT_bootstrap.css",SITE_ADM_PLUGIN),
					array("bootstrap-switch/css/bootstrap-switch.min.css",SITE_ADM_PLUGIN));
	$scripts= array("core/datatable.js",
					array("data-tables/jquery.dataTables.js",SITE_ADM_PLUGIN),
					array("data-tables/DT_bootstrap.js",SITE_ADM_PLUGIN),
					array("bootstrap-switch/js/bootstrap-switch.min.js",SITE_ADM_PLUGIN));
	chkPermission($module);

$_SESSION["toastr_message"] = disMessage(array('type' => 'suc', 'var' => 'User has been updated successfully.'));

	$Permission=chkModulePermission($module);
	$metaTag = getMetaTags(array("description"=>"Admin Panel",
			"keywords"=>'Admin Panel',
			"author"=>SITE_NM));
	$id = isset($_GET["id"]) ? (int)trim($_GET["id"]) : 0;
	$postType = isset($_POST["type"])?trim($_POST["type"]):'';
	$type = isset($_GET["type"])?trim($_GET["type"]):$postType;
	$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage').' Languages';
	$winTitle = $headTitle.' - '.SITE_NM;

	$breadcrumb = array($headTitle);

	if(isset($_POST["submitAddForm"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
		extract($_POST);
		//_print_r($_POST);exit;
		$objPost->languageName = isset($languageName) ? $languageName : '';
		$objPost->url_constant = isset($url_constant) ? $url_constant : '';
		$objPost->status= isset($status) ? $status : 'n';
		//$objPost->default_lan= isset($default_lan) ? $default_lan : '';
		if($objPost->languageName != "" && $objPost->url_constant != ""){
			if($type == 'edit' && $id > 0){
				if(in_array('edit',$Permission)){
					if($objPost->default_lan=='y'){
						$db->update($table,array('default_lan'=>'n'),array("1"=>"1"));
					}
					if($objPost->default_lan=='n'){
						if(getTotalRows($table,"default_lan='y' AND id != '".$id."'",'id')==0){
							$db->update($table,array('default_lan'=>'y'),array("id"=>"1"));
						}
					}
					$temp = array();
					$temp['languageName'] = $objPost->languageName;
					//$temp['default_lan'] = $objPost->default_lan;
					//$temp['status'] = $objPost->status;
					if(in_array('status',$Permission)){
						$temp['status'] = $objPost->status;
					}
					$temp['url_constant'] = $objPost->url_constant;
					$db->update($table, $temp, array("id"=>$id));
					$activity_array = array("id"=>$id,"module"=>$module,"activity"=>'edit');
					add_admin_activity($activity_array);
					$_SESSION["toastr_message"] = disMessage(array('type'=>'suc','var'=>'recEdited'));
				}else{
					$toastr_message = $_SESSION["toastr_message"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
				}
			} else {
				if(in_array('add',$Permission)){
					if(getTotalRows($table,"languageName='".$objPost->languageName."'",'id')==0){
						$objPost->createdDate = date('Y-m-d H:i:s');
						if($objPost->default_lan=='y'){
							$db->update($table, array('default_lan'=>'n'),array("1"=>"1"));
						}
						$valArray = array();
						$valArray['languageName'] = $objPost->languageName;
						//$valArray['default_lan'] = $objPost->default_lan;
						if(in_array('status',$Permission)){
							$valArray['status'] = $objPost->status;
						}
						$valArray['created_date'] = $objPost->createdDate;
						$valArray['url_constant'] = $objPost->url_constant;
						$insertId = $db->insert($table, $valArray)->getLastInsertId();
						$_SESSION["toastr_message"] = disMessage(array('type'=>'suc','var'=>'recAdded'));
						if($insertId > 0){
							$q = $db->select("tbl_constant","*",array("languageId"=>1,"subId"=>0))->results();
							foreach($q as $constant){
								$insArray[] = array("subId"=>$constant['id'],"languageId"=>$insertId,"constantName"=>$constant['constantName'],"constantValue"=>$constant['constantValue'],"type"=>$constant['type'],"created_date"=>$objPost->createdDate);
							}
							$db->insertBatch('tbl_constant',$insArray, true);
							$default_lan = getTableValue($table,"id",array("default_lan"=>'y'));
							makeConstantFile($default_lan,$insertId);
							
							$activity_array = array("id"=>$insertId,"module"=>$module,"activity"=>'add');
							add_admin_activity($activity_array);
							
							$pageTitle = "pageTitle_".$insertId;
							$metaKeyword = "metaKeyword_".$insertId;
							$metaDesc = "metaDesc_".$insertId;
							$pageDesc = "pageDesc_".$insertId;
							$skill_name = "skill_name_".$insertId;
							$skill_description = "skill_description_".$insertId;
							$job_category = "job_category_".$insertId;
							$job_category_description = "job_category_description_".$insertId;
							$degree_name = "degree_name_".$insertId;
							$industry_name = "industry_name_".$insertId;
							$industry_description = "industry_description_".$insertId;
							$company_size = "company_size_".$insertId;
							$group_type = "group_type_".$insertId;
							$group_type_description = "group_type_description_".$insertId;
							$plan_name = "plan_name_".$insertId;
							$plan_description = "plan_description_".$insertId;
							$licenses_endorsements_name 	= "licenses_endorsements_name_".$insertId;
							$airport_name 	= "airport_name_".$insertId;
							
							
							$db->query("ALTER TABLE tbl_content ADD ".$pageTitle." VARCHAR(255) NOT NULL AFTER `pageTitle`");
							$db->query("ALTER TABLE tbl_content ADD ".$metaKeyword." mediumtext NOT NULL AFTER metaKeyword");
							$db->query("ALTER TABLE `tbl_content` ADD `".$metaDesc."` TEXT NOT NULL AFTER `metaDesc`");
							$db->query("ALTER TABLE `tbl_content` ADD ".$pageDesc." TEXT NOT NULL AFTER pageDesc");

							
							$db->query('UPDATE tbl_content cate INNER JOIN tbl_content fcate ON (cate.pId = fcate.pId) SET cate.'.$pageTitle.' = fcate.pageTitle');
							$db->query('UPDATE tbl_content cate INNER JOIN tbl_content fcate ON (cate.pId = fcate.pId) SET cate.'.$metaKeyword.' = fcate.metaKeyword');
							/*$db->query('UPDATE tbl_content cate INNER JOIN tbl_content fcate ON (cate.pId = fcate.pId) SET cate.'.$metaDesc.' = fcate.metaDesc');
							$db->query('UPDATE tbl_content cate INNER JOIN tbl_content fcate ON (cate.pId = fcate.pId) SET cate.'.$pageDesc.' = fcate.pageDesc');*/

							$db->query("ALTER TABLE `tbl_skills` ADD `".$skill_name."` VARCHAR(64) NOT NULL AFTER `skill_name`");
							$db->query('update tbl_skills cate inner join tbl_skills fcate ON (cate.id = fcate.id) SET cate.'.$skill_name.' = fcate.skill_name');
							$db->query("ALTER TABLE `tbl_skills` ADD `".$skill_description."` text NOT NULL AFTER `skill_description`");
							$db->query('update tbl_skills cate inner join tbl_skills fcate ON (cate.id = fcate.id) SET cate.'.$skill_description.' = fcate.skill_description');

							$db->query("ALTER TABLE `tbl_job_category` ADD `".$job_category."` VARCHAR(64) NOT NULL AFTER `job_category`");
							$db->query('update tbl_job_category cate inner join tbl_job_category fcate ON (cate.id = fcate.id) SET cate.'.$job_category.' = fcate.job_category');
							$db->query("ALTER TABLE `tbl_job_category` ADD `".$job_category_description."` mediumtext NOT NULL AFTER `job_category_description`");
							$db->query('update tbl_job_category cate inner join tbl_job_category fcate ON (cate.id = fcate.id) SET cate.'.$job_category_description.' = fcate.job_category_description');

							$db->query("ALTER TABLE `tbl_degrees` ADD `".$degree_name."` varchar(64) NOT NULL AFTER `degree_name`");
							$db->query('update tbl_degrees cate inner join tbl_degrees fcate ON (cate.id = fcate.id) SET cate.'.$degree_name.' = fcate.degree_name');

							$db->query("ALTER TABLE `tbl_industries` ADD `".$industry_name."` VARCHAR(128) NOT NULL AFTER `industry_name`");
							$db->query('update tbl_industries cate inner join tbl_industries fcate ON (cate.id = fcate.id) SET cate.'.$industry_name.' = fcate.industry_name');
							$db->query("ALTER TABLE `tbl_industries` ADD `".$industry_description."` mediumtext NOT NULL AFTER `industry_description`");
							$db->query('update tbl_industries cate inner join tbl_industries fcate ON (cate.id = fcate.id) SET cate.'.$industry_description.' = fcate.industry_description');

							$db->query("ALTER TABLE `tbl_company_sizes` ADD `".$company_size."` VARCHAR(128) NOT NULL AFTER `company_size`");
							$db->query('update tbl_company_sizes cate inner join tbl_company_sizes fcate ON (cate.id = fcate.id) SET cate.'.$company_size.' = fcate.company_size');

							$db->query("ALTER TABLE `tbl_group_types` ADD `".$group_type."` VARCHAR(512) NOT NULL AFTER `group_type`");
							$db->query('update tbl_group_types cate inner join tbl_group_types fcate ON (cate.id = fcate.id) SET cate.'.$group_type.' = fcate.group_type');
							$db->query("ALTER TABLE `tbl_group_types` ADD `".$group_type_description."` mediumtext NOT NULL AFTER `group_type_description`");
							$db->query('update tbl_group_types cate inner join tbl_group_types fcate ON (cate.id = fcate.id) SET cate.'.$group_type_description.' = fcate.group_type_description');

							$db->query("ALTER TABLE `tbl_tariff_plans` ADD `".$plan_name."` VARCHAR(512) NOT NULL AFTER `plan_name`");
							$db->query('update tbl_tariff_plans cate inner join tbl_tariff_plans fcate ON (cate.id = fcate.id) SET cate.'.$plan_name.' = fcate.plan_name');
							$db->query("ALTER TABLE `tbl_tariff_plans` ADD `".$plan_description."` text NOT NULL AFTER `plan_description`");
							$db->query('update tbl_tariff_plans cate inner join tbl_tariff_plans fcate ON (cate.id = fcate.id) SET cate.'.$plan_description.' = fcate.plan_description');

							$db->query("ALTER TABLE `tbl_license_endorsements` ADD `".$licenses_endorsements_name."` varchar(255) NOT NULL AFTER `licenses_endorsements_name`");
							$db->query('update tbl_license_endorsements cate inner join tbl_license_endorsements fcate ON (cate.id = fcate.id) SET cate.'.$licenses_endorsements_name.' = fcate.licenses_endorsements_name');

							$db->query("ALTER TABLE `tbl_airport` ADD `".$airport_name."` varchar(200) NOT NULL AFTER `airport_name`");
							$db->query('update tbl_airport cate inner join tbl_airport fcate ON (cate.id = fcate.id) SET cate.'.$airport_name.' = fcate.airport_name');
						}
												
						/* Alter table as new column end*/
						//$toastr_message = $_SESSION["toastr_message"] = disMessage(array('type'=>'suc','var'=>'recAdded'));
						//redirectPage(SITE_ADM_MOD.$module);
					}else{
						$_SESSION["toastr_message"] = disMessage(array('type'=>'err','var'=>'recExist'));
					}
				}else{
					$toastr_message = $_SESSION["toastr_message"] = disMessage(array('type'=>'err','var'=>'NoPermission'));
				}
			}
			redirectPage(SITE_ADM_MOD.$module);
		}
		else {
			$toastr_message = array('type'=>'err','var'=>'fillAllvalues');
		}
	}
	$objLanguage = new Language();
	$pageContent = $objLanguage->getPageContent();
	require_once(DIR_ADMIN_TMPL."parsing-nct.tpl.php");
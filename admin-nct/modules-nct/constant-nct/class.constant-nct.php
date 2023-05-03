<?php
class Constant extends Home {
	public $constantValue;
	public $constantName;
	public $data = array();
	public function __construct($id=0, $searchArray=array(), $type='') {
		$this->data['id'] = $this->id = $id;
		$this->table = 'tbl_constant';
		$this->type = ($this->id > 0 ? 'edit' : 'add');
		$this->searchArray = $searchArray;
		$this->ctype = $this->searchArray['ctype'];
		parent::__construct();
		if($this->id>0){
			$qrySel = $this->db->select($this->table, array("id","constantValue","constantName","created_date"),array("id"=>$id))->result();
			$fetchRes = $qrySel;
			$this->data['constantValue'] = $this->constantValue = $fetchRes['constantValue'];
			$this->data['constantName'] = $this->constantName = $fetchRes['constantName'];
		}
		else{
			$this->data['constantValue'] = $this->constantValue = '';
			$this->data['constantName'] = $this->constantName = '';
		}
		switch($type){
			case 'add' : {
				$this->data['content'] =  (in_array('add',$this->Permission))?$this->getForm():'';
				break;
			}
			case 'import_excel' : {
				$this->data['content'] =  (in_array('import',$this->Permission))?$this->getImportCsvForm():'';
				break;
			}
			case 'edit' : {
				$this->data['content'] =  (in_array('edit',$this->Permission))?$this->getForm():'';
				break;
			}
			case 'view' : {
				$this->data['content'] =  '';
				break;
			}
			case 'delete' : {
				$this->data['content'] = (in_array('delete',$this->Permission))?json_encode($this->dataGrid()):'';
				break;
			}
			case 'langArray' : {
				$qryLang = $this->db->select("tbl_language",array("id","languageName"),array("status"=>'a'),"ORDER BY languageName")->results();
				foreach($qryLang as $fetchLang){
					$this->langArray[$fetchLang['id']] = $fetchLang['languageName'];
				}
				break;
			}
			case 'datagrid' :  {
				$this->data['content'] = (in_array('module',$this->Permission))?json_encode($this->dataGrid()):'';
			}
		}
	}
	public function getImportCsvForm() {
		$content = '';
		$content .=	$this->fields->form_start(array("name"=>"frmCont","extraAtt"=>"novalidate='novalidate'")).
			$this->fields->fileBox(array("label"=>"".MEND_SIGN."Import EXCEL : ","name"=>"file_csv","class"=>"logintextbox-bg required","value"=>$this->data['constantName']));
			$qrySel = $this->db->select("tbl_language", array("id","languageName","created_date"),array("status"=>'a'))->results();
			$content .= $this->fields->buttonpanel_start().
			$this->fields->button(array("onlyField"=>true,"name"=>"submitExcelForm", "type"=>"submit", "class"=>"green", "value"=>"Submit", "extraAtt"=>"")).
			$this->fields->button(array("onlyField"=>true,"name"=>"cn", "type"=>"button", "class"=>"btn-toggler", "value"=>"Cancel", "extraAtt"=>"")).
			$this->fields->buttonpanel_end().
			$this->fields->form_end();
			//submitExcelForm
		return sanitize_output($content);
	}
	public function getForm() {
		$content= $constant_value_content1 = '';
		if($this->id  > 0){
			$dispalay_constant= new Templater(DIR_ADMIN_TMPL.$this->module."/display_constant-nct.tpl.php");
			$dispalay_constant_content = $dispalay_constant->parse();
			$search=array("%CONSTANT_NAME%");
			$replace=array($this->data['constantName']);
			$constant_name_field=str_replace($search,$replace,$dispalay_constant_content);
		}else{
			$constant_name= new Templater(DIR_ADMIN_TMPL.$this->module."/constant_name-nct.tpl.php");
			$constant_name_content = $constant_name->parse();
			$search=array("%CONSTANT_NAME%");
			$replace=array($this->data['constantName']);
			$constant_name_field=str_replace($search,$replace,$constant_name_content);
		}
		$qrySel = $this->db->select("tbl_language", array("id","languageName","created_date"),array("status"=>'a'))->results();
		$i = 0;
		$qrySubL = $this->db->select($this->table, array("subId"),array("id ="=>$this->id))->result();
		$subId = $qrySubL['subId'];
		$constant_value= new Templater(DIR_ADMIN_TMPL.$this->module."/constant_value-nct.tpl.php");
		$constant_value_content = $constant_value->parse();
		$constant_value_search=array("%MEND_SIGN%","%LANGUAGE_NAME%","%CONSTANT_VALUE%","%ID%");
		foreach($qrySel as $fetchRes){
			if($this->type=='edit'){
				$qrysel1 = $this->db->select($this->table, array("id","constantValue","created_date"),array("languageId ="=>$fetchRes["id"])," AND ( id = $this->id OR ".($subId==0?'subId = '.$this->id.'':'id = '.$subId.'').")" )->results();
				$fetchRow = $qrysel1;
				$this->constantValue = ($this->type=='edit') ? $fetchRow[0]['constantValue'] : '';
				$constant_value_replace=array(MEND_SIGN,$fetchRes['languageName'],stripslashes($this->constantValue),$fetchRes['id']);
			}else{
				$constant_value_replace=array(MEND_SIGN,$fetchRes['languageName'],'',$fetchRes['id']);
			}
			$constant_value_content1.=str_replace($constant_value_search,$constant_value_replace,$constant_value_content);
		}
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module."/form-nct.tpl.php");
		$main_content = $main_content->parse();
		$fields = array("%MEND_SIGN%","%CONSTANT_NAME_FIELD%","%CONSTANT_VALUE%","%TYPE%","%ID%");
		$fields_replace = array(MEND_SIGN,$constant_name_field,$constant_value_content1,$this->type,$this->id);
		$content=str_replace($fields,$fields_replace,$main_content);
		return sanitize_output($content);
	}
	public function dataGrid() {

		$content = $operation = $whereCond = $whereCond1= $totalRow = NULL;
		$result = $tmp_rows = $row_data = array();
		extract($this->searchArray);
		$langId = isset($langId)?$langId:1;
		$chr = isset($chr)?$chr:"";
		$chr = str_replace(array('_', '%'), array('\_', '\%'),$chr );

		
		$chr = mb_convert_encoding((trim(addslashes($chr))), 'HTML-ENTITIES', 'UTF-8');


		$whereCond = array();
		$aWhere = array($langId);
		//if(isset($chr) && $chr != '') {
			$aWhere[] = "%$chr%";
			$aWhere[] = "%$chr%";
		//}
		if(isset($sort))
			$sorting = $sort.' '. $order;
		else
			$sorting = 'id DESC';
		$totalRowTmp = $this->db->pdoQuery('SELECT COUNT(id) AS nmrows FROM tbl_constant WHERE (languageId = '.$langId.' AND (constantValue Like "%'.$chr.'%" OR constantName LIKE "%'.$chr.'%"))')->result();
		$totalRow = $totalRowTmp['nmrows'];
		$qrySel = 'SELECT id,subId,constantValue,constantName,created_date FROM tbl_constant WHERE (languageId = '.$langId.' AND (constantValue Like "%'.$chr.'%" OR constantName LIKE "%'.$chr.'%")) ORDER BY '.$sorting.' limit '.$offset.' , '.$rows;
		$Qrysel = $this->db->pdoQuery($qrySel);
		$qrysel = $Qrysel->results();
		
		foreach($qrysel as $fetchRes) {
			//if(strlen($fetchRes["constantValue"])>50) {
				$newContentVal = myTruncate($fetchRes["constantValue"], 50);
			//}
			$id = $fetchRes['subId'] == 0 ? $fetchRes['id']:$fetchRes['subId'];
			$operation =(in_array('edit',$this->Permission))?'&nbsp;&nbsp;'.$this->operation(array("href"=>SITE_ADM_MOD.$this->module."/ajax.".$this->module.".php?action=edit&id=".$id,"extraAtt" => 'target="_blank"',"class"=>"btn default btn-xs black btnEdit","value"=>'<i class="fa fa-edit"></i>&nbsp;Edit')):'';
		//	$operation .=(in_array('delete',$this->Permission))?'&nbsp;&nbsp;'.$this->operation(array("href"=>"ajax.".$this->module.".php?action=delete&id=".$fetchRes['id']."","class"=>"btn default btn-xs red btn-delete","value"=>'<i class="fa fa-trash-o"></i>&nbsp;Delete')):'';
			$constantValue = '<span title="'.$fetchRes["constantValue"].'">'.$newContentVal.'</span>';
			$final_array = array(stripslashes($fetchRes["constantName"]),stripslashes($constantValue));
			if(in_array('edit',$this->Permission) || in_array('delete',$this->Permission) || in_array('view',$this->Permission) ){
				$final_array =  array_merge($final_array, array($operation));
			}
			$row_data[] = $final_array;
		}
		$result["sEcho"]=$sEcho;
		$result["iTotalRecords"] = (int)$totalRow;
		$result["iTotalDisplayRecords"] = (int)$totalRow;
		$result["aaData"] = $row_data;
		return $result;
	}
	public function getSelectBoxOption(){
		$content = '';
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module."/select_option-nct.tpl.php");
		$content.= $main_content->parse();
		return sanitize_output($content);
	}
	public function toggel_switch($text){
		$text['action'] = isset($text['action']) ? $text['action'] : 'Enter Action Here: ';
		$text['check'] = isset($text['check']) ? $text['check'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? ''.trim($text['class']) : '';
		$text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module.'/switch-nct.tpl.php');
		$main_content=$main_content->parse();
		$fields = array("%NAME%","%CLASS%","%ACTION%","%EXTRA%","%CHECK%");
		$fields_replace = array($text['name'],$text['class'],$text['action'],$text['extraAtt'],$text['check']);
		return str_replace($fields,$fields_replace,$main_content);
	}
	public function operation($text){
		$text['href'] = isset($text['href']) ? $text['href'] : 'Enter Link Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? ''.trim($text['class']) : '';
		$text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module.'/operation-nct.tpl.php');
		$main_content=$main_content->parse();
		$fields = array("%HREF%","%CLASS%","%VALUE%","%EXTRA%");
		$fields_replace = array($text['href'],$text['class'],$text['value'],$text['extraAtt']);
		return str_replace($fields,$fields_replace,$main_content);
	}
	public function getPageContent(){
		$final_result = NULL;
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module."/".$this->module.".tpl.php");
		$main_content->breadcrumb = $this->getBreadcrumb();
		$main_content->getForm = $this->getForm();
		$main_content->langArray = $this->langArray;
		$final_result = $main_content->parse();
		return $final_result;
	}
}
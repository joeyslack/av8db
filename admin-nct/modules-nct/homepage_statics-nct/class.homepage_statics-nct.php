<?php
class HomePageStatics extends Home{
	public $benefits;
	public $benefits_constants;
	public $isActive;
	public $data = array();
	public function __construct($module, $id=0, $objPost=NULL, $searchArray=array(), $type='') {
		global $db, $fields, $sessCataId;
		$this->db = $db;
		$this->data['id'] = $this->id = $id;
		$this->fields = $fields;
		$this->module = $module;
		$this->table = 'tbl_homepage_statics';
		$this->type = ($this->id > 0 ? 'edit' : 'add');
		$this->searchArray = $searchArray;
		parent::__construct();
		$fetchRes = '';
		if($this->id>0){
			$qrySel = $this->db->select($this->table, array("*"),array("id"=>$id))->result();
			$fetchRes = $qrySel;
			$this->data['content_type'] = $this->content_type = $fetchRes['type'];
			$this->data['value'] = $this->value =  $fetchRes['value'];
			$this->data['created_date'] = $this->created_date =  $fetchRes['created_date'];
			$this->data['ipaddress'] = $this->ipaddress =  $fetchRes['ipaddress'];
			$this->data['status'] = $this->status =  $fetchRes['status'];
		}else{
			$this->data['content_type'] = $this->content_type = '';
			$this->data['value'] = $this->value =  $fetchRes['value'];
			$this->data['created_date'] = $this->created_date =  '';
			$this->data['ipaddress'] = $this->ipaddress =  '';
			$this->data['status'] = $this->status =  'y';
		}
		switch($type){
			case 'add' : {
				$this->data['content'] =  $this->getForm();
				break;
			}
			case 'edit' : {
				$this->data['content'] =  $this->getForm();
				break;
			}
			case 'view' : {
				$this->data['content'] =  $this->viewForm();
				break;
			}
			case 'delete' : {
				$this->data['content'] =  json_encode($this->dataGrid());
				break;
			}
			case 'datagrid' : {
				$this->data['content'] =  json_encode($this->dataGrid());
			}
		}
	}
	public function getForm() {
		$content = '';
		if($this->id  > 0){
			$dispalay_constant = new Templater(DIR_ADMIN_TMPL.$this->module."/display_constant-nct.tpl.php");
			$dispalay_constant_content = $dispalay_constant->parse();
			$search = array("%CONSTANT_NAME%");
			$replace = array($this->data['content_type']);
			$constant_name_field = str_replace($search,$replace,$dispalay_constant_content);
		}else{
			$constant_name = new Templater(DIR_ADMIN_TMPL.$this->module."/constant_name-nct.tpl.php");
			$constant_name_content = $constant_name->parse();
			$search = array("%CONSTANT_NAME%");
			$replace = array($this->data['content_type']);
			$constant_name_field = str_replace($search,$replace,$constant_name_content);
		}
		$qrySel = $this->db->select("tbl_language", array("id","languageName","created_date"),array("status"=>'a'))->results();
		$i = 0;
		$constant_value= new Templater(DIR_ADMIN_TMPL.$this->module."/constant_value-nct.tpl.php");
		$constant_value_content = $constant_value->parse();
		$constant_value_search=array("%MEND_SIGN%","%CONSTANT_VALUE%");
		//foreach($qrySel as $fetchRes){
			if($this->type == 'edit'){
				$qrysel1 = $this->db->select($this->table, array("id","type"),array('id'=>$this->id))->result();
				$fetchRow = $qrysel1;
				$this->constantValue = ($this->type=='edit') ? $fetchRow["type"] : '';
				$constant_value_replace = array(MEND_SIGN,stripslashes($this->constantValue));
			}else{
				$constant_value_replace = array(MEND_SIGN,'');
			}
			$constant_value_content1 .= str_replace($constant_value_search,$constant_value_replace,$constant_value_content);
		//}
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module."/form-nct.tpl.php");
		$main_content = $main_content->parse();
		$status_a = ($this->status == 'y' ? 'checked':'');
		$status_d = ($this->status != 'y' ? 'checked':'');
		$fields = array("%MEND_SIGN%","%CONSTANT_NAME_FIELD%","%CONSTANT_VALUE%","%VALUE%","%TYPE%","%ID%","%STATUS_A%","%STATUS_D%");
		$fields_replace = array(MEND_SIGN,$constant_name_field,$constant_value_content1,$this->value,$this->type,$this->id,$status_a,$status_d);
		$content=str_replace($fields,$fields_replace,$main_content);
		return sanitize_output($content);
	}
	public function dataGrid() {
		$content = $operation = $whereCond = $totalRow = NULL;
		$result = $tmp_rows = $row_data = array();
		extract($this->searchArray);
		$chr = str_replace(array('_', '%'), array('\_', '\%'),$chr );
		$whereCond = array();
		if(isset($chr) && $chr != '') {
			$whereCond = array("type LIKE"=> "%$chr%");
		}
		if(isset($sort))
			$sorting = $sort.' '. $order;
		else
			$sorting = 'id DESC';
		if($sort == 'id')
			$sorting = "id DESC";
		$totalRow = $this->db->count($this->table, $whereCond);
		$qrySel = $this->db->select($this->table,array("*"), $whereCond, " ORDER BY $sorting limit $offset , $rows")->results();
		foreach($qrySel as $fetchRes) {
			$status = ($fetchRes['status']=="y") ? "checked" : "";
			$switch  = (in_array('status',$this->Permission))?$this->toggel_switch(array("action"=>"ajax.".$this->module.".php?id=".$fetchRes['id']."","check"=>$status)):'';
			$operation = '';
			$operation .= (in_array('edit',$this->Permission))?$this->operation(array("href"=>"ajax.".$this->module.".php?action=edit&id=".$fetchRes['id']."","class"=>"btn default btn-xs black btnEdit","value"=>'<i class="fa fa-edit"></i>&nbsp;Edit')):'';
			//nct68
			/*$operation .= (in_array('delete',$this->Permission))?'&nbsp;&nbsp;'.$this->operation(array("href"=>"ajax.".$this->module.".php?action=delete&id=".$fetchRes['id']."","class"=>"btn default btn-xs red btn-delete","value"=>'<i class="fa fa-trash-o"></i>&nbsp;Delete')):'';*/
			//$operation .=(in_array('view',$this->Permission))?'&nbsp;&nbsp;'.$this->operation(array("href"=>"ajax.".$this->module.".php?action=view&id=".$fetchRes['id']."","class"=>"btn default blue btn-xs btn-viewbtn","value"=>'<i class="fa fa-laptop"></i>&nbsp;View')):'';
			$final_array =  array($fetchRes["id"],$fetchRes["type"],$fetchRes["value"]);
			
			if(in_array('status',$this->Permission)){
				$final_array =  array_merge($final_array, array($switch));
			}
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
	public function displaybox($text){
		$text['label'] = isset($text['label']) ? $text['label'] : 'Enter Text Here: ';
		$text['value'] = isset($text['value']) ? $text['value'] : '';
		$text['name'] = isset($text['name']) ? $text['name'] : '';
		$text['class'] = isset($text['class']) ? 'form-control-static '.trim($text['class']) : 'form-control-static';
		$text['onlyField'] = isset($text['onlyField']) ? $text['onlyField'] : false;
		$text['extraAtt'] = isset($text['extraAtt']) ? $text['extraAtt'] : '';
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module.'/displaybox.tpl.php');
		$main_content=$main_content->parse();
		$fields = array("%LABEL%","%CLASS%","%VALUE%");
		$fields_replace = array($text['label'],$text['class'],$text['value']);
		return str_replace($fields,$fields_replace,$main_content);
	}
	public function getPageContent(){
		$final_result = NULL;
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module."/".$this->module.".tpl.php");
		$main_content->breadcrumb = $this->getBreadcrumb();
		$final_result = $main_content->parse();
		return $final_result;
	}
}
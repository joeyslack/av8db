<?php
class Language extends Home{
	public $languageName;
	public $isActive;
	public $data = array();
	public function __construct($id=0, $searchArray=array(), $type='') {
		$this->data['id'] = $this->id = $id;
		$this->type = ($this->id > 0 ? 'edit' : 'add');
		$this->searchArray = $searchArray;
		parent::__construct();
		$this->table = 'tbl_language';
		if($this->id>0){
			$qrySel = $this->db->select($this->table, array("id","languageName","default_lan","status","created_date","url_constant"),array("id"=>$id))->result();
			$fetchRes = $qrySel;

			$category = $fetchRes['languageName'];
			$this->data['languageName'] = $this->languageName = $fetchRes['languageName'];
			$this->data['status'] = $this->status = $fetchRes['status'];
			$this->data['default_lan'] = $this->default_lan = $fetchRes['default_lan'];
			$this->data['url_constant'] = $this->url_constant = $fetchRes['url_constant'];
		}else{
			$this->data['languageName'] = $this->languageName = '';
			$this->data['status'] = $this->status = 'a';
			$this->data['default_lan'] = $this->default_lan = 'y';
			$this->data['url_constant'] = $this->url_constant = '';
		}
		switch($type){
			case 'add' : {
				$this->data['content'] = (in_array('add',$this->Permission))?$this->getForm():'';
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
				$this->data['content'] =  (in_array('delete',$this->Permission))?json_encode($this->dataGrid()):'';
				break;
			}
			case 'datagrid' : {
				$this->data['content'] =  (in_array('module',$this->Permission))?json_encode($this->dataGrid()):'';
			}
		}
	}
	public function getForm() {
		$content = '';
		$main_content = new Templater(DIR_ADMIN_TMPL.$this->module."/form-nct.tpl.php");
		$main_content = $main_content->parse();
		$default_y=($this->default_lan=='y'?'checked':'');
		$default_n=($this->default_lan!='y'?'checked':'');
		$status_a=($this->status == 'a' ? 'checked':'');
		$status_d=($this->status != 'a' ? 'checked':'');
		$fields = array("%MEND_SIGN%","%LANGUAGE_NAME%","%DEFAULT_Y%","%DEFAULT_N%","%STATUS_A%","%STATUS_D%","%TYPE%","%ID%","%URL_CONSTANT%");
		$fields_replace = array(MEND_SIGN,$this->data['languageName'],$default_y,$default_n,$status_a,$status_d,$this->type,$this->id,$this->data['url_constant']);
		$content=str_replace($fields,$fields_replace,$main_content);
		return sanitize_output($content);
	}
	public function dataGrid() {
		$content = $operation = $whereCond = $totalRow = NULL;
		$result = $tmp_rows = $row_data = array();
		extract($this->searchArray);
		$chr = str_replace(array('_', '%'), array('\_', '\%'),$chr );
		$whereCond = array('status !='=>'t');
		if(isset($chr) && $chr != '') {
			$whereCond = $whereCond + array("and languageName LIKE"=> "%$chr%");
		}
		if(isset($sort))
			$sorting = $sort.' '. $order;
		else
			$sorting = 'default_lan ASC';
		if($sort == 'id')
			$sorting = 'id DESC';
		$totalRow = $this->db->count($this->table, $whereCond);
		$qrySel = $this->db->select($this->table,array("id","languageName","default_lan","status","created_date"), $whereCond, " ORDER BY $sorting limit $offset , $rows" )->results();
		foreach($qrySel as $fetchRes) {
			$id =  $fetchRes['id'];
			$status = $fetchRes['status'];
			$status = ($status=='a')?"checked":"";
			$disable = 'disabled="disabled"';
			$default_status = ($fetchRes['default_lan']=='y')?"checked":"";
			$default_lan = (isset($fetchRes['default_lan']))?$fetchRes['default_lan']:"";
			$switch  =$this->toggel_switch(array("action"=>"ajax.".$this->module.".php?id=".$fetchRes['id']."","check"=>$status,'extraAtt'=>'data-switch_action="update_status" '.(($fetchRes['default_lan'] == 'y')?$disable : '') ));
			$default  =$this->toggel_switch(array("action"=>"ajax.".$this->module.".php?id=".$fetchRes['id']."","check"=>$default_status,'extraAtt'=>'data-switch_action="update_default" '.(($fetchRes['default_lan'] == 'y' || $fetchRes['status'] == 'd' )?$disable:'').' '));
			//$switch=($default_lan!='y')?$switch:'';
			$operation = (in_array('edit',$this->Permission))?$this->operation(array("href"=>"ajax.".$this->module.".php?action=edit&id=".$fetchRes['id']."","class"=>"btn default btn-xs black btnEdit","value"=>'<i class="fa fa-edit"></i>&nbsp;Edit')):'';
			$operation .=(in_array('delete',$this->Permission) && $default_lan!='y')?'&nbsp;&nbsp;'.$this->operation(array("href"=>"ajax.".$this->module.".php?action=delete&id=".$fetchRes['id']."","class"=>"btn default btn-xs red btn-delete","value"=>'<i class="fa fa-trash-o"></i>&nbsp;Delete')):'';
			$final_array = array($id,$fetchRes["languageName"].(($default_lan=='y')?' (Default Language)':''),$fetchRes["created_date"]);
			//if(in_array('status',$this->Permission)){
				$final_array =  array_merge($final_array, array($default,$switch));
			//}
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
		$final_result = $main_content->parse();
		return $final_result;
		
	}
}
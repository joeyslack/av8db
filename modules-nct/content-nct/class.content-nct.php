<?php

class Content {

    public $page_name;
    public $page_title;
    public $meta_keyword;
    public $meta_desc;
    public $page_desc;
    public $isActive;
    public $data = array();

    public function __construct($id = 0) {
        global $db, $fields, $sessCataId,$lId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = 'content-nct';
        $this->table = 'tbl_content';
        $this->type = ($this->id > 0 ? 'edit' : 'add');

        if ($this->id > 0) {
            $qrySel = $this->db->select("tbl_content", "*", array("pId" => $id))->result();
            $fetchRes = $qrySel;
            
            $this->data['pId'] = $this->pId = $fetchRes['pId'];
            $this->data['pageTitle'] = $this->pageTitle = $fetchRes['pageTitle_'.$lId];
            $this->data['metaKeyword'] = $this->metaKeyword = $fetchRes['metaKeyword_'.$lId];
            $this->data['metaDesc'] = $this->metaDesc = $fetchRes['metaDesc_'.$lId];
            $this->data['pageDesc'] = $this->pageDesc = $fetchRes['pageDesc_'.$lId];

            $this->data['page_slug'] = $this->page_slug = $fetchRes['page_slug'];
            $this->data['isActive'] = $this->isActive = $fetchRes['isActive'];
            $this->data['createdDate'] = $this->createdDate = $fetchRes['createdDate'];
        } 
    }

    public function getPageTitle() {
        return filtering($this->pageTitle);
    }

    public function getPageDesc() {
        return filtering($this->pageDesc, 'output', 'text');
    }

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");

        $main_content->page_title = $this->getPageTitle();
        $main_content->page_content = $this->getPageDesc();

        $final_result = $main_content->parse();
        return $final_result;
    }

}

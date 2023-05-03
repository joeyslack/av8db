<?php

class Statistics extends Home {

    public function __construct() {
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
    }

   function getPageContent() {

        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        
        $main_content->breadcrumb = $this->getBreadcrumb();
        
        $final_result = $main_content->parse();
        return $final_result;
    }

}

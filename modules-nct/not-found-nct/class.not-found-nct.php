<?php

class Not_found {

    function __construct() {
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
    }

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");

        $final_result = $main_content->parse();
        return $final_result;
    }

}

?>
<?php
class Unsubscribe extends Home {
    function __construct($module = "", $id = 0, $token = "", $reffToken = "") {
        foreach ($GLOBALS as $key => $values) {
            $this -> $key = $values;
        }
        $this -> module = $module;
        $this -> id = $id;

    }

    public function getPageContent() {
        $main_content = new Templater(DIR_TMPL . $this -> module . "/" . $this -> module . ".tpl.php");
        $main_content_parsed = $main_content->parse();
        $fields_replace = array();
        $fields = array();
        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);
        return $final_result;
    }

}
?>

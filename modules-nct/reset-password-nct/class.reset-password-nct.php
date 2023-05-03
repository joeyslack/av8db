<?php

class ResetPassword {

    function __construct() {
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
    }

    public function getPageContent($activationToken) {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        
        $main_content->set('hidd', $activationToken);
        
        $final_result = $main_content->parse();
        return filtering($final_result, 'output', 'text');
    }

}

?>
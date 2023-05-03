<?php

class Job extends Home {

    function __construct() {
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
    }

    public function getPageContent() {
        $final_result = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $final_result = $main_content->parse();


        return $final_result;
    }

}

?>

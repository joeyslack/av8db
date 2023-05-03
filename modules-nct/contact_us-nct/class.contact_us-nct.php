<?php
class Contact extends Home{
    function __construct($current_user_id,$platform='web') {
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->platform = $platform;
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);

        $qrySel = $this->db->select("tbl_users", "*", array("id" => $this->current_user_id))->result();
            $fetchRes = $qrySel;
            $this->id = $fetchRes['id'];
            $this->firstName = filtering($fetchRes['first_name'], 'input', 'string');
            $this->lastName = filtering($fetchRes['last_name'], 'input', 'string');
            $this->email = filtering($fetchRes['email_address'], 'input', 'text');
         
    }
	

	
	public function getPageContent() {
        $final_result = NULL;
		$main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");

        $contact_form_tpl_parsed = $main_content->parse();

        $fields = array("%FIRST_NAME%","%LAST_NAME%","%EMAIL_ADDRESS%","%READONLY%");
        $first_name = $last_name = $email_address = $readonly = '';
        if (isset($this->current_user_id) && $this->current_user_id != "") {
            $user_id = $this->id;
            $first_name = $this->firstName;
            $last_name = $this->lastName;
            $email_address = $this->email;
            $readonly = ' readonly="readonly" ';
        }
        $fields_replace = array($first_name,$last_name,$email_address,$readonly);
        $contact_form_tpl_replaced = str_replace($fields, $fields_replace, $contact_form_tpl_parsed);
 
        return $contact_form_tpl_replaced;
	}
}

?>

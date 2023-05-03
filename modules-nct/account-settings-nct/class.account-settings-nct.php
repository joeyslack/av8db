<?php

class Account_settings extends Profile {

    function __construct($current_user_id = 0, $platform='web') {
        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->platform = $platform;
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
    }

    public function getPageContent() {
        $final_result = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");

        $change_password_form_tpl = new Templater(DIR_TMPL . $this->module . "/change-password-form-nct.tpl.php");
        $change_password_form_tpl_parsed = $change_password_form_tpl->parse();

        $main_content->set('change_password_form', $change_password_form_tpl_parsed);
        $main_content->set('membership_plan', $this->getSubscribedMembershipPlan($this->current_user_id));

        $get_account_settings = $this->db->select("tbl_notification_settings", array('send_connection_request','receive_invitation_group','accept_connection','apply_job','follow_company','like_comment_share','accept_group','follow_user'), array("user_id" => $this->current_user_id))->result();
        if (!$get_account_settings) {
            $lastId = $this->db->insert("tbl_notification_settings", array("user_id" => $this->current_user_id))->getLastInserId();
            
            if($lastId) {
                $get_account_settings = $this->db->select("tbl_notification_settings", array('send_connection_request','receive_invitation_group','accept_connection','apply_job','follow_company','like_comment_share','accept_group','follow_user'), array("user_id" => $this->current_user_id))->result();
            }
        }
        
        $notification_settings_tpl = new Templater(DIR_TMPL . $this->module . "/notification-settings-nct.tpl.php");
        $notification_settings_tpl_parsed = $notification_settings_tpl->parse();

        $main_content->set('notification_settings', $notification_settings_tpl_parsed);
        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%SEND_CONNECTION_REQUEST%",
            "%RECEIVE_INVITATION_GROUP%",
            "%ACCEPT_CONNECTION%",
            "%APPLY_JOB%",
            "%FOLLOW_COMPANY%",
            "%LIKE_COMMENT_SHARE%",
            "%ACCEPT_GROUP%",
            "%FOLLOW_USER%"
        );
        $checked = (($this->platform == 'app') ? 'y' : 'checked="checked"');
        $notchecked = (($this->platform == 'app') ? 'n' : '');

        $field_replace = array(
            ( ( $get_account_settings['send_connection_request'] == 'y' ) ? $checked : $notchecked ),
            ( ( $get_account_settings['receive_invitation_group'] == 'y' ) ? $checked : $notchecked ),
            ( ( $get_account_settings['accept_connection'] == 'y' ) ? $checked : $notchecked ),
            ( ( $get_account_settings['apply_job'] == 'y' ) ? $checked : $notchecked ),
            ( ( $get_account_settings['follow_company'] == 'y' ) ? $checked : $notchecked ),
            ( ( $get_account_settings['like_comment_share'] == 'y' ) ? $checked : $notchecked ),
            ( ( $get_account_settings['accept_group'] == 'y' ) ? $checked : $notchecked ),
            ( ( $get_account_settings['follow_user'] == 'y' ) ? $checked : $notchecked ),
        );
        if($this->platform == 'app'){
            $final_result = $get_account_settings;
        } else {
            $final_result = str_replace($fields, $field_replace, $main_content_parsed);
        }


        return $final_result;
    }

    public function processChangePassword($user_id) {

        $response = array();
        $response['status'] = false;

        $password = filtering($_POST['old_password'], 'input');
        $new_password = filtering($_POST['password'], 'input');
        $confirm_new_password = filtering($_POST['confirm_password'], 'input');
        if($new_password!= '' && $confirm_new_password != '' && $password !=''){
            if ($new_password == $confirm_new_password) {
                $get_user_details = $this->db->select('tbl_users','*',array('id'=>$this->current_user_id,'password'=>md5($password)))->result(); 
                if ($get_user_details) {
                    if ($new_password == $password) {
                        if ($company_name == '') {
                            $response['error'] = LBL_BOTH_PASS_SAME;
                            return $response;
                        }
                    } else {
                        $this->db->update("tbl_users", array("password" => md5($confirm_new_password)), array("id" => $this->current_user_id));
                        $response['status'] = true;
                        $response['success'] = LBL_PASSWORD_UPDATED;
                        return $response;
                    }
                } else {
                    $response['error'] = LBL_WRONG;
                    return $response;
                }
            } else {
                $response['error'] = LBL_PASS_DIDNT_MATCH;
                return $response;
            }
        } else {
            $response['error'] = ERROR_ADD_EDIT_EDUCATION_FILL_ALL_MANDATORY_FIELDS ;
            

            return $response;

        }
        
    }

    public function processUpdateEmailpreference($user_id){
        
        $column_name = filtering($_REQUEST['column_name'], 'input');
        $column_value = filtering($_REQUEST['column_value'], 'input');
        $response['status'] = false;

        if($column_name != '' && $column_value != ''){
            $affected_rows = $this->db->update("tbl_notification_settings", array($column_name => $column_value, "updated_on" => date('Y-m-d H:i:s')), array("user_id" => $user_id))->affectedRows();

            if ($affected_rows && $affected_rows > 0) {
                $response['operation_status'] = "success";
                $response['message'] = LBL_SUCCESS;
                $response['status'] = true;
            } else {
                $response['operation_status'] = "error";
                $response['message'] = ERROR_SOME_ISSUE_TRY_LATER;
            }
        } else {
            $response['operation_status'] = "error";
            $response['message'] = ERROR_SOME_ISSUE_TRY_LATER;
        }
        return $response;
    }

}

?>
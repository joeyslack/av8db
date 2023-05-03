<?php

class Membership_plans extends Home {

    function __construct($current_user_id=0,$platform='web') {
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }

        $this->platform = $platform;
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);
    }

    public function checkPlanSubscriptionCriteria($plan_id) {
        $response = array();
        $response['status'] = false;
        $checkIfExists = $this->db->select("tbl_tariff_plans", "*", array("id" => $plan_id))->result();
        if ($checkIfExists) {
            if ($checkIfExists['status'] == 'a') {
                $response['status'] = true;
                return $response;
            } else {
                $response['error'] = LBL_PLAN_ISNT_ACTIVE;
                return $response;
            }
        } else {
            $response['error'] = LBL_PLAN_TRYING_PURCHASE_DOESNT_EXIST;
            return $response;
        }
    }

    public function processInMailsSubscription($user_id, $no_of_inmails) {
        $response = array();
        $response['status'] = false;

        if (checkWhetherToShowAdhocInmails()) {
            $get_plan_details = $this->db->select("tbl_tariff_plans", "*", array("plan_type" => "ah"))->result();
            if ($get_plan_details) {
                if ($get_plan_details['status'] == 'a') {

                    $plan_id = filtering($get_plan_details['id'], 'input', 'int');
                    $price = filtering($get_plan_details['price'], 'input', 'float');
                    $invoice_id = generateInvoiceId();

                    if ($price) {
                        $total_price = $price * $no_of_inmails;
                        
                        $subscription_history_array = array(
                            "user_id" => $user_id,
                            "plan_id" => filtering($get_plan_details['id'], 'input', 'int'),
                            "plan_type" => filtering($get_plan_details['plan_type'], 'input'),
                            "plan_name" => filtering($get_plan_details['plan_name'], 'input'),
                            "plan_description" => filtering($get_plan_details['plan_description'], 'input'),
                            "plan_duration" => filtering($get_plan_details['plan_duration'], 'input', 'int'),
                            "plan_duration_unit" => filtering($get_plan_details['plan_duration_unit'], 'input'),
                            "inmails_received" => $no_of_inmails,
                            "inmails_outstanding" => $no_of_inmails,
                            "price" => $total_price,
                            "subscribed_on" => date("Y-m-d H:i:s")
                        );

                        $subscription_id = $this->db->insert("tbl_subscription_history", $subscription_history_array)->getLastInsertId();

                        $insert_array = array(
                            "user_id" => $user_id,
                            "invoice_id" => $invoice_id,
                            "plan_id" => $plan_id,
                            "subscription_id" => $subscription_id,
                            "unit_price" => $price,
                            "quantity" => $no_of_inmails,
                            "total_price" => $total_price,
                            "added_on" => date("Y-m-d H:i:s")
                        );

                        $ph_id = $this->db->insert("tbl_payment_history", $insert_array)->getLastInsertId();
                        if ($ph_id) {
                            $response['status'] = true;
                            $response['invoice_id'] = $invoice_id;
                            return $response;
                        } else {
                            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                            return $response;
                        }
                    } else {
                        $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                        return $response;
                    }
                } else {
                    $response['error'] = LBL_PLAN_ISNT_ACTIVE;
                    return $response;
                }
            } else {
                $response['error'] = LBL_PLAN_TRYING_PURCHASE_DOESNT_EXIST;
                return $response;
            }
        } else {
            $response['error'] = LBL_INMAIL_SUBCRIPTION_DESABLED;
            return $response;
        }
    }

    public function processPlanSubscription($user_id, $plan_id) {
        $response = array();
        $response['status'] = false;

        $get_plan_details = $this->db->select("tbl_tariff_plans", "*", array("id" => $plan_id))->result();
        if ($get_plan_details) {
            if ($get_plan_details['status'] == 'a') {

                $price = filtering($get_plan_details['price'], 'input', 'float');
                $invoice_id = generateInvoiceId(3);

                if ($price) {

                    $subscription_history_array = array(
                        "user_id" => $user_id,
                        "plan_id" => filtering($get_plan_details['id'], 'input', 'int'),
                        "plan_type" => filtering($get_plan_details['plan_type'], 'input'),
                        "plan_name" => filtering($get_plan_details['plan_name'], 'input'),
                        "plan_description" => filtering($get_plan_details['plan_description'], 'input'),
                        "plan_duration" => filtering($get_plan_details['plan_duration'], 'input', 'int'),
                        "plan_duration_unit" => filtering($get_plan_details['plan_duration_unit'], 'input'),
                        "inmails_received" => filtering($get_plan_details['no_of_inmails'], 'input', 'int'),
                        "inmails_outstanding" => filtering($get_plan_details['no_of_inmails'], 'input', 'int'),
                        "price" => filtering($get_plan_details['price'], 'input', 'float'),
                        "subscribed_on" => date("Y-m-d H:i:s"),
                        "expires_on"  => date('Y-m-d H:i:s', strtotime('+1 year'))
                    );

                    $subscription_id = $this->db->insert("tbl_subscription_history", $subscription_history_array)->getLastInsertId();

                    $insert_array = array(
                        "user_id" => $user_id,
                        "invoice_id" => $invoice_id,
                        "plan_id" => $plan_id,
                        "subscription_id" => $subscription_id,
                        "unit_price" => filtering($get_plan_details['price']),
                        "quantity" => 1,
                        "total_price" => filtering($get_plan_details['price']),
                        "added_on" => date("Y-m-d H:i:s")
                    );
                    //echo "<pre>";print_r($insert_array);exit;
                    $ph_id = $this->db->insert("tbl_payment_history", $insert_array)->getLastInsertId();
                    $final_invoice_id = $invoice_id.$ph_id;
                    $payment_update = array('invoice_id'=>$final_invoice_id);
                    $this->db->update("tbl_payment_history", $payment_update, array("id" => $ph_id));

                    if ($ph_id) {
                        $response['status'] = true;
                        $response['invoice_id'] = $final_invoice_id;
                        return $response;
                    } else {
                        $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                        return $response;
                    }
                } else {
                    $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                    return $response;
                }
            } else {
                $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                return $response;
            }
        } else {
            $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
            return $response;
        }
    }

    public function processPlanSubscriptionForFJ($user_id, $plan_id, $job_id) {
        $response = array();
        $response['status'] = false;
        $get_plan_details = $this->db->select("tbl_tariff_plans", "*", array("id" => $plan_id))->result();

        if ($get_plan_details) {
            if ($get_plan_details['status'] == 'a') {

                $price = filtering($get_plan_details['price'], 'input', 'float');
                $invoice_id = generateInvoiceId();

                if ($price) {

                    $subscription_history_array = array(
                        "user_id" => $user_id,
                        "plan_id" => filtering($get_plan_details['id'], 'input', 'int'),
                        "plan_type" => filtering($get_plan_details['plan_type'], 'input'),
                        "plan_name" => filtering($get_plan_details['plan_name'], 'input'),
                        "plan_description" => filtering($get_plan_details['plan_description'], 'input'),
                        "plan_duration" => filtering($get_plan_details['plan_duration'], 'input', 'int'),
                        "plan_duration_unit" => filtering($get_plan_details['plan_duration_unit'], 'input'),
                        "inmails_received" => filtering($get_plan_details['no_of_inmails'], 'input', 'int'),
                        "inmails_outstanding" => filtering($get_plan_details['no_of_inmails'], 'input', 'int'),
                        "price" => filtering($get_plan_details['price'], 'input', 'float'),
                        "subscribed_on" => date("Y-m-d H:i:s")
                    );
                    $subscription_id = $this->db->insert("tbl_subscription_history", $subscription_history_array)->getLastInsertId();
                    $insert_array = array(
                        "user_id" => $user_id,
                        "invoice_id" => $invoice_id,
                        "plan_id" => $plan_id,
                        "subscription_id" => $subscription_id,
                        "unit_price" => filtering($get_plan_details['price']),
                        "quantity" => 1,
                        "job_id"=>$job_id,
                        "total_price" => filtering($get_plan_details['price']),
                        "added_on" => date("Y-m-d H:i:s")
                    );

                    $ph_id = $this->db->insert("tbl_payment_history", $insert_array)->getLastInsertId();
                    if ($ph_id) {
                        $response['status'] = true;
                        $response['invoice_id'] = $invoice_id;
                        $plan_duration = $get_plan_details['plan_duration'];
                        $plan_duration_unit = $get_plan_details['plan_duration_unit'];
                        if($plan_duration_unit == 'w') {
                            $added_days = 7;
                        } else if($plan_duration_unit == 'm') {
                            $added_days = 30;
                        }
                        $total_added_days = $plan_duration * $added_days;
                        $featured_till_date = $this->db->select("tbl_jobs", array('featured_till'), array('id' => $job_id))->result();
                        if($featured_till_date['featured_till'] == '0000-00-00 00:00:00') {
                            $date = date('Y-m-d H:i:s');
                        } else {
                            $date = $featured_till_date['featured_till'];
                        }
                        $added_date = date('Y-m-d H:i:s', strtotime($date. ' + '.$total_added_days.' days'));
                        //$affectedRows = $this->db->update("tbl_jobs", array('is_featured' => 'y' , 'featured_till' => $added_date), array('id' => $job_id))->affectedRows();
                        //$affectedRows = $this->db->update("tbl_jobs", array( 'featured_till' => $added_date), array('id' => $job_id))->affectedRows();
                        return $response;
                    } else {
                        $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME ;
                        return $response;
                    }
                } else {
                    $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                    return $response;
                }
            } else {
                $response['error'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
                return $response;
            }
        } else {
            $response['error'] = LBL_PLAN_TRYING_PURCHASE_DOESNT_EXIST ;
            return $response;
        }
    }    

    public function getAdhocInmailsDetails($no_of_inmails) {
        $final_result = NULL;

        $adhoc_inmails_details_tpl = new Templater(DIR_TMPL . $this->module . "/adhoc-inmails-details-nct.tpl.php");
        $adhoc_inmails_details_tpl_parsed = $adhoc_inmails_details_tpl->parse();

        $adhoc_plan_details = $this->db->select("tbl_tariff_plans", "*", array("plan_type" => "ah"))->result();

        $fields = array(
            "%UNIT_PRICE%",
            "%NO_OF_INAMILS%",
            "%TOTAL_PRICE%",
            "%MEMBERSHIP_PLAN_URL%"
        );

        $unit_price = filtering($adhoc_plan_details['price'], 'output', 'float');
        $total_price = $unit_price * $no_of_inmails;

        $fields_repalce = array(
            CURRENCY_SYMBOL . $unit_price,
            $no_of_inmails,
            CURRENCY_SYMBOL . $total_price,
            SITE_URL . "membership-plans"
        );

        $final_result = str_replace($fields, $fields_repalce, $adhoc_inmails_details_tpl_parsed);

        return $final_result;
    }

    public function getPlanDetails($plan_id) {
        $final_result = NULL;

        $subscription_details_tpl = new Templater(DIR_TMPL . $this->module . "/subscription-details-nct.tpl.php");
        $subscription_details_tpl_parsed = $subscription_details_tpl->parse();

        $plan_details = $this->db->select("tbl_tariff_plans", "*", array("id" => $plan_id))->result();

        $fields = array(
            "%PLAN_ID_ENCRYPTED%",
            "%PLAN_NAME%",
            "%PRICE%",
            "%NO_OF_INMAILS%",
            "%MEMBERSHIP_PLAN_URL%",
            "%PLAN_DURATION%"
        );
        //echo "<pre>";print_r($plan_details);exit;
        $unit = (filtering($plan_details['plan_duration_unit']) == 'm') ? 'month' : 'year';
        $fields_repalce = array(
            base64_encode($plan_details['id']),
            filtering($plan_details['plan_name_'.$this->lId]),
            CURRENCY_SYMBOL . filtering($plan_details['price'], 'output', 'float'),
            filtering($plan_details['no_of_inmails'], 'output', 'int'),
            SITE_URL . "membership-plans",
            filtering($plan_details['plan_duration']).' '.$unit
        );

        $final_result = str_replace($fields, $fields_repalce, $subscription_details_tpl_parsed);
        
        return $final_result;
    }

    public function getPlanDetailsForFJ($plan_id, $job_id) {
        $final_result = NULL;
        $featured_details_tpl = new Templater(DIR_TMPL . $this->module . "/featured-job-details-nct.tpl.php");
        $featured_details_tpl_parsed = $featured_details_tpl->parse();

        $plan_details = $this->db->select("tbl_tariff_plans", "*", array("id" => $plan_id))->result();

        $fields = array(
            "%PLAN_ID_ENCRYPTED%",
            "%JOB_ID_ENCRYPTED%",
            "%PLAN_NAME%",
            "%PRICE%",
            "%PLAN_DURATION%",
            "%JOB_URL%",
        );

        $plan_duration_unit = $plan_details['plan_duration_unit'] == 'w' ? 'Week' : 'Month';
        $plan_name = filtering($plan_details['plan_name_'.$this->lId]);
        $fields_repalce = array(
            base64_encode($plan_details['id']),
            encryptIt($job_id),
            $plan_name,
            CURRENCY_SYMBOL . filtering($plan_details['price'], 'output', 'float'),
            filtering($plan_details['plan_duration'], 'output', 'int') . " " . $plan_duration_unit,
            SITE_URL . "jobs/my-jobs"
        );

        $final_result = str_replace($fields, $fields_repalce, $featured_details_tpl_parsed);
        return $final_result;
    }

    public function getMembershipPlans() {
        $final_result = $plans = NULL;
        $membership_plans_ul_tpl = new Templater(DIR_TMPL . $this->module . "/membership-plans-ul-nct.tpl.php");
        $membership_plans = $this->db->select("tbl_tariff_plans", "*", array("plan_type" => 'r', "status" => 'a'))->results();
        if ($membership_plans) {
            //echo "<pre>";print_r($membership_plans);exit;
            $single_membership_plan_tpl = new Templater(DIR_TMPL . $this->module . "/single-membership-plan-nct.tpl.php");
            $single_membership_plan_tpl_parsed = $single_membership_plan_tpl->parse();
            $fields = array("%PLAN_COLOR_CLASS%","%PLAN_IMG%","%PLAN_NAME%","%PLAN_DESCRIPTION%","%NO_OF_INMAILS%","%PRICE_WITH_CURRENCY_SYMBOL%","%PLAN_SUBSCRIPTION_URL%","%plan_duration%","%PLAN_LABEL%","%active%",'%PLAN_ID%','%ISFERRYPILOT%',"%HIDE%");
            $plan_color_icons = array(
                array("color" => "","icon" => "{SITE_THEME_IMG}plan-ico1.png"),
                array("color" => "purple-bg","icon" => "{SITE_THEME_IMG}plan-ico2.png"),
                array("color" => "orange-bg","icon" => "{SITE_THEME_IMG}plan-ico3.png")
            );
            $currentPlanId = $this->db->pdoQuery("SELECT plan_id FROM tbl_subscription_history sh WHERE sh.plan_type = ? AND sh.user_id = ? AND sh.isActive= ? ORDER BY sh.id DESC limit 0,1",array('r',$this->session_user_id,'y'))->result();
            //echo $currentPlanId['plan_id'];exit;
            for ($i = 0; $i < count($membership_plans); $i++) {
                $plan_unit = $plan_duration = '';
                $plan_id = filtering($membership_plans[$i]['id'], 'input', 'int');
                $plan_subscription_url = SITE_URL . "subscribe-plan/" . encryptIt($plan_id);
                $plan_name = filtering($membership_plans[$i]['plan_name_'.$this->lId]);
                $plan_description = filtering($membership_plans[$i]['plan_description_'.$this->lId]);
                $no_of_inmails = filtering($membership_plans[$i]['no_of_inmails'], 'output', 'int');
                $plan_duration = filtering($membership_plans[$i]['plan_duration'], 'output', 'int');
                $plan_unit = ($membership_plans[$i]['plan_duration_unit'] == 'm') ? LBL_MONTH_SMALL : 'year';
                $price = CURRENCY_SYMBOL . filtering($membership_plans[$i]['price'], 'output', 'float');
                $planLabel = ($currentPlanId['plan_id'] == $membership_plans[$i]['id']) ? LBL_CURRENT_PLAN : LBL_SELECT_PLAN;
                $planActive = ($currentPlanId['plan_id'] == $membership_plans[$i]['id']) ? 'active' : '';
                $isFerryPilot = ($plan_name == 'Ferry Pilot' || $plan_id == '8') ? 'hide' : '';
                if($plan_name == 'Ferry Pilot' || $plan_id == '8'){
                    $user_info = $this->db->select('tbl_users_licenses_endorsement', array('id'),array('user_id' => $this->session_user_id,'licenses_id' => '1','verification_status' => 'y'))->result();
                    if($user_info['id'] > 0 && $user_info != ''){
                         $hide = '';
                    }else{
                        $hide = 'hide';
                    }
                }else{
                    $hide = '';
                }
                $fields_replace = array(
                    $plan_color_icons[$i]['color'],
                    $plan_color_icons[$i]['icon'],
                    $plan_name,
                    $plan_description,
                    $no_of_inmails,
                    $price,
                    $plan_subscription_url,
                    $plan_duration.'-'.$plan_unit,
                    $planLabel,
                    $planActive,
                    encryptIt($plan_id),
                    $isFerryPilot,
                    $hide
                );
                if($this->platform == 'app'){
                    $plans[] = array(
                        'id'=>$plan_id,
                        'icon'=>$plan_color_icons[$i]['color'],
                        'name'=>$plan_name,
                        'description'=>$plan_description,
                        'no_of_inmails'=>$no_of_inmails,
                        'price'=>$price.'/'.$plan_duration.'-'.$plan_unit
                    );
                } else {
                    $plans .= str_replace($fields, $fields_replace, $single_membership_plan_tpl_parsed);
                }
            }
        }
        if($this->platform == 'app'){
            $final_result = $plans;
        } else {
            $membership_plans_ul_tpl->set('plans', $plans);
            $final_result = $membership_plans_ul_tpl->parse();
        }
        return $final_result;
    }

    public function getPageContent() {
        $final_result = $adhoc_inmail_form = NULL;
        $unit_price = 0.00;

        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->set('membership_plans', $this->getMembershipPlans());

        if (checkWhetherToShowAdhocInmails()) {
            $adhoc_inmails_form_tpl = new Templater(DIR_TMPL . $this->module . "/adhoc-inmails-form.tpl.php");
            $adhoc_inmail_form = $adhoc_inmails_form_tpl->parse();

            $adhoc_plan_details = $this->db->select("tbl_tariff_plans", "*", array("plan_type" => "ah"))->result();

            $unit_price = filtering($adhoc_plan_details['price'], 'output', 'float');
        }

        $main_content->set('adhoc_inmail_form', $adhoc_inmail_form);
        $main_content_parsed = $main_content->parse();

        $fields = array(
            "%USER_NAME_FULL%",
            "%ADHOC_INMAILS_UNIT_PRICE%",
            "%CURRENCY_SYMBOL%",
            "%ADHOC_INMAIL_DESCRIPTION%",
            "%PENDING_INMAIL%",
            "%REMAINING_DAYS%",
            "%PLAN_ID%",
            "%CLASS_HIDE%"


        );

        $user_inmails = $this->db->select("tbl_user_inmails", "*", array("user_id" => $this->session_user_id))->result();
        $inmails_expires_on = strtotime($user_inmails['inmails_expires_on']);
        $inmails_outstanding = ($user_inmails['inmails_outstanding'] > 0) ? filtering($user_inmails['inmails_outstanding'], 'output', 'int') : '-';
        $no_of_remaining_days = getDateDiff(date("Y-m-d"), date("Y-m-d", $inmails_expires_on), 'day');
        $no_of_remaining_days = ($no_of_remaining_days > 0) ? $no_of_remaining_days : '-';
        $class_hide='';
        /*if($user_inmails == ''){
            $class_hide='hidden';
        }*/
        $adHocDescription = getTableValue("tbl_tariff_plans","plan_description_".$this->lId,array("plan_type"=>'ah',"status"=>'a'));
        $fields_replace = array(
            filtering($_SESSION['first_name']) . " " . filtering($_SESSION['last_name']),
            $unit_price,
            CURRENCY_SYMBOL,
            $adHocDescription,
            $inmails_outstanding,
            $no_of_remaining_days,
            encryptIt($adhoc_plan_details['id']),
            $class_hide

        );

        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

}

?>

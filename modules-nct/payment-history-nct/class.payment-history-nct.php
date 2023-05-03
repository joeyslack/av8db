<?php

class Payment_history {

    public $page_name;
    public $page_title;
    public $meta_keyword;
    public $meta_desc;
    public $page_desc;
    public $isActive;
    public $data = array();

    public function __construct($id = 0,$current_user_id,$platform='web') {

        global $db, $fields, $sessCataId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = 'payment-history-nct';
        $this->table = 'tbl_payment_history';

        $this->platform = $platform;
        $this->session_user_id = $_SESSION['user_id']!=''?$_SESSION['user_id']:'0';
        $this->current_user_id = (($this->platform == 'app') ? $current_user_id : $this->session_user_id);

        
    }

    public function getPageContent($page=1) {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");

        if(isset($_GET['page']) && $_GET['page'] != "" && $_GET['page'] > 1) {
            $page = filtering($_GET['page'], 'input', 'int');
        } else {
            $page = $page;
        }

        $transactions = $this->getTransactions($page);

        $message = $class_msg='';
        if(empty($transactions)){
            $message = LBL_NO_TRANSACTION;
        }
        if($message==''){
            $class_msg='hidden';
        }

        $main_content->set('transactions', $transactions['content']);
        $main_content->set('load',$transactions['load']);
        $main_content->set('pagination', $transactions['pagination']);
        $main_content->set('message', $message);
        $main_content->set('class_msg',$class_msg);
        $final_result_html = $main_content->parse();

        

        $final_result = $main_content->parse();
        return $final_result;
    }

    public function getTransactions($page=1){
        $transaction_html =$load_more_html= '';

        
        $limit = NO_OF_JOBS_PER_PAGE;
        $offset = ($page - 1 ) * $limit;

        $query = "Select ph.*,p.plan_type from tbl_payment_history as ph left join tbl_tariff_plans as p on(ph.plan_id = p.id) where ph.user_id = ? and ph.payment_status = ? order by ph.id desc";

        $count_selection_query = "Select count(ph.id) as no_of_row from tbl_payment_history as ph left join tbl_tariff_plans as p on(ph.plan_id = p.id) where ph.user_id = ? and ph.payment_status = ? order by ph.id desc";

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $getAllResults = $this->db->pdoQuery($count_selection_query,array($this->current_user_id,'c'))->result();
        $totalRows = $getAllResults['no_of_row'];

        //$getShowableResults = $this->db->pdoQuery($query)->results();
        $getShowableResults = $this->db->pdoQuery($query . $limit_query,array($this->current_user_id,'c'))->results();
        //error_reporting(-1);
        //echo '<pre>';print_r($getShowableResults);exit;
        if ($getShowableResults || $this->platform == 'app') {
            $showableRows = count($getShowableResults);

            $single_transaction_tpl = new Templater(DIR_TMPL . $this->module . "/single-transaction-nct.tpl.php");
            $single_transaction_tpl_parsed = $single_transaction_tpl->parse();

            $fields = array("%TRANSACTION_ID%","%DATE%","%AMOUNT%","%PAY_TYPE%");

            for ($i = 0; $i < count($getShowableResults); $i++) {

                $type = $getShowableResults[$i]['plan_type'];
                if($type == 'r'){
                    $type = LBL_REGULAR.' '.LBL_PLAN;   
                }elseif ($type == 'ah') {
                    $type = LBL_ADHOC.' '.LBL_PLAN;
                }elseif ($type == 'fj') {
                    $type = LBL_FEATURED_JOB;
                }
                $transaction_id = filtering($getShowableResults[$i]['transaction_id'], 'output', 'int');
                $transaction_date = convertDate('displayWeb',filtering($getShowableResults[$i]['added_on'], 'output', 'output'));
                $price = filtering($getShowableResults[$i]['total_price'], 'output', 'int');
                $fields_replace = array(
                    $transaction_id,
                    $transaction_date,
                    $price,
                    $type
                );
                if($this->platform == 'app'){
                    $app_array[] = array(
                        'transaction_id'=>$transaction_id,
                        'transaction_date'=>$transaction_date,
                        'price'=>$price,
                        'type'=>$type,
                    );
                } else {
                    $transaction_html .= str_replace($fields, $fields_replace, $single_transaction_tpl_parsed);
                }
            }
            $page_data = getPagerData($totalRows,$limit,$page);

            if ($page_data->numPages > 0 && $page_data->numPages > $page ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . "/load-more-new-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/payment_load/currentPage/" . ($page + 1);
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $load_more_html .= $load_more_li_tpl->parse();
            }
            if($this->platform == 'app'){
                $final_app_array = (!empty($app_array)?$app_array:array());
                $page_data = getPagerData($totalRows, $limit,$page);
                $pagination = array('current_page'=>$page,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
                $final_result_array = array('transactions'=>$final_app_array,'pagination'=>$pagination);
            } else {
                $final_result_array['content']=$transaction_html;
                $final_result_array['load']=$load_more_html;
                $final_result_array['pagination']=getPagination($totalRows, $showableRows, NO_OF_JOBS_PER_PAGE, $page);
            }
            return $final_result_array;
            
        }

    }

}

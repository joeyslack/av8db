<?php

class paymenthistory extends Home {

    public $status;
    public $data = array();

    public function __construct($module, $id = 0, $objPost = NULL, $searchArray = array(), $type = '') {
        global $db, $fields, $sessCataId;
        $this->db = $db;
        $this->data['id'] = $this->id = $id;
        $this->fields = $fields;
        $this->module = $module;
        $this->table = 'tbl_payment_history';

        $this->searchArray = $searchArray;
        parent::__construct();

        switch ($type) {
            case 'datagrid' : {
                    $this->data['content'] = json_encode($this->dataGrid());
                }
        }
    }

    public function dataGrid() {
        $content = $operation = $whereCond = $totalRow = NULL;
        $result = $tmp_rows = $row_data = array();
        extract($this->searchArray);
        $chr = str_replace(array('_', '%'), array('\_', '\%'), $chr);
        $whereCond = '';
        if (isset($chr) && $chr != '') {

            $whereCond = " WHERE  ph.invoice_id LIKE '%" . $chr . "%' OR ph.transaction_id LIKE '%" . $chr . "%'
             OR DATE_FORMAT(ph.added_on, '" . MYSQL_DATE_FORMAT . "') LIKE '%" . $chr . "%' OR DATE_FORMAT(ph.updated_on, '" . MYSQL_DATE_FORMAT . "') LIKE '%" . $chr . "%' 
             OR concat_ws('', '" . CURRENCY_SYMBOL . "',ph.total_price) LIKE '%" . $chr . "%' OR  ( CONCAT(u.first_name, ' ', u.last_name) LIKE  '%" . $chr . "%' )  OR  
            ( IF( payment_status = 'pr', 'Processing', IF( payment_status = 'c', 'Completed', IF(payment_status = 'p', 'Pending', 'Denied') ) ) ) LIKE  '%" . $chr . "%'
            OR  
            ( IF( plan_type = 'r', 'Plan', IF(plan_type = 'fj', 'Featured Job', 'Adhoc Inmail') ) ) LIKE  '%" . $chr . "%'
             ";
        }

        if (isset($sort))
            $sorting = (in_array($sort, array('first_name')) ? 'u.' : (in_array($sort, array('plan_type')) ? 'tf.' : (in_array($sort, array('user_type')) ? 'u.' : 'ph.'))) . $sort . ' ' . $order;
            //$sorting = $sort . ' ' . $order;
        else
            $sorting = 'ph.id DESC';

        $query = "SELECT ph.*, CONCAT(u.first_name, ' ' ,u.last_name) as user_name, tf.plan_type,
                    IF(tf.plan_type = 'r', 'Plan', IF(tf.plan_type = 'fj', 'Featured Job', 'Adhoc Inmail')  ) as paid_for 
                    FROM tbl_payment_history ph 
                    LEFT JOIN tbl_users u on ph.user_id = u.id 
                    LEFT JOIN tbl_tariff_plans tf ON tf.id = ph.plan_id "
                    . $whereCond . " ORDER BY " . $sorting ;

        $query_with_limit = $query . " LIMIT ". $offset.", ".$rows;
        $qrySel = $this->db->pdoQuery($query_with_limit)->results();

        $all_transactions = $this->db->pdoQuery($query)->results();
        $totalRow = count($all_transactions);

        foreach ($qrySel as $fetchRes) {
            $id = $fetchRes['id'];
            //echo "<pre>";print_r($fetchRes);exit;

            
            $payment_status = filtering($fetchRes['payment_status']);
            switch ($payment_status) {
                case 'c':
                    $payment_status_text = "Completed";
                    break;
                case 'pr':
                    $payment_status_text = "Processing";
                    break;
                case 'p':
                    $payment_status_text = "Pending";
                    break;
                case 'd':
                    $payment_status_text = "Denied";
                    break;
                default:
                    $payment_status_text = " - ";
                    break;
            }

           
            $user_name=(filtering($fetchRes["user_name"]) != '')?filtering($fetchRes["user_name"]):'N/A';

            $final_array=array(
                filtering($fetchRes['id'], 'output', 'int'),
                $user_name,
                filtering($fetchRes["invoice_id"]),
                filtering($fetchRes["transaction_id"]),
                filtering(convertDate('onlyDate', $fetchRes["added_on"])),
                filtering($fetchRes['paid_for']),
                $payment_status_text,
                CURRENCY_SYMBOL . filtering($fetchRes["total_price"])

            );


            $row_data[] = $final_array;
        }

        $result["sEcho"] = $sEcho;
        $result["iTotalRecords"] = (int) $totalRow;
        $result["iTotalDisplayRecords"] = (int) $totalRow;
        $result["aaData"] = $row_data;

        return $result;
    }
   

    public function getPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_ADMIN_TMPL . $this->module . "/" . $this->module . ".tpl.php");
        $main_content->breadcrumb = $this->getBreadcrumb();
        $final_result = $main_content->parse();
        return $final_result;
    }

}

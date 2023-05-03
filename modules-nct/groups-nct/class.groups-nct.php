<?php

class Groups extends Home {

    function __construct() {
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
        $this->session_user_id = filtering($_SESSION['user_id'], 'input', 'int');
    }

    public function getGroupsPageContent($type) {
        $final_result = NULL;

        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");

        if (isset($_GET['page']) && $_GET['page'] != "" && $_GET['page'] > 1) {
            $page = filtering($_GET['page'], 'input', 'int');
        } else {
            $page = 1;
        }

        $response = $this->getGroups($this->session_user_id, $type, $page);

        $content = $response['content'];
        $pagination = $response['pagination'];

        
        $main_content_parsed = $main_content->parse();

        $plan = $this->getSubscribedMembershipPlan($this->session_user_id);

        $fields = array(
            "%MY_GROUPS_ACTIVE_CLASS%",
            "%JOINED_GROUPS_ACTIVE_CLASS%",
            '%CONTENT%',
            '%PAGINATION%',
            '%SUBSCRIBED_MEMBERSHIP_PLAN_DETAILS%'
        );

        $my_groups_active_class = $joined_groups_active_class = '';
        if ('my_groups' == $type) {
            $my_groups_active_class = "active";
        } else if ('joined_groups' == $type) {
            $joined_groups_active_class = "active";
        }

        $fields_replace = array(
            $my_groups_active_class,
            $joined_groups_active_class,
            $content,
            $pagination,
            $plan
        );


        $final_result = str_replace($fields, $fields_replace, $main_content_parsed);

        return $final_result;
    }

    public function getGroups($user_id, $type, $currentPage = 1, $getPagination = true,$plateform = 'web') {
        $final_result_array = array();

        $final_result_html = $groups_html = NULL;
        $totalRows = $showableRows = 0;

        $limit = NO_OF_GROUPS_PER_PAGE;
        $offset = ($currentPage - 1 ) * $limit;

        if ('my_groups' == $type) {
            $data_selection_query = "SELECT groups.*,gtypes.group_type_".$this->lId." as group_type ";
            $count_selection_query = "SELECT count(groups.id) as no_of_groups ";

            $query = " FROM tbl_groups groups 
                        LEFT JOIN tbl_group_types gtypes ON groups.group_type_id = gtypes.id
                        WHERE groups.user_id = ? AND groups.status = ? ORDER BY groups.id DESC ";
            $where_array=array($user_id,'a');
        } else if ('joined_groups' == $type) {
            $data_selection_query = "SELECT groups.*,gtypes.group_type_".$this->lId." as group_type ";
            $count_selection_query = "SELECT count(gmembers.id) as no_of_groups ";

            $query = " FROM tbl_group_members gmembers 
                        LEFT JOIN tbl_groups groups ON groups.id = gmembers.group_id 
                        LEFT JOIN tbl_group_types gtypes ON groups.group_type_id = gtypes.id 
                        WHERE gmembers.user_id = ? AND groups.status = ? AND gmembers.action !=  ? AND gmembers.action != ? ORDER BY gmembers.id DESC ";
            $where_array=array($user_id,'a','r','jr');
        }

        $limit_query = " LIMIT " . $limit . " OFFSET " . $offset;

        $getAllResults = $this->db->pdoQuery($count_selection_query . $query,$where_array)->result();
        $totalRows = $getAllResults['no_of_groups'];

        $getShowableResults = $this->db->pdoQuery($data_selection_query . $query . $limit_query,$where_array)->results();

        if ($getShowableResults) {
            $showableRows = count($getShowableResults);

            $groups_ul_tpl = new Templater(DIR_TMPL . $this->module . "/groups-ul-nct.tpl.php");
            $single_group_li_tpl = new Templater(DIR_TMPL . $this->module . "/single-group-li-nct.tpl.php");
            $single_group_li_tpl_parsed = $single_group_li_tpl->parse();

            $fields = array(
                "%GROUP_ID%",
                "%GROUP_NAME%",
                "%GROUP_TYPE%",
                //"%GROUP_INDUSTRY%",
                "%GROUP_LOGO_URL%",
                "%GROUP_URL%",
                "%COUNT_GROUP_MEMBERS%",
                "%MEMEBER_TEXT%",
                "%CONNECTION_COUNT%",
                "%CONNECTION_COUNT_TEXT%",
                "%MANAGE_BTN_HTML%",
            );
            require_once(DIR_MOD . 'common_storage.php');
            $both_group_storage = new storage();
            $src2 = DIR_NAME_GROUP_LOGOS.'/';

            for ($i = 0; $i < count($getShowableResults); $i++) {
                $group_id = filtering($getShowableResults[$i]['id'], 'output', 'int');

                $connection_count = '';
                $user_id_arr = getConnections($user_id);
                if(is_array($user_id_arr) && !empty($user_id_arr)) {

                    $connected_members = $this->db->pdoQuery('SELECT COUNT(*) as total_connection FROM tbl_group_members 
                    WHERE user_id IN ('. implode(",", $user_id_arr) .') AND group_id = ?
                    AND action != ? AND action != ? ',array($group_id,"r","jr"))->result();    

                    $connection_count = $connected_members['total_connection'];
                } else {
                    $connection_count = 0;
                }

                $group_members = $this->db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members 
                    WHERE  group_id = ? 
                    AND action != ? AND action != ? ',array($group_id,"r","jr"))->result();

                $count_group_members = $memeber_text =  $connection_count_text = '';
                
                $connection_count_text = $connection_count > 1 ? LBL_GRP_DTL_MEMBERS_TITLE : LBL_MEMBER_GRP;
                $count_group_members = $group_members['total_members'];
                $memeber_text = $count_group_members > 1 ? LBL_GRP_DTL_MEMBERS_TITLE : LBL_MEMBER_GRP;

                $group_url = get_group_detail_url(filtering($getShowableResults[$i]['id'], 'output', 'int'));

                $groups_img = '';
                $group_logo_url = $both_group_storage->getImageUrl1('av8db',$getShowableResults[$i]['group_logo'],$src2);

                // $group_logo_url = SITE_UPD_GROUP_LOGOS . filtering($getShowableResults[$i]['group_logo'], 'output');

                if($plateform == 'web'){
                    $ck = getimagesize($group_logo_url);
                    if (!empty($ck)) {
                        $group_logo_url = $getShowableResults[$i]['group_logo'] == '' ? '<span class="company-letter-square company-letter">'.ucfirst($getShowableResults[$i]['group_name'][0]).'</span>' : '<img src="'.$group_logo_url.'">';
                    }else{
                        $group_logo_url = '<span title="'.$getShowableResults[$i]['group_name'].'" class="company-letter-square company-letter">' . ucfirst($getShowableResults[$i]['group_name'][0]) . '</span>';
                    }
                }else{
                    $group_logo_url = ($getShowableResults[$i]['group_logo'] != '') ? $group_logo_url : "";
                }

                if($type == 'my_groups') {
                    $manage_group_url_tpl = new Templater(DIR_TMPL . $this->module . "/edit-group-url-nct.tpl.php");
                    $manage_group_url_tpl_parsed = $manage_group_url_tpl->parse();
                    $fields_manage_group = array("%URL%","%ENCRYPTED_GROUP_ID%");
                    $fields_replace_manage_group = array(SITE_URL . "edit-group-form/" . encryptIt($group_id) . '/' .$_SESSION['user_id'],encryptIt($group_id));
                    $manage_btn_html = str_replace($fields_manage_group, $fields_replace_manage_group, $manage_group_url_tpl_parsed);
                } else if($type == 'joined_groups') {
                    $remove_group_url_tpl=new Templater(DIR_TMPL . $this->module . "/remove-group-url-nct.tpl.php");
                    $remove_group_url_tpl_parsed = $remove_group_url_tpl->parse();
                    $fields_remove_group = array("%URL%", "%ID%", "%DATA_ID%");
                    $fields_replace_remove_group = array("javascript:void(0);", "removeJoinedGroup", $group_id);
                    $manage_btn_html = str_replace($fields_remove_group, $fields_replace_remove_group, $remove_group_url_tpl_parsed);
                }
                $grp_name = filtering($getShowableResults[$i]['group_name'], 'output');
                $grp_type = filtering($getShowableResults[$i]['group_type'], 'output');
                //$industry_name = filtering($getShowableResults[$i]['industry_name'], 'output');
                $fields_replace = array(
                    filtering($getShowableResults[$i]['id'], 'output', 'int'),
                    ucwords($grp_name),
                    ucwords($grp_type),
                    //ucwords($industry_name),
                    $group_logo_url,
                    $group_url,
                    $count_group_members,
                    $memeber_text,
                    $connection_count,
                    $connection_count_text,
                    $manage_btn_html,
                );
                if($plateform == 'app'){
                    $app_array[] = array('group_id'=>$getShowableResults[$i]['id'],'group_name'=>$grp_name,'group_type'=>$grp_type,'group_members'=>$count_group_members,'connected_members'=>$connection_count,'group_logo'=>$group_logo_url);
                } else {
                    $groups_html .= str_replace($fields, $fields_replace, $single_group_li_tpl_parsed);
                }
            }
            $page_data = getPagerData($totalRows, NO_OF_GROUPS_PER_PAGE,$currentPage);

            if ($page_data->numPages > 0 && $page_data->numPages > $currentPage ) {

                $load_more_li_tpl = new Templater(DIR_TMPL . "/load-more-new-nct.tpl.php");
                $load_more_link = SITE_URL . "ajax/getGroups_load/currentPage/" . ($currentPage + 1)."/".$type;
                
                $load_more_li_tpl->set('load_more_link', $load_more_link);
                $groups_html .= $load_more_li_tpl->parse();
            }
            if($plateform == 'app'){
                $final_result_array['groups'] = $app_array;
                $page_data = getPagerData($totalRows, NO_OF_GROUPS_PER_PAGE,$currentPage);
                $final_result_array['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$totalRows);
            } else {
                $groups_ul_tpl->set('groups', $groups_html);
                $final_result_html = $groups_ul_tpl->parse();
                $final_result_array['content'] = $final_result_html;
                $final_result_array['pagination'] = getPagination($totalRows, $showableRows, NO_OF_GROUPS_PER_PAGE, $currentPage);
            }
        } else {
            if ($totalRows > 0 && $currentPage > 1) {
                $final_result_array = $this->getGroups($user_id, $type, ( $currentPage - 1));
            } else {
                $no_result_found_tpl = new Templater(DIR_TMPL . $this->module . "/no-result-found-nct.tpl.php");

                if ('my_groups' == $type) {
                    $message = ERROR_YOU_HAVE_NOT_CREATED_ANY_GROUP;
                    $no_result_found_tpl->set('class', 'hidden');

                } else if ('joined_groups' == $type) {
                    $message = ERROR_YOU_HAVE_NOT_JOINED_ANY_GROUP;
                    $no_result_found_tpl->set('class','');


                }

                $no_result_found_tpl->set('message', $message);
                $final_result_html = $no_result_found_tpl->parse();

                $final_result_array['content'] = $final_result_html;
                $final_result_array['pagination'] = "";
            }
        }
        return $final_result_array;
    }
    public function removeJoinedGroup($user_id, $group_id) {

        $response = array();

        $affectedRows = $this->db->delete("tbl_group_members", array("group_id" => $group_id, "user_id" => $user_id))->affectedRows();

        if($affectedRows && $affectedRows > 0) {
            $response['status'] = true;
            $response['msg'] = LBL_GROUP_SUCCESSFULLY_REMOVED;
        } else {
            $response['status'] = false;
            $response['msg'] = ERROR_COM_DET_THERE_SEEMS_ISSUE_TRY_AFTER_SOMETIME;
        }


        return $response;
    }
}
?>
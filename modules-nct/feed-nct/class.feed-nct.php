<?php

class Feed extends Home {

    function __construct($feed_id = '') {
        $this->feed_id = $feed_id;
        parent::__construct();

        foreach ($GLOBALS as $key => $values) {
            $this->$key = $values;
        }
    }

    public function getFeedPageContent() {
        $final_result = NULL;
        $main_content = new Templater(DIR_TMPL . $this->module . "/" . $this->module . ".tpl.php");


        $feeds_container_tpl = new Templater(DIR_TMPL . "feeds-container-nct.tpl.php");
        $feeds_li = getSingleFeed($this->feed_id,'web','',$this->module);
        $feeds_container_tpl->set('feeds_li', $feeds_li);
        $feeds_container_tpl_parsed = $feeds_container_tpl->parse();

        $fields = array(
            "%LIKE_UNLIKE_URL%",
            "%POST_COMMENT_URL%",
            "%POST_AN_UPDATE_URL%",
        );
        $fields_replace = array(
            SITE_URL . "like-unlike",
            SITE_URL . "post-comment",
            SITE_URL . "share-an-update"
        );

        $feed = str_replace($fields, $fields_replace, $feeds_container_tpl_parsed);
        $main_content->set('feed', $feed);
        $final_result = $main_content->parse();
        $fields_new=array("%MEMBERSHIP_PLAN%");
        $fields_replace_new=array($this->getSubscribedMembershipPlan($_SESSION['user_id']));
        $final_content = str_replace($fields_new, $fields_replace_new, $final_result);
        return $final_content;


        
    }

}

<?php
$sqlSettings = $db->select("tbl_site_settings", array("constant", "value"))->results();
foreach ($sqlSettings as $conskey => $consval) {
    define($consval["constant"], $consval["value"]);
}
define("DIR_URL", "/Users/jslack/working/av8db_code/");
define("SALT_FOR_ENCRYPTION", "connectin");
$host = $_SERVER['HTTP_HOST'];
$request_uri = $_SERVER['REQUEST_URI'];
$canonical_url = "http://" . $host . $request_uri;
define('CANONICAL_URL', $canonical_url);
define('YEAR', date("Y"));
define('MEND_SIGN', '<font color="#FF0000">*</font>');
define('AUTHOR', 'NCrypted');
define('GENERATOR', 'ConnectIn 1.0');
define('COPYRIGHT', 'NCrypted Technologies Pvt. Ltd.');
define('ADMIN_NM', 'Administrator');
define('REGARDS', SITE_NM);
define("SITE_INC", SITE_URL . "includes-nct/");
define("DIR_INC", DIR_URL . "includes-nct/");
define("DIR_HYBRIDAUTH", DIR_INC . "hybridauth/hybridauth/");
define("SITE_UPD", SITE_URL . "upload-nct/");
define("DIR_UPD", DIR_URL . "upload-nct/");
define("SITE_MOD", SITE_URL . "modules-nct/");
define("DIR_MOD", DIR_URL . "modules-nct/");
define('SITE_THEME', SITE_URL . 'themes-nct/');
define("DIR_THEME", DIR_URL . "themes-nct/");
define("SITE_THEME_CSS", SITE_URL . "themes-nct/css-nct/");
define('SITE_THEME_FONTS', SITE_URL . "themes-nct/fonts-nct/");
define('SITE_THEME_IMG', SITE_URL . "themes-nct/images-nct/");
define('SITE_THEME_JS', SITE_URL . "themes-nct/js-nct/");
define('DIR_THEME_IMG', DIR_THEME . 'images-nct/');
define("DIR_THEME_CSS", DIR_THEME . "themes-nct/css-nct/");
define('DIR_THEME_FONTS', DIR_THEME . "themes-nct/fonts-nct/");
define('DIR_THEME_JS', DIR_THEME . "themes-nct/js-nct/");
define("SITE_JS", SITE_INC . "javascript-nct/");
define("SITE_PLUGIN", SITE_JS . "plugins-nct/");
//define('SITE_LOGO_URL', SITE_THEME_IMG  . SITE_LOGO);
define('SITE_LOGO_URL', 'https://storage.googleapis.com/av8db/site-images-nct/' . SITE_LOGO);
define("DIR_FUN", DIR_URL . "includes-nct/functions-nct/");
define("DIR_TMPL", DIR_URL . "templates-nct/");
define("DIR_CACHE", DIR_UPD . "cache-nct/");
define('USER_DEFAULT_AVATAR', 'default_profile_pic.png');
define('PRODUCT_DEFAULT_IMAGE', SITE_THEME_IMG . 'product-default-image.jpg');
define('DEFAULT_LANGUAGE_ID', 1);
define('DEFAULT_LANGUAGE_TITLE', 'English');
/* Start Custom upload directory paths and URLs */
define("DIR_NAME_USERS", "users-nct");
define("SITE_UPD_USERS", SITE_UPD . DIR_NAME_USERS . "/");
define("DIR_UPD_USERS", DIR_UPD . DIR_NAME_USERS . "/");

define("SITE_UPD_USERS_COVER", SITE_UPD ."user_cover-nct/");
define("DIR_UPD_USERS_COVER", DIR_UPD . "user_cover-nct/");


define("DIR_NAME_COMPANY_LOGOS", "company-logos-nct");
define("SITE_UPD_COMPANY_LOGOS", SITE_UPD . DIR_NAME_COMPANY_LOGOS . "/");
define("DIR_UPD_COMPANY_LOGOS", DIR_UPD . DIR_NAME_COMPANY_LOGOS . "/");

define("DIR_NAME_COMPANY_BANNER_IMAGES", "company-banner-images-nct");
define("SITE_UPD_COMPANY_BANNER_IMAGES", SITE_UPD . DIR_NAME_COMPANY_BANNER_IMAGES . "/");
define("DIR_UPD_COMPANY_BANNER_IMAGES", DIR_UPD . DIR_NAME_COMPANY_BANNER_IMAGES . "/");

define("DIR_NAME_GROUP_LOGOS", "group-logos-nct");
define("SITE_UPD_GROUP_LOGOS", SITE_UPD . DIR_NAME_GROUP_LOGOS . "/");
define("DIR_UPD_GROUP_LOGOS", DIR_UPD . DIR_NAME_GROUP_LOGOS . "/");

define("DIR_NAME_FEEDS", "feed-images-nct");
define("SITE_UPD_FEEDS", SITE_UPD . DIR_NAME_FEEDS . "/");
define("DIR_UPD_FEEDS", DIR_UPD . DIR_NAME_FEEDS . "/");
/* End Custom upload directory paths and URLs */
/* Start ADMIN SIDE */
define("SITE_ADMIN_URL", SITE_URL . "admin-nct/");
define("SITE_ADM_CSS", ADMIN_URL . "themes-nct/css-nct/");
define("SITE_ADM_IMG", ADMIN_URL . "themes-nct/images-nct/");
define("SITE_ADM_INC", ADMIN_URL . "includes-nct/");
define("SITE_ADM_MOD", ADMIN_URL . "modules-nct/");
define("SITE_ADM_JS", ADMIN_URL . "includes-nct/javascript-nct/");
define("SITE_ADM_UPD", ADMIN_URL . "upload-nct/");
define("SITE_JAVASCRIPT", SITE_URL . "includes-nct/javascript-nct/");
define("SITE_ADM_PLUGIN", ADMIN_URL . "includes-nct/plugins-nct/");
define("SITE_ADM_JAVA", SITE_ADMIN_URL . "includes-nct/javascript-nct/");

define("DIR_ADMIN_URL", DIR_URL . "admin-nct/");
define("DIR_ADMIN_THEME", DIR_ADMIN_URL . "themes-nct/");
define("DIR_ADMIN_TMPL", DIR_ADMIN_URL . "templates-nct/");
define("DIR_ADM_INC", DIR_ADMIN_URL . "includes-nct/");
define("DIR_ADM_MOD", DIR_ADMIN_URL . "modules-nct/");
define("DIR_ADM_PLUGIN", DIR_ADM_INC . "plugins-nct/");
/* End ADMIN SIDE */
define("NMRF", '<div class="no-results">No more results found.</div>');
define("LOADER",'<img alt="Loading.." src="'.SITE_THEME_IMG.'ajax-loader-transparent.gif" class="lazy-loader" />');
define("MESSAGES_LOADER",'<img alt="Loading.." src="'.SITE_THEME_IMG.'mesages-loader.gif" class="lazy-loader" />');
define("PHP_DATE_FORMAT", 'M d, Y');
define("PHP_DATE_FORMAT_MONTH", 'M Y');
define("PHP_DATE_FORMAT_MONTH_YEAR", 'M Y');
define("MYSQL_DATE_FORMAT", '%b %d, %Y');
define("BOOTSTRAP_DATEPICKER_FORMAT", 'M d, yyyy');
define("BOOTSTRAP_DATEPICKER_YEAR_FORMAT", 'yyyy');
/* Start Paypal Settings */
define('SANDBOX_MODE_ENABLED', true);
define('PAYPAL_BN_CODE', 'NCryptedTechnologies_SP_EC');
define('PAYPAL_CURRENCY_CODE', 'USD');
define('CURRENCY_SYMBOL', '$');
define('RETURN_URL', SITE_URL . 'payment_successful');
define('CANCEL_RETURN_URL', SITE_URL . 'transaction_cancelled');
define('NOTIFY_URL', SITE_URL . 'notify/');
/* End Paypal Settings */
//define("GOOGLE_MAPS_API_KEY", "AIzaSyDdUNwDsMUgonNscXdqmZAAWn4B1mFweDM");
//define("GOOGLE_MAPS_API_KEY", "AIzaSyB35vRXA0oFTCnaJo_kq0r_1zu1MhviSWA");

$months_array = array(LBL_MONTH_JANUARY,LBL_MONTH_FEBRUARY,LBL_MONTH_MARCH,LBL_MONTH_APRIL,LBL_MONTH_MAY,LBL_MONTH_JUNE,LBL_MONTH_JULY,LBL_MONTH_AUGEST,LBL_MONTH_SEPTEBER,LBL_MONTH_OCTOBER,LBL_MONTH_NOVEMBER,LBL_MONTH_DECEMBER);
define("MONTHS_ARRAY", serialize($months_array));
define("NO_OF_COMPANIES_PER_PAGE", 5);
define("NO_OF_JOBS_PER_PAGE", 5);
define("NO_OF_SIMILAR_JOBS_PER_PAGE", 4);
define("NO_OF_JOB_APPLICANTS_PER_PAGE", 10);
define("NO_OF_GROUPS_PER_PAGE", 5);
$user_profile_picture_resize_array = array(
    array('width' => 28, 'height' => 28),
    array('width' => 40, 'height' => 40),
    array('width' => 60, 'height' => 60),
    array('width' => 90, 'height' => 90),
    array('width' => 335, 'height' => 335),
);
define("USER_PROFILE_PICTURE_RESIZE_ARRAY", serialize($user_profile_picture_resize_array));
$company_logo_resize_array = array(array('width' => 30, 'height' => 30),array('width' => 300, 'height' => 300));
define("COMPANY_LOGO_RESIZE_ARRAY", serialize($company_logo_resize_array));
$group_logo_resize_array = array(
    array('width' => 30, 'height' => 30),
    array('width' => 300, 'height' => 300),
    array('width' => 40, 'height' => 40),
);
define("GROUP_LOGO_RESIZE_ARRAY", serialize($group_logo_resize_array));
$company_banner_image_resize_array = array(array('width' => 750, 'height' => 250));
define("COMPANY_BANNER_IMAGE_RESIZE_ARRAY", serialize($company_banner_image_resize_array));
$feed_image_resize_array = array(array('width' => 300, 'height' => 300));
define("FEED_IMAGE_RESIZE_ARRAY", serialize($feed_image_resize_array));
define("NO_OF_COMMON_CONNECTION_PER_PAGE", 6);
define("NO_OF_COUNT_PEOPLE_FOR_RANDOM", 12);

define("NO_OF_CONNECTION_PER_PAGE", 10);
define("NO_OF_INVITATION_PER_PAGE", 6);
define("NO_OF_SEARCH_RESULTS_PER_PAGE", 5);
define("NO_OF_NOTIFICATIONS_PER_PAGE", 10);
define("NO_OF_PEOPLE_YOU_KNOW_PER_PAGE", 6);
define("SHOW_FOOTER_STATISTICS", true);
define("MAX_NO_OF_MEMBERS_RANGE_SLIDER", 500);

define("FIREBASE_SERVER_KEY", "AIzaSyCTQloVTS0ER6Q4kEFbdxNzCmkP2lzwMGw");
define("SITE_CONTACTUS", SITE_URL."contactus-nct/");

define("DEFAULT_USET_IMAGE",SITE_THEME_IMG."default-user.png");

define("FERRY_PILOT_PLAN_ID",'8');

$url = explode('/', SIGNUP_URL);
define("URL_TO_MATCH_SIGNUP",'signup');

define("FLIGHT_SCHOLL_ID",'3');
define("FLIGHT_SCHOLL_NAME",'Flight School');

define("COACHING_SERVICE_ID",'10');
define("COACHING_SERVICE_NAME",'Coaching Services');
<?php
function getRatingCount($company_id = 0){
    global $db;
    $rate_data = "SELECT cr.*,u.id as userId,u.first_name,u.last_name,u.cover_photo FROM tbl_companies as c LEFT JOIN tbl_company_rate_review as cr ON c.id = cr.company_id LEFT JOIN tbl_users as u ON u.id = cr.sender_id WHERE cr.company_id = '".$company_id."' ORDER by cr.id DESC";
    $qryRes = $db->pdoQuery($rate_data)->results();
    $totalRes = $db->pdoQuery($rate_data)->affectedRows();
    return $totalRes;
}
function redirectPage($url) {header('Location:' . $url);exit;}
function sanitize_output($buffer) {
    $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s');
    $replace = array('>', '<', '\\1', '');
    return preg_replace($search, $replace, $buffer);
}
function Slug($string) {
    $slug = strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    $slug_exists = slug_exist($slug);
    if ($slug_exists) {
        $i = 1;
        $baseSlug = $slug;
        while (slug_exist($slug)) {
            $slug = $baseSlug . "-" . $i++;
        }
    }
    return $slug;
}
function slug_exist($slug) {
    global $db;
    $sql = "SELECT page_slug FROM tbl_content WHERE page_slug = '" . $slug . "' ";
    $content_page = $db->pdoQuery($sql)->result();
    if ($content_page) {
        return true;
    }
}
function encryptIt($q){return base64_encode($q);}
function decryptIt($q){return base64_decode($q);}
function _print($arry, $bool = false) {echo "<pre>";print_r($arry);echo "</pre>";}
function convertDate($what, $date) {
    if ($what == 'wherecond')
        return date('Y-m-d', strtotime($date));
    else if ($what == 'display')
        return date('d M, Y h:i A', strtotime($date));
    else if ($what == 'displayWeb')
        return date('d M Y', strtotime($date));
    else if ($what == 'onlyDate')
        return date(PHP_DATE_FORMAT, strtotime($date));
    else if ($what == 'monthYear')
        return date(PHP_DATE_FORMAT_MONTH_YEAR, strtotime($date));
    else if ($what == 'onlyMonth')
        return date(PHP_DATE_FORMAT_MONTH, strtotime($date));
    else if ($what == 'gmail')
        return date('D, M d, Y - h:i A', strtotime($date));
    else if ($what == 'onlyDateForCSV')
        return date('M d,Y', strtotime($date));
}
function genrateRandom($length = 8, $seeds = 'alphanum') {
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $seedings['hexidec'] = '0123456789abcdef';
    if (isset($seedings[$seeds])) {
        $seeds = $seedings[$seeds];
    }
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);
    $str = '';
    $seeds_count = strlen($seeds);
    for ($i = 0; $length > $i; $i++) {
        $str .= $seeds{mt_rand(0, $seeds_count - 1)};
    }
    return $str;
}
function generateEmailTemplateSendEmail($type, $arrayCont, $to) {
    global $sessUserId;
    global $db;
    $selectFields = array('subject', 'templates');
    $whereConditions = array('constant' => $type);
    $q = $db->select('tbl_email_templates', $selectFields, $whereConditions)->result();
    $subject = trim(stripslashes($q["subject"]));
    $message = trim(stripslashes($q["templates"]));
    /* Start Replace Global Constants */
    $constants_array = get_defined_constants(true);
    $user_constants_array = $constants_array['user'];

    $user_constants = (array_keys($user_constants_array));
    for ($i = 0; $i < count($user_constants); $i++) {
        $message = str_replace("###" . $user_constants[$i] . "###", "" . $user_constants_array[$user_constants[$i]] . "", $message);
        $subject = str_replace("###" . $user_constants[$i] . "###", "" . $user_constants_array[$user_constants[$i]] . "", $subject);
    }
    /* End Replace Global Constants */
    /* Start Replace Email specific values */
    $array_keys = (array_keys($arrayCont));
    for ($i = 0; $i < count($array_keys); $i++) {
        $message = str_replace("###" . $array_keys[$i] . "###", "" . $arrayCont[$array_keys[$i]] . "", $message);
        $subject = str_replace("###" . $array_keys[$i] . "###", "" . $arrayCont[$array_keys[$i]] . "", $subject);
    }
    /* End Replace Email specific values */
    $return = sendEmailAddress($to, $subject, $message);
    return $return;
}
function generateTemplates($greetings, $regards, $subject, $msgContent) {
    $content = $logo_img = '';
    if ('' != SITE_LOGO) {
        $logo_img = '<img src="' . SITE_THEME_IMG .SITE_LOGO . ' " alt="' . SITE_NM . '" />';
    }
    $content .= '<div style="background:#f0f0f0; border:1px solid #E1E1E1; padding:25px; font-family:Verdana, Geneva, sans-serif">
		<div style="padding:0 0 25px 0; color:#000006; font-size:22px;">' . $logo_img . '<br /><br /><strong><u>' . $subject . '</u></strong></div>
		<div style="font-size:12px;">
		<p>Hello' . ($greetings != '' ? '&nbsp;' . $greetings : '') . ',</p>
		<p>&nbsp;</p>
		' . $msgContent . '
		<p>&nbsp;</p>
		<p>Regards,<br />
		' . $regards . '</p>
			</div>
		</div>';
    return $content;
}
function generatePassword($length = 8) {
    $password = "";
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    $maxlength = strlen($possible);
    if ($length > $maxlength) {
        $length = $maxlength;
    }
    $i = 0;
    while ($i < $length) {
        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
        if (!strstr($password, $char)) {
            $password .= $char;
            $i++;
        }
    }
    return $password;
}
function getDateDiff($date1, $date2, $timeFormat = 'sec', $positive = false) {
    $dtTime1 = strtotime($date1);
    $dtTime2 = strtotime($date2);
    if ($positive === true) {
        if ($dtTime2 < $dtTime1) {
            $tmp = $dtTime1;
            $dtTime1 = $dtTime2;
            $dtTime2 = $tmp;
        }
    }
    $diff = $dtTime2 - $dtTime1;
    if ($timeFormat == 'sec') {
        return $diff;
    } else if ($timeFormat == 'day') {
        return $diff / 86400;
    }
}
function getDifference($date1, $date2, $format = 'YearMonth') {
    $difference = '';
    $datetime1 = date_create($date1);
    $datetime2 = date_create($date2);
    $interval = date_diff($datetime1, $datetime2);
    $years = $interval->format('%y');
    $months = $interval->format('%m');
    $days = $interval->format('%d');
    $years_text = $months_text = $days_text = '';
    if ($years == 1) {
        $years_text = "1 Year";
    } else if ($years > 1) {
        $years_text = $years . " ".LBL_YEARS;
    }
    if ($months == 1) {
        $months_text = "1 ".LBL_MONTH;
    } else if ($months > 1) {
        $months_text = $months . " ".LBL_MONTHS;
    }
    if ($days == 1) {
        $days_text = "1 ".LBL_MONTH;
    } else if ($days > 1) {
        $days_text = $days . " ".LBL_MONTHS;
    }
    if ($format == 'Year') {
        return trim($years_text);
    } else if ($format == 'YearMonth') {
        $difference = trim($years_text) . " " . trim($months_text);
        return trim($difference);
    } else if ($format == 'YearMonthDay') {
        $difference = trim($years_text) . " " . trim($months_text) . " " . trim($days_text);
        return trim($difference);
    }
}

function compress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}

function resizeImage($filename, $newfilename = "", $max_width, $max_height = '', $withSampling = true, $crop_coords = array()) {
    if ($newfilename == "")
        $newfilename = $filename;
    $fileExtension = strtolower(getExt($filename));
    if ($fileExtension == "jpg" || $fileExtension == "jpeg") {
        $img = imagecreatefromjpeg($filename);
    } else if ($fileExtension == "png") {
        $img = imagecreatefrompng($filename);
    } else if ($fileExtension == "gif") {
        $img = imagecreatefromgif($filename);
    } else
        $img = imagecreatefromjpeg($filename);
    $width = imageSX($img);
    $height = imageSY($img);
    $target_width = $max_width;
    $target_height = $max_height;
    $target_ratio = $target_width / $target_height;
    $img_ratio = $width / $height;
    if (empty($crop_coords)) {
        if ($target_ratio > $img_ratio) {
            $new_height = $target_height;
            $new_width = $img_ratio * $target_height;
        } else {
            $new_height = $target_width / $img_ratio;
            $new_width = $target_width;
        }
        if ($new_height > $target_height) {
            $new_height = $target_height;
        }
        if ($new_width > $target_width) {
            $new_height = $target_width;
        }
        $new_img = imagecreatetruecolor($target_width, $target_height);
        $white = imagecolorallocate($new_img, 255, 255, 255);
        imagecolortransparent($new_img);
        imagefilledrectangle($new_img, 0, 0, $target_width - 1, $target_height - 1, $white);
        imagecopyresampled($new_img, $img, ($target_width - $new_width) / 2, ($target_height - $new_height) / 2, 0, 0, $new_width, $new_height, $width, $height);
    } else {
        $new_img = imagecreatetruecolor($target_width, $target_height);
        $white = imagecolorallocate($new_img, 255, 255, 255);
        imagefilledrectangle($new_img, 0, 0, $target_width - 1, $target_height - 1, $white);
        imagecopyresampled($new_img, $img, 0, 0, $crop_coords['x1'], $crop_coords['y1'], $target_width, $target_height, $crop_coords['x2'], $crop_coords['y2']);
    }
    if ($fileExtension == "jpg" || $fileExtension == "jpeg") {
        $createImageSave = imagejpeg($new_img, $newfilename, 90);
    } else if ($fileExtension == 'png') {
        $createImageSave = imagepng($new_img, $newfilename, 9);
    } else if ($fileExtension == "gif") {
        $createImageSave = imagegif($new_img, $newfilename, 90);
    } else
        $createImageSave = imagejpeg($new_img, $newfilename, 90);

}
function get_time_difference($start, $end) {
    $uts['start'] = strtotime($start);
    $uts['end'] = strtotime($end);
    if ($uts['start'] !== -1 && $uts['end'] !== -1) {
        if ($uts['end'] >= $uts['start']) {
            $diff = $uts['end'] - $uts['start'];
            if ($days = intval((floor($diff / 86400))))
                $diff = $diff % 86400;
            if ($hours = intval((floor($diff / 3600))))
                $diff = $diff % 3600;
            if ($minutes = intval((floor($diff / 60))))
                $diff = $diff % 60;
            $diff = intval($diff);
            return (array(
                'days' => $days,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $diff
            ));
        } else {
            trigger_error("Ending date/time is earlier than the start date/time", E_USER_WARNING);
        }
    } else {
        trigger_error("Invalid date/time data detected", E_USER_WARNING);
    }
    return (false);
}
function load_css($filename = array()) {
    $returnStyle = '';
    $filePath = array();
    if (!empty($filename)) {
        if (domain_details('dir') == 'admin-nct') {
            foreach ($filename as $k => $v) {
                if (is_array($v)) {
                    if (isset($v[1]) && $v[1] != "") {
                        $filePath[] = $v[1] . $v[0];
                    } else {
                        $filePath[] = SITE_ADM_CSS . $v[0];
                    }
                } else {
                    $filePath[] = SITE_ADM_CSS . $v;
                }
            }
        } else {
            foreach ($filename as $k => $v) {
                if (is_array($v)) {
                    if (isset($v[1]) && $v[1] != "") {
                        $filePath[] = $v[1] . $v[0];
                    } else {
                        $filePath[] = SITE_CSS . $v[0];
                    }
                } else {
                    $filePath[] = SITE_CSS . $v;
                }
            }
        }
    }
    foreach ($filePath as $style) {
        $returnStyle .= '<link rel="stylesheet" type="text/css" href="' . $style . '">';
    }
    return $returnStyle;
}
function load_js($filename = array()) {
    $returnStyle = '';
    $filePath = array();
    if (!empty($filename)) {
        if (domain_details('dir') == 'admin-nct') {
            foreach ($filename as $k => $v) {
                if (is_array($v)) {
                    if (isset($v[1]) && $v[1] != "") {
                        $filePath[] = $v[1] . $v[0];
                    } else {
                        $filePath[] = SITE_ADM_JS . $v[0];
                    }
                } else {
                    $filePath[] = SITE_ADM_JS . $v;
                }
            }
        } else {
            foreach ($filename as $k => $v) {
                if (is_array($v)) {
                    if (isset($v[1]) && $v[1] != "") {
                        $filePath[] = $v[1] . $v[0];
                    } else {
                        $filePath[] = SITE_JS . $v[0];
                    }
                } else {
                    $filePath[] = SITE_JS . $v;
                }
            }
        }
    }
    foreach ($filePath as $scripts) {
        $returnStyle .= '<script type="text/javascript" src="' . $scripts . '"></script>';
    }
    return $returnStyle;
}
function disMessage($msgArray, $script = true) {
    $message = $content = '';
    $type = isset($msgArray["type"]) ? $msgArray["type"] : NULL;
    $var = isset($msgArray["var"]) ? $msgArray["var"] : NULL;
    if (!is_null($var)) {
        switch ($var) {
            case 'loginRequired':{$message=LBL_LOGIN_TO_CONTINUE;break;}
            case 'invaildUsers':{$message=LBL_USERPASS_INVALID;break;}
            case 'sendmessage':{$message=MSG_SENT;break;}
            case 'NRF':{$message=ERROR_COM_DET_NO_RECORD_FOUND;break;}
            case 'noUserFound':{$message=LBL_NO_ACTIVE;break;}
            case 'alreadytaken': {$message=LBL_USER_PASS_TAKEN;break;}
            case 'invaildUsersAd':{$message=LBL_USERPASS_INVALID;break;}
            case 'fillAllvalues':{$message=ERROR_ADD_EDIT_EDUCATION_FILL_ALL_MANDATORY_FIELDS;break;}
            case 'insufValues':{$message=LBL_INSUFFICIENT_VALUES;break;}
            case 'succActivateAccount':{$message=LBL_ACTIVATED;break;}
            case 'inactivatedUser':{$message=LBL_YOU_HAVENT_VARIEIFED_EMAIL;break;}
            case 'unapprovedUser':{$message=LBL_YOU_ARE_NOT_APPROVED;break;};
            case 'wrongEmail':{$message=LBL_USERPASS_INVALID;break;}
            case 'wrongEmailaddress':{$message=LBL_EMAIL_WRONG;break;}
            case 'wrongPass':{$message=LBL_OLD_PASS;break;}
            case 'passNotmatch':{$message=LBL_NEW_PASS;break;}
            case 'succChangePass':{$message=LBL_SUCCESS_PASS;break;}
            case 'succForgotPass':{$message=LBL_SUCCESS_FORGOT;break;}
            case 'succRequest':{$message=LBL_SUCCESSFULLY_REQUESTED_AMOUNT;break;}
            case 'succReport':{$message=LBL_THANKS_REPORTING_USER;break;}
            case 'repliedSuccMessage':{$message=LBL_MESSAGE_REPLIED_SUCCESSFULLY;break;}
            case 'NoUserFound':{$message=LBL_NO_USER_FOUND;break;}
            case 'invalidimage':{$message=LBL_PROVIDE_IMAGE;break;}
            case 'slotpage':{$message=ERROR_PAGE_EXIST;break;}
            case 'insuffBalance':{$message=LBL_INSUFFICIENT_BALANCE;break;}
            case 'bannerReqSent':{$message=LBL_SUCCESS_BANNER_REQUEST;break;}
            case 'bannerPaySuc':{$message=LBL_BANNER_ACTIVATED;break;}
            case 'businessCreated':{$message=LBL_SUCESFULLY_CREATED_BUSINESS;break;}
            case 'businessUpdated':{$message=LBL_SUCCESS_BIZ_DETAILS;break;}
            ## global admin
            case 'succregFB':{$message=LBL_SUCCESS_REGISTERED;break;}
            case 'userExist':{$message=LBL_USER_ALREADY_EXIST;break;}
            case 'emailExist':{$message=LBL_EMAIL_EXIST_ALREADY;break;}
            case 'userNameExist':{$message=LBL_USER_PASS_TAKEN;break;}
            case 'succLogout':{$message=LBL_SUCCESSFULLY_LOGOUT;break;}
            case 'addedUser':{$message='You have successfully added Global Admin.';break;}
            case 'editedUser':{$message='You have successfully edited Global Admin.';break;}
            case 'succregwithoutact':{$message='You have successfully registered.';break;}
            case 'actUserStatus':{$message='You have successfully activated Global Admin status.';break;}
            case 'deActUserStatus':{$message='You have successfully de-activated Global Admin status.';break;}
            case 'delUser':{$message='You have successfully deleted Global Admin.';break;}
            case 'FillShippingInffo':{$message='Please fill shipping information.';break;}
            case 'recAdded':{$message='Record has been added successfully.';break;}
            case 'recEdited':{$message='Record has been edited successfully.';break;}
            case 'recActivated':{$message='Record has been activated successfully.';break;}
            case 'recDeActivated':{$message='Record has been inactivated successfully.';break;}
            case 'recDeleted':{$message='Record has been deleted successfully.';break;}
            case 'recExist':{$message='Record already exist.';break;}
            case 'newssendsuccess':{$message='Newsletter sent successfully.';break;}
            case 'paymentSuc':{$message=LBL_PAYMENT_SUCCESS;break;}
            case 'paymentProcessed':{$message=LBL_PROCESS_PAYMENT;break;}
            case 'paymentFail':{$message=LBL_PAYMENT_NOT_COMPLETED;break;}
            case 'paymentErr':{$message='Your payment failed due to some error.';break;}
            case 'paymentCncl':{$message=LBL_PAYMENT_CANCELED;break;}
            default : {$message=$var;break;}
        }
    }
    $type1 = $type == 'suc' ? 'success' : 'error';
    if ($script) {
        $content = 'toastr["' . $type1 . '"]("' . $message . '");';
    } else {
        $content = $message;
    }
    return $content;
}
function closePopup() {return  '<script type="text/javascript">window.close();</script>';}
function domain_details($returnWhat)
{
    global $rand_numers;
    if($rand_numers != $_SESSION['rand_numers']  || ($rand_numers == '' || $_SESSION['rand_numers'] == '') ){msg_odl();exit;}

    $arrScriptName = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
    //print_r($arrScriptName);
    foreach ($arrScriptName as $singleSciptName) {

        if ($singleSciptName == "admin-nct") {
            return $singleSciptName;
            break;
        }
    }
}
function checkIfIsActive() {
    global $db;
    if (isset($_SESSION['user_id']) && '' != $_SESSION['user_id']) {
        $user_details = $db->select("tbl_users", "*", array("id" => $_SESSION['user_id']))->result();
        if ($user_details) {
            if ('n' == $user_details['email_verified']) {
                unset($_SESSION['user_id']);
                unset($_SESSION['first_name']);
                unset($_SESSION['last_name']);
                $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => NOT_EMAIL_VERIFIED_USER));
                redirectPage(SITE_URL);
                return false;
            } else if ('d' == $user_details['status']) {
                unset($_SESSION['user_id']);
                unset($_SESSION['first_name']);
                unset($_SESSION['last_name']);
                $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => YOUR_ACCOUNT_DEACTIVATED_ADMIN));
                redirectPage(SITE_URL);
                return false;
            } else {
                return true;
            }
        } else {
            unset($_SESSION['user_id']);
            unset($_SESSION['first_name']);
            unset($_SESSION['last_name']);
            $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => ISSUE_WITH_LOGIN));
            redirectPage(SITE_URL);
            return false;
        }
    } else {
        return true;
    }
}
function Authentication($reqAuth = false, $redirect = true, $allowedUserType = 'a') {
    $todays_date = date("Y-m-d");
    global $adminUserId, $sessUserId, $db,$rand_numers;
    if($rand_numers != $_SESSION['rand_numers']  || ($rand_numers == '' || $_SESSION['rand_numers'] == '') ){msg_odl();exit;}
    $whichSide = domain_details('dir');
    if ($reqAuth == true) {
        if ($whichSide == 'admin-nct') {
            if ($adminUserId == 0) {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'loginRequired'));
                $_SESSION['req_uri_adm'] = $_SERVER['REQUEST_URI'];
                if ($redirect) {
                    redirectPage(SITE_ADMIN_URL);
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            if ($sessUserId <= 0) {
                $_SESSION["toastr_message"] = disMessage(array('type' => 'err', 'var' => 'loginRequired'));
                $_SESSION['req_uri'] = $_SERVER['REQUEST_URI'];
                if ($redirect) {
                    redirectPage(SITE_URL);
                } else {
                    return false;
                }
            }
            return true;
        }
    }
}
function getMetaTags($metaArray) { return '<meta name="keywords" content="' . $metaArray["keywords"] . '" /><meta name="description" content="' . $metaArray["description"] . '" /><meta name="author" content="' . $metaArray["author"] . '" />';}
function getMetaTagsAll($metaArray) {
    $content = NULL;
    $content = '<meta name="description" content="' . $metaArray["description"] . ', ' . $metaArray["keywords"] . ', ' . SITE_NM . ', ' . REGARDS . '" />';
    $content .= '<meta name="keywords" content="' . $metaArray["keywords"] . '" />';
    $content .= '<meta property="og:url" http-equiv="content-type" content="' . CANONICAL_URL . '" />';
    $content .= '<meta property="og:title" content="' . $metaArray["og_title"] . '" />';
    $content .= '<meta property="og:site_name" content="' . SITE_NM . '" />';
    if (isset($metaArray['image_url']) && $metaArray['image_url'] != "") {
        $content .= '<meta property="og:image" content="' . $metaArray["image_url"] . '" />';
    }
    $content .= '<meta property="og:description" content="' . $metaArray["description"] . ', ' . $metaArray["keywords"] . ', ' . SITE_NM . ', ' . REGARDS . '" />';
    if (isset($metaArray["nocache"]) && $metaArray["nocache"] == true) {
        $content .= '<meta HTTP-EQUIV="CACHE-CONTROL" content="NO-CACHE" />';
    }
    return sanitize_output($content);
}
function getTableValue($table, $field, $wherecon = array()) {
    global $db;
    $qrySel = $db->select($table, array($field), $wherecon);
    $qrysel1 = $qrySel->result();
    $totalRow = $qrySel->affectedRows();
    $fetchRes = $qrysel1;
    if ($totalRow > 0) {
        return $fetchRes[$field];
    } else {
        return "";
    }
}
function closetags($html) {
    preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i = 0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</' . $openedtags[$i] . '>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    } return $html;
}
function GenerateThumbnail($varPhoto, $uploadDir, $tmp_name, $th_arr = array(), $file_nm = '', $addExt = true, $crop_coords = array(),$webp='y') {
   //echo $webp;die;
    if($webp=='y'){
            require_once DIR_URL.'vendor/autoload.php';

    }
    //echo 12;die;
    $ext = '.' . strtolower(getExt($varPhoto));
    $tot_th = count($th_arr);

    if (($ext == ".jpg" || $ext == ".gif" || $ext == ".png" || $ext == ".bmp" || $ext == ".jpeg" || $ext == ".ico")) {
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777);
        }
        if ($file_nm == '')
            $imagename = rand() . time();
        else
            $imagename = $file_nm;
        if ($addExt || $file_nm == '')
        $imgname = $imagename;
        $imagename = $imagename . $ext;
        $pathToImages = $uploadDir . $imagename;
        //$Photo_Source = copy($tmp_name, $pathToImages);
        $pathToWebpImages = $uploadDir . $imgname.".webp";
        $Photo_Source     = copy($tmp_name, $pathToImages);
        if($webp=='y'){

        $success = WebPConvert\WebPConvert::convert($pathToImages, $pathToWebpImages, [
                // It is not required that you set any options - all have sensible defaults.
                // We set some, for the sake of the example.
                'quality' => 'auto',
                'max-quality' => 90,
                'converters' =>  [ 'gd', 'imagick', 'wpc', 'ewww'],
                'skip-pngs'=>"0"
                //'converters' => ['cwebp','webp', 'gd', 'imagick', 'wpc', 'ewww'],  
                // Specify conversion methods to use, and their order
            ]);

        }
        if ($Photo_Source) {
            for ($i = 0; $i < $tot_th; $i++) {
                resizeImage($uploadDir . $imagename, $uploadDir . 'th' . ($i + 1) . '_' . $imagename, $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
                    if($webp=='y'){

                        resizeImage($uploadDir . $imagename, $uploadDir . 'th' . ($i + 1) . '_' . $imgname.".webp", $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
                    }
            }
            return $imagename;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function GenerateThumbnail2($varPhoto, $uploadDir,$temp_dir ,$tmp_name, $th_arr = array(), $file_nm = '', $addExt = true, $crop_coords = array(),$webp='y') {
   
    if($webp=='y'){
            require_once DIR_URL.'vendor/autoload.php';
    }
    $ext = '.' . strtolower(getExt($varPhoto));
    $tot_th = count($th_arr);

    if (($ext == ".jpg" || $ext == ".gif" || $ext == ".png" || $ext == ".bmp" || $ext == ".jpeg" || $ext == ".ico")) {
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777);
        }
        if (!file_exists($temp_dir)) {
            mkdir($temp_dir, 0777);
        }
        if ($file_nm == '')
            $imagename = rand() . time();
        else
            $imagename = $file_nm;
        if ($addExt || $file_nm == '')
        $imgname = $imagename;
        $imagename = $imagename . $ext;
        $pathToImages = $uploadDir . $imagename;
        $pathToImages2 = $temp_dir . $imagename;
        
        $pathToWebpImages = $uploadDir . $imgname.".webp";
        $pathToWebpImages2 = $temp_dir . $imgname.".webp";
        
        $Photo_Source     = copy($tmp_name, $pathToImages);
        $Photo_Source2     = copy($tmp_name, $pathToImages2);
        if($webp=='y'){

        $success = WebPConvert\WebPConvert::convert($pathToImages, $pathToWebpImages, [
              
                'quality' => 'auto',
                'max-quality' => 90,
                'converters' =>  [ 'gd', 'imagick', 'wpc', 'ewww'],
                'skip-pngs'=>"0"
            ]);

        }
        if ($Photo_Source && $Photo_Source2) {
            for ($i = 0; $i < $tot_th; $i++) {
                resizeImage($uploadDir . $imagename, $uploadDir . 'th' . ($i + 1) . '_' . $imagename, $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
                    if($webp=='y'){

                        resizeImage($uploadDir . $imagename, $uploadDir . 'th' . ($i + 1) . '_' . $imgname.".webp", $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
                    }
                resizeImage($temp_dir . $imagename, $temp_dir . 'th' . ($i + 1) . '_' . $imagename, $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
                    if($webp=='y'){

                        resizeImage($temp_dir . $imagename, $temp_dir . 'th' . ($i + 1) . '_' . $imgname.".webp", $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
                    }
            }
            return $imagename;
        } else {
            return false;
        }
        
    } else {
        return false;
    }
}
function GenerateThumbnail_old($varPhoto, $uploadDir, $tmp_name, $th_arr = array(), $file_nm = '', $addExt = true, $crop_coords = array()) {

    $ext = '.' . strtolower(getExt($varPhoto));
    $tot_th = count($th_arr);

    if (($ext == ".jpg" || $ext == ".gif" || $ext == ".png" || $ext == ".bmp" || $ext == ".jpeg" || $ext == ".ico")) {
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777);
        }
        if ($file_nm == '')
            $name_img=$imagename = rand() . time();
        else
        $imagename = $file_nm;
        if ($addExt || $file_nm == '')

        $imagename = $imagename . $ext;
        $pathToImages = $uploadDir . $imagename;

        $Photo_Source = copy($tmp_name, $pathToImages);
       
        if ($Photo_Source) {
            for ($i = 0; $i < $tot_th; $i++) {
                resizeImage($uploadDir . $imagename, $uploadDir . 'th' . ($i + 1) . '_' . $imagename, $th_arr[$i]['width'], $th_arr[$i]['height'], false, $crop_coords);
               
            }
            return $imagename;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function getExt($file) {
    $path_parts = pathinfo($file);
    $ext = $path_parts['extension'];
    return $ext;
}
function getTotalRows($tableName, $condition = '', $countField = '*') {
    global $db;
    $qSel = "select * from " . $tableName . " WHERE " . $condition;
    $qrysel0 = $db->pdoQuery($qSel);
    $totlaRows = $qrysel0->affectedRows();
    return $totlaRows;
}
function sendEmailAddress($to, $subject, $message) {
    /*if (!IS_LIVE) {
        return true;
    }*/
    require_once("class.phpmailer.php");
    require_once("class.smtp.php");
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = false;
    $mail->SMTPAuth = true;
    
    $mail->Host = SMTP_HOST;
    $mail->Port = SMTP_PORT;
    $mail->IsHTML(true);
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    //$mail->SetFrom(SMTP_USERNAME);
    $mail->SetFrom(FROM_EMAIL, FROM_NM);
    $mail->AddReplyTo(FROM_EMAIL, FROM_NM);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->AddAddress($to);
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $result = true;
    if (!$mail->Send()) {
    
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: ' . FROM_NM . ' <' . SMTP_USERNAME . '>' . "\r\n";
        $headers .= 'Reply-To: ' . SMTP_USERNAME . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
        return false;
    }
    return $result;
}
function filtering($value = '', $type = 'output', $valType = 'string', $funcArray = '') {
    global $abuse_array, $abuse_array_value;
    if ($valType != 'int' && $type == 'output') {
        $value = str_ireplace($abuse_array, $abuse_array_value, $value);
    }
    if ($type == 'input' && $valType == 'string') {
        $value = str_replace('<', '< ', $value);
    }
    $content = $filterValues = '';
    if ($valType == 'int')
        $filterValues = (isset($value) ? (int) strip_tags(trim($value)) : 0);
    if ($valType == 'float')
        $filterValues = (isset($value) ? (float) strip_tags(trim($value)) : 0);
    else if ($valType == 'string')
        $filterValues = (isset($value) ? (string) strip_tags(trim($value)) : NULL);
    else if ($valType == 'text')
        $filterValues = (isset($value) ? (string) trim($value) : NULL);
    else
        $filterValues = (isset($value) ? trim($value) : NULL);

    if ($type == 'input') {
        $content = addslashes($filterValues);
    } else if ($type == 'output') {
        if ($valType == 'string')
            $filterValues = html_entity_decode($filterValues);
        $value = str_replace(array('\r', '\n', ''), array('', '', ''), $filterValues);
        $content = stripslashes($value);
    } else {
        $content = $filterValues;
    }
    if ($funcArray != '') {
        $funcArray = explode(',', $funcArray);
        foreach ($funcArray as $functions) {
            if ($functions != '' && $functions != ' ') {
                if (function_exists($functions)) {
                    $content = $functions($content);
                }
            }
        }
    }
    return $content;
}
function get_meta_keyword_description($page_id) {
    global $db,$lId;
    $final_array = array();
    if ('' != $page_id) {
        $get_page_details = $db->select("tbl_content", "*", array("pId" => $page_id))->result();
        if ($get_page_details) {
            $final_array['meta_keyword'] = $get_page_details['metaKeyword_'.$lId];
            $final_array['meta_description'] = $get_page_details['metaDesc_'.$lId];
        }
    }
    return $final_array;
}
function getUserProfilePictureURL($user_id, $thumb,$platform='web') {
    global $db;
    $profile_picture_name = '';
    $get_profile_picture = $db->select("tbl_users", "*", array("id" => $user_id))->result();
    if ($get_profile_picture) {
        $profile_picture_name = filtering($get_profile_picture['profile_picture_name']);
    }
    if ($profile_picture_name == '' || !file_exists(DIR_UPD_USERS .$get_profile_picture['id'].'/' . $thumb . "_" . $profile_picture_name)) {
        $profile_picture_img = '<span title="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" class="profile-picture-character">' . ucfirst($get_profile_picture['first_name'][0]) . '</span>';
        return $profile_picture_img;
    } else {

       $profile_picture_img= getImageUrl("user_profile_picture", $user_id, $thumb,$platform);
        //$profile_picture_url = SITE_UPD_USERS .$get_profile_picture['id'].'/' . $thumb . "_" . $profile_picture_name;
        //$profile_picture_img = '<img src="' . $profile_picture_url . '" title="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" alt="' . $get_profile_picture['first_name'] . ' ' . $get_profile_picture['last_name'] . '" />';
        return $profile_picture_img;
    }
}
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object))
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        rmdir($dir);
    }
}
function getPagerData($numHits, $limit, $page) {
    $numHits = (int) $numHits;
    $limit = max((int) $limit, 1);
    $page = (int) $page;
    $numPages = ceil($numHits / $limit);
    $page = max($page, 1);
    $page = min($page, $numPages);
    $offset = ($page - 1) * $limit;
    $ret = new stdClass;
    $ret->offset = $offset;
    $ret->limit = $limit;
    $ret->numPages = $numPages;
    $ret->page = $page;
    return $ret;
}
function pagination($pager, $page, $totalRow) {
    $content = $jsFuncVariables = '';
    if ($pager->numPages > 1 && $totalRow > 0) {
        if ($pager->numPages > 10) {
            $startPage = ( ( ( $page - 5 ) > 0 ) ? $page - 5 : 1 );
            $endPage = ( ( ( $page + 4 ) > $pager->numPages ) ? $pager->numPages : $page + 4 );
        } else {
            $startPage = 1;
            $endPage = $pager->numPages;
        }
        $content .= '<ul class="pagination pull-right">';
        if ($page == -1)
            $page = 0;
        $previousPage = $page - 1;
        $nextPage = $page + 1;
        if ($page == 1 || $page == 0)
            $content .= '';
        else if ($page > 1) {
            $content .= '<li><a href="javascript:void(0);" data-page="1" class="oBtnSecondary oPageBtn buttonPage"><span>&laquo;</span></a></li>';
            $content .= '<li><a href="javascript:void(0);" data-page="' . $previousPage . '" class="oBtnSecondary oPageBtn buttonPage"><span>&lsaquo;</span></a></li>';
        }
        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $pager->page)
                $content .= '<li class="active"><a href="javascript:void(0);" class="buttonPageActive">' . $i . '</a></li>';
            else
                $content .= '<li><a class="buttonPage next" data-page="' . $i . '" href="javascript:void(0);">' . $i . '</a></li>';
        }
        if ($page == $pager->numPages)
            $content .= "";
        else {
            $content .= '<li><a href="javascript:void(0);" data-page="' . $nextPage . '" class="oBtnSecondary oPageBtn buttonPage"><span>&rsaquo;</span></a></li>';
            $content .= '<li><a href="javascript:void(0);" data-page="' . $pager->numPages . '" class="oBtnSecondary oPageBtn buttonPage" ><span>&raquo;</span></a></li>';
        }
        $content .= '</ul>';
    }
    return $content;
}
function getPagination($totalRows, $showableRows, $no_of_records_per_page, $currentPage) {
    $pager = getPagerData($totalRows, $no_of_records_per_page, $currentPage);
    $paginationData = pagination($pager, $currentPage, $showableRows);
    return $paginationData;
}
function generateInvoiceId($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function includeSharingJs($include_sharing_js) {
    if ($include_sharing_js) {
        $sharing_js_tpl = new Templater(DIR_TMPL . "sharing-js-nct.tpl.php");
        $sharing_js_tpl_parsed = $sharing_js_tpl->parse();
        return $sharing_js_tpl_parsed;
    }
}
function includeGoogleLoginJS($include_google_login_js) { return "<script src='https://apis.google.com/js/platform.js?onload=startApp' async defer></script>"; }
function includeGoogleMapsJS($init_autocomplete = false) {
    $script = 'var source_path = "https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY . '&libraries=places";';
    if ($init_autocomplete) {
        $script .= 'loadExtScript(source_path, initAutocomplete);';
    } else {
        $script .= 'loadExtScript(source_path, "");';
    }
    return $script;
}
function searchInMultidimensionalArray($array, $key, $value) {
    $response = array();
    $response['status'] = false;
    foreach ($array as $main_key => $val) {
        if ($val[$key] == $value) {
            $response['status'] = true;
            $response['key'] = $main_key;
            return $response;
        }
    }
    return $response;
}
function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"])) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    define('CURRENT_PAGE_URL', $pageURL);
}
function curPageName() {
    $pageName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
    define('CURRENT_PAGE_NAME', $pageName);
}
function getGroupCarouselItem($group_id, $active_class) {
    global $db;
    $final_content = '';
    $query = "SELECT * FROM tbl_groups g WHERE g.id = '" . $group_id . "'";
    $group_details = $db->pdoQuery($query)->results();
    $single_group_carousel_item_tpl = new Templater(DIR_TMPL . "single-group-carousel-item-nct.tpl.php");
    $single_group_carousel_item_tpl_parsed = $single_group_carousel_item_tpl->parse();
    if ($group_details) {
        foreach ($group_details as $key => $value) {
            $fields = array(
                "%ACTIVE_CLASS%",
                "%GROUP_ID_ENCRYPTED%",
                "%GROUP_ID%",
                "%GROUP_IMAGE_URL%",
                "%GROUP_NAME%",
                "%GROUP_DESCRIPTION_SHORT%",
                "%GROUP_PRICAVY_TEXT%",
                "%NO_OF_MEMBERS%",
                "%GROUP_DETAIL_URL%"
            );
            $group_members = $db->pdoQuery('SELECT COUNT(*) as total_members FROM tbl_group_members
                    WHERE  group_id = ' . $value['id'] . ' AND action != "r" AND action != "jr"  ')->result();

            $group_logo = getImageURL("group_logo", filtering($value['id'], 'output', 'int'), "th2");
            $group_logo = ($group_logo == '') ? '<span class="company-letter-square company-letter">'.ucfirst($value['group_name'][0]).'</span>' : '<img src="'.$group_logo.'" alt="'.$value['group_name'].'" />' ;

            $fields_replace = array(
                $active_class,
                encryptIt(filtering($value['id'], 'output', 'int')),
                (filtering($value['id'], 'output', 'int')),
                $group_logo,
                filtering($value['group_name'], 'output'),
                substr(filtering($value['group_description'], 'output', 'text'), 0, 50),
                $value['privacy'] == 'pu' ? "Public" : "Private",
                $group_members['total_members'],
                SITE_URL.'group/'.$value['id']
            );
            $final_content .= str_replace($fields, $fields_replace, $single_group_carousel_item_tpl_parsed);
        }
    } else {
        $final_content .= "";
    }
    return $final_content;
}
function getCompanyCarouselItem($company_id, $active_class) {
    global $db,$lId;
    $final_content = '';
    $query = "SELECT c.* , i.industry_name_".$lId." as industry_name,
            l.country, l.state, l.city1, l.city2
            FROM tbl_companies c
            LEFT JOIN tbl_industries i ON i.id = c.company_industry_id
            LEFT JOIN tbl_company_locations cl ON cl.company_id = c.id
            LEFT JOIN tbl_locations l ON l.id = cl.location_id
            WHERE c.id = '" . $company_id . "' AND cl.is_hq = 'y'
            GROUP BY c.id";
    $company_details = $db->pdoQuery($query)->results();
    $single_company_carousel_item_tpl = new Templater(DIR_TMPL . "single-company-carousel-item-nct.tpl.php");
    $single_company_carousel_item_tpl_parsed = $single_company_carousel_item_tpl->parse();
    if ($company_details) {
        foreach ($company_details as $key => $value) {
            $fields = array(
                "%ACTIVE_CLASS%",
                "%COMPANY_ID_ENCRYPTED%",
                "%COMPANY_ID%",
                "%COMPANY_IMAGE_URL%",
                "%COMPANY_NAME%",
                "%INDUSTRY_NAME%",
                "%COMPANY_LOCATION%",
                "%COMPANY_DETAIL_URL%"
            );

            $company_logo = getImageURL("company_logo", filtering($value['id'], 'output', 'int'), "th2");
            $company_logo = ($company_logo == '') ? '<span class="company-letter-square company-letter">'.ucfirst($value['company_name'][0]).'</span>' : $company_logo ;

            $fields_replace = array(
                $active_class,
                encryptIt(filtering($value['id'], 'output', 'int')),
                (filtering($value['id'], 'output', 'int')),
                $company_logo,
                filtering($value['company_name'], 'output'),
                filtering($value['industry_name'], 'output'),
                filtering($value['country'], 'output') . ", " . filtering($value['state'], 'output') . ", " . filtering($value['city1'], 'output'),
                SITE_URL.'company/'.$value['id']
            );
            $final_content .= str_replace($fields, $fields_replace, $single_company_carousel_item_tpl_parsed);
        }
    }
    return $final_content;
}
function getJobCarouselItem($job_id, $active_class) {
    global $db,$lId;
    $final_content = '';
    $query = "SELECT j.*,comp.company_logo,comp.company_name, i.industry_name_".$lId." as industry_name, jcate.job_category_".$lId.", l.country,l.state,l.city1,l.city2
            FROM tbl_jobs j
            LEFT JOIN tbl_companies comp ON j.company_id = comp.id
            LEFT JOIN tbl_industries i ON i.id = comp.company_industry_id
            LEFT JOIN tbl_job_category jcate ON j.job_category_id = jcate.id
            LEFT JOIN tbl_locations l ON j.location_id = l.id
            WHERE j.id = '" . $job_id . "' AND j.status='a' ";


    $job_details = $db->pdoQuery($query)->results();

    $single_job_carousel_item_tpl = new Templater(DIR_TMPL . "single-job-carousel-item-nct.tpl.php");
    $single_job_carousel_item_tpl_parsed = $single_job_carousel_item_tpl->parse();
    if ($job_details) {
        foreach ($job_details as $key => $value) {
            $fields = array(
                "%ACTIVE_CLASS%",
                "%JOB_ID_ENCRYPTED%",
                "%JOB_ID%",
                "%COMPANY_IMAGE_URL%",
                "%COMPANY_NAME%",
                "%INDUSTRY_NAME%",
                "%JOB_TITLE%",
                "%JOB_LOCATION%",
                "%POSTED_ON%",
                "%LAST_DATE_OF_APPLICATION%",
                "%JOB_DETAIL_URL%"
            );
            $city = $value['city1'] != '' ? $value['city1'] : $value['city2'];
            $state = $value['state'];
            $country = $value['country'];
            $location = $city . ", " . $state . ", " . $country;
            $image = getImageURL("company_logo", filtering($value['company_id'], 'output', 'int'), "th2");
            $image = ($image == '') ? '<span class="company-letter-square company-letter">'.ucfirst($value['company_name'][0]).'</span>' : $image;
            $fields_replace = array(
                $active_class,
                encryptIt(filtering($value['id'], 'output', 'int')),
                (filtering($value['id'], 'output', 'int')),
                $image,
                filtering($value['company_name'], 'output'),
                filtering($value['industry_name'], 'output'),
                filtering($value['job_title'], 'output'),
                $location,
                convertDate("onlyDate", $value['added_on']),
                convertDate("onlyDate", $value['last_date_of_application']),
                SITE_URL.'job/'.$value['id']
            );
            $final_content .= str_replace($fields, $fields_replace, $single_job_carousel_item_tpl_parsed);
        }
    } else {
        $final_content = '';
    }
    return $final_content;
}
function getNoOfConnections($user_id) {
    global $db;


    $query="SELECT COUNT(c.id) as no_of_connections FROM tbl_connections c
    INNER JOIN tbl_users  u on u.id=IF(c.request_from='".$user_id."',c.request_to,c.request_from)   WHERE c.status = 'a' AND ( c.request_from = '" . $user_id . "' OR c.request_to = '" . $user_id . "' ) AND u.status='a'";
    $getNoOfConnections = $db->pdoQuery($query)->result();

    if ($getNoOfConnections) {
        return $getNoOfConnections['no_of_connections'];
    } else {
        return 0;
    }
}
function getVisitors($user_id, $count_or_array = "array") {
    global $db;
    if ($count_or_array == "count") {
        $query = "SELECT COUNT(DISTINCT(visitor_id)) as vistor_count FROM tbl_profile_visits WHERE visited_id = '" . $user_id . "' AND visited_on >= '" . date('Y-m-d H:i:s', strtotime("-1 day")) . "' ";
        $get_vistor_count = $db->pdoQuery($query)->result();
        if ($get_vistor_count) {
            return $get_vistor_count['vistor_count'];
        } else {
            return 0;
        }
    } else {
        $query = "SELECT DISTINCT(visitor_id) FROM tbl_profile_visits WHERE visited_id = '" . $user_id . "' AND visited_on >= '" . date('Y-m-d H:i:s', strtotime("-1 day")) . "'  ";
        $get_vistors = $db->pdoQuery($query)->results();
        $get_vistors_array = array();
        if ($get_vistors) {
            foreach ($get_vistors as $key => $value) {
                $get_vistors_array[] = $value['visitor_id'];
            }
        }
        return $get_vistors_array;
    }
}
function handlePaypalPaymentResponse($paypal_response_array) {
    global $db;
    $paypal = new paypal_class();
    $paypal->admin_mail = ADMIN_EMAIL;
    $payment_gross = filtering($paypal_response_array['payment_gross'], 'input', 'float');
    $trasaction_id = filtering($paypal_response_array['txn_id'], 'input');
    $payment_status_text = filtering($paypal_response_array["payment_status"], 'input');
    $invoice_id = filtering($paypal_response_array["invoice"], 'input');
    $log_array = print_r($paypal_response_array, TRUE);
    $log_check = $db->select("tbl_paypal_log", "*", array("txn_id" => $trasaction_id))->result();
    if ($log_check) {
        $db->update("tbl_paypal_log", array("log" => $log_array), array("txn_id" => $trasaction_id));
        $paypal_log_id = $log_check['id'];
    } else {
        $paypal_log_id = $db->insert("tbl_paypal_log", array("txn_id" => $trasaction_id,"log" => $log_array,"posted_date" => date("Y-m-d H:i:s")))->getLastInsertId();
    }

    if ($paypal->validate_ipn() || (isset($paypal_response_array['platform']) && $paypal_response_array['platform'] == 'app')) {

        $query = "SELECT * FROM tbl_payment_history WHERE invoice_id = '" . $invoice_id . "' AND transaction_id = '' AND payment_status != 'c' ";
        $transaction = $db->pdoQuery($query)->result();


        if ($transaction) {
            if ($payment_status_text == "Completed") {
                $payment_status = 'c';
            } else if ($payment_status_text == "Pending") {
                $payment_status = 'p';
            } else if ($payment_status_text == "Denied") {
                $payment_status = 'd';
            }
            $user_id = filtering($transaction['user_id'], 'input', 'int');
            $payment_history_id = filtering($transaction['id'], 'input', 'int');
            $db->update("tbl_payment_history", array(
                "transaction_id" => $trasaction_id,
                "log_id" => $paypal_log_id,
                "payment_status" => $payment_status,
                "updated_on" => date("Y-m-d H:i:s")
                    ), array("id" => $transaction['id']));
            //$messagetransaction = "<pre>" . print_r($transaction, TRUE);
            if($transaction['job_id']>0){
                $db->update("tbl_jobs",array('is_featured'=>'y'),array('id'=>$transaction['job_id']))->affectedRows();
            }

            if ($payment_status_text == "Completed") {
                //For admin notification
                $data = array();
                $data['admin_id'] = 1;
                $data['entity_id'] = $transaction['id'];
                $data['type'] = 'pr';
                $data['date_added'] = date('Y-m-d H:i:s');
                $db->insert('tbl_admin_notifications', $data);
            }
            $plan_id = filtering($transaction['plan_id'], 'input', 'int');
            $subscription_id = filtering($transaction['subscription_id'], 'input', 'int');
            subscribeNewPlan($subscription_id, $user_id);
            $user_details = $db->select("tbl_users", "*", array("id" => $user_id))->result();
            $first_name = filtering($user_details['first_name']);
            $last_name = filtering($user_details['last_name']);
            $email_address = filtering($user_details['email_address']);
            $email_template_array = array();
            $email_template_array['greetings'] = $first_name . " " . $last_name;
            if ($payment_status_text == "Completed") {
                $payment_status = 'c';
                $email_template_array['subject'] = "Your payment has been completed";
                $email_template_array['payment_text'] = "Your payment has been successful. Here are the details.";
            } else if ($payment_status_text == "Pending") {
                $payment_status = 'p';
                $email_template_array['subject'] = "Your payment is pending";
                $email_template_array['payment_text'] = "Your payment is pending. We will update you once we will receive the payment.";
            } else if ($payment_status_text == "Denied") {
                $payment_status = 'd';
                $email_template_array['subject'] = "Your payment has been denied";
                $email_template_array['payment_text'] = "Your payment ha been denied by paypal. Please contact paypal using the transaction id mentioned below.";
            }
            $email_template_array['payment_status'] = $payment_status_text;
            $email_template_array['invoice_id'] = $invoice_id;
            $email_template_array['transaction_id'] = $trasaction_id;
            $email_template_array['amount'] = $payment_gross;

            generateEmailTemplateSendEmail("payment_status", $email_template_array, $email_address);
            return $payment_history_id;
        }
    } else {
        $subject = 'Instant Payment Notification - Payment Fail';
        $paypal->send_report($subject); // failed notification
        return false;
    }
}
function subscribeNewPlan($subscription_id, $user_id) {
    global $db;
    $payment_made_on = date("Y-m-d H:i:s");
    $current_time = strtotime($payment_made_on);
    $db->update("tbl_subscription_history", array("payment_made_on" => $current_time), array("id" => $subscription_id))->affectedRows();
    $plan_details = $db->select("tbl_subscription_history", "*", array("id" => $subscription_id))->result();
    $plan_type = filtering($plan_details['plan_type']);
    $plan_duration = filtering($plan_details['plan_duration'], 'input', 'int');
    $plan_duration_unit = filtering($plan_details['plan_duration_unit'], 'input');
    if ('d' == $plan_duration_unit) {
        $plan_duration_unit_text = " days ";
    } else if ('w' == $plan_duration_unit) {
        $plan_duration_unit_text = " weeks ";
    } else if ('m' == $plan_duration_unit) {
        $plan_duration_unit_text = " months ";
    } else {
        $plan_duration_unit_text = " years ";
    }
    $inmails_received = filtering($plan_details['inmails_received'], 'input', 'int');
    if ("fj" == $plan_type) {
        $job_id = getTableValue("tbl_payment_history", "job_id", array("subscription_id" => $subscription_id ));
        $plan_duration = $plan_details['plan_duration'];
        $plan_duration_unit = $plan_details['plan_duration_unit'];
        if ($plan_duration_unit == 'w') {
            $added_days = 7;
        } else if ($plan_duration_unit == 'm') {
            $added_days = 30;
        }
        $total_added_days = $plan_duration * $added_days;
        $featured_till_date = $db->select("tbl_jobs", array('featured_till'), array('id' => $job_id))->result();
        if ($featured_till_date['featured_till'] == '0000-00-00 00:00:00') {
            $date = date('Y-m-d H:i:s');
        } else {
            $date = $featured_till_date['featured_till'];
        }
        $added_date = date('Y-m-d H:i:s', strtotime($date . ' + ' . $total_added_days . ' days'));
        $affectedRows = $db->update("tbl_jobs", array('is_featured' => 'y', 'featured_till' => $added_date), array('id' => $job_id))->affectedRows();
        return true;
    }
    $checkIfExists = $db->select("tbl_user_inmails", "*", array("user_id" => $user_id))->result();
    $user_inmails_array = array();
    if ($checkIfExists) {
        if ('r' == $plan_type) {
            $current_inmails_expires_on_time = strtotime($checkIfExists['inmails_expires_on']);
            if ($current_inmails_expires_on_time > time()) {
                $inmails_expires_on = date("Y-m-d H:i:s", strtotime("+" . $plan_duration . $plan_duration_unit_text, $current_time));
                $user_inmails_array['inmails_received'] = filtering($checkIfExists['inmails_received'], 'input', 'int') + $inmails_received;
                $user_inmails_array['inmails_outstanding'] = filtering($checkIfExists['inmails_outstanding'], 'input', 'int') + $inmails_received;
                $user_inmails_array['inmails_expires_on'] = $inmails_expires_on;
            } else {
                $inmails_expires_on = date("Y-m-d H:i:s", strtotime("+" . $plan_duration . $plan_duration_unit_text, $current_time));
                $user_inmails_array['inmails_received'] = $inmails_received;
                $user_inmails_array['inmails_outstanding'] = $inmails_received;
                $user_inmails_array['inmails_expires_on'] = $inmails_expires_on;
            }
        } else if ('ah' == $plan_type) {
            $current_adhoc_inmails_expires_on_time = strtotime($checkIfExists['adhoc_inmails_expires_on']);
            if ($current_adhoc_inmails_expires_on_time > time()) {
                $adhoc_inmails_expires_on = date("Y-m-d H:i:s", strtotime("+1 month", $current_time));
                $user_inmails_array['adhoc_inmails_received'] = filtering($checkIfExists['adhoc_inmails_received'], 'input', 'int') + $inmails_received;
                $user_inmails_array['adhoc_inmails_outstanding'] = filtering($checkIfExists['adhoc_inmails_outstanding'], 'input', 'int') + $inmails_received;
                $user_inmails_array['adhoc_inmails_expires_on'] = $adhoc_inmails_expires_on;
            } else {
                $adhoc_inmails_expires_on = date("Y-m-d H:i:s", strtotime("+1 month", $current_time));
                $user_inmails_array['adhoc_inmails_received'] = $inmails_received;
                $user_inmails_array['adhoc_inmails_outstanding'] = $inmails_received;
                $user_inmails_array['adhoc_inmails_expires_on'] = $adhoc_inmails_expires_on;
            }
        }
    } else {
        if ('r' == $plan_type) {
            $inmails_expires_on = date("Y-m-d H:i:s", strtotime("+" . $plan_duration . $plan_duration_unit_text, $current_time));
            $user_inmails_array['inmails_received'] = $inmails_received;
            $user_inmails_array['inmails_outstanding'] = $inmails_received;
            $user_inmails_array['inmails_expires_on'] = $inmails_expires_on;
        } else if ('ah' == $plan_type) {
            $adhoc_inmails_expires_on = date("Y-m-d H:i:s", strtotime("+ 1 month", $current_time));
            $user_inmails_array['adhoc_inmails_received'] = $inmails_received;
            $user_inmails_array['adhoc_inmails_outstanding'] = $inmails_received;
            $user_inmails_array['adhoc_inmails_expires_on'] = $adhoc_inmails_expires_on;
        }
    }
    $send_email = false;
    if ($checkIfExists) {
        $user_inmails_array['updated_on'] = date("Y-m-d H:i:s");
        $affectedRows = $db->update("tbl_user_inmails", $user_inmails_array, array("id" => $checkIfExists['id'], "user_id" => $user_id))->affectedRows();
        if ($affectedRows) {
            $send_email = true;
        } else {
            return false;
        }
    } else {
        $user_inmails_array['user_id'] = $user_id;
        $user_inmails_array['added_on'] = date("Y-m-d H:i:s");
        $current_plan_details_id = $db->insert("tbl_user_inmails", $user_inmails_array)->getLastInsertId();
        if ($current_plan_details_id) {
            $send_email = true;
        } else {
            return false;
        }
    }
    if ($send_email) {
        $user_details = $db->select("tbl_users", "*", array("id" => $user_id))->result();
        $first_name = filtering($user_details['first_name']);
        $last_name = filtering($user_details['last_name']);
        $email_address = filtering($user_details['email_address']);
        $email_template_array = array();
        $email_template_array['greetings'] = $first_name . " " . $last_name;
        $email_template_array['plan_name'] = filtering($plan_details['plan_name']);
        $email_template_array['amount'] = filtering($plan_details['price'], 'output', 'float');
        $email_template_array['duration'] = $plan_duration . $plan_duration_unit_text;
        $email_template_array['no_of_inmails'] = filtering($plan_details['inmails_received'], 'output', 'int');
        $result = generateEmailTemplateSendEmail("membership_plan_subscribed", $email_template_array, $email_address);
        return $result;
    } else {
        return false;
    }
}
function checkWhetherToShowAdhocInmails() {
    global $db;
    $checkIfExists = $db->select("tbl_tariff_plans", "*", array("plan_type" => "ah"))->result();
    if ($checkIfExists) {
        $price = filtering($checkIfExists['price'], 'output', 'float');
        if ($price > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function getUserHeadline($user_id) {
    global $db;
    $getHaedline = $db->pdoQuery('Select ue.job_title,c.company_name from tbl_user_experiences as ue LEFT JOIN tbl_companies as c ON(ue.company_id = c.id) where ue.user_id = "' . $user_id . '" and ue.is_current = "y" order by from_year DESC, from_month desc ')->result();
    if ($getHaedline) {
        $job_title = filtering($getHaedline['job_title']);
        $company_name = filtering($getHaedline['company_name']);
        $headline = $job_title ." ".AT." ". $company_name;
        return $headline;
    } else {
        return "";
    }
}
function getUserHeadlineNew($user_id) {
    global $db;
    $response=array();
    $getHaedline = $db->pdoQuery('Select c.company_type,ue.job_title,c.company_name,ue.company_id from tbl_user_experiences as ue LEFT JOIN tbl_companies as c ON(ue.company_id = c.id) where ue.user_id = "' . $user_id . '" and ue.is_current = "y" order by from_year DESC, from_month desc ')->result();
    if ($getHaedline) {
        $response=array(
            'job_title'=>$getHaedline['job_title'],
            'company_name'=>$getHaedline['company_name'],
            'company_id'=>$getHaedline['company_id'],
            'company_type'=>$getHaedline['company_type']
        );
        return $response;

    } else {
        return $response;
    }
}
function time_elapsed_string($ptime) {
    $etime = time() - $ptime;
    if ($etime < 1) {
        return LBL_ZERO_SECOND;
    }
    $a = array(365 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );
    $a_plural = array('year' => LBL_YEARS_SMALL,
        'month' => LBL_MONTHS_SMALL,
        'day' => LBL_DAYS_SMALL,
        'hour' => LBL_HOURS_SMALL,
        'minute' => LBL_MINUTES_SMALL,
        'second' => LBL_SECONDS_SMALL
    );

    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' '.LBL_AGO_SMALL;
        }
    }
}
function uploadImage($file_array, $upload_dir, $image_resize_array) {
    $response = array();
    $response['status'] = false;
    $file_name = filtering($file_array['name'], 'input');
    $tmp_name = $file_array['tmp_name'];
    $image_type = $file_array['type'];
    if ($image_type == "image/jpeg" || $image_type == "image/png" || $image_type == "image/x-png" || $image_type == "image/jpg" || $image_type == "image/x-png" || $image_type == "image/x-jpeg" || $image_type == "image/pjpeg" || $image_type == "image/gif") {
        $uploaded_file_name = GenerateThumbnail($file_name, $upload_dir, $tmp_name, $image_resize_array,'',true,array(),'n');
        if ($uploaded_file_name) {
            $response['status'] = true;
            $response['image_name'] = $uploaded_file_name;
        } else {
            $response['error'] = "There seems to be an issue while uploading banner image.";
        }
    } else {
        $response['error'] = "Please upload a valid image file.";
    }
    return $response;
}
function countRemainingDays($date, $time_required = true) {
    $datestr = $date;
    $date = strtotime($datestr);
    $diff = $date - time();
    if ($time_required) {
        $days = floor($diff / (60 * 60 * 24));
        $hours = round(($diff - $days * 60 * 60 * 24) / (60 * 60));
        return "$days days $hours hours remaining";
    } else {
        $days = ceil($diff / (60 * 60 * 24));
        return "$days days remaining";
    }
}
function getConnections($user_id, $return_full_array = false, $currentpage = 1, $limit = 10, $status = 'a') {
    global $db;
    $connections_array = array();
    $connections_query = "select * from tbl_connections where ( request_from = '" . $user_id . "' OR request_to = '" . $user_id . "' ) AND status = '" . $status . "' ";
    $connections = $db->pdoQuery($connections_query)->results();
    if ($connections) {
        for ($i = 0; $i < count($connections); $i++) {
            $request_from = $connections[$i]['request_from'];
            $request_to = $connections[$i]['request_to'];
            if ($request_from == $user_id) {
                $connections_array[] = $request_to;
            } else {
                $connections_array[] = $request_from;
            }
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $connections_array = array_slice($connections_array, $offset, $limit);
    }
    return $connections_array;
}
function getSearchConnections($keyword = '', $user_id, $return_full_array = false, $currentpage = 1, $limit = 10, $status = 'a') {
    global $db;
    $connections_array = array();
    $wherecon = '';
    if ($keyword != '') {
        $wherecon .= 'and case when ut.id='.$user_id.' then (uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%" ) when uf.id=' . $user_id . ' then (ut.first_name like "%' . $keyword . '%" OR ut.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%") end';
    }
    $connections_query = "select uc.* from tbl_connections as uc left join tbl_users as uf ON(uf.id = uc.request_from) left join tbl_users as ut ON(ut.id = uc.request_to) WHERE ( request_from = '" . $user_id . "' OR request_to = '" . $user_id . "' ) AND uc.status = '" . $status . "' AND uf.status='a' AND ut.status='a' " . $wherecon . " ";

    $connections = $db->pdoQuery($connections_query)->results();
    if ($connections) {
        for ($i = 0; $i < count($connections); $i++) {
            $request_from = $connections[$i]['request_from'];
            $request_to = $connections[$i]['request_to'];
            if ($request_from == $user_id) {
                $connections_array[] = $request_to;
            } else {
                $connections_array[] = $request_from;
            }
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $connections_array = array_slice($connections_array, $offset, $limit);
    }
    return $connections_array;
}
//get following 19/9/18
function getFollowing($user_id, $return_full_array = false, $currentpage = 1, $limit = 10, $status = 'f') {
    global $db;
    $connections_array = array();
    $connections_query = "select * from tbl_follower where ( follower_form = '" . $user_id . "'  ) AND status = '" . $status . "' ";
    $connections = $db->pdoQuery($connections_query)->results();
    if ($connections) {
        for ($i = 0; $i < count($connections); $i++) {
            $follower_form = $connections[$i]['follower_form'];
            $follower_to = $connections[$i]['follower_to'];
            if ($follower_form == $user_id) {
                $connections_array[] = $follower_to;
            } else {
                $connections_array[] = $follower_form;
            }
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $connections_array = array_slice($connections_array, $offset, $limit);
    }
    return $connections_array;
}
function getSearchFollowing($keyword = '', $user_id, $return_full_array = false, $currentpage = 1, $limit = 10, $status = 'f') {
    global $db;
    $connections_array = array();
    $wherecon = '';
    if ($keyword != '') {
        $wherecon .= 'and case when ut.id='.$user_id.' then (uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%" ) when uf.id=' . $user_id . ' then (ut.first_name like "%' . $keyword . '%" OR ut.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%") end';
    }
    $connections_query = "select uc.* from tbl_follower as uc left join tbl_users as uf ON(uf.id = uc.follower_form) left join tbl_users as ut ON(ut.id = uc.follower_to) WHERE ( follower_form = '" . $user_id . "'  ) AND uc.status = '" . $status . "' " . $wherecon . " ";
    $connections = $db->pdoQuery($connections_query)->results();
    if ($connections) {
        for ($i = 0; $i < count($connections); $i++) {
            $follower_form = $connections[$i]['follower_form'];
            $follower_to = $connections[$i]['follower_to'];
            if ($follower_form == $user_id) {
                $connections_array[] = $follower_to;
            } else {
                $connections_array[] = $follower_form;
            }
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $connections_array = array_slice($connections_array, $offset, $limit);
    }
    return $connections_array;
}

function getFollower($user_id, $return_full_array = false, $currentpage = 1, $limit = 10, $status = 'f') {
    global $db;
    $connections_array = array();
    $connections_query = "select * from tbl_follower where ( follower_to = '" . $user_id . "'  ) AND status = '" . $status . "' ";
    $connections = $db->pdoQuery($connections_query)->results();
    if ($connections) {
        for ($i = 0; $i < count($connections); $i++) {
            $follower_form = $connections[$i]['follower_form'];
            $follower_to = $connections[$i]['follower_to'];
            if ($follower_to == $user_id) {
                $connections_array[] = $follower_form;
            } else {
                $connections_array[] = $follower_to;
            }
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $connections_array = array_slice($connections_array, $offset, $limit);
    }
    return $connections_array;
}
function getSearchFollower($keyword = '', $user_id, $return_full_array = false, $currentpage = 1, $limit = 10, $status = 'f') {
    global $db;
    $connections_array = array();
    $wherecon = '';
    if ($keyword != '') {
        $wherecon .= 'and case when ut.id='.$user_id.' then (uf.first_name like "%'.$keyword.'%" OR uf.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%" ) when uf.id=' . $user_id . ' then (ut.first_name like "%' . $keyword . '%" OR ut.last_name like "%' . $keyword . '%" or concat(uf.first_name," ",uf.last_name) like "%' . $keyword . '%") end';
    }
    $connections_query = "select uc.* from tbl_follower as uc left join tbl_users as uf ON(uf.id = uc.follower_form) left join tbl_users as ut ON(ut.id = uc.follower_to) WHERE ( follower_to = '" . $user_id . "'  ) AND uc.status = '" . $status . "' " . $wherecon . " ";
    $connections = $db->pdoQuery($connections_query)->results();
    if ($connections) {
        for ($i = 0; $i < count($connections); $i++) {
            $follower_form = $connections[$i]['follower_form'];
            $follower_to = $connections[$i]['follower_to'];
            if ($follower_to == $user_id) {
                $connections_array[] = $follower_form;
            } else {
                $connections_array[] = $follower_to;
            }
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $connections_array = array_slice($connections_array, $offset, $limit);
    }
    return $connections_array;
}
function shuffle_assoc($list) {
    if (!is_array($list))
        return $list;
    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key) {
        $random[$key] = $list[$key];
    }
    return $random;
}
function getImageURL($entity, $entity_id, $thumb_name,$platform='web',$return='html') {
    global $db;

    $final_result = '';
    if ("user_profile_picture" == $entity) {
        $image_name = getTableValue("tbl_users", "profile_picture_name", array("id" => (string)$entity_id));
        $first_name = getTableValue("tbl_users", "first_name", array("id" => (string)$entity_id));
        $last_name = getTableValue("tbl_users", "last_name", array("id" => (string)$entity_id));
        $img_alt = $first_name . ' ' . $last_name;
        $dir_upd = DIR_UPD_USERS.$entity_id.'/';
        $site_upd = SITE_UPD_USERS.$entity_id.'/';
    } else if ("company_logo" == $entity) {
        $image_name = getTableValue("tbl_companies", "company_logo", array("id" => (string)$entity_id));
        $company_name = getTableValue("tbl_companies", "company_name", array("id" => (string)$entity_id));
        $dir_upd = DIR_UPD_COMPANY_LOGOS;
        $site_upd = SITE_UPD_COMPANY_LOGOS;
    } else if ("company_banner" == $entity) {
        $image_name = getTableValue("tbl_companies", "banner_image", array("id" => $entity_id));
        $company_name = getTableValue("tbl_companies", "company_name", array("id" => (string)$entity_id));
        $dir_upd = DIR_UPD_COMPANY_BANNER_IMAGES;
        $site_upd = SITE_UPD_COMPANY_BANNER_IMAGES;
    } else if ("group_logo" == $entity) {
        $image_name = getTableValue("tbl_groups", "group_logo", array("id" => $entity_id));
        $dir_upd = DIR_UPD_GROUP_LOGOS;
        $site_upd = SITE_UPD_GROUP_LOGOS;
    }else if("user_cover_picture"==$entity){
        $image_name = getTableValue("tbl_users", "cover_photo", array("id" => (string)$entity_id));
        $dir_upd = DIR_UPD_USERS_COVER.$entity_id.'/';
        $site_upd = SITE_UPD_USERS_COVER.$entity_id.'/';
    }

    if ($image_name != "") {
        $img_arr= explode(".", $image_name); 
        $webp_path=$site_upd . $thumb_name . "_" . $img_arr[0].".webp";

        if (file_exists($dir_upd . $thumb_name . "_" . $image_name )) {
            if ("user_profile_picture" == $entity) {
                $profile_picture_url = $site_upd . $thumb_name . "_" . $image_name;
                if($platform == 'app'){
                    $final_result = $profile_picture_url;
                } else {
                    if(file_exists($dir_upd . $thumb_name . "_". $img_arr[0].".webp")){
                        $final_result='<picture>
                                    <source srcset="'.$webp_path.'" type="image/webp">
                                    <source srcset="' . $profile_picture_url . '" type="image/jpg">
                                    <img src="' . $profile_picture_url . '" class="" alt="' . $img_alt . '" /> 
                                </picture>';
                    }else{
                       $final_result = '<span class="profile-picture-character">' . ucfirst(mb_substr($first_name, 0, 1, 'utf-8')) . '</span>'; 
                    }
                    
                   // $final_result = '<img src="' . $profile_picture_url . '" alt="' . $img_alt . '" />';
                }
            } else {
                if("user_cover_picture" == $entity && $platform == 'app'){

                    $final_result = $site_upd . $image_name;

                }else if($platform != 'app' && "company_logo" == $entity ){
                    $url=$site_upd . $thumb_name . "_" . $image_name;
                    if(file_exists($dir_upd . $thumb_name . "_". $img_arr[0].".webp")){
                        $final_result='<picture>
                                    <source srcset="'.$webp_path.'" type="image/webp">
                                    <source srcset="' . $url . '" type="image/jpg">
                                    <img src="' . $url . '" class="" alt="img" /> 
                                </picture>';
                    }/*else{
                       $final_result= '<span class="company-letter-square company-letter">'.ucfirst($company_name[0]).'</span>';
                    }*/
                }else if($platform != 'app' && "company_banner" == $entity ){
                    $url=$site_upd . $thumb_name . "_" . $image_name;
                    if(file_exists($dir_upd . $thumb_name . "_". $img_arr[0].".webp")){
                        $final_result='<picture>
                                    <source srcset="'.$webp_path.'" type="image/webp">
                                    <source srcset="' . $url . '" type="image/jpg">
                                    <img src="' . $url . '" class="" alt="img" /> 
                                </picture>';
                    }else{
                        $url = SITE_THEME_IMG . "no-image-cover.jpg";
                        $webp_path=SITE_THEME_IMG . "no-image-cover.webp";
                        $final_result='<picture>
                                    <source srcset="'.$webp_path.'" type="image/webp">
                                    <source srcset="' . $url . '" type="image/jpg">
                                    <img src="' . $url . '" class="" alt="img" /> 
                                </picture>';
                       
                    }
                }
                else{
                    
                    $final_result = $site_upd . $thumb_name . "_" . $image_name;
                }
            }
        } else {
            if($platform == 'web'){
                if("user_cover_picture" == $entity){
                    $final_result=SITE_THEME_IMG."u-pro-bg.jpg";
                }else{
                    $final_result = '<span class="profile-picture-character">' . ucfirst(mb_substr($first_name, 0, 1, 'utf-8')) . '</span>';
                }
                
            } else {
                $final_result = "";
            }
        }
    } else {
        if($platform == 'app'){
            $final_result = "";
        }else{
            if ("user_profile_picture" == $entity) {
                $final_result = '<span class="profile-picture-character">' . ucfirst(mb_substr($first_name, 0, 1, 'utf-8')) . '</span>';
            } else if('company_logo' == $entity || 'group_logo' == $entity){
                $final_result = '';
            } else {
                if ("company_banner" == $entity && $platform == 'app'){
                    $final_result = "";
                } else if("user_cover_picture" == $entity){
                    $final_result=SITE_THEME_IMG."u-pro-bg.jpg";
                }
                else {
                    if("company_banner" == $entity && $platform != 'app'){
                        $url = SITE_THEME_IMG . "no-image-cover.jpg";
                        $webp_path=SITE_THEME_IMG . "no-image-cover.webp";
                        $final_result='<picture>
                                    <source srcset="'.$webp_path.'" type="image/webp">
                                    <source srcset="' . $url . '" type="image/jpg">
                                    <img src="' . $url . '" class="" alt="img" /> 
                                </picture>';
                    }else{
                        $final_result = SITE_THEME_IMG . "no-image-cover.jpg";

                    }
                }
            }
        }

    }
    return $final_result;
}
function getSecondDegreeConnections($user_id) {
    global $db;
    $user_ids_array = array();
    $connected_uses_ids_array = getConnections($user_id);
    if (is_array($connected_uses_ids_array) && !empty($connected_uses_ids_array)) {
        $connected_uses_ids_imploded = implode(",", $connected_uses_ids_array);
        $query = "select * from tbl_connections where ( request_from IN (" . $connected_uses_ids_imploded . ") OR request_to IN (" . $connected_uses_ids_imploded . ") ) AND status = 'a' AND
                request_from != '" . $user_id . "' AND request_to != '" . $user_id . "'  ";
        $connections = $db->pdoQuery($query)->results();
        if ($connections) {
            for ($i = 0; $i < count($connections); $i++) {
                $request_from = $connections[$i]['request_from'];
                $request_to = $connections[$i]['request_to'];
                if (in_array($request_from, $connected_uses_ids_array)) {
                    $intermediate_user_id = $request_to;
                } else {
                    $intermediate_user_id = $request_from;
                }
                if (!in_array($intermediate_user_id, $user_ids_array)) {
                    $sql_query = "select * from tbl_connections where ( ( request_from = '" . $user_id . "' AND request_to = '" . $intermediate_user_id . "' )  ) OR ( ( request_from = '" . $intermediate_user_id . "' AND request_to = '" . $user_id . "' )  ) ";
                    $checkIfRequestSent = $db->pdoQuery($sql_query)->result();
                    if (!$checkIfRequestSent) {
                        $user_ids_array[] = $intermediate_user_id;
                    }
                }
            }
        }
    }
    return $user_ids_array;
}
function getCommonConnections($first_user_id, $second_user_id, $return_full_array = false, $currentpage = 1, $limit = 10) {
    global $db;
    $common_user_ids_array = array();
    $query = "SELECT *
                FROM tbl_connections
                WHERE
                ( ( request_from = '" . $first_user_id . "' OR request_to = '" . $first_user_id . "' ) AND status = 'a' ) OR
                ( ( request_from = '" . $second_user_id . "' OR request_to = '" . $second_user_id . "' ) AND status = 'a' ) ";
    $common_connections = $db->pdoQuery($query)->results();
    if ($common_connections) {
        $first_user_connections = getConnections($first_user_id);
        $second_user_connections = getConnections($second_user_id);
        for ($i = 0; $i < count($common_connections); $i++) {
            $request_from = $common_connections[$i]['request_from'];
            $request_to = $common_connections[$i]['request_to'];
            if (!in_array($request_from, $common_user_ids_array) && $request_from != $first_user_id && $request_from != $second_user_id && in_array($request_from, $first_user_connections) && in_array($request_from, $second_user_connections)) {
                $common_user_ids_array[] = $request_from;
            }
            if (!in_array($request_to, $common_user_ids_array) && $request_to != $first_user_id && $request_to != $second_user_id && in_array($request_to, $first_user_connections) && in_array($request_to, $second_user_connections)) {
                $common_user_ids_array[] = $request_to;
            }
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $common_user_ids_array = array_slice($common_user_ids_array, $offset, $limit);
    }
    return $common_user_ids_array;
}
function getUserLanguages($user_id) {
    global $db;
    $language_array = array();
    $user_languages = $db->pdoQuery("SELECT language_id FROM tbl_user_languages WHERE user_id = " . $user_id . " ")->results();
    if ($user_languages) {
        foreach ($user_languages as $key => $value) {
            $val_array = $db->pdoQuery("SELECT language FROM tbl_languages WHERE id = " . $value['language_id'] . " and status = 'a' ")->result();
            $language_array[] = $val_array['language'];
        }
        return $language_array;
    } else {
        return false;
    }
}
function getUserSkills($user_id) {
    global $db,$lId;
    $skills_array = array();
    $user_skills=$db->pdoQuery("select skill_id from tbl_user_skills where user_id = ".$user_id." ")->results();
    if ($user_skills) {
        foreach ($user_skills as $key => $value) {
            $val_array = $db->pdoQuery("SELECT skill_name_".$lId." as skill_name FROM tbl_skills WHERE id = " . $value['skill_id'] . " and status = 'a' ")->result();
            $skills_array[] = $val_array['skill_name'];
        }
        return $skills_array;
    } else {
        return false;
    }
}
function getSimilarProfiles($user_id,$session_user_id, $return_full_array = false, $currentpage = 1, $limit = 10) {
    global $db;
    $content = NULL;
    $query = 'SELECT DISTINCT(ue.industry_id)
            FROM tbl_user_experiences ue
            LEFT JOIN tbl_companies c ON c.id = ue.company_id
            WHERE ue.user_id = ' . $user_id . '
              ';
    $similar_profile_array = $db->pdoQuery($query)->results();
    $new_array = array();
    if ($similar_profile_array) {
        foreach ($similar_profile_array as $key => $value) {
            $new_array[] = $value['industry_id'];
        }
    }

    $industry_ids = implode("','", $new_array);
    $user_id_arr_accepted = getConnections($session_user_id);
    $user_id_arr_sent = getConnections($session_user_id, false, 1, 10, 's');
    $user_id_arr = array_merge($user_id_arr_accepted, $user_id_arr_sent);
    $connection_user_ids = implode("','", $user_id_arr);
    $secondDegree = getSecondDegreeConnections($session_user_id);
    $second_degree_ids = implode("','", $secondDegree);
    $query = "SELECT u.id
            FROM tbl_users u
            LEFT JOIN tbl_user_experiences ue ON ue.user_id = u.id
            LEFT JOIN tbl_companies c ON c.id = ue.company_id
            WHERE ue.industry_id IN ('$industry_ids')
            AND u.id != '" . $session_user_id . "'
            AND u.id != '" . $user_id . "'
            AND u.id NOT IN ('$connection_user_ids')
            AND u.id IN ('$second_degree_ids')
            AND u.status = 'a'
            GROUP BY u.id ORDER BY  CASE WHEN ue.is_headline THEN 1 WHEN c.company_industry_id THEN 2  ELSE 3 END  DESC LIMIT 10";
    $similar_profile_array = $db->pdoQuery($query)->results();
    $user_ids_array = array();
    if ($similar_profile_array) {
        foreach ($similar_profile_array as $key => $value) {
            $user_ids_array[] = $value['id'];
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $user_ids_array = array_slice($user_ids_array, $offset, $limit);
    }
    return $user_ids_array;
}
function video_string($video_post){
  $video_id = explode("?v=", $video_post); 
    if (empty($video_id[1]))
        
    $video_id = explode("/v/", $video_post); 
    // $video_id = explode("&", $video_id[1]);
     // Deleting any other params
    if (empty($video_id[1]))
    $video_id = explode(".be/", $video_post);
    //$video_id = explode("&", $video_id[1]);
    if(empty($video_id[1]))
    $video_id = explode("d/", $video_post);
    $video_id = explode("&", $video_id[1]);

    
    $video_id = $video_id[0];
    $video_post='<iframe width="560" height="315" src="https://www.youtube.com/embed/'.$video_id.'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    return $video_post;
  
}
function getSingleFeed($feed_id,$platform='web',$current_user_id,$module='',$feed_act_id=0) {
    global $db;
    $final_content = "";
    $user_id = filtering($_SESSION['user_id'], 'input', 'int');
    $query = "SELECT f.*, count(sf.shared_feed_id) as no_of_shares, count(l.id) as no_of_likes, count(c.id) as no_of_comments
                FROM tbl_feeds f
                LEFT JOIN tbl_likes l ON l.feed_id = f.id
                LEFT JOIN tbl_feeds sf ON sf.shared_feed_id = f.id
                LEFT JOIN tbl_comments c ON c.feed_id = f.id
                WHERE f.id = '" . $feed_id . "'
                GROUP BY f.id ";
    $feed_details = $db->pdoQuery($query)->result();
    //_print_r($feed_details);exit();
    if ($feed_details) {
        $company_logo_feed_tpl = new Templater(DIR_TMPL . "company-logo-feed-nct.tpl.php");
        $company_logo_feed_tpl_parsed = $company_logo_feed_tpl->parse();
        $fields_company_logo = array("%IMAGE_URL%", "%COMPANY_NAME%");
       // $fields_company_logo = array("%IMG%");
        $original_author_html = $group_name = $keyword = $detail_url = $sub_id = $sub_type = "";
        $shared_feed_id = filtering($feed_details['shared_feed_id'], "input", "int");
        if ($shared_feed_id > 0) {
            $original_feed_details = $db->select("tbl_feeds", "*", array("id" => $shared_feed_id))->result();
            if ($original_feed_details) {
                $original_author_tpl = new Templater(DIR_TMPL . "original-author-nct.tpl.php");
                $feed_title_tpl_parsed = "";
                if ($original_feed_details['post_title'] != "") {
                    $feed_title_tpl = new Templater(DIR_TMPL . "feed-title-nct.tpl.php");
                    $feed_title_tpl_parsed = $feed_title_tpl->parse();
                }
                $original_author_tpl->set('post_title', $feed_title_tpl_parsed);
                $original_author_tpl_parsed = $original_author_tpl->parse();
                $fields = array(
                    "%USER_PROFILE_PICTURE%",
                    "%USER_PROFILE_URL%",
                    "%USER_NAME_FULL%",
                    // "%HEADLINE%",
                    "%FEED_TITLE%",
                    "%DESCRIPTION%",
                    "%POST_IMAGE%",
                    "%KEYWORD%",
                    "%DETAIL_PAGE_URL%",
                    "%GROUP_NAME_FULL%",
                    "%POST_VIDEO%",
                    "%VIDEO_CLASS%",
                    "%VIEW_FULL_POST%",
                    "%HIDE_FEED_VIEW%",
                    "%FEED_URL%",
                    "%VIDEO_SPACE_CLASS%"
                );
                $app_post_image = $post_image = $post_video=$org_user_status=$view_full_post=$video_space_class="";
                if($original_feed_details['image_name'] != "" && $original_feed_details['video_code'] != "" ){
                    $video_space_class="post-video-space";
                }
                if ($original_feed_details['image_name'] != "") {
                    $image_name = filtering($original_feed_details['image_name']);
                    $app_post_image = $image_url = SITE_UPD_FEEDS . $image_name;
                    $post_image_tpl = new Templater(DIR_TMPL . "post-image-nct.tpl.php");
                    $post_image_tpl_parsed = $post_image_tpl->parse();
                    $fields_post_image = array("%IMAGE_NAME%", "%IMAGE_URL%");
                    $fields_replace_post_image = array($image_name, $image_url);
                    $post_image = str_replace($fields_post_image, $fields_replace_post_image, $post_image_tpl_parsed);
                }
                $video_class="hidden";
                if($original_feed_details['video_code'] !=""){
                    $post_video=$original_feed_details['video_code'];
                    $video_class='';
                }
                if ($original_feed_details['company_id']) {
                    $company_logo_url = getImageURL("company_logo", $original_feed_details['company_id'], "th2",$platform);

                    $profile_url = get_company_detail_url($original_feed_details['company_id']);
                    $postedByName = filtering(getTableValue("tbl_companies", "company_name", array("id" => $original_feed_details['company_id'])));
                    if($company_logo_url != ''){
                        $fields_replace_company_logo = array($company_logo_url,$postedByName);
                        $profile_picture = str_replace($fields_company_logo, $fields_replace_company_logo, $company_logo_feed_tpl_parsed);
                    }
                    else{
                        $profile_picture = '<span class="profile-picture-character">' . ucfirst(mb_substr($postedByName, 0, 1, 'utf-8')) . '</span>';
                    }
                    if($platform=='app'){
                        $profile_picture=$company_logo_url;
                    }
                    $type= 'c';
                    $sub_type =$sub_id='';
                    $outher_id = $original_feed_details['company_id'];

                } else {
                    $first_name = filtering(getTableValue("tbl_users", "first_name", array("id" => $original_feed_details['user_id'])));
                    $last_name = filtering(getTableValue("tbl_users", "last_name", array("id" => $original_feed_details['user_id'])));
                    $profile_picture=getImageURL("user_profile_picture", $original_feed_details['user_id'], "th2",$platform);
                    $org_user_status=get_user_status($original_feed_details['user_id']);
                    $profile_url="javascript:void(0)";
                    if($org_user_status=='a'){
                        $profile_url = get_user_profile_url($original_feed_details['user_id']);


                    }
                    $postedByName = $first_name . " " . $last_name;
                    $type='u';
                    if($original_feed_details['type']=='a'){
                        $type='a';
                    }
                    $outher_id=$original_feed_details['user_id'];

                }

                if($original_feed_details['group_id']){
                    $group_name = getTableValue("tbl_groups","group_name",array("id"=>$original_feed_details['group_id']));
                    $detail_url = SITE_URL.'group/'.$original_feed_details['group_id'];
                    $keyword = 'in';
                    $type='u';
                    $sub_type = 'g';
                    $sub_id = $original_feed_details['group_id'];
                    $outher_id=$original_feed_details['user_id'];
                }
                if($original_feed_details['type']=='a'){
                    $ori_description= myTruncate_feed(filtering($original_feed_details['description'], "output", "text"),300);
                }else{
                    $ori_description= filtering($original_feed_details['description'], "output", "text");
                }
                $view_full_post="feed_des";
                if($original_feed_details['type']=='a'){
                    $view_full_post='';
                }
                $hide_feed_view='hidden';
                if($original_feed_details['type']=='a' && $module != 'feed-nct'){
                    $feedDescription=$original_feed_details['description'];
                    if(strlen($feedDescription) < 300){
                        $hide_feed_view='hidden';
                     }else{
                     $hide_feed_view='';
                    }
                }
                if($platform == 'web'){
                    $fields_replace = array(
                        $profile_picture,
                        $profile_url,
                        ucwords($postedByName),
                        //getUserHeadline($original_feed_details['user_id']),
                        ucwords(filtering($original_feed_details['post_title'])),
                        $ori_description,
                        ($platform == 'app'?$app_post_image:$post_image),
                        $keyword,
                        $detail_url,
                        ucwords($group_name),
                        video_string($post_video),
                        $video_class,
                        $view_full_post,
                        $hide_feed_view,
                        SITE_URL . "feed/" . encryptIt($original_feed_details['id']),
                        $video_space_class

                    );
                }else{
                    $video_post=$post_video;
                   /* if($video_post != ''){
                        
                        $video_post=video_string($video_post);
                    }*/
                    $fields_replace = array(
                        $profile_picture,
                        $profile_url,
                        $postedByName,
                        //getUserHeadline($original_feed_details['user_id']),
                        filtering($original_feed_details['post_title']),
                        filtering($original_feed_details['description'], "output", "text"),
                        ($platform == 'app'?$app_post_image:$post_image),
                        $outher_id,
                        $type,
                        $keyword,
                        $sub_id,
                        $group_name,
                        $sub_type,
                        $post_video
                    );
                }


                if($platform=='app'){
                    $keys = array('user_profile_picture','user_profile_url','user_name_full','headline','feed_title','description','post_imag','outher_id','type','keyword','sub_id','sub_name','sub_type','feed_video');
                    $original_author_html = array_combine($keys, $fields_replace);
                } else {
                    $original_author_html = str_replace($fields, $fields_replace, $original_author_tpl_parsed);
                }
            }
        }
        //share job on feed start
        if($feed_details['shared_job_id']>0){
            $job_detail = $db->select("tbl_jobs","*",array("id"=>$feed_details['shared_job_id']))->result();
            $company_name = getTableValue("tbl_companies","company_name",array("id"=>$job_detail['company_id']));
            $original_author_tpl = '';
            $original_author_tpl = new Templater(DIR_TMPL . "original-author-nct.tpl.php");
                $feed_title_tpl_parsed = "";
                if ($original_feed_details['post_title'] != "") {
                    $feed_title_tpl = new Templater(DIR_TMPL . "feed-title-nct.tpl.php");
                    $feed_title_tpl_parsed = $feed_title_tpl->parse();
                }
                $original_author_tpl->set('post_title', $feed_title_tpl_parsed);
                $original_author_tpl_parsed = $original_author_tpl->parse();
                $fields = array(
                    "%USER_PROFILE_PICTURE%",
                    "%USER_PROFILE_URL%",
                    "%USER_NAME_FULL%",
                    "%HEADLINE%",
                    "%FEED_TITLE%",
                    "%DESCRIPTION%",
                    "%POST_IMAGE%",
                    "%KEYWORD%",
                    "%DETAIL_PAGE_URL%",
                    "%GROUP_NAME_FULL%"
                );
                $company_logo_url = getImageURL("company_logo", $job_detail['company_id'], "th2",$platform);
                $image = ($platform == 'app') ? $company_logo_url : ($company_logo_url == '' ?'<span class="profile-picture-character">'.ucfirst($job_detail['job_title'][0]).'</span>' : '<img src="'.$company_logo_url.'">');
                $appendText = strlen($job_detail['key_responsibilities']) >250 ? '...' : '';
                $description = preg_replace('/\s+?(\S+)?$/', '', substr($job_detail['key_responsibilities'], 0, 250));
                if($platform == 'web'){
                $fields_replace = $app_replace =array(
                        $image,
                        get_job_detail_url($job_detail['id']),
                        ucwords($job_detail['job_title']),
                        "",
                        "",
                        $description.$appendText,
                        "",
                        AT,
                        SITE_URL.'company/'.$job_detail['company_id'],
                        $company_name

                    );
                }else{
                    $fields_replace = $app_replace =array(
                    $image,
                    get_job_detail_url($job_detail['id']),
                    $job_detail['job_title'],
                    "",
                    "",
                    $description.$appendText,
                    "",
                    $job_detail['id'],
                    'j',
                    AT,
                    $job_detail['company_id'],
                    $company_name,
                    'c'
                );
                }
                if($platform=='app'){

                    $original_author_html = "";
                    $keys = array('user_profile_picture','user_profile_url','user_name_full','headline','feed_title','description','post_imag','outher_id','type','keyword','sub_id','sub_name','sub_type');
                    $original_author_html = array_combine($keys, $app_replace);
                } else {
                    $original_author_html = str_replace($fields, $fields_replace, $original_author_tpl_parsed);
                }
        }
        //share job on feed end
        //share company on feed start
        if($feed_details['shared_company_id']>0){
            $company_detail = $db->select("tbl_companies","*",array("id"=>$feed_details['shared_company_id']))->result();
            $original_author_tpl = '';
            $original_author_tpl = new Templater(DIR_TMPL . "original-author-nct.tpl.php");
                $feed_title_tpl_parsed = "";
                if ($original_feed_details['post_title'] != "") {
                    $feed_title_tpl = new Templater(DIR_TMPL . "feed-title-nct.tpl.php");
                    $feed_title_tpl_parsed = $feed_title_tpl->parse();
                }
                $original_author_tpl->set('post_title', $feed_title_tpl_parsed);
                $original_author_tpl_parsed = $original_author_tpl->parse();
                $fields = array(
                    "%USER_PROFILE_PICTURE%",
                    "%USER_PROFILE_URL%",
                    "%USER_NAME_FULL%",
                    "%HEADLINE%",
                    "%FEED_TITLE%",
                    "%DESCRIPTION%",
                    "%POST_IMAGE%",
                    "%KEYWORD%",
                    "%DETAIL_PAGE_URL%",
                    "%GROUP_NAME_FULL%",
                    "%VIEW_FULL_POST%"
                );
                $company_logo_url = getImageURL("company_logo", $company_detail['id'], "th2",$platform);
                $image = ($platform == 'app') ? $company_logo_url : '<img src="'.$company_logo_url.'">';
                $appendText = strlen($company_detail['company_description']) >250 ? '...' : '';
                $description = preg_replace('/\s+?(\S+)?$/', '', substr($company_detail['company_description'], 0, 250));

                $com_image = ($platform == 'app') ? $company_logo_url : ($company_logo_url != '') ? '<img src="'.$company_logo_url.'">' : '<span class="profile-picture-character">'.ucfirst($company_detail['company_name'][0]).'</span>';
                $fields_replace =array(
                    $com_image,
                    get_company_detail_url($company_detail['id']),
                    $company_detail['company_name'],
                    "",
                    "",
                    $description.$appendText,
                    "",
                    $company_detail['id'],
                    'c','');
                if($platform=='app'){
                    $fields_replace =array(
                        $com_image,
                        get_company_detail_url($company_detail['id']),
                        $company_detail['company_name'],
                        "",
                        "",
                        $description.$appendText,
                        "",
                        $company_detail['id'],
                        'c');

                    $original_author_html = '';
                    $keys = array('user_profile_picture','user_profile_url','user_name_full','headline','feed_title','description','post_imag','outher_id','type');
                    $original_author_html = array_combine($keys, $fields_replace);
                } else {
                    $fields_replace =array(
                        $com_image,
                        get_company_detail_url($company_detail['id']),
                        $company_detail['company_name'],
                        "",
                        "",
                        $description.$appendText,
                        "",
                        "",
                        "",
                        "",
                        ""
                    );
                    $original_author_html = str_replace($fields, $fields_replace, $original_author_tpl_parsed);
                }
        }
        //share company on feed end
        //post activity
        $activity_status = $activity_status_html ="";

        $action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
        if($action=='post_all_activity'){
            if($feed_act_id > 0){
               $feed_act=getTableValue("tbl_feed_activity", "status", array("feed_id" => $feed_id,"user_id"=>$current_user_id,"id"=>$feed_act_id));

            }

            $likeid = getTableValue("tbl_likes", "feed_id", array("feed_id" => $feed_id,"user_id"=>$current_user_id));
            $comment_id = getTableValue("tbl_comments", "feed_id", array("feed_id" => $feed_id,"user_id"=>$current_user_id));
            $first_name = getTableValue("tbl_users", "first_name", array("id"=>$current_user_id));
            $last_name = getTableValue("tbl_users", "last_name", array("id"=>$current_user_id));
            $status_activity="";

            if($feed_act=='like' && $feed_id==$likeid){

                $activity_status_tpl = new Templater(DIR_TMPL . "post_activity_status-nct.tpl.php");
                $activity_status_tpl_parsed = $activity_status_tpl->parse();
                $fields_post_image = array("%STATUS_ACTIVITY%");
                $status_activity=ucwords($first_name)." ".ucwords($last_name)." ".LIKED_THIS;
                $fields_replace_post_image = array($status_activity);
                $activity_status_html = str_replace($fields_post_image, $fields_replace_post_image, $activity_status_tpl_parsed);

            }else if($feed_id==$comment_id && $feed_act=='comment'){
                $activity_status_tpl = new Templater(DIR_TMPL . "post_activity_status-nct.tpl.php");
                $activity_status_tpl_parsed = $activity_status_tpl->parse();
                $fields_post_image = array("%STATUS_ACTIVITY%");
                $status_activity=ucwords($first_name)." ".ucwords($last_name)." ".COMMENTED_THIS;
                $fields_replace_post_image = array($status_activity);
                $activity_status_html = str_replace($fields_post_image, $fields_replace_post_image, $activity_status_tpl_parsed);
            }else if($feed_details['posted_or_shared']=='s' && $feed_act=='share'){
                if($feed_details['shared_feed_id']!='' && $feed_details['company_id']=='' && $feed_details['group_id']==''){

                    $sharepost_id = getTableValue("tbl_feeds", "user_id", array("id" => $feed_details['shared_feed_id']));
                    $share_first_name = getTableValue("tbl_users", "first_name", array("id"=>$sharepost_id));
                    $share_last_name = getTableValue("tbl_users", "last_name", array("id"=>$sharepost_id));
                    $status_activity=ucwords($first_name)." ".ucwords($last_name)." ".SHARED." ".ucwords($share_first_name)." ".ucwords($share_last_name).POST_LBL;

                }else if($feed_details['shared_feed_id']!='' && $feed_details['company_id']!=''){
                    $company_name=getTableValue("tbl_companies", "company_name", array("id" => $feed_details['company_id']));
                    $status_activity=ucwords($first_name)." ".ucwords($last_name)." ".SHARED." ".$company_name.POST_LBL;
                }else if($feed_details['shared_feed_id']!='' && $feed_details['group_id']!=''){
                    $group_name=getTableValue("tbl_groups", "group_name", array("id" => $feed_details['group_id']));
                    $status_activity=ucwords($first_name)." ".ucwords($last_name)." ".SHARED." ".$company_name.POST_LBL;
                }
                else if($feed_details['shared_job_id']){
                    $job_title=getTableValue("tbl_jobs", "job_title", array("id" => $feed_details['shared_job_id']));
                    $status_activity=ucwords($first_name)." ".ucwords($last_name)." ".SHARED." ".JOB_FOR." ".$job_title;
                    //$first_name." ".$last_name." ".SHARED." ".$job_title.POST_LBL;
                }


                $activity_status_tpl = new Templater(DIR_TMPL . "post_activity_status-nct.tpl.php");
                $activity_status_tpl_parsed = $activity_status_tpl->parse();
                $fields_post = array("%STATUS_ACTIVITY%");

                $fields_replace_post = array($status_activity);
                $activity_status_html = str_replace($fields_post, $fields_replace_post, $activity_status_tpl_parsed);
            }else{


                $activity_status_tpl = new Templater(DIR_TMPL . "post_activity_status-nct.tpl.php");
                $activity_status_tpl_parsed = $activity_status_tpl->parse();
                $fields_post_image = array("%STATUS_ACTIVITY%");
                $status_activity=ucwords($first_name)." ".ucwords($last_name)." ".LBL_POST_THIS;
                $fields_replace_post_image = array($status_activity);
                $activity_status_html = str_replace($fields_post_image, $fields_replace_post_image, $activity_status_tpl_parsed);

            }
        }
        $single_feed_tpl = new Templater(DIR_TMPL . "single-feed-nct.tpl.php");
        $feed_title_tpl_parsed = "";
        if ($feed_details['post_title'] != "") {
            $feed_title_tpl = new Templater(DIR_TMPL . "feed-title-nct.tpl.php");
            $feed_title_tpl_parsed = $feed_title_tpl->parse();
        }
        $single_feed_tpl->set('post_title', $feed_title_tpl_parsed);
        $single_feed_tpl->set('post_actions', '');
        $single_feed_tpl_parsed = $single_feed_tpl->parse();
        $fields = array(
            "%FEED_ID_ENCRYPTED%",
            "%USER_PROFILE_PICTURE%",
            "%USER_PROFILE_URL%",
            "%USER_NAME_FULL%",
            "%FEED_URL%",
            "%HEADLINE%",
            "%ORIGINAL_AUTHOR%",
            "%FEED_TITLE%",
            "%DESCRIPTION%",
            "%POST_IMAGE%",
            "%COMMENTS%",
            '%TIME_AGO%',
            "%TITLE%",
            "%COMMENT_FORM%",
            "%LIKE_COMMENT_SHARE_LINKS%",
            "%POST_VIDEO%",
            "%HIDE_CLASS%",
            "%EDIT_FEED_URL%",
            "%VIDEO_CLASS%",
            "%ACTIVITY%",
            "%VIEW_HIDE%",
            "%DROPDOWN_HIDE%",
            "%COMMENT_HIDE%",
            "%VIEW_FULL_POST%",
            "%HIDE_FEED_VIEW%",
            "%VIEW_HIDE_PUB%",
            "%FEED_URL_NEW%",
            "%VIDEO_SPACE_CLASS%",
            "%FEED_ID%",
            "%IS_FEED_REPORTED%",
            "%IS_OWNER%"
        );
        $post_image = "";
        if ($feed_details['image_name'] != "") {
            require_once(DIR_MOD."storage.php");
            $storage = new storage();

            $image_name = filtering($feed_details['image_name']);
            $image_url = $storage->getImageUrl('av8db',$image_name);
            $post_image_tpl = new Templater(DIR_TMPL . "post-image-nct.tpl.php");
            $post_image_tpl_parsed = $post_image_tpl->parse();
            $fields_post_image = array("%IMAGE_NAME%", "%IMAGE_URL%");
            $fields_replace_post_image = array($image_name, $image_url);
            $post_image = str_replace($fields_post_image, $fields_replace_post_image, $post_image_tpl_parsed);
        }

        $isFeedReported = ($feed_details['isFeedReported'] == 'y') ? 'hide' : '';
        $isOwner = ($feed_details['user_id'] == $_SESSION['user_id']) ? 'hide' : '';

        $post_video="";
        $video_class="hidden";
        if ($feed_details['video_code'] != ""){
                    $post_video=$feed_details['video_code'];
                    $video_class='';
        }
        $feed_url = SITE_URL . "feed/" . encryptIt($feed_details['id']);

        if($feed_details['type']=='a'){
                        $editfeed_url=SITE_URL . "publish-editpost/". encryptIt($feed_details['id']);

        }else{
            $editfeed_url=SITE_URL . "edit-feed/" . encryptIt($feed_details['id']);

        }

        $class='hidden';

        if($feed_details['user_id']==$_SESSION["user_id"]){
            $class='';
        }

        $feedDescription = filtering($feed_details['description'], "output", "text");
        $hide_feed_view='hidden';
        if($feed_details['type']=='a' && $module != 'feed-nct'){
            $feedDescription=$feed_details['description'];
            $feedDescription= stripslashes(myTruncate_feed($feedDescription,300));
            if(strlen($feedDescription) < 300){
                        $hide_feed_view='hidden';
            }else{
                $hide_feed_view='';
            }
        }
        if ($feed_details['company_id']) {
            $app_img_url = $company_logo_url = getImageURL("company_logo", $feed_details['company_id'], "th2",$platform);
            $profile_url = get_company_detail_url($feed_details['company_id']);
            $postedByName = filtering(getTableValue("tbl_companies", "company_name", array("id" => $feed_details['company_id'])));
            $postedByHeadLine = "";
            if($company_logo_url != ''){
                $company_logo_url;
               $fields_replace_company_logo = array($company_logo_url, $postedByName);
                $profile_picture = str_replace($fields_company_logo, $fields_replace_company_logo, $company_logo_feed_tpl_parsed);
            }else{
                $profile_picture = '<span class="profile-picture-character">' . ucfirst($postedByName[0]) . '</span>';
            }

        } else {
            $first_name=filtering(getTableValue("tbl_users","first_name",array("id" => $feed_details['user_id'])));
            $last_name=filtering(getTableValue("tbl_users", "last_name", array("id" => $feed_details['user_id'])));
            $app_img_url = $profile_picture = $posted_image_url = getImageURL("user_profile_picture", $feed_details['user_id'], "th3",$platform);

                $user_status=get_user_status($feed_details['user_id']);
                $profile_url="javascript:void(0)";
                if($user_status=='a'){
                    $profile_url = get_user_profile_url($feed_details['user_id']);

                }

            $postedByName = $first_name . " " . $last_name;
            $postedByHeadLine = '';
            //$postedByHeadLine = getUserHeadline($feed_details['user_id']);
        }
        $comment_form = $like_comment_share_links = '';
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
            $comment_form = getCommenctForm();
            $like_comment_share_links = getLikeCommentShareLink($feed_details['id'], $_SESSION['user_id']);
        }
        $timestamp = time_elapsed_string(strtotime($feed_details['updated_on']));

        
        $view_hide=$view_hide_pub='hidden';
        $view_full_post="feed_des";
        if($feed_details['type']=='a'){
            $view_hide='';
            $view_full_post='';
        }
        if($feed_details['type']=='a' && $feed_details['status']=='s'){
            $view_hide_pub="";
        }
        $dropdown_hide=$video_space_class='';
        if($class=='hidden' && $view_hide=='hidden'){
            $dropdown_hide='hidden';
        }
        $comment_hide='';
        if($feed_details['status']=='s'){
            $comment_hide='hidden';
        }
        $feed_url_new="javascript:void(0);";

        if($module != 'feed-nct' && $module != 'group-detail-nct'){
            $feed_url_new=$feed_url;
        }else{
            if($module=='feed-nct'){
                $view_hide='hidden';

            }
        }
        if($feed_details['type']=='g'){
                    $feed_url_new="javascript:void(0);";

        }
        if($feed_details['image_name'] != '' && $feed_details['video_code'] != ''){
            $video_space_class='post-video-space';
        }
        $fields_replace = array(
            encryptIt($feed_details['id']),
            $profile_picture,
            $profile_url,
            ucwords($postedByName),
            $feed_url,
            $postedByHeadLine,
            $original_author_html,
            filtering($feed_details['post_title']),
            $feedDescription,
            $post_image,
            getCommentsContainer($feed_id),
            $timestamp,
            ucwords($feed_details['post_title']),
            $comment_form,
            $like_comment_share_links,
            video_string($post_video),
            $class,
            $editfeed_url,
            $video_class,
            $activity_status_html,
            $view_hide,
            $dropdown_hide,
            $comment_hide,
            $view_full_post,
            $hide_feed_view,
            $view_hide_pub,
            $feed_url_new,
            $video_space_class,
            $feed_details['id'],
            $isFeedReported,
            $isOwner
        );

        $cQuery = $db->pdoQuery('select c.user_id,concat(u.first_name," ",u.last_name) as name,c.comment from tbl_comments as c left join tbl_users as u on (u.id = c.user_id) where feed_id = ? order by c.id desc',array($feed_details['id']));
        $ctotal = $cQuery->affectedRows();
        $cfetch = $cQuery->result();
        if($ctotal>0){
            $chead_lines = '';
            //$chead_lines = getUserHeadline($cfetch['user_id']);
            $cimage_url = getImageURL("user_profile_picture", $cfetch['user_id'], "th3",$platform);
            $comment = filtering($cfetch['comment']);
            $last_comment = array('user_id'=>$cfetch['user_id'],'user_name'=>ucwords($cfetch['name']),'user_image'=>$cimage_url,'user_tagline'=>$chead_lines,'comment'=>$comment);
        } else {
            $last_comment = array();
        }

        if($platform == 'app'){
            $is_liked = $db->count("tbl_likes", array("user_id" => $current_user_id, "feed_id" => $feed_id));
            $user_id = (isset($feed_details['company_id']) && $feed_details['company_id']!='') ? $feed_details['company_id'] : $feed_details['user_id'];
            $feed_type = (isset($feed_details['company_id']) && $feed_details['company_id']!='') ? 'c' : 'u';
            $company_owener_id='';
            if($feed_type=='c'){
                $company_owener_id=$feed_details['user_id'];
            }
            list($width, $height) = getimagesize($image_url);
            if(!empty($original_author_html)){
                $original_author_html=$original_author_html;
            }else{
                $original_author_html=NULL;

            }
            $video_post=$post_video;
            
            $feed_details_type=$feed_details['type'];
            if(($feed_details['type']=='c' && $feed_details['shared_company_id'] !='') || ($feed_details['type']=='j' && $feed_details['shared_job_id'] !='') || $feed_details['type']=='g'){
                $feed_details_type='u';
            }
            $app_array = array(
                'post_id'=>$feed_details['id'],
                'user_id'=>$user_id,
                'feed_user_type'=>$feed_details_type,
                'user_name'=>$postedByName,
                'user_profile_image' => $app_img_url,
                'user_tagline'=>$postedByHeadLine,
                'post_title'=>filtering($feed_details['post_title']),
                'post_description'=>filtering($feed_details['description'], "output", "text"),
                'post_image'=>(($image_url!='' && $app_post_image == '')?$image_url:''),
                'post_image_height'=>(($height != "")?$height:0),
                'post_image_width'=>(($width != "")?$width:0),
                'is_liked'=>(($is_liked>0)?true:false),
                'no_of_likes'=>getLikeCount($feed_details['id']),
                'no_of_comments'=>getCommentsCount($feed_details['id']),
                'no_of_shares'=>getSharesCount($feed_details['id']),
                'timestamp'=>$timestamp,
                'last_comment'=>$last_comment,
                'posted_or_shared'=>$feed_details['posted_or_shared'],
                'original_author'=>$original_author_html,
                'feed_video'=>$video_post,
                'activity_status'=>(($status_activity != "")?$status_activity:""),
                'user_status'=>$user_status,
                'org_user_status'=>(($org_user_status != "")?$org_user_status:""),
                'company_owener_id'=>$company_owener_id,
                'shared_with'=>$feed_details['shared_with'],
                'type_feed'=>$feed_details['type'],
                'group_id'=>($feed_details['group_id']>0)?$feed_details['group_id']:''

            );
            $final_content = $app_array;
        } else {
            $final_content = str_replace($fields, $fields_replace, $single_feed_tpl_parsed);
        }
        return $final_content;
    } else {
        return false;
    }
}
function getCommenctForm() {
    $comment_form = new Templater(DIR_TMPL . "comment-form-nct.tpl.php");
    $comment_form_parsed = $comment_form->parse();
    $fields = array("%POST_COMMENT_URL%");
    $fields_replace = array(SITE_URL . "post-comment");
    $final_result = str_replace($fields, $fields_replace, $comment_form_parsed);
    return $final_result;
}
function getLikeCommentShareLink($feed_id, $user_id) {
    global $db;
    $like_comment_share_links = new Templater(DIR_TMPL . "like-comment-share-links-nct.tpl.php");
    $like_comment_share_links_parsed = $like_comment_share_links->parse();
    $fields = array("%LIKE_OR_UNLIKE%","%NO_OF_LIKES%","%NO_OF_SHARES%","%NO_OF_COMMENTS%","%LIKE_CLASS%","%FEED_ID%","%IS_FEED_REPORTED%","%IS_OWNER%");
    $checkIfLiked = $db->select("tbl_likes", "*", array("user_id" => $user_id, "feed_id" => $feed_id))->result();
    $feed_details = $db->select("tbl_feeds", "*", array("id" => $feed_id))->result();
    $isFeedReported = ($feed_details['isFeedReported'] == 'y') ? 'hide' : '';
    $isOwner = ($feed_details['user_id'] == $_SESSION['user_id']) ? 'hide' : '';

    if ($checkIfLiked) {
        $like_or_unlike = LBL_UNLIKE;
        $class = 'fa fa-thumbs-down';
    } else {
        $like_or_unlike = LBL_COM_DET_LIKE;
        $class = 'fa fa-thumbs-up';
    }
    $fields_replace = array(
        $like_or_unlike,
        getLikeCount($feed_id),
        getSharesCount($feed_id),
        getCommentsCount($feed_id),
        $class,
        $feed_id,
        $isFeedReported,
        $isOwner
    );
    $final_result = str_replace($fields, $fields_replace, $like_comment_share_links_parsed);
    return $final_result;
}
function getSingleCommentBox($feed_id, $comment_id,$platform='web') {
    global $db;
    $sql = "SELECT c.*, u.first_name, u.last_name
        FROM tbl_comments c
        LEFT JOIN tbl_users u ON u.id = c.user_id
        WHERE c.feed_id = '" . $feed_id . "' AND c.id = '" . $comment_id . "' ";
    $comment = $db->pdoQuery($sql)->result();
    if ($comment) {
        $single_comment_tpl = new Templater(DIR_TMPL . "single-comment-nct.tpl.php");
        $single_comment_tpl_parsed = $single_comment_tpl->parse();

        $user_status=get_user_status($comment['user_id']);
        $user_profile_url="javascript:void(0)";
        if($user_status=='a'){
            $user_profile_url = get_user_profile_url($comment['user_id']);

        }

        $first_name = filtering(getTableValue("tbl_users", "first_name", array("id" => $comment['user_id'])));
        $last_name = filtering(getTableValue("tbl_users", "last_name", array("id" => $comment['user_id'])));
        $user_name_full = $first_name . " " . $last_name;
        $fields = array(
            "%USER_PROFILE_PICTURE%",
            "%USER_PROFILE_URL%",
            "%USER_NAME_FULL%",
            "%COMMENT%",
            "%TIME_AGO%",
            "%COMMENT_ID%",
            "%DEL_COMMENT%"
        );
        /*$dateDifference = get_time_difference($comment['update_on'], date("Y-m-d H:i:s"));
        if ($dateDifference['days']) {
            $time_ago = $dateDifference['days'] ." ". LBL_DAYS_AGO;
        } else if ($dateDifference['hours']) {
            $time_ago = $dateDifference['hours'] ." ". LBL_HOURS_AGO;
        } else if ($dateDifference['minutes']) {
            $time_ago = $dateDifference['minutes'] ." ".LBL_MINS_AGO;
        } else if ($dateDifference['seconds']) {
            $time_ago = $dateDifference['seconds'] . " ".LBL_SEC_AGO;
        } else {
            $time_ago = LBL_AGO;
        }*/
        $timestamp = time_elapsed_string(strtotime($comment['update_on']));


        $uImage = getImageURL("user_profile_picture", $comment['user_id'], "th2",$platform);
        $comment_text = filtering($comment['comment'], 'output');
        $del_class='hidden';
        if($_SESSION["user_id"]==$comment['user_id']){
            $del_class='';
        }
        $id_comment=filtering($comment['id'], 'output');

        $fields_replace = array(
            $uImage,
            $user_profile_url,
            ucwords($user_name_full),
            $comment_text,
            $timestamp,
            $id_comment,
            $del_class
        );
        if($platform=='app'){
            $app_array = array('user_id'=>$comment['user_id'],
                'user_name'=>$user_name_full,
                'user_image'=>$uImage,
                'comment'=>$comment_text,
                'time_ago'=>$timestamp,
                'commented_on'=>$comment['update_on'],
                'formatted_date'=>date('F d,Y | h:m A',strtotime($comment['update_on'])),
                'comment_id'=>$comment['id'],
                'user_status'=>$user_status
            );
            $final_result = $app_array;
        } else {
            $final_result = str_replace($fields, $fields_replace, $single_comment_tpl_parsed);
        }
    }
    return $final_result;
}
function cmp($a, $b) {
    return $a["id"] - $b["id"];
}

function getComments($feed_id, $currentPage = 1,$platform='web') {
    global $db;
    $response = array();
    $response['status'] = false;
    $comments_html = "";
    $limit = ($platform=='app'?10:2);
    $next_available_records = 0;
    $offset = ($currentPage - 1 ) * $limit;
    $count = $db->count('tbl_comments',array('feed_id'=>$feed_id));
    $sql = "SELECT c.*, u.first_name, u.last_name
                FROM tbl_comments c
                LEFT JOIN tbl_users u ON u.id = c.user_id
                WHERE c.feed_id = '" . $feed_id . "' ORDER BY c.id desc ";

    $sql_with_limit = $sql . " LIMIT $offset,$limit" ;

    $comments = $db->pdoQuery($sql_with_limit)->results();
    usort($comments, "cmp");
    $app_array = array();
    if ($comments) {
        $sql_with_next_limit = $sql . " LIMIT " . $limit . " OFFSET " . ( $offset + $limit );
        $next_comments = $db->pdoQuery($sql_with_next_limit)->results();
        $next_available_records = count($next_comments);
        if ($next_available_records > 0) {
            $load_more_comments_tpl = new Templater(DIR_TMPL . "load-more-comments-nct.tpl.php");
            $load_more_link = SITE_URL . "getComments/feed_id/" . encryptIt($feed_id) . "/currentPage/" . ($currentPage + 1);
            $load_more_comments_tpl->set('load_more_link', $load_more_link);
            $comments_html .= $load_more_comments_tpl->parse();
        }

        for ($i = 0; $i < count($comments); $i++) {
            $comment_id = $comments[$i]['id'];
            $getSingleCommentBox = getSingleCommentBox($feed_id, $comment_id,$platform);
            if($platform == 'app'){
                $app_array[] = $getSingleCommentBox;
            } else {
                $comments_html .= $getSingleCommentBox;
            }
        }
    }
    $response['status'] = true;
    if($platform == 'app'){
        $response['comments'] = $app_array;
        $page_data = getPagerData($count, $limit,$currentPage);
        $response['pagination'] = array('current_page'=>$currentPage,'total_pages'=>$page_data->numPages,'total'=>$count);
    } else {
        $response['comments'] = $comments_html;
    }
    return $response;
}
function getCommentsContainer($feed_id) {
    global $db;
    $final_result = "";
    $commentsCount = getCommentsCount($feed_id);
    $comments_container_tpl = new Templater(DIR_TMPL . "comments-container-nct.tpl.php");
    $comments_container_tpl_parsed = $comments_container_tpl->parse();
    $fields = array("%COMMENTS%");
    $response = getComments($feed_id);
    $fields_replace = array($response['comments']);
    $final_result = str_replace($fields, $fields_replace, $comments_container_tpl_parsed);
    return $final_result;
}
function getPendingInvitations($user_id,$return_full_array = false, $currentpage = 1, $limit = 10, $status = 's') {
    global $db;
    $invitations_array = array();
    $query = "SELECT * FROM tbl_connections WHERE (request_to = '".$user_id."' ) AND status = '" . $status . "' ";
    $invitations = $db->pdoQuery($query)->results();
    if ($invitations) {
        for ($i = 0; $i < count($invitations); $i++) {
           $con_id= getTableValue("tbl_connections", "id", array("request_from" => $user_id,"request_to"=>$invitations[$i]['request_from'],"status"=>'a'));
           if($con_id==''){
                $request_from = $invitations[$i]['request_from'];
                $invitations_array[] = $request_from;
           }
            
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $invitations_array = array_slice($invitations_array, $offset, $limit);
    }
    return $invitations_array;
}
function getSentInvitations($user_id, $return_full_array = false, $currentpage = 1, $limit = 10, $status = 's') {
    global $db;
    $invitations_array = array();
    $query = "SELECT * FROM tbl_connections WHERE (request_from = '".$user_id."') AND status = '" . $status . "' ";
    $invitations = $db->pdoQuery($query)->results();
    if ($invitations) {
        for ($i = 0; $i < count($invitations); $i++) {
            $con_id= getTableValue("tbl_connections", "id", array("request_to" => $user_id,"request_from"=>$invitations[$i]['request_to'],"status"=>'a'));
           if($con_id==''){
            $request_to = $invitations[$i]['request_to'];
            $invitations_array[] = $request_to;
           }
            
        }
    }
    if ($return_full_array) {
        $offset = ( $currentpage - 1 ) * $limit;
        $invitations_array = array_slice($invitations_array, $offset, $limit);
    }
    return $invitations_array;
}
function insertVisitors($visitor_id, $visited_id) {
    global $db;
    $query = "SELECT * FROM tbl_profile_visits WHERE visitor_id = '" . $visitor_id . "' AND visited_id = '" . $visited_id . "' AND DATE(visited_on) = '" . date("Y-m-d") . "' ";
    $checkIfVisited = $db->pdoQuery($query)->result();
    if (!$checkIfVisited) {
        $arrayToBeInserted = array(
            "visitor_id" => $visitor_id,
            "visited_id" => $visited_id,
            "visited_on" => date("Y-m-d H:i:s"),
        );
        $id = $db->insert('tbl_profile_visits', $arrayToBeInserted)->getLastInsertId();
    }
}
function checkIfAbleToSendInMails($user_id) {
    global $db;
    $user_inmails = $db->select("tbl_user_inmails", "*", array("user_id" => $user_id))->result();
    if ($user_inmails) {
        $inmails_expires_on = strtotime($user_inmails['inmails_expires_on']);
        $adhoc_inmails_expires_on = strtotime($user_inmails['adhoc_inmails_expires_on']);
        if ($inmails_expires_on > time()) {
            $inmails_outstanding = filtering($user_inmails['inmails_outstanding'], 'output', 'int');
            if ($inmails_outstanding > 0) {
                return true;
            } else {
                return false;
            }
        } else if ($adhoc_inmails_expires_on > time()) {
            $adhoc_inmails_outstanding = filtering($user_inmails['adhoc_inmails_outstanding'], 'output', 'int');
            if ($adhoc_inmails_outstanding > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function deductInMail($user_id) {
    global $db;
    $user_inmails = $db->select("tbl_user_inmails", "*", array("user_id" => $user_id))->result();
    if ($user_inmails) {
        $inmails_expires_on = strtotime($user_inmails['inmails_expires_on']);
        $adhoc_inmails_expires_on = strtotime($user_inmails['adhoc_inmails_expires_on']);
        if ($inmails_expires_on > time() || $adhoc_inmails_expires_on > time()) {
            $inmails_outstanding = filtering($user_inmails['inmails_outstanding'], 'output', 'int');
            $adhoc_inmails_outstanding = filtering($user_inmails['adhoc_inmails_outstanding'], 'output', 'int');
            if ($inmails_expires_on > time() && $adhoc_inmails_expires_on > time()) {
                if ($inmails_expires_on > $adhoc_inmails_expires_on && $adhoc_inmails_outstanding > 0) {
                    $deduct_from = 'a';
                } else {
                    $deduct_from = 'r';
                }
            } else {
                if ($inmails_expires_on > time()) {
                    $deduct_from = 'r';
                } else {
                    $deduct_from = 'a';
                }
            }
            $updateArray = array();
            if ($deduct_from == 'r') {
                $updateArray['inmails_outstanding'] = $inmails_outstanding - 1;
            } else {
                $updateArray['adhoc_inmails_outstanding'] = $adhoc_inmails_outstanding - 1;
            }
            $updateArray['updated_on'] = date("Y-m-d H:i:s");
            $affectedRows = $db->update("tbl_user_inmails", $updateArray, array("user_id" => $user_id))->affectedRows();
            if ($affectedRows > 0) {
                return true;
            } else {
                return true;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function getLikeCount($feed_id) {
    global $db;
    return $db->count("tbl_likes", array("feed_id" => $feed_id));
}
function getCommentsCount($feed_id) {
    global $db;
    return $db->count("tbl_comments",array("feed_id" => $feed_id));
}
function getSharesCount($feed_id) {
    global $db;
    return $db->count("tbl_feeds", array("shared_feed_id" => $feed_id));
}
function getYears($date1, $date2, $format = 'YearMonth') {
    $difference = '';
    $date1 = new DateTime($date1);
    $date2 = new DateTime($date2);
    $interval = date_diff($date1, $date2);
    $months = $interval->m + ($interval->y * 12);
    return $months / 12;
}
function getUserExperience($user_id, $company_id) {
    global $db;
    $industry_id = getTableValue("tbl_companies", "company_industry_id", array("id" => $company_id));
    $query = $db->pdoQuery('Select from_month,from_year,to_month,to_year,is_current from tbl_user_experiences as ue LEFT JOIN tbl_companies as c ON(ue.company_id = c.id) where ue.user_id = ' . $user_id . ' and c.company_industry_id = ' . $industry_id . '')->results();
    $finalExperience = 0;
    foreach ($query as $key => $experiences) {
        $months_array = unserialize(MONTHS_ARRAY);
        $from=$months_array[filtering($experiences['from_month'])-1] . ' ' . filtering($experiences['from_year']);
        $from_date = filtering($experiences['from_year']) . "-" . filtering($experiences['from_month']) . "-01";
        if ($experiences['is_current'] == 'y') {
            $to = "Present";
            $to_date = date("Y-m-d");
        } else {
            $to=$months_array[filtering($experiences['to_month']) - 1] . ' ' . filtering($experiences['to_year']);
            $to_date = filtering($experiences['to_year']) . "-" . filtering($experiences['to_month']) . "-01";
        }
        $experience = getYears($from_date, $to_date);
        $finalExperience += $experience;
    }
    return ceiling($finalExperience, 0.05);
}
function getGroupMember($group_id) {
    global $db,$userIds;
    $query=$db->pdoQuery('select user_id from tbl_group_members where group_id = "' . $group_id . '"')->results();
    foreach ($query as $key => $value) {
        $userIds .= ',' . $value['user_id'];
    }
    return substr($userIds, 1);
}
function company_follower($company_id){
    global $db;
    $query=$db->pdoQuery('select user_id from tbl_company_followers where company_id = "' . $company_id . '"')->results();
    foreach ($query as $key => $value) {
        $userIds .= ',' . $value['user_id'];
    }
    return substr($userIds, 1);
}
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1) {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number / $significance) * $significance) : false;
    }
}
function getStatisticsArray() {
    global $db;
    $returnArray = array();
    if(STATISTICS_DISP_TYPE == 'l'){
        $qrySel = $db->pdoQuery("SELECT * FROM  tbl_users")->results();
        $countUser = count($qrySel);
        $qrySel = $db->pdoQuery("SELECT * FROM  tbl_jobs")->results();
        $countJobs = count($qrySel);
        $qrySel = $db->pdoQuery("SELECT * FROM  tbl_companies WHERE company_type = 'r' ")->results();
        $countCompanies = count($qrySel);
        $qrySel = $db->pdoQuery("SELECT * FROM  tbl_groups")->results();
        $countGroups = count($qrySel);
        $qrySel = $db->pdoQuery("SELECT sum(total_price) as revenuEarned FROM  tbl_payment_history where payment_status = 'c'")->results();
        $revenuEarned = CURRENCY_SYMBOL . $qrySel[0]['revenuEarned'];
        $returnArray["total_users"] = $countUser;
        $returnArray["total_jobs"] = $countJobs;
        $returnArray["total_companies"] = $countCompanies;
        $returnArray["total_groups"] = $countGroups;
        $returnArray["revenue_earned"] = $revenuEarned;
    }else{
       $qrySel = $db->pdoQuery("SELECT * FROM  tbl_homepage_statics WHERE status = 'y' ")->results(); 
       $countUser=$countJobs=$countCompanies=$countGroups=0;
       foreach ($qrySel as $value) {
            if($value['type']=='Total users'){
                $countUser=$value['value'];

            }else if($value['type']=='Total business'){
                $countCompanies=$value['value'];

           }else if($value['type']=='Total jobs'){
                    $countJobs=$value['value'];

           }else if($value['type']=='Total groups'){
                    $countGroups=$value['value'];

           }
       }
      
        $returnArray["total_users"] = $countUser;
        $returnArray["total_jobs"] = $countJobs;
        $returnArray["total_companies"] = $countCompanies;
        $returnArray["total_groups"] = $countGroups;
    }

    
    return $returnArray;
}
function getGroupMembers($groups = array()) {
    global $db;
    $groupIds = implode(',', $groups);
    $query = $db->pdoQuery('Select user_id from tbl_group_members where group_id IN(' . $groupIds . ') AND action IN ("a","aa") group by user_id')->results();
    $groupMembers = array();
    foreach ($query as $key => $value) {
        $groupMembers[] = $value['user_id'];
    }
    return $groupMembers;
}
function get_user_status($user_id){
    global $db;
    $user_status=getTableValue("tbl_users", "status", array("id" => $user_id));
    return $user_status;
}
function get_user_profile_url($id = 0){ return SITE_URL . "profile/" . $id; }
function get_job_detail_url($id = 0){ return SITE_URL . "job/" . $id; }
function get_company_detail_url($id = 0){ return SITE_URL . "company/" . $id; }
function get_group_detail_url($id = 0){ return SITE_URL . "group/" . $id; }
function _print_r($arry,$bool=false){echo "<pre>";print_r($arry);echo "</pre>";if($bool)exit;}
function doLogin($id=0,$first_name='',$last_name=''){
    $_SESSION['user_id'] = $id;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;
}
function myTruncate($string, $limit, $break=" ", $pad="...",$onlyText=true){
    $string=($onlyText==true)?str_replace('&nbsp;',' ',strip_tags($string)):$string;
    if(strlen($string) <= $limit) return $string;
    if(false !== ($breakpoint = strpos($string, $break, $limit))) {
        if($breakpoint < strlen($string) - 1) {$string = substr($string, 0, $breakpoint) . $pad;}
    }
    return $string;
}
function reverse_geocode($address) {
    $address = str_replace(" ", "+", $address);
    //$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
    $url = "https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&key=".GOOGLE_MAPS_API_KEY;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($response);
    //print_r($json);
    foreach ($json->results as $result) {
        $address1='';
        foreach($result->address_components as $addressPart) {
            if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types)))
                $city = $addressPart->long_name;
            else if((in_array('administrative_area_level_1', $addressPart->types)  && (in_array('political', $addressPart->types))) || (in_array('administrative_area_level_2', $addressPart->types) && (in_array('political', $addressPart->types)))){
                $state = $addressPart->long_name;
            } else if((in_array('postal_code', $addressPart->types))){
                $postal_code = $addressPart->long_name;
            } else if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types)))
                $country = $addressPart->long_name;
            else
                $address1 .= $addressPart->long_name.", ";
        }
        if(($city != '') && ($state != '') && ($country != ''))
            $address = $city.', '.$state.', '.$country;
        else if(($city != '') && ($state != ''))
            $address = $city.', '.$state;
        else if(($state != '') && ($country != ''))
            $address = $state.', '.$country;
        else if($country != '')
            $address = $country;
        $address1=trim($address1, ",");
        $array["country"]=$country;
        $array["state"]=$state;
        $array["city"]=$city;
        $array["address"]=$address1;
        $array["postal_code"]=$postal_code;
    }
    $array["status"]=$json->status;
    $array['lat'] = $json->results[0]->geometry->location->lat;
    $array['long'] = $json->results[0]->geometry->location->lng;
    return $array;
}
function push_notification($data_array=array()){
    $device_id = isset($data_array["device_id"])?$data_array["device_id"]:"";
    $device_type = isset($data_array["device_type"])?$data_array["device_type"]:"";
    $title = isset($data_array["title"])?$data_array["title"]:"";
    $status = isset($data_array["status"])?$data_array["status"]:"a";
    //print_r()
    if($device_type=='ios'){
       userSendNotificationiOS($device_id,$data_array);

    }else{
        $data_array['status']=$status;
        $data_array['body']=$title;
        $data_array['title']=SITE_NM;
        $data_array['sound']='default';


        $fields = array(
            'to' =>  $device_id ,
            "priority"=> "high",
            // 'notification' => array(
            //     'body' => $title,
            //     'title' => SITE_NM,
            //     'sound' => 'default'
            // ),
            'data' => $data_array
        );

        $headers = array(
            'Authorization: key='.FIREBASE_SERVER_KEY,
            'Content-Type: application/json'
         );


        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result  = curl_exec($ch);
        curl_close($ch);
        //sendEmailAddress('surbhi.katar@ncrypted.com', 'push noti out', json_encode($fields));
    }
    
    return $result;


}
function userSendNotificationiOS($sender_token = '', $noti_message = '') {
   // sendEmailAddress('surbhi.katar@ncrypted.com', 'push noti out', $noti_message);
    $deviceToken = $sender_token;
    $ctx = stream_context_create();
    // ck.pem is your certificate file
    //stream_context_set_option($ctx, 'ssl', 'local_cert', DIR_INC.'functions-nct/apns-dis-cert.pem');
    stream_context_set_option($ctx, 'ssl', 'local_cert', DIR_INC.'functions-nct/Connectin.pem');

    stream_context_set_option($ctx, 'ssl', 'passphrase', '123');
    // Open a connection to the APNS server
    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

    if (!$fp)
    {
        //sendEmailAddress('surbhi.katar@ncrypted.com', 'push noti err', $fp);

        return;
        //exit("Failed to connect: $err $errstr" . PHP_EOL);
    }
    // Create the payload body

    $body['aps'] = array(
        'alert' => array(
            'body' => $noti_message['title'],
            'title' => SITE_NM,
        ),
        'sound' => 'default',
        'data' => $noti_message
    );
    
    $payload = json_encode($body);
    // Build the binary notification
    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

   // sendEmailAddress('surbhi.katar@ncrypted.com', 'push noti err', $msg);

    $result = fwrite($fp, $msg, strlen($msg));

    // Close the connection to the server
    fclose($fp);
   /* if (!$result){
        echo 123;die;
    }else{
        echo "success";die;
    }*/
}
function set_notification($user_id=0,$activity='',$data_array=array()){
    global $db;

    if($user_id>0){
        $wherecon='';
        if($activity == 'LIKED'){
            $wherecon= " AND n.feed_id = ".$data_array['feed_id']." ";

        }else if($activity == 'COMMENT'){
            $wherecon= " AND n.feed_id = ".$data_array['feed_id']." ";

        }else if($activity == 'cra'){
            $wherecon='';
        }else if($activity == 'rgjr'){
            $wherecon= " AND n.group_id = ".$data_array['group_id']." ";

        }else if($activity == 'gjra'){
            $wherecon= " AND n.group_id = ".$data_array['group_id']." ";

        }else if($activity == 'nfg'){
            $wherecon= " AND n.group_id = ".$data_array['group_id']."  AND n.feed_id = ".$data_array['feed_id']." ";

        }else if($activity == 'ampg'){
            $wherecon= " AND n.group_id = ".$data_array['group_id']." ";
        }else if($activity == 'rgji'){
            $wherecon= " AND n.group_id = ".$data_array['group_id']." ";

        }else if($activity == 'fc'){
            $wherecon= " AND n.company_id = ".$data_array['company_id']." ";

        }else if($activity == 'aj'){
            $wherecon= " AND n.job_id = ".$data_array['job_id']." ";

        }else if($activity == 'share'){
            $wherecon= " AND n.feed_id = ".$data_array['feed_id']." ";

        }else if($activity == 'fu'){
            $wherecon='';
        }else if($activity == 'nfc'){
            $wherecon= " AND n.company_id = ".$data_array['company_id']." ";
            if($data_array['job_id'] > 0){
                $wherecon .=" AND n.job_id = ".$data_array['job_id']."";
            }else if($data_array['feed_id'] > 0){
                $wherecon.=" AND n.feed_id = ".$data_array['feed_id']."";
            }

        }else if($activity == 'jpc'){
            $wherecon= " AND n.job_id = ".$data_array['job_id']." ";

        }


        $notification_id=$data_array['notification_id'];
        $notify_id='';
        if($notification_id > 0){
            $notify_id= " AND n.id = ".$data_array['notification_id']." ";

        }
        $query = "select n.id as postid,n.added_on,n.type,n.action_by_user_id,n.feed_id,n.group_id,n.job_id,n.company_id  FROM tbl_notifications n WHERE n.user_id = ? ".$notify_id." ".$wherecon." ";
        $getAllResults = $db->pdoQuery($query,array($user_id))->result();

        $notification_date = $getAllResults['added_on'];
        $time_ago = time_elapsed_string(strtotime($getAllResults['added_on']));
        $type = $getAllResults['type'];
        $action_by_user_id = filtering($getAllResults['action_by_user_id'], 'input', 'int');
        $feed_id = filtering($getAllResults['feed_id'], 'input', 'int');
        $group_id = filtering($getAllResults['group_id'], 'input', 'int');
        $job_id = filtering($getAllResults['job_id'], 'input', 'int');
        $company_id = filtering($getAllResults['company_id'], 'input', 'int');


            if ($action_by_user_id > 0) {
                $action_by_user_details = $db->select("tbl_users", array('first_name,last_name'), array("id" => $action_by_user_id))->result();
                $action_by_user_name = ucwords(filtering($action_by_user_details['first_name'])) . " " . ucwords(filtering($action_by_user_details['last_name']));
            }

            if ($feed_id > 0) {
                $feed_details = $db->select("tbl_feeds", array('post_title'), array("id" => $feed_id))->result();
                $post_title = ucwords(filtering($feed_details['post_title']));
            }

            if ($group_id > 0) {
                $group_details = $db->select("tbl_groups", array('group_name'), array("id" => $group_id))->result();
                $group_name = ucwords(filtering($group_details['group_name']));
            }

            if ($job_id > 0) {
                $job_details = $db->select("tbl_jobs", array('job_title'), array("id" => $job_id))->result();
                $job_title = ucwords(filtering($job_details['job_title']));
            }

            if ($company_id > 0) {
                $company_details = $db->select("tbl_companies", array('company_name'), array("id" => $company_id))->result();
                $company_name = ucwords(filtering($company_details['company_name']));
            }




        $device_ids = $db->select('tbl_logged_devices',array('device_id','device_type'),array('user_id'=>$user_id))->results();
        foreach ($device_ids as $devices) {
            if($activity == 'LIKED'){
                $notification_type = 'like_post';
                $notification_text = $action_by_user_name . ' '.LBL_LIKED_YOUR_POST . ' '. $post_title;
                $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                $notification_title = $action_by_user_name . ' '.LBL_LIKED_YOUR_POST.' ' . $post_title;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');


                $push_data_array['title'] = $data_array['liked_by'].' '.LBL_LIKED_YOUR_POST;
                $push_data_array['count']=1;
             } else if($activity == 'COMMENT'){
                $notification_type = 'comment_on_post';
                $notification_text = $action_by_user_name . ' '.LBL_COMMENTED_ON_YOUR_POST.' ' . $post_title;
                $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                $notification_title = $action_by_user_name . ' '.LBL_COMMENTED_ON_YOUR_POST.' ' . $post_title;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');

                $push_data_array['title'] = $data_array['commented_by'].' '.LBL_COMMENTED_ON_YOUR_POST;
                $push_data_array['count']=2;
            } else if($activity == 'cra'){
                $notification_type = 'connection_request_accept';
                $notification_text = LBL_COM_DET_YOUR_CONNECTION_REQUEST_ACCEPTED .' '. $action_by_user_name;
                $notification_url = get_user_profile_url($action_by_user_id);
                $notification_title = LBL_CONNECTION_REQUEST_ACCEPTED;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');

                $push_data_array['title']=LBL_COM_DET_YOUR_CONNECTION_REQUEST_ACCEPTED.' '.$data_array['user_name'];
                $push_data_array['count']=3;
            } else if($activity == 'rgjr'){
                $notification_type = 'group_join_request';
                $notification_text = $action_by_user_name.' ' . LBL_SENT_REQUEST_JOIN_GROUP.' '  . $group_name;
                $notification_url = get_group_detail_url($group_id);
                $notification_title = LBL_SENT_REQUEST_JOINING_GROUP;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_SENT_REQUEST_JOIN_GROUP.' '.$data_array['group_name'];
                $push_data_array['count']=4;
            } else if($activity == 'gjra'){
                $notification_type = 'request_accept_to_join_group';
                $notification_text = $action_by_user_name.' ' .LBL_ACCEPTED_YOUR_REQUEST_FOR_JOINING_GROUP .' ' . $group_name;
                $notification_url = get_group_detail_url($group_id);
                $notification_title = LBL_GROUP_JOINING_REQUEST_ACCEPTED ;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');

                $push_data_array['title'] = $data_array['user_name'].' '.LBL_ACCEPTED_YOUR_REQUEST_FOR_JOINING_GROUP.' '.$data_array['group_name'];
                $push_data_array['count']=5;

            } else if($activity == 'nfg'){
                $notification_type = 'posted_in_group';
                $notification_text = $action_by_user_name.' ' . LBL_POSTED_GROUP.' '. $group_name;
                $notification_url = get_group_detail_url($group_id).'?id='.encryptIt($feed_id).'#'.encryptIt($feed_id);
                $notification_title = LBL_NEW_POST;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_POSTED_GROUP.' '.$data_array['group_name'];
                $push_data_array['count']=6;

            } else if($activity == 'ampg'){
                $notification_type = 'added_in_group';
                $notification_text = $action_by_user_name.' ' . LBL_ADDED_IN_GROUP .' '. $group_name;
                $notification_url = get_group_detail_url($group_id);
                $notification_title = LBL_ADDED_MEMBER;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_ADDED_IN_GROUP.' '.$data_array['group_name'];
                $push_data_array['count']=7;

            } else if($activity == 'rgji'){
                $notification_type = 'group_join_invitaion';
                $notification_text= $action_by_user_name.' ' . LBL_INVITED_YOU_TO_JOIN_GROUP.' ' . $group_name;
                $notification_url = get_group_detail_url($group_id);
                $notification_title = LBL_GROUP_JOINING_INVITATION;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');


                $push_data_array['title'] = $data_array['user_name'].' '.LBL_INVITED_YOU_TO_JOIN_GROUP.' '.$data_array['group_name'];
                $push_data_array['count']=8;

            } else if($activity == 'fc'){
                $notification_type = 'follow_company';
                $notification_text = $action_by_user_name.' ' . LBL_FOLLOWED_COMPANY .' '. $company_name;
                $notification_url = get_company_detail_url($company_id);
                $notification_title = LBL_FOLLOW_COMPANY;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_FOLLOWED_COMPANY.' '.$data_array['company_name'];
                $push_data_array['count']=9;
            } else if($activity == 'aj'){
                $notification_type = 'applied_on_job';
                $notification_text = $action_by_user_name.' ' . LBL_APPLIED_ON_JOB.' ' . $job_title;
                $notification_url = SITE_URL . "job-applicants/job/" . $job_id;
                $notification_title = LBL_APPLIED_ON_JOB_CAPITAL;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_APPLIED_ON_JOB.' '.$data_array['job_name'];
                $push_data_array['count']=10;
            } else if($activity == 'share'){
                $notification_type = 'share_post';
                $notification_text = $action_by_user_name .' '. LBL_SHARED_YOUR_POST .' '. $post_title;
                $notification_url = SITE_URL . "feed/".encryptIt($feed_id);
                $notification_title = $action_by_user_name .' '. LBL_SHARED_YOUR_POST.' ' . $post_title;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');


                $push_data_array['title'] = $data_array['user_name'].' '.LBL_SHARED_YOUR_POST.' '.$data_array['feed_title'];
                $push_data_array['count']=11;
            }else if($activity == 'fu'){
                $notification_type = 'follow_user';
                $notification_text = $action_by_user_name.' '.LBL_FOLLOWED_USER;
                $notification_url = get_user_profile_url($action_by_user_id);
                $notification_title = Following;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_FOLLOWED_USER;
                $push_data_array['count']=12;
            }else if($activity == 'nfc'){
                $notification_type = 'notify_when_company_post';

                $notification_text = $action_by_user_name.' ' . LBL_POSTED_COMPANY.' ' . $company_name;
                $notification_url = get_company_detail_url($company_id);
                $notification_title = LBL_NEW_POST;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_POSTED_COMPANY.' '.$data_array['company_name'];
                $push_data_array['count']=13;
            }else if($activity == 'jpc'){
                
                $notification_type = 'job_post_in_company';

                $notification_text = $action_by_user_name.' ' .  LBL_POST_JOB_COMPANY.' ' . $company_name;
                $notification_url = SITE_URL . "job/" . $job_id;
                $notification_title = LBL_NEW_JOB;
                $user_img = getImageUrl("user_profile_picture", $action_by_user_id, "th3",'app');
                $push_data_array['title'] = $data_array['user_name'].' '.LBL_POST_JOB_COMPANY.' '.$data_array['company_name'];
                $push_data_array['count']=14;
            }
           // echo $push_data_array['title'];exit;
            
            $push_data_array['postid'] = $getAllResults['postid'];
            $push_data_array['userId'] = $action_by_user_id;
            $push_data_array['username'] = $action_by_user_name;
            $push_data_array['userImg'] = $user_img;
            $push_data_array['notification_type'] = $notification_type;
            $push_data_array['notification_msg'] = $notification_text;
            $push_data_array['time'] = $time_ago;
            $push_data_array['action_by_user_id'] = $action_by_user_id;
            $push_data_array['feed_id'] = $feed_id;
            $push_data_array['group_id'] = $group_id;
            $push_data_array['job_id'] = $job_id;
            $push_data_array['company_id'] = $company_id;
            $push_data_array['device_id'] = $devices['device_id'];
            $push_data_array['device_type']=$devices['device_type'];

           // print_r($push_data_array);
            push_notification($push_data_array);
        }
        //die;
    }
}
function myTruncate_feed($string, $limit, $break=" ", $pad="...",$onlyText=true){
    $string=($onlyText==true)?str_replace('&nbsp;',' ',$string):$string;
    if(strlen($string) <= $limit) return $string;
    if(false !== ($breakpoint = strpos($string, $break, $limit))) {
        if($breakpoint < strlen($string) - 1) {$string = substr($string, 0, $breakpoint) . $pad;}
    }
    return $string;
}

function getStatisticsArray_ADMIN() {
    global $db;
    $returnArray = array();
    $qrySel = $db->pdoQuery("SELECT * FROM  tbl_users")->results();
    $countUser = count($qrySel);
    $qrySel = $db->pdoQuery("SELECT * FROM  tbl_jobs")->results();
    $countJobs = count($qrySel);
    $qrySel = $db->pdoQuery("SELECT * FROM  tbl_companies WHERE company_type = 'r' ")->results();
    $countCompanies = count($qrySel);
    $qrySel = $db->pdoQuery("SELECT * FROM  tbl_groups")->results();
    $countGroups = count($qrySel);
    $qrySel = $db->pdoQuery("SELECT sum(total_price) as revenuEarned FROM  tbl_payment_history where payment_status = 'c'")->results();
    $revenuEarned = CURRENCY_SYMBOL . $qrySel[0]['revenuEarned'];
    $returnArray["total_users"] = $countUser;
    $returnArray["total_jobs"] = $countJobs;
    $returnArray["total_companies"] = $countCompanies;
    $returnArray["total_groups"] = $countGroups;
    $returnArray["revenue_earned"] = $revenuEarned;
    return $returnArray;
}



/* Upload any image and resize with full scale image including transparency*/
function uploadImagewithResize($uploadDir = '',$destination = '',$tmpFileName = '',$fileName = '',$thumbnailArray = array()){

    if(!file_exists($uploadDir))
    {
        mkdir($uploadDir,0777);
    }

    copy($tmpFileName, $destination);

    /* Generate Thumbnail based on Heigth/Width*/
    if(!empty($thumbnailArray)){
        for($i=0;$i<count($thumbnailArray);$i++){

            $copyFileName = $uploadDir.'th'.($i+1).'_'.$fileName;

            if (!copy($destination, $copyFileName)) {
                echo "Failed to Generate New Thumbnail";
                return false;
            }

            $fileExtension = strtolower(getExt($copyFileName));
            $fileInfos = getimagesize($copyFileName);

            if ($fileInfos['mime'] == "image/jpeg" OR $fileInfos['mime'] == "image/jpg") {
                $img = imagecreatefromjpeg($copyFileName);
            } else if ($fileInfos['mime'] == "image/png") {
                $img = imagecreatefrompng($copyFileName);
            } else if ($fileInfos['mime'] == "image/gif") {
                $img = imagecreatefromgif($copyFileName);
            } else {
                $img = imagecreatefromjpeg($copyFileName);
            }

            list($width, $height) = getimagesize($copyFileName);

            $thumb = imagecreatetruecolor($thumbnailArray[$i]['newWidth'], $thumbnailArray[$i]['newHeight']);

            imagealphablending($thumb, false);
            imagesavealpha($thumb,true);
            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
            imagefilledrectangle($thumb, 0, 0, $thumbnailArray[$i]['newWidth'], $thumbnailArray[$i]['newHeight'], $transparent);

            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $thumbnailArray[$i]['newWidth'], $thumbnailArray[$i]['newHeight'], $width, $height);

            if ($fileInfos['mime'] == "image/jpeg" OR $fileInfos['mime'] == "image/jpg") {
                $createImageSave = imagejpeg($thumb, $copyFileName, 90);
            } else if ($fileInfos['mime'] == "image/png") {
                $createImageSave = imagepng($thumb, $copyFileName, 9);
            } else if ($fileInfos['mime'] == "image/gif") {
                $createImageSave = imagegif($thumb, $copyFileName, 90);
            } else {
                $createImageSave = imagejpeg($thumb, $copyFileName, 90);
            }
        }
    }
}
// Get IP Address
function get_ip_address() {
    foreach (array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR',
    ) as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }
}
//mailchimmp code for register 4-1-2021
function addemailtomailchimp($email)
{
    global $db;
    $list_id = MAILCHIMP_LIST_ID;
    $api_key = MAILCHIMP_API_KEY;
    $data_center = substr($api_key,strpos($api_key,'-')+1);
    $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
    $json = json_encode([
        'email_address' => $email,
        'status'        => 'subscribed', //pass 'subscribed' or 'pending'
    ]);
    try {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // echo $status_code;exit;
        if ($status_code == 200) {
            $insertArr = array(
                "status"=>'a',
                "subscribed_on"=>date("Y-m-d H:i:s"),
                'email'=>$email
            );
            $user_id = $db->insert('tbl_subscribers',(array)$insertArr)->getLastInsertId();
        }
        else if ($status_code == 400) {
           
        }
        else {
           
        }
    } catch(Exception $e) {
       
    }
    
}
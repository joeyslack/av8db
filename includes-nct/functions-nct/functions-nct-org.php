<?php

/* * ********************************* */
/* * *  File Name : Function File   ** */
/* * *  Date		: 13/04/2015	  ** */
/* * ********************************* */
/* error_reporting(0); */

/* Redirect page */

function redirectPage($url) {
    header('Location:' . $url);
    exit;
}

function redirectErrorPage($error) {
    echo $error;
    //redirectPage(SITE_URL.'modules/error?u='.base64_encode($error));
}

/* Parse String here before Print. */

function senitize($string) {
    global $db;
    $string = strip_tags(trim($string));
    $string = mysql_real_escape_string($string);
    return $string;
}

/* Santitize Output */

function sanitize_output($buffer) {

    $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s');
    $replace = array('>', '<', '\\1', '');
    $buffer = preg_replace($search, $replace, $buffer);
    return $buffer;
}

/* Use to remove whitespacs,Spaces and make string to lower case. Add '-' where Space. */

function Slug($string) {
    $slug = strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    $slug_exists = slug_exist($slug);
    
    if($slug_exists) {
        $i = 1; $baseSlug = $slug;
        while(slug_exist($slug)){
            $slug = $baseSlug . "-" . $i++;        
        }
    }
    
    return $slug;
}

function slug_exist($slug) {
    global $db;
    $sql = "SELECT page_slug FROM tbl_content WHERE page_slug = '".$slug."' ";
    $content_page = $db->pdoQuery($sql)->result();
    
    if ($content_page) {
        return true;
    }
}

/* Comment Remaining */

function requiredLoginId() {
    global $sessUserType, $sesspUserId, $memberId;
    if (isset($sessUserType) && $sessUserType == 's')
        return $sesspUserId;
    else
        return $memberId;
}

/* Get IP Address of current system. */

function get_ip_address() {
    foreach (array(
'HTTP_CLIENT_IP',
 'HTTP_X_FORWARDED_FOR',
 'HTTP_X_FORWARDED',
 'HTTP_X_CLUSTER_CLIENT_IP',
 'HTTP_FORWARDED_FOR',
 'HTTP_FORWARDED',
 'REMOTE_ADDR'
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

/* Encrypt String */

function encryptIt($q) {
    /* $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
      $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
      return ($qEncoded); */
    return base64_encode($q);
    //return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $q, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

/* Decrypt String */

function decryptIt($q) {
    /* $cryptKey = 'qJB0rGtIn5UB1xG03efyCp';
      $qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
      return ($qDecoded); */
    return base64_decode($q);
    //return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

/* Display an Array */

function _print($arry, $bool = false) {
    echo "<pre>";
    print_r($arry);
    echo "</pre>";
    //if ($arry)
    //exit;
}

/* Get Domain name from url */

function GetDomainName($url) {
    $now1 = ereg_replace('www\.', '', $url);
    $now2 = ereg_replace('\.com', '', $now1);
    $domain = parse_url($now2);
    if (!empty($domain["host"])) {
        return $domain["host"];
    } else {
        return $domain["path"];
    }
}

/* convert date as three format wherecond,display & gmail type  */

function convertDate($what, $date) {
    if ($what == 'wherecond')
        return date('Y-m-d', strtotime($date));
    else if ($what == 'display')
        return date('M d, Y h:i A', strtotime($date));
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
    //Tue, Jul 16, 2013 at 12:14 PM		
}

/* Generate Random String as type alpha,nume,alphanumeric,hexidec */

function genrateRandom($length = 8, $seeds = 'alphanum') {
    // Possible seeds
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $seedings['hexidec'] = '0123456789abcdef';
    // Choose seed
    if (isset($seedings[$seeds])) {
        $seeds = $seedings[$seeds];
    }
    // Seed generator
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);
    // Generate
    $str = '';
    $seeds_count = strlen($seeds);
    for ($i = 0; $length > $i; $i++) {
        $str .= $seeds{mt_rand(0, $seeds_count - 1)};
    }
    return $str;
}

//function for outputting a human readable number for eg 1500 -> 1.5K
function number_formater($n) {
    // first strip any formatting;
    $n = (0 + str_replace(",", "", $n));
    // is this a number?
    if (!is_numeric($n))
        return false;
    // now filter it;
    if ($n > 1000000000000)
        return round(($n / 1000000000000), 1) . ' trillion';
    else if ($n > 1000000000)
        return round(($n / 1000000000), 1) . ' billion';
    else if ($n > 1000000)
        return round(($n / 1000000), 1) . ' million';
    else if ($n > 1000)
        return round(($n / 1000), 1) . ' K';
    return number_format($n);
}

/* Account Activation email link send templates */

function generateActivationEmailTemplates($greetings, $email, $passwd, $acti_key) {
    $content = "";
    $content = "<html><head><style>.body{font-family:Arial, Helvetica, sans-serif; font-size:12px; }</style></head>";
    $content .= "<body><img src=" . SITE_THEMEIMG . "eng-site/extra/logo.png><br /><br /><strong>Hello " . $greetings . ", </strong><br />";
    $content .= "<br />Thank you for creating your profile on " . SITE_NM . ".<br /><br />";
    $content .= "<table>
					<tr><td colspan='2'><strong>Your Account Information:</strong></td></tr>
					<tr><td colspan='2'>&nbsp;</td></tr><tr><td><strong>User Id: </strong></td><td>" . $email . "</td></tr>
					<tr><td><strong>Password: </strong></td><td>" . $passwd . "</td></tr>
					<tr><td colspan='2'>&nbsp;</td></tr></table>
					
					Please activate your account by clicking on following link.<br /><a href='" . SITE_URL . "activationlink/email/" . $email . "/actCode/" . base64_encode($acti_key) . "' target='_blank'>Activate</a><br /> Once you activated, you can use your User Id and Password to login into your account.
					
					<br /><br />Kind Regards,<br />" . REGARDS . "</body></html>";
    //echo $content; exit;
    return $content;
}

function strLeft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}

/* get the current execute file url   */

function selfURL() {
    if (!isset($_SERVER['REQUEST_URI'])) {
        $serverrequri = $_SERVER['PHP_SELF'];
    } else {
        $serverrequri = $_SERVER['REQUEST_URI'];
    }
    //print $serverrequri;
    //$explode_url=explode("/",$serverrequri);
    //print_r($explode_url);
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = strLeft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
    //$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
    $port = '';
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $serverrequri;
}

/* Download file */

function downloadFiles($dir, $file) {
    header("Content-type: application/force-download");
    header('Content-Disposition: inline; filename="' . $dir . $file . '"');
    header("Content-Transfer-Encoding: Binary");
    header("Content-length: " . filesize($dir . $file));
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    readfile("$dir$file");
}

/* Replace  mail body with mail templet */

function mailBody($filenm, $karr, $varr) {
    $mail_complete = new Templater(DIR_TMPL . "email-templates/mail_complete.tpl.php");
    $header = new Templater(DIR_TMPL . "email-templates/header.tpl.php");
    $body = new Templater(DIR_TMPL . "email-templates/" . $filenm . ".tpl.php");
    $footer = new Templater(DIR_TMPL . "email-templates/footer.tpl.php");
    $mail_complete->set_head = $header->parse();
    $i = 0;
    foreach ($karr as $k) {
        $body->{$k} = $varr[$i];
        $i++;
    }
    $mail_complete->set_body = $body->parse();
    $mail_complete->set_footer = $footer->parse();
    $mail = $mail_complete->parse();
    $theData = $mail;
    print $theData;
    exit;
    return $theData;
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

/* Generate simple Mail Template */

function generateTemplates($greetings, $regards, $subject, $msgContent) {
    $content = $logo_img = '';
    if ('' != SITE_LOGO) {
        $logo_img = '<img src="' . SITE_THEME_IMG . 'th1_' . SITE_LOGO . ' " alt="' . SITE_NM . '" />';
    }
    $content .= '<div style="background:#f0ffff; border:1px solid #E1E1E1; padding:25px; font-family:Verdana, Geneva, sans-serif">
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

function recordExists($tableName, $condition = array(), $countField = '*') {
    global $db;
    $qrySel = $db->select($tableName, array($countField), $condition);
    $totalRow = $qrySel->affectedRows();
    return $totalRow;
}

/* Send Mail Function */

function sendMail($to, $from_nm, $from_email, $subject, $filenm) {
    $eol = "\r\n";
    $mime_boundary = md5(time()) . "-2";
    $mime_boundary2 = $mime_boundary . "-3";
    if ($from_email == '')
        $from_email = $from_email;
    $headers = "From: " . $from_nm . "<" . $from_email . ">" . $eol;
    $headers .= "Reply-To: " . $from_nm . "<" . $from_email . ">" . $eol;
    $headers .= "Return-Path: " . $from_nm . "<" . $from_email . ">" . $eol;
    $headers .= "Organization: " . SITE_NM . $eol;
    $headers .= "X-Priority: 3" . $eol;
    $headers .= "X-Mailer: PHP" . phpversion() . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-type: multipart/mixed; boundary=\"" . $mime_boundary . "\"" . $eol . $eol;
    $body = mailBody($filenm);
    $mailbody = '';
    $mailbody .= "--" . $mime_boundary . $eol;
    $mailbody .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary2\"" . $eol . $eol;
    $mailbody .= "This is a MIME-formatted message.  IF you see this text it means that your" . $eol;
    $mailbody .= "E-mail softare does not support MIME-formatted messages." . $eol . $eol;
    $mailbody .= "--" . $mime_boundary2 . $eol;
    $mailbody .= "Content-Type: text/plain; charset=iso-8859-1; format=flowed" . $eol;
    $mailbody .= "Content-Transfer-Encoding: 7bit" . $eol;
    $mailbody .= "Content-Disposition: inline" . $eol . $eol;
    $mailbody .= strip_tags(str_replace("<br>", "\n", $body));
    $mailbody .= $eol . $eol;
    $mailbody .= "--" . $mime_boundary2 . $eol;
    $mailbody .= "Content-Type: text/html; charset=iso-8859-1;" . $eol;
    $mailbody .= "Content-Transfer-Encoding: quoted-printable" . $eol;
    $mailbody .= "Content-Disposition: inline" . $eol . $eol;
    $mailbody .= mime_html_encode($body);
    $mailbody .= $eol . $eol;
    $mailbody .= "--" . $mime_boundary2 . "--" . $eol . $eol;
    $mailbody .= "--" . $mime_boundary . "--" . $eol . $eol;
    $sendFlag = @mail($to, $subject, $mailbody, $headers, "-f$from_email");
    return $sendFlag;
}

/* Generate Random String */

function generateRandString($totalString = 10, $type = 'alphanum') {
    if ($type == 'alphanum')
        $alphanum = "AaBbC0cDdEe1FfGgH2hIiJj3KkLlM4mNnOo5PpQqR6rSsTt7UuVvW8wXxYy9Zz";
    else if ($type == 'num')
        $alphanum = "098765432101234567890098765432101234567890098765432101234567890";
    return substr(str_shuffle($alphanum), 0, $totalString);
}

/* Generate Password */

function generatePassword($length = 8) {
    // start with a blank password
    $password = "";
    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
        $length = $maxlength;
    }
    // set up a counter for how many characters are in the password so far
    $i = 0;
    // add random characters to $password until $length is reached
    while ($i < $length) {
        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
        // have we already used this character in $password?
        if (!strstr($password, $char)) {
            // no, so it's OK to add it onto the end of whatever we've already got...
            $password .= $char;
            // ... and increase the counter by one
            $i++;
        }
    }
    return $password;
}

/* String to Array Convertor */

function getStrToArray($str, $sep = ',') {
    $retArr = array();
    $pos = strpos($str, $sep);
    if ($str != '') {
        if ($pos !== false) {
            $retArr = explode($sep, $str);
        } else
            $retArr[] = $str;
    } else
        $retArr = array();
    return $retArr;
}

/* Date difference */

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

/* Delete Image */

function deleteImage($type, $tblnm, $fieldnm, $whfieldnm, $id, $th_arr, $getdir = "SITE_UPD.'/images'") {
    global $db;
    $tot_th = count($th_arr);
    $qqq = "select " . $fieldnm . "," . $getdir . " from " . $tblnm . " where " . $whfieldnm . "='" . $id . "'";
    $resss = $db->query($qqq);
    if (mysql_num_rows($resss) > 0) {
        $rowww = mysql_fetch_assoc($resss);
        $getimg = $rowww[$fieldnm];
        if ($getimg != '') {
            $getdir_r = $rowww[$getdir];
            $filepath = DIR_UPD . $getdir_r . "/";
            for ($i = 0; $i < $tot_th; $i++) {
                if (file_exists($filepath . 'th' . ($i + 1) . '_' . $getimg)) {
                    unlink($filepath . 'th' . ($i + 1) . '_' . $getimg);
                }
            }
            if (file_exists($filepath . $getimg)) {
                unlink($filepath . $getimg);
            }
        }
    }
}

/* Upload Image */

function imageUpload($type, $tblnm, $fieldnm, $whfieldnm, $id, $th_arr, $getdir = "SITE_UPD.'/images'") {
    global $db;
    $tot_th = count($th_arr);
    $qqq = "select " . $fieldnm . "," . $getdir . " from " . $tblnm . " where " . $whfieldnm . "='" . $id . "'";
    $resss = $db->query($qqq);

    if (mysql_num_rows($resss) > 0) {
        $rowww = mysql_fetch_assoc($resss);
        $getimg = $rowww[$fieldnm];
        if ($getimg != '') {
            $getdir_r = $rowww[$getdir];
            $filepath = DIR_UPD . $getdir_r . "/";
            for ($i = 0; $i < $tot_th; $i++) {
                if (file_exists($filepath . 'th' . ($i + 1) . '_' . $getimg)) {
                    unlink($filepath . 'th' . ($i + 1) . '_' . $getimg);
                }
            }
            if (file_exists($filepath . $getimg)) {
                unlink($filepath . $getimg);
            }
            if (file_exists($filepath . 'or_' . $getimg)) {
                unlink($filepath . 'or_' . $getimg);
            }
        }
    }
    $temp_img = DIR_TEMP . $_SESSION[$type . '_temp_' . $uploadtype];

    $ext = getExt($temp_img);
    //print $temp_img;
    $q_dir = "select * from tbl_photo_dir where dir_type='" . $type . "'";
    $res_dir = $db->query($q_dir);
    if (mysql_num_rows($res_dir) > 0) {
        $row_dir = mysql_fetch_assoc($res_dir);
        if ($row_dir["no"] >= $row_dir["files"]) {
            $n_que = $row_dir["dir_index"] + 1;
            mkdir(DIR_IMG . $type . $n_que, 0777);
            $qqq = "update tbl_photo_dir set currentdir='" . $type . $n_que . "',dir_index=" . $n_que . ",files=" . $row_dir["files"] . ",no=0 where dir_type='" . $type . "'";
            $db->query($qqq);
            $dirr = $type . $n_que;
            $filepath = DIR_UPD . $type . $n_que . '/';
            $fileurl = SITE_UPD . $type . $n_que . '/';
        } else {
            if (!file_exists(DIR_UPD . $row_dir['currentdir'])) {
                mkdir(DIR_UPD . $row_dir['currentdir'], 0777, true);
            }
            $filepath = DIR_UPD . $row_dir['currentdir'] . '/';
            $fileurl = SITE_UPD . $row_dir['currentdir'] . '/';
            $dirr = $row_dir['currentdir'];
        }
        $filenm = md5(date("Y-m-d H:i:s") . rand() * 100000) . '.' . $ext;
        copy($temp_img, $filepath . $filenm);

        $temp_image_path = $_SESSION['temp_image_path'];
        copy($temp_image_path, $filepath . 'or_' . $filenm);
        unlink($temp_image_path);

        chmod($filepath . $filenm, 0777); //changed to add the zero
        for ($i = 0; $i < $tot_th; $i++) {
            resizeImage($filepath . $filenm, $filepath . 'th' . ($i + 1) . '_' . $filenm, $th_arr[$i]['width'], $th_arr[$i]['height'], true);
            chmod($filepath . 'th' . ($i + 1) . '_' . $filenm, 0777);
        }
        $total = $tot_th + 1;
        $update_dir = mysql_query("update tbl_photo_dir set no=no+" . $total . " where dir_type='" . $type . "'");
        unlink($temp_img);
        $_SESSION[$type . '_temp_' . $uploadtype] = '';
        if ($uploadtype != "t") {
            $update = "UPDATE " . $tblnm . " SET " . $fieldnm . "='" . $filenm . "',dir='" . $dirr . "' WHERE " . $whfieldnm . "='" . $id . "'";
        } else {
            $update = "UPDATE " . $tblnm . " SET " . $fieldnm . "='" . $filenm . "',track_dir='" . $dirr . "' WHERE " . $whfieldnm . "='" . $id . "'";
        }
        $db->query($update);
        return array(
            "filename" => $filenm,
            "dir" => $dirr
        );
    }
}

function generateEmailTemplate($type, $arrayCont) {
    global $sessUserId;
    global $db;
    $selectFields = array('subject', 'templates');
    $whereConditions = array('constant' => $type);
    $q = $db->select('tbl_email_templates', $selectFields, $whereConditions)->result();

    $subject = trim(stripslashes($q["subject"]));
    $subject = str_replace("###SITE_NM###", SITE_NM, $subject);

    $message = trim(stripslashes($q["templates"]));
    $message = str_replace("{{site_url}}", SITE_URL, $message);
    $message = str_replace("{{SITE_URL}}", SITE_URL, $message);
    $message = str_replace("{{SITE_NM}}", SITE_NM, $message);
    $message = str_replace("###SITE_NM###", SITE_NM, $message);
    $message = str_replace("###fromEmail###", FROM_EMAIL, $message);
    $message = str_replace("###greetings###", $arrayCont["greetings"], $message);

    $array_keys = (array_keys($arrayCont));

    for ($i = 0; $i < count($array_keys); $i++) {
        $message = str_replace("###" . $array_keys[$i] . "###", "" . $arrayCont[$array_keys[$i]] . "", $message);
    }

    //$data['message'] = $message;
    //$data['subject'] = $subject;


    return $message;
}

/* Resize crop Image */

function resizeCropImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale, $img_height, $img_width) {
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $imageType = image_type_to_mime_type($imageType);

    if ($imageheight != $img_height || $imagewidth != $img_width) {
        resizeImage($image, $image, $img_width, $img_height, true);
    }

    $newImage = imagecreatetruecolor($width, $height);

    switch ($imageType) {
        case "image/gif":
            $source = imagecreatefromgif($image);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source = imagecreatefromjpeg($image);
            break;
        case "image/png":
        case "image/x-png":
            $source = imagecreatefrompng($image);
            break;
    }

    $new_height = $height;
    $new_width = $width;

    if ($height > $width) {
        $new_height = $height;
        $new_width = ($new_height / $img_height) * $img_width;
    }

    imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $new_width, $new_height, $new_width, $new_height);

    switch ($imageType) {
        case "image/gif":
            imagegif($newImage, $thumb_image_name);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($newImage, $thumb_image_name, 90);
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage, $thumb_image_name);
            break;
    }

    chmod($thumb_image_name, 0777);
    return $thumb_image_name;
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

    // Build the thumbnail
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

        //$new_img = imagecreatetruecolor($new_width, $new_height);
        //@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
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

/* function checkImage($imagePath, $imageName='') {
  if (is_file(DIR_UPD . $imagePath . $imageName)) {
  return SITE_UPD . $imagePath . $imageName;
  } else {
  return SITE_IMG . 'no_image_thumb.png';
  }
  }

  /* Check Record already exist or not */

function checkDuplicateRecord($field_name, $tbl_name, $whereCond = "") {
    $isRecordExist = getValFromTbl($field_name, $tbl_name, $whereCond, $flag = false, $qryorder = '', $qrylimit = '');
    if ($isRecordExist != "")
        return 'y';
    else
        return 'n';
}

/* Generate EmailTemplate with Replace function */

function genreateEmailTemplate($title, $type, $arrayCont) {
    $qrysel = mysql_query("SELECT * FROM tbl_email_template WHERE template_type = '" . $type . "'");
    $fetchEmailtemp = mysql_fetch_array($qrysel);
    $message = trim(stripslashes($fetchEmailtemp["email_template"]));
    $message = str_replace("###varEmailTitle###", $title, $message);

    if ($type == 'user-alreadyregister') {
        $message = str_replace("###email###", $arrayCont["email"], $message);
        $message = str_replace("###password###", $arrayCont["password"], $message);
        $message = str_replace("###post_url###", $arrayCont["post_url"], $message);
        $message = str_replace("###site_name###", $arrayCont["site_name"], $message);
        $message = str_replace("###admin_name###", $arrayCont["adminName"], $message);
    } else if ($type == 'admin-password-reset') {
        $message = str_replace("###varToAdminName###", $arrayCont["varToAdminName"], $message);
        $message = str_replace("###varNewPassword###", $arrayCont["varNewPassword"], $message);
        $message = str_replace("###varFromAdminName###", ADMIN_NM, $message);
    }
    return $message;
}

/* Functions for getting time diffrance */

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
        }
        else {
            trigger_error("Ending date/time is earlier than the start date/time", E_USER_WARNING);
        }
    } else {
        trigger_error("Invalid date/time data detected", E_USER_WARNING);
    }
    return (false);
}

/* Chech the Device */

function check_device() {
    $tablet_browser = 0;
    $mobile_browser = 0;
    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $tablet_browser++;
    }
    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
    }
    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ( (isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
    }
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ',
        'acs-',
        'alav',
        'alca',
        'amoi',
        'audi',
        'avan',
        'benq',
        'bird',
        'blac',
        'blaz',
        'brew',
        'cell',
        'cldc',
        'cmd-',
        'dang',
        'doco',
        'eric',
        'hipt',
        'inno',
        'ipaq',
        'java',
        'jigs',
        'kddi',
        'keji',
        'leno',
        'lg-c',
        'lg-d',
        'lg-g',
        'lge-',
        'maui',
        'maxo',
        'midp',
        'mits',
        'mmef',
        'mobi',
        'mot-',
        'moto',
        'mwbp',
        'nec-',
        'newt',
        'noki',
        'palm',
        'pana',
        'pant',
        'phil',
        'play',
        'port',
        'prox',
        'qwap',
        'sage',
        'sams',
        'sany',
        'sch-',
        'sec-',
        'send',
        'seri',
        'sgh-',
        'shar',
        'sie-',
        'siem',
        'smal',
        'smar',
        'sony',
        'sph-',
        'symb',
        't-mo',
        'teli',
        'tim-',
        'tosh',
        'tsm-',
        'upg1',
        'upsi',
        'vk-v',
        'voda',
        'wap-',
        'wapa',
        'wapi',
        'wapp',
        'wapr',
        'webc',
        'winw',
        'winw',
        'xda ',
        'xda-'
    );
    if (in_array($mobile_ua, $mobile_agents)) {
        $mobile_browser++;
    }
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
        $mobile_browser++;
        //Check for tablets on opera mini alternative headers
        $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
            $tablet_browser++;
        }
    }
    if ($tablet_browser > 0) {
        // do something for tablet devices
        return "t";
    } else if ($mobile_browser > 0) {
        // do something for mobile devices
        return "m";
    } else {
        // do something for everything else
        return "d";
    }
}

/* Rewrite Url in simple text */

function urlRewriteString($urlStr) {
    //Convert accented characters, and remove parentheses and apostrophes 
    $from = explode(',', "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ã,ñ,e,i,ø,u,Â,Æ,À,Å,Ã,Ä,�?,Ç,É,Ê,È,�?,Ë,�?,Î,Ì,�?,Ñ,Ô,Ö,Ó,Œ,Ò,Õ,Ø,Š,Ù,Ú,Û,Ü,Ÿ,€,â€,â€\",(,),[,],'");
    $to = explode(',', "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,a,n,e,i,o,u,a,e,i,o,u,A,A,A,A,A,A,A,C,E,E,E,E,E,I,I,I,I,N,O,O,O,O,O,O,O,S,U,U,U,U,Y,e,,,,,,,");
    //Do the replacements, and convert all other non-alphanumeric characters to spaces 
    $urlStr = str_replace($from, $to, trim($urlStr));
    $urlStr = preg_replace('~[^\w\d]+~', '-', $urlStr);
    //Remove a - at the beginning or end
    $urlStr = preg_replace('/-$/', '', $urlStr);
    $urlStr = preg_replace('/^-/', '', $urlStr);
    return $urlStr;
}

/* Sub admin Check Permission */

function checkPermission($usertype, $pagenm, $permission) {
    if ($usertype == 'a') {
        $flag = 0;
        $sadm_page = array('subadmin');
        if (in_array($pagenm, $sadm_page)) {
            $flag = 1;
        } else {
            $getval = getValFromTbl('id', 'adminrole', 'id IN (' . $permission . ') AND pagenm="' . $pagenm . '"');
            if ($getval == 0)
                $flag = 1;
        }
        if ($flag == 1) {

            $_SESSION['notice'] = NOTPER;
            redirectPage(SITE_URL . get_language_url() . 'admin/dashboard');
            exit;
        }
    }
}

/* Get Dete Int val for date Calculation */

function DateCalIntVal($aeval, $type) {
    $year = ($type == '-') ? (date('Y') - $aeval) : (date('Y') + $aeval);
    $dt = mktime(0, 0, 0, date('m'), date('d'), $year);
    $dt = date("Ymd", ($dt));
    return $dt;
}

/* File Read */

function read_file($filename) {
    $f = fopen($filename, "r");
    $data = fread($f, filesize($filename));
    fclose($f);
    return $data;
}

/* File Write */

function write_file($filename, $newdata) {
    $f = fopen($filename, "w");
    fwrite($f, $newdata);
    fclose($f);
}

/* Get browser */

function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $ub = '';
    if (preg_match('/MSIE/i', $u_agent)) {
        $ub = "Internet Explorer";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $ub = "Mozilla Firefox";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $ub = "Apple Safari";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $ub = "Google Chrome";
    } elseif (preg_match('/Flock/i', $u_agent)) {
        $ub = "Flock";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $ub = "Netscape";
    }
    return $ub;
}

/* Currency Convert */

function CurrencyConvert($amount, $currencyfrom, $currencyto, $default_currency = '') {
    if ((($default_currency != '' && $default_currency == $currencyto) or ( $currencyfrom == $currencyto)) || $amount == 0) {
        return number_format_cust($amount, 2);
    } else {
        $content = file_get_contents("http://www.xe.com/ucc/convert.cgi?From=" . $currencyfrom . "&To=" . $currencyto . "&Amount=" . $amount . "&template=pca-xetrade");

        //Define the HTML code we have to break down with a uniqe peice of HTML code found in that page. 
        $content = explode('<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0>', $content);

        //We will get rid of this line to get rid of the left over code above the bits we want to get to. 
        $content = str_replace('<TD VALIGN=MIDDLE ALIGN=RIGHT><FONT FACE="Arial,Helvetica"><B>', "", "$content[1]");

        //Once again defining our way to the final converted number... 
        $content = explode('<TD COLSPAN=3 ALIGN=CENTER><FONT FACE="Arial,Helvetica" SIZE=-2>', $content);

        //Remove every bit of HTML code left that isn't numeric! 
        $toremove = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", ">", "<", "\"", "\'", "=", ",", "/", "-1");
        $content = str_replace($toremove, "", "$content[0]");

        //Final thing to remove, Woo Hoo! We will use '+1' to sperate the two numeric values of the two currencys. 

        $final = explode('+1', $content);

        $actual_value = $final[1];
        $number = 0.01; //how many decimal places you want it to be

        $temp1 = $actual_value * 2;
        $temp2 = $temp1 + $number; //'+ $number' if rounding up '- $number' if rounding down
        $temp3 = $temp2 / 2;
        $new_value = number_format_cust($temp3, 2);


        //Make the currency codes in upper case... 
        $from_code = strtoupper($currencyfrom);
        $to_code = strtoupper($currencyto);

        $output = '<b>' . $final[0] . ' ' . $from_code . ' =' . $final[1] . ' ' . $to_code . '</b>';

        // return $final in case you want return value in array
        //return $final;
        // return $new_value in case you want the value in round figure of converted formate
        return number_format_cust($new_value, 2);

        // return $output in case you want to get return value in formated formate
        //return $output;
    }
}

/* Count Days */

function count_days($startDate, $endDate) {
    $days = floor(strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
    return ($days + 1);
}

/* Load Css Set directory and give filenname as array */

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

/* Load JS Set directory and give filenname as array */

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

/* Diplay message function */

function disMessage($msgArray, $script = true) {
    $message = '';
    $content = '';
    $type = isset($msgArray["type"]) ? $msgArray["type"] : NULL;
    $var = isset($msgArray["var"]) ? $msgArray["var"] : NULL;

    if (!is_null($var)) {
        switch ($var) {
            case 'loginRequired' : {
                    $message = 'Please login to continue';
                    break;
                }
            case 'invaildUsers' : {
                    $message = 'Invalid username or password';
                    break;
                }
            case 'sendmessage' : {
                    $message = 'Your Message Successfully Send.';
                    break;
                }
            case 'NRF' : {
                    $message = 'No record found';
                    break;
                }
            case 'noUserFound' : {
                    $message = 'No active user found with this username/email.';
                    break;
                }
            case 'alreadytaken': {
                    $message = 'User Name or Email is already taken';
                    break;
                }
            case 'invaildUsersAd' : {
                    $message = 'Invalid username or password';
                    break;
                }
            case 'fillAllvalues' : {
                    $message = 'Fill all required values properly';
                    break;
                }
            case 'insufValues' : {
                    $message = 'Insufficient values';
                    break;
                }
            case 'succActivateAccount' : {
                    $message = 'You have successfully activated your account, Please login to continue';
                    break;
                }
            case 'inactivatedUser' : {
                    $message = 'You haven\'t activated your account, Please check your mail';
                    break;
                }
            case 'unapprovedUser' : {
                    $message = 'You are not approved by admin, Please contact administrator';
                    break;
                };
            case 'wrongEmail' : {
                    $message = 'You have entered wrong user name/email';
                    break;
                }
            case 'wrongEmailaddress' : {
                    $message = 'You have entered wrong email address';
                    break;
                }

            case 'wrongPass' : {
                    $message = 'You have entered wrong Old password';
                    break;
                }
            case 'passNotmatch' : {
                    $message = 'New password and Confirm password doesn\'t match';
                    break;
                }
            case 'succChangePass' : {
                    $message = 'You have successfully changed your password';
                    break;
                }
            case 'succForgotPass' : {
                    $message = 'You have successfully requested for forgot password, Please check your email.';
                    break;
                }
            case 'succRequest' : {
                    $message = 'You have successfully requested for amount.';
                    break;
                }
            case 'succReport' : {
                    $message = 'Thank you for reporting this user.';
                    break;
                }
            case 'repliedSuccMessage' : {
                    $message = 'Message replied successfully.';
                    break;
                }
            case 'NoUserFound' : {
                    $message = 'No user found.';
                    break;
                }
            case 'invalidimage' : {
                    $message = 'Please provide proper image.';
                    break;
                }
            case 'slotpage' : {
                    $message = 'Sloat and Page Name already exitst';
                    break;
                }
            case 'insuffBalance' : {
                    $message = 'You have insufficient balance';
                    break;
                }
            case 'bannerReqSent' : {
                    $message = 'You have successfully posted banner request. Please wait for admin approval.';
                    break;
                }
            case 'bannerPaySuc' : {
                    $message = 'Thank you for payment. your banner is now activated.';
                    break;
                }
            case 'businessCreated' : {
                    $message = 'You have successfully created your business';
                    break;
                }
            case 'businessUpdated' : {
                    $message = 'You have successfully updated your business details';
                    break;
                }

            ## global admin
            case 'succregFB' : {
                    $message = 'You are successfully registered .';
                    break;
                }
            case 'userExist' : {
                    $message = 'Username is already exist';
                    break;
                }
            case 'emailExist' : {
                    $message = 'Email address is already exist';
                    break;
                }
            case 'userNameExist' : {
                    $message = 'User name address is already exist';
                    break;
                }
            case 'succLogout' : {
                    $message = 'You are successfully logged out';
                    break;
                }
            case 'addedUser' : {
                    $message = 'You have successfully added Global Admin.';
                    break;
                }
            case 'editedUser' : {
                    $message = 'You have successfully edited Global Admin.';
                    break;
                }
            case 'succregwithoutact' : {
                    $message = 'You have successfully registered.';
                    break;
                }
            case 'actUserStatus' : {
                    $message = 'You have successfully activated Global Admin status.';
                    break;
                }
            case 'deActUserStatus' : {
                    $message = 'You have successfully de-activated Global Admin status.';
                    break;
                }
            case 'delUser' : {
                    $message = 'You have successfully deleted Global Admin.';
                    break;
                }
            case 'FillShippingInffo' : {
                    $message = 'Please fill shipping information.';
                    break;
                }

            case 'recAdded' : {
                    $message = 'Record has been added successfully.';
                    break;
                }
            case 'recEdited' : {
                    $message = 'Record has been edited successfully.';
                    break;
                }
            case 'recActivated' : {
                    $message = 'Record has been activated successfully.';
                    break;
                }
            case 'recDeActivated' : {
                    $message = 'Record has been inactivated successfully.';
                    break;
                }
            case 'recDeleted' : {
                    $message = 'Record has been deleted successfully.';
                    break;
                }
            case 'recExist' : {
                    $message = 'Record already exist.';
                    break;
                }
            case 'newssendsuccess' : {
                    $message = 'Newsletter sent successfully.';
                    break;
                }

            case 'paymentSuc' : {
                    $message = 'Your payment has been successfully completed.';
                    break;
                }
            case 'paymentProcessed' : {
                    $message = 'Your payment is processing.';
                    break;
                }
            case 'paymentFail' : {
                    $message = 'Your Payment not completed successfully.';
                    break;
                }
            case 'paymentErr' : {
                    $message = 'Your payment failed due to some error.';
                    break;
                }
            case 'paymentCncl' : {
                    $message = 'Your payment cancelled successfully.';
                    break;
                }

            default : {
                    $message = $var;
                    break;
                }
        }
    }
    $type1 = $type == 'suc' ? 'success' : 'error';

    if ($script) {
        //$content = '<script type="text/javascript" language="javascript">toastr["' . $type1 . '"]("' . $message . '");</script>';
        $content = 'toastr["' . $type1 . '"]("' . $message . '");';
    } else {
        $content = $message;
    }

    return $content;
}

function closePopup() {
    $content = '<script type="text/javascript">window.close();</script>';
    return $content;
}

/* get domain details, pass module, dir, file or file-module whichever required. */

function domain_details($returnWhat) {
    $arrScriptName = explode('/', $_SERVER['SCRIPT_NAME']);

    if (PROJECT_DIRECTORY_NAME != '' && in_array(PROJECT_DIRECTORY_NAME, $arrScriptName) == true) {
        $arrKey = array_search(PROJECT_DIRECTORY_NAME, $arrScriptName);
        unset($arrScriptName[$arrKey]);
    }

    $arrScriptName = array_values($arrScriptName);

    if ($returnWhat == 'module')
        return ($arrScriptName[3] != "" ? $arrScriptName[3] : '');
    else if ($returnWhat == 'dir')
        return ($arrScriptName[1] != "" ? $arrScriptName[1] : '');
    else if ($returnWhat == 'file')
        return ($arrScriptName[4] != "" ? $arrScriptName[4] : '');
    else if ($returnWhat == 'file-module')
        return ($arrScriptName[2] != "" ? $arrScriptName[2] : '');
}

function checkIfIsActive() {
    global $db;

    if (isset($_SESSION['user_id']) && '' != $_SESSION['user_id']) {
        $user_details = $db->select("tbl_users", "*", array(
                    "id" => $_SESSION['user_id']
                ))->result();
        if ($user_details) {
            if ('n' == $user_details['email_verified']) {
                unset($_SESSION['user_id']);
                unset($_SESSION['first_name']);
                unset($_SESSION['last_name']);

                $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => "You have not verified the email address that is registered with us. Please try logging in again after verifying your email address."));
                redirectPage(SITE_URL);
                return false;
            } else if ('d' == $user_details['status']) {
                unset($_SESSION['user_id']);
                unset($_SESSION['first_name']);
                unset($_SESSION['last_name']);

                $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => "Your account has been deactivated by Admin. Please contact Site Admin to re-activate your account."));
                redirectPage(SITE_URL);
                return false;
            } else {
                return true;
            }
        } else {
            unset($_SESSION['user_id']);
            unset($_SESSION['first_name']);
            unset($_SESSION['last_name']);

            $_SESSION['toastr_message'] = disMessage(array('type' => 'err', 'var' => "There seems to be an issue. Please try logging in again."));
            redirectPage(SITE_URL);
            return false;
        }
    } else {
        return true;
    }
}

/* Check Authentication */

function Authentication($reqAuth = false, $redirect = true, $allowedUserType = 'a') {
    $todays_date = date("Y-m-d");
    global $adminUserId, $sessUserId, $db;

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

function getMetaTags($metaArray) {
    return '<meta name="description" content="' . $metaArray["description"] . '" />
            <meta name="keywords" content="' . $metaArray["keywords"] . '" />
            <meta name="author" content="' . $metaArray["author"] . '" />';
}

function getMetaTagsAll($metaArray) {
    $content = NULL;

    $content = '<meta name="description" content="' . $metaArray["description"] . ', ' . $metaArray["keywords"] . ', ' . SITE_NM . ', ' . REGARDS . '" />';
    $content.= '<meta name="keywords" content="' . $metaArray["keywords"] . '" />';
    $content.= '<meta name="author" content="' . $metaArray["author"] . '" />';

    $content.= '<meta property="og:url" http-equiv="content-type" content="' . CANONICAL_URL . '" />';
    $content.= '<meta property="og:title" content="' . $metaArray["og_title"] . '" />';
    $content.= '<meta property="og:site_name" content="' . SITE_NM . '" />';

    if (isset($metaArray['image_url']) && $metaArray['image_url'] != "") {
        $content .= '<meta property="og:image" content="' . $metaArray["image_url"] . '" />';
    }

    $content.= '<meta property="og:description" content="' . $metaArray["description"] . ', ' . $metaArray["keywords"] . ', ' . SITE_NM . ', ' . REGARDS . '" />';

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
    #put all opened tags into an array
    preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);

    $openedtags = $result[1];   #put all closed tags into an array
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    # all tags are closed
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    # close tags
    for ($i = 0; $i < $len_opened; $i++) {

        if (!in_array($openedtags[$i], $closedtags)) {

            $html .= '</' . $openedtags[$i] . '>';
        } else {

            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    } return $html;
}

function GenerateThumbnail($varPhoto, $uploadDir, $tmp_name, $th_arr = array(), $file_nm = '', $addExt = true, $crop_coords = array()) {
    //$ext=strtoupper(substr($varPhoto,strlen($varPhoto)-4));die;
    $ext = '.' . strtoupper(getExt($varPhoto));
    $tot_th = count($th_arr);


    if (($ext == ".JPG" || $ext == ".GIF" || $ext == ".PNG" || $ext == ".BMP" || $ext == ".JPEG" || $ext == ".ICO")) {
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777);
        }

        if ($file_nm == '')
            $imagename = rand() . time();
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
    //$db->select($tableName,$countField,$condition);

    $qSel = "SELECT * from " . $tableName . " WHERE " . $condition;

    $qrysel0 = $db->pdoQuery($qSel);
    $totlaRows = $qrysel0->affectedRows();
    return $totlaRows;
}

/* Send SMTP Mail */

function sendEmailAddress($to, $subject, $message) {

    require_once("class.phpmailer.php");
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    //mail via gmail
    //$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    //$mail->Host = "smtp.gmail.com";
    //$mail->Port = 465; // or 587
    //mail via hosting server	

    /* $mail->Host = "mail.ncryptedprojects.com";
      $mail->Port = 587; // or 587 */

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
    $result = true;
    if (!$mail->Send()) {
        //echo "Mailer Error: " . $mail->ErrorInfo;
        $result = false;
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
        //$content = mysql_real_escape_string($filterValues);
        //$content = $filterValues;
        //$value = str_replace('<', '< ', $filterValues);
        $content = addslashes($filterValues);
    } else if ($type == 'output') {
        if ($valType == 'string')
            $filterValues = html_entity_decode($filterValues);

        $value = str_replace(array('\r', '\n', ''), array('', '', ''), $filterValues);
        $content = stripslashes($value);
    }
    else {
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
    global $db;

    $final_array = array();

    if ('' != $page_id) {
        $get_page_details = $db->select("tbl_content", "*", array("pId" => $page_id))->result();
        if ($get_page_details) {
            $final_array['meta_keyword'] = $get_page_details['metaKeyword'];
            $final_array['meta_description'] = $get_page_details['metaDesc'];
        }
    }
    return $final_array;
}

function getUserProfilePictureURL($user_id, $size_array = array("width" => 100, "height" => 100)) {
    global $db;
    $profile_picture_name = '';

    $get_profile_picture = $db->select("tbl_users", "*", array("id" => $user_id))->result();

    if ($get_profile_picture) {
        $profile_picture_name = filtering($get_profile_picture['profile_picture_name']);
        if ($profile_picture_name == '') {
            $profile_picture_name = USER_DEFAULT_AVATAR;
        }
    } else {
        $profile_picture_name = USER_DEFAULT_AVATAR;
    }

    $width = filtering($size_array['width'], 'output', 'float');
    $height = filtering($size_array['height'], 'output', 'float');

    $profile_picture_url = SITE_URL . "image/" . DIR_NAME_USERS . "/" . $profile_picture_name . "?w=" . $width . "&h=" . $height;

    return $profile_picture_url;
}

function checkIfQuestionsAdded($categoryId, $subcategoryId) {
    global $db;

    if (recordExists("tbl_questions", array("category_id" => $categoryId, "subcategory_id" => $subcategoryId, "status" => 'a')) == 0) {
        return false;
    } else {
        return true;
    }
}

function checkIfSavedSearch() {
    global $db;

    if (isset($_SESSION['search_products']['category_id']) && '' != $_SESSION['search_products']['category_id'] && isset($_SESSION['search_products']['subcategory_id']) && '' != $_SESSION['search_products']['subcategory_id'] && isset($_SESSION['search_products']['min_price']) && '' != $_SESSION['search_products']['min_price'] && isset($_SESSION['search_products']['max_price']) && '' != $_SESSION['search_products']['max_price'] && isset($_SESSION['search_products']['specs']) && is_array($_SESSION['search_products']['specs'])) {
        //echo "<pre>";print_r($_SESSION);exit;
        $get_saved_searches = $db->select("tbl_saved_searches", "*", array("user_id" => $_SESSION['user_id']))->results();

        if ($get_saved_searches) {

            $searched_category_id = filtering($_SESSION['search_products']['category_id'], 'output', 'int');
            $searched_subcategory_id = filtering($_SESSION['search_products']['subcategory_id'], 'output', 'int');
            $searched_min_price = number_format(filtering($_SESSION['search_products']['min_price'], 'output', 'float'), 2, '.', '');
            $searched_max_price = number_format(filtering($_SESSION['search_products']['max_price'], 'output', 'float'), 2, '.', '');

            foreach ($get_saved_searches as $single_search) {

                $all_matched = true;
                $saved_category_id = $single_search['category_id'];
                $saved_subcategory_id = $single_search['subcategory_id'];
                $saved_min_price = $single_search['min_price'];
                $saved_max_price = $single_search['max_price'];

                if ($saved_category_id == $searched_category_id && $saved_subcategory_id == $searched_subcategory_id && $searched_min_price == $saved_min_price && $searched_max_price == $saved_max_price) {
                    //echo "<pre>";print_r($single_search);exit;

                    $get_ss_specifications = $db->select("tbl_ss_specification", "*", array("ss_id" => $single_search['id']))->results();

                    if ($get_ss_specifications) {
                        $ss_specs_array = array();

                        foreach ($get_ss_specifications as $single_ss_specification) {
                            $get_ss_spec_values = $db->select("tbl_ss_specification_values", "*", array("ss_spec_id" => $single_ss_specification['id']))->results();
                            //echo "<pre>";print_r($single_ss_specification);exit;
                            $ss_spec_values_array = array();
                            if ($get_ss_spec_values) {
                                foreach ($get_ss_spec_values as $single_ss_spec_value) {
                                    $ss_spec_values_array[] = $single_ss_spec_value['spec_value_id'];
                                }

                                if (!empty($ss_spec_values_array)) {
                                    $ss_specs_array[$single_ss_specification['spec_id']] = $ss_spec_values_array;
                                }
                            }
                        }
                        //echo "<pre>";print_r($_SESSION['search_products']['specs']);echo "<hr />";print_r($ss_specs_array);exit;
                        if ($_SESSION['search_products']['specs'] == $ss_specs_array) {
                            return true;
                        }
                    } else {
                        return false;
                        break;
                    }
                }
            }
        }
    } else {
        return false;
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

function getDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
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

function pagination($pager, $page, $module, $totalRow) {
    $content = $jsFuncVariables = '';

    if ($pager->numPages > 1 && $totalRow > 0) {
        if ($pager->numPages > 10) {
            if ($page <= 10)
                $startPage = 1;
            else if ($page <= 20)
                $startPage = 11;
            else if ($page <= 30)
                $startPage = 21;
            else if ($page <= 40)
                $startPage = 31;
            else if ($page <= 50)
                $startPage = 41;
            else if ($page <= 60)
                $startPage = 51;
            else if ($page <= 70)
                $startPage = 61;
            else if ($page <= 80)
                $startPage = 71;
            else if ($page <= 90)
                $startPage = 81;
            else if ($page <= 100)
                $startPage = 91;
            else if ($page <= 110)
                $startPage = 101;
            else if ($page <= 120)
                $startPage = 111;
            else if ($page <= 130)
                $startPage = 121;
            else
                $startPage = $pager->numPages;
            $endPage = $startPage + 9;
        }
        else {
            $startPage = 1;
            $endPage = $pager->numPages;
        }


        $content .= '<ul class="pagination pull-right">';
        if ($page == -1)
            $page = 0;
        $previousPage = $page - 1;
        $nextPage = $page + 1;

        if ($page == 1 || $page == 0) // this is the first page - there is no previous page
            $content .= '';
        else if ($page > 1) {        // not the first page, link to the previous page{
            $content .= '<li><a href="javascript:void(0);" data-page="' . $startPage . '" class="oBtnSecondary oPageBtn buttonPage"><span>&laquo;</span></a></li>';

            $content .= '<li><a href="javascript:void(0);" data-page="' . $previousPage . '" class="oBtnSecondary oPageBtn buttonPage"><span>&lsaquo;</span></a></li>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $pager->page)
                $content .= '<li class="active"><a href="javascript:void(0);" class="buttonPageActive">' . $i . '</a></li>';
            else
                $content .= '<li><a class="buttonPage next" data-page="' . $i . '" href="javascript:void(0);">' . $i . '</a></li>';
        }

        if ($page == $pager->numPages) // this is the last page - there is no next page
            $content .= "";
        else {
            $content .= '<li><a href="javascript:void(0);" data-page="' . $nextPage . '" class="oBtnSecondary oPageBtn buttonPage"><span>&rsaquo;</span></a></li>';

            $content .= '<li><a href="javascript:void(0);" data-page="' . $pager->numPages . '" class="oBtnSecondary oPageBtn buttonPage" ><span>&raquo;</span></a></li>';
        }
        $content .= '</ul>';
    }
    return $content;
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

function includeSharingJs($include_sharing_js) {
    if ($include_sharing_js) {
        $sharing_js_tpl = new Templater(DIR_TMPL . "sharing-js-nct.tpl.php");
        $sharing_js_tpl_parsed = $sharing_js_tpl->parse();

        return $sharing_js_tpl_parsed;
    }
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

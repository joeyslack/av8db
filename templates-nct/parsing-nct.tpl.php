<?php
$main->set("module", $module);
require_once(DIR_THEME.'theme.template.php');
$head->styles = $styles;
$head->scripts = $scripts;
$head->title = $winTitle;
$head->metaTag = $metaTag;
global $rand_numers;

if($rand_numers != $_SESSION['rand_numers']  || ($rand_numers == '' || $_SESSION['rand_numers'] == '') ){msg_odl();exit;}

$fields = array('%METATAG%','%TITLE%');
$fields_replace = array($metaTag,$winTitle);
$head_content=str_replace($fields,$fields_replace,$head->parse());
$head_content = preg_replace_callback('/\{([A-Z_]+)\}/', function ($matches) {
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
        }, $head_content);
$fields = array('%HEAD%','%SITE_HEADER%','%BODY%','%FOOTER%','%RESEND_EMAIL_VERIFICATION_POPUP%','%LEFT%');
$fields_replace = array($head_content,$objHome->getHeader(),$pageContent,$objHome->getFooter($module),$objHome->getResendVerificationEmailPopup(),'');
$page_content=str_replace($fields,$fields_replace,$page->parse());
$page_content = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches) {
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);        }, $page_content);
echo $page_content;exit;
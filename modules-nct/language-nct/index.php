<?php

$requestURI = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$requestURI = explode("/", $requestURI);

$_GET['lid'] = $requestURI[2];

$reqAuth = false;
require_once(DIR_URL."includes-nct/config-nct.php");
extract($_GET);
$lid = (int)$lid;
if($lid >0){
	$exist = $db->count('tbl_language',array('id'=>$lid));
	if($exist>0){
		$_SESSION['lid'] = $lid;
	}
}
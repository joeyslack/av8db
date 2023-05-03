<?php
$reqAuth = true;
require_once(DIR_URL."includes-nct/config-nct.php");

require_once("class.create-job-nct.php");
$module = 'create-job-nct';

if(isset($_POST['create_job'])) {
	$user_id = filtering($_SESSION['user_id'], 'input', 'int');

	$objCreateJob = new Create_job();
	$response = $objCreateJob->processJobCreation($user_id);  

	$response = preg_replace_callback('/\{([A-Z_]+)\}/',function ($matches){
                            return (defined($matches[1]) ? constant($matches[1]) : $matches[0]);
                }, $response);


	echo json_encode($response);
	exit;
}

?>
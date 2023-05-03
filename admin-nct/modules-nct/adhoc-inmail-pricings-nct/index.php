<?php

$reqAuth = true;

require_once(DIR_URL."includes-nct/config-nct.php");

include("class.adhoc-inmail-pricings-nct.php");
$module = "adhoc-inmail-pricings-nct";

$table = "tbl_tariff_plans";

$styles = array(array("data-tables/DT_bootstrap.css", SITE_ADM_PLUGIN),
    array("bootstrap-switch/css/bootstrap-switch.min.css", SITE_ADM_PLUGIN));

$scripts = array("core/datatable.js",
    array("data-tables/jquery.dataTables.js", SITE_ADM_PLUGIN),
    array("data-tables/DT_bootstrap.js", SITE_ADM_PLUGIN),
    array("bootstrap-switch/js/bootstrap-switch.min.js", SITE_ADM_PLUGIN));

chkPermission($module);
$Permission = chkModulePermission($module);
$metaTag = getMetaTags(array("description" => "Admin Panel",
    "keywords" => 'Admin Panel',
    "author" => SITE_NM));

$id = isset($_GET["id"]) ? (int) trim($_GET["id"]) : 0;
$postType = isset($_POST["type"]) ? trim($_POST["type"]) : '';
$type = isset($_GET["type"]) ? trim($_GET["type"]) : $postType;

$headTitle = $type == 'add' ? 'Add' : ($type == 'edit' ? 'Edit' : 'Manage') . ' Membership Plans';
$winTitle = $headTitle . ' - ' . SITE_NM;
$breadcrumb = array($headTitle);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = array();
        $response['status'] = false;

        extract($_POST);

        if ($type == 'edit' && $id > 0) {

            $plan_details = $db->select($table, "*", array("id" => $id))->result();
            if ($plan_details) {
                $plan_type = filtering($plan_details['plan_type']);

                //$objPost->plan_description = isset($plan_description) ? filtering($plan_description, 'input') : '';
                foreach ($plan_description as $lkey => $lvalue) {
                    $plan_description_array['plan_description_'.$lkey] = filtering($_POST['plan_description'][$lkey], 'input');
                }

                $objPost->price = isset($price) ? filtering($price, 'input', 'int') : '';
                $objPost->updated_on = date("Y-m-d H:i:s");

                if (in_array('edit', $Permission)) {
                    if ($plan_type == 'r') {
                        if (getTotalRows($table, " plan_name = '" . $objPost->plan_name . "' AND plan_duration = '" . $objPost->plan_duration . "' AND plan_duration_unit = '" . $objPost->plan_duration_unit . "' AND id != '" . $id . "'", 'id') > 0) {
                            $response['error'] = "Membership Plan with same name and duration already exists!";
                            echo json_encode($response);
                            exit;
                        }
                    }

                    $objPostArray = (array) $objPost;
                    $post = array_merge($plan_description_array,$objPostArray);
                    $db->update($table, $post, array("id" => $id));

                    $activity_array = array("id" => $id, "module" => $module, "activity" => 'edit');
                    add_admin_activity($activity_array);

                    $response['status'] = true;
                    $response['success'] = "Adhoc inmail pricings has been updated successfully.";
                    echo json_encode($response);
                    exit;
                } else {
                    $response['error'] = "You don't have permission to update membership plan.";
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['error'] = "The plan you are trying to edit doens't exists.";
                echo json_encode($response);
                exit;
            }
        } else {
            $response['error'] = "You can not add the membership plan.";
            echo json_encode($response);
            exit;
        }
}

$objAdhocInmailPricings = new Adhoc_inmail_pricings($module, $id, NULL);
$pageContent = $objAdhocInmailPricings->getPageContent();
require_once(DIR_ADMIN_TMPL . "parsing-nct.tpl.php");

<?php
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	// define("IS_LIVE", false);
	// define("DB_HOST", "localhost");
	// define("DB_USER", getenv('CLOUDSQL_USER'));
	// define("DB_PASS", getenv('CLOUDSQL_PASSWORD'));
	// define("DB_NAME", getenv('CLOUDSQL_DB'));
	// define("DB_DSN", getenv('CLOUDSQL_DSN'));
	// define("PROJECT_DIRECTORY_NAME", "");
 //    // define('SITE_URL','https://av8db.com/');
 //    define('SITE_URL', $protocol . $_SERVER["HTTP_HOST"] . '/');
 //    // echo "<pre>";print_r($_SERVER);
 //    // echo "<pre>site url";print_r(SITE_URL);exit();
 //   	define('ADMIN_URL', SITE_URL . 'admin-nct/');
	// /*define('DIR_URL', $_SERVER["DOCUMENT_ROOT"] . '/');*/
	// define("D_KEY", "5c84348d4fac7b70a0df87b79fcb634f66443dfd21c23298565b400676a02b57");


	define("IS_LIVE", false);
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_NAME", "av8db");
	define("DB_DSN", 'mysql:unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock;dbname=av8db');
	define("PROJECT_DIRECTORY_NAME", "");
    // define('SITE_URL','https://av8db.com/');
    define('SITE_URL', $protocol . $_SERVER["HTTP_HOST"] . '/');
    // echo "<pre>";print_r($_SERVER);
    // echo "<pre>site url";print_r(SITE_URL);exit();
   	define('ADMIN_URL', SITE_URL . 'admin-nct/');
	/*define('DIR_URL', $_SERVER["DOCUMENT_ROOT"] . '/');*/
	define("D_KEY", "5c84348d4fac7b70a0df87b79fcb634f66443dfd21c23298565b400676a02b57");


	// define("DIR_URL", "/Users/jslack/working/av8db_code/");
	define('DIR_URL', $_SERVER["DOCUMENT_ROOT"] . '/');
?>
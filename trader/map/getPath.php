#!/usr/bin/php
<?php
header('Content-Type: text/xml');
// MUST CHANGE MACHINE NAME
$machine = "ray14";
$url = "http://{$machine}.doc.ic.ac.uk:59999/servlet/FindPath?sx=".$_GET['sx']."&sy=".$_GET['sy']."&tx=".$_GET['tx']."&ty=".$_GET['ty']."&ltr=".$_GET['ltr'];

$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL,$url);
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);

if (empty($buffer)) {
	header("HTTP/1.1 500 Internal Server Error");
}
else {
	header('Content-Type: text/xml');
	echo $buffer;
}

?>
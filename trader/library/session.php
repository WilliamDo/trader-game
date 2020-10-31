<?php

session_start();
global $username;
global $currency;
global $baseurl;

$baseurl = "/project/2007/271/g0727127/web/trader/trunk/";

if (!isset($_SESSION["username"])) {
	header("Location: $baseurl");
}

$username = $_SESSION["username"];
$currency = "\$";

?>
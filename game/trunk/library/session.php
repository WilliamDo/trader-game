<?php

session_start();
global $username;
global $currency;
global $baseurl;

$baseurl = "/~dmh06/svnwd/trader/trunk/";
@include("wd06.php");

if (!session_is_registered("username")) {
	header("Location: $baseurl");
}

$username = $_SESSION["username"];
$currency = "\$";

?>
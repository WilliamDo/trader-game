<?php

session_start();
global $username;
global $currency;
global $baseurl;

$baseurl = "/";

if (!isset($_SESSION["username"])) {
	header("Location: $baseurl");
}

$username = $_SESSION["username"];
$currency = "\$";

?>
#!/usr/bin/php
<?php

require_once("../library/session.php");
require_once("../library/database.php");

$query  = "UPDATE users SET online=true,lastseen='".date("Y-m-d H:i:s")."' WHERE username='$username'";
$result = pg_query($dbconn, $query);

if(!$result) {
	die();
}

?>
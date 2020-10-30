#!/usr/bin/php
<?php

require_once("library/session.php");
require_once("library/database.php");

$query  = "UPDATE users SET online=false WHERE username='$username'";
$result = pg_query($dbconn, $query);

if(!$result) {
	die("Could not connect to database");
}

session_destroy();
header("Location: index.php");

?>
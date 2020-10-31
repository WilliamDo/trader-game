<?php

require_once("../library/session.php");
require_once("../library/database.php");

$query  = "UPDATE messages SET unread=false WHERE receiver='$username'";
$result = pg_query($dbconn, $query);

if(!$result) {
	die();
}

?>
<?php

require_once("../library/session.php");
require_once("../library/database.php");

$query  = "SELECT x FROM battles WHERE opponent='$username'";
$result = pg_query($dbconn, $query);

echo pg_num_rows($result);

?>
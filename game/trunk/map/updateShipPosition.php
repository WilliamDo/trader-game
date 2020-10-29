<?php

require_once("../library/session.php");
require_once("../library/database.php");

$query  = "SELECT number FROM portlocation WHERE x=".$_POST['x']." and y=".$_POST['y'];
$result = pg_query($dbconn, $query);

$row = pg_fetch_row($result);

$port_no = $row[0];

if($row) {	// moved to a port
	$query  = "UPDATE ship SET x=".$_POST['x'].",y=".$_POST['y'].",number=".$row[0]." WHERE username='$username'";
	$qupd_trades = "UPDATE Market SET port=$port_no WHERE username='$username'";
//	echo "Port";
//	echo $row[0];
}
else {	// not in port
	$query  = "UPDATE ship SET x=".$_POST['x'].",y=".$_POST['y'].",number=null WHERE username='$username'";
	$qupd_trades = "UPDATE Market SET port=null WHERE username='$username'";
}

$result = pg_query($dbconn, $query);
$delete = pg_query($qupd_trades);

?>

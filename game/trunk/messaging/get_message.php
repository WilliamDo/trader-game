<?php

	require_once("../library/loadall.php");

	$message_no = $_GET["message_no"];

	$qry_message = "SELECT * FROM Messages WHERE receiver='$username' AND number='$message_no'";
	$message = db_exec_query($qry_message);
	$message_record = pg_fetch_assoc($message);

	$qupd_read = "UPDATE Messages SET unread=false WHERE number='$message_no'";
	db_exec_query($qupd_read);

	echo("<b>Message</b>");

	printpg($message_record["message"]);

?>

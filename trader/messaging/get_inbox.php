#!/usr/bin/php
<?php
	/* Retrieve inbox */
	require_once("../library/loadall.php");

	$qry_mail = "SELECT *,to_char(sent, 'Day DD Mon, HH24:MI:SS') AS timesent,subject
			FROM Messages WHERE receiver = '$username' ORDER BY sent DESC";	
	$mailbox = db_exec_query($qry_mail);	

	if (!$mailbox) {
		echo "Query failed";
	} else {
		if (pg_num_rows($mailbox) == 0) {
			printpg("You do not have any messages :(");
		}	
	}
	


	while ($message = pg_fetch_assoc($mailbox)) {
		echo "<div id='inbox'>";
		echo "<div id='sender'>";
		
		$sender = $message["sender"];
		$timestamp = $message["timesent"];
		
		echo "$sender wrote on $timestamp";
		echo "</div>";

		$wrote = $message["message"];

		echo "<div id='message'>$wrote</div>";

		echo "</div>";
	}

?>

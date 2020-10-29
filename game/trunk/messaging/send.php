<?php

	require_once("../library/loadall.php");

	/* TODO Retrieve username from SESSION or POST? */
	
	/* Message variables */
	$from = $_POST["username"];
	$to = $_POST["receiver"];
	$subject = $_POST["subject"];
	$message = $_POST["message"];
	
	printbr("From: $from");
	printbr("To: $to");
	printbr("Subject: $subject");
	printbr("Message: $message");

	$today = date('Y-m-j H:i:s');
	printpg("Time: " . $today);

	/* Insert message into the database */
	$qry_send_message = "INSERT INTO Messages (sender,receiver,subject,message,sent)
				VALUES ('$from','$to','$subject','$message','$today')";
	$sent = db_exec_query($qry_send_message);
	
	if (!$sent) {
		printpg("Failed, message was not sent :(");
	} else {
		printpg("Message sent");
	}
	
?>

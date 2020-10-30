#!/usr/bin/php
<?php

require_once("library/database.php");

session_start();

$username = $_POST["username"];
$password = $_POST["password"];	

if ($username == "") {
	header("Location: index.php?error=4");
	exit();
}

// Look up the user
$qry_user = "SELECT * FROM Users WHERE username = '". $username ."'";
$user_record = pg_query($qry_user);

if (!$user_record) {
	// Query failed
	header("Location: index.php?error=1");
	pg_close($dbconn);	// Close the database
	exit();
}

else {
	if (pg_num_rows($user_record) == 0) {
		header("Location: index.php?error=2");
		pg_close($dbconn);	// Close the database
		exit();
	}
	
	$user_info = pg_fetch_assoc($user_record);

	if ($password == $user_info["password"]) {

		// Update online flag and set lastseen timestamp
		$query  = "UPDATE users SET online=true,lastseen='".date("Y-m-d H:i:s")."' WHERE username='$username'";
		$result = pg_query($query);

		if(!$result) {
			pg_close($dbconn);	// Close the database
			header("Location: index.php?error=1");
		}

		$_SESSION["username"] = $username;
		header("Location: map.php");
	}


	else {
		header("Location: index.php?error=3&username=" . $username);
		exit();
	}
}

?>

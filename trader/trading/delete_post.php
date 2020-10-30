#!/usr/bin/php
<?php

	require_once("../library/database.php");
	$offer_id = $_POST["offer_id"];

	$qdel_post = "DELETE FROM Market WHERE offer_id='$offer_id'";
	$result = pg_query($qdel_post);

	if ($result) {
		echo "Post deleted";
	} else {
		echo "Oops!";
	}


?>

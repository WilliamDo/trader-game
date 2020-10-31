<?php
	/* Should change name to "bid" */

	require_once("../library/loadall.php");
	
	$offer_id = $_POST["offer_id"];
	$price_offered = $_POST["offer_price"];

	/* Retrieve what people are offering me */	

	$qry_post_offer = "INSERT INTO offer (offer_id, username, price)
				VALUES ($offer_id,'$username',$price_offered)";
	
	$post_offer = @pg_query($qry_post_offer);
	
	if (!$post_offer) {
		printpg("Query failure, you might have already made a bid for this item, please wait for a reply!");
	} else {
		printpg("Posted offer :o");
	}
	
?>

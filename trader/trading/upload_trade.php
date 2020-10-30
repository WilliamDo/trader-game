#!/usr/bin/php
<?php

	require_once("../library/loadall.php");
	
	$item_offered = $_POST["item_offered"];
	$quantity = $_POST["quantity"];
	$wants = $_POST["price"];
	
	$port_no = $_POST["port"];


	$qry_left = "select (quantity-sum) as left,* from inventory 
			inner join (select username,item,sum(quantity) from market where selling = true group by username,item) 
			as sum on sum.item = inventory.item and sum.username = inventory.username 
			where sum.username='$username' and inventory.item='$item_offered'";

	
	$user_item = pg_fetch_assoc(pg_query($qry_left));

	$has = $user_item["left"];

	if ($has == null) {
		$qry_left = "select * from inventory where username='$username' and item='$item_offered'";
		$user_item = pg_fetch_assoc(pg_query($qry_left));
		$has = $user_item["quantity"];
	}

	if ((int)$has < (int)$quantity) {
		echo "You do not have $quantity $item_offered";
		exit;
	}

//	printbr("From: $username");
//	printbr("Offering: $item_offered");
//	printbr("Quantity: $quantity");
//	printbr("Wants: $wants");
	
	$today = date('Y-m-j H:i:s');
//	printpg($today);

	/* Look up all users */
	$qry_post_trade = "INSERT INTO Market (username, item, asking_price, quantity, timeposted, port) 
				VALUES ('$username','$item_offered',$wants,$quantity,'$today',$port_no)";

	try {	
		$trade = @db_exec_query($qry_post_trade);
	} catch (Exception $e) {
		echo "ERROR: Something went wrong :(";
		exit;
	}
	
	if (!$trade) {
		printpg("Failed, couldn't post offer :(");
	} else {
		printpg("Offer posted");
	}
	
?>

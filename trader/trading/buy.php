#!/usr/bin/php
<?php

	require_once("../library/loadall.php");
	if ($_POST["buyer"]) {
		$buyer_username = $_POST["buyer"];
	} else {
		$buyer_username = $username;
	}

	$offer_id = $_POST["offer_id"];

	/* Get the offer */
	$qry_offer = "SELECT * FROM Market WHERE offer_id = $offer_id";
	$offer = db_exec_query($qry_offer);
	
	if (!$offer) {
		printpg("Query failed");
	} else {
		if (pg_num_rows($offer) == 0) {
			printpg("This offer doesn't exist anymore, someone might have beat you to it!");
			exit();
		}	
	}
	
	/* User buying */
	$qry_buyer = "SELECT * FROM Ship WHERE username = '$buyer_username'";
	$buyer_record = db_exec_query($qry_buyer);	
	
	$buyer = pg_fetch_assoc($buyer_record);	
	$buyer_points = $buyer["points"];

	$offer_record = pg_fetch_assoc($offer);	
	$price = $offer_record["asking_price"];	

	if ($_POST["price"]) {
		$price = $_POST["price"];
	}

	if ($buyer_points < $price) {
		printpg("You cannot afford this trade");
	} else {
		$quantity = $offer_record["quantity"];
		$item = $offer_record["item"];
		$seller_username = $offer_record["username"];
		
		if ($_POST["buyer"]) {
			$seller_username = $username;
		}

		/* Update the seller's inventory */
		$qupd_seller = "UPDATE Inventory SET quantity=quantity-$quantity 
				WHERE username='$seller_username' AND item='$item'";
		db_exec_query($qupd_seller);

		/* Update the buyer's inventory */
		$qry_check_buyer = "SELECT * FROM Inventory WHERE username = '$buyer_username' AND item = '$item'";
		$check_buyer = db_exec_query($qry_check_buyer);
		
		if (pg_num_rows($check_buyer) == 0) {
			$qupd_buyer = "INSERT INTO Inventory (username,item,quantity) 
					VALUES ('$buyer_username', '$item', $quantity)";
		} else {

			$qupd_buyer = "UPDATE Inventory SET quantity=quantity+$quantity 
					WHERE username='$buyer_username' AND item='$item'";
	
		}

		db_exec_query($qupd_buyer);

		/* Deduct from the buyer's balance */
		$qupd_points_buyer = "UPDATE Ship SET points=points-$price WHERE username='$buyer_username'";
		db_exec_query($qupd_points_buyer);

		/* Deduct from the seller's balance */
		$qupd_points_seller = "UPDATE Ship SET points=points+$price WHERE username='$seller_username'";
		db_exec_query($qupd_points_seller);

		/* Delete the post from the market */
		$qdel_offer = "DELETE FROM market WHERE offer_id=$offer_id";
		db_exec_query($qdel_offer);

		/* Add trade to the trading log */
		$now = date('Y-m-j H:i:s');
		$qins_trade = "INSERT INTO trades (time,item,quantity,price) VALUES ('$now','$item',$quantity,$price)";
		db_exec_query($qins_trade);

		$recipient = $offer_record["username"];
		if ($_POST["buyer"]) {
			echo "You sold $quantity of $item for {$currency}{$price}";
			$message = "$username sold to you $quantity of $item";
		} else {
			echo "You bought $quantity of $item for {$currency}{$price}";
			$message = "$username bought from you $quantity of $item";
		}
		$qins_message = "INSERT INTO Messages (sender,receiver,sent,message) VALUES ('System Admin','$recipient','$now','$message')";
		pg_query($qins_message);
	}	


?>

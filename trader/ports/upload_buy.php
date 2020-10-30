#!/usr/bin/php
<?php

	require_once("../library/loadall.php");
	
	$buyer_username = $username;
	$item = $_POST["item"];
	$quantity = $_POST["quantity"];
	$port_no = $_POST["port"];
	$price = $_POST["price"];

	/* Port's quantity */	
	$port_item = pg_fetch_assoc(pg_query("SELECT * FROM PortServices 
						INNER JOIN Item on Item.item = PortServices.product 
						WHERE number='$port_no' AND product='$item'"));
        $port_quantity = $port_item["quantity"];
	$product_type = $port_item["item_type"];

	/* Buyer's information */
	$qry_buyer = "SELECT * FROM Ship WHERE username = '$buyer_username'";
	$buyer_record = db_exec_query($qry_buyer);	
	$buyer = pg_fetch_assoc($buyer_record);	
	$buyer_points = $buyer["points"];


	if ($buyer_points < $price) {
		printpg("You have insufficient funds to make this purchase");
	} else {

		if ($quantity > $port_quantity) {
			echo "The port does not have $quantity of $item";
			exit;
		}

		/* Deduct from the buyer's balance */
		$qupd_points_buyer = "UPDATE Ship SET points=points-$price WHERE username='$buyer_username'";
		db_exec_query($qupd_points_buyer);

		/* Update the port's inventory */
		$qupd_seller = "UPDATE PortServices SET quantity=quantity-$quantity 
				WHERE number=$port_no and product='$item'";
//		echo $qupd_seller;
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

		/* Add trade to the trading log */
		$now = date('Y-m-j H:i:s');
		$qins_trade = "INSERT INTO trades (time,item,quantity,price) VALUES ('$now','$item',$quantity,$price)";
		db_exec_query($qins_trade);

		
		$buyer_record = db_exec_query($qry_buyer);	
		$buyer = pg_fetch_assoc($buyer_record);	
		$buyer_points = $buyer["points"];

		printpg("{$currency}{$price} have been deducted from your account and you have been credited with $quantity $item");
		
		printpg("Thank you for trading with us, please come again");
	}	


?>

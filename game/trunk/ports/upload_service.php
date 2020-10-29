<?php

	$service_name = $_GET["service"];
	$port_no = $_GET["port"];
	$quantity = $_GET["quantity"];


	require_once("../library/loadall.php");

	$buyer_username = $username;
	printpg("You requested $quantity of $service_name from port number $port_no");

	/* Price total */
	$qry_port_service = "SELECT * FROM PortServices WHERE number=$port_no AND product='$service_name'";
	$res_port_service = pg_query($qry_port_service);
	$port_service = pg_fetch_assoc($res_port_service);
	$price = $port_service["quantity"];
	$total = $price * $quantity;


	/* Buyer's information */
	$qry_buyer = "SELECT * FROM Ship WHERE username = '$buyer_username'";
	$buyer_record = db_exec_query($qry_buyer);
	$buyer = pg_fetch_assoc($buyer_record);
	$buyer_points = $buyer["points"];

	if ($buyer_points < $total) {
		printpg("You have insufficient funds to carry out this transaction");
	} else {

		if ($service_name == "repair") {
			$service_name = "health";
		}

		$current = $buyer[$service_name];		

		if ($service_name == "health") {
			$diff = 100 - $current;
			if ($diff < $quantity) {
				echo "You may only repair up to $diff units, please try again";
				exit;
			}
		} else {
			$diff = 50 - $current;
			if ($diff < $quantity) {
				echo "You can only recruit $diff more members into your crew";
				exit;
			}
		}

		/* Deduct from the buyer's balance */
		$qupd_points_buyer = "UPDATE Ship SET points=points-$total WHERE username='$buyer_username'";
		db_exec_query($qupd_points_buyer);
	
		/* Add to user's stats */
		$qupd_ship = "UPDATE Ship SET $service_name=$service_name+$quantity WHERE username='$buyer_username'";
		db_exec_query($qupd_ship);
		printpg("{$currency}{$total} has been deducted from your account. $quantity $service_name has been added to your ship");
	}


?>

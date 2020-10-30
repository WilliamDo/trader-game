#!/usr/bin/php
<?php
	require_once("../library/loadall.php");
?>
Item: <?php echo $_POST["item"]; ?>
<br />
Quantity: <?php echo $_POST["quantity"]; ?>
<br />
Price: <?php echo $_POST["price"]; ?>
<br />
<?php

	$today = date('Y-m-j H:i:s');
	$item_offered = $_POST["item"];
	$wants = $_POST["price"];
	$quantity = $_POST["quantity"];
	$port_no = $_POST["port"];

	$qry_post_trade = "INSERT INTO Market (username, item, asking_price, quantity, timeposted, selling, port) 
				VALUES ('$username','$item_offered',$wants,$quantity,'$today',false, $port_no)";

	try {
		$trade = @db_exec_query($qry_post_trade);
	} catch (Exception $e) {
		echo "ERROR: Something went wrong :(";
		exit;
	}

	if (!$trade) {
		printpg("Failed, couldn't post request :(");
	} else {
		printpg("Request posted");
	}

?>

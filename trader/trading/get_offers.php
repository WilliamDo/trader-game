<?php

	require_once("../library/loadall.php");

	$selling = $_POST["selling"];
	$port_no = $_POST["port"];
	$item_name = $_POST["item"];

	printOffers($selling, $port_no, $username, $item_name);
	function printOffers($selling, $port_no, $username, $item_name) {
		global $currency;
		if ($selling) {
			$trade_type = "true";
			$trade_message = " is selling ";
		} else {
			$trade_type = "false";
			$trade_message = " wants to buy ";
		}
		$qry_offers = "SELECT * FROM market WHERE username <> '$username' 
				AND item='$item_name' AND selling=$trade_type AND port=$port_no";
		$offers = db_exec_query($qry_offers);

		if (pg_num_rows($offers) == 0) {
			echo "<hr />There are currently no market offers";
		}
	
		echo "<table class='inventory'>";

		while ($offer = pg_fetch_assoc($offers)) {
			$offer_id = $offer["offer_id"];
			$offerer = $offer["username"];
			if ($selling) {
				$trade_function = "buy($offer_id)";
			} else {
				$trade_function = "sell_to_user($offer_id,'$offerer')";
			}
			
			echo "<tr>";
			echo "<td>" . $offer["username"] . $trade_message . $offer["quantity"] . " for $currency" . $offer["asking_price"] .
				"<br />Price per unit: " . number_format(($offer["asking_price"] / $offer["quantity"]),2);
			echo "</td><td><form><br />
				<input type='button' value='Accept' onclick=\"$trade_function\" />
				</form>
				</td>";
			
			echo "</tr>";
		}
	
		echo "</table>";
	}

?>

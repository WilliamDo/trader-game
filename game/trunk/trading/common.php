<?php

	function docked($username) {
		$qry_ship = "SELECT * FROM Ship WHERE username='$username' AND number is not null";
		$ship_result = db_exec_query($qry_ship);
		$ship = pg_fetch_assoc($ship_result);
		$ship_name = $ship["shipname"];

		if (pg_num_rows($ship_result) == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function getPortNumber($username) {
		$qry_ship = "SELECT number FROM Ship WHERE username='$username' 
				AND number is not null";
		$ship_result = db_exec_query($qry_ship);
		$ship = pg_fetch_assoc($ship_result);
		return $ship["number"];
	}
	
	function getPortName($port_no) {
		$qry_port = "SELECT name FROM Port WHERE number=$port_no";
		$res_port = pg_query($qry_port);
		$port = pg_fetch_assoc($res_port);
		return $port["name"];
	}

	function printItemInfo($item_name) {
		global $currency;	
		$qry_item_info = "SELECT round((sum(round(price,0)) / sum(round(quantity,0))),2) AS ppi 
					FROM trades WHERE item='$item_name'";

		$item_info = pg_fetch_assoc(pg_query($qry_item_info));
		$ppi = $item_info["ppi"];
		if (!$ppi) {$ppi = "n/a";}

		$qry_total = "SELECT count(item) AS total FROM trades";
		$total_info = pg_fetch_assoc(pg_query($qry_total));
		$total = $total_info["total"];

		$qry_fraction = "SELECT count(item) AS total FROM trades WHERE item='$item_name'";
		$fraction_info = pg_fetch_assoc(pg_query($qry_fraction));
		$fraction = $fraction_info["total"];

		if ($total != 0) {
			$percentage = number_format($fraction*100 / $total, 1);
		} else {
			$percentage = number_format(0, 0);
		}
		
		$qry_item = "SELECT imageurl FROM Item WHERE item='$item_name'";
		$res_item = pg_query($qry_item);
		$inf_item = pg_fetch_assoc($res_item);
	
		$imageurl = $inf_item["imageurl"];
		echo "<table><tr>";
		echo "<td width='70'><img src='$imageurl' class=item_image /></td>";
		echo "<td>$item_name<br />average price per unit: {$currency}{$ppi}<br />percentage of trades: $percentage%</td>";
		echo "</table></tr>";
	}
	
	function printOffers($selling, $port_no, $username, $item_name) {
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

		global $currency;
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

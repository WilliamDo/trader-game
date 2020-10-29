<?php
	/* Query for price per item */
	$qry_item_info = "SELECT round((sum(round(price,0)) / sum(round(quantity,0))),2) AS ppi, 
				round(round((sum(round(price,0)) / sum(round(quantity,0))),2) * 1.175,2) AS rppi
				FROM trades WHERE item='$item_name'";

	$item_info = pg_fetch_assoc(pg_query($qry_item_info));
	
	$ppi = $item_info["ppi"];
	$rppi = $item_info["rppi"];

	if (!$ppi) {
		$ppi = "n/a";
	}

	/* Percentage of trades */
	$qry_total = "SELECT count(item) AS total FROM trades";
	$total_info = pg_fetch_assoc(pg_query($qry_total));
	$total = $total_info["total"];

	$qry_fraction = "SELECT count(item) AS fraction FROM trades WHERE item='$item_name'";
	$fraction_info = pg_fetch_assoc(pg_query($qry_fraction));
	$fraction = $fraction_info["fraction"];

	if ($total != 0) {
		$percentage = number_format($fraction * 100 / $total, 1);
	} else {
		$percentage = number_format(0, 0);
	}
	
	$qry_item = "SELECT imageurl FROM Item WHERE item='$item_name'";
	$res_item = pg_query($qry_item);
	$inf_item = pg_fetch_assoc($res_item);
	
	$imageurl = $inf_item["imageurl"];

	/* Print item information */
	echo "<table><tr><td width='70'><img src='$imageurl' class='item_image' /></td><td>";
	echo ""; 
	if (!$is_service) {
		echo "$item_name<br />average price per unit: {$currency}{$ppi}<br />percentage of trades: $percentage%";
	} else {
		$qry_port = "SELECT * FROM Port WHERE number='$port_no'";
		$res_port = pg_query($qry_port);
		$port = pg_fetch_assoc($res_port);
		
		$port_name = $port["name"];

		printpg("Services for $service_name at $port_name");
	}
	echo "</td></tr></table><hr />";


?>

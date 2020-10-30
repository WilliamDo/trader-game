<?php
	
	/* Return the ship */
	$qry_ship = "SELECT * FROM Ship WHERE username='$username' AND number is not null";
	$ship_result = db_exec_query($qry_ship);
	$ship = pg_fetch_assoc($ship_result);
	$ship_name = $ship["shipname"];

	if (pg_num_rows($ship_result) == 0) {
		printpg("You have not docked at any port :(");
	} else {
		/* Docked */
		$port_no = $ship["number"];
		$port = pg_fetch_assoc(pg_query("SELECT * FROM Port WHERE number='$port_no'"));
		$port_name = $port["name"];
		printpg("Welcome to $port_name");

?>
<script>

	function port_buy(item, port) {
		$.get("ports/buy.php", {item: item, port: port}, function(data) {
			$.facebox(data);
		});
	}

	function port_service(service, port) {
		if (service != "upgrade") {
			$.get("ports/service.php", {service: service, port: port}, function (data) {
				$.facebox(data);
			});
		} else {
			$.get("ports/upgrade.php",{port: port}, function (data) {
				$.facebox(data);
			});
		}
	}


</script>
<hr />
<form>

<table width="100%">
	<tr>
		<td valign="top" width="50%">

<?php
	/* Port goods */	
	$qry_port_goods = "SELECT * FROM PortServices INNER JOIN Item ON Item.item = PortServices.product WHERE number=$port_no AND item_type='good' ORDER BY Item.item";
	$port_goods = pg_query($qry_port_goods);
	
	if (pg_num_rows($port_goods) == 0) {
		echo "This port does not sell any goods";
	} else {
		echo "Goods";
	}
	echo "<table class='inventory'>";
	while($product = pg_fetch_assoc($port_goods)) {
		$quantity = $product["quantity"];
		$name = $product["item"];
		$imageurl = $product["imageurl"];
		$description = $product["description"];
		
		echo "<tr><td width='70'><img src='$imageurl'class='item_image' /></td>
			<td><b>$name</b><br />Quantity: $quantity<br />$description</td>
			<td><input type='button' value='Buy' onclick=\"port_buy('$name',$port_no)\" /></td></tr>";
	}
	
	echo "</table>";
?>
		</td>
		<td valign="top">
<?php
	/* Port services */
	$qry_port_goods = "SELECT * FROM PortServices INNER JOIN Item on Item.item = PortServices.product WHERE number=$port_no AND item_type='service'";
        $port_goods = pg_query($qry_port_goods);

	if (pg_num_rows($port_goods) == 0) {
		echo "There are no services at this port";
	} else {
		echo "Services";
	}
	echo "<table class='inventory'>";
	while($product = pg_fetch_assoc($port_goods)) {
	
                $quantity = $product["quantity"];
                $name = $product["item"];
		$description = $product["description"];
		$imageurl = $product["imageurl"];

		switch ($name) {
			case "repair" : $button = "Repair"; break;
			case "upgrade" : $button = "Upgrade"; break;
			case "crew": $button = "Recruit"; break;
		}

		echo "<tr><td width='70'><img src='$imageurl' class='item_image' /></td>";
		
		echo "<td><b>Ship $name</b>";
		if ($name != "upgrade") {
			echo "<br />Price: {$currency}{$quantity}";
		}
		echo "<br />$description</td>";
		
		
		echo "<td><input class='btn' type='button' value='$button' onclick=\"port_service('$name',$port_no)\" /></td></tr>";
        }

	echo "</table>";
?>

		</td>

	</tr>

</table>
</form>
<?php } ?>

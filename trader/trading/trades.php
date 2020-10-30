<?php
	require_once("library/loadall.php");

	$qry_items = "SELECT * FROM inventory INNER JOIN item ON item.item=inventory.item WHERE username='$username' AND quantity>0 ORDER BY item.item";
	$qry_none = "SELECT item.item AS item, quantity, description, imageurl
			FROM (users CROSS JOIN item) LEFT OUTER JOIN inventory 
			ON item.item=inventory.item AND users.username=inventory.username 
			WHERE item_type='good' AND users.username='$username' 
				AND inventory.item is null or (users.username='$username' AND quantity=0) ORDER BY item";
	$inventory = db_exec_query($qry_items);
	$not_owned = db_exec_query($qry_none);
	
	if (pg_num_rows($inventory) != 0) {
		echo "Items you own";
	}		

	echo "<table class='inventory'>";
	while ($item = pg_fetch_assoc($inventory)) {
		$item_name = $item["item"];
		$imageurl = $item["imageurl"];


/* Hmm */

		$qry_left = "select (quantity-sum) as left,* from inventory 
                        inner join (select username,item,sum(quantity) from market where selling = true group by username,item) 
                        as sum on sum.item = inventory.item and sum.username = inventory.username      
                        where sum.username='$username' and inventory.item='$item_name'";

                $user_item = pg_fetch_assoc(pg_query($qry_left));

                $has = $user_item["left"];
		$q = $user_item["quantity"];
                if ($has == null || $q == 0) {
                        $qry_left = "select * from inventory where username='$username' and item='$item_name'";
                        $user_item = pg_fetch_assoc(pg_query($qry_left));
                        $has = $user_item["quantity"];
                }

/* Hmm */


		echo "<tr>";
		echo "<td width='70'><img src='$imageurl' class='item_image' /></td>";
		echo "<td width='600'><b>" . $item["item"] . "</b><br /> " .
			"Quantity: " . $item["quantity"] . " (You have $has available to sell) "	. 
			"<br />" . $item["description"] . "</td>";
		echo "<td>
			<input class='btn' type='button' value='Buy' onclick=\"show_buy('$item_name')\" />
			<input class='btn' type='button' value='Sell' onclick=\"show_sell('$item_name')\" />
			</td>";
		echo "</tr>";
	}
	
	echo "</table>";


	if (pg_num_rows($not_owned) != 0) {
		echo "<br />Items you do not own";
	}
		
	echo "<table class='inventory'>";
	while ($item = pg_fetch_assoc($not_owned)) {
		$item_name = $item["item"];
		$imageurl = $item["imageurl"];
		echo "<tr id='item'>";
		echo "<td width='70'><img src='$imageurl' class='item_image' /></td>";

		echo "<td width='600'><b>" . $item["item"] . "</b><br /> " .
			"Quantity: None"	. 
			"<br />" . $item["description"] .  "</td>";
	
		echo "<td>
			<input class='btn' type='button' value='Buy' onclick=\"show_buy('$item_name')\" />
			</td>";
		echo "</tr>";
	}

	echo "</table>";
?>
<script>

	function show_buy(item) {
		$.get('trading/market_place.php', {item: item}, function(data) {
			$.facebox(data);
		});
	}

	function show_sell(item) {
		$.get('trading/sell.php', {item: item}, function(data) {
			$.facebox(data);
		});
	}

</script>

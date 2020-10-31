<?php
	require_once("../library/loadall.php");

	$port_no = $_GET["port"];
	$item_name = "upgrade";
	$service_name = $item_name;
	$is_service = true;
	include("getItemInfo.php");

	$qry_user_ship = "SELECT * FROM Ship INNER JOIN ShipType 
				ON Ship.shiptype = ShipType.shiptype 
				AND username='$username'";
	$res_user_ship = pg_query($qry_user_ship);
	$user_ship = pg_fetch_assoc($res_user_ship);

	$rank = $user_ship["rank"];

	$qry_ships = "SELECT * FROM ShipType WHERE rank>$rank ORDER BY rank";
	$ships = pg_query($qry_ships);

	if (pg_num_rows($ships) == 0) {
		echo "You already have the best ship!";
	} else {

	$ship = pg_fetch_assoc($ships);

	$imageurl = $ship["imageurl"];
	$name = $ship["shiptype"];
	$price = $ship["price"];
?>
<script>

	function upgrade(ship) {
		$.get("ports/upload_upgrade.php",{ship: ship}, function(data) {
			$.facebox(data);
			window.frames[0].getShipInfo('<?=$username;?>',true);
			parent.updateBalanceAndFood();
		});
	}

</script>
<table>

	<tr>
		<td valign='top' width='100'>
			<img src='<?=$imageurl; ?>' />
			
		</td>

		<td>
			<p>You need <?php echo "{$currency}{$price}"; ?> to upgrade to the <?=$name; ?> ship</p>

			<form>

				Would you like to make this upgrade?
				<p><input type='button' value='Yes, upgrade' onclick="upgrade('<?=$name; ?>')" /></p>
	
			</form>

		</td>
	</tr>

</table>
<?php } ?>

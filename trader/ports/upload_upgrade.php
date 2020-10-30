#!/usr/bin/php
<?php
	require_once("../library/loadall.php");

	
	
	$ship_name = $_GET["ship"];
	$buyer_username = $username;

	$qry_ships = "SELECT * FROM ShipType WHERE shiptype='$ship_name'";
	$ships = pg_query($qry_ships);	
	$ship = pg_fetch_assoc($ships);

	$imageurl = $ship["imageurl"];
	$price = $ship["price"];

	/* Buyer's information */
	$qry_buyer = "SELECT * FROM Ship WHERE username = '$buyer_username'";
	$buyer_record = db_exec_query($qry_buyer);
	$buyer = pg_fetch_assoc($buyer_record);
	$buyer_points = $buyer["points"];

	if ($buyer_points < $price) {
		printpg("You have insufficient funds to carry out this transaction");
	} else {

		$qupd_ship = "UPDATE Ship SET shiptype='$ship_name' WHERE username='$username'";
		db_exec_query($qupd_ship);

		$qupd_points_buyer = "UPDATE Ship SET points=points-$price WHERE username='$buyer_username'";
		db_exec_query($qupd_points_buyer);

		echo "<div align='center'><img src='$imageurl' /></div>";
		echo "<p>Congratulations! You have upgraded to the $ship_name ship.";
	

?>
<script>

	window.frames[0].shipUpgrade('<?=$ship_name;?>');

</script>

<?php } ?>

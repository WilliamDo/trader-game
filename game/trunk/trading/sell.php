<?php
	require_once("../library/loadall.php");
	require_once("common.php");

	/* Check if the user has docked */
	if (!docked($username)) {
		echo "Please move to a port to begin trading";
		exit;
	}
	
	/* Get port info */
	$port_no = getPortNumber($username);
	$port_name = getPortName($port_no);
		
	/* Print item statistics */
	$item_name = $_GET["item"];
	printItemInfo($item_name);
?>

<?php
	/* Determine quantity user has not yet posted for sale */
	$qry_items = "SELECT * FROM inventory WHERE username='$username' AND item='$item_name'";
	$inventory = db_exec_query($qry_items);
	$item = pg_fetch_assoc($inventory);

	$qry_left = "select (quantity-sum) as left,* from inventory 
			inner join (select username,item,sum(quantity) from market where selling=true group by username,item) 
			as sum on sum.item = inventory.item and sum.username = inventory.username 
			where sum.username='$username' and inventory.item='$item_name'";

	$user_item = pg_fetch_assoc(pg_query($qry_left));

	$has = $user_item["left"];
	if ($has == null) {
		$qry_left = "select * from inventory where username='$username' and item='$item_name'";
		$user_item = pg_fetch_assoc(pg_query($qry_left));
		$has = $user_item["quantity"];
	}
	
	echo "You have " . $has . " left to sell";
	
?>
	
<script type="text/javascript">
	function sell(item, port) {
		var quantity = document.sell_request.quantity.value;
		var ppi = document.sell_request.price.value;
		var total = ppi * quantity;

		$.post("trading/upload_trade.php", {price: total, item_offered: item, quantity: quantity, port: port}, function(data) {
			parent.updateBalanceAndFood();
			$.facebox(data);
		});
	}

	function calculateTotal() {
		var ppi = document.sell_request.price.value;
		var q = document.sell_request.quantity.value;
		var total = ppi * q;
		$("#totalPrice").html("Total: <?=$currency; ?>" + total);
	}

	function sell_to_user(id, buyer) {
		$.post("trading/buy.php", {offer_id: id, buyer: buyer}, function(data) {
			parent.updateBalanceAndFood();
			$.facebox(data);
		});
	}

	function getSellOffers() {
		$.post("trading/get_offers.php", {selling: 0, port: <?=$port_no; ?>, item: "<?=$item_name; ?>"}, function(data) {
			$("#offers_sell_div").html(data);
		});
	}
	
	var facebox_interval = setInterval(getSellOffers, 2000);
</script>
<hr />
<p>Sell your items</p>
<form name='sell_request'>
	Quantity:	
	<input type='text' name='quantity' size='6' onkeyup="calculateTotal()" />

	Price per unit:
	<input type='text' name='price' size='6' onkeyup="calculateTotal()" />

	<p><span id="totalPrice"></span></p>

	<p align='center'>
	<input type='button' value='Sell' onclick="sell(<?php echo "'$item_name', $port_no"; ?>)" /></p>
</form>
<div id='offers_sell_div' class='items_posted'>
<?php	
	/* Print trades that other people have requested */
	printOffers(false, $port_no, $username, $item_name);

?>
</div>

<?php

	require_once("../library/loadall.php");
	require_once("common.php");
	
	/* Check if the user has cked */
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

<script type="text/javascript">

	function buy(id) {
		$.post("trading/buy.php", {offer_id: id}, function(data) {
			parent.updateBalanceAndFood();
			$.facebox(data);
		});
	}
	
	function calculateTotal() {
		var ppi = document.buy_request.ppi.value;
		var q = document.buy_request.quantity.value;
		var total = ppi * q;
		$("#totalPrice").html("Total: <?=$currency; ?>" + total);
			return ppi * q;
	}

	function request(item, port) {
		document.buy_request.request_button.disabled="disabled";
		var total = calculateTotal();
		var q = document.buy_request.quantity.value;
		$.post("trading/request.php", {item: item, price: total, quantity: q, port: port}, function(data) {
			$.facebox(data);
		});
	}
	
	function getOffers() {
		$.post("trading/get_offers.php", {selling: "true", port: <?=$port_no; ?>, item: "<?=$item_name;?>"}, function(data) {
			$("#offers_div").html(data);
		});
	}

	var facebox_interval = setInterval(getOffers, 2000);

</script>
<hr />
<p>Item request form</p>
	<p align='center'><form name='buy_request'>
		Quantity: <input type='text' name='quantity' size='6' onkeyup='calculateTotal()' />
		Price per item: <input type='text' name='ppi' size='6' onkeyup='calculateTotal()' />
	</p>
	<p><span id='totalPrice'></span></p>
	<input type='button' name='request_button' value='Request' onclick="request(<?php echo "'$item_name', $port_no"; ?>)" />
</form>

<div id='offers_div' class='items_posted'>
<?php
	/* Print trades that other people are selling */
	printOffers(true, $port_no, $username, $item_name);

?>
</div>
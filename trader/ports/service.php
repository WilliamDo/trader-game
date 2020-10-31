<?php

	require_once("../library/loadall.php");
	$service_name = $_GET["service"];
	$port_no = $_GET["port"];

	$item_name = $service_name;
	$is_service = true;
	include("getItemInfo.php");	



	/* Explain to user price of blah blah */
	$qry_port_service = "SELECT * FROM PortServices WHERE number='$port_no' AND product='$service_name'";
	$port_service = pg_query($qry_port_service);
	$service = pg_fetch_assoc($port_service);
	
	$service_price = $service["quantity"];

	if ($service_name == "repair") {
		printpg("You can add one unit of health for {$currency}{$service_price}");
		printpg("Please specify how many units you would like");
	}

	if ($service_name == "crew") {
		printpg("You can recuit one crew member for {$currency}{$service_price}");
		printpg("Please specify how many crew members you would like");
	}

?>
<script>
	function updateTotal(ppi) {
		var q = document.quantity_request.units.value;
		var total = ppi * q;
		$("#total").html(total);
		return total;
	}

	function buy_service(port, service) {
		var q = document.quantity_request.units.value;
		$.get("ports/upload_service.php", {port: port, service: service, quantity: q}, function(data) {
			$.facebox(data);
			parent.updateBalanceAndFood();
			window.frames[0].getShipInfo('<?=$username;?>');
		});
	}
</script>
<form name='quantity_request'>
	Units: <input type='text' size=6 name='units' onkeyup='updateTotal(<?php echo $service_price; ?>)' />
	Total: <span id='total'></span> 
	<p><input type="button" value="Accept" onclick="buy_service(<?php echo "$port_no, '$service_name'"; ?>)" /></p>
</form>

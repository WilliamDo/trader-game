<?php
	require_once("../library/loadall.php");
	$item_name = $_GET["item"];
	$port_no = $_GET["port"];
	$is_service = false;
	include("getItemInfo.php");

	/* Get port name */
	$qry_port = "SELECT * FROM Port WHERE number=$port_no";
	$res_port = pg_query($qry_port);
	$port = pg_fetch_assoc($res_port);
	$port_name = $port["name"];
        echo "Buying $item_name at $port_name";

	/* Check port availability */
	$port_item = pg_fetch_assoc(pg_query("SELECT * FROM PortServices WHERE number='$port_no' AND product='$item_name'"));
	$quantity = $port_item["quantity"];

        if ($quantity == 0) {
                printpg("No $item_name available at this port :(");
                exit;
        } else {
		if (!$rppi) {
			/* Use rppi if no trades have taken place */
			$rppi = 1.00;
		}
                printpg("The port currently can sell $quantity of $item_name at " . $currency . number_format($rppi, 2) ." per unit, how much would you like to buy?");
        }

?>
<script>

	function updateTotal(ppi) {
		var q = document.quantity_request.quantity.value;
		var total = ppi * q;
		$("#total").html(total);
		return total;
	}

	function buy_item(ppi, item, port_no) {
		var p = updateTotal(ppi);
		var q = document.quantity_request.quantity.value;
		
		$.post("ports/upload_buy.php",{item: item, port: port_no, quantity: q, price: p},function(data) {
			$.facebox(data);
			parent.updateBalanceAndFood();
		});
	}

</script>
<form name="quantity_request">
	Quantity: <input type='text' size='6' name='quantity' onkeyup='updateTotal(<?php echo $rppi; ?>)' />
	Total: <span id='total'></span>
	<p align='center'><input type='button' value='Buy' onclick="buy_item(<?php echo "$rppi,'$item_name',$port_no" ?>)" /></p>
</form>

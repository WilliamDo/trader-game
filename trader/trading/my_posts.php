<?php
	$qry_posts = "SELECT * FROM Market, Item WHERE Market.item = Item.item AND username='$username' AND selling=true";
	$posts = pg_query($qry_posts);

?>
<script>

	function deletePost(id) {
		$.post("trading/delete_post.php", {offer_id: id}, function(data) {
			$.facebox (data);
			parent.updateBalanceAndFood();
		});
	}

</script>
<form>

<table width='100%'>
	<tr>
		<td valign='top' width='50%'>
		

<?php
	if (pg_num_rows($posts) == 0) {
		echo "You have not posted any sales";
	} else {
		echo "Your posts";
	}

?>
<table class='inventory'>
<?php

	while ($post = pg_fetch_assoc($posts)) {
		$offer_id = $post["offer_id"];
		$quantity = $post["quantity"];
		$price = $post["asking_price"];
		$item = $post["item"];
		$imageurl = $post["imageurl"];
		
		echo "<tr><td width='70'><img src='$imageurl' class='item_image' /></td><td width='600'>";
		echo "$item<br />You wanted to sell $quantity for {$currency}{$price}";
		echo "</td><td><input type='button' value='Remove' onclick='deletePost($offer_id)' />";
		echo "</td></tr>";
	}

?>
</table>
		</td>
		<td valign='top'>

<?php
	
	$qry_posts = "SELECT * FROM Market, Item WHERE Market.item = Item.item AND username='$username' AND selling=false";
        $posts = pg_query($qry_posts);
	
	if (pg_num_rows($posts) == 0) {
		echo "You have not posted any buy requests";
	} else {
		echo "Your requests";
	}
?>

<table class='inventory'>
<?php
	

        while ($post = pg_fetch_assoc($posts)) {
                $offer_id = $post["offer_id"];
                $quantity = $post["quantity"];
                $price = $post["asking_price"];
                $item = $post["item"];
                $imageurl = $post["imageurl"];

                echo "<tr><td width='70'><img src='$imageurl' class='item_image' /></td><td width='600'>";
                echo "$item<br />You wanted to buy $quantity for {$currency}{$price}";
                echo "</td><td><input type='button' value='Remove' onclick='deletePost($offer_id)' />";
                echo "</td></tr>";
        }

?>
</table>
		</td>
	</tr>
</table>

</form>



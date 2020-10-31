<?php

require_once("../library/session.php");
require_once("../library/database.php");

header('Content-Type: text/xml');


$query  = "SELECT points FROM ship WHERE username='$username'";
$result = pg_query($dbconn, $query);
$row    = pg_fetch_assoc($result);

$points = number_format($row['points']);


$qry_left = "select (quantity-sum) as left,* from inventory 
             inner join (select username,item,sum(quantity) from market where selling = true group by username,item) 
             as sum on sum.item = inventory.item and sum.username = inventory.username 
             where sum.username='$username' and inventory.item='food'";

$user_item = pg_fetch_assoc(pg_query($qry_left));
$food = $user_item["left"];

if ($food == null) {
	$qry_left = "select * from inventory where username='$username' and item='food'";
	$user_item = pg_fetch_assoc(pg_query($qry_left));
	$food = $user_item["quantity"];
}

/*$query  = "SELECT quantity FROM inventory WHERE username='$username' and item='food'";
$result = pg_query($dbconn, $query);
$row    = pg_fetch_assoc($result);

$food =  $row['quantity'];*/

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
echo "<info>\n";
echo "\t<balance>$points</balance>\n";
echo "\t<food>$food</food>\n";
echo "</info>";

?>
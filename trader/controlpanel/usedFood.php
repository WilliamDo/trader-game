#!/usr/bin/php
<?php

require_once("../library/session.php");
require_once("../library/database.php");

// ALSO REMOVE PENDING CHALLENGES BY USER (sorry about non-descriptive file name)
$query  = "DELETE FROM battles WHERE challenger='$username'";
$result = pg_query($dbconn, $query);

/*
$query  = "SELECT FROM battles WHERE opponent='$username'";
$result = pg_query($dbconn, $query);
$*/

$query  = "SELECT crew FROM ship WHERE username='$username'";
$result = pg_query($dbconn, $query);
$row = pg_fetch_row($result);

$query  = "UPDATE inventory SET quantity=quantity-".$row[0]." WHERE username='$username' and item='food'";
$result = pg_query($dbconn, $query);


/*$query  = "SELECT quantity FROM inventory WHERE username='$username' and item='food'";
$result = pg_query($dbconn, $query);

$row = pg_fetch_row($result);
echo $row[0];*/

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

echo $food;
/*
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n\n";
echo "<data>\n";
echo "<food>$food</food>";
echo "<challenge>$food</challenge>";
echo "</data>";
*/
?>
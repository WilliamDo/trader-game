<?php

require_once("../library/session.php");
require_once("../library/database.php");

$choice = $_GET['r'];

$query    = "SELECT challenger FROM battles WHERE opponent='$username'";
$result   = pg_query($dbconn, $query);
$row      = pg_fetch_row($result);
$opponent = $row[0];

$balanceLoss = 0.5;
$itemLoss = 0.6;

if ($choice == "accept") {

	// Calculate probability of each ship winning: crew/max_crew * weapons/max_weapons * health * type of ship (0.6, 0.8, 1.0)

	$max_crew = $max_weapons = 50;
	$shipscore = array("Pirate" => 0.6, "Cruise" => 0.8, "Battle" => 1.0);
	
	// Your ship	
	$query    = "SELECT crew,shiptype,health FROM ship WHERE username='$username'";
	$result   = pg_query($dbconn, $query);
	$row      = pg_fetch_assoc($result);

	$query2   = "SELECT quantity FROM inventory WHERE username='$username' and item='weapons'";
	$result2  = pg_query($dbconn, $query2);
	$row2     = pg_fetch_assoc($result2);

	$myScore = ($row['crew']/$max_crew) * ($row2['quantity']/$max_weapons) * ($row['health']/100) * $shipscore[$row['shiptype']];


	// Opponent ship
	$query    = "SELECT crew,shiptype,health FROM ship WHERE username='$opponent'";
	$result   = pg_query($dbconn, $query);
	$row      = pg_fetch_assoc($result);

	$query2   = "SELECT quantity FROM inventory WHERE username='$opponent' and item='weapons'";
	$result2  = pg_query($dbconn, $query2);
	$row2     = pg_fetch_assoc($result2);

	$opponentScore = ($row['crew']/$max_crew) * ($row2['quantity']/$max_weapons) * ($row['health']/100) * $shipscore[$row['shiptype']];


	// Calculate how much health to lose
	$minScore = min($myScore, $opponentScore);
	$maxScore = max($myScore, $opponentScore);

	$loserLoss = intval((1-($minScore/$maxScore))/2*100);
	$winnerLoss = intval($loserLoss/2);


	// Randomly decide whether lesser ship should win with a 10% probability

	$againstAllOdds = false;

	if (rand(1,10) > 9) {
		$againstAllOdds = true;

		$temp = $myScore;
		$myScore = $opponentScore;
		$opponentScore = $temp;
	}

	if($myScore > $opponentScore) {
		$winner = $username;
		$loser = $opponent;
	}
	else {
		$winner = $opponent;
		$loser = $username;
	}

	$query    = "UPDATE ship SET health=health-$loserLoss WHERE username='$loser'";
	$result   = pg_query($dbconn, $query);
	$query    = "UPDATE ship SET health=health-$winnerLoss WHERE username='$winner'";
	$result   = pg_query($dbconn, $query);


/*	echo "My score: $myScore<br>";	
	echo "Opponent score: $opponentScore<br>";
	echo "Loser loss: $loserLoss<br>";
	echo "Winner loss: $winnerLoss<br>";
	echo "Against all odds: ";
	if($againstAllOdds) { echo "true"; }
	else {	echo "false"; }*/	

	echo "<p>You have chosen to accept the challenge.</p>";

	if ($winner == $username) {
		if($againstAllOdds) {
			echo "<p>Remarkably, you have won the battle. Congratulations!</p>";
		}
		else {
			echo "<p>Congratulations, you have won the battle with ease!</p>";
		}
	}

	else {	// lost
		if($againstAllOdds) {
			echo "<p>Against all the odds, you have lost this battle.</p>";
		}
		else {
			echo "<p>Unfortunately, you have lost the battle.</p>";
		}
	}



	if ($winner == $opponent) {
		if($againstAllOdds) {
			$messageToSend = "Remarkably, you have won the battle. Congratulations!";
		}
		else {
			$messageToSend = "Congratulations, you have won the battle with ease!";
		}
	}

	else {	// lost
		if($againstAllOdds) {
			$messageToSend = "Against all the odds, you have lost this battle.";
		}
		else {
			$messageToSend = "Unfortunately, you have lost the battle.";
		}
	}
}

else {	// choice == "decline"
	$winner = $opponent;
	$loser = $username;

	$balanceLoss = $balanceLoss/2;
	$itemLoss = $itemLoss/2;

	echo "<p>You have chosen to accept the challenge.</p>";
	echo "You have bribed your opponent with 30% of your food and weapons, and 25% of your balance.</li>";
}

echo "<p>You have sustained some damage to your ship. It would be unwise to commence battle again.</p>";

$query    = "SELECT points FROM ship WHERE username='$loser'";
$result   = pg_query($dbconn, $query);
$row      = pg_fetch_assoc($result);
$pointsToAdjust = intval($row['points']*$balanceLoss);

$query    = "UPDATE ship SET points=points-$pointsToAdjust WHERE username='$loser'";
$result   = pg_query($dbconn, $query);
$query    = "UPDATE ship SET points=points+$pointsToAdjust WHERE username='$winner'";
$result   = pg_query($dbconn, $query);


$query    = "SELECT quantity FROM inventory WHERE username='$loser' and item='food'";
$result   = pg_query($dbconn, $query);
$row      = pg_fetch_assoc($result);
$foodToAdjust = intval($row['quantity']*$itemLoss);

$query    = "UPDATE inventory SET quantity=quantity-$foodToAdjust WHERE username='$loser' and item='food'";
$result   = pg_query($dbconn, $query);
$query    = "UPDATE inventory SET quantity=quantity+$foodToAdjust WHERE username='$winner' and item='food'";
$result   = pg_query($dbconn, $query);


$query    = "SELECT quantity FROM inventory WHERE username='$loser' and item='weapons'";
$result   = pg_query($dbconn, $query);
$row      = pg_fetch_assoc($result);
$weaponsToAdjust = intval($row['quantity']*$itemLoss);

$query    = "UPDATE inventory SET quantity=quantity-$weaponsToAdjust WHERE username='$loser' and item='weapons'";
$result   = pg_query($dbconn, $query);
$query    = "UPDATE inventory SET quantity=quantity+$weaponsToAdjust WHERE username='$winner' and item='weapons'";
$result   = pg_query($dbconn, $query);

$query    = "DELETE from battles WHERE challenger='$username' or opponent='$username'";
$result   = pg_query($dbconn, $query);

$query    = "INSERT INTO Messages (sender,receiver,subject,message,sent) VALUES ('System Admin','$opponent',' ','Outcome of battle with {$username}:<br />$messageToSend','".date('Y-m-j H:i:s')."')";
$result   = pg_query($dbconn, $query);

?>

<script type="text/javascript">
parent.updateBalanceAndFood(false, true);
window.frames[0].getShipInfo('<?=$username;?>',false,true);
</script>
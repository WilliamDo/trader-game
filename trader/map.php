#!/usr/bin/php
<?php

require_once("library/session.php");
require_once("library/database.php");

$query  = "SELECT shipname,points FROM ship WHERE username='$username'";
$result = pg_query($dbconn, $query);
$row    = pg_fetch_assoc($result);

$shipname = $row['shipname'];

?>
<html>

<head>
<title>Title Here</title>
<link rel="stylesheet" type="text/css" href="cssjs/main.css" />
<link rel="stylesheet" type="text/css" href="cssjs/facebox/facebox.css" />
<script type="text/javascript" src="cssjs/jquery.js"></script>
<script type="text/javascript" src="cssjs/jquery.ajaxmanager.js"></script>
<script type="text/javascript" src="cssjs/jQuery.colorBlend.js"></script>
<script type="text/javascript" src="cssjs/facebox/facebox.js"></script>
<script type="text/javascript" src="cssjs/main.js"></script>
<script type="text/javascript">
var user = "<?=$username;?>";
var baseurl = "<?=$baseurl;?>";
</script>
</head>

<body>

<div id="endOfGame">
<div id="food_msg" class="error_msg"><p>You have depleted all your food supplies. Your crew has starved.</p><p><a href="reset.php">Restart your game.</a></p></div>
<div id="damage_msg" class="error_msg"><p>Your ship has been destroyed and it is sinking. Your crew has abandoned ship.</p><p><a href="reset.php">Restart your game.</a></p></div>
</div>

<div id="content">

<div id="left">
	<div id="left_top">
		<p id="logoutbar"><span id="locateMe"><a href="javascript:locateMe()">Find my ship</a></span><a href="#">Help</a> | <a href="logout.php">Logout</a></p>
		<p>You are logged in as <b><?=$username;?></b></p>
		<p id="shipname">Your ship is named <b><?=$shipname;?></b></p>
		<p>Balance: <?=$currency;?><span id="balance_text"><?=$points;?></span></p>
		<p>Food available: <span id="food_text"><?=$food;?></span><!-- <a href="javascript:updateBalanceAndFood()">Update</a> --></p>
	</div>

	<div id="left_bottom">
		<p class="heading">Online now</p>
			<p id="alone">You're alone :(</p>
			<ul id="users_online"></ul>

		<p id="onlineofflinetoggle"><span style="font-size:8pt">&#x25bc;</span> <a href="javascript:showOffline()">Show offline</a></p>

		<div id="offline">
			<ul id="users_offline"></ul>
		</div>
	</div>

	<div id="ship_info">
		<p class="heading" id="stats_heading">Ship stats</p>

		<table id="ship_table">
		<tr><td class="table_left">Ship name</td><td id="ship_name"></td></tr>
		<tr><td class="table_left">Owner</td><td id="ship_owner"></td></tr>
		<tr><td class="table_left">Type of ship</td><td id="ship_type"></td></tr>
		<tr><td class="table_left">Number of crew</td><td id="ship_crew"></td></tr>
		<tr><td class="table_left">Health</td><td id="ship_health"></td></tr>
		<tr><td class="table_left">Current port</td><td id="ship_port"></td></tr>
		</table>

		<table id="port_table">
		<tr><td class="table_left">Port name</td><td id="port_name"></td></tr>
		<tr><td class="table_left">Services available</td><td id="port_services"></td></tr>
		</table>
	</div>
</div>

<div id="loading"></div>

<div id="mapcontainer">
	<iframe id="mapiframe" name="mapiframe" src="map/map.php"></iframe>
	<div class="pan" id="pannorthwest" onMouseOver="scrollNorthWest();" onMouseOut="scrollEnd();">&#x25e4;</div>
	<div class="pan" id="pannorth" onMouseOver="scrollNorth();" onMouseOut="scrollEnd();">&#x25b2;</div>
	<div class="pan" id="pannortheast" onMouseOver="scrollNorthEast();" onMouseOut="scrollEnd();">&#x25e5;</div>
	<div class="pan" id="panwest" onMouseOver="scrollEast();" onMouseOut="scrollEnd();">&#x25c0;</div>
	<div class="pan" id="paneast" onMouseOver="scrollWest();" onMouseOut="scrollEnd();">&#x25b6;</div>
	<div class="pan" id="pansouthwest" onMouseOver="scrollSouthWest();" onMouseOut="scrollEnd();">&#x25e3;</div>	
	<div class="pan" id="pansouth" onMouseOver="scrollSouth();" onMouseOut="scrollEnd();">&#x25bc;</div>
	<div class="pan" id="pansoutheast" onMouseOver="scrollSouthEast();" onMouseOut="scrollEnd();">&#x25e2;</div>	
</div> <!-- #mapcontainer -->

<div id="bottom">

	<div id="bottom_left">
		<div class="tabtitle" id="tabtitle0"><a href="javascript:showTab(0)">Messages</a></div>
		<div class="tabtitle" id="tabtitle1"><a href="javascript:showTab(1)">Inventory</a></div>
		<div class="tabtitle" id="tabtitle2"><a href="javascript:showTab(2)">Trades</a></div>
		<div class="tabtitle" id="tabtitle3"><a href="javascript:showTab(3)">Port Services</a></div>
		<div class="tabtitle" id="tabtitle4"><a href="javascript:showTab(4)">Battles</a></div>
		<div class="tabtitle" id="tabtitle5"><a href="javascript:showTab(5)">Leaderboard</a></div>
	</div>
	
	<div id="bottom_right">
		<div id="tab0" class="tab"><? include("messaging/inbox.php"); ?></div>
		<div id="tab1" class="tab"></div>
		<div id="tab2" class="tab"></div>
		<div id="tab3" class="tab"></div>
		<div id="tab4" class="tab"></div>
		<div id="tab5" class="tab"></div>
	</div>

</div>



</div>

</div> <!-- #content -->

</body>
</html>
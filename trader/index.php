#!/usr/bin/php
<? $gamename = "SEA QUEST"; ?>
<html>
<head>
<title><?=$gamename;?></title>
<link rel="stylesheet" type="text/css" href="cssjs/facebox/facebox.css" />
<script type="text/javascript" src="cssjs/jquery.js"></script>
<script type="text/javascript" src="cssjs/facebox/facebox.js"></script>
<script type="text/javascript">
var facebox_interval = 0;
$(document).ready(function() {
	$('a[rel*=facebox]').facebox();
});

function success(user,pass) {
	$(document).trigger("close.facebox");
	$.facebox("You have successfully registered. Please log in.");
	document.login.username.value = user;
	document.login.password.value = pass;
}
</script>
<style type="text/css">
body {
	font-family:sans-serif;
	text-align:center;
	background-color:#fff;
	background-image:url("images/back_map.jpg");
	background-repeat:repeat-x;
	background-position:top center; 
}

a:link, a:visited, a:active, a:hover {
	color:white;
	text-decoration:underline;
	outline:none;
}

a:hover {
	text-decoration:none;
}

#welcome, #login_form, #credits {
	position: absolute;
	left:50%;
	color:yellow;
	padding: 10px;
	background-color:rgb(73,79,100);
}

#welcome, #credits {
	width:500px;
	margin-left:-250px;
	
}

#welcome {
	text-align:justify;
	top:100px;
}

#credits {
	top:600px;
	text-align:center;
	font-size:10pt;
}

#login_form {
	top: 320px;
	width:260px;
	margin-left:-130px;
}

#login_form table {
	width:100%;
	color:yellow;
}

td p {
	margin:5px 0 10px 0;
}

.title {
	width:40%;
	/*text-align:right;*/
}

h1 {
	font-size:50pt;
}
</style>
</head>

<body>
<h1>&#x2620;</h1>

<div id="welcome">
<p>Welcome to <?=$gamename;?>...</p>
<p>Set in the modern day world, skill and intelligence is required to survive on sea, the quest for dominance is through trading and battling.</p>
<p>No allies and one common goal... all for one and one for all!</p>
</div>

<form name="login" action="authenticate.php" method="post">
<table id="login_form">
<tr><td colspan="2"><p>Please login:</p></td></tr>

<?php

// Error messages if any
if ($_GET["error"]) {
	echo "<tr><td colspan=\"2\"><p>";

	$err_code = $_GET["error"];
	switch($err_code) {
		case 1: echo "Internal database error, please re-enter details."; break;
		case 2: echo "Username is not recognised."; break;
		case 3: echo "Incorrect password."; break;
		case 4: echo "Please enter a username."; break;
	}

	echo "</p></td></tr>";
}

?>

<tr><td class="title">Username: </td><td><input type="text" value="<?=($_POST["username"]);?>" name="username" /></td></tr>
<tr><td class="title">Password: </td><td><input type="password" name="password" /></td></tr>
<tr><td colspan="2" style="text-align:center"><p><input type="submit" value="Login" /></p></td></tr>
<tr><td colspan="2"><p>Not registered?<br /><a href="register.php" rel="facebox">Register an account</a></p></td></tr>
</table>
</form>


<div id="credits">
<p>William Do (wd06), Daryl Harrison (dmh06), Hakan Ozbay (ho06)<br />2008</p>
</div>

</body>
</html>
<?
require_once("library/database.php");

if ($_POST['submit']) {

	$error = false;

	if (!$_POST['password']) {
		echo "You must enter a password.";
		$error = true;
	}

	else {
		if (!$_POST['confirmpassword']) {
			echo "You must confirm your password.";
			$error = true;
		}

		else if ($_POST['password'] != $_POST['confirmpassword']) {
			echo "The passwords you entered do not match.";
			$error = true;
		}

		else {
			if (!$_POST['username']) {
				echo "You must enter a username.";
				$error = true;
			}

			else {
				$query = "INSERT INTO Users VALUES('".$_POST['username']."','".$_POST['password']."',false,'2000-01-01 00:00:00')";
				$result = @pg_query($dbconn, $query);
				if (!$result) {
					echo "The username you have chosen has already been taken.";
					$error = true;
				}

				else {
					$randomport = rand(0,49);
					$query = "SELECT x,y,number FROM portlocation ORDER BY RANDOM() LIMIT 1";
					$result = pg_query($dbconn, $query);
					$row    = pg_fetch_assoc($result);

					$query = "INSERT INTO ship VALUES('".$_POST['username']."','".$_POST['shipname']."', 'Pirate', ".$row['x'].", ".$row['y'].", 1, 1000, ".$row['number'].", 100)";
					$result = @pg_query($dbconn, $query);
					if (!$result) {
						echo "The ship name you have chosen has already been taken.";
						$error = true;
					}

					$query = "INSERT INTO inventory VALUES('".$_POST['username']."','food', 250)";
					$result = pg_query($dbconn, $query);
					$query = "INSERT INTO inventory VALUES('".$_POST['username']."','weapons', 5)";
					$result = pg_query($dbconn, $query);

					$query    = "INSERT INTO Messages (sender,receiver,subject,message,sent) VALUES ('System Admin','".$_POST['username']."',' ','Welcome! Please refer to the <a href=\"#\">help pages</a> to get started.','".date('Y-m-j H:i:s')."')";
					$result   = pg_query($dbconn, $query);
				}
			}
		}
	}

	if (!$error) {
//		echo "You have successfully registered - please log in";
		// redirect after successful registration
		echo "Success";
		//header("Locatin: login.php");
	}
}

else {

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="cssjs/facebox/facebox.css" />
<script type="text/javascript" src="cssjs/jquery.js"></script>
<script type="text/javascript" src="cssjs/facebox/facebox.js"></script>
<script type="text/javascript">
	var facebox_interval = 0;
	$(document).ready(function() {
		$('a[rel*=facebox]').facebox();
	});

function registerMe(form) {
	form = document.regForm;
	$.post("register.php", {
		username: form.username.value,
		password: form.password.value,
		confirmpassword: form.confirmpassword.value,
		shipname: form.shipname.value,
		submit: 'true'
		},
		function(data) {
			if (data != "Success") {
				$("#error").show();
				$("#error").html(data);
			}
			else {
				parent.success(form.username.value, form.password.value);
			}
		}
	);
}
</script>
<style type="text/css">
#error {
	color:red;
	font-weight:bold;
	display:none;
}

#register_form td {
	padding:8px 0;
}
</style>
</head>

<form method="post" name="regForm">
<p id="error"></p>
<table id="register_form">
<tr><td colspan="2"><p>Please choose the following details:</p></td></tr>
<tr><td class="title">Username: </td><td><input type="text" value="<?=$_POST["username"];?>" name="username" /></td></tr>
<tr><td class="title">Password: </td><td><input type="password" name="password" /></td></tr>
<tr><td class="title">Confirm password: </td><td><input type="password" name="confirmpassword" /></td></tr>
<tr><td class="title">Ship name: </td><td><input type="text" name="shipname" /></td></tr>
<tr><td colspan="2" style="text-align:center"><p><input type="button" name="submit" onClick="registerMe(this);" value="Register" /></p></td></tr>
</table>
</form>

<?php

}

?>
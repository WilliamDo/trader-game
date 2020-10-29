var steps;	// Array to store steps

var prev_x;	// Stores position of boat when it was last centred on map
var prev_y;

var moving = false;
var ltr = "true";

var numberOfSteps = 0;

//var currentlyDisplayedUser;
//var currentlyDisplayedPort;

//var shipDisplay = true;

var ajaxManager1 = $.manageAjax({manageType: 'abortOld', maxReq: 1}); // prevents multiple updateShipPosition requests
var ajaxManager2 = $.manageAjax({manageType: 'abortOld', maxReq: 1}); // prevents multiple updateLastSeen requests
var ajaxManager3 = $.manageAjax({manageType: 'abortOld', maxReq: 1}); // prevents multiple getShipInfo and getPortInfo requests
var ajaxManager4 = $.manageAjax({manageType: 'abortOld', maxReq: 1, blockSameRequest:true}); // prevents multiple getShipPostition requests
var ajaxManager5 = $.manageAjax({manageType: 'abortOld', maxReq: 1, blockSameRequest:true}); // prevents multiple getShipPostition requests
var ajaxManager6 = $.manageAjax({manageType: 'abortOld', maxReq: 1}); // prevents multiple checkBattleRequest requests

$(document).ready(function(){
	$.ajax({
		type: "GET",
		url: "getShipPositions.php",
		data: "displayOwn=true",
		success: function(data) {
			$('html,body').animate({scrollLeft: current_x*50-350, scrollTop: current_y*50-250}, 5);
			$(data).find("ship").each(function(){
				var username = $("username", this).text();
				var shiptype = $("shiptype", this).text();
				var pos_x = $("x", this).text() * 50;
				var pos_y = $("y", this).text() * 50;

				if (username == user) {
					getShipInfo(username);	// display own ship stats
				}

				$("#ships").append("<div class=\"ship ship"+shiptype+"\" id=\""+username+"-ship\" style=\"left:"+pos_x+";top:"+pos_y+"\" onmouseover=\"getShipInfo('"+username+"')\"></div>");
			});
		},
		error: function() {
			alert("Unable to contact the server at this time. Please try again later.");
		}
	});

	$.ajax({
		type: "GET",
		url: "getPortPositions.php",
		success: function(data) {
			$(data).find("port").each(function(){
				var portnumber = $("portnumber", this).text();
				var portname = $("portname", this).text();
				var pos_x = $("x", this).text();
				var pos_y = $("y", this).text();

				$("#ports").append("<div class=\"port\" style=\"left:"+pos_x+";top:"+pos_y+";background:url(ports/"+portnumber+".png)\" onmouseover=\"getPortInfo("+portnumber+")\"></div>");
			});
			finishedLoading();
		},
		error: function() {
			alert("Unable to contact the server at this time. Please try again later.");
		}
	});

	setInterval(updatePositions, 500);
});


function getShipInfo(username) {
	if (moving == true) { // || (currentlyDisplayedUser == username && shipDisplay == true)
		return;
	}

	var url = baseurl+"map/getShipStats.php";

	ajaxManager3.add({
		type: "GET",
		url: url,
		data: "user="+username,
		success: function(data) {
			parent.getShipInfo(data);
		}/*,
		error: function() {
			alert("Unable to contact the server at this time. Please try again later.");
		}*/
	});
}

function getPortInfo(portnumber) {
	if (moving == true) { // || (currentlyDisplayedPort == portnumber && shipDisplay == false)
		return;
	}

	//currentlyDisplayedPort = portnumber;
	//shipDisplay = false;

	ajaxManager3.add({
		type: "GET",
		url: "getPortInfo.php",
		data: "port="+portnumber,
		success: function(data) {
			parent.getPortInfo(data);
		}/*,
		error: function() {
			alert("Unable to contact the server at this time. Please try again later.");
		}*/
	});
}

function updatePositions() {
	ajaxManager4.add({
		type: "GET",
		url: "getShipPositions.php",
		//timeout: 2900,
		success: function(data) {
			$(data).find("ship").each(function(){
				var username = $("username", this).text();
				var shiptype = $("shiptype", this).text();
				var pos_x = $("x", this).text() * 50;
				var pos_y = $("y", this).text() * 50;

				$("#"+username+"-ship").css({backgroundImage:"url(ships/"+shiptype+".png)"});
				$("#"+username+"-ship").css({left:pos_x, top:pos_y}, 500, "linear");
			});

			$(data).find("unread").each(function(){
				parent.unreadMessages();
			});
		}/*,
		error: function() {
			alert("Unable to contact the server at this time. Please try again later.");
		}*/
	});
}

function shipUpgrade(shipt) {
	shiptype = shipt;
	$("#"+user+"-ship").css({backgroundImage:"url(ships/"+shipt+".png)"});
}

var loading_timeout;
var battleReq_timeout;

function click(x, y) {
	if(moving == true) {
		return;
	}

	updateLastSeen();
	loading_timeout = setTimeout(loading,700);

	checkBattleRequest();
	battleReq_timeout = setInterval(checkBattleRequest,200);
	
	moving = true;

	$.ajax({
		type: "GET",
		url: "getPath.php",
		data: "sx="+current_x+"&sy="+current_y+"&tx="+x+"&ty="+y+"&ltr="+ltr,
		success: function(data) {
			clearTimeout(loading_timeout);

			$(data).find("steps").each(function(){
				numberOfSteps = $("size", this).text() * 1;
			});

			var i = 0;
			prev_x = current_x*50-350;
			prev_y = current_y*50-250;
			steps = [];

			$('body').animate({scrollLeft: prev_x, scrollTop: prev_y}, 500, function(){
				$(data).find("step").each(function(){
					steps[i] = {x: $("x", this).text(), y: $("y", this).text(), ltr: $("ltr", this).text()};
					i++;
				});
				move();
			});

			finishedLoading();
		},
		error: function() {
			alert("Unable to contact the server at this time. Please try again later.");
			moving = false;
			finishedLoading();
		}
	});
}

function move() {
	if (steps[0] == null || battleRequest == true) {
		if (battleRequest == true) {
			parent.showTab(4,true);
		}

		clearInterval(battleReq_timeout);
		moving = false;
		return;
	}

	var old_x = current_x;

	current_x = steps[0].x;
	current_y = steps[0].y;

	// Check which direction the ship is travelling in
	if(steps[0].ltr == "false") { // Moving right-to-left
		$("#"+user+"-ship").css({backgroundImage:"url(ships/"+shiptype+"-reverse.png)"});
		ltr = "false";
	}
	else { // Moving left-to-right
		$("#"+user+"-ship").css({backgroundImage:"url(ships/"+shiptype+".png)"});
		ltr = "true";
	}

	steps.shift();

	var pos_x = current_x*50-350;
	var pos_y = current_y*50-250;
	
	if(Math.abs(pos_x-prev_x) > 300 || Math.abs(pos_y-prev_y) > 200) { // If boat is nearing the edge of the map, re-centre it
		$('html,body').animate({scrollLeft: pos_x, scrollTop: pos_y}, 500, "linear");
		prev_x = pos_x;
		prev_y = pos_y;
	}

	$("#"+user+"-ship").animate({left:current_x*50, top:current_y*50}, 500, "linear", function(){
		$.ajax({
			type: "POST",
			url: "../controlpanel/usedFood.php",
			success: function(data) {
				parent.updateBalanceAndFood(true);
				move();
			}
		});

		var d = new Date();
		var t = d.getTime();

		//$.ajax({
		ajaxManager1.add({
			type: "POST",
			url: "updateShipPosition.php",
			data: "x="+current_x+"&y="+current_y/*,
			error: function() {
				alert("Unable to contact the server at this time. Please try again later.");
			}*/
		});
	});
}

function checkBattleRequest() {
	ajaxManager6.add({
		type: "GET",
		url: "../controlpanel/battleRequest.php",
		success: function(data) {
			if (data == 1) {
				battleRequest = true;
			}
			else {
				battleRequest = false;
			}
		}
	});
}

function updateLastSeen() {
	ajaxManager2.add({
		type: "POST",
		url: "../controlpanel/updateLastSeen.php"
	});
}

function stopMoving() {
	alert("stop");
	steps = [];
}

function loading() {
	parent.loading();
}

function finishedLoading() {
	parent.finishedLoading();
}

function locateMe() {
	var pos_x = current_x*50-350;
	var pos_y = current_y*50-250;
	$('body').animate({scrollLeft: pos_x, scrollTop: pos_y}, 500);
}
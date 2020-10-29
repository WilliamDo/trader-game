var interval0;
var interval1;
var interval2;
var interval3;
var interval4;
var interval5;

var messagesSelected = true;
var messagesNotified = false;

var facebox_interval;

$(document).ready(function() {
	$('a[rel*=facebox]').facebox();

	updateBalanceAndFood();
	
	getUsers();
	setInterval(getUsers, 120000);

	updateInbox();
	interval0 = setInterval(showInbox, 1000);
});


function showTab(n,flash) {
	updateLastSeen();

	for(i=0; i<6; i++) {
		if(i!=n) {
			$("#tab"+i).hide();
			$("#tabtitle"+i).css({backgroundColor:'transparent'});
			clearInterval(eval("interval"+i));
		}
	}

	if (n==0) {
		messagesSelected = true;
	}
	else {
		messagesSelected = false;
	}

	switch(n) {
		case 0: updateInbox(); interval0 = setInterval(updateInbox, 1000); break;
		case 1: updateTrades(); interval1 = setInterval(updateTrades, 1000); break;
		case 2: updatePosts(); interval2 = setInterval(updatePosts, 1000); break;
		case 3: updatePort(); interval3 = setInterval(updatePort, 1000); break;
		case 4: updateBattles(); interval4 = setInterval(updateBattles, 1000); break;
		case 5: updateLeaderboard(); interval5 = setInterval(updateLeaderboard, 1000); break;
	}

	$("#tab"+n).fadeIn("slow");
	$("#tabtitle"+n).css({backgroundColor:'#EFFBF5'});

	if (flash == true) {
		$("#bottom").colorBlend([{param:"background-color", fromColor:"#ccc", toColor:"orange", duration:1000, cycles:2}]);
	}
}

function updateInbox() {
	messagesNotified = false;
	$.post("messaging/readAll.php");
	showInbox();
}

function updateTrades() {
	$.get("includePage.php?page=trading/trades.php", function(data) {
		$("#tab1").html(data);
		$('#tab1 a[rel*=facebox]').facebox();
	});
}

function updatePosts() {
	$.get("includePage.php?page=trading/my_posts.php", function(data) {
		$("#tab2").html(data);
		$('#tab2 a[rel*=facebox]').facebox();
	});
}

function updatePort() {
	$.get("includePage.php?page=ports/dock.php", function(data) {
		$("#tab3").html(data);
		$('#tab3 a[rel*=facebox]').facebox();
	});
}

function updateBattles() {
	$.get("includePage.php?page=battles/battles.php", function(data) {
		$("#tab4").html(data);
		$('#tab4 a[rel*=facebox]').facebox();
	});
}

function updateLeaderboard() {
	$.get("controlpanel/leaderboard.php", function(data) {
		$("#tab5").html(data);
		$('#tab5 a[rel*=facebox]').facebox();
	});
}

function unreadMessages() {
	if (!messagesSelected && !messagesNotified) {
		$("#tabtitle0").colorBlend([{param:"background-color", fromColor:"orange", toColor:"#bbb", duration:2000, cycles:3}]);
		messagesNotified = true;

		updateBalanceAndFood(true);
		window.frames[0].getShipInfo(user,false);
	}
}


var ajaxManager2 = $.manageAjax({manageType: 'abortOld', maxReq: 1}); // prevents multiple updateLastSeen requests
var ajaxManager1 = $.manageAjax({manageType: 'abortOld', maxReq: 1}); // prevents multiple updateBalanceAndFood requests

// GET ONLINE/OFFLINE USERS

function getUsers() {
	$.ajax({
		type: "GET",
		url: "controlpanel/getUsers.php",
		timeout: 90000,
		success: function(data) {
			var i=0;

			$("#users_online").empty();
			$("#users_offline").empty();
			$("#alone").hide();

			$(data).find("timedout").each(function(){ // If timeout detected (after 15 mins)
				window.location = "logout.php";
			});

			$(data).find("user").each(function(){
				var username = $("username", this).text();
				var online = $("online", this).text();
				if (online == "t") {
					i++;
					$("#users_online").append("<li><a href=\"messaging/write_message.php?to="+username+"\" rel=\"facebox\">"+username+"</a></li>");
				}
				else {
					$("#users_offline").append("<li><a href=\"messaging/write_message.php?to="+username+"\" rel=\"facebox\">"+username+"</a></li>");
				}
			});

			$('#left_bottom a[rel*=facebox]').facebox();

			if(i==0) {	// no one else online
				$("#alone").show();
			}
		}/*,
		error: function() {
			alert("Unable to contact the server at this time. Please try again later.");
		}*/
	});
}

function getShipInfo(data) {
	$("#port_table").hide();
	$("#ship_table").hide();
	$("#ship_table").show() //fadeIn('slow');

	$("#stats_heading").html("Ship stats");

	$(data).find("stats").each(function(){
		var shipname = $("shipname", this).text();
		var owner = $("owner", this).text();
		var shiptype = $("shiptype", this).text();
		var crew = $("crew", this).text();
		var health = $("health", this).text();

		if (health < 0) {
			health = "0";
			
			if(owner == user) {
				gameOver(1);
			}
		}

		var x = $("x", this).text();
		var y = $("y", this).text();
		var port = $("port", this).text();
		$("#ship_name").html(shipname);
		$("#ship_owner").html(owner);
		$("#ship_type").html(shiptype);
		$("#ship_crew").html(crew);
		$("#ship_health").html(health);
		$("#ship_port").html(port);
	});
}

function getPortInfo(data) {
	$("#ship_table").hide();
	$("#port_table").hide();
	$("#port_table").show() //fadeIn('slow');

	$("#stats_heading").html("Port info");

	$(data).find("port").each(function(){
		var portname = $("name", this).text();
		var portservices = $("services", this).text();
		$("#port_name").html(portname);
		$("#port_services").html(portservices);
	});
}

function updateBalanceAndFood() {
	url = baseurl+"controlpanel/getBalanceAndFood.php";

	ajaxManager1.add({
		type: "GET",
		url: url,
		success: function(data) {
			$(data).find("info").each(function(){
				var balance = $("balance", this).text();
				var food = $("food", this).text() * 1;

				$("#balance_text").html(balance);
				
				if (food > 0) { $("#food_text").html(food); }
				else {
					$("#food_text").html("depleted");
					window.frames[0].steps = [];
					gameOver(0);
				}
			});
		}
	});
}

function updateFood(number) {
	if (number != 0) { $("#food_text").html(number); }
	else { $("#food_text").html("depleted"); }
}

function gameOver(reason) {
	$("#endOfGame").show();
	$("body").css({backgroundImage:"url('images/grey_layer.gif')"});

	if (reason == 0) { $("#food_msg").show(); }
	else { $("#damage_msg").show(); }
}

function showOffline() {
	$("#onlineofflinetoggle").html("<span style=\"font-size:8pt\">&#x25b2;</span> <a href=\"javascript:hideOffline()\">Hide offline</a>");
	$("#offline").fadeIn("slow");
}

function hideOffline() {
	$("#onlineofflinetoggle").html("<span style=\"font-size:8pt\">&#x25bc;</span> <a href=\"javascript:showOffline()\">Show offline</a>");
	$("#offline").fadeOut("slow");
}

function updateLastSeen() {
	ajaxManager2.add({
		type: "POST",
		url: "controlpanel/updateLastSeen.php"
	});
}

// MAP SCROLLING

var speed = 4;
var slow = 0.85;

function scrollEnd() {
	speedX = 0;
	speedY = 0;
}

function scrollNorth() {
	speedY = -speed;
}

function scrollSouth() {
	speedY = speed;

}

function scrollEast(which){
	speedX = -speed;
}

function scrollWest(which){
	speedX = speed;
}

function scrollNorthEast(which){
	speedX = speed*slow;
	speedY = -speed*slow;
}

function scrollSouthEast(which){
	speedX = speed*slow;
	speedY = speed*slow;
}

function scrollSouthWest(which){
	speedX = -speed*slow;
	speedY = speed*slow;
}

function scrollNorthWest(which){
	speedX = -speed*slow;
	speedY = -speed*slow;
}

// LOADING

function loading() {
	$("#loading").show();
}

function finishedLoading() {
	$("#loading").fadeOut('slow');
}

function locateMe() {
	window.frames[0].locateMe();
}
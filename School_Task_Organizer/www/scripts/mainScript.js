/*
var clock, clockInterval, clockButton, clockCookie;
var clockCookieCheckInterval;


function doClock(){
	clock = document.getElementById("clockElement");
	clockButton = document.getElementById("clockButton");
	
	if (clockButton.value == "off"){
		setCookie("clock", "on", 1);
		clockButton.innerHTML = "Disable Clock";
		clockButton.value = "on";
		clock.innerHTML = "Enabled";
		
		clockInterval = window.setInterval(syncClock, 450);
	}
	else{
		clearInterval(clockInterval);
		setCookie("clock", "", -1); //clear
		clockButton.innerHTML = "Enable Clock";
		clock.innerHTML = "";
		clockButton.value = "off";
	}
	
}

function syncClock(){
	var time = new Date();
	//clock.innerHTML = new String().concat(time.getHours(), ":", time.getMinutes(), ":",
	//										time.getSeconds());
	clock.innerHTML = time.toLocaleTimeString();
}

function openLoginForm(){
	document.getElementById("loginButton").style.display = "none";
	document.getElementById("loginForm").style.display = "block";
}

function closeLoginForm(){
	document.getElementById("loginButton").style.display = "inline";
	document.getElementById("loginForm").style.display = "none";
}


function checkClockCookie(){
	var cookies = document.cookie;
	document.getElementById('cookies').innerHTML = cookies;
	
	var nameOfCookie = "clock";
	clockCookie = cookies.substr(cookies.search(nameOfCookie)+nameOfCookie.length+1, 2);
	
	if(clockCookie == "on" && clockButton.value == "off"){
		doClock();
	}
}

function setCookie(name, value, expireDays){
	var date = new Date();
	date.setTime(date.getTime() + (expireDays*24*60*60*1000));
	var expires = "expires="+ date.toUTCString();
	document.cookie = name + "=" + value + ";" + expires + ";path=/";
}
*/



//document.write("Hello");


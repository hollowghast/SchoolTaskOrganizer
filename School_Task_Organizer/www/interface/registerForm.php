<!DOCTYPE html>
<html>
<head>
	<?php
		require("html_defaultHead.html");
	?>
	
	<title>Register</title>

</head>

<body>
	<form action="../db_modifications/registerNewUser.php" method="POST" >
		<label for="tfUsername">Username: *</label>
		<input type="text" id="tfUsername" name="username" placeholder="I'd suggest *your name*"
			cols="20" required >
		<br>
		<label for="tfPassword">Password: *</label>
		<input type="password" id="tfPassword" name="password" placeholder="CouldBe5omePassword"
			cols="20" required >
		<br>
		<label for="tfClass">Class:</label>
		<input type="text" id="tfClass" name="class" placeholder="ahif16" cols="10" >
		<br>
		<input type="submit" value="Register">
	</form>
</body>

</html>
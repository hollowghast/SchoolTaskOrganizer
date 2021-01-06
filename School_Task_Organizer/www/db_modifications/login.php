<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		require("../interface/html_defaultHead.html");
	?>
	<title>Login Page</title>
</head>

<body>
<?php
	$username = $_POST['username'];
	$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	
	if ($hashed_password == FALSE){
		die("Unable to hash password");
	}
	
	$pg_db_connect = "host=localhost port=5432 dbname=2020-School_Tasks_Organizer" . 
							" user=postgres password=postgres";
		$pg_db_connection = pg_connect($pg_db_connect);
		
		if (pg_connection_status($pg_db_connection) == PGSQL_CONNECTION_OK){
			$query = 
			"
			SELECT *
			FROM end_user
			WHERE username = $1 AND secretpassword = $2;
			";
			$result = pg_query_params($pg_db_connection, $query,
				array($username, $hashed_password));
			
			if($result != FALSE){
				$_SESSION['username'] = $username;
				$_SESSION['hashed_password'] = $hashed_password;
				
				setcookie('username', $username, time() + (60*60*24*3), '/', '', TRUE);
				setcookie('hashed_password', $hashed_password, time() + (60*60*24*3), '/', "", TRUE);
				
				print("<p>Login successful</p>");
				print("<p>Tab can be closed</p>");
			}
			
			pg_close($pg_db_connection);
		}
		else{
			print("Some problems with database.");
		}
?>
</body>

</html>
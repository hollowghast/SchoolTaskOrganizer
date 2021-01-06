<!DOCTYPE html>
<html>
<head>

</head>

<body>
	<?php
		$pg_db_connect = "host=localhost port=5432 dbname=2020-School_Tasks_Organizer" . 
							" user=postgres password=postgres";
		$pg_db_connection = pg_connect($pg_db_connect);
		
		if (pg_connection_status($pg_db_connection) == PGSQL_CONNECTION_OK){
			print("Connection successful");
			
			$query = 
			"INSERT INTO end_user(username, secretpassword, _ip_address)
			VALUES($1, $2, $3);
			";
			$result = pg_query_params($pg_db_connection, $query, array("User0", "pass4User0", "0.0.0.0"));
			print($result);
			
			pg_close($pg_db_connection);
		}
		else{
			print("Error while establishing a connection");
		}
	?>
</body>

</html>
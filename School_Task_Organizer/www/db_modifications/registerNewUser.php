<?php
	$username = $_POST['username'];
	$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$class = $_POST['class'];
	
	if ($hashed_password == FALSE){
		die("Unable to hash password");
	}
	
	$pg_db_connect = "host=localhost port=5432 dbname=2020-School_Tasks_Organizer" . 
							" user=postgres password=postgres";
		$pg_db_connection = pg_connect($pg_db_connect);
		
		if (pg_connection_status($pg_db_connection) == PGSQL_CONNECTION_OK){
			//look if class exists
			$query = 
			"
			SELECT classid
			FROM classofschool
			WHERE nameofclass = $1;
			";
			$classid = pg_query_params($pg_db_connection, $query,
				array($class));
			
				if($classid == FALSE){
					$query = 
					"
					SELECT nextval('sq_classid');
					";
					$classid = pg_query_params($pg_db_connection, $query,
						array());
						
					if ($classid != FALSE){
						$query = 
						"
						INSERT INTO classofschool
						VALUES($1, $2);
						";
						$result = pg_query_params($pg_db_connection, $query,
							array(pg_fetch_result($classid, 0, 0), $class));
					
						if ($result != FALSE){
							$query = 
							"
							INSERT INTO end_user
							VALUES($1, $2, $3, $4);
							";
							$result = pg_query_params($pg_db_connection, $query,
								array($username, $hashed_password,
									pg_fetch_result($classid, 0, 0), $_SERVER['REMOTE_ADDR']));
						}
					}
				}
				
			pg_close($pg_db_connection);
		}
		else{
			print("Some problems with database.");
		}
?>
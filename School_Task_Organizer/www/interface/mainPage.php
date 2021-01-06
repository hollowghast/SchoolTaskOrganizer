<?php
	//session_start();
?>

<!--
BUGS:

B1:
	when task-table is empty -> error // fixed
	
	

Worktasks:

T1:
Dynamisches Anzeigen der verfügbaren Lehrer, Fächer, ...



-->


<!DOCTYPE html>
<html>

<head>
	<!-- basics -->
	<?php
		require("html_defaultHead.html");
		
		$DB_OPERATIONS = array("INSERT", "UPDATE", "DELETE");
		
		$db_Task_AllColumns = array("taskid", "teacherid", "subject_abbrv", "description", "classid",
			"teamid", "importanceid", "due_date", "from_date", "links", "_added_date", 
			"_created_by", "files", "done", "due_time");
		$db_Task_EssentialColumns = array("description", "importanceid", "done");
		// key => value
		// table-column-name => name-for-ui
		$db_Task_QuickViewColumns = array("teacherid" => "Teacher", "subject_abbrv" => "Subject",
		"description" => "Description", "classid" => "Class",
			"importanceid" => "Importance", "due_date" => "Due",
			"due_time" => "", "links" => "Links", "done" => "Done");
		
		
		$pre_stmt_addTask = 
			"INSERT INTO Task(subject_abbrv, description, classid, importanceid, due_date, done, due_time, teacherid)
			VALUES($1, $2, $3, $4, TO_DATE($5, 'YYYY-MM-DD'), $6, $7, $8);";
		$pre_stmt_updateTask =
			"UPDATE task
			SET	done = NOT done
			WHERE taskid = $1;";
		$pre_stmt_removeTask =
			"DELETE FROM Task
			WHERE taskid = $1;";
			
		$pg_db_connect = "host=localhost port=5432 dbname=2020-School_Tasks_Organizer" . 
							" user=postgres password=postgres";
		$pg_db_connection = pg_connect($pg_db_connect);
		
		if (pg_connection_status($pg_db_connection) != PGSQL_CONNECTION_OK){
			die("Connection to Database not successful");
		}
		
		pg_prepare($pg_db_connection, "InsertTask", $pre_stmt_addTask);
		pg_prepare($pg_db_connection, "ToggleDoneOfTask", $pre_stmt_updateTask);
		pg_prepare($pg_db_connection, "RemoveTask", $pre_stmt_removeTask);
	?>
	
	<title>All Tasks</title>
	<!-- <meta http-equiv="refresh" content="30"> -->

	<script>
		/*window.onload = function() {
			//var element = document.createElement("script");
			//element.src = "../scripts/mainScript.js";
			//document.body.appendChild(element);
			
			//clockCookieCheckInterval = setInterval(checkClockCookie(), 2000);
		};*/
	</script>
</head>

<body class="container-fluid">
	<!-- check for recursive calls (task-add, ...) -->
	<?php
		if(!empty($_POST)){
			//if it's not empty there's a task to add | or a task to update
			print_r($_POST);
			
			$op = $_POST["form"];
			
			if($op == $DB_OPERATIONS[0]){ //INSERT
				pg_execute($pg_db_connection, "InsertTask",
						array($_POST['subject'], $_POST['description'], $_POST['class'], $_POST['importance'],
						$_POST['due'], (empty($_POST['done'])?"false":"true"), $_POST['due_time'],
						$_POST['teacher']));
			}
			else if($op == $DB_OPERATIONS[1]){ //UPDATE
				pg_execute($pg_db_connection, "ToggleDoneOfTask", array($_POST["btnDone"]));
			}
			else if($op == $DB_OPERATIONS[2]){ //DELETE
				//DELETE task WHERE x;
				pg_execute($pg_db_connection, "RemoveTask", array($_POST["btnRemove"]));
			}
		}
	?>

	<p>Beta 20.5.25</p>
	<!--<a href="registerForm.php">Register</a>
	<br><hr>
	-->
	
	<!-- <button onclick="openLoginForm()" id="loginButton">Login</button>
	<form method="POST" action="../db_modifications/login.php" id="loginForm" style="display:none;">
		<label for="tfUsername">Username:</label>
		<input type="textfield" cols="20" id="tfUsername" name="username"
			placeholder="Your Username" required />
		<br>
		<label>Password:</label>
		<input type="password" cols="20" id="tfPassword" name="password"
			placeholder="Your Password" required />
		<br>
	-->
		<!-- Delegates to login.php where data gets compared with DB -->
	<!--	<input type="submit" value="Send" />
		
		<input type="button" onclick="closeLoginForm()" value="Collapse" />
		<input type="reset" value="Reset" />
	</form>
	
	<button onclick="doClock()" id="clockButton" value="off">Enable Clock</button>
	<div id="clockElement"></div>
	<div id="cookies"></div>
	-->
	
	<h3>Tasks</h3>
	
	<!-- first try -->
	<?php
		//get User (registered or logged in) -> Cookies/Session
			//if guest -> show all
		//Open DB Connection
			//filter relevant tasks
		
		//if user logged in
			//get filtered tasks
		
		//else get all tasks with default user
		/*
		$pg_db_connect = "host=localhost port=5432 dbname=2020-School_Tasks_Organizer" . 
							" user=postgres password=postgres";
		$pg_db_connection = pg_connect($pg_db_connect);
		
		if (pg_connection_status($pg_db_connection) == PGSQL_CONNECTION_OK){
			//Show all tasks
			
			$query = 
			"
			SELECT * FROM Task;
			";
			$result = pg_query_params($pg_db_connection, $query, array());
			print_r(pg_fetch_row($result));
			
			pg_close($pg_db_connection);
		}
		else{
			print("Some problems with database.");
		}
		*/
		
	?>
	
	<!-- Test -->
	<?php
		//phpinfo();
	?>
	
	<!-- current stable version -->
	<?php
		//show all tasks of db
		
		//but only if table is not empty
		
			$query = 
			"
			SELECT *
			FROM Task
			ORDER BY done, due_date, subject_abbrv;
			";
			$result = pg_query_params($pg_db_connection, $query, array());
			//print_r(pg_fetch_row($result));
			//print_r(pg_fetch_all($result));
			$rows = pg_fetch_all($result);
			
			if(!empty($rows)){
				
			//QuickView
			echo("<table class=\"table table-bordered\">");
				echo("<tr>");
					foreach($db_Task_QuickViewColumns as $col => $name){
						echo("<th>");
							echo($name);
						echo("</th>");
					}
				echo("</tr>");
				
				$i = 0;
				$now_array = date_parse(date("d.m.Y"));
				$due_array = array();
				for(; $i < count($rows); $i++){	
				//print_r($rows[$i]);
					echo("<tr style=\"background-color: ");
					$due_array = date_parse($rows[$i]["due_date"]);
					
					if($rows[$i]["done"] == 't'){
						echo "#FFF";
					}
					else if($due_array["year"] <= $now_array["year"]){
						if($due_array["month"] <= $now_array["month"]){
							if($due_array["day"] < $now_array["day"]){
								echo "#A00";
							}
						}
					}
					//echo (((strtotime($rows[$i]["due_date"]) >= date("Y-m-d"))?"#FFF":"#A00"));
					echo (";\" >");
					foreach($db_Task_QuickViewColumns as $col => $name){
							echo("<td>");		
								if($col == "description"){
									echo("<textarea cols=100 rows=4 disabled>");
										echo($rows[$i][$col]);
									echo("</textarea>");
								}
								else if($col == "links"){
									$links = explode(",", $rows[$i][$col]);
									$j = 1;
									foreach($links as $link){
										if(!empty($link)){
											echo("<a href='$link' >Angabe/Abgabe $j</a><br>");
										}
										$j++;
									}
								}
								else if($col == "done"){
									//TODO - update db when clicked (POST request mit prepared statment?)
									echo("<form action=\"\" method=\"POST\" >");
										echo("<input type=\"hidden\" name=\"form\" value=\"" . 
											$DB_OPERATIONS[1] . "\" />");
										echo("<button type=\"submit\" name=\"btnDone\" class=\"btnDone\" id=\"btnDone" . $i . "\"" .
											" value=\"" . $rows[$i]["taskid"] . "\">");
										
										echo(($rows[$i][$col] == "f")?"&#9898;":"&#10003;");
										
										echo("</button>");
									echo("</form>");
								}
								else if($col == "teacherid"){
									echo($rows[$i][$col] . " - needs a select");
								}
								else{
									echo($rows[$i][$col]);
								}
							echo("</td>");
					}
					echo ("<td>");
					echo("<form action=\"\" method=\"POST\" >");
										echo("<input type=\"hidden\" name=\"form\" value=\"" . 
											$DB_OPERATIONS[2] . "\" />");
										echo("<button type=\"submit\" name=\"btnRemove\"
											class=\"btnRemove\" id=\"btnRemove" . $i . "\"" .
											" value=\"" . $rows[$i]["taskid"] . "\">");
										
										echo("Remove");
										
										echo("</button>");
									echo("</form>");
					echo("</td>");
					echo("</tr>");
				}
			echo("</table>");
			}
			
		//in order to show urgent first
		//categorized (due, open, scheduled)
		//qick view including subject, description, teacher, due date, links
	?>
	
	<hr>
	<button onclick="showTaskInput()"><h3>Add new Task</h3></button>
	
	<form class="form-control" id="taskInputForm" style="display:none;"
	 action="<?php echo /*$_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_URI']*/ '' ?>" method="POST">
						<input type="hidden" name="form" value="<?php echo $DB_OPERATIONS[0] ?>" />
						
						<div class="form-group">
							<label for="teacher">Teacher:</label>
							<select name="teacher">
								<?php
									$query = "
										SELECT *
										FROM teacher;
									";
									$result = pg_query_params($pg_db_connection, $query, array());
									$rows = pg_fetch_all($result);
									
									if(!empty($rows)){
										foreach($rows as $teacher){
											echo("<option value=\"" . $teacher["teacherid"] . "\">" .
												$teacher["abbreviation"] . "</option>");
										}
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="subject">Subject:</label>
							<select name="subject">
								<?php
									$query = "
										SELECT *
										FROM subject;
									";
									$result = pg_query_params($pg_db_connection, $query, array());
									$rows = pg_fetch_all($result);
									
									if(!empty($rows)){
										foreach($rows as $subject){
											echo("<option value=\"" . $subject["abbreviation"] . "\">" .
												$subject["abbreviation"] . "</option>");
										}
									}
								?>
							</select>
							<br>
						</div>
						<div class="form-group">
							<label for="description">Description:</label>
							<textarea name="description" cols=50 rows=4 placeholder="Description"></textarea>
							<br>
						</div>
						<div class="form-group">
							<label for="class">Class:</label>
							<select name="class">
								<?php
									$query = "
										SELECT *
										FROM classofschool;
									";
									$result = pg_query_params($pg_db_connection, $query, array());
									$rows = pg_fetch_all($result);
									
									if(!empty($rows)){
										foreach($rows as $class){
											echo("<option value=\"" . $class["classid"] . "\">" .
												$class["nameofclass"] . "</option>");
										}
									}
								?>
							</select>
						
							<label for="team">Team:</label>
							<select name="team">
								<?php
									$query = "
										SELECT *
										FROM team;
									";
									$result = pg_query_params($pg_db_connection, $query, array());
									$rows = pg_fetch_all($result);
									
									if(!empty($rows)){
										foreach($rows as $team){
											echo("<option value=\"" . $team["teamid"] . "\">" .
												$team["nameofteam"] . "</option>");
										}
									}
								?>
							</select>
							<br>
						</div>
						<div class="form-group">
							<label for="importance">Importance:</label>
							<select name="importance">
								<?php
									$query = "
										SELECT *
										FROM importance;
									";
									$result = pg_query_params($pg_db_connection, $query, array());
									$rows = pg_fetch_all($result);
									
									if(!empty($rows)){
										foreach($rows as $importance){
											echo("<option value=\"" . $importance["importanceid"] . "\">" .
												$importance["title"] . "</option>");
										}
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="from">From:</label>
							<input type="date" name="from" max="<?php echo date("Y-m-d"); ?>"
								value="<?php echo date("Y-m-d"); ?>" />
							
							
							<label for="due">Due:</label>
							<input type="date" name="due" min="<?php echo date("Y-m-d"); ?>"
								value="<?php echo date("Y-m-d"); ?>" />
							<input type="time" name="due_time" value="08:00" />
							<br>
						</div>
						<div class="form-group">
							<label for="links">Links:</label>
							<textarea name="links" cols=50 rows=4></textarea>
							
							<label for="files">Files:</label>
							<input type="file" name="files" multiple>
							<br>
						
						</div>
						<div class="form-group">
							<input type="checkbox" name="done" /><cvalue>done</cvalue>
							<br>
						</div>
		<input type="submit" value="Add" >
	</form>
	
	
	
	
	<script src="../scripts/mainScript.js"></script>
	<script src="../scripts/taskSpecificFunctions.js"></script>

<?php
	pg_close($pg_db_connection);
?>
</body>

</html>
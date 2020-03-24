<?php
/* ---------------------------------------------------------------------------
 * filename    : fr_api.php
 * author      : George Corser, gcorser@gmail.com
 * description : Returns JSON object of all the names in the jeepney_persons file OR 
 *               (if id param is set) only one person's name
 * ---------------------------------------------------------------------------
 */
	include 'database.php';
	
	$pdo = Database::connect();
	if($_GET['id']) 
		$sql = "SELECT * from jeepney_persons WHERE id=" . $_GET['id']; 
	else
		$sql = "SELECT * from jeepney_persons";

	$arr = array();
	foreach ($pdo->query($sql) as $row) {
	
		array_push($arr, $row['lname'] . ", ". $row['fname']);
		
	}
	Database::disconnect();

	echo '{"names":' . json_encode($arr) . '}';
?>
				
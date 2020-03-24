<?php 

session_start();
if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');   // go to login page
	exit;
}
$id = $_GET['id']; // for MyAssignments
$sessionid = $_SESSION['fr_person_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body>
    <div class="container">
		<?php 
		//Calling the gets logo
			include 'functions.php';
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3><?php if($id) echo 'My'; ?>Shifts</h3>
		</div>
		
		<div class="row">
			<p>Each shift is 4 hours.</p>
			<a href="fr_event_create.php" class="btn btn-success"> Create Destination </a>
			<a href="fr_assign_create.php" class="btn btn-success">Assign customer to a driver </a>
			<a href="fr_persons.php" class="btn btn-success">List of all persons </a>
			
			<p>
			<br>
				<?php if($_SESSION['fr_person_title']=='Administrator')
					echo '<a href="fr_assign_create.php" class="btn btn-primary">Add Assignment</a>';
				?>
				<a href="logout.php" class="btn btn-primary">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['fr_person_title']=='Administrator')
					echo '<a href="fr_persons.php">Volunteers</a> &nbsp;';
				?>
				<a href="fr_events.php" class="btn btn-primary">Shifts</a> &nbsp;
				<?php if($_SESSION['fr_person_title']=='Administrator')
					echo '<a href="fr_assignments.php">AllShifts</a>&nbsp;';
				?>
				<a href="fr_assignments.php?id=<?php echo $sessionid; ?>" class="btn btn-primary">MyShifts</a>&nbsp;
				<?php if($_SESSION['fr_person_title']=='Drivers')
					echo '<a href="fr_events.php" class="btn btn-primary">Drivers</a>';
				?>
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Date of Pickup</th>
						<th>Time of Pickup</th>
						<th>Pickup Location</th>
						<th>Destination</th>
						<th>Name of Person</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					include 'database.php';
					//include 'functions.php';
					$pdo = Database::connect();
					
					if($id) 
						$sql = "SELECT * FROM fr_assignments 
						LEFT JOIN jeepney_persons ON jeepney_persons.id = fr_assignments.assign_per_id 
						LEFT JOIN fr_fares ON fr_fares.id = fr_assignments.assign_event_id
						WHERE jeepney_persons.id = $id 
						ORDER BY fare_date ASC, fare_time ASC, lname ASC, lname ASC;";
					else
						$sql = "SELECT * FROM fr_assignments 
						LEFT JOIN jeepney_persons ON jeepney_persons.id = fr_assignments.assign_per_id 
						LEFT JOIN fr_fares ON fr_fares.id = fr_assignments.assign_event_id
						ORDER BY fare_date ASC, fare_time ASC, lname ASC, lname ASC;";

					foreach ($pdo->query($sql) as $row) {
						echo '<tr>';
						echo '<td>'. Functions::dayMonthDate($row['fare_date']) . '</td>';
						echo '<td>'. Functions::timeAmPm($row['fare_time']) . '</td>';
						echo '<td>'. $row['fare_location'] . '</td>';
						echo '<td>'. $row['fare_description'] . '</td>';
						echo '<td>'. $row['lname'] . ', ' . $row['fname'] . '</td>';
						echo '<td width=250>';
						# use $row[0] because there are 3 fields called "id"
						echo '<a class="btn" href="fr_assign_read.php?id='.$row[0].'">Details</a>';
						if ($_SESSION['fr_person_title']=='Administrator' )
							echo '&nbsp;<a class="btn btn-success" href="fr_assign_update.php?id='.$row[0].'">Update</a>';
						if ($_SESSION['fr_person_title']=='Administrator' 
							|| $_SESSION['fr_person_id']==$row['assign_per_id'])
							echo '&nbsp;<a class="btn btn-danger" href="fr_assign_delete.php?id='.$row[0].'">Delete</a>';
						if($_SESSION["fr_person_id"] == $row['assign_per_id']) 		echo " &nbsp;&nbsp;Me";
						echo '</td>';
						echo '</tr>';
					}
					Database::disconnect();
				?>
				</tbody>
			</table>
    	</div>

    </div> <!-- end div: class="container" -->
	
</body>
</html>
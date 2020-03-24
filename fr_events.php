<?php
/* ---------------------------------------------------------------------------
 * filename    : fr_events.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays a list of events (table: fr_events)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["fr_person_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['fr_person_id'];
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body style="background-image: url('jeepneyrideshare1.png'); background-repeat: no-repeat;  background-position: right top">


    <div class="container">
		  <?php 
			//gets logo
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3>Travel Info</h3>
		</div>
		
		<div class="row">
			<p>Each final distination takes 1 hour</p>
			<a href="fr_event_create.php" class="btn btn-success"> Create Destination </a>
			<a href="fr_assign_create.php" class="btn btn-success">Assign customer to a driver </a>
			<a href="fr_persons.php" class="btn btn-success">List of all persons </a>
			
			<p>
				<?php if($_SESSION['fr_person_title']=='Administrator')
					echo '<a href="fr_event_create.php" class="btn btn-primary">Add Shift</a>';
				?>
				<br>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['fr_person_title']=='Administrator')
					echo '<a href="fr_persons.php">Volunteers</a> &nbsp;';
				?>
				<a href="fr_events.php">Shifts</a> &nbsp;
				<?php if($_SESSION['fr_person_title']=='Administrator')
					echo '<a href="fr_assignments.php">AllShifts</a>&nbsp;';
				?>
				<a href="fr_assignments.php?id=<?php echo $sessionid; ?>">MyShifts</a>&nbsp;
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Date of Pickup</th>
						<th>Time of Pickup</th>
						<th>Pickup Location</th>
						<th>Pickup Description</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `fr_fares`.*, SUM(case when assign_per_id ='. $_SESSION['fr_person_id'] .' then 1 else 0 end) AS sumAssigns, COUNT(`fr_assignments`.assign_event_id) AS countAssigns FROM `fr_fares` LEFT OUTER JOIN `fr_assignments` ON (`fr_fares`.id=`fr_assignments`.assign_event_id) GROUP BY `fr_fares`.id ORDER BY `fr_fares`.fare_date ASC, `fr_fares`.fare_time ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>'. Functions::dayMonthDate($row['fare_date']) . '</td>';
							echo '<td>'. Functions::timeAmPm($row['fare_time']) . '</td>';
							echo '<td>'. $row['fare_location'] . '</td>';
							if ($row['countAssigns']==0)
								echo '<td>'. $row['fare_description'] . ' - UNASSIGNED </td>';
							else
								echo '<td>'. $row['fare_description'] . ' (' . $row['countAssigns']. ' riders)' . '</td>';
							//echo '<td width=250>';
							echo '<td>';
							echo '<a class="btn" href="fr_event_read.php?id='.$row['id'].'">Details</a> &nbsp;';
							if ($_SESSION['fr_person_title']=='Driver' )
								echo '<a class="btn btn-primary" href="fr_event_read.php?id='.$row['id'].'">Driver</a> &nbsp;';
							if ($_SESSION['fr_person_title']=='Rider' )
								echo '<a class="btn btn-primary" href="fr_event_read.php?id='.$row['id'].'">Rider</a> &nbsp;';
							if ($_SESSION['fr_person_title']=='Administrator' )
								echo '<a class="btn btn-success" href="fr_event_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							if ($_SESSION['fr_person_title']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="fr_event_delete.php?id='.$row['id'].'">Delete</a>';
							if($row['sumAssigns']==1) 
								echo " &nbsp;&nbsp;Me";
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
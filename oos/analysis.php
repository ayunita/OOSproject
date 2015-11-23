<?php
	session_start();
	if($_SESSION['role'] != 's'){
		header("Location: restriction.html");
		exit();
	}

?>

<html>
<script src="jquery-1.11.3.js" type="text/javascript"></script>
<script src="style.js" type="text/javascript"></script>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Data Analysis</title>
</head>

<?php
	include ("PHPconnectionDB.php");
	//establish connection
	$conn=connect();
?>
<html>
<body>
	<h1>Data Analysis Page</h1>
	<button onclick="location.href = 'logout.php';">Logout</button>
	<h2>Data Analysis</h2>
	<div id="data_analysis_btn">Data Analysis</div>
	<div id="data_analysis_panel">
		<form action="" method="post">
			<fieldset>
				<legend>Scalar Information:</legend>
				Sensor id: <input type="text" name="sensor_analysis_id"> <br /> <br />
				Location: <input type = "text" name = "location_analysis"> <br /> <br />
				Date: <input type="text" name="date_analysis">DD/MM/YYYY HH:MM:SS <br /> <br />
				TO Date: <input type="text" name="date_analysis">DD/MM/YYYY HH:MM:SS <br /> <br />
				<button type="reset">Reset</button>
				<input type="submit" name="upload_analysis" value="Submit">
				<button type = "submit" name = "yearly" value = "yearly">Yearly</button>
				<button type = "submit" name = "quarterly" value = "quarterly">Quarterly</button>
				<button type = "submit" name = "monthly" value = "monthly">Monthly</button>
				<button type = "submit" name = "weekly" value = "weekly">Weekly</button>
				<button type = "submit" name = "daily" value = "daily">Daily</button>
			</fieldset>
		</form>
	</div>
</body>
</html>
<?php
// -----------------------------------------------------------------------
// FACT TABLE
// Use this block of code every time 
         // Drop fact table (if any)
         echo "Drop Fact Table <br>";
			$query = "DROP Table fact_table";
			$result = oci_parse($conn, $query);
			$res  = oci_execute($result);
			if ($res) {
				oci_commit($conn);
			} else {
				$e = oci_error($result);
				echo "Error Selecting data ".$e['message']."<br>";
			}
			oci_free_statement($result);
			
			// Create fact_table to speed up OLAP queries for data_cube/roll-up
			// Contains data relevant to the queries
			// sensor id, location, and time(according to the values of column date), 
			// and report the corresponding average, min, and max values of a sensor.
			echo "Create Fact Table<br>";
			
			$query = "Create Table fact_table AS SELECT sensors.sensor_id, sensors.location, scalar_data.date_created, scalar_data.value ".
			         "FROM sensors, scalar_data ".
			         "WHERE sensors.sensor_id = scalar_data.sensor_id ";
			$result = oci_parse($conn, $query);
			$res  = oci_execute($result);
			if ($res) {
				oci_commit($conn);
			} else {
				$e = oci_error($result);
				echo "Error Selecting data " . $e['message'] . "<br>";
			}
			oci_free_statement($result);
// -----------------------------------------------------------------------
$query = "INSERT INTO fact_table VALUES (2, 'A', TO_DATE('01-NOV-15', 'DD/MM/YY'), 20)";
			$result = oci_parse($conn, $query);
			$res  = oci_execute($result);
			if ($res) {
				oci_commit($conn);
			} else {
				$e = oci_error($result);
				echo "Error Selecting data " . $e['message'] . "<br>";
			}
			oci_free_statement($result);
			
			
			
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['yearly'])) {
      echo "Year <br>";
      $date_format ="TO_CHAR(date_created, 'YYYY') as year";
		$date_rollup ="TO_CHAR(date_created, 'YYYY')";
		$date_group = "year";
    }
    elseif(isset($_POST['quarterly'])) {
    	echo "Quarterly <br>";
    }
    elseif(isset($_POST['monthly'])) {
      echo "Month <br>";
      $date_format = "TO_CHAR(date_created, 'YYYY') as year, TO_CHAR(date_created, 'Mon') as month";
		$date_rollup = "TO_CHAR(date_created, 'YYYY'), TO_CHAR(date_created, 'Mon')";
		$date_group = "month";
    }
    elseif(isset($_POST['weekly'])) {
    	echo "Weekly <br>";
    	$date_format = "TO_CHAR(date_created, 'YYYY') as year, TO_CHAR(date_created, 'WW') as week";
		$date_rollup = "TO_CHAR(date_created, 'YYYY'), TO_CHAR(date_created, 'WW')";
		$date_group = "week";
    }
    elseif(isset($_POST['daily'])) {
    	echo "Daily <br>";
		$date_format = "TO_CHAR(date_created, 'YYYY') as year, TO_CHAR(date_created, 'Mon') as month, TO_CHAR(date_created, 'DD') as day";			
		$date_rollup = "TO_CHAR(date_created, 'YYYY'), TO_CHAR(date_created, 'Mon'), TO_CHAR(date_created, 'DD')";
		$date_group = "day";
    }
    
    /*
      sensor id, location, and time(according to the values of column date), and report the corresponding average, min, and max values of a sensor.
    */
    /*
    $query = "SELECT scalar_data.date_created, avg(scalar_data.value), max(scalar_data.value), min(scalar_data.value) ".
             "FROM scalar_data, sensors ".
             "WHERE scalar_data.sensor_id = sensors.sensor_id ".
             "GROUP BY ROLLUP(year from scalar_data.date_created)";
    */
    /*
    // Fact Table
    			$query = "Create Table fact_table AS SELECT sensors.sensor_id, sensors.location, scalar_data.date_created, scalar_data.value ".
			         "FROM sensors, scalar_data ".
			         "WHERE sensors.sensor_id = scalar_data.sensor_id ";
    */
    $query = "SELECT sensor_id, location, date_created, avg(value), max(value), min(value) From fact_table GROUP BY ROLLUP(year from date_created)";
    
    $query = "SELECT sensor_id, location, date_created, avg(value), max(value), min(value) ".
             "FROM fact_table ".
             "GROUP BY sensor_id, location, date_created ".
             "ORDER BY sensor_id, location, date_created ";
             
    				$stid = oci_parse($conn, $query);
				$res  = oci_execute($stid);
				if ($res) {
					oci_commit($conn);
				} else {
					$e = oci_error($stid);
					echo "Error Selecting data " . $e['message'] . "<br>";
				}
				$number_columns = oci_num_fields($stid);
				echo "Number Col -> ".$number_columns."<br>";
				for ($i = 1; $i <= $number_columns; ++$i) {
					echo "| ".strtolower(oci_field_name($stid, $i))." ";
				}
				echo " |";
				echo "<br>";
				while ($row = oci_fetch_array($stid, OCI_NUM)) {
					for ($i = 0; $i < $number_columns; $i += 6) {
						echo "----------------------------<br>".
						     "sensor_id -> ".$row[$i]."<br>".
						     "location -> ".$row[$i+1]."<br>".
						     "date -> ".$row[$i+2]."<br>".
						     "avg -> ".$row[$i+3]."<br>".
						     "max -> ".$row[$i+4]."<br>".
						     "min -> ".$row[$i+5]."<br>";
					}
		
				}
	
	echo "<br><br>------------<br><br>";
	$query = "SELECT avg(value) FROM fact_table";
	    				$stid = oci_parse($conn, $query);
				$res  = oci_execute($stid);
				if ($res) {
					oci_commit($conn);
				} else {
					$e = oci_error($stid);
					echo "Error Selecting data " . $e['message'] . "<br>";
				}
				
				$number_columns = oci_num_fields($stid);
				echo "Number Col -> ".$number_columns."<br>";
				for ($i = 1; $i <= $number_columns; ++$i) {
					echo "| ".strtolower(oci_field_name($stid, $i))." ";
				}
				echo " |";
				echo "<br>";
				
				while ($row = oci_fetch_array($stid, OCI_NUM)) {
					for ($i = 0; $i < $number_columns; $i += 1) {
						echo "----------------------------<br>".
						     "test avg -> ".$row[$i]."<br>";
					}
				}
				
		
				
	
				oci_close($conn);
    /*
    
     "SELECT EXTRACT(year FROM date_created), avg(sd.value), max(sd.value), min(sd.value) ".
                "FROM scalar_data sd, subscriptions sc ".
                "WHERE sd.sensor_id = '"+Sensor+"' ".
                "GROUP BY EXTRACT(year FROM date_created) ".
                "ORDER BY EXTRACT(year FROM date_created)";
                */
 
    
    
    /*
    				$query = "SELECT "
				.$patient_format.$test_format.$date_format
				." ,COUNT(*) as images "
				."FROM fact_table "
				."GROUP BY ROLLUP ("
				.$patient_format.$test_format.$date_rollup
				.") ";
	
				$stid = oci_parse($conn, $query);
				$res  = oci_execute($stid);
				if ($res) {
					oci_commit($conn);
				} else {
					$e = oci_error($stid);
					echo "Error Selecting data " . $e['message'] . "<br>";
				}
				*/
}
/*
if($_GET){
    if(isset($_GET['year'])){
        echo "You clicked Year <br>";
    }elseif(isset($_GET['month'])){
        echo "You clicked Month <br>";
    }
}
*/
/*
// -----------------------------------------------------------------------
// FACT TABLE
// Use this block of code every time 
         // Drop fact table (if any)
			$query = "DROP VIEW fact_table";
			$result = oci_parse($conn, $query);
			$res  = oci_execute($result);
			if ($res) {
				oci_commit($conn);
			} else {
				$e = oci_error($result);
				echo "Error Selecting data ".$e['message']."<br>";
			}
			oci_free_statement($result);
			
			// Create fact_table to speed up OLAP queries for data_cube/roll-up
			// Contains data relevant to the queries
			// sensor id, location, and time(according to the values of column date), 
			// and report the corresponding average, min, and max values of a sensor.
			
			$query = "Create View fact_table AS SELECT sensors.sensor_id, sensors.location, scalar_data.date_created, scalar_data.value ".
			         "FROM sensors, scalar_data "
			         "WHERE sensors.sensor_id = scalar_data.sensor_id ";
			$result = oci_parse($conn, $query);
			$res  = oci_execute($result);
			if ($res) {
				oci_commit($conn);
			} else {
				$e = oci_error($result);
				echo "Error Selecting data " . $e['message'] . "<br>";
			}
			oci_free_statement($result);
// -----------------------------------------------------------------------
https://github.com/mswillia123/CMPUT391/blob/master/ris/dataAnalysisModule.php
				switch ($period) {
					case "month":
						$date_format = "TO_CHAR(date_created, 'YYYY') as year, TO_CHAR(date_created, 'Mon') as month";
						$date_rollup = "TO_CHAR(date_created, 'YYYY'), TO_CHAR(date_created, 'Mon')";
						break;
					case "week":
						//if month is required along with week, use the following instead
						//$date_format = "TO_CHAR(R.date_created, 'YYYY') as year, TO_CHAR(R.date_created, 'Mon') as month, TO_CHAR(R.date_created, 'W') as week";
						//$date_rollup = "TO_CHAR(R.date_created, 'YYYY'), TO_CHAR(R.date_created, 'Mon'), TO_CHAR(R.date_created, 'W')";
						$date_format = "TO_CHAR(date_created, 'YYYY') as year, TO_CHAR(date_created, 'WW') as week";
						$date_rollup = "TO_CHAR(date_created, 'YYYY'), TO_CHAR(date_created, 'WW')";
						break;
					case "year":
						$date_format ="TO_CHAR(date_created, 'YYYY') as year";
						$date_rollup ="TO_CHAR(date_created, 'YYYY')";
						break;
					default: //as per Dr. Yuan, day/all should not be an option. This code can be uncommented if day/all report is required
						//$date_format = "TO_CHAR(date_created, 'YYYY') as year, TO_CHAR(date_created, 'Mon') as month, TO_CHAR(date_created, 'DD') as day";			
						//$date_rollup = "TO_CHAR(date_created, 'YYYY'), TO_CHAR(date_created, 'Mon'), TO_CHAR(date_created, 'DD')";
				}
			
			
			oci_close($conn);
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
			$query = "DROP Table fact_table1";
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
			
			$query = "CREATE TABLE fact_table1 AS SELECT * ".
			         "FROM ( ".
			         "SELECT s.sensor_id, s.location, ".
			         "TO_CHAR(d.date_created, 'YYYY') as year, ".
                  "TO_CHAR(date_created, 'Q') as quarter, ".
                  "TO_CHAR(date_created, 'Mon') as month, ".
                  "TO_CHAR(date_created, 'W') as week, ".
                  "TO_CHAR(date_created, 'DD') as day, ".
                  "d.value ".
                  "FROM sensors s, scalar_data d ".
                  "WHERE s.sensor_id = d.sensor_id ".
                  "AND s.sensor_type = 's' )";
			         
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['yearly'])) {
      echo "Yearly <br>";
		$query = "SELECT * ".
		         "FROM ( ".
               "SELECT sensor_id, location, year, AVG(value), MIN(value), MAX(value) ".
               "FROM fact_table1 ".
               "GROUP BY CUBE(sensor_id, location, year) ) ".
               "WHERE sensor_id IS NOT NULL ".
               "AND location IS NOT NULL ".
               "AND year IS NOT NULL";
      $type = 1;
      $n = 6;
    }
    elseif(isset($_POST['quarterly'])) {
    	echo "Quarterly <br>";
    	$query = "SELECT * ".
               "FROM ( ".
               "SELECT sensor_id, location, quarter, year, AVG(value), MIN(value), MAX(value) ".
               "FROM fact_table1 ".
               "GROUP BY CUBE(sensor_id, location, quarter, year) ) ".
               "WHERE sensor_id IS NOT NULL ".
               "AND location IS NOT NULL ".
               "AND quarter IS NOT NULL ".
               "AND year IS NOT NULL";
      $type = 2;
      $n = 7;
    }
    elseif(isset($_POST['monthly'])) {
      echo "Monthly <br>";
      $query = "SELECT * ".
               "FROM ( ".
               "SELECT sensor_id, location, month, year, AVG(value), MIN(value), MAX(value) ".
               "FROM fact_table1 ".
               "GROUP BY CUBE(sensor_id, location, month, year) ) ".
               "WHERE sensor_id IS NOT NULL ".
               "AND location IS NOT NULL ".
               "AND month IS NOT NULL ".
               "AND year IS NOT NULL";
      $type = 3;
      $n = 7;
    }
    elseif(isset($_POST['weekly'])) {
    	echo "Weekly <br>";
    	$query = "SELECT * ".
    	         "FROM ( ".
    	         "SELECT sensor_id, location, week, month, year, AVG(value), MIN(value), MAX(value) ".
    	         "FROM fact_table1 ".
    	         "GROUP BY CUBE(sensor_id, location, week, month, year) ) ".
    	         "WHERE sensor_id IS NOT NULL ".
    	         "AND location IS NOT NULL ".
    	         "AND week IS NOT NULL ".
    	         "AND month IS NOT NULL ".
    	         "AND year IS NOT NULL";
    	$type = 4;
    	$n = 8;
    }
    elseif(isset($_POST['daily'])) {
    	echo "Daily <br>";
    	$query = "SELECT * ".
    	         "FROM ( ".
    	         "SELECT sensor_id, location, day, month, year, AVG(value), MIN(value), MAX(value) ".
    	         "FROM fact_table1 ".
    	         "GROUP BY CUBE(sensor_id, location, day, month, year) ) ".
    	         "WHERE sensor_id IS NOT NULL ".
    	         "AND location IS NOT NULL ".
    	         "AND day IS NOT NULL ".
    	         "AND month IS NOT NULL ".
    	         "AND year IS NOT NULL";
    	$type = 5;
    	$n = 8;
    }
    
    			$stid = oci_parse($conn, $query);
				$res  = oci_execute($stid);
				if ($res) {
					oci_commit($conn);
				} else {
					$e = oci_error($stid);
					echo "Error Selecting data " . $e['message'] . "<br>";
				}
				$number_columns = oci_num_fields($stid);
				while ($row = oci_fetch_array($stid, OCI_NUM)) {
					for ($i = 0; $i < $number_columns; $i += $type + 5) {
                 echo "----------------------------------<br>".
                      "Sensor ID	-> ".$row[$i]."<br>".
                      "Location -> ".$row[$i+1]."<br>";
                      if ($type == 1) {
                      	// Yearly
                        echo "Year -> ".$row[$i+2]."<br>".
                             "Average -> ".$row[$i+3]."<br>".
                             "Minimum -> ".$row[$i+4]."<br>".
                             "Maximum -> ".$row[$i+5]."<br>";
                      } elseif($type == 2) {
                      	// Quarterly
                      	echo "Quarter -> ".$row[$i+2]."<br>".
                      	     "Year -> ".$row[$i+3]."<br>".
                      	     "Average -> ".$row[$i+4]."<br>".
                      	     "Minimum -> ".$row[$i+5]."<br>".
                      	     "Maximum -> ".$row[$i+6]."<br>";
                      } elseif($type == 3) {
                      	// Monthly
                      	echo "Month -> ".$row[$i+2]."<br>".
                      	     "Year -> ".$row[$i+3]."<br>".
                      	     "Average -> ".$row[$i+4]."<br>".
                      	     "Minimum -> ".$row[$i+5]."<br>".
                      	     "Maximum -> ".$row[$i+6]."<br>";
                      } elseif($type == 4) {
                      	// Weekly
                      	echo "Week -> ".$row[$i+2]."<br>".
                      	     "Month -> ".$row[$i+3]."<br>".
                      	     "Year -> ".$row[$i+4]."<br>".
                      	     "Average -> ".$row[$i+5]."<br>".
                      	     "Minimum -> ".$row[$i+6]."<br>".
                      	     "Maximum -> ".$row[$i+7]."<br>";
                      } elseif($type == 5) {
                      	// Daily
                      	echo "Day -> ".$row[$i+2]."<br>".
                      	     "Month -> ".$row[$i+3]."<br>".
                      	     "Year -> ".$row[$i+4]."<br>".
                      	     "Average -> ".$row[$i+5]."<br>".
                      	     "Minimum -> ".$row[$i+6]."<br>".
                      	     "Maximum -> ".$row[$i+7]."<br>";
                      }
					}
				}
				oci_close($conn);
}
?>
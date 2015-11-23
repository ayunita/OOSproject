<?php
    function generateReport($conn){
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
                  "TO_CHAR(date_created, 'D') as day, ".
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
                       "AND year IS NOT NULL ".
                       "ORDER BY sensor_id, year";
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
                       "AND year IS NOT NULL ".
                       "ORDER BY sensor_id, quarter, year";
              $type = 2;
              $n = 7;
            }
            elseif(isset($_POST['monthly'])) {
              echo "Monthly <br>";
              $query = "SELECT * ".
                       "FROM ( ".
                       "SELECT sensor_id, location, month, quarter, year, AVG(value), MIN(value), MAX(value) ".
                       "FROM fact_table1 ".
                       "GROUP BY CUBE(sensor_id, location, month, quarter, year) ) ".
                       "WHERE sensor_id IS NOT NULL ".
                       "AND location IS NOT NULL ".
                       "AND month IS NOT NULL ".
                       "AND quarter IS NOT NULL ".
                       "AND year IS NOT NULL ".
                       "ORDER BY sensor_id, month, quarter, year";
              $type = 3;
              $n = 8;
            }
            elseif(isset($_POST['weekly'])) {
                echo "Weekly <br>";
                $query = "SELECT * ".
                         "FROM ( ".
                         "SELECT sensor_id, location, week, month, quarter, year, AVG(value), MIN(value), MAX(value) ".
                         "FROM fact_table1 ".
                         "GROUP BY CUBE(sensor_id, location, week, month, quarter, year) ) ".
                         "WHERE sensor_id IS NOT NULL ".
                         "AND location IS NOT NULL ".
                         "AND week IS NOT NULL ".
                         "AND month IS NOT NULL ".
                         "AND quarter IS NOT NULL ".
                         "AND year IS NOT NULL ".
                         "ORDER BY sensor_id, week, month, quarter, year";
                $type = 4;
                $n = 9;
            }
            elseif(isset($_POST['daily'])) {
                echo "Daily <br>";
                $query = "SELECT * ".
                         "FROM ( ".
                         "SELECT sensor_id, location, day, week, month, quarter, year, AVG(value), MIN(value), MAX(value) ".
                         "FROM fact_table1 ".
                         "GROUP BY CUBE(sensor_id, location, day, week, month, quarter, year) ) ".
                         "WHERE sensor_id IS NOT NULL ".
                         "AND location IS NOT NULL ".
                         "AND day IS NOT NULL ".
                         "AND week IS NOT NULL ".
                         "AND month IS NOT NULL ".
                         "AND quarter IS NOT NULL ".
                         "AND year IS NOT NULL ".
                         "ORDER BY sensor_id, day, week, month, quarter, year";
                $type = 5;
                $n = 10;
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
                        echo '<table border="1">';
                        
                        // format in table
                        if ($type == 1) {
                            echo "<tr><td>Sensor id</td><td>Location</td><td>Year</td><td>AVG</td><td>MIN</td><td>MAX</td></tr>";
                        } elseif($type == 2) {
                            echo "<tr><td>Sensor id</td><td>Location</td><td>Quarter</td><td>Year</td><td>AVG</td><td>MIN</td><td>MAX</td></tr>";
                        } elseif($type == 3) {
                            echo "<tr><td>Sensor id</td><td>Location</td><td>Month</td><td>Quarter</td><td>Year</td><td>AVG</td><td>MIN</td><td>MAX</td></tr>";
                        } elseif($type == 4) {
                            echo "<tr><td>Sensor id</td><td>Location</td><td>Week</td><td>Month</td><td>Quarter</td><td>Year</td><td>AVG</td><td>MIN</td><td>MAX</td></tr>";
                        } elseif($type == 5) {
                            echo "<tr><td>Sensor id</td><td>Location</td><td>Day</td><td>Week</td><td>Month</td><td>Quarter</td><td>Year</td><td>AVG</td><td>MIN</td><td>MAX</td></tr>";
                        }
                        while ($row = oci_fetch_array($stid, OCI_NUM)) {
                            for ($i = 0; $i < $number_columns; $i += $type + 5) {
                              if ($type == 1) {
                                // Yearly
                                echo "<tr><td>".$row[$i]."</td><td>".$row[$i+1]."</td>";
                                echo "<td>".$row[$i+2]."</td>".
                                     "<td>".$row[$i+3]."</td>".
                                     "<td>".$row[$i+4]."</td>".
                                     "<td>".$row[$i+5]."</td></tr>";
                              } elseif($type == 2) {
                                // Quarterly
                                echo "<tr><td>".$row[$i]."</td><td>".$row[$i+1]."</td>";
                                echo "<td>".$row[$i+2]."</td>".
                                     "<td>".$row[$i+3]."</td>".
                                     "<td>".$row[$i+4]."</td>".
                                     "<td>".$row[$i+5]."</td>".
                                     "<td>".$row[$i+6]."</td></tr>";
                              } elseif($type == 3) {
                                // Monthly
                                echo "<tr><td>".$row[$i]."</td><td>".$row[$i+1]."</td>";
                                echo "<td>".$row[$i+2]."</td>".
                                     "<td>".$row[$i+3]."</td>".
                                     "<td>".$row[$i+4]."</td>".
                                     "<td>".$row[$i+5]."</td>".
                                     "<td>".$row[$i+6]."</td>".
                                     "<td>".$row[$i+7]."</td></tr>";
                              } elseif($type == 4) {
                                // Weekly
                                echo "<tr><td>".$row[$i]."</td><td>".$row[$i+1]."</td>";
                                echo "<td>".$row[$i+2]."</td>".
                                     "<td>".$row[$i+3]."</td>".
                                     "<td>".$row[$i+4]."</td>".
                                     "<td>".$row[$i+5]."</td>".
                                     "<td>".$row[$i+6]."</td>".
                                     "<td>".$row[$i+7]."</td>".
                                     "<td>".$row[$i+8]."</td></tr>";
                              } elseif($type == 5) {
                                // Daily
                                echo "<tr><td>".$row[$i]."</td><td>".$row[$i+1]."</td>";
                                echo "<td>".$row[$i+2]."</td>".
                                     "<td>".$row[$i+3]."</td>".
                                     "<td>".$row[$i+4]."</td>".
                                     "<td>".$row[$i+5]."</td>".
                                     "<td>".$row[$i+6]."</td>".
                                     "<td>".$row[$i+7]."</td>".
                                     "<td>".$row[$i+8]."</td>".
                                     "<td>".$row[$i+9]."</td></tr>";
                              }
                            }
                        }
                        oci_close($conn);
                        echo '</table>';
        }
    }

?>
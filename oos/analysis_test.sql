/*
 * Create FACT TABLE from running CUBE
 * it removes the null column
 */

CREATE TABLE fact_table
AS SELECT *
FROM 
(
  SELECT *
  FROM
  (
    SELECT s.sensor_id, s.location, d.date_created, 
    AVG(d.value) as AVG, MAX(d.value) as MAX, MIN(d.value) as MIN
    FROM sensors s, scalar_data d
    WHERE s.sensor_id = d.sensor_id
    AND s.sensor_type = 's'
    GROUP BY CUBE(s.sensor_id, s.location, d.date_created)
  )
  WHERE sensor_id is not null
  AND location is not null
  AND date_created is not null
)
;


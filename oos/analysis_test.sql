/*
 * fact table
 */
CREATE TABLE fact_table1
AS SELECT *
FROM 
(
  SELECT s.sensor_id, s.location, TO_CHAR(d.date_created, 'YYYY') as year, 
        TO_CHAR(date_created, 'Mon') as month, 
        TO_CHAR(date_created, 'DD') as day,
        d.value
  FROM sensors s, scalar_data d
  WHERE s.sensor_id = d.sensor_id
  AND s.sensor_type = 's'
)
;

/*
 * cube yearly
 */

SELECT sensor_id, location, year, AVG(value), MIN(value), MAX(value)
FROM fact_table1
GROUP BY CUBE(sensor_id, location, year)
;

/*
 * cube monthly
 */

SELECT sensor_id, location, month, year, AVG(value), MIN(value), MAX(value)
FROM fact_table1
GROUP BY CUBE(sensor_id, location, month, year)
;

/*
 * cube daily
 */

SELECT sensor_id, location, day, month, year, AVG(value), MIN(value), MAX(value)
FROM fact_table1
GROUP BY CUBE(sensor_id, location, day, month, year)
;
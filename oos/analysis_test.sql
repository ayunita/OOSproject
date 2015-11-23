SELECT s.sensor_id, s.location, 
          TO_CHAR(d.date_created, 'YYYY') as year,
          TO_CHAR(date_created, 'Q') as quarter,
          TO_CHAR(date_created, 'Mon') as month, 
          TO_CHAR(date_created, 'W') as week, 
          TO_CHAR(date_created, 'DD') as day,
          d.value
FROM sensors s, scalar_data d
WHERE s.sensor_id = d.sensor_id
AND s.sensor_type = 's'
;

/*
 * fact table
 */
CREATE TABLE fact_table1
AS SELECT *
FROM 
(
  SELECT s.sensor_id, s.location, 
          TO_CHAR(d.date_created, 'YYYY') as year,
          TO_CHAR(date_created, 'Q') as quarter,
          TO_CHAR(date_created, 'Mon') as month, 
          TO_CHAR(date_created, 'W') as week, 
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
 * cube quarterly
 */

SELECT sensor_id, location, quarter, year, AVG(value), MIN(value), MAX(value)
FROM fact_table1
GROUP BY CUBE(sensor_id, location, quarter, year)
;


/*
 * cube monthly
 */

SELECT sensor_id, location, month, year, AVG(value), MIN(value), MAX(value)
FROM fact_table1
GROUP BY CUBE(sensor_id, location, month, year)
;

/*
 * cube weekly
 */

SELECT sensor_id, location, week, month, year, AVG(value), MIN(value), MAX(value)
FROM fact_table1
GROUP BY CUBE(sensor_id, location, week, month, year)
;

/*
 * cube daily
 */

SELECT sensor_id, location, day, month, year, AVG(value), MIN(value), MAX(value)
FROM fact_table1
GROUP BY CUBE(sensor_id, location, day, month, year)
;


/*
 * FORMATTED
 * cube yearly
 */
SELECT *
FROM
(
  SELECT sensor_id, location, year, AVG(value), MIN(value), MAX(value)
  FROM fact_table1
  GROUP BY CUBE(sensor_id, location, year)
)
WHERE sensor_id IS NOT NULL
AND location IS NOT NULL
AND year IS NOT NULL
;

/*
 * FORMATTED
 * cube quarter
 */
SELECT *
FROM
(
  SELECT sensor_id, location, quarter, year, AVG(value), MIN(value), MAX(value)
  FROM fact_table1
  GROUP BY CUBE(sensor_id, location, quarter, year)
)
WHERE sensor_id IS NOT NULL
AND location IS NOT NULL
AND quarter IS NOT NULL
AND year IS NOT NULL
;

/*
 * FORMATTED
 * cube quarter
 */
SELECT *
FROM
(
  SELECT sensor_id, location, month, year, AVG(value), MIN(value), MAX(value)
  FROM fact_table1
  GROUP BY CUBE(sensor_id, location, month, year)
)
WHERE sensor_id IS NOT NULL
AND location IS NOT NULL
AND month IS NOT NULL
AND year IS NOT NULL
;

/*
 * FORMATTED
 * cube weekly
 */
SELECT *
FROM
(
  SELECT sensor_id, location, week, month, year, AVG(value), MIN(value), MAX(value)
  FROM fact_table1
  GROUP BY CUBE(sensor_id, location, week, month, year)
)
WHERE sensor_id IS NOT NULL
AND location IS NOT NULL
AND week IS NOT NULL
AND month IS NOT NULL
AND year IS NOT NULL
;

/*
 * FORMATTED
 * cube daily 
 */
SELECT *
FROM
(
  SELECT sensor_id, location, day, month, year, AVG(value), MIN(value), MAX(value)
  FROM fact_table1
  GROUP BY CUBE(sensor_id, location, day, month, year)
)
WHERE sensor_id IS NOT NULL
AND location IS NOT NULL
AND day IS NOT NULL
AND month IS NOT NULL
AND year IS NOT NULL
;
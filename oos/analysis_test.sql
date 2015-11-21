/*
 * 1. AVERAGE / MIN / MAX
 * return the nice format of cube
 * note: date_created group by this format DD/MM/YYYY HH:MM:SS
 * sensor id | location | date created | avg (value)
 * --------------------------------------------------
 * ......... | .........| ............ | ...........
 * --------------------------------------------------
 *                      | avg(total)   | ...........
 * 
 */

SELECT * 
FROM (
        SELECT s.sensor_id, s.location, d.date_created, AVG(d.value)
        FROM sensors s, scalar_data d
        WHERE s.sensor_id = d.sensor_id
        AND s.sensor_id = 1
        GROUP BY CUBE(s.sensor_id, s.location, d.date_created)
      )
WHERE sensor_id IS NULL
AND location IS NULL
AND date_created IS NULL

UNION ALL

SELECT * 
FROM (
        SELECT s.sensor_id, s.location, d.date_created, AVG(d.value)
        FROM sensors s, scalar_data d
        WHERE s.sensor_id = d.sensor_id
        AND s.sensor_id = 1
        GROUP BY CUBE(s.sensor_id, s.location, d.date_created)
      )
WHERE sensor_id IS NOT NULL
AND location IS NOT NULL
AND date_created IS NOT NULL
;

/* 
 * The raw format for #1    
 */
SELECT s.sensor_id, s.location, d.date_created, AVG(d.value)
FROM sensors s, scalar_data d
WHERE s.sensor_id = d.sensor_id
AND s.sensor_id = 1
GROUP BY CUBE(s.sensor_id, s.location, d.date_created)
;


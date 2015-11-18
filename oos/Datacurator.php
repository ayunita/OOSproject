<?php
        /**
         * Describe this method
         */
        function checkSensorId($conn, $sensorId) {
            if (!$sensorId) {
                  echo "Please provide seneor_id <br>";
                  return 1;
              }
              // check if sensor_id is in database
              $sql = "SELECT * FROM sensors WHERE sensor_id = ".$sensorId;
            $stid = oci_parse($conn, $sql);
            oci_execute($stid);
            $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
            if (!$row) {
                echo "Sensor id = ".$sensorId." not found <br >";
                return 1;
            }
            return 0;
        }
        
        
        /*
         *  taken from:
         *  http://php.net/manual/en/function.base64-encode.php
         *  http://php.net/manual/en/function.oci-new-descriptor.php
         *  (C) 2015 The PHP Group modified by Yunita
         */

         /**
          * Describe this method
          */
        function uploadImage($conn, $sensor_id, $date_created, $description) {
 
            $image_id = generateId($conn, "images");
            if ($image_id == 0) {
                return;
            }
            
            $image2 = file_get_contents($_FILES['file_image']['tmp_name']);
            $image2tmp = resizeImage($_FILES['file_image']);
           
            $image2Thumbnail = file_get_contents($image2tmp['tmp_name']);
            // encode the stream
            $image = base64_encode($image2);
            $imageThumbnail = base64_encode($image2Thumbnail);
            
            $sql = "INSERT INTO images (image_id, sensor_id, date_created, description, thumbnail, recoreded_data)
                      VALUES(".$image_id.", ".$sensor_id.", TO_DATE('".$date_created."', 'DD/MM/YYYY'), '".$description."', empty_blob(), empty_blob())
                       RETURNING thumbnail, recoreded_data INTO :thumbnail, :recoreded_data";
        
            $result = oci_parse($conn, $sql);
            $recoreded_dataBlob = oci_new_descriptor($conn, OCI_D_LOB);
            $thumbnailBlob = oci_new_descriptor($conn, OCI_D_LOB);
            
            oci_bind_by_name($result, ":recoreded_data", $recoreded_dataBlob, -1, OCI_B_BLOB);
            oci_bind_by_name($result, ":thumbnail", $thumbnailBlob, -1, OCI_B_BLOB);
            
            $res = oci_execute($result, OCI_DEFAULT) or die ("Unable to execute query");
    
            
            if (($recoreded_dataBlob->save($image)) && ($thumbnailBlob->save($imageThumbnail))) {
                oci_commit($conn);
            } else {
                oci_rollback($conn);
            }
            oci_free_statement($result);
            $recoreded_dataBlob->free();
            $thumbnailBlob->free();
            echo "New image is added with image_id ->".$image_id."<br>";   
        }
    
        // taken from http://php.net/manual/en/function.imagecopyresized.php
        // (C) 2015 The PHP Group modified by Yishou
        
        /**
         * Describe this method, where do you get it? (reference please)
         */
        function resizeImage($source){
            list($w, $h, $type) = getimagesize($source['tmp_name']);
            
            // Retrieve old image
            $src_img = imagecreatefromjpeg($source['tmp_name']); 
            if ($w > 100 || $h > 100) {
                $scale =  100 / (($h > $w) ? $h : $w);
                $nw = $w * $scale;
                $nh = $h * $scale;
                $dest_img = imagecreatetruecolor($nw, $nh);
                imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $nw, $nh, $w, $h);
                // overwrite file with new thumbnail
                imagejpeg($dest_img, $source['tmp_name']); 
                // Clean up
                imagedestroy($src_img);
                imagedestroy($dest_img);
            }

            return $source;
        }
 
        /**
         * 
         */
        function generateId ($conn, $tableName) {
           // generate an id for the corresponding table
             $i = 0;
             while (true) {
                 if ($i == 9999) {
                     // reached the limit
                    echo "Unable to generate another id, sorry <br>";
                    return 0;
                 }
               // if an id is already taken then generate a new one
               $id = rand(1000, 9999);
               if ($tableName == "images") {
                   $sql = "SELECT * FROM IMAGES WHERE image_id = ".$id;
               } elseif($tableName == "audio_recordings") {
                    $sql = "SELECT * FROM AUDIO_RECORDINGS WHERE recording_id = ".$id;
               } elseif($tableName == "scalar_data") {
                    $sql = "SELECT * FROM SCALAR_DATA WHERE id = ".$id;
               }
               $stid = oci_parse($conn, $sql);
               oci_execute($stid);
               $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
               if (!$row) {
                   // id is not taken
                   break;
               } else {
                   // id is taken, generate another one
                   $i++;
                   continue;
               }
           }
           return $id;
       }
    
?>
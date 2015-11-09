<!--
This is an example of uploading image to oracle database.
to see the result you should change the sql query (for id) in blob.php and load.php

This example using this table:
CREATE TABLE test (id NUMBER, image BLOB);
-->

<form action="blob.php" method="post" enctype="multipart/form-data">
Upload file: <input type="file" name="file" /><br />
<input type="submit" name="upload" value="Upload" /> - <input type="reset" value="Reset" />
</form>
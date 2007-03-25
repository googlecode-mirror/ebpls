<?php

require_once "./includes/eBPLS_header.php";

echo "<br><br>";
echo "<center>";
echo "<form enctype='multipart/form-data' action='uploading.php' method='post'>";
echo " <input type='hidden' name='MAX_FILE_SIZE' value='120000' />";
echo " LGU Logo upload file: <input name='userfile' type='file' />";
echo "<br><br>";
echo " <input type='submit' value='Upload Logo' />";
echo "</form>";
echo "</center>";

require_once "./includes/eBPLS_footer.php";
?> 
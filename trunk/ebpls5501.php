<?php

include'setup/setting.php';
global $link2db;
echo $link2db."fdsfsdfdsfsd";
$link2db = mysql_connect("$thDbHost","$thDbUser","$thDbUKey");
//echo "$thDbHost","$thDbUser","$thDbUKey";
if (! $link2db) die("Couldn't connect to MySQL");
mysql_select_db($thDbName, $link2db) or die("Couldn't open $db: ".mysql_error());

?>

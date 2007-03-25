<?php

require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                 
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
                                                                                                 
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

if ($permit_type=="Motorized") {
$getit = SelectMultiTable($dbtype,$dbLink,"$owner a, $permittable c",
		"concat(a.owner_last_name, ', ', a.owner_first_name, ' ', 
		a.owner_middle_name), c.pin, c.$appdate", 
		"where a.owner_id = c.owner_id and
		c.active = 1 and a.owner_id = $owner_id ");
$checkife = @mysql_num_rows($getit);
if ($checkife <= 0) {
$getit = SelectMultiTable($dbtype,$dbLink,"$owner a",
		"concat(a.owner_last_name, ', ', a.owner_first_name, ' ', 
		a.owner_middle_name)", 
		"where a.owner_id = $owner_id ");
}
} else {
$getit = SelectMultiTable($dbtype,$dbLink,"$owner a, $permittable c",
		"concat(a.owner_last_name, ', ', a.owner_first_name, ' ', 
		a.owner_middle_name), c.pin, c.$appdate", 
		"where a.owner_id = c.owner_id and
		c.active = 1 and a.owner_id = $owner_id ");
}
$getd = FetchRow($dbtype,$getit);

?>

<table border="1" width="40%" id="table1">
	<tr>
		<td>
		<p align="center">Acknowledgement of Application Receipt</td>
	</tr>
</table>
<table border="1" width="40%" id="table2">
	<tr>
		<td width="10%">Date of Application</td>
		<td><?php echo $getd[2]; ?></td>
	</tr>
	<tr>
		<td width="90%">Owner's Name</td>
		<td><?php echo $getd[0]; ?></td>
	</tr>
	<tr>
		<td width="138">Access Pin</td>
		<td><?php echo $getd[1]; ?></td>
	</tr>
	<tr>
		<td width="138">Prepared By</td>
		<td><?php echo $usern; ?></td>
	</tr>
</table>



<table border=1 align=left width = 40%>

<tr>
<td>
<?php

include "includes/mtop.php";                                                                                                 
?>
</td></tr>

</table>






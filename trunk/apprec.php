<?php

require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                 
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
                                                                                                 
//--- get connection from DB
global $ThUserData;
$permit_type = 'Business';
require "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);




$getit = SelectMultiTable($dbtype,$dbLink,"$owner a, ebpls_business_enterprise b 
			",
			"concat(a.owner_last_name, ', ', a.owner_first_name, ' ', 
			a.owner_middle_name), b.business_name, a.owner_first_name", 
			"where a.owner_id = b.owner_id and
			a.owner_id = $owner_id and b.business_id = $business_id");

$getd = FetchRow($dbtype,$getit);


$getpass = mysql_query("select * from authen where owner_id='$owner_id'");
$getit = mysql_fetch_assoc($getpass);
$getus = $getit['username'];
$passwd = $getd[2]."usergen";

?>
<table border=1 align=left width = 80%>
<tr>
<td>
Acknowledgement of Application Receipt
</td>
</tr>
<tr>
<td>Date Printed</td><td><?php echo date('M d, Y'); ?></td>
</tr>
<tr>
<td>Owner's Name</td><td><?php echo $getd[0]; ?></td>
</tr>
<tr>
<td>Business Name</td><td><?php echo $getd[1]; ?></td>
</tr>
<tr>
<td>Access Pin</td><td><?php echo $pin; ?></td>
</tr>
<tr>
<td>Website Username/Password</td><td><?php echo $getus."/".$passwd; ?></td>
</tr>
<tr><td></td></tr>
<tr><td>Line of Business</td><td>Capital Investment</td><td>Gross Sales</td><tr>
<?php
                                                                                                 
$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature",
			"bus_nature, cap_inv, last_yr",
 			"where owner_id=$owner_id and business_id=$business_id
 			and active = 1");
 while ($getit = FetchRow($dbtype,$getnat)){
?>
<tr>
<td><?php echo $getit[0]; ?></td>
<td><?php echo $getit[1]; ?></td>
<td><?php echo $getit[2]; ?></td>
</tr>
<?php
}
?>
<tr></tr><tr></tr>
<tr><td>Prepared By:<?php echo $usern; ?></td></tr>
</table>






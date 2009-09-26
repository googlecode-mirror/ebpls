<?php
require_once "includes/variables.php";
//get permit format
$permitform = mysql_query("select * from permit_templates where permit_type='$permit_type'") or die("permit template");
$permitget = mysql_fetch_row($permitform);
print "
<html>
<form method = post action='index.php?part=4&class_type=Permits&itemID_321&permit_type=";
echo $permit_type;
print "'>
<body>
<div align= center><br>
Republic of the Philippines<br>
<br>";
                                                                                                                            
echo $permitget[1];
                                                                                                                            
print "
<br>
<br>
<b>";
echo $permitget[2];

print "<br>
<br>
</b>
                                                                                                                            
<table cellpadding=2 cellspacing=2 border=0 align="; echo $permitget[19]; print ">
<td>
Permit No.:
</td>
<td>";
//get code
$getperm = mysql_query("select $incode from $permittable where owner_id=$owner_id") or die("die");
$getp = mysql_fetch_row($getperm);
echo $getp[0];
print "
</td>
</table>
<br><br>
<div align = right>
		   Validity:
";

$getval=mysql_query("select adddate($appdate, interval 1 year), $appdate from $permittable  where owner_id=$owner_id") or die("adddate");
$getdate = mysql_fetch_row($getval);
echo $getdate[1].' '.'TO'.' '. $getdate[0];

print "
<br>
</div>
<br>
<div align=left>
<br>
<table cellpadding=2 cellspacing=2 border=1 align=center>
<tbody>
<tr>
";

$getdetails = mysql_query("select ebpls_motorized_vehicles.motorized_body_no,$owner.owner_last_name,  
$owner.owner_middle_name,
$owner.owner_first_name,
$owner.owner_house_no,  
$owner.owner_street,
$owner.owner_barangay_code,
$owner.owner_zone_code,
$owner.owner_district_code,
$owner.owner_city_code,  
$owner.owner_province_code,  
$owner.owner_zip_code, 
ebpls_motorized_vehicles.route
from ebpls_motorized_vehicles, $owner 
where $owner.owner_id=ebpls_motorized_vehicles.motorized_operator_id and 
ebpls_motorized_vehicles.permit_type='$permit_type' and 
$owner.owner_id = $owner_id") 
or die("details error"
.mysql_error());
$getall = mysql_fetch_row($getdetails);

print "<td>MTOP/BODY
NUMBER<br>
</td>
<td><br>";
echo $getall[0];
print "</td>
</tr>
<tr>
<td>APPLICANT<br>
</td>
<td><br>";
echo $getall[1].' '.$getall[2].' '.$getall[3];
print "</td>
</tr>
<tr>
<td>ADDRESS<br>
</td>
<td><br>";
echo $getall[4].' '.$getall[5].' '.$getall[6].' '.$getall[7];
print "<br>";
echo $getall[8].' '.$getall[9].' '.$getall[10].' '.$getall[11];
print "
</td>
</tr>
<tr>
<td>ROUTE/ZONE/AREA
OF OPERATION<br>
</td>
<td><br>";
echo $getall[12];
print "
</td>
</tr>
</tbody>
</table>
<br>
</div>
<div align= left>&nbsp;&nbsp;&nbsp; ";
//insert paragraph 1                                                                                                       
echo $permitget[3];

print "</div><br>
<table cellpadding='2' cellspacing='2' border='1' align=center>
<tbody>
<tr>
<td>MAKE<br>
</td>
<td>ENGINE NUMBER<br>
</td>
<td>CHASIS NUMBER<br>
</td>
<td>PLATE NUMBER<br>
</td>
</tr>";

$getcar = mysql_query("select ebpls_motorized_vehicles.motorized_motor_model,
ebpls_motorized_vehicles.motorized_motor_no,
ebpls_motorized_vehicles.motorized_chassis_no,
 ebpls_motorized_vehicles.motorized_plate_no 
from ebpls_motorized_vehicles, ebpls_mtop_owner where ebpls_mtop_owner.owner_id=ebpls_motorized_vehicles.motorized_operator_id and 
ebpls_motorized_vehicles.permit_type='$permit_type' and ebpls_mtop_owner.owner_id =$owner_id") or die("details error".mysql_error());

while ($getit = mysql_fetch_row($getcar)){
print "<tr>\n";
foreach ($getit as $field )
print "<td>&nbsp; $field </td>\n";
print "</tr>\n";
}
print "
</tbody>
</table>

<br>
<table align=left>
<tr>
<td>
&nbsp;&nbsp;&nbsp; ";
echo $permitget[4];
print "
</td></tr></table>
<br><br><br> ";
                                                                                                                            
print "<table border=0><td width = 60%><u>";
echo $permitget[11];
print "</u><br>";
echo $permitget[12];
print "</td><td align=right width=60%><u>";
echo $permitget[8];
print "</u><br>";
echo $permitget[13];
print "</td></table><br><br>";
                                                                                                                            
print "<table border=0><td width = 60%><u>";
echo $permitget[9];
print "</u><br>";
echo $permitget[14];
print "</td><td align=right width=60%><u>";
echo $permitget[10];
print "</u><br>";
echo $permitget[15];
print "</td></table>

</body>
</html>
";

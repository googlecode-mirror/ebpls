<?php
?>

<table border=1><br>
<tr>
<td align="center" valign="center" class='normal'><b>Fee Description</b></td>
<td align="center" valign="center" class='normal'><b>Fee Type</b></td>
<td align="center" valign="center" class='normal'><b>Transaction</b></td><td>&nbsp;</td>
</tr>

<?php
$xi = 0;
$getnew=mysql_query("select distinct(biz_desc), biz_id, biz_type,  biz_trans, active  from
                        biz_fee where biz_trans like '$ptype%' and biz_desc like '$biz_desc%'")
                        or die ("new".mysql_error());


while ($xi<2) {
	if ($xi==1) {
		$sta = 'New';
	} else {
		$sta = 'ReNew';
	}

while ($getit=mysql_fetch_row($getnew))
{
	if ($getit[2]==1) {	
		$get='Constant';
	} elseif ($getit[2]==2) {
		$get='Formula';
	} elseif ($getit[2]==3) {
		$get='Range' ;
	}
print "<tr><td>$getit[0]</td><td>$get</td><td>$getit[3]</td>
	<td><a href='index.php?part=8112&permit_type=Business&com=Edit&biz_id=$getit[1]&trans=$getit[3]&updateit=1'>
	<font color=blue>Edit</font></a>";

if ($getit[4]==1) {
print "	&nbsp; <a href='index.php?part=8112&permit_type=Business&com=DeAct&biz_id=$getit[1]&trans=$getit[3]'>
	<font color=blue>Deactivate</font></a></td></tr>";
} else {
print " &nbsp; <a href='index.php?part=8112&permit_type=Business&com=Act&biz_id=$getit[1]&trans=$getit[3]'>
        <font color=blue>Activate</font></a></td></tr>";
}
}
$xi=$xi+=1;
}
?>

<?php
require_once "includes/variables.php";
print "
<html>
<form method = post action='index.php?part=4221&permit_type=";
echo $permit_type;
print "'>
<body>
<div align= center><br>
Republic of the Philippines<br>
<br>";

echo $office;

print "
<br>
<br>
<b>";
echo $permit;
print "
<br>
<br>
</b>

<table cellpadding=2 cellspacing=2 border=0 align="; echo $permit_pos; print ">
<td>
Permit No.:
</td>
<td>";
if ($permit_date=='1') {
echo $permit_header.$currdate[year]."-".$permit_sequence;
} else {
echo $permit_header.$permit_sequence;
}
print "
</td>
</table>
<br>
<br>
<div align=right> Validity:";
echo $tdate." TO ".$tdate;
print "
</div>
<br>
<br>
<div align=left>
<br>
<table cellpadding=2 cellspacing=2 border=1 align=center>
<tbody>
<tr>
";

print "<td>MTOP/BODY
NUMBER<br>
</td>
<td><br>";

print "</td>
</tr>
<tr>
<td>APPLICANT<br>
</td>
<td><br>";
print "</td>
</tr>
<tr>
<td>ADDRESS<br>
</td>
<td><br>";
print "
</td>
</tr>
<tr>
<td>ROUTE/ZONE/AREA
OF OPERATION<br>
</td>
<td><br>";
print "
</td>
</tr>
</tbody>
</table>
<br>
</div>
<div align= left>&nbsp;&nbsp;&nbsp; "; 

echo $par1;

print "
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

print "
</tbody>
</table>


<br>
<table align=left>
<tr>
<td>
&nbsp;&nbsp;&nbsp; ";
echo $par2;
print "
</td></tr></table>
<br><br><br> ";

print "<table border=0><td width = 400><u>";
echo $sign1;
print "</u><br>";
echo $pos1;
print "</td><td align=right width=400><u>";
echo $sign2;
print "</u><br>";
echo $pos2;
print "</td></table><br><br>";

print "<table border=0><td width = 400><u>";
echo $sign3;
print "</u><br>";
echo $pos3;
print "</td><td align=right width=400><u>";
echo $sign4;
print "</u><br>";
echo $pos4;
print "</td></table>";





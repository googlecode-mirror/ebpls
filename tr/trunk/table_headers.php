<?php
require_once "includes/variables.php";

if ($permit_type=='Business') {

	if ($part=='4212') {

print "<table border=1 size = 900 align=center>\n";
print "<td>Owner ID </td>\n";
print "<td>Business ID </td>\n";
print "<td>Permit Number </td>\n";
print "<td><a href='#'><font color=blue>Owner Name</font></a> </td>\n";
print "<td><a href=''><font color=blue>Business Name</font></a></td>\n";
print "<td><a href=''><font color=blue>Business Branch</font></a> </td>\n";
print "<td><a href=''><font color=blue>Last Application Date</font></a> </td>\n";
print "<td>Transaction </td>\n";
print "<td>&nbsp </td>\n";
	} else {

print "<table border=1 size = 900 align=center>\n";
print "<td>Owner ID </td>\n";
print "<td>Business ID </td>\n";
print "<td>Permit Number </td>\n";
print "<td><a href=''><font color=blue>Owner Name</font></a> </td>\n";
print "<td><a href=''><font color=blue>Business Name</font></a></td>\n";
print "<td><a href=''><font color=blue>Business Branch</font></a> </td>\n";
print "<td><a href=''><font color=blue>Last Application Date</font></a> </td>\n";
print "<td>Renewal Date </td>\n";
print "<td>&nbsp </td>\n";
	}

} else {

	if ($part=='1121') {


print "<table border=1 size = 900 align=center>\n";
print "<td>Owner ID </td>\n";
print "<td>Permit Number </td>\n";
print "<td><a href=''><font color=blue>Lastname</font></a> </td>\n";
print "<td><a href=''><font color=blue>Firstname</font></a></td>\n";
print "<td><a href=''><font color=blue>Middlename</font></a> </td>\n";
print "<td><a href=''><font color=blue>Last Application Date</font></a> </td>\n";
print "<td>Renewal Date </td>\n";
print "<td>&nbsp </td>\n";

	} elseif ($part=='2212') {

print "<table border=1 size = 900 align=center>\n";
print "<td>Owner ID </td>\n";
print "<td>Permit Number </td>\n";
print "<td><a href=''><font color=blue>Lastname</font></a> </td>\n";
print "<td><a href=''><font color=blue>Firstname</font></a></td>\n";
print "<td><a href=''><font color=blue>Middlename</font></a> </td>\n";
print "<td><a href=''><font color=blue>Last Application Date</font></a> </td>\n";
print "<td>Transacation </td>\n";
print "<td>&nbsp </td>\n";


	} elseif ($part=='3212') {

print "<table border=1 align=center>\n";
print "<td>Owner ID </td>\n";
print "<td>Permit Number </td>\n";
print "<td><a href=''><font color=blue>Lastname</font></a> </td>\n";
print "<td><a href=''><font color=blue>Firstname</font></a></td>\n";
print "<td><a href=''><font color=blue>Middlename</font></a> </td>\n";
print "<td>Birthdate </td>\n";
print "<td><a href=''><font color=blue>Application Date</font></a> </td>\n";
print "<td>Balance</td>\n";
print "<td>&nbsp </td>\n";

	}

}
?>







<?php 
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");                                                                                                                        
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//require_once("includes/eBPLS_header.php");
                                                                                                                                                                                                                                     
//--- get connection from DB
$dbLink = get_db_connection();
?>

<?php
echo date("F dS Y h:i:s A");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
  <title>eBPLS Report Listing Menu</title>
  <meta name="RAD" content=" Development Academy of the Philippines ">
  <link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body>

<h4 align="center"> Republic of the Philippines </h4>
<h4 align="center"> Province of ________________ </h4>
<h4 align="center"> Municipality of ______________ </h4>
<h4 align="center"> OFFICE OF THE _______________ </h4>
<h4 align="center"><u> REPORT LISTING </u></h4>

<hr>

<div align="center">
<select align="center", name='ptype'> </div>
<?php 
print "<option value=Business Permit'>Business Permit/License</option>";
print "<option value='MotorizedPermit'>Motorized Permit</option>";
print "<option value='FranchisePermit'>Franchise Permit</option>";
print "<option value='OccupationalPermit'>Occupational Permit</option>";
print "<option value='PeddlersPermi't>Peddlers Permit</option>";
print "<option value='FishingPermit'>Fishing Permit</option>";
print "<option value='-------'></option>";

print "<option value=Listing of Business Enterprise by Barangay'>Listing of Business Enterprise by Barangay</option>";
print "<option value=Listing of Business Enterprise by Capital Investment'>Listing of Business Enterprise by Capital Investment</option>";
print "<option value=Listing of Business Enterprise by Owner'>Listing of Business Enterprise by Owner</option>";
print "<option value=Listing of Business Enterprise by Category'>Listing of Business Enterprise by Category</option>";
print "<option value=Listing of Business Enterprise by Business Type'>Listing of Business Enterprise by Business Type</option>";
print "<option value=Listing of Business Enterprise by Nature of Business'>Listing of Business Enterprise by Nature of Business</option>";
print "<option value='-------'></option>";

print "<option value=Master List of Business Establishment'>Master List of Business Establishment</option>";
print "<option value=Monthly List of Registrants'>Monthly List of Registrants</option>";
print "<option value=Top N Business Establishments'>Top N Business Establishments</option>";
print "<option value='-------'></option>";

print "<option value=Business Permit Application'>Business Permit Application</option>";
print "<option value=Blacklisted Business Establishment'>Blacklisted Business Establishment</option>";
print "<option value=List of Establishments Without Permit'>List of Establishments Without Permit</option>";
print "<option value=List of Exempted Business Establishment'>List of Exempted Business Establishment</option>";
print "<option value=Tax Order of Payment'>Tax Order of Payment</option>";
print "<option value='-------'></option>";

print "<option value=Abstract of General Collection'>Abstract of General Collection</option>";
print "<option value=Abstract of CTC issued'>Abstract of CTC Issued</option>";
print "<option value=Listing of Individual Community Tax Certificate Application'>Listing of Individual Community Tax Certificate Application</option>";
print "<option value=Listing of Business Community Tax Certificate Application'>Listing of Business Community Tax Certificate Application</option>";
print "<option value='-------'></option>";

print "<option value=Amusement Tax Collection'>Amusement Tax Collection</option>";
print "<option value=Business Fee Collections'>Business Fee Collections</option>";
print "<option value=Business Tax Collection'>Business Fee Collections</option>";
print "<option value=Collection Performance'>Collection Performance</option>";
print "<option value=Collection Summary'>Collection Summary'</option>";
print "<option value=Comparative Statements'>Comparative Statements</option>";
print "<option value=Delinquency List'>Delinquency List</option>";
print "<option value=Notice of Delinquency'>Notice of Delinquency</option>";
print "<option value=Individual Record of Payment'>Individual Record of Payment</option>";
print "<option value=Individual Business Taxes Delinquency'>Individual Business Taxes Delinquency</option>";
print "<option value=Master List of Franchise Tax'>Master List of Franchise Tax</option>";
print "<option value=Master List of Professional Tax'>Master List of Professional Tax</option>";
print "<option value=Notice of Business Tax Collection'>Notice of Business Tax Collection</option>";
print "<option value=Summary of Collection'>Summary of Collection</option>";
print "<option value=Unrenewed, Unpaid, Pending Permits'>Unrenewed, Unpaid, Pending Permits</option>";
print "<option value=Tax Clearance Certificate'>Tax Clearance Certificate</option>";
print "<option value=Summary of Business Fee Delinquency'>Summary of Business Fee Delinquency</option>";
print "<option value=Summary of Business Requirement Delinquency'>Summary of Business Requirement Delinquency</option>";
print "<option value=Summary of Daily Cash Collection'>Summary of Daily Cash Collection</option>";
print "<option value='-------'></option>";

print "<option value=Business Permit'>Business Permit</option>";
print "<option value=Motorized Vehicle Permit'>Motorized Vehicle Permit</option>";
print "<option value=Fishery Permit'>Fishery Permit</option>";
print "<option value=Occupational Permit'>Occupational Permit</option>";
print "<option value=Franchise Permit'>Franchise Permit</option>";
print "<option value=Registry of Occupational Permit'>Registry of Occupational Permit</option>";
print "<option value=Registry of Fishery and Aquatic Resources'>Registry of Fishery and Aquatic Resources'</option>";
print "<option value=Registry of Motorized Vehicles'>Registry of Motorized Vehicles</option>";
print "<option value=Business Profile'>Business Profile</option>";
print "<option value='-------'></option>";

print "<option value=Business Listing per PSIC'>Business Listing per PSIC</option>";
print "<option value=Audit Trail'>Audit Trail</option>";
print "<option value='-------'></option>";


?>
</select>

<table>
	<tbody>
		<tr>
		&nbsp; <input type=submit name='searchfee' value="Search" > </td>
		</tr>
	</tbody>
</table>

<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>


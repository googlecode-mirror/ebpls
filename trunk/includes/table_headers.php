<?php
/*  Modification History:
	2008.04.04 RJC Minor text change to ensure centering
	2008.05.06 RJC Handle undefined variables to reduce clutter in phperror.log
*/
require_once "includes/variables.php";

if(!isset($ord) or $ord == 'desc') {	//2008.05.06 set ascending if undefined
       $ord ='asc';
} else {
       $ord ='desc';
}

$search_businesstype = isset($search_businesstype) ? $search_businesstype : ''; //2008.05.06 resolve undefined
if ($permit_type=='Business') {
	if ($itemID_==1221 || $itemID_==5212) {
?>
<div align=left>

View <a href='index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH'> All Applications</a> | <a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&disapp=1">
Disapproved Applications</a></div>
<?php
	} elseif ($itemID_==2212) {
?>
<div align=left>

View <a href='index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH'> All Payments</a> | <a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&paypend=1">
Payments with pending checks</a></div>
<?php	
}	
?>
<table border=0 width=100% align=center>

<tr>
<td class="hdr" align=center width=13%>Permit&nbsp;No.</td>
<td class="hdr" align=center>
<a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&orderby='order by <?php echo $owner; ?>.owner_last_name <?php echo $ord;?>'&ord=<?php echo $ord;?>">
 Owner </a> </td>
<td class="hdr" align=center>
<a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&orderby='order by ebpls_business_enterprise.business_name <?php echo $ord;?>'&ord=<?php echo $ord;?>">
 Business </a></td>
<td class="hdr" align=center>
<a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&orderby='order by ebpls_business_enterprise.business_branch <?php echo $ord;?>'&ord=<?php echo $ord;?>">
 Branch </a> </td>
<td class="hdr" align=center width=12%>
<a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=Business&busItem=Business&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&orderby='order by <?php echo $permittable; ?>.<?php echo $appdate; ?> <?php echo $ord;?>'&ord=<?php echo $ord;?>">
 Last&nbsp;Application</a> </td>
<td class="hdr" align=center>Transaction </td>
<td class="hdr" align=center width=12%>Action</td>

</tr>
	
<?php

} else {
?>
<table border=0 width=100% align=center>
<tr>
<td class="hdr" align=center width=15%>Permit Number </td>
<td class="hdr" align=center>
<a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=<?php echo $permit_type; ?>&busItem=<?php echo $permit_type; ?>&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&orderby='order by <?php echo $owner; ?>.owner_last_name <?php echo $ord;?>'&ord=<?php echo $ord;?>">
 Fullname </a> </td>
<td class="hdr" align=center>
<a href="index.php?part=4&class_type=Permits&itemID_=<?php echo $itemID_; ?>&permit_type=<?php echo $permit_type; ?>&busItem=<?php echo $permit_type; ?>&search_businesstype=<?php echo $search_businesstype; ?>&mtopsearch=SEARCH&orderby='order by <?php echo $permittable; ?>.<?php echo $appdate; ?> <?php echo $ord;?>'&ord=<?php echo $ord;?>">
 Last Application Date </a></td>
<td class="hdr" align=center width=15%>Transaction </td>
<td class="hdr" align=center>Action </td>
</tr>
<!--
//	if ($part=='1221') {
//	print "<td>Renewal Date </td>\n";
//	} elseif ($part=='2212' or $part=='3212') {
<td>Transaction </td>
//	} elseif ($part=='3212') {
//	print "<td>Renewal Date </td>\n";
//	print "<td>Balance</td>\n";
	}
<td>&nbsp </td>-->
<?php
}
?>







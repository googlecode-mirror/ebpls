<?php
//$x='-'.$searchmonth.'-';
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                               
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
                                                                                                               
//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;



if ($searchmonth2=='01') {
        $datestart2=date("Y").'-01-'.'01'.' '.'00:00:00';
        $dateend2 = date("Y").'-03-'.'31'.' '.'23:59:00';
} elseif ($searchmonth2=='02') {
        $datestart2=date("Y").'-04-'.'01'.' '.'00:00:00';
        $dateend2 = date("Y").'-06-'.'31'.' '.'23:59:00';
} elseif ($searchmonth2=='03') {
        $datestart2=date("Y").'-07-'.'01'.' '.'00:00:00';
        $dateend2 = date("Y").'-09-'.'30'.' '.'23:59:00';
} elseif ($searchmonth2=='04') {
        $datestart2=date("Y").'-10-'.'01'.' '.'00:00:00';
        $dateend2 = date("Y").'-12-'.'31'.' '.'23:59:00';
}


if ($searchmonth4=='01') {
        $datestart=date("Y").$x.'01'.' '.'00:00:00';
        $dateend = date("Y").'-03-'.'31'.' '.'23:59:00';
} elseif ($searchmonth4=='02') {
        $datestart=date("Y").'-04-'.'01'.' '.'00:00:00';
        $dateend = date("Y").'-06-'.'31'.' '.'23:59:00';
} elseif ($searchmonth4=='03') {
        $datestart=date("Y").'-07-'.'01'.' '.'00:00:00';
        $dateend = date("Y").'-09-'.'30'.' '.'23:59:00';
} elseif ($searchmonth4=='04') {
        $datestart=date("Y").'-10-'.'01'.' '.'00:00:00';
        $dateend = date("Y").'-12-'.'31'.' '.'23:59:00';
}





if ($ctctype=='individual') {

	if ($button3=='SUBMIT') {
	$x = '-'.$searchmonth3.'-';
	$result = mysql_query( "select ctc_code,ctc_first_name, ctc_last_name, ctc_date_issued,ctc_tax_due as total  from ebpls_ctc_individual where ctc_date_issued like '%$x%'")  or die("SELECT Error: ".mysql_error());
//display the  results
//$num_rows = mysql_num_rows($result);
require_once "includes/form_ctc_absreport.php";
print "	<table border=0 cellspacing=1 cellpadding=1 width='100%'>
	<td align=center  valign=top class='normal'>&nbsp;
	<h4 align=center>
        </table>
        <table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";


print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number </h3></td>\n";
print "<td colspan=2 bgcolor=#004f9d><h3>Name </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";

//populate table
while ($get_info = mysql_fetch_row($result)){
print "<tr>\n";
foreach ($get_info as $field )
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_individual where ctc_date_issued like '%$x%'" ) or die("SELECT Error: ".mysql_error());

print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align="right">\n";
print "<td width = '67%'></td>\n";
print "<td width='17%'><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){

foreach ($total_amt as $tot )

print "<td width='16%'>&nbsp<font color=red>$tot</font>\n";
}

} elseif ($button4=='SUBMIT') {
//abs QTR
$result = mysql_query( "select ctc_code,ctc_first_name, ctc_last_name, ctc_date_issued,ctc_tax_due as total  from ebpls_ctc_individual where ctc_date_issued between  '$datestart' and '$dateend'")  or die("SELECT Error: ".mysql_error());
//display the  results
//$num_rows = mysql_num_rows($result);
require_once "includes/form_ctc_absreport.php";
print " <table border=0 cellspacing=1 cellpadding=1 width='100%'>
        <td align=center  valign=top class='normal'>&nbsp;
        <h4 align=center>
        Abstract Of Community Tax Certificate Quarterly Report
        </H4>
        </td>
        </table>
        <table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";


print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number </h3></td>\n";
print "<td colspan=2 bgcolor=#004f9d><h3>Name </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";

//populate table
while ($get_info = mysql_fetch_row($result)){
print "<tr>\n";
foreach ($get_info as $field )
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_individual where ctc_date_issued between  '$datestart' and '$dateend'" ) or die("SELECT Error: ".mysql_error());

print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align="right">\n";
print "<td width = '67%'></td>\n";
print "<td width='17%'><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){

foreach ($total_amt as $tot )

print "<td width='16%'>&nbsp<font color=red>$tot</font>\n";
} 




 
	} elseif ($button2=='SUBMIT') {
	//ctc QTR

	$result = mysql_query( "select ctc_code,ctc_first_name,ctc_middle_name, ctc_last_name, ctc_address, ctc_birth_date,ctc_civil_status, ctc_citizenship, ctc_date_issued, ctc_tax_due   from ebpls_ctc_individual where ctc_date_issued between  '$datestart2' and '$dateend2'")  or die("SELECT Error: ".mysql_error());

//$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_individual where ctc_date_issued between  '$datestart' and '$dateend'" ) or die("SELECT Error: ".mysql_error());



//display the  results
//$num_rows = mysql_num_rows($result);
require_once "includes/form_ctc_listreport.php";

print " <table border=0 cellspacing=1 cellpadding=1 width='100%'>
        <td align=center  valign=top class='normal'>&nbsp;
        <h4 align=center>
        List Of Registrants Quarterly
        </H4>
        </td>
        </table>
        <table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";



print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number </h3></td>\n";
print "<td colspan=3 bgcolor=#004f9d><h3>Name </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Address </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Birthday </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Civil Status </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Citizenship </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";




//populate table
while ($get_info = mysql_fetch_row($result)){
print "<tr>\n";
foreach ($get_info as $field )
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
//$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_individual where ctc_date_issued between  '$datestart' and '$dateend'" ) or die("SELECT Error: ".mysql_error());

$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_individual where ctc_date_issued between  '$datestart2' and '$dateend2'" ) or die("SELECT Error: ".mysql_error());



print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align="right">\n";
print "<td width = '80%'></td>\n";
print "<td width='10%'><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){

foreach ($total_amt as $tot )

print "<td width='10%'>&nbsp<font color=red>$tot</font></td>\n";
 
}

} elseif ($button1=='SUBMIT') {
//ctc monthly
$x='-'.$searchmonth1.'-';
$result = mysql_query( "select ctc_code,ctc_first_name,ctc_middle_name, ctc_last_name, ctc_address, ctc_birth_date,ctc_civil_status, ctc_citizenship, ctc_date_issued, ctc_tax_due   from ebpls_ctc_individual where ctc_date_issued like '%$x%'")  or die("SELECT Error: ".mysql_error());
//display the  results
//$num_rows = mysql_num_rows($result);
require_once "includes/form_ctc_listreport.php";
print " <table border=0 cellspacing=1 cellpadding=1 width='100%'>
        <td align=center  valign=top class='normal'>&nbsp;
        <h4 align=center>
        List Of Registrants Monthly
        </H4>
        </td>
        </table>
        <table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";



print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number</h3></td>\n";
print "<td colspan=3 bgcolor=#004f9d><h3>Name </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Address </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Birthday </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Civil Status </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Citizenship </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";




//populate table
while ($get_info = mysql_fetch_row($result)){
print "<tr>\n";
foreach ($get_info as $field )
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_individual where ctc_date_issued like '%$x%'" ) or die("SELECT Error: ".mysql_error());

print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align="right">\n";
print "<td width = '80%'></td>\n";
print "<td width='10%'><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){

foreach ($total_amt as $tot )

print "<td width='10%'>&nbsp<font color=red>$tot</font>\n";
} // end of if
print "</td></table>\n";
print "</tr></table></body></html>\n";
}

} elseif ($ctctype=='business') {

if ($button3=='SUBMIT') {
$x = '-'.$searchmonth3.'-';
$result = mysql_query( "select ctc_code,ctc_company, ctc_company_address,ctc_business_nature, ctc_date_issued,ctc_tax_due as total  from ebpls_ctc_business where ctc_date_issued like '%$x%'")  or die("SELECT Error: ".mysql_error());
//display the  results
//$num_rows = mysql_num_rows($result);
require_once "includes/form_ctc_absreport.php";
print "	<table border=0 cellspacing=1 cellpadding=1 width='100%'>
	<td align=center  valign=top class='normal'>&nbsp;
	<h4 align=center>
        Abstract Of Business Community Tax Certificate Monthly Report
	</H4>
	</td>
	</table>
	<table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";



print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business Name </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business Address </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business Nature </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";




//populate table
while ($get_info = mysql_fetch_row($result)){ 
print "<tr>\n";
foreach ($get_info as $field ) 
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_business where ctc_date_issued like '%$x%'" ) or die("SELECT Error: ".mysql_error());

print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align=right>\n";
print "<td width = '67%'></td>\n";
print "<td width='17%'><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){
foreach ($total_amt as $tot )
print "<td width='16%'>&nbsp<font color=red>$tot</font>\n";
}
} elseif ($button4=='SUBMIT') {
//abs QTR
$result = mysql_query( "select ctc_code,ctc_company, ctc_company_address,ctc_business_nature, ctc_date_issued,ctc_tax_due as total  from ebpls_ctc_business where ctc_date_issued between  '$datestart' and '$dateend'")  or die("SELECT Error: ".mysql_error());
//display the  results
//$num_rows = mysql_num_rows($result);
require_once "includes/form_ctc_absreport.php";
print " <table border=0 cellspacing=1 cellpadding=1 width='100%'>
        <td align=center  valign=top class='normal'>&nbsp;
        <h4 align=center>
        Abstract Of Business Community Tax Certificate Quarterly Report
        </H4>
        </td>
        </table>
        <table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";


print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business Name </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business Address </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business Nature </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";

//populate table
while ($get_info = mysql_fetch_row($result)){ 
print "<tr>\n";
foreach ($get_info as $field ) 
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_business where ctc_date_issued between  '$datestart' and '$dateend'") or die("SELECT Error: ".mysql_error());

print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align="right">\n";
print "<td width = '67%'></td>\n";
print "<td width='17%'><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){
foreach ($total_amt as $tot )
print "<td width='16%'>&nbsp<font color=red>$tot</font>\n";
} 
} elseif ($button2=='SUBMIT') {
//ctc QTR

$result = mysql_query( "select ctc_code,ctc_company, ctc_company_address,ctc_business_nature,
ctc_tin_no,ctc_organization_type,ctc_place_of_incorporation,  ctc_date_issued,ctc_tax_due as total  from ebpls_ctc_business  where ctc_date_issued between  '$datestart2' and '$dateend2'")  or die("SELECT Error: ".mysql_error());
//display the  results
//$num_rows = mysql_num_rows($result);

require_once "includes/form_ctc_listreport.php";
print " <table border=0 cellspacing=1 cellpadding=1 width='100%'>
        <td align=center  valign=top class='normal'>&nbsp;
        <h4 align=center>
        List Of Business Registrants Quarterly
        </H4>
        </td>
        </table>
        <table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";

print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number </h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Name </h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Address </h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Nature </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business TIN </h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Organization Type </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Place of Incorporation </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";

//populate table
while ($get_info = mysql_fetch_row($result)){ 
print "<tr>\n";
foreach ($get_info as $field ) 
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_business where ctc_date_issued between  '$datestart2' and '$dateend2'") or die("SELECT Error: ".mysql_error());

print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align="right">\n";
print "<td width = '67%'></td>\n";
print "<td width='17%' align=right><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){
foreach ($total_amt as $tot )
print "<td width=10>&nbsp<font color=red>$tot</font>\n";
} 
} elseif ($button1=='SUBMIT') {
//ctc monthly
$x = '-'.$searchmonth1.'-';

$result = mysql_query( "select ctc_code,ctc_company, ctc_company_address,ctc_business_nature,
ctc_tin_no,ctc_organization_type,ctc_place_of_incorporation,  ctc_date_issued,ctc_tax_due as total  from ebpls_ctc_business  where ctc_date_issued like '%$x%'")  or die("SELECT Error: ".mysql_error());
//display the  results
//$num_rows = mysql_num_rows($result);

require_once "includes/form_ctc_listreport.php";
print " <table border=0 cellspacing=1 cellpadding=1 width='100%'>
        <td align=center  valign=top class='normal'>&nbsp;
        <h4 align=center>
        List Of Business Registrants Monthly
        </H4>
        </td>
        </table>
        <table border=0 cellspacing=1 cellpadding=1 width='100%'><td>";

print "<table border=1 cellspacing=0 cellpadding=1 width='100%'>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>CTC Number</h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Name </h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Address </h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Nature </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Business TIN </h3></td>\n";
print "<td  colspan=1 bgcolor=#004f9d><h3>Business Organization Type </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Place of Incorporation </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Date Issued </h3></td>\n";
print "<td colspan=1 bgcolor=#004f9d><h3>Amount Paid </h3></td>\n";

//populate table
while ($get_info = mysql_fetch_row($result)){
print "<tr>\n";
foreach ($get_info as $field )
print "<td>&nbsp$field&nbsp</td>\n";
print "</tr>\n";
}
$total = mysql_query("select sum(ctc_tax_due) as total from ebpls_ctc_business where ctc_date_issued like '%$x%'") or die("SELECT Error: ".mysql_error());

print "</table>";
print "<table border=0 width='100%'>\n";
//print "<td align="right">\n";
print "<td width = '80%'></td>\n";
print "<td width='20%' align=right><b>Total</b></td>\n";

while ($total_amt = mysql_fetch_row($total)){
foreach ($total_amt as $tot )
print "<td width=10>&nbsp<font color=red>$tot</font>\n";
}
}
print "</td></table>\n";
print "</tr></table></body></html>\n";

}
mysql_close($link);
?>  



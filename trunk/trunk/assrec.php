<?php

require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                               
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
                                                                                                               
//--- get connection from DB
global $ThUserData;
//$permit_type = 'Business';
require_once "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLink=Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

$result = SelectMultiTable($dbtype,$dbLink,"$owner, ebpls_business_enterprise",
			" concat($owner.owner_first_name,' ', $owner.owner_middle_name, 
			' ', $owner.owner_last_name) as full,
                        ebpls_business_enterprise.business_name, 
			ebpls_business_enterprise.business_payment_mode",
                        "where $owner.owner_id=$owner_id and 
			ebpls_business_enterprise.owner_id=$owner_id and
                        ebpls_business_enterprise.business_id=$business_id");
$list = FetchRow($dbtype,$result);
?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="CENTER">
<!---// start of the table //-->
<br>
<table border=0 cellspacing=0 cellpadding=0   width='90%'>
                <tr><td align="center" valign="center" class='titleblue'  width='90%'> Assessment for <?php echo ucfirst($permit_type); ?> Permit</td></tr>
                <tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
                <tr>
                        <td align="center" valign="center" class='normal'>
                          <form name="_FRM" method="POST"
action='index.php?part=4&class_type=Permits&itemID_=4212&owner_id=<?php echo $owner_id;?>&business_id=<?php echo $business_id?>&stat=<?php echo $stat; ?>&busItem=Business'>
                                        <input type='hidden' name='tx_mode'>
                                        <input type='hidden' name='tx_code' value='<?php echo $tx_code;?>'>
                                        <input type='hidden' name='permit_no' value='<?php echo $permit_no;?>'>
                                        <input type='hidden' name='owner_id' value='<?php echo $owner_id;?>'>
                                        <input type='hidden' name='business_id' value='<?php echo $business_id;?>'>
                                        <input type='hidden' name='permit_type' value='<?php echo $permit_type;?>'>
                                        <input type='hidden' name='method_of_application' value='<?php echo $method_of_application;?>'>
                                        <table border=0 cellspacing=0 cellpadding=1 width='90%'>
                                                <!--// start of the owner information //-->
                                                <tr><td align="center" valign="top" class='subtitleblue' colspan=2 > Owner / Business Information </td></tr>                                                <tr><td align="right" valign="top" class='normal' colspan=2 height=10> &nbsp;<td></tr>
                                        </table>
                                        <table border=0 cellspacing=0 cellpadding=1>
                                        <tr><td align=left>Name of Owner:</td> <td> <?php echo stripslashes($list[0]); ?> </td></tr>
                                        <tr><td align=left>Name of Business:</td> <td> <?php echo stripslashes($list[1]); ?> </td></tr>
                                        <input type=hidden name = pmode = <?php echo $list[2]; ?>>
                                                <tr><td align=left>Payment Mode:</td> <td> <?php echo $list[2]; ?> </td></tr>
                                        </table>
<br>
<?php
$chkbacktax = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$chkbacktax = FetchArray($dbtype,$chkbacktax);
if ($chkbacktax[sbacktaxes]=='1' and $stat=='Retire') {
 $tftnum=1;
        $htag = 'Assessment';
        $tft = '';
        require "includes/headerassess.php";
        require_once "includes/assessment.php";
$total_tax_compute = $grandamt;
$howmany = $df;
                                                                                                                                                                                                                                                           
?>
<input type='hidden' name='wala_lang'>
<?php
} else {
$gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag=FetchArray($dbtype,$gettag);
$pmode = $list[2];
$lockit = '';
if ($gettag[sassess]=='') {
$tftnum = 1;
        $tft = ' and c.taxfeetype=1'; // or c.taxfeetype=4';
        $htag = 'Assessment of Taxes';
        require "includes/headerassess.php";
        $totexempt=0;
        require "includes/assessment.php";
$total_tax_compute = $grandamt;
$howmany = $df;
$tftnum=4;
        $tft = ' and c.taxfeetype=4';// and c.taxfeetype=4';
        $htag = 'Assessment of Special Fees';
        require "includes/headerassess.php";
        $totexempt=0;
        require "includes/assessment.php";
$total_sf_compute = $grandamt;
$howmany = $df+$howmany;
                                                                                                                                                                                                                                                           
        $tft =' and c.taxfeetype<>1';// or c.taxfeetype<>4';
        $htag = 'General Charges';
        require "includes/headerassess.php";
        require "includes/feeassess.php";
                                                                                                                                                                                                                                                           
                                                                                                                                                                                                                                                           
} else {
        $tftnum=1;
        $htag = 'Assessment';
        $tft = '';
        require "includes/headerassess.php";
        require_once "includes/assessment.php";
$total_tax_compute = $grandamt;
$howmany = $df;
}
}
                                                                                                                                                                                                                                                           


/*
$gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","") or die ("gettag");
$gettag=FetchArray($dbtype,$gettag);
$pmode = $list[2];
$lockit = 'readonly';
$PROCESS='COMPUTE';
if ($gettag[sassess]=='') {
	$tft = ' and c.taxfeetype=1';
	$htag = 'Assessment of Taxes';
	require "includes/headerassess.php";
	require "includes/assessment.php";
	$tft =' and c.taxfeetype<>1';
	$htag = 'Assessment of Fees';
	require "includes/headerassess.php";
	require "includes/feeassess.php";
} else {
	$htag = 'Assessment';
	$tft = '';
	require "includes/headerassess.php";
	require_once "includes/assessment.php";
}
*/
?>
<input type = hidden name = howmany value = <?php echo $df; ?>>
<?php
//$grandamt=$grandamt+=$totfee;
?>
<br><table border=0 width=100%><tr>
<td width=25%></td>
<td align=right width=50%><b>Total Taxes, Fees and Other Charges :</b></td>
<td align=right bgcolor=blue>
<font color=white>Php<?php $vart=$grandamt; echo $ga=number_format($grandamt,2); ?></font> </td></tr></table>
<?php
if ($gettag[0]=='') {
$ota = $grandamt;
}
require "includes/paymentsched.php";
?>
<br><br><br>

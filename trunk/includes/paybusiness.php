<?php
//	@eBPLS_PAGE_APP_INPUT5 : this is the permit application pages
//				-  will input the franchise operator permit application

require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once "includes/variables.php";
                                                                                                               
define(CMD_PAYMENT_NEW_CASH,"NEW_CASH");
define(CMD_PAYMENT_NEW_CHECK,"NEW_CHECK");
define(CMD_PAYMENT_SCHEDULES,"SCHEDULES");
define(CMD_PAYMENT_RECEIPT,"RECEIPT");



//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
$debug 		= false;

$getclear = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a, 
			ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c","sum(a.check_amount)",
                        "where c.transaction='$istat' and
                        a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='CHECK' and a.check_status='CLEARED' and
                        c.trans_id=$owner_id and c.payment_id=$business_id");
$gettotalpay = FetchRow($dbtype,$getclear);
$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a, 
			ebpls_transaction_payment_or_details b","sum(total_amount_paid)",
                        "where a.or_no=b.or_no and b.trans_id=$owner_id and 
			b.or_entry_type='CASH' and
                        b.payment_id=$business_id and b.transaction='$istat'");
$gettotalcash = FetchRow($dbtype,$getcas);
$totalpaidtax = $gettotalpay[0]+$gettotalcash[0];

?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<div align="left">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='100%' >

		<tr><td align="center" valign="center" class='header'  width='100%'> Payment for <?php echo $permit_type; ?> Permit</td></tr>
		<!--<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>-->
</table>
<!---// end of the table //-->
</form>


<form name="_FRM" method="POST" action="" onSubmit="">
<input type=hidden name=trans_id value="">
<input type=hidden name=cmd value="0">
<table border=1 cellpadding=0 cellspacing=1 width="100%" align=center>
<tr>
<td colspan=6 align=center class=header2>Transaction Information</td></tr>
<tr><td colspan=2>
<table border="1" cellpadding="0" cellspacing="0" width="100%" class="sub">
<tr>
<input type=hidden name=newpred value="<?php echo $newpred; ?>">
<input type=hidden name=noregfee value="<?php echo $noregfee; ?>">
<th>Trans Type</th>
<th>Payment Mode</th>
<th>Payment Status</th>
</tr>
<?php
/*
if ($owner_id=='') {
$owner_id=$own_id;
}
*/
$getdata = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, 
				ebpls_business_enterprise_permit b, $owner c",
			"a.business_payment_mode, b.paid, 
			concat(c.owner_last_name,',',c.owner_first_name,' ',c.owner_middle_name)
			as fullname, concat(c.owner_house_no,
			c.owner_street,c.owner_barangay_code,
			c.owner_zone_code,c.owner_district_code,
			c.owner_city_code,c.owner_province_code,
			c.owner_zip_code) as address, c.owner_gender,
			c.owner_phone_no, a.business_name, 
			concat(a.business_lot_no,' ', a.business_street,' ',   
			a.business_barangay_code,' ', a.business_zone_code,' ',    
			a.business_barangay_name,' ', a.business_district_code,' ',   
			a.business_city_code,' ', a.business_province_code,' ',  
			a.business_zip_code) as bizadd,b.transaction",
			"where a.owner_id = b.owner_id and a.business_id = b.business_id and 
			c.owner_id=$owner_id and
			a.owner_id = $owner_id and a.business_id = $business_id and 
			b.transaction='$stat' and b.active=1");
$getid = FetchRow($dbtype,$getdata);
$pmode = $getid[0];


$getdata = mysql_query("select * from ebpls_owner a, ebpls_barangay b, ebpls_zone c,
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.owner_barangay_code=b.barangay_code and
			a.owner_zone_code=c.zone_code and a.owner_district_code=d.district_code and
			a.owner_city_code=e.city_municipality_code and a.owner_province_code=f.province_code and
			a.owner_city_code=g.upper");    

		
			
			
$getda=FetchArray($dbtype,$getdata);


if ($getda[zone_desc]=='') {
	
	$getdata = mysql_query("select * from ebpls_owner a, ebpls_barangay b, 
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.owner_barangay_code=b.barangay_code and
			 a.owner_district_code=d.district_code and
			a.owner_city_code=e.city_municipality_code and a.owner_province_code=f.province_code and
			a.owner_city_code=g.upper");  
			 $getda=FetchArray($dbtype,$getdata); 
}
		
			
	


$add = "$getda[owner_street] $getda[barangay_desc] $getda[zone_desc], $getda[district_desc] 
		$getda[city_municipality_desc], $getda[province_desc], $getda[zip_code]";

$getdata = mysql_query("select * from ebpls_business_enterprise a, ebpls_barangay b, ebpls_zone c,
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.business_id='$business_id'
						and a.business_barangay_code=b.barangay_code and
			a.business_zone_code=c.zone_code and a.business_district_code=d.district_code and
			a.business_city_code=e.city_municipality_code and a.business_province_code=f.province_code and
			a.business_city_code=g.upper");    

		
		
			
$getda=FetchArray($dbtype,$getdata);


if ($getda[zone_desc]=='') {
	
	$getdata = mysql_query("select * from ebpls_business_enterprise a, ebpls_barangay b, 
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.business_id='$business_id'
						and a.business_barangay_code=b.barangay_code and
			 a.business_district_code=d.district_code and
			a.business_city_code=e.city_municipality_code and a.business_province_code=f.province_code and
			a.business_city_code=g.upper");    

			 $getda=FetchArray($dbtype,$getdata); 
}
		
			
	


$busadd = "$getda[business_street] $getda[barangay_desc] $getda[zone_desc], $getda[district_desc] 
		$getda[city_municipality_desc], $getda[province_desc], $getda[zip_code]";



if ($getid[1]==1) {
	$paystat = 'Paid';
} else {
	$paystat = 'UnPaid';
}

if ($getid[2]=='') {
	?>
	<body onload="parent.location='index.php?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=2212&mtopsearch=SEARCH';"></body>
	<?php
}

?>
<tr>
<input type=hidden name=stat value=<?php echo $istat; ?>>
<td align=center><?php echo $istat; ?></td>
<td align=center><?php if ($istat=='Retire') { $getid[0]='ANNUAL'; } echo $getid[0]; ?></td>
<td align=center><font color=red><?php echo $paystat; ?></font> </td>
</tr>
</table>
<tr><td valign=top>
<table border=0 cellpadding=0 cellspacing=1 width="100%" align = center valign=top>
<tr><td colspan=2 align=center valign=top>Owner Information</td></tr>
<tr><td>Name</td><td><?php echo $getid[2];?></td></tr>
<tr><td>Address</td><td><?php echo $add;?></td></tr>
<tr><td>Gender</td><td><?php echo $getid[4];?></td></tr>
<tr><td>Phone No.</td><td><?php echo $getid[5];?></td></tr>
</table>
</td>
<td valign=top>
<table border=0 cellpadding=0 cellspacing=1 width="100%" align =center valign=top>
<tr><td align=center colspan=2 align=center valign=top>Business Information</td></tr>
<tr><td >Business Name</td><td><?php echo stripslashes($getid[6]);?></td></tr>
<tr><td >Address</td><td><?php echo $busadd;?></td></tr>
</table>
</td>
</tr>

<!-- print fee details -->
<table border=1 cellpadding=5 cellspacing=0 width="90%" align = center>
<!--<tr><td align=center colspan=2><b><font size=3>Fee Details</td></tr>
<tr><td width=35%>Payment Mode </td><td align=left>[<B><?php echo $getid[0];?>]
<input type=hidden name=pmode value='<?php echo $getid[0];?>'>
</b></td></tr>-->
</table>
<tr><td>
<?php
$chkbacktax = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$chkbacktax = FetchArray($dbtype,$chkbacktax);
if ($chkbacktax[sbacktaxes]=='1' and $stat=='Retire') {
$tftnum=1;
$tft='';
$htag = 'Assessment';
require "includes/headerassess.php";
require "includes/assessment.php";
$total_tax_compute = $grandamt;
$howmany = $df;

?>
<input type='hidden' name='wala_lang'>
<?php
} else {
$gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag=FetchArray($dbtype,$gettag);
	if ($gettag[sassess]=='') {
$tftnum = 1;
      //  $tft = ' and c.taxfeetype=1'; // or c.taxfeetype=4';
        $htag = 'Assessment';
        require "includes/headerassess.php";
        $totexempt=0;
        require "includes/assessment.php";
$total_tax_compute = $grandamt;
$howmany = $df;
$tftnum=4;
//         $tft = ' and c.taxfeetype=4';// and c.taxfeetype=4';
//         $htag = 'Assessment of Special Fees';
//         require "includes/headerassess.php";
//         $totexempt=0;
//         require "includes/assessment.php";
// $total_sf_compute = $grandamt;
// $howmany = $df+$howmany;
           if ($noregfee<>1) {                                                                                                                  
        $tft =' and c.taxfeetype<>1';// or c.taxfeetype<>4';
        $htag = 'General Charges';
        require "includes/headerassess.php";
        require "includes/feeassess.php";
    }

	} else {
$tftnum=1;
$tft='';
$htag = 'Assessment';
$com='approve';
require "includes/headerassess.php";
require "includes/assessment.php";

	}
}
//$grandamt = $total_tax_compute + $total_sf_compute + $fee;
//echo "$total_tax_compute + $total_sf_compute + $fee";
//echo $grandamt;
//$ttax=$grandamt+$totfee;
$totfee = round($totfee,2);
$ttax = $grandamt - $totfee;

$gid = SelectDataWhere($dbtype,$dbLink,"bus_grandamt",
	"where business_id=$business_id and active = 0 and
         owner_id=$owner_id order by gid desc limit 1");
        $haveexist = NumRows($dbtype,$gid);
                if ($haveexist<>0) {
                        $mt = FetchRow($dbtype,$gid);
                        $grdmt = $mt[3];
                } else {
		$grdmt = $grandamt+$totfee;
		}
$tabs = abs($grdmt-$totalpaidtax);
if ($grdmt=='0.00') {
        $grdmt=$grandamt;
}

$gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag=FetchArray($dbtype,$gettag);
if ($gettag[sassess]=='') {
	$grdmt = ($ota-$add2fee)+$totfee;
	$tabs = abs($grdmt-$totalpaidtax);
	$grandamt = $grdmt;
}


//getpayhistory
$gethis = SelectDataWhere($dbtype,$dbLink,"ebpls_transaction_payment_or_details",
			"where trans_id=$owner_id and payment_id=$business_id and 
			 transaction='$istat' and payment_part='$s+1' and 
			 or_entry_type<>'CHECK'");
$getc = NumRows($dbtype,$gethis);
                                                                                                 
$gethis = SelectDataWhere($dbtype,$dbLink,"ebpls_transaction_payment_or_details a, 
			ebpls_transaction_payment_check b",
			"where a.trans_id=$owner_id and a.payment_id=$business_id 
			 and a.transaction='$istat' and a.payment_part='$s+1' 
			 and a.or_entry_type='CHECK' and b.check_status='CLEARED'
                         and a.or_no=b.or_no");
$getch = NumRows($dbtype,$gethis);
$gethis = $getc+$getch;
if ($gethis>0 and $pmode=='ANNUAL') {
        $grdmt=0;
	$tabs=0;
	$grandamt=0;
	$an = 1;
}
?>
<?php

if ($gettag[sassess]<>1) {

$grdmt = $total_tax_compute + $total_sf_compute + $fee;
//$grdmt=$fee+$gentax;
?>
<table border=0 cellpadding=0 cellspacing=0 width="100%" align = center class=sub>

<td width=25%>&nbsp;</td><td>&nbsp;</td>
<td align=right width=50%>Total Taxes, Fees and Other Charges:</td>
<td align=right width=25% bgcolor=orange color=white>
<input type=hidden name=amtpay value=<?php echo $grdmt;?>>
<b>Php <?php $vart = $grdmt; echo number_format($grdmt,2);?></b></font></td></tr>
		<!--
		<tr><td></td><td></td><td>Amount Paid:</td><td align=right><font color=red>
		<input type=hidden name=tpaid value=<?php echo $totalpaidtax;?>>
		<b> <?php echo number_format($totalpaidtax,2);?></b></font></td></tr>
		
		<tr><td></td><td></td><td>Balance:</td><td align=right><font color=red>
		<input type=hidden name=balance value=<?php echo number_format($tabs,2);?>>
		<b>Php <?php echo number_format($tabs,2);?></b></font></td></tr>
		-->
</table>
<?php
}
?>
<!--</table>-->

<?php


if ($an==1) {
$grandamt=0;
} else {
	if ($gettag[sassess]==1) {
	$grdmt= $grandamt;
	
	} else {
	$grandamt = $grdmt; 
	}
}

require_once "includes/paymentsched.php";
print "<br>";
require_once "includes/historypay.php";

 ?>

<table border=0 cellpadding=2 cellspacing=0 width="70%"><br>
<tr>
</tr>
</table>
<BR>
</form>

<?



?>

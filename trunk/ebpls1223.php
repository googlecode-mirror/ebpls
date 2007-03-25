<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
global $ThUserData;

require_once "includes/variables.php";

if ($ownsearch=='searchme') {
	$ownsearch=' S E A R C H ';
} 
if ($addveh=='transfer') {
	
	include_once "includes/form_trans_search.html";
}


if ($addown=='ADD NEW') {
	$tag = $permit_type;
	require "includes/form_mtop_owner.php";
} elseif ($addbus=='ADD NEW') {
	require "ebpls1224.php";
} elseif ($addbus=='addbus' || $addbus==' A D D ') {
	if ($business_id=='') {
	$addbiz='Save';
	}
	require_once "ebpls1224.php";
} elseif ($com=='search') {
	if ($search_type=='Enterprise') {
	require_once "includes/form_bus_search.html";
	} else {
	$tag = $permit_type;	
	require_once "includes/form_owner_search.html";
	}
} elseif ($bussearch==' S E A R C H ') {

include_once "class/BusinessEstablishmentClass.php";
        $getbus = new BusinessEstablishment;
	if ($srch_frm==1) {
        $getbus->GetBusinessByName($search_bus,$search_branch);
	} elseif ($srch_frm==2) {
        $getbus->GetBusinessByNameCTC($search_bus,$search_branch);
        } elseif ($srch_frm==3) {
        $getbus->GetBusinessByOnline($search_bus,$search_branch);
        } 


        $getcn = $getbus->outnumrow;

		if ($getcn==0) {
		$datarow[2] = $search_bus;
		require "ebpls1224.php";
	?>
		<body onload='javascript:alert("No record found for this business");'></body>
	<?php
		} else {
		require_once "includes/form_bus_search.html";
	?>
<input type=hidden name=genpin  value='<?php echo $genpin; ?>'>
		<br>
		<div align=center><font color=red>Total Records: <?php echo $getcn; ?></font></div>
	<br><br><table border=1 size = 100% cellspacing=0 cellpadding=0 align=center>
	<tr width=80% bgcolor='#EEEEEE'>
	<td bgcolor='#E6FFE6' align=center>Name</td>
	<td bgcolor='#E6FFE6' align=center>Branch </td>
	<td bgcolor='#E6FFE6' align=center>Address </td>
	<td bgcolor='#E6FFE6' align=center>Action&nbsp; </td></tr>
<?php
		$getbus->ListBusiness($getbus->outselect,$owner_id,$genpin);
                echo $getbus->outarray;
		}
		print "</table>";

} elseif ($ownsearch==' S E A R C H ') {

include "class/TaxpayerClass.php";
	$getown = new TaxPayer;
	$getown->GetTaxPayerByName($search_first,$search_last);
	$getcn = $getown->outnumrow; 
		if ($getcn==0 ) {
	?>
		<br>
		<body onload='javascript:alert("No record found for this person.");'></body>
	<?php
		$owner_first_name=$search_first;
		$owner_last_name=$search_last;
		require "includes/form_mtop_owner.php";
		} else {
		require_once "includes/form_owner_search.html";
		$totcnt=$getcn;
	?>
		<br>
		<div align=center>
		<font color=red>Total Records: <?php echo $totcnt; ?></font></div>
	<?php
		
	?>
		<br><br>
		<table border=1 size = 100% cellspacing=0 cellpadding=0 align=center>
		
<?php
		include_once "pager/searchowner_page.php";
		}//end while
		print "</table>";

}elseif ($motorsearch==' S E A R C H ') {

include "class/TaxpayerClass.php";
	$getown = new TaxPayer;
	$getown->GetMotor($mmodel,$mnum,$cnum,$pnum,$bnum,$bcolor,$route,$ltype,$ltoreg,$cro);
	$getcn = $getown->outnumrow; 
		if ($getcn==0 ) {
	?>
		<br>
		<body onload='javascript:alert("No record found for this vehicle.");'></body>
	<?php
	require_once "includes/form_trans_search.html";
		} else {
		require_once "includes/form_trans_search.html";
		$totcnt=$getcn;
	?>
		<br>
		<div align=center>
		<font color=red>Total Records: <?php echo $totcnt; ?></font></div>
	<?php
		
	?>
		<br><br>
		<table border=1 size = 100% cellspacing=0 cellpadding=0 align=center>
		<tr width=80% bgcolor='#EEEEEE'>
		<tr>
		<td align=center> Motor Model </td>
		<td align=center> Motor Number </td>
		<td align=center> Chasis Number </td>
		<td align=center> Plate Number </td>
		<td align=center> Body Number </td>
		<td align=center> Body Color </td>
		<td align=center> Route </td>
		<td align=center> Line Type </td>
		<td align=center> LTO Registration No </td>
		<td align=center> Certificate of Registration </td>
		<td align=center> Action </td>
		</tr>
<?php
		$getown->ListMotor($getown->outselect,$permit_type,$owner_id,$stat);
		echo $getown->outarray;
/*
		<tr bgcolor='<?php echo $varcolor;?>'>
		<td>&nbsp;<?php echo $fullname; ?>&nbsp</td>
		<td>
		<a href='index.php?part=4&itemID_=1221&addbiz=Select&owner_id=<?php echo $getown->outarray[owner_id]; ?>&business_id=<?php echo $business_id; ?>&permit_type=<?php echo $permit_type; ?>'>
		<font color=blue>Attach</font></a></td>
		</tr>
<?php
		}*/
		}//end while
		print "</table>";
}

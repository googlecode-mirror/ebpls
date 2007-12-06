<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
$is_new=true;
//$dbLink = get_db_connection();
if ($owner_id == "" and $business_id == "") {
	$is_disabled = "disabled";
} else {
	$is_disabled = "";
}
$submit_button='PROCESS';
$tdate=date('Y');
$datetoday=date('Y-m-d H:i:s');
if ($pro==1) {
//check valid gsm 
if ($owner_gsm_no<>'') {
	if (strlen($owner_gsm_no)==13) {
		$prefix = substr($owner_gsm_no, 0,4);
			if ($prefix=='+639') {
				$cell=substr($owner_gsm_no,1) ;
					if (!is_numeric($cell)) {
						$blak = 1;
					} else {
						$blak = 0;
					}
			} else {
			   $blak = 1;
			}
	} else {
		$blak = 1;
	}
} else {
     $blak = 0;
}
if ($blak==1) {

?>
<body onload='javascript:alert("Invalid mobile number format! Please enter as follows: +63##########"); parent.location="index.php?part=4&itemID_=1001&permit_type=CTC&owner_id=<? echo $owner_id;?>&ctc_type=INDIVIDUAL&busItem=CTC&comm=<? echo $trekcom;?>"'>
</body>
<?php
}
if ($blak==0) {
if ($owner_id =="" || $owner_id == 0) {
$addown = mysql_query("insert into $owner (owner_first_name,
owner_middle_name,
owner_last_name,
owner_street,
owner_barangay_code,
owner_zone_code,
owner_district_code,
owner_city_code,owner_province_code,
owner_zip_code,owner_citizenship,owner_civil_status,
owner_gender,owner_tin_no,owner_icr_no,owner_phone_no,owner_gsm_no,owner_email_address,
owner_others,owner_birth_date,owner_reg_date,
owner_lastupdated,owner_lastupdated_by) values
('$owner_first_name',
'$owner_middle_name',
'$owner_last_name',
'$owner_street',
'$owner_barangay_code',
'$owner_zone_code','$owner_district_code','$owner_city_code',
'$owner_province_code','$owner_zip_code','$owner_citizenship','$owner_civil_status','$owner_gender','$owner_tin_no','$owner_icr_no','$owner_phone_no','$owner_gsm_no','$owner_email_address'   ,'$owner_others','$owner_birth_date',now(),now(),'$usern')") or die("INSERT:".mysql_error());
?>
<body onLoad="javascript: alert('Taxpayer has been added.');"></body>
<?
} else {
$updateown = mysql_query("update $owner set (owner_first_name='$owner_first_name',
owner_middle_name='$owner_middle_name',
owner_last_name='$owner_last_name',
owner_street='$owner_street',
owner_barangay_code='$owner_barangay_code',
owner_zone_code='$owner_zone_code',
owner_district_code='$owner_district_code',
owner_city_code='$owner_city_code',
owner_province_code='$owner_province_code',
owner_zip_code='$owner_zip_code',owner_citizenship='$owner_citizenship',owner_civil_status='$owner_civil_status',
owner_gender='$owner_gender',owner_tin_no='$owner_tin_no', owner_icr_no='$owner_icr_no', owner_phone_no='$owner_phone_no', owner_gsm_no='$owner_gsm_no',owner_email_address='$owner_email_address',
owner_others='$owner_others',owner_birth_date='$owner_birth_date',owner_lastupdated=now(),owner_lastupdated_by='$usern'");
?>
<body onLoad="javascript: alert('Taxpayer has been updated.');"></body>
<?

}

//if ($tag=='Business') {
if ($busItem=='CTC') {
$asap=mysql_query("select owner_id from ebpls_owner where owner_first_name='$owner_first_name' and owner_middle_name='$owner_middle_name' and owner_last_name='$owner_last_name' order by owner_id desc limit 1") or die("Wah".mysql_error());
$asap=mysql_fetch_row($asap);
$owner_id=$asap[0];
$redito='index.php?part=4&itemID_=1001&owner_id='.$owner_id.'&ctc_type=INDIVIDUAL&permit_type=CTC&busItem=CTC&comm='.$comm;
$search='';
$comm="";
} else {
	?>
<body onLoad="parent.location='index.php?part=4&class_type=Permits&itemID_=1221&permit_type=<? echo $tag;?>&owner_id=<? echo $owner_id;?>&stat=New&addOwner=ADD&permit_type=CTC&busItem=<? echo $permit_type;?>'"></body>
<?
}

}

} else {
//	if ($tag=='Business') {
if ($busItem=='CTC') {
//	$asap=mysql_query("select owner_id from ebpls_owner order by owner_id desc limit 1") or die("Wah".mysql_error());
//$asap1=mysql_fetch_row($asap);
//$owner_id=$asap1[0];
$allclear='index.php?part=4&itemID_=1001&owner_id='.$owner_id.'&ctc_type=INDIVIDUAL&permit_type=CTC&busItem=CTC&comm='.$comm;
} else {
$allclear = 'index.php?part=4&class_type=Permits&busItem='.$tag.'&itemID_=1221&permit_type='.$tag.'&mtopadd=ADD&stat=New&busItem='.$tag;
}
//	} else {

//	$allclear = 'index.php?part=4&class_type=Permits&itemID_=1221&permit_type='.$tag.'&mtopadd=ADD&stat=New&busItem='.$permit_type;
//	}
//$addOwner='';
/*	$newowner = mysql_query("insert into ebpls_owner (owner_first_name,
owner_middle_name,
owner_last_name,
owner_street,
owner_barangay_code,
owner_zone_code,
owner_district_code,
owner_city_code,owner_province_code,
owner_zip_code,owner_citizenship,owner_civil_status,
owner_gender,owner_tin_no,owner_icr_no,owner_phone_no,owner_gsm_no,owner_email_address,
owner_others,owner_birth_date,owner_reg_date,
owner_lastupdated,owner_lastupdated_by) values
('$owner_first_name',
'$owner_middle_name',
'$owner_last_name',
'$owner_street',
'$owner_barangay_code',
'$owner_zone_code','$owner_district_code','$owner_city_code',
'$owner_province_code','$owner_zip_code','$owner_citizenship','$owner_civil_status','$owner_gender','$owner_tin_no','$owner_icr_no','$owner_phone_no','$owner_gsm_no','$owner_email_address'   ,'$owner_others','$owner_birth_date',now(),now(),'$usern')") or die("INSERTROBERT:".mysql_error());
*/
//$search='';
//$asap=mysql_query("select owner_id from ebpls_owner order by owner_id desc limit 1") or die("Wah".mysql_error());
//$asap=mysql_fetch_row($asap);
//$owner_id=$asap[0];
//$pro=1;
}
//echo $pro."ghgh";
if ($upOwner=='UPDATE') {
$search='';
}
if ($_search=='S U B M I T') {
$comm='ctc';
$_search='';
$search='';
}
$saveenabled = "disabled";
$printenabled = "disabled";
if ($ctc_process=='PROCESS') {
	if ($exempted<>'') {
		$eexempted=1;
		$ctc_basic_tax=1;
	} else {
		$eexempted=0;
		$ctc_basic_tax=5;
	}
	$getinterest = mysql_query("select interest_rate, ceiling_rate, penalty_date from ebpls_ctc_interest where ctc_type='Individual'") or die("ctc interest".mysql_error());
	$getinterest = mysql_fetch_row($getinterest);
	$ctc_additional_tax1_due=(int)($ctc_additional_tax1/1000);
	$ctc_additional_tax2_due=(int)($ctc_additional_tax2/1000);
	$ctc_additional_tax3_due=(int)($ctc_additional_tax3/1000);
	$ctc_total_amount_due=$ctc_basic_tax+$ctc_additional_tax1_due+$ctc_additional_tax2_due+$ctc_additional_tax3_due;
	if ($ctc_total_amount_due > $getinterest[1]) {
		$ctc_total_amount_due = $getinterest[1];
	} else {
		$ctc_total_amount_due = $ctc_total_amount_due;
	}
	$nhyear = date('Y');
	$nhdate = $getinterest[2];
	$nhdatetoday = date('n');
	if ($nhdatetoday >= $nhdate) {
		$ctc_total_interest_due = (($ctc_total_amount_due * $getinterest[0])/100)*($nhdatetoday);
	} else {
		$ctc_total_interest_due = 0;
	}
	$ctc_total_paid=$ctc_total_amount_due+$ctc_total_interest_due;
	$ctc_process='';
	$submit_button='SAVE';
	$action="";
	$_search='';
	$search='';
	$comm='ctc';
//	setUrlRedirect("index.php?part=4&itemID_=1001&ctc_type=INDIVIDUAL&busItem=CTC&owner_id=$owner_id&comm=ctc&eexempted=$eexempted&ctc_basic_tax=$ctc_basic_tax&ctc_additional_tax1_due=$ctc_additional_tax1_due&ctc_additional_tax2_due=$ctc_additional_tax2_due&ctc_additional_tax3_due=$ctc_additional_tax3_due&ctc_additional_tax1=$ctc_additional_tax1&ctc_additional_tax2=$ctc_additional_tax2&ctc_additional_tax3=$ctc_additional_tax3&ctc_total_amount_due=$ctc_total_amount_due&ctc_total_interest_due=$ctc_total_interest_due&ctc_total_paid=$ctc_total_paid&submit_button=SAVE");
$saveenabled = "";
}
if ($ctc_process=='SAVE') {
if ($ctc_code=="") {
	echo "<div align=center><font color=red><b><i>Enter CTC Number or Invalid CTC Number!!!</i></b></font</div>";
	$comm='ctc';
	$_search='';
	$search='';
} else {
$chkduplicaterec = mysql_query("select ctc_code from ebpls_ctc_individual where ctc_code='$ctc_code'");
$chkduplicaterec1 = mysql_num_rows($chkduplicaterec);
if ($chkduplicaterec1 > 0) {
?>
<body onLoad="javascript: alert('Duplicate CTC Number!'); _FRM.ctc_code.focus();"></body>
<?
$ctc_basic_tax=floatval(str_replace(",","",$ctc_basic_tax));
$ctc_additional_tax1=floatval(str_replace(",","",$ctc_additional_tax1));
$ctc_additional_tax2=floatval(str_replace(",","",$ctc_additional_tax2));
$ctc_additional_tax3=floatval(str_replace(",","",$ctc_additional_tax3));
$ctc_additional_tax1_due=floatval(str_replace(",","",$ctc_additional_tax1_due));
$ctc_additional_tax2_due=floatval(str_replace(",","",$ctc_additional_tax2_due));
$ctc_additional_tax3_due=floatval(str_replace(",","",$ctc_additional_tax3_due));
$ctc_total_paid=floatval(str_replace(",","",$ctc_total_paid));
$ctc_total_interest_due=floatval(str_replace(",","",$ctc_total_interest_due));
$ctc_process='';
$submit_button='SAVE';
$comm='ctc';
$_search='';
$search='';
} else {
if ($ctc_application_fee=='') {
	$ctc_application_fee=0;
}
if ($ctc_tax_exempted=='') {
	$ctc_tax_exempted=0;
}
if ($ctc_code=='') {
	$ctc_code=0;
}
// 		$ctc_basic_tax=number_format($ctc_basic_tax,2,'.','');
// 		$ctc_additional_tax1_due=number_format($ctc_additional_tax1_due,2,'.','');
// 		$ctc_additional_tax2_due=number_format($ctc_additional_tax2_due,2,'.','');
// 		$ctc_additional_tax3_due=number_format($ctc_additional_tax3_due,2,'.','');
// 		$ctc_total_interest_due=number_format($ctc_total_interest_due,2,'.','');
// 		$ctc_tax_exempted=number_format($ctc_tax_exempted,2,'.','');
// 		$ctc_total_paid=number_format($ctc_total_paid,2,'.','');
// 		$ctc_total_paid=number_format($ctc_total_paid,2,'.','');
	$ctc_basic_tax=floatval(str_replace(",","",$ctc_basic_tax));
	$ctc_additional_tax1=floatval(str_replace(",","",$ctc_additional_tax1));
	$ctc_additional_tax2=floatval(str_replace(",","",$ctc_additional_tax2));
	$ctc_additional_tax3=floatval(str_replace(",","",$ctc_additional_tax3));
	$ctc_additional_tax1_due=floatval(str_replace(",","",$ctc_additional_tax1_due));
	$ctc_additional_tax2_due=floatval(str_replace(",","",$ctc_additional_tax2_due));
	$ctc_additional_tax3_due=floatval(str_replace(",","",$ctc_additional_tax3_due));
	$ctc_total_amount_due=floatval(str_replace(",","",$ctc_total_amount_due));
	$ctc_total_interest_due=floatval(str_replace(",","",$ctc_total_interest_due));
	$savetoctc = mysql_query("insert into ebpls_ctc_individual (ctc_code, ctc_first_name, ctc_owner_id, ctc_middle_name, ctc_last_name, 
				ctc_birth_date, ctc_address, ctc_gender, ctc_civil_status, ctc_acct_code, 
				ctc_place_issued, ctc_date_issued, ctc_for_year, ctc_tin_no, ctc_occupation, 
				ctc_height, ctc_weight, ctc_icr_no, ctc_citizenship, ctc_application_fee, 
				ctc_place_of_birth, ctc_basic_tax, ctc_additional_tax1, ctc_additional_tax2, ctc_additional_tax3, 
				ctc_tax_interest, ctc_tax_exempted, ctc_tax_due) values 
				('$ctc_code', '$ctc_first_name', '', '$ctc_middle_name', '$ctc_last_name', 
				'$ctc_birth_day', '$ctc_address', '$ctc_gender', '$ctc_civil_status', '$ctc_acct_code', 
				'$ctc_place_issued', now(), $tdate, '$ctc_tin_no', '$ctc_occupation', 
				'$ctc_height', '$ctc_weight', '$ctc_icr_no', '$ctc_citizenship', $ctc_application_fee, 
				'$ctc_place_of_birth', $ctc_basic_tax, $ctc_additional_tax1, $ctc_additional_tax2, $ctc_additional_tax3, 
				$ctc_total_interest_due, $ctc_tax_exempted, $ctc_total_amount_due)") or die("Paktay".mysql_error());
				$ctc_basic_tax=floatval(str_replace(",","",$ctc_basic_tax));
$ctc_additional_tax1=floatval(str_replace(",","",$ctc_additional_tax1));
$ctc_additional_tax2=floatval(str_replace(",","",$ctc_additional_tax2));
$ctc_additional_tax3=floatval(str_replace(",","",$ctc_additional_tax3));
$ctc_additional_tax1_due=floatval(str_replace(",","",$ctc_additional_tax1_due));
$ctc_additional_tax2_due=floatval(str_replace(",","",$ctc_additional_tax2_due));
$ctc_additional_tax3_due=floatval(str_replace(",","",$ctc_additional_tax3_due));
$ctc_total_paid=floatval(str_replace(",","",$ctc_total_paid));
$ctc_total_interest_due=floatval(str_replace(",","",$ctc_total_interest_due));
$ctc_process='';
$submit_button='SAVE';
$comm='ctc';
$_search='';
$search='';

	?>
	<body onLoad="alert('CTC has been saved.!!');"></body>
<?php
	$printenabled = "";
	$saveenabled = "disabled";
	$processenabled = "disabled";
}
}
}
?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript'>

function validate_ctc_application(_url)
{
	var _FRM = document._FRM;
	var msgTitle = "Business Permit Application\n";

	if( isBlank(_FRM.owner_first_name.value) == true)
	{
		alert( msgTitle + "Please input a valid firstname!");
		_FRM.owner_first_name.focus();
		return false;
	}
	if( isBlank(_FRM.owner_middle_name.value) == true)
	{
		alert( msgTitle + "Please input a valid middlename!");
		_FRM.owner_middle_name.focus();
		return false;
	}
	if( isBlank(_FRM.owner_last_name.value) == true)
	{
		alert( msgTitle + "Please input a valid lastname!");
		_FRM.owner_last_name.focus();
			return false;
	}
	
	/* 
	//--- validate the business details
	if( isBlank(_FRM.business_name.value) == true)
	{
		alert( msgTitle + "Please input a valid business name!");
		_FRM.business_name.focus();
		return false;
	}
	
	if( isBlank(_FRM.business_id.value) == true)
	{
		alert( msgTitle + "Please add business first by clicking either Search link!");
		return false;
	}
	*/
	
	 _FRM.action=_url;

	 _FRM.submit();
			 
 return true;
}

	
</script>
<div align="CENTER">
<!---// start of the table //-->
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
	<tr><td align="center" valign="center" class='header'  width='100%'>Community Tax Certificate</td></tr>
	<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
	<tr>
		<td align="center" valign="center" class='title'>
			<form name="_FRM" method="POST" >
				<input type='hidden' name='cmd' value='CTC1'>
				<input type='hidden' name='tx_code' value='<?php echo $tx_code;?>'>
				<input type='hidden' name='status_of_application' value='<?php echo $status_of_application;?>'>
				<input type='hidden' name='permit_type' value='<?php echo $permit_type;?>'>
				<input type='hidden' name='method_of_application' value='<?php echo $method_of_application;?>'>
				<input type='hidden' name='sub_method_of_application' value='<?php echo $sub_method_of_application;?>'>
				<input type='hidden' name='permit_no' value='<?php echo $permit_no;?>'>
				<input type='hidden' name='child_reload_permit_no' value='<?php echo $child_reload_permit_no;?>'>
				<input type='hidden' name='child_reload' value='<?php echo $child_reload;?>'>
				<input type='hidden' name='child_reload_owner_id' value='<?php echo $child_reload_owner_id;?>'>
				<input type='hidden' name='child_reload_tax' value='<?php echo $child_reload_tax;?>'>
				<input type='hidden' name='child_reload_tax_details_id' value='<?php echo $child_reload_tax_details_id;?>'>
				<input type='hidden' name='child_reload_fee' value='<?php echo $child_reload_fee;?>'>
				<input type='hidden' name='child_reload_fee_details_id' value='<?php echo $child_reload_fee_details_id;?>'>
				<input type='hidden' name='owner_id' value='<?php echo $owner_id;?>'>
				<input type='hidden' name='_search' value='<?php echo $_search;?>'>
				<input type='hidden' name='submit_button' value=<?php echo $submit_button;?>>
				<input type='hidden' name='trekcom' value=<?php echo $comm; ?>>
	
<?php 
if ($comm=='Add') {
	include'includes/form_mtop_owner.php';
} elseif ($comm=='Edit') {
	include'includes/form_mtop_owner.php';
	//$rrtt='ebpls_owner';
	//include'ebpls1222.php';
} elseif ($comm=='ctc') {
	include'form_ctc_individual_application.php';
}
 else {
	if ($search=='') 
	{		
?>
          		<table border=0 cellspacing=1 cellpadding=1 width='100%'>
            	<!--// start of the owner information //-->
            			<tr>
            			<td align="center" valign="top" class='header2' colspan=4 >
                			Individual CTC</td>
            		</tr>
            		<tr>
	        			<td align="right" valign="top" class='normal' colspan=4>&nbsp; </td>
            		</tr>
            		<tr>
              			<td align="right" valign="top" class='normal' colspan=1> &nbsp;
                		</td>
              			<td align="left" valign="top" class='normal' colspan=3>&nbsp;
<?php
//--- check if new  / renew , then have a search button
		if ($owner_id<>0)
		{
			if ($comm=='Attach') {
				$chkifnew = mysql_query("select * from ebpls_owner where owner_id=$owner_id") or die("kaboom".mysql_error());
				$chkifnew = mysql_fetch_row($chkifnew);
				$owner_id = $chkifnew[0];
				$owner_first_name=$chkifnew[1];
				$owner_middle_name=$chkifnew[2];
				$owner_last_name=$chkifnew[3];
				$owner_gender=$chkifnew[15];
			}
		} else 
		{
			$owner_id=0;
		}
		if($is_new)
		{
			echo "&nbsp<a href='index.php?part=4&itemID_=1001&permit_type=CTC&owner_id=$owner_id&busItem=CTC&ctc_type=INDIVIDUAL&search=Search' class='subnavwhite'>Search Taxpayer</a>&nbsp";
			if($owner_id > 0){
				echo "<a href='index.php?part=4&itemID_=1001&permit_type=CTC&owner_id=$owner_id&busItem=CTC&ctc_type=INDIVIDUAL&comm=Edit' class='subnavwhite'>Update Taxpayer</a>&nbsp;";
			}
		}
		else
		{
			echo "&nbsp<a href='javascript:showNewWin(\"owner_update.php?part=100&owner_id=$owner_pop_id&mode=renew\",820,500);' class='subnavwhite'>Update Taxpayer</a>&nbsp";
		}
		echo "&nbsp<a href='index.php?part=4&itemID_=1001&permit_type=CTC&owner_id=0&ctc_type=INDIVIDUAL&busItem=CTC&comm=Add' class='subnavwhite'>Add New Taxpayer</a>&nbsp";
		$getinfoname = mysql_query("select * from ebpls_owner where owner_id=$owner_id");
		$getinfoname1 = mysql_fetch_array($getinfoname);
		$owner_first_name = $getinfoname1[owner_first_name];
		$owner_middle_name = $getinfoname1[owner_middle_name];
		$owner_last_name = $getinfoname1[owner_last_name];
		$owner_gender = $getinfoname1[owner_gender];
?>
						<input type='hidden' name='owner_id' maxlength=25 class='text180'  value="<?php echo $owner_id; ?>">
              			</td>
            		</tr>
            		<tr>
            			<td align="right" valign="top" class='normal' width='16.67%'> <font color="#FF0000">*
                			</font>First Name : </td>
              			<td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <input type='text' name='owner_first_name' maxlength=60 class='text180'  value="<?php echo $owner_first_name; ?>" disabled>
              			</td>
              			<td align="right" valign="top" class='normal' width='16.67%'> <font color="#FF0000">*
                			</font>Middle Name : </td>
              			<td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <input type='text' name='owner_middle_name' maxlength=60 class='text180' value="<?php echo $owner_middle_name; ?>" disabled>
              			</td>
            		</tr>
            		<tr>
              			<td align="right" valign="top" class='normal' width='16.67%'> <font color="#FF0000">*
                			</font>Last Name : </td>
              			<td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <input type='text' name='owner_last_name' maxlength=60 class='text180' value="<?php echo $owner_last_name; ?>" disabled>
              			</td>
              			<td align="right" valign="top" class='normal' width="16.67%">Gender : </td>
              			<td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <select name='owner_gender' class='select100' readonly disabled>
                			<option value='M' <?php echo (! strcasecmp($owner_gender,'M')) ? ('selected') : (''); ?> >M</option>
                  			<option value='F' <?php echo (! strcasecmp($owner_gender,'F')) ? ('selected') : (''); ?>>F</option>
                			</select> </td>
            		</tr>
     				<tr>
     				<table>
     					<tr>
	    		        	<td align="center" valign="top" class='normal' colspan=4> &nbsp;
                				&nbsp; <input type='submit' name='_search' value='S U B M I T' <? echo $is_disabled; ?>>
                				&nbsp; <input type='button' name='_RESET' value='R E S E T'  <? echo $is_disabled;?> onclick="parent.location='index.php?part=4&itemID_=1001&busItem=CTC&permit_type=CTC&ctc_type=INDIVIDUAL&item_id=CTC'">
              				</td>
              			</tr>
              		</table>
              		</tr>
              	</table>
        		<!--// end of the owner information //-->
            
<?php
	} else {
?>
				<table border=0 cellspacing=0 cellpadding=0 width='720'>
					<tr><td align="center" valign="center" class='header'> Owner Search</td></tr>
					<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
					<tr>
						<td align="center" valign="center" class='title'>
						<!--<form name="_FRM" method="POST" action="">-->
						<!--// start of the table //-->
						<table border=0 cellspacing=0 cellpadding=0  width='720'>
							<tr><td align="center" valign="center" class='title' height=5 colspan=2></td></tr>
							<tr>				
            					<td align="left" valign="top" class='normalbold' colspan=2 height=15> 
                					<div align="center">SEARCH BY 
                  					<input type='hidden' name='_search_' >
                  					<input type='hidden' name='_search_owner_id_' >
                					</div></td>
				 
							</tr> 
							<tr>
								<td align="right" valign="top" class='normal' width=353>
									Lastname :
								</td>
								<td width="367" align="left" valign="top" class='normal'>
									&nbsp;<input type='text' name='search_lastname' maxlength=255 class='text180' value='<?php echo $search_lastname; ?>'>
								</td>
							</tr> 
							<tr>
								<td align="right" valign="top" class='normal' colspan=2><img src='images/spacer.gif' height=20 width=5>
								</td>
							</tr>
							<tr>
            			 		<td align="center" valign="top" class='normal' > <div align="right">
                  					<input type='submit' name='_search' value=' S E A R C H ' >
                					</div></td>
              					<td align="left" valign="top" class='normal' > &nbsp; &nbsp;
									<input type='reset' name='_RESET' value=' R E S E T ' >&nbsp;
								</td>
							</tr>
							<tr>
								<td align="right" valign="top" class='normal' colspan=2>&nbsp; 
								</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr><td>
						<table border=0 cellspacing=0 cellpadding=0  width='90%'>
							<tr>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="20%">Last Name</td>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="20%">First Name</td>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="20%">Middle Name</td>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="10%">House No/ Name</td>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="10%">Barangay</td>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="5%">Gender</td>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="10%">Civil Status</td>
								<td align='center' class='bold' bgcolor='#E6FFE6' width="5%">Action</td>
							</tr>
						<!---// end of the table //-->
			<?php
				if($_search ==' S E A R C H ')
				{	
					$itemID_=ctcinsearch;
					$searchowner = @mysql_query("select * from ebpls_owner where owner_last_name like '$search_lastname%'");
					$searchcnt = mysql_num_rows($searchowner);
					if ($searchcnt == 0)
					{
						echo "<div align=center><font size=2 color=red><b>No Matching Record!</b></font></div>";
					}
					//else
					//{
					while ($searchown = mysql_fetch_row($searchowner)) {
						$searchbrgy = @mysql_query("select * from ebpls_barangay where barangay_code='$searchown[6]'");
						$iBrgy = @mysql_fetch_array($searchbrgy);
						print "<tr>";
						print "<td>$searchown[3]</td>\n";
						print "<td>$searchown[1]</td>\n";
						print "<td>$searchown[2]</td>\n";
						print "<td>$searchown[5]</td>\n";
						print "<td>$iBrgy[barangay_desc]</td>\n";
						print "<td>$searchown[14]</td>\n";
						print "<td>$searchown[13]</td>\n";
						print "<td><a href='index.php?part=4&itemID_=1001&owner_id=$searchown[0]&permit_type=CTC&busItem=CTC&ctc_type=INDIVIDUAL&comm=Attach'>Attach</a></td></tr>\n";
				}
			//}
		} 
		}
		?>
						</table>
					<!--</form>-->
	</td>
</tr>
<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<?php
}
?>
            </tr>
          </table>
	
	</td>
       </tr>
<tr><td align="center" valign="center" class='title' height=10></td></tr>
</table>
<!---// end of the table //-->
</form>
</div>
</body></html>

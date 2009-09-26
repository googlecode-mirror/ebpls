<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("includes/variables.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
$is_new=true;
//$dbLink = get_db_connection();
if ($owner_id == "" and $business_id == "") {
        $is_disabled = "disabled";
} else {
        $is_disabled = "";
}
$saveenabled = "disabled";
$printenabled = "disabled";
if ($saveur=='saveit') {
	$result = mysql_query("insert into ebpls_business_enterprise
		(business_name , business_branch, business_scale
                , business_lot_no, business_street , business_barangay_code
                , business_zone_code,  business_district_code
                , business_city_code, business_province_code, business_zip_code
                , business_contact_no, business_fax_no , business_email_address
                , business_location_desc , business_building_name
                , business_phone_no, business_category_code, business_dot_acr_no
                , business_sec_reg_no, business_tin_reg_no, business_dti_reg_no
                , business_dti_reg_date, business_date_established, business_start_date
                , business_occupancy_code, business_offc_code, business_main_offc_name
                , business_main_offc_lot_no, business_main_offc_street, business_main_offc_barangay_name
                , business_main_offc_barangay_code, business_main_offc_zone_code
                , business_main_offc_district_code , business_main_offc_city_code
                , business_main_offc_zip_code, business_main_offc_tin_no
                , employee_male, business_no_del_vehicles
                , business_payment_mode, business_nso_assigned_no
                , business_nso_estab_id, business_industry_sector_code
                , business_remarks , business_create_ts, business_update_by
                , business_update_ts, employee_female,retire)
		values
		($business_name, '$business_branch', '$business_scale', '$business_lot_no', 
		'$business_street', '$business_barangay_code','$business_zone_code','$business_district_code'
		,'$business_city_code','$business_province_code','$business_zip_code','$business_contact_no',
		'$business_fax_no','$business_email_address','$business_location_desc',
		'$business_building_name','$business_phone_no','$business_category_code',
		'$business_dot_acr_no','$business_sec_reg_no','$business_tin_reg_no',
		'$business_dti_reg_no','$business_dti_reg_date',
		'$business_date_established','$business_start_date','$business_occupancy_code',
		'$business_offc_code','$business_main_offc_name','$business_main_offc_lot_no',
		'$business_main_offc_street_no','$business_main_offc_barangay_name',
		'$business_main_offc_barangay_code','$business_main_offc_zone_code',
		'$business_main_offc_district_code','$business_main_offc_city_code',
		'$business_main_offc_zip_code','$business_main_offc_tin_no',
		$employees_M,$business_no_del_vehicles,'$business_payment_mode',
		'$business_nso_assigned_no','$business_nso_estab_id',
		'$business_industry_sector_code','$business_remarks',
		now(),'$usern',now(),$employees_F,1)")
		or die ("die insert1".mysql_error());
	$getbusid = mysql_query("select * from ebpls_business_enterprise where business_name='$business_name' and business_branch='$business_branch' order by business_name desc limit 1");
	$getbusid = mysql_fetch_row($getbusid);
	$business_id=$getbusid[0];
	
$search='';
?>
<body onLoad="javascript: alert('Business has been added.');"></body>
<?

}
if ($upOwner=='UPDATE') {
$search='';
}
if ($_search=='S U B M I T') {
	
	if ($business_name=='') {
?> 
	<body onload='javascript:alert("Blank Business");'></body>
<?php
$search='';
} else {
	$comm='ctc';
}
$search='';
$_search='';
}

if ($ctc_process=='PROCESS') {
		$getinterest = mysql_query("select interest_rate,ceiling_rate, penalty_date from ebpls_ctc_interest where ctc_type='Corporate'") or die("ctc error".mysql_error());
		$getinterest = mysql_fetch_row($getinterest);
		$ctc_basic_tax=500;
		$ctc_additional_tax1_due=((int)($ctc_additional_tax1/5000))*2;
		$ctc_additional_tax2_due=((int)($ctc_additional_tax2/5000))*2;
		$ctc_total_amount_due=$ctc_basic_tax+$ctc_additional_tax1_due+$ctc_additional_tax2_due+$ctc_additional_tax3_due;
		if ($ctc_total_amount_due > 10500) {
			$ctc_total_amount_due=10500;
		}
		if ($ctc_total_amount_due > $getinterest[1]) {
                	$ctc_total_amount_due = $getinterest[1];
        	} else {
                	$ctc_total_amount_due = $ctc_total_amount_due;
        	}	
		$nhyear = date('Y');
		$nhdate = $getinterest[2];
		$nhdatetoday = date('n');
		echo "$nhdatetoday > $nhdate VooDoo";
		if ($nhdatetoday >= $nhdate) {
			$ctc_total_interest_due = (($ctc_total_amount_due * $getinterest[0])/100)*($nhdatetoday);
		} else {
			$ctc_total_interest_due = 0;
		}
		$ctc_total_amount_paid=$ctc_total_amount_due+$ctc_total_interest_due;
// 		$ctc1_additional_tax1=$ctc_additional_tax1;
// 		$ctc1_additional_tax2=$ctc_additional_tax2;
// 		$ctc1_additional_tax1_due=$ctc_additional_tax1_due;
// 		$ctc1_additional_tax2_due=$ctc_additional_tax2_due;
// 		$ctc1_total_amount_due=$ctc_total_amount_due;
// 		$ctc1_total_interest_due=$ctc_total_interest_due;
// 		$ctc1_total_amount_paid=$ctc_total_amount_paid;
// 		$ctc_additional_tax1=number_format($ctc_additional_tax1,2);
// 		$ctc_additional_tax2=number_format($ctc_additional_tax2);
// 		$ctc_additional_tax1_due=number_format($ctc_additional_tax1_due);
// 		$ctc_additional_tax2_due=number_format($ctc_additional_tax2_due);
// 		$ctc_total_amount_due=number_format($ctc_total_amount_due);
// 		$ctc_total_interest_due=number_format($ctc_total_interest_due);
// 		$ctc_total_amount_paid=number_format($ctc_total_amount_paid);
		$comm='ctc';
		$ctc_process='';
		$action="";
		$_search='';
		$search='';
		$saveenabled = "";
		
}
if ($ctc_process=='SAVE') {
	if ($ctc_code=="") {
	echo "<div align=center><font color=red><b><i>Enter CTC Number or Invalid CTC Number!!!</i></b></font</div>";
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
		if ($ctc_acct_code=='') {
                        $ctc_acct_code="";
                }
        $chkduplicaterec = mysql_query("select ctc_code from ebpls_ctc_business where ctc_code='$ctc_code'");
		//echo "select ctc_code from ebpls_ctc_business where ctc_code='$ctc_code'";
		$chkduplicaterec1 = mysql_num_rows($chkduplicaterec);
		if ($chkduplicaterec1 > 0) {
		?>
		<body onLoad="javascript: alert('Duplicate CTC Code Found'); _FRM.ctc_code.focus();"></body>
		<?
		$ctc_basic_tax=floatval(str_replace(",","",$ctc_basic_tax));
		$ctc_additional_tax1=floatval(str_replace(",","",$ctc_additional_tax1));
		$ctc_additional_tax2=floatval(str_replace(",","",$ctc_additional_tax2));
		$ctc_additional_tax1_due=floatval(str_replace(",","",$ctc_additional_tax1_due));
		$ctc_additional_tax2_due=floatval(str_replace(",","",$ctc_additional_tax2_due));
		$ctc_total_amount_paid=floatval(str_replace(",","",$ctc_total_amount_paid));
		$ctc_total_interest_due=floatval(str_replace(",","",$ctc_total_interest_due));
		$ctc_process='';
		$comm='ctc';
		$_search='';
		$search='';
		$saveenabled = "";
	} else {
        //$ctc1_additional_tax1=$ctc_additional_tax1;
        //echo $ctc1_additional_tax1."<br>";
		//$ctc_basic_tax=number_format($ctc_basic_tax,2,'.','');
		//$ctc_additional_tax1=round($ctc_additional_tax1,2);
		//$ctc_additional_tax2=round($ctc_additional_tax2,2);
		//$ctc_total_interest_due=number_format($ctc_total_interest_due,2,'.','');
		//$ctc_tax_exempted=number_format($ctc_tax_exempted,2,'.','');
		//$ctc_total_amount_paid=number_format($ctc_total_amount_paid,2,'.','');
		$ctc_basic_tax=floatval(str_replace(",","",$ctc_basic_tax));
		$ctc_additional_tax1=floatval(str_replace(",","",$ctc_additional_tax1));
		$ctc_additional_tax2=floatval(str_replace(",","",$ctc_additional_tax2));
		$ctc_additional_tax1_due=floatval(str_replace(",","",$ctc_additional_tax1_due));
		$ctc_additional_tax2_due=floatval(str_replace(",","",$ctc_additional_tax2_due));
		$ctc_total_amount_due=floatval(str_replace(",","",$ctc_total_amount_due));
		$ctc_total_interest_due=floatval(str_replace(",","",$ctc_total_interest_due));
		$ctc_total_amount_paid=floatval(str_replace(",","",$ctc_total_amount_paid));
		$savetoctc = mysql_query("insert into ebpls_ctc_business values 
					('$ctc_code', '$ctc_place_issued', now(), $yearnow, 
					'$ctc_company', '', '$ctc_tin_no', '$ctc_organization_type', '$ctc_place_of_incorporation', 
					'$ctc_business_nature', $ctc_basic_tax, $ctc_additional_tax1, $ctc_additional_tax2, $ctc_total_interest_due, 
					'$ctc_company_address', '$ctc_incorporation_day', $ctc_total_amount_due,'$ctc_acct_code')") or die(mysql_error());
		$ctc_process='';
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
		<tr><td align="center" valign="center" class='header'  width='100%'>Community Tax Certificate Tax Payer Search</td></tr>
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
				<input type='hidden' name='ctc1_additional_tax1_due' value=<?php echo $ctc1_additional_tax1_due;?>>
				<input type='hidden' name='ctc1_additional_tax2_due' value=<?php echo $ctc1_additional_tax2_due;?>>
				<input type='hidden' name='ctc1_additional_tax3_due' value=<?php echo $ctc1_additional_tax3_due;?>>
				<input type='hidden' name='ctc1_basic_tax' value=<?php echo $ctc1_basic_tax;?>>
				<input type='hidden' name='ctc1_total_amount_paid' value=<?php echo $ctc1_total_amount_paid;?>>
				<input type='hidden' name='ctc1_total_interest_due' value=<?php echo $ctc1_total_interest_due;?>>
				<input type='hidden' name='exempted' value=<?php echo $exempted;?>>
				<input type='hidden' name='ctc1_additional_tax1' value=<?php echo $ctc1_additional_tax1;?>>
				<input type='hidden' name='ctc1_additional_tax2' value=<?php echo $ctc1_additional_tax2;?>>
				
<?php 
if ($comm=='Add') {
	$addbiz='Save';
	include'ebpls1224.php';
	$business_id='';
	$_search='';
	$search='';

} elseif ($comm=='ctc') {
	include'form_ctc_business_application.php';
} else {
	if ($search=='') 
	{		
?>
          		<table border=0 cellspacing=1 cellpadding=1 width='100%'>
            	<!--// start of the owner information //-->
            			<tr>
            			<td align="center" valign="top" class='header2' colspan=4 >
                			Business Information</td>
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
		if ($business_id<>0)
		{
			if ($comm=='Attach') {
				$chkifnew = mysql_query("select * from ebpls_business_enterprise where business_id=$business_id") or die("kaboom".mysql_error());
				$chkifnew = mysql_fetch_row($chkifnew);
				$business_id = $chkifnew[0];
				$business_name=$chkifnew[2];
				$business_branch=$chkifnew[3];
				
			}
		} else 
		{
			$business_id=0;
		}
		if($is_new)
		{
			echo "&nbsp<a href='index.php?part=4&itemID_=1002&permit_type=CTC&busItem=CTC&business_id=$business_id&ctc_type=BUSINESS&search=Search' class='subnavwhite'>Search Business</a>&nbsp";
			if($business_id > 0){
				echo "<a href='index.php?part=4&itemID_=1002&permit_type=CTC&busItem=CTC&business_id=$business_id&ctc_type=BUSINESS&comm=Add' class='subnavwhite'>Update Business</a>&nbsp;";
			}
		}
		else
		{
			echo "&nbsp<a href='index.php?part=4&itemID_=1002&permit_type=CTC&busItem=CTC&business_id=$business_id&ctc_type=BUSINESS&comm=Add' class='subnavwhite'>Update Business</a>&nbsp";
		}
		echo "&nbsp<a href='index.php?part=4&itemID_=1002&permit_type=CTC&busItem=CTC&business_id=&ctc_type=BUSINESS&comm=Add' class='subnavwhite'>Add New Business</a>&nbsp";
		$filltext = mysql_query("select * from ebpls_business_enterprise where business_id=$business_id") or die(mysql_error());
		$filltext1=mysql_fetch_array($filltext);
		$business_name=$filltext1[business_name];
		$business_branch=$filltext1[business_branch];
?>
						<input type='hidden' name='business_id' maxlength=25 class='text180'  value="<?php echo $business_id; ?>">
              			</td>
            		</tr>
            		<tr>
            			<td align="right" valign="top" class='normal' width='16.67%'> <font color="#FF0000">*
                			</font>Business Name : </td>
              			<td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <input type='text' name='business_name' maxlength=60 class='text180'  value='<?php echo $business_name; ?>' readonly>
              			</td>
              			<td align="right" valign="top" class='normal' width='16.67%'> <font color="#FF0000">*
                			</font>Business Branch : </td>
              			<td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <input type='text' name='business_branch' maxlength=60 class='text180' value='<?php echo $business_branch; ?>' readonly>
              			</td>
            		</tr>
            		<tr>
     				<table>
     					<tr>
	    		        	<td align="center" valign="top" class='normal' colspan=4> &nbsp;
                				&nbsp; <input type='submit' name='_search' value='S U B M I T' <? echo $is_disabled; ?>>
                				&nbsp; <input type='reset' name='_RESET' value='C L E A R' <? echo $is_disabled;?> onclick="parent.location='index.php?part=4&itemID_=1002&busItem=CTC&permit_type=CTC&ctc_type=BUSINESS&item_id=CTC'">
              				</td>
              			</tr>
              		</table>
              		</tr>
              	</table>
        		<!--// end of the owner information //-->
            
<?php
	} else {
?>
				<table border=0 cellspacing=0 cellpadding=0 width='100%'>
					<tr><td align="center" valign="center" class='header'> Business Search</td></tr>
					<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
					<tr>
						<td align="center" valign="center" class='title'>
						<!--<form name="_FRM" method="POST" action="">-->
						<!--// start of the table //-->
						<table border=0 cellspacing=0 cellpadding=0  width='100%'>
							<tr><td align="center" valign="center" class='title' height=5 colspan=2></td></tr>
							<tr>				
            					<td align="left" valign="top" class='normalbold' colspan=2 height=15> 
                					<div align="center">SEARCH BY 
                  					<input type='hidden' name='_search_' >
                  					<input type='hidden' name='_search_business_id_' >
                					</div></td>
				 
							</tr> 
							<tr>
								<td align="right" valign="top" class='normal' width=50%>
									Business Name :
								</td>
								<td width="50%" align="left" valign="top" class='normal'>
									&nbsp;<input type='text' name='search_bizname' maxlength=255 class='text180' value='<?php echo $search_bizname; ?>'>
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
						<table border=0 cellspacing=0 cellpadding=0  width='90%' align=center>
						<tr>
								<td align='center' class='bold' bgcolor='#E6FFE6'>Business Name</td>
								<td align='center' class='bold' bgcolor='#E6FFE6'>Business Branch</td>
								<td align='center' class='bold' bgcolor='#E6FFE6'>&nbsp;</td>
							</tr>
						<!---// end of the table //-->
			<?php
				if($_search ==' S E A R C H ')
				{	
					$itemID_=ctcinsearch1;
					$searchbusiness = mysql_query("select * from ebpls_business_enterprise where business_name like '$search_bizname%' order by business_name") or die("kablog".mysql_error());
					$searchcnt = mysql_num_rows($searchbusiness);
			
					if ($searchcnt == 0)
					{
						echo "<div align=center><font size=2 color=red><b>No Matching Record</b></font></div>";
					}
					//else
					//{
					while ($searchbiz = mysql_fetch_row($searchbusiness)) {
						print "<tr>";
						print "<td align=center>$searchbiz[2]</td>\n";
						print "<td align=center>$searchbiz[3]</td>\n";
						print "<td align=center><a href='index.php?part=4&itemID_=1002&permit_type=CTC&busItem=CTC&business_id=$searchbiz[0]&ctc_type=BUSINESS&comm=Attach'>Attach</a></td></tr>\n";
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

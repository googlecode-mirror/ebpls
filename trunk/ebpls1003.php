<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");



//setUrlRedirect('index.php?part=999');


//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;
$debug 		= false;

require_once "includes/variables.php";

if ($business_id<>''){

$result = mysql_query("Select * from ebpls_business_enterprise where business_id = $business_id") or die("1");
$datarow = mysql_fetch_row($result);

} else {
$cntbus = mysql_query("select count(business_id)+ 1 as total from ebpls_business_enterprise") or die("cnt");
$bus=mysql_fetch_row($cntbus);
$business_id=$bus[0];
$datarow[2]=$search_bus;
}
?>
<head>
<title>Business Maintenance</title>
<form method=post name=_FRM action='index.php?part=4&itemID_=1002&business_id=&ctc_type=BUSINESS&comm=Add' onSubmit=''>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<script language='Javascript' src='javascripts/default.js'></script>
</head>
<body>
<input type=hidden name=saveur value='saveit'>
<div align="CENTER">
<!---// start of the table //-->
<input type='hidden' name='owner_id' maxlength=25 class='text180'  value="<?php echo $owner_id; ?>">
<input type='hidden' name='owner_last_name' maxlength=25 class='text180'  value="<?php echo $owner_last_name; ?>">
<input type='hidden' name='owner_first_name' maxlength=25 class='text180'  value="<?php echo $owner_first_name; ?>">
<input type='hidden' name='owner_middle_name' maxlength=25 class='text180'  value="<?php echo $owner_middle_name; ?>">
<input type='hidden' name='business_id' maxlength=25 class='text180'  value="<?php echo $business_id; ?>"> 
<input type='hidden' name='maintenance_mode' maxlength=25 class='text180'>
<table width='90%' border=0 cellpadding=1 cellspacing=1>
  <!--// start of the owner information //-->
  <!--// end of the owner information //-->
  <!--// start of the business permit information //-->
  <tr> 
    <td align="center" valign="top" class='header2' colspan=4 > Business Enterprise Maintenance</td>
  </tr>
  <tr> 
    	<td align="left" valign="top" class='normalred' colspan=4>&nbsp;</td>
  </tr>
  <tr> 
  	<td align="left" valign="top" class='normalred' colspan=4></td>
  </tr>
  <tr> 
      	<td align="left" valign="top" class='normalred' colspan=4>&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top" class='normalbold' colspan=4>Business Information </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Business Name : </td>
    <td align="left" valign="top" class='normal' >&nbsp; <input type='text' name='business_name' maxlength=255 class='text180'  value="<?php echo stripslashes($datarow[2]); ?>">
      </td>
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Business Branch
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_branch' maxlength=255 class='text180'  value="<?php echo $datarow[3]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal' > Business Scale : </td>
    <td align="left" valign="top" class='normal'>&nbsp;&nbsp;<select name='business_scale'>
		<?php
			if ($addbus<>'addbus') {
		?>
    		    <option value='<?php echo $datarow[70]; ?>'> <?php echo $datarow[70]; ?>	
		<?php
    		   	} //else {    	
	    ?>
        		<option value='MICRO'>Micro</option>
        		<option value='COTTAGE'>Cottage</option>	
        		<option value='SMALL'>Small</option>
        		<option value='MEDIUM'>Medium</option>
        		<option value='LARGE'>Large</option>
    	<?php
        		//}
        ?>
      </select> </td>
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Payment 
      Mode : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <select name='business_payment_mode' class='select100'>
		<?php
			if ($addbus<>'addbus') {
		?>
    			<option value='<?php echo $datarow[44]; ?>'> <?php echo $datarow[44]; ?>
    	<?php
				} //else {
		?> 
  <!---	  	    <option value='MONTHLY'>Monthly</option> -->
        		<option value='QUARTERLY' selected>Quarterly</option>
        		<option value='SEMI-ANNUAL'>Semi-Annual</option>
        		<option value='ANNUAL'>Annual</option>
    	<?php
				//}
		?>
      </select> </td>
  </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal' >&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold' >Business Contact Information </td>
    
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Lot 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_lot_no' maxlength=255 class='text180'  value="<?php echo $datarow[5]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>District 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_district_code','ebpls_district','district_code','district_desc',$datarow[10]);?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Street 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_street' maxlength=255 class='text180'  value="<?php echo $datarow[6]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>City 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$datarow[11]);?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Barangay 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
      <?php  echo get_select_data($dbLink,'business_barangay_code','ebpls_barangay','barangay_code',
	'barangay_desc',$datarow[7]);?>
    </td>
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Province 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_province_code','ebpls_province','province_code','province_desc',$datarow[12]);?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Zone 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_zone_code','ebpls_zone','zone_code','zone_desc',$datarow[8]);?> 
    </td>
    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Zip 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_zip_code','ebpls_zip','zip_code','zip_desc',$datarow[13]);?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > Contact Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_contact_no' maxlength=255 class='text180'  value="<?php echo $datarow[14]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal' > Fax Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_fax_no' maxlength=255 class='text180'  value="<?php echo $datarow[15]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal'><div align="left"></div></td>
  </tr>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold'  > Business Other Information  </td>
    
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Date 
      Established :<br> (yyyy-mm-dd) 
      <input type='hidden' name='business_date_established' maxlength=10 class='text180' value="<?php echo $datarow[27];?>"> 
    </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
      <?php set_form_date($datarow[27],3) ?>
    </td>
    <td align="right" valign="top" class='normal' > <font color="#FF0000">* </font>Number 
      of Delivery Vehicles : </td>
    <?php
    if ($addbiz=='Save' || $itemID_==1002) {
	?>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_no_del_vehicles' maxlength=255 class='text180'  value=0> 
	    </td>
	<?php
	} elseif ($addbiz=='update' || $itemID_==1002) {
	?>
    	<td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_no_del_vehicles' maxlength=255 class='text180'  value="<?php echo $datarow[43]; ?>"> 
    	</td>
    <?php
	}
	?>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Start 
      Date : <br> (yyyy-mm-dd)
      <input type='hidden' name='business_start_date' maxlength=10 class='text180' value="<?php echo $datarow[28];?>"> 
    </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
      <?php set_form_date($datarow[28],4) ?>
    </td>
    <td align="right" valign="top" class='normal'  > Location Desc : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_location_desc' maxlength=255 class='text180'  value="<?php echo $datarow[18]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Occupancy 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_occupancy_code','ebpls_occupancy_type','occupancy_type_code','occupancy_type_desc',$datarow[29]);?> 
    </td>
    <td align="right" valign="top" class='normal'  > Remarks : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_remarks' maxlength=255 class='text180'  value="<?php echo $datarow[50]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > <font color="#FF0000">* </font>Ownership Type
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_category_code','ebpls_business_category','business_category_code','business_category_desc',$datarow[21]);?> 
    </td>
    <td align="right" valign="top" class='normal'  > &nbsp; </td>
    <td align="left" valign="top" class='normal'>&nbsp;  
    </td>
  </tr>
  <tr>
    <td align="right" valign="top" class='normal' > <font color="#FF0000">* </font>Number 
      of Employees : </td>
    <?php
    if ($addbiz=='Save' || $itemID_==1002) {
	?>
	    <td align="left" valign="top" class='normal' >&nbsp; Male <input type='text' name='employees_M' maxlength=3 size=3 value=0> &nbsp; Female <input type='text' name='employees_F' maxlength=3 size=3 value=0>    
	    </td>
	<?php
    } elseif ($addbiz=='update' || $itemID_==1002) {
	?>
    	<td align="left" valign="top" class='normal' >&nbsp; Male <input type='text' name='employees_M' maxlength=3 size=3 value="<?php echo $datarow[42]; ?>"> &nbsp; Female <input type='text' name='employees_F' maxlength=3 size=3 value="<?php echo $datarow[72]; ?>">    
    	</td>
    <?php
	}
	?>
    <td align="right" valign="top" class='normal'  >&nbsp;</td>
    <td align="left" valign="top" class='normal'>&nbsp; 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > Email Address : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_email_address' maxlength=255 class='text180'  value="<?php echo $datarow[16]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'  > &nbsp; </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal'  >&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold'> Business Necessities Information</td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >DOT ACR Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_dot_acr_no' maxlength=255 class='text180'  value="<?php echo $datarow[22]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'  >DTI Registration Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_dti_reg_no' maxlength=255 class='text180'  value="<?php echo $datarow[25]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >SEC Registration Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_sec_reg_no' maxlength=255 class='text180'  value="<?php echo $datarow[23]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'  > DTI Registration Date : <br> (yyyy-mm-dd) 
      <input type='hidden' name='business_dti_reg_date' maxlength=10 class='text180' value="<?php echo $datarow[26];?>"> 
    </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
      <?php set_form_date($datarow[26],2) ?>
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >BIR Registration Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_tin_reg_no' maxlength=255 class='text180'  value="<?php echo $datarow[24]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'  >NSO Assigned Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_nso_assigned_no' maxlength=255 class='text180'  value="<?php echo $datarow[47]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  >Industry Sector : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_industry_sector_code','ebpls_industry_sector','industry_sector_code','industry_sector_desc',$datarow[49]);?> 
    </td>
    <td align="right" valign="top" class='normal' >NSO Established ID : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_nso_estab_id' maxlength=255 class='text180'  value="<?php echo $datarow[48]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal'  >&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4" align="left" valign="top" class='normalbold'  >Business Main Information </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > Office Building Name: 
    </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_building_name' maxlength=255 class='text180'  value="<?php echo $datarow[19]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'> Office District : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_main_offc_district_code','ebpls_district','district_code','district_desc',$datarow[37]);?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > Office Name: </td>
    <td align="left" valign="top" class='normal' >&nbsp; <input type='text' name='business_main_offc_name' maxlength=255 class='text180'  value="<?php echo $datarow[31]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'> Office City : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_main_offc_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$datarow[38]);?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > Office Lot : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_main_offc_lot_no' maxlength=255 class='text180'  value="<?php echo $datarow[32]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal'> Office Zip : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_main_offc_zip_code','ebpls_zip','zip_code','zip_desc',$datarow[39]);?> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal' > Office Street : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_main_offc_street_no' maxlength=255 class='text180'  value="<?php echo $datarow[33]; ?>"> 
    </td>
    <td align="right" valign="top" class='normal' >Office TIN Number : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_main_offc_tin_no' maxlength=255 class='text180'  value="<?php echo $datarow[40]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'  > Office Barangay : </td>
    <td align="left" valign="top" class='normal'>&nbsp; 
      <?php  echo get_select_data($dbLink,'business_main_offc_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$datarow[35]);?>
    </td>
    <td align="right" valign="top" class='normal'  > Office Phone Number 
      : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='business_phone_no' maxlength=255 class='text180'  value="<?php echo $datarow[20]; ?>"> 
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top" class='normal'> Office Zone : </td>
    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'business_main_offc_zone_code','ebpls_zone','zone_code','zone_desc',$datarow[36]);?> 
    </td>
    <td align="right" valign="top" class='normal'  >&nbsp;</td>
    <td align="left" valign="top" class='normal'>&nbsp;
    </td>
  </tr>
      <td colspan="4" align="right" valign="top" class='normal'>&nbsp;</td>
  </tr>
  <tr> 
      <td align="center" valign="top" class='header2' colspan=4 > <img src='images/spacer.gif' height=10 width=10></tr>
  <tr>
	<td align="center" valign="top" class='normal' colspan=4>
	<input type='button' name='_ADDNEW1'  value='S A V E' onClick='javascript:validate_add_new_business_application();'>
	<input type=button value='CANCEL' onClick="history.go(-1)">
	<input type='reset' name='_RESET' onClick='' value='R E S E T' >
	</td>
   </tr>
  <tr> 
    <td colspan="4" align="right" valign="top" class='normal'>&nbsp;</td>
  </tr>
  <tr> 
      <td colspan="4" align="right" valign="top" class='normal'>&nbsp;</td>
  </tr>
</table>
</div>
</form>
</body>
</html>
<script language="Javascript">


function validate_add_new_business_application()
{
		var _FRM = document._FRM;
		var msgTitle = "Business Permit Application\n";
/*
		
		if( isBlank(_FRM.owner_id.value) == true)
		{
			alert( msgTitle + "Fatal Error : Cant add a business w/o an owner !");
			_FRM.owner_id.focus();
			return false;
		}
*/
		//--- validate the business details
		if( isBlank(_FRM.business_name.value) == true)
		{
			alert( msgTitle + "Please input a valid business name!");
			_FRM.business_name.focus();
			return false;
		}
		if( isBlank(_FRM.business_branch.value) == true)
		{
			alert( msgTitle + "Please input a valid business branch name!");
			_FRM.business_branch.focus();
			return false;
		}


		if( isBlank(_FRM.business_lot_no.value) == true)
		{
			alert( msgTitle + "Please input a valid business lot #!");
			_FRM.business_lot_no.focus();
			return false;
		}
		if( isBlank(_FRM.business_street.value) == true)
		{
			alert( msgTitle + "Please input a valid business street #!");
			_FRM.business_street.focus();
			return false;
		}
	//	
	//	if( isBlank(_FRM.business_dot_acr_no.value) == true)
	//	{
	//		alert( msgTitle + "Please input a valid business DOT ACR #!");
	//		_FRM.business_dot_acr_no.focus();
	//		return false;
	//	}
		
	//	if( isBlank(_FRM.business_dti_reg_no.value) == true)
	//	{
	//		alert( msgTitle + "Please input a valid business DTI REG #!");
	//		_FRM.business_dti_reg_no.focus();
	//		return false;
	//	}
	//	
	//	if( isBlank(_FRM.business_sec_reg_no.value) == true)
	//	{
	//		alert( msgTitle + "Please input a valid business SEC REG #!");
	//		_FRM.business_sec_reg_no.focus();
	//		return false;
	//	}
	//if( isBlank(_FRM.business_main_offc_tin_no.value) == true)
	//{
	//		alert( msgTitle + "Please input a valid business TIN REG #!");
	//		_FRM.business_main_offc_tin_no.focus();
	//		return false;
	//	}
	//	if( isBlank(_FRM.business_nso_assigned_no.value) == true)
	//	{
	//		alert( msgTitle + "Please input a valid business NSO Assigned #!");
	//		_FRM.business_nso_assigned_no.focus();
	//		return false;
	//	}
	//	if( isBlank(_FRM.business_nso_estab_id.value) == true)
	//	{
	//		alert( msgTitle + "Please input a valid business NSO Established #!");
	//		_FRM.business_nso_estab_id.focus();
	//		return false;
	//	}
	//	
//		if( isBlank(_FRM.business_no_employees.value) == true || isDigit(_FRM.business_no_employees.value) == false)
//		{
//			alert( msgTitle + "Please input a valid # of employees!");
//			_FRM.business_no_employees.focus();
//			return false;
//	
//		}
		if( isBlank(_FRM.business_no_del_vehicles.value) == true  || isDigit(_FRM.business_no_del_vehicles.value) == false)
		{
			alert( msgTitle + "Please input a valid # of dDelivery vehicles!");
			_FRM.business_no_del_vehicles.focus();
			return false;
	
		}
		
		//-- set date

		y_idx = _FRM._YEAR2.options.selectedIndex;
		m_idx = _FRM._MONTH2.options.selectedIndex;
		d_idx = _FRM._DAY2.options.selectedIndex;

		_FRM.business_dti_reg_date.value = _FRM._YEAR2.options[y_idx].value + '-' + _FRM._MONTH2.options[m_idx].value + '-' + _FRM._DAY2.options[d_idx].value;

		if( isBlank(_FRM.business_dti_reg_date.value) == true || isValidDate(_FRM.business_dti_reg_date.value) == false)
		{
			alert( msgTitle + "Please input a valid DTI registration date!");

			return false;
		}

		//-- set date

		y_idx = _FRM._YEAR3.options.selectedIndex;
		m_idx = _FRM._MONTH3.options.selectedIndex;
		d_idx = _FRM._DAY3.options.selectedIndex;

		_FRM.business_date_established.value = _FRM._YEAR3.options[y_idx].value + '-' + _FRM._MONTH3.options[m_idx].value + '-' + _FRM._DAY3.options[d_idx].value;

		if( isBlank(_FRM.business_date_established.value) == true || isValidDate(_FRM.business_date_established.value) == false)
		{
			alert( msgTitle + "Please input a valid established date!");

			return false;
		}


	//-- set date

		y_idx = _FRM._YEAR4.options.selectedIndex;
		m_idx = _FRM._MONTH4.options.selectedIndex;
		d_idx = _FRM._DAY4.options.selectedIndex;

		_FRM.business_start_date.value = _FRM._YEAR4.options[y_idx].value + '-' + _FRM._MONTH4.options[m_idx].value + '-' + _FRM._DAY4.options[d_idx].value;

		if( isBlank(_FRM.business_start_date.value) == true || isValidDate(_FRM.business_start_date.value) == false)
		{
			alert( msgTitle + "Please input a valid start date!");
			return false;
		}


		

		_FRM.submit();
	 	return true;



}
</script>

<?php

function set_add_status($owner_id,$business_name,$business_id)
{
	//--- set the calling page forms
?>
	<script language="Javascript">
		function business_maintenance_status()
		{
			var win_opener  = window.opener;
			var frm 	= win_opener.document._FRM;

			frm.owner_id.value='<?php echo $owner_id;?>';
			frm.business_name.value='<?php echo $business_name;?>';
			alert("Business Maintenance :\n(<?php echo addslashes($business_name);?>) successfully added" );
			win_opener.attachToApplication('<?php echo $owner_id;?>','<?php echo $business_id?>');
			window.close();
		}
		business_maintenance_status();
	</script>
<?php
}
	if($success_flag)
	{
		set_add_status($owner_id,$business_name , $ret_business_id);
	}
?>



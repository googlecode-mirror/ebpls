<?php
//	@eBPLS_PAGE_APP_OWNER: owner criteria page
//	- start page for owner search
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");


//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;

$debug 		= false;

$status_str   	= "";

//--- get the owner
$clsOwner 	= new EBPLSOwner ( $dbLink, $debug );

$add_reload_flag = false;


$reload = mysql_query ("select * from ebpls_mtop_owner where owner_id =$id") or die("SELECT Error: ".mysql_error());
$owner_datarow = mysql_fetch_row($reload);
?>
<html>
<title>Edit Owner</title>
<head>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>

</head>
<body>
<br>
<div align='center'>
<form name="_FRM" method="POST" >
	<input type='hidden' name='mode'>
	<table width='750' border=0 cellpadding=1 cellspacing=1>
	  <!--// start of the owner information //-->
	  <tr> 
	    <td align="center" valign="top" class='subtitleblue' colspan=4 > Motorized Permit Owner Information</td>
	  </tr>
	  <tr> 
	  	<td align="left" valign="top" class='normalred' colspan=4>&nbsp;</td>
	  </tr>
	  <tr> 
	    <td align="left" valign="top" class='normalbold' colspan=4> Personal Details:</td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal' width=125> <font color="#FF0000">* 
	      </font>First Name : </td>
	    <td align="left" valign="top" class='normal' width=250>&nbsp; <input type='text' name='owner_first_name' maxlength=60 class='text180'  value="<?php $owner_datarow[OWNER_FIRST_NAME]; ?>">	
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Civil 
	      Status : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <select name='owner_civil_status'  class='select100'>
		<option value='single' <?php echo (! strcasecmp($owner_datarow[OWNER_CIVIL_STATUS],'single')) ? ('selected') : (''); ?>>Single</option>
		<option value='married' <?php echo (! strcasecmp($owner_datarow[OWNER_CIVIL_STATUS],'married')) ? ('selected') : (''); ?>>Married</option>
		<option value='widowed' <?php echo (! strcasecmp($owner_datarow[OWNER_CIVIL_STATUS],'widowed')) ? ('selected') : (''); ?>>Widowed</option>
		<option value='divorced' <?php echo (! strcasecmp($owner_datarow[OWNER_CIVIL_STATUS],'divorced')) ? ('selected') : (''); ?>>Divorced</option>
	      </select> </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Last 
	      Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_last_name' maxlength=60 class='text180' value="<?php echo $owner_datarow[OWNER_LAST_NAME]; ?>"> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">*</font> 
	      Gender : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <select name='owner_gender' class='select100'>
		<option value='M' <?php echo (! strcasecmp($owner_datarow[OWNER_GENDER],'M')) ? ('selected') : (''); ?> >M</option>
		<option value='F' <?php echo (! strcasecmp($owner_datarow[OWNER_GENDER],'F')) ? ('selected') : (''); ?>>F</option>
	      </select> </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Middle 
	      Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_middle_name' maxlength=60 class='text180' value="<?php echo $owner_datarow[OWNER_MIDDLE_NAME]; ?>"> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Citizenship 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_citizenship' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_CITIZENSHIP]; ?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> Birth Date : 
	      <input type='hidden' name='owner_birth_date' maxlength=10 class='text180' value="<?php echo $owner_datarow[OWNER_BIRTH_DATE];?>"> 
	    </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	      <?php set_form_date($owner_datarow[OWNER_BIRTH_DATE]) ?>
	    </td>
	    <td align="right" valign="top" class='normal'> Tin No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_tin_no' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_TIN_NO];?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td colspan="4" align="right" valign="top" class='normal'><div align="left"></div></td>
	  </tr>
	  <tr> 
	    <td colspan="4" align="left" valign="top" class='normalbold'>Contact 
		Information </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal' width=125> <font color="#FF0000">* 
	      </font>Street : </td>
	    <td align="left" valign="top" class='normal' width=250>&nbsp; <input type='text' name='owner_street' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_STREET]; ?>"> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>City 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$owner_datarow[OWNER_CITY_CODE]);?> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>House 
	      No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_house_no' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_HOUSE_NO]; ?>"> 
	    </td>
	    <td align="right" valign="top" class='normal'> Zip : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_zip_code','ebpls_zip','zip_code','zip_desc',$owner_datarow[OWNER_ZIP_CODE]);?> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Barangay 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	      <!--<input type='text' name='owner_barangay_code' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_BARANGAY_CODE]; ?>"//-->
	      <?php echo get_select_data($dbLink,'owner_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$owner_datarow[OWNER_BARANGAY_CODE]);?> 
	    </td>
	    <td align="right" valign="top" class='normal'> Telephone No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_phone_no' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_PHONE_NO];?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Zone 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_zone_code','ebpls_zone','zone_code','zone_desc',$owner_datarow[OWNER_ZONE_CODE]);?> 
	    </td>
	    <td align="right" valign="top" class='normal'> Mobile No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_gsm_no' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_GSM_NO];?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>District 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_district_code','ebpls_district','district_code','district_desc',$owner_datarow[OWNER_DISTRICT_CODE]);?> 
	    </td>
	    <td align="right" valign="top" class='normal'> Email Address : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_email_address' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_EMAIL_ADDRESS];?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Province 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_province_code','ebpls_province','province_code','province_desc',$owner_datarow[OWNER_PROVINCE_CODE]);?> 
	    </td>
	    <td align="right" valign="top" class='normal'> Others : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_others' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_OTHERS];?>"> 
	    </td>
	  </tr>
	  <tr> 
	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	    	
	  	    	&nbsp;&nbsp;
	  	    	
	  	    </td>
	  </tr>
	   <tr> 
	  	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	  	    	<input type='button' name='addOwner' value='A D D' onClick="checkValidOwner();">
	  	  	    	&nbsp;&nbsp;
	  	  	    	<input type='reset' name='resetOwner' value='R E S E T' >
	  	  	    </td>
	  </tr>
	   <tr> 
	  	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	  	    	 
	  	  	    	&nbsp;&nbsp;
	  	  	    	
	  	  	    </td>
	  </tr>
</table>
</form>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function checkValidOwner()
{
	var _FRM = document._FRM;
	
	 
		var msgTitle = "Owner Application\n";

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

		//-- set the bday

		var y_idx = _FRM._YEAR1.options.selectedIndex;
		var m_idx = _FRM._MONTH1.options.selectedIndex;
		var d_idx = _FRM._DAY1.options.selectedIndex;

		_FRM.owner_birth_date.value = _FRM._YEAR1.options[y_idx].value + '-' + _FRM._MONTH1.options[m_idx].value + '-' + _FRM._DAY1.options[d_idx].value;

		if( isBlank(_FRM.owner_birth_date.value) == true || isValidDate(_FRM.owner_birth_date.value) == false)
		{
			alert( msgTitle + "Please input a valid birthdate!");
			_FRM.comm_tax_cert_owner_birth_date.focus();
			return false;
		}


		if( isBlank(_FRM.owner_house_no.value) == true)
		{
			alert( msgTitle + "Please input a valid house number!");
			_FRM.owner_house_no.focus();
			return false;
		}
		if( isBlank(_FRM.owner_street.value) == true)
		{
			alert( msgTitle + "Please input a valid street number!");
			_FRM.owner_street.focus();
			return false;
		}

		if( isBlank(_FRM.owner_citizenship.value) == true)
		{
			alert( msgTitle + "Please input a valid citizenship!");
			_FRM.owner_citizenship.focus();
			return false;
		}
		_FRM.mode.value='add';
		_FRM.submit();
		return true;
	 
	
	 
}

function reload_parent(id)
{
	var msgTitle = "Owner Application\n";
	
	alert( msgTitle + "Add owner success!");
	
	var win_opener 	= window.opener;
	var _doc 	= win_opener.document;
	 
	win_opener.return2_parent(id);
	window.close();
}

<?php

if($add_reload_flag)
{
	echo "reload_parent('$owner_id');\n";
}

?>
</script>
</div>
</body>
</html>

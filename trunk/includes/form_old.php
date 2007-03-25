<?php
require_once "includes/variables.php"
?>
<html>
<head>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css">

</head>
<body>
<br>
<div align='center'>
<form name="_FRM" method="POST">
<!--<form name="_FRM" method="POST" action="index.php?part=1221&permit_type=<?php echo $tag; ?>&owner_id=<?php echo $usernum[0]; ?>&stat=<?php echo $status;?>">
	<input type='hidden' name='addOwner' value=' A D D '>-->
	<input type='hidden' name='owner_id' value=<?php echo $usernum[0]; ?>>
	<input type=hidden name='mode'>
	<table width='750' border=0 cellpadding=1 cellspacing=1>
	  <!--// start of the owner information //-->
	  <tr> 
	    <td align="center" valign="top" class='subtitleblue' colspan=4 > <?php echo $tag; ?> Permit Owner Information</td>
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
	    <td align="left" valign="top" class='normal' width=250>&nbsp; 
<input tabindex=1 type='text' name='owner_first_name1' maxlength=60 class='text180' value='<?php echo $owner_first_name; ?>'>
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Civil 
	      Status : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <select tabindex=4 name='owner_civil_status'  class='select100' value ='<?php echo $owner_civil_status; ?>'>
	      
                <option value='Single'>Single</option>
                <option value='Married'>Married</option>
                <option value='Widowed'>Widowed</option>
                <option value='Divorced'>Divorced</option>

</select> </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Middle 
	      Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
<input tabindex=2 type='text' name='owner_middle_name' maxlength=60 class='text180' value='<?php echo $owner_middle_name; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">*</font> 
	      Gender : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <select tabindex=5 name='owner_gender' class='select100' value=<?php echo $owner_gender ?>>
		<option value='M' <?php echo (! strcasecmp($owner_datarow[OWNER_GENDER],'M')) ? ('selected') : (''); ?> >M</option>
		<option value='F' <?php echo (! strcasecmp($owner_datarow[OWNER_GENDER],'F')) ? ('selected') : (''); ?>>F</option>
	      </select> </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Last 
	      Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
<input tabindex=3 type='text' name='owner_last_name' maxlength=60 class='text180' value='<?php echo $owner_last_name; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Citizenship 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp;
	 <select  tabindex=6 name='owner_citizenship'> 
	<option  value='Filipino'> Filipino </option>

	<?php 
	$cit = mysql_query("select cit_desc from ebpls_citizenship") or die("SELECT Error :".mysql_error());
	while ($getcit = mysql_fetch_row($cit)){
        foreach ($getcit as $field )
	print "<option  value=$field> $field </option>";
	}
	?>

	</select>
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> Birth Date : <br>(yyyy/mm/dd)
	      <input type='hidden' name='owner_birth_date' maxlength=10 class='text180' value="<?php echo $owner_datarow[OWNER_BIRTH_DATE];?>"> 
	    </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	      <?php set_form_date($owner_datarow[OWNER_BIRTH_DATE]) ?>
	    </td>
	    <td align="right" valign="top" class='normal'> Tin No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_tin_no' maxlength=255 class='text180' value=<?php echo $owner_tin_no;?>> 
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
	      </font>Address : </td>
	    <td align="left" valign="top" class='normal' width=250>&nbsp; <input type='text' name='owner_street' maxlength=255 class='text180' value='<?php echo $owner_street; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>City 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$owner_datarow[OWNER_CITY_CODE]);?> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font> 
	      Barangay :</td>
	    <td align="left" valign="top" class='normal'> 
		<input type='hidden' name='owner_house_no' value= '' maxlength=255 class='text180'>&nbsp; 
		<?php echo get_select_data($dbLink,'owner_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$owner_datarow[OWNER_BARANGAY_CODE]);?>
  
	</td>
	    <td align="right" valign="top" class='normal'> Zip : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_zip_code','ebpls_zip','zip_code','zip_desc',$owner_datarow[OWNER_ZIP_CODE]);?> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> Zone : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	    <?php echo get_select_data($dbLink,'owner_zone_code','ebpls_zone','zone_code','zone_desc',$owner_datarow[OWNER_ZONE_CODE]);?>
	    </td>
	    <td align="right" valign="top" class='normal'> Telephone No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_phone_no' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_PHONE_NO];?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>District 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp;
	 <?php echo get_select_data($dbLink,'owner_district_code','ebpls_district','district_code','district_desc',$owner_datarow[OWNER_DISTRICT_CODE]);?>

	    </td>
	    <td align="right" valign="top" class='normal'> Mobile No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_gsm_no' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_GSM_NO];?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Province 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	 <?php echo get_select_data($dbLink,'owner_province_code','ebpls_province','province_code','province_desc',$owner_datarow[OWNER_PROVINCE_CODE]);?>
    
	</td>
	    <td align="right" valign="top" class='normal'> Email Address : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_email_address' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_EMAIL_ADDRESS];?>"> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'>  </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
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
	  	  	    	<input type='button' name='addOwner' value=' A D D ' onClick="javascript:checkValidOwner();">
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


		//if( isBlank(_FRM.owner_house_no.value) == true)
		//{
		//	alert( msgTitle + "Please input a valid house number!");
		//	_FRM.owner_house_no.focus();
		//	return false;
		//}
		if( isBlank(_FRM.owner_street.value) == true)
		{
			alert( msgTitle + "Please input a valid Address!");
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

</script>
</div>
</body>
</html>



<!--function reload_parent(id)
{
	var msgTitle = "Owner Application\n";
	
	alert( msgTitle + "Add owner success!");
	
	var win_opener 	= window.opener;
	var _doc 	= win_opener.document;
	 
	win_opener.return2_parent(id);
	window.close();
}



if($add_reload_flag)
{
	echo "reload_parent('$owner_id');\n";
}

</script>
</div>
</body>
</html> -->

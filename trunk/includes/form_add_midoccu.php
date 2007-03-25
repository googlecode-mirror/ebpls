<!--
<html>
<head>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>

</head>
<body>
<br>
<div align='center'> 
<?php
if ($owner_id=='') {
	$owner_id=0;
}
$getemp = SelectMultiTable($dbtype,$dbLink,$permittable,
			 "occ_permit_code, occ_permit_application_date,
                          occ_position_applied, occ_employer,occ_employer_trade_name,
                          occ_employer_lot_no, occ_employer_street, business_id",
                         "where owner_id=$owner_id and active=1");

                        $getit = FetchRow($dbtype,$getemp);
                        $permit_code = $getit[0];
                        $pos_app = $getit[2];
                        $permit_date =$getit[1];
                        $employer_name=$getit[3];
                        $trade_name = $getit[4];
                        $street = $getit[6];
                        $lot_no = $getit[5];
                                                                                                               
?>
<form name="_FRM" method="POST" action="index.php?part=1221&owner_id=<?php echo $owner_id; ?>&permit_type=Occupational&stat=<?php echo $status; ?>&create=No&addfee=Add">-->
<form method=post  action ='index.php?part=4&class_type=Permits&itemID_=1221&addfee=Add&owner_id=<?php echo $owner_id; ?>&permit_type=<?php echo $tag; ?>&stat=<?php echo $status; ?>&create=No'>

	<input type='hidden' name='mode'>
	<table width='90%' border=0 cellpadding=1 cellspacing=1>
	  <!--// start of the owner information //-->
	  <tr> 
	    <td align="center"> </td>
	  </tr>
	  <tr> 
	  	<td align="left" valign="top" class='normalred' colspan=4>
<input type=hidden name=frmedit value='<?php echo $frmedit; ?>'>
	</td>
	  </tr>
	  <tr> 
	    <td align="left" valign="top" class='normalbold' colspan=4></td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal' > <font color="#FF0000"> 
	      </font>Position Applied : </td>
	    <td align="left" valign="top" class='normal' >&nbsp; <input type='text' name='pos_app' maxlength=30 class='text180'  value= '<?php echo $pos_app; ?>'>
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>&nbsp </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	  </tr>
	  <tr> 
	 <td align="right" valign="top" class='normal' > <font color="#FF0000">
              </font>Employer Name : </td>
            <td align="left" valign="top" class='normal' >&nbsp; 
	<?php echo get_select_data($dbLink,'employer_business','ebpls_business_enterprise','business_id','business_name',$getit[7],"true","","");?>

            </td>
	 </tr>


<!--
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font> 
	     Employer Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='employer_name' maxlength=60 class='text180' value='<?php echo $employer_name; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"></font> 
	      &nbsp </td>
	    <td align="left" valign="top" class='normal'>&nbsp;
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>Trade Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='trade_name' maxlength=60 class='text180' value='<?php echo $trade_name; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>&nbsp </td>
	    <td align="left" valign="top" class='normal'>&nbsp;
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal' width=125> <font color="#FF0000">
	      </font>Street : </td>
	    <td align="left" valign="top" class='normal' width=250>&nbsp; <input type='text' name='street' maxlength=255 class='text180' value='<?php echo $street; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>City 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$owner_datarow[OWNER_CITY_CODE]);?>
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>Lot 
	      No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='lot_no' maxlength=255 class='text180' value='<?php echo $lot_no; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> Zip : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_zip_code','ebpls_zip','zip_code','zip_desc',$owner_datarow[OWNER_ZIP_CODE]);?> 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>Barangay 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
-->
	      <!--<input type='text' name='owner_barangay_code' maxlength=255 class='text180' value="<?php echo $owner_datarow[OWNER_BARANGAY_CODE]; ?>"//-->
	      <?php //echo get_select_data($dbLink,'owner_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$owner_datarow[OWNER_BARANGAY_CODE]);?> 
<!--
	    </td>
	    <td align="right" valign="top" class='normal'> &nbsp </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>Zone 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_zone_code','ebpls_zone','zone_code','zone_desc',$owner_datarow[OWNER_ZONE_CODE]);?> 
	    </td>
	    <td align="right" valign="top" class='normal'>&nbsp </td>
	    <td align="left" valign="top" class='normal'>&nbsp;
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>District 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_district_code','ebpls_district','district_code','district_desc',$owner_datarow[OWNER_DISTRICT_CODE]);?> 
	    </td>
	    <td align="right" valign="top" class='normal'> &nbsp </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> </font>Province 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <?php echo get_select_data($dbLink,'owner_province_code','ebpls_province','province_code','province_desc',$owner_datarow[OWNER_PROVINCE_CODE]);?> 
	    </td>
	    <td align="right" valign="top" class='normal'> &nbsp
	    <td align="left" valign="top" class='normal'>&nbsp; 
	    </td>
	  </tr>
	  <tr> 
	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	    	
	  	    	&nbsp;&nbsp;
	  	    	
	  	    </td>
	  </tr>
-->
<!--	   <tr> 
	  	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	  	    	<input type='submit' name='addEmp' value='A D D'>
	  	  	    	&nbsp;&nbsp;
	  	  	    	<input type='reset' name='resetOwner' value='R E S E T' >
	  	  	    </td>
	  </tr>
	   <tr> 
	  	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	  	    	 
	  	  	    	&nbsp;&nbsp;
	  	  	    	
	  	  	    </td>
	  </tr>-->
</table>
<!--</form>
</div>
</body>
</html>-->

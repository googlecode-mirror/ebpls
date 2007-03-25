<?php
require_once "includes/variables.php";
include_once "class/TaxpayerClass.php";
?>

<div align='center'>
<?php


if ($permit_type=='Business') {
	$lble = "Lastname/Legal Entity";
} else {
	$lble = "Lastname";
}

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
	$addOwner="";

	}

	//validate TIN

	if ($owner_tin_no<>'' and $blak==0) {
	$asap=SelectDataWhere($dbtype,$dbLink,"ebpls_owner","where owner_tin_no='$owner_tin_no' and owner_id<>'$owner_id'");
	$asap=NumRows($dbtype,$asap);
		if ($asap>0) {
		  $addOwner="";
		?>
	        <body onload='javascript:alert("Exisitng TIN Found");'>
        	</body>
		<?php
		$blak=1;
		}
        }


	if ($owner_id>0) {
	$strValues = "edit_by='',edit_locked=0";
           $strWhere="owner_id=$owner_id";
       $qu = mysql_query("update ebpls_owner set $strValues where $strWhere"); 

	    if ($blak==0 || $blak=='') {
	        $addown=new TaxPayer;
		$strValues="owner_first_name='$owner_first_name',owner_middle_name='$owner_middle_name',
	             owner_last_name='$owner_last_name',owner_street='$owner_street',
	             owner_legal_entity='$owner_legal_entity',
        	     owner_barangay_code='$owner_barangay_code',owner_zone_code='$owner_zone_code',
	             owner_district_code='$owner_district_code',owner_city_code='$owner_city_code',
        	     owner_province_code='$owner_province_code',owner_zip_code='$owner_zip_code',
	             owner_citizenship='$owner_citizenship',owner_civil_status='$owner_civil_status',
        	     owner_gender='$owner_gender',owner_tin_no='$owner_tin_no',
	             owner_icr_no='$owner_icr_no',owner_phone_no='$owner_phone_no',
        	     owner_gsm_no='$owner_gsm_no',owner_email_address='$owner_email_address',
	             owner_others='$owner_others',owner_birth_date='$owner_birth_date',
	             owner_lastupdated=now(),owner_lastupdated_by='$usern',edit_by='',edit_locked=0";
		$strWhere="owner_id=$owner_id";
	        $addown->UpdateTaxPayer($strValues,$strWhere);
       	   }
//      }
        $redito = "index.php?part=4&itemID_=1221&permit_type=$tag&stat=$stat&owner_id=$owner_id&upOwner=UPDATE&business_id=$business_id&busItem=$permit_type&frmedit=yes";
 $addown=new TaxPayer;
        $strValues = "edit_by='',edit_locked=0";
           $strWhere="owner_id='$owner_id'";
       $qu = mysql_query("update ebpls_owner set $strValues where $strWhere");

setUrlRedirect($redito);
                                                                                                                             
                                                                                                                             
	} else {


	if ($blak==0) {
	$addown = new TaxPayer;
	
	$InsValues = "'','$owner_first_name','$owner_middle_name',
		      '$owner_last_name','$owner_legal_entity','','$owner_street',
		      '$owner_barangay_code','$owner_zone_code','$owner_district_code',
		      '$owner_city_code','$owner_province_code','$owner_zip_code',
		      '$owner_citizenship','$owner_civil_status','$owner_gender',
		      '$owner_tin_no','$owner_icr_no','$owner_phone_no',
		      '$owner_gsm_no','$owner_email_address','$owner_others',
		      '$owner_birth_date',now(), now(),'$usern','',0";
	$addown->InsertTaxPayer($InsValues);
	$owner_id = $addown->outid;

		if ($busItem=='CTC') {
	$asap=SelectDataWhere($dbtype,$dbLink,"ebpls_owner","order by owner_id desc limit 1");
	$asap=FetchArray($dbtype,$asap);
	$owner_id=$asap[owner_id];
	$redito='index.php?part=4&itemID_=1001&owner_id='.$owner_id.'&ctc_type=INDIVIDUAL&busItem=CTC&comm='.$comm;
	$search='';
	$comm="";
		} else {
	$redito = "index.php?part=4&class_type=Permits&itemID_=1221&permit_type=$tag&owner_id=$owner_id&stat=$stat&addOwner=ADD&busItem=$permit_type";
		}
	 $addown=new TaxPayer;
        $strValues = "edit_by='',edit_locked=0";
           $strWhere="owner_id=$owner_id";
       $qu = mysql_query("update ebpls_owner set $strValues where $strWhere");

	setUrlRedirect($redito);
	} else {
	$addOwner='';
	}
	} //owner_id>0


} else {
	if ($busItem=='CTC') {

//may tinagal ako dito, may gamit daw sa ctc... ewan ko lang -- von 6/15/05

	$allclear='index.php?part=4&itemID_=1001&owner_id='.$owner_id.'&ctc_type=INDIVIDUAL&busItem=CTC&comm='.$comm.'&pro='.$pro;
	} else {
	$allclear = "index.php?part=4&class_type=Permits&busItem=$tag&itemID_=1221&permit_type=$tag&mtopadd=ADD&stat=$stat&busItem=$tag";
	}
	$addOwner='';
}



if ($owner_id>0) {
	if ($comm<>'Add') {
$reload = SelectDataWhere($dbtype,$dbLink,$owner,"where owner_id =$owner_id");
$owner=FetchArray($dbtype,$reload);
	} elseif ($comm=='Add' and $owner_id<>'') {
$reload = SelectDataWhere($dbtype,$dbLink,$owner,"where owner_id =$owner_id");
$owner=FetchArray($dbtype,$reload);
	}
if ($owner[edit_locked]==1 and $owner[edit_by]<>substr($usern,0,50)) {
?>
        <body onload='alert("Cannot edit owner, currently used by <?php echo $owner[edit_by]; ?>"); parent.location="index.php?part=4&itemID_=1221&class_type=Permits&permit_type=<?php echo $permit_type; ?>&busItem=<?php echo $permit_type; ?>&mtopsearch=SEARCH";'></body>
<?php
} else {
	 $addown=new TaxPayer;
	 if ($owner_id<>'') {
		 
                $strValues="edit_by='$usern',edit_locked=1";
                $strWhere="owner_id='$owner_id'";
                $addown->UpdateTaxPayer($strValues,$strWhere);
            }

}


$owner_first_name=$owner[owner_first_name];
$owner_middle_name=$owner[owner_middle_name];
$owner_last_name=$owner[owner_last_name];
$owner_legal_entity=$owner[owner_legal_entity];
$owner_house_no =$owner[owner_house_no];
$owner_street =$owner[owner_street];
$owner_barangay_code=$owner[owner_barangay_code]; 
$owner_zone_code=$owner[owner_zone_code]; 
$owner_district_code =$owner[owner_district_code];
$owner_city_code =$owner[owner_city_code];
$owner_province_code=$owner[owner_province_code]; 
$owner_zip_code =$owner[owner_zip_code];
$owner_citizenship =$owner[owner_citizenship];
$owner_civil_status =$owner[owner_civil_status];
$owner_gender =$owner[owner_gender];
$owner_tin_no =$owner[owner_tin_no];
$owner_icr_no =$owner[owner_icr_no];
$owner_phone_no =$owner[owner_phone_no];
$owner_gsm_no =$owner[owner_gsm_no];
$owner_email_address =$owner[owner_email_address];
$owner_others =$owner[owner_others];
$owner_birth_date =substr($owner[owner_birth_date],0,10);

}


if($cancelme==1) {
         $addown=new TaxPayer;
         if ($owner_id<>'') {
                $strValues="edit_by='',edit_locked=0";
                $strWhere="owner_id='$owner_id'";
                $addown->UpdateTaxPayer($strValues,$strWhere);
        ?>
        <body onload='parent.location="index.php?part=4";'></body>
<?php
     }  else {
	      ?>
        <body onload='parent.location="index.php?part=4&class_type=Permits&permit_type=<?php echo $permit_type; ?>&busItem=<?php echo $busItem; ?>&itemID_=1221&mainfrm=Main";'></body>
<?php
}                                                                                                           
}


?>
<form name="_FRM" method="POST" action="<?php echo $allclear; ?>">
<script type="text/javascript" src="javascripts/ajax.js"></script>
<script type="text/javascript">
/************************************************************************************************************
(C) www.dhtmlgoodies.com, October 2005
                                                                                                                                               
This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.
                                                                                                                                               
Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.
                                                                                                                                               
Thank you!
                                                                                                                                               
www.dhtmlgoodies.com
Alf Magne Kalleland
                                                                                                                                               
************************************************************************************************************/
var ajax = new sack();
                                                                                                                                               
function getCityList(sel)
{
	var provCode = sel.options[sel.selectedIndex].value;
        document.getElementById('owner_city_code').options.length = 0;        // Empty city select box
        if(provCode.length>0){
                ajax.requestFile = 'getCities.php?owner_province_code='+provCode;   // Specifying which file to get
                ajax.onCompletion = createCities;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                                               
function createCities()
{
        var obj = document.getElementById('owner_city_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}
          
function getDistrictList(sel)
{
        var cityCode = sel.options[sel.selectedIndex].value;
        document.getElementById('owner_district_code').options.length = 0;        // Empty city select box
        if(cityCode.length>0){
                ajax.requestFile = 'getCities.php?owner_city_code='+cityCode;   // Specifying which file to get
                ajax.onCompletion = createDistrict;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                             
                                                                                                                             
function createDistrict()
{
        var obj = document.getElementById('owner_district_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}
          

function getZip(sel)
{
        var cityCode = sel.options[sel.selectedIndex].value;
        document.getElementById('owner_zip_code').options.length = 0;        // Empty city select box
        if(cityCode.length>0){
                ajax.requestFile = 'getCities.php?owner_city_code='+cityCode;   // Specifying which file to get
                ajax.onCompletion = createZip;       // Specify function that will be executed after file has been found
//                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                             
                                                                                                                             
function createZip()
{
        var obj = document.getElementById('owner_zip_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}
                                                                                                                          
function getBrgyList(sel)
{
        var districtCode = sel.options[sel.selectedIndex].value;
        document.getElementById('owner_barangay_code').options.length = 0;        // Empty city select box
        if(districtCode.length>0){
                ajax.requestFile = 'getCities.php?owner_district_code='+districtCode;   // Specifying which file to get
                ajax.onCompletion = createBrgy;       // Specify function that will be executed after file has been found 
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                             
                                                                                                                             
function createBrgy()
{
        var obj = document.getElementById('owner_barangay_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}

function getZoneList(sel)
{
        var brgyCode = sel.options[sel.selectedIndex].value;
        document.getElementById('owner_zone_code').options.length = 0;        // Empty city select box
        if(brgyCode.length>0){
                ajax.requestFile = 'getCities.php?owner_barangay_code='+brgyCode;   // Specifying which file to get
                ajax.onCompletion = createZone;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                             
                                                                                                                             
function createZone()
{
        var obj = document.getElementById('owner_zone_code');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}

 
                                                                                                                                               
</script>
        <?php
        /*if ($owner_id>0) {
	 print "<input type=hidden name=upOwner value='UPDATE'>";
        $buttontag = 'U P D A T E';
	} else {
        print "<input type=hidden name=addOwner value='ADD'>";
        $buttontag = ' A D D ';
        }*/
		if ($permit_type == 'CTC' || $permit_type <> 'Business') {
			$ast = "* ";
		} else {
			$ast = "";
		}
        ?>


	<input type='hidden' name='addOwner' value='<?php echo $addOwner; ?>'>
	<input type='hidden' name='owner_id' value=<?php echo $owner_id; ?>>
	<input type='hidden' name='busItem' value=<?php echo $busItem; ?>>
	<input type='hidden' name='comm' value=<?php echo $comm; ?>>
	<input type='hidden' name='search' value=<?php echo $search; ?>>
	<input type='hidden' name='blak' value=<?php echo $blak; ?>>
	
	<input type=hidden name='mode' >
	<table width='100%' border=0 cellpadding=0 cellspacing=0>
	  <!--// start of the owner information //-->
	  <tr> 
	    <td align="center" valign="top" class='header2' colspan=4 > <?php echo $tag; ?> Permit Owner Information</td>
	  </tr>
	  <tr> 
	  	<td align="left" valign="top" class='normalred' colspan=4>&nbsp;</td>
	  </tr>
<input type=hidden name=changeondin value=''>
	  <tr> 
	    <td align="left" valign="top" class='normalbold' colspan=4> Personal Details:</td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal' width='20%'> <font color="#FF0000"><? echo $ast; ?></font>
		First Name : </td>
	    <td align="left" valign="top" class='normal' width='30%'>&nbsp; 
<input tabindex=1 type='text' name='owner_first_name' maxlength=60 class='text180' value='<?php echo $owner_first_name; ?>'>
	    </td>
	    <td align="right" valign="top" class='normal' width='20%'> <font color="#FF0000">* </font>Civil 
	      Status : </td>
	    <td align="left" valign="top" class='normal' width='30%'>&nbsp; <select tabindex=4 name='owner_civil_status'  class='select100'>
	     	<?php
		if ($owner_civil_status<>''){
	     	?>
		<option value='<?php echo $owner_civil_status; ?>'>
		<?php echo $owner_civil_status; ?>
		</option>
		<?php
		}
		?>

 
                <option value='Single'>Single</option>
                <option value='Married'>Married</option>
                <option value='Widowed'>Widowed</option>
                <option value='Divorced'>Divorced</option>

</select> </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"><? echo $ast; ?></font>Middle 
	      Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
<input tabindex=2 type='text' name='owner_middle_name' maxlength=60 class='text180' value='<?php echo $owner_middle_name; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">*</font> 
	      Gender : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <select tabindex=5 name='owner_gender' class='select100'>
		<option value='M' <?php echo (! strcasecmp($owner_gender,'M')) ? ('selected') : (''); ?> >M</option>
		<option value='F' <?php echo (! strcasecmp($owner_gender,'F')) ? ('selected') : (''); ?>>F</option>
	      </select> </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font> 
	      Lastname : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
<input tabindex=3 type='text' name='owner_last_name' maxlength=60 class='text180' value='<?php echo $owner_last_name; ?>'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Citizenship 
	    	: </td>
	    <td align="left" valign="top" class='normal'>&nbsp;
	 <select  tabindex=6 name='owner_citizenship'>
	<?php 
	$cit = mysql_query("select cit_desc from ebpls_citizenship") or die("SELECT Error :".mysql_error());
	while ($getcit = mysql_fetch_row($cit)){
		if ($getcit[0]==$owner_citizenship) {
			$issel = 'selected';
		} else {
			$issel = '';
		}

	print "<option  value='$getcit[0]' $issel> $getcit[0] </option>";
	}
	?>

	</select>
	    </td>
	  </tr>
	  <?php if ($permit_type=='Business') {
		  ?>
		  <tr>
		   <td align="right" valign="top" class='normal'>
	      Legal Entity : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
<input tabindex=3 type='text' name='owner_legal_entity' maxlength=60 class='text180' value='<?php echo $owner_legal_entity; ?>'> 
	    </td>
	    	</tr>
	   <?php } else {
		   ?>
		   <input type=hidden name=owner_legal_entity>
	<?php
			}
			?>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000"> *</font>Birth Date (yyyy-mm-dd): 
	    </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
		 <input id="cid" name="owner_birth_date" value="<?php echo $owner_birth_date; ?>" type="text" size="10">
<!--<input id="cid" name="owner_birth_date" value="<?php echo $owner_birth_date; ?>"
type="text" size="10" onFocus="javascript:vDateType='2'" onKeyUp="DateFormat(this,this.value,event,false,'2')" onBlur="DateFormat(this,this.value,event,true,'2')">-->
<!--        <a href="javascript:NewCal('cid','mmddyyyy')" tabindex=7>
        <img src="includes/datepick/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>-->


	    </td>
	    <td align="right" valign="top" class='normal'> TIN : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_tin_no' maxlength=255 class='text180' tabindex=8 value=<?php echo $owner_tin_no;?>> 
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
	    <td align="right" valign="top" class='normal' width='20%'> <font color="#FF0000">* 
	      </font>Address : </td>
	    <td align="left" valign="top" class='normal' width='33.33%'>&nbsp; <input type='text' name='owner_street' maxlength=255 class='text180' tabindex=9 value='<?php echo $owner_street; ?>'> 
	    </td>

<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Province
              : </td>
            <td align="left" valign="top" class='normal'>&nbsp;
   <?php 
echo get_select_prov($dbLink,'owner_province_code','ebpls_province','province_code','province_desc',$owner_province_code,$owner_province_code);
?>	                                                                                    
        </td>

	  </tr>
	  <tr> 
<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>City
              : </td>
            <td align="left" valign="top" class='normal'>&nbsp; 
<?php
//echo get_select_city($dbLink,'owner_city_code','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$owner_city_code,$owner_province_code);
		$getz = SelectDataWhere($dbtype,$dbLink,"ebpls_city_municipality",
                        "where city_municipality_code  = '$owner_city_code'");
	        $owner_city_desc = FetchArray($dbtype,$getz);
        	$owner_city_desc = $owner_city_desc[city_municipality_desc];
?>
		<select id="owner_city_code" name="owner_city_code" onchange='getDistrictList(this);' class=select200>
		<option value="<?php echo $owner_city_code; ?>"><?php echo $owner_city_desc; ?></option>
                </select>

            </td>
                                                                                                 
<?php
	$getzip = SelectDataWhere($dbtype,$dbLink,"ebpls_zip",
			"where upper = '$owner_city_code'");
	$owner_zip = FetchArray($dbtype,$getzip);
	$owner_zip = $owner_zip[zip_desc];
?>

	    <td align="right" valign="top" class='normal'> Zip : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	    <input type=hidden name='owner_zip_code' value='<?php echo $owner_zip; ?>' readonly
		maxlength=10 class='text180'><?php echo $owner_zip; ?>

<!--	    <select id="owner_zip_code" name="owner_zip_code">
                </select>-->



	    </td>
	  </tr>
	  <tr> 
<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>District: </td>
            <td align="left" valign="top" class='normal'>&nbsp;
         <?php //echo get_select_dist($dbLink,'owner_district_code','ebpls_district','district_code','district_desc',$owner_district_code,$owner_city_code);
 $getz = SelectDataWhere($dbtype,$dbLink,"ebpls_district",
                        "where district_code  = '$owner_district_code'");
                $owner_district_desc = FetchArray($dbtype,$getz);
                $owner_district_desc = $owner_district_desc[district_desc];

?>
                                                                                                 
	     <select id="owner_district_code" name="owner_district_code" onchange='getBrgyList(this);' class=select200>
		<option value="<?php echo $owner_district_code; ?>"><?php echo $owner_district_desc; ?></option>
                </select>



            </td>

	    <td align="right" valign="top" class='normal'> Telephone No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_phone_no' maxlength=255 class='text180' tabindex=10 value="<?php echo $owner_phone_no;?>"> 
	    </td>
	  </tr>
	  <tr> 
<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>
              Barangay :</td>
            <td align="left" valign="top" class='normal'>
                <input type='hidden' name='owner_house_no' value= '' maxlength=255 class='text180'>&nbsp;
                <?php //echo get_select_barg($dbLink,'owner_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$owner_barangay_code,$owner_district_code);
		$getz = SelectDataWhere($dbtype,$dbLink,"ebpls_barangay",
                        "where barangay_code  = '$owner_barangay_code'");
                $owner_barangay_desc = FetchArray($dbtype,$getz);
                $owner_barangay_desc = $owner_barangay_desc[barangay_desc];

?>
		<select id="owner_barangay_code" name="owner_barangay_code" onchange='getZoneList(this);' class=select200>
		<option value="<?php echo $owner_barangay_code; ?>"><?php echo $owner_barangay_desc; ?></option>
                </select>
                                                                                                 
        </td>

	    <td align="right" valign="top" class='normal'> Mobile No (+639121234567): </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_gsm_no' maxlength=255 class='text180' tabindex=11 value="<?php echo $owner_gsm_no?>"> 
	    </td>
	  </tr>
	  <tr>

<td align="right" valign="top" class='normal'> Zone : </td>
            <td align="left" valign="top" class='normal'>&nbsp;
            <?php //echo get_select_zone($dbLink,'owner_zone_code','ebpls_zone','zone_code','zone_desc',$owner_zone_code,$owner_barangay_code);
		 $getz = SelectDataWhere($dbtype,$dbLink,"ebpls_zone",
                        "where zone_code  = '$owner_zone_code'");
                $owner_zone_desc = FetchArray($dbtype,$getz);
                $owner_zone_desc = $owner_zone_desc[zone_desc];
?>
	    <select id="owner_zone_code" name="owner_zone_code" class=select200>
		<option value="<?php echo $owner_zone_code; ?>"><?php echo $owner_zone_desc; ?></option>
                </select>	


            </td>
	    <td align="right" valign="top" class='normal'> Email Address : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_email_address' maxlength=255 class='text180' tabindex=12 value="<?php echo $owner_email_address;?>"> 
	    </td>
	  </tr>
	  <tr>
	    <td align="right" valign="top" class='normal'>  </td>
	    <td align="left" valign="top" class='normal'>&nbsp; 
	    </td>
	    <td align="right" valign="top" class='normal'> Others : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_others' maxlength=255 class='text180' tabindex=13 value="<?php echo $owner_others?>"> 
	    </td>
	  </tr>


	  <tr> 
	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	    	
	  	    	&nbsp;&nbsp;
	  	    	
	  	    </td>
	  </tr>
	   <tr> 
<input type=hidden name='pro' value='<?php echo $pro; ?>'>

	  	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	  	    	<input type='button' name='addowner' value='   SAVE   ' onClick='javascript:checkValidOwner();'>
				<input type=hidden name=cancelme>
				<input type=button value='CANCEL' onClick="_FRM.cancelme.value=1; _FRM.submit();">
	  	  	    </td>
	  </tr>
	   <tr> 
	  	  	    <td align="center" valign="top" class='normal' colspan=4> 
	  	  	    	 
	  	  	    	&nbsp;&nbsp;
	  	  	    	
	  	  	    </td>
	  </tr>
</table>
</form>

</div>


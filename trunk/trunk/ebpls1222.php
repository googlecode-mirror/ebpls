<?php
//	@eBPLS_PAGE_APP_OWNER: owner criteria page
//	- start page for owner search
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/ebpls.owner.class.php");
include "class/TaxpayerClass.php";
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;

$debug 		= false;

$status_str   	= "";

//--- get the owner
//$clsOwner 	= new EBPLSOwner ( $dbLink, $debug );

$add_reload_flag = false;
include "includes/variables.php";
                                
if ($pro==1) {
//        if ($changeon==1 || $changeondin==1) {


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
        <body onload='javascript:alert("Please Input Valid GSM Number: +639191234567");'>
        </body>
<?php
        }
	if ($blak==0 || $blak=='') {
	$addown=new TaxPayer;
$strValues="owner_first_name='$owner_first_name',owner_middle_name='$owner_middle_name',
   	     owner_last_name='$owner_last_name',owner_street='$owner_street',
             owner_barangay_code='$owner_barangay_code',owner_zone_code='$owner_zone_code',
             owner_district_code='$owner_district_code',owner_city_code='$owner_city_code',
             owner_province_code='$owner_province_code',owner_zip_code='$owner_zip_code',
             owner_citizenship='$owner_citizenship',owner_civil_status='$owner_civil_status',
             owner_gender='$owner_gender',owner_tin_no='$owner_tin_no',
             owner_icr_no='$owner_icr_no',owner_phone_no='$owner_phone_no',
             owner_gsm_no='$owner_gsm_no',owner_email_address='$owner_email_address',
             owner_others='$owner_others',owner_birth_date='$owner_birth_date',
             owner_lastupdated=now(),owner_lastupdated_by='$usern'";
$strWhere="owner_id=$owner_id";
	$addown->UpdateTaxPayer($strValues,$strWhere);
	}
//	}
        $redito = "index.php?part=4&itemID_=1221&permit_type=".$tag."&stat=".$stat."&owner_id=".$owner_id."&upOwner=UPDATE&business_id=".$business_id."&busItem=".$permit_type."&frmedit=yes";
setUrlRedirect($redito);
               
		
                                                                                                                                             
}
if ($comm<>'Add') {
$reload = SelectDataWhere($dbtype,$dbLink,$owner,"where owner_id =$owner_id");
$owner=FetchRow($dbtype,$reload);
} elseif ($comm=='Add' and $owner_id<>'') {
$reload = SelectDataWhere($dbtype,$dbLink,$owner,"where owner_id =$owner_id");
$owner=FetchRow($dbtype,$reload);
}
?>
<html>
<title>Owner Details</title>
<head>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>

</head>
<body>
<br>
<div align='center'>
<form name='_FRM' method='POST' action="<?php echo $redito; ?>">
 
	<?php
	if ($owner_id==0) {
       	print "<input type=hidden name=addOwner value='ADD'>";
	$buttontag = ' A D D ';
	} else {
	print "<input type=hidden name=upOwner value='UPDATE'>";
	$buttontag = 'U P D A T E';
	}
	?>
<input type=hidden name=changeondin value='<?php echo $changeondin; ?>'>
	<input type='hidden' name='mode'>
	<input type='hidden' name='addOwner' value='<?php echo $addOwner; ?>'>
	 <input type='hidden' name='comm' value=<?php echo $comm; ?>>
        <input type='hidden' name='search' value=<?php echo $search; ?>>
        <input type='hidden' name='blak' value=<?php echo $blak; ?>>

	<input type ='hidden' name=owner_id value=<?php echo $owner_id; ?>>
	<input type='hidden' name='busItem' value=<?php echo $busItem; ?>>
	<table width='100%' border=0 cellpadding=1 cellspacing=1>
	  <!--// start of the owner information //-->
	  <tr> 
	    <td align="center" valign="top" class='header2' colspan=4 > <?php echo $tag; ?> Permit Owner Information</td>
	  </tr>
	  <tr> 
	  	<td align="left" valign="top" class='normalred' colspan=4>&nbsp;</td>
	  </tr>
	  <tr> 
	    <td align="left" valign="top" class='normalbold' colspan=4> Personal Details:</td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal' width=20%> <font color="#FF0000">* 
	      </font>First Name : </td>
	    <td align='left' valign='top' class='normal' width=33%>&nbsp; <input type='text' name='owner_first_name' maxlength=60 class='text180'  value='<?php echo $owner[1]; ?>'tabindex=1 onchange='javascript:flagchange(changeondin);'>
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Civil 
	      Status : </td>

	    <td align='left' valign='top' class='normal'>&nbsp; <select name='owner_civil_status' tabindex=4 class='select100'  onchange='javascript:flagchange(changeondin);'>
		<option value='<?php echo $owner[13];?>'><?php echo $owner[13]; ?>
		<option value='Single'>Single</option>
		<option value='Married'>Married</option>
		<option value='Widowed'>Widowed</option>
		<option value='Divorced'>Divorced</option>
	      </select> </td>

	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Middle
	      Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' tabindex=2 name='owner_middle_name' maxlength=60 class='text180' value="<?php echo $owner[2]; ?>"  onchange='javascript:flagchange(changeondin);'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">*</font> 
	      Gender : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <select name='owner_gender' tabindex=5 class='select100'  onchange='javascript:flagchange(changeondin);'>
		<option value='M' <?php echo (! strcasecmp($owner[14],'M')) ? ('selected') : (''); ?> >M</option>
		<option value='F' <?php echo (! strcasecmp($owner[14],'F')) ? ('selected') : (''); ?>>F</option>
	      </select> </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Last 
	      Name : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' tabindex=3 name='owner_last_name' maxlength=60 class='text180' value="<?php echo $owner[3]; ?>"  onchange='javascript:flagchange(changeondin);'> 
	    </td>
	    <td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Citizenship 
	      : </td>
	    <td align="left" valign="top" class='normal'>&nbsp;<!-- <input type='text' name='owner_citizenship' maxlength=255 class='text180' value=""> -->
	    <select  name='owner_citizenship' tabindex=6 class='select100' onchange='javascript:flagchange(changeondin);'>
	<?php
	if ($owner_id=='0') {
		$defcit = 'Filipino';
	} else {
		$defcit = $owner[12];
        }

        $cit = SelectMultiTable($dbtype,$dbLink,"ebpls_citizenship","cit_desc","");
	        while ($getcit = FetchRow($dbtype,$cit)){
			if ($getcit[0]==$defcit) {
				$selec = 'selected';
			} else {
				$selec = '';
			}
		        print "<option  value=$getcit[0] $selec> $getcit[0] </option>";
	        }
        ?>
        </select>


	    </td>
	  </tr>
	  <tr> 
	    <td align="right" valign="top" class='normal'> Birth Date : 
	    </td>
	    <td align="left" valign="top" class='normal'>
		<input id="cid" name="owner_birth_date" value="<?php echo substr($owner[21],0,10); ?>"type="text" size="10" readonly>
        	<a href="javascript:NewCal('cid','mmddyyyy')" onclick='javascript:flagchange(changeondin);'>
        <img src="includes/datepick/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a>
	    </td>
	    <td align="right" valign="top" class='normal'> Tin No : </td>
	    <td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_tin_no' maxlength=255 class='text180' value="<?php echo $owner[15];?>"  onchange='javascript:flagchange(changeondin);'> 
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
	    <td align="right" valign="top" class='normal' width=20%> <font color="#FF0000">* 
	      </font>Address : </td>
	    <td align="left" valign="top" class='normal' width=80%>&nbsp; <input type='text' name='owner_street' maxlength=255 class='text180' value="<?php echo $owner[5].' '.$owner[4]; ?>"  onchange='javascript:flagchange(changeondin);'> 
	    </td>
</tr><tr>
<td align="right" valign="top" class='normal'> <font color="#FF0000">* </font>Province
              : </td>
            <td align="left" valign="top" class='normal'>&nbsp;
<?php
	if ($owner_province_code<>$owner[10] and $owner_province_code<>'') {
		$owner[10]=$owner_province_code;
		$owner[6]='';
		$owner[7]='';
		$owner[8]='';
		$owner[9]='';
		print "<input type=hidden name=changeon value=1>";
	} 
 
echo get_select_prov($dbLink,'owner_province_code','ebpls_province','province_code','province_desc',$owner[10],'');
?>
                                                                                                 
</td>
</tr>
<tr>
<td align="right" valign="top" class='normal'> Telephone No : </td>
<td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_phone_no' maxlength=255 class='text180' value="<?php echo $owner[17];?>" onchange='javascript:flagchange(changeondin);'> 
</td>
</tr>
<tr>
<td align="right" valign="top" class='normal'> Mobile No : </td>
<td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_gsm_no' maxlength=13 class='text180' value="<?php echo $owner[18];?>" onchange='javascript:flagchange(changeondin);'> 
</td>
</tr>
<tr>
<td align="right" valign="top" class='normal'> Email Address : </td>
<td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_email_address' maxlength=255 class='text180' value="<?php echo $owner[19];?>" onchange='javascript:flagchange(changeondin);'> 
</td>
</tr>
<tr> 
<td align="right" valign="top" class='normal'> Others : </td>
<td align="left" valign="top" class='normal'>&nbsp; <input type='text' name='owner_others' maxlength=255 class='text180' value="<?php echo $owner[20];?>" onchange='javascript:flagchange(changeondin);'> 
</td>
</tr>
<tr> 
<td align="center" valign="top" class='normal' colspan=4> &nbsp;&nbsp;</td>
</tr>
<tr> 
<input type=hidden name='pro' value=''>
<td align="center" valign="top" class='normal' colspan=4> 
<input type='button' name='butun' value='    OK    ' onClick='javascript:checkValidOwner();'>
<!-- onClick='javascript:checkValidOwner();'>-->
&nbsp;&nbsp;
<input type='button' name='cancelOwner' value='CANCEL' onClick="history.go(-1);")
</td>
</tr><tr>
<td align="center" valign="top" class='normal' colspan=4> 
	  	  	    	&nbsp;&nbsp;
</td>
</tr>
</table>
</form>
<script language='Javascript' src='javascripts/default.js'></script>

function flagchange(x)
{
	x.value = 1;
}


</script>
</div>
</body>
</html>


<!--
function reload_parent(id)
//{
	var msgTitle = "Owner Application\n";
	
	alert( msgTitle + "Add owner success!");
	
	var win_opener 	= window.opener;
	var _doc 	= win_opener.document;
	 
	win_opener.return2_parent(id);
	window.close();
}



//if($add_reload_flag)
//{
	echo "reload_parent('$owner_id');\n";
//}


</script>
</div>
</body>
</html>

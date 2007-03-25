<?php

include'lib/phpFunctions-inc.php';
include'includes/config.php';

dbConnect();
///*

if (isset($iSubmitUpload)){
	$allowed_file_size_in_bytes=1000000;
	$file_dir=eBPLS_APP_URL;	
	
//	echo "this is the filename $fupload .... $fupload_name";
	//if (!empty($fupload_name)){
	if ($fupload_size <= $allowed_file_size_in_bytes) {
		if (($pos = strrpos($fupload_name, ".")) == FALSE)
			echo "Error -Invalid Filename";
		else {
			$extension = substr($fupload_name, $pos + 1);
		}
		if (strtolower($extension) == 'jpg' or strtolower($extension == 'png')) {
			copy ($fupload, "images/$fupload_name") or die ("Couldn't copy");
			copy ($fupload, "images/ebpls_logo.jpg") or die ("Couldn't copy");
		} else {
			?>
			<body onload='javascript:alert ("Cannot upload logo. Invalid file type.");'></body>
			<?
		}
		
		$dataid_ = mysql_query("UPDATE $tbl_current SET lguimage='$fupload_name', revdate=Now() ",$dbLink);
		
		?>		
		<!--<META HTTP-EQUIV="Refresh" CONTENT="0;URL=<?php eBPLS_APP_URL ?>index.php?part=4&class_type=Preference&selMode=ebpls_npreferences&action_=6">-->
		<?php
	}
	else {
	//$validateID=8;
	//include'validate-inc.php';			
	?>
	<body onload='javascript:alert ("Cannot upload logo. File exceeds maximum allowable size of 1MB.");'></body>
	<?
	//print "<hr>Upload Status: &nbsp &nbsp &nbsp Unable to upload file.<br>";
	//print "File too large. Allowable maximum file size is 50kb.<hr>";
	}
	
	//}
	//else {
	//$validateID=2;
	//include'validate-inc.php';			
	//}
}
//*/
/*********/
/*********/
/*********/

if ($iSubmitApply == 'Apply'){
	if ($ipredcomp == 'ON') {
		$ipredval = "1";
	} else {
		$ipredval = "0";
	}
	
//echo $iBodyColor;
//exit;
	//$iLGUName=strip_tags($iLGUName);
	//$iLGUProvince=strip_tags($iLGUProvince);
	//$iLGUMunicipality=strip_tags($iLGUMunicipality);
// 	$iLGUOffice=strip_tags($iLGUOffice);
// 	$iLGUPenalty=strip_tags($iLGUPenalty);
// 	$iLGUInterest=strip_tags($iLGUInterest);
	//$iLGURenMonth=strip_tags($iLGURenMonth);
	//$iLGURenDay=strip_tags($iLGURenDay);
	//$iLGURenYear=strip_tags($iLGURenYear);
	
	/*if ($iPayment==1){
		$iSeparateTaxesFees=Null;
	}
	
	if ($iSeparateTaxesFees==1){
		$iPayment=Null;
	}*/
	
//change in assessment style

	$getsty = mysql_query("select sassess from ebpls_buss_preference");
	$getsty = mysql_fetch_row($getsty);
	$getsty = $getsty[0];

	if ($getsty<>$iSingleAssessment) {
		$deltempasses = mysql_query("truncate tempassess") or die ("truncate");
	}
	$chkrecord = @mysql_query("select * from ebpls_buss_preference");
	$chkrec = @mysql_num_rows($chkrecord);
	if ($chkrec==0) {
		if ($iPayment == 1 and $iSeparateTaxesFees == 1) {
		?>
		<body onload='javascript:alert ("Invalid reference settings combination."); return false;'></body>
		<?
		} else {	
		$dataid_ = mysql_query("insert into $tbl_current (spermit,sassess,sor,sbacktaxes,srequire,lguname,lguprovince,lgumunicipality,lguoffice,revdate,staxesfees,spayment,sdecimal,or_print,lgucode,spaywoapprov,swaivetax,swaivefee,lgu_add,lgu_tel,predcomp,iReset) values ('$iSinglePermit', '$iSingleAssessment', '$iSingleOR', '$iBackTaxes', '$iRequire', '$niLGUName', '$niLGUProvince', '$iLGUMunicipality', '$iLGUOffice', Now(), '$iSeparateTaxesFees', '$iPayment', '$iUnggoy', '$iOR_Print','$iLGUName','$iPaywoAprrov', '$iWaivetax', '$iWaivefee','$lgu_add', '$lgu_tel','$ipredval','$iReset')",$dbLink);
		//
		$d = mysql_query("update ebpls_business_enterprise set  business_province_code='$niLGUProvince' and  business_city_code='$iLGUMunicipality'");
		?>
		<body onload='javascript:alert ("Data updated successfully");'></body>
		<?
		$iSubmitApply = "";
		}
	} else {
	if ($iPayment == 1 and $iSeparateTaxesFees == 1) {
	?>
	<body onload='javascript:alert ("Invalid Combination"); return false;'></body>
	<?
	} else {	
	//if (checkdate($iLGURenMonth, $iLGURenDay, $iLGURenYear)){
	//$iRenewalDate=$iLGURenYear.'-'.$iLGURenMonth.'-'.$iLGURenDay;
	$dataid_ = mysql_query("UPDATE $tbl_current SET spermit='$iSinglePermit', sassess='$iSingleAssessment', sor='$iSingleOR', sbacktaxes='$iBackTaxes', srequire='$iRequire', lguname='$niLGUName', lguprovince='$niLGUProvince', lgumunicipality='$iLGUMunicipality', lguoffice='$iLGUOffice', revdate=Now(), staxesfees='$iSeparateTaxesFees', spayment='$iPayment', sdecimal='$iUnggoy', mp='$iMP', bt='$iBT', minhigh='$iMinHigh', or_print='$iOR_Print', lgucode='$niLGUName', bodycolor='$iBodyColor', spaywoapprov='$iPaywoApprov', swaivetax='$iWaivetax', swaivefee='$iWaivefee', lgu_add='$lgu_add', lgu_tel='$lgu_tel', predcomp = '$ipredval',iReset='$iReset' ",$dbLink);

	?>
	<body onload='javascript:alert ("Data updated successfully");'></body>
	<?
	$iSubmitApply = "";
	}
	}
	//renewaldate='$iRenewalDate', rateofpenalty='$iLGUPenalty', rateofinterest='$iLGUInterest',
	//$validateID=5;
	//include'validate-inc.php';

	//}
	//else {
	//$validateID=7;
	//include'validate-inc.php';		
	//}
}

$dataRecord_ = mysql_query("SELECT * FROM $tbl_current",$dbLink);
		
if (mysql_affected_rows($dbLink)==1 ){
$dataRecord__=mysql_fetch_array($dataRecord_);
$niSPermit=$dataRecord__[spermit];
$niSAssess=$dataRecord__[sassess];
$niSOR=$dataRecord__[sor];
$niSBackTaxes=$dataRecord__[sbacktaxes];
$niSRequire=$dataRecord__[srequire];
$niSeparateTaxesFees=$dataRecord__[staxesfees];
$niPayment=$dataRecord__[spayment];
$niUnggoy=$dataRecord__[sdecimal];
$niPaywoApprov=$dataRecord__[spaywoapprov];
$niWaivetax=$dataRecord__[swaivetax];
$niWaivefee=$dataRecord__[swaivefee];
$iLGUName=$dataRecord__[lguname];
$iLGUProvince=$dataRecord__[lguprovince];
$iLGUMunicipality=$dataRecord__[lgumunicipality];
$iLGUOffice=$dataRecord__[lguoffice];
$iLGUImage=$dataRecord__[lguimage];
$iMP=$dataRecord__[mp];
$iBT=$dataRecord__[bt];
$iMinHigh=$dataRecord__[minhigh];
$iOR_Print=$dataRecord__[or_print];
$iLGUPenalty=$dataRecord__[rateofpenalty];
$iLGURenewal=$dataRecord__[renewaldate];
$iLGUInterest=$dataRecord__[rateofinterest];
$lgu_add=$dataRecord__[lgu_add];
$lgu_tel=$dataRecord__[lgu_tel];
$ipredcomp=$dataRecord__[predcomp];
$niReset=$dataRecord__[iReset];
if ($ipredcomp == '1') {
	$ipredcheck = "CHECKED";
} else {
	$ipredcheck = "";
}

$iLGURenewalDate=explode('-',$iLGURenewal);
/*echo "1...$iLGURenewalDate[0]<br>";
echo "2...$iLGURenewalDate[1]<br>";
echo "3...$iLGURenewalDate[2]<br>";*/
$iLGURenYear=$iLGURenewalDate[0];
$iLGURenMonth=$iLGURenewalDate[1];
$iLGURenDay=$iLGURenewalDate[2];
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content=" PAARV ">
	<link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
<title></title>
</head>

<form ENCTYPE="multipart/form-data" method="post" name="_FRM" >
<body onLoad="javascript: _FRM.niLGUProvince.focus();">
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">

function flagchange(x)
{
        x.value = 1;
}
function SubmitRef1()
{
	var _FRM = document._FRM;
	if (isBlank(_FRM.niLGUProvince.value) == true) {
		alert("Please select Province.");
		_FRM.niLGUProvince.focus();
		return false;
	}
	if (_FRM.niLGUName.value == "") {
		alert("Please select LGU.");
		_FRM.niLGUName.focus();
		return false;
	}
	_FRM.iSubmitApply.value="Apply";
	_FRM.submit();
	return true;
}
function SubmitRef2()
{
	var _FRM = document._FRM;
	if (_FRM.niLGUProvince.value == "") {
		alert("Enter Valid Province!!");
		_FRM.niLGUProvince.focus();
		return false;
	}
	if (_FRM.niLGUName.value == "") {
		alert("Enter Valid LGU!!");
		_FRM.niLGUName.focus();
		return false;
	}
	doyou = confirm("Changing the setting may have an adverse \n effect on existing applications.\n Do you want to continue?");
	if (doyou==true) {
    	_FRM.iSubmitApply.value="Apply";
		_FRM.submit();
		return true;
	} else {
		_FRM.iSubmitApply.value=""
		alert("Transaction Cancelled");
		_FRM.submit();
		return false;
    }
	
}


</script>


<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=GUName value="<?php echo $GUName;?>">
<input type=hidden name='iSubmitApply'>
<table width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=3 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=3 align=center>
</td>
</tr>
<tr><td colspan=3 class=header2 align=center width=100%>General Reference</td></tr>

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
        document.getElementById('niLGUName').options.length = 0;        // Empty city select box
        if(provCode.length>0){
                ajax.requestFile = 'getCities.php?owner_province_code='+provCode;   // Specifying which file to get
                ajax.onCompletion = createCities;       // Specify function that will be executed after file has been found
                ajax.runAJAX();         // Execute AJAX function
        }
}
                                                                                                                              
function createCities()
{
        var obj = document.getElementById('niLGUName');
        eval(ajax.response);    // Executing the response from Ajax as Javascript code
}
</script>
<tr><td colspan=3 align=center width=100%>&nbsp;</td></tr>
<tr><td colspan=3 align=center width=100%>&nbsp;</td></tr>
<tr>
<td width=15%>&nbsp;</td>
<td align=right>Name of Province </td>
<td align="left" valign="top" class='normal'>&nbsp;
<input type=hidden name=changeondin value=<?php echo $changeondin; ?>>
<input type=hidden name=pro value=<?php echo $pro; ?>>

<?php

        if ($niLGUProvince<>$iLGUProvince and $niLGUProvince<>'') {
              $iLGUProvince=$niLGUProvince;
                print "<input type=hidden name=changeon value=1>";
        }
echo get_select_prov1($dbLink,'niLGUProvince','ebpls_province','province_code','province_desc',$iLGUProvince,$iLGUProvince);
?>


</td>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<td align=right>Name Of LGU</td>
<td align="left" valign="top" class='normal'>&nbsp;
<?php

$LGUDesc = mysql_query("select * from ebpls_buss_preference");
$LGUDescnum = mysql_num_rows($LGUDesc);
if ($LGUDescnum<>0) {
$LGUDesc1 = mysql_fetch_array($LGUDesc);
$iLGUName = $LGUDesc1[lguname];
}
if ($niLGUName<>$iLGUName and $niLGUName=='') {
	$niLGUName=$iLGUName;
}
 if ($niLGUName<>$iLGUName and $niLGUName<>'') {
                $iLGUName=$niLGUName;
        } else {
                $iLGUName = $niLGUName;
        }
echo get_select_city($dbLink,'niLGUName','ebpls_city_municipality','city_municipality_code','city_municipality_desc',$iLGUName,$iLGUProvince);
?>
<!--  <select id="niLGUName" name="niLGUName"  class=select200>
                <option value="<?php echo $niLGUName; ?>"><?php echo $niLGUName; ?></option>
                </select>-->
</td>
</tr>
<tr>
<td width=15%>&nbsp;</td>
<td align=right>Address</td>
<td>&nbsp;&nbsp;<input type=text size=30 name="lgu_add" value="<?php echo $lgu_add; ?>">
</td>
</tr>
<tr>
<td width=15%>&nbsp;</td>
<td align=right>Tel/Fax No.</td>
<td>&nbsp;&nbsp;<input type=text name="lgu_tel" value="<?php echo $lgu_tel; ?>">
</td>
</tr>
<!--
<tr>
<td>Municipality</td>
<td><input type=text name=iLGUMunicipality size=50 maxlength=120 value="<?php echo $iLGUMunicipality;?>"> </td>
</tr>

<tr>
<td>Color Scheme</td>
<td>
<select name=iBodyColor>
<option value="EEEEEE"> Default (silver)
<option value="DDDDDD"> Silver1
<option value="333366"> Blue
<option value="333399"> Blue1
<option value="006666"> Blue Green
<option value="339999"> Blue Green1
<option value="66CCFF"> Navy Blue
<option value="99CCFF"> Navy Blue1
<option value="FF6600"> Orange
<option value="FF9933"> Orange1

</select>
</td>
</tr>
-->
<!--<tr>
<td>Office</td>
<td><input type=text name=iLGUOffice size=50 maxlength=120 value="<?php echo $iLGUOffice;?>"> </td>
</tr>-->

<tr>
<td width=15%>&nbsp;</td>
<td align=right>Logo</td>
<td>&nbsp;&nbsp;<input   type=file  name=fupload  size=30 maxlength=50 value="<?php echo $iLGUImage;?>"><input type=submit name=iSubmitUpload value=Upload></td>
</tr>
<tr>
<td width=15%>&nbsp;</td>
<td align=right>&nbsp;</td>
<td>&nbsp;<font class="def_label"> (Max image size 2Mb / Image types : jpg, png)</font></td>
</tr>


<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<td align=right><b>OPTIONS:</b></td>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
/*$niSPermit=$dataRecord__[spermit];
$niSAssess=$dataRecord__[sassess];
$niSOR=$dataRecord__[sor];
$niSBackTaxes=$dataRecord__[sbacktaxes];
$niSRequire=$dataRecord__[srequire];*/
if ($niSPermit==1){
?>
<td align=right><input type=checkbox name=iSinglePermit value=1 CHECKED></td><td align=left> Allow single permit per line of business</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iSinglePermit value=1></td><td align=left> Allow single permit per line of business</td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niSAssess==1){
?>
<td align=right><input type=checkbox name=iSingleAssessment value=1 CHECKED></td><td align=left> Allow single line assessment</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iSingleAssessment value=1></td><td align=left> Allow single line assessment</td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niSOR==1){
?>
<td align=right><input type=checkbox name=iSingleOR value=1 CHECKED></td><td align=left> Allow official receipt per line of business</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iSingleOR value=1></td><td align=left> Allow official receipt per line of business</td>
<?php
}
?>
</tr>

<!--<tr>
<td width=15%>&nbsp;</td>
<?php
if ($iOR_Print==1){
?>
<td align=right><input type=checkbox name=iOR_Print value=1 CHECKED></td><td align=left> Allow separate O.R. for taxes and fees</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iOR_Print value=1></td><td align=left> Allow separate O.R. for taxes and fees</td>
<?php
}
?>
</tr>-->

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niSBackTaxes==1){
?>
<td align=right><input type=checkbox name=iBackTaxes value=1 CHECKED></td><td align=left> Compare with back tax for retirement</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iBackTaxes value=1></td><td align=left> Compare with back tax for retirement</td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<td align=right><input type=checkbox name=ipredcomp value="ON" <?php echo $ipredcheck;?>></td><td align=left> Compute tax from preceding payment schedule.  <font class="def_label"> (Check separate computation of taxes & fees)</font></td>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niPayment==1){
?>
<td align=right><input type=checkbox name=iPayment value=1 CHECKED></td><td align=left> Allow payment per line of business. <font class="def_label"> (Uncheck if separate computation of taxes & fees is checked)</font></td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iPayment value=1></td><td align=left> Allow payment per line of business. <font class="def_label"> (Uncheck if separate computation of taxes & fees is checked)</font></td>
<?php
}
?>
</tr>


<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niSeparateTaxesFees==1){
?>
<td align=right><input type=checkbox name=iSeparateTaxesFees value=1 CHECKED></td><td align=left> Allow separate computation of taxes & fees. <font class="def_label"> (Uncheck if payment per line of business is checked)</font></td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iSeparateTaxesFees value=1></td><td align=left> Allow separate computation of taxes & fees. <font class="def_label"> (Uncheck if payment per line of business is checked)</font></td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niSRequire==1){
?>
<td align=right><input type=checkbox name=iRequire value=1 CHECKED></td><td align=left> Allow only complete requirements</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iRequire value=1></td><td align=left> Allow only complete requirements</td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niUnggoy==1){
?>
<td align=right><input type=checkbox name=iUnggoy value=1 CHECKED></td><td align=left> Allow Decimal Computations</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iUnggoy value=1></td><td align=left> Allow Decimal Computations</td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niPaywoApprov==1){
?>
<td align=right><input type=checkbox name=iPaywoApprov value=1 CHECKED></td><td align=left> Allow Payment Without Approval</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iPaywoApprov value=1></td><td align=left> Allow Payment Without Approval</td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niWaivetax==1){
?>
<td align=right><input type=checkbox name=iWaivetax value=1 CHECKED></td><td align=left> Allow Waiving of Tax</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iWaivetax value=1></td><td align=left> Allow Waiving of Tax</td>
<?php
}
?>
</tr>

<tr>
<td width=15%>&nbsp;</td>
<?php
if ($niReset==1){
?>
<td align=right><input type=checkbox name=iReset value=1 CHECKED></td><td align=left> Reset permit number every January 1</td>
<?php
}
else {
?>
<td align=right><input type=checkbox name=iReset value=1></td><td align=left> Reset permit number every January 1</td>
<?php
}
?>
</tr>


<!--<tr>
<?php
if ($iMinHigh==1){
?>
<td colspan=2><input type=checkbox name=iMinHigh value=1 CHECKED> Allow Use of Minimum Tax</td>
<?php
}
else {
?>
<td colspan=2><input type=checkbox name=iMinHigh value=1> Allow Use of Minimum Tax</td>
<?php
}
?>
</tr>-->

<!--
<tr>
<td><br></td>
</tr>

<tr bgcolor=#EEEEEE>
<td>Mode of Penalty</td>
</tr>

<tr>
<td>
<table width=90%>
<tr>
<td width=15% align=left>Percent</td>
<td width=75%><input type=text name=iPercent size=10></td>
</tr>

<tr>
<td width=15% align=left>Effectivity Date</td>
<td width=75%><input type=text name=iEffDate size=10></td>
</tr>

</table>
</td>
</tr>
-->
<tr>
<td colspan=2><br></td>
</tr>


<tr>
<td colspan=3></td>
</tr>

<tr>
<td align=center colspan=3>
<?
$chkrecord = @mysql_query("select * from ebpls_buss_preference");
$chkrec = @mysql_num_rows($chkrecord);
 if ($chkrec == 0) {
	?>
<input type=button name=Name1 value=Save onClick="javascript: SubmitRef1();">
<?
} else {
?>
<input type=button name=Name1 value=Save onClick="javascript: SubmitRef2();">
<?
}
?> <!--&nbsp &nbsp &nbsp To view Business Permit & Licensing Setup click <a href=index.php?part=4&class_type=Preference&selMode=ebpls_nbusiness>here</a>.--></td>
</tr>

<tr>
<td colspan=3 align=center>
<?php
$data_item=1;
include'tablemenu-inc.php';
?>
</td>
</tr>

</table>

<?php
mysql_close($thDbLink);
?>
</form>
</body>
</html>

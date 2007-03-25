<?php
include'lib/phpFunctions-inc.php';

dbConnect();
/*********/
/*********/
/*********/
if ($iActivate == "ON") {
	$iActivate = "A";
} else {
	$iActivate = "X";
}

if ($iSubmitPenalty == 'Apply'){
	$iLGUPenalty=strip_tags($iLGUPenalty);
	$iLGUInterest=strip_tags($iLGUInterest);
	$iLGURenMonth=strip_tags($iLGURenMonth);
	$iLGURenDay=strip_tags($iLGURenDay);
	$iLGURenYear=strip_tags($iLGURenYear);
	$iRemarks=strip_tags($iRemarks);
	if ($valueof_id == "") {
	/*
	if (empty($valueof_id)){
		$varOption=0;	
	}
	*/
	//if (checkdate($iRenewalDate)){
	//$iRenewalDate=$iLGURenYear.'-'.$iLGURenMonth.'-'.$iLGURenDay;
		
	$xmyid = @mysql_query("SELECT * FROM $tbl_current where optsurcharge = '$iSurchargePay'"); //id = '$valueof_id' and
	$cntRecord=@mysql_num_rows($xmyid);
	
	if ($cntRecord==0 ){
		if ($iIndicator =='1' and is_numeric($iLGUPenalty)==false) {
			?>
			<body onLoad="javascript: alert('Invalid Surcharge Amount'); _FRM.iLGUPenalty.focus(); _FRM.iLGUPenalty.select();"></body>
			<?
			} else {
	if ($iActivate == 'A') {
		$myidc = @mysql_query("UPDATE $tbl_current SET status='X' WHERE id > '0' ");
	}
	$myid = @mysql_query("INSERT INTO $tbl_current (renewaldate,rateofpenalty,rateofinterest,indicator,status,remarks,createdate,revdate,optsurcharge,surtype,inttype,feeonly) VALUES ('$iRenewalDate','$iLGUPenalty','$iLGUInterest','$iIndicator','$iActivate','$iRemarks',Now(),Now(),'$iSurchargePay','$SurType', '$IntType','$FeeOnly')"); // ,'$iDuePay' // ,optduedates
	
	if ($myid == TRUE){
	?>
	<body onLoad="javascript: alert('Record Successfully Added to the database!!');"></body>
	<?
	}
	else {
	?>
	<body onLoad="javascript: alert('Record could Not Be Added to the database!!');"></body>
	<?
	}
	}
	}
	elseif ($cntRecord==1) { // for record update...
			?>
			<body onLoad="javascript: alert('Existing Payment Mode.'); _FRM.iSurchargePay.focus();"></body>
			<?
	}
	} else {
		$xmyid = mysql_query("SELECT * FROM $tbl_current where optsurcharge = '$iSurchargePay' and id !='$valueof_id'"); //id = '$valueof_id' and
	$cntRecord=mysql_num_rows($xmyid);
	
	if ($cntRecord==0 ){
		if ($iIndicator =='1' and is_numeric($iLGUPenalty)==false) {
			?>
			<body onLoad="javascript: alert('Invalid Surcharge Amount'); _FRM.iLGUPenalty.focus(); _FRM.iLGUPenalty.select();"></body>
			<?
			} else {
	if ($iActivate == 'A') {
		$myidc = mysql_query("UPDATE $tbl_current SET status='X' WHERE id > '0' ");
	}
	$myid = mysql_query("UPDATE $tbl_current SET renewaldate='$iRenewalDate', rateofpenalty='$iLGUPenalty', rateofinterest='$iLGUInterest', indicator='$iIndicator', remarks='$iRemarks', revdate=Now(), status='$iActivate', optsurcharge='$iSurchargePay', optduedates='$iDuePay', inttype='$IntType', feeonly='$FeeOnly', surtype='$SurType' WHERE id='$valueof_id' ");
	
	if ($myid == TRUE){
	?>
	<body onLoad="javascript: alert('Record Successfully Updated to the database!!');"></body>
	<?
	}
	else {
	?>
	<body onLoad="javascript: alert('Record could Not Be Added to the database!!');"></body>
	<?
	}
	}
	}
	elseif ($cntRecord==1) { // for record update...
			?>
			<body onLoad="javascript: alert('Existing Payment Mode.'); _FRM.iSurchargePay.focus();"></body>
			<?
		}
		
	}
	
	//}
	//else {
	//$validateID=7;
	//include'validate-inc.php';		
	//}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<link rel="stylesheet" href="stylesheets/calendar.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="javascripts/calendar1.js?random=20060118"></script>
<script language='Javascript' src='includes/datepick1/datetimepicker.js'></script>
<script language='Javascript'>
function checkvalues()
{
	
	var _FRM = document._FRM
	
	
	if (isBlank(_FRM.iRenewalDate.value)) {
		alert("Invalid/Blank Renewal Date!!");
		return false;
	}
	if (isBlank(_FRM.iLGUPenalty.value)  || _FRM.iLGUPenalty.value<0) {
		alert("Invalid Surcharge!!");
		_FRM.iLGUPenalty.focus();
		_FRM.iLGUPenalty.select();
		return false;
	}
	
	
	
	if (isBlank(_FRM.iLGUInterest.value) || isNaN(_FRM.iLGUInterest.value) || _FRM.iLGUInterest.value<0) {
		alert("Invalid Interest!!");
		_FRM.iLGUInterest.focus();
		_FRM.iLGUInterest.select();
		return false;
	}
	if (_FRM.iSurchargePay.value == "") {
		alert("Invalid/Blank Payment Mode!!")	;
		return false;
	}
	_FRM.iSubmitPenalty.value = "Apply";
	_FRM.submit();
	return true;
}
</script>
<form ENCTYPE="multipart/form-data" method="post" name="_FRM" action="index.php?part=4&class_type=Preference&BusItem=ebpls_ninterestsur&selMode=ebpls_npenalty&action_=7&itemEvent=1&data_item=0">

<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=itemEvent value="<?php echo $itemEvent;?>">
<input type=hidden name=iSubmitPenalty>

<table width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>
<?php
if ($itemEvent==3){

?>
<tr width=100%>
	<td align=center colspan=3 class=header2> Schedule of Payment (Due Dates) </td>
</tr>
<?php
}else {
?>
<tr width=100%>
	<td align=center colspan=3 class=header2> Business Interest/Surcharge </td>
</tr>
<?php
}
?>
<tr><td colspan=2 ><br></td></tr>
<tr><td colspan=2 ><br></td></tr>
<tr>
<td colspan=2 align=center>
<?php
if ($itemEvent==1){
include'renewaldate-inc.php';
}
elseif ($itemEvent==2){
include'includes/lstof_renewaldate-inc.php';
}
elseif ($itemEvent==3){
include'includes/lstof_duedates-inc.php';
}
?>
</td>
</tr>

<input type=hidden name=valueof_id value="<?php echo $valueof_id;?>">

<tr>
<td colspan=2><br></td>
</tr>


<tr>
<td colspan=2>&nbsp;</td>
</tr>
<?php
if ($itemEvent==1){
?>
<tr>
<td colspan=2><input type=button name=SubmitButton value=Apply onclick="javascript: checkvalues();"> &nbsp <a href=index.php?part=4&class_type=<?php echo $class_type;?>&selMode=ebpls_npenalty&action_=7&itemEvent=2&data_item=0>View All</a> | To view Due Dates schedule of payment click <a href=index.php?part=4&class_type=<?php echo $class_type;?>&selMode=ebpls_npenalty&action_=7&itemEvent=3&data_item=0&yahooo=102>here</a>. <!--&nbsp &nbsp &nbsp To view Business Permit & Licensing Setup click <a href=index.php?part=4&selMode=ebpls_nbusiness>here</a>.--></td>
</tr>

<?php
}
?>

<tr>
<td colspan=2 align=center>
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

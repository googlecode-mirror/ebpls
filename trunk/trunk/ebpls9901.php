<?php
include'lib/phpFunctions-inc.php';

dbConnect();

/*********/
/*********/
/*********/

if (isset($iSubmitPenalty)){

	$iLGUPenalty=strip_tags($iLGUPenalty);
	$iLGUInterest=strip_tags($iLGUInterest);
	$iLGURenMonth=strip_tags($iLGURenMonth);
	$iLGURenDay=strip_tags($iLGURenDay);
	$iLGURenYear=strip_tags($iLGURenYear);
	$iRemarks=strip_tags($iRemarks);
	/*
	if (empty($valueof_id)){
		$varOption=0;	
	}
	*/
	if (checkdate($iLGURenMonth, $iLGURenDay, $iLGURenYear)){
	$iRenewalDate=$iLGURenYear.'-'.$iLGURenMonth.'-'.$iLGURenDay;
	
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE status='A' ",$link2db); //id = '$valueof_id' and
	$cntRecord=mysql_num_rows($xmyid);
	
	if ($cntRecord==0 ){
	$myid = mysql_query("INSERT INTO $tbl_current (renewaldate,rateofpenalty,rateofinterest,indicator,status,remarks,createdate,revdate) VALUES ('$iRenewalDate','$iLGUPenalty','$iLGUInterest','$iIndicator','$iActivate','$iRemarks',Now(),Now())", $link2db);
	
	if ($myid == TRUE){
	$validateID=0;
	include'validate-inc.php';					
	}
	else {
	$validateID=1;
	include'validate-inc.php';		
	}
	
	}
	elseif ($cntRecord==1) { // for record update...
	$dataid_ = mysql_query("UPDATE $tbl_current SET renewaldate='$iRenewalDate', rateofpenalty='$iLGUPenalty', rateofinterest='$iLGUInterest', indicator='$iIndicator', remarks='$iRemarks', revdate=Now(), status='$iActivate' WHERE id='$valueof_id' ",$link2db);
	
	$validateID=5;
	include'validate-inc.php';		
	
	} // end od Record Update

	}
	else {
	$validateID=7;
	include'validate-inc.php';		
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content=" FourJ's ">
	<link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
<title></title>
</head>
<body>
<form ENCTYPE="multipart/form-data" method="post">

<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=itemEvent value="<?php echo $itemEvent;?>">

<table width=90% align=center cellspacing=0 cellpadding=0>
<tr>
<td colspan=2 align=center><br>
<?php
$data_item=0;
include'tablemenu-inc.php';
?>
</td>
</tr>

<tr><td colspan=2 bgcolor="#EEEEEE"><?php echo MODTITLESTYLE1;?> Penalty <?php echo MODTITLESTYLE2;?></td></tr>

<tr>
<td colspan=2 align=center>
<?php
if ($itemEvent==1){
include'renewaldate-inc.php';
}
elseif ($itemEvent==2){
include'includes/lstof_renewaldate-inc.php';
}
?>
</td>
</tr>

<input type=hidden name=valueof_id value="<?php echo $valueof_id;?>">

<tr>
<td colspan=2><br></td>
</tr>


<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>
<?php
if ($itemEvent==1){
?>
<tr>
<td colspan=2><input type=submit name=iSubmitPSIC value=Apply> &nbsp <a href=index.php?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=2&data_item=0>View All</a> <img src="../images/back.gif" /><!--&nbsp &nbsp &nbsp To view Business Permit & Licensing Setup click <a href=index.php?part=4&class_type=Preference&selMode=ebpls_nbusiness>here</a>.--></td>
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

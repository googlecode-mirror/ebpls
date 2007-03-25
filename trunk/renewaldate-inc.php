<?php

/*extract data from penalty table*/
//$seleRecord = mysql_query("SELECT * FROM $tbl_current",$dbLink);
//$SeleRecord=th_query($seleRecord);

if ($com=='edit'){
$seleRecord = mysql_query("SELECT * FROM $tbl_current where id = '$valueof_id'",$dbLink);
$seleRecord_=mysql_fetch_array($seleRecord);
$valueof_id=$seleRecord_[id];
$iLGUPenalty=$seleRecord_[rateofpenalty];
$iRenewalDate=$seleRecord_[renewaldate];
$iLGUInterest=$seleRecord_[rateofinterest];
$iRemarks=$seleRecord_[remarks];
$iIndicator=$seleRecord_[indicator];
$SurType=$seleRecord_[surtype];
$IntType=$seleRecord_[inttype];
$FeeOnly=$seleRecord_[feeonly];
if ($iIndicator == '1') {
	$is_check1 = "CHECKED";
} elseif ($iIndicator == '2') {
	$is_check2 = "CHECKED";
}
if ($SurType == '1') {
	$surtypecheck1 = "CHECKED";
} elseif ($SurType == '2') {
	$surtypecheck2 = "CHECKED";
}
if ($IntType == '1') {
	$intypecheck1 = "CHECKED";
} elseif ($IntType == '2') {
	$intypecheck2 = "CHECKED";
}
if ($FeeOnly == '1') {
	$feecheck1 = "CHECKED";
} elseif ($FeeOnly == '2') {
	$feecheck2 = "CHECKED";
} elseif ($FeeOnly == '3') {
	$feecheck3 = "CHECKED";
}
$iActivate=$seleRecord_[status];
$iSurchargePay=$seleRecord_[optsurcharge];
$iDuePay=$seleRecord_[optduedates];
if ($iActivate == "A") {
	$scheck = "CHECKED";
} else {
	$scheck = "";
}
$iLGURenewalDate=explode('-',$iLGURenewal);
$iLGURenYear=$iLGURenewalDate[0];
$iLGURenMonth=$iLGURenewalDate[1];
$iLGURenDay=$iLGURenewalDate[2];

/*extraction of month*/
//	$DatadB = mysql_query("SELECT * FROM ebpls_buss_monthlyref WHERE moid = '$iLGURenMonth' ",$dbLink) or die ('Invalid query');
//	$aValueofRec=mysql_num_rows($DatadB);
//
//	if ($aValueofRec == 1 ){
//	$nidescription_=mysql_fetch_array($DatadB);
//	$niMonthDescription=$nidescription_[modesc];
//	}
//	else {
//	$validateID=1;
//	include'validate-inc.php';	
//	}


}
else {
$valueof_id='';
$iLGUPenalty='';
$iLGURenewal='';
$iLGUInterest='';
$iRemarks='';
$iIndicator='';
//$iActivate=$seleRecord_[status];
//$iLGURenewalDate=explode('-',$iLGURenewal);
$iLGURenYear=date(Y);;
$iLGURenMonth='';
$iLGURenDay='';
	
}

?>

<table>

<tr>
<td>Date of Renewal</td>
<td>
<input type="text" value="<?php echo $iRenewalDate; ?>" readonly name="iRenewalDate" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.iRenewalDate,'mm-dd',this);_FRM.changeondin.value=1;">
<!--<select name=iLGURenMonth>
<?php
//$tbl_current
//ebpls_buss_monthlyref
	$Optiondata = "SELECT * FROM ebpls_buss_monthlyref ORDER BY id ";
	$Optionresult=th_query($Optiondata);
	while($dtarow 	= @mysql_fetch_array($Optionresult)){
		echo "<option value=$dtarow[moid]>$dtarow[modesc]";
	}
	echo "<option value=$iLGURenMonth SELECTED>$niMonthDescription";
	
	
?>
</select> <input type=text name=iLGURenDay size=4 maxlength=2 value="<?php echo $iLGURenDay;?>">&nbsp; 
<input type=hidden name=iLGURenYear size=6 value=<?php echo date('Y');?>><font size=2><?php echo date('Y');?></font>-->
<!--<input type=text name=iLGURenYear size=4 maxlength=4 value="<?php echo $iLGURenYear;?>">-->
</td>
</tr>

<tr>
<td>Surcharge</td>
<td><input type=text name=iLGUPenalty size=5 maxlength=5 value="<?php echo $iLGUPenalty;?>"> &nbsp &nbsp
<input type='radio' name='iIndicator' value='1' <? echo $is_check1;?>>Constant &nbsp <input type='radio' name='iIndicator' value='2' <? echo $is_check2;?>>Formula
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type=radio name='SurType' value='1' <? echo $surtypecheck1;?>>Taxes&nbsp;
	<input type=radio name='SurType' value='2' <? echo $surtypecheck2;?>>Taxes and Fees
</td>
</tr>
<tr>
<td>Interest</td>
<td><input type=text name=iLGUInterest size=5 maxlength=5 value="<?php echo $iLGUInterest;?>"> &nbsp 
&nbsp;<font class="def_label"> (Decimal)</font><input type=checkbox name='iActivate' value='ON' <? echo $scheck;?>>Activate

<!--Surcharge Pay Option-->
<!--
<?php
if ($iAnnual==A){
?>
<input type=checkbox name=iAnnual value='A' CHECKED>Annual
<?php
}
else {
?>
<input type=checkbox name=iAnnual value='A'>Annual
<?php
}
?>
-->
<!--end here-->

</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type=radio name='IntType' value='1' <? echo $intypecheck1;?>>Simple&nbsp;
	<input type=radio name='IntType' value='2' <? echo $intypecheck2;?>>Cumulative
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type=radio name='FeeOnly' value='1' <? echo $feecheck1;?>>Taxes&nbsp;
	<input type=radio name='FeeOnly' value='2' <? echo $feecheck2;?>>Taxes and Fees&nbsp;
	<input type=radio name='FeeOnly' value='3' <? echo $feecheck3;?>>Taxes,Fees and Surcharge
</td>
</tr>
<tr>
<td>Payment Mode</td>
<td>
<select name=iSurchargePay>
<?php
if ($iSurchargePay==MON){
?>
<option value=MON SELECTED>Monthly
<option value=QTR>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN>Annual
<?php
}
elseif ($iSurchargePay==QTR){
?>
<option value=MON>Monthly
<option value=QTR SELECTED>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN>Annual

<?php
}
elseif ($iSurchargePay==SEM){
?>
<option value=MON>Monthly
<option value=QTR>Quarterly
<option value=SEM SELECTED>Semi-Annual
<option value=ANN>Annual

<?php
}
elseif ($iSurchargePay==ANN){
?>
<option value=MON>Monthly
<option value=QTR>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN SELECTED>Annual
<?php
}
else {
?>
<option value=MON>Monthly
<option value=QTR>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN>Annual
<option value='' SELECTED>-Select Here-
<?php
}
?>
</select>
</td>
</tr>
<!--
<tr>
<td>Due Dates Pay</td>
<td>
<select name=iDuePay>
<?php
if ($iDuePay==MON){
?>
<option value=MON SELECTED>Monthly
<option value=QTR>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN>Annual
<?php
}
elseif ($iDuePay==QTR){
?>
<option value=MON>Monthly
<option value=QTR SELECTED>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN>Annual

<?php
}
elseif ($iDuePay==SEM){
?>
<option value=MON>Monthly
<option value=QTR>Quarterly
<option value=SEM SELECTED>Semi-Annual
<option value=ANN>Annual

<?php
}
elseif ($iDuePay==ANN){
?>
<option value=MON>Monthly
<option value=QTR>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN SELECTED>Annual
<?php
}
else {
?>
<option value=MON>Monthly
<option value=QTR>Quarterly
<option value=SEM>Semi-Annual
<option value=ANN>Annual
<option value=XXX SELECTED>-Select Here-
<?php
}
?>
</select>
</td>
</tr>
-->
<tr>
<td>Remarks</td>
<td><textarea name=iRemarks rows=3 cols=50 maxlength=255><?php echo $iRemarks?></textarea></td>
</tr>
<!--
<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2></td>
</tr>
-->
</table>

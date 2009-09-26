<?php
//echo $data_item;
if (isset($iSubmitMonthly)){
	$iMonthlyDueDates=strip_tags($iMonthlyDueDates);
 
	if (checkdate($x3, $x4, $x2)){
		$iMonthlyDueDates=$x2.'-'.$x3.'-'.$x4;
		//echo "$iMonthlyDueDates";
		//exit;
	$xRecord = mysql_query("UPDATE ebpls_buss_penalty1 SET revdate=Now(), mdue='$iMonthlyDueDates' ",$dbLink);
	
	?>
	<body onLoad="javascript: alert('Record Successfully Updated!!');"></body>
	<?
	}
}

////////////////////////////qtrs
if (isset($iSubmitMonthly1)){
	$year = date('Y');
	$date1 = intval(str_replace("-","",$qtr1iMonthlyDueDates));
	$date2 = intval(str_replace("-","",$qtr2iMonthlyDueDates));
	$date3 = intval(str_replace("-","",$qtr3iMonthlyDueDates));
	$date4 = intval(str_replace("-","",$qtr4iMonthlyDueDates));
	if ($date1 > $date2 || $date1 > $date3 || $date1 > $date4 || $date2 > $date3 || $date2 > $date4 || $date3 > $date4) {
		?>
	<body onLoad="javascript: alert('Invalid Dates.'); "></body>
	<?
	} else {
	$iMonthlyDueDates=strip_tags($iMonthlyDueDates);
 

	$xRecord = mysql_query("UPDATE ebpls_buss_penalty1 SET revdate=Now(), qtrdue1='$qtr1iMonthlyDueDates', qtrdue2='$qtr2iMonthlyDueDates', qtrdue3='$qtr3iMonthlyDueDates', qtrdue4='$qtr4iMonthlyDueDates' ",$dbLink);
	
	?>
	<body onLoad="javascript: alert('Record Successfully Updated!!');"></body>
	<?
	}
}
////////////////////////////
//echo $dbLink."==";
/////////////////////////////semi
if (isset($iSubmitMonthly2)){
	//$iMonthlyDueDates=strip_tags($iMonthlyDueDates);
	$year = date('Y');
	$date1 = intval(str_replace("-","",$sem1iMonthlyDueDates));
	$date2 = intval(str_replace("-","",$sem2iMonthlyDueDates));
	if ($date1 > $date2) {
		?>
	<body onLoad="javascript: alert('Invalid Dates.'); "></body>
	<?
	} else {
	$xRecord = mysql_query("UPDATE ebpls_buss_penalty1 SET revdate=Now(), semdue1='$sem1iMonthlyDueDates', semdue2='$sem2iMonthlyDueDates'",$dbLink);
	
	?>
	<body onLoad="javascript: alert('Record Successfully Updated!!');"></body>
	<?
	}
	
}
/////////////////////////////

$seleRecord = mysql_query("SELECT * FROM ebpls_buss_penalty1 ",$dbLink);
//$SeleRecord=th_query($seleRecord);

if (mysql_affected_rows($dbLink)==1 ){
$seleRecord_=mysql_fetch_array($seleRecord);

$iMonthlyDueDates=$seleRecord_[mdue];
$qtr1iMonthlyDueDates=$seleRecord_[qtrdue1];
$qtr2iMonthlyDueDates=$seleRecord_[qtrdue2];
$qtr3iMonthlyDueDates=$seleRecord_[qtrdue3];
$qtr4iMonthlyDueDates=$seleRecord_[qtrdue4];
$sem1iMonthlyDueDates=$seleRecord_[semdue1];
$sem2iMonthlyDueDates=$seleRecord_[semdue2];


}
?>
<input type=hidden name=yahooo value="<?php echo $yahooo;?>">
<br>
<table width=90% align=center>
<tr>
<td colspan=2>[ <a href=index.php?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=3&data_item=0&yahooo=102>Quarterly</a> | <a href=index.php?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=3&data_item=0&yahooo=103>Semi-Annual</a> | <a href=<?php echo $PHP_SELF;?>?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=1&data_item=0>Back to Penalty</a>]</td>
</tr>

<?php
if ($yahooo==101){
?>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td>Date Schedule:</td>
<td>
<input type="text" value="<?php echo $iRenewalDate; ?>" readonly name="iRenewalDate" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.iRenewalDate,'mm-dd',this);_FRM.changeondin.value=1;">
<!--<select name=x3>
<?php
//$tbl_current
//ebpls_buss_monthlyref
//	$Optiondata = "SELECT * FROM ebpls_buss_monthlyref ORDER BY id ";
//	$Optionresult=th_query($Optiondata);
//	while($dtarow 	= @mysql_fetch_array($Optionresult)){
//		echo "<option value=$dtarow[moid]>$dtarow[modesc]";
//	}
//	echo "<option value=$x3 SELECTED>$x2description";
	
	
?>
</select> <input type=text name=x4 size=4 maxlength=2 value="<?php echo $x4;?>"> <?php echo $iLGURenYear;?><input type=text name=x2 size=4 maxlength=4 value="<?php echo $x2;?>">-->
</td>
</tr>

<!--
<tr>
<td width=15%>Date :</td>
<td width=75%><input type=text name=iMonthlyDueDates size=10 value="<?php echo $iMonthlyDueDates;?>"></td>
</tr>
-->

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><input type=submit name=iSubmitMonthly value=Save>&nbsp;<input type=button name=iCancelMonthly value=Cancel onClick="parent.location='<?php echo $PHP_SELF;?>?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=1&data_item=0'">&nbsp;<input type=reset name=iResetMonthly value=Reset><!--<input type=reset name=iSubmitReset value=Reset>--></td>
</tr>

<?php
}
elseif ($yahooo==102){
?>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td>Date Schedule Qtr1 :</td>
<td>
<input type="text" value="<?php echo $qtr1iMonthlyDueDates; ?>" readonly name="qtr1iMonthlyDueDates" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.qtr1iMonthlyDueDates,'mm-dd',this);_FRM.changeondin.value=1;">
<!--<select name=x31>
<?php
//$tbl_current
//ebpls_buss_monthlyref
//	$Optiondata = "SELECT * FROM ebpls_buss_monthlyref ORDER BY id ";
//	$Optionresult=th_query($Optiondata);
//	while($dtarow 	= @mysql_fetch_array($Optionresult)){
//		echo "<option value=$dtarow[moid]>$dtarow[modesc]";
//	}
//	echo "<option value=$x31 SELECTED>$x31description";
	
	
//?>
</select> <input type=text name=x41 size=4 maxlength=2 value="<?php echo $x41;?>"> <?php echo $iLGURenYear;?><input type=hidden name=x21 size=4 maxlength=4 value="<?php echo date('Y');?>">&nbsp;<font size=2><?php echo date('Y');?></font>-->
</td>
</tr>

<!--qtr2-->
<tr>
<td>Date Schedule Qtr2 :</td>
<td>
<input type="text" value="<?php echo $qtr2iMonthlyDueDates; ?>" readonly name="qtr2iMonthlyDueDates" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.qtr2iMonthlyDueDates,'mm-dd',this);_FRM.changeondin.value=1;">
<!--<select name=x311>
<?php
//$tbl_current
//ebpls_buss_monthlyref
//	$Optiondata = "SELECT * FROM ebpls_buss_monthlyref ORDER BY id ";
//	$Optionresult=th_query($Optiondata);
//	while($dtarow 	= @mysql_fetch_array($Optionresult)){
//		echo "<option value=$dtarow[moid]>$dtarow[modesc]";
//	}
//	echo "<option value=$x311 SELECTED>$x311description";
	
	
//?>
</select> <input type=text name=x411 size=4 maxlength=2 value="<?php echo $x411;?>"> <?php echo $iLGURenYear;?><input type=hidden name=x211 size=4 maxlength=4 value="<?php echo date('Y');?>">&nbsp;<font size=2><?php echo date('Y');?></font>-->
</td>
</tr>

<!--qtr3-->

<tr>
<td>Date Schedule Qtr3 :</td>
<td>
<input type="text" value="<?php echo $qtr3iMonthlyDueDates; ?>" readonly name="qtr3iMonthlyDueDates" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.qtr3iMonthlyDueDates,'mm-dd',this);_FRM.changeondin.value=1;">
<!--<select name=x3111>
<?php
//$tbl_current
//ebpls_buss_monthlyref
	$Optiondata = "SELECT * FROM ebpls_buss_monthlyref ORDER BY id ";
	$Optionresult=th_query($Optiondata);
	while($dtarow 	= @mysql_fetch_array($Optionresult)){
		echo "<option value=$dtarow[moid]>$dtarow[modesc]";
	}
	echo "<option value=$x3111 SELECTED>$x3111description";
	
	
?>
</select> <input type=text name=x4111 size=4 maxlength=2 value="<?php echo $x4111;?>"> <?php echo $iLGURenYear;?><input type=hidden name=x2111 size=4 maxlength=4 value="<?php echo date('Y');?>">&nbsp;<font size=2><?php echo date('Y');?></font>-->
</td>
</tr>

<!--qtr4-->
<tr>
<td width=25%>Date Schedule Qtr4 :</td>
<td align=left>
<input type="text" value="<?php echo $qtr4iMonthlyDueDates; ?>" readonly name="qtr4iMonthlyDueDates" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.qtr4iMonthlyDueDates,'mm-dd',this);_FRM.changeondin.value=1;">
<!--<select name=x31111>
<?php
//$tbl_current
//ebpls_buss_monthlyref
	$Optiondata = "SELECT * FROM ebpls_buss_monthlyref ORDER BY id ";
	$Optionresult=th_query($Optiondata);
	while($dtarow 	= @mysql_fetch_array($Optionresult)){
		echo "<option value=$dtarow[moid]>$dtarow[modesc]";
	}
	echo "<option value=$x31111 SELECTED>$x31111description";
	
	
?>
</select> <input type=text name=x41111 size=4 maxlength=2 value="<?php echo $x41111;?>"> <?php echo $iLGURenYear;?><input type=hidden name=x21111 size=4 maxlength=4 value="<?php echo date('Y');?>">&nbsp;<font size=2><?php echo date('Y');?></font>-->
</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><input type=submit name=iSubmitMonthly1 value=Save>&nbsp;<input type=button name=iCancelMonthly value=Cancel onClick="parent.location='<?php echo $PHP_SELF;?>?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=1&data_item=0'">&nbsp;<input type=reset name=iResetMonthly value=Reset><!--<input type=reset name=iSubmitReset value=Reset>--></td>
</tr>

<?php
}
elseif ($yahooo==103){
?>
<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td width=25%>Date Schedule Semi-Annual 1 :</td>
<td align=left>
<input type="text" value="<?php echo $sem1iMonthlyDueDates; ?>" readonly name="sem1iMonthlyDueDates" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.sem1iMonthlyDueDates,'mm-dd',this);_FRM.changeondin.value=1;">

</td>
</tr>

<!--qtr2-->
<tr>
<td>Date Schedule Semi-Annual 2 :</td>
<td>
<input type="text" value="<?php echo $sem2iMonthlyDueDates; ?>" readonly name="sem2iMonthlyDueDates" onclick="displayCalendar(checkid2,'mm-dd',this);">
                        <img src="images/cal.gif" width="16" height="16" border="0" alt="Pick a date" onclick ="displayCalendar(_FRM.sem2iMonthlyDueDates,'mm-dd',this);_FRM.changeondin.value=1;">

</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<tr>
<td colspan=2><input type=submit name=iSubmitMonthly2 value=Save>&nbsp;<input type=button name=iCancelMonthly value=Cancel onClick="parent.location='<?php echo $PHP_SELF;?>?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=1&data_item=0'">&nbsp;<input type=reset name=iResetMonthly value=Reset><!--<input type=reset name=iSubmitReset value=Reset>--></td>
</tr>
<?php
}
?>
</table>

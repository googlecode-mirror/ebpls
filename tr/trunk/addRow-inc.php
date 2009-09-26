<?php
if (!empty($aTAXFEEid)){

	if (!empty($rangeAction) and $rangeAction=='Delete'){
	$listmyid = "SELECT * FROM ebpls_buss_taxrange WHERE rangeid='$rangeid' and rangestatus='A' LIMIT 1";	
	}
	else {
	$listmyid = "SELECT * FROM ebpls_buss_taxrange WHERE taxfeeid='$aTAXFEEid' and rangestatus='A' ORDER BY rangeid ASC ";
	}
	$Dbresult=th_query($listmyid);
	 $lastRecord_=0;
	 $ctr=0;
	while($dtarow 	= @mysql_fetch_array($Dbresult)){
	
	$rangelow[$ctr]=$dtarow[rangelow];
	$rangehigh[$ctr]=$dtarow[rangehigh];
	$rangeamount[$ctr]=$dtarow[rangeamount];
	$rangeind[$ctr]=$dtarow[rangeind];
	$rangeID[$ctr]=$dtarow[rangeid];
	
	$lastRecord_++;
	$ctr++;
	
}


if ($lastRecord_==0){
$lastRecord_=15;	
$editRange=0;
}
else{
$editRange=1;
}

}
else {
$ctr=0;	
}
?>

<input type=hidden name=editRange value="<?php echo $editRange;?>">
<input type=hidden name=lastRecord_ value="<?php echo $lastRecord_;?>">
<input type=hidden name=rangeid value="<?php echo $rangeid;?>">
<input type=hidden name=rangeAction value="<?php echo $rangeAction;?>">


<?php
if (empty($eventApply)) {
?>

<table>
<tr>
<td>No.</td>
<td>Low</td>
<td>High</td>
<td>Amount</td>
<td>Indicator</td>
<td>&nbsp</td>
</tr>

<?php
$ctr=0;
$linectr=1;
while ($ctr < $lastRecord_){
?>

<input type=hidden name=rangeID[] value="<?php echo $rangeID[$ctr];?>">

<tr>
<td><?php echo $linectr;?></td>
<td><input type=text name=rangelow[<?php echo $ctr;?>] size=15 value="<?php echo $rangelow[$ctr];?>"></td>
<td><input type=text name=rangehigh[<?php echo $ctr;?>] size=15 value="<?php echo $rangehigh[$ctr];?>"></td>
<td><input type=text name=rangeamount[<?php echo $ctr;?>] size=15 value="<?php echo $rangeamount[$ctr];?>"></td>
<td>
<select name=rangeind[<?php echo $ctr;?>]>
<?php
if ($rangeind[$ctr]==1){
?>
<option value=1 SELECTED>Amount
<option value=2>Formula
<?php
}
elseif ($rangeind[$ctr]==2){
?>
<option value=1>Amount
<option value=2 SELECTED>Formula
<?php
}
else {
?>
<option value=1>Amount
<option value=2>Formula
<?php
}
?>
</select>
</td>
<?php
if ($valueof_ResultId==1){
//<a href=$PHP_SELF?natureid=$dtarow[natureid]&action_=1&actionID=1&part=$part&selMode=$selMode&natureaction=Edit&bussEvent=$bussEvent&aTFOID=$dtarow[tfoid]&valueof_ResultId=1&aTAXFEEid=$dtarow[taxfeeid]>Edit</a>
?>
<td><a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&action_=1&class_type=Preference&pref_type=Business&actionID=1&part=<?php echo $part;?>&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeID[$ctr];?>&rangeAction=Delete>Delete</a></td>
<?php
}
else {
?>

<td>111&nbsp</td>
</tr>
<?php
}
?>

<?php
$ctr++;
$linectr++;
}
?>

<?php
if (!empty($eventAddRow) and $eventAddRow=='PlusOne'){ // Adding new row for range value
$taddr=0;
$ctr=0;
while ($taddr < $nofrow) {
?>

<tr>
<td><?php echo $linectr;?></td>
<td><input type=text name=AddRangeLow[<?php echo $ctr;?>] size=15 value="<?php echo $AddRangeLow[$ctr];?>"></td>
<td><input type=text name=AddRangeHigh[<?php echo $ctr;?>] size=15 value="<?php echo $AddRangeHigh[$ctr];?>"></td>
<td><input type=text name=AddRangeAmount[<?php echo $ctr;?>] size=15 value="<?php echo $AddRangeAmount[$ctr];?>"></td>
<td>
<select name=AddRangeInd[<?php echo $ctr;?>]>
<?php
if ($AddRangeInd==1){
?>
<option value=1 SELECTED>Amount
<option value=2>Formula
<?php
}
elseif ($AddRangeInd==2) {
?>
<option value=1>Amount
<option value=2 SELECTED>Formula
<?php
}
else {
?>
<option value=1>Amount
<option value=2>Formula
<?php
}
?>
</select>
</td>
<?php
if ($valueof_ResultId==1){
//<a href=$PHP_SELF?natureid=$dtarow[natureid]&action_=1&actionID=1&part=$part&selMode=$selMode&natureaction=Edit&bussEvent=$bussEvent&aTFOID=$dtarow[tfoid]&valueof_ResultId=1&aTAXFEEid=$dtarow[taxfeeid]>Edit</a>
?>
<td><a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&action_=1&actionID=1&part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeID[$ctr];?>&rangeAction=Delete>Delete</a></td>
<?php
}
?>

<td>&nbsp</td>
</tr>
<?php
$taddr++;
$ctr++;
$linectr++;
}
}
?>


<?php
if (!empty($rangeAction) and $rangeAction=='Delete'){
	//echo "$rangeid";
?>
<tr>
<td colspan=6 align=center><br></td>
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=6 align=center><font color="#ff0033">Are you sure you want to delete this record??? [ <a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&class_type=Preference&pref_type=Business&action_=1&actionID=1&part=<?php echo $part;?>&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeid;?>&rangeAction=&OkforDelete=RangeValue>Yes</a> ] [ <a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&class_type=Preference&pref_type=Business&action_=1&actionID=1&part=<?php echo $part;?>&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeid;?>&rangeAction=>Cancel</a> ]</font></td>
</tr>

<tr>
<td colspan=6 align=center><br></td>
</tr>

<?php
}
?>

<tr>
<td colspan=4><input type=submit name=iSaveRange value="Save"><input type=submit name=iCancelRange value="Cancel"><input type=reset name=Reset value="Reset"> <?php if ($natureaction==Edit){}else {?><input type=submit name=iRows value="Add Rows"><?php } ?> <?php if ($valueof_ResultId==1){?><input type=text name=nofrow size=5 maxlength=2>&nbsp;<input type=submit name=addingrow value='Add Row'><!--<a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&class_type=Preference&pref_type=Business&action_=1&actionID=1&part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeid;?>&rangeAction=&eventAddRow=PlusOne&nofrow=<?php echo $nofrow;?>>Add Row</a>--> | <a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&action_=101&actionID=1&part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeid;?>&rangeAction=&eventApply=Applyto>Apply Range To</a> <!--| <a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&action_=101&actionID=1&part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&natureaction=Edit&bussEvent=<?php echo $bussEvent;?>&aTFOID=<?php echo $aTFOID;?>&valueof_ResultId=1&aTAXFEEid=<?php echo $aTAXFEEid;?>&rangeid=<?php echo $rangeid;?>&rangeAction=&eventApply=Copyto>Copy Range To</a> -->| <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=<?php echo $bussEvent;?>&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Add New</a> |<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&natureid=<?php echo $natureid;?>&action_=1&actionID=1&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&natureaction=Edit>Back to list</a><?php } ?></td>
</tr>
</table>

<?php 
}
else {
?>


<?php
}
?>
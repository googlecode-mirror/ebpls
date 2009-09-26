<?php

	if ($permit_type=='Franchise') {
		$fsel='selected';
	}elseif ($permit_type=='Motorized') {
		$msel='selected';
        }elseif ($permit_type=='Occupational') {
		$osel='selected';
        }elseif ($permit_type=='Peddlers') {
		$psel='selected';
        }elseif ($permit_type=='Fishery') {
		$fisel='selected';
        }

?>

<tr>
<input type=hidden name=searc>
<td align="right" align="center"> Permit Type </td>
<td align="left" align="center"> &nbsp;&nbsp;<select name=feet class=select2000 onchange='_FRM.searc.value=1; _FRM.submit();'>
<!--<option value=Franchise <?php echo $fsel; ?>>Franchise</option>-->
<option value=Motorized <?php echo $msel; ?>>Motorized</option>
<option value=Occupational <?php echo $osel; ?>>Occupational</option>
<option value=Peddlers <?php echo $psel; ?>>Peddlers</option>
<option value=Fishery <?php echo $fisel; ?>>Fishery</option>
</td>
</tr>
<tr>
<td align="right" align="center">
Fee Description:
</td>
<td align="left" align="center">
<input type=hidden name='feeid' value=<?php echo $id ?>>
&nbsp&nbsp<input type=text name =feedesc value="<?php echo $feedesc ?>" >&nbsp;<font class="def_label"> (1 - 15 chars)</font>

&nbsp; <input type=submit name='searchfee' value="Search" > </td>

</tr>

<tr>
<td align="right" valign="center">
Amount:
</td>
<td align="left" valign="center">
&nbsp&nbsp<input type=text name =feeamount value='<?php echo $feeamount ?>'>&nbsp;<font class="def_label"> (1 - 9999999999)</font>
</td>
</tr>

<tr>
<td align="right" valign="center">
Transaction Type:
</td>
<td align="left" valign="center">
&nbsp

<select name='ptype'>
<?php 
if ($com=='' || $com=='Delete') {
?>
<option value='New'>New</option>
<option value='ReNew'>ReNew</option>
<?php
       if ($feet=='Franchise' || $feet=='Motorized') {
?>

<option value='Transfer/Dropping'>Transfer/Dropping</option>
<?php
	}
} else {
?>
<option value="<? echo $ptype; ?>"><?php echo $ptype; ?></option>
<option value='New'>New</option>
<option value='ReNew'>ReNew</option>
<?php
	if ($feet=='Franchise' || $feet=='Motorized') {
?>
<option value='Transfer/Dropping'>Transfer/Dropping</option>
<?php
	}
}
?>

</select>
</td>
</tr>
<?php
if ($feet == "Motorized") {
?>
<tr>
	<td align=center>Every</td>
	<td>&nbsp;&nbsp;<input type=text name="MFees" value="<? echo $MFees;?>"> year(s)</td>
</tr>
<?php
}
?>

<tr>
<td align="right" valign="center" class='subtitle'>
&nbsp
</td>
<td align="left" valign="center" class='subtitle'>
&nbsp
</td>
</tr>

<tr>
<td align="right" valign="center" class='subtitle'>
<!--<input type = submit value="Save" name="mtopadd" onClick='javascript:CheckData(feedesc,feeamount);'>
-->
</td>
<td align="left" valign="center" class='subtitle'>&nbsp;&nbsp;
<input type=hidden name=sb>
<input type = button value="Save" name="mtopadd" onClick='VerifyOtherPermit();'>
<input type=button value='Cancel' onclick='_FRM.feedesc.value=""; _FRM.feeamount.value=""; ClearValues();'>
<input type = button value="Reset" onclick='javascript: _FRM.feedesc.value=""; _FRM.feeamount.value=""; _FRM.feet.focus();'>
</td>
</tr>


                <!---// start of the table //-->
                <table border=0 cellspacing=0 cellpadding=0  width='620'>
                                <tr><td align="center" valign="top" class='' colspan=2>&nbsp;</td>
                                </tr>
		<br>
		</table>
<script language="javascript">
function ClearValues()
{
	_FRM = document._FRM;
	alert("Transaction Cancelled.");
	_FRM.feet.value = "";
	_FRM.feedesc.value = "";
	_FRM.feeamount.value = "";
	_FRM.ptype.value = "";
	_FRM.submit();
	return true;
}
</script>
<body onLoad="javascript: _FRM.feet.focus();"></body>


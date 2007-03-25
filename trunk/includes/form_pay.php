<?php

$getyearnow = date('Y');
$getpay = SelectMultiTable($dbtype,$dbLink,"temppayment",
			"payid, payamt, pay_date, pay_type, 
			or_no,status", 
			"where owner_id = $owner_id and permit_type='$permit_type' 
			and permit_status='$stat' and pay_date like '$getyearnow%'");
$payedamount = 0;
while ($getpayed = mysql_fetch_assoc($getpay)) {
	$payedamount = $payedamount + $getpayed['payamt'];
}
$payedass = 0;

if ($checkrentype1['renewaltype'] == '1' and $stat=="ReNew") {
$gettopay = SelectMultiTable($dbtype,$dbLink,"ebpls_mot_penalty",
                        "surcharge, interest, late, backtax",
                        "where motorized_permit_id = '$owner_id'
                        and date_input like '$getyearnow%'");
						
while ($gettopayed = mysql_fetch_assoc($gettopay)) {
        $payedass = $payedass + $gettopayed['surcharge'];
        $payedass = $payedass + $gettopayed['interest'];
		$payedass = $payedass + $gettopayed['late'];
		$payedass = $payedass + $gettopayed['backtax'];

}
}

if ($checkrentype1['renewaltype'] == '2' and $stat=="ReNew") {
	
$gettopay = SelectMultiTable($dbtype,$dbLink,"ebpls_mot_penalty",
                        "surcharge, interest, late, backtax",
                        "where motorized_permit_id = '$owner_id'
                        and date_input like '$getyearnow%'");
						
while ($gettopayed = mysql_fetch_assoc($gettopay)) {
        $payedass = $payedass + $gettopayed['surcharge'];
        $payedass = $payedass + $gettopayed['interest'];
		$payedass = $payedass + $gettopayed['late'];
		$payedass = $payedass + $gettopayed['backtax'];

}
}


/*$gettopay1 = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
                        "fee_amount, multi_by",
                        "where owner_id = '$owner_id' and permit_type='$permit_type'
                        and input_date like '$getyearnow%' and active = '0' and permit_status != '$stat'");
while ($gettopayed1 = mysql_fetch_assoc($gettopay1)) {
        $payedass = $payedass + ($gettopayed1['fee_amount'] * $gettopayed1['multi_by']);

}

$gettopay2 = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
                        "fee_amount, multi_by",
                        "where owner_id = '$owner_id' and permit_type='$permit_type'
                        and input_date like '$getyearnow%' and active = '0' and permit_status = '$stat'");
while ($gettopayed2 = mysql_fetch_assoc($gettopay2)) {
        $payedass = $payedass + ($gettopayed2['fee_amount'] * $gettopayed2['multi_by']);

}*/

if ($checkrentype1['renewaltype'] == '2' and $stat =="ReNew") {
	$getfees908 = @mysql_query("select * from temppayment  where permit_type= '$permit_type' and permit_status = '$stat' and owner_id = '$owner_id' and pay_date like '$getyearnow%' and status = '0'");
	$addamty = 0;
	while ($addamty34 = mysql_fetch_assoc($getfees908)) {
		$addamty = $addamty + $addamty34['payamt'];
	}
}
if ($permit_type == "Motorized" and $checkrentype1['renewaltype'] == '1' and $stat =="ReNew") {
	$getpaidpen = @mysql_query("select * from ebpls_mot_penalty  where motorized_permit_id= '$owner_id'");
	$paidpen = 0;
	while ($getpaidpen11 = mysql_fetch_assoc($getpaidpen)) {
		$paidpen = $paidpen + $getpaidpen11['surcharge'] + $getpaidpen11['late'] + $getpaidpen11['interest'] + $getpaidpen11['backtax'];
	}
}
//$balancefornow = ($totalfee + $paidpen) - $payedamount;
//$balancefornow = $totalfee - $payedamount +$payedass;
$balancefornow = $totalbills - $payedamount;
//echo "$balancefornow = $totalfee - $payedamount +$payedass";
//$balancefornow = $totalfee - $payedamount + $addamty + $payedass;
//echo $addamty."VooDoo";
if ($balancefornow > 0) {
?>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function checkValidpay()
{
        var _FRM = document._FRM;
                                                                                                               
                var msgTitle = "Payment\n";
                                                                                                               
                if(isNaN(_FRM.amtpaid.value))
                {
                        alert( msgTitle + "Please input a valid amount!");
                        _FRM.amtpaid.focus();
                        return false;
                }
		if(_FRM.orno1.value=='')
                {
                        alert( msgTitle + "Please input a valid or number!");
                        _FRM.orno1.focus();
                        return false;
                }
                
            if(_FRM.orno1.value.length>10)
                {
                        alert( msgTitle + "Or Number Exceed Max Length");
                        _FRM.orno1.focus();
                        _FRM.orno1.select();
                        return false;
                }

                _FRM.submit();                                                                                               
}
                                                                                                               
</script>


<br>
<table cellpadding=1 cellspacing=1 border=0 align=center width=50% >
<!--	  <tr>
      		<td align=right>Control Number :
	        </td>
	<td>
<input type="hidden" name="orno" value="<?php echo $or_no; ?>" ><?php echo $or_no; ?>
</td>
<td style="vertical-align: top; text-align: left;">
</td>

</tr>-->
	<tr>
      	<td align=right>O.R. Number :
	    </td>
		<td>
			<input type="text" name="orno1" value="" size=5 >
		</td>
		<td style="vertical-align: top; text-align: left;">
		</td>

</tr>
<tr>
      <td align=right>Amount Paid :<br>
      </td>
      <td style="vertical-align: top; text-align: left;"><input
 type="text" name="amtpaid" value="<?php echo round($totalfee - $payedamount + $payedass, 2); ?>"
	 size="5" readonly>
      
<input
 type="button" name="addpay" value="Process" size="5" readonly <?php $PROCESS="" ?>
onClick="javascript:checkValidpay()"><br>
      </td>

    </tr>
    <tr>
      <td align=right><br>
      </td>

	<td style="vertical-align: top; text-align: left;">
	<input type = hidden name=amtchange value="<?php echo $amtchange; ?>">

      </td>
 <td style="vertical-align: top;"><br>
      </td>

    </tr>
    <tr>
      <td style="vertical-align: top; height: 50%; width: 50%;"><br>
      </td>
      <td style="vertical-align: top;"><br>
      </td>

<td style="vertical-align: top; text-align: left;"> </td>

    </tr>
  
</table>
<?
}
?>

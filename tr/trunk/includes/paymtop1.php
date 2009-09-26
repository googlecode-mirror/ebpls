<?php

require_once "includes/variables.php";
// get total vehicle
$tfee = SelectMultiTable($dbtype,$dbLink,$permittable,
			"owner_id", 
			"where owner_id = '$owner_id' and transaction='$stat' and active='1' and paid='0'");
			
$ispay=NumRows($dbtype,$tfee);


if ($permit_type=='Motorized' || $permit_type=='Franchise') {

	$totvec=SelectMultiTable($dbtype,$dbLink,"ebpls_motorized_vehicles",
  			"count(motorized_motor_id)",
                        "where motorized_operator_id = $owner_id and permit_type='$tag'");
$tot = FetchRow($dbtype,$totvec);
?>
<input type=hidden name=totalvec value=<?php echo $tot[0]; ?>>
Total vehicles: <?php echo $tot[0]; ?> <br><br>
<table border=1>
<tr>
<td> Vehicle ID </td><td> Motor Model </td><td> Motor Number </td>
<td> Chasis Number </td><td> Plate Number </td><td> Body Number </td>
<td> Route </td><td> Line Type </td>
</tr>
<?php
//populate vehicle
$result = SelectMultiTable($dbtype,$dbLink,$vehicle, 
			"motorized_motor_id, motorized_motor_model, motorized_motor_no, 
			 motorized_chassis_no, motorized_plate_no, motorized_body_no,
			 route, linetype",
			"where motorized_operator_id = $owner_id and permit_type='$tag'");
while ($get_info = FetchRow($dbtype,$result)){
print "<tr>\n";
foreach ($get_info as $field )
print "<td>&nbsp;$field&nbsp</td>\n";
print "</tr>\n";
}//end while
print "</table>";

} // end if not occu

print "<br>";
require_once "includes/form_add_mtoassesment.html";

//if ($permit_type=='Occupational') {
if ($permit_type<>'Fishery') {
print "<table border = 1 >";
print "<tr>";
print "<td>";
print "Fee </td> <td>";
print "Amount </td> ";
}

	if ($permit_type=='Motorized' || $permit_type=='Franchise') {
		print "<td>No. of Units</td><td>Total Amount</td></tr>";
	}


if ($permit_type=='Motorized' || $permit_type=='Franchise') {
$getyearnow = date('Y');
//get from fees paid
$getfees1 = @mysql_query("select * from ebpls_fees_paid where permit_type= '$permit_type' and permit_status = '$stat' and owner_id = '$owner_id' and input_date like '$getyearnow%'");
$getnoveh = @mysql_query("select * from ebpls_motorized_vehicles where motorized_operator_id = '$owner_id' and status = '1'");
$tot = mysql_num_rows($getnoveh);
$totalfee = 0;
while ($getfees = @mysql_fetch_assoc($getfees1)) {
print "<tr>\n";
//foreach ($getfee as $field )
print "<td>&nbsp;$getfees[fee_desc]&nbsp</td>\n";
print "<td align=right>&nbsp;$getfees[fee_amount]&nbsp</td>\n";
print "<td align=right>&nbsp;$tot&nbsp;</td>\n";
$tamt =$getfees[fee_amount] * $tot;
$totalamt = number_format($tamt,2);
print "<td align=right>&nbsp;$totalamt&nbsp;</td>";
print "</tr>\n";
$totalfee = $totalfee + $tamt;
}

include_once "includes/other_permit_penalty.php";
$totalfee=$totalfee + $otherpen + $otherint + $otherlate;
$totalfeenf = number_format($totalfee,2);
?>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>Grand Total:</td>
<td align=right>&nbsp;<?php echo $totalfeenf; ?> </td> </tr>
</table>
</td></table>
<?php
	if ($stat<>'New') {
		$ota=$totalfeenf;
		$totfee=0;
		require_once"includes/penalty.php";
		$totalpay=$grandamt;
	} else {
		$totalpay=$totalfeenf;
	}
} else {

	if ($permit_type==Fishery) {
	$getboat = SelectDataWhere($dbtype,$dbLink,"fish_boat","where owner_id=$owner_id");
	while ($getb = FetchRow($dbtype,$getboat))
	{
		$getfee = SelectMultiTable($dbtype,$dbLink,"boat_fee",
			"amt,range_lower, range_higher",
			"where boat_type='$getb[4]' and
                        range_lower<$getb[5] and range_higher>=$getb[5] and
                        transaction='$stat' and active = 1");
		$getnum=NumRows($dbtype,$getfee);
       	 	if ($getnum==0) {
                $getfee = SelectMultiTable($dbtype,$dbLink,"boat_fee","amt",
			"where boat_type='$getb[4]' and
                        range_lower<=$getb[5] and range_higher=0 and
                        transaction='$stat' and active = 1");
        	}
	$getfee = FetchRow($dbtype,$getfee);
	$ttfee = $ttfee+$getfee[0];
	}
	$getot = SelectDataWhere($dbtype,$dbLink,$fee," where permit_type='$stat' and active=1");
	$getact = SelectMultiTable($dbtype,$dbLink,"fish_activity","sum(act_fee)",
			"where owner_id=$owner_id and transaction='$stat' and active = 1");
	$getact = FetchRow($dbtype,$getact);
	$tfee1 = $getact[0];
	
	
	
$getboat = SelectDataWhere($dbtype,$dbLink,"fish_assess","where owner_id=$owner_id");
while ($getb = FetchArray($dbtype,$getboat))
{
$getfee = SelectDataWhere($dbtype,$dbLink,"culture_fee",
			"where culture_id='$getb[culture_id]' and
		    transaction='$stat' and active = 1  ");
			
$getnum = FetchArray($dbtype,$getfee);
       
		if ($getnum[fee_type]=='3') {
				$getfee = SelectDataWhere($dbtype,$dbLink,"culture_range",
					"where culture_id='$getb[culture_id]' and
		             range_lower<$getb[amt] and range_higher>=$getb[amt] ");
				$getnum = NumRows($dbtype,$getfee);
		        	if ($getnum==0) {
			
	            		$getfee =  SelectDataWhere($dbtype,$dbLink,"culture_range",
                        "where culture_id='$getb[culture_id]' and
                        range_lower<=$getb[amt] and range_higher=0");
                	}
    	} 

        
$getfee1 = FetchArray($dbtype,$getfee);
$ttfee1 = $ttfee1+$getfee1[amt]; 
}
	
	
	
	
	
	
	
	
	
?>
	<table width=60%>
	<tr>
	<td>Fees from Boat Registration</td><td><?php echo number_format($ttfee,2); ?></td>
	</tr>
	<tr>
	<td>Fees from Fish Activities</td><td><?php echo number_format($ttfee1,2); ?></td>
	</tr>
	<tr>
	<td>Other Fees</td><td></td></tr>
	<?php
		while ($getj = FetchRow($dbtype,$getot))
		{
	?>
	<tr>
	<td align=right><?php echo $getj[1]; ?> &nbsp; -----------------</td><td><?php echo $getj[2]; ?></td>
	</tr>
	<?php
	$ff = $ff + $getj[2];
		}
	?>
	<tr><td></td><td>_______</td></tr>
	<tr>
	<td>Total Assessement</td><td><?php $totass = $ttfee+$ttfee1+$ff; echo number_format($totass,2); ?></td>
	</tr>
<?php
	$totalfee = $totass;
include_once "includes/other_permit_penalty.php";
$totass=$totass + $otherpen + $otherint + $otherlate;
$totalfeenf = number_format($totass,2);
?>
<tr>
<td>Grand Total</td><td><?php echo number_format($totass,2); ?></td>
</tr>
<?php
	} else {
	$mfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
				"fee_desc, fee_amount",  
				"where owner_id = $owner_id and permit_type='$permit_type' 
				and permit_status='$stat'");
	while ($getfee = FetchRow($dbtype,$mfee)){
	print "<tr>\n";
	foreach ($getfee as $field )
	print "<td>&nbsp;$field&nbsp</td>\n";
	print "</tr>\n";
	}//end while
//total fee
$tfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
			"sum(fee_amount)", 
			"where owner_id = $owner_id and permit_type='$permit_type'
			 and permit_status='$stat'");
$totalfee=FetchRow($dbtype,$tfee);
include_once "includes/other_permit_penalty.php";
$totalfee[0]=$totalfee[0] + $otherpen + $otherint + $otherlate;
$totalfeenf = number_format($totalfee[0],2);
?>

<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>Grand Total:</td><td>&nbsp;<?php echo $totalfeenf; ?></td></tr>
</table>
</td></table>
<?php
	}
	if ($stat<>'New') {
		$ota=$totalfee[0];
		$totfee=0;
		require_once"includes/penalty.php";
		$totalpay=$grandamt;
	} else {
		$totalpay=$totalfee[0];
	}

$totpay=$totalfee[0];
$totalfee=$totalfee[0];
}
$getmax = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or",
			" count(or_no) + 1","");
$getor = FetchRow($dbtype,$getmax);
$or_no = $getor[0];


if ($com=='cash') {
	if ($permit_type=='Fishery') {
		$totalpay = $totass;
		$totalfee = $totass;
	}

	
if ($ispay==1 || $nopayment == '1') {
	
require_once "includes/form_pay.php";
}

} elseif ($com=='reassess') {

}
//payment history //
?>
<bR><table cellspacing=0 cellpadding=0 border=1 width=60% align=center>
<tr><td align=center valign=top class='header2'>
Payment History </td>  </tr>  </table> <br>
<?php
$getpay = SelectMultiTable($dbtype,$dbLink,"temppayment",
			"payid, payamt, pay_date, pay_type, 
			or_no,status", 
			"where owner_id = $owner_id and permit_type='$permit_type' 
			and permit_status='$stat' and pay_date like '$getyearnow%'");
?>
<table border=1 align=center>
<td>OR No </td><td>Paid Amount </td><td>Paid Date </td>
<td>Action </td>
<?php
$totalpayment_=0;
while ($get_info = FetchRow($dbtype,$getpay)){
print "<tr>\n";
//foreach ($get_info as $field )
print "<td>&nbsp;$get_info[4]&nbsp</td>\n";
$pdamt = number_format($get_info[1],2);
print "<td align=right>&nbsp;$pdamt&nbsp</td>\n";
print "<td>&nbsp;$get_info[2]&nbsp</td>\n";

$totalpayment_=$get_info[1]+$totalpayment_;
if ($get_info[5]<>0) {
print "<td><a class=subnavwhite href='index.php?part=4&class_type=Permits&itemID_=2212&owner_id=$owner_id&payid=$get_info[0]&cmd=delete&com=cash&permit_type=$tag&stat=$stat&busItem=$permit_type'>Delete</a></td>";

?>
<td><div align=center><a href="#" class=subnavwhite onclick='window.open("ebplsothrec.php?owner_id=<?php echo $owner_id; ?>&or_no=<?php echo $get_info[4]; ?>&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat; ?>");'>Print OR</a></div></td>
<?php


} else {

?>
<td><div align=center><a href="#" class=subnavwhite onclick='window.open("ebplsothrec.php?owner_id=<?php echo $owner_id; ?>&or_no=<?php echo $get_info[4]; ?>&permit_type=<?php echo $permit_type; ?>&stat=<?php echo $stat; ?>");'>Re-Print OR</a></div></td>
<?php
}
print "</tr>\n";
}//end while
$getpay = SelectMultiTable($dbtype,$dbLink,"temppayment","sum(payamt)", 
			"where owner_id = $owner_id and permit_type='$permit_type' 
			 and permit_status='$stat' and pay_date like '$getyearnow%'");
$totalpaid = FetchRow($dbtype,$getpay);
$totalpaidnf = number_format($totalpaid[0],2);
?>
<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>Total : </td>
<td align=right>&nbsp;<?php echo $totalpaidnf; ?> </td>
<?php
$hj = $totalpaid[0];
$amtchange = $hj - $totalpay;
if ($amtchange<0) {
	print "<td></td>\n";
} else {
	print "<td></td>\n";
}

$amtchange=abs($amtchange);
//$amtchangenf = number_format($amtchange,2);
print "<td align=right>&nbsp;$amtchangenf </td>\n";
print "</tr>";




print "</table>";
?>
<script language="Javascript">
function DeletePayment(x)
{
	var _FRM = document._FRM;
	delconf = confirm("Delete Payment?");
	if (delconf == true) {
		_FRM.delproc.value = "yes";
		_FRM.payid.value = x;
		_FRM.submit();
		return true;
	} else {
		alert("Transaction Cancelled.");
		_FRM.delproc.value = "no";
		return false;
	}
}
</script>
<input type=hidden name=delproc>
<input type=hidden name=payid value="<? echo $payid;?>">
		
<?php



require_once "includes/form_add_lastpermit.html";
//}





?>

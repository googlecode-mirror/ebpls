<?php
//check penalty


                                                                                                    
$getpen = mysql_query("select datediff(renewaldate,'$tdate'),rateofpenalty, renewaldate,
			indicator, rateofinterest
                         from ebpls_buss_penalty order by id desc limit 1") or die ("penalty".mysql_error());
$pen = mysql_fetch_row($getpen);


                                                                                                               
if ($pen[0]<0) {
                                                                                                               
$htag = '(Penalties)';
require "../$directory/includes/headerassess.php";
	if ($pen[3]==1) {
		$per = $pen[1];
		$per = $per;//."%";
	} else {
		$per = $pen[1];
	}



if ($gettag[0]=='') {
$ota = abs($ota+$totfee-$totalpaidtax);
$grandamt=$ota;
	if ($an==1) {
        $ota=0;
	$grandamt =0;
        }

}

if ($itemID_=='4212') {
	$ota=$ota1 + $totfee;
}




?>

<br><table border=0 align =center width=90%><br>
<tr>
<td width=100 align=right >Penalty Date:</td><td width=50><?php echo $pen[2]; ?></td>
<td align=right> Penalty (Property):</td><td ><?php echo $per; ?> </td>
<td align=right>Surcharge (Percentage):</td>
<td ><?php echo $pen[4]; ?> </td>
</tr>
</table>

<table border=0 align =center width=90% cols=4><br>
<tr>
<td>&nbsp;</td>
<td align=left width=180>Tax w/o Penalty/Surcharge:</td>
<td align=right><?php echo number_format($ota,2);?></td>
</tr>
<!--
<tr>
<td>&nbsp;</td>
<td align=left width=180>Total Tax Paid:</td>
<td align=right><?php// echo $totalpaidtax;?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align=left width=180>Balance:</td>
<td align=right><?php 
//$bal = $original - $totalpaidtax;
//echo $bal;
?></td>
</tr>

-->

<tr><td>&nbsp;</td>
<td align=left width=180>Penalty (Amount):</td>
<td align=right>
<?php 
//$grandamt=$gradmt-$totalpaidtax;
$grandamt = $ota;
$dd = $pen[1];
if ($pen[3]==1) {
$pan = ((($grandamt-$totfee)/4) + $totfee) * $pen[1];
} else {
eval("\$penamt=$grandamt$dd;");
$pan = $penamt;
}
//$penamt = number_format($grandamt*$pen[1],2);
/*
 $gid = mysql_query("select * from bus_grandamt
                                where business_id=$business_id and
                                owner_id=$owner_id") or die ("gid");
        $haveexist = mysql_num_rows($gid);
                if ($haveexist<>0) {
                        $mt = mysql_fetch_row($gid);
                        $penamt = $mt[6];
			$penamt = number_format($penamt,2);
              }*/

$penamt=number_format($pan,2);
echo $penamt;


?>

</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<?php
$totalpen = $grandamt+$pan; //($grandamt*$pen[1]);
//$totalpen=round($totalpen,2);
$ga=number_format($totalpen,2);

$d = date('m',strtotime($pen[2]));
$s = date('m');
$d = $s - $d;
$x = 0;
//surcharge
while ($x <> $d)
{
$ttax = $grandamt * $pen[4];
//$ttax = ((($grandamt-$totfee) /4) + $totfee) * $pen[4];
$x++;
}
$t = $ttax;
$ttax = number_format($t,2);
/*
 $gid = mysql_query("select * from bus_grandamt
                                where business_id=$business_id and
                                owner_id=$owner_id") or die ("gid");
        $haveexist = mysql_num_rows($gid);
                if ($haveexist<>0) {
                        $mt = mysql_fetch_row($gid);
                        $grandamt = $mt[3];
                        $totalpen = $mt[4];
                        $t = $mt[5];
			$ttax = number_format($t,2);
			$ga=number_format($totalpen,2);
                } 
*/

?>
<tr><td>&nbsp;</td><td width=100>Total Tax with Penalty:</td>
<td align=right><font color=red><?php echo $ga; ?></td></font>
      <td>&nbsp;</td></tr>

<tr><td>&nbsp;</td><td width=100><u>Surcharge Amount:</u></td>
<td align=right><font color=red><u><?php echo $ttax; ?></u></td></font>
      <td>&nbsp;</td></tr>



<tr><td>&nbsp;</td><td width=100>Total Tax:</td>
<td align=right><font color=red><?php $grandamt = $totalpen + $t; 
					echo   $tax = number_format($totalpen + $t,2); ?></td></font>
      <td>&nbsp;</td></tr>

<?php   
}
$origitax = $totalpen - $pan; 
$grdmt = $grandamt; 

if ($pan=='' || $t=='' || $totalpen=='') {
	$pan=0;
	$t=0;
	$totalpen=0;
}      
/*

   $gid = mysql_query("select * from bus_grandamt
                                where business_id=$business_id and
                                owner_id=$owner_id") or die ("gid");
        $haveexist = mysql_num_rows($gid);
                if ($haveexist<>0) {
                        $mt = mysql_fetch_row($gid);
                        $grandamt = $mt[3];
                        $totalpen = $mt[4];
                        $t = $mt[5];
                } else {
                        $insertamt = mysql_query("insert into bus_grandamt values
                                        ('',$owner_id, $business_id, $grandamt, $totalpen, $t,
					$pan, $origitax, 'penaltycode')")
                                        or die ("insertamt1".mysql_error());
                }

*/
?>

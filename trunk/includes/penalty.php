<?php
//check penalty
$YearToday = date('Y')."-";

$getpen = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_penalty",
			"datediff('$YearToday.$penda','$tdate'),rateofpenalty, renewaldate,
			indicator, rateofinterest, optsurcharge", 
			"order by id desc limit 1");
$pen = FetchRow($dbtype,$getpen);


if ($pen[0]<0) {

$htag = '(Penalties)';
//require "../$directory/includes/headerassess.php";
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

<table border=0 align =right width=90% cols=4><br>


<tr><td>&nbsp;</td>
<td align=left width=180>Surcharge (Amount):</td>
<td align=right>
<?php 
//$grandamt=$gradmt-$totalpaidtax;
$grandamt = $ota;
$dd = $pen[1];
if ($pen[3]==1) {
//$pan = ((($grandamt-$totfee)/4) + $totfee) * $pen[1];
$pan = $pen[1];
} else {
eval("\$penamt=$paym$dd;");
$pan = $penamt;
}

$penamt=number_format($pan,2);
echo $penamt;
$penamt1=round($pan,2);

?>

</td>
<td>&nbsp;</td>
</tr>
<tr>

<?php
$totalpen = $paym+$pan; //($grandamt*$pen[1]);
//$totalpen=round($totalpen,2);
$ga=number_format($totalpen,2);


//surcharge
$dedsi=strtolower($pen[5]);
if ($dedsi=='mon') {
	$d = date('m',strtotime($pen[2])); //setting date
	$s = date('m'); //curretn date
	$d = $s - $d;
} elseif ($dedsi=='qtr') {
	$d = date('m',strtotime($pen[2])); //setting date
        $s = date('m'); //curretn date

	$s = $s/3;

		if ($s<=1) { //1st qtr
			$d=0;
		} elseif ($s>1 and $s<=2) { //2nd qtr
			$d=1;
		} elseif ($s>2 and $s<=3) { //3rd qtr
			$d=2;
		} else {
			$d=3;
		}
} elseif ($dedsi=='sem') {
	$d = date('m',strtotime($pen[2])); //setting date
        $s = date('m'); //curretn date
                                                                                                 
        $s = $s/6;
                                                                                                 
                if ($s<=1) { //1st part
                        $d=0;
                } else { //2nd part
                        $d=1;
		}
} elseif ($dedsi=='ann') {
	$d = date('m',strtotime($pen[2])); //setting date
        $s = date('m'); //curretn date

	        $s = $s/12;
                                                                                                 
                if ($s<=1) { //1st part
                        $d=0;
                } else { //2nd part
                        $d=1;
                }


}


$x = 0;
$origpaym = $paym;
//surcharge
while ($x <> $d)
{
$ttax = $paym * $pen[4];

$paym=round($paym,2) + round($ttax,2);

//$ttax = ((($grandamt-$totfee) /4) + $totfee) * $pen[4];
$x++;
}
$ttax = $paym - $origpaym;
$t = $ttax;
$ttax = number_format($t,2);


?>
<tr><td>&nbsp;</td><td width=100>Total Tax with Surcharge:</td>
<td align=right><font color=red><?php echo $ga; ?></td></font>
      <td>&nbsp;</td></tr>

<tr><td>&nbsp;</td><td width=100><u>Interest:</u></td>
<td align=right><font color=red><u><?php $surcharge=$t; echo $ttax; ?></u></td></font>
      <td>&nbsp;</td></tr>

<?php
	if ($notpaid > 0 and $stat=='ReNew' and $paymode[$si+1]=='First') {
	//$grand=$grand+$t+$totalpen;

?>		

<tr><td>&nbsp;</td><td width=100>Back Taxes :</td>
<td align=right><font color=gray><?php $baktax = $notpaid;
			 echo number_format($notpaid,2); ?></td></font>
      <td>&nbsp;</td></tr>

<?php
	} else { $baktax=0;
	}
?>

<?php
	if ($notpaid > 0 and $stat=='Retire') {
	$grand=$grand+$t+$totalpen;
?>		
<tr><td>&nbsp;</td><td width=100>Back Taxes:</td>
<td align=right><font color=gray><?php $baktax = $notpaid;
			
			 echo number_format($notpaid,2); ?></td></font>
      <td>&nbsp;</td></tr>

<?php
	}
?>

<tr><td>&nbsp;</td><td width=100>Total Tax:</td>
<td align=right><font color=red><?php $grandamt = $totalpen + $t + $baktax; 
					echo   $tax = number_format($totalpen + $t + $baktax,2); 
					$amt2pay[$si+1] = $grandamt;?></td></font>
      <td>&nbsp;</td></tr>
      <input type=hidden name=t value=<?php echo $t; ?>>
      <input type=hidden name=pan value=<?php echo $pan; ?>>
      <input type=hidden name=grand value=<?php echo $grand; ?>>
      
</table>

<?php   
$tax1=round($totalpen,2);
}
$origitax = $totalpen - $pan; 
$grdmt = $grandamt; 

if ($pan=='' || $t=='' || $totalpen=='') {
	$pan=0;
	$t=0;
	$totalpen=0;
}      

?>

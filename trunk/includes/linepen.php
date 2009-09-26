<?php


//check penalty
$YearToday = date('Y')."-";

$getpen = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_penalty",
                        "datediff('$YearToday.$penda','$tdate'),rateofpenalty, renewaldate,
                        indicator, rateofinterest, optsurcharge",
                        "order by id desc limit 1");

//check penalty
                                                                                                               
//$getpen = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_penalty",
//		"datediff(renewaldate,'$tdate'),rateofpenalty, renewaldate,
//		 indicator, rateofinterest","order by id desc limit 1");
$pen = FetchRow($dbtype,$getpen);
if ($pen[0]<0) {

	if ($pen[3]==1) {
		$per = $pen[1];
		$per = $per;//."%";
	} else {
		$per = $pen[1];
}
$lineptax = $ttamt;
?>

<tr><td></td><td></td>
<td align=right width=180>Penalty (Amount):</td>
<td align=right>
<?php 
if ($pen[3]==1) {
$pan = $grandamt*$pen[1];
} else {
eval("\$penamt=$grandamt$pen[1];");
$pan = $penamt;
}
$penamt = number_format($grandamt*$pen[1],2);
echo $penamt;
?>

</td>
</tr>
<?php
$totalpen = $grandamt+($grandamt*$pen[1]);
$ga=number_format($totalpen,2);

$d = date('m',$pen[2]);
$s = date('m');
$d = $s - $d;
$x = 0;
while ($x <> $d)
{

$ttax = $grandamt * $pen[4];
$x++;
}
$t = $ttax;
$ttax = number_format($t,2);
?>
<tr><td></td><td></td><td align=right width=100>Total Tax with Penalty:</td>
<td align=right><font color=red><?php echo $ga; ?></td></font>
      </tr>

<tr><td></td><td></td><td align=right  width=100><u>Surcharge Amount:</u></td>
<td align=right><font color=red><u><?php echo $ttax; ?></u></td></font>
      </tr>

<tr><td></td><td></td><td align=right width=100>Total Tax Per Line With Penalty/Surcharge:</td>
<td align=right><font color=red><?php $ttamt = $totalpen + $t; 
					echo   $tax = number_format($totalpen + $t,2); ?></td></font>
      </tr>
<?php
     }
?>

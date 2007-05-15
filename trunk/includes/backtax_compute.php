<?php
//Module that computes Back Tax
$getnyeartoday = date('Y');
$getpaydate = @mysql_query("select * from ebpls_transaction_payment_or a, ebpls_transaction_payment_or_details b where b.permit_type = 'Business' and a.trans_id = '$owner_id' and payment_id = '$business_id' and a.or_no = b.or_no order by a.or_no desc limit 1");
$checkifnew = mysql_num_rows($getpaydate);
if ($checkifnew == 0) {
	$getstartdate = mysql_query("select * from ebpls_business_enterprise_permit where owner_id = '$owner_id' and business_id = '$business_id' order by business_permit_id asc limit 1");
	$getpaydatey = mysql_fetch_assoc($getstartdate);
	$lastpaydatey = substr($getpaydatey[application_date],0,4);
} else {
	$getpaydatey = @mysql_fetch_assoc($getpaydate);
	$lastpaydatey = substr($getpaydatey[ts],0,4);
}

$firstyear = $lastpaydatey;
$lastpaydateyr = $lastpaydatey;
$nbacktax = 0;
$nbacktaxlast = 0;
while ($lastpaydatey < $getnyeartoday) 
{
	$ncomp = $getnyeartoday - $lastpaydatey;
	$ngydiff = $getnyeartoday - $firstyear;
	if ($lastpaydateyr == $firstyear and $ncomp >=1) { //Start of Surcharge/Interest for Back Tax
		if (strtolower($pmode)=='quarterly') {
			$paybpart = 4;
		}
		if (strtolower($pmode)=='semi-annual') {
			$paybpart = 2;
		}
		if (strtolower($pmode)=='annual') {
			$paybpart = 1;
		}
		$getpays = mysql_query("select * from ebpls_transaction_payment_or_details where
		trans_id = '$owner_id' and payment_id='$business_id' and ts like '$lastpaydatey%'");
		$getpays1 = mysql_query("select * from ebpls_transaction_payment_or_details where
		trans_id = '$owner_id' and payment_id='$business_id' and ts like '$lastpaydatey%' and payment_part = '1'");
		$getnumpays1 = @mysql_num_rows($getpays1);
		$getpays2 = mysql_query("select * from ebpls_transaction_payment_or_details where
		trans_id = '$owner_id' and payment_id='$business_id' and ts like '$lastpaydatey%' and payment_part = '2'");
		$getnumpays2 = @mysql_num_rows($getpays2);
		$getpays3 = mysql_query("select * from ebpls_transaction_payment_or_details where
		trans_id = '$owner_id' and payment_id='$business_id' and ts like '$lastpaydatey%' and payment_part = '3'");
		$getnumpays3 = @mysql_num_rows($getpays3);
		$getpays4 = mysql_query("select * from ebpls_transaction_payment_or_details where
		trans_id = '$owner_id' and payment_id='$business_id' and ts like '$lastpaydatey%' and payment_part = '4'");
		$getnumpays4 = @mysql_num_rows($getpays4);
		$getnumpays = 0;
		if ($getnumpays1 > 0 ) {
			$getnumpays = 1;
			$npaymentparts[1] = 1;
		} else {
			$npaymentparts[1] = 0;
		}
		if ($getnumpays2 > 0 ) {
			$getnumpays = 2;
			$npaymentparts[2] = 1;
		} else {
			$npaymentparts[2] = 0;
		}
		if ($getnumpays3 > 0 ) {
			$getnumpays = 3;
			$npaymentparts[3] = 1;
		} else {
			$npaymentparts[3] = 0;
		}
		if ($getnumpays4 > 0 ) {
			$getnumpays = 4;
			$npaymentparts[4] = 1;
		} else {
			$npaymentparts[4] = 0;
		}
		$getnopay = $paybpart - $getnumpays;
		if ($getnopay > 0) {
			//$paypartnow = $paybpart - $getnumpays + 1;
			$nx = 1 + $getnumpays;
			while ($nx <= $paybpart) {
				//If Quarterly
				$ngettaxesfees = mysql_query("select * from bus_grandamt where owner_id = '$owner_id' and business_id = '$business_id' and ts = '$lastpaydatey' and paypart = '$nx'");
				$ngettaxesfees1 = mysql_fetch_assoc($ngettaxesfees);
				if ($npaymentparts[$nx] != '1' and $ngettaxesfees1[grandamt] > 0) {
					
					$ngettax = mysql_query("select * from bus_grandamt where owner_id = '$owner_id' and business_id = '$business_id' and ts = '$lastpaydatey' and paypart = '$nx'");
					while ($ngettaxes = mysql_fetch_assoc($ngettax)) {
						if ($ngettaxes[grandamt] > 0) {
							$checknumrow = @mysql_num_rows($ngettax);
							if ($checknumrow == 4) {
								$npayment_type = "qtr";
							} elseif ($checknumrow == 2) {
								$npayment_type = "sem";
							} elseif ($checknumrow == 1) {
								$npayment_type = "ann";
							}
							$getpended = @mysql_query("select * from ebpls_buss_penalty where status = 'A'");
							$getpended = @mysql_fetch_assoc($getpended);
							$getrenew = @mysql_query("select * from ebpls_buss_penalty1 order by id desc limit 1");
							$getrenew = @mysql_fetch_assoc($getrenew);
							$addend = "$firstyear-";
							if ($paybpart=='4') {
								if ($ngettaxes[paypart] == '1') {
									$getpendedrenewal = $getrenew['qtrdue1'];
								} elseif ($ngettaxes[paypart] == '2') {
									$getpendedrenewal = $getrenew['qtrdue2'];
								} elseif ($ngettaxes[paypart] == '3') {
									$getpendedrenewal = $getrenew['qtrdue3'];
								} elseif ($ngettaxes[paypart] == '4') {
									$getpendedrenewal = $getrenew['qtrdue4'];
								}
							}
							if ($paybpart=='2') {
								if ($ngettaxes[paypart] == '1') {
									$getpendedrenewal = $getrenew['semdue1'];
								} elseif ($ngettaxes[paypart] == '2') {
									$getpendedrenewal = $getrenew['semdue2'];
								}
							}
							if ($paybpart=='1') {
								$getpendedrenewal = $getpended['renewaldate'];
							}
							$renewaldate = "$addend$getpendedrenewal";
							$renewaldate = strtotime($renewaldate);
							$renewaldate = date('Y-m-d', $renewaldate);
					
							$itaxesfees = $ngettaxes['grandamt'];
							$itaxes = $ngettaxes['origtax'];
							if ($getpended['indicator'] == '1') {
								$buspen = $getpended['rateofpenalty'];
							} elseif ($getpended['indicator'] == '2') {
								$nratepen = $getpended['rateofpenalty'];
								if ($getpended['surtype'] == '1') {
									eval("\$buspen=$itaxes * $nratepen;");
								} elseif ($getpended['surtype'] == '2') {
									eval("\$buspen=$itaxesfees * $nratepen;");
								}
							}
							
							$nbacktaxlast = $nbacktaxlast + $buspen;
							$d = date('m',strtotime($renewaldate)); //setting date
							$s = date('m'); //current date
							if (strtolower($getpended['optsurcharge']) == "qtr") {
								if ($d >=1 and $d <=3) {
									$subtrahend = 1;
								} elseif ($d >=4 and $d <=6) {
									$subtrahend = 2;
								} elseif ($d >=7 and $d <=9) {
									$subtrahend = 3;
								} elseif ($d >=10 and $d <=12) {
									$subtrahend = 4;
								}
								if ($s >=1 and $s <=3) {
									$subtractor = 1 + ($ngydiff * 4);
								} elseif ($s >=4 and $s <=6) {
									$subtractor = 2 + ($ngydiff * 4);
								} elseif ($s >=7 and $s <=9) {
									$subtractor = 3 + ($ngydiff * 4);
								} elseif ($s >=10 and $s <=12) {
									$subtractor = 4 + ($ngydiff * 4);
								}
							}
							if (strtolower($getpended['optsurcharge']) == "mon") {
								$subtrahend = $d;
								$subtractor = $s+ ($ngydiff * 12);
							}
							if (strtolower($getpended['optsurcharge']) == "sem") {
							if ($d >=1 and $d <=6) {
								$subtrahend = 1;
							} elseif ($d >=7 and $d <=12) {
								$subtrahend = 2;
							}
							if ($s >=1 and $s <=6) {
								$subtractor = 1 + ($ngydiff * 2);
							} elseif ($s >=7 and $s <=12) {
								$subtractor = 2 + ($ngydiff * 2);
							}
						}
						if (strtolower($getpended['optsurcharge']) == "ann") {
							$subtrahend = 1;
							$subtractor = 1 + ($ngydiff);
						}
						$d = ($subtractor - $subtrahend) + 1;
						if ($d > 36) {
							$d = 36;
						}
						$x = 1;
						$counter = 1;
						$intrate = "1$getpended[rateofinterest]";
						if ($getpended['inttype'] == '1') {
							$interestrate = $getpended[rateofinterest] * $d;
						} elseif ($getpended['inttype'] == '2') {
							while ($x <= $d) {
								$counter = $counter * $intrate;
								$x++;
							}
						$interestrate = $counter - 1;
						}
						if ($getpended['feeonly'] == '1') {
							$busint = $interestrate * ($itaxes);
						} elseif ($getpended['feeonly'] == '2') {
							$busint = $interestrate * ($itaxesfees);
						} elseif ($getpended['feeonly'] == '3') {
							$busint = $interestrate * ($itaxesfees + $buspen);
						}
						
						$nbacktaxlast = $nbacktaxlast + $busint;
						///////
						}
					}
				}
				$buspen = round($buspen,2);
				$busint = round($busint,2);
				$updatebusgran = mysql_query("update bus_grandamt set totpenamt = '$buspen', si = '$busint' where owner_id = '$owner_id' and business_id = '$business_id' and ts = '$lastpaydatey' and paypart = '$nx'");
				$nx = $nx + 1;
			}
		}
		$lastpaydateyr = $lastpaydateyr + 1;
	}
	//End of Surcharge/Interest for Back Tax
	$backtaks=SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
	"sum(grandamt), sum(totpenamt), sum(si), sum(penamt)",
	"where owner_id=$owner_id and business_id='$business_id'  and ts = '$lastpaydatey'  and active = 0
	order by gid desc");
	$bt = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details a,
	ebpls_transaction_payment_or b","b.total_amount_paid,a.ts",
	"where a.trans_id=$owner_id and a.payment_id=$business_id 
	and a.trans_id = b.trans_id and a.or_no=b.or_no
	and a.or_entry_type<>'CHECK' and ts like '$lastpaydatey%'");
	while ($getche = FetchRow($dbtype,$bt))
	{
		$getch = $getche[0]+$getch;
	}

	$totbak = $getch;
	$getch=0;
	$bt = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details a, 
	ebpls_transaction_payment_check b","b.check_amount,a.ts",
	"where a.trans_id=$owner_id and a.payment_id=$business_id 
	and a.or_entry_type='CHECK' and b.check_status='CLEARED'
	and a.or_no=b.or_no and ts like '$lastpaydatey%'");
	while ($getche = FetchRow($dbtype,$bt))
	{
		$getch = $getche[0]+$getch;
	}
	$totbak = $totbak + $getch;
	if ($istat=='Retire') {
		$backtaks=SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
		"sum(grandamt), sum(totpenamt), sum(si), sum(penamt)",
		"where owner_id=$owner_id and business_id='$business_id'  and ts = '$lastpaydatey' 
		and active = 0 order by gid desc");
	} else {
		$backtaks=SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
		"sum(grandamt), sum(totpenamt), sum(si), sum(penamt)",
		"where owner_id=$owner_id and business_id='$business_id'  and ts = '$lastpaydatey'  and active = 0
		order by gid desc");
	}
	$backtaks1=mysql_fetch_row($backtaks);
	if ($backtaks1[0]=='') {
		$notpaid = 0;
	} else {
		$baktax1=$backtaks1[0];
		$baktax2=$backtaks1[1];
		$baktax3=$backtaks1[2];
		$baktax4=$backtaks1[3];
		$totbaktax=$baktax1+$baktax2+$baktax3+$baktax4;
		$notpaid = $totbaktax-$totbak;
		if ($notpaid < 0) {
			$notpaid = 0;
		}
	}
	//$notpaid = $notpaid + $nbacktaxlast;
	$checkpref = @mysql_query("select * from ebpls_buss_preference");
	$checkpref1 = @mysql_fetch_assoc($checkpref);
	$checksbak = $checkpref1['sbacktaxes'];
	if ($checksbak == 1) {
		if ($divfee > $notpaid) {
			$notpaid = 0;
		}
	}
	$notpaid = $notpaid;
	if ($notpaid >= 1) {
	?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align=left><b><font color="black">Backtax for <? echo $lastpaydatey;?></font></b></td>
			<td align=right><b><?php echo round($notpaid,2);?></b></td>
		</tr>
	</table>
	<?php
	}
	$amt2pay = $amt2pay + $notpaid;
	if ($notpaid >= 1) {
		$nbacktax = $nbacktax + $notpaid;
	} else {
		$nbacktax = 0;
	}
	$lastpaydatey++;
}

?>

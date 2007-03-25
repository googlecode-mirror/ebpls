<?php
//if ($stat != "New") {

//check if have new line or record paid
$newtax=0;
$newfee=0;
if ($stat == 'ReNew' and $monthcounter == $qtrcnt) {
$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='New' and active=1");
$havnew = mysql_num_rows($checknew);

	if ($havnew>0) { //may bago na line
		$nat=0;
		while ($newnew = mysql_fetch_assoc($checknew)) {
			$nature[$nat] = $newnew[bus_code];
			$nat++;
		
		}
	}	
$checknew12 = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='$stat' and active=1 and recpaid =1");
$havnew12 = mysql_num_rows($checknew12);

	if ($havnew12>0) { //may bago na line
		$wagpenalty = 1;
	} else {
		$wagpenalty = 0;
	}

	//check kung bayad na luma
	
	$checkluma = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='$stat' and
						recpaid='1' and active=1");
$bayadluma = mysql_num_rows($checkluma);
$checkluma1 = mysql_fetch_assoc($checkluma);
	if ($bayadluma>0) { //bayad na
		
		$wagpenalty = 1;
	} else {
		$wagpenalty = 0;
	}
	
		//pano pag may bagong line na hindi pa bayad luma na late na.. bwiset na tax payer na yan
		if ($havnew>0) {
			//total lahat ng tax nya
			$bis=0;
			
			while ($bis<$nat) {
			$biset = mysql_query("select * from tempassess 
						where owner_id='$owner_id' and business_id='$business_id' and
						natureid='$nature[$bis]' and active=1 and date_create like '$yearnow%'");
				while ($amtbis =mysql_fetch_assoc($biset)) {
					$getfeeid = $amtbis[tfoid];
					$gettotfee = mysql_query("select * from ebpls_buss_tfo where
												tfoid='$getfeeid'");
					$gid = mysql_fetch_assoc($gettotfee);
					if ($gid[taxfeetype]==1) {
						$newtax = $newtax + $amtbis[compval];
					} else {
						$newfee = $newfee + $amtbis[compval];
					}
				}
			
		
			$bis++;			
			}
		}
			
						
		if (strtolower($pmode) == "quarterly") {
			$LBnewtax = $newtax / 4;
			if ($staxfee == '1') {
				$LBnewfee = $newfee;
			} else {
				$LBnewfee = $newfee / 4;
			}
		} elseif (strtolower($pmode) == "semi-annual") {
			$LBnewtax = $newtax / 2;
			if ($staxfee == '1') {
				$LBnewfee = $newfee;
			} else {
				$LBnewfee = $newfee / 2;
			}
		} elseif (strtolower($pmode) == "annual") {
			$LBnewtax = $newtax;
			$LBnewfee = $newfee;
		}
	//echo "<BR>VooDoo".$newtax."===".$newfee;
} else {
	$wagpenalty = 0;
	$LBnewtax = 0;
	$LBnewfee = 0;
}
$anongmonthito = date('m', $checkluma1[date_create]);
	if (strtolower($pmode) == "quarterly") {
		if ($anongmonthito >= 1 and $anongmonthito <= 3) {
			$anongquartito = 1;
		} elseif  ($anongmonthito >= 4 and $anongmonthito <=6) {
			$anongquartito = 2;
		} elseif  ($anongmonthito >= 7 and $anongmonthito <= 9) {
			$anongquartito = 3;
		} elseif  ($anongmonthito >= 10 and $anongmonthito <= 12) {
			$anongquartito = 4;
		}
		$sdcounter = $qtrcnt;
	} elseif (strtolower($pmode) == "semi-annual") {
		if ($anongmonthito >= 1 and $anongmonthito <= 6) {
			$anongquartito = 1;
		} elseif  ($anongmonthito >= 7 and $anongmonthito <=12) {
			$anongquartito = 2;
		}
		$sdcounter = $semcnt;
	} elseif (strtolower($pmode) == "annual") {
		if ($anongmonthito >= 1 and $anongmonthito <= 12) {
			$anongquartito = 1;
		}
		$sdcounter = 1;
	}
	if ($sdcounter > $anongmonthito) {
		$wagpenalty = 0;
		$LBnewtax = 0;
	$LBnewfee = 0;
	}
	
/*===========================*/	
$yeartoday = date('Y');
$addend = "$yeartoday-";
$getpended = @mysql_query("select * from ebpls_buss_penalty where status = 'A'");
$getpended = @mysql_fetch_assoc($getpended);
$getrenew = @mysql_query("select * from ebpls_buss_penalty1 order by id desc limit 1");
$getrenew = @mysql_fetch_assoc($getrenew);

if (strtolower($pmode)=='quarterly') {
	if ($qtrcnt == '1') {
		$getpendedrenewal = $getrenew['qtrdue1'];
	} elseif ($qtrcnt == '2') {
		$getpendedrenewal = $getrenew['qtrdue2'];
	} elseif ($qtrcnt == '3') {
		$getpendedrenewal = $getrenew['qtrdue3'];
	} elseif ($qtrcnt == '4') {
		$getpendedrenewal = $getrenew['qtrdue4'];
	}
}
if (strtolower($pmode)=='semi-annual') {
	if ($semcnt == '1') {
		$getpendedrenewal = $getrenew['semdue1'];
	} elseif ($semcnt == '2') {
		$getpendedrenewal = $getrenew['semdue2'];
	}
}
if (strtolower($pmode)=='annual') {
	$getpendedrenewal = $getpended['renewaldate'];
}
$renewaldate = "$addend$getpendedrenewal";
$renewaldate = strtotime($renewaldate);
$renewaldate = date('Y-m-d', $renewaldate);
$nStartDate = date('Y-m-d', $startdate);

if ($wagpenalty == 0) {

	if ($permit_type == "Business" and $amt2pay != '0') {
		
		$buspen = 0;
		$busint = 0;
		$olddivtax = $divtax;
		$olddivfee = $divfee;
		if ($havnew > 0 and $monthcounter==$qtrcnt) {
			$divtax = $divtax - $LBnewtax;
		}
		
		if ($predcomp == '1' and $stat == "New") {
			if (strtolower($pmode) == "quarterly") {
				$nTotalTax = $divtax * 4;
			} elseif (strtolower($pmode) == "semi-annual") {
				$nTotalTax = $divtax * 2;
			} elseif (strtolower($pmode) == "annual") {
				$nTotalTax = $divtax;
			}
		} else {
			$nTotalTax = $divtax;
		}
		
		if ($havnew > 0) {
			$nTotalFee = $amt2pay - ($nTotalTax + $LBnewtax);
			$nTotalFee = $nTotalFee - $LBnewfee;
		} else {
			
			$nTotalFee = $amt2pay - $nTotalTax;
		}
		//echo "$nTotalTax + $nTotalFee";
		
		$datetoday = date('Y-m-d');
		$getrec = @mysql_query("select * from ebpls_business_enterprise where owner_id = '$owner_id' and transaction = '$stat' and active = '1'");
		$getrec = @mysql_fetch_assoc($getrec);
		$getpermit = @mysql_query("select * from ebpls_business_enterprise_permit where owner_id = '$owner_id' and transaction = '$stat' and active = '1' and business_id = '$getrec[business_id]'");
		$getpermit = @mysql_fetch_assoc($getpermit);
		$monthnow = date('m');
		$monthnow = $monthnow - 0;
		
		$getpaydate = @mysql_query("select * from ebpls_transaction_payment_or a, ebpls_transaction_payment_or_details b where b.permit_type = '$permit_type' and a.trans_id = '$owner_id' and b.payment_id = '$business_id' and a.or_no = b.or_no order by a.or_no desc limit 1");
		$getpaydate = @mysql_fetch_assoc($getpaydate);
		$lastpaydate = substr($getpaydate[ts],0,4);
		$lastpaydateyear = $lastpaydate;
		$yeardiff = 0;
		if ($datetoday > $renewaldate) {
			if ($getpended['indicator'] == '1') {
				$buspen = $getpended['rateofpenalty'];
			} elseif ($getpended['indicator'] == '2') {
				$nratepen = $getpended['rateofpenalty'];
				if ($getpended['surtype'] == '1') {
					eval("\$buspen=$nTotalTax * $nratepen;");
				} elseif ($getpended['surtype'] == '2') {
					eval("\$buspen=($nTotalTax + $nTotalFee) * $nratepen;");
				}
			}
			
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
					$subtractor = $s + ($ngydiff * 12);
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
				$busint = $interestrate * ($nTotalTax);
			} elseif ($getpended['feeonly'] == '2') {
				$busint = $interestrate * ($nTotalTax + $nTotalFee);
			} elseif ($getpended['feeonly'] == '3') {
				$busint = $interestrate * (($nTotalTax + $nTotalFee) + $buspen);
			}
			$buspen = round($buspen,2);
			$busint = round($busint,2);
			$amt2pay = $amt2pay + $buspen + $busint;
			$nSurchargeAmount = $buspen;
			$nInterestAmount = $busint;
			if ($buspen > 0 || $busint > 0) {
			?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align=left><b>Surcharge</b></td>
					<td align=right><b><?php echo number_format($buspen,2);?></b></td>
				</tr>
				<tr>
					<td align=left><b>Interest</b></td>
					<td align=right><b><?php echo number_format($busint,2);?></b></td>
				</tr>
			</table>
			<?php
			}
		}
	
	} 
} else {
		
		$LBnewtax=0;
		$nSurchargeAmount = 0;
		$nInterestAmount = 0;
}
$newtax=0;
$newfee=0;
// $divtax = $olddivtax;
// $divfee = $olddivfee;
if ($buspen == "" or $buspen == 0) {
	$nSurchargeAmount = 0;
}
if ($busint == "" or $busint == 0) {
        $nInterestAmount = 0;
}
$divtax = $divtax + $LBnewtax;
//echo "$divfee $divtax $getsumnun1 VooDoo";
?>

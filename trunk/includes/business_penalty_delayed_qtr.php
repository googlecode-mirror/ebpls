<?php
//if ($stat != "New") {
	if (strtolower($pmode)=='quarterly') {
		if ($robert == '1') {
			$getpendedrenewal = $getrenew['qtrdue1'];
		} elseif ($robert == '2') {
			$getpendedrenewal = $getrenew['qtrdue2'];
		} elseif ($robert == '3') {
			$getpendedrenewal = $getrenew['qtrdue3'];
		} elseif ($robert == '4') {
			$getpendedrenewal = $getrenew['qtrdue4'];
		}
	}
	if (strtolower($pmode)=='semi-annual') {
		if ($robert == '1') {
			$getpendedrenewal = $getrenew['semdue1'];
		} elseif ($robert == '2') {
			$getpendedrenewal = $getrenew['semdue2'];
		}
	}
	if (strtolower($pmode)=='annual') {
		$getpendedrenewal = $getpended['renewaldate'];
	}
	if ($permit_type == "Business" and $grandamt != '0') {
		$getexempt = @mysql_query("select * from ebpls_business_enterprise where owner_id = '$owner_id' and retire = '0' and business_id = '$business_id'");
		$getexempt1 = @mysql_fetch_assoc($getexempt);
		$getbusexempt = @mysql_query("select * from ebpls_business_category where business_category_code = '$getexempt1[business_category_code]'");
		$getbusexempt1 = @mysql_fetch_assoc($getbusexempt);
		$buspen = 0;
		$busint = 0;
		$nTotalTax = ($grandamt - (($grandamt * $getbusexempt1[tax_exemption]) / 100));
		$nTotalTax = round($nTotalTax,2);
		$nTotalFee = $totfee;
		$yeartoday = date('Y');
		$addend = "$yearnow1-";
		$datetoday = date('Y-m-d');
		$getrec = @mysql_query("select * from ebpls_business_enterprise where owner_id = '$owner_id' and transaction = '$stat' and active = '1'");
		$getrec = @mysql_fetch_assoc($getrec);
		$getpermit = @mysql_query("select * from ebpls_business_enterprise_permit where owner_id = '$owner_id' and transaction = '$stat' and active = '1' and business_id = '$getrec[business_id]'");
		$getpermit = @mysql_fetch_assoc($getpermit);
		$getpended = @mysql_query("select * from ebpls_buss_penalty where status = 'A'");
		$getpended = @mysql_fetch_assoc($getpended);
		$getrenew = @mysql_query("select * from ebpls_buss_penalty1 order by id desc limit 1");
		$getrenew = @mysql_fetch_assoc($getrenew);
		$monthnow = date('m');
		$monthnow = $monthnow - 0;
		$getpendedrenewal = $getpended['renewaldate'];
		$renewaldate = "$addend$getpendedrenewal";
		$renewaldate = strtotime($renewaldate);
		$renewaldate = date('Y-m-d', $renewaldate);
		$getpaydate = @mysql_query("select * from ebpls_transaction_payment_or a, ebpls_transaction_payment_or_details b where b.permit_type = 'Business' and a.trans_id = '$owner_id' and a.or_no = b.or_no order by a.or_no desc limit 1");
		$getpaydate = @mysql_fetch_assoc($getpaydate);
		$lastpaydate = substr($getpaydate[ts],0,4);
		$lastpaydateyear = $lastpaydate;
		$nsyeardiff = 0;
		$ngydiff = $yeartoday - $yearnow1;
		if ($datetoday > $renewaldate) {
			if ($getpended['indicator'] == '1') {
				$buspen = $getpended['rateofpenalty'];
			} elseif ($getpended['indicator'] == '2') {
				$nratepen = $getpended['rateofpenalty'];
				if ($getpended['surtype'] == '1') {
					eval("\$buspen=($nTotalTax) * $nratepen;");
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
				$busint = $interestrate * ($nTotalTax + $nTotalFee + $buspen);
			}
			$updatebus = UpdateQuery($dbtype,$dbLink,"bus_grandamt","active = 0","owner_id = '$owner_id' and business_id='$business_id' and ts != '$yearnow1'");
			$ramt2 = round($ramt2, 2);
			$nTaxnFee = round($nTotalTax + $nTotalFee, 2);
			$nTotalTax = round($nTotalTax, 2);
			$buspen = round($buspen, 2);
			$busint = round($busint, 2);
			$nbacktax = round($nbacktax, 2);
			$divtax = round($divtax, 2);
			$totalexptax = round($totalexptax, 2);
			$totexemption = ($grandamt * $getbusexempt1[tax_exemption]) / 100;
			$totexemption = round($totexemption, 2);
			$busvalues = "'', '$owner_id', '$business_id', '$nTaxnFee', '$buspen', '$busint', '0', '$nTotalTax', '', '1', '$stat', '$totexemption', '$yearnow1', '$pmode', '$robert'";
			$insertbus = InsertQuery($dbtype,$dbLink,"bus_grandamt","",$busvalues);
		}
	}
//}
?>
<?php
//Module that saves information to table bus_grandamt
//if ($itemID_ == "4212") {
	$yearfornow = date('Y');
	if (strtolower($pmode)=='quarterly') {
		$paympart = $qtrcnt;
	}
	if (strtolower($pmode)=='semi-annual') {
		$paympart = $semcnt;
	}
	if (strtolower($pmode)=='annual') {
		$paympart = '1';
	}
	if ($ramt2 == 0) {
		$getgh = SelectDataWhere($dbtype,$dbLink,"bus_grandamt", "where owner_id = '$owner_id' and business_id = '$business_id' and  transaction='$stat' and ts = '$yearfornow' and paymode = '$pmode' and paypart = '$paympart'");
		$getghg=FetchRow($dbtype,$getgh);
		$nghigh = $getghg[3];
	} else {
		$nghigh = 0;
	}
	$updatebus = UpdateQuery($dbtype,$dbLink,"bus_grandamt","active = 0","owner_id = '$owner_id' and business_id='$business_id' and ts != '$yearfornow'");
	if ($nghigh <= 0) 
	{
		
		$ramt2 = round($ramt2, 2);
		$buspen = round($buspen, 2);
		$busint = round($busint, 2);
		$nbacktax = round($nbacktax, 2);
		$divtax = round($divtax, 2);
		$totalexptax = round($totalexptax, 2);
		//if ($) {
		//	$nbacktax = 0;
		//}
		if ($paympart != '1') {
			$nbacktax = 0;
			$totalexptax = 0;
		}
		if ($stat == 'New') {
			$checkfornew = mysql_query("select * from bus_grandamt where owner_id = '$owner_id' and business_id = '$business_id' and transaction = 'New' and ts != '$yearfornow' and paymode = '$pmode' and paypart = '$paympart'");
			$checkfornew1 = mysql_num_rows($checkfornew);
		} else {
			$checkfornew1 = 0;
		}
		if ($checkfornew1 == 0) {
			$deletebus=DeleteQuery($dbtype,$dbLink,"bus_grandamt", "owner_id = '$owner_id' and business_id = '$business_id' and  transaction='$stat' and ts = '$yearfornow' and paymode != '$pmode'");
			$deletebus=DeleteQuery($dbtype,$dbLink,"bus_grandamt", "owner_id = '$owner_id' and business_id = '$business_id' and  transaction='$stat' and ts = '$yearfornow' and paymode = '$pmode' and paypart = '$paympart'");
			if ($payst == "TAX WAIVED") {
				$busvalues = "'', '$owner_id', '$business_id', '0', '0', '0', '0', '0', '', '1', '$stat', '0', '$yearfornow', '$pmode', '$paympart'";
			} else {
				$foryeard = substr($yearfornow, 0, 4);
				$getpens = mysql_query("select sum(penalty), sum(surcharge) from comparative_statement where owner_id = '$owner_id' and business_id = '$business_id' and payment_number = '$paympart' and for_year = '$foryeard'");
				$getpens1 = @mysql_fetch_row($getpens);
				$setdef1 = 0;
				$setdef2 = 0;
				if ($nSurchargeAmount == "" || $nSurchargeAmount == 0) {
					if ($paympart == $haveaddpay) {
						$nSurchargeAmount = $getpens1[0];
					} else {
						$nSurchargeAmount = 0;
                                        }
					$setdef1 = 1;
				}
				if ($nInterestAmount == "" || $nInterestAmount == 0) {
					if ($paympart == $haveaddpay) {
                                        	$nInterestAmount = $getpens1[1];
					} else {
						$nInterestAmount = 0;
					}
					$setdef2 = 1;
                                }
								$NgTotalTaxFee = $NgTotalTaxFee - $nbAwas;
				$busvalues = "'', '$owner_id', '$business_id', '$NgTotalTaxFee', '$nSurchargeAmount', '$nInterestAmount', '$nbacktax', '$divtax', '', '1', '$stat', '$totalexptax', '$yearfornow', '$pmode', '$paympart'";
			}
			if ($nbong == '2' and $stat == 'New') {
				if (strtolower($pmode) == "quarterly") {
					$nBusTax = round($divtax * 4,2);
					$nBusFee = $amt2pay - (($divtax * 4) + $nbacktax + $nSurchargeAmount + $nInterestAmount);
				} elseif (strtolower($pmode) == "semi-annual") {
					$nBusTax = round($divtax * 2, 2);
					$nBusFee = $amt2pay - (($divtax * 2) + $nbacktax + $nSurchargeAmount + $nInterestAmount);
				} elseif (strtolower($pmode) == "annual") {
					$nBusTax = round($divtax,2);
					$nBusFee = $amt2pay - ($divtax + $nbacktax + $nSurchargeAmount + $nInterestAmount);
				}
				$nhbusgrand = ($amt2pay - ($nbacktax + $nSurchargeAmount + $nInterestAmount)) - $nbAwas;
				$busvalues = "'', '$owner_id', '$business_id', '$nhbusgrand', '$nSurchargeAmount', '$nInterestAmount', '$nbacktax', '$totaltax', '', '1', '$stat', '$totalexptax', '$yearfornow', '$pmode', '$paympart'";
				$insertbus = InsertQuery($dbtype,$dbLink,"bus_grandamt","",$busvalues);
			} else {
				$insertbus = InsertQuery($dbtype,$dbLink,"bus_grandamt","",$busvalues);
			}
		}
	}
//}
if ($setdef1 == 1) {
	$nSurchargeAmount = 0;
}
if ($setdef2 == 1) {
        $nInterestAmount = 0;
}
if ($nbong == "1") { //may additional line
	if (strtolower($pmode) =="quarterly") {
		$gtcount = $qtrcnt;
	} elseif (strtolower($pmode) =="semi-annual") {
		$gtcount = $semcnt;
	} elseif (strtolower($pmode) =="annual") {
		$gtcount = 1;
	}
	if ($scounter == $gtcount) {
		$paidtaxes = $paidtaxes;
	} else {
		$paidtaxes = 0;
	}
	if (is_array($amt2pay)) {
		$amt2pay = 0;
	}
	if ($predcomp != 1) {
		$nBusTax = $divtax - $paidtaxes;
		$nBusFee = $amt2pay - ($divtax + $nbacktax + $nSurchargeAmount + $nInterestAmount) + $paidtaxes;
	} elseif ($predcomp == "1" and $stat == 'New') { //predcomp activated
		if (strtolower($pmode) == "quarterly") {
			$nBusTax = $divtax * 4;
			$nBusFee = $amt2pay - (($divtax * 4) + $nbacktax + $nSurchargeAmount + $nInterestAmount);
		} elseif (strtolower($pmode) == "semi-annual") {
			$nBusTax = $divtax * 2;
			$nBusFee = $amt2pay - (($divtax * 2) + $nbacktax + $nSurchargeAmount + $nInterestAmount);
		} elseif (strtolower($pmode) == "annual") {
			$nBusTax = $divtax;
			$nBusFee = $amt2pay - (($divtax) + $nbacktax + $nSurchargeAmount + $nInterestAmount);
		}
	}
} elseif ($nbong == "3") { //may additional line na predcomp activated pa
	$nBusTax = $isbad;
	$nBusFee = 0;
	$nbacktax = 0;
} else { //normal

	$nBusTax = $divtax;
	$nBusFee = $amt2pay - ($divtax + $nbacktax + $nSurchargeAmount + $nInterestAmount);
}
//echo $nBusFee."VooDoo";
?>

<?php
$iCurrentYear = date('Y');
$iCurrentMonth = date('m');
$getpreference = @mysql_query("select * from ebpls_buss_preference");
$getPreference = @mysql_fetch_assoc($getpreference);
$AssessMode = $getPreference['sassess'];
$AssessType = $getPreference['staxesfees'];
$PredComp = $getPreference['predcomp'];

if ($AssessMode == '1') { //Per Line Setting
	$getbusdet = @mysql_query("select * from ebpls_owner a, ebpls_business_enterprise b where a.owner_id = '$owner_id' and a.owner_id = b.owner_id and b.business_id = '$business_id' and b.retire = '0'");
	$getBusdet = @mysql_fetch_assoc($getbusdet);
	$getbusinesscategory = @mysql_query("select * from ebpls_business_category where business_category_code = '$getBusdet[business_category_code]'");
	$GetBusinesscategory = @mysql_fetch_assoc($getbusinesscategory);
	if (strtolower($getBusdet['business_payment_mode']) == "quarterly") {
		$PaymentParts = 4;
		if ($iCurrentMonth > 0 and $iCurrentMonth < 4) {
			$iPaymentpart = 1;
		} elseif ($iCurrentMonth > 3 and $iCurrentMonth < 7) {
			$iPaymentpart = 2;
		} elseif ($iCurrentMonth > 6 and $iCurrentMonth < 10) {
			$iPaymentpart = 3;
		} elseif ($iCurrentMonth > 9 and $iCurrentMonth < 13) {
			$iPaymentpart = 4;
		}
	} elseif (strtolower($getBusdet['business_payment_mode']) == "semi-annual") {
		$PaymentParts = 2;
		if ($iCurrentMonth > 0 and $iCurrentMonth < 7) {
			$iPaymentpart = 1;
		} elseif ($iCurrentMonth > 6 and $iCurrentMonth < 13) {
			$iPaymentpart = 2;
		}
	} elseif (strtolower($getBusdet['business_payment_mode']) == "annual") {
		$PaymentParts = 1;
		$iPaymentpart = 1;
	}
	if ($AssessType == '1') { //Separate Computation of Taxes and Fees
		$getnaturedet = @mysql_query("select * from tempbusnature where owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
		while ($GetNaturedet = @mysql_fetch_assoc($getnaturedet)) {
			$getassessdet = @mysql_query("select * from tempassess where natureid = '$GetNaturedet[bus_code]' and owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
			while ($GetAssessdet = @mysql_fetch_assoc($getassessdet)) {
				$gettaxfeeType = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
				$GetTaxFeeType = @mysql_fetch_assoc($gettaxfeeType);
				if ($GetTaxFeeType['taxfeetype'] == '1') {
					$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%' and payment_part = '$pay_mode'");
				} else {
					$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%'");
				}
				$Getpaymentdet = @mysql_num_rows($getpaymentdet);
				if ($Getpaymentdet == '0') {
					$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetTaxFeeType[tfoid]'");
					$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
					if ($GetTaxFeetype['taxfeetype'] == '1') {
						$CompValAmount = ($GetAssessdet['compval'] / $PaymentParts)  - ((($GetAssessdet['compval']  / $PaymentParts)* $GetBusinesscategory['tax_exemption']) / 100);
						$CompValAmount = $CompValAmount - $nbbawas;
					} else {
						$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$GetAssessdet[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
						$CheckIf0 = @mysql_num_rows($checkif0);
						if ($CheckIf0 > 0) {
							$CompValAmount = 0;
						} else {
							$CompValAmount = $GetAssessdet['compval'];
						}
					}
					$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '$GetNaturedet[bus_code]', '$GetAssessdet[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
				}
			}
		}
	} else { //Not Separate Computation of Taxes and Fees
		$getnaturedet = @mysql_query("select * from tempbusnature where owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
		while ($GetNaturedet = @mysql_fetch_assoc($getnaturedet)) {
			$getassessdet = @mysql_query("select * from tempassess where natureid = '$GetNaturedet[bus_code]' and owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
			while ($GetAssessdet = @mysql_fetch_assoc($getassessdet)) {
				$gettaxfeeType = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
				$GetTaxFeeType = @mysql_fetch_assoc($gettaxfeeType);
				if ($GetTaxFeeType['taxfeetype'] == '1') {
					$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%' and payment_part = '$pay_mode'");
				} else {
					$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%'");
				}
				$Getpaymentdet = @mysql_num_rows($getpaymentdet);
				if ($Getpaymentdet == '0') {
					$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
					$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
					$CompValAmount = $GetAssessdet['compval'] / $PaymentParts  - ((($GetAssessdet['compval']  / $PaymentParts)* $GetBusinesscategory['tax_exemption']) / 100);
					$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$GetTaxFeeType[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
					$CheckIf0 = @mysql_num_rows($checkif0);
					if ($CheckIf0 > 0) {
						$CompValAmount = 0;
					} else {
						$CompValAmount = $CompValAmount;
					}
					$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '$GetNaturedet[bus_code]', '$GetAssessdet[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
				}
			}
		}
	}
} else { //Per Estab Setting
	$getbusdet = @mysql_query("select * from ebpls_owner a, ebpls_business_enterprise b where a.owner_id = '$owner_id' and a.owner_id = b.owner_id and b.business_id = '$business_id' and b.retire = '0'");
	$getBusdet = @mysql_fetch_assoc($getbusdet);
	$getbusinesscategory = @mysql_query("select * from ebpls_business_category where business_category_code = '$getBusdet[business_category_code]'");
	$GetBusinesscategory = @mysql_fetch_assoc($getbusinesscategory);
	if (strtolower($getBusdet['business_payment_mode']) == "quarterly") {
		$PaymentParts = 4;
		if ($iCurrentMonth > 0 and $iCurrentMonth < 4) {
			$iPaymentpart = 1;
		} elseif ($iCurrentMonth > 3 and $iCurrentMonth < 7) {
			$iPaymentpart = 2;
		} elseif ($iCurrentMonth > 6 and $iCurrentMonth < 10) {
			$iPaymentpart = 3;
		} elseif ($iCurrentMonth > 9 and $iCurrentMonth < 13) {
			$iPaymentpart = 4;
		}
	} elseif (strtolower($getBusdet['business_payment_mode']) == "semi-annual") {
		$PaymentParts = 2;
		if ($iCurrentMonth > 0 and $iCurrentMonth < 7) {
			$iPaymentpart = 1;
		} elseif ($iCurrentMonth > 6 and $iCurrentMonth < 13) {
			$iPaymentpart = 2;
		}
	} elseif (strtolower($getBusdet['business_payment_mode']) == "annual") {
		$PaymentParts = 1;
		$iPaymentpart = 1;
	}
	if ($AssessType == '1') { //Separate Computation of Taxes and Fees
		if ($PredComp != '1') {
			$getnaturedet = @mysql_query("select * from tempbusnature where owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
			while ($GetNaturedet = @mysql_fetch_assoc($getnaturedet)) {
				$getassessdet = @mysql_query("select * from tempassess where natureid = '$GetNaturedet[bus_code]' and owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
				while ($GetAssessdet = @mysql_fetch_assoc($getassessdet)) {
					$gettaxfeeType = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
					$GetTaxFeeType = @mysql_fetch_assoc($gettaxfeeType);
					if ($GetTaxFeeType['taxfeetype'] == '1') {
						$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%' and payment_part = '$pay_mode'");
					} else {
						$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%'");
					}
					$Getpaymentdet = @mysql_num_rows($getpaymentdet);
					if ($Getpaymentdet == '0') {
						$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
						$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
						if ($GetTaxFeetype['taxfeetype'] == '1') {
							$CompValAmount = round($GetAssessdet['compval'] / $PaymentParts  - ((($GetAssessdet['compval']  / $PaymentParts)* $GetBusinesscategory['tax_exemption']) / 100),2);
							$CompValAmount = $CompValAmount - $nbbawas;
						} else {
							$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$GetTaxFeeType[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
							$CheckIf0 = @mysql_num_rows($checkif0);
							if ($CheckIf0 > 0) {
								$CompValAmount = 0;
							} else {
								$CompValAmount = $GetAssessdet['compval'];
							}
						}
						$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '$GetNaturedet[bus_code]', '$GetAssessdet[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
					}
				}
			}
			//Default Fees
			$getdefaultfees = @mysql_query("select * from ebpls_buss_tfo where tfoindicator = '1'");
			while ($Getdefaultfees = @mysql_fetch_assoc($getdefaultfees)) {
				$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and tfoid = '$Getdefaultfees[tfoid]' and date_create like '$iCurrentYear%'");
				$Getpaymentdet = @mysql_num_rows($getpaymentdet);
				if ($Getpaymentdet == '0') {
					$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$Getdefaultfees[tfoid]'");
					$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
					if ($GetTaxFeetype['taxfeetype'] != '1') {
						$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$Getdefaultfees[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
						$CheckIf0 = @mysql_num_rows($checkif0);
						if ($CheckIf0 > 0) {
							$CompValAmount = 0;
						} else {
							$CompValAmount = $Getdefaultfees['defamt'];
						}
					}
					$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '', '$Getdefaultfees[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
				}
			}
		} else { // Preceding Quarters
			$getnaturedet = @mysql_query("select * from tempbusnature where owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
			while ($GetNaturedet = @mysql_fetch_assoc($getnaturedet)) {
				$getassessdet = @mysql_query("select * from tempassess where natureid = '$GetNaturedet[bus_code]' and owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
				while ($GetAssessdet = @mysql_fetch_assoc($getassessdet)) {
					$gettaxfeeType = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
					$GetTaxFeeType = @mysql_fetch_assoc($gettaxfeeType);
					if ($GetTaxFeeType['taxfeetype'] == '1') {
						$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%' and payment_part = '$pay_mode'");
					} else {
						$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%'");
					}
					$Getpaymentdet = @mysql_num_rows($getpaymentdet);
					if ($Getpaymentdet == '0') {
						$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
						$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
						if ($GetTaxFeetype['taxfeetype'] == '1') {
							if ($istat == "New") {
								$CompValAmount = round($GetAssessdet['compval'] - ((($GetAssessdet['compval']  / $PaymentParts)* $GetBusinesscategory['tax_exemption']) / 100),2);
							} else {
								$CompValAmount = round($GetAssessdet['compval'] / $PaymentParts  - ((($GetAssessdet['compval']  / $PaymentParts)* $GetBusinesscategory['tax_exemption']) / 100),2);
							}
							$CompValAmount = $CompValAmount - $nbbawas;
						} else {
							$CompValAmount = $GetAssessdet['compval'];
						}
						$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$GetTaxFeeType[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
						$CheckIf0 = @mysql_num_rows($checkif0);
						if ($CheckIf0 > 0) {
							$CompValAmount = 0;
						} else {
							$CompValAmount = $CompValAmount;
						}
						$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '$GetNaturedet[bus_code]', '$GetAssessdet[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
					}
				}
			}
			//Default Fees
			$getdefaultfees = @mysql_query("select * from ebpls_buss_tfo where tfoindicator = '1'");
			while ($Getdefaultfees = @mysql_fetch_assoc($getdefaultfees)) {
				$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and tfoid = '$Getdefaultfees[tfoid]' and date_create like '$iCurrentYear%'");
				$Getpaymentdet = @mysql_num_rows($getpaymentdet);
				if ($Getpaymentdet == '0') {
					$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$Getdefaultfees[tfoid]'");
					$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
					if ($GetTaxFeetype['taxfeetype'] != '1') {
						$CompValAmount = $Getdefaultfees['defamt'];
					}
					$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$Getdefaultfees[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
					$CheckIf0 = @mysql_num_rows($checkif0);
					if ($CheckIf0 > 0) {
						$CompValAmount = 0;
					} else {
						$CompValAmount = $CompValAmount;
					}
					$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '', '$Getdefaultfees[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
				}
			}
		}
	} else { //Not Separate Computation of Taxes and Fees
		$getnaturedet = @mysql_query("select * from tempbusnature where owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
		while ($GetNaturedet = @mysql_fetch_assoc($getnaturedet)) {
			$getassessdet = @mysql_query("select * from tempassess where natureid = '$GetNaturedet[bus_code]' and owner_id = '$owner_id' and business_id = '$business_id' and active = '1'");
			while ($GetAssessdet = @mysql_fetch_assoc($getassessdet)) {
				$gettaxfeeType = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
				$GetTaxFeeType = @mysql_fetch_assoc($gettaxfeeType);
				if ($GetTaxFeeType['taxfeetype'] == '1') {
					$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%' and payment_part = '$pay_mode'");
				} else {
					$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and natureid = '$GetNaturedet[bus_code]' and tfoid = '$GetAssessdet[tfoid]' and date_create like '$iCurrentYear%'");
				}
				$Getpaymentdet = @mysql_num_rows($getpaymentdet);
				if ($Getpaymentdet == '0') {
					$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$GetAssessdet[tfoid]'");
					$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
					$CompValAmount = $GetAssessdet['compval'] / $PaymentParts  - ((($GetAssessdet['compval']  / $PaymentParts)* $GetBusinesscategory['tax_exemption']) / 100);
					$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$GetTaxFeeType[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
					$CheckIf0 = @mysql_num_rows($checkif0);
					if ($CheckIf0 > 0) {
						$CompValAmount = 0;
					} else {
						$CompValAmount = $CompValAmount;
					}
					$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '$GetNaturedet[bus_code]', '$GetAssessdet[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
				}
			}
		}
		//Default Fees
		$getdefaultfees = @mysql_query("select * from ebpls_buss_tfo where tfoindicator = '1'");
		while ($Getdefaultfees = @mysql_fetch_assoc($getdefaultfees)) {
			$getpaymentdet = @mysql_query("select * from ebpls_payment_details where owner_id = '$owner_id' and business_id = '$business_id' and tfoid = '$Getdefaultfees[tfoid]' and date_create like '$iCurrentYear%'");
			$Getpaymentdet = @mysql_num_rows($getpaymentdet);
			if ($Getpaymentdet == '0') {
				$gettaxfeetype = @mysql_query("select * from ebpls_buss_tfo where tfoid = '$Getdefaultfees[tfoid]'");
				$GetTaxFeetype = @mysql_fetch_assoc($gettaxfeetype);
				if ($GetTaxFeetype['taxfeetype'] != '1') {
					$CompValAmount = $Getdefaultfees['defamt'];
				}
				$checkif0 = @mysql_query("select * from fee_exempt where tfoid = '$Getdefaultfees[tfoid]' and active = '1' and business_category_code = '$GetBusinesscategory[business_category_code]'");
				$CheckIf0 = @mysql_num_rows($checkif0);
				if ($CheckIf0 > 0) {
					$CompValAmount = 0;
				} else {
					$CompValAmount = $CompValAmount;
				}
				$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', '$iPaymentDetails', '$orno', '$owner_id', '$business_id', '', '$Getdefaultfees[tfoid]', '$CompValAmount', $pay_mode, now(), '$usern')");
			}
		}
	}
}
if ($penamt > 0) {
	$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', 'Surcharge', '$orno', '$owner_id', '$business_id', '', '', '$penamt', $pay_mode, now(), '$usern')");
}
if ($surcharge > 0) {
	$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', 'Interest', '$orno', '$owner_id', '$business_id', '', '', '$surcharge', $pay_mode, now(), '$usern')");
}
if ($sbacktax > 0) {
	$insertPaymentdet = @mysql_query("insert into ebpls_payment_details values ('', 'Back Tax', '$orno', '$owner_id', '$business_id', '', '', '$sbacktax', $pay_mode, now(), '$usern')");
}
<?php
include'includes/variables.php';
include'class/eBPLS.motorized.penalty.class.php';
include'lib/phpFunctions-inc.php';
if ($iIndicator == '1') {
	$iIndicator = "1";
} elseif ($iIndicator == '2') {
	$iIndicator = "2";
}
if ($iActivate == 'ON') {
	$iActivate = "1";
} else {
	$iActivate = "0";
}
if ($iLActivate == 'ON') {
	$iLActivate = "1";
} else {
	$iLActivate = "0";
}
if ($iBackTax == 'ON') {
	$iBackTax = "1";
} else {
	$iBackTax = "0";
}
if ($sb==md5(Submit)) {
	if ($bbo=='') {
		$datetoday = date("Y-m-d H:i:s");
		$nAnnounce = new EBPLSPenalty($dbLink,'false');
		$nAnnounce->setData(RENEWALTYPE,$iRenewalType);
		$nAnnounce->setData(RENEWALDATE1,$iRenewalDate1);
		$nAnnounce->setData(RENEWALDATE2,$iRenewalDate2);
		$nAnnounce->setData(RENEWALDATE3,$iRenewalDate3);
		$nAnnounce->setData(RENEWALDATE4,$iRenewalDate4);
		$nAnnounce->setData(RENEWALDATE5,$iRenewalDate5);
		$nAnnounce->setData(RENEWALDATE6,$iRenewalDate6);
		$nAnnounce->setData(RENEWALDATE7,$iRenewalDate7);
		$nAnnounce->setData(RENEWALDATE8,$iRenewalDate8);
		$nAnnounce->setData(RENEWALDATE9,$iRenewalDate9);
		$nAnnounce->setData(RENEWALDATE0,$iRenewalDate0);
		$nAnnounce->setData(RATEOFPENALTY,$iSurcharge);
		$nAnnounce->setData(RATEOFINTEREST,$iInterest);
		$nAnnounce->setData(INDICATOR,$iIndicator);
		$nAnnounce->setData(STATUS,$iActivate);
		$nAnnounce->setData(INTYPE,$IntType);
		$nAnnounce->setData(FEEONLY,$FeeOnly);
		$nAnnounce->setData(LATE_FILING_FEE,$iLate);
		$nAnnounce->setData(F_STATUS,$iLActivate);
		$nAnnounce->setData(BACKTAX,$iBackTax);
		$nAnnounce->setData(PERMIT_TYPE,'Motorized');
		$nAnnounce->setData(UPDATED_BY,$usern);
		$nAnnounce->setData(DATE_UPDATED,$datetoday);
		$nAnnounce->add();
		?>
		<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
		<?
	} else {
		$datetoday = date("Y-m-d H:i:s");
		$nAnnounce = new EBPLSPenalty($dbLink,'false');
		$datetoday = date("Y-m-d H:i:s");
		$nAnnounce = new EBPLSPenalty($dbLink,'false');
		$nAnnounce->setData(RENEWALTYPE,$iRenewalType);
		$nAnnounce->setData(RENEWALDATE1,$iRenewalDate1);
		$nAnnounce->setData(RENEWALDATE2,$iRenewalDate2);
		$nAnnounce->setData(RENEWALDATE3,$iRenewalDate3);
		$nAnnounce->setData(RENEWALDATE4,$iRenewalDate4);
		$nAnnounce->setData(RENEWALDATE5,$iRenewalDate5);
		$nAnnounce->setData(RENEWALDATE6,$iRenewalDate6);
		$nAnnounce->setData(RENEWALDATE7,$iRenewalDate7);
		$nAnnounce->setData(RENEWALDATE8,$iRenewalDate8);
		$nAnnounce->setData(RENEWALDATE9,$iRenewalDate9);
		$nAnnounce->setData(RENEWALDATE0,$iRenewalDate0);
		$nAnnounce->setData(RATEOFPENALTY,$iSurcharge);
		$nAnnounce->setData(RATEOFINTEREST,$iInterest);
		$nAnnounce->setData(INDICATOR,$iIndicator);
		$nAnnounce->setData(STATUS,$iActivate);
		$nAnnounce->setData(INTYPE,$IntType);
		$nAnnounce->setData(FEEONLY,$FeeOnly);
		$nAnnounce->setData(LATE_FILING_FEE,$iLate);
		$nAnnounce->setData(F_STATUS,$iLActivate);
		$nAnnounce->setData(BACKTAX,$iBackTax);
		$nAnnounce->setData(PERMIT_TYPE,'Motorized');
		$nAnnounce->setData(UPDATED_BY,$usern);
		$nAnnounce->setData(DATE_UPDATED,$datetoday);
		$nAnnounce->update($bbo);
		?>
		<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
		<?
	}
}
$nAnnounce = new EBPLSPenalty($dbLink,'false');
$nAnnounce->searchpen('Motorized');
$nResult = $nAnnounce->out;
$iRenewalType = $nResult[renewaltype];
if ($iRenewalType == '1') {
	$renewalcheck1 = "CHECKED";
	$renewalcheck2 = "";
} elseif  ($iRenewalType == '2') {
	$renewalcheck1 = "";
	$renewalcheck2 = "CHECKED";
}
if ($renewtype == '1') {
	$renewalcheck1 = "CHECKED";
	$renewalcheck2 = "";
	$iRenewalType = '1';
} elseif  ($renewtype == '2') {
	$renewalcheck1 = "";
	$renewalcheck2 = "CHECKED";
	$iRenewalType = '2';
}


$iRenewalDate1 = $nResult[renewaldate1];
$iRenewalDate2 = $nResult[renewaldate2];
$iRenewalDate3 = $nResult[renewaldate3];
$iRenewalDate4 = $nResult[renewaldate4];
$iRenewalDate5 = $nResult[renewaldate5];
$iRenewalDate6 = $nResult[renewaldate6];
$iRenewalDate7 = $nResult[renewaldate7];
$iRenewalDate8 = $nResult[renewaldate8];
$iRenewalDate9 = $nResult[renewaldate9];
$iRenewalDate0 = $nResult[renewaldate0];
$iSurcharge = $nResult[rateofpenalty];
$iInterest = $nResult[rateofinterest];
$iIndicator = $nResult[indicator];
$iActivate = $nResult[status];
$IntType = $nResult[intype];
$FeeOnly = $nResult[feeonly];
$iLate = $nResult[late_filing_fee];
$iLActivate = $nResult[f_status];
$iBackTax = $nResult[backtax];
$bbo = $nResult[id];
if ($iIndicator == '1') {
	$is_check1 = "CHECKED";
} elseif ($iIndicator == '2') {
	$is_check2 = "CHECKED";
}
if ($iActivate == '1') {
	$scheck = "CHECKED";
} else {
	$scheck = "";
}
if ($iLActivate == '1') {
	$lcheck = "CHECKED";
} else {
	$lcheck = "";
}
if ($iBackTax == '1') {
	$backcheck = "CHECKED";
} else {
	$backcheck = "";
}
if ($IntType == '1') {
	$intypecheck1 = "CHECKED";
} elseif ($IntType == '2') {
	$intypecheck2 = "CHECKED";
}
if ($FeeOnly == '1') {
	$feecheck1 = "CHECKED";
} elseif ($FeeOnly == '2') {
	$feecheck2 = "CHECKED";
}

include'html/eBPLS_motorized_penalty.html';
?>

<?php
include'includes/variables.php';
include'class/eBPLS.penalty.class.php';
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
		$nAnnounce->setData(RENEWALDATE,$iRenewalDate);
		$nAnnounce->setData(RATEOFPENALTY,$iSurcharge);
		$nAnnounce->setData(RATEOFINTEREST,$iInterest);
		$nAnnounce->setData(INDICATOR,$iIndicator);
		$nAnnounce->setData(STATUS,$iActivate);
		$nAnnounce->setData(INTYPE,$IntType);
		$nAnnounce->setData(FEEONLY,$FeeOnly);
		$nAnnounce->setData(LATE_FILING_FEE,$iLate);
		$nAnnounce->setData(F_STATUS,$iLActivate);
		$nAnnounce->setData(BACKTAX,$iBackTax);
		$nAnnounce->setData(PERMIT_TYPE,'Fishery');
		$nAnnounce->setData(UPDATED_BY,$usern);
		$nAnnounce->setData(DATE_UPDATED,$datetoday);
		$nAnnounce->add();
		?>
		<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
		<?
	} else {
		$datetoday = date("Y-m-d H:i:s");
		$nAnnounce = new EBPLSPenalty($dbLink,'false');
		$nAnnounce->setData(RENEWALDATE,$iRenewalDate);
		$nAnnounce->setData(RATEOFPENALTY,$iSurcharge);
		$nAnnounce->setData(RATEOFINTEREST,$iInterest);
		$nAnnounce->setData(INDICATOR,$iIndicator);
		$nAnnounce->setData(STATUS,$iActivate);
		$nAnnounce->setData(INTYPE,$IntType);
		$nAnnounce->setData(FEEONLY,$FeeOnly);
		$nAnnounce->setData(LATE_FILING_FEE,$iLate);
		$nAnnounce->setData(F_STATUS,$iLActivate);
		$nAnnounce->setData(BACKTAX,$iBackTax);
		$nAnnounce->setData(PERMIT_TYPE,'Fishery');
		$nAnnounce->setData(UPDATED_BY,$usern);
		$nAnnounce->setData(DATE_UPDATED,$datetoday);
		$nAnnounce->update($bbo);
		?>
		<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
		<?
	}
}
$nAnnounce = new EBPLSPenalty($dbLink,'false');
$nAnnounce->searchpen('Fishery');
$nResult = $nAnnounce->out;
$iRenewalDate = $nResult[renewaldate];
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
if ($iBackTax == '1') {
	$backcheck = "CHECKED";
} else {
	$backcheck = "";
}
include'html/eBPLS_fishery_penalty.html';
?>

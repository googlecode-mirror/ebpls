<?php
include'includes/variables.php';
include'class/eBPLS.CTC.Settings.class.php';
include'lib/phpFunctions-inc.php';
if ($sb=='Submit') {
	$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
	$nCTCSett->search(NULL,'Individual');
	$rResult = $nCTCSett->out;
	if (is_array($rResult)) {
		$datetoday = date("Y-m-d H:i:s");
		$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
		$nCTCSett->setData(CTC_TYPE,'Individual');
		$nCTCSett->setData(INTEREST_RATE,$individual_interest_rate);
		$nCTCSett->setData(CEILING_RATE,$individual_ceiling_rate);
		$nCTCSett->setData(PENALTY_DATE,$individual_penalty_date);
		$nCTCSett->setData(MODIFIED_DATE,$datetoday);
		$nCTCSett->setData(UPDATED_BY,$usern);
		$nCTCSett->update($rResult[id]);
		$bbo="";
	} else {
		$datetoday = date("Y-m-d H:i:s");
		$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
		$nCTCSett->setData(CTC_TYPE,'Individual');
		$nCTCSett->setData(INTEREST_RATE,$individual_interest_rate);
		$nCTCSett->setData(CEILING_RATE,$individual_ceiling_rate);
		$nCTCSett->setData(PENALTY_DATE,$individual_penalty_date);
		$nCTCSett->setData(MODIFIED_DATE,$datetoday);
		$nCTCSett->setData(UPDATED_BY,$usern);
		$nCTCSett->add();
	}
	$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
	$nCTCSett->search(NULL,'Corporate');
	$rResult = $nCTCSett->out;
	if (is_array($rResult)) {
		$datetoday = date("Y-m-d H:i:s");
		$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
		$nCTCSett->setData(CTC_TYPE,'Corporate');
		$nCTCSett->setData(INTEREST_RATE,$corporate_interest_rate);
		$nCTCSett->setData(CEILING_RATE,$corporate_ceiling_rate);
		$nCTCSett->setData(PENALTY_DATE,$corporate_penalty_date);
		$nCTCSett->setData(MODIFIED_DATE,$datetoday);
		$nCTCSett->setData(UPDATED_BY,$usern);
		$nCTCSett->update($rResult[id]);
		$bbo="";
	} else {
		$datetoday = date("Y-m-d H:i:s");
		$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
		$nCTCSett->setData(CTC_TYPE,'Corporate');
		$nCTCSett->setData(INTEREST_RATE,$corporate_interest_rate);
		$nCTCSett->setData(CEILING_RATE,$corporate_ceiling_rate);
		$nCTCSett->setData(PENALTY_DATE,$corporate_penalty_date);
		$nCTCSett->setData(MODIFIED_DATE,$datetoday);
		$nCTCSett->setData(UPDATED_BY,$usern);
		$nCTCSett->add();
	}
	?>
	<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
	<?
}
$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
$nCTCSett->search(NULL,'Individual');
$nResult = $nCTCSett->out;
$individual_interest_rate = $nResult[interest_rate];
$individual_ceiling_rate = $nResult[ceiling_rate];
$individual_penalty_date = $nResult[penalty_date];
$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
$nCTCSett->search(NULL,'Corporate');
$nResult = $nCTCSett->out;
$corporate_interest_rate = $nResult[interest_rate];
$corporate_ceiling_rate = $nResult[ceiling_rate];
$corporate_penalty_date = $nResult[penalty_date];
if ($individual_penalty_date == '1') {
	$pendateseli1 = "Selected";
} elseif ($individual_penalty_date == '2') {
	$pendateseli2 = "Selected";
} elseif ($individual_penalty_date == '3') {
	$pendateseli3 = "Selected";
} elseif ($individual_penalty_date == '4') {
	$pendateseli4 = "Selected";
} elseif ($individual_penalty_date == '5') {
	$pendateseli5 = "Selected";
} elseif ($individual_penalty_date == '6') {
	$pendateseli6 = "Selected";
}
if ($corporate_penalty_date == '1') {
	$pendateselc1 = "Selected";
} elseif ($corporate_penalty_date == '2') {
	$pendateselc2 = "Selected";
} elseif ($corporate_penalty_date == '3') {
	$pendateselc3 = "Selected";
} elseif ($corporate_penalty_date == '4') {
	$pendateselc4 = "Selected";
} elseif ($corporate_penalty_date == '5') {
	$pendateselc5 = "Selected";
} elseif ($corporate_penalty_date == '6') {
	$pendateselc6 = "Selected";
}
include'html/ctc_interest.html';

?>

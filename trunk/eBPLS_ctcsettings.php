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
$nCTCSett = new EBPLSCTCSettings($dbLink,'false');
$nCTCSett->search(NULL,'Corporate');
$nResult = $nCTCSett->out;
$corporate_interest_rate = $nResult[interest_rate];
$corporate_ceiling_rate = $nResult[ceiling_rate];
include'html/ctc_interest.html';

?>

<?php
include'includes/variables.php';
include'class/eBPLS.SignTemp.class.php';
include'lib/phpFunctions-inc.php';

if ($sb=='Submit') {
	if ($bbo=='') {
		$nSignTemp = new EBPLSSignTemp($dbLink,'false');
		$nSignTemp->searchcomp($iReportFile, $iSign, $iType);
		$rResult = $nSignTemp->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript:alert ("Existing Record Found!!"); _FRM.iReportFile.focus();'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nSignTemp = new EBPLSSignTemp($dbLink,'false');
			$nSignTemp->setData(RS_ID,'');
			$nSignTemp->setData(REPORT_FILE,$iReportFile);
			$nSignTemp->setData(SIGN_ID,$iSign);
			$nSignTemp->setData(DATE_UPDATED,$datetoday);
			$nSignTemp->setData(UPDATED_BY,$usern);
			$nSignTemp->setData(SIGN_TYPE,$iType);
			$nSignTemp->add();
			$iReportFile = "";
			$iSign = "";
			$iType = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!"); _FRM.iReportFile.focus();'></body>
			<?
		}
		
	} else {
		$nSignTemp = new EBPLSSignTemp($dbLink,'false');
		$nSignTemp->searchcomp1($bbo,$iReportFile, $iSign, $iType);
		$rResult = $nSignTemp->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript:alert ("Existing Record Found!!"); _FRM.iReportFile.focus();'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nSignTemp = new EBPLSSignTemp($dbLink,'false');
			$nSignTemp->setData(REPORT_FILE,$iReportFile);
			$nSignTemp->setData(SIGN_ID,$iSign);
			$nSignTemp->setData(DATE_UPDATED,$datetoday);
			$nSignTemp->setData(UPDATED_BY,$usern);
			$nSignTemp->setData(SIGN_TYPE,$iType);
			$nSignTemp->update($bbo);
			$bbo="";
			$iReportFile = "";
			$iSign = "";
			$iType = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!"); _FRM.iReportFile.focus();'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$nSignTemp = new EBPLSSignTemp($dbLink,'false');
	$nSignTemp->delete($bbo);
	$bbo="";
	?>
	<body onload='javascript:alert ("Record Deleted!!"); _FRM.iReportFile.focus();'></body>
	<?
}
if ($com == "edit") {
	$nSignTemp = new EBPLSSignTemp($dbLink,'false');
	$nSignTemp->search($bbo,NULL);
	$nResult = $nSignTemp->out;
	$iReportFile = $nResult[report_file];
	$iSign = $nResult[sign_id];
	$iType = $nResult[sign_type];
	if ($iType == "1") {
		$iSelect1 = "selected";
	} elseif ($iType == "2") {
		$iSelect2 = "selected";
	} elseif ($iType == "3") {
		$iSelect3 = "selected";
	}
}
include'html/eBPLS_signtemp.html';

?>

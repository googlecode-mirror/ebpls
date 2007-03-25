<?php
include'includes/variables.php';
include'class/eBPLS.permit.format.class.php';
if ($sb=='Submit') {
	if ($permit_date=='ON') {
		$permit_date1 = 1;
	} else {
		$permit_date1 = 2;
	}
	//echo $bbo."VooDoo";
	if ($bbo=='') {
		$nEconomic = new EBPLSPermitFormat($dbLink,'false');
		$nEconomic->search(NULL,$permit_type);
		$rResult = $nEconomic->out;
		if (is_array($rResult)) {
			?>
			<body onload='javascript:alert ("Existing/Invalid Code Found");'></body>
			<?php
		} else {
			$datetoday = date("Y-d-m H:i:s");
			$nEconomic = new EBPLSPermitFormat($dbLink,'false');
			$nEconomic->setData(PERMIT_TYPE,$permit_type);
			$nEconomic->setData(USER,$usern);
			$nEconomic->setData(DATE_ENTERED,$datetoday);
			$nEconomic->setData(PERMIT_HEADER,$permit_header);
			$nEconomic->setData(PERMIT_DATE,$permit_date1);
			$nEconomic->setData(PERMIT_SEQUENCE,$permit_sequence);
			$nEconomic->add();
			$permit_type = "";
			$permit_header = "";
			$permit_sequence = "";
			$permit_date1 = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nEconomic = new EBPLSPermitFormat($dbLink,'false');
		$nEconomic->searchcomp($bbo,$permit_type);
		$rResult = $nEconomic->rcount;
		if ($rResult[0] > 0) {
			?>
			<body onload='javascript:alert ("Existing/Invalid Code Found"); return false;'></body>
			<?php
		} else {
			$datetoday = date("Y-d-m H:i:s");
			$nEconomic = new EBPLSPermitFormat($dbLink,'false');
			$nEconomic->setData(PERMIT_TYPE,$permit_type);
			$nEconomic->setData(USER,$usern);
			$nEconomic->setData(DATE_ENTERED,$datetoday);
			$nEconomic->setData(PERMIT_HEADER,$permit_header);
			$nEconomic->setData(PERMIT_DATE,$permit_date1);
			$nEconomic->setData(PERMIT_SEQUENCE,$permit_sequence);
			$nEconomic->update($bbo);
			$bbo="";
			$permit_type = "";
			$permit_header = "";
			$permit_sequence = "";
			$permit_date1 = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$nEconomic = new EBPLSPermitFormat($dbLink,'false');
	$nEconomic->delete($bbo);
	$bbo="";
	?>
	<body onload='javascript:alert ("Record Deleted!!");'></body>
	<?
}
if ($com=="edit") {
$nEconomic = new EBPLSPermitFormat($dbLink,'false');
$nEconomic->search($bbo,NULL);
$nResult = $nEconomic->out;
$permit_type=$nResult[permit_type];
$permit_header=$nResult[permit_header];
if ($nResult[permit_date]==1) {
	$permit_date_check='checked';
} else {
	$permit_date_check='';
}
$permit_sequence=$nResult[permit_sequence];
}
include'html/eBPLS_Permit_Format.html';
include'pager/permit_format_page.php';
?>

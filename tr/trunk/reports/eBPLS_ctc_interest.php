<?php
include'includes/variables.php';
include'class/eBPLS.citizenship.class.php';
if ($sb=='Submit') {
	if ($bbo=='') {
		$nCitizenship = new EBPLSCitizenship($dbLink,'false');
		$nCitizenship->search(NULL,$nCode);
		$rResult = $nCitizenship->out;
		if (is_array($rResult)) {
			?>
			<body onload='javascript:alert ("Existing/Invalid Code Found");'></body>
			<?php
		} else {
			$datetoday = date("Y-d-m H:i:s");
			$nCitizenship = new EBPLSCitizenship($dbLink,'false');
			$nCitizenship->setData(CTC_TYPE,$nCode);
			$nCitizenship->setData(CTC_TYPE,$nCode);
			$nCitizenship->add();
		}
		
	} else {
		$datetoday = date("Y-d-m H:i:s");
		$nCitizenship = new EBPLSCitizenship($dbLink,'false');
		$nCitizenship->setData(CIT_DESC,$nCode);
		$nCitizenship->update($bbo);
		$bbo="";
	}
}elseif ($confx==1) {
	$nCitizenship = new EBPLSCitizenship($dbLink,'false');
	$nCitizenship->delete($bbo);
	$bbo="";
}
$nCitizenship = new EBPLSCitizenship($dbLink,'false');
$nCitizenship->search($bbo,NULL);
$nResult = $nCitizenship->out;
$industry_id = $nResult[cit_id];
$nCode = $nResult[cit_desc];
include'html/citizenship.html';
include'pager/citizenship_page.php';
?>

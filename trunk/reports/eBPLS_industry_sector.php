<?php
include'includes/variables.php';
include'class/eBPLS.industry.class.php';
if ($sb=='Submit') {
	if ($bbo=='') {
		$nIndustry = new EBPLSIndustry($dbLink,'false');
		$nIndustry->search(NULL,$nCode);
		$rResult = $nIndustry->out;
		if (is_array($rResult)) {
			?>
			<body onload='javascript:alert ("Existing/Invalid Code Found");'></body>
			<?php
		} else {
			$datetoday = date("Y-d-m H:i:s");
			$nIndustry = new EBPLSIndustry($dbLink,'false');
			$nIndustry->setData(INDUSTRY_SECTOR_CODE,$nCode);
			$nIndustry->setData(INDUSTRY_SECTOR_DESC,$nType);
			$nIndustry->setData(INDUSTRY_SECTOR_DATE_REGISTERED,$datetoday);
			$nIndustry->setData(INDUSTRY_SECTOR_DATE_UPDATED,$datetoday);
			$nIndustry->setData(UPDATED_BY,$ThUserData[username]);
			$nIndustry->add();
		}
		
	} else {
		$datetoday = date("Y-d-m H:i:s");
		$nIndustry = new EBPLSIndustry($dbLink,'false');
		$nIndustry->setData(INDUSTRY_SECTOR_CODE,$nCode);
		$nIndustry->setData(INDUSTRY_SECTOR_DESC,$nType);
		$nIndustry->setData(INDUSTRY_SECTOR_DATE_UPDATED,$datetoday);
		$nIndustry->setData(UPDATED_BY,$ThUserData[username]);
		$nIndustry->update($bbo);
		$bbo="";
	}
}elseif ($confx==1) {
	$nIndustry = new EBPLSIndustry($dbLink,'false');
	$nIndustry->delete($bbo);
	$bbo="";
}
$nIndustry = new EBPLSIndustry($dbLink,'false');
$nIndustry->search($bbo,NULL);
$nResult = $nIndustry->out;
$industry_id = $nResult[industry_sector_code];
$nCode = $nResult[industry_sector_code];
$nType = $nResult[industry_sector_desc];
include'html/industry.html';
include'pager/industry_page.php';
?>

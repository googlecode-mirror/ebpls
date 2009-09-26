<?php
include'includes/variables.php';
include'class/eBPLS.economic.org.class.php';
if ($sb=='Submit') {
	//echo $bbo."VooDoo";
	if ($bbo=='') {
		$nEconomic = new EBPLSEconomicOrg($dbLink,'false');
		$nEconomic->search(NULL,$nCode);
		$rResult = $nEconomic->out;
		if (is_array($rResult)) {
			?>
			<body onload='javascript:alert ("Existing/Invalid Code Found");'></body>
			<?php
		} else {
			$datetoday = date("Y-d-m H:i:s");
			$nEconomic = new EBPLSEconomicOrg($dbLink,'false');
			$nEconomic->setData(ECONOMIC_ORG_CODE,$nCode);
			$nEconomic->setData(ECONOMIC_ORG_DESC,$nType);
			$nEconomic->setData(UPDATED_BY,$ThUserData[username]);
			$nEconomic->setData(DATE_UPDATED,$datetoday);
			$nEconomic->add();
			$nCode='';
			$nType='';
			 ?>
                        <body onload='javascript:alert ("Data is successfully added to the database.");'></body>
                        <?php
		}
		
	} else {
		 $nEconomic = new EBPLSEconomicOrg($dbLink,'false');
		$nEconomic->searchedit($nCode,$bbo,"ebpls_economic_org","economic_org_code");
                $rResult = $nEconomic->outnumrow;
                if ($rResult>0) {
                        ?>
                        <body onload='javascript:alert ("Existing/Invalid Code Found");'></body>
                        <?php
                } else {
		$datetoday = date("Y-d-m H:i:s");
		$nEconomic = new EBPLSEconomicOrg($dbLink,'false');
		$nEconomic->setData(ECONOMIC_ORG_CODE,$nCode);
		$nEconomic->setData(ECONOMIC_ORG_DESC,$nType);
		$nEconomic->setData(UPDATED_BY,$ThUserData[username]);
		$nEconomic->setData(DATE_UPDATED,$datetoday);
		$nEconomic->update($bbo);
		$bbo="";
		$com='edit';
		 ?>
                        <body onload='javascript:alert ("Record Successfully Updated");'></body>
                        <?php
		}
	}
}elseif ($confx==1) {
	$nEconomic = new EBPLSEconomicOrg($dbLink,'false');
	$nEconomic->delete($bbo);
	$bbo="";
	$nCode='';
        $nType='';
	?>
	<body onload='javascript:alert ("Record Deleted!!");'></body>
	<?

}

if ($com=='edit' || $confx=='cancel') {
$nEconomic = new EBPLSEconomicOrg($dbLink,'false');
$nEconomic->search($bbo,NULL);
$nResult = $nEconomic->out;
$economic_org_id = $nResult[economic_org_id];
$nCode = $nResult[economic_org_code];
$nType = $nResult[economic_org_desc];
}
include'html/eBPLS_Economic_Organization.html';
include'pager/economic_org_page.php';
?>

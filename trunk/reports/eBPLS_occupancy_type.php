<?php
include'includes/variables.php';
include'class/eBPLS.occupancy.class.php';
if ($sb=='Submit') {
	if ($bbo=='') {
		$nOccupancy = new EBPLSOccupancy($dbLink,'false');
		$nOccupancy->search(NULL,$nCode);
		$rResult = $nOccupancy->out;
		if (is_array($rResult)) {
			?>
			<body onload='javascript:alert ("Existing/Invalid Code Found");'></body>
			<?php
		} else {
			$datetoday = date("Y-d-m H:i:s");
			$nOccupancy = new EBPLSOccupancy($dbLink,'false');
			$nOccupancy->setData(OCCUPANCY_TYPE_CODE,$nCode);
			$nOccupancy->setData(OCCUPANCY_TYPE_DESC,$nType);
			$nOccupancy->setData(OCCUPANCY_TYPE_DATE_REGISTERED,$datetoday);
			$nOccupancy->setData(OCCUPANCY_TYPE_DATE_UPDATED,$datetoday);
			$nOccupancy->setData(UPDATED_BY,$ThUserData[username]);
			$nOccupancy->add();
		}
		
	} else {
		$datetoday = date("Y-d-m H:i:s");
		$nOccupancy = new EBPLSOccupancy($dbLink,'false');
		$nOccupancy->setData(OCCUPANCY_TYPE_CODE,$nCode);
		$nOccupancy->setData(OCCUPANCY_TYPE_DESC,$nType);
		$nOccupancy->setData(OCCUPANCY_TYPE_DATE_UPDATED,$datetoday);
		$nOccupancy->setData(UPDATED_BY,$ThUserData[username]);
		$nOccupancy->update($bbo);
		$bbo="";
	}
}elseif ($confx==1) {
	$nOccupancy = new EBPLSOccupancy($dbLink,'false');
	$nOccupancy->delete($bbo);
	$bbo="";
}
$nOccupancy = new EBPLSOccupancy($dbLink,'false');
$nOccupancy->search($bbo,NULL);
$nResult = $nOccupancy->out;
$occupancy_id = $nResult[banks_id];
$nCode = $nResult[occupancy_type_code];
$nType = $nResult[occupancy_type_desc];
include'html/occupancy.html';
include'pager/occupancy_page.php';
?>

<?php
include'includes/variables.php';
include'class/eBPLS.District.class.php';
include'class/eBPLS.LGU.class.php';
include'lib/phpFunctions-inc.php';


if ($sb=='Submit') {
	if ($bbo=='') {
		$nDistrict = new EBPLSDistrict($dbLink,'false');
		$nDistrict->searchcomp1($iLGU,$iDistrict);
		$rResult = $nDistrict->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing District Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nDistrict = new EBPLSDistrict($dbLink,'false');
			$nDistrict->setData(DISTRICT_CODE,'');
			$nDistrict->setData(DISTRICT_DESC,$iDistrict);
			$nDistrict->setData(DISTRICT_DATE_REGISTERED,$datetoday);
			$nDistrict->setData(DISTRICT_DATE_UPDATED,$datetoday);
			$nDistrict->setData(UPDATED_BY,$usern);
			$nDistrict->setData(G_ZONE,'');
			$nDistrict->setData(UPPER,$iLGU);
			$nDistrict->setData(BLGF_CODE,$iBLGFCode);
			$nDistrict->add();
			$iLGU = "";
			$iDistrict = "";
			$iBLGFCode = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nDistrict = new EBPLSDistrict($dbLink,'false');
		$nDistrict->searchcomp($bbo,$iDistrict, $iLGU);
		$rResult = $nDistrict->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing District Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nDistrict = new EBPLSDistrict($dbLink,'false');
			$nDistrict->setData(DISTRICT_DESC,$iDistrict);
			$nDistrict->setData(DISTRICT_DATE_REGISTERED,$datetoday);
			$nDistrict->setData(DISTRICT_DATE_UPDATED,$datetoday);
			$nDistrict->setData(UPDATED_BY,$usern);
			$nDistrict->setData(G_ZONE,'');
			$nDistrict->setData(UPPER,$iLGU);
			$nDistrict->setData(BLGF_CODE,$iBLGFCode);
			$nDistrict->update($bbo);
			$bbo="";
			$iLGU = "";
			$iDistrict = "";
			$iBLGFCode = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$check1 = mysql_query("select * from ebpls_owner where owner_district_code = '$bbo'");
	$check1 = mysql_num_rows($check1);
	$check2 = mysql_query("select * from ebpls_business_enterprise where business_district_code = '$bbo'");
	$check2 = mysql_num_rows($check2);
	if ($check1 > 0 || $check2 >0) {
		?>
		<body onload='javascript:alert ("Cannot Delete. Record exist in other table(s).");'></body>
		<?
	} else {
		$nDistrict = new EBPLSDistrict($dbLink,'false');
		$nDistrict->delete($bbo);
		$bbo="";
		?>
		<body onload='javascript:alert ("Record Deleted!!");'></body>
		<?
	}
}
if ($com == "edit") {
$nDistrict = new EBPLSDistrict($dbLink,'false');
$nDistrict->search($bbo,NULL);
$nResult = $nDistrict->out;
$iDistrict = $nResult[district_desc];
$iBLGFCode = $nResult[blgf_code];
$nLGU = new EBPLSLGU($dbLink,'false');
$nLGU->search($nResult[upper],NULL);
$iResult = $nLGU->out;
$iLGU = $iResult[city_municipality_code];
}
include'html/eBPLS_district.html';

?>

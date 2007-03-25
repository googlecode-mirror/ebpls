<?php
include'includes/variables.php';
include'class/eBPLS.Barangay.class.php';
include'class/eBPLS.Zone.class.php';
include'lib/phpFunctions-inc.php';
if ($sb=='Submit') {
	if ($bbo=='') {
		$nZone = new EBPLSZone($dbLink,'false');
		$nZone->searchcomp1($iBarangay,$iZone);
		$rResult = $nZone->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing Zone Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nZone = new EBPLSZone($dbLink,'false');
			$nZone->setData(ZONE_CODE,'');
			$nZone->setData(ZONE_DESC,$iZone);
			$nZone->setData(ZONE_DATE_REGISTERED,$datetoday);
			$nZone->setData(ZONE_DATE_UPDATED,$datetoday);
			$nZone->setData(UPDATED_BY,$usern);
			$nZone->setData(G_ZONE,'');
			$nZone->setData(UPPER,$iBarangay);
			$nZone->setData(BLGF_CODE,'');
			$nZone->add();
			$iBarangay = "";
			$iBLGFCode = "";
			$iZone = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nZone = new EBPLSZone($dbLink,'false');
		$nZone->searchcomp($bbo,$iZone, $iBarangay);
		$rResult = $nZone->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing Zone Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nZone = new EBPLSZone($dbLink,'false');
			$nZone->setData(ZONE_DESC,$iZone);
			$nZone->setData(ZONE_DATE_REGISTERED,$datetoday);
			$nZone->setData(ZONE_DATE_UPDATED,$datetoday);
			$nZone->setData(UPDATED_BY,$usern);
			$nZone->setData(G_ZONE,'');
			$nZone->setData(UPPER,$iBarangay);
			$nZone->setData(BLGF_CODE,'');
			$nZone->update($bbo);
			$bbo="";
			$iBarangay = "";
			$iBLGFCode = "";
			$iZone = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$check1 = mysql_query("select * from ebpls_owner where owner_zone_code = '$bbo'");
	$check1 = mysql_num_rows($check1);
	$check2 = mysql_query("select * from ebpls_business_enterprise where business_zone_code = '$bbo'");
	$check2 = mysql_num_rows($check2);
	if ($check1 > 0 || $check2 >0) {
		?>
		<body onload='javascript:alert ("Cannot Delete. Record exist in other table(s).");'></body>
		<?
	} else {
		$nZone = new EBPLSZone($dbLink,'false');
		$nZone->delete($bbo);
		$bbo="";
		$iBarangay = "";
		$iZone = "";
		$iBLGFCode = "";
		?>
		<body onload='javascript:alert ("Record Deleted!!");'></body>
		<?
	}
}
if ($com == "edit") {
$nZone = new EBPLSZone($dbLink,'false');
$nZone->search($bbo,NULL);
$nResult = $nZone->out;
$iZone = $nResult[zone_desc];
$nBarangay = new EBPLSBarangay($dbLink,'false');
$nBarangay->search($nResult[upper],NULL);
$iResult = $nBarangay->out;
$iBarangay = $iResult[barangay_code];
}
include'html/eBPLS_zone.html';

?>

<?php
include'includes/variables.php';
include'class/eBPLS.Barangay.class.php';
include'class/eBPLS.District.class.php';
include'lib/phpFunctions-inc.php';
if ($iGZone == "ON") {
	$g_zone = "1";
} else {
	$g_zone = "0";
}
if ($sb=='Submit') {
	if ($bbo=='') {
		$nBarangay = new EBPLSBarangay($dbLink,'false');
		$nBarangay->searchcomp1($iDistrict,$iBarangay);
		$rResult = $nBarangay->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing Barangay Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nBarangay = new EBPLSBarangay($dbLink,'false');
			$nBarangay->setData(BARANGAY_CODE,'');
			$nBarangay->setData(BARANGAY_DESC,$iBarangay);
			$nBarangay->setData(BARANGAY_DATE_REGISTERED,$datetoday);
			$nBarangay->setData(BARANGAY_DATE_UPDATED,$datetoday);
			$nBarangay->setData(UPDATED_BY,$usern);
			$nBarangay->setData(G_ZONE,$g_zone);
			$nBarangay->setData(UPPER,$iDistrict);
			$nBarangay->setData(BLGF_CODE,$iBLGFCode);
			$nBarangay->add();
			$iDistrict = "";
			$iBarangay = "";
			$iBLGFCode = "";
			$iDistrict = "";
			$iCheck = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nBarangay = new EBPLSBarangay($dbLink,'false');
		$nBarangay->searchcomp($bbo,$iBarangay, $iDistrict);
		$rResult = $nBarangay->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing Barangay Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nBarangay = new EBPLSBarangay($dbLink,'false');
			$nBarangay->setData(BARANGAY_DESC,$iBarangay);
			$nBarangay->setData(BARANGAY_DATE_REGISTERED,$datetoday);
			$nBarangay->setData(BARANGAY_DATE_UPDATED,$datetoday);
			$nBarangay->setData(UPDATED_BY,$usern);
			$nBarangay->setData(G_ZONE,$g_zone);
			$nBarangay->setData(UPPER,$iDistrict);
			$nBarangay->setData(BLGF_CODE,$iBLGFCode);
			$nBarangay->update($bbo);
			$bbo="";
			$iDistrict = "";
			$iBarangay = "";
			$iBLGFCode = "";
			$iDistrict = "";
			$iCheck = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$check1 = mysql_query("select * from ebpls_owner where owner_barangay_code = '$bbo'");
	$check1 = mysql_num_rows($check1);
	$check2 = mysql_query("select * from ebpls_business_enterprise where business_barangay_code = '$bbo'");
	$check2 = mysql_num_rows($check2);
	if ($check1 > 0 || $check2 >0) {
		?>
		<body onload='javascript:alert ("Cannot Delete. Record exist in other table(s).");'></body>
		<?
	} else {
		$nBarangay = new EBPLSBarangay($dbLink,'false');
		$nBarangay->delete($bbo);
		$bbo="";
		$iBarangay = "";
		$iDistrict = "";
		$iBLGFCode = "";
		$iCheck = "";
		?>
		<body onload='javascript:alert ("Record Deleted!!");'></body>
		<?
	}
}
if ($com == "edit") {
$nBarangay = new EBPLSBarangay($dbLink,'false');
$nBarangay->search($bbo,NULL);
$nResult = $nBarangay->out;
$iBarangay = $nResult[barangay_desc];
$iBLGFCode = $nResult[blgf_code];
$iZone = $nResult[g_zone];
if ($iZone == "1") {
	$iCheck = "checked";
} else {
	$iCheck = "";
}
$nDistrict = new EBPLSDistrict($dbLink,'false');
$nDistrict->search($nResult[upper],NULL);
$iResult = $nDistrict->out;
$iDistrict = $iResult[district_code];
}
include'html/eBPLS_barangay.html';

?>

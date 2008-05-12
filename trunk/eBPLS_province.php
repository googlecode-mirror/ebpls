<?php
include'includes/variables.php';
include'class/eBPLS.Province.class.php';
include'lib/phpFunctions-inc.php';

$iProvince = isset($iProvince) ? $iProvince : ''; //2008.05.11
$iBLGFCode = isset($iBLGFCode) ? $iBLGFCode : '' ;
$sb = isset($sb) ? $sb : '';
$bbo = isset($bbo) ? $bbo : '';
$confx = isset($confx) ? $confx : '';
$com = isset($com) ? $com : '';		

while (strpos(trim($iProvince),"  ")>0) {
	$iProvince=str_replace("  "," ",trim($iProvince));
}

if ($sb=='Submit') {
	if ($bbo=='') {
		$nProvinceIssued = new EBPLSProvince($dbLink,'false');
		$nProvinceIssued->search(NULL,$iProvince);
		$rResult = $nProvinceIssued->out;
		if (is_array($rResult)) {
			?>
			<body onload='javascript:alert ("Existing Province Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nProvinceIssued = new EBPLSProvince($dbLink,'false');
			$nProvinceIssued->setData(PROVINCE_DESC,addslashes($iProvince));
			$nProvinceIssued->setData(PROVINCE_DATE_REGISTERED,$datetoday);
			$nProvinceIssued->setData(PROVINCE_DATE_UPDATED,$datetoday);
			$nProvinceIssued->setData(UPDATED_BY,$usern);
			$nProvinceIssued->setData(BLGF_CODE,$iBLGFCode);
			$nProvinceIssued->add();
			$iProvince = "";
			$iBLGFCode = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nProvinceIssued = new EBPLSProvince($dbLink,'false');
		$nProvinceIssued->searchcomp($bbo, $iProvince);
		$ifexist = $nProvinceIssued->rcount;
		if ($ifexist >0) {
			?>
                        <body onload='javascript:alert ("Existing Province Found!!");'></body>
                        <?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nProvinceIssued = new EBPLSProvince($dbLink,'false');
			$nProvinceIssued->setData(PROVINCE_DESC,addslashes($iProvince));
			$nProvinceIssued->setData(PROVINCE_DATE_UPDATED,$datetoday);
			$nProvinceIssued->setData(UPDATED_BY,$usern);
			$nProvinceIssued->setData(BLGF_CODE,$iBLGFCode);
			$nProvinceIssued->update($bbo);
			$bbo="";
			$iProvince = "";
			$iBLGFCode = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$check1 = mysql_query("select * from ebpls_owner where owner_province_code = '$bbo'");
	$check1 = mysql_num_rows($check1);
	$check2 = mysql_query("select * from ebpls_business_enterprise where business_province_code = '$bbo'");
	$check2 = mysql_num_rows($check2);
	if ($check1 > 0 || $check2 >0) {
		?>
		<body onload='javascript:alert ("Cannot Delete. Record exist in other table(s).");'></body>
		<?
	} else {
		$nProvinceIssued = new EBPLSProvince($dbLink,'false');
		$nProvinceIssued->delete($bbo);
		$bbo="";
		?>
		<body onload='javascript:alert ("Record Deleted!!");'></body>
		<?
	}
}
if ($com == 'edit') {
	$nProvince = new EBPLSProvince($dbLink,'false');
	$nProvince->search($bbo,NULL);
	$nResult = $nProvince->out;
	$iProvince = $nResult[province_desc];
	$iBLGFCode = $nResult[blgf_code];
}
	include'html/eBPLS_province.html';

?>

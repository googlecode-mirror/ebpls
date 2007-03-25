<?php
include'includes/variables.php';
include'class/eBPLS.LGU.class.php';
include'class/eBPLS.Province.class.php';
include'lib/phpFunctions-inc.php';

if ($sb=='Submit') {
	if ($bbo=='') {
		$nLGU = new EBPLSLGU($dbLink,'false');
		$nLGU->searchcomp1($iLGUCode, $iLGUDescription, $iProvince);
		$rResult = $nLGU->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing LGU Code/LGU Name Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nLGU = new EBPLSLGU($dbLink,'false');
			$nLGU->setData(CITY_MUNICIPALITY_CODE,$iLGUCode);
			$nLGU->setData(CITY_MUNICIPALITY_DESC,$iLGUDescription);
			$nLGU->setData(CITY_MUNICIPALITY_DATE_REGISTERED,$datetoday);
			$nLGU->setData(CITY_MUNICIPALITY_DATE_UPDATED,$datetoday);
			$nLGU->setData(UPDATED_BY,$usern);
			$nLGU->setData(UPPER,$iProvince);
			$nLGU->setData(BLGF_CODE,$iBLGFCode);
			$nLGU->add();
			$iLGUCode = "";
			$iLGUDescription = "";
			$iBLGFCode = "";
			$iProvince = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nLGU = new EBPLSLGU($dbLink,'false');
                $nLGU->searchcomp2($bbo, $iLGUCode, $iLGUDescription, $iProvince);
                $rResult = $nLGU->rcount;
                if ($rResult > 0) {
                        ?>
                        <body onload='javascript: alert ("Existing LGU Code/LGU Name Found!!");'></body>
                        <?php
                } else {
			$datetoday = date("Y-m-d H:i:s");
			$nLGU = new EBPLSLGU($dbLink,'false');
			$nLGU->setData(CITY_MUNICIPALITY_CODE,$iLGUCode);
			$nLGU->setData(CITY_MUNICIPALITY_DESC,$iLGUDescription);
			$nLGU->setData(CITY_MUNICIPALITY_DATE_REGISTERED,$datetoday);
			$nLGU->setData(CITY_MUNICIPALITY_DATE_UPDATED,$datetoday);
			$nLGU->setData(UPDATED_BY,$usern);
			$nLGU->setData(UPPER,$iProvince);
			$nLGU->setData(BLGF_CODE,$iBLGFCode);
			$nLGU->update($bbo);
			 $r = mysql_query("update ebpls_owner set owner_province_code = '$iProvince' where owner_city_code = '$bbo'");         

			$bbo="";
			$iLGUCode = "";
                        $iLGUDescription = "";
                        $iBLGFCode = "";
                        $iProvince = "";
           
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$check1 = mysql_query("select * from ebpls_owner where owner_city_code = '$bbo'");
	$check1 = mysql_num_rows($check1);
	$check2 = mysql_query("select * from ebpls_business_enterprise where business_city_code = '$bbo'");
	$check2 = mysql_num_rows($check2);
	if ($check1 > 0 || $check2 >0) {
		?>
		<body onload='javascript:alert ("Cannot Delete. Record exist in other table(s).");'></body>
		<?
	} else {
		$nLGU = new EBPLSLGU($dbLink,'false');
		$nLGU->delete($bbo);
		$bbo="";
		?>
		<body onload='javascript:alert ("Record Deleted!!");'></body>
		<?
	}
}
if ($com == "edit") {
$nLGU = new EBPLSLGU($dbLink,'false');
$nLGU->search($bbo,NULL);
$nResult = $nLGU->out;
$iLGUCode = $nResult[city_municipality_code];
$iLGUDescription = $nResult[city_municipality_desc];
$iUpper = $nResult[upper];
$iBLGFCode = $nResult[blgf_code];
$nProvince = new EBPLSProvince($dbLink,'false');
$nProvince->search($nResult[upper],NULL);
$iResult = $nProvince->out;
$iProvince = $iResult[province_code];
}
include'html/eBPLS_lgu.html';

?>

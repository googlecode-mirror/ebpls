<?php
include'includes/variables.php';
include'class/eBPLS.ZIP.class.php';
include'class/eBPLS.LGU.class.php';
include'lib/phpFunctions-inc.php';


if ($sb=='Submit') {
	if ($bbo=='') {
		$nZIP = new EBPLSZIP($dbLink,'false');
		$nZIP->searchcomp1($iZIP, $iLGU);
		$rResult = $nZIP->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript: alert ("Existing ZIP Code/LGU Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nZIP = new EBPLSZIP($dbLink,'false');
			$nZIP->setData(ZIP_CODE,$iZIP);
			$nZIP->setData(ZIP_DESC,$iZIP);
			$nZIP->setData(ZIP_REGISTERED,$datetoday);
			$nZIP->setData(ZIP_DATE_UPDATED,$datetoday);
			$nZIP->setData(UPDATED_BY,$usern);
			$nZIP->setData(G_ZONE,'');
			$nZIP->setData(UPPER,$iLGU);
			$nZIP->setData(BLGF_CODE,'');
			$nZIP->add();
			$iZIP = "";
			$iLGU = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nZIP = new EBPLSZIP($dbLink,'false');
                $nZIP->searchcomp($bbo,$iZIP);
                $rResult = $nZIP->rcount;
                if ($rResult > 0) {
                        ?>
                        <body onload='javascript: alert ("Existing ZIP Code Found!!");'></body>
                        <?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nZIP = new EBPLSZIP($dbLink,'false');
			$nZIP->setData(ZIP_CODE,$iZIP);
			$nZIP->setData(ZIP_DESC,$iZIP);
			$nZIP->setData(ZIP_REGISTERED,$datetoday);
			$nZIP->setData(ZIP_DATE_UPDATED,$datetoday);
			$nZIP->setData(UPDATED_BY,$usern);
			$nZIP->setData(G_ZONE,'');
			$nZIP->setData(UPPER,$iLGU);
			$nZIP->setData(BLGF_CODE,'');
			$nZIP->update($bbo);
			$bbo="";
			$iZIP = "";
			$iLGU = "";

			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$check1 = mysql_query("select * from ebpls_owner where owner_zip_code = '$bbo'");
	$check1 = mysql_num_rows($check1);
	$check2 = mysql_query("select * from ebpls_business_enterprise where business_zip_code = '$bbo'");
	$check2 = mysql_num_rows($check2);
	if ($check1 > 0 || $check2 >0) {
		?>
		<body onload='javascript:alert ("Cannot Delete. Record exist in other table(s).");'></body>
		<?
	} else {
		$nZIP = new EBPLSZIP($dbLink,'false');
		$nZIP->delete($bbo);
		$bbo="";
		?>
		<body onload='javascript:alert ("Record Deleted!!");'></body>
		<?
	}
}
if ($com == "edit") {
$nZIP = new EBPLSZIP($dbLink,'false');
$nZIP->search($bbo,NULL);
$nResult = $nZIP->out;
$iZIP = $nResult[zip_code];
$nLGU = new EBPLSLGU($dbLink,'false');
$nLGU->search($nResult[upper],NULL);
$iResult = $nLGU->out;
$iLGU = $iResult[city_municipality_code];
}
include'html/eBPLS_zip.html';

?>

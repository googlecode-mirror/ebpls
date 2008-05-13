<?php
include'includes/variables.php';
include'class/eBPLS.industry.class.php';

$sb = isset($sb) ? $sb : ''; //2008.05.13 
$bbo = isset($bbo) ? $bbo : '';
$confx = isset($confx) ? $confx : '';
$com = isset($com) ? $com : '';	

if ($sb=='Submit') {
	if ($bbo=='' ) {

		$nIndustry = new EBPLSIndustry($dbLink,'false');
		$nIndustry->search($nCode,NULL);
		$rResult = $nIndustry->out;
		if (is_array($rResult)) {
			?>
			<body onload='ExistRec();'></body>
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
			$nCode='';
                        $nType='';
			 ?>
                        <body onload='AddRec();'></body>
                        <?php
		}
		
	} else {

		$nIndustry = new EBPLSIndustry($dbLink,'false');
                $nIndustry->searchedit($nCode,$bbo,"ebpls_industry_sector","industry_sector_code");
                $rResult = $nIndustry->outnumrow;
                if ($rResult) {
                        ?>
                        <body onload='ExistRec();'></body>
                        <?php
                } else {


		$datetoday = date("Y-d-m H:i:s");
		$nIndustry = new EBPLSIndustry($dbLink,'false');
		$nIndustry->setData(INDUSTRY_SECTOR_CODE,$nCode);
		$nIndustry->setData(INDUSTRY_SECTOR_DESC,$nType);
		$nIndustry->setData(INDUSTRY_SECTOR_DATE_UPDATED,$datetoday);
		$nIndustry->setData(UPDATED_BY,$ThUserData[username]);
		$nIndustry->update($bbo);
		$bbo="";
                $com='edit';

			 ?>
                        <body onload='UpRec();'></body>
                        <?php


		}
	}
}elseif ($confx==1) {
	$nIndustry = new EBPLSIndustry($dbLink,'false');
	$nIndustry->delete($bbo);
	$bbo="";
        $nCode='';
        $nType='';
	?>
	<body onload='DelRec();'></body>
	<?
}elseif ($confx=='cancel') {
	
	$bbo="";
    $nCode='';
    $nType='';
 }

if ($com=='edit') {
$nIndustry = new EBPLSIndustry($dbLink,'false');
$nIndustry->search($bbo,NULL);
$nResult = $nIndustry->out;
$industry_id = $nResult[industry_sector_code];
$nCode = $nResult[industry_sector_code];
$nType = $nResult[industry_sector_desc];
}
include'html/industry.html';
include'pager/industry_page.php';
?>

<?php
include'includes/variables.php';
include'class/eBPLS.occupancy.class.php';

$sb = isset($sb) ? $sb : ''; //2008.05.13 
$bbo = isset($bbo) ? $bbo : '';
$confx = isset($confx) ? $confx : '';
$com = isset($com) ? $com : '';	

if ($sb=='Submit') {

	if ($bbo=='') {
		$nOccupancy = new EBPLSOccupancy($dbLink,'false');
		$nOccupancy->searchcomp($nCode,$nType);
		$rResult = $nOccupancy->rcount;
		
		if ($rResult > 0) {
			?>
			<body onload='ExistRec();'></body>
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
			$nCode = '';
			$nType = '';
			 ?>
                        <body onload='AddRec();'></body>
                        <?php
		}
		
	} else {

		 $nOccupancy = new EBPLSOccupancy($dbLink,'false');
                $nOccupancy->searchedit($nCode,$bbo,"ebpls_occupancy_type","occupancy_type_code");
                $rResult = $nOccupancy->outnumrow;
                if ($rResult>0) {
                        ?>
                        <body onload='ExistRec();'></body>
                        <?php
                } else {


		$datetoday = date("Y-d-m H:i:s");
		$nOccupancy = new EBPLSOccupancy($dbLink,'false');
		$nOccupancy->setData(OCCUPANCY_TYPE_CODE,$nCode);
		$nOccupancy->setData(OCCUPANCY_TYPE_DESC,$nType);
		$nOccupancy->setData(OCCUPANCY_TYPE_DATE_UPDATED,$datetoday);
		$nOccupancy->setData(UPDATED_BY,$ThUserData[username]);
		$nOccupancy->update($bbo);
		$bbo="";
                $com='edit';
		 ?>
                        <body onload='UpRec();'></body>
                        <?php
		}
	}

}elseif ($confx==1) {
	
		$cntrec= SelectDataWhere($dbtype,$dbLink,"ebpls_occupancy_type a, ebpls_business_enterprise b","where a.occupancy_type_code='$bbo' and b.business_occupancy_code=a.occupancy_type_code ");
		$cnt = NumRows($dbtype,$cntrec);
		
		if ($cnt>0) {
?>
       <body onload='ExistOther();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_noccupancy&is_desc=ASC";'></body>
<?php			
		} else {
		$deact = DeleteQuery($dbtype,$dbLink,"ebpls_occupancy_type",
				     "occupancy_type_code='$bbo'");
	?>
       <body onload='DelRec();parent.location="index.php?part=4&class_type=Preference&selMode=ebpls_noccupancy&is_desc=ASC";'></body>
<?php	
		}  
	
	$bbo="";
        $nCode='';
        $nType='';
	
	

}
if ($com=='edit' || $confx=='cancel') {

$nOccupancy = new EBPLSOccupancy($dbLink,'false');
$nOccupancy->search($bbo,NULL);
$nResult = $nOccupancy->out;
$occupancy_id = $nResult[banks_id];
$nCode = $nResult[occupancy_type_code];
$nType = $nResult[occupancy_type_desc];
}
include'html/occupancy.html';
include'pager/occupancy_page.php';

?>

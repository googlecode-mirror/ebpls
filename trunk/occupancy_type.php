<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include'class/occupancy.class.php';
if ($orderbyasdes==1) {
	$orderbyasdes=0;
	$ascdesc='asc';
} else {
	$orderbyasdes=1;
	$ascdesc='desc';
}
//if ($ascdesc1=='') {
//	$ascdesc1=$ascdesc;
//} else {
//	$ascdesc=$ascdesc1;
//}
if ($Submit=='Submit') {		
	if ($com=='edit') {
		$nQuery = "update $ebpls_dbn set $ebpls_foc='$ebpls_foc1', $ebpls_fod='$ebpls_fod1',$ebpls_dupdated=now(), $ebpls_updatedby='$usern' where $ebpls_foc='$bcode'";
		$nOccupancy = new OCCUPANCY;
		$nOccupancy->UpdateQuery($ebpls_dbn, $nQuery);
	} else {
		$nQuery = "select * from $ebpls_dbn where $ebpls_foc='$ebpls_foc1'";
		$nOccupancy = new OCCUPANCY;
		$nOccupancy->Query1($nQuery);
		$nRecord = $nOccupancy->dbResultOut;
		if ($nRecord==0) {
			$nQuery = "insert into $ebpls_dbn values ('$ebpls_foc1', '$ebpls_fod1',now(),now(),'$usern')";
			$nOccupancy = new OCCUPANCY;
			$nOccupancy->InsertQuery($ebpls_dbn, $nQuery);
		}
	$bcode='';
	}
}elseif ($com=='delete') {
	$nQuery = "delete from $ebpls_dbn where $ebpls_foc='$bcode'";
	$nOccupancy = new OCCUPANCY;
	$nOccupancy->DeleteQuery($ebpls_dbn, $nQuery);
}elseif ($com=='edit') {
	$nQuery = "select * from $ebpls_dbn where $ebpls_foc='$bcode'";
	$nOccupancy = new OCCUPANCY;
	$nOccupancy->FetchRow($nQuery);
	$get_info=$nOccupancy->dbResultOut;
}
$nQuery = "select * from $ebpls_dbn where $ebpls_foc='$bcode'";
$nOccupancy = new OCCUPANCY;
$nOccupancy->FetchRow($nQuery);
$nRecord = $nOccupancy->dbResultOut;
$bn = $nRecord[1];
$data_item=0;
include'tablemenu-inc.php';

if ($selMode=='ebpls_noccupancy') {
$fld1 = 'occupancy_type_code'; 
$ovalue1='code';
$fld2 = 'occupancy_type_desc';
$ovalue2='desc';
	if ($wator=='') {
		$wator = 'occupancy_type_desc';
	}
} else {
$fld1 = 'industry_sector_code';
$ovalue1='code';
$fld2 = 'industry_sector_desc';
$ovalue2='desc';
	if ($wator=='') {
                $wator = 'industry_sector_desc';
        }

	
}
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
$fromr = abs((($pager * $max_resultsr) - $max_resultsr));
if ($ordervalue=='type') {
	$searchsqlr="select * from $ebpls_dbn order by $fld1 $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='head') {
	$searchsqlr="select * from $ebpls_dbn order by $fld2 $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from $ebpls_dbn order by $fld1 $ascdesc limit $fromr, $max_resultsr";
}
include'html/occupancy.html';
?>

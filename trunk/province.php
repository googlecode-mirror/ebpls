<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include'class/province.class.php';
if ($sb=='Submit') {
	
	
	$bcode = str_replace("  "," ",trim($bcode));
	
	$nQuery = "select * from $preft where $prefd='$bcode'";
	echo $nQuery;
	$nProvince = new Province;
	$nProvince->Query1($nQuery);
	$nRecord = $nProvince->dbResultOut;
	if ($nRecord==0) {
		$bname=strtoupper($bname);
		$sQuery = "select * from $preft where $prefc='$bname' and blgf_code='$blgf_codes'";
		$nProvince = new Province;
		$nProvince->Query1($sQuery);
		$chkDuplicate = $nProvince->dbResultOut;
		if ($chkDuplicate==0) {
			$dQuery = "insert into $preft values ('$bname', '$bname',now(),now(),'$usern', '$blgf_codes')";
			$nProvince = new Province;
			$nProvince->InsertQuery($preft, $dQuery);
			?>
			<body onLoad='javascript:alert("Record Successfully Saved");'></body>
			<?
 		} else {
?>
                <body onload='javascript:alert("Duplicate Record Found");'></body>
<?php

//	 		echo "<div align=center>DUPLICATE RECORD</div>";
 		}
	} elseif ($com=='edit') {
                $bname=strtoupper($bname);
                $sQuery = "update $preft set $prefd='$bname', blgf_code='$blgf_codes' where $prefc='$bbo'";
                $nProvince = new Province;
                $nProvince->UpdateQuery($preft, $sQuery);
				$blgf_codes='';
				?>
				<body onLoad='javascript:alert("Record Successfully Updated");'></body>
				<?
        } else {
?>
                <body onload='javascript:alert("Duplicate Code Record Found");'></body>
<?php

//	 		echo "<div align=center>DUPLICATE RECORD</div>";
 	}
 		$bcode='';
 		$bname='';
 		
}
//$bname='';
if ($confx==1) {

	$sQuery = "delete from $preft where $prefc='$bbo'";
	$nProvince = new Province;
	$nProvince->DeleteQuery($sQuery);
?>
        <body onload='javascript:alert("Record Deleted");'></body>
<?php
$confx="";
}
$nQuery = "select * from $preft where $prefd='$bcode'";
$nProvince = new Province;
$nProvince->FetchRow($nQuery);
$getRecord = $nProvince->dbResultOut;
$pn = $getRecord[1];
$blgf_codes=$getRecord[5];
//$uQuery = "select * from $prefu where $prefdc='$gf[5]'";
//$nProvince = new Province;
//$nProvince->FetchRow($uQuery);
//$getData = $nProvince->dbResultOut;
$data_item=0;
include'tablemenu-inc.php';
if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
                                                                                                                                                            
// Define the number of results per page
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$fromr = abs((($pager * $max_resultsr) - $max_resultsr));
                                                                                                                                                            
$searchsqlr="select * from $preft order by $prefd $ascdesc limit $fromr, $max_resultsr";
$cntsqlr = "select count(*) from $preft";

include'html/province.html';
$data_item=1;
include'tablemenu-inc.php';
?>

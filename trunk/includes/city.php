<?php
include'lib/phpFunctions-inc.php';
include'includes/variables.php';
include'class/city.class.php';
include'class/province.class.php';
if ($bcode=='') {
        $bcode=0;
}
if ($sb=='Submit') {
	$nQuery = "select * from $preft where $prefc = '$bcode'";
	$mcode=strtoupper($mcode);
        $mdesc=strtoupper($mdesc);
        $sname=strtoupper($sname);
	$nCity = new City;
	$nCity->Query1($nQuery);
	$nRecord = $nCity->dbResultOut;
	if ($nRecord==0) {
		$sQuery = "select * from $preft where $prefc='$mcode' and $prefd='$mdesc' and blgf_code='$blgf_codes' and upper='$sname'";
		$nCity = new City;
		$nCity->Query1($sQuery);
		$chkDuplicate = $nCity->dbResultOut;
		if ($chkDuplicate==0) {
			$nQuery = "insert into $preft values ('$mcode', '$mdesc', now(), now(), '$usern','$sname','$blgf_codes')";
			$nCity = new City;
			$nCity->InsertQuery($preft, $nQuery);
        	} else {
?>
                	<body onload='javascript:alert("Duplicate Record Found");'></body>
<?php

//	 		echo "<div align=center>DUPLICATE RECORD</div>";
 		}
	} elseif ($com=='edit') {
                $nQuery = "update $preft set $prefc='$mcode', $prefd='$mdesc', city_municipality_date_updated=now(), updated_by='$usern', upper='$sname', blgf_code='$blgf_codes' where $prefc='$bcode'";
                $nCity = new City;
                $nCity->UpdateQuery($preft, $nQuery);
		$blgf_codes='';
        } else {
?>
                	<body onload='javascript:alert("Duplicate Record Found");'></body>
<?php

//	 		echo "<div align=center>DUPLICATE RECORD</div>";
	}
	$bcode='';
}
if ($confx==1) {
	$nQuery = "delete from $preft where $prefc='$bcode'";
	$nCity = new City;
	$nCity->DeleteQuery($nQuery);
	$bcode='';
	$confx='';
?>
	<body onload='javascript:alert("Record Deleted");'></body>
<?php
}
$nQuery = "select * from $preft where $prefc='$bcode'";
$nCity = new City;
$nCity->FetchRow($nQuery);
$getRecord = $nCity->dbResultOut;
$mcode=$getRecord[0];
$mdesc=$getRecord[1];
$blgf_codes=$getRecord[6];
//$uQuery = "select * from $prefu where $prefdc='$gf[5]'";
//$nProvince = new Province;
//$nProvince->FetchRow($uQuery);
//$getData = $nProvince->dbResultOut;
$selectpref2 = $getRecord[5];
$data_item=0;
include'tablemenu-inc.php';
?>
<?php
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
                                                             
$deductme = 5;
$sep = '--';                                                  
if ($valuekey=='code') {
	$searchsqlr="select a.* , b.province_desc 
	from $preft a, $prefu b where a.upper=b.province_code 
	order by $prefc $ascdesc limit $fromr, $max_resultsr";
} elseif ($valuekey=='desc') {
	$searchsqlr="select a.*, b.province_desc
        from $preft a, $prefu b where a.upper=b.province_code
	order by $prefd $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select a.*, b.province_desc
        from $preft a, $prefu b where a.upper=b.province_code
	order by a.upper $ascdesc limit $fromr, $max_resultsr";
}
$cntsqlr = "select count(*) from $preft";
include'html/city.html'; 
$data_item=1;
include'tablemenu-inc.php';
?>

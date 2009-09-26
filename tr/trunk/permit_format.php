<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include'class/permit.format.class.php';
if ($Submit=='Submit') {		
	if ($permit_date<>'') {
		$permit_date1=1;
	} else {
		$permit_date1=0;
	}

//echo $permit_type."<br>";
	$nQuery = "select * from permit_templates where permit_type='$permit_type'";
	$nPermit = new PermitFormat;
	$nPermit->Query1($nQuery);
	$nRecord = $nPermit->dbResultOut;
	$nPermit->FetchRow($nQuery);
	$chkPermit = $nPermit->dbResultOut;
	if ($nRecord==0) {
		$nQuery = "insert into permit_templates values ('', '$permit_type','$usern',now(),'$permit_header',$permit_date1,$permit_sequence)";
		$nPermit = new PermitFormat;
		$nPermit->InsertQuery('permit_templates', $nQuery);
	} else {
		if ($com=='edit') {
			$sQuery = "update permit_templates set permit_type='$permit_type', user='$usern',date_entered=now(), permit_header='$permit_header', permit_date=$permit_date1, permit_sequence=$permit_sequence where tempid=$bcode";
			$nPermit = new PermitFormat;
                	$nPermit->UpdateQuery('permit_templates', $sQuery);
                	$permit_type='';
                	$permit_header='';
                	$permit_date1='';
                	$permit_sequence='';
                	$bcode='';
			$com='';
		} else {
			echo "<font color=red size=2><b><i>DUPLICATE RECORD</i></b></font>";
		}
	}
}

if ($com=='delete') {
	$nQuery = "delete from permit_templates where tempid=$bcode";
	$nPermit = new PermitFormat;
	$nPermit->DeleteQuery($nQuery);
	$bcode='';
	$com='';
}
include'tablemenu-inc.php';
if ($bcode<>'') {
$dQuery = "select * from permit_templates where tempid=$bcode";
$nPermit = new PermitFormat;
$nPermit->FetchRow($dQuery);
$getRecord = $nPermit->dbResultOut;
$permit_type=$getRecord[1];
$permit_header=$getRecord[4];
if ($getRecord[5]==1) {
	$permit_date1=1;
} else {
	$permit_date1=0;
}
$permit_sequence=$getRecord[6];
}
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
if ($ordervalue=='type') {                                                                                                               
	$searchsqlr="select * from permit_templates order by permit_type $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='head') {                                                                                                               
	$searchsqlr="select * from permit_templates order by permit_header $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='sequ') {                                                                                                               
	$searchsqlr="select * from permit_templates order by permit_sequence $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from permit_templates order by permit_type $ascdesc limit $fromr, $max_resultsr";
}
$cntsqlr = "select count(*) from permit_templates";
$result=mysql_query("select * from permit_templates") or die("11".mysql_error());
$totalcnt = mysql_num_rows($result);
if ($totalcnt==0) {
	print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
}
$resultr = mysql_query($searchsqlr)or die (mysql_error());
// Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
include'html/permit_format.html';
?>

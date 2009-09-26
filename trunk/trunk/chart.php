<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include'class/chart.class.php';
$preft = 'chart_accounts';
$prefd = 'caid';
$prefm = 'coa';

if ($sb=='Submit') {
	$sQuery = "select * from ebpls_buss_tfo where tfoid='$nAcctDesc'";
	$nChart = new Chart;
	$nChart->Query1($sQuery);
	$chkRecord = $nChart->dbResultOut;
        if ($chkRecord<>0) {
		$nChart->FetchRow($sQuery);
        	$adc = $nChart->dbResultOut[1];
       	} else {
                $adc = $nAcctDesc;
        }
	$sQuery = "select * from $preft where $prefd='$bbo'";
	$nChart->Query1($sQuery);
	$chkRecord = $nChart->dbResultOut;
	if ($chkRecord==0) {
		$nQuery = "select * from $preft where accnt_code='$acode'";
		$nChart = new Chart;
		$nChart->Query1($nQuery);
		$chkCode = $nChart->dbResultOut;
		
		if ($chkCode==0 and $acode<>'') {
			$adc = addslashes($adc);
			$nValues="'','$nAcctDesc', '$adc', '$acode', '$atype', '$usern', now()";
			$nNewChart = new Chart;
			$nNewChart->InsertQuery('chart_accounts', '', $nValues);
			$bbo='';
			?>
                 <body onload='javascript:AddRec();'></body>
<?php
		} else {
?>
		<body onload='javascript:ExistRec();'></body>
<?php
		}	
	} else {
		$adc = addslashes($adc);
		$nQuery = "update $preft set tfoid='$nAcctDesc', tfodesc = '$adc',
                        accnt_code='$acode',accnt_type='$atype',
                        input_by='$usern',date_modified=now()
                        where $prefd='$bbo'";
		$nChart->UpdateQuery('chart_accounts', $nQuery);
		$bbo='';
		?>
		 <body onload='javascript:UpRec();'></body>
<?php
	}
$bbo='';
//$bname='';
}elseif ($confx==1) {
	$nWhere = "caid = '$bbo'";
	$nChart = new Chart;
	$nChart->DeleteQuery('chart_accounts', $nWhere); 
	$bbo=0;
}
$nQuery = "select * from $preft where $prefd='$bbo'";
$nChart = new Chart;
$nChart->FetchRow($nQuery);
$nRecord = $nChart->dbResultOut;
$ad = $nRecord[1];
$ac = $nRecord[3];
$at = $nRecord[4];
if ($at=='CREDIT') {
		$dat1='selected';
} else {
		$dat='selected';
}
include'html/chart.html';
?>

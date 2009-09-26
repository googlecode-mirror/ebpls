<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include'class/ctc.settings.class.php';
if ($Submit=='Submit') {
	$nQuery = "select * from ebpls_ctc_interest";	
	$nCTC = new CTC;
	$nCTC->Query1($nQuery);
	$Record = $nCTC->dnResultOut;
	if ($Record==0) {
		$nValues = "'', 'Individual',$individual_interest_rate,$individual_ceiling_rate,now(),'$usern'";
		$nCTC = new CTC;
		$nCTC->InsertQuery('ebpls_ctc_interest','',$nValues);
		$nValues = "'', 'Corporate',$corporate_interest_rate,$corporate_ceiling_rate,now(),'$usern'";
                $nCTC = new CTC;
                $nCTC->InsertQuery('ebpls_ctc_interest','',$nValues);
	} else {
		$nValues = "update ebpls_ctc_interest set interest_rate=$individual_interest_rate, ceiling_rate=$individual_ceiling_rate, modified_date=now(),updated_by='$usern' where ctc_type='Individual'";
                $nCTC = new CTC;
                $nCTC->InsertQuery('ebpls_ctc_interest',$nValues);
                $nValues = "update ebpls_ctc_interest set interest_rate=$corporate_interest_rate, ceiling_rate=$corporate_ceiling_rate ,modified_date=now(),updated_by='$usern' where ctc_type='Corporate'";
                $nCTC = new CTC;
                $nCTC->InsertQuery('ebpls_ctc_interest','',$nValues);
	}
	print "<div align=center><font color=red><b><i>Save Successfully</i></b></font></div>";
}
include'tablemenu-inc.php';
$nValues = "select * from ebpls_ctc_interest where ctc_type='Individual'";
$nCTC = new CTC;
$nCTC->FetchRow($nValues);
$getinfo1 = $nCTC->dbResultOut;
$nValues = "select * from ebpls_ctc_interest where ctc_type='Corporate'";
$nCTC = new CTC;
$nCTC->FetchRow($nValues);
$getinfo2 = $nCTC->dbResultOut;
$individual_interest_rate=$getinfo1[2];
$individual_ceiling_rate=$getinfo1[3];
$corporate_interest_rate=$getinfo2[2];
$corporate_ceiling_rate=$getinfo1[3];
include'html/ctc_interest.html';
?>

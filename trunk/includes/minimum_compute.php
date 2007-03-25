<?php
		if ($totind<$getd[min_amt]) {
			$totind=$getd[min_amt];
			$compvalrange=$getd[min_amt];
			$usemin = 'Replaced with Minimum Amount ';
			$compval=$getd[min_amt];
		}
$getd[tfodesc] = addslashes($getd[tfodesc]);
$chkintfo = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo",
			"where tfodesc='$getd[tfodesc]'");
	$chkintfo = FetchArray($dbtype,$chkintfo);
	if ($chkintfo[taxfeetype]==1) {
	$tottax=$tottax+$totind;
	}

$getd[tfodesc] = stripslashes($getd[tfodesc]);	
?>

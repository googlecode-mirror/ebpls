<?php
		if ($totind<$getd['defamt']) {
			$totind=$getd['defamt'];
			$compvalrange=$getd['defamt'];
			$usemin = 'Replaced with Minimum Amount ';
			$compval=$getd['defamt'];
		}
$getd['tfodesc'] = addslashes($getd['tfodesc']);
$chkintfo = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo",
			"where tfodesc='$getd[tfodesc]'");
	$chkintfo = FetchArray($dbtype,$chkintfo);
	if ($chkintfo['taxfeetype']==1) {
	$tottax=$tottax+$totind;
	}

$getd['tfodesc'] = stripslashes($getd['tfodesc']);	
?>

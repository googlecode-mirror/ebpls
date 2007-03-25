<?php
                                                                                                
$getd[tfodesc] = addslashes($getd[tfodesc]);
$chkintfo = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo","where tfodesc='$getd[tfodesc]'","");
$chkintfo = FetchArray($dbtype,$chkintfo);
	if ($chkintfo[taxfeetype]<>1 and $totind==0) {
		$totfeeexempt=$totfeeexempt+$origfee[0];
	
	}else	{
		
		$feecompute=$feecompute+$totind;
	}
$chkintfo = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo","where tfodesc like 'mayor%'","");
$chkintfo = FetchArray($dbtype,$chkintfo);
	if ($chkintfo[taxfeetype]==1 and $totind==0) {
		$totfeeexempt=$totfeeexempt+$origfee[0];
	
	}else	{
		
		$feecompute=$feecompute+$totind;
	}
$getd[tfodesc] = stripslashes($getd[tfodesc]);
?>

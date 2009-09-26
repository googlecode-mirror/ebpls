<?php
include'includes/variables.php';
include'class/eBPLS.Signatories.class.php';
include'lib/phpFunctions-inc.php';


if ($sb=='Submit') {
	$iName = addslashes($iName);
	$iPosition = addslashes($iPosition);
	$iOffice = addslashes($iOffice);
	if ($bbo=='') {
		$nSign = new EBPLSSign($dbLink,'false');
		$nSign->searchcomp1($iName);
		$rResult = $nSign->rcount;
		if ($rResult > 0) {
			?>
			<body onload='javascript:alert ("Existing Record Found!!"); _FRM.iName.focus();'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nSign = new EBPLSSign($dbLink,'false');
			$nSign->setData(SIGN_ID,'');
			$nSign->setData(GS_NAME,$iName);
			$nSign->setData(GS_POS,$iPosition);
			$nSign->setData(GS_OFFICE,$iOffice);
			$nSign->add();
			$iName = "";
			$iPosition = "";
			$iOffice = "";
			?>
			<body onload='javascript:alert ("Record Successfully Added to the database!!"); _FRM.iName.focus();'></body>
			<?
		}
		
	} else {
		$nSign = new EBPLSSign($dbLink,'false');
		$nSign->searchcomp($bbo,$iName);
		$rResult = $nSign->rcount;
		if ($rResult[0] > 0) {
			?>
			<body onload='javascript:alert ("Existing Record Found!! _FRM.iName.focus();");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nSign = new EBPLSSign($dbLink,'false');
			$nSign->setData(SIGN_ID,'');
			$nSign->setData(GS_NAME,$iName);
			$nSign->setData(GS_POS,$iPosition);
			$nSign->setData(GS_OFFICE,$iOffice);
			$nSign->update($bbo);
			$bbo="";
			$iName = "";
			$iPosition = "";
			$iOffice = "";
			?>
			<!--<body onload='javascript:alert ("Record Successfully Updated!!");'></body>-->
			<body onload='javascript:alert ("Data is successfully updated!!"); _FRM.iName.focus();'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$nSign = new EBPLSSign($dbLink,'false');
	$nSign->delete($bbo);
	$bbo="";
	?>
	<body onload='javascript:alert ("Record Deleted!!"); _FRM.iName.focus();'></body>
	<?
}
if ($com == "edit") {
	$nSign = new EBPLSSign($dbLink,'false');
	$nSign->search($bbo,NULL);
	$nResult = $nSign->out;
	$iName = stripslashes($nResult[gs_name]);
	$iPosition = stripslashes($nResult[gs_pos]);
	$iOffice = stripslashes($nResult[gs_office]);
}
include'html/eBPLS_signatories.html';

?>

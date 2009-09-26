<?php
include'includes/variables.php';
include'class/eBPLS.Requirements.class.php';
include'lib/phpFunctions-inc.php';
if ($ireqindicator == "ON") {
	$ireqindicator = 1;
} else {
	$ireqindicator = "";
}
if ($sb=='Submit') {
	if ($bbo=='') {
		$nRequirements = new EBPLSRequirements($dbLink,'false');
		$nRequirements->searchcomp(NULL,$iDesc,$iPermitType);
		$rResult = $nRequirements->rcount;
		if ($rResult[0] > 0) {
			?>
			<body onload='javascript:alert ("Existing Requirement Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nRequirements = new EBPLSRequirements($dbLink,'false');
			$nRequirements->setData(REQDESC,$iDesc);
			$nRequirements->setData(RECSTATUS,"A");
			$nRequirements->setData(REQINDICATOR,$ireqindicator);
			$nRequirements->setData(DATECREATED,$datetoday);
			$nRequirements->setData(REVDATE,$datetoday);
			$nRequirements->setData(PERMIT_TYPE,$iPermitType);
			$nRequirements->add();
			$iDesc = "";
			$iPermitType = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nRequirements = new EBPLSRequirements($dbLink,'false');
		$nRequirements->searchcomp($bbo,$iDesc,$iPermitType);
		$rResult = $nRequirements->rcount;
		if ($rResult[0] > 0) {
			?>
			<body onload='javascript:alert ("Existing Requirement Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nRequirements = new EBPLSRequirements($dbLink,'false');
			$nRequirements->setData(REQDESC,$iDesc);
			$nRequirements->setData(RECSTATUS,"A");
			$nRequirements->setData(REQINDICATOR,$ireqindicator);
			$nRequirements->setData(REVDATE,$datetoday);
			$nRequirements->setData(PERMIT_TYPE,$iPermitType);
			$nRequirements->update($bbo);
			$bbo="";
			$iDesc = "";
			$iPermitType = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$nRequirements = new EBPLSRequirements($dbLink,'false');
	$nRequirements->delete($bbo);
	$bbo="";
	?>
	<body onload='javascript:alert ("Record Successfully Deleted!!");'></body>
	<?
}
if ($conact==1) {
	
	$nRequirements = new EBPLSRequirements($dbLink,'false');
	$datetoday = date("Y-m-d H:i:s");
	$nRequirements->searchexist($bbo);
	if ($nRequirements->rcount > 0) {
		?>
		<body onload='javascript:alert ("Cannot Delete, Record exist in other table!!");'></body>
		<?
	} else {
		$nRequirements->setData(RECSTATUS,"A");
		$nRequirements->update($bbo);
		$bbo="";
		?>
		<body onload='javascript:alert ("Record Successfully Activated!!");'></body>
		<?
	}
}
if ($com == "edit") {
	$nRequirements = new EBPLSRequirements($dbLink,'false');
	$nRequirements->search($bbo,NULL);
	$nResult = $nRequirements->out;
	$iDesc = $nResult[reqdesc];
	$iPermitType = $nResult[permit_type];
	$ireqindicator = $nResult[reqindicator];
	if ($ireqindicator == '1') {
		$is_checked = "CHECKED";
	} else {
		$is_checked = "";
	}
}
include'html/eBPLS_requirements.html';

?>

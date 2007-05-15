<?php
include'includes/variables.php';
include'class/eBPLS.TFO.class.php';
include'lib/phpFunctions-inc.php';
if ($itfoindicator == "ON") {
	$itfoindicator1 = '1';
} else {
	$itfoindicator1 = '0';
}
if ($sb=='Submit') {
	$iDesc = addslashes($iDesc);
	if ($bbo=='') {
		$nRequirements = new EBPLSTFO($dbLink,'false');
		$nRequirements->searchcomp(NULL,$iDesc);
		$rResult = $nRequirements->rcount;
		if ($rResult[0] > 0) {
			?>
			<body onload='javascript:alert ("Existing Tax, Fee and Other Charges Found!!");'></body>
			<?php
		} else {
			if ($itfoindicator == 1 and $iDefAmount < 1) {
				?>
				<body onload='javascript:alert ("Enter Valid Default Amount!!");'></body>
				<?
			} else {
			$datetoday = date("Y-m-d H:i:s");
			$nRequirements = new EBPLSTFO($dbLink,'false');
			$nRequirements->setData(TFODESC,$iDesc);
			$nRequirements->setData(TFOSTATUS,"A");
			$nRequirements->setData(TFOINDICATOR,$itfoindicator1);
			$nRequirements->setData(TAXFEETYPE,$iTFOType);
			$nRequirements->setData(DATECREATED,$datetoday);
			$nRequirements->setData(DEFAMT,$iDefAmount);
			$nRequirements->setData(OR_PRINT,$iYearType);
			$nRequirements->setData(COUNTER,$iNoYear);
			$nRequirements->add();
			$iDesc = "";
			$itfoindicator = "";
			$iTFOType = "";
			$iDefAmount = "";
			$iYearType = "";
			$iNoYear = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
			}
		}
		
	} else {
		$nRequirements = new EBPLSTFO($dbLink,'false');
		$nRequirements->searchcomp($bbo,$iDesc,$iPermitType);
		$rResult = $nRequirements->rcount;
		if ($rResult[0] > 0) {
			?>
			<body onload='javascript:alert ("Existing Tax, Fee and Other Charges Found!!");'></body>
			<?php
		} else {
			if ($itfoindicator == 1 and $iDefAmount < 1) {
				?>
				<body onload='javascript:alert ("Enter Valid Default Amount!!");'></body>
				<?
			} else {
			$datetoday = date("Y-m-d H:i:s");
			$nRequirements = new EBPLSTFO($dbLink,'false');
			$nRequirements->setData(TFODESC,$iDesc);
			$nRequirements->setData(TFOSTATUS,"A");
			$nRequirements->setData(TFOINDICATOR,$itfoindicator1);
			$nRequirements->setData(TAXFEETYPE,$iTFOType);
			$nRequirements->setData(DATECREATED,$datetoday);
			$nRequirements->setData(DEFAMT,$iDefAmount);
			$nRequirements->setData(OR_PRINT,$iYearType);
			$nRequirements->setData(COUNTER,$iNoYear);
			$nRequirements->update($bbo);
			$bbo="";
			$iDesc = "";
			$itfoindicator = "";
			$iTFOType = "";
			$iDefAmount = "";
			$iYearType = "";
			$iNoYear = "";
			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
			}
		}
	}
}elseif ($confx==1) {
	$nRequirements = new EBPLSTFO($dbLink,'false');
	$datetoday = date("Y-m-d H:i:s");
	$nRequirements->checksearch($bbo);
	$nResult = $nRequirements->rcount;
	if ($nResult > 0) {
		?>
		<body onload='javascript:alert ("Cannot Delete, Existing Record found in other table!!");'></body>
		<?
	} else {
		$nRequirements = new EBPLSTFO($dbLink,'false');
		$nRequirements->delete($bbo);
		?>
		<body onload='javascript:alert ("Record Successfully Deleted!!");'></body>
		<?
	}
		$bbo="";
}
if ($com == "edit") {
	$nRequirements = new EBPLSTFO($dbLink,'false');
	$nRequirements->search($bbo,NULL);
	$nResult = $nRequirements->out;
	$iDesc = $nResult[tfodesc];
	$iTFOType = $nResult[taxfeetype];
	$iDefAmount = $nResult[defamt];
	$iYearType = $nResult[or_print];
	$iNoYear = $nResult[counter];
	$itfoindicator = $nResult[tfoindicator];
}
include'html/eBPLS_tfo.html';

?>

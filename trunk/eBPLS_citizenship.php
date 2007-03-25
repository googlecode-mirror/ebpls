<?php
include'includes/variables.php';
include'class/eBPLS.citizenship.class.php';
if ($sb=='Submit') {
	if ($bbo=='') {
		$nCitizenship = new EBPLSCitizenship($dbLink,'false');
		$nCitizenship->search(NULL,$nCode);
		$rResult = $nCitizenship->out;
		if (is_array($rResult)) {
			?>
			<body onload='ExistRec();'></body>
			<?php
		} else {
			$datetoday = date("Y-d-m H:i:s");
			$nCitizenship = new EBPLSCitizenship($dbLink,'false');
			$nCitizenship->setData(CIT_DESC,$nCode);
			$nCitizenship->add();
			 ?>
                        <body onload='AddRec();'></body>
                        <?php
		}
		
	} else {
		$nCitizenship = new EBPLSCitizenship($dbLink,'false');
                $nCitizenship->search(NULL,$nCode);
                $rResult = $nCitizenship->out;
                if (is_array($rResult)) {
                        ?>
                        <body onload='ExistRec();'></body>
                        <?php
                } else {


		$datetoday = date("Y-d-m H:i:s");
		$nCitizenship = new EBPLSCitizenship($dbLink,'false');
		$nCitizenship->setData(CIT_DESC,$nCode);
		$nCitizenship->update($bbo);
		$bbo="";
		 ?>
                        <body onload='UptRec();'></body>
                        <?php
		}
	}
}elseif ($confx==1) {
	$check1 = mysql_query("select * from ebpls_owner where owner_citizenship = '$bbo'");
	$check1 = mysql_num_rows($check1);
	if ($check1 > 0) {
		?>
		<body onload='javascript:alert ("Cannot Delete. Record exist in other table(s).");'></body>
		<?
	} else {
		$nCitizenship = new EBPLSCitizenship($dbLink,'false');
		$nCitizenship->delete($bbo);
		$bbo="";
		?>
		<body onload='DelRec();'></body>
		<?
	}
}
$nCitizenship = new EBPLSCitizenship($dbLink,'false');
$nCitizenship->search($bbo,NULL);
$nResult = $nCitizenship->out;
$industry_id = $nResult[cit_id];
$nCode = $nResult[cit_desc];
include'html/citizenship.html';
include'pager/citizenship_page.php';
?>

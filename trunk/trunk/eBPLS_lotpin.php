<?php
include'includes/variables.php';
include'class/eBPLS.Lot.Pin.class.php';
include'class/eBPLS.Province.class.php';
include'class/eBPLS.LGU.class.php';
include'lib/phpFunctions-inc.php';

if ($sms_send == "ON") {
	$smssend = '1';
} else {
	$smssend = '0';
}
if ($sb=='Submit') {
	if ($bbo=='') {
		$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
		$nAnnounce->search(NULL, $announcements);
		$rResult = $nAnnounce->rcount;
		echo $rResult[0];
		if ($rResult[0] > 0) {
			?>
			<body onload='javascript: alert ("Existing Announcement Found!!");'></body>
			<?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
			$nAnnounce->setData(ANNOUNCEMENT,$announcements);
			$nAnnounce->setData(ANNOUNCED_BY,$announced_by);
			$nAnnounce->setData(DATE_MODIFIED,$datetoday);
			$nAnnounce->setData(MODIFIED_BY,$usern);
			$nAnnounce->setData(SMS_SEND,$smsend);
			$nAnnounce->add();
			$announcements = "";
			$announced_by = "";
			$sms_send = "";
			?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
		
	} else {
		$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
                $nAnnounce->searchcomp($bbo,$announcements);
                $rResult = $nAnnounce->rcount;
                if ($rResult > 0) {
                        ?>
                        <body onload='javascript: alert ("Existing Announcement Found!!");'></body>
                        <?php
		} else {
			$datetoday = date("Y-m-d H:i:s");
			$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
			$nAnnounce->setData(ANNOUNCEMENT,$announcements);
			$nAnnounce->setData(ANNOUNCED_BY,$announced_by);
			$nAnnounce->setData(DATE_MODIFIED,$datetoday);
			$nAnnounce->setData(MODIFIED_BY,$usern);
			$nAnnounce->setData(SMS_SEND,$smsend);
			$nAnnounce->update($bbo);
			$announcements = "";
			$announced_by = "";
			$sms_send = "";
			$bbo = "";

			?>
			<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
			<?
		}
	}
}elseif ($confx==1) {
	$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
	$nAnnounce->delete($bbo);
	$bbo="";
	?>
	<body onload='javascript:alert ("Record Deleted!!");'></body>
	<?
}
if ($com == "edit") {
$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
$nAnnounce->getLgu();
$nAnnounce->search($bbo,NULL);
$nResult = $nAnnounce->out;
$announcements = $nResult[announcements];
$announced_by = $nResult[announced_by];
$sms_checked = $nResult[sms_send];
}
$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
$nAnnounce->getLgu();
$Pref = $nAnnounce->out;
$iProv = $Pref[lguprovince];
$iLgu = $Pref[lguname];
$nProvince = new EBPLSProvince($dbLink,'false');
$nProvince->search($iProv,NULL);
$getProvince = $nProvince->out[province_desc];
$nLGU = new EBPLSLGU($dbLink,'false');
$nLGU->search($iLgu,NULL);
$getLGU = $nLGU->out[city_municipality_desc];
include'html/eBPLS_lotpin.html';

?>

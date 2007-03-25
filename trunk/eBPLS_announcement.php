<?php
include'includes/variables.php';
include'class/eBPLS.Announcement.class.php';
include'lib/phpFunctions-inc.php';

if ($sms_send == "ON") {
	$smssend = '1';
} else {
	$smssend = '0';
}
if ($sb=='Submit') {
	$announcements=addslashes($announcements);
	$announced_by = addslashes($announced_by);
	if ($bbo=='') {
		$nAnnounce = new EBPLSAnnouncement($dbLink,'false');
		$nAnnounce->search(NULL, $announcements);
		$rResult = $nAnnounce->rcount;
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
if ($sms_send==1) {
 $msgcom = 'Annoucement:'.$announcements.'-'.$announced_by;

                $check1 = mysql_query("select * from sms_archive
                                where msg='$msgcom'") or die(mysql_error());
                $check1 = mysql_num_rows($check1);
                        if ($check1==0 || $check1=='') {
                                $getnum = mysql_query ("select owner_gsm_no from ebpls_owner
                                        where owner_gsm_no <>''");
                                while ($sendit = mysql_fetch_row($getnum))
                                {
                                $owner_gsm_no = $sendit[0];
                                                if (strlen($owner_gsm_no)==13) {
                                                        $prefix = substr($owner_gsm_no, 0,4);
                                                        if ($prefix=='+639') {
                                                                $cell=substr($owner_gsm_no,1) ;
                                                                if (!is_numeric($cell)) {
                                                                $blak = 1;
                                                                } else {
                                                                $blak = 0;
                                                                }
                                                        } else {
                                                           $blak = 1;
                                                        }
                                                } else {
                                                        $blak=1;
                                                }

                                        if ($blak==0) {
                                        $sendna = mysql_query("insert into sms_send
                                        values ('','$sendit[0]', '$msgcom', 1,
                                                now())") or die ("Insert Error: SMS Send");
                                              
                                        }
                                }
                        }

}

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
$nAnnounce->search($bbo,NULL);
$nResult = $nAnnounce->out;
$announcements = stripslashes($nResult[announcements]);
$announced_by = stripslashes($nResult[announced_by]);
$sms_checked = $nResult[sms_send];
if ($sms_checked == '1'){
	$is_checked = "CHECKED";
} else {
	$is_checked = "";
}
}
include'html/eBPLS_announcement.html';

?>

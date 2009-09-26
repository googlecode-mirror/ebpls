<?php
//$x=0;
//while ($x<1) {
	$s=shell_exec('/usr/local/bin/gsmsmsstore -b 115200 -s /dev/ttyS0 -t SM -l 2>&1 > msg.txt');
//	$x=0;
//}
	
?>
	<body onload='parent.location="sms_rec.php":'></body>

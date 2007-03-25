<?php
   //get messages to send from annoucement
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                 
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
include("includes/variables.php");
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

                                                                                                 
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;

?>
<title>SMS Sender </title>
<div align = center>
<table border=0>
<td>
<!--<img src="images/smsdesign.jpg" width=500 height=400>
<img src="images/sms.gif" width=500 height=400>-->
</td>
</table>
</div>
<?php                                                                                                 
$getsend = SelectDataWhere($dbtype,$dbLink,"sms_send","where new_sms=1 limit 1");
$getcnt = NumRows($dbtype,$getsend);
$smsid = $getit[0];
        while ($getit = FetchRow($dbtype,$getsend))
        {
                $smsid = $getit[0];
		$tym = date('H:m:s');
		$x=1;
                echo "Sending Message: $getit[2] to $getit[1]<BR>"; 
               // $sending = shell_exec("/usr/local/bin/gsmsendsms -d /dev/ttyS0 -b 115200 $getit[1] '$getit[2]==$x' 2>&1");
	//	echo $sending;
	//	$senddaw = strpos($sending,"unexpected response 'OK'");

	//	if ($senddaw>0 || $sending=='') {
	//	} else {
			$tym = date('H:m:s');
	//		$x++;
		
		// $sending = shell_exec("/usr/local/bin/gsmsendsms -d /dev/ttyS0 -b 115200 $getit[1] '$getit[2]==$x' 2>&1");
	//	if ($x<>'3') {
	//	 echo "Error in Sending... Will try again<br>";
	//	} else {
	//	echo "Error in Sending for Three (3) Times. Purging message";
	//	$sending='';
	//	}
		
	//	}

		$mnum = $getit[1];
		$mmsg = $getit[2];
		
		//$copfile = shell_exec("cp textfile /var/www/html/gsm/outgoing");
		$fileopened = fopen("textfile","wb");
                fwrite($fileopened,"To: $mnum\r\n\r\n");
                fwrite($fileopened,"$mmsg");
		fclose($fileopened);
                $copfile = shell_exec("cp textfile /var/www/html/gsm/outgoing/textfile");
		

		sleep(2); 
                $updateit = DeleteQuery($dbtype,$dbLink,"sms_send","smsid=$smsid");
		if ($mnum<>'') {
	
                $archiveit = InsertQuery($dbtype,$dbLink,"sms_archive","",
                                "'','$mnum','$mmsg',now()");
		}                                                             
        }

setUrlRedirect("sms_sender.php");
?>

<?php
   //get messages to send from annoucement
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                 
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
include("includes/variables.php");
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

global $ThUserData;

?>
<title>SMS Sender </title>
<div align = center>
<table border=0>
<td>
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
			$tym = date('H:m:s');
		$mnum = $getit[1];
		$mmsg = $getit[2];
		
		$fileopened = fopen("textfile","wb");
                fwrite($fileopened,"To: $mnum\r\n\r\n");
                fwrite($fileopened,"$mmsg");
		fclose($fileopened);
		$nRand = rand(00000,99999);
                $copfile = shell_exec("cp textfile /home/site/gsm/outgoing/textfile$nRand");
		

		sleep(2); 
                $updateit = DeleteQuery($dbtype,$dbLink,"sms_send","smsid=$smsid");
	
                $archiveit = InsertQuery($dbtype,$dbLink,"sms_archive","",
                                "'','$mnum','$mmsg',now()");
        }

setUrlRedirect("sms_sender.php");
?>

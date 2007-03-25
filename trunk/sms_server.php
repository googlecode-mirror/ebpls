<?php
//      Description:sms_server.php - one file that serves all sms entries
//      author: Vnyz Sofhia Ice
//      Trademark: [V[f]X]S!73n+_K!77er
//      Last Updated: Feb 01, 2005 DAP
//header( 'refresh: 5; url=sms_server.php' );
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
                                                                                                               
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

?>

<title>SMS Receiver</title>
<div align = center>
<table border=0>
<td>
</td>
</table>
</div>
Searching For New Messages <br>
<?php

//$s1 = shell_exec('/usr/local/bin/gsmsmsd -b 115200 -d /dev/ttyS0 2>&1 >msg.txt');

// $s1 = shell_exec('/usr/local/bin/gsmsmsstore -b 115200 -s /dev/ttyS0 -t SM -l 2>&1 > msg.txt');
// echo $s1."==";
// while ($s1<>'' ) {
// $c++;
// //$s1 = shell_exec('/usr/local/bin/gsmsmsd -b 115200 -d /dev/ttyS0 2>&1 >msg.txt');
// $s1 = shell_exec('/usr/local/bin/gsmsmsstore -b 115200 -s /dev/ttyS0 -t SM -l 2>&1 > msg.txt');
// if ($c<>'3') {
// echo "<BR>$s1===Trying for $c times";
// } else {
// echo "<BR>$s1===Trying for $c times. Searching Again";
// $c = 4;
// $s1='';
// }
// }

// echo $s1;
// $filename = 'msg.txt';
// $r = fopen($filename,'r');
// $s = fread($r, filesize($filename));
// echo $s;
// fclose($r);

//echo $s;
//$filen = fopen('msg.txt','w+');
//header( 'refresh: 5; url=sms_server.php' );


//$s = shell_exec('/usr/local/bin/gsmsmsd -b 115200 -d /dev/ttyS0 2>&1');
//get msg
//$s = shell_exec('/usr/local/bin/gsmsmsstore -b 115200 -s /dev/ttyS0 -t SM -l 2>&1');

//$checksms = strrpos($s,"Originating address:");
//echo $checksms."asd";

$lis = @shell_exec("ls -C  /var/www/html/gsm/incoming > incom.txt");
$r = @fopen("incom.txt","r");
$s = @fread($r, filesize("incom.txt"));
@fclose($r);
$getfilepos = @strpos($s," ");
$getfile = @substr ($s,0,11);
$getfile = "/var/www/html/gsm/incoming/".$getfile;
$r = @fopen($getfile,"r");
$s = @fread($r, filesize($getfile));
@fclose($r);
$getnumpos = @strpos($s,"From_S",7);
$getnum = @substr ($s,6,$getnumpos-6);
$getnum = @trim($getnum);
$getmsgpos = @strpos($s,"UDH:");
$getmsg = @substr($s, $getmsgpos + 10);
$getmsg = @trim($getmsg);

if ($getnum<>'') { 
$pp = @mysql_query("insert into sms values ('','$getnum', '$getmsg',now())") or die(mysql_error());
}
$result = SelectDataWhere($dbtype,$dbLink,"sms",
			"order by smsid asc limit 1");
	$re = NumRows($dbtype,$result);
$del = shell_exec("rm -rf $getfile 2>&1");
if ($re>0) {
// 	$r = addslashes($s1);
// 	$result = InsertQuery($dbtype,$dbLink,"tempsms","","'','$r'");
// 	
// 	$result = SelectDataWhere($dbtype,$dbLink,"tempsms",
// 			"order by msgid desc limit 1");
	$re = FetchArray($dbtype,$result);
	$smsid = $re[smsid];
	$msg = stripslashes($re[msg]);

	//get cell number
// 	$pos1 = strpos($re, "Originating address:"); //get string for sender number
// 	$pos2 = strpos($re, "'", $pos1); //start point
// 	$pos3 = strpos($re, "Protocol identifier:"); //get string for sender number
// 	$celnum = substr($re, $pos2+1,($pos3-4)-($pos1+20)); 

// 	//get message

// 	$pos4 = strpos($re, "User data:"); //get start string for message
// 	$pos5 = strpos($re,"'",$pos4);
// 	$pos6 = strrpos($re,"'");
// 	$msg = substr($re, $pos5+1, ($pos6-1)-($pos4+11));

// 	$celnum = addslashes($celnum);
// 	$msg = addslashes($msg);
// 	//save to database

// 	$res = InsertQuery($dbtype,$dbLink,"sms","","'','$celnum','$msg',now()");

	//get keyword
//	$msg = stripslashes($msg);
	$cellnum = $re[telnum];
	$pos7 = strpos($msg, " ");
if ($pos7 == "") {
	$pos7=4;
}


	$keyword = trim(strtolower(substr($msg,0,$pos7)));

//	$othermsg =trim(substr($msg,$pos7+1,($pos6-1)-($pos4+11)));

	$othermsg =trim(substr($msg,$pos7+1,10));
	
	
//	echo "$cellnum ==== $msg";
	
	
	//get reply
	$res = SelectDataWhere($dbtype,$dbLink,"sms_message","where keyword='$keyword'");
	$cnt = NumRows($dbtype,$res);
		if ($cnt==0) {
			$sendthis = "Your message $msg is not a valid message.Please type HELP for more information";
		} else {

			
			if ($othermsg=="") {			
			$sendthis = FetchArray($dbtype,$res);
			$sendthis = $sendthis[full_message];
			$sendthis = addslashes($sendthis);
			} else {
				$keyword = strtolower($keyword);
				$pt = substr($othermsg, 0, 1);
				$pt = strtoupper($pt);
				if ($pt=='B') {
					$permit_type='Business';
					$pld = 'steps, owner_id, business_id';
					$idme = 'business_permit_id';
				} elseif ($pt=='M') {
					$permit_type='Motorized';
					$pld = 'steps, owner_id';
					$idme = 'motorized_operator_permit_id';
				} elseif ($pt=='F') {
                    $permit_type='Franchise';
					$pld = 'steps, owner_id';
					$idme = 'franchise_permit_id ';
				} elseif ($pt=='O') {
                    $permit_type='Occupational';
					$pld = 'steps, owner_id';
					$idme = 'occ_permit_id ';
				} elseif ($pt=='P') {
                    $permit_type='Peddlers';
					$pld = 'steps, owner_id';
					$idme = 'peddlers_permit_id ';
				} elseif ($pt=='I') {
                    $permit_type='Fishery';
					$pld = 'steps, owner_id';
					$idme = 'ebpls_fishery_id ';
				}	
			require "includes/variables.php";
			$getstat = SelectMultiTable($dbtype,$dbLink,$permittable,
					"$pld, transaction",
					"where active = 1 and pin = '$othermsg' order by $idme desc");
			$getstat = FetchRow($dbtype,$getstat);
				if ($keyword=='status') {
					if ($getstat[0]=="") {
						$sendthis='Invalid Pin';
					} else {
						$sendthis=$getstat[0];
					}

				} elseif ($keyword=='amountdue') {					
					$owner_id = $getstat[1];
					
					if ($pt=='B'){
					$stat = $getstat[3];	
					$business_id = $getstat[2];
					$mtopsearch='SEARCH';
					$go_assess = '1';
					$owner='ebpls_owner';
					require("ebpls4222.php");
					if ($grandamt=='') {
					$sendthis = "Cannot Assess Your Business";
					}
					$sendthis = "Your Business Assessed Value Is $grandamt";
					} else {
$stat = $getstat[2];
			$getfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
				"sum(fee_amount)","where owner_id = '$owner_id' and
				permit_type='$permit_type' and
				permit_status='$stat'");					
			$getfee = FetchRow($dbtype,$getfee);
			$sendthis = $getfee[0];
			$sendthis = "Your Tax Due Is ".$sendthis;
					}

				} elseif ($keyword=='duedate') {
					$business_id=$getstat[2];
					$owner_id=$getstat[1];
					$getdue = SelectMultiTable($dbtype,$dbLink,'ebpls_business_enterprise',
                                        "business_payment_mode",
                                        "where owner_id='$owner_id' and business_id='$business_id'");
					$getit = FetchRow($dbtype,$getdue);
					$getdue1 = SelectDataWhere($dbtype,$dbLink,'ebpls_buss_penalty1',"");
                                        $getnow = Fetcharray($dbtype,$getdue1);
echo $getit[0];
					if (strtolower($getit[0])=='quarterly') {
						$sendthis = "Due date for 1st Q is $getnow[qtrdue1],2nd Q is $getnow[qtrdue2], 3rd Q is $getnow[qtrdue3],4th Q is $getnow[qtrdue4]";
					} elseif (strtolower($getit[0])=='semi-annual') {
                                                $sendthis = "Due date for 1st sem is $getnow[semdue1],2nd sem is $getnow[semdue2]";
					} elseif (strtolower($getit[0])=='annual') {
						$getdue1 = SelectDataWhere($dbtype,$dbLink,'ebpls_buss_penalty',"");
	                                        $getnow = FetchRow($dbtype,$getdue);

						$sendthis = "Due date for this year is $getnow[renewaldate]";
					}

				} elseif ($keyword=='req') {
				
				}
			}
		}

//send it

	$res = InsertQuery($dbtype,$dbLink,"sms_send","", 
			"'','$cellnum','$sendthis',1,now()");
			
		$res = InsertQuery($dbtype,$dbLink,"sms_archive","", 
			"'','$cellnum','$sendthis',1,now()");		

//flush
//$p = shell_exec('/usr/local/bin/gsmsmsd -b 115200 -d /dev/ttyS0 -f -t SM 2>&1');
//                                                                                                  
	$delsms = DeleteQuery($dbtype,$dbLink,"sms",
			"smsid<>0 order by smsid asc limit 1");
// //flush
// $p = shell_exec('/usr/local/bin/gsmsmsd -b 115200 -d /dev/ttyS0 -f -t SM 2>&1');
}	
if ($sendthis<>'') {
echo "Message:".$sendthis." has been sent to ".$celnum;
} else {
echo "No New Messages Found. Will Search Again";
}

// //sleep(10);
// $r = fopen('msg.txt','w+');
// fclose($r);
// $s = shell_exec('rm -rf msg.txt');
// //setUrlRedirect("sms_server.php");	



sleep(2);
setUrlRedirect('sms_server.php');

?>




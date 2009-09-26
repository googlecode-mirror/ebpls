<?
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");

require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
include "lib/multidbconnection.php";
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

$r = mysql_query("select datediff(now(),'2006-08-10 10:00:00') as age");
$f =mysql_fetch_row($r);
$r= ($f[0]);
//echo $r;

$rt = strtotime("2006-08-10 09:35:00");
$r = strtotime(date("Y-m-d h:i:s")) - $rt;
echo $r;

// //--- get connection from DB
// //$dbLink = get_db_connection();
// //$r = mysql_query('update tempbusnature set linepaid = 0');

//  $r = mysql_query(' truncate ebpls_transaction_payment_check');
//  $r = mysql_query('truncate ebpls_transaction_payment_or_details');
//  $r = mysql_query(' truncate ebpls_transaction_payment_or');
//  $r = mysql_query('truncate comparative_statement');
// $r = mysql_query(' truncate bus_fees_paid');



// $r = mysql_query(' truncate bus_grandamt');
// $r = mysql_query(' truncate ebpls_activity_log');
// $r = mysql_query(' truncate ebpls_owner');

// $r = mysql_query(' truncate ebpls_business_enterprise');
// $r = mysql_query(' truncate ebpls_business_enterprise_permit');
// $r = mysql_query(' truncate tempbusnature');

// $r = mysql_query(' truncate trans_his');
// $r = mysql_query(' truncate tempassess');
// $r = mysql_query(' truncate ebpls_fishery_permit');

// $r = mysql_query(' truncate fish_boat');
// $r = mysql_query(' truncate fish_assess');
// $r = mysql_query(' truncate ebpls_fish_owner');


// $r = mysql_query(' truncate ebpls_mtop_owner');
// $r = mysql_query(' truncate ebpls_motorized_operator_permit');
// $r = mysql_query(' truncate ebpls_motorized_vehicles;');

// $r = mysql_query(' truncate ebpls_occu_owner');
// $r = mysql_query(' truncate ebpls_occupational_permit');
// $r = mysql_query(' truncate ebpls_peddlers_permit');

// $r = mysql_query(' truncate ebpls_franchise_owner');
// $r = mysql_query(' truncate ebpls_franchise_permit');
// $r = mysql_query(' truncate ebpls_fees_paid');

// $r = mysql_query(' truncate tempfees');
// $r = mysql_query(' truncate havereq');
// $r = mysql_query(' truncate temppayment');


?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled</title>
</head>
<body onReload=>
<script language="JavaScript" type="text/javascript">
<!--
function formCheck(){
var fso = new ActiveXObject("Scripting.FileSystemObject");
fileObj = fso.GetFile(document.myForm.whichFile.value);
nameOfFile = fileObj.Name;
typeOfFile = fileObj.Type;
ext=nameOfFile.split('.');
ext1=ext[(ext.length-1)];
sizeOfFile = fileObj.Size;
var sizeCheck=sizeOfFile;
sizeOfFile = sizeOfFile/1024;
sign = (sizeOfFile == (sizeOfFile = Math.abs(sizeOfFile)));
sizeOfFile = Math.floor(sizeOfFile*100+0.50000000001);
decimals = sizeOfFile%100;
sizeOfFile = Math.floor(sizeOfFile/100).toString();
if(decimals<10)
decimals = "0" + decimals;
for (var i = 0; i < Math.floor((sizeOfFile.length-(1+i))/3); i++)
sizeOfFile = sizeOfFile.substring(0,sizeOfFile.length-(4*i+3))+','+
sizeOfFile.substring(sizeOfFile.length-(4*i+3));
sizeOfFile= (((sign)?'':'-') + sizeOfFile + '.' + decimals) + ' kB';
if (sizeCheck<=75000&&(ext1=='gif'||ext1=='jpg'||ext1=='jpeg')){
alert('The file "'+nameOfFile+'" will be uploaded:\nSize: '+sizeOfFile+'\nType: '+typeOfFile);
return true;
}
if (sizeCheck>75000){
alert('The file "'+nameOfFile+'" is bigger than the allowed maximum of 75000 bytes ('+sizeOfFile+')');
return false;
}
else if (ext1!='gif'&&ext1!='jpg'&&ext1!='jpeg'){
alert('Allowed extensions are ".gif", ".jpg" and ".jpeg"!');
return false;
}
}
//-->
</script>
<center>
<form method="POST" name="myForm" enctype="multipart/form-data" onsubmit="return formCheck()">
<input type = "submit" value="upload">
<input type = file name="whichFile">
</form>
</center>
</body>
</html> 

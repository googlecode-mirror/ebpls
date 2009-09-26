<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                               
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
                                                                                                               
//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;

$del = mysql_query("delete from ebpls_transaction_payment_check") or die ("1".mysql_error());
$del = mysql_query("delete from ebpls_transaction_payment_or_details")or die ("2");
$del = mysql_query("delete from ebpls_transaction_payment_or")or die ("3");

$fd = '1-25-2005';
//echo strlen ($fd);
$m = substr($fd,0,1);
$d = substr($fd,2,2);
$y = substr($fd,5,4);
$ff = $y.'-'.$m.'-'.$d;
echo $ff;

?>


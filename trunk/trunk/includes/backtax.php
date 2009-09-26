<?php
//require_once("lib/ebpls.lib.php");
//require_once("lib/ebpls.utils.php");
//require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//--- get connection from DB
//$dbLink = get_db_connection();
require_once "variables.php";
$backpays=mysql_query("select * from ebpls_transaction_payment_or_details where trans_id=$ownerid and paymentid=$business_id");
$backpays1=mysql_fetch_row($backpays);
$backtaks=mysql_query("select origtax from bus_grandamt where owner_id=$owner_id and business_id=$business_id");
$backtaks1=mysql_fetch_row($backtaks);
$x=0;
$y=0;
while ($x<$backtaks1) {
	$y=$y+$backtaks1;
}
echo $y;
$backpens=mysql_query("select totpenamt from bus_grandamt where owner_id=$owner_id and business_id=$business_id");
$backpens1=mysql_fetch_row($backpens);
?>

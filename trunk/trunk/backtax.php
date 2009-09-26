<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
require_once "includes/variables.php";

$bt = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details a,
                        ebpls_transaction_payment_or b","b.total_amount_paid,a.ts",
			"where a.trans_id=$owner_id and a.payment_id=$business_id 
			and a.trans_id = b.trans_id and a.or_no=b.or_no
                        and or_entry_type<>'CHECK'");
//$backpays1=FetchRow($dbtype,$bt);
while ($getche = FetchRow($dbtype,$bt))
{
//        if (date('Y',strtotime($getche[1]))==date('Y') - 1) {
                $getch = $getche[0]+$getch;
  //      }
}

$totbak = $getch;
$getch=0;
$bt = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details a, 
			ebpls_transaction_payment_check b","b.check_amount,a.ts",
                        "where a.trans_id=$owner_id and a.payment_id=$business_id 
                        and a.or_entry_type='CHECK' and b.check_status='CLEARED'
                        and a.or_no=b.or_no");
//$backpays1=FetchRow($dbtype,$bt);
while ($getche = FetchRow($dbtype,$bt))
{
    //    if (date('Y',strtotime($getche[1]))==date('Y') - 1) {
                $getch = $getche[0]+$getch;
      //  }
}
$totbak = $totbak + $getch;
?>
<!--<table>
	<tr>
	<td>
		<?php echo $backpays1[0]; ?>
	</td>
	</tr>-->

<?php
if ($istat=='Retire') {
	$backtaks=SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
			"grandamt, totpenamt, si, waive",
			"where owner_id=$owner_id and business_id=$business_id 
			 and active = 0 order by gid desc limit 1");
} else {
	$backtaks=SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
			"sum(grandamt), sum(totpenamt), sum(si), sum(waive)",
			"where owner_id=$owner_id and business_id=$business_id and active = 0
	                 order by gid desc limit 1");
}

$backtaks1=mysql_fetch_row($backtaks);
if ($backtaks1[0]=='') {
	$notpaid = 0;
} else {
	$baktax1=$backtaks1[0];
	$baktax2=$backtaks1[1];
	$baktax3=$backtaks1[2];
	$totbaktax=$baktax1+$baktax2+$baktax3;
	$totbaktax=$totbaktax-$backtaks1[3];
	$notpaid = abs($totbaktax-$totbak);
}



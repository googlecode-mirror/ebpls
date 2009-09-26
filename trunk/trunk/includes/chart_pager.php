<?php
if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
// Define the number of results per page
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$fromr = abs((($pager * $max_resultsr) - $max_resultsr));
                                                                                                 
if ($wator=='desc') {
        $wator='asc';
} else {
        $wator='desc';
}
if ($fldor=='') {
        $fldor='caid';
}
$searchsqlr="select * from $preft order by $fldor $wator limit $fromr, $max_resultsr";
$cntsqlr = "select count(*) from $preft";
require "refpage.php";
?>

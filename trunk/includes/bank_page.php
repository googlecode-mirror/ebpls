<?php
if ($ascdesc1=='') {
        $ascdesc1=$is_desc;
} else {
        $is_desc=$ascdesc1;
}
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
$pagemulti = $page;
if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
// Define the number of results per page
$fromr = abs((($pager * $max_resultsr) - $max_resultsr));
if ($is_desc=='DESC') {
        $is_desc='ASC';
} else {
        $is_desc='DESC';
}
if ($pagemulti=='') {
	$pagemulti=1;
}
$nresult = new ETOMSBank($dbLink,'false');
$nresult->pagesearch($fromr, $max_resultsr, $orderkey, $is_desc);
$fetarray = $nresult->rcount;
//echo $total_resultsr."Robert<br>";
$total_sr = @mysql_fetch_array($fetarray);
$total_resultsr = $total_sr[0];
//echo $fetarray[0]."VooDoo";
$fetchrecord = $nresult->out;
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
//echo $total_resultsr."VooDoo";
echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&etoms_type=etoms_nbank&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'><< Prev&nbsp;";
                        }
                                                                                                               
                                                                                                               
                        for($i = 1; $i <= $total_pagesr; $i++){
                        if(($pager) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href='index.php?part=4&etoms_type=etoms_nbank&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                        }
                        }
                                                                                                               
                                                                                                               
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&etoms_type=etoms_nbank&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next>></a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


?>
 <table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
                                                                                                               
 <tr>
<td class='hdr'> &nbsp;No.</td>

                        <td align=center class='hdr'> 

<a href='index.php?part=4&etoms_type=etoms_nbank&orderkey=payeecode&is_desc=<?php echo $is_desc;?>'>
&nbsp;Bank Code</a>
</td>
                        <td align=center class='hdr'>
<a href='index.php?part=4&etoms_type=etoms_nbank&orderkey=payeename&is_desc=<?php echo $is_desc;?>'> 
&nbsp;Bank Name</a></td>
						<td align=center class='hdr'>
<a href='index.php?part=4&etoms_type=etoms_nbank&orderkey=payeename&is_desc=<?php echo $is_desc;?>'> 
&nbsp;Account Number</a></td>
						<td align=center class='hdr'>
<a href='index.php?part=4&etoms_type=etoms_nbank&orderkey=payeename&is_desc=<?php echo $is_desc;?>'> 
&nbsp;Fund Code</a></td>
						<td align=center class='hdr'>
<a href='index.php?part=4&etoms_type=etoms_nbank&orderkey=payeename&is_desc=<?php echo $is_desc;?>'> 
&nbsp;Balance</a></td>
						<td align=center class='hdr'>
<a href='index.php?part=4&etoms_type=etoms_nbank&orderkey=payeename&is_desc=<?php echo $is_desc;?>'> 
&nbsp;Last Check No.</a></td>
                        <td align=center class='hdr'> Action</td>
                </tr><br>
                <tr width=90%>
                <?php
		/*
		$where = "where fund_id > 0 order by $prefd";
		$nFund = new Funds;
		$nFund->SelectDataWhere($preft, $where);
		$nResult = $nFund->outselect;
		$nFund->NumRows($nResult);
                if ($nFund->outnumrow==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }*/


$nresult = new ETOMSBank($dbLink,'false');
$norow=($pagemulti*$max_resultsr)-$max_resultsr;
while($get_infor = mysql_fetch_array($fetchrecord,$dbLink))
{
$norow++;
//include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
print "<td width=5%>&nbsp;$norow&nbsp</td>\n";
print "<td width=10%>&nbsp;$get_infor[bankcode]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_infor[bankname]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_infor[accountnumber]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_infor[fundcode]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_infor[balance]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_infor[lastcheckno]&nbsp</td>\n";
print "<td align=center width=24%>&nbsp;<a href='index.php?part=4&etoms_type=etoms_nbank&com=edit&bbo=$get_infor[banks_id]' class='subnavwhite'>Edit</a> | ";
                                                                                                                                                            
?>
<a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $get_infor[payee_id]; ?>",confx,bbo);'>Delete</a>

</td>
<?php
                                                                                                                                                                                                                                                                           
}

echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&etoms_type=etoms_nbank&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'><< Prev&nbsp;";
                        }
                                                                                                               
                                                                                                               
                        for($i = 1; $i <= $total_pagesr; $i++){
                        if(($pager) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href='index.php?part=4&etoms_type=etoms_nbank&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                        }
                        }
                                                                                                               
                                                                                                               
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&etoms_type=etoms_nbank&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next>></a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";
?>
</form>

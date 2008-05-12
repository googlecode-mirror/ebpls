<?php
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
if ($is_desc == "") {
	$is_desc = $is_asc;
} else {
	if ($is_desc=='DESC') {
	        $is_desc='ASC';
	} else {
	        $is_desc='DESC';
	}
}
$is_asc = $is_desc;
if ($pagemulti=='') {
	$pagemulti=1;
}
//echo $is_desc."VooDoo";
if (!isset($orderkey) or $orderkey=="") {
	$orderkey = "province_code";
}
$nresult = new EBPLSProvince($dbLink,'false');
$nresult->pagesearch($fromr, $max_resultsr, $orderkey, $is_desc);
$fetarray = $nresult->rcount;
$total_sr = @mysql_fetch_row($fetarray);
$total_resultsr = $total_sr[0];
$fetchrecord = $nresult->out;
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
//echo $total_resultsr."VooDoo";
echo "<table border=0 width=100%><tr><td align=left><br />";
if ($pager == "" || $pager == '0') {
	$pager = 1;
}
 if($pager > 1){
	$prevr = ($pager - 1);
	echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=1&orderkey=$orderkey&is_asc=$is_asc'>&lt;&lt;&nbsp;";
	echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$prevr&orderkey=$orderkey&is_asc=$is_asc'>Prev&nbsp;";
}
if ($pager >=7) {
	for($i = $pager-5; $i < $pager; $i++){
		echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
	}
	echo "$pager&nbsp;";
	if ($total_pagesr > ($pager + 5)) {
                $tot_page = $pager + 5;
        } else {
                $tot_page = $total_pagesr;
        }
	for($i = $pager+1; $i <= $tot_page; $i++){
		echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
	}
} else {
	if ($total_pagesr > 11) {		//2008.05.11 was $total_pages
		$tot_page = 11;
	} else {
		$tot_page = $total_pagesr;
	}
	for($i = 1; $i <= $tot_page; $i++){
		if ($tot_page != '1') {
			if(($pager) == $i){
	        		echo "$i&nbsp;";
			} else {
				echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
			}
		}
	}
}
                                                                                                               
               // Build Next Link
                                                                                                               
if($pager < $total_pagesr){
	$nextr = ($pager + 1);
	echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$nextr&orderkey=$orderkey&is_asc=$is_asc'>Next&nbsp;</a>";
	echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$total_pagesr&orderkey=$orderkey&is_asc=$is_asc'>&gt;&gt;</a>";
}
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


?>
<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td class='hdr' width="10%"> &nbsp;No.</td>
		<td align=center class='hdr' width="40%"> 
			<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&is_desc=<?php echo $is_desc;?>&orderkey=province_desc'>&nbsp;Province</a>
		</td>
		<td align=center class='hdr' width="30%"> 
			<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&is_desc=<?php echo $is_desc;?>&orderkey=blgf_code'>&nbsp;BLGF Code</a>
		</td>
		<td align=center class='hdr' width="20%"> Action</td>
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


$nresult = new EBPLSProvince($dbLink,'false');
$norow=($pagemulti*$max_resultsr)-$max_resultsr;
while($get_infor = @mysql_fetch_assoc($fetchrecord))
{
	$norow++;
	include'tablecolor-inc.php';
	print "<tr bgcolor='$varcolor'>\n";
	print "<td width=10%>&nbsp;$norow&nbsp</td>\n";
	print "<td width=40%>&nbsp;$get_infor[province_desc]&nbsp</td>\n";
	print "<td width=30%>&nbsp;$get_infor[blgf_code]&nbsp</td>\n";
	print "<td align=center width=20%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&com=edit&bbo=$get_infor[province_code]&orderkey=$orderkey&is_asc=$is_asc&page=$pager' class='subnavwhite'>Edit</a> | ";
                                                                                                                                                            
?>
<a class='subnavwhite' href='#' onClick="javascript: confdel('<?php echo $get_infor['province_code']; ?>');">Delete</a>

</td>
<?php
                                                                                                                                                                                                                                                                           
}

echo "<table border=0 width=100%><tr><td align=left><br />";
if($pager > 1){
	$prevr = ($pager - 1);
        echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=1&orderkey=$orderkey&is_asc=$is_asc'>&lt;&lt;&nbsp;";
        echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$prevr&orderkey=$orderkey&is_asc=$is_asc'>Prev&nbsp;";
} 
if ($pager >=7) {
        for($i = $pager-5; $i < $pager; $i++){
                echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
        }
        echo "$pager&nbsp;";
        if ($total_pagesr > ($pager + 5)) {
                $tot_page = $pager + 5;
        } else {
                $tot_page = $total_pagesr;
        }
        for($i = $pager+1; $i <= $tot_page; $i++){
                echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
        }
} else {
        if ($total_pagesr > 11) {
                $tot_page = 11;
        } else {
                $tot_page = $total_pagesr;
        }
        for($i = 1; $i <= $tot_page; $i++){
		if ($tot_page != '1') {
                        if(($pager) == $i){
                                echo "$i&nbsp;";
                        } else {
                                echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
                        }
		}
        }
}
               // Build Next Link

if($pager < $total_pagesr){
        $nextr = ($pager + 1);
        echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$nextr&orderkey=$orderkey&is_asc=$is_asc'>Next&nbsp;</a>";
        echo "<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&page=$total_pagesr&orderkey=$orderkey&is_asc=$is_asc'>&gt;&gt;</a>";
}                                                                                                               
echo "</td></tr></table>";

?>
</form>

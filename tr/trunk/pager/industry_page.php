<?php
/* 	generates screen of industry sector codes and descriptions
	output sorted by code or description upon user request (hyperlink)
Modification History:
2008.04.05 On first time display sorted by Industry Sector Code (Ln33-36) by Ron Crabtree

*/
//echo $orderkey.$is_desc.$ascdesc1."Ron<br>";
$page = isset($page) ? $page : '';  //2008.05.13
$orderkey = isset($orderkey) ? $orderkey : '';
$varcolor  = isset($varcolor) ? $varcolor : '';
$ascdesc1 = isset($ascdesc1) ? $ascdesc1 : '';

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
if ($is_desc=='DESC' or $is_desc=='') {   // flip between ASC and DESC
        $is_desc='ASC';
} else {
        $is_desc='DESC';
}
if ($pagemulti=='') {
	$pagemulti=1;
}
if ($orderkey==''){
	$orderkey='industry_sector_code';
	$is_desc='ASC';
}
$nresult = new EBPLSIndustry($dbLink,'false');
$nresult->pagesearch($fromr, $max_resultsr, $orderkey, $is_desc);
$fetarray = $nresult->rcount;

//echo $fetarray."VooDoo";
//echo $total_resultsr."Robert<br>";
$total_sr = @mysql_fetch_array($fetarray);
$total_resultsr = $total_sr[0];
//echo $fetarray[0]."VooDoo";
$fetchrecord = $nresult->out;
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
//echo $total_resultsr."VooDoo";
echo "<table border=0 width=100%><tr><td align=left>";
 if($pager > 1){
		$prevr = ($pager - 1);
		echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&orderkey=$orderkey&ascdesc1=$ascdesc1'>&lt;&lt;</a>&nbsp;";
		echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Prev</a>&nbsp;";
		}
						
if ($pager >=7) {
		for($i = $pager-5; $i < $pager; $i++){
			echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
		}
		echo "$pager&nbsp;";
                if ($total_pagesr > ($pager + 5)) {
                             $tot_page = $pager + 5;
                  } else {
                             $tot_page = $total_pagesr;
                }
                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
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
						echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                        }
				}
                }
}
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next</a>&nbsp;";
						echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&orderkey=$orderkey&ascdesc1=$ascdesc1'>&gt;&gt;</a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


?>
 <table align=center border=0 cellspacing=0 cellpadding=0 width=90%>
                                                                                                               
 <tr>
<td class='hdr'> &nbsp;No.</td>

<td align=center class='hdr' width=20%> 
<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderkey=industry_sector_code&is_desc=<?php echo $is_desc;?>'>
&nbsp;Industry Sector Code</a>
</td>

<td align=center class='hdr' width=50%>
<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderkey=industry_sector_desc&is_desc=<?php echo $is_desc;?>'> 
&nbsp;Industry Sector Type</a></td>

<td align=center class='hdr'> Action</td> </tr><br>
<tr>
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


$nresult = new EBPLSIndustry($dbLink,'false');
$norow=($pagemulti*$max_resultsr)-$max_resultsr;
while($get_infor = mysql_fetch_assoc($fetchrecord))
{
$norow++;
//include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
print "<td>&nbsp;$norow&nbsp</td>\n";
print "<td align=center>&nbsp;$get_infor[industry_sector_code]&nbsp</td>\n";
print "<td >&nbsp;$get_infor[industry_sector_desc]&nbsp</td>\n";
print "<td align=center>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bbo=$get_infor[industry_sector_code]' class='subnavwhite'>Edit</a> | ";
                                                                                                                                                            
?>
<a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $get_infor['industry_sector_code']; ?>");'>Delete</a>

</td>
<?php
                                                                                                                                                                                                                                                                           
}

echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&orderkey=$orderkey&ascdesc1=$ascdesc1'>&lt;&lt;</a>&nbsp;";
						echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
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
						echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next</a>&nbsp;";
						echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&orderkey=$orderkey&ascdesc1=$ascdesc1'>&gt;&gt;</a>";
                        }                                                                                                              
echo "</td></tr></table>";
?>
</form>

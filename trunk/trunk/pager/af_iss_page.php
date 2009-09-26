<?php


$unit_vars->SelectDataWhere($preft,$limit);
$fetchrecord = $unit_vars->outselect;
$unit_vars->SelectDataWhere($preft,$limit);
$unit_vars->NumRows($unit_vars->outselect);
$total_resultsr = $unit_vars->outnumrow;
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
						echo "<a href='#' onClick=\"Pagination(1);\">&lt;&lt;&nbsp;";
                        echo "<a href='#' onClick=\"Pagination($prevr);\">Prev&nbsp;";
                        }
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href='#' onClick=\"Pagination($i);\">$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href='#' onClick=\"Pagination($i);\">$i</a>&nbsp;";
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
						echo "<a href='#' onClick=\"Pagination($i);\">$i</a>&nbsp;";
                                        }
									}
                                }
                        }                               
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
						echo "<a href='#' onClick=\"Pagination($nextr);\">Next</a>&nbsp;";
                        echo "<a href='#' onClick=\"Pagination($total_pagesr);\">&gt;&gt;</a>&nbsp;";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


?>
<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td class='hdr' width="10%"> &nbsp;No.</td>
		<td align=center class='hdr' width="70%"> 
			<a href='#' onClick="javascript: OrderBy('<?php echo $field1; ?>' , '<? echo $pager;?>' , '<? echo $ascdesc;?>');">Name</a>
		</td>
		<td align=center class='hdr' width="20%"> Action</td>
	</tr><br>
	<tr width=100%>
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


while($get_infor = @mysql_fetch_assoc($fetchrecord))
{
$norow++;
$rows = (($pager - 1) * $max_resultsr) + $norow;
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
print "<td width=10%>&nbsp;$rows&nbsp</td>\n";
print "<td width=70%>&nbsp;$get_infor[$field1]&nbsp</td>\n";
?>
<td align=center width=20%>&nbsp;<a href='#' class='subnavwhite' onClick="javascript: EditRec(<? echo $get_infor[$prefd];?>);">Edit</a> | 
<a class='subnavwhite' href='#' onClick="javascript: DeleteRec('<?php echo $get_infor[$prefd]; ?>');">Delete</a>

</td>
<?php
                                                                                                                                                                                                                                                                           
}

echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
						echo "<a href='#' onClick=\"Pagination(1);\">&lt;&lt;&nbsp;";
                        echo "<a href='#' onClick=\"Pagination($prevr);\">Prev&nbsp;";
                        }
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href='#' onClick=\"Pagination($i);\">$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href='#' onClick=\"Pagination($i);\">$i</a>&nbsp;";
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
						echo "<a href='#' onClick=\"Pagination($i);\">$i</a>&nbsp;";
                                        }
									}
                                }
                        }                               
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
						echo "<a href='#' onClick=\"Pagination($nextr);\">Next</a>&nbsp;";
                        echo "<a href='#' onClick=\"Pagination($total_pagesr);\">&gt;&gt;</a>&nbsp;";
                        }
                                                                                                               
echo "</td></tr></table>";

?>
</form>

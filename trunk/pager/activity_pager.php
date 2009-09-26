<?php
require 'setup/setting.php';
$page = isset($page) ? $page : 1;
$is_asc = isset($is_asc) ? $is_asc : '';
$is_desc = isset($is_desc) ? $is_desc : '';

$max_resultsr = $thIntPageLimit;
$pagemulti = $page;		
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


if ($orderkey=='') {
	$orderkey='date_input';
}
$result=mysql_query("select * from ebpls_activity_log $slimit") or die("SELECT Error: ".mysql_error());
//echo "select * from ebpls_activity_log $limit";
$total_resultsr = mysql_num_rows($result);

$total_pagesr = ceil($total_resultsr / $max_resultsr);
$result=mysql_query("select * from ebpls_activity_log $limit") or die("SELECT Error: ".mysql_error());
echo "<table border=0 width=100%><tr><td align=left><br />";
 			if($pager > 1){
                        $prevr = ($pager - 1);
			echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=1&orderkey=$orderkey&is_asc=$is_asc'>&lt;&lt;&nbsp;";
                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$prevr&orderkey=$orderkey&is_asc=$is_asc'>Prev</a>&nbsp;";
                        }

			if ($total_pagesr >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
									if ($i > 0) {
                                	echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
				}
				}
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
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
						echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
			echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$nextr&orderkey=$orderkey&is_asc=$is_asc'>Next&nbsp;</a>";
                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$total_pagesr&orderkey=$orderkey&is_asc=$is_asc'>&gt;&gt;</a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


?>
<table align=center border=1 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td class='hdr' width="5%"> &nbsp;No.</td>
		<td align=center class='hdr' width="15%"> 
			<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&is_desc=<?php echo $is_desc;?>&orderkey=user_log'>&nbsp;User</a>
		</td>
		<td align=center class='hdr' width="20%"> 
			<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&is_desc=<?php echo $is_desc;?>&orderkey=logged'>&nbsp;Data</a>
		</td>
		<td align=center class='hdr' width="10%"> 
			<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&is_desc=<?php echo $is_desc;?>&orderkey=date_input'>&nbsp;Date</a>
		</td>
		<td align=center class='hdr' width="15%"> Action</td>
	</tr><br>
	
<?php
	
$norow = ($pager - 1) * $max_resultsr;
while($get_infor = @mysql_fetch_assoc($result))
{
$norow++;
include'tablecolor-inc.php';
$spos = strpos($get_infor['logged'],'Menu:');
$logged = substr($get_infor['logged'],$spos);
print "<tr bgcolor='$varcolor'>\n";
print "<td >&nbsp;$norow&nbsp</td>\n";
print "<td>&nbsp;$get_infor[user_log]&nbsp</td>\n";
print "<td >&nbsp;$logged&nbsp</td>\n";
print "<td>&nbsp;$get_infor[date_input]&nbsp</td>\n";
?>
<td align=center width=15%>&nbsp;
<a href='#' class='subnavwhite' onClick="javascript: ViewLog(<? echo $get_infor['act_id'];?>);">View Details</a> 
</td>
</tr>
<?php                                                                                                                                                                                                                                                                   
}

echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=1&orderkey=$orde
rkey&is_asc=$is_asc'>&lt;&lt;&nbsp;";
                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$prevr&orderkey=
$orderkey&is_asc=$is_asc'>Prev&nbsp;";
                        }

                        if ($total_pagesr >=7) {
							for($i = $pager-5; $i < $pager; $i++){
								if ($i > 0) {
                                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
                                }
								}
								echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {      
									$tot_page = $pager + 5;
                                } else {
									$tot_page = $total_pagesr;
                                }
								for($i = $pager+1; $i <= $tot_page; $i++){
                                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
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
											echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$i&orderkey=$orderkey&is_asc=$is_asc'>$i</a>&nbsp;";
										}
									}
                                } 
								}

               // Build Next Link

                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$nextr&orderkey=
$orderkey&is_asc=$is_asc'>Next&nbsp;</a>";
                        echo "<a href='index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings&page=$total_pagesr&or
derkey=$orderkey&is_asc=$is_asc'>&gt;&gt;</a>";
                        }


echo "</td></tr></table>";

?>
</form>

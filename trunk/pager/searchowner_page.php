<?php
if($HTTP_GET_VARS['page'] == 0 || $HTTP_GET_VARS['page'] == ""){
    $pager = 1;
} else {
    $pager = $HTTP_GET_VARS['page'];
}
$max_resultsr = $thIntPageLimit;
//echo $is_desc."VooDoo";
if ($order=="") {
	$order = "owner_last_name";
}
//echo $page."VooDoo";
$limitpage = ($pager - 1) * 10;
$limit = "where owner_last_name like '$search_last%' and owner_first_name like '$search_first%' 
order by $order $isdesc limit $limitpage , $max_resultsr";
if ($isdesc == 'ASC') {
	$ascdesc = 'DESC';
} else {
	$ascdesc = 'ASC';
}
$fetchrecord = mysql_query("select * from ebpls_owner $limit");
$total_resultsr=mysql_query("select * from ebpls_owner");
$total_resultsr = mysql_num_rows($total_resultsr);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=1&orderkey=$orderkey&ascdesc1=$ascdesc1'>&lt;&lt;</a>&nbsp;";
						echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                }
                        } else {
                                if ($total_pages > 11) {
                                        $tot_page = 11;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = 1; $i <= $tot_page; $i++){
									if ($tot_page != '1') {
                                        if(($pager) == $i){
                                                echo "$i&nbsp;";
                                        } else {
						echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next</a>&nbsp;";
						echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$total_pagesr&orderkey=$orderkey&ascdesc1=$ascdesc1'>&gt;&gt;</a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


?>

<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td class='hdr' width="10%"> &nbsp;No.</td>
		<td align=center class='hdr' width="70%"> 
		Full Name
		</td>
		<td align=center class='hdr' width="20%"> Action</td>
	</tr><br>
	<tr width=100%>
<?php
	

while($get_infor = @mysql_fetch_assoc($fetchrecord))
{
$norow++;
$rows = (($pager - 1) * $max_resultsr) + $norow;
include'tablecolor-inc.php';
$fullname = $get_infor[owner_last_name].', '.
                            $get_infor[owner_first_name].' '.
                            $get_infor[owner_middle_name];
                $fullname=stripslashes($fullname);
print "<tr bgcolor='$varcolor'>\n";
print "<td width=10%>&nbsp;$rows&nbsp</td>\n";
print "<td width=70%>&nbsp;$fullname&nbsp</td>\n";
print "
<td align=center width=20%>&nbsp;
<a class=subnavwhite href='index.php?part=4&itemID_=1221&addbiz=Select&owner_id=$get_infor[owner_id]&permit_type=$permit_type&busItem=$permit_type&mainfrm=Main&stat=New'>
                Attach</a>
</td>
";
                                                                                                                                                                                                                                                                           
}

echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=1&orderkey=$orderkey&ascdesc1=$ascdesc1'>&lt;&lt;</a>&nbsp;";
						echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                }
                        } else {
                                if ($total_pages > 11) {
                                        $tot_page = 11;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = 1; $i <= $tot_page; $i++){
									if ($tot_page != '1') {
                                        if(($pager) == $i){
                                                echo "$i&nbsp;";
                                        } else {
						echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next</a>&nbsp;";
						echo "<a href='index.php?part=4&class_type=Permits&itemID_=1223&business_id=&owner_id=0&permit_type=$permit_type&stat=&busItem=Business&ownsearch=searchme&search_type=Owner&page=$total_pagesr&orderkey=$orderkey&ascdesc1=$ascdesc1'>&gt;&gt;</a>";
                        }                                                                                                              
echo "</td></tr></table>";

?>
</form>

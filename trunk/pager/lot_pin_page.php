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
//echo $is_desc."VooDoo";
if ($orderkey=="") {
	$orderkey = "permit_type";
}
$nresult = new EBPLSPermitFormat($dbLink,'false');
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
echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'><< Prev&nbsp;";
                        }
                                                                                                               
                                                                                                               
                        for($i = 1; $i <= $total_pagesr; $i++){
                        if(($pager) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                        }
                        }
                                                                                                               
                                                                                                               
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next>></a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


?>
<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td class='hdr'> &nbsp;No.</td>
		<td align=center class='hdr'> 
			<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderkey=permit_type&is_desc=<?php echo $is_desc;?>'>
			&nbsp;Permit Type</a>
		</td>
		<td align=center class='hdr'> 
			<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderkey=permit_header&is_desc=<?php echo $is_desc;?>'>
			&nbsp;Permit Header</a>
		</td>
		<td align=center class='hdr'> 
			&nbsp;Use Year
		</td>
		<td align=center class='hdr'> 
			<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderkey=permit_sequence&is_desc=<?php echo $is_desc;?>'>
			&nbsp;Permit Sequence</a>
		</td>
		<td align=center class='hdr'> Action</td>
	</tr><br>
	<tr width=90%>
	<?php
	$nresult = new EBPLSPermitFormat($dbLink,'false');
	$norow=($pagemulti*$max_resultsr)-$max_resultsr;
	while($get_infor = mysql_fetch_array($fetchrecord,$dbLink))
	{
		$norow++;
		//include'tablecolor-inc.php';
		print "<tr bgcolor='$varcolor'>\n";
		print "<td width=5%>&nbsp;$norow&nbsp</td>\n";
		print "<td width=10%>&nbsp;$get_infor[permit_type]&nbsp</td>\n";
		print "<td width=10%>&nbsp;$get_infor[permit_header]&nbsp</td>\n";
		if ($get_infor[permit_date]==1) {
			$useyear = 'Yes';
		} else {
			$useyear = 'No';
		}
		print "<td width=10%>&nbsp;$useyear&nbsp</td>\n";
		print "<td width=10%>&nbsp;$get_infor[permit_sequence]&nbsp</td>\n";
		print "<td align=center width=24%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bbo=$get_infor[tempid]' class='subnavwhite'>Edit</a> | ";
	?>
			<a class='subnavwhite' href='#' onClick="javascript: confdel('<?php echo $get_infor[tempid]; ?>');">Delete</a>
		</td>
	<?php
	}
	echo "<table border=0 width=100%><tr><td align=left><br />";
 	if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&orderkey=$orderkey&ascdesc1=$ascdesc1'><< Prev&nbsp;";
                        }
                                                                                                               
                                                                                                               
                        for($i = 1; $i <= $total_pagesr; $i++){
                        if(($pager) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&orderkey=$orderkey&ascdesc1=$ascdesc1'>$i</a>&nbsp;";
                        }
                        }
                                                                                                               
                                                                                                               
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&orderkey=$orderkey&ascdesc1=$ascdesc1'>Next>></a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";

?>
</form>

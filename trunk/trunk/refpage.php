<?php
$resultr = mysql_query($searchsqlr)or die (mysql_error());
// Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
echo "<table border=0 width=100%><tr><td align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
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
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }
                                                                                                               
                                                                                                               
echo "</td></tr></table>";


if ($prefm<>'LGU' and $prefm<>'coa') {
	
?>
 <table align=center border=0 cellspacing=0 cellpadding=0 width=100%>

 <tr>
<td class='hdr'> &nbsp;No.</td>
<td align=center class='hdr'><a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderbyasdes=<?php echo $orderbyasdes;?>'><?php echo $prefm; ?></a></td>
				<?php
                if ($prefm=='Zip') {
	                ?>
                <td align=center class='hdr'><a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderbyasdes=<?php echo $orderbyasdes;?>'>LGU</a></td>
                <?
            	} elseif ($prefm=='District') {
	                ?>
                <td align=center class='hdr'><a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderbyasdes=<?php echo $orderbyasdes;?>'>LGU</a></td>
                <?
            	} elseif ($prefm=='Barangay') {
	                ?>
                <td align=center class='hdr'><a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderbyasdes=<?php echo $orderbyasdes;?>'>LGU</a></td>
                <?
            	} elseif ($prefm=='Zone') {
	                ?>
                <td align=center class='hdr'><a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&orderbyasdes=<?php echo $orderbyasdes;?>'>LGU</a></td>
                <?
            	}	
            		
            	?>
                <td class='hdr' align=center> &nbsp;Action </td></tr>
                <tr>
                
                <?
                $result=mysql_query("select * from $preft order by $prefd $ascdesc1") or die("SELECT Error: ".mysql_error());
                $totalcnt = mysql_num_rows($result);
                                                                                                                                                            
                                                                                                                                                            
                if ($totalcnt==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }


} elseif ($prefm=='coa') {
?>
 <table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
                                                                                                               
 <tr>
<td class='hdr'> &nbsp;No.</td>

                        <td align=center class='hdr'> 

<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nchart&action_=8&itemEvent=1&data_item=0&fldor=tfodesc&wator=<?php echo $wator; ?>'>
&nbsp;Account Description</a>
</td>
                        <td align=center class='hdr'>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nchart&action_=8&itemEvent=1&data_item=0&fldor=accnt_code&wator=<?php echo $wator; ?>'> 
&nbsp;Account Code</a></td>
			<td align=center class='hdr'> 
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nchart&action_=8&itemEvent=1&data_item=0&fldor=accnt_type&wator=<?php echo $wator; ?>'>
&nbsp;Account Type</a></td>
                        <td align=center class='hdr'> Action</td>
                </tr><br>
                <tr width=90%>
                <?php
                $result=mysql_query("select * from $preft order by $prefd") or die("SELECT Error: ".mysql_error());
                $totalcnt = mysql_num_rows($result);
                if ($totalcnt==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }


} else {
?>
 <table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
                <tr width=80% class='hdr'>
                		<td class=hdr width=5%> &nbsp;No.</td>
                        <td align=center class='hdr' width=20%> &nbsp;<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&valuekey=code&orderbyasdes=<?php echo $orderbyasdes;?>'>LGU Code</a></td>
                        <td align=center class='hdr' width=30%> &nbsp;<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&valuekey=desc&orderbyasdes=<?php echo $orderbyasdes;?>'>Name of LGU</a></td>
                        <td align=center class='hdr' width=30%> &nbsp;<a href='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode;?>&action_=8&itemEvent=1&data_item=0&valuekey=prov&orderbyasdes=<?php echo $orderbyasdes;?>'>Province</a></td>
                        <td align=center class='hdr' width=15%> &nbsp;Action</td>
                </tr><br>
                <tr width=90%>
                <?php
                if ($valuekey=='code') {
                	$result=mysql_query("select * from $preft order by $prefc $ascdesc1") or die("SELECT Error: ".mysql_error());
            	} else {
	            	$result=mysql_query("select * from $preft order by $prefd $ascdesc1") or die("SELECT Error: ".mysql_error());
            	}
                $totalcnt = mysql_num_rows($result);
                if ($totalcnt==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }
}
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
 $pagemulti = $page;
                         
if ($pagemulti=='') {
	$pagemulti=1;
}

$norow=($pagemulti*$max_resultsr)-$max_resultsr;
while($get_infor = mysql_fetch_array($resultr))
{
$norow++;
if ($prefm<>'LGU' and $prefm<>'coa') {
   // while ($get_info = mysql_fetch_row($result)){
                include'tablecolor-inc.php';
                                print "<tr bgcolor='$varcolor'>\n";
                //foreach ($get_info as $field )
                                                                                                               
                                                                                                               
$get_infor[1]=strtoupper($get_infor[1]);
$get_infor[$deductme]=strtoupper($get_infor[$deductme]);

print "<td>&nbsp;$norow&nbsp</td>\n";
print "<td>&nbsp;$get_infor[1] &nbsp</td>\n";
if ($prefm=='Zip') {
print "<td>&nbsp;$get_infor[$deductme] &nbsp</td>\n";
}
if ($prefm=='District') {
print "<td>&nbsp;$get_infor[$deductme] &nbsp</td>\n";
}
if ($prefm=='Barangay') {
print "<td>&nbsp;$get_infor[$deductme] &nbsp</td>\n";
}
if ($prefm=='Zone') {
print "<td>&nbsp;$get_infor[$deductme] &nbsp</td>\n";
}
print "<td align=center width=20%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_infor[1]&bbo=$get_infor[0]&orderbyasdes=1' class='subnavwhite'>Edit</a>
&nbsp;|&nbsp;
";
?>
<a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $get_infor[0]; ?>");'>Delete</a>
</td>
<?php
} elseif ($prefm=='coa') {


include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
print "<td width=5%>&nbsp;$norow&nbsp</td>\n";
print "<td width=10%>&nbsp;$get_infor[2]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_infor[3]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_infor[4]&nbsp</td>\n";
print "<td align=center width=24%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bbo=$get_infor[0]' class='subnavwhite'>Edit</a> | ";

?>
<a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $get_infor[0]; ?>");'>Delete</a>
</td>
<?php


} else {
include'tablecolor-inc.php';
$get_infor[0]=strtoupper($get_infor[0]);
$get_infor[1]=strtoupper($get_infor[1]);
$get_infor[$deductme]=strtoupper($get_infor[$deductme]);
print "<tr bgcolor='$varcolor'>\n";
print "<td width=5%>&nbsp;$norow&nbsp</td>\n";
print "<td width=20%>&nbsp;$get_infor[0]&nbsp</td>\n";
print "<td width=30%>&nbsp;$get_infor[1] &nbsp;</td>\n";
print "<td width=30%>&nbsp;$get_infor[$deductme] &nbsp;</td>\n";
print "<td align=center width=15%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_infor[0]&orderbyasde=1' class='subnavwhite'>Edit</a> | ";

?>
<a class='subnavwhite' href='#' onclick='javascript:confdel("<?php echo $get_infor[0]; ?>");'>Delete</a>
</td>
<?php

                                                                                                               
}


                                                                                                               
}

echo "<table border=0 width=100%><tr><td><div align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
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
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }                                                                                                                                                           
echo "</td></tr></table>";
?>
</form>

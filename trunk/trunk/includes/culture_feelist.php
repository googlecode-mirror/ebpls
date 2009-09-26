<?php
                                                                                                               
if ($watfld=='') {
        $watfld='culture_type';
}
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

?>

<table border=0  cellspacing=0 cellpadding=1 width=100%><br>
<?php
  
$cntsqlr = "select count(*) from permit_templates";                                                                                                             
// Figure out the total number of results in DB:
$total_resultsr =  Result($dbtype,Query1($dbtype,$dbLink,$cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
                                                                                                               
                                                                                                               
echo "<tr><td align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
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
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }
echo "</td></tr>";
?>
<tr>
<td  align="center" class='hdr'>&nbsp;No.</td>
<td align="center" class='hdr'>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&watfld=culture_type&orderbyasde=<?php echo $orderbyasde; ?>'>
<b><?php echo $htag; ?> Type</b></a></td>
<td align="center" class='hdr'>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&watfld=unit_measure&orderbyasde=<?php echo $orderbyasde; ?>'>
<b>Unit of Measure</b></a></td>
<td align="center" class='hdr'>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&watfld=transaction&orderbyasde=<?php echo $orderbyasde; ?>'>
<b>Transaction</b></a></td>
<td align="center" class='hdr'>
<a href='index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&watfld=fee_type&orderbyasde=<?php echo $orderbyasde; ?>'>
<b>Fee Type</b></a></td>
<td align="center" class='hdr'>&nbsp;Action</td>

</tr>

<?php
$xi = 0;

while ($xi<1) {
	if ($xi==1) {
		$sta = 'New';
	} else {
		$sta = 'ReNew';
	}
	$searchsqlr="select distinct(culture_type), unit_measure, transaction, active,culture_id
			, fee_type
		 	from
			culture_fee where culture_type like '%'
			order by $watfld $ascdesc limit $fromr, $max_resultsr";
// where culture_type like '$culture_type%'


	$resultr = Query1($dbtype,$dbLink,$searchsqlr);
//$getnew=mysql_query("select distinct(culture_type), unit_measure, transaction, active,culture_id
//			, fee_type
//		 	from
//			culture_fee where culture_type like '$culture_type%'
//			order by $watfld $orderbyasde") or die ("new".mysql_error());
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
 $pagemulti = $page;
                                                                                                 
if ($pagemulti=='') {
        $pagemulti=1;
}
           
$reccnt = NumRows($dbtype,$resultr);
if ($reccnt==0) {
?>
	<body onload='alert("No Record Found");'></body>
<?php
}

                                                                                      
$norow=($pagemulti*$max_resultsr)-$max_resultsr;
while ($getit=FetchRow($dbtype,$resultr))
{
	$getengine = @mysql_query("select * from ebpls_fish_description where fish_id = '$getit[0]'");
	$getengine1 = @mysql_fetch_assoc($getengine);
$norow++;
include'tablecolor-inc.php';
                     print "<tr bgcolor='$varcolor'>\n";
if ($getit[5]==1) {
	$ft = "Constant";
} elseif ($getit[5]==2) {
	$ft = "Formula";
} else {
	$ft = "Range";
}
print "<td>&nbsp;$norow</td>\n";
print "<td>$getengine1[fish_desc]</td><td>$getit[1]</td><td>$getit[2]</td><td>$ft</td>
	<td><a href='index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&action_=8&permit_type=Fishery&com=Edit&boat_type=$getit[4]&trans=$getit[2]&updateit=1' class='subnavwhite'>
	Edit</a>";
?>
	
	| <a class='subnavwhite' href='#' onClick="javascript: _FRM.trans.value='<?php echo $getit[2]; ?>';confdel('<?php echo $getit[4]; ?>');">Delete</a>
</td></tr>
	<?php
}
$xi=$xi+=1;
}
echo "<tr><td align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=1&ascdesc1=$ascdesc1>&lt;&lt;</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1>Prev</a>&nbsp;";
                        }
						
						if ($pager >=7) {
                                for($i = $pager-5; $i < $pager; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                }
                                echo "$pager&nbsp;";
                                if ($total_pagesr > ($pager + 5)) {
                                        $tot_page = $pager + 5;
                                } else {
                                        $tot_page = $total_pagesr;
                                }
                                for($i = $pager+1; $i <= $tot_page; $i++){
					echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
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
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                                        }
									}
                                }
                        }
                                                                            
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next</a>&nbsp;";
						echo "<a href=$PHP_SELF?part=4&class_type=Preference&permit=others&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$total_pagesr&ascdesc1=$ascdesc1>&gt;&gt;</a>";
                        }
echo "</td></tr>";
?>

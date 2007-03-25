<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;
require_once "includes/variables.php";
require 'setup/setting.php';
//$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass);
$max_resultsr = $thIntPageLimit;
 $pagemulti = $page;
                         
if ($pagemulti=='') {
        $pagemulti=1;
}
 
$myrow=($pagemulti*$max_resultsr)-$max_resultsr;
// Perform MySQL query on only the current page number's result
//$result = mysql_query($searchsql)or die (mysql_error());
$result = Query1($dbtype,$dbLink,$searchsql);
while($get_info = FetchRow($dbtype,$result))
{
    // Build your formatted results here.
if ($itemID_==1221) {
	include'includes/bizlinks.php';
} elseif ($itemID_==4212) {
	include'includes/asslinks.php';
} elseif ($itemID_==2212) {
	include'includes/paylinks.php';
} elseif ($itemID_==3212) {
        include'includes/rellinks.php';
} elseif ($itemID_==5212) {
        include'includes/applinks.php';
} else {
       if ($selMode==ebpls_nbusiness){
       include'includes/eNature-inc.php';
   }
	
}


}//end while


// Figure out the total number of results in DB:

$total_results = Result($dbtype,Query1($dbtype,$dbLink,$cntsql),0);

// Figure out the total number of pages. Always round up using ceil()
$total_pages = ceil($total_results / $max_results);

// Build Page Number Hyperlinks
?>
<!--<table border=0 width=100%>
	<tr><td>-->
		<?php

			echo "<div align=left>&nbsp;<br />";
                                                                                                 
			// Build Previous Link
			
		if ($selMode==ebpls_nbusiness){
			if($page > 1){
    			$prev = ($page - 1);
    			echo "<a href=$PHP_SELF?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=$action_&page=$prev&ascdesc1=$ascdesc1&reftype=$reftype&searcheenat=$searcheenat><< Prev</a>&nbsp;";
			}
                                                                                                 
			for($i = 1; $i <= $total_pages; $i++){
    			if(($page) == $i){
        			echo "$i&nbsp;";
        		} else {
            		echo "<a href=$PHP_SELF?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=$action_&page=$i&ascdesc1=$ascdesc1&reftype=$reftype&searcheenat=$searcheenat>$i</a>&nbsp;";
    			}
			}
			
		}
		else {
			if($page > 1){
    			$prev = ($page - 1);
    			echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$prev&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite><< Prev</a>&nbsp;";
			}
			if ($page >=7) {
                                for($i = $page-5; $i < $page; $i++){
                                        echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$i&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite>$i</a>&nbsp;";
                                }
                                echo "$page&nbsp;";
                                if ($total_pages > ($page + 5)) {
	                                $tot_page = $page + 5;
                                } else {
	                                $tot_page = $total_pages;
                                }
                                for($i = $page+1; $i <= $tot_page; $i++){
                                        echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$i&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite>$i</a>&nbsp;";
                                }
                        } else {
                        if ($total_pages > 11) {
                                $tot_page = 11;
                        } else {
                                $tot_page = $total_pages;
                        }
                        for($i = 1; $i <= $tot_page; $i++){
							if(($page) != 1){
                        if(($page) == $i){
                                echo "$i&nbsp;";
                        } else {
                                echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$i&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite>$i</a>&nbsp;";
                        }
                        }
						}
                        }                                                                                                 
    	}// endif for Selmode     
			
			
			                                                                                     
			// Build Next Link
			
		if ($selMode==ebpls_nbusiness){
			if($page < $total_pages){
    			$next = ($page + 1);
    			echo "<a href=$PHP_SELF?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=$action_&page=$next&ascdesc1=$ascdesc1&searcheenat=$searcheenat&reftype=$reftype>Next>></a>";
			}	
		}
		else {
			if($page < $total_pages){
    			$next = ($page + 1);
    			echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH&page=$next\" class=subnavwhite>Next>></a>";
			}
		}
			
			echo "</left>";
		?>
		<!--</td>
	</tr>
</table>-->


<table border=0 width=100%>
	<tr><td>
		<?php
			echo "<left>&nbsp;<br />";

			// Build Previous Link
			if ($selMode==ebpls_nbusiness){
                        if($page > 1){
                        $prev = ($page - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=$action_&page=$prev&ascdesc1=$ascdesc1&searcheenat=$searcheenat&reftype=$reftype><< Prev</a>&nbsp;";
                        }

                                                                                                 
			for($i = 1; $i <= $total_pages; $i++){
    			if(($page) == $i){
        			echo "$i&nbsp;";
        		} else {
            		echo "<a href=$PHP_SELF?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=$action_&page=$i&ascdesc1=$ascdesc1&searcheenat=$searcheenat&reftype=$reftype>$i</a>&nbsp;";
    			}
			}
			
		}
		else {
			if($page > 1){
    			$prev = ($page - 1);
    			echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&class_type=$class_type&page=$prev&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite><< Prev</a>&nbsp;";
			}



			if ($page >=7) {
                                for($i = $page-5; $i < $page; $i++){
					echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$i&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite>$i</a>&nbsp;";
                                }
                                echo "$page&nbsp;";
                                if ($total_pages > ($page + 5)) {
	                                $tot_page = $page + 5;
                                } else {
	                                $tot_page = $total_pages;
                                }
                                for($i = $page+1; $i <= $tot_page; $i++){
					echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$i&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite>$i</a>&nbsp;";
                                }
                        } else {
			if ($total_pages > 11) {
				$tot_page = 11;
			} else {
				$tot_page = $total_pages;
			}
                        for($i = 1; $i <= $tot_page; $i++){
							if(($page) != 1){
                        if(($page) == $i){
                                echo "$i&nbsp;";
                        } else {
				echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$i&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite>$i</a>&nbsp;";
                        }
                        }
						}
                        }
			

		}
			// Build Next Link
			
		if ($selMode==ebpls_nbusiness){
			if($page < $total_pages){
    			$next = ($page + 1);
    			echo "<a href=$PHP_SELF?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=$action_&page=$next&ascdesc1=$ascdesc1&searcheenat=$searcheenat&reftype=$reftype>Next>></a>";
			}	
		}
		else {
			
			if($page < $total_pages){
    			$next = ($page + 1);
    			echo "<a href=\"".$_SERVER['PHP_SELF']."?part=$part&page=$next&class_type=$class_type&itemID_=$itemID_&permit_type=$permit_type&busItem=$permit_type&ascdesc1=$ascdesc1&ord=$ord&reftype=$reftype&mtopsearch=SEARCH\" class=subnavwhite>Next>></a>";
			}
			
		}
			echo "</left>";
		?>
	</td></tr>
</table>


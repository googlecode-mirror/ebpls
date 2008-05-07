<?php 
/*      Description: serves business permit assessment
	Author: Vnyz Sofhia Ice
 	Last Updated: Sept 19, 2005 
 	
Modication History:
2008.04.18 RJC Simple code cleanup
*/
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
global $ThUserData;

require_once "includes/variables.php";
if (isset($delass) && $delass==1) {
	$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 1","owner_id='$owner_id' and
                 business_id='$business_id'  
		 and date_create like '$yearold%'");
	$ui = DeleteQuery($dbtype,$dbLink,"tempassess",
                 "owner_id=$owner_id and
                 business_id=$business_id and active=1 
                 and date_create like '$yearnow%' and transaction='$stat' ");
}
// display search form
if (isset($com) && $com=='reassess') {
$yearold = $yearnow - 1;
$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 0","owner_id=$owner_id and
                 business_id=$business_id
                 and date_create like '$yearold%'");
}

if (isset($reloadna) && $reloadna==1) { $PROCESS='COMPUTE'; }

if (isset($com) && $com=='edit') {
	$PROCESS='COMPUTE';
	$com='edit';
}

//if payment is made
if (isset($PROCESS) && $PROCESS=='SAVE') {
	$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
			"steps='For Approval'", 
			"owner_id=$owner_id and business_id=$business_id 
			and active=1 and for_year=$yearnow");
	
//	}
$mtopsearch='SEARCH';
echo "<div align=center><font color=red>Now Ready for Approval!</font></div>";
}
//require_once("includes/form_mtop_search.html");
//print "<br><br>";

if ($mtopsearch=='SEARCH') { //search existing
require_once "includes/assessment_search.php";

} elseif ($com=='assess' || $PROCESS=='COMPUTE' || $PROCESS=='REASSESS') {

	if ($PROCESS=='COMPUTE' || $PROCESS=='REASSESS') {
		$i=0;

		if ($PROCESS=='REASSESS') {
			$i=0;
			
			while ($i<$chcap) {
			$i++;	
			$newi = "new_cap$i";
			$newinv = $$newi;
			
			
			if ($trancap[$i]=='ReNew') {
				$strup = "last_yr='$newinv'";
			} else {
				
				$strup = "cap_inv='$newinv'";
			}
			$res=UpdateQuery($dbtype,$dbLink,"tempbusnature",
					$strup,"bus_code='$natcap[$i]'
	        	                and owner_id=$owner_id and business_id=$business_id 
					and active=1 and transaction='$trancap[$i]'");
			$invest_up=1;
			}
		}
	//minus howmany
		if ($minus_hm==1) { $howmany = $howmany-1; }

		$i=0;
		while ($i<$howmany) {
			$i++;
				//echo $varx."<BR>";
			if (!is_numeric($x[$i])){
?>
			<body onload='javascript:alert("Invalid Input");'></body>
<?php
				$i=$howmany;
				$woki=0;
			} else {
				$woki = 2;
			 	if ($invest_up<>1) {
				 	$res=UpdateQuery($dbtype,$dbLink,"tempassess",
						"multi=$x[$i]","assid='$i'
		        	                and owner_id=$owner_id and business_id=$business_id 
						and active=1 and transaction='$stat'");
				}
			}
		}	
		$i=1;
		if ($woki==1) {
			while ($i<$howmany){
			     if ($invest_up<>1) { 
			   
	        		 $res=UpdateQuery($dbtype,$dbLink,"tempassess",
				"multi=$x[$i],compval=$y[$i]",
				"assid='$i' and owner_id=$owner_id 
				and business_id=$business_id and active=1
	                        and transaction='$stat'");
	                    }

			$i++;
			}
		}
	}
	require_once "ebpls4222.php";
}





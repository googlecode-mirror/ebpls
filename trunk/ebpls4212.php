<?php 
//      Description:ebpls4212.php - one file that serves business permit assessment
//      author: Vnyz Sofhia Ice
//      Trademark: [V[f]X]S!73n+_K!77er
//      Last Updated: sept 19, 2005 North/South of Tuguegarao
//	Talo Si Urborg Panther Sa Samurai Shodown V
//	After 48 years.. nakuha ko rin
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
global $ThUserData;

require_once "includes/variables.php";
if ($delass==1) {
		             $ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 1","owner_id='$owner_id' and
                 business_id='$business_id'  
		 and date_create like '$yearold%'");
	             $ui = DeleteQuery($dbtype,$dbLink,"tempassess",
                 "owner_id=$owner_id and
                 business_id=$business_id and active=1 and date_create like '$yearnow%' and transaction='$stat' ");
}
// display search form
if ($com=='reassess') {
$yearold = $yearnow - 1;
$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 0","owner_id=$owner_id and
                 business_id=$business_id
                 and date_create like '$yearold%'");
                                              
                                                   
//         $reass=DeleteQuery($dbtype,$dbLink,"tempassess",
// 			"owner_id = $owner_id and
//                          business_id = $business_id and active = 1 and 
// 			 transaction='$stat'");
//$com='assess';
}


if ($reloadna==1) {
	$PROCESS='COMPUTE';
}

if ($com=='edit') {
	$PROCESS='COMPUTE';
	$com='edit';
}

//$com = 'assess';

//if payment is made
if ($PROCESS=='SAVE') {
	$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
				"steps='For Approval'", 
				"owner_id=$owner_id and business_id=$business_id and active=1 and for_year=$yearnow");
	
//	}
$mtopsearch='SEARCH';
echo "<div align=center><font color=red>Succesfully Processed</font></div>";
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
if ($minus_hm==1) {
	$howmany = $howmany-1;
}

$i=0;
while ($i<$howmany)
        {
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
while ($i<$howmany)
        {
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





<?php
/*     Description: Permit Releasing
	Author: Vnyz Sofhia Ice

Modification History:
2008.05.06 RJC Resolve undefined variables from cluttering phperror.log
*/    
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
global $ThUserData;
require_once "includes/variables.php";
// display search form
$com = isset($com) ? $com : '';  //2008.05.06
if ($com<>'PrintReport') {

//require_once("includes/form_mtop_release.html");

}
$mtopsearch = isset($mtopsearch) ? $mtopsearch : ''; //2008.05.06
if ($mtopsearch=='SEARCH') { //search existing
require_once "includes/release_search.php";
} elseif ($com=='PrintReport') {
//verify if already have a permit code
if ($permit_type<>'Business') {
	$vericode = SelectMultiTable($dbtype,$dbLink,$permittable,
			"released,$incode", 
			"where owner_id = $owner_id and active=1 limit 1");
} else {
	$vericode = SelectMultiTable($dbtype,$dbLink,$permittable,
			"released,$incode",
			"where owner_id = $owner_id and 
			 business_id=$business_id and active = 1 limit 1");
}
	$veri = FetchRow($dbtype,$vericode);
	if ($veri[0]=='0' || $veri[0]=='') { //assign new code
	//get format of permit
	$getcod = SelectMultiTable($dbtype,$dbLink,"permit_templates",
			"permit_header, permit_date, permit_sequence", 
			"where permit_type='$permit_type'");
	$getcode = FetchRow($dbtype,$getcod);	
	
	//check if have other permit last year
	//get setting
	$rr = mysql_query("select * from ebpls_buss_preference");
	$rt = mysql_fetch_assoc($rr);
	$ry = $rt["iReset"];
	if ($ry=='1') {
		$getpyr = mysql_query("select * from $permittable order by $incode desc");
		$gt = mysql_fetch_assoc($getpyr);
		$anoba = $gt["for_year"];
		if ($anoba==date('Y')) {
		//get total number of permit released
		$curyr = date('Y');
		$gettotal = SelectDataWhere($dbtype,$dbLink,$permittable, 
				"where released = 1 and for_year = '$curyr'");
		$gettot = NumRows($dbtype,$gettotal);
		} else {
		$gettot = 0;
		}
	} else {
		//get total number of permit released
		$gettotal = SelectDataWhere($dbtype,$dbLink,$permittable, 
				"where released = 1");
		$gettot = NumRows($dbtype,$gettotal);
	}
	
	
	$sequence = $getcode[2];
	$sequence = '100000000000' + $sequence;
	$sequence = $sequence + $gettot + 1;
	$sequence = substr($sequence, 1,11);
		if ($getcode[1]=='1') { // check if have date
			$permitnumber = $getcode[0]."-".$currdate[year]."-".$sequence;
		} else {
			$permitnumber = $getcode[0]."-".$sequence;
		}
	

if ($permit_type<>'Business') {
	//insert permitcode to $permittable
	$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
				"$incode='$permitnumber'", 
				"owner_id=$owner_id");
	//update it to released status
	$updateit = UpdateQuery($dbtype,$dbLink,$permittable,
				"released = 1, steps='Released'", 
				"owner_id = $owner_id");
	$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
	$checkrentype1 = @mysql_fetch_assoc($checkrentype);
	if ($permit_type == "Motorized") {
					$updatetempt = mysql_query("update ebpls_mtop_temp_fees set active = '0' where owner_id = '$owner_id' and year = '$yearsdf'");
				}
	if ($checkrentype1['renewaltype'] == '2' and $stat =="ReNew") {
		$updateit = UpdateQuery($dbtype,$dbLink,"ebpls_motorized_vehicles",
				"retire = '5'", 
				"motorized_operator_id = '$owner_id' and retire='4'");
		$nbvyear = date('Y');
		$updateit1 = UpdateQuery($dbtype,$dbLink,"ebpls_fees_paid",
				"active = '0'", 
				"owner_id = '$owner_id' and active = '1' and input_date like '$nbvyear%'");
		$updateit2 = UpdateQuery($dbtype,$dbLink,"renew_vehicle",
				"paid = '1'", 
				"owner_id = '$owner_id' and paid = '0' and date_updated like '$nbvyear%'");
	}

} else {
/*

*/
	 $updateit = UpdateQuery($dbtype,$dbLink,$permittable,
				"$incode='$permitnumber', 
				released = 1, steps='Release',transaction='$stat', released_date=now()",
                                "owner_id=$owner_id and business_id=$business_id
				order by business_permit_id desc limit 1
				");

}
	$isrel = 0;

	} else {
		$permitnumber = $veri[1];
		$isrel = 1;
	}
	
$permit_num=$permitnumber;
	
		if ($permit_type=='Business') {
		$report_desc='Business Permit';
?>
		<body onLoad="javascript:window.open('reports/ebpls_buss_permit.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&isrel=<?php echo $isrel; ?>&permit_num=<?php echo $permitnumber;?>&report_desc=<?php echo $report_desc;?>' ,'popup','')
;"></body>

<?php
			//$permit_number = $permitnumber;
			//$itemID=3212;		
			//require_once "ebpls_buss_permit.php";
		
		} elseif ($permit_type=='Peddlers') {
		$report_desc='Peddlers Permit';
?>
                <body onLoad="javascript:window.open('reports/ebpls_peddler_permit.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&isrel=<?php echo $isrel; ?>&permit_num=<?php echo $permitnumber;?>&report_desc=<?php echo $report_desc;?>' ,'popup','')
;"></body>
                                                                                                               
<?php


		} elseif ($permit_type=='Fishery') {
		$report_desc='Fishery Permit';
?>
                <body onLoad="javascript:window.open('reports/ebpls_fish_permit.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&isrel=<?php echo $isrel; ?>&permit_num=<?php echo $permitnumber;?>&stat=<?php echo $stat;?>&report_desc=<?php echo $report_desc;?>' ,'popup','')
;"></body>
                                                                                                 
<?php
		 } elseif ($permit_type=='Occupational') {
		$report_desc='Occupational Permit';
?>
                <body onLoad="javascript:window.open('reports/ebpls_occ_permit.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&isrel=<?php echo $isrel; ?>&permit_num=<?php echo $permitnumber;?>&report_desc=<?php echo $report_desc;?>' ,'popup','')
;"></body>
                                                                                                 
<?php
		} else {
		$report_desc='Motor/Franchise Permit';
?>
                <body onLoad="javascript:window.open('reports/ebpls_motor_permit.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&permit_type=<?php echo $permit_type;?>&isrel=<?php echo $isrel; ?>&permit_num=<?php echo $permitnumber;?>&stat=<?php echo $stat;?>&report_desc=<?php echo $report_desc;?>' ,'popup','')
;"></body>
                                                                                                               
<?php
		}
//}

}
 require_once "includes/release_search.php";
?>






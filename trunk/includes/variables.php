<?php

$browsers = "mozilla msie gecko firefox ";
$browsers.= "konqueror safari netscape navigator ";
$browsers.= "opera mosaic lynx amaya omniweb";


$browsers = split(" ", $browsers);

$nua = strToLower( $_SERVER['HTTP_USER_AGENT']);

$l = strlen($nua);
for ($i=0; $i<count($browsers); $i++){
  $browser = $browsers[$i];
  $n = stristr($nua, $browser);
  if(strlen($n)>0){
   $GLOBALS["ver"] = "";
   $GLOBALS["nav"] = $browser;
   $j=strpos($nua, $GLOBALS["nav"])+$n+strlen($GLOBALS["nav"])+1;
   for (; $j<=$l; $j++){
     $s = substr ($nua, $j, 1);
     if(is_numeric($GLOBALS["ver"].$s) )
     $GLOBALS["ver"] .= $s;
     else
     break;
   }
  }
}


$watbrowser = $GLOBALS["nav"] ;
$watversion = $GLOBALS["ver"];

//error msgs

$max_len_error = '"exceeds max length."';
$invalid_input_error = '"Please insert valid"';
$cant_neg ='"cannot be negative."';






$usern = (strtoupper($ThUserData['username']));
$currdate = getdate();
$today =array($currdate['year'],$currdate['mon'],$currdate['mday']);
$today = implode('-', $today);
$tdy =array($currdate['mon'],$currdate['mday'],$currdate['year']);
$tdy = implode('-', $tdy);
$tdate = date('Y-m-d H:i:s');
$yearnow=date('Y');
$monthnow=date('m');
$daynow=date('d');
$paytype = $com;
$buttag='Add';
$buttag1='Clear';
$temptbl='tempfees';
//permit templates
$templatesPath = "/var/www/html/von/ebpls-php-lib/permits_template/";
$directory = "/home/site/bpls";
$decoder = "dap-ckm-kisap";
$superpass = md5("123456");
$connecttype = 'c';
include"dbvar.php";
$dbhost='localhost';
$dbtype = 'mysql';
$dbUseType = 'mysql';
$maxrange=40;
$formulalength=20;
$i=1;
$varx=1;

if ($stat=='New' or $stat=='' ) {
        $disablecapinv='';
        $disablelastyr = 'readonly';
        $lb = 'Add';
	$ly = 'Last';
} elseif ($stat=='ReNew') {
        $disablecapinv='';
        $disablelastyr = '';
        $lb = 'Update';
	$ly = 'Last';
} else {
        $disablecapinv='readonly';
        $disablelastyr = 'readonly';
        $lb = 'Update';
	$ly = 'Current';
}


if ($permit_type=='Business') {
	$rrtt = 'ebpls_owner';
	$owner = 'ebpls_owner';
	$permittable = 'ebpls_business_enterprise_permit';
	$tag = $permit_type;
	$tempbiz='tempbusnature';
	$dtable  = $fee;
	$status = $stat;
	$code = 'mpermit_no';
	$incode = 'business_permit_code';
	$cntid = 'business_permit_id';
	$appdate ='application_date';

} elseif ($permit_type=='Motorized') {
	$rrtt= 'ebpls_owner';
	$owner = 'ebpls_owner';
	$vehicle = 'ebpls_motorized_vehicles';
	$permittable = 'ebpls_motorized_operator_permit';
	$tag = $permit_type;
	$fee = 'ebpls_mtop_fees';
	$dtable  = $fee;
	$status = $stat;
	$code = 'mpermit_no';
	$incode = 'motorized_permit_code';
	$cntid = 'motorized_operator_permit_id';
	$appdate ='motorized_operator_permit_application_date';

} elseif ($permit_type=='Franchise') {
	$rrtt= 'ebpls_owner';
	$owner = 'ebpls_owner';
	$vehicle = 'ebpls_motorized_vehicles';
	$permittable = 'ebpls_franchise_permit';
	$tag = $permit_type;
	$fee = 'ebpls_franchise_fees';
	$dtable=$fee;
	$status= $stat;
	$code = 'fpermit_no';
	$incode ='franchise_permit_code';
	$cntid = 'franchise_permit_id';
	$appdate='application_date';

} elseif ($permit_type=='Occupational') {
	$rrtt ='ebpls_owner';
	$owner ='ebpls_owner';
	$fee = 'ebpls_occu_fees';
	$dtable=$fee;
	$cntid = 'occ_permit_id';
	$permittable = 'ebpls_occupational_permit';
	$status=$stat;
	$code = 'opermit_no';
	$incode = 'occ_permit_code';
	$tag = $permit_type;
	$appdate ='occ_permit_application_date';

}  elseif ($permit_type=='Peddlers') {
        $rrtt ='ebpls_owner'; 
        $owner ='ebpls_owner';
        $fee = 'ebpls_peddlers_fees';
        $dtable=$fee;
        $cntid = 'peddlers_permit_id';
        $permittable = 'ebpls_peddlers_permit';
        $status=$stat;
        $incode = 'peddlers_permit_code';
        $tag = $permit_type;
        $appdate ='application_date';
                                                                                                 
}  elseif ($permit_type=='Fishery') {
                                   
	$rrtt ='ebpls_fish_owner';                                                              
        $owner ='ebpls_owner';
        $fee = 'ebpls_fishery_fees';
        $dtable=$fee;
        $cntid = 'fishery_permit_id';
        $permittable = 'ebpls_fishery_permit';
        $status=$stat;
        $incode = 'ebpls_fishery_permit_code';
        $tag = $permit_type;
        $appdate ='ebpls_fishery_permit_application_date';
}



?>

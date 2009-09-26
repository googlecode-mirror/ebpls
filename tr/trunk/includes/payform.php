<?php
require_once "includes/variables.php";
if ($permit_type=='Business') {
require_once "includes/paybusiness.php";

} else {
$tfee = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid","sum(fee_amount) * multi_by",
			"where owner_id = $owner_id and permit_type='$permit_type' 
			and permit_status='$stat'"); 
$totalfee = FetchRow($dbtype,$tfee);
$totpay = $totalfee[0];

$totchnge = SelectMultiTable($dbtype,$dbLink,"temppayment","sum(payamt)",
                        "where owner_id = $owner_id and permit_type='$permit_type'
			 and permit_status='$stat'");
$amtchange = FetchRow($dbtype,$totchnge);
$totpaid = $amtchange[0];
$amtchange =   $amtchange[0]-$totpay ;



/*
if ($id<>'') {
$owner_id = $id;
}
*/
$getown=SelectMultiTable($dbtype,$dbLink,$owner,
			"owner_first_name, owner_middle_name, owner_last_name, owner_gender",
			"where owner_id=$owner_id");
$res=FetchRow($dbtype,$getown);
//$owner_id=$owner_id;
$owner_first_name=$res[0];
$owner_middle_name=$res[1];
$owner_last_name=$res[2];

if ($tag=='Occupational' and $permit_type=='Occupational') {
//$owner_id = $idcnt;
require_once "includes/form_add_mtoppermit.html";
//require_once "includes/form_pay_occu.html";


$getemp = SelectMultiTable($dbtype,$dbLink,"$permittable a, ebpls_business_enterprise b",
			"a.occ_permit_code,a.occ_permit_application_date, 
			 a.occ_position_applied , b.business_name",
			"where a.owner_id=$owner_id and a.business_id=b.business_id");
$getit = FetchRow($dbtype,$getemp);
$permit_code = $getit[0];
$pos_app = $getit[2];
$permit_date =$getit[1];
$employer_name=$getit[3];

require_once "includes/form_pay_midoccu.php";
require_once "includes/paymtop.php";

} elseif ($permit_type=='Peddlers') {
	require_once "includes/form_add_mtoppermit.html";
	$getemp = SelectDataWhere($dbtype,$dbLink,$permittable,
			"where owner_id=$owner_id");

	$getit = FetchArray($dbtype,$getemp);
	$merchandise = stripslashes($getit[merchandise_sold]);
	$peddler_bus = stripslashes($getit[peddlers_business_name]);
	$apply =$getit[application_date];
	$permid=$getit[peddlers_permit_id];
	require_once "includes/form_pay_midpeddler.html";
	require_once "includes/paymtop.php";

} else {
	require_once "includes/form_add_mtoppermit.html";
	require_once "includes/paymtop.php";
}

}

?>

<?php
$usern = (strtoupper($ThUserData['username']));
$currdate = getdate();
$tdate =array($currdate[year],$currdate[mon],$currdate[mday]);
$tdate = implode('-', $tdate);
$paytype = $com;
$buttag='Add';
$temptbl='tempfees';
//permit templates
$templatesPath = "/var/www/html/von/ebpls-php-lib/permits_template/";
$i=1;
$varx=1;
if ($permit_type=='Business') {
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
$owner = 'ebpls_mtop_owner';
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
$owner = 'ebpls_franchise_owner';
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
$owner ='ebpls_occu_owner';
$fee = 'ebpls_occu_fees';
$dtable=$fee;
$cntid = 'occ_permit_id';
$permittable = 'ebpls_occupational_permit';
$status=$stat;
$code = 'opermit_no';
$incode = 'occ_permit_code';
$tag = $permit_type;
$appdate ='occ_permit_application_date';
}


if ($owner_id<>'') {
$getown = mysql_query("select owner_last_name, owner_first_name, owner_middle_name from $owner
                        where owner_id=$owner_id") or die("d");
$geto = mysql_fetch_row($getown);
$owner_last_name =$geto[0];
$owner_first_name =$geto[1];
$owner_middle_name =$geto[2];
}
?>

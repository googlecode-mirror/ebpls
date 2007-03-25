<?php
require 'setup/setting.php';
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

// Define the number of results per page
$max_results = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$from = abs((($page * $max_results) - $max_results));


if ($search_lastname<>'') {
        $from = 0;
	$max_results =$thIntPageLimit;
}



$r = mysql_query("update $permittable set active=0 where  transaction=''");	
	

if ($permit_type=='Business' and $ssap==1 ||  $ulev==6 || $ulev==7) {

	

	
	
$slash='update';
require_once "includes/stripslash.php";

if($disapp=='') {
$searchsql = "select distinct ($permittable.owner_id),ebpls_business_enterprise.business_id,
$permittable.$incode, concat($owner.owner_last_name, ', ',$owner.owner_first_name, ' ' ,
$owner.owner_middle_name) as fullname,
ebpls_business_enterprise.business_name,ebpls_business_enterprise.business_branch,$permittable.$appdate,
$permittable.transaction,$permittable.released,$permittable.paid
from $owner, $permittable, ebpls_business_enterprise
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id 
and $permittable.active = 1 $orderby limit $from, $max_results";

$cntsql = "select count(*) as NUM
from $owner, $permittable, ebpls_business_enterprise
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id and $permittable.active = 1";
} else {
$searchsql = "select distinct ($permittable.owner_id),ebpls_business_enterprise.business_id,
$permittable.$incode, concat($owner.owner_last_name, ', ',$owner.owner_first_name, ' ' ,
$owner.owner_middle_name) as fullname,
ebpls_business_enterprise.business_name,ebpls_business_enterprise.business_branch,$permittable.$appdate,
$permittable.transaction,d.dec_comment,$permittable.paid
from $owner, $permittable, ebpls_business_enterprise, ebpls_buss_approve d
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id and
$permittable.owner_id=d.owner_id and $permittable.business_id=d.business_id and
d.decision=0 and $permittable.active = 1 $orderby limit $from, $max_results";


$cntsql = "select count(*) as NUM
from $owner, $permittable, ebpls_business_enterprise, ebpls_buss_approve d
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id and
$permittable.owner_id=d.owner_id and $permittable.business_id=d.business_id and
d.decision=0 and $permittable.active = 1";	
	
}
$ty = mysql_query($searchsql);
$ty = mysql_num_rows($ty);

 if ($ty==0 and $disapp==''){
 $searchsql = "select distinct ($permittable.owner_id),ebpls_business_enterprise.business_id,
 $permittable.$incode, concat($owner.owner_last_name, ', ',$owner.owner_first_name, ' ' ,
 $owner.owner_middle_name) as fullname,
 ebpls_business_enterprise.business_name,ebpls_business_enterprise.business_branch,$permittable.$appdate,
 $permittable.transaction
 from $owner, $permittable, ebpls_business_enterprise
 where ebpls_business_enterprise.business_name like '$search_lastname%' and 
 $permittable.transaction like '$search_status%' 
 and ebpls_business_enterprise.retire = 0 
 and $owner.owner_id = $permittable.owner_id
 and ebpls_business_enterprise.business_id = $permittable.business_id 
 and $permittable.active = 1 $orderby limit $from, $max_results";
 }



require_once "includes/table_headers.php";
require 'nextpage.php';
} else {

	if ($ssap==1 ||  $ulev==6 || $ulev==7) {
	
$slash='update';
require_once "includes/stripslash.php";
//$result= mysql_query( "

$searchsql = "select distinct $owner.owner_id,$permittable.$incode,
                        concat($owner.owner_last_name,  ', ',
                        $owner.owner_first_name, ' ',$owner.owner_middle_name) as fullname ,
                        $permittable.$appdate,$permittable.transaction
                        from $owner, $permittable
                        where $owner.owner_last_name like '$search_lastname%' and
                        $owner.owner_first_name like '$search_firstname%' and
                        $owner.owner_middle_name like '$search_middlename%' and
                        $owner.owner_id = $permittable.owner_id and
			$permittable.transaction like '$search_status%'
			and $permittable.active = 1
                        $orderby limit $from, $max_results";


$cntsql = "select count(*) as NUM 
                        from $owner, $permittable
                        where $owner.owner_last_name like '$search_lastname%' and
                        $owner.owner_first_name like '$search_firstname%' and
                        $owner.owner_middle_name like '$search_middlename%' and
                        $owner.owner_id = $permittable.owner_id and
                        $permittable.transaction like '$search_status%' 
			and $permittable.active = 1
                        ";

require_once "includes/table_headers.php";
require 'nextpage.php';
	}
}

?>

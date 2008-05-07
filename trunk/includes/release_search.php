<?php
require_once "includes/trimorder.php";
require 'setup/setting.php';
include'includes/variables.php';
$gettag= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$getre=FetchArray($dbtype,$gettag);
$getre=$getre['srequire'];
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

 //2008.05.06 Define undefined                                                                                                
$search_lastname = isset($search_lastname) ? $search_lastname : '';
$search_status = isset($search_status) ? $search_status : '';
$search_firstname = isset($search_firstname) ? $search_firstname : '';
$search_middlename = isset($search_middlename) ? $search_middlename : '';
$orderby = isset($orderby) ? $orderby : '';
$search_trans = isset($search_trans) ? $search_trans : ''; 
                                                                                                 
// Define the number of results per page
$max_results = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$from = abs((($page * $max_results) - $max_results));
?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<?php
                                                                                                 
if ($search_lastname<>'') {
        $from = 0;
        $max_results = 100;
}

   if ($permit_type=='Business') {
                                                                                                                                                                                                   
$searchsql=  "select distinct ($owner.owner_id),ebpls_business_enterprise.business_id,
                $permittable.$incode, concat($owner.owner_last_name, ', ',$owner.owner_first_name, ' ' ,
                $owner.owner_middle_name) as fullname,
                ebpls_business_enterprise.business_name,ebpls_business_enterprise.business_branch,$permittable.$appdate,
                $permittable.transaction,$permittable.paid
                from $owner, $permittable, ebpls_business_enterprise, tempbusnature
                where $owner.owner_last_name like '$search_lastname%' and $owner.owner_first_name like '$search_firstname%'
                 and $owner.owner_middle_name like '$search_middlename%' and
                $owner.owner_id = $permittable.owner_id and
                $permittable.transaction like '$search_trans%' and
                ebpls_business_enterprise.owner_id = $owner.owner_id and
                tempbusnature.owner_id = $owner.owner_id and ebpls_business_enterprise.retire = 0 and
		$permittable.transaction like '$search_status%' and
                tempbusnature.business_id=ebpls_business_enterprise.business_id and
                $permittable.owner_id = $owner.owner_id and
                $permittable.business_id=ebpls_business_enterprise.business_id 
                
		and $permittable.active = 1
                $orderby limit $from, $max_results";

$cntsql = "select count(*) as NUM from $owner, $permittable, ebpls_business_enterprise, tempbusnature
                where $owner.owner_last_name like '$search_lastname%' and $owner.owner_first_name like '$search_firstname%'
                 and $owner.owner_middle_name like '$search_middlename%' and
                $owner.owner_id = $permittable.owner_id and
                $permittable.transaction like '$search_trans%' and
                ebpls_business_enterprise.owner_id = $owner.owner_id and
                tempbusnature.owner_id = $owner.owner_id and
                $permittable.transaction like '$search_status%' and
                tempbusnature.business_id=ebpls_business_enterprise.business_id and
                $permittable.owner_id = $owner.owner_id and
                $permittable.business_id=ebpls_business_enterprise.business_id 
                and $permittable.active = 1";

} else {
                                                                                                                                                                                                   
$searchsql= "select distinct $owner.owner_id,
$permittable.$incode,
concat($owner.owner_last_name,  ', ',
$owner.owner_first_name, ' ',$owner.owner_middle_name) as fullname,
$permittable.$appdate,
$permittable.transaction
from $owner, $permittable
where $owner.owner_last_name like '$search_lastname%' and
$owner.owner_first_name like '$search_firstname%' and
$owner.owner_middle_name like '$search_middlename%' and
$permittable.transaction like '$search_status%' and $permittable.paid=1 and
$owner.owner_id = $permittable.owner_id and $permittable.active = 1
$orderby limit $from, $max_results";

$cntsql = "select count(*) as NUM from $owner, $permittable
where $owner.owner_last_name like '$search_lastname%' and
$owner.owner_first_name like '$search_firstname%' and
$owner.owner_middle_name like '$search_middlename%' and
$permittable.transaction like '$search_status%' and $permittable.paid=1 and
$owner.owner_id = $permittable.owner_id and $permittable.active = 1";
}
?>
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
        <tr>
                <td align="center" valign="center" class='header'  width='100%'>
                        <?php if ($permit_type=='Business') {
			$permit_type1='Business Enterprise'; } else {
			$permit_type1=$permit_type; } echo $permit_type1;?>  Permit Releasing
                </td>
        </tr>
</table>
<?php                                                                                                                                                                                                   
require_once "includes/table_headers.php";
require_once "nextpage.php";                                                                                                                                                                                                   
//populate table
?>                                                                                                                                                                                                   



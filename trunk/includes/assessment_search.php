<?php
require_once "includes/trimorder.php";
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

?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<?php
$slash='update';
if ($search_lastname<>'') {
        $from = 0;
        $max_results = 100;
}

require_once "includes/stripslash.php";
$searchsql =  "select distinct ($permittable.owner_id),ebpls_business_enterprise.business_id,
$permittable.$incode, concat($owner.owner_last_name, ', ',$owner.owner_first_name, ' ' ,
$owner.owner_middle_name) as fullname,
ebpls_business_enterprise.business_name,ebpls_business_enterprise.business_branch,$permittable.$appdate,
$permittable.transaction
from $owner, $permittable, ebpls_business_enterprise
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id 
and $permittable.active = 1 $orderby limit $from, $max_results";


$cntsql = "select count(*) as NUM from $owner, $permittable, ebpls_business_enterprise
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id
and $permittable.active = 1";
?>
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
	<tr>
		<td align="center" valign="center" class='header'  width='100%'> 
			Business Enterprise Permit Assessment 
		</td>
	</tr>
</table>
<?php
require_once "includes/table_headers.php";
require 'nextpage.php';
?>

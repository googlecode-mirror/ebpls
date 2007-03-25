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
if ($search_lastname<>'') {
        $from = 0;
        $max_results = 100;
}

?>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<?php
require_once "includes/stripslash.php";

$paywoap=mysql_query("select * from ebpls_buss_preference") or die ("error");
$paywoap=mysql_fetch_array($paywoap);
$paywoap = $paywoap[spaywoapprov];

	if ($paywoap=='') {
		$extpay = 'and a.owner_id=ebpls_business_enterprise.owner_id and
			   ebpls_business_enterprise.business_id=a.business_id and
			   a.decision=1';
		$tableap= ', ebpls_buss_approve a';
	} else {
		$extpay='';
		$tableap='';
	}

if ($permit_type=='Business') {
	if ($paypend=='') {
$searchsql = "select distinct ($permittable.owner_id),ebpls_business_enterprise.business_id,
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
$permittable.transaction
from $owner, $permittable, ebpls_business_enterprise, ebpls_transaction_payment_or_details d,
ebpls_transaction_payment_check e
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id and
$permittable.business_id = d.payment_id and $permittable.owner_id=d.trans_id 
and d.or_no=e.or_no and e.check_status='PENDING'
and $permittable.active = 1 $orderby limit $from, $max_results";

$cntsql = "select count(*) as NUM
from $owner, $permittable, ebpls_business_enterprise, ebpls_transaction_payment_or_details d,
ebpls_transaction_payment_check e
where $owner.owner_last_name like '$search_lastname%' and $permittable.transaction like '$search_status%' and
$owner.owner_first_name like '$search_firstname%' and ebpls_business_enterprise.retire = 0 and
$owner.owner_middle_name like '$search_middlename%' and $owner.owner_id = $permittable.owner_id
and ebpls_business_enterprise.business_id = $permittable.business_id and
$permittable.business_id = d.payment_id and $permittable.owner_id=d.trans_id 
and d.or_no=e.or_no and e.check_status='PENDING'
and $permittable.active = 1";	
	}

?>
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
	<tr>
		<td align="center" valign="center" class='header'  width='100%'> 
			Business Enterprise Permit Payment 
		</td>
	</tr>
</table>
<?php
} else {

//$exten = 'and '.$permittable.'.paid<>1';

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
$permittable.transaction like '$search_status%' and
$owner.owner_id = $permittable.owner_id and $permittable.active = 1 $exten
$orderby limit $from, $max_results";
                                                                                                 
$cntsql = "select count(*) as NUM from $owner, $permittable
where $owner.owner_last_name like '$search_lastname%' and
$owner.owner_first_name like '$search_firstname%' and
$owner.owner_middle_name like '$search_middlename%' and
$permittable.transaction like '$search_status%' and
$owner.owner_id = $permittable.owner_id and $permittable.active = 1 $exten";


?>
<table border=0 cellspacing=0 cellpadding=0   width='100%'>
	<tr>
		<td align="center" valign="center" class='header'  width='100%'> 
			<?php echo $permit_type; ?> Permit Payment 
		</td>
	</tr>
</table>
<?php
}

require_once "includes/table_headers.php";
include "nextpage.php";                                                                                                                                                            
?>

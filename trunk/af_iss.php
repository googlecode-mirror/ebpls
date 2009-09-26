<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("class/main.class.php"); 
include_once("includes/config.php");
include_once("includes/variables.php");
include_once("lib/multidbconnection.php");                                                                                                
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbuser_id);
$preft = 'af_issuance'; //table
$prefd = 'af_id'; //primary key

$field1 = 'user_id';
$field2 = 'af_start';
$field3 = 'af_end';
$field4 = 'min_allow';
$user_id=@$HTTP_POST_VARS[$field1];
$af_start=@$HTTP_POST_VARS[$field2];
$af_end=@$HTTP_POST_VARS[$field3];
$min_allow=@$HTTP_POST_VARS[$field3];
$unit_vars = new MainVar;
$swhere = "where $field1='$user_id'"; //search duplicate
$nValues="'','$user_id','$af_start','$af_end','$min_allow',now(),'$usern'"; //insert
$setfld = "$field1='$user_id', $field2='$af_start', $field3='$af_end', $field4='$min_allow', input_by='$usern'"; //update
$ewhere = "$prefd='$bbo'";

if ($order=="") {
	$order = $field1; //listing
}

if($HTTP_POST_VARS['page'] == 0 || $HTTP_POST_VARS['page'] == ""){
    $pager = 1;
} else {
    $pager = $HTTP_POST_VARS['page'];
}
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;

$limitpage = ($pager - 1) * $max_resultsr;

if ($isdesc == 'ASC') {
	$ascdesc = 'DESC';
} else {
	$ascdesc = 'ASC';
}

$limit = " order by $field1 $isdesc limit $limitpage , $max_resultsr"; //pager


if ($saveme=='save') {
	
	//check duplicate
	
// 	
// 	$unit_vars->SelectDataWhere($preft,$swhere);
// 	$unit_vars->NumRows($unit_vars->outselect);
	
	   // if ($unit_vars->outnumrow==0 and $bbo==0) {
		
			if ($bbo==0) {
			
			$unit_vars->InsertQuery($preft,'', $nValues);
?>
		<body onload='javascript:alert ("Record Saved!");'></body>
<?php
			$com='';
		$user_id="";
		$af_start="";
		$af_end="";
		$bbo="";
		
		} elseif ($com=='edit') {
		
		
		$unit_vars->UpdateQuery($preft, $setfld,$ewhere);
?>
		<body onload='javascript:alert ("Record Updated!");'></body>
<?php
		$com='';
		$user_id="";
		$af_start="";
		$af_end="";
		$bbo="";
		} else {
?>
		<body onload='javascript:alert ("Existing User Found"); frm.af_start.focus(); frm.af_start.select();'></body>
<?php
		}	

$bbo='';


}elseif ($com=='delete') { ///delete
	$nWhere = "$prefd = '$bbo'";
	
	$unit_vars->DeleteQuery($preft, $nWhere); 
	?>
		<body onload='javascript:alert ("Record Deleted!");'></body>
<?php
	$bbo='';
	$com = "";
	$user_id="";
	$af_start="";
	$af_end="";
}



if ($com=='edit') {

$where = "where $prefd='$bbo'";
$unit_vars->SelectDataWhere($preft,$where);
$unit_vars->FetchArray($unit_vars->outselect);
$bbo = $unit_vars->outarray[$prefd];
$user_id = $unit_vars->outarray[$field1];
$af_start = $unit_vars->outarray[$field2];
$af_end = $unit_vars->outarray[$field3];
$min_allow = $unit_vars->outarray[$field3];
}


include_once "html/af_iss.html";
include_once "pager/af_iss_page.php";
?>
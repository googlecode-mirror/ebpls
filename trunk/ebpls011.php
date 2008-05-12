<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("class/main.class.php"); 
include_once("includes/config.php");
include_once("includes/variables.php");
include_once("lib/multidbconnection.php");                                                                                                
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$preft = 'ebpls_activity_log'; //table
$prefd = 'act_id';
$field1 = 'user_log';
$unit_vars = new MainVar;
$search_user=$_POST["search_user"];
$search_cat=$_POST["search_cat"];
?>
<form method="post" name="porm" action="index.php?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings">
<table width=100% align=center cellspacing=0 cellpadding=0 border=0>
<tr >
<body onfocus="ClearAct();"></body>
<td colspan=2 align=center class=header><b><font color=white>SETTINGS</font></b></td>
</tr>
<tr><td colspan=2 class=header2 align=center width=100%>Activity Log</td></tr>
<tr><td colspan=2 ><br></td></tr>
<tr width=100%>
<td >Enter String: &nbsp; <input type=text name="search_user" value="<?php echo $search_user; ?>">
&nbsp;
<select name="search_cat" class="select200">
<option value='1'>Username</option>
<option value='2'>Log</option>
<option value='3'>Date</option>
</select>
&nbsp;<input type=button name="bs" value="Search" onclick='porm.submit();'>
</tr>

</table>
<?php
if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
$ipage = $thIntPageLimit;
$max_resultsr = $thIntPageLimit;
//echo $is_desc."VooDoo";

$limitpage = ($pager - 1) * $thIntPageLimit;

if ($isdesc == 'ASC') {
	$ascdesc = 'DESC';
} else {
	$ascdesc = 'ASC';
}

if ($search_cat==1) {
	$addlimit = "where user_log like '$search_user%'";
	$slimit = "where user_log like '$search_user%'";
} elseif ($search_cat==2) {
	$addlimit = "where logged like '%$search_user%'";
	$slimit = "where logged like '%$search_user%'";
} else {
	$addlimit = "where date_input like '$search_user%'";
	$slimit = "where date_input like '$search_user%'";
}

if ($order=='') {
	$order=$field1;
}
if ($orderkey == "") {
	$orderkey = $field1;
}
//echo $slimit;
$limit = "$addlimit order by $orderkey $isdesc limit $limitpage , $max_resultsr"; //pager
include_once "pager/activity_pager.php"
?>
</form>

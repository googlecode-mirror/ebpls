<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("class/main.class.php"); 
include_once("includes/config.php");
include_once("includes/variables.php");
include_once("lib/multidbconnection.php");                                                                                                
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$preft = 'ebpls_activity_log'; //table
$prefd = 'act_id'; //primary key

$field1 = 'user_log';
$field2 = 'logged';
$field3 = 'date_input';
$logid=$_COOKIE["logid"];
$r = new MainVar;
$r->SelectDataWhere($preft,"where act_id='$logid'");
$r->FetchArray($r->outselect);
$p = $r->outarray;

?>
<form name="_FRM" method="post">
<script language='Javascript' src='javascripts/default.js'></script>
<script language='Javascript' src='includes/datepick/datetimepicker.js'></script>
<script language='Javascript' src='javascripts/javafunc.js'></script>
<link href="stylesheets/default.css" rel="stylesheet" type="text/css"/>
<table border="0" width="100%">
	<tr>
		<td width="157">Date Logged:</td>
		<td><?php echo $p[$field3]; ?></td>
	</tr>
	<tr>
		<td width="157">User:</td>
		<td><?php echo $p[$field1]; ?></td>
	</tr>
	<tr>
		<td width="30%">Activity:</td>
		<td><?php echo $p[$field2]; ?></td>
	</tr>
	<tr>
	<td></td></tr>
	<tr>
	<td colspan=2>
	<input type="button" value="Close" onclick="window.close();">
	</td>
	</tr>
</table>
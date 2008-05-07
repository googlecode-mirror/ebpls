<?php 
require_once'lib/ebpls.lib.php';
require_once("lib/ebpls.utils.php");                                                                                                                        
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
require_once "includes/variables.php";                                                                                                                                                                                                                                     

//--- get connection from DB
//$dbLink = get_db_connection();

?>
<body> </body>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
</head>
<body >
<div align="center">
<table border=0 cellspacing=0 cellpadding=0  width='100%'>
	<?php
		$class_type = isset($class_type)?$class_type:"Permits"; //2008.04.25
		if ($class_type=='Permits' and $permit_type=='' and $itemID_=='') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Permits</td>
			</tr>
	<?php
		}
		elseif ($class_type=='Permits' and $permit_type=='Business'  and $itemID_=='1221') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Business Permit Process</td>
			</tr>
	<?php
		}
		elseif ($class_type=='Permits' and $permit_type=='Franchise' and $mtopsearch=='SEARCH' and $itemID_=='1221') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Franchise Permit Process</td>
			</tr>
	<?php
		}
		elseif ($class_type=='Permits' and $permit_type=='Fishery' and $mtopsearch=='SEARCH' and $itemID_=='1221') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Fishery Permit Process</td>
			</tr>
	<?php
		}
		elseif ($class_type=='Permits' and $permit_type=='Motorized' and $mtopsearch=='SEARCH' and $itemID_=='1221') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Motorized Permit Process</td>
			</tr>
	<?php
		}
		elseif ($class_type=='Permits' and $permit_type=='Occupational' and $mtopsearch=='SEARCH' and $itemID_=='1221') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Occupational Permit Process</td>
			</tr>
	<?php
		}
		elseif ($class_type=='Permits' and $permit_type=='Peddlers' and $mtopsearch=='SEARCH' and $itemID_=='1221') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Peddlers Permit Process</td>
			</tr>
	<?php
		}
		elseif ($permit_type=='CTC' and $itemID_=='') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Community Tax Certificate</td>
			</tr>
	<?php
		}
		elseif ($class_type=='Reports' and $itemID_=='') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Reports</td>
			</tr>
	<?php
		}
		elseif ($class_type=='' and $itemID_=='' and $Search=='SEARCH') {
	?>
			<tr>
				<td class='header' colspan=2 align=center>Search Results</td>
			</tr>
	<?php
		}
	?>		
</table>


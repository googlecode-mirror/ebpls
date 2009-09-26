<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");

require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");

//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;
?>
<head>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
<!-- Start of Table -->

<form name=frm_signatories action='Signatories.php'>
	<table align=center border=0 cellspacing=0 cellpadding=3 width=90%>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
			<td> &nbsp </td>
			<td align=right valign=top> Name : </td>
			<td align=left valign=top> &nbsp <input type=text maxlength=255 name=Signatory class=text180></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
			<td> &nbsp </td>
			<td align=right valign=top> Position : </td>
			<td align=left valign=top> &nbsp <input type=text maxlength=255 name=Position class=text180></td>
			<td> &nbsp </td>
		</tr>
	</table>
	<table align=center border=0 cellspacing=0 cellpadding=0 width=90%>
		<tr width=90%>
			<td align=center valign=top><input type=submit maxlength=255 class=text180 value=Submit name=sb>
			<input type=Button class=text180 value=Cancel onClick:'javascript:window.back;'>
			<input type=Reset class=text180 value=Reset>
			&nbsp</td>
			<td> &nbsp </td>
			<td> &nbsp </td>
			<td> &nbsp </td>
			<td> &nbsp </td>
			<td> &nbsp </td>
			<td> &nbsp </td>
			<td> &nbsp </td>
		</tr>
	</table>
</form>
	<table align=center border=1 cellspacing=0 cellpadding=0 width=300>
		<tr width=90%>
			<td width=25%> &nbsp</td>
			<td width=25%> &nbsp</td>
			<td width=25%> &nbsp</td>
			<td width=25%> &nbsp</td>
		</tr>
	</table>
<?php

if ($sb=='Submit') {
	
	$r = mysql_query("insert into global_sign (gs_name, gs_pos) 
					values ('$Signatory', '$Position')") or die ("die");
					
}
?>
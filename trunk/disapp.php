<?php
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
global $ThUserData;
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);


		$get = mysql_query("select * from ebpls_buss_approve a
							where a.owner_id='$owner_id' and
                                         a.business_id='$business_id'");
               
               
?>
<script language='Javascript' src='includes/datepick/datetimepicker.js'></script>
<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>


<div align=right><a href="javascript:window.close();"><b>Close this Window [X]</b></a></div>
<br><br>
<table border=0 align=center>
	<tr>
		<td colspan=2 align=center> Reason for Disapproved Status </td>
	</tr>
	<tr><td></td></tr>
	<?php
	 while ($ge = mysql_fetch_assoc($get)){
		 ?>
	<tr>
		<td>&nbsp;<?php echo $ge['dec_comment'];?></td>
	</tr>
	<?php
	}
	?>
</table>
<br><br>
<div align=center>
<input type=button name=canit value=CLOSE onClick='javascript: window.close()'>


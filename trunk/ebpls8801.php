<?php

include'lib/phpFunctions-inc.php';
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content=" FourJ's ">
	<link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
<title></title>
</head>
<body>
<form ENCTYPE="multipart/form-data" method="post">

<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=itemEvent value="<?php echo $itemEvent;?>">

<table border=0 width=90% align=center cellspacing=0 cellpadding=0>
<tr>
<td colspan=2 align=center><br>
<?php
$data_item=0;
include'tablemenu-inc.php';
?>
</td>
</tr>

<tr><td colspan=2 ><br></td></tr>
<tr><td colspan=2 ><br></td></tr>

<tr>
<td align=center width=45%><img src="/images/doc07.gif" /><br>
<a href=?part=4&class_type=Preference&permit_type=Franchise&itemID_=4221>Franchise</a></td>
<td align=center width=45%><img src="/images/doc07.gif" /><br>
<a href=?part=4&class_type=Preference&permit_type=Occupational&itemID_=4221>Occupational</a></td>
</tr>

<tr><td colspan=2 ><br></td></tr>
<tr><td colspan=2 ><br></td></tr>

<tr>
<td align=center width=45%><img src="/images/doc07.gif" /><br>
<a href=?part=4&class_type=Preference&permit_type=Fishery&itemID_=4221>Fishery</a></td>
<td align=center width=45%><img src="/images/doc07.gif" /><br>
<a href=?part=4&&class_type=Preferencepermit_type=Peddlers&itemID_=4221>Peddlers</a></td>
</tr>

<tr><td colspan=2><br></td></tr>
<tr><td colspan=2 ><br></td></tr>

<tr>
<td align=center width=45%><img src="/images/doc07.gif" /><br>
<a href=?part=4&class_type=Preference&permit_type=Motorized&itemID_=4221>Motorized</a></td>
<td align=center width=45%><br></td>
</tr>

<tr>
<td colspan=2 align=center>
<?php
$data_item=1;
include'tablemenu-inc.php';
?>
</td>
</tr>

</table>

<?php
mysql_close($thDbLink);
?>
</form>
</body>
</html>

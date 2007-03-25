<form method=post ENCTYPE="multipart/form-data" name='_FRM'>
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>

<tr><td colspan=2 class=header2 align=center width=100%>SUPER ADMINISTRATOR</td></tr>
	</table>
<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">

<?php
include_once "includes/variables.php";
if (md5($iAdminPassword)==$superpass || $scmd==1 || $scmd==2) {
	$varAccess=1;
} else {
	if ($iSubmitAdminPassword<>'')
	{
?>
	<body onload='alert("Invalid password.");'></body>
<?php
	}
}


if ($varAccess==0){
?>
<br>
<br>
<body onLoad="_FRM.iAdminPassword.focus();">

<br>
<br>
<table width=30% align=center>
<tr><td><marquee>
This page is for <b><i>Super Administrator<i></b> use only. 
</marquee></td></tr>
</table>
<br>

<p align=center>
Password :<input type=password name=iAdminPassword size=10 maxlength=15> <input type=submit name=iSubmitAdminPassword value=Go>
</p>

<?php
}
elseif ($varAccess==1 || $scmd==2){
?>
<table width=90% align=center>
<tr>
<td>
<?php
include'admin_work.php';
?>
</td>
</tr>
</table>
<?php
}
?>

</body>
</html>

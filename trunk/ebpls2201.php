<?php
/*
$db="ebpls";
$link2db = mysql_connect("localhost","ebpls","ebpls");
if (! $link2db) die("Couldn't connect to MySQL");
mysql_select_db($db, $link2db) or die("Couldn't open $db: ".mysql_error());
*/

require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
                                                                                                               
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
                                                                                                               
//--- get connection from DB
$dbLink = get_db_connection();
global $ThUserData;

//$dbLink = get_db_connection();
$tbl_current="ebpls_buss_nature";

if (isset($ISubmitBussNature)){
	$nidescription=strip_tags(strtoupper($idescription));
	$xmyid = mysql_query("SELECT * FROM $tbl_current WHERE naturedesc = '$nidescription' ",$link2db);
		
	if (mysql_affected_rows($link2db)==0 ){
		$action_=1;
		$actionID=1;
	/*$myid = mysql_query("INSERT INTO $tbl_current (naturedesc,naturestatus,natureoption) VALUES ('$nidescription','A','1')",$link2db);
	
	if ($myid == TRUE){
	//print "Ok...";	
	
	
	}
	else {
	print "<p align=center><b><i><font size=+1>An error has occured!!!</font></i></b></p>";	
	}
	*/
	}
	else {
	print "<p align=center><b><i><font size=+1>An error has occured... (Record already exist!!!)</font></i></b></p>";
	}
	
}


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
<form>

<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=actionID value="<?php echo $actionID;?>">

<table width=90% align=center cellspacing=0 cellpadding=0>

<tr bgcolor="#DDDDDD">
<td colspan=2 align=center><font size=+1><b><i><a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=<?php echo $selMode;?>>Business Permit Licensing System Setup</a></i></b></font></td>
</tr>

<tr>
<td colspan=2 align=center><br><br></td>
</tr>

<tr>
<td colspan=2 align=right><img src="/images/diamond.gif" /> <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=<?php echo $selMode;?>&action_=1>Business Nature</a> <img src="/images/diamond.gif" /> <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=<?php echo $selMode;?>&action_=2>Business Requirements</a></td>
</tr>

<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>

<?php
if (empty($action_)){
?>
<tr>
<td colspan=2><b><i>Master List of Business Nature Information</i></b></td>
</tr>
<?php
}
?>

<?php
if ($action_==1){ // Business Nature Add Module start here...
?>
<tr>
<td colspan=2><b><i>Business Nature (Add Module)</i></b> &nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=35 height=35/></a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><br></td>
</tr>

<tr>
<td width=15% valign=top>Description of Nature</td>
<td width=75%><textarea name=idescription rows=3 cols=80><?php echo $nidescription;?></textarea></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td><br></td>
<td><input type=submit name=ISubmitBussNature value="Submit Information"></td>
</tr>

<!-- End here for Business Nature Information-->
<!-- Start here of TaxFeeOtherCharges-->

<?php
if (!empty($actionID) and $actionID==1){
?>

<tr>
<td colspan=2><br></td>
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><b><i>Tax Requirements</i></b></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2>Add More...</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><b><i>Fee Requirements</i></b></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2>Add More...</td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr bgcolor="#EEEEEE">
<td colspan=2><b><i>Other Charges</i></b></td>
</tr>

<tr>
<td colspan=2><br></td>
</tr>

<tr>
<td colspan=2>Add More...</td>
</tr>

<?php
}
?>
<!-- End here of TaxFeeOtherCharges-->


<?php
}
elseif ($action_==2){
?>
<tr>
<td colspan=2><b><i>Business Requirements (Add Module)</i></b> &nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=<?php echo $selMode;?>><img src="/images/back1.gif" width=35 height=35/></a></td>
</tr>
<?php
}
?>
</table>

</form>
</body>
</html>


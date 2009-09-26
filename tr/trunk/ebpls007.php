<?php
//	eBPLS_PAGE_USER_LIST: This module allows user admins to view user-list of specified organization
include'includes/variables.php';
global $ThUserData;

//	this is for limiting scope of users
switch ($intUserLevel) {
	case eBPLS_ROOT_ADMIN: $intViewableLevelStart = eBPLS_USER_CTC; $intViewableLevelEnd = eBPLS_ROOT_ADMIN; break;
	case eBPLS_USER_ADMIN: $intViewableLevelStart = eBPLS_USER_CTC; $intViewableLevelEnd = eBPLS_USER_ADMIN; break;
	default: $intViewableLevelStart = 0; $intViewableLevelEnd = 0; break;
}

//	Kick and Unlock exclusive functions

if($frmBtnKick == "Kick User") {
	$checkif = @mysql_query("select username, login, logout from ebpls_user where id = '$frmId'");
	$checkiff = @mysql_fetch_assoc($checkif);
	if ($checkiff['login'] <= $checkiff['logout']) {
		?>
        <body onload='javascript:alert ("Cannot kick offline user!"); return false;'></body>
        <?
	} else {
	if ($ThUserData['username'] == $checkiff['username']) {
		?>
        <body onload='javascript:alert ("Cannot kicked own account!"); return false;'></body>
        <?
	} else {
	$frmThreadId = $frmId;
	setUserLogout($frmThreadId);
	$frmBtnKick = "";
	?>
    <body onload='javascript:alert ("User is successfully kicked!");'></body>
	<?
	}
	}
	//header("Location: " . $HTTP_SERVER_VARS['PHP_SELF'].'?part=4&class_type=Settings&itemID_=7&busItem=Settings&permit_type=Settings&settings_type=UserManager&item_id=Settings&com=kick');
}
if($frmBtnUnlock == "Unlock User"){
	$checkif = @mysql_query("select lockout from ebpls_user where id = '$frmId'");
	$checkiff = @mysql_fetch_assoc($checkif);
	if ($checkiff[lockout] == "") {
		?>
        <body onload='javascript:alert ("Cannot Unlock open user!"); return false;'></body>
        <?
	} else {
    $frmThreadId = $frmId;
	unlockUser($frmThreadId);
	$frmBtnUnlock = "";
	?>
        <body onload='javascript:alert ("User is successfully unlocked!!");'></body>
        <?
	}
//	header("Location: " . $HTTP_SERVER_VARS['PHP_SELF'].'?part=4&class_type=Settings&itemID_=7&busItem=Settings&permit_type=Settings&settings_type=UserManager&item_id=Settings&com=unlock');
}
?>


<?php
// ********************** START HERE **********************
echo "<div align=\"CENTER\" class=\"thText\">\n";
require_once "lib/dbhtmltable.class.php";
$objDbTable = new DbHtmlTable(
    $thThemeColor3,                     // <table> row alternating colour 1
    $thThemeColor4,                     // <table> row alternating colour 2
    "ARIAL,HELVETICA,SANS-SERIF",       // results' font face
    "2",                                // results' font size
    null,                               // <table> border colour
    550,                                // <table> width
    1,                                  // <table> cellspacing
    2                                   // <table> cellpadding
);

if (empty($frmCorpNames)) {

	// display Administrator List
	if ($com == "kick") {
                echo "<div align=\"CENTER\"><font size=2 color=red>Successfully Kicked User!</font></div><br>";
		$com = "";
        }
	if ($com == "unlock") {
                echo "<div align=\"CENTER\"><font size=2 color=red>Successfully Unlock User!</font></div><br>";
		$com = "";
        }
	$strCurDomain = isset($ThUserData['domain']) ? $ThUserData['domain'] : '';
	$strLabelToShow = ($strCurDomain) ? "$strCurDomain : " : "";
	echo "<table border=0 width=100% align=center cellspacing=0 cellpadding=0>\n
		<tr><td colspan=2 class=header align=center width=100%>SETTINGS</td></tr>\n
		<tr>\n
			<td colspan=2 align=center>\n
			</td>\n
		</tr>\n
		<tr width=100%>\n
			<td align=center colspan=3 class=header2> User Manager </td>\n
		</tr>\n
		<tr>\n
			<td colspan=2 align=center>\n
			</td>\n
		</tr>\n
		<tr>\n
			<td colspan=2 align=center>\n
			</td>\n
		</tr>\n
		<tr>\n
			<td colspan=2 align=center>\n
			</td>\n
		</tr>\n
	</table>";
	
	$objDbTable->setTextBeforeTable("\n\n");
/* echo "<span><input type=\"HIDDEN\" name=\"frmThreadId\" value=\"\">\n";
        echo "<input type=\"BUTTON\" name=\"frmBtnAdd\" value=\"Add User\" onClick=\"javascript:
popwin('" . getFilename(eBPLS_PAGE_USER_ADD) . "?frmDomain=$strCurDomain', 'adduser');\"> &nbsp;
&nbsp;\n";
 echo "<input type=\"BUTTON\" name=\"frmBtnEdit\" value=\"Edit User\" onClick=\"javascript: popwin('" . getFilename(eBPLS_PAGE_USER_UPDATE) . "?frmThreadId='+document._FRM.frmThreadId.value+'&frmDomain=$strCurDomain', 'edituser');\"> &nbsp; &nbsp;\n";
        echo "<input type=\"SUBMIT\" name=\"frmBtnKick\" value=\"Kick User\"> &nbsp; &nbsp;\n";
        echo "<input type=\"SUBMIT\" name=\"frmBtnUnlock\" value=\"Unlock User\"> &nbsp; &nbsp;\n";
        echo "<input type=\"BUTTON\" name=\"frmBtnDelete\" value=\"Delete User\" onClick=\"javascript: popwin('" . getFilename(eBPLS_PAGE_USER_DELETE) . "?frmThreadId='+document._FRM.frmThreadId.value+'&frmDomain=$strCurDomain', 'deleteuser');\"> &nbsp; &nbsp;</span>\n";
  */                                                                                               

	echo $objDbTable->getDbHtmlTable(
	    "SELECT id, (login <= logout) AS status, level,  username, CONCAT_WS(', ', lastname, firstname) AS fullname, email, gsmnum, lockout FROM ebpls_user WHERE level BETWEEN $intViewableLevelStart AND $intViewableLevelEnd ORDER BY status, level DESC, username LIMIT " . $GLOBALS['thIntPageLimit'],      // your SQL query statement
	    array(' ', 'Status', 'User&nbsp;Level',  'User&nbsp;Name', 'Full&nbsp;Name', 'Email&nbsp;Address', 'GSM&nbsp;Number', 'Lock'),                                                // your customized column titles
	    array('setAdminRadioButton', 'decodeStatus', 'decodeUserLevel',  null, 'setRecLeftAlign', 'formatEmailLink', 'formatGSM', 'decodeLockField'),  // your user-defined functions
	    array('User Name' => 'username', 'Email Address' => 'email', 'GSM Phone No.' => 'gsmnum'),                                             // your desired search fields
	    $thDbLink                                                                                                                              // your MySQL connection link resource
	) . "<br>\n";
	echo "<input type=\"HIDDEN\" name=\"frmThreadId\" value=\"\">\n";
	echo "<input type=\"HIDDEN\" name=\"frmBtnKick\" >\n";
	echo "<input type=\"HIDDEN\" name=\"frmBtnUnlock\" >\n";
	echo "<input type=\"BUTTON\" name=\"frmBtnAdd\" value=\"Add User\" onClick=\"javascript: popwin('" . getFilename(eBPLS_PAGE_USER_ADD) . "?frmDomain=$strCurDomain', 'adduser');\"> &nbsp; &nbsp;\n";
	//echo "<input type=\"BUTTON\" name=\"frmBtnEdit\" value=\"Edit User\" onClick=\"javascript: popwin('" . getFilename(eBPLS_PAGE_USER_UPDATE) . "?frmThreadId='+document._FRM.frmThreadId.value+'&frmDomain=$strCurDomain', 'edituser');\"> &nbsp; &nbsp;\n";
	echo "<input type=\"BUTTON\" name=\"frmBtnEdit\" value=\"Edit User\" onClick=\"javascript: EditUserCheck();\"> &nbsp; &nbsp;\n";
	echo "<input type=\"BUTTON\" name=\"BtnKick\" value=\"Kick User\" onClick=\"KickUser();\"> &nbsp; &nbsp;\n";
	echo "<input type=\"BUTTON\" name=\"BtnUnlock\" value=\"Unlock User\" onClick=\"javascript: UnlockUser();\"> &nbsp; &nbsp;\n";
	echo "<input type=\"BUTTON\" name=\"frmBtnDelete\" value=\"Delete User\" onClick=\"javascript: DeleteUserCheck();\"> &nbsp; &nbsp;\n";
	echo "</form>\n";
	echo "</div>\n";

}
?>
<script language="Javascript">
function DeleteUserCheck()
{
	var _FRM = document._FRM;
	if (isNaN(_FRM.frmThreadId.value) == true)
	{
		alert("Select Valid User.");
		return false;
	}
	popwin('<? echo getFilename(eBPLS_PAGE_USER_DELETE);?>?frmThreadId='+document._FRM.frmThreadId.value+'&frmDomain=<? echo $strCurDomain;?>', 'deleteuser');
	return true;
}
function EditUserCheck()
{
	var _FRM = document._FRM;
	if (isNaN(_FRM.frmThreadId.value) == true)
	{
		alert("Select Valid User.");
		return false;
	}
	popwin('<? echo  getFilename(eBPLS_PAGE_USER_UPDATE);?>?frmThreadId='+_FRM.frmThreadId.value+'&frmDomain=<? echo $strCurDomain;?>', 'edituser');
	return true;
}
function UnlockUser()
{
	var _FRM = document._FRM;
	if (isNaN(_FRM.frmThreadId.value) == true)
	{
		alert("Select Valid User.");
		return false;
	}
	confirmunlock = confirm("Unlock User Account?");
	if (confirmunlock == true)
	{
		_FRM.frmBtnUnlock.value = "Unlock User";
	} else {
		alert("Transaction Cancelled!!");
		return false;
	}
	_FRM.submit();
	return true;
}
function KickUser()
{
	var _FRM = document._FRM;
	if (isNaN(_FRM.frmThreadId.value) == true)
	{
		alert("Select Valid User.");
		return false;
	}
	confirmkick = confirm("Kick User Account?");
	if (confirmkick == true)
	{
		_FRM.frmBtnKick.value = "Kick User";
	} else {
		alert("Transaction Cancelled!!");
		return false;
	}
	_FRM.submit();
	return true;
}
</script>
<?

// **********************  END HERE  **********************

// *** Module Dependent User-defined Functions ***
function decodeUserLevel($intLevel)
{
	include'includes/variables.php';
	$intLevel =decrypt_md5($intLevel,$decoder);
	if ($intLevel == "") {
		$intLevel = '0';
	}
	return $GLOBALS['thUserLevel'][$intLevel][1];
}
function decodeStatus($intStat)
{
	$strFontColor = ($intStat) ? $GLOBALS['thThemeColor6'] : $GLOBALS['thThemeColor5'];
	return ($intStat) ?
		"<font color=\"$strFontColor\">OFFLINE</font>" :
		"<font color=\"$strFontColor\">ONLINE</font>";
}
function formatEmailLink($strInput)
{
	return "<div align=\"RIGHT\"><a href=\"mailto:$strInput\">$strInput</a></div>";
}
function formatGSM($strInput)
{
	return "<div align=\"LEFT\" class=\"thText\">$strInput</div>";
}
function setRecLeftAlign($strInput)
{
	return "<div align=\"LEFT\" class=\"thText\">$strInput</div>";
}
function decodeGroup($intGroup)
{
	return $GLOBALS['arrGroups'][$intGroup];
}
function setRadioButton($intId)
{
	global $intLoopCtr;
	return "<input type=\"RADIO\" name=\"frmId_$intLoopCtr\" value=\"$intId\" onClick=\"document._FRM_$intLoopCtr.frmThreadId_$intLoopCtr.value=$intId;\">";
}
function setAdminRadioButton($intId)
{
	return "<input type=\"RADIO\" name=\"frmId\" value=\"$intId\" onClick=\"document._FRM.frmThreadId.value=$intId;\">";
}
function decodeLockField($strDateTime)
{
	$strFontColor = (empty($strDateTime)) ? $GLOBALS['thThemeColor5'] : $GLOBALS['thThemeColor6'];
	return (empty($strDateTime)) ?
		"<font color=\"$strFontColor\">OPEN</font>" :
		"<font color=\"$strFontColor\">LOCKED</font>";
}


?>

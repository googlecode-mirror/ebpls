<?php
//	eBPLS_PAGE_USER_DELETE: This module allows admins to delete user accounts.
ob_start();
$strFrmctr = (empty($frmctr)) ? "" : "_$frmctr";
$qryVar1 = "frmDomain".$strFrmctr;
$qryVar2 = "frmThreadId".$strFrmctr;
require_once "includes/config.php";
require_once "setup/" . $$qryVar1 . "/setting.php";
require_once "lib/ebpls.lib.php";
dbConnect();
$intUserLevel = isUserLogged();
syncUserCookieDbLogStat();
require_once("lib/ebpls.utils.php");
include("includes/variables.php");
include("lib/multidbconnection.php");
                                                                                
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

//--- get connection from DB
//$dbLink = get_db_connection();

?>
<html>
<head>
	<title>eBPLS</title>
	<link href="stylesheets/default.css" rel="stylesheet" type="text/css">	
	<script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="" vlink="" alink="" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="CENTER" valign="MIDDLE">

<table width="650" border="0" cellspacing="1" cellpadding="1">
	<tr>
		<td bgcolor="<?php echo($thThemeColor4); ?>" align="RIGHT" class="thText">
			<a href="javascript: opener.location.reload(true); window.close();"><b>Close this Window [X]</b></a><br>
		</td>
	</tr>
</table>

<?php

// update record if triggered
if (!empty($frmBtnDelete)) {
	$checkif = @mysql_query("select username,login,logout from ebpls_user where id = '$frmId'");
	$checkiff = @mysql_fetch_assoc($checkif);
	$isonline =  strtotime($checkiff[login])-strtotime($checkiff[logout]);
	if ($ThUserData[username] == $checkiff[username]) {
		?>
        <body onload='javascript:alert ("Cannot delete own account!!"); return false;'></body>
		<?
	} else {
	if ($isonline > 0) {
		?>
        <body onload='javascript:alert ("Cannot delete online user.\nAsk user to logout or\nkick user before deleting."); return false;'></body>
        <?
	} else {
	$strQuery = "DELETE FROM ebpls_user WHERE id = $frmId";
	}
	}
	//echo "$intUserLevel :: $strQuery<BR> :: " . eBPLS_USER_CTC;	
	if ($intUserLevel >= eBPLS_USER_CTC ) {
		
		$result = th_query($strQuery);
		//--- delete from the listings
		delSubLevelListings($dbLink,$frmId);	
		
	} else {
		$result = FALSE;
	}
	if ($result === FALSE) {
		
			"<div align=\"CENTER\" class=\"thFieldTitle\">Delete Failed! Please Contact Your Administrator.</div>";
	} else {
		echo "<div align=\"CENTER\" class=\"thFieldTitle\">Delete Successfull!</div>";
	}
} else {

// ********************** START HERE **********************
if (!empty($$qryVar2) || !empty($frmId)) {
	require_once "lib/dbhtmltable.class.php";
	$objDbTable = new DbHtmlTable(
	    $thThemeColor3,                     // <table> row alternating colour 1
	    $thThemeColor4,                     // <table> row alternating colour 2
	    "ARIAL,HELVETICA,SANS-SERIF",       // results' font face
	    "2",                                // results' font size
	    "#000000",                          // <table> border colour
	    500,                                // <table> width
	    1,                                  // <table> cellspacing
	    2                                   // <table> cellpadding
	);
	echo "<div align=\"CENTER\" class=\"thText\">\n";
	echo "<form method=\"POST\" action=\"" . $HTTP_SERVER_VARS['PHP_SELF'] . "\">\n";
	echo "<span class=\"thSectionTitle\">Delete This User?</span><br>\n";
	echo $objDbTable->getDbRecHtmlTable(
	    "SELECT id, level, username, password, lastname, firstname, designation, email, gsmnum, dateadded, lastupdated FROM ebpls_user WHERE id = " . $$qryVar2,
	    array('User Id', 'User Level', 'User Name', 'Password', 'Lastname', 'Firstname', 'Designation', 'Email Address', 'GSM Number', 'Date Added', 'Last Updated'),
	    array('setInputHidden', 'decodeUserLevel', 'setInputText', 'setInputPassword', 'setInputText', 'setInputText', 'setInputText', 'setInputText', 'setInputText', null, null, null),
	    $thDbLink,
	    200,
	    300
	) . "<br>\n";
	echo "<input type=\"HIDDEN\" name=\"frmctr\" value=\"{$frmctr}\">\n";
	echo "<input type=\"HIDDEN\" name=\"$qryVar1\" value=\"" . $$qryVar1 . "\">\n";
	echo "<input type=\"HIDDEN\" name=\"$qryVar2\" value=\"" . $$qryVar2 . "\">\n";
	echo "<input type=\"SUBMIT\" name=\"frmBtnDelete\" value=\"Delete\"> &nbsp; &nbsp;\n";
	echo "<input type=\"BUTTON\" name=\"frmBtnCancel\" value=\"Close\" onClick=\"javascript: window.close();\"> &nbsp; &nbsp;\n";
	echo "</form>\n";
	echo "</div>\n";
} else {
	echo "<br><br><span class=\"thFieldTitle\">Access Denied : Insufficient Parameters!</span>";
}
// **********************  END HERE  **********************

}



// *** Module Dependent User-defined Functions ***
function decodeUserLevel($intLevel)
{
	return "<input type=\"TEXT\" name=\"$strFormName\" value=\"" . $GLOBALS['thUserLevel'][$intLevel][1] . "\" size=\"20\" style=\"width:270px\" readonly=\"readonly\">";
}
function setInputHidden($strInput, $strFieldName)
{
	$strFormName = "frm" . ucfirst($strFieldName);
	return "<input type=\"HIDDEN\" name=\"$strFormName\" value=\"$strInput\">$strInput";
}
function setInputText($strInput, $strFieldName)
{
	$strFormName = "frm" . ucfirst($strFieldName);
	return "<input type=\"HIDDEN\" name=\"{$strFormName}Old\" value=\"$strInput\"><input type=\"TEXT\" name=\"$strFormName\" value=\"$strInput\" size=\"20\" style=\"width:270px\" readonly=\"readonly\">";
}
function setInputPassword($strInput, $strFieldName)
{
	$strFormName = "frm" . ucfirst($strFieldName);
	return "<input type=\"Password\" name=\"$strFormName\" value=\"$strInput\" size=\"20\" style=\"width:270px\" readonly=\"readonly\">";
}

function setHighLight($strInput)
{
	return "<div align=\"CENTER\" class=\"thErrorMsg\" style=\"font-size:14pt;\">{$strInput}</div>";
}

function delSubLevelListings($dbLink,$uid)
{
	$sql    	= "DELETE FROM ebpls_user_sublevel_listings WHERE user_id=$uid";
	@mysql_query($sql, $dbLink);
}
?>

		</td>
	</tr>
</table>

</body>
</html>
<?php
if ($intUserLevel > -1) setCurrentActivityLog($thStrLogAction);
ob_end_flush();
?>

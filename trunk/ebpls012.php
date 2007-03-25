<?php
//	eBPLS_POP_ACTLOG_VIEW_DETAILS: This module allows users to view the details of the specific activity log.
ob_start();
require_once "includes/config.php";
require_once "setup/" . $frmDomain . "/setting.php";
require_once "lib/ebpls.lib.php";
dbConnect();
$intUserLevel = isUserLogged();
syncUserCookieDbLogStat();
?>
<html>
<head>
	<title>eBPLS: View Log Details</title>
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
			<a href="javascript: window.close();"><b>Close this Window [X]</b></a><br>
		</td>
	</tr>
</table>

<?php
// ********************** START HERE **********************
if (!empty($logseqno)) {
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
	echo "<div align=\"CENTER\" class=\"thText\">";
	echo "<span class=\"thSectionTitle\">Log Details</span>";
	echo $objDbTable->getDbRecHtmlTable(
	    "SELECT id, userid, userlevel, username, querystring, remoteip, lastupdated FROM ebpls_activity_log WHERE id = $logseqno",      // your SQL query statement
	    array('Sequence<nobr /> No.', 'User<nobr /> Id<nobr /> No.', 'User<nobr /> Level', 'User<nobr /> Name',  'Querystring<nobr /> Used', 'Remote<nobr /> IP', 'Date<nobr /> Logged'),                                                                                           // your customized column titles
	    array(null, null, 'decodeUserLevel', null, 'decodeFuncNum', 'myCsv2br', 'myCsv2br', 'myNl2br'),                                                                                                          // your user-defined functions
	    $thDbLink                                                                                                                                                             // your MySQL connection link resource
	);
	echo "</div>";
}
// **********************  END HERE  **********************



// *** Module Dependent User-defined Functions ***
function decodeUserLevel($intLevel)
{
	return $GLOBALS['thUserLevel'][$intLevel][1];
}
function myNl2br($strInput)
{
	return nl2br($strInput)."&nbsp;";
}
function myCsv2br($strInput)
{
	return str_replace('|-|', '<br />', nl2br($strInput))."&nbsp;";
}
function decodeFuncNum($intFuncNum, $arrRow)
{
	switch ($intFuncNum) {
		case eBPLS_PAGE_LOGIN                     : $strFunc = "Log In"; break;
		case eBPLS_PAGE_LOGOUT                    : $strFunc = "Log Out"; break;
		case eBPLS_PAGE_REFRESH                   : $strFunc = "Page Refresh"; break;
		case eBPLS_PAGE_MAIN                      : $strFunc = "Main Page"; break;
		case eBPLS_PAGE_USER_LIST                 : $strFunc = "List User"; break;
		case eBPLS_PAGE_USER_ADD                  : $strFunc = "Add User"; break;
		case eBPLS_PAGE_USER_UPDATE               : $strFunc = "Update User"; break;
		case eBPLS_PAGE_USER_DELETE               : $strFunc = "Delete User"; break;
		case eBPLS_PAGE_USER_KICK           	  : $strFunc = "Kick User"; break;
		case eBPLS_PAGE_USER_UNLOCK               : $strFunc = "Unlock User"; break;
		case eBPLS_PAGE_REPORT_SUMMARY            : $strFunc = "View Reports"; break;
		case eBPLS_PAGE_SETTING_UPDATE            : $strFunc = "View/Update Settings"; break;
		case eBPLS_PAGE_ACTLOG_VIEW               : $strFunc = "View Activity Log"; break;
		case eBPLS_POP_ACTLOG_VIEW_DETAILS        : $strFunc = "View Log Details"; break;
		default:
			$strFunc = "";
			break;
	}
	return "<div align=\"LEFT\">(#".$intFuncNum.") " . $strFunc . "</div>";
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

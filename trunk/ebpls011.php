<?php
//	eBPLS_PAGE_ACTLOG_VIEW: This module allows users to view activity logs of all users

include 'includes/variables.php';
require_once("lib/ebpls.utils.php");

if ($intUserLevel > eBPLS_USER_ADMIN && empty($ThUserData[domain])) {
?>
<?php





}

//--- chk the sublevels
//if(   ! is_valid_sublevels(169))
//{
// 	setUrlRedirect('index.php?part=999');
	
//} 




// ********************** START HERE **********************
require_once "lib/dbhtmltable.class.php";
$objDbTable = new DbHtmlTable(
    $thThemeColor3,                     // <table> row alternating colour 1
    $thThemeColor4,                     // <table> row alternating colour 2
    "ARIAL,HELVETICA,SANS-SERIF",       // results' font face
    "2",                                // results' font size
    null,                               // <table> border colour
    700,                                // <table> width
    1,                                  // <table> cellspacing
    2                                   // <table> cellpadding
);
if (empty($frmCorpNames) || $intUserLevel <= eBPLS_USER_ADMIN) {
	// display Administrator List
	$strCurDomain = $ThUserData[domain];
	echo "<table border=0 width=100% align=center cellspacing=0 cellpadding=0>\n
		<tr><td colspan=2 class=header align=center width=100%>SETTINGS</td></tr>\n
		<tr>\n
			<td colspan=2 align=center>\n
			</td>\n
		</tr>\n
		<tr width=100%>\n
			<td align=center colspan=3 class=header2> ACTIVITY LOG </td>\n
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
	echo $objDbTable->getDbHtmlTable(
	    "SELECT id, userlevel, username, lastupdated FROM ebpls_activity_log WHERE userlevel < " . eBPLS_ROOT_ADMIN . " ORDER BY lastupdated DESC LIMIT 20",      // your SQL query statement
	    array('Sequence No.', 'User Level', 'ebpls User',  'Log Date'),                                                                                           // your customized column titles
	    array(null, 'decodeUserLevel', null, 'decodeFuncNum', null),                                                                                                          // your user-defined functions
	    array('User Name' => 'username',  'Sequence Number' => 'id', 'User Level' => 'userlevel', 'Date Logged' => 'lastupdated'),                                                                    // your desired search fields
	    $thDbLink                                                                                                                                                             // your MySQL connection link resource
	);
} else {
	for ($i=0; $i < count($frmCorpNames); $i++) {
		$arrQryStrVar[] = "frmCorpNames[$i]";
		$arrQryStrVal[] = $frmCorpNames[$i];
	}
	$HTTP_SERVER_VARS['QUERY_STRING'] = $objDbTable->updateQueryString(
		$arrQryStrVar,
		$arrQryStrVal
	);
	
}
echo "</div>";
// **********************  END HERE  **********************













// *** Module Dependent User-defined Functions ***
function decodeUserLevel($intLevel)
{
        return $GLOBALS['thUserLevel'][$intLevel][1];
	
}

function decodeFuncNum($intFuncNum, $strFieldName, $arrRow)
{
	global $strCurDomain;
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
		case eBPLS_PAGE_ALLOWED_IP_LIST        	  : $strFunc = "View Allowed IP List"; break;
		case eBPLS_POP_ALLOWED_IP_ADD        	  : $strFunc = "Add Allowed IP Address"; break;
		case eBPLS_POP_ALLOWED_IP_UPDATE	      : $strFunc = "Update Allowed IP Address"; break;
		case eBPLS_POP_ALLOWED_IP_DELETE          : $strFunc = "Delete Allowed IP Address"; break;
		case eBPLS_DB_DETAILS_MAINTENANCE         : $strFunc = "DB Details Maintenance"; break;
		case eBPLS_DB_DETAILS_MAINTENANCE_LIST    : $strFunc = "DB Details Maintenance List"; break;
		case eBPLS_DB_DETAILS_MAINTENANCE_INPUT   : $strFunc = "DB Details Maintenance Input"; break;
		case eBPLS_DB_DETAILS_MAINTENANCE_PROCESS : $strFunc = "DB Details Maintenance Process"; break;
		case eBPLS_PAGE_CTC_CRITERIA              : $strFunc = "CTC Page"; break;
		case eBPLS_PAGE_CTC_CRITERIA_RES          : $strFunc = "CTC Criteria Results"; break;
		case eBPLS_PAGE_CTC_INPUT 				  : $strFunc = "CTC Creatation"; break;
		case eBPLS_PAGE_CTC_PROCESS               : $strFunc = "CTC Process"; break;
		case eBPLS_PAGE_CTC_PRINT_IND             : $strFunc = "CTC Individual Print Page "; break;
		case eBPLS_PAGE_CTC_PRINT_BUS             : $strFunc = "CTC Corporate/Business Print Page"; break;
		case eBPLS_PAGE_APP_INPUT                 : $strFunc = "Permit Application Data Entry"; break;
		case eBPLS_PAGE_APP_LIST                  : $strFunc = "Permit Application List"; break;
		case eBPLS_PAGE_APP_CRITERIA              : $strFunc = "Permit Criteria Page"; break;
		case eBPLS_PAGE_APP_SEARCH_LISTINGS1      : $strFunc = "Listing Page - Application Business Permit"; break;
		case eBPLS_PAGE_APP_SEARCH_LISTINGS2      : $strFunc = "Listing Page - Application Motorized Operator's Permit"; break;
		case eBPLS_PAGE_APP_SEARCH_LISTINGS3      : $strFunc = "Listing Page - Application Occupational Permit"; break;
		case eBPLS_PAGE_APP_SEARCH_LISTINGS4      : $strFunc = "Listing Page - Application Peddlers Permit"; break;
		case eBPLS_PAGE_APP_SEARCH_LISTINGS5      : $strFunc = "Listing Page - Application Franchise Permit"; break;
		case eBPLS_PAGE_APP_SEARCH_LISTINGS6      : $strFunc = "Listing Page - Application Fishery Permit"; break;
		case eBPLS_PAGE_APP_INPUT1                : $strFunc = "Data Entry - Application Business Permit"; break;
		case eBPLS_PAGE_APP_INPUT2                : $strFunc = "Data Entry - Application Motorized Operator's Permit"; break;
		case eBPLS_PAGE_APP_INPUT3                : $strFunc = "Data Entry - Application Occupational Permit"; break;
		case eBPLS_PAGE_APP_INPUT4                : $strFunc = "Data Entry - Application Peddlers Permit"; break;
		case eBPLS_PAGE_APP_INPUT5                : $strFunc = "Data Entry - Application Franchise Permit"; break;
		case eBPLS_PAGE_APP_INPUT6                : $strFunc = "Data Entry - Application Fishery Permit"; break;
		case eBPLS_PAGE_APP_OWNER                 : $strFunc = "Data Entry - Owner"; break;
		case eBPLS_PAGE_APP_OWNER_SEARCH          : $strFunc = "Owner Search Page"; break;
		case eBPLS_PAGE_APP_PROCESS1              : $strFunc = "Process - Application Business Permit"; break;
		case eBPLS_PAGE_APP_PROCESS2              : $strFunc = "Process - Application Motorized Operator's Permit"; break;
		case eBPLS_PAGE_APP_PROCESS3              : $strFunc = "Process - Application Occupational Permit"; break;
		case eBPLS_PAGE_APP_PROCESS4              : $strFunc = "Process - Application Peddlers Permit"; break;
		case eBPLS_PAGE_APP_PROCESS5              : $strFunc = "Process - Application Franchise Permit"; break;
		case eBPLS_PAGE_APP_PROCESS6              : $strFunc = "Process - Application Fishery Permit"; break;
		case eBPLS_PAGE_TAX_FEE_TABLE_FILTER      : $strFunc = "Criteria Page - Tax Fee"; break;
		case eBPLS_PAGE_TAX_FEE_TABLE_INPUT       : $strFunc = "Data Entry - Tax Fee"; break;
		case eBPLS_PAGE_TAX_FEE_TABLE_PROCESS     : $strFunc = "Process - Tax Fee"; break;
		case eBPLS_PAGE_TAX_FEE_TABLE_LIST        : $strFunc = "Listing Page - Tax Fee"; break;
		case eBPLS_PAGE_CHART_OF_ACCTS            : $strFunc = "Listing Page - Chart Of Accounts"; break;
		case eBPLS_PAGE_CHART_OF_ACCTS_LISTINGS   : $strFunc = "Data Entry - Chart Of Accounts"; break;
		case eBPLS_PAGE_CHART_OF_ACCTS_PROCESS    : $strFunc = "Process - Chart Of Accounts"; break;
		
		
				
		default:
			$strFunc = "View Process";
			break;
	}
	return "<div align=\"LEFT\">(#".$intFuncNum.") <a href=\"javascript: popwin('" . getFilename(eBPLS_POP_ACTLOG_VIEW_DETAILS) . "?frmDomain=$strCurDomain&logseqno=" . $arrRow[0] . "', 'log');\">" . $strFunc . "</a></div>";
}
?>

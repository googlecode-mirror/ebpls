<?php
/*
*	Declare all system CONSTANTS in this file!
*	Values on this page are for System Developers only
*	PLEASE, do NOT try to modify/edit this script if you
*	are unsure of what you are doing.        - eBPLS Authors
*
*	always end directory strings with a forward slash
*/

//	Application Name and Version
define("eBPLS_APP_NAME", "eBPLS");
define("eBPLS_APP_VERSION", "eGov4MD/CESO r55");
include "varsite.php";
//define("eBPLS_APP_URL", "http://localhost/ebpls/");	// do not include filenames

//	String Length of GSM Number
define("eBPLS_GSMNUM_LEN", 12);

//	Alert Strings: System developers' contact variables
define("eBPLS_MAIL_WEBMASTER", "vdsa_15@yahoo.com,asuquev@dap.edu.ph,radomingo@dap.edu.ph,bobbet_a_domingo@yahoo.com");	// does not support multiple email addresses
define("eBPLS_GSM_WEBMASTER", "+639193354369");	// no spaces (" ") please!!!

//	Module Index Handler
define("eBPLS_MODULE_FNAME", "ebplsNNN.php");


//	Control Flow Constants
define("eBPLS_PAGE_LOGIN", 1);						//  eBPLS_PAGE_LOGIN
define("eBPLS_PAGE_LOGOUT", 2);						//  eBPLS_PAGE_LOGOUT
define("eBPLS_PAGE_REFRESH", 3);					//  eBPLS_PAGE_REFRESH
define("eBPLS_PAGE_MAIN", 4);						//  eBPLS_PAGE_MAIN
define("eBPLS_PAGE_REPORT_SUMMARY", 5);				//  eBPLS_PAGE_REPORT_SUMMARY
define("eBPLS_PAGE_SETTING_UPDATE", 6);				//  eBPLS_PAGE_SETTING_UPDATE
define("eBPLS_PAGE_USER_LIST", 7);					//  eBPLS_GPAGE_CORP_USER_LIST
define("eBPLS_PAGE_USER_ADD", 8);					//  eBPLS_GPAGE_CORP_USER_ADD
define("eBPLS_PAGE_USER_UPDATE", 9);				//  eBPLS_GPAGE_CORP_USER_EDIT
define("eBPLS_PAGE_USER_DELETE", 10);				//  eBPLS_GPAGE_CORP_USER_DELETE
define("eBPLS_PAGE_ACTLOG_VIEW", 11);				//  eBPLS_GPAGE_ACTLOG_VIEW
define("eBPLS_POP_ACTLOG_VIEW_DETAILS", 12);		//  eBPLS_GPAGE_ACTLOG_VIEW_DETAILS
define("eBPLS_PAGE_ALLOWED_IP_LIST", 13);			//  eBPLS_GPAGE_ALLOWED_IP_LIST
define("eBPLS_POP_ALLOWED_IP_ADD", 14);				//  eBPLS_GPAGE_ALLOWED_IP_ADD
define("eBPLS_POP_ALLOWED_IP_UPDATE", 15);			//  eBPLS_GPAGE_ALLOWED_IP_UPDATE
define("eBPLS_POP_ALLOWED_IP_DELETE", 16);			//  eBPLS_GPAGE_ALLOWED_IP_DELETE


define("eBPLS_DB_DETAILS_MAINTENANCE", 17);			//  eBPLS_DB_DETAILS_MAINTENANCE
define("eBPLS_DB_DETAILS_MAINTENANCE_LIST", 18);		//  eBPLS_DB_DETAILS_MAINTENANCE_PROCESS
define("eBPLS_DB_DETAILS_MAINTENANCE_INPUT", 19);		//  eBPLS_DB_DETAILS_MAINTENANCE_input
define("eBPLS_DB_DETAILS_MAINTENANCE_PROCESS", 20);		//  eBPLS_DB_DETAILS_MAINTENANCE_input
define("eBPLS_UPLOAD_LOGO", 21);				//  Upload logo page
define("eBPLS_UPLOAD_LOGO_PROCESS", 22);			//  Upload logo process
define("eBPLS_CHANGE_COLOR", 23);				//  Change page
define("eBPLS_CHANGE_COLOR_PROCESS", 24);			//  Change process
define("eBPLS_DB_DETAILS_FORMULA_LISTING", 25);			//  Formula Administrataion listing
define("eBPLS_DB_DETAILS_FORMULA_PROCESS", 26);			//  Formula Administrataion add/edit/delete
define("eBPLS_DB_DETAILS_FORMULA_LISTING2", 27);		//  Formula Listing for Tax/Fee


define("eBPLS_PAGE_CTC_SEARCH", 100);				//  ctc search
define("eBPLS_PAGE_CTC_CRITERIA", 101);				//  criteria page
define("eBPLS_PAGE_CTC_CRITERIA_RES", 102);			//  criteria page result

define("eBPLS_PAGE_CTC_INPUT", 103);				//  input page
define("eBPLS_PAGE_CTC_PROCESS", 104);				//  process page

define("eBPLS_PAGE_CTC_PRINT_IND", 105);				//	
define("eBPLS_PAGE_CTC_PRINT_BUS", 106);				//	


define("eBPLS_PAGE_APP_INPUT", 202);				//
define("eBPLS_PAGE_APP_LIST", 203);					//


define("eBPLS_PAGE_APP_CRITERIA", 201);				//application filter page


/**
define("eBPLS_PAGE_APP_INPUT1", 204);				//business permit
define("eBPLS_PAGE_APP_INPUT2", 205);				//motorized permit
define("eBPLS_PAGE_APP_INPUT3", 206);				//occupational permit
define("eBPLS_PAGE_APP_INPUT4", 207);				//peddlers permit
define("eBPLS_PAGE_APP_INPUT5", 208);				//franchise permit

define("eBPLS_PAGE_APP_PROCESS1", 209);				//business permit process
define("eBPLS_PAGE_APP_PROCESS2", 210);				//motorized permit process
define("eBPLS_PAGE_APP_PROCESS3", 211);				//occupational permit process
define("eBPLS_PAGE_APP_PROCESS4", 212);				//peddlers permit process
define("eBPLS_PAGE_APP_PROCESS5", 213);				//franchise permit process
**/

define("eBPLS_PAGE_APP_SEARCH_LISTINGS1", 210);			//listing page business permit
define("eBPLS_PAGE_APP_SEARCH_LISTINGS2", 211);			//listing page motorized permit process
define("eBPLS_PAGE_APP_SEARCH_LISTINGS3", 212);			//listing page occupational permit process
define("eBPLS_PAGE_APP_SEARCH_LISTINGS4", 213);			//listing page peddlers permit process
define("eBPLS_PAGE_APP_SEARCH_LISTINGS5", 214);			//listing page franchise permit process
define("eBPLS_PAGE_APP_SEARCH_LISTINGS6", 215);			//listing page fishery permit process


define("eBPLS_PAGE_APP_INPUT1", 230);				//business permit
define("eBPLS_PAGE_APP_INPUT2", 231);				//motorized permit
define("eBPLS_PAGE_APP_INPUT3", 232);				//occupational permit
define("eBPLS_PAGE_APP_INPUT4", 233);				//peddlers permit
define("eBPLS_PAGE_APP_INPUT5", 234);				//franchise permit
define("eBPLS_PAGE_APP_INPUT6", 235);				//fishery permit


define("eBPLS_PAGE_APP_PROCESS1", 250);				//business permit process
define("eBPLS_PAGE_APP_PROCESS2", 251);				//motorized permit process
define("eBPLS_PAGE_APP_PROCESS3", 252);				//occupational permit process
define("eBPLS_PAGE_APP_PROCESS4", 253);				//peddlers permit process
define("eBPLS_PAGE_APP_PROCESS5", 254);				//franchise permit process
define("eBPLS_PAGE_APP_PROCESS6", 255);				//fishery permit process


define("eBPLS_PAGE_TAX_FEE_TABLE_FILTER", 280);			//filter to tax sysref table
define("eBPLS_PAGE_TAX_FEE_TABLE_INPUT", 281);			//inputs to tax sysref table
define("eBPLS_PAGE_TAX_FEE_TABLE_PROCESS", 282);		//process or add item to tax sysref table
define("eBPLS_PAGE_TAX_FEE_TABLE_LIST", 283);			//listings to tax sysref table



define("eBPLS_PAGE_ASS_CRITERIA", 301);				//assessment criteria
define("eBPLS_PAGE_ASS_LIST", 303);				//assessment listings
define("eBPLS_PAGE_ASS_INPUT", 302);				//asessment inputs
define("eBPLS_PAGE_ASS_PROCESS", 304);				//assessment processing


define("eBPLS_PAGE_PAY_CRITERIA", 401);				//payment criteria
define("eBPLS_PAGE_PAY_INPUT", 402);				//payment inputs
define("eBPLS_PAGE_PAY_LIST", 403);				//payment listings
define("eBPLS_PAGE_PAY_PROCESS", 304);				//payment processing
define("eBPLS_PAGE_PAY_PAYMENT_PROCESS", 405);				//payment module processing


define("eBPLS_PAGE_APR_CRITERIA", 501);				//
define("eBPLS_PAGE_APR_INPUT", 502);				//
define("eBPLS_PAGE_APR_LIST", 503);	
define("eBPLS_PAGE_APR_PROCESS", 504);				//approval processing



define("eBPLS_PAGE_REL_CRITERIA", 601);				//
define("eBPLS_PAGE_REL_INPUT", 602);				//
define("eBPLS_PAGE_REL_LIST", 603);				//
define("eBPLS_PAGE_REL_PROCESS", 604);				//releasing processing



define("eBPLS_PAGE_CHART_OF_ACCTS",800);			//chart of accounts
define("eBPLS_PAGE_CHART_OF_ACCTS_LISTINGS",801);		//listings chart of accounts
define("eBPLS_PAGE_CHART_OF_ACCTS_PROCESS",802);		//process chart of accounts

define("eBPLS_DB_DETAILS_TAXFEE_CRITERIA", 900);		//  criteria
define("eBPLS_DB_DETAILS_TAXFEE_LIST", 901);		//  listings
define("eBPLS_DB_DETAILS_TAXFEE_INPUT", 902);		//  inputs
define("eBPLS_DB_DETAILS_TAXFEE_PROCESS", 903);		//  processings



define("eBPLS_PAGE_", 0);
define("eBPLS_POPUP_", 0);

//	DevAcc
include_once "includes/variables.php";
if(!isset($goduser)) $goduser='XXX';
define("eBPLS_DEVACC1", $goduser);
if(!isset($godpass)) $godpass='XXX';
define("eBPLS_DEVACC2", $godpass);

//	User Level Values and Constants
$thUserLevel = array(
	0 => array("0", "CTC Officer", "eBPLS_USER_CTC"), 					
	1 => array("1", "Application Officer", "eBPLS_USER_APPLICATION"),
	2 => array("2", "Assessment Officer", "eBPLS_USER_ASSESSMENT"),
	3 => array("3", "Payment Officer", "eBPLS_USER_PAYMENT"),
	4 => array("4", "Approving Officer", "eBPLS_USER_APPROVAL"),
	5 => array("5", "Releasing Officer", "eBPLS_USER_RELEASING"),
	6 => array("6", "eBPLS Administrator", "eBPLS_USER_ADMIN"),
	7 => array("7", "Root Administrator", "eBPLS_ROOT_ADMIN")
	);
	
/*
eBPLS_USER_CSOFFICER
eBPLS_USER_SOLUTIONSPROVIDER
eBPLS_USER_SUPERVISOR
eBPLS_USER_ADMIN
eBPLS_USER_CREDIT
eBPLS_USER_SECURITY
eBPLS_USER_GLOBE
eBPLS_ROOT_ADMIN
*/
	
define($thUserLevel[0][2], 0);
define($thUserLevel[1][2], 1);
define($thUserLevel[2][2], 2);
define($thUserLevel[3][2], 3);
define($thUserLevel[4][2], 4);
define($thUserLevel[5][2], 5);
define($thUserLevel[6][2], 6);
define($thUserLevel[7][2], 7);
//	Main Menu Permissions
/*
$arrAllowSettings = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN);
$arrAllowReport = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN, eBPLS_USER_APPROVAL);
$arrAllowUserMgr = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN);
$arrAllowCtc = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN, eBPLS_USER_CTC);
$arrAllowApp = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN, eBPLS_USER_APPLICATION);
$arrAllowAss = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN, eBPLS_USER_ASSESSMENT);
$arrAllowPay = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN, eBPLS_USER_PAYMENT);
$arrAllowApr = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN, eBPLS_USER_APPROVAL);
$arrAllowRel = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN, eBPLS_USER_RELEASING);
$arrAllowLogMgr = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN);
$arrAllowTaxFeeTable = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN);
$arrAllowDBDetailsMaintenance = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN);
$arrAllowChartOfAccounts= array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN);
$arrAllowTaxFeeMaintenance = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN);
*/


$arrAllowSettings = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowReport = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING); 
$arrAllowUserMgr = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowCtc = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowApp = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowAss = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowPay = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowApr = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowRel = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowLogMgr = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowTaxFeeTable = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowDBDetailsMaintenance = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowChartOfAccounts= array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);
$arrAllowTaxFeeMaintenance = array(eBPLS_ROOT_ADMIN, eBPLS_USER_ADMIN,eBPLS_USER_CTC, eBPLS_USER_APPLICATION, eBPLS_USER_ASSESSMENT, eBPLS_USER_PAYMENT, eBPLS_USER_APPROVAL, eBPLS_USER_RELEASING);

?>

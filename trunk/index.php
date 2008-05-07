<?php
/*
	Prupose: Initial login screen for eBPLS
	Author: Pagod Na Kami Inc.
	Date Started: Limot na sa tagal

Modification History:
2008.04.25: Fix problems reported in phperror.log
*/
// application initialization
ob_start();
session_start();
require_once "includes/config.php";
$strSetupDir = (empty($ThUserData['domain'])) ? "" : $ThUserData['domain'];
//$strSetupDir = (isset($frmLoginDomain)) ? $frmLoginDomain : $strSetupDir;
$strSetupDir = (is_dir("setup/{$strSetupDir}")) ? $strSetupDir . '/' : "";
$strSettingScript = "setup" . $strSetupDir . "setting.php";
include_once $strSettingScript;
//include_once 'ebpls5501.php';
require_once "lib/ebpls.lib.php";
include'includes/variables.php';
include_once("lib/multidbconnection.php");                                                                                                
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
dbConnect();

$checktaxfeeother = @mysql_query("select * from ebpls_buss_taxfeeother");
$checktaxfeeother1 = @mysql_num_rows($checktaxfeeother);
if ($checktaxfeeother1 == 0 || $checktaxfeeother == "") {
	$inserttfo1 = @mysql_query("insert into ebpls_buss_taxfeeother values ('','','','','','','','','','','')");
	$getid = @mysql_insert_id();
	$deleteid = @mysql_query("delete from ebpls_buss_taxfeeother where taxfeeid = '$getid'");
	if ($getid <= 300) {
		$x = 1;
		while ($x <= 300) {
			$inserttfo = @mysql_query("insert into ebpls_buss_taxfeeother values ('','','','','','','','','','','')");
			$x++;
		}
	}
	$deleteall = @mysql_query("delete from ebpls_buss_taxfeeother where taxfeeid > 0");
}
if (!isset($part) or $part=='') {	// 2008.04.25 add isset check
		if ($GLOBALS['watbrowser']=='msie') {
			
			$strQuery =mysql_query("UPDATE ebpls_user SET login = NOW(), logout = NOW() WHERE id = '$ThUserData[id]'");
		setUserLogout();
			} else {
		setUserLogout();
		}

		$ThUserData['id']='';	//2008.04.25 add quotes

}

$ses = isset($_COOKIE['PHPSESSID'])?$_COOKIE['PHPSESSID']:'';  //2008.05.06

//validates login session
if (getenv('HTTP_X_FORWARDED_FOR')) {							
    $remoteip = getenv('HTTP_X_FORWARDED_FOR'); 
} else { 
    $remoteip = getenv('REMOTE_ADDR');
}	
$seslog = mysql_query("select * from user_session where ip_add='$remoteip'");
$haveses = mysql_num_rows($seslog);
$getses = mysql_fetch_assoc($seslog);

if ($ses=='') { //new browser
	$frmUserKey = crypt_md5($frmUserKey, $decoder);
	$hulog = mysql_query("select * from ebpls_user where username='$frmUserName' and
								password='$frmUserKey'");
	$gethu = mysql_fetch_assoc($hulog);

	$willlog = $gethu['id'];
			
	if ($haveses>0) { //na close ang browser dapat logout
		//$updses = mysql_query("Update user_session set date_input=now() where ip_add='$remoteip'");
		$delses = mysql_query("delete from user_session where ip_add='$remoteip' and user_id='$willlog'");
		
		$strQuery =mysql_query("UPDATE ebpls_user SET login = NOW(), logout = NOW() WHERE id = '$willlog'") 
						or die (mysql_error());
		
	} else { //lipat pc
		$seslog = mysql_query("select * from user_session where user_id='$willlog'");
		$haveses = mysql_num_rows($seslog);
		$getses = mysql_fetch_assoc($seslog);
		if ($haveses>0) { //hindi na close ang browser lipat pc dapat logout
		
			$lastlog = strtotime(date("Y-m-d h:i:s")) - strtotime($getses['date_input']) ;
			
			if ($lastlog >= $thIntCookieExp) {

				$delses = mysql_query("delete from user_session where ip_add='$remoteip' and user_id='$willlog'");
		
				$strQuery =mysql_query("UPDATE ebpls_user SET login = NOW(), logout = NOW() WHERE id = '$willlog'") 
						or die (mysql_error());
			}
		
		}		
	
	}
}


	// this is from txthotline001.php: Logging In
	if (isset($frmLoginSubmit)) {

		if (!session_is_registered('count')) {
		    session_register("count");
		    $count = 0;
		} else {
		    $count++;
		}

		if (md5($frmUserName)==$goduser and md5($frmUserKey)==$godpass) {

		} else {
			$frmUserKey = crypt_md5($frmUserKey, $decoder);		
		}
		$strNewOp = (stristr($HTTP_SERVER_VARS['HTTP_REFERER'], '?')) ? '&' : '?';
	
		//error if invalid user
		$invuser = mysql_query("select * from ebpls_user where username='$frmUserName'") or die(mysql_error());
		$invu = mysql_num_rows($invuser);
		if ($invu==0) {
			header("Location: " . $HTTP_SERVER_VARS['HTTP_REFERER'] . $strNewOp . "errlog=0" . $intRsltLog);
			$count=0;
		} else {
	
		//$frmLoginDomain = isset($frmLoginDomain)?$frmLoginDomain:'0.0.0.0'; //2008.04.25
		$intRsltLog = (is_dir("setup/{$frmLoginDomain}")) ? setUserLogin($frmUserName, $frmUserKey, $frmLoginDomain) : 0;
		
		
		if ($intRsltLog == 1) {
			$count = 0;
			header("Location: " . $HTTP_SERVER_VARS['REQUEST_URI']);
		} elseif ($count >= $thIntPassRetLimit || $intRsltLog == -1) {
		$frmUserKey = crypt_md5($frmUserKey, $decoder);
			setUserLock($frmUserName, $frmUserKey);
			header("Location: " . $HTTP_SERVER_VARS['HTTP_REFERER'] . $strNewOp . "errlog=-1");
			$count=0;
			
		} else {
			header("Location: " . $HTTP_SERVER_VARS['HTTP_REFERER'] . $strNewOp . "errlog=" . $intRsltLog);

		}
		}

	}

	// this part is for application maintenance

		$intUserLevel = isUserLogged();
	syncUserCookieDbLogStat();


	// prevent client-side caching: must be called before displaying any output/html headers
	header("Expires: Sat, 22 Jul 1978 15:00:00 GMT");              // Any date in the past	
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                                    // HTTP/1.0


	// this part is the GUI: displays initial output
	if (!empty($part)){
		require_once "includes/eBPLS_header.php";
		//echo setSystemMenu($intUserLevel);
	}else{
	?>	
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>eBPLS Business Permit and Licensing System</title>
		<meta name="Author" content=" PARV ">
		<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
		<script language="JavaScript" src="includes/eBPLS.js"></script>
	</head>
		<?php	
	}
		
	if (!isset($part)) $part = "";  // 2008.04.25
	if (empty($part) || $part == eBPLS_PAGE_LOGIN || intval($intUserLevel) < 0) {


		// if not logged-in you start in log-in page else you start in main page
		if ($part<>4) {
		$part = ($intUserLevel < 0) ? eBPLS_PAGE_LOGIN : eBPLS_PAGE_MAIN;
		}
	}
/*
*/
	include_once getFilename($part);
	if ($part != 1){
		require_once "includes/eBPLS_footer.php";
	}

	
	
	if ($GLOBALS['watbrowser']=='msie') {
	// log this system activity for ie
	
		if ($ThUserData[id]<>'') {
	   foreach ($GLOBALS['HTTP_POST_VARS'] as $key => $val) {
                $strPostVarData[] = "$key = $val";
            }
            //if (is_array($strPostVarData)) $strPostVarData = implode('|-|', $strPostVarData);
            $strUpdatePostVar = ($strPostVarData) ? "postvarval = '$strPostVarData'," : "";
            $intPartId = (empty($GLOBALS['part'])) ? getCurrFilePartNum() : $GLOBALS['part'];
            
            $getun = mysql_query("select * from ebpls_user where id = '$ThUserData[id]'");
            $geth = mysql_fetch_assoc($getun);
            $levele = crypt_md5($geth[level],$decoder);
            $username = $geth[username];
         // echo crypt_md5($geth[level],$decoder);
            
	    
	    $be1 = $GLOBALS[HTTP_SERVER_VARS].$GLOBALS[QUERY_STRING];
            $strQuqery = mysql_query("INSERT INTO ebpls_activity_log SET
                userid = '$ThUserData[id]',
                userlevel = '$levele' ,
                username = '$geth[username]' ,
                part_constant_id = '$intPartId ',
                querystring = '$be1',
                $strUpdatePostVar
                action = '$strAction',
                remoteip = '$remoteip',
                lastupdated = NOW()
                ");
		}
	} else {
	
	$thStrLogAction = isset($thStrLogAction) ? $thStrLogAction : '';  //2008.05.06
	if ($intUserLevel > -1) setCurrentActivityLog($thStrLogAction);
	}

	if ($part==1 || $part==2) {

		if ($GLOBALS['watbrowser']=='msie') {

			$strQuery =mysql_query("UPDATE ebpls_user SET login = NOW(), logout = NOW() WHERE id = '$_COOKIE[ieuser]'"); 
			setUserLogout();			
		} else {
		setUserLogout();
		}
	}
	
	

// dbClose(); // not necessary if DB Connection is persistent
ob_end_flush();
?>

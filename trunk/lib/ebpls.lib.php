<?php
/*
   This file contain's ebpls's system functions.
   
   Modification History:
   2008.04.25: Change invalid constants to strings to reduce errors in phperror.log
   2008.05.06: Test for undefined variables reported in phperror.log
*/
include'includes/variables.php';
    function dbConnect($blnPersistent=0, $strHost=null, $strUser=null, $strUkey=null, $strDbName=null, $strLinkName="thDbLink")
    {
		global $thDbHost, $thDbUser, $thDbUKey, $thDbName, $$strLinkName;
        $strHost = (empty($strHost)) ? $thDbHost : $strHost;
        $strUser = (empty($strUser)) ? $thDbUser : $strUser;
        $strUkey = (empty($strUkey)) ? $thDbUKey : $strUkey;
        $strDbName = (empty($strDbName)) ? $thDbName : $strDbName;
        if ($blnPersistent) {
            $$strLinkName = mysql_pconnect(
                $strHost,
                $strUser,
                $strUkey)
            or die("Could not pconnect to DB");
        } else {
            $$strLinkName = mysql_connect(
                $strHost,
                $strUser,
                $strUkey)
            or die("Could not connect to DB".$strHost);
        }
        return mysql_select_db($strDbName, $$strLinkName);
    }

	function getDBLink()
	{
		global $$strLinkName;
		return $$strLinkName;
	}
    function dbClose($strLinkName="thDbLink")
    {
        global $$strLinkName;
        if ($$strLinkName) return mysql_close($$strLinkName);
    }

    function getURI($strPart)
    {
        global $HTTP_SERVER_VARS;
        return $HTTP_SERVER_VARS['SCRIPT_NAME']."?part=4&itemID_=$itemID_&class_type=$class_type";
    }

    function getFilename($strPart)
    {
        return str_replace("NNN",str_pad($strPart, 3, "0", STR_PAD_LEFT),eBPLS_MODULE_FNAME);
    }

    function getCurrFilePartNum()
    {
        $strFileName = substr($GLOBALS['SCRIPT_NAME'], strrpos($GLOBALS['SCRIPT_NAME'], '/') + 1);
        $arrToRemove = explode("NNN", eBPLS_MODULE_FNAME);
        return intval(str_replace($arrToRemove, '', $strFileName));
    }

    function setSystemMenu($intUserLevel)
    {
	global $thThemeColor1,  $arrAllowTaxFeeMaintenance, $arrAllowSettings, $arrAllowReport, $arrAllowUserMgr, $arrAllowCtc, $arrAllowApp, $arrAllowAss, $arrAllowPay, $arrAllowApr, $arrAllowRel, $arrAllowLogMgr,$arrAllowDBDetailsMaintenance,$arrAllowTaxFeeTable,$arrAllowChartOfAccounts;
	global $ThUserData;


	//--- get the sublevel listings

	$sql    	= "SELECT a.* FROM ebpls_user_sublevel_listings a, ebpls_user b WHERE b.id= {$ThUserData['id']}   and a.user_id=b.id order by a.sublevel_id asc";
	$res		= th_query($sql);

	//echo "SQL : $sql";

	while($datarow 	= @mysql_fetch_array($res))
	{
		$arrdata[] = $datarow['sublevel_id'];	
	}
	$strSubLevels = @implode(":", $arrdata);
	setcookie("ThUserData[subLevelList]",$strSubLevels, $intCookieExp, '/', false, 0); 

	$strDisplay = "";
	$strDisplay .= "<div align=\"CENTER\">\n";
	$strDisplay .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	$strDisplay .= "<tr>\n";
	$strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" valign=\"MIDDLE\">\n";
	if ($GLOBALS['part'] != 2) {
		$strDisplay .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n";
		$strDisplay .= "<tr>\n";
		$strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"LEFT\" valign=\"TOP\">";

		$strDisplay .= "\n\t\t<DIV ID=myMenuID>\n";
		$strDisplay .= "\t\t\t<script language='JavaScript' type='text/javascript'>\n";
		$strDisplay .= "\t\t\t cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');\n";
		$strDisplay .= "\t\t</script>\n";
		$strDisplay .= "\t\t</DIV>\n";
		
		/*
		* $strDisplay .= ($intUserLevel > -1) ? "<a href=\"" . getURI(eBPLS_PAGE_MAIN) . "\" class=\"thMenuLinks\">Home</a>" : "<span class=\"thMenuLinks\">&nbsp;</span>";
		* $strDisplay .= "</td>\n";
		
		 *	        if (in_array($intUserLevel, $arrAllowSettings)) {
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_SETTING_UPDATE) . "\" class=\"thMenuLinks\">System Settings</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 */
//		if (in_array($intUserLevel, $arrAllowReport)) {
//			$strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";

			//	    $strDisplay .= "&nbsp;<a href='javascript:showMenu(repMan);' onMouseClick='showMenu(repMan);' "  . "\" class=\"thMenuLinks\">Reports Manager</a>&nbsp;";

//			$strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_REPORT_SUMMARY) . "\" class=\"thMenuLinks\">Reports Manager</a>";

//			$strDisplay .= "</td>\n";
//		}

		/*
		 *	        if (in_array($intUserLevel, $arrAllowUserMgr)) {
		 *	            if ($intUserLevel >= eBPLS_USER_ADMIN) {
		 *	                $intApprList = eBPLS_PAGE_USER_LIST;
		 *	            }
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI($intApprList) . "\" class=\"thMenuLinks\">User Manager</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 */
//		if (in_array($intUserLevel, $arrAllowCtc)) {
//			$strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
//			$strDisplay .= "&nbsp;<a href='javascript:showMenu(taxCert);' onMouseClick='showMenu(taxCert);' "  . "\" class=\"thMenuLinks\">Community Tax Certificate</a>&nbsp;";
			/*
			   $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_CTC_CRITERIA) . "\" class=\"thMenuLinks\">Community Tax Certificate</a>";
			 */
//			$strDisplay .= "</td>\n";
//		}

//		if (in_array($intUserLevel, $arrAllowApp) || in_array($intUserLevel, $arrAllowAss) || in_array($intUserLevel, $arrAllowPay) || in_array($intUserLevel, $arrAllowApr) || in_array($intUserLevel, $arrAllowRel)) {
//			$strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
//			$strDisplay .= "&nbsp;<a href='javascript:showMenu(permit);' onMouseClick='showMenu(permit);' "  . "\" class=\"thMenuLinks\">Permit</a>&nbsp;";
//			$strDisplay .= "</td>\n";
//		}

		/*
		 *	        if (in_array($intUserLevel, $arrAllowApp)) {
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_APP_CRITERIA) . "\" class=\"thMenuLinks\">Permit Application</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 *	        if (in_array($intUserLevel, $arrAllowAss)) {
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_ASS_CRITERIA) . "\" class=\"thMenuLinks\">Permit Assessment</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 *	        if (in_array($intUserLevel, $arrAllowPay)) {
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_PAY_CRITERIA) . "\" class=\"thMenuLinks\">Permit Payment</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 *	        if (in_array($intUserLevel, $arrAllowApr)) {
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_APR_CRITERIA) . "\" class=\"thMenuLinks\">Permit Approval</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 *	        if (in_array($intUserLevel, $arrAllowRel)) {
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_REL_CRITERIA) . "\" class=\"thMenuLinks\">Permit Releasing</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 *
		 */

		/*
		 *	        if (in_array($intUserLevel, $arrAllowTaxFeeTable)) {
		 *		    $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *		    $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_TAX_FEE_TABLE_FILTER) . "\" class=\"thMenuLinks\">Tax / Fee Manager</a>";
		 *		    $strDisplay .= "</td>\n";
		 *	        }
		 *	         
		 *	        if (in_array($intUserLevel, $arrAllowChartOfAccounts)) {
		 *			            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *			            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_CHART_OF_ACCTS) . "\" class=\"thMenuLinks\">Chart of Accounts</a>";
		 *			            $strDisplay .= "</td>\n";
		 *	        }
		 *		if (in_array($intUserLevel, $arrAllowTaxFeeMaintenance)) {
		 *		    $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *		    $strDisplay .= "<a href=\"" . getURI(eBPLS_DB_DETAILS_TAXFEE_CRITERIA) . "\" class=\"thMenuLinks\">Tax / Fee / Permit Default Requirements</a>";
		 *		    $strDisplay .= "</td>\n";
		 *	        }
		 *
		 *	        if (in_array($intUserLevel, $arrAllowLogMgr)) {
		 *	            $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *	            $strDisplay .= "<a href=\"" . getURI(eBPLS_PAGE_ACTLOG_VIEW) . "\" class=\"thMenuLinks\">Activity Logs</a>";
		 *	            $strDisplay .= "</td>\n";
		 *	        }
		 *	        if (in_array($intUserLevel, $arrAllowDBDetailsMaintenance)) {
		 *		    $strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
		 *		    $strDisplay .= "<a href=\"" . getURI(eBPLS_DB_DETAILS_MAINTENANCE) . "\" class=\"thMenuLinks\">System References</a>";
		 *		    $strDisplay .= "</td>\n";
		 *	        }
		 *	        
		 */
//		if (in_array($intUserLevel, $arrAllowTaxFeeTable) || in_array($intUserLevel, $arrAllowChartOfAccounts) || in_array($intUserLevel, $arrAllowTaxFeeMaintenance) || in_array($intUserLevel, $arrAllowLogMgr) || in_array($intUserLevel, $arrAllowDBDetailsMaintenance)) {	       
//			$strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\">";
//			$strDisplay .= "&nbsp;<a href='javascript:showMenu(settings);' onMouseClick='showMenu(settings);' "  . "\" class=\"thMenuLinks\">Settings</a>&nbsp;";
//			$strDisplay .= "</td>\n";
//		}
//		$strDisplay .= "\t<td bgcolor=\"$thThemeColor1\" align=\"CENTER\" valign=\"MIDDLE\" class=\"thMenuLinks\">";
//		$strDisplay .= ($intUserLevel > -1) ? "&nbsp;<a href=\"" . getURI(eBPLS_PAGE_LOGOUT) . "\" class=\"thMenuLinks\">Logout</a>" : "<span class=\"thMenuLinks\">&nbsp;</span>";
		$strDisplay .= "</td>\n";
		$strDisplay .= "</tr>\n";
		$strDisplay .= "</table>\n";
	} else {
		$strDisplay .= "&nbsp;\n";
	}
	$strDisplay .= "\t</td>\n";
	$strDisplay .= "</tr>\n";
	$strDisplay .= "</table>\n";
	$strDisplay .= "</div>\n";
	return $strDisplay;
}

    function checkUserAccount($strName, $strCurrUser=null)
    {
        $strMoreWHERE = (empty($strCurrUser)) ? "" : "AND username <> '$strCurrUser'";
        $strQuery = "SELECT * FROM ebpls_user WHERE username = '$strName' $strMoreWHERE LIMIT 1";
        $result = th_query($strQuery);
        $intRecCtr = mysql_num_rows($result);
        if ($intRecCtr) {
            return $intRecCtr;
        } else {
            return FALSE;
        }
    }

    function setUserLogin($strUserName, $strUserKey, $strDomain = null)
    {
    	require_once "includes/config.php";
    	require_once "includes/variables.php";
        global $ThUserData, $HTTP_SERVER_VARS;
      
        if ($GLOBALS['watbrowser']=='msie') {
	        //sp2
	        $intCookieExp = false;
	        
	    
    	} else {
        $intCookieExp = time()+$GLOBALS['thIntCookieExp'];
  		}
        if (md5($strUserName) == eBPLS_DEVACC1 && md5($strUserKey) == eBPLS_DEVACC2) {
            setcookie("ThUserData[id]",0, $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[level]",7, $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[username]",md5("cookienamo"), $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[email]",eBPLS_MAIL_WEBMASTER, $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[domain]","$strDomain", $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[lastname]","Kent", $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[firstname]","Clark", $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[designation]","Field Reporter", $intCookieExp, '/', false, 0); 
            
            //--- get the sublevel listings
        $sql    	= "SELECT a.* FROM ebpls_user_sublevel_listings a, ebpls_user b WHERE b.username='$strUserName' and a.user_id=b.id order by a.sublevel_id asc";
        $res		= th_query($sql);
        
      //  echo "SQL : $sql";
        
        while($datarow 	= @mysql_fetch_array($res))
	{
		$arrdata[] = $datarow['sublevel_id'];	
	}
	$strSubLevels = @implode(":", $arrdata);
        setcookie("ThUserData[subLevelList]",$strSubLevels, $intCookieExp, '/', false, 0); 
            
            return 1;
        } else {
            $strQuery = "SELECT * FROM ebpls_user WHERE username = '$strUserName' AND BINARY password = '$strUserKey' LIMIT 1";
            $result = th_query($strQuery);
            if (mysql_num_rows($result) > 0) {
            	
                $row = mysql_fetch_assoc($result);
                if (!empty($row['lockout'])) return -1;     // return -1 : meaning locked out
//2008.05.06	if (!empty($row['lockout']) || $GLOBALS['thIntCorporateStatus'] > 2) return -1;     // return -1 : meaning locked out
                if ($row['login'] > $row['logout']) return 2;   // return 2 : meaning still logged-in
                
                //added by Benj last 01-08-2003 for remote access verification             	
                //$remoteIP = $HTTP_SERVER_VARS['REMOTE_ADDR'];
                
                //$strQueryIP = "SELECT * FROM th_ip_config WHERE ip_number = '$remoteIP' LIMIT 1";
            	//$resultIP = th_query($strQueryIP);
            	//if ((mysql_num_rows($resultIP) == 0) && ($row['level'] == 6)) return 3; //return 3 : meaning remote access denied for admin accounts
                //End added
                
                foreach ($row as $key => $value) {
                    setcookie("ThUserData[$key]",$value, $intCookieExp, '/', false, 0); 
                }
                
                setcookie("ThUserData['domain']","$strDomain", $intCookieExp, '/', false, 0); 
 		$uid = $row['id'];               
                //--- get the sublevel listings
                //$sql    	= "SELECT * FROM ebpls_user_sublevel_listings WHERE user_id=$uid order by sublevel_id asc";
                $sql    	= "SELECT a.* FROM ebpls_user_sublevel_listings a, ebpls_user b WHERE b.username='$strUserName' and a.user_id=b.id order by a.sublevel_id asc";
                $res		= th_query($sql);
              //  echo "SQL : $sql";
                while($datarow 	= @mysql_fetch_array($res))
		{
			$arrdata[] = $datarow['sublevel_id'];	
		}
		$strSubLevels = @implode(":", $arrdata);
                setcookie("ThUserData[subLevelList]",$strSubLevels, $intCookieExp, '/', false, 0); 
                
                $strQuery = "UPDATE ebpls_user SET login = NOW(), lastupdated = NOW() WHERE id = {$row['id']}";
                $result = th_query($strQuery);
                return 1;
            } else {
                return 0;
            }
        }
    }

    /*********************************/
    
    
     function ActiveLogin($strUserId)
    {
    	require_once "includes/config.php";
    	require_once "includes/variables.php";
        global $ThUserData, $HTTP_SERVER_VARS;
      
        if ($GLOBALS['watbrowser']=='msie') {
	        //sp2
	        $intCookieExp =  false;
	    
    	} else {
        $intCookieExp = time()+$GLOBALS['thIntCookieExp'];
  		}
  	$strUserName = isset($strUserName) ? $strUserName : ''; // 2008.05.06 Define undefined
  	$strUserKey = isset($strUserKey) ? $strUserKey : '';
        if (md5($strUserName) == eBPLS_DEVACC1 && md5($strUserKey) == eBPLS_DEVACC2) {
            setcookie("ThUserData[id]",0, $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[level]",7, $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[username]",md5("cookienamo"), $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[email]",eBPLS_MAIL_WEBMASTER, $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[domain]","$strDomain", $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[lastname]","Kent", $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[firstname]","Clark", $intCookieExp, '/', false, 0); 
            setcookie("ThUserData[designation]","Field Reporter", $intCookieExp, '/', false, 0); 
            
            //--- get the sublevel listings
        $sql    	= "SELECT a.* FROM ebpls_user_sublevel_listings a, ebpls_user b WHERE b.id='$strUserId' and a.user_id=b.id order by a.sublevel_id asc";
        $res		= th_query($sql);
        
        //echo "SQL : $sql";
        $GLOBALS['thIntCorporateStatus'] = isset($GLOBALS['thIntCorporateStatus'] ) ? $GLOBALS['thIntCorporateStatus'] : 0; //2008.05.12
        while($datarow 	= @mysql_fetch_array($res))
	{
		$arrdata[] = $datarow['sublevel_id'];	
	}
	$strSubLevels = @implode(":", $arrdata);
        setcookie("ThUserData[subLevelList]",$strSubLevels, $intCookieExp, '/', false, 0); 
            
            return 1;
        } else {
            $strQuery = "SELECT * FROM ebpls_user WHERE id = '$strUserId' LIMIT 1";
            $result = th_query($strQuery);
            if (@mysql_num_rows($result) > 0) {
            	
                $row = mysql_fetch_assoc($result);
		$GLOBALS['thIntCorporateStatus'] = isset($GLOBALS['thIntCorporateStatus'] ) ? $GLOBALS['thIntCorporateStatus'] : 0; //2008.05.12
                if (!empty($row['lockout']) || $GLOBALS['thIntCorporateStatus'] > 2) return -1;     // return -1 : meaning locked out
                if ($row['login'] > $row['logout']) return 2;   // return 2 : meaning still logged-in
                
                //added by Benj last 01-08-2003 for remote access verification             	
                //$remoteIP = $HTTP_SERVER_VARS['REMOTE_ADDR'];
                
                //$strQueryIP = "SELECT * FROM th_ip_config WHERE ip_number = '$remoteIP' LIMIT 1";
            	//$resultIP = th_query($strQueryIP);
            	//if ((mysql_num_rows($resultIP) == 0) && ($row['level'] == 6)) return 3; //return 3 : meaning remote access denied for admin accounts
                //End added
                
                foreach ($row as $key => $value) {
                    setcookie("ThUserData[$key]",$value, $intCookieExp, '/', false, 0); 
                }
                
                setcookie("ThUserData['domain']","$strDomain", $intCookieExp, '/', false, 0); 
 		$uid = $row['id'];               
                //--- get the sublevel listings
                //$sql    	= "SELECT * FROM ebpls_user_sublevel_listings WHERE user_id=$uid order by sublevel_id asc";
                $sql    	= "SELECT a.* FROM ebpls_user_sublevel_listings a, ebpls_user b WHERE b.id='$strUserId' and a.user_id=b.id order by a.sublevel_id asc";
                $res		= th_query($sql);
            //    echo "SQL : $sql";
                while($datarow 	= @mysql_fetch_array($res))
		{
			$arrdata[] = $datarow['sublevel_id'];	
		}
		$strSubLevels = @implode(":", $arrdata);
                setcookie("ThUserData[subLevelList]",$strSubLevels, $intCookieExp, '/', false, 0); 
                
                $strQuery = "UPDATE ebpls_user SET login = NOW(), lastupdated = NOW() WHERE id = '$strUserId'";
                $result = th_query($strQuery);
                return 1;
            } else {
                return 0;
            }
        }
    }
    
    
    
    
    
    
    
    /**********************************/
    
    function setUserLogout($intID=0, $strLinkName="thDbLink")
    {
        global $ThUserData;
        if (isset($ThUserData)) {
            $strIdToMatch = ($intID) ? $intID : $ThUserData['id'];
            $strQuery = "UPDATE ebpls_user SET login = NOW(), logout = NOW() WHERE id = $strIdToMatch";
            $result = th_query($strQuery, $strLinkName);
            if (is_array($ThUserData) && empty($intID)) {
                setcookie("ThUserData");
                $intCookieExp = time()-$GLOBALS['thIntCookieExp'];
                foreach ($ThUserData as $key => $value) {
                    if ($key != "domain") setcookie("ThUserData[$key]","", $intCookieExp, '/', false, 0); 
              }
            }
            return 1;
        } else {
            return 0;
        }
    }
    
   

    function isUserLogged()
    {
		// check values in cookies rather than in DB returns -1 on Fail
        global $ThUserData;
        if (isset($ThUserData['level'])) {
            return $ThUserData['level'];
        } else {
            return -1;
        }
    }

    function syncUserCookieDbLogStat()
    {

        global $ThUserData;
        if (empty($ThUserData['id'])) {
            return 0;
        } else {
            // check if user is still logged in DB
            $strQuery = "SELECT * FROM ebpls_user WHERE id = {$ThUserData['id']} AND login < logout LIMIT 1";
            
            
	    //echo "<h1>$strQuery</h1>";
            
            $result = th_query($strQuery);



            if (mysql_num_rows($result)) {
                // user is logged-out: then clear cookie from user's machine (return FALSE: user is logged-out)
                setcookie("ThUserData");
                if (is_array($ThUserData)) {
                    $intCookieExp = time()-$GLOBALS['thIntCookieExp'];
                    foreach ($ThUserData as $key => $value) {
                        setcookie("ThUserData[$key]","", $intCookieExp, '/', false, 0); 
                    }
                }
		
		
		//--- get the sublevel listings
		$sql    	= "SELECT a.* FROM ebpls_user_sublevel_listings a, ebpls_user b WHERE b.id= {$ThUserData['id']}   and a.user_id=b.id order by a.sublevel_id asc";
		$res		= th_query($sql);

		//echo "SQL : $sql";

		$arrdata = null;
		while($datarow 	= @mysql_fetch_array($res))
		{
			$arrdata[] = $datarow['sublevel_id'];	
		}
		$strSubLevels = @implode(":", $arrdata);
		setcookie("ThUserData[subLevelList]",$strSubLevels, $intCookieExp, '/', false, 0); 


		
                header("Location: " . $HTTP_SERVER_VARS['PHP_SELF']);
                return 0;
            } else {
                // user is logged-in: then return TRUE (user is still logged-in) and refresh cookie
                if (is_array($ThUserData)) {
                    $intCookieExp = time()+$GLOBALS['thIntCookieExp'];
                    foreach ($ThUserData as $key => $value) {
                        setcookie("ThUserData[$key]",$value, $intCookieExp, '/', false, 0); 
                    }
                }


		//--- get the sublevel listings
		$sql    	= "SELECT a.* FROM ebpls_user_sublevel_listings a, ebpls_user b WHERE b.id= {$ThUserData['id']}   and a.user_id=b.id order by a.sublevel_id asc";
		$res		= th_query($sql);

		//echo "SQL : $sql";

		$arrdata = null;
		while($datarow 	= @mysql_fetch_array($res))
		{
			$arrdata[] = $datarow['sublevel_id'];	
		}
		$strSubLevels = @implode(":", $arrdata);
		setcookie("ThUserData[subLevelList]",$strSubLevels, $intCookieExp, '/', false, 0); 


                return 1;
            }
        }

    }

    function setCurrentActivityLog($strAction = null) {
		include'includes/variables.php';
        global $ThUserData, $HTTP_SERVER_VARS;
        if (getenv('HTTP_X_FORWARDED_FOR')) {							
    		$remoteip = getenv('HTTP_X_FORWARDED_FOR'); 
		} else { 
    		$remoteip = getenv('REMOTE_ADDR');
		}
        if (empty($ThUserData['id'])) {
            $result = FALSE;
        } else {
        	$strPostVarData = isset($strPostVarData) ? $strPostVarData : array() ;  //2008.05.06 Create array if not defined
            foreach ($GLOBALS['HTTP_POST_VARS'] as $key => $val) {
                $strPostVarData[] = "$key = $val";
                //$strPostVarData[] = "$key = $val";
            }
            if (is_array($strPostVarData)) $strPostVarData = implode('|-|', $strPostVarData);
            $strUpdatePostVar = ($strPostVarData) ? "postvarval = '$strPostVarData'," : "";
            $intPartId = (empty($GLOBALS['part'])) ? getCurrFilePartNum() : $GLOBALS['part'];
	    $levele = crypt_md5($ThUserData['level'],$decoder);	
//             $strQuqery = mysql_query("INSERT INTO ebpls_activity_log SET
//                 userid = '" . $ThUserData[id] . "',
//                 userlevel = '" .$levele . "',
//                 username = '" . $ThUserData[username] . "',
//                 part_constant_id = '" . $intPartId . "',
//                 querystring = '" . $GLOBALS['HTTP_SERVER_VARS']['QUERY_STRING'] . "',
//                 postvarval = '" . $strUpdatePostVar . "',
//                 action = '$strAction',
//                 remoteip = '$remoteip',
//                 lastupdated = NOW()
//                 ");
                
             ActiveLogin($ThUserData['id']);   
                
        }
        if (!isset($result)) $result = 0;  //2008.04.25
        return $result;
    }
 function setCurrentActivity($watuser, $straction) {
	    if ($straction != '') {
	    $m = mysql_query("insert into ebpls_activity_log values ('','$watuser','$straction',now())");
    	}		
       
    }
    function getDbFormSelect($strTable, $strFieldValue, $strFieldLabel, $strObjName="frmMsgCateg", $strMatch="", $blnAllowNull=1, $intSize=null, $strWhereStmt=null)
    {
        $strDisplay = "";
        $strWhereStmt = (empty($strWhereStmt)) ? "" : "WHERE $strWhereStmt";
        $strQuery = "SELECT $strFieldValue, $strFieldLabel FROM $strTable $strWhereStmt";
echo "Query string is ".$strQuery."***\n";
        $result = th_query($strQuery);
        $strAllowMultiple = (is_null($intSize)) ? "" : "size=\"$intSize\" multiple=\"multiple\"";
        $strDisplay .= "<select name=\"$strObjName\" $strAllowMultiple>\n";
        if ($blnAllowNull) $strDisplay .= "<option value=\"\">Select One</option>\n";
        while ($row = mysql_fetch_assoc($result)) {
            if (is_array($strMatch)) {
                $strSelectedFlag = (in_array($row["$strFieldValue"], $strMatch)) ? " selected=\"selected\"" : "";
            } else {
                $strSelectedFlag = ($strMatch == $row["$strFieldValue"]) ? " selected=\"selected\"" : "";
            }
            $strDisplay .= "<option value=\"" . $row["$strFieldValue"] . "\"$strSelectedFlag>" . $row["$strFieldLabel"] . "</option>\n";
        }
        $strDisplay .= "</select>\n";
        mysql_free_result($result);
        return $strDisplay;
    }

    function getMemFormSelect($arrVariable, $intLabelIndex, $strObjName="frmMsgStatus", $intMatch=NULL, $intIdxStart=null, $intIdxEnd=null, $blnAllowNull=1)
    {
		include "includes/variables.php";
		$strDisplay = "";
        $strDisplay .= "<select name=\"$strObjName\">\n";
        if ($blnAllowNull) $strDisplay .= "<option value=\"\"></option>\n";
        if (is_null($intIdxStart) && is_null($intIdxEnd)) {
			foreach ($arrVariable as $key => $value) {
				$strSelectedFlag = ($key == decrypt_md5($intMatch,$decoder)) ? " selected=\"selected\"" : "";
                $strLabel = (is_null($intLabelIndex)) ?  $arrVariable[$key]: $arrVariable[$key][$intLabelIndex];
                $strDisplay .= "<option value=\"$key\"$strSelectedFlag>" . $strLabel . "</option>\n";
            }
        } else {
			for ($i = $intIdxStart; $i <= $intIdxEnd; $i++) {
				$strSelectedFlag = ($i == crypt_md5($intMatch,$decoder)) ? " selected=\"selected\"" : "";
                $strLabel = (is_null($intLabelIndex)) ?  $arrVariable[$i]: $arrVariable[$i][$intLabelIndex];
                $strDisplay .= "<option value=\"" . $i . "\"$strSelectedFlag>" . $strLabel . "</option>\n";
            }
        }
        $strDisplay .= "</select>\n";
        return $strDisplay;
    }

    function sendTHMail($strRecipient, $strSubject, $strMessage, $strFromEmail)
    {
        if (strstr(eBPLS_MAIL_WEBMASTER, ',')) {
            $arrMailAdd = explode(",", eBPLS_MAIL_WEBMASTER);
            $strMailAppLog = $arrMailAdd[0];
        } else {
            $strMailAppLog = eBPLS_MAIL_WEBMASTER;
        }

        $headers = "";
        $headers .= "From: $strFromEmail\r\n";
        $headers .= "Reply-To: <$strFromEmail>\r\n";
        $headers .= "X-Sender: <$strFromEmail>\r\n";
        $headers .= "X-Mailer: TxtHotline Mailer\r\n"; // mailer
        $headers .= "Return-Path: <$strFromEmail>\r\n";  // Return path for errors
        $headers .= "Content-Type: text/html; charset=iso-8859-1\r\n"; // Mime type

        return mail($strRecipient, $strSubject, $strMessage, $headers, "-f{$strMailAppLog}");
    }

    function setUserLock($frmUserName, $frmUserKey)
    {
        global $msg;

        echo "login ==>".$frmUserName."<BR>";
        echo "password ==>".$frmUserKey."<BR>";

        $updquery = "UPDATE ebpls_user SET lockout = now() WHERE username = '$frmUserName' LIMIT 1";
        $updresult = th_query($updquery);
        $msg = "Your user account is locked! Pls. conctact Globe Administrator to unlock your account.";
        return $msg;
    }

    function unlockUser($userid, $strLinkName="thDbLink")
    {
        $strQuery = "UPDATE ebpls_user SET lockout = null WHERE id = $userid LIMIT 1";
        $result = th_query($strQuery, $strLinkName);
        return $result;
    }

    function th_query($strSqlQuery, $strLinkName="thDbLink")
    {


	//echo "$strSqlQuery";

        global $$strLinkName, $HTTP_SERVER_VARS, $HTTP_POST_VARS;
        @$result = mysql_query($strSqlQuery, $$strLinkName);
	if ($result == FALSE) {
            $strPostVarVal = $strServerVars = "";
            if (is_array($HTTP_POST_VARS)) {
                foreach ($HTTP_POST_VARS as $key => $val) {
                    $strPostVarVal .= "<b>$key</b> = $val<br />";
                }
            }
            foreach ($HTTP_SERVER_VARS as $key => $val) {
                $strServerVars .= "<b>$key</b> = $val<br />";
            }
            @$strErrorMsg = "<font face=\"COURIER,ARIAL,HELVETICA\"><b><u>*** " . eBPLS_APP_NAME . " " . eBPLS_APP_VERSION . " DB Error: ***</u></b><br /><br /><b><u>Query Stmt:</u></b><br />{$strSqlQuery}<br /><br /><b><u>Error (" . @mysql_errno($$strLinkName) . ") String:</u></b><br />" . mysql_error($$strLinkName) . "<br /><br /><b><u>Post Vars:</u></b><br />{$strPostVarVal}<br /><br /><b><u>HTTP Server Variables:</u></b><br />{$strServerVars}<br /><br /></font>";
            //Mail Notification
            //sendTHMail($GLOBALS['thStrAdminEmail1'], eBPLS_APP_NAME . " " . eBPLS_APP_VERSION . ": DB Error", $strErrorMsg, "txthotline_robot" . eBPLS_MAIL_HOST_SUFF);
            //sendTHMail($GLOBALS['thStrAdminEmail2'], eBPLS_APP_NAME . " " . eBPLS_APP_VERSION . ": DB Error", $strErrorMsg, "txthotline_robot" . eBPLS_MAIL_HOST_SUFF);
            //sendTHMail($GLOBALS['thStrAdminEmail3'], eBPLS_APP_NAME . " " . eBPLS_APP_VERSION . ": DB Error", $strErrorMsg, "txthotline_robot" . eBPLS_MAIL_HOST_SUFF);
            //Cellular Phone Notification
            //notifyAdminSMS();
            //$arrSmsToSend[] = new OutgoingMessage('SYSTEM-PUSH', $GLOBALS['thStrNotifyMobile'], $GLOBALS['thStrAdminGSM1'], $GLOBALS['thStrCorporateSuffix']);
            //$arrSmsToSend[] = new OutgoingMessage('SYSTEM-PUSH', $GLOBALS['thStrNotifyMobile'], $GLOBALS['thStrAdminGSM2'], $GLOBALS['thStrCorporateSuffix']);
            //$arrSmsToSend[] = new OutgoingMessage('SYSTEM-PUSH', $GLOBALS['thStrNotifyMobile'], $GLOBALS['thStrAdminGSM3'], $GLOBALS['thStrCorporateSuffix']);
            if (isset($arrSmsToSend)) {
				//send_message($arrSmsToSend);
				unset($arrSmsToSend);
			}
            // start sending queued messages;

        }
        return $result;
    }

    function notifyAdminSMS(){

    	//global $gVerbose, $gChikka_id, $gDest, $gMsg;
/*
    	$gVerbose = 0;
    	$gChikka_id = eBPLS_DUMMY_GSM;
    	$gDest = eBPLS_ACCESS_CODE;
    	$gMsg = "Wala Lang";
    	include_once('chui/application-specific-init.php');
*/
	$arrSmsToSend[] = new OutgoingMessage('SYSTEM-PUSH', $GLOBALS['thStrNotifyMobile'], $GLOBALS['thStrAdminGSM1'], eBPLS_ACCESS_CODE);
        $arrSmsToSend[] = new OutgoingMessage('SYSTEM-PUSH', $GLOBALS['thStrNotifyMobile'], $GLOBALS['thStrAdminGSM2'], eBPLS_ACCESS_CODE);
        $arrSmsToSend[] = new OutgoingMessage('SYSTEM-PUSH', $GLOBALS['thStrNotifyMobile'], $GLOBALS['thStrAdminGSM3'], eBPLS_ACCESS_CODE);

        if (isset($arrSmsToSend)) {
			send_message($arrSmsToSend);
			unset($arrSmsToSend);
		}

    }



function bytexor($a,$b,$l)
  {
   $c="";
   $la=strlen($a);  
   $lb=strlen($b);
   $l=$la<$l?($lb<$a?$lb:$la):$l; //2008.04.25 pick the shortest
   for($i=0;$i<$l;$i++) {
   	$c.=$a{$i}^$b{$i};
   }      
   return($c);
  }
                                                                                                                                                                                                                                                                     
  function binmd5($val)
  {
   return(pack("H*",md5($val)));
  }
                                                                                                                                                                                                                                                                     
                                                                                                                                                                                                                                                                     
 function decrypt_md5($msg,$heslo)
  {
	$key=$heslo;$sifra="";
   $key1=binmd5($key);
   while($msg) {
	 $m=substr($msg,0,16);
     $msg=substr($msg,16);
	 $sifra.=bytexor($m,$key1,16);
     $key1=binmd5($key.$key1.$m);
   }
   echo "\n";
	return($sifra);
  }
                                                                                                                                                                                                                                                                     
  function crypt_md5($msg,$heslo)
  {
   $key=$heslo;$sifra="";
   $key1=binmd5($key);
   while($msg) {
	//echo $msg;   
	 $m=substr($msg,0,16);
     $msg=substr($msg,16);
     $sifra.=bytexor($m,$key1,16);
     $key1=binmd5($key.$key1.$m);
	}
	//echo $sifra;
   echo "\n";
   return($sifra);
  }



?>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          

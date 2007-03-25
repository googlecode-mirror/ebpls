<!-- eBPLS_PAGE_SETTING_UPDATE -->
<?php
require_once "includes/config.php";
require_once "setup/" . $ThUserData[domain] . "/setting.php";
require_once "lib/updateSetting.lib.php";
require_once "lib/ebpls.lib.php";
require_once("lib/ebpls.utils.php");
include("includes/variables.php");
dbConnect(1);

function getCodes(){
	$queryChk = "SELECT * FROM ebpls_codes_table WHERE id = 1";
	$resultChk = th_query($queryChk);
/*	
	if(mysql_num_rows($resultChk) > 0){
		$row = mysql_fetch_array($resultChk);
	}
*/
	return $row;
}

function convertHrsToSec($cookieSet){
	
	global $cookieSetHr;
	global $cookieSetSec;
	
	echo "cookieSetHr Received==>".$cookieSetHr."<BR>"; 
	$cookieSetHr = intval($cookieSet);
	$cookieSetSec = ($cookieSetHr * 60);
	return $cookieSetSec;
}

function convertSecToHrs($thIntCookieExp){
	
	global $cookieSetSec;
	global $cookieSetHr;
	
	//echo "cookieSetHr Received==>".$cookieSetHr."<BR>"; 
	$cookieSetSec = $thIntCookieExp;
	$cookieSetHr = ($cookieSetSec / 60);
	return $cookieSetHr;
}

$arrCodes = getCodes();
//print_r($arrCodes);

if($frmSubmitPref=="Submit"){
	$priColor = "#6A6A6A";
	$secColor = "#6FCDF9";
	$shade1Color = "#FFFFFF";
	$shade2Color = "#FFFFFF";
	
	$cookieSet = convertHrsToSec($cookieSet);
	$arrKeys = array(
		'thIntPassLen',
		'thIntPassRetLimit',
		'thIntCookieExp',
		'thIntPageLimit'
		);
	$arrValues = array(
		$passLen,
		$passRetLimit,
		$cookieSet,
		$pageLimit
		);	

	
	$strSetupDir = (empty($ThUserData[domain])) ? "" : $ThUserData[domain] . '/';
	
	updateConfig($arrKeys,$arrValues,"setup/" . $strSetupDir . "setting.php");
	//echo "<== REACHED THIS PLACE ==><BR>";
	//exit();
	$frmSubmitPref="";
	header("Location: " . $HTTP_SERVER_VARS['PHP_SELF'].'?part=4&class_type=Settings&itemID_=6&busItem=Settings&permit_type=Settings&settings_type=Syssettings&item_id=Settings&v=1');
}



//--- chk the sublevels
/*if(   ! is_valid_sublevels(167))
 {
 	setUrlRedirect('index.php?part=999');
	
 } 
*/
?>

<script language="JavaScript" src="includes/AnchorPosition.js"></script>
	<script language="JavaScript" src="includes/PopupWindow.js"></script>
	<script language="JavaScript" src="includes/ColorPicker2.js"></script>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	var cp = new ColorPicker('window');
	var cp2 = new ColorPicker();
	// Runs when a color is clicked
	function pickColor(color) {
		field.value = color;
		}
	
	var field;
	function pick1(anchorname) {
		field = document.prefSetForm.priColor;
		//alert("DOCUMENT = "+ document.forms[0].color;);
		//alert("FIELD = "+ field);
		cp.show(anchorname);
		}
	function pick2(anchorname) {
		field = document.prefSetForm.secColor;
		cp.show(anchorname);
		}
	function pick3(anchorname) {
		field = document.prefSetForm.shade1Color;
		cp.show(anchorname);
		}
	function pick4(anchorname) {
		field = document.prefSetForm.shade2Color;
		cp.show(anchorname);
		}
	//-->
	</SCRIPT>
<center>
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
  	 <!-- form begins here-->
    <form name="prefSetForm" method="POST" action="?part=4&itemID_=6&busItem=Settings&permit_type=Settings&settings_type=Syssettings&item_id=Settings">
	<tr><td colspan=2 class=header align=center width=100%>SETTINGS</td></tr>
    
      <?php
      if ($GLOBALS['watbrowser']=='msie') {
$getun = mysql_query("select * from ebpls_user where id = '$ThUserData[id]'");
            $geth = mysql_fetch_assoc($getun);
            $slevele = crypt_md5($geth[level],$decoder);
       		
		}

      	//echo "intUserLevel ==>".$intUserLevel."<BR>";
    	if (($GLOBALS['intUserLevel'] == eBPLS_USER_ADMIN)||($GLOBALS['intUserLevel'] == eBPLS_ROOT_ADMIN)) {
      ?>
      <!--
      |&nbsp;<A HREF="javascript:popitup4('<?php echo getFilename(eBPLS_PAGE_REL_PENDQUEUE);?>')"><b>View Message Queue</b></A>&nbsp;
      |&nbsp;<A HREF="<?php echo getURI(eBPLS_PAGE_MSGSTAT_LIST);?>"><b>Customer Support Groups</b></A>&nbsp;
      |&nbsp;<A HREF="<?php echo getURI(eBPLS_PAGE_CATEG_LIST);?>"><b>Category Manager</b></A>&nbsp;|
        <br>
      
      |&nbsp;<A HREF="<?php echo getURI(eBPLS_PAGE_ALLOWED_IP_LIST);?>"><b>Allowed Admin IP List</b></A>&nbsp;| 
      //--> 
      <?php
    	}
      ?>
        
    <tr> 
      <td colspan="2" align="CENTER" class="header2">System Settings</td>
    </tr>
    <?php
    if (decrypt_md5($GLOBALS['intUserLevel'],$decoder) >= eBPLS_USER_ADMIN || $slevele>=eBPLS_USER_ADMIN) {
    ?>
    <tr> 
      <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="165"><b>Password Length:</b> </td>
      <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="341">
	  	<input type="text" name="passLen" size="10" value="<?php echo $thIntPassLen ?>">&nbsp;<font class="def_label"> (1-20)</font>
	  </td>
	</tr>
	<tr> 
      <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="165"><b>Password Retry Limit:</b> </td>
      <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="341">
	  	<input type="text" name="passRetLimit" size="10" value="<?php echo $thIntPassRetLimit ?>">&nbsp;<font class="def_label"> (1-9)</font>
	  </td>
	</tr>
		
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Cookie Duration (Minutes):</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="cookieSet" size="10" value="<?php echo convertSecToHrs($thIntCookieExp); ?>">&nbsp;<font class="def_label"> (1-86400)</font>
	  </td>
    </tr>
	  
	 <tr>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Items Per Page:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="pageLimit" size="10" value="<?php echo $thIntPageLimit ?>">&nbsp;<font class="def_label"> (1-100)</font>
	  </td> 
    </tr>
    <!--
    <tr>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="226"> 
          <b>Municipality:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="410"> 
          <input type="text" name="adminEmail1" size="25" maxlength="150" value="<?php echo "$thStrAdminEmail"; ?>">
      </tr>
    <tr>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="226"> 
          <b>Office:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="410"> 
          <input type="text" name="adminGSM" size="25" maxlength="150" value="<?php echo "$thStrAdminGSM"; ?>">
        
    </tr>
    //-->
    <!--
    <tr> 
      <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" colspan="2">
	  <input type=radio name=relOptButton value="3" <?php if($isSelected=='3'){echo "checked";}elseif($isSelected=='2'){echo "";}elseif($isSelected=='1'){echo "";}else{echo "disabled";}?> style="color : black">&nbsp; Assign to a specific officer (online or offline)
	  	<?php echo getDbFormSelect('th_user', 'id', 'username', 'userOnOff', $thIntRelationshipUser,0);?>
      </td>
    </tr>
    //-->
<!--    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>CTC Code (Individual):</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strCtcCode" size="20" maxlength="20" value="<? echo $arrCodes[ctc_ind_code] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>CTC Code (Business):</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strCtcCode" size="20" maxlength="20" value="<? echo $arrCodes[ctc_bus_code] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Application Code:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strApplicationCode" size="20" maxlength="20" value="<? echo $arrCodes[app_code] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Assessment Code:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strAssessmentCode" size="20" maxlength="20" value="<? echo $arrCodes[ass_code] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Payment Code:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strPaymentCode" size="20" maxlength="20" value="<? echo $arrCodes[pay_code] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Official Receipt No.:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strOrNoCode" size="20" maxlength="20" value="<? echo $arrCodes[or_no] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Approval Code:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strApprovalCode" size="20" maxlength="20" value="<? echo $arrCodes[apr_code] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Release Code:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strReleaseCode" size="20" maxlength="20" value="<? echo $arrCodes[rel_code] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Business Permit No.:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strBusPermitNo" size="20" maxlength="20" value="<? echo $arrCodes[bpermit_no] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Motorized Permit No.:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strMotPermitNo" size="20" maxlength="20" value="<? echo $arrCodes[mpermit_no] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Occupational Permit No.:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strOccPermitNo" size="20" maxlength="20" value="<? echo $arrCodes[opermit_no] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Peddlers Permit No.:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strPedPermitNo" size="20" maxlength="20" value="<? echo $arrCodes[ppermit_no] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Franchise Permit No.:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strFraPermitNo" size="20" maxlength="20" value="<? echo $arrCodes[fpermit_no] ?>">
	  </td>
    </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Fishery Permit No.:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="strFisPermitNo" size="20" maxlength="20" value="<? echo $arrCodes[fishery_permit_no] ?>">
	  </td>
    </tr>-->
    <!-- 
    <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Primary Theme Colour:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="priColor" size="10" maxlength="7" value="<?php echo $thThemeColor1 ?>">&nbsp;<A HREF="#" onClick="pick1('pick');return false;" NAME="pick" ID="pick"> Change Color </A>

	   </td>
	 </tr>
    <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Secondary Theme Colour:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="secColor" size="10" maxlength="7" value="<?php echo $thThemeColor2 ?>">&nbsp;<A HREF="#" onClick="pick2('pick');return false;" NAME="pick" ID="pick"> Change Color </A>
	   </td>
	 </tr>
	 <tr> 
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="224"> 
          <b>Theme Shade 1 Colour:</b> </td>
        <td bgcolor=<?php echo $thThemeColor3 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="shade1Color" size="10" maxlength="7" value="<?php echo $thThemeColor3 ?>">&nbsp;<A HREF="#" onClick="pick3('pick');return false;" NAME="pick" ID="pick"> Change Color </A>
	   </td>
	 </tr>
	 <tr> 
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="224"> 
          <b>Theme Shade 2 Colour:</b> </td>
        <td bgcolor=<?php echo $thThemeColor4 ?> align="LEFT" class="thText" width="282"> 
          <input type="text" name="shade2Color" size="10" maxlength="7" value="<?php echo $thThemeColor4 ?>">&nbsp;<A HREF="#" onClick="pick4('pick');return false;" NAME="pick" ID="pick"> Change Color </A>
	   </td>
	 </tr>
	 -->
	 <?php
	 }
	 ?> 
      <tr> 
        <td colspan="2" align="RIGHT" > 
          &nbsp;<br>
        </td>
      </tr>
      
      <tr> 
        <td colspan="2" align="CENTER" > 
          <br>
	  <input type=hidden name="frmSubmitPref">
          <input type="button" name="frmSubmit" value="Save" onclick='VerifySet();'>
          &nbsp; &nbsp; 
		 <input type="BUTTON" name="frmCancel" value="Cancel" onClick="javascript: prefSetForm.frmSubmitPref.value = ''; prefSetForm.submit();">
          <br>
        </td>
      </tr>
    </form>
    <!-- form ends here-->
  </table>
  <SCRIPT LANGUAGE="JavaScript">
   cp.writeDiv()

  </SCRIPT>
</center>

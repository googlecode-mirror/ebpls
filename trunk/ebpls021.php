<?php

//require_once "./includes/eBPLS_header.php";
require_once "includes/config.php";
require_once "setup/" . $ThUserData[domain] . "/setting.php";
require_once "lib/updateSetting.lib.php";
require_once "lib/ebpls.lib.php";
require_once("lib/ebpls.utils.php");


if($frmSubmitPref=="Save"){
	$arrKeys = array(
		'thProvince',
		'thMunicipality',
		'thOffice',
		'thSignatory1',
		'thSignatory2',
		'thSignatory3'
		);
	$arrValues = array(
		'"'.$setProvince.'"',
		'"'.$setMunicipality.'"',
		'"'.$setOffice.'"',
		'"'.$setSignatory1.'"',
		'"'.$setSignatory2.'"',
		'"'.$setSignatory3.'"'
		);	
			
	$strSetupDir = (empty($ThUserData[domain])) ? "" : $ThUserData[domain] . '/';
	
	updateConfig($arrKeys,$arrValues,"setup/" . $strSetupDir . "setting.php");
	echo "<== REACHED THIS PLACE ==><BR>";
	//exit();
	$frmSubmitPref="";
	header("Location: " . $HTTP_SERVER_VARS['PHP_SELF'].'?part='.eBPLS_UPLOAD_LOGO);
}

//--- chk the sublevels
/*if(   ! is_valid_sublevels(171))
{
 	setUrlRedirect('index.php?part=999');
	
} 
*/

echo "<br><br>";
echo "<center>";
//echo "<form enctype='multipart/form-data' action='uploading.php' method='post'>";
echo "<form enctype='multipart/form-data' action='".getURI(eBPLS_UPLOAD_LOGO_PROCESS)."' method='post'>";
echo "<table width=\"513\" border=\"0=\" cellspacing=\"1\" cellpadding=\"1\">";
echo "<input type='hidden' name='MAX_FILE_SIZE' value='120000' />";
echo "<font face=\"ARIAL\" size=\"2\" color=\"#000000\"><b>LGU Logo upload file:</b></font> <input name='userfile' type='file' />";
echo "<tr><td colspan=\"2\" align=\"center\"><input type='submit' value='Upload Logo' /></td></tr>";
echo "</form>";
echo "<form name=\"prefSetForm\" method=\"POST\" action='".getURI(eBPLS_UPLOAD_LOGO)."'>";
echo "<tr>";
echo "<td bgcolor=".$thThemeColor3." align=\"LEFT\" class=\"thText\" width=\"226\">"; 
echo "<b>Province:</b> </td>";
echo "<td bgcolor=".$thThemeColor3." align=\"LEFT\" class=\"thText\" width=\"410\">"; 
echo "<input type=\"text\" name=\"setProvince\" size=\"25\" maxlength=\"150\" value=\"".$thProvince."\">";
echo "</tr>";
echo "<tr>";
echo "<td bgcolor=".$thThemeColor3." align=\"LEFT\" class=\"thText\" width=\"226\">"; 
echo "<b>Municipality:</b> </td>";
echo "<td bgcolor=".$thThemeColor3." align=\"LEFT\" class=\"thText\" width=\"410\">"; 
echo "<input type=\"text\" name=\"setMunicipality\" size=\"25\" maxlength=\"150\" value=\"".$thMunicipality."\">";
echo "</tr>";
echo "<tr>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"226\">"; 
echo "<b>Office:</b> </td>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"410\">"; 
echo "<input type=\"text\" name=\"setOffice\" size=\"25\" maxlength=\"150\" value=\"".$thOffice."\">";
echo "</tr>";
echo "<tr>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"226\">"; 
echo "<b>Signatory1:</b> </td>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"410\">"; 
echo "<input type=\"text\" name=\"setSignatory1\" size=\"25\" maxlength=\"150\" value=\"".$thSignatory1."\">";
echo "</tr>";
echo "<tr>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"226\">"; 
echo "<b>Signatory2:</b> </td>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"410\">"; 
echo "<input type=\"text\" name=\"setSignatory2\" size=\"25\" maxlength=\"150\" value=\"".$thSignatory2."\">";
echo "</tr>";
echo "<tr>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"226\">"; 
echo "<b>Signatory3:</b> </td>";
echo "<td bgcolor=".$thThemeColor4." align=\"LEFT\" class=\"thText\" width=\"410\">"; 
echo "<input type=\"text\" name=\"setSignatory3\" size=\"25\" maxlength=\"150\" value=\"".$thSignatory3."\">";
echo "</tr>";
echo "<tr>"; 
echo "<td colspan=\"2\" bgcolor=".$thThemeColor4." align=\"CENTER\" class=\"thTableHeader\">"; 
echo "<br>";
echo "<input type=\"SUBMIT\" name=\"frmSubmitPref\" value=\"Save\">";
echo "<br>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";
echo "</center>";

//require_once "./includes/eBPLS_footer.php";
?> 

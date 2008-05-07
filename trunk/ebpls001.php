<?php
//	eBPLS_PAGE_LOGIN: Login Page
/*
Modification History:
2008.04.25: Correct strings NOT constants for getenv function
*/
if (getenv('HTTP_X_FORWARDED_FOR')) {							
    $remoteip = getenv('HTTP_X_FORWARDED_FOR'); 
} else { 
    $remoteip = getenv('REMOTE_ADDR');
}	
//echo $remoteip;
?>
<br>
<div align="CENTER">
<table WIDTH=788 border="0" cellspacing="0" cellpadding="0">
<?php
	if (isset($errlog)) {
		$strErrMessage = "";
		switch ($errlog) {
			case -1:
				$strErrMessage = "User account is locked! Contact your system administrator.";
				break;
			case 0:
				$strErrMessage = "Invalid username or password!";
				break;
			case 1:
				$strErrMessage = "Login Successful";
				break;
			case 2:
				$strErrMessage = "User account in use.";
				break;
			case 3:
				$strErrMessage = "Access Denied: You are trying to access a eBPLS Admin Account<br> outside the defined domains!";
				break;
				
			default:
				$strErrMessage = "Illegal Operation! Please contact the author of this program.";
				break;
		}
		echo("\t<tr>\n");
		echo("\t\t<td colspan=\"3\" align=\"CENTER\" valign=\"MIDDLE\" class=\"thFieldTitle\">\n");
		echo("\t\t\t<font color=\"RED\">$strErrMessage</font><br>\n");
		echo("\t\t</td>\n");
		echo("\t</tr>\n");
	}
?>
	<script language="javascript">
		function clear_browse()
		{
			var _FRM = document._FRM;
			_FRM.frmUserName.value = "";
			_FRM.frmUserKey.value = "";
		}
	</script>
	<form method="POST" name='_FRM' action='index.php?part=4'>
	<body onload='_FRM.frmUserName.focus();'></body>
	<TR>
		<TD>
			<IMG SRC="images/eBPLSLoginPage_01.gif" WIDTH=270 HEIGHT=224 ALT=""></TD>
		<TD> <img src="images/eBPLSLoginPage_02.gif" width=243 height=224 alt=""></TD>
		<TD>
			<IMG SRC="images/eBPLSLoginPage_03.gif" WIDTH=275 HEIGHT=224 ALT=""></TD>
	</TR>
	<TR>
		<TD height="144"> <IMG SRC="images/eBPLSLoginPage_04.gif" WIDTH=270 HEIGHT=144 ALT=""></TD>
		<TD> <table align="center" width="243" height="142" cellpadding="0" cellspacing="0">
            <tr> 
              <td align="RIGHT" valign="MIDDLE" class="thFieldTitle"> User Name:<br> 
              </td>
              <td align="LEFT" valign="MIDDLE" class="thFieldTitle"> <input type="text" name="frmUserName" value="" size="10"> 
                &nbsp; &nbsp; &nbsp; &nbsp; </td>
            </tr>
            <tr> 
              <td align="RIGHT" valign="MIDDLE" class="thFieldTitle"> Password:<br> 
              </td>
              <td align="LEFT" valign="MIDDLE" class="thFieldTitle"> <input type="password" name="frmUserKey" value="" size="10"> 
                &nbsp; &nbsp; &nbsp; &nbsp; </td>
            </tr>
            <tr> 
              <td colspan="2" class="thFieldTitle"> <input type="submit" name="frmLoginSubmit" value="    Log In   "> 
                &nbsp;&nbsp;&nbsp;&nbsp; <input type="reset" id="frmBtnCancel" onclick='_FRM.frmUserName.focus();'name="frmBtnCancel" value="   Cancel   " onClick="javascript: clear_browse();"> 
                <br> </td>
            </tr>
          </table></TD>
		<TD> 
			<IMG SRC="images/eBPLSLoginPage_06.gif" WIDTH=275 HEIGHT=144 ALT=""></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/eBPLSLoginPage_07.gif" WIDTH=270 HEIGHT=207 ALT=""></TD>
		<TD>
			<IMG SRC="images/eBPLSLoginPage_08.gif" WIDTH=243 HEIGHT=207 ALT=""></TD>
		<TD>
			<IMG SRC="images/eBPLSLoginPage_09.gif" WIDTH=275 HEIGHT=207 ALT=""></TD>
	</TR>
	
	

   </form>
</table>
</div>

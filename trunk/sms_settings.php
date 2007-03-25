<?php
//      Description:sms_settings.php - one file that sets sms message setting
//      author: Vnyz Sofhia Ice
//      Trademark: [V[f]X]S!73n+_K!77er
//      Last Updated: Feb 01, 2005 DAP
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//--- get connection from DB
//$dbLink = get_db_connection();
global $ThUserData;

require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

if ($msgid=='') {
	$msgid=0;
}


if ($savebt=='SAVE') {
	
	if ($keyword<>null and $message<>null) {
		$keyword = strtolower(addslashes($keyword));
		$message = trim(addslashes($message)); 
		if ($com=='editsms') {

			$result = UpdateQuery($dbtype,$dbLink,"sms_message",
					"keyword='$keyword', full_message='$message',
					dateupdated=now(),updateby='$usern'","msgid=$msgid");
		} else {
							
			$result = InsertQuery($dbtype,$dbLink,"sms_message","", 
					"'','$keyword','$message',now(),'$usern'");
		}
		$msgid=0;
		$keyword='';
		$message='';
	}
}


if ($com=='deletesms') {
	$result = DeleteQuery($dbtype,$dbLink,"sms_message","msgid=$msgid");
	$msgid=0;
} elseif ($com=='editsms') {
	$getmsg = SelectDataWhere($dbtype,$dbLink,"sms_message","where msgid=$msgid");
	$getmsg = mysql_fetch_row($getmsg);
}


?>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function VerifyIt(x,y)
{
        var _FRM = document._FRM;
                var msgTitle = "SMS Settings\n";
                if(x.value=='')
                {
                        alert( msgTitle + "Please input a keyword!");
                        _FRM.x.focus();
                        return false;
                } elseif (y.value=='')
		{
			alert( msgTitle + "Please input a message!");
                        _FRM.x.focus();
                        return false;
                }
}
</script>

<title>Short Messaging Service Settings</title>
        <form name="_FRM" method="POST"  action ="">
        <table border=1 align=center width=300<br><br>
        <tr>
        <td align=right>Keyword:</td>
        <td><input type=hidden name=msgid value=<?php echo $getmsg[0]; ?>>
	    <input type=text name=keyword value=<?php echo $getmsg[1]; ?>>
	    <input type=submit name=submitbt value='Search' size=6>	
	</td>	
        </tr>
	<tr></tr>
        <tr>
        <td align=right>Message:</td>
        <td>
        <textarea NAME=message ROWS=5 COLS=19><?php echo $getmsg[2]; ?></textarea>
        </td>
        </tr>
        </table>

<table border=1 align=center width=600<br><br>
<tr>
<td align=center>Keyword</td><td align=center>Message</td><td></td>
</tr>
	<?php
	
		if ($submitbt=='Search') {
		        $sms = SelectDataWhere($dbtype,$dbLink,"sms_message", 
				"where keyword like '$keyword%'");
		} else {
			$sms = SelectDataWhere($dbtype,$dbLink,"sms_message","");
		}
		while ($msg=FetchRow($dbtype,$sms))
			{
		$msg[1]=stripslashes($msg[1]);
		$msg[2]=stripslashes($msg[2]);
	?>
		<tr>
		        <td><input type=hidden name=msgid value=<?php echo $msg[0]; ?>>
		            <?php echo $msg[1]; ?>
			</td>
			<td>
			    <?php echo $msg[2]; ?>
		        </td>
			<td>
                            <a href='sms_settings.php?com=editsms&msgid=<?php echo $msg[0]; ?>'>
				<font color=blue>Edit</font></a>
			    <a href='sms_settings.php?com=deletesms&msgid=<?php echo $msg[0]; ?>'>
                                <font color=blue>Delete</font></a>	
			    
                        </td>

		        </tr>
	<?php
			}
	?>


<table border=0 align=center><br>
<tr><td>
<input type=submit name=savebt value='SAVE' onClick='javascript:VerifyIt(keyword, message)'> &nbsp;
<input type=reset value=CLEAR>
</td></tr></table>
<br>





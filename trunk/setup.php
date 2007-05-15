<?php
$valid=$HTTP_POST_VARS["valid"];
$dbname=$HTTP_POST_VARS["dbname"];
$dbuser=$HTTP_POST_VARS["dbuser"];
$dbpass=$HTTP_POST_VARS["dbpass"];
$dbhost=$HTTP_POST_VARS["dbhost"];
$site=$HTTP_POST_VARS["site"];
//$webdir=$HTTP_POST_VARS["webdir"];
$insdir=$HTTP_POST_VARS["insdir"];
function updateConfig($arrVarNames='a000',$arrNewValues=0,$strConfigFile="setup/setting.php")
{
	if (count($arrVarNames) == count($arrNewValues)) {
		$arrConfigBuffer = NULL;
		// buffer the config file!
		$fp1 = fopen($strConfigFile,"rb");
		$arrNeedle = array(";","\r","\t","\n");
		while (!feof($fp1)) {
			$strBuffer = fgets($fp1, 4096);
			if (stristr($strBuffer,'$') && stristr($strBuffer,'=') && stristr($strBuffer,';')) {
				$strBuffer = str_replace($arrNeedle,"",$strBuffer);
				$arrTempBuffer = split('=',$strBuffer);
				$strVarName = str_replace("\$","",array_shift($arrTempBuffer));
				$strCurrValue = implode("=",$arrTempBuffer);
				$arrConfigBuffer[trim($strVarName)] = trim($strCurrValue);
			}
		}
		fclose($fp1);
		// update the values of the config buffer
		if (is_array($arrVarNames)) {
			foreach($arrVarNames as $strAssocName) {
				$arrConfigBuffer[$strAssocName] = array_shift($arrNewValues);
			}
		} else {
			$arrConfigBuffer[$arrVarNames] = $arrNewValues;
		}
		//echo "<== REACHED THIS PLACE ==><BR>";
		// re-write the file
		$fp2 = fopen($strConfigFile,"wb");
		fwrite($fp2,"<?php\r\n\r\n");
		foreach ($arrConfigBuffer as $key => $value) {
			if (stristr($value,' //')) {
				$value = str_replace(' //',';/* ',$value);
				$value .= ' */';
			} elseif ((stristr($value,'/*') && stristr($value,'*/'))) {
				$value = str_replace('/*',';/*',$value);
			} else {
				$value = $value.";";
			}
			fwrite($fp2,"\$$key = $value\r\n");
		}
		fwrite($fp2,"\r\n?>");
		fclose($fp2);
		return 1;
	} else {
		return FALSE;
	}
}
//update variables
function updateVars($arrVarNames='a000',$arrNewValues=0,$strConfigFile="includes/dbvar.php")
{
	if (count($arrVarNames) == count($arrNewValues)) {
		$arrConfigBuffer = NULL;
		// buffer the config file!
		$fp1 = fopen($strConfigFile,"rb");
		$arrNeedle = array(";","\r","\t","\n");
		while (!feof($fp1)) {
			$strBuffer = fgets($fp1, 4096);
			$arrTempBuffer = split('=',$strBuffer);
			$strVarName = array_shift($arrTempBuffer);
			$arrConfigBuffer[trim($strVarName)] = trim($strVarName);			
			echo $arrConfigBuffer[trim($strVarName)]."<br>";
		}
		fclose($fp1);
		// update the values of the config buffer
		if (is_array($arrVarNames)) {
			foreach($arrVarNames as $strAssocName) {
				$arrConfigBuffer[$strAssocName] = array_shift($arrNewValues);
			}
		} else {
			$arrConfigBuffer[$arrVarNames] = $arrNewValues;
		}
		//echo "<== REACHED THIS PLACE ==><BR>";
		// re-write the file
		$fp2 = fopen($strConfigFile,"wb");
		foreach ($arrConfigBuffer as $key => $value) {
			if (stristr($value,' //')) {
				$value = str_replace(' //',';/* ',$value);
				$value .= ' */';
			} elseif ((stristr($value,'/*') && stristr($value,'*/'))) {
				$value = str_replace('/*',';/*',$value);
			} else {
				$value = $value;
			}
			fwrite($fp2,"$value\r\n");
		}
		fclose($fp2);
		return 1;
	} else {
		return FALSE;
	}
}
function updateVarsite($arrNewValues=0, $strConfigFile="includes/varsite.php")
{
		$fp2 = fopen($strConfigFile,"wb");
		$value .= $arrNewValues;
		fwrite($fp2,"<?php\r\n");
		fwrite($fp2,"$value\r\n");
		fwrite($fp2,"?>\r\n");
		fclose($fp2);
		return 1;
}
////
   
if ($valid==1) {
	$r = @mysql_connect($dbhost,$dbuser,$dbpass);
	if ($r=='') {
		echo "Cannot connect to server using $dbuser@$dbhost";
	} else {
		$db = mysql_select_db($dbname);
		
		if ($db=='') {
			echo "Cannot connect to database $dbname, I will now create it for you<br>";
			
			$cdb = mysql_query("create database $dbname") or die(mysql_error());
		} 
		
			$db = mysql_select_db($dbname);
		
			
// 			$s1 = shell_exec("cd $webdir 2>&1 ");
// 			
// 				
// 			if ($s1<>'') {
// 				echo "Invalid web directory, no directory found moron";
// 			} else {
				echo "Installing now....... ";
				$s3 = shell_exec("tar -xzvf bpls.tar.gz 2>&1");
				
				//$s3 = shell_exec("mv bplsline $insdir 2>&1");
				//echo "<BR>$s3";
				$s3 = shell_exec("mysql -u$dbuser -p$dbpass $dbname<ebpls.sql");
				
				echo "Done....";
				$x=0;
				while ($x<300) {
					$ins = mysql_query("insert into ebpls_buss_taxfeeother 
						(taxfeeid) values ('')");
					$x++;
				}
				$delins = mysql_query("delete from ebpls_buss_taxfeeother");
				
				
				$ty = mysql_query("truncate ebpls_user");
				
				
				
				
				$ty = mysql_query("INSERT INTO ebpls_user (id, level, csgroup, username, password, lastname, firstname, designation, email, gsmnum, login, logout, lockout, currthreads, roundrobinflag, dateadded, lastupdated) VALUES (1, 'ã', NULL, 'ebpls', '°eæw', 'EBPLS', 'EBPLS', '', '', '', '2007-03-07 16:35:31', '2007-03-07 16:35:31', NULL, NULL, NULL, '2006-07-06 09:56:38', '2007-03-07 16:35:31')");
				$ty = mysql_query("INSERT INTO ebpls_user (id, level, csgroup, username, password, lastname, firstname, designation, email, gsmnum, login, logout, lockout, currthreads, roundrobinflag, dateadded, lastupdated) VALUES (2, 'ã', NULL, 'nccfoo', '»]vìkL', 'FOO', 'NCC', '', '', '', '2007-02-01 13:49:49', '2007-01-23 14:01:26', NULL, NULL, NULL, '2006-07-06 09:57:11', '2007-02-01 13:49:49')") or die (mysql_error());

			//}
		
		$arrKeys = array(
		'thDbUser',
		'thDbUKey',
		'thDbName',
		);
		$dbuser1 = "\"$dbuser\"";
		$dbpass1 = "\"$dbpass\"";
		$dbname1 = "\"$dbname\"";
	$arrValues = array(
		$dbuser1,
		$dbpass1,
		$dbname1,
		);	
updateConfig($arrKeys,$arrValues,"setup/setting.php");

		$arrKeys = array(
		"define(\"eBPLS_APP_URL\", \"http://192.168.1.104/bpls/\");	// do not include filenames"
		);
		$xsite = "define(\"eBPLS_APP_URL\", \"".$site."/\");	// do not include filenames";
	$arrValues = $xsite;	
updateVarsite($arrValues,"includes/varsite.php");
		$arrKeys = array(
		"dbuser",
		"dbpass",
		"dbname"
		);
		$dbuser1 = "'$dbuser'";
		$dbpass1 = "'$dbpass'";
		$dbname1 = "'$dbname'";
	$arrValues = array(
		$dbuser1,
		$dbpass1,
		$dbname1
		);	
updateConfig($arrKeys,$arrValues,"includes/dbvar.php");
		
			
			
			
	}
	
}
	
	
	?>
<form name=x method=post>
<table border="0" width="100%">
	<tr>
		<td width="161">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="161">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td width="161">Enter BPLS Site:</td>
		<td><input type="text" name="site" size="20"></td>
	</tr>
	<tr>
		<td width="161">Enter Database host:</td>
		<td><input type="text" name="dbhost" size="20" value='localhost'></td>
	</tr>
	<tr>
		<td width="161">Enter Database Name:</td>
		<td><input type="text" name="dbname" size="20"></td>
	</tr>
	<tr>
		<td width="161">Enter Database User:</td>
		<td><input type="text" name="dbuser" size="20"></td>
	</tr>
	<tr>
		<td width="161">Enter Database Password:</td>
		<td><input type="text" name="dbpass" size="20"></td>
	</tr>
	<tr>
		<td width="161">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<input type="hidden" name="valid" size="20">
	<tr>
		<td width="161">&nbsp;</td>
		<td><input type="button" value="Install" name="B1" onclick='Verifym();'></td>
	</tr>
</table>
<script language='javascript'>
function isBlank(_arg)
{
	if(_arg == null || _arg == "undefined" || _arg.length == 0)
	{
		return true;
	}
	else
	{
		var cnt = 0;
		var _str = "";
		for(var i=0;i<_arg.length;i++)
		{
			if( ! (_arg.charCodeAt(i) == 13 || _arg.charCodeAt(i) == 32))
			{
				_str += _arg.charAt(i);
			}

		}
		return (_str.length ==  0 || _str == "") ? (true) : (false);

	}
}

function Verifym()
{
	var x = document.x;
	
	if (isBlank(x.site.value)) {
		alert ("Please input site. Mutha");
		x.site.select();
		x.site.focus();
		return false;
	}
	if (isBlank(x.dbhost.value)) {
		alert ("Please input database hostname. Mutha");
		x.dbhost.select();
		x.dbhost.focus();
		return false;
	}
	
	if (isBlank(x.dbname.value)) {
		alert ("Please input database name. Mutha");
		x.dbname.select();
		x.dbname.focus();
		return false;
	}
	
	
	if (isBlank(x.dbuser.value)) {
		alert ("Please input database user. Mutha");
		x.dbuser.select();
		x.dbuser.focus();
		return false;
	}
	
	if (isBlank(x.dbpass.value)) {
		alert ("Please input database password. Mutha");
		x.dbpass.select();
		x.dbpass.focus();
		return false;
	}
	x.valid.value=1;
	x.submit();
}
</script>
	

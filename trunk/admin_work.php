<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
include_once'lib/ebpls.utils.php';
$file_dir=eBPLS_APP_URL;	
$iAdminPassword = md5($iAdminPassword);
if ($scmd==1) {
//$backup1 = passthru("mysqldump -cf --add-drop-table -h$dbhost -u$dbuser -p$dbpass $dbname > backup/$backup");
system("mysqldump -cf --add-drop-table -h$dbhost -u$dbuser -p$dbpass $dbname > backup/$backup");
system("gzip -9 backup/$backup &2<1");
$backupfile = $backup.".gz";
$query = mysql_query("insert into backups (backuptime, data) values (now(),'$backup')");
?>
<body onload='ConfDown("<?php echo $backupfile; ?>");'></body>
<?php

}

if ($scmd==2) {

	$allowed_file_size_in_bytes=100000000;
	$file_dir=eBPLS_APP_URL;	
//	$restore = trim($restore);
	
//	echo "this is the filename $fupload .... $fupload_name";
	//if (!empty($fupload_name)){\
	
	if ($fupload_size <= $allowed_file_size_in_bytes) {
		if (($pos = strrpos($fupload_name, ".")) == FALSE)
			echo "Error -Invalid Filename";
		else {
			$extension = substr($fupload_name, $pos + 1);
		}
		if ($extension == 'txt' or $extension == 'sql') {
			copy ($fupload, "backup/restore.sql");
			
			system("mysql -h$dbhost -u$dbuser -p$dbpass $dbname < backup/restore.sql");
			//echo "mysql -h$dbhost -u$dbuser -p$dbpass $dbname < $restore";
			$query = mysql_query("insert into restore (restoretime, data) values (now(),'$restore')");
			?>
			<body onload='alert("Database Restored");'</body>
			<?php
         
			
		} else {
			?>
			<body onload='javascript: alert ("Cannot restore database. Invalid file type.");'></body>
			<?
		}
			
		
	}
	else {
		?>
	<body onload='javascript:alert ("Cannot restore database. File exceeds maximum allowable size of 10MB.");'></body>
	<?
	//print "<hr>Upload Status: &nbsp &nbsp &nbsp Unable to upload file.<br>";
	//print "File too large. Allowable maximum file size is 50kb.<hr>";
	}
	
	//}
	//else {
	//$validateID=2;
	//include'validate-inc.php';			
	//}
}
if ($scmd == '200') {
	$ccdate = date('Y');
	$getrec = @mysql_query("select * from ebpls_business_enterprise_permit where for_year < '$ccdate' and transaction = 'New' and paid = '0'");
	while ($getRec = @mysql_fetch_assoc($getrec)) {
		$gethis = @mysql_query("select * from trans_his where business_id = '$getRec[business_id]'");
		$getHis = @mysql_num_rows($gethis);
		if ($getHis == 0) {
			$deletebus1 = @mysql_query("delete from ebpls_business_enterprise_permit where business_id = '$getRec[business_id]'");
			$deletebus2 = @mysql_query("delete from ebpls_business_enterprise where business_id = '$getRec[business_id]'");
			$deletebus3 = @mysql_query("delete from bus_grandamt where business_id = '$getRec[business_id]'");
			$deletebus4 = @mysql_query("delete from tempassess where business_id = '$getRec[business_id]'");
			$deletebus5 = @mysql_query("delete from tempbusnature where business_id = '$getRec[business_id]'");
		}
	}
}
	
                                                                                        




?>
<html>
<head>
	<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content="DAP_KSDO">
	
<title></title>
</head>

<script language='Javascript' src='javascripts/default.js'></script>
<script language='javascript'>
function BackUp(x)
{
	if (x.value=='') {
                        alert("Please input valid filename");
                        x.focus();
                        return false;
        } 
    if (x.value.length>30) {
                        alert("Filename exceeds max length");
                        x.focus();
                        x.select();
                        return false;
        }    
        
      
        doyou = confirm("Commence backup now?");
        if (doyou==true) {
	parent.location='index.php?part=4&scmd=1&class_type=&selMode=ebpls_nadmin&iAdminPassword=<?php echo $iAdminPassword; ?>&backup=' + x.value;
     
	   } else {
	return false;
	}

        return true;
}

function ConfDown(dme)
{
	doyou = confirm("Backup Finished, Download?");
	if (doyou==true) {
	 window.open('backup/' + dme,'viewpay',
				'left=20,top=20,width=450,height=450,toolbar=0,resizable=1');     
	} else {
		alert ("File is saved at the backup folder.");
	return false;
	}
}

function RestoreDB()
{
	var _FRM = document._FRM;
        if (_FRM.fupload.value=='') {
                        alert("Please input valid filename");
                        return false;
        }
        doyou = confirm("Commence restore now?");
        if (doyou==true) {
       // parent.location='index.php?part=4&scmd=2&class_type=&selMode=ebpls_nadmin&iAdminPassword=<?php echo $iAdminPassword; ?>&restore=' + _FRM.fupload.value;
      	_FRM.scmd.value=2;
         _FRM.submit();
       } else {
        return false;
        }
                                                                                                 
        return true;
}
function purgerecord()
{
	var _FRM = document._FRM;
	doyou = confirm("Delete Dormant Records?");
	if (doyou==true) {
        _FRM.prec.value == 'purge';
        _FRM.scmd.value=200;
         _FRM.submit();  
    } else {
        return false;
        }                                                                        
        return true;
}

</script>

<form name=_FRM action="index.php?part=4&class_type=Preference&selMode=ebpls_nsign&action_=8&itemEvent=1&data_item=0&orderbyasde=1" ENCTYPE="multipart/form-data" method="post">
<body onLoad="_FRM.backup.focus();">
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>

<tr><td colspan=2 ><br></td></tr>

		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
		<input type=hidden name=scmd>
		<input type=hidden name=prec>
			<td align=right valign=top> Backup Database : </td>
			<td align=left valign=top> &nbsp;
			<input type=text name=backup>&nbsp; 
			<input type=button value='Go' onclick='BackUp(backup);'></td>
			<td> &nbsp </td>
		</tr>
		<tr><td><br></td></tr>
		<tr width=90%>
			<td align=right valign=top> Restore Database : </td>
			<td align=left valign=top> &nbsp; 
			<input   type=file  name=fupload   maxlength=50 value="<?php echo $fupload;?>">&nbsp; 
			<input type=button value='Go' onclick='RestoreDB();'>
			</td>
			<td> &nbsp </td>
		</tr>
		<tr><td><br></td></tr>
		<tr width=90%>
			<td align=center valign=top colspan='3'> <input   type=button  name=purge   value="Delete Dormant Records" onclick="purgerecord();"> </td>
		</tr>
	</table>
</body>
</form>
</body>
</html>

<?php
$dataRecord_ = mysql_query("SELECT * FROM ebpls_buss_preference",$dbLinkFunc);
		
if (mysql_affected_rows($dbLinkFunc)==1 ){
$dataRecord__=mysql_fetch_array($dataRecord_);
$niSPermit=$dataRecord__[spermit];
$niSAssess=$dataRecord__[sassess];
$niSOR=$dataRecord__[sor];
$niSBackTaxes=$dataRecord__[sbacktaxes];
$niUnggoy=$dataRecord__[sdecimal];
$niSRequire=$dataRecord__[srequire];
$iLGUName=$dataRecord__[lguname];
$iLGUProvince=$dataRecord__[lguprovince];
$iLGUMunicipality=$dataRecord__[lgumunicipality];
$iLGUOffice=$dataRecord__[lguoffice];
$iLGUImage=$dataRecord__[lguimage];
$iBodyColorScheme=$dataRecord__[bodycolor];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- XHTML Compliant ito by Robert and Von-->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content="PARV Solutions">
	<link href="stylesheets/default.css" rel="stylesheet" type="text/css">
	
	<!--
	-->
</head>
<body  text="#000000" link="#333333" vlink="#333333" alink="#D0E8FF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?php echo $iBodyColorScheme;?>"> <!--"#3C746B"-->


	
<?php
         if ($part<>2) {
?>

<table width="100%" height="80" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="Left" valign="center" width=20%>
			<img src=images/<?php echo $iLGUImage;?> border="0" hspace="0" vspace="0" width="80" height="63">
		</td>
		<td align="Right" valign="center" width=80%>
			<img src="images/title.gif" border="0" hspace="0" vspace="0" height=63>
		</td>
	</tr>
</table>
<?php
       }
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td bgcolor="#FFFFFF" valign="TOP">
		<!-- Begin Body Here -->

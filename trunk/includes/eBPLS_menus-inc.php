<?php
/*
Modification History:
2008.04.25: Change invalid constants to strings to reduce PHP errors in log
2008.05.11: Changed BusItem to busItem in 'busItem=ebpls_ninterestsur' strings after line 968
*/
include("includes/variables.php");
include_once("lib/multidbconnection.php");                                                                                                
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
//$dbupen = Open();


if ($GLOBALS['watbrowser']=='msie' and $ThUserData['id']<>'') {
setcookie("ThUserData['id']",$ThUserData['id'], $intCookieExp, '/', false, 0); 
$ieuser=$_COOKIE['ieuser'];
}
$busItem = isset($busItem) ? $busItem : '' ; //2008.05.11
$user_id = isset($ThUserData['id'])?$ThUserData['id']:""; //2008.04.25
$ses = $_COOKIE['PHPSESSID'];

//validates login session
if (getenv('HTTP_X_FORWARDED_FOR')) {							
    $remoteip = getenv('HTTP_X_FORWARDED_FOR'); 
} else { 
    $remoteip = getenv('REMOTE_ADDR');
}	
$seslog = mysql_query("select * from user_session where ip_add='$remoteip'");
$haveses = mysql_num_rows($seslog);
$getses = mysql_fetch_array($seslog);
if ($haveses>0) {
	$updses = mysql_query("Update user_session set date_input=now(), user_id='$ThUserData[id]' where ip_add='$remoteip'");
} else {
//4008.05.06	$insses = mysql_query("insert into user_session values ('','$remoteip','$ses','$ThUserData[id]',now())");
	$insses = mysql_query("insert into user_session values ('','$remoteip','$ses','$user_id',now())");
}


if ($ieuser=='') {
?>
<Script language='javascript'>
function setCookie( name, value, expires, path, domain, secure ) {
  var today = new Date();
  today.setTime( today.getTime() );
  if ( expires ) {
    expires = expires * 1000 * 60 * 60 * 24;
  }
  var expires_date = new Date( today.getTime() + (expires) );
  document.cookie = name+"="+escape( value ) +
    ( ( expires ) ? ";expires="+expires_date.toGMTString() : "" ) + //expires.toGMTString()
    ( ( path ) ? ";path=" + path : "" ) +
    ( ( domain ) ? ";domain=" + domain : "" ) +
    ( ( secure ) ? ";secure" : "" );
}
</script>
<body onload='setCookie("ieuser ","<?php echo $ThUserData['id']; ?>","50","","","");'></body>
<?
$ieuser=$_COOKIE[ieuser];
	//$ieuser=$ThUserData[id];
}

if ($ieuser=='' || $part=='' || $user_id=='') {
	setUserLogout();
?>
	<body onload='parent.location="index.php";'></body>
<?php
}

if ($GLOBALS['watbrowser']=='msie' and $ThUserData['id']=='') {
	$user_id=$_COOKIE['ieuser'];
	$ThUserData['id'] = $_COOKIE['ieuser'];
}

if (!isset($fuploadname)) $fuploadname='';  //2008.04.25
?>


<link href="stylesheets/default.css" rel="stylesheet" type="text/css">
<script language='Javascript' src="javascripts/default.js"></script><!-- subukan mo ierase..amok to -->
<input type=hidden name=fuploadname value="<?php echo $fuploadname;?>">
<script language='Javascript' src='includes/eBPLS.js'></script>
<input type=hidden name=part value="<?php echo $part;?>">


<?php


if (isset($_COOKIE['ThUserData'])) {
   foreach ($_COOKIE['ThUserData'] as $name => $value) {
	   $xx = "ThUserData['$name']";
	   @eval ("\$t = '$'.$xx");
	   $t = $value;
	   //echo $t;
      // echo "$name : $value <br />\n";
   }
}


include"includes/variables.php";
/*
$getuid = SelectDataWhere($dbtype,$dbLink,"ebpls_user",
			"where username='$usern'");
$uid=FetchArray($dbtype,$getuid);
$ThUserData['id']=$uid[id];
$user_id=$uid[id];*/
include"includes/bizlevel.php";
include"includes/motorlevel.php";
include"includes/franlevel.php";
include"includes/occulevel.php";
include"includes/pedlevel.php";
include"includes/fishlevel.php";
include"includes/ctclevel.php";
include"includes/setlevel.php";
include"includes/reportlevel.php";
include"includes/referlevel.php";
if ($ThUserData['id']==0  and $ThUserData['username']==md5("cookienamo") and $ThUserData['level']==7) {
$godmode = 'on';
$ulev=6;
} else {
	
// 	$chkit = SelectDataWhere($dbtype,$dbLink,
// 						"ebpls_user","where id=$ThUserData[id] ");
// 	$chkit = FetchArray($dbtype,$chkit);
	
	
$ulev = $ThUserData['level'];

$ulev = decrypt_md5($ulev,$decoder);
}

$chkpenalty = mysql_query("select * from ebpls_buss_penalty1");
$chkpenalty = mysql_num_rows($chkpenalty);
if ($chkpenalty==0) {
	$insertrec = mysql_query("insert into ebpls_buss_penalty1 values ('','','','','','','','',now())");
}
//$ulev=6;
?>
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr>
<?php
        $tdate=date('D, M d, Y');
?>
<td width="30%" class="color3">Today is <?php echo $tdate;?></td>
<td width="30%" align="center">Welcome, <b> <?php echo(strtoupper($ThUserData['firstname'])); ?> <?php echo(strtoupper($ThUserData['lastname'])); ?></b></td>
<td width="40%" height=25 align=right > [ <a href=?part=4>HOME</a>
<?php
        if ($ulev>5) {
?>
|
<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=ebpls_nadmin&action_=2>Admin</a>
<?php
}
?>

 | <a href=?part=4&selMode=FAQS>FAQs</a> |
<a href=?part=4&selMode=links>Links</a> | <a href=?part=2>Logout</a> ] &nbsp</td>
</tr>
</table>
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr>
<!--<td width=2% align=center valign=top>--> <!--first table within a cell-->
<td width=20% align=center valign=top bgcolor="#DDDDDD"> <!--first table within a cell-->
<table border=0 width=100% cellspacing=0 cellpadding=0 class="mnu">

<tr>

<td colspan="2" height="10" class="header" align=center>SEARCH </td>
</tr>
<form action = 'index.php?part=4' method=post name="_FRM1">
<input type=hidden name=superlog value=<?php echo isset($superlog)?$superlog:0 ; ?>>
<tr><input type=hidden name=ieuser value=<?php echo $ieuser; ?>>
<td height="19">&nbsp;<b>Business/Lastname</b></td>
<td height="19"><input type=text name=search_lastname size=15 
	value="<?php echo isset($itxt_Search)?$itxt_Search:'';?>" style="font-size:10"></td>
</tr>

<tr>
	<td align="left" height="19"><b>&nbsp;Permit Type</b></td>
	<td height="19" align="left"><select name=permit_type height="19" style="font-size:10">
	<?php
	
	if ($bp==1 || $ulev==6 || $ulev==7) { ?>
	<option value='Business'>Business</option>
	<?php
	}
        /*if ($mp==1 || $ulev==6 || $ulev==7) { ?>
	<option value='Motorized'>Motorized</option>
	<?php
        }
        if ($op==1 || $ulev==6 || $ulev==7) { ?>

	<option value='Occupational'>Occupational</option>
	<?php
        }
        if ($pp==1 || $ulev==6 || $ulev==7) { ?>

	<option value='Peddlers'>Peddlers</option>
	<?php
        }
        if ($fp==1 || $ulev==6 || $ulev==7) { ?>

	<!--<option value='Franchise'>Franchise</option>-->
	<?php
        }
        if ($ip==1 || $ulev==6 || $ulev==7) { ?>

	<option value='Fishery'>Fishery</option>
	<?php } */ ?>
	</select>
	</td>
</tr>

<tr>
	<td align="left" height="19"><b>&nbsp;Transaction</b></td>
	<td height="19" align=left><select name=search_businesstype height="19" style="font-size:10">
	

	<?php
        
        if ($ssap==1 ||  $ulev==6 || $ulev==7) { ?>
	<option value=1221>Application</option>
	<?php
        }
        if ($ssass==1 ||  $ulev==6 || $ulev==7) { ?>

	<option value=4212>Assessment</option>
	<?php
        }
        if ($ssapp==1 ||  $ulev==6 || $ulev==7) { ?>

	<option value=5212>Approval</option>
	<?php
        }
        if ($sspay==1 ||  $ulev==6 || $ulev==7) { ?>

	<option value=2212>Payment</option>
	<?php
        }
        if ($ssar==1 ||  $ulev==6 || $ulev==7) { ?>

	<option value=3212>Release</option>
	<?php } ?>
	</select>
	</td>
</tr>

<tr>
	<td align="left" height="19">&nbsp;<b>Application Type</b></td>
	<td><select name='search_status' height="19" style="font-size:10">
	<option value=''></option>
	<option value='New'>New</option>
	<option value='ReNew'>ReNew</option>
	<option value='Retire'>Retire</option>
	</select>
	</td>
</tr>

<tr>
	<td align="left" height="19">&nbsp;</td>
	<td><input type=submit name=Search value="SEARCH" style="font-size:10" height="19"></td>
</tr>
</form>
<!--
<tr bgcolor="#EEEEEE">
<td><a href=?part=4>HOME</a></td>
</tr>
-->
</table>
<table border=0 width=100% cellspacing=0 cellpadding=0 class="mnu">
<tr>
<td colspan="2" height="7" class="header" align=center>MAIN MENU</td>
</tr>


<?php if ($pm==1 || $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'><a href=?part=4&class_type=Permits>Permits</a></td>
</tr>

<?php
} // end pm = 1
if ($part<>""){
	//include 'includes/imagesrc.php';
	$class_type = isset($class_type) ? $class_type : ''; //2008.05.111
	if ($class_type=='Permits') {
?>

<?php if ($bp==1 || $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'>&nbsp &nbsp &nbsp -
<a href=?part=4&itemID_=1221&class_type=Permits&permit_type=Business&busItem=Business&mtopsearch=SEARCH>Business</a></td>
</tr>

<?php
}
	if ($busItem=='Business'){
?>

<?php if ($bpap==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>
<?php
}
if ($bpas==1 ||  $ulev==6 || $ulev==7) {
?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=4212&mtopsearch=SEARCH'>Assessment</a></td>
</tr>
<?php
}
if ($bpapp==1 ||  $ulev==6 || $ulev==7) {
?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=5212&mtopsearch=SEARCH'>Approval</a></td>
</tr>
<?php
}
if ($bpay==1 ||  $ulev==6 || $ulev==7) {
?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>
<?php
}
if ($bpar==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
}
?>

<?php 
/*if ($fp==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp - 
<a href=?part=4&class_type=Permits&itemID_=1221&permit_type=Franchise&busItem=Franchise&mtopsearch=SEARCH> Franchise</a></td>
</tr>

<?php
}
if ($busItem=='Franchise'){
?>

<?php
if ($fpap==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Franchise&busItem=Franchise&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>
<?php
}
if ($fpay==1 ||  $ulev==6 || $ulev==7) {
?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Franchise&busItem=Franchise&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>
<?php
}
if ($fpar==1 ||  $ulev==6 || $ulev==7) {
?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Franchise&busItem=Franchise&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
}*/
?>

<?php /*if ($ip==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=?part=4&class_type=Permits&itemID_=1221&permit_type=Fishery&busItem=Fishery&mtopsearch=SEARCH> Fishery</a></td>
</tr>

<?php
}


/*if ($busItem==Fishery){
?>
<?php
                                                                                
                                                                                
if ($ipap==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Fishery&busItem=Fishery&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>
<?php
}
if ($ipay==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Fishery&busItem=Fishery&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>
<?php
}
if ($ipar==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Fishery&busItem=Fishery&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
}

/*if ($mp==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=?part=4&class_type=Permits&itemID_=1221&permit_type=Motorized&busItem=Motorized&mtopsearch=SEARCH> Motorized</a></td>
</tr>

	<?php
		if ($busItem==Motorized){
	                                                                        
                                                                                
if ($mpap==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Motorized&busItem=Motorized&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>
<?php
}
if ($mpay==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Motorized&busItem=Motorized&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>
<?php
}
if ($mpar==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Motorized&busItem=Motorized&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
}

}

if ($op==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=?part=4&class_type=Permits&itemID_=1221&permit_type=Occupational&busItem=Occupational&mtopsearch=SEARCH> Occupational</a></td>
</tr>

<?php
	if ($busItem==Occupational){
?>
<?php
                                                                                
                                                                                
if ($opap==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Occupational&busItem=Occupational&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>
<?php
}
if ($opay==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Occupational&busItem=Occupational&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>
<?php
}
if ($opar==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Occupational&busItem=Occupational&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
}
}
if ($pp==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=?part=4&class_type=Permits&itemID_=1221&permit_type=Peddlers&busItem=Peddlers&mtopsearch=SEARCH> Peddlers</a></td>
</tr>

<?php
	if ($busItem==Peddlers){
?>
<?php
                                                                                
                                                                                
if ($ppap==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Peddlers&busItem=Peddlers&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>
<?php
}
if ($ppay==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Peddlers&busItem=Peddlers&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>
<?php
}
if ($ppar==1 ||  $ulev==6 || $ulev==7) {
?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&class_type=Permits&permit_type=Peddlers&busItem=Peddlers&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
}
}*/
}

?>
	

<?php if ($ctc==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
	<td class='bold'><a href=?part=4&class_type=CTC&permit_type=CTC&busItem=CTC&itemID_=&item_id=CTC>CTC</a></td>
</tr>

<?php 
if ($busItem=='CTC') { 
?>	


<?php if ($ctci==1 ||  $ulev==6 || $ulev==7) { ?>
	<tr>
		<td class='bold'> &nbsp &nbsp &nbsp -
		<a href='?part=4&itemID_=1001&busItem=CTC&permit_type=CTC&ctc_type=INDIVIDUAL&item_id=CTC'>Individual</a></td>
	</tr>
<?php
}
if ($ctcb==1 ||  $ulev==6 || $ulev==7) {
?>

	<tr>
		<td class='bold'> &nbsp &nbsp &nbsp - 
		<a href='?part=4&itemID_=1002&busItem=CTC&permit_type=CTC&ctc_type=BUSINESS&item_id=CTC'>Corporate</a></td>
	</tr>
<?php 
}
}
}
?>


<?php 

if ($rmlevel==1 ||  $ulev==6 || $ulev==7) {
?>
<tr>
<td class='bold'><a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports>Reports</a></td>
</tr>
<?php

}
if ($busItem=='Reports') {                                                                                                
        if ($brm1==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Business>Business</a></td>
</tr>
                                                                                                 
<?php
        }
 
                                                                                                
        /*if ($mpl1==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Motorized>Motorized</a></td>
</tr>
                                                                                                 
<?php
        }

                                                                                                
        if ($ocl==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Occupational>Occupational</a></td>
</tr>
                                                                                                 
<?php
        }
                                                                                                
        if ($fpl==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<!--<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Franchise>Franchise</a></td>
</tr>-->
                                                                                                 
<?php
        }

                                                                                                
        if ($fil1==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Fishery>Fishery</a></td>
</tr>
                                                                                                 
<?php
                                                                                                
}
                                                                                                
        if ($ppl1==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Peddlers>Peddlers</a></td>
</tr>
                                                                                                 
<?php
        }*/
                                                                                                
        if ($ctl1==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=CTC>CTC</a></td>
</tr>
                                                                                                 
<?php
        }
                                                                                                 
                                                                                                
         if ($col1==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Tax/Fee>Collections</a></td>
</tr>
                                                                                                 
<?php
        }
                                                                                                 
        if ($col1==2) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=Abstract>Abstract of Collection</a></td>
</tr>
                                                                                                 
<?php
        }
                                                                                                 
                                                                                                 
     
                                                                                                 
  if ($sys1==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <a href=?part=4&class_type=Reports&itemID_=921&busItem=Reports&permit_type=Reports&rpt=System>System</a></td>
</tr>
                                                                                                 
<?php
        }
}

if ($reflevel==1 ||  $ulev==6 || $ulev==7) { ?>


<tr>
<td class='bold'><a href=?part=4&class_type=Preference>References</a></td>
</tr>
<?php
}
	if ($class_type=='Preference') {
if ($pcl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nProvince&is_desc=&page=1>Province </a></td>
</tr>
<?php
}
if ($lll==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nLGU&is_desc=&page=1>LGU </a></td>
</tr>
<?php
}
if ($zcl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nzip&is_desc=ASC&page=1>Zip </a></td>
</tr>
<?php
}
if ($dcl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_ndistrict&is_desc=&page=1>District </a></td>
</tr>

<?php
}
if ($bll==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nbarangay&is_desc=&page=1>Barangay </a></td>
</tr>
<?php
}
if ($zll==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nzone&is_desc=&page=1>Zone </a></td>
</tr>

<?php
}

if ($brpl==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&pref_type=Business>Business Permit</a></td>
</tr>

<?php
	$pref_type = isset($pref_type) ? $pref_type : '' ; //2008.05.11
	if ($pref_type=='Business') {
?>
<?php
if ($tfol==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nTFO&is_desc=ASC>Tax, Fee and Other Charges</a></td>
</tr>
<?php
}
if ($bnl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_nbusiness&action_=0&orderbyasdes=1&reftype=bus&permit_type=Business>Business Nature</a></td>
</tr>

<!--<tr>
<td> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&pref_type=Business&selMode=ebpls_npsic&action_=9&itemEvent=1&data_item=0>PSIC </a></td>
</tr>-->

<?php
			}
		}
	}
?>

<?php

if ($opfl==1 ||  $ulev==6 || $ulev==7) { 
	if ( $ulev==6 || $ulev==7) {
		$idd='ebpls_notherfees';
	}

?>
	
<!--<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&permit=others&selMode=<?php echo $idd; ?>&action_=8&itemEvent=1&data_item=0>Other Permit </a></td>
</tr>-->
<?php
}
/*if ($class_type==Preference and $permit==others) {

if ($fpfl==1 ||  $ulev==6 || $ulev==7) {
	if ($eng==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'>  &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_nenginetype&is_desc=ASC>Engine Type</a></td>
</tr>

<?php
}
if ($fishde==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'>  &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&permit=others&selMode=ebpls_nfishact&is_desc=ASC>Fishery Activity Description</a></td>
</tr>

<?php
}
	if ($bfl==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nboatfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1>Boat Fees </a></td>
</tr>
<?php
	}

	if ($fcl==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nfishcfees&permit=others&action_=8&itemEvent=1&data_item=0&orderbyasdes=1>Fish Activities Fees </a></td>
</tr>
<?php
	}
}
}*/

if ($coal==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nchart&action_=8&itemEvent=1&data_item=0&orderbyasdes=1>Chart Of Accounts</a></td>
</tr>
<?php
}
if ($brl==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nRequirements&is_desc=ASC>Requirements</a></td>
</tr>
<?php
}


if ($otl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nownership&is_desc=ASC>Ownership Type</a></td>
</tr>
<?php
}
if ($ctcsl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nctc>CTC Settings</a></td>
</tr>
<?php
}
if ($occutl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_noccupancy&is_desc=ASC>Occupancy Type</a></td>
</tr>

<?php
}
if ($iscl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nindustry&is_desc=ASC>Industry Sector</a></td>
</tr>

<?php
}
if ($citi==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_ncitizenship&is_desc=ASC>Citizenship</a></td>
</tr>

<?php
}
if ($citi==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_neconomic_area&is_desc=ASC>Economic Area</a></td>
</tr>
<?php
}

if ($citi==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_neconomic_org&is_desc=ASC>Economic Organization</a></td>
</tr>

<?php
}
if ($pnfl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_npermitform&is_desc=ASC>Permit Number Format</a></td>
</tr>

<?php
}
if ($lotpl==1 ||  $ulev==6 || $ulev==7) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nlotpin&action_=8&itemEvent=1&data_item=0&orderbyasdes=1>Lot Pin</a></td>
</tr>
                                                                                                 
<?php
}
if ($anbl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nannouncement&is_desc=ASC&page=1>Announcement</a></td>
</tr>

<?php
}
if ($psl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&busItem=ebpls_ninterestsur&action_=7&itemEvent=1&data_item=0>Interest/Surcharge</a></td>
</tr>
<?php
}
if ($busItem == 'ebpls_ninterestsur') {
if ($psl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&busItem=ebpls_ninterestsur&selMode=ebpls_npenalty&action_=7&itemEvent=1&data_item=0>Business</a></td>
</tr>
<?php
}
}
/*if ($busItem == 'ebpls_ninterestsur') {
if ($psl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&busItem=ebpls_ninterestsur&selMode=ebpls_nmotorpenalty&action_=7&itemEvent=1&data_item=0>Motorized</a></td>
</tr>
<?php
}
}
if ($busItem == 'ebpls_ninterestsur') {
if ($psl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&busItem=ebpls_ninterestsur&selMode=ebpls_nfishpenalty&action_=7&itemEvent=1&data_item=0>Fishery</a></td>
</tr>
<?php
}
}
if ($busItem == 'ebpls_ninterestsur') {
if ($psl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&busItem=ebpls_ninterestsur&selMode=ebpls_noccpenalty&action_=7&itemEvent=1&data_item=0>Occupational</a></td>
</tr>
<?php
}
}*/
if ($rsl==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nsign&is_desc=ASC>Signatories </a></td>
</tr>

<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nreportsign&is_desc=ASC>Signatory Templates </a></td>
</tr>

<!--<tr>
<td> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_ntemplate&action_=8&itemEvent=1&data_item=0>Templates </a></td>
</tr>-->
<?php
}

if ($faql==1 ||  $ulev==6) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_faq&orderbyasdes=1&page=1>FAQ Admin </a></td>
</tr>
                                                                                                 
<?php
}

if ($linkl==1 ||  $ulev==6) { ?>
                                                                                                 
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_link&orderbyasdes=1&page=1>Link Admin </a></td>
</tr>
                                                                                                 
<?php
}
if ($gsl==1 ||  $ulev==6 || $ulev==7) { ?>
<tr>
<td class='bold'> &nbsp &nbsp &nbsp -
<a href=index.php?part=4&class_type=Preference&selMode=ebpls_npreferences&action_=6>General</a></td>
</tr>
<?php 
}
}
?>
<!--
<tr>
<td><a href=?part=2>Logout</a></td>
</tr>
-->
<?php
}
?>
<?php if ($setlevel==1 ||  $ulev==6 || $ulev==7) { ?>

<tr>
<td class='bold'><a href=?part=4&permit_type=Settings&busItem=Settings&item_id=Settings>Settings</a></td>
</tr>

<?php 
}
if ($busItem=='Settings' and $item_id=='Settings') { 
?>	
<?php if ($ssl==1 ||  $ulev==6 || $ulev==7) { ?>


	<tr>
		<td class='bold'> &nbsp &nbsp &nbsp - 
		 <a href='?part=4&itemID_=6&busItem=Settings&permit_type=Settings&settings_type=Syssettings&item_id=Settings'>System Settings</a></td>
	</tr>
<?php 
}
if ($uml==1 ||  $ulev==6 || $ulev==7) { ?>
	<tr>
		<td class='bold'> &nbsp &nbsp &nbsp - 
		<a href='?part=4&itemID_=7&busItem=Settings&permit_type=Settings&settings_type=UserManager&item_id=Settings'>User Manager</a></td>
	</tr>
	
<?php 
}
if ($acl==1 ||  $ulev==6 || $ulev==7) { ?>
	<tr>
		<td class='bold'> &nbsp &nbsp &nbsp - 
		<a href='?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings'>Activity Log</a></td>
	</tr>
	
<?php 
}
//if ($cspl==1 ||  $ulev==6 || $ulev==7) { ?>
	<!--<tr>
		<td class='bold'> &nbsp &nbsp &nbsp - 
		<a href='?part=4&itemID_=23&busItem=Settings&permit_type=Settings&settings_type=ColorScheme&item_id=Settings'><font size=1>Color Scheme Preference</font></a></td>
	</tr>-->
	
	<!--<tr>
		<td> &nbsp &nbsp &nbsp -
		<a href='?part=4&itemID_=&busItem=Settings&permit_type=Settings&settings_type=SysReference&item_id=Settings'><font size=1>System Reference</font></a></td>
	</tr>-->

<?php 
//}
}

?>

<tr>
<td align=left><hr></td>
</tr>

<tr>
<td align=center><b><i>Announcement Board</b></i></td>
</tr>
<?php
if(!isset($_GET['pagean'])){
    $pagean= 1;
} else {
    $pagean= $_GET['pagean'];
}
                                                                                                               
// Define the number of results per page
$max_resultsan= 1;
// Figure out the limit for the query based
// on the current page number.
$froman= abs((($pagean* $max_resultsan) - $max_resultsan));

$searchsqlan="select announcements, announced_by, 
				date_modified,eaid from ebpls_announcement
				order by eaid desc limit $froman, $max_resultsan";

$cntsqlan = "select count(eaid) as NUM from ebpls_announcement";

//$resultan = mysql_query($searchsqlan)or die (mysql_error());
$resultan = Query1($dbtype,$dbLink,$searchsqlan);
// Figure out the total number of results in DB:
$total_resultsan = Result($dbtype,Query1($dbtype,$dbLink,$cntsqlan),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesan = ceil($total_resultsan / $max_resultsan);


echo "<tr><td><div align=left>";
                                                                                                 
if($pagean > 1){
	$prevan = ($pagean - 1);
	echo "<a href=$PHP_SELF?part=4&pagean=$prevan>< Prev</a>&nbsp;";
}
                                                                                                 
for($i = 1; $i <= $total_pagesan; $i++){
	if(($pagean) == $i){
		echo "No. $i&nbsp;:";
	} else {
                        //echo "<a href=$PHP_SELF?part=4&page=$i>$i</a>&nbsp;";
	}
}
                                                                
               // Build Next Link
                                                                                                               
if($pagean < $total_pagesan){
                        	$nextan = ($pagean + 1);
                        	echo "<a href=$PHP_SELF?part=4&pagean=$nextan>Next ></a>";
}
                                
                                                                                                 
echo "</td></tr>";
while($get_infoan = FetchRow($dbtype,$resultan))
{

?>
<tr>
<td align=left valign=top >
<!--<textarea name=ab rows=5 cols=23 readonly>-->
<?php echo $get_infoan[0];?>
</td>
</tr>
<tr>
<td align=right valign=top><?php echo $get_infoan[1].' ('.$get_infoan[2].') <br><br>';?></td>
</tr>
<?php
}
?>

<tr>
<td>

</td>
</tr>
</table>
</td>
<td width=80% valign=top >
<?php


if ($permit_type=='Business') {
	include "includes/resetvars.php";
	include"includes/bizlevel.php";
//} elseif ($permit_type=='Motorized') {
//	include "includes/resetvars.php";
//	include"includes/motorlevel.php";
//} elseif ($permit_type=='Franchise') {
//	include "includes/resetvars.php";
//	include"includes/franlevel.php";
//} elseif ($permit_type=='Occupational') {
//	include "includes/resetvars.php";
//	include"includes/occulevel.php";
//} elseif ($permit_type=='Peddler') {
//	include "includes/resetvars.php";
//	include"includes/pedlevel.php";
//} elseif ($permit_type=='Fishery') {
//	include "includes/resetvars.php";
//	include"includes/fishlevel.php";
} elseif ($permit_type=='CTC') {
	include "includes/resetvars.php";
	include"includes/ctclevel.php";
} elseif ($permit_type=='Settings') {	
	include "includes/resetvars.php";
	include"includes/setlevel.php";
} elseif ($permit_type=='Reports') {
	include "includes/resetvars.php";
	include"includes/reportlevel.php";
} elseif ($class_type=='Preference') {
	include "includes/resetvars.php";
	include"includes/referlevel.php";
}
if ($ulev==6) {
	include_once 'body.php';
include_once'includes/bodycontent-inc.php';
include "logger.php";
} else {
include "includes/security.php";
}

?>
</td>

</tr>
</table>

</form>

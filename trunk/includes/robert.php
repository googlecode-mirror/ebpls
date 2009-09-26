<!--<form action="<?php echo $PHP_SELF;?>" method="post"> -->
<input type=hidden name=fuploadname value="<?php echo $fuploadname;?>">

<input type=hidden name=part value="<?php echo $part;?>">

<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr>
<td colspan=3 height=25 align=right > [ <a href=?part=4>HOME</a> | <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&selMode=ebpls_nadmin&action_=2>Admin</a> | FAQs | Links | <a href=?part=2>Logout</a> ] &nbsp</td>
</tr>

<tr>
<td width=2% align=center valign=top> <!--first table within a cell-->
<td width=18% align=center valign=top bgcolor="#DDDDDD"> <!--first table within a cell-->
<table border=0 width=100% cellspacing=2 cellpadding=2>
<!--
<tr bgcolor="#EEEEEE">
<td><font face="ARIAL" size="1"><b>Welcome:</b></font> &nbsp;
<font face="ARIAL" size="1" color="#000066"><b>"<?php echo(strtoupper($ThUserData['username'])); ?>"</b></font> &nbsp;
<font face="ARIAL" size="3" color="#CC0000"><b><i>(<?php echo($thUserLevel[$ThUserData['level']][1]); ?>)</i></b></font>
<br><br>
</td>
</tr>
-->

<tr>
<td align=center>
<img src=images/<?php echo $iLGUImage;?> border="0" hspace="0" vspace="0" width="80" height="63">
</td>
</tr>
<form action = 'index.php?part=4' method=post>
<tr>
<td><input type=text name=search_lastname size=15 value="<?php echo $itxt_Search;?>"><br></td>
</tr>

<tr>
	<td><select name=permit_type class=normal>
	<option value='Business'>Business</option>
	<option value='Motorized'>Motorized</option>
	<option value='Occupational'>Occupational</option>
	<option value='Peddlers'>Peddlers</option>
	<option value='Franchise'>Franchise</option>
	<option value='Fishery'>Fishery</option>
	</select>
	</td>
</tr>

<tr>
	<td><select name=search_businesstype class=normal>
	<option value=1221>Application</option>
	<option value=4212>Assessment</option>
	<option value=5212>Approval</option>
	<option value=2212>Payment</option>
	<option value=3212>Release</option>
	</select>
	</td>
</tr>

<tr>
	<td><select name='search_status' class=normal>
	<option value=0>-----</option>
	<option value='New'>New</option>
	<option value='ReNew'>ReNew</option>
	<option value='Retire'>Retire</option>
	</select>
	</td>
</tr>

<tr>
	<td><input type=submit name=Search value="SEARCH"></td>
</tr>
</form>
<!--
<tr bgcolor="#EEEEEE">
<td><a href=?part=4>HOME</a></td>
</tr>
-->
<tr bgcolor="#EEEEEE">
<?php
if ($permit_type<>""){
	$permit_type='Permit';
}
?>
<td><?php echo $permit_type;?> Permits</td>
</tr>

<?php
if ($part<>""){
	include 'includes/imagesrc.php';
?>

<tr>
<td>&nbsp &nbsp 

<img src=<?php echo $imagesrc1; ?> width=20 height=20/> <a href=?part=4&itemID_=1221&permit_type=Business&busItem=Business&mtopsearch=SEARCH>Business</a></td>
</tr>

<?php
	if ($busItem==Business){
?>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Business&busItem=Business&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Business&busItem=Business&itemID_=4212&mtopsearch=SEARCH'>Assessment</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Business&busItem=Business&itemID_=5212&mtopsearch=SEARCH'>Approval</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Business&busItem=Business&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Business&busItem=Business&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
?>

<tr>
<td> &nbsp &nbsp <img src=<?php echo $imagesrc2; ?> width=20 height=20/><a href=?part=4&itemID_=2212&permit_type=Franchise&busItem=Franchise&mtopsearch=SEARCH> Franchise</a></td>
</tr>

<?php
	if ($busItem==Franchise){
?>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
?>

<tr>
<td> &nbsp &nbsp <img src=<?php echo $imagesrc3; ?> width=20 height=20/><a href=?part=4&itemID_=2212&permit_type=Fishery&busItem=Fishery&mtopsearch=SEARCH> Fishery</a></td>
</tr>

<?php
	if ($busItem==Fishery){
?>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
?>

<tr>
<td> &nbsp &nbsp <img src=<?php echo $imagesrc4;?> width=20 height=20/><a href=?part=4&itemID_=2212&permit_type=Motorized&busItem=Motorized&mtopsearch=SEARCH> Motorized</a></td>
</tr>

<?php
	if ($busItem==Motorized){
?>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
?>

<tr>
<td> &nbsp &nbsp <img src=<?php echo $imagesrc5; ?> width=20 height=20/><a href=?part=4&itemID_=2212&permit_type=Occupational&busItem=Occupational&mtopsearch=SEARCH> Occupational</a></td>
</tr>

<?php
	if ($busItem==Occupational){
?>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
?>

<tr>
<td> &nbsp &nbsp <img src=<?php echo $imagesrc6; ?> width=20 height=20/><a href=?part=4&itemID_=2212&permit_type=Peddlers&busItem=Peddlers&mtopsearch=SEARCH> Peddlers</a></td>
</tr>

<?php
	if ($busItem==Peddlers){
?>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=1221&mainfrm=Main'>Application</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=2212&mtopsearch=SEARCH'>Payment</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
<a href='?part=4&permit_type=Franchise&busItem=Franchise&itemID_=3212&mtopsearch=SEARCH'>Releasing</a></td>
</tr>

<?php
}
?>


<tr bgcolor="#EEEEEE">
	<td><a href=?part=4&busItem=CTC&permit_type=CTC&itemID_=CTC&item_id=CTC>CTC</a></td>
</tr>

<?php 
if ($busItem==CTC and $item_id==CTC) { 
?>	

	<tr bgcolor="#EEEEEE">
		<td> &nbsp &nbsp &nbsp &nbsp &nbsp -
		<a href='?part=4&itemID_=101&busItem=CTC&permit_type=CTC&ctc_type=INDIVIDUAL&item_id=CTC'>Individual</a></td>
	</tr>

	<tr bgcolor="#EEEEEE">
		<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
		<a href='?part=4&itemID_=101&busItem=CTC&permit_type=CTC&ctc_type=BUSINESS&item_id=CTC'>Business</a></td>
	</tr>

	<tr bgcolor="#EEEEEE">
		<td> &nbsp &nbsp &nbsp &nbsp &nbsp - 
		<a href='?part=4&itemID_=1217&busItem=CTC&item_id=CTC&permit_type=CTC'>CTC Report</a></td>
	</tr>

<?php 
}
?>

<tr bgcolor="#EEEEEE">
<td><a href=?part=4&permit_type=&busItem=Settings&item_id=Settings>Settings</a></td>
</tr>

<?php 
if ($busItem==Settings and $item_id==Settings) { 
?>	

	<tr bgcolor="#EEEEEE">
		<td> &nbsp  - 
		<a href='?part=4&itemID_=6&busItem=Settings&permit_type=Settings&settings_type=Syssettings&item_id=Settings'><font size=1>System Settings</font></a></td>
	</tr>

	<tr bgcolor="#EEEEEE">
		<td> &nbsp  - 
		<a href='?part=4&itemID_=7&busItem=Settings&permit_type=Settings&settings_type=UserManager&item_id=Settings'><font size=1>User Manager</font></a></td>
	</tr>
	
	<tr bgcolor="#EEEEEE">
		<td> &nbsp  - 
		<a href='?part=4&itemID_=11&busItem=Settings&permit_type=Settings&settings_type=ActivityLog&item_id=Settings'><font size=1>Activity Log</font></a></td>
	</tr>
	
	<tr bgcolor="#EEEEEE">
		<td> &nbsp  - 
		<a href='?part=4&itemID_=23&busItem=Settings&permit_type=Settings&settings_type=ColorScheme&item_id=Settings'><font size=1>Color Scheme Preference</font></a></td>
	</tr>
	
	<tr bgcolor="#EEEEEE">
		<td> &nbsp  -
		<a href='?part=4&itemID_=&busItem=Settings&permit_type=Settings&settings_type=SysReference&item_id=Settings'><font size=1>System Reference</font></a></td>
	</tr>

<?php 
}
?>

<tr bgcolor="#EEEEEE">
<td><a href=?part=4&itemID_=921&busItem=Reports&permit_type=Reports>Reports</a></td>
</tr>

<tr bgcolor="#EEEEEE">
<td><a href=?part=4&selMode=ebpls_nbusiness>Preferences</a></td>
</tr>
<!--
<tr bgcolor="#EEEEEE">
<td><a href=?part=2>Logout</a></td>
</tr>
-->
<?php
}
?>
<tr>
<td align=left><br><br></td>
</tr>

<tr>
<td align=left><b><i>Food for thought</i><b></td>
</tr>

<tr>
<td align=center valign=top><!--<?php include'includes/quotes-inc.php' ?>--></td>
</tr>

</table>
</td>
<td width=80% valign=top >
<?php
//include'includes/bodycontent-inc.php';
?>
</td>
<!--<td width=20% align=center valign=top><?php include'includes/quotes-inc.php' ?></td>-->
</tr>
</table>

</form>

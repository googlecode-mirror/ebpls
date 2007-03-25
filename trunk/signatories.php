<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
if ($orderbyasde==1) {
	$orderbyasde=0;
$ascdesc='asc';
} else {
	$orderbyasde=1;
	$ascdesc='desc';
}

if ($bcode=='') {
        $bcode=0;
}

if ($sb=='SAVE' and $Signatory<>'' and $Position<>'' and $Office<>'') {
$gt = mysql_query ("select * from global_sign where sign_id = $bcode") or die ("select * from global_sign where sign_id = $bcode");
$gf1 = mysql_num_rows($gt);
$Signatory = addslashes($Signatory);
$Position = addslashes($Position);
$Office = addslashes($Office);
	if ($gf1==0) {
		
	 $r = mysql_query("insert into global_sign (gs_name, gs_pos, gs_office)
                values ('$Signatory', '$Position', '$Office')") or die ("die".mysql_error());
	} else {
        $r = mysql_query ("update global_sign set gs_name='$Signatory', gs_pos='$Position', gs_office='$Office'
                        where sign_id=$bcode") or die ("fD");
	$bcode='0';
	}
}elseif ($com=='delete') {
   $r = mysql_query("delete from global_sign where sign_id=$bcode") or die ("ff");
}


if ($Signatory=='' || $Position=='' || $Office=='' and $sb<>'') {
?>
        <body onload='alert("Please Fill Up Data Required");'></body>
<?php
}

                                                                                                 
$gt = mysql_query ("select * from global_sign where sign_id = $bcode") or die ("select * from global_sign where sign_id = $bcode");
$gf = mysql_fetch_row($gt);
$name1=stripslashes($gf[1]);
$posi=stripslashes($gf[2]);
$office=stripslashes($gf[3]);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content="DAP-KSDO">
	
<title></title>
</head>
<body>

<form name=_FRM action="index.php?part=4&class_type=Preference&selMode=ebpls_nsign&action_=8&itemEvent=1&data_item=0&orderbyasde=1" ENCTYPE="multipart/form-data" method="post">

<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=pref_type value="<?php echo $pref_type;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=itemEvent value="<?php echo $itemEvent;?>">

<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr>
<td colspan=2 align=center><br>
<?php
$data_item=0;
include'tablemenu-inc.php';
?>
</td>
</tr>


<tr><td colspan=2 class=header2 align=center width=100%>SIGNATORIES</td></tr>
<tr><td colspan=2 ><br></td></tr>

		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Name : </td>
			<td align=left valign=top> &nbsp <input type=text  name=Signatory class=text180 value='<?php echo $name1; ?>' maxlength="30">&nbsp;<font class="def_label"> (Max 30 chars)</font></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Position : </td>
			<td align=left valign=top> &nbsp <input type=text name=Position class=text180 value='<?php echo $posi; ?>' maxlength="30">&nbsp;<font class="def_label"> (Max 30 chars)</font></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Office : </td>
			<td align=left valign=top> &nbsp <input type=text name=Office class=text180 value='<?php echo $office; ?>' maxlength="30">&nbsp;<font class="def_label"> (Max 30 chars)</font></td>
			<td> &nbsp </td>
		</tr>
	</table>
	<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr width=90%>
			<td align=center valign=top>
			&nbsp</td>
		</tr>
		<tr width=90%>
			<td align=center valign=top>
			<input type=submit value=SAVE name=sb>
			<input type=Button value=CANCEL onClick:'javascript:window.back;'>
			&nbsp<br><br></td>
		</tr>
	</table>
</form>
	
		<?php
		$result=mysql_query("select * from global_sign") or die("SELECT Error: ".mysql_error());
		$totalcnt = mysql_num_rows($result);
		
if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
                                                                                                               
                                                                                                               
// Define the number of results per page
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$fromr = abs((($pager * $max_resultsr) - $max_resultsr));
                                                                                                               
if ($ordervalue=='name') {                                                                                                               
$searchsqlr="select * from global_sign order by gs_name $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='pos') {
	$searchsqlr="select * from global_sign order by gs_pos $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='off') {
	$searchsqlr="select * from global_sign order by gs_office $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from global_sign order by gs_name $ascdesc limit $fromr, $max_resultsr";
}
$cntsqlr = "select count(*) from global_sign";

//		
$resultr = mysql_query($searchsqlr)or die (mysql_error());
                                                                                                               
                                                                                                               
 // Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
                                                                                                              
echo "<table border=0 width=100%><tr><td><div align=left><br />";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1><< Prev&nbsp;";
                        }
                                                                                                               
                                                                                                               
                        for($i = 1; $i <= $total_pagesr; $i++){
                        if(($pager) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                        }
                        }
                                                                                                               
                                                                                                               
                                                                                                               
               // Build Next Link
                                                                                                               
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next>></a>";
                        }
                                                                                                               
                                                                                                               
//echo "</td></tr></table>";

//

		if ($totalcnt==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }
print "<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr width=90% bgcolor='#EEEEEE' height=20>
			<td bgcolor='#E6FFE6' class=normal> &nbsp;No.</td>
			<td bgcolor='#E6FFE6' class=normal> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nsign&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=name>Name</a></td>
			<td bgcolor='#E6FFE6'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nsign&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=pos>Position</a></td>
			<td bgcolor='#E6FFE6'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nsign&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=off>Office</a></td>
			<td bgcolor='#E6FFE6'> &nbsp;Action</td>
		</tr><br>
		<tr width=90%>";
                $norow=0;
                while ($get_info = mysql_fetch_row($resultr)){
	                $norow++;
                include'tablecolor-inc.php';
				print "<tr bgcolor='$varcolor'>\n";
                //foreach ($get_info as $field )
print "<td>&nbsp;$norow&nbsp</td>\n";                                                                                                                                                                                                                                                                     
print "<td>&nbsp;$get_info[1]&nbsp</td>\n";
print "<td>&nbsp;$get_info[2]&nbsp</td>\n";
print "<td>&nbsp;$get_info[3]&nbsp</td>\n";
print "<td align=center>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_info[0]' class='subnavwhite'>Edit</a> |";
?>
<a href='#' class='subnavwhite' onclick='confdel(<?php echo $get_info[0]; ?>);'>Delete</a>
</td>
<?php
}
?>
<script language="Javascript">
function confdel(cc)
{
         var _FRM = document._FRM;
        doyou = confirm("Record Will Be Deleted, Continue?");
                                                                                                 
                                                                                                 
        if (doyou==true) {
	parent.location='index.php?part=4&class_type=Preference&selMode=<?php echo $selMode; ?>&action_=8&itemEvent=1&data_item=0&com=delete&bcode=' + cc;
        }
        //        _FRM.submit();
               return true;
}
</script>

		</tr>
	</table>

<tr>
<td colspan=2 align=center><br>

</td>
</tr>

</table>
<?php 
echo "<table border=0 width=100%><tr><td align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1><< Prev&nbsp;";
                        }
                                                                                                                                                            
                                                                                                                                                            
                        for($i = 1; $i <= $total_pagesr; $i++){
                        if(($pager) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                        }
                        }
                                                                                                                                                            
                                                                                                                                                            
                                                                                                                                                            
               // Build Next Link
                                                                                                                                                            
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next>></a>";
                        }
                                                                                                                                                            
                                                                                                                                                            
echo "</td></tr></table>";

$data_item=1;
include'tablemenu-inc.php';
?>

</form>
</body>
</html>

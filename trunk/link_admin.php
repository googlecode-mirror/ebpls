<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';

$bbo = isset($bbo) ? $bbo : ''; //2008.05.12  Define undefined variables
$orderbyasde = isset($orderbyasde) ? $orderbyasde : '';
$link = isset($link) ? $link : '';
$desc = isset($desc) ? $desc : '';
$sb = isset($sb) ? $sb : '';
$confx = isset($confx) ? $confx : '';
$itemEvent = isset($itemEvent) ? $itemEvent : '';
$ordervalue = isset($ordervalue) ? $ordervalue : '';

$bcode = $bbo;
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
$link = addslashes($link);
$desc = addslashes($desc);

if ($sb=='SAVE' and $link<>'' and $desc<>'') {
$gt = mysql_query ("select * from links where linkid = $bcode");
$gf1 = mysql_num_rows($gt);
	if ($gf1==0) {

	$gt = mysql_query("select * from links where link='$link'");
                $rt = mysql_num_rows($gt);
                if ($rt>0) {
                ?>
                 <body onload='javascript:alert ("Duplicate entry found");'></body>
                <?php
                } else {
	 $r = @mysql_query("insert into links values ('', '$link', '$desc',now() )");
	 ?>
			<body onload='javascript:alert ("Data is successfully added to the database!!");'></body>
			<?
		}
	} else {
		$gt = mysql_query("select * from links where link='$link' and linkid<>'$bbo'");
                $rt = mysql_num_rows($gt);
                if ($rt>0) {
                ?>
                 <body onload='javascript:alert ("Duplicate entry found");'></body>
                <?php
                } else {

        $r = @mysql_query ("update links  set link='$link', 
				link_desc='$desc', date=now()
                        where linkid=$bcode");
?>
		<body onload='javascript:alert ("Record Successfully Updated!!");'></body>
		<?
	$bcode='0';
		}
	}
}elseif ($confx=='1') {
   $r = mysql_query("delete from links where linkid=$bcode") or die ("ff");
   ?>
	<body onload='javascript:alert ("Record Deleted!!");'></body>
	<?
}

                                                                                                 
$gt = mysql_query ("select * from links where linkid = $bcode");
$gf = mysql_fetch_row($gt);
$link=$gf[1];
$desc=$gf[2];
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
<body onLoad="javascript: _FRM.link.focus();">
<form name=_FRM action="index.php?part=4&class_type=Preference&selMode=link&itemEvent=1&data_item=0&orderbyasde=1" ENCTYPE="multipart/form-data" method="post">

<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=pref_type value="<?php echo $pref_type;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=itemEvent value="<?php echo $itemEvent;?>">
<input type=hidden name=bbo value="<?php echo $bcode;?>">
<input type=hidden name=confx>

<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>
<tr><td colspan=2 class=header2 align=center width=100%>Link Admin</td></tr>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Link : </td>
			<td align=left valign=top> &nbsp <input type=text maxlength=255 name=link class=text180 value='<?php echo $link; ?>' maxlength="30">&nbsp;<font class="def_label"> (Max 30 chars)</font></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Description : </td>
			<td align=left valign=top> &nbsp <input type=text maxlength=255 name=desc class=text180 value='<?php echo $desc; ?>' maxlength="30">&nbsp;<font class="def_label"> (Max 30 chars)</font></td>
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
			<input type=hidden name=sb>
                        <input type=button value=Save name=sib onclick='VerifyLink();'>
                        <input type=Button value=Cancel onClick='CancelCat();'>
                        <input type=reset value=Reset>
		</td>
		</tr>
	</table>
</form>
	
<?php
$result=mysql_query("select * from links") or die("SELECT Error: ".mysql_error());
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
                                                                                                               
if ($ordervalue=='link') {                                                                                                               
$searchsqlr="select * from links order by link $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='desc') {
	$searchsqlr="select * from links order by link_desc $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='date') {
	$searchsqlr="select * from links order by date $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from links order by linkid $ascdesc limit $fromr, $max_resultsr";
}
$cntsqlr = "select count(*) from links";

//		
$resultr = mysql_query($searchsqlr)or die (mysql_error());
                                                                                                               
                                                                                                               
 // Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
                                                                                                              
echo "<table border=0 width=100%><tr><td><div align=left>";
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

//

		if ($totalcnt==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }
print "<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
		<trbgcolor='#EEEEEE' height=20>
			<td bgcolor='#E6FFE6' class=normal> &nbsp;No.</td>
			<td bgcolor='#E6FFE6' class=normal> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_link&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=link>Link</a></td>
			<td bgcolor='#E6FFE6'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_link&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=desc>Description</a></td>";

//			<td bgcolor='#E6FFE6'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_link&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=date>Last Updated</a></td>
print "	<td bgcolor='#E6FFE6'> &nbsp;Action</td>
	</tr><br>
	<tr>";
$norow=0;
while ($get_info = mysql_fetch_row($resultr)){
	$norow++;
	include'tablecolor-inc.php';
	print "<tr bgcolor='$varcolor'>\n";
                //foreach ($get_info as $field )
	print "<td>&nbsp;$norow&nbsp</td>\n";                                                                                                                                                                                                                                                                     
	$getm = "http://".$get_info[1];

?>
<td>&nbsp;<a href='#' 'subnavwhite' onclick='window.open("<?php echo $getm; ?>");'><?php echo $get_info[1]; ?> </a>&nbsp</td>
<?php
	print "<td>&nbsp;$get_info[2]&nbsp</td>\n";
//print "<td>&nbsp;$get_info[3]&nbsp</td>\n";
	print "<td align=center>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bbo=$get_info[0]' class='subnavwhite'>Edit</a>
 |";
?>
<a href='#' class='subnavwhite' onclick='confdel(<?php echo $get_info[0]; ?>);'>Delete</a>
</td>
<?php
}
?>
</script>
		</tr>
	</table>
<!--
<tr>
<td colspan=2 align=center><br>

</td>
</tr>
</table>-->
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

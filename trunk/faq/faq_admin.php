<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
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
$faq_question = addslashes($faq_question);
$faq_answer = addslashes($faq_answer);

if ($sb=='SAVE' and $faq_question<>'' and $faq_answer<>'') {
$gt = mysql_query ("select * from faq where faqid = $bcode");
$gf1 = mysql_num_rows($gt);
	if ($gf1==0) {

		$gt = mysql_query("select * from faq where faq_question='$faq_question'");
		$rt = mysql_num_rows($gt);
		if ($rt>0) {
		?>
		 <body onload='javascript:alert ("Duplicate entry found");'></body>
                <?php
		} else {
	 	$r = mysql_query("insert into faq values ('', '$faq_question', '$faq_answer',now() )");
		 ?>
                <body onload='javascript:alert ("Data is successfully added to the database.");'></body>
                <?php
		}
	} else {

	 $gt = mysql_query("select * from faq where faq_question='$faq_question' and faqid<>'$bcode'");
                $rt = mysql_num_rows($gt);
                if ($rt>0) {
                ?>
                 <body onload='javascript:alert ("Duplicate entry found");'></body>
                <?php
		} else {
		        $r = mysql_query ("update faq  set faq_question='$faq_question', 
				faq_answer='$faq_answer', last_updated=now()
                        where faqid=$bcode") or die ("fD");
		$bcode='0';
		?>
                <body onload='javascript:alert ("Record Successfully Updated");'></body>
                <?php
		}
	}
}elseif ($confx=='1') {
   $r = mysql_query("delete from faq where faqid=$bcode") or die ("ff");
   ?>
	<body onload='javascript:alert ("Record Deleted!!");'></body>
	<?
}

                                                                                                 
$gt = mysql_query ("select * from faq where faqid = $bcode");
$gf = mysql_fetch_row($gt);
$faq_question=$gf[1];
$faq_answer=$gf[2];
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
<body onLoad="javascript: _FRM.faq_question.focus();">

<form name=_FRM action="index.php?part=4&class_type=Preference&selMode=ebpls_faq&is_desc=ASC" ENCTYPE="multipart/form-data" method="post">

<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=pref_type value="<?php echo $pref_type;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=bbo value="<?php echo $bcode;?>">
<input type=hidden name=confx>
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>
<tr><td colspan=2 class=header2 align=center width=100%>FAQ Admin</td></tr>
<tr><td colspan=2 ><br></td></tr>

		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Question : </td>
			<td align=left valign=top> &nbsp <input type=text name=faq_question class=text180 value='<?php echo $faq_question; ?>' maxlength="30">&nbsp;<font class="def_label"> (Max 30 chars)</font></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> Answer : </td>
			<td align=left valign=top> &nbsp <input type=text name=faq_answer class=text180 value='<?php echo $faq_answer; ?>' maxlength="30">&nbsp;<font class="def_label"> (Max 30 chars)</font></td>
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
                        <input type=button value=Save name=sib onclick='VerifyFAQ();'>
                        <input type=Button value=Cancel onClick='CancelCat();'>
			<input type=reset value=Reset>
			&nbsp<br><br></td>
		</tr>
	</table>
</form>
	
		<?php
		$result=mysql_query("select * from faq") or die("SELECT Error: ".mysql_error());
		$totalcnt = mysql_num_rows($result);
		
if(!isset($_GET['page'])){
    $pager = 1;
} else {
    $pager = $_GET['page'];
}
if ($is_desc == "") {
        $is_desc = $is_asc;
} else {
        if ($is_desc=='DESC') {
                $is_desc='ASC';
        } else {
                $is_desc='DESC';
        }
}
$is_asc = $is_desc;
// Define the number of results per page
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
// Figure out the limit for the query based
// on the current page number.
$fromr = abs((($pager * $max_resultsr) - $max_resultsr));
                                                                                                               
if ($ordervalue=='faq_question') {                                                                                                               
$searchsqlr="select * from faq order by faq_question $is_desc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='faq_answer') {
	$searchsqlr="select * from faq order by faq_answer $is_desc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='date') {
	$searchsqlr="select * from faq order by last_updated $is_desc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from faq order by faqid $is_desc limit $fromr, $max_resultsr";
}
$cntsqlr = "select count(*) from faq";

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
                       // print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }
print "<table align=center border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr width=90% height=20>
			<td class='hdr'> &nbsp;No.</td>
			<td class='hdr'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_faq&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=faq_question>Question</a></td>
			<td class='hdr'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_faq&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=faq_answer>Answer</a></td>
			<td class='hdr'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_faq&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=last_updated>Last Updated</a></td>
			<td class='hdr'> &nbsp;Action</td>
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
print "<td align=center>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bbo=$get_info[0]' class='subnavwhite'>Edit</a>
 |";
?>
<a href='#' class='subnavwhite' onclick='confdel(<?php echo $get_info[0]; ?>);'>Delete</a>
</td>
<?php
}
?>
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

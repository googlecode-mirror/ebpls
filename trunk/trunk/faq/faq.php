<?php
require'includes/variables.php';
include'lib/phpFunctions-inc.php';
?>
<html>
<head>
	<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content="DAP-KSDO">
	
<title></title>
</head>
<body>
<form name=_FRM action="index.php?part=4selMode=FAQS" ENCTYPE="multipart/form-data" method="post">
<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header2 align=center width=100%>Frequently Asked Questions</td></tr>
<tr><td colspan=2 ><br></td></tr>

		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
<?php
    $result=mysql_query("select * from faq") or die("SELECT Error: ".mysql_error());
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
        $searchsqlr="select * from faq order by faqid $ascdesc limit $fromr, $max_resultsr";
$cntsqlr = "select count(*) from faq";
//
$resultr = mysql_query($searchsqlr)or die (mysql_error());
 // Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
echo "<table border=0 width=100%><tr><td><div align=left>";
 if($pager > 1){
                        $prevr = ($pager - 1);
                        echo "<a href=$PHP_SELF?part=4&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$prevr&ascdesc1=$ascdesc1><< Prev&nbsp;";
                        }
                        for($i = 1; $i <= $total_pagesr; $i++){
                        if(($pager) == $i){
                                echo "Page $i&nbsp;";
                        } else {
                        echo "<a href=$PHP_SELF?part=4&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$i&ascdesc1=$ascdesc1>$i</a>&nbsp;";
                        }
                        }
               // Build Next Link
                        if($pager < $total_pagesr){
                        $nextr = ($pager + 1);
                        echo "<a href=$PHP_SELF?part=4&selMode=$selMode&action_=8&itemEvent=1&data_item=0&page=$nextr&ascdesc1=$ascdesc1>Next>></a>";
                        }
                if ($totalcnt==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }
                $norow=0;
?>
	</td>
	</tr>
	<tr><td><br></td></tr>
<?php
                while ($get_info = mysql_fetch_row($resultr)){
                        $norow++;
                include'tablecolor-inc.php';
                print "<tr bgcolor='$varcolor'>\n";
?>
                <td align=left valign=top width=10%>Question: </td>
		<td>
		<?php echo stripslashes($get_info[1]); ?>
</td>
		</tr>
<?php
		include'tablecolor-inc.php';
		print "<tr bgcolor='$varcolor'>\n";
?>
                <td align=left valign=top>Answer:</td>
		<td>
		<?php echo stripslashes($get_info[2]); ?>
</td>
                </tr>
<?php
		}
?>
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
</table>
</form>
</body>
</html>

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
if ($ascdesc1=='') {
	$ascdesc1=$ascdesc;
} else {
	$ascdesc=$ascdesc1;
}

if ($bcode=='') {
        $bcode=0;
}

if ($sb=='Submit') {
$gt = mysql_query ("select * from report_signatories where rs_id = $bcode") or die ("select * from report_signatories where rs_id = $bcode");
$gf1 = mysql_num_rows($gt);
	if ($gf1==0) {
	 $r = mysql_query("insert into report_signatories values ('', '$report_file', $sign_id,now(),'$usern', '$sign_type')") or die ("die".mysql_error());
	} else {
        $r = mysql_query ("update report_signatories set report_file='$report_file', sign_id=$sign_id, date_updated=now(),updated_by='$usern', sign_type='$sign_type'
                        where rs_id=$bcode") or die ("fD");
	$bcode='0';
	}
}elseif ($com=='delete') {
   $r = mysql_query("delete from report_signatories where rs_id=$bcode") or die ("ff");
}
                                                                                                 
$gt = mysql_query ("select * from report_signatories where rs_id = $bcode") or die ("select * from report_signatories where rs_id = $bcode");
$gf = mysql_fetch_row($gt);
$report_file1=$gf[1];
$sign_id1=$gf[2];
$sign_type1=$gf[5];
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<link rel="stylesheet" href="stylesheets/default.css" type="text/css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content=" FourJ's ">
	
<title></title>
</head>
<body>

<form name=_FRM action="index.php?part=4&class_type=Preference&selMode=ebpls_nreportsign&action_=8&itemEvent=1&data_item=0&orderbyasde=1" ENCTYPE="multipart/form-data" method="post">

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


<tr><td colspan=2 class=header2 align=center width=100%>SIGNATORY TEMPLATE</td></tr>
<tr><td colspan=2 ><br></td></tr>

		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> REPORT TYPE : </td>
			<td align=left valign=top>&nbsp;
			<?php
                                                                //where report_desc like '%business%' or report_desc like 'business%'
                                                                //$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error());
                                                                $result= mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error());
                                                        //$reports=mysql_fetch_row($result);
                                                        print "<select name='report_file'>";
                                                                while ($reports=mysql_fetch_row($result)) {
								if ($reports[0]==$report_file1) {
									$shek='SELECTED';
								} else {
									$shek='';
								}	
                                                                        print"<option value='$reports[0]' $shek>$reports[0]</option>";
                                                                        }
                                                                print"</select>";
                                                        //echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
                                                ?>
			 <!--&nbsp <input type=text maxlength=255 name=report_file class=text180 value='<?php echo $report_file; ?>'>--></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
			<td align=right valign=top> SIGNATORIES : </td>
			<td align=left valign=top> &nbsp 
<?php
                                                                //where report_desc like '%business%' or report_desc like 'business%'
                                                                //$result = mysql_query("select report_desc, report_file from ebpls_reports order by report_desc asc") or die(mysql_error());
                                                                $result= mysql_query("select sign_id,gs_name from global_sign") or die(mysql_error());
                                                        //$reports=mysql_fetch_row($result);
                                                        print "<select name='sign_id'>";
                                                                while ($reports=mysql_fetch_row($result)) {
								if ($reports[0]==$sign_id1) {                                                                        $shek='SELECTED';
                                                                } else {
                                                                    $shek='';
                                                                }
                                                                        print"<option value=$reports[0] $shek>$reports[1]</option>";
                                                                        }
                                                                print"</select>";
                                                        //echo get_select_data($dbLink,'report_list','ebpls_reports','report_file','report_desc',$owner_datarow[report_id]);
                                                ?>
<!--<input type=text maxlength=255 name=sign_id class=text180 value='<?php echo $sign_id; ?>'>--></td>
			<td> &nbsp </td>
		</tr>
		<tr width=90%>
                        <td align=right valign=top> SIGNATORY TYPE : </td>
                        <td align=left valign=top> &nbsp <select name='sign_type'>";
				<?php 
				if ($reports[0]==$sign_type1) {                                                                        $shek='SELECTED';
                                } else {
                                        $shek='';
                                }
				?>
                                <option value=1 <?php if (1==$sign_type1) {
                                      $shek='SELECTED';
                                } else {
                                        $shek='';
                                }
				echo $shek;?>>Approval</option>
				<option value=2 <?php if (2==$sign_type1) {
                                      $shek='SELECTED';
                                } else {
                                        $shek='';
                                }
				echo $shek;?>>Recommendation</option>
				<option value=3 <?php if (3==$sign_type1) {
                                      $shek='SELECTED';
                                } else {
                                        $shek='';
                                }
				echo $shek;?>>Note</option>
				</select>
			</td>
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
			<input type=submit value=Submit name=sb>
			<input type=Button value=Cancel onClick='history.go(-1);'>
			<input type=Reset value=Reset>
			&nbsp<br><br></td>
		</tr>
	</table>
</form>
	
		<?php
		$result=mysql_query("select * from report_signatories") or die("SELECT Error: ".mysql_error());
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
$searchsqlr="select * from report_signatories order by report_file $ascdesc limit $fromr, $max_resultsr";
} elseif ($ordervalue=='pos') {
	$searchsqlr="select * from report_signatories order by sign_id $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from report_signatories order by report_file $ascdesc limit $fromr, $max_resultsr";
}
$cntsqlr = "select count(*) from report_signatories";

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
			<td bgcolor='#E6FFE6' class=normal> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nreportsign&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=name>Report Type</a></td>
			<td bgcolor='#E6FFE6'> &nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nreportsign&action_=8&itemEvent=1&data_item=0&orderbyasde=$orderbyasde&ordervalue=pos>Signatories</a></td>
			<td bgcolor='#E6FFE6'> &nbsp;Signatory Type</td>
			<td bgcolor='#E6FFE6'> &nbsp;Action</td>
		</tr><br>
		<tr width=90%>";
		require 'setup/setting.php';
                $max_resultsr = $thIntPageLimit;
		$pagemulti = $page;
                                                                                                 
		if ($pagemulti=='') {
        		$pagemulti=1;
		}
                                                                                                 
		$norow=($pagemulti*$max_resultsr)-$max_resultsr;
		while ($get_info = mysql_fetch_row($resultr)){
	                $norow++;
                include'tablecolor-inc.php';
				print "<tr bgcolor='$varcolor'>\n";
                //foreach ($get_info as $field )
print "<td>&nbsp;$norow&nbsp</td>\n";                                                                                                                                                                                                                                                                     
print "<td>&nbsp;$get_info[1]&nbsp</td>\n";
$getsign = mysql_query("select gs_name from global_sign where sign_id=$get_info[2]");
$getsign = mysql_fetch_row($getsign);
print "<td>&nbsp;$getsign[0]&nbsp</td>\n";
if ($get_info[5]==1) {
	$angelfire='Approval';
} elseif ($get_info[5]==2) {
	$angelfire='Recommendation';
} else {
	$angelfire='Note';
}
print "<td>&nbsp;$angelfire&nbsp</td>\n";
print "<td align=center>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_info[0]' class='subnavwhite'>Edit</a>&nbsp;|&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=delete&bcode=$get_info[0]' class='subnavwhite'>Delete</a>
</td>\n";

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

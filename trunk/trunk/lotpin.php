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

if ($Submit=='Submit') {		
if ($permit_date<>'') {
	$permit_date1=1;
} else {
	$permit_date1=0;
}

//echo $permit_type."<br>";
$bname1 =  mysql_query("select * from lot_pin where lotpin='$lotpin'") 
		or die ("**".mysql_error());
$bc1 = mysql_num_rows($bname1);
$bc2 = mysql_fetch_row($bname1);
	if ($bc1==0) {
	 $newlotpin = mysql_query("insert into lot_pin
         		values ('', '$lotpin','$biz_id',now(),'$usern','$parcel')") 
			or die("Arg ".mysql_error());
	$bcode=0;
	$datarow[10]='';
	$datarow[7]='';
	$parcel='';
	$lotpin='';
	
	} else {
		$chkduplicate = mysql_query("select * from lot_pin where lotpin='$lotpin' 
		and pin_id<>$bcode") or die("ddd".mysql_error());
		$chkduplicate = mysql_num_rows($chkduplicate);
		//echo $permit_type."=".$bc2[1];
		if ($chkduplicate<>0 and $lotpin==$bc2[1]) {
			echo "<font color=red size=2><b><i>DUPLICATE RECORD</i></b></font>";
		} else {
		$updatetableindividual = mysql_query("update lot_pin
	         set lotpin='$lotpin', user_add='$usern',parcel='$parcel',
		date_add=now(), business_id=$biz_id where pin_id=$bcode") 
		or die("Uh Ah ".mysql_error());
     	}
    }
}
if ($confx=='1') {
	$bname1 =  mysql_query("delete from lot_pin where pin_id=$bcode") or die ("**".mysql_error());
	$confx='';
?>
	<body onload='javascript:alert("Record Deleted");'></body>
<?php
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>eBPLS Business Permit and Licensing System</title>
	<meta name="Author" content="PAARV">
	<link href="stylesheets/default.css" rel="stylesheet" type="text/css">
<title></title>
</head>
<body onLoad="javascript: _FRM.business_district_code.focus();">
<form ENCTYPE="multipart/form-data" method="post" name="_FRM">
<input type=hidden name=part value="<?php echo $part;?>">
<input type=hidden name=class_type value="<?php echo $class_type;?>">
<input type=hidden name=selMode value="<?php echo $selMode;?>">
<input type=hidden name=action_ value="<?php echo $action_;?>">
<input type=hidden name=itemEvent value="<?php echo $itemEvent;?>">
<input type=hidden name=pro value="<?php echo $pro;?>">

<table border=0 width=100% align=center cellspacing=0 cellpadding=0>
<tr><td colspan=2 class=header align=center width=100%>REFERENCES</td></tr>
<tr>
        <td colspan=2 align=center>
</td>
</tr>
<?php
if ($bcode<>'') {
	$getinfo1 = mysql_query("select * from lot_pin where pin_id=$bcode") 
	or die("Uh Ah ".mysql_error());
	$getinfo1 = mysql_fetch_row($getinfo1);
}

$getprovince = mysql_query("select a.province_desc, a.blgf_code from
		ebpls_province a, ebpls_buss_preference b where
		a.province_code=b.lguprovince");
$getprov = mysql_fetch_row($getprovince);

$getcity = mysql_query("select a.city_municipality_desc, a.blgf_code, 
		a.city_municipality_code from
                ebpls_city_municipality a, ebpls_buss_preference b where
                a.city_municipality_code=b.lguname");
$getcity = mysql_fetch_row($getcity);



if ($business_barangay_code<>'' and $bcode>0) {
//       $business_barangay_code='0';
$getbrgy = mysql_query("select * from ebpls_barangay
                where barangay_code = '$business_barangay_code'");
$getbrgy = mysql_fetch_row($getbrgy);


}

$getdist = mysql_query("select * from ebpls_district 
		where district_code = '$business_district_code'");
$getdist = mysql_fetch_row($getdist);
/*
$getbrgy = mysql_query("select * from ebpls_barangay
                where barangay_code = '$business_barangay_code'");
$getbrgy = mysql_fetch_row($getbrgy);
*/
?>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
                                                                                                 
function flagchange(x)
{
        x.value = 1;
}

function confdel(cc)
{
         var _FRM = document._FRM;
        doyou = confirm("Record Will Be Deleted, Continue?");
                                                                                                 
        if (doyou==true) {
                _FRM.bcode.value = cc;
               _FRM.confx.value = 1;
        } else {
                _FRM.confx.value=0;
                return false;
        }
              _FRM.submit();
               return true;
}


</script>
<input type=hidden name=bcode value="<?php echo $bcode;?>">
<input type=hidden name=confx value="<?php echo $confx;?>">
<input type=hidden name=changeondin value=<?php echo $changeondin; ?>>
<input type=hidden name=prov_blgf value='<?php echo $getprov[1]; ?>'>
<input type=hidden name=city_blgf value='<?php echo $getcity[1]; ?>'>
<input type=hidden name=dist_blgf value='<?php echo $getdist[7]; ?>'>
<input type=hidden name=brgy_blgf value='<?php echo $getbrgy[7]; ?>'>
<?php
$lotpin = $getprov[1]."-".$getcity[1]."-".$getdist[7]."-".$getbrgy[7]."-";
?>

		<tr width=100%>
			<td align=center colspan=2 class=header2> Lot PIN </td>
		</tr>
		<tr><td colspan=2 ><br></td></tr>
		<tr><td colspan=2 ><br></td></tr>

		<tr width=100%>
			<td align=right valign=top> Province : </td>
			<td align=left valign=top> &nbsp;<?php echo $getprov[0]; ?></td> 
			<td> &nbsp </td>
		</tr>
		<tr width=100%>
			<td align=right valign=top> Municipality : </td>
			<td align=left valign=top> &nbsp;<?php echo $getcity[0]; ?></td> 
			<td> &nbsp </td>
		</tr>
		<tr width=100%>
			<td align=right valign=top> District : </td>
			 <td align="left" valign="top" class='normal'>&nbsp; 
<?php
                                                                                                 
if ($business_district_code<>$datarow[10] and $business_district_code<>'') {
        $datarow[10] = $business_district_code;
} else {
        $business_district_code=$datarow[10];
}
                                                                                                 
                                                                                                 
echo get_select_dist($dbLink,'business_district_code','ebpls_district','district_code','district_desc',$datarow[10],$getcity[2]);
?></td>

			<td> &nbsp </td>
		</tr>
		<tr width=100%>
			<td align=right valign=top> Barangay : </td>
			<td align=left valign=top> &nbsp 
  <?php
                                                                                                 
if ($business_barangay_code<>$datarow[7] and $business_barangay_code<>'') {
        $datarow[7] = $business_barangay_code;
} else {
        $business_barangay_code=$datarow[7];
}
                                                                                                 
                                                                                                 
echo get_select_barg($dbLink,'business_barangay_code','ebpls_barangay','barangay_code','barangay_desc',$datarow[7],$business_district_code);
?>
</td>
			<td> &nbsp </td>
		</tr>
  <tr width=100%>
                        <td align=right valign=top> Building Name : </td>
                        <td align=left valign=top> &nbsp

	<select name=biz_id class=select2000>
<?php
$geto = mysql_query("select * from ebpls_business_enterprise 
			where business_building_name<>''");

	while ($geti = mysql_fetch_array($geto))
		{
?>
	<option value=<?php echo $geti[business_id]; ?>>
	<?php echo $geti[business_building_name]."-".$geti[business_name]; ?>
	</option>
<?php
		}
?>
</select>

</td>
                        <td> &nbsp </td>
                </tr>

<tr width=100%>
                        <td align=right valign=top> Parcel Number : </td>
                        <td align=left valign=top> &nbsp
<input type=text name=parcel value='<?php echo $parcel; ?>' onchange='javascript:changelotpin();'>  
</td>
                        <td> &nbsp </td>
</tr>

<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
                                                                                                 
                                                                                                 
function changelotpin()
{
         var _FRM = document._FRM;
	_FRM.lotpin.value = _FRM.lotpin.value + _FRM.parcel.value;



}
</script>
<tr width=100%>
                        <td align=right valign=top> Lot Pin : </td>
                        <td align=left valign=top> &nbsp
<input type=text readonly name=lotpin value=<?php echo $lotpin; ?>>
</td>
                        <td> &nbsp </td>
</tr>



</table>
<table align=center border=0 cellspacing=0 cellpadding=0 width=90%>
	<tr width=100%>
		<td align=center valign=top>
			&nbsp</td>
	</tr>
	<tr width=100%>
		<td align=center valign=top>
			<input type=submit value='Submit' name=Submit>
			<input type=Button value=Cancel onClick='history.go(-1)'>
			<input type=Reset value=Reset>
			&nbsp<br><br></td>
	</tr>
</table>
 <table align=center border=0 cellspacing=0 cellpadding=0 width=100%>

<?php
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
                                                                                                               
if ($ordervalue=='type') {                                                                                                               
	$searchsqlr="select * from lot_pin order by lotpin $ascdesc limit $fromr, $max_resultsr";
} else {
	$searchsqlr="select * from lot_pin order by lotpin $ascdesc limit $fromr, $max_resultsr";
}
$cntsqlr = "select count(*) from lot_pin";

		$result=mysql_query("select * from lot_pin") or die("11".mysql_error());
		$totalcnt = mysql_num_rows($result);
		if ($totalcnt==0) {
                        print "<br><font color=red><div align= center>&nbsp No record found&nbsp</div></font>\n";
                }
$resultr = mysql_query($searchsqlr)or die ("dasdas".mysql_error());
                                                                                                               
// Figure out the total number of results in DB:
$total_resultsr = mysql_result(mysql_query($cntsqlr),0);
// Figure out the total number of pages. Always round up using ceil()
$total_pagesr = ceil($total_resultsr / $max_resultsr);
                                                                                                               
                                                                                                               
echo "<tr><td align=left>";
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
                        
?>
                                                                                                 
</td></tr>
<tr>
<td class='hdr'>&nbsp;No.</td>
<td class='hdr'>&nbsp;<a href=index.php?part=4&class_type=Preference&selMode=ebpls_nlotpin&action_=8&itemEvent=1&data_item=0&orderbyasde=<?php echo $orderbyasde;?>&ordervalue=type>Lot Pin</a>&nbsp</td>
<td class='hdr'>&nbsp;Action</td>
</tr>
<?php                                                                                       
                                                                                                               
require 'setup/setting.php';
$max_resultsr = $thIntPageLimit;
 $pagemulti = $page;
                         
if ($pagemulti=='') {
        $pagemulti=1;
}
 
$norow=($pagemulti*$max_resultsr)-$max_resultsr;                
while ($get_info = mysql_fetch_row($resultr)){
	                $norow++;
	                if ($get_info[5]==1) {
		                $yesorno='Yes';
	                } else {
		                $yesorno='No';
	                }
                include'tablecolor-inc.php';
				print "<tr bgcolor='$varcolor'>\n";
                //foreach ($get_info as $field )
print "<td>&nbsp;$norow&nbsp</td>\n"; 
print "<td>&nbsp;$get_info[1]&nbsp</td>\n";
//print "<td align=center width=20%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_info[0]' class='subnavwhite'>Edit</a> &nbsp;|";
?>
<td>
<a href='#'class='subnavwhite' onclick='javascript:confdel("<?php echo $get_info[0]; ?>")'>Delete</a></td>
<?php
//<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=delete&bcode=$get_info[0]' class='subnavwhite'>Delete</a>
//</td>\n";

}

echo "<tr><td align=left>";
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
echo "</td></tr>";
?>
		</tr>
	</table>


</form>
</body>
</html>

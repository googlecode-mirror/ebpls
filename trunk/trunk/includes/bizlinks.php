<?php
/* Prepares display of businesses (one line per business) when assessments requested.
Modification History:
2008.04.04 Add NOWRAP to permit#, Date and Action to improve readability by Ron Crabtree (RJC)
	Add alternating light/dark bands as in other screens by RJC
	Removed "Application" from Action hyperlinks to reduce wrapping to next line by RJC
*/

//foreach ($get_info as $field )
// display with alternating light then dark bands
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";

if ($permit_type=='Business') {
//print "<td>&nbsp;&nbsp</td>\n";
//print "<td>&nbsp;&nbsp</td>\n";
$a= ucfirst(stripslashes($get_info[2]));
$b= ucfirst(stripslashes($get_info[3]));
$c= ucfirst(stripslashes($get_info[4]));
print "<td NOWRAP>&nbsp;$a&nbsp;</td>\n";
print "<td>&nbsp;<a href='index.php?part=4&class_type=Permits&itemID_=1222&owner_id=$get_info[0]&com=Edit&permit_type=$tag&stat=$get_info[7]&business_id=$get_info[1]&busItem=Business&addbiz=update' class='subnavwhite'>$b</a>&nbsp</td>\n";
print "<td>&nbsp;<a href='index.php?part=4&class_type=Permits&itemID_=1224&business_id=$get_info[1]&owner_id=$get_info[0]&permit_type=$tag&stat=$get_info[7]&addbiz=update&busItem=Business' class='subnavwhite'>$c</a>&nbsp</td>\n";
//print "<td>&nbsp;<a href='index.php?part=4&class_type=Permits&itemID_=1222&owner_id=$get_info[0]&com=Edit&permit_type=$tag&stat=$get_info[4]&business_id=$get_info[1]&busItem=Business&addbiz=update' class=subnavwhite>$c</a>&nbsp</td>\n";
print "<td>&nbsp;$get_info[5]&nbsp</td>\n";
print "<td NOWRAP>&nbsp;$get_info[6]&nbsp</td>\n";
print "<td>&nbsp;$get_info[7]&nbsp</td>\n";
                                                                                                                                                                                                                                                                                                                                                                                                       
} else {
                                                                                                                                                                                                                                                                                                                                                                                                       
$a= ucfirst(stripslashes($get_info[1]));
$b= ucfirst(stripslashes($get_info[2]));
$c= ucfirst(stripslashes($get_info[3]));
print "<td NOWRAP>&nbsp;$a&nbsp</td>\n";
print "<td>&nbsp;$b&nbsp</td>\n";
print "<td>&nbsp;$c&nbsp</td>\n";
print "<td>&nbsp;$get_info[4]&nbsp</td>\n";
}
        if ($permit_type=='Motorized') {
$nhjyear = substr($c, 0, 4);
$nhjkyear = date('Y');
print "<td NOWRAP>
<a href='index.php?part=4&class_type=Permits&itemID_=1222&owner_id=$get_info[0]&com=Edit&permit_type=$tag&stat=$get_info[4]&busItem=Motorized' class=subnavwhite> Edit </a>&nbsp
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=$get_info[4]&permit_type=$tag&stat=$get_info[4]&busItem=Motorized' class=subnavwhite> ReAssess </a>&nbsp;";
if ($nhjyear < $nhjkyear) {
print "<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=ReNew&permit_type=$tag&stat=ReNew&busItem=Motorized&nghji=yuhsp' class=subnavwhite> ReNew </a>&nbsp";
}
print "<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=Drop&permit_type=$tag&stat=Transfer/Dropping&busItem=Motorized'  class=subnavwhite> Drop
</a></td>";
}
elseif ($permit_type=='Franchise') {
print "<td>
<a href='index.php?part=4&class_type=Permits&itemID_=1222&owner_id=$get_info[0]&com=Edit&permit_type=$tag&stat=$get_info[4]&busItem=Franchise'  class=subnavwhite>Edit </a>&nbsp
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=$get_info[4]&permit_type=$tag&stat=$get_info[4]&busItem=Franchise'  class=subnavwhite> ReAssess </a>&nbsp
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=ReNew&permit_type=$tag&stat=ReNew&busItem=Franchise ' class=subnavwhite> ReNew </a>&nbsp
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=Drop&permit_type=$tag&stat=Transfer/Dropping&busItem=Franchise'  class=subnavwhite> Drop</a></td>";
} elseif ($permit_type=='Business') {
        print "<td NOWRAP>
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=$get_info[7]&permit_type=$tag&stat=$get_info[7]&business_id=$get_info[1]&busItem=Business&addbiz=update&bizcom=Select' class=subnavwhite>Edit</a>&nbsp";

if (date('Y',strtotime($get_info[6]))<>date('Y') and $get_info[8]==1) {;

print "|
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=ReNew&permit_type=$tag&stat=ReNew&business_id=$get_info[1]&busItem=Business'  class=subnavwhite>ReNew</a>&nbsp";
}


if ($get_info[9]==1) {
print "|
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=Drop&permit_type=$tag&stat=Retire&business_id=$get_info[1]&busItem=Business'  class=subnavwhite>Retire</a>";
}
$disapp = isset($disapp) ? $disapp : 0 ;
if ($disapp==1) {
?>
|
<a href='#' class=subnavwhite onclick='ViewDisApp("<?php echo $get_info[0]; ?>","<?php echo $get_info[1]; ?>");'>Details</a>
<?php
}
?>
|
<a href='#' class=subnavwhite onclick='ViewAppHis("<?php echo $get_info[0]; ?>","<?php echo $get_info[1]; ?>");'>History</a>
<?php
echo "</td>";
         } elseif ($permit_type=='Fishery') {
$nhjyear = substr($c, 0, 4);
$nhjkyear = date('Y'); 
print "<td>
<a href='index.php?part=4&class_type=Permits&itemID_=1222&owner_id=$get_info[0]&com=Edit&permit_type=$tag&stat=$get_info[4]&busItem=Fishery'  class=subnavwhite> Edit </a>&nbsp
<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=$get_info[4]&permit_type=$tag&stat=$get_info[4]&busItem=Fishery'  class=subnavwhite> ReAssess </a>&nbsp";
if ($nhjyear < $nhjkyear) {
print"<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=ReNew&permit_type=$tag&stat=ReNew&busItem=Fishery'  class=subnavwhite> ReNew </a></td>";
}
        } elseif ($permit_type=='Peddlers') {
$nhjyear = substr($c, 0, 4);
$nhjkyear = date('Y'); 
print "<td>
<a href='index.php?part=4&class_type=Permits&itemID_=1222&owner_id=$get_info[0]&com=Edit&permit_type=$tag&stat=$get_info[4]&busItem=Peddlers'  class=subnavwhite>Edit </a>&nbsp<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=$get_info[4]&permit_type=$tag&stat=$get_info[4]&busItem=Peddlers'  class=subnavwhite> ReAssess </a>&nbsp";
if ($nhjyear < $nhjkyear) {
print "<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=ReNew&permit_type=$tag&stat=ReNew&busItem=Peddlers'  class=subnavwhite> ReNew </a></td>";
}
        } elseif ($permit_type=='Occupational') {
$nhjyear = substr($c, 0, 4);
$nhjkyear = date('Y'); 
print "<td>
<a href='index.php?part=4&class_type=Permits&itemID_=1222&owner_id=$get_info[0]&com=Edit&permit_type=$tag&stat=$get_info[4]&busItem=Occupational'  class=subnavwhite> Edit </a>&nbsp";
//<a href='index.php?part=4&itemID_=1221&owner_id=$get_info[0]&com=$get_info[4]&permit_type=$tag&stat=$get_info[7]&busItem=Occupational'  class=subnavwhite> ReAssess </a>&nbsp
if ($nhjyear < $nhjkyear) {
print "<a href='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=$get_info[0]&com=ReNew&permit_type=$tag&stat=ReNew&busItem=Occupational&create=yes'  class=subnavwhite> ReNew </a></td>";
}
        }
print "</tr>\n";
?>

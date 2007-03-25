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
include'tablecolor-inc.php';
print "<tr bgcolor='$varcolor'>\n";
print "<td width=5%>&nbsp;$norow&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_info[1]&nbsp</td>\n";
print "<td width=6%>&nbsp;$get_info[2]&nbsp</td>\n";
print "<td align=center width=20%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_info[0]' class='subnavwhite'>Edit</a>
<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=delete&bcode=$get_info[0]' class='subnavwhite'>Delete</a>
</td>\n</tr>";
}
echo "<tr><td align=left>";
include'includes/pagination.php';
echo "</td></tr>";

?>

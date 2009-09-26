<?php
$norow=0;
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
        print "<td>&nbsp;$get_info[4]&nbsp</td>\n";
        print "<td>&nbsp;$yesorno&nbsp</td>\n";
        print "<td>&nbsp;$get_info[6]&nbsp</td>\n";
        print "<td align=center width=20%>&nbsp;<a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=edit&bcode=$get_info[0]' class='subnavwhite'>Edit</a> | <a href='index.php?part=4&class_type=Preference&selMode=$selMode&action_=8&itemEvent=1&data_item=0&com=delete&bcode=$get_info[0]' class='subnavwhite'>Delete</a>
</td>\n";
                                                                                                 
}
?>

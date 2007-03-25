<?php
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

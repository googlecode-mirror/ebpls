<?php
require_once "includes/variables.php"

 $result = mysql_query ("insert into ebpls_buss_assessment values
                ('',$owner_id, $business_id,$n,$i,$x,$z)") or die ("huhuhu");



function SaveIt(howmany)
{
        for(i = 1;i<howmany;i++)
        {
        <?php

        $x="$"."x".$i;
        $z="$"."z".$i;
        $n="$"."natureid".$i;
        $t="$"."taxfeeid".$i;
        ?>



                result = mysql_query ("insert into ebpls_buss_assessment values
                ('',$owner_id, $business_id,$n,$i,$x,$z)") or die ("huhuhu");
        $re= "insert into ebpls_buss_assessment values ('',$id, $business_id,$n,$t,$x,$z)";
        $i=$i+=1;
        ?>

        }
        alert ("<?php echo $re; ?>");
}

?>

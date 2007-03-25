<?php

if ($stat=='New') {
	$tftype=1;
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
	$tfttype=3;
}

//get naturecode
$getnat = mysql_query ("select a.bus_code, b.naturedesc, a.cap_inv, a.last_yr
                        from tempbusnature a, ebpls_buss_nature b
                         where owner_id=$owner_id and business_id=$business_id and a.bus_code=b.natureid")
                        or die ("cnt1".mysql_error());
while ($getn = mysql_fetch_row($getnat)){

$cnt = mysql_query ("select natureid
                        from ebpls_buss_taxfeeother a, ebpls_buss_tfo c
                        where natureid=$getn[0] and a.taxtype='$tftype'  and c.tfoid=a.tfoid $tft ")
                        or die ("cnt2".mysql_error());
$cnt1 = mysql_num_rows($cnt);//get total count of tax per natureid
$lop1 =0;

$getd1 = mysql_query ("select c.tfodesc, a.taxfeeamtfor, a.taxfeeid, b.naturedesc, a.taxfeeind,
                        a.taxfeeoption, a.taxfeemode,c.taxfeetype
			from ebpls_buss_taxfeeother a, ebpls_buss_nature b, ebpls_buss_tfo c
                        where a.natureid=$getn[0] and b.natureid=$getn[0] and a.taxtype='$tftype'
			and c.tfoid=a.tfoid $tft")
                        or die ("cnt3".mysql_error());

//print labels
print "<br><table border=1 align =center width=300><br>";
print "<tr><td align=left>Line Of Business:</td><td>$getn[1]</td><td>Amount/Formula
       </td><td>Tax Due</td></tr>";
print "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

        while ($lop1<$cnt1){

                while ($getd=mysql_fetch_row($getd1)) {

                $lop1=$lop1+=1;
                        if ($PROCESS=='COMPUTE') {
//gety tempassess
						
                        $temp = mysql_query ("select a.natureid, a.taxfeeid, a.multi,
                                        c.tfodesc, b.taxfeeamtfor,b.taxfeeind
                                        from tempassess a, ebpls_buss_taxfeeother b, ebpls_buss_tfo c
                                        where a.assid='$varx' and b.taxtype='$tftype'
                                    and a.owner_id=$owner_id and a.business_id=$business_id and
                                    a.natureid=b.natureid and a.taxfeeid=b.taxfeeid and b.tfoid=c.tfoid $tft
                                    ") or die (mysql_error());
                        $gethuh = mysql_fetch_row($temp);
                        if ($gethuh[5]==2) { //compute formula
//$d = '((int)';
//$d='';
										
                                        eval("\$totind=$getn[2]$gethuh[4];");

                                } elseif ($gethuh[5]==3) { //get range
                                        $gethuh[4]='range';

					$getrange = mysql_query("select rangeamount
						from ebpls_buss_taxrange where
                                		taxfeeid=$gethuh[1] and rangelow = $gethuh[2]") or die ("f");
                			$haveex = mysql_num_rows($getrange);
                			if ($haveex<>1) {

                                        $getrange = mysql_query("select rangeamount from ebpls_buss_taxrange where
                                        taxfeeid=$gethuh[1] and rangelow <= $gethuh[2] and
                                        rangehigh >= $gethuh[2]") or die ("range".mysql_error());
                                	$lookrange = mysql_num_rows($getrange);
						if ($lookrange==0) {
                                                $getrange =  mysql_query("select rangeamount 
						from ebpls_buss_taxrange where
                                                taxfeeid=$gethuh[1] and rangeind = 2
                                                ") or die ("range".mysql_error());
                                 		}
					}



                                                $range = mysql_fetch_row($getrange);

                                        if (is_numeric($range[0])) {
                                                $totind=$range[0];
                                        } else {
                                                $gethuh[4]=$range[0];
                                                eval("\$totind=$gethuh[2]$range[0];");
                                        }
                                        $totind = round($totind,2);

                                } else { //constant
                                		$totind = $getn[2] * $gethuh[4];
                                }
                        $gethuh[2] = round($gethuh[2],2);
                        $grandamt = $grandamt + $totind;
		
        if ($getd[4]==2 or $getd[4]==3) { //formula and range
				$btc = number_format($getn[2], 2);
                print "<tr><td align=left width=100>$gethuh[3]</td>
		<td align=right><input type=hidden  name=x[$varx] size=5 value=$gethuh[2] >$btc</td>";

	} elseif ($getd[5]==3) {
                                                                                                              
	      print "<tr><td align=left width=100>$gethuh[3]</td>
                <td align=right><input type=text  name=x[$varx] size=5 value=$gethuh[2] ></td>";
 
        } else { //constant

                print "<tr><td align=left width=100>$gethuh[3]</td>
		<td align=right><input type=hidden  name=x[$varx] size=5 value=$gethuh[2]>$gethuh[2]</td>";
        }

$totind = round($totind,2);
$taxdue = number_format($totind,2);

print "<td align=right>
        <input type=hidden name=natureid[$varx] value=$gethuh[0]>
        <input type=hidden name=z[$varx] value=$gethuh[4]>$gethuh[4]
	<input type=hidden name=taxfeeid[$varx] value=$gethuh[1]></td>
        <td align=right><input type=hidden name=y$varx value=$totind size=5 readonly>$taxdue</td>
        </tr>";


} else { //put to tempassess;




        if ($getd[4]==2 and $stat=='New') {
                $indi=1;
                $xv = $getn[2];
                eval("\$totind=$xv$getd[1];");
		$rtag ='';
        } elseif ($getd[4]==2 and $stat=='ReNew') {
                $indi=1;
                $xv = $getn[3];
                eval("\$totind=$xv$getd[1];");
		$rtag ='';
         } elseif ($getd[4]==3) {
                //$getd[1]='range';
		$xv = $getn[2];

		$getrange = mysql_query("select rangeamount, rangelow from ebpls_buss_taxrange where
				taxfeeid=$getd[2] and rangelow = $xv") or die ("f");
		$haveex = mysql_num_rows($getrange);
		if ($haveex<>1) {
			
                $getrange = mysql_query("select rangeamount from ebpls_buss_taxrange where
                                taxfeeid=$getd[2] and rangelow <= $xv and
                                rangehigh >= $xv") or die ("range".mysql_error());

                $lookrange = mysql_num_rows($getrange);

                        if ($lookrange==0) {

                                $getrange =  mysql_query("select rangeamount from ebpls_buss_taxrange where
                                taxfeeid=$getd[2] and rangeind = 2
                                ") or die ("range".mysql_error());
                        }
		}

                $range = mysql_fetch_row($getrange);

                        if (is_numeric($range[0])) {
                                $totind=$range[0];
				$rtag ='range';

                        } else {
                                $getd[1]=$range[0];
                                eval("\$totind=$xv$range[0];");
                        }

        } else {
                $indi=0;
                $xv=1;
                $totind = $getd[1];
		$rtag ='';
        }
$totind = round($totind,2);
$grandamt = $grandamt + $totind;



if ($getd[4]==2 or $getd[4]==3) {
$xd = number_format($xv,2);
print "<tr><td align=left width=100>$getd[0] </td><td align=right>
        <input type=hidden  name=x[$varx] size=5 value=$xv>$xd</td>";
} elseif ($getd[5]==3) {
print "<tr><td align=left width=100>$getd[0] </td><td align=right>
        <input type=text  name=x[$varx] size=5 value=$xv></td>";

} else {

print "<tr><td align=left width=100>$getd[0] </td><td align=right>
        <input type=hidden  name=x[$varx] size=5 value=$xv>$xv</td>";
}

if (is_numeric($getd[1])) {
$getd[1] = number_format($getd[1],2);
}
$totind=number_format($totind,2);
print "
        <td align=right>
        <input type=hidden name=natureid[$varx] value=$getn[0]>
	<input type=hidden name=z[$varx] value=$getd[1]>$getd[1]&nbsp; $rtag
 
        <input type=hidden name=taxfeeid[$varx] value=$getd[2]></td>
        <td align=right><input type=hidden name=y$varx value=$totind size =5 readonly>
	$totind</td>
        </tr>


";



//delete if existing
        $result = mysql_query ("delete from tempassess where assid='$varx'
                        and owner_id=$owner_id and business_id=$business_id") or die (mysql_error());


//save to tempassess

if ($indi==0) {
 $result = mysql_query ("insert into tempassess
            (assid, owner_id, business_id, natureid, taxfeeid, multi, amt, formula)
            values
            ($varx,$owner_id, $business_id,$getn[0],
            $getd[2],$xv,'$getd[1]','0')")or die ("amt11w".mysql_error());


} else {

$result = mysql_query ("insert into tempassess
            (assid, owner_id, business_id, natureid, taxfeeid, multi, amt, formula)
            values
            ($varx,$owner_id, $business_id,$getn[0],
            $getd[2],$xv,0,'$getd[1]')")or die ("formula".mysql_error());

}
}

$varx++;
}
}
}

?>
<table border =1 width=300><br>
<tr><td width=100></td> <td></td><td align=right>Total Tax:</td><td align=right>
<font color=red>Php &nbsp;<?php echo $ga = number_format($grandamt,2); ?></font></td></tr>
</table>


<?php
$ota1 = $grandamt;
?>

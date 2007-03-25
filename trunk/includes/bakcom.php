<?php
$stat='New';
if ($stat=='New') {
	$tftype=1;
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
	$tfttype=3;
}


//use decimal
$dec= mysql_query("select sdecimal from ebpls_buss_preference");
$dec = mysql_fetch_row($dec);
$dec = $dec[0];
if ($dec=='') {
	$d = '(';
} else {
	$d = '(int)(';
}

$cntcomplex = mysql_query("select * from ebpls_buss_complex where taxfeeid = $taxfeeid") or
                        die (mysql_error());
$cnter = mysql_num_rows($cntcomplex);

//while ($beg < $cnt) {
while ($tfoi = mysql_fetch_row($cntcomplex)) {
//get naturecode
$getnat = mysql_query ("select a.bus_code, b.naturedesc, a.cap_inv, a.last_yr
                        from tempbusnature a, ebpls_buss_nature b
                         where owner_id=$owner_id and business_id=$business_id and a.bus_code=b.natureid and active = 1 and a.bus_code = $nat_id")
                        or die ("cnt1".mysql_error());


while ($getn = mysql_fetch_row($getnat)){


$cnt = mysql_query ("select natureid
                        from ebpls_buss_taxfeeother a, ebpls_buss_tfo c
                        where natureid=$getn[0] and a.taxfeeid<>$taxfeeid and
			a.taxtype='$tftype'  and c.tfoid=a.tfoid $tft ")
                        or die ("cnt2".mysql_error());
$cnt1 = mysql_num_rows($cnt);//get total count of tax per natureid
$lop1 =0;

$getd1 = mysql_query ("select c.tfodesc, a.taxfeeamtfor, a.taxfeeid, b.naturedesc, a.taxfeeind,
                        a.taxfeeoption, a.taxfeemode,c.taxfeetype 
			from ebpls_buss_taxfeeother a, ebpls_buss_nature b, ebpls_buss_tfo c
                        where a.natureid=$getn[0] and b.natureid=$getn[0]
			and a.taxfeeid<>$taxfeeid and a.taxtype='$tftype'
			and  c.tfoid=a.tfoid $tft")
                        or die ("cnt3".mysql_error());

        while ($lop1<$cnt1){

                while ($getd=mysql_fetch_row($getd1)) {

                $lop1=$lop1+=1;
                        if ($PROCESS=='COMPUTE') {
//gety tempassess
						
                        $temp = mysql_query ("select a.natureid, a.taxfeeid, a.multi,
                                        c.tfodesc, b.taxfeeamtfor,b.taxfeeind, b.taxfeemode
                                        from tempassess a, ebpls_buss_taxfeeother b, ebpls_buss_tfo c
                                        where a.assid='$varx' and b.taxtype='$tftype'
                                    and a.owner_id=$owner_id and a.business_id=$business_id and
                                    a.natureid=b.natureid and a.taxfeeid=b.taxfeeid and b.tfoid=c.tfoid $tft
                                    ") or die (mysql_error());
                        $gethuh = mysql_fetch_row($temp);
		                        if ($gethuh[5]==2) { //compute formula
					eval("\$totind=($gethuh[2]$gethuh[4];");
					
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
                                                eval("\$totind=$d($gethuh[2]$range[0];");
                                        }
                                        $totind = round($totind,2);

                                } else { //constant
                                		$totind = $gethuh[2] * $gethuh[4];
                                }
                        $gethuh[2] = round($gethuh[2],2);
                        $grandamt = $grandamt + $totind;
		

$totind = round($totind,2);
$taxdue = number_format($totind,2);

//end of compute
} else { //put to tempassess;


        if ($getd[4]==2 and $stat=='New') {
                $indi=1;
                $xv = $getn[2];
		eval("\$totind=($xv$getd[1];");
		$rtag ='';
        } elseif ($getd[4]==2 and $stat=='ReNew') {
                $indi=1;
                $xv = $getn[3];
                eval("\$totind=($xv$getd[1];");
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
                                eval("\$totind=$d($xv$range[0];");
                        }

        } else {
                $indi=0;
                $xv=1;
                $totind = $getd[1];
		$rtag ='';
        }
$totind = round($totind,2);
$grandamt = $grandamt + $totind;

if (is_numeric($getd[1])) {
$getd[1] = number_format($getd[1],2);
}
$totd=number_format($totind,2);

}
$varx++;
if ($beg < $cnter+1) {
//$beg++;
if ($compid==''){
$cntop = mysql_query("select * from ebpls_buss_complex where taxfeeid = $taxfeeid 
                        order by compid asc limit 1") or
                        die ("1".mysql_error());
} else {
$cntop = mysql_query("select * from ebpls_buss_complex where taxfeeid = $taxfeeid and
			compid<> $compid and compid > $compid order by compid asc limit 1") or
                        die (mysql_error());
}
$optr1 = mysql_fetch_row($cntop);
$compid = $optr1[0];
//echo $optr1[4].$compid;
$outamt = $outamt.$totind.$optr1[4].$optr1[5];
$beg++;
}
//echo $totind.$tfoi[4].$tfoi[5]."<br>";
}
}
//$outamt = $outamt.$getd[1]."==".$totind.$tfoi[4].$tfoi[5];
//$totind='';
//$beg++;
}
}
//eval("\$totind=$outamt;");
$se = mysql_query("select a.taxfeeamtfor
                        from ebpls_buss_taxfeeother a, ebpls_buss_nature b, ebpls_buss_tfo c
                        where a.natureid=$nat_id and b.natureid=$nat_id
                        and a.taxfeeid=$taxfeeid and a.taxtype='$tftype'
                        and  c.tfoid=a.tfoid $tft") or die (mysql_error());
$f = mysql_fetch_row($se);
$f = $f[0];
$totind = number_format($totind);
echo $f.$outamt;

?>

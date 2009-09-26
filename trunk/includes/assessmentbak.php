<?php

if ($stat=='New') {
	$tftype=1;
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
	$tfttype=3;
}


//update old assess

$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
		"active = 0","owner_id=$owner_id and
		 business_id=$business_id and transaction<>'$stat'");
//use decimal
$dec= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$dec = FetchArray($dbtype,$dec);
$dec = $dec[sdecimal];
if ($dec=='1') {
	$d = '(';
} else {
	$d = '(int)(';
}
$df = 0;
//get naturecode

if ($stat=='Retire') {
	$retstr = "and a.retire=2";
} else {
	$retstr='';
}

$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature a, ebpls_buss_nature b",
			"a.bus_code, b.naturedesc, a.cap_inv, a.last_yr, a.transaction",
                        "where owner_id=$owner_id and business_id=$business_id 
			and a.bus_code=b.natureid and active = 1 $retstr");
while ($getn = FetchRow($dbtype,$getnat)){
$stt=$getn[4];
if ($stt=='New') {
        $tftype=1;
} elseif ($stt=='ReNew') {
        $tftype=2;
} elseif ($stt=='Retire') {
        $tftype=3;
}

//print labels
?>
<table border=0 align =left width=100%><br>
<tr><td align=left>LINE OF BUSINESS: &nbsp; <b><?php echo $getn[1]; ?></b></td></tr>
</table><br>
<table border=0 align =center width=100% cellspacing=0><br>
<td align =center bgcolor=gray width=25%>TAXES/FEES</td>
<td align =center bgcolor=gray width=25%>BASE VALUE/INPUT</td>
<td align =center bgcolor=gray width=25%>AMOUNT/FORMULA</td>
<td align =center bgcolor=gray width=25%>TAX DUE</td></tr>
</table>
<table border=1 align =center width=100% cellspacing=0>
<?php
/*
if ($gettag[sassess]=='') {
	while ($tftnum<4) {
$tft = ' and c.taxfeetype='.$tftnum;
}
*/
$cnt = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_taxfeeother a, ebpls_buss_tfo c",
                        "where natureid=$getn[0] and a.taxtype='$tftype'
                         and c.tfoid=a.tfo_id $tft");
$cnt1 = NumRows($dbtype,$cnt);//get total count of tax per natureid
$lop1 =0;
                                                                                                 
$getd1 = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxfeeother a,
                        ebpls_buss_nature b, ebpls_buss_tfo c",
                        "c.tfodesc, a.amtformula, a.taxfeeid, b.naturedesc, a.indicator,
                        a.basis, a.mode,c.taxfeetype, c.tfoid, a.uom, a.min_amt",
                        "where a.natureid=$getn[0] and b.natureid=$getn[0]
                        and a.taxtype='$tftype' and c.tfoid=a.tfo_id
                        $tft order by a.mode asc");



        while ($lop1<$cnt1){
                while ($getd=FetchRow($dbtype,$getd1)) {
                $lop1=$lop1+=1;
                        if ($PROCESS=='COMPUTE') {
//gety tempassess
                        $temp = SelectMultiTable($dbtype,$dbLink,
				"tempassess a, ebpls_buss_taxfeeother b, ebpls_buss_tfo c",
				"a.natureid, a.taxfeeid, a.multi,
                                 c.tfodesc, b.taxfeeamtfor,b.taxfeeind, 
  				 b.taxfeemode, b.uom, c.tfoid",
                                "where a.assid='$varx' and b.taxtype='$tftype'
                                 and a.owner_id=$owner_id and a.business_id=$business_id and
				 a.active = 1 and a.transaction = '$stat' and
                                 a.natureid=b.natureid and a.taxfeeid=b.taxfeeid 
				 and b.tfoid=c.tfoid $tft");
                        $gethuh = FetchRow($dbtype,$temp);
					if ($gethuh[5]==2) { //compute formula
						if ($get[6]==2) {
				//	$taxfeeid = $gethuh[1];
				//	$nat_id = $gethuh[0];
				//	$gethuh[4]='complex formula';
				//	$outamt = $d.$gethuh[4];
                        	//	require 'includes/complex.php';
		        	       		} elseif ($gethuh[6]==1) {       
					
						if ($gethuh[4]<>'complex formula') {
						eval("\$totind=$d($gethuh[2]$gethuh[4];");
						} else {
						$gethuh[4]='complex formula';
						}
						}
                        		} elseif ($gethuh[5]==3) { //get range
                                        	$gethuh[4]='range';
					$getrange = SelectMultiTable($dbtype,$dbLink,
						"ebpls_buss_taxrange","rangeamount",
						"where taxfeeid=$gethuh[1] and 
						 rangelow = $gethuh[2]
						 and rangestatus='A'");
                			$haveex = NumRows($dbtype,$getrange);
               			if ($haveex<>1) {
                                   $getrange = SelectMultiTable($dbtype,$dbLink,
					"ebpls_buss_taxrange","rangeamount",
					"where taxfeeid=$gethuh[1] and 
					 rangelow <= $gethuh[2] and
                                         rangehigh >= $gethuh[2] and rangestatus='A'");
                                	$lookrange = NumRows($dbtype,$getrange);
						if ($lookrange==0) {
                                                $getrange =  SelectMultiTable($dbtype,$dbLink,
						"ebpls_buss_taxrange","rangeamount", 
						"where taxfeeid=$gethuh[1]
						and rangestatus='A'
						order by rangeid desc limit 1");
                                 		}
				}

                                  $range = FetchRow($dbtype,$getrange);

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
                        
$gethuh[3]=stripslashes($gethuh[3]);
	
	
        if ($getd[4]==2 or $getd[4]==3 and $getd[5]<>3) { //formula and range
		if ($stt=='New') {
			
			 if ($getd[5]==3) {
                                $xv = 0;
                        } else {
	                	$xv = $getn[2];
			}
                } else {
			 if ($getd[5]==3) {
                                $xv = 0;
                        } else {
		                $xv=$getn[3];
			}
                }
	
			$btc = number_format($xv, 2);


		if ($getd[5]<>3) {
                print "<tr><td align=left width=25%>$gethuh[3]</td>
		<td align=right><input type=hidden  name=x[$varx] size=5 value=$gethuh[2] $lockit >$btc</td>";
		} else {		
		 print "<tr><td align=left width=25%>$gethuh[3]</td>
                <td align=right>$gethuh[7] &nbsp <input type=text  name=x[$varx] size=5 value=$gethuh[2] $lockit ></td>";
        $tbut=1;
		}


	} elseif ($getd[5]==3) {
                                                                                                              
	      print "<tr><td align=left width=25%>$gethuh[3]</td>
                <td align=right>$gethuh[7] &nbsp <input type=text  name=x[$varx] size=5 value=$gethuh[2] $lockit ></td>";
	$tbut=1; 
        } else { //constant

                print "<tr><td align=left width=25%>$gethuh[3]</td>
		<td align=right><input type=hidden  name=x[$varx] size=5 value=$gethuh[2] $lockit>$gethuh[2] </td>";
        }

if ($gethuh[6]==2) {
$taxfeeid = $gethuh[1];
$nat_id = $gethuh[0];
$fr = $d."(".$xv.$gethuh[4];
$gethuh[4]='complex formula';
require 'includes/complex.php';
//$taxdue=number_format($totind, 2);
}
include'includes/minimum_compute.php';
$getd[0]=addslashes($getd[0]);
$chkiffee = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo","where tfodesc='$getd[0]'");
$chkiffee = FetchArray($dbtype,$chkiffee);
if ($chkiffee[taxfeetype]<>1){
	$totfee=$totfee+$totind;
}
/*
$chkiffee = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo","where tfodesc like 'Mayor%'");
$chkiffee = mysql_fetch_row($chkiffee);
if ($chkiffee[tfodesc]==$getd[0]){
	$totfee=$totfee+$totind;
}
*/
$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
                        ebpls_buss_tfo c","a.*",
                        "where a.business_id=$business_id and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$gethuh[8] and b.tfoid=$gethuh[8] and
                        b.active=1");
$getfeex = NumRows($dbtype,$getex);
if ($getfeex>0) {
                                             
	$exemptedfee = $exemptedfee + $gethuh[4];
        $usemin = 'Fee Exempted';
        $rtag='';
        $gethuh[4]=0;
        $taxdue='0.00';
        $totind=0;
}

$totind = round($totind,2);
$taxdue = number_format($totind,2);
$tt = $tt + $totind;
$grandamt = $tt;

print "<td align=right  width=25%>
        <input type=hidden name=natureid[$varx] value=$gethuh[0]>
        <input type=hidden name=z[$varx] value=$gethuh[4]>$usemin1 &nbsp; $gethuh[4] 
	<input type=hidden name=taxfeeid[$varx] value=$gethuh[1]></td>
        <td align=right width=25%>
	<input type=hidden name=y$varx value=$totind size=5 readonly>$usemin &nbsp; $taxdue</td>
        </tr>";
$usemin='';
$havemayor = strpos(strtolower($getd[0]),'mayor');
if ($getd[7]<>1 || $havemayor>-1) {
        $feecompute = $feecompute + $totind;
	$tt = $tt - $totind;
	$grandamt = $tt;
  }
} else { //put to tempassess;
$nat_id = $getn[0];

        if ($getd[4]==2 and $stt=='New') {
                $indi=1;
                $xv = $getn[2];
			if ($getd[5]==3) {
				$xv=0;
			}

		                if ($getd[6]==2) {
					$taxfeeid = $getd[2];
					$fr = $d."(".$xv.$getd[1];
					require 'includes/complex.php';
					$getd[1]='complex formula';
				} else {
					eval("\$totind=$d($xv$getd[1];");
				}
		$rtag ='';

        } elseif ($getd[4]==2 and $stt=='ReNew' || $stt=='Retire') {
                $indi=1;
                $xv = $getn[3];

		 if ($getd[5]==3) {
                                $xv=0;
                 }


		if ($getd[6]==2) {
			$taxfeeid = $getd[2];
			$fr = $d."(".$xv.$getd[1];
			$getd[1]='complex formula';
                        require 'includes/complex.php';
                } else {
                eval("\$totind=$d($xv$getd[1];");
		}
		$rtag ='';
         } elseif ($getd[4]==3) {
		if ($stt=='New') {
			if ($getd[5]==3) {
				$xv = 0;
			} else {
				$xv = $getn[2];
			}
		} else {

			if ($getd[5]==3) {
                                $xv = 0;
                        } else {
				$xv=$getn[3];
			}
		}

		$getrange = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxrange",
				"rangeamount, rangelow",
				"where taxfeeid=$getd[2] and rangelow = $xv
				and rangestatus='A'");
		$haveex = NumRows($dbtype,$getrange);
		if ($haveex<>1) {
			
                $getrange = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxrange",
				"rangeamount",
				"where taxfeeid=$getd[2] and rangelow <= $xv and
                                rangehigh >= $xv and rangestatus='A'");
                $lookrange = NumRows($dbtype,$getrange);

                        if ($lookrange==0 || $lookrange=='') {
                                $getrange =  SelectMultiTable($dbtype,$dbLink,
						"ebpls_buss_taxrange",
						"rangeamount",
						"where taxfeeid=$getd[2] and rangestatus='A' 
						 order by rangeid desc limit 1");
                        }
		}
                $range = FetchRow($dbtype,$getrange);
                        if (is_numeric($range[0])) {
                                $totind=$range[0];
				$rtag ='range';
				$compvalrange=$totind;
                        } else {
                                $getd[1]=$range[0];
                                eval("\$totind=$d($xv$range[0];");
				$compvalrange=$totind;

                        }

        } else {
                $indi=0;
                $xv=1;
                $totind = $getd[1];
		$rtag ='';
        }
include'includes/minimum_compute.php';
$getd[0] = addslashes($getd[0]);
$chkiffee = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo","where tfodesc='$getd[0]'");
$chkiffee = FetchArray($dbtype,$chkiffee);
if ($chkiffee[taxfeetype]<>1){
	$totfee=$totfee+$totind;
}

$getd[0]=stripslashes($getd[0]);
if ($getd[4]==2 or $getd[4]==3) {
	if ($getd[5]<>3) {
	$xd = number_format($xv,2);
	print "<tr><td align=left  width=25%>$getd[0] </td><td align=right>
        <input type=hidden  name=x[$varx] size=5 value=$xv $lockit>$xd</td>";

	} else {

	print "<tr><td align=left  width=25%>$getd[0] </td><td align=right>$getd[9] &nbsp
        <input type=text  name=x[$varx] size=5 value=0 $lockit></td>";
	$tbut=1;
	$xv = 0;
	$totind=0;
	}
} elseif ($getd[5]==3) {
	print "<tr><td align=left  width=25%>$getd[0] </td><td align=right>$getd[9] &nbsp
        <input type=text  name=x[$varx] size=5 value=$xv $lockit></td>";
	$tbut=1;
} else {
	print "<tr><td align=left  width=25%>$getd[0] </td><td align=right>
        <input type=hidden  name=x[$varx] size=5 value=$xv $lockit>$xv </td>";
}


if (is_numeric($getd[1])) {
	$nfgetd = number_format($getd[1],2);
} else {
	$nfgetd = $getd[1];
}
$ttnf = $totind;
$ttn=number_format($ttnf,2);
if ($getd[6]==2) {
$nfgetd='complex formula';
}

if ($rtag=='range') {
	$nfgetd='';
	$getd[1]=$range[0];
	$ttn=number_format($compvalrange,2);
	$totind = $compvalrange;
}

//check for exempted fees
//$getd[7] tfoid

$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
                        ebpls_buss_tfo c","a.*",
			"where a.business_id=$business_id and
			a.business_category_code=b.business_category_code and
			c.tfoid=$getd[8] and b.tfoid=$getd[8] and
			b.active=1");
$getfeex = NumRows($dbtype,$getex);
if ($getfeex>0) {
	$exemptedfee = $exemptedfee + $getd[1];
	$usemin = 'Fee Exempted';
	$nfgetd = '0.00';
	$rtag='';
	$ttn = '0.00';
	$getd[1]=0;
	$ttnf=0;
	$totind=0;
	$chngcompval = 1;
}
//$totind = round($ttn,2); //dating ganito
$totind = round($totind,2);

$grandamt = $grandamt + $totind;
print "
        <td align=right  width=25%>
        <input type=hidden name=natureid[$varx] value=$getn[0]>
	<input type=hidden name=z[$varx] value=$getd[1]>$usemin1 &nbsp; $nfgetd&nbsp; $rtag  
        <input type=hidden name=taxfeeid[$varx] value=$getd[2]></td>
        <td align=right width=25%>
	<input type=hidden name=y$varx value=$ttnf size =5 readonly>$usemin &nbsp;
	$ttn </td>
        </tr>
";
$usemin='';
$havemayor = strpos(strtolower($getd[0]),'mayor');
                                                                                                 
if ($getd[7]<>1 || $havemayor>-1) {
	$totmpf = $totmpf + $totind;
        $feecompute =  $feecompute + $totind;
	$grandamt = $grandamt - $totind;
  }

//delete if existing
        $result = DeleteQuery($dbtype,$dbLink,"tempassess",
			"assid='$varx' and owner_id=$owner_id 
			and business_id=$business_id
			and active=1 and transaction='$stat'");
//save to tempassess
if ($indi==0) {

	if ($rtag<>'range') {
		$compval = $xv*$getd[1];
	} else {
		$compval=$compvalrange;
		$totind=$compval;
			if ($totind<$chkcp) { //use minimum
				$totind=$chkcp;
				$compval=$chkcp;
			}

	}
		if ($compval=='') {
		eval("\$compval=$d($xv$getd[1]));");
		}

	if ($chngcompval==1) {
		$compval=0;
		$chngcompval=0;
	}

 $result = InsertQuery($dbtype,$dbLink,"tempassess",
            		"(assid, owner_id, business_id, natureid, 
			 taxfeeid, multi, amt, formula, 
			 compval, tfoid, active, transaction)",
            		"$varx,$owner_id, $business_id,$getn[0],
		         $getd[2],$xv,'$getd[1]','$getd[1]',$compval, $getd[8],
			 1, '$stat'");
} else {
if ($getd[1]<>'complex formula') {
	if ($chk1[0]<>1) { //use min
	
		if (!is_numeric($getd[1])) {
		eval("\$compval=$d($xv$getd[1];");
		}
	} else {
		$compval = $getd[1];
	}
} else {
eval ("\$compval=$fr$ff$outamt$addcp;");
}
$result = InsertQuery($dbtype,$dbLink,"tempassess",
	  	      "(assid, owner_id, business_id, natureid, taxfeeid, 
			multi, amt, formula, compval, tfoid,active, transaction)",
            	      "$varx,$owner_id, $business_id,$getn[0],
	               $getd[2],$xv,0,'$getd[1]',$compval, $getd[8],1, '$stat'");

}
}

if ($gettag[sassess]=='1') {
$feecompute=0;
}
$rt = ($rt + $totind) - $feecompute;
$df=$df+=1;
$varx++;
$add2fee=$feecompute + $add2fee;
$feecompute='';
}
/*
if ($gettag[sassess]=='') {
	$tftnum=4;
	}
}
*/
?>
</table>
<table border=0 align=center width=100% cellpadding=1 cellspacing = 1 >
<tr>
<?php

	if ($tbut==1) {;
?>
<td width=25%><input type=submit name='PROCESS' value='COMPUTE'></td>
	<?php
	} else {
?>
<td width=25%>&nbsp;</td>
<?php
	}

if ($tftnum==1) {
?>
<td width=50%>Total Tax for <?php echo $getn[1]; ?>:</td>
<td width=25% align= right bgcolor=red><font color=white>
<?php $grd = $grd+ $rt; echo number_format($rt,2); ?></font>&nbsp;
</tr></table>
<?php
}
$rt = 0;
$tbut=0;
}
}
?>
<table border =0 width=100%>
<?php
$grandamt=$grd;
include'includes/exemption.php';
?>
<tr><td width=25%></td><td width=25%>&nbsp;</td>
<td align=right width=25%>Total Tax :</td><td align=right width=25% bgcolor=black>
<font color=white>Php &nbsp;<?php echo $ga = number_format($grandamt,2); ?></font></td></tr>
</table>


<?php
$ota1 = $grandamt;
?>

<?php

echo "ddddddddddddddddddddddddddddd";


if ($stat=='New') {
        $tftype=1;
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
        $tfttype=3;
}

$totexempt=0;
$xop=0;
$subexempt=0;
$feecompute=0;
//use decimal
$dec= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$dec = FetchArray($dbtype,$dec);
$dec = $dec[sdecimal];

if ($dec<>'1') {
        $is_dec = '(int)';
}


if ($stat=='Retire') {
        $retstr = "and a.retire=2";
} else {
        $retstr='';
}

//get naturecode
$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature a, ebpls_buss_nature b, tempassess c",
			"distinct a.bus_code, b.naturedesc, a.linepaid, a.transaction,c.date_create",
                        "where a.owner_id=$owner_id and a.business_id=$business_id and
			c.owner_id=$owner_id and c.business_id=$business_id and 
			a.bus_code=b.natureid and c.natureid=a.bus_code and
			c.active=1 and c.transaction='$stat' and 
			a.owner_id=c.owner_id and a.business_id=c.business_id 
			and a.active=1 $retstr");


while ($getn = FetchRow($dbtype,$getnat)){
$stt=$getn[3];

	if ($stt=='New') {
        	$tftype=1;
	} elseif ($stt=='ReNew') {
        	$tftype=2;
	} else {
        	$tftype=3;
	}


$cnt = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_taxfeeother a, ebpls_buss_tfo d",
			 "where a.natureid=$getn[0] and a.taxtype='$tftype' 
			and a.tfo_id=d.tfoid $tft");
$cnt1 = NumRows($dbtype,$cnt);
$lop1 =0;
$getd1 = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxfeeother a, ebpls_buss_nature b, 
			tempassess c, ebpls_buss_tfo d",
			"d.tfodesc, a.amtformula, a.taxfeeid, b.naturedesc, c.multi,
			a.indicator, a.mode, a.uom, d.tfoid, d.taxfeetype, a.min_amt,c.date_create",
                        "where a.natureid='$getn[0]' and b.natureid=$getn[0]
			and c.active=1 and c.transaction='$stat' 
			and c.natureid=a.natureid and c.natureid=b.natureid and
			c.natureid='$getn[0]' and a.taxtype='$tftype' and 
			c.taxfeeid = a.taxfeeid and c.owner_id=$owner_id and 
			c.business_id=$business_id and a.tfo_id=d.tfoid $tft");

?>
<table border=1 align=center width=100% >
<tr>
<td align=left>Line Of Business:
<b><?php echo $getn[1]; ?></b></td>
</tr>
</table>
<table border=1 align=center width=100% cellspacing=1 class=sub>
<tr>
<th width=25%>TAXES/FEES</th>
<th width=25%>BASE VALUE/INPUT</th>
<th width=25%>AMOUNT/FORMULA</th>
<th width=25%>TAX DUE</th></tr>
<tr>
<?php                                                                                                                                                            
while ($lop1<$cnt1)
{

$lop1++
echo "1<BR";

/*
	while ($getd=FetchArray($dbtype,$getd1)) {
		$show_complex='';
		$usemin='';                                                                                                                                                            
		$lop1++;

/*		if ($getd[indicator]==2) {

		if ($getd[mode]==2) { //complex
  include_once "class/TaxFeeOtherChargesClass.php";
                        $searchme = new TaxFee;
                        $searchme->CountTaxFeeComplex($getd[taxfeeid]);
                        $how_many = $searchme->outnumrow;
                        $loop=0;
                //sub X0
                $complex_formula =str_replace("X0",$getd[multi],strtoupper($getd[amtformula]));
                $gTFO = new TaxFee;
                	while ($loop<$how_many) {
                        $loop++;
                        $gTFO->FetchTaxFeeArray($searchme->outselect);
                        $get_varx = $gTFO->outarray;
                        $gTempAssess = new TaxFee;
  	        $gTempAssess->ReplaceValue($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
                $replace_var = $gTempAssess->outarray;
                $complex_formula = str_replace($get_varx[var_complex],$replace_var[compval],$complex_formula);
                	}
                @eval("\$totind=$is_dec$complex_formula;");
                $show_complex = $getd[amtformula];
                $getd[amtformula]='complex formula: ';

                } else {
		 $formula_rep = str_replace("X0",$getd[multi],strtoupper($getd[amtformula]));
                @eval("\$totind=$is_dec$formula_rep;");
		}


		} elseif ($getd[indicator]==3) { //get range
                $getd[amtformula]='range';
		$uom = $getd[uom];
		$getrange = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxrange",
				"rangeamount, rangelow",
				"where taxfeeid=$getd[taxfeeid] and 
				rangelow = $getd[multi]");
                $haveex = NumRows($dbtype,$getrange);
                if ($haveex<>1) {
                $getrange = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxrange",
				"rangeamount",
				"where taxfeeid=$getd[taxfeeid] and rangelow <= $getd[multi] and
                                rangehigh >= $getd[multi]");
                $lookrange = NumRows($dbtype,$getrange);
                        if ($lookrange==0 || $lookrange=='') {
                                $getrange =  SelectMultiTable($dbtype,$dbLink,
				"ebpls_buss_taxrange","rangeamount",
				"where taxfeeid=$getd[taxfeeid]
				order by rangeid desc limit 1");
                        }
                }                                                                                               
                $range = FetchRow($dbtype,$getrange);
                                                                                                               
                        if (is_numeric($range[0])) {
                                $totind=$range[0];
				$getd[amtformula]='range';
                        } else {

		$formula_rep = str_replace("X0",$getd[multi],strtoupper($range[0]));
                @eval("\$totind=$is_dec$formula_rep;");
                $getd[amtformula]=$range[0];
                        }

		} else {
		$uom = $getd[uom];
		$totind=$getd[amtformula] * $getd[multi];

		}

include'includes/minimum_compute.php';

$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
                        ebpls_buss_tfo c","a.*","where a.business_id=$business_id and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$getd[tfoid] and b.tfoid=$getd[tfoid] and
                        b.active=1");
                                                                                                 
$getfeex = NumRows($dbtype,$getex);
	if ($getfeex>0) {
        $exemptedfee = $exemptedfee + $getd[amtformula];
        $uom = 'Fee Exempted';
        $getdnf = '0.00';
        $getd[amtformula]=0;
        $origtotind=$totind;
        $totind=0;
	}
include'includes/pay_exempt.php';
$grandamt = $grandamt + $totind;
$ttamt=$ttamt+$totind;
$totindnf=number_format($totind,2);
$getdnf=number_format($getd[multi],2);



?>
<tr><td width=25%><?php echo $getd[tfodesc]; ?> </td>
<td align=right width=25%><?php echo $uom."&nbsp;".$getdnf; ?> 
</td>
<td align=right width=25%>
<?php echo $usemin.$getd[amtformula].$show_complex; ?>
</td>
<td align=right width=25%><?php echo $totindnf; ?></td>
</tr>
<?php
/*$havemayor = strpos(strtolower($getd[tfodesc]),'mayor');
//exemption of mayor
//if ($tft=='') {
  if ($getd[min_amt]<>1 || $havemayor>-1) {
	$totmpf = $totmpf + round($totind,2);
	$mayorfee = round($totind,2) + $mayorfee;	
	$feecomputeline = $feecomputeline + round($totind,2);
	$havemayor='';
	$add2fee = $mayorfee;
  }
*/
//}

$varx++;
$uom='';
		}//while $getd=FetchArray($dbtype,$getd1)


$tt = $ttamt;
$havepay = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details",
			"payment_part, linepaid",
			"where trans_id = $owner_id and payment_id=$business_id 
			and or_entry_type='CASH' and transaction='$istat' 
			order by or_detail_id");
$cntcash = NumRows($dbtype,$havepay);
$havecheck = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details a,
				ebpls_transaction_payment_check b",
				"a.payment_part, a.linepaid",
				"where a.trans_id = $owner_id and a.payment_id=$business_id
				 and a.transaction='$istat' and a.or_entry_type='CHECK'
				 and a.or_no=b.or_no and b.check_status='CLEARED'
	                         order by or_detail_id ");
$cntcheck = NumRows($dbtype,$havecheck);
$cnthave = $cntcash+$cntcheck;
$linepd = FetchRow($dbtype,$havepay);
$ppart = $cnthave+1;

$grandamt = $ttamt;
/*
if ($tftnum==1) {
	if ($cnthave<3) {
?>
	<tr>
        <td colspan=3 align=right >Total Tax for <?php echo $getn[1]; ?>:</td>
        <td align=right bgcolor="#CCFFCC">
<?php 
	}

$origtax = $ttamt;
	if ($itemID_==5212) {
	$pmode = $datarow[business_payment_mode];
	}// else {

        if ($cnthave<>0 ) { //and $linepd==0) {
	 $haveline =  SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details",
			"payment_part, linepaid",
			"where trans_id = $owner_id and payment_id=$business_id
			 and linepaid=1 and transaction = '$stat'
                         order by or_detail_id");
	 $linehave = NumRows($dbtype,$haveline);

			   	if ($pmode=='QUARTERLY') {
	                                $rempay=4-$cnthave+$linehave;
					$divi = 4;
        	                }elseif ($pmode=='SEMI-ANNUAL'){
                	        $rempay=2-$cnthave+$linehave;
				$divi = 2;
				} else {
				$divi = 1;
				}

		if ($cnthave>3) {
		$gid = SelectDataWhere($dbtype,$dbLink,"bus_grandamt",
                                "where business_id=$business_id and active = 0 and
                                owner_id=$owner_id order by gid desc limit 1");
	        $haveexist = NumRows($dbtype,$gid);

                	if ($haveexist<>0) {
                        $mt = FetchRow($dbtype,$gid);
                        $grdmt = $mt[3];
			$ttamt = $grdmt-$totalpaidtax;
	                }

		} elseif  ($cnthave>=1 and $linehave==0 ) {
		$gid = SelectDataWhere($dbtype,$dbLink,"bus_grandamt",
                                "where business_id=$business_id and active = 0 and
                                owner_id=$owner_id order by gid desc limit 1");
                $haveexist = NumRows($dbtype,$gid);
                        if ($haveexist<>0) {
                        $mt = FetchRow($dbtype,$gid);
                        $grdmt = $mt[3];
			$ttamt = $grdmt-$totalpaidtax; 
			}
			if ($pmode=='SEMI-ANNUAL') {
				$ttamt = $ttamt/2;
			} else {
				 $ttamt = $origtax/$divi*$rempay;
			}
		//echo $grandamt."--------";
		} else {

			if ($pmode=='ANNUAL') {
				$divi = 1;
				$rempay=1;
			}
		
		$ttamt = $ttamt/$divi*$rempay;
		}
	}



if ($gettag[sassess]=='') {
	$ttamt = $tt;
} else {
	$mayorfee=0;
}

$spay= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$spay = FetchArray($dbtype,$spay);

if ($spay[spayment]=='') {
	$gentax = $origtax+$gentax;
	//echo number_format($origtax,2);
} else {
	$gentax = $gentax+($ttamt-$mayorfee);
	echo number_format($ttamt-$mayorfee,2);
}

$mayorfee =0;
if ($dec[sassess]==1 and $dec[spayment]==1) {
	include'includes/exemption.php';
}

$chkbuspreference = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$chkbuspreference = FetchArray($dbtype,$chkbuspreference);
if ($chkbuspreference[spayment]=='1') {
	$subexempt=$subexempt+$totexempt;
	$totexempt=0;
}
$gamt = $gamt+$ttamt;
?>
</font></td>
<?php

$gettag = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag = FetchArray($dbtype,$gettag);
                                                                                                               

if ($itemID_<>5212){
	if ($gettag[sassess]<>''){
		if ($stt<>'New') {
		$uselp = 1;
		include"includes/exemption.php";
			if ($spay[spayment]==1) {
			include"includes/linepen.php";
		   	$gentax=$ttamt;	
			}
		}
	}

}


if ($getn[2]==1 and $itemID_<>5212  and $cnthave<3 and $statpin=='') {
?>
	<tr>
<?php
	if ($getn[2]=='') {

		if ($feecomputeline=='') {
			$feecomputeline=0;
		}	

?>
		
        <td align=right colspan=3>Pay This Line Only By:</td>
        <td align=right><font color=blue>
	<a href='#' onClick="javascript:PaymentCommand('CASH',<?php echo $ttamt; ?>,'Per Line',<?php echo $ppart; ?>,<?php echo $ppart; ?>,<?php echo $getn[0]; ?>,'','',<?php echo $feecomputeline; ?>)">
	<font color=blue>Cash</font></a>&nbsp;
	<a href='#' onClick="javascript:PaymentCommand('CHECK',<?php echo $ttamt; ?>,'Per Line',<?php echo $ppart; ?>,<?php echo $ppart; ?>,<?php echo $getn[0]; ?>,'','',<?php echo $feecomputeline; ?>)">
	<font color=blue>Check</font></a>
</td> </tr>
<?php
	} else {


$getorn=SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b",
			"a.or_no, b.or_entry_type", 
			"where b.trans_id=$owner_id and b.payment_id=$business_id and
			b.nat_id=$getn[0] and b.linepaid=1 and a.or_no=b.or_no 
			and a.trans_id=b.trans_id");
$getorn=FetchRow($dbtype,$getorn);

?>
	 <td></td><td align=right></td>
         <td align=right><font color=blue>
	<a href="ebplsreceipt.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id;?>&or_no=<?php echo $getorn[0]; ?>&cmd=<?php echo $getorn[1]; ?>&paymde=Per Line&nature_id=<?php echo $getn[0]; ?>">Re-Print OR</a>
</td> </tr>
<?php
	}

$feecomputeline=0;

?>

</tr><td></td></tr>
</table>

<?php
$twop = $twop+$origtax;
$grdt = $ttamt+$grdt;
$grandamt = $grdt;
} //($getn[2]==1 and $itemID_<>5212  and $cnthave<3 and $statpin=='')


$ttamt=0;
$totind=0;
}//tftnum
*/
//}
//}

?>
</table>
<?php


if ($uselp<>1) {
//$grandamt = $gamt-$totmpf ;//$grdt;
}


if ($tftnum==1) {
	if ($itemID_==2212) {
?>

	<table border=0 align=center width=100% class=sub>
	<tr><td width=25%>&nbsp;</td><td width=25%>&nbsp;</td>
<!--<td width=25% align=right>Tax :</td>
<td width=25% align=right>
Php &nbsp;<?php echo number_format($grandamt,2); ?></td>-->
	</tr>
<?php
	$chkbuspreference = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
	$chkbuspreference = FetchArray($dbtype,$chkbuspreference);

		if ($chkbuspreference[spayment]=='1') {
			$totexempt=$subexempt;
		}

		if ($chkbuspreference[sassess]==1) {
			$add2fee=0;
		}

		include'includes/exemption.php';
?>
<tr><td width=25%>&nbsp;</td><td width=25%>&nbsp;</td><td width=25% align=right>Total Tax :</td>
<td align=right width=25% bgcolor="lightblue">
<b>Php &nbsp;<?php $vart = $gentax; echo number_format($gentax,2); ?></b></td></tr>
</table>
<?php

	}
}

?>

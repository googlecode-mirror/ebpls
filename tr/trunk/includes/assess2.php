<?php

/*if ($stat=='New') {
        $tftype=1;
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
        $tfttype=3;
}

//use decimal
$dec= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$dec = FetchArray($dbtype,$dec);
$dec = $dec[sdecimal];
if ($dec<>'1') {
        $is_dec = '(int)';
} else {
        $is_dec = '';
}
$df = 0;
//get naturecode

if ($stat=='Retire') {
        $retstr = "and a.retire=2";
} else {
        $retstr='';
}
*/


echo "ddd";
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
$PROCESS='COMPUTE';
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
                while ($getd=FetchArray($dbtype,$getd1)) {
                $show_complex='';
                $lop1++;

//check number of years
                $getyears = SelectMultiTable($dbtype,$dbLink,
                                "tempassess a,  ebpls_buss_tfo c",
                                "c.or_print,a.date_create,c.tfodesc",
                                "where a.tfoid=c.tfoid and a.tfoid = $getd[tfoid] order by a.date_create");

                $getyr  = FetchArray($dbtype,$getyears);
                $bill_date = date('Y') - date('Y',strtotime($getyr[date_create]));


                if ($getyr[or_print] > $bill_date) {
                        $varx = $varx - 1;
                print "<input type=hidden name=minus_hm value=1>";
                }

                if ($getyr[or_print] <= $bill_date || $getyr[or_print]=='') {
                        if ($PROCESS=='COMPUTE') {
//gety tempassess
                        $temp = SelectMultiTable($dbtype,$dbLink,
                                "tempassess a, ebpls_buss_taxfeeother b, ebpls_buss_tfo c",
                                "a.natureid, a.taxfeeid, a.multi,
                                 c.tfodesc, b.amtformula,b.indicator,
                                 b.mode, b.uom, c.tfoid,a.date_create",
                                "where a.assid='$varx' and b.taxtype='$tftype'
                                 and a.owner_id=$owner_id and a.business_id=$business_id and
                                 a.active = 1 and a.transaction = '$stat' and
                                 a.natureid=b.natureid and a.taxfeeid=b.taxfeeid
                                 and b.tfo_id=c.tfoid $tft");
                        $gethuh = FetchArray($dbtype,$temp);
                                        if ($gethuh[indicator]==2) { //compute formula
                                                if ($gethuh[mode]==2) { //complex
                 include_once "class/TaxFeeOtherChargesClass.php";
                        $searchme = new TaxFee;
                        $searchme->CountTaxFeeComplex($gethuh[taxfeeid]);
                        $how_many = $searchme->outnumrow;
                        $loop=0;
                //sub X0
                $complex_formula =str_replace("X0",$gethuh[multi],strtoupper($gethuh[amtformula]));
                $gTFO = new TaxFee;
                while ($loop<$how_many) {
                        $loop++;
                        $gTFO->FetchTaxFeeArray($searchme->outselect);
                        $get_varx = $gTFO->outarray;
                        $gTempAssess = new TaxFee;
                $gTempAssess->ReplaceValue($get_varx[complex_tfoid],$owner_id,$business_id,$gethuh[natureid]);
                $replace_var = $gTempAssess->outarray;
                $complex_formula = str_replace($get_varx[var_complex],$replace_var[compval],$complex_formula);
                }
                @eval("\$totind=$is_dec$complex_formula;");
                $show_complex = $gethuh[amtformula];
      $gethuh[amtformula]='complex formula: ';



                                                } elseif ($gethuh[mode]==1 || $gethuh[mode]==0) { //normal
                $formula_rep = str_replace("X0",$gethuh[multi],strtoupper($gethuh[amtformula]));
                @eval("\$totind=$is_dec$formula_rep;");
                                                }
                                        } elseif ($gethuh[indicator]==3) { //get range
                                                $gethuh[amtformula]='range';
                                        $getrange = SelectMultiTable($dbtype,$dbLink,
                                                "ebpls_buss_taxrange","rangeamount",
                                                "where taxfeeid=$gethuh[taxfeeid] and
                                                 rangelow = $gethuh[multi]");
                                        $haveex = NumRows($dbtype,$getrange);
                                if ($haveex<>1) {
                                   $getrange = SelectMultiTable($dbtype,$dbLink,
                                        "ebpls_buss_taxrange","rangeamount",
                                        "where taxfeeid=$gethuh[taxfeeid] and
                                         rangelow <= $gethuh[multi] and
                                         rangehigh >= $gethuh[multi]");
                                        $lookrange = NumRows($dbtype,$getrange);
                                                if ($lookrange==0) {
                                                $getrange =  SelectMultiTable($dbtype,$dbLink,
                                                "ebpls_buss_taxrange","rangeamount",
                                                "where taxfeeid=$gethuh[taxfeeid]
                                                order by rangeid desc limit 1");
                                                }
                                }

                                  $range = FetchRow($dbtype,$getrange);

                                        if (is_numeric($range[0])) {
                                                $totind=$range[0];
                                        } else {
                                                $gethuh[amtformula]=$range[0];
                $formula_rep = str_replace("X0",$gethuh[multi],strtoupper($range[0]));
                @eval("\$totind=$is_dec$formula_rep;");
                                        }
                                        $totind = round($totind,2);

                                } else { //constant
                                                $totind = $gethuh[multi] * $gethuh[amtformula];
                                }
                        $gethuh[multi] = round($gethuh[multi],2);
                        $grandamt = $grandamt + $totind;

$gethuh[tfodesc]=stripslashes($gethuh[tfodesc]);


        if ($getd[indicator]==2 or $getd[indicator]==3 and $getd[basis]<>3) { //formula and range
                if ($stt=='New') {

                         if ($getd[basis]==3) {
                                $basis = 0;
                        } else {
                                $basis = $getn[2];
                        }
                } else {
                         if ($getd[basis]==3) {
                                $basis = 0;
                        } else {
                                $basis=$getn[3];
                        }
   }

                        $btc = number_format($basis, 2);


                if ($getd[basis]<>3) {
                print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                <td align=right><input type=hidden  name=x[$varx] size=5 value=$gethuh[multi] $lockit >$btc</td>";
                } else {
                 print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                <td align=right>$gethuh[uom] &nbsp <input type=text  name=x[$varx] size=5 value=$gethuh[multi] $lockit ></td>";
        $tbut=1;
                }


        } elseif ($getd[basis]==3) {

              print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                <td align=right>$gethuh[uom] &nbsp <input type=text  name=x[$varx] size=5 value=$gethuh[multi] $lockit ></td>";
        $tbut=1;
        } else { //constant

                print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                <td align=right><input type=hidden  name=x[$varx] size=5 value=$gethuh[multi] $lockit>$gethuh[multi] </td>";
        }
if ($gethuh[mode]==2) {
$taxfeeid = $gethuh[taxfeeid];
$nat_id = $gethuh[natureid];
/*
$fr = $d."(".$xv.$gethuh[4];
$gethuh[4]='complex formula';
require 'includes/complex.php';*/
//$taxdue=number_format($totind, 2);
}
include'includes/minimum_compute.php';
$getd[tfodesc]=addslashes($getd[tfodesc]);
$chkiffee = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo","where tfodesc='$getd[tfodesc]'");
$chkiffee = FetchArray($dbtype,$chkiffee);
if ($chkiffee[taxfeetype]<>1){
        $totfee=$totfee+$totind;
}
$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
                        ebpls_buss_tfo c","a.*",
                        "where a.business_id=$business_id and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$gethuh[tfoid] and b.tfoid=$gethuh[tfoid] and
                        b.active=1");
$getfeex = NumRows($dbtype,$getex);
if ($getfeex>0) {

        $exemptedfee = $exemptedfee + $gethuh[amtformula];
        $usemin = 'Fee Exempted';
        $rtag='';
        $gethuh[amtformula]=0;
        $taxdue='0.00';
        $totind=0;
}

$totind = round($totind,2);
$taxdue = number_format($totind,2);
$tt = $tt + $totind;
$grandamt = $tt;
if (is_numeric($gethuh[amtformula])) {
$gethuh[amtformula]=number_format($gethuh[amtformula],2);
}
print "<td align=right  width=25%>
        <input type=hidden name=natureid[$varx] value=$gethuh[natid]>
        <input type=hidden name=z[$varx] value=$gethuh[amtformula]>$usemin1 &nbsp;
        $gethuh[amtformula] $show_complex
        <input type=hidden name=taxfeeid[$varx] value=$gethuh[taxfeeid]></td>
        <td align=right width=25%>
        <input type=hidden name=y$varx value=$totind size=5 readonly>$usemin &nbsp; $taxdue</td>
        </tr>";
$usemin='';
$havemayor = strpos(strtolower($getd[tfodesc]),'mayor');
if ($getd[taxfeetype]<>1 || $havemayor>-1) {
        $feecompute = $feecompute + $totind;
        $tt = $tt - $totind;
        $grandamt = $tt;
  }



/* ============================*/

}

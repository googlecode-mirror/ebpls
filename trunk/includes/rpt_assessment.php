<?php

/*

			GGGGGGGGGGGGGGGGGGG
	GGGGGGGGGGGGGGGGGGGGGGG
	GGGGGGGG
	GGGGGGGG
	GGGGGGGG
	GGGGGGGG
	GGGGGGGG
	GGGGGGGG
	GGGGGGGG
	GGGGGGGG			GGGGGGGGG			
	GGGGGGGG			GGGGGGGGG
	GGGGGGGG				GGGGG
	GGGGGGGG				GGGGG
	GGGGGGGG				GGGGG
	GGGGGGGGGGGGGGGGGGGGGGGGGGGGG
	GGGGGGGGGGGGGGGGGGGGGGGGGGGGG
	GGGGGGGGGGGGGGGGGGGGGGGG
	GGGGGGGGGGGGGGG
	

*/


//get garbage zone

$getbar = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, ebpls_barangay b",
			"b.g_zone",
                        "where a.owner_id=$owner_id and a.business_id=$business_id 
			and a. business_barangay_code=b.barangay_code");
$getbara = FetchRow($dbtype,$getbar);
$g_zone=$getbara[0];



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

$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature a, ebpls_buss_nature b",
			"a.bus_code, b.naturedesc, a.cap_inv, a.last_yr, a.transaction,a.linepaid",
                        "where owner_id=$owner_id and business_id=$business_id 
			and a.bus_code=b.natureid and active = 1 $retstr");
while ($getn = FetchRow($dbtype,$getnat)){
	$stt=$stat;
	if ($stt=='New') {
        	$tftype=1;
	} elseif ($stt=='ReNew') {
        	$tftype=2;
	} elseif ($stt=='Retire') {
        	$tftype=3;
	}

//print labels

$pdf->Cell(30,5,'LINE OF BUSINESS',0,0,'C');
$pdf->SetX(45);
$pdf->Cell(30,5,$getn[1],1,1,'C');

$pdf->Cell(30,5,'TAXES/FEES',0,1,'C');
$pdf->SetX(15);
$pdf->Cell(30,5,'BASE VALUE/INPUT',1,0,'C');
$pdf->SetX(45);
$pdf->Cell(20,5,'AMOUNT/FORMULA',1,0,'C');
$pdf->SetX(65);
$pdf->Cell(20,5,'TAX DUE',1,0,'C');



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

                $getyears = SelectMultiTable($dbtype,$dbLink,
                                "tempassess a,  ebpls_buss_tfo c",
                                "c.or_print,a.date_create,c.tfodesc, c.counter",
                                "where a.tfoid=c.tfoid and a.tfoid = $getd[tfoid] and a.active=0 
				 order by a.date_create desc limit 1");
                $getyr  = FetchArray($dbtype,$getyears);

		$bill_date = date('Y') - date('Y',strtotime($getyr[date_create]));

		if ($getyr[counter]==1) {
			$cnt = 0;
			$fg = date('Y',strtotime($getyr[date_create]));
			while ($cnt<$getyr[or_print]) {
			$cnt++;
			$getyears = SelectMultiTable($dbtype,$dbLink,
                                "tempassess a,  ebpls_buss_tfo c",
                                "c.or_print,a.date_create,c.tfodesc, c.counter",
                                "where a.tfoid=c.tfoid and a.tfoid = $getd[tfoid] 
                                 and a.date_create like '$fg%'");
        	        $geyr  = NumRows($dbtype,$getyears);
		
				if ($geyr==0) {
				$cnt = $getyr[or_print]+1;
				$bill_date=$bill_date+1;
				}

			$fg=$fg-1;
			}

                }

 
	
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
//		echo "$get_varx[var_complex],$replace_var[compval],$complex_formula";
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
	
      


}

?>

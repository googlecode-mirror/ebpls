<?php

if ($comm_ass==1) {
	$wopi = 0;
	
	while ($wopi<$gr) {
	
		if (!is_numeric($gross[$wopi]) || $gross[$wopi]<1 || !is_numeric($inputy[$wopi])) {
?>
	<body onload="alert('Invalid Gross Sales');"></body>
<?php
		$cango = 0;
		} else {
		$cango=1;
		}
		$wopi++;
	}
	
	if ($cango=='1') {
		
		//truncate years
		
		$tempval=$yearar;
		$xx = strpos($tempval,",");
		$p=0;
		while ($xx>0) {
	
		$year[$p]= substr($tempval,0,$xx);
		$tempval = substr($tempval,$xx+1);
		$xx = strpos($tempval,",");
	
		$p++;
		}
		
		
		
		
	$wopi=0;
	 $lop=-1;
	$dbd=0;
	$varx=0;
	$vnyz=0;
	$robertTax = 0;
	$robertFee = 0;
	
	while ($wopi<$gr) {
		$gopi=0;
		$yearnow1='';
		
		//while ($gopi<$p) {
			
	$lop++;
	
	$tempid = $tempbus[$wopi];
	$lastyr = $gross[$wopi];
	$stat=$stat;
	$com='assess';
	$tftnum=1;
	$tft = '';
	$grandamt = 0;
	$totfee=0;
	$exemptot=0;
	$totind=0;
	$regfee=0;
	$robertTax = 0;
	$robertFee = 0;
	$exemptedfee=0;
	$robertgrand=0;
	 $getbus=SelectDataWhere($dbtype,$dbLink,"tempbusnature",
					"where tempid=$tempid");
			        $getbu = FetchRow($dbtype,$getbus);
			        $owner_id = $getbu[5];
			        $business_id = $getbu[6];
			        $bus_code = $getbu[1];
			        $bus_nature = $getbu[2];
			        $oldlay = $getbu[4];
			       //	if ($yearnow1=='') {
				       	
				       	$yearnow1 = $yearko[$vnyz];
				       //	echo "$yearnow1 $yearnow -  $delayed  + $dbd $bus_nature $lastyr<Br>";
				     	
				    $vnyz++;
			       	//}
			       	
			       	$inputdate = $yearnow1."-12-31";
	$inputdate = date('Y-m-d',strtotime($inputdate));
			//echo $yearnow1."<BR>";       	
			       
				     //  echo $inputdate."   $gr<BR>";
			        
		        
			       
				      
				   
			        $result = InsertQuery($dbtype,$dbLink,"tempbusnature","",
		                        "'', '$bus_code', '$bus_nature',$oldlay,
                	        	$lastyr,$owner_id,$business_id, '$inputdate',
	                        	0, 1,'','ReNew','0'");
        			$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
	                             "tempid='$tempid'");
	
	
	//commence assessment wish me luck
	
$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"sassess","");
$prefset = FetchArray($dbtype,$staxfee);
$sassesscomplex = $prefset['sassess']; // per estab

//get garbage zone


$getbar = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, ebpls_barangay b",
			"b.g_zone",
                        "where a.owner_id=$owner_id and a.business_id=$business_id 
			and a. business_barangay_code=b.barangay_code");
$getbara = FetchRow($dbtype,$getbar);
$g_zone=$getbara[0];


if ($stat=='New') {
	$tftype=2;
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
	$tfttype=2;
}
//update old assess


$yearold = $yearnow1 - 1;
$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 0","owner_id='$owner_id' and
                 business_id='$business_id'  and natureid='$bus_code'
		 and date_create like '$yearold%'");

$ui = DeleteQuery($dbtype,$dbLink,"tempassess",
                 "owner_id=$owner_id and
                 business_id=$business_id and active=1 and natureid='$bus_code' and date_create like '$yearnow1%'");



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


	$retstr='';


$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature a, ebpls_buss_nature b",
			"a.bus_code, b.naturedesc, a.cap_inv, a.last_yr, a.transaction,a.linepaid",
                        "where owner_id=$owner_id and business_id=$business_id 
			and a.bus_code=b.natureid and active = 1 and a.bus_code = '$bus_code' $retstr");
while ($getn = FetchRow($dbtype,$getnat)){
	
	$grandamt=0;
	
$capt++;
	    $checktempass = mysql_query("select * from tempassess where natureid='$getn[0]' and
	                					owner_id = '$owner_id' and business_id='$business_id' and
	                					active=1") or die(mysql_error());;
	                $tempass = mysql_num_rows($checktempass);
	                
	                if ($tempass==0) {
		                $PROCESS='';
		             
	                } else {
		               // $PROCESS='COMPUTE';
		               
	                }
	$stt=$getn[4];
	if ($stt=='New') {
        	$tftype=2;
	} elseif ($stt=='ReNew') {
        	$tftype=2;
	} elseif ($stt=='Retire') {
        	$tftype=2;
	} 
	
$getcapv = SelectMultiTable($dbtype,$dbLink,"tempbusnature a, ebpls_buss_nature b",
			"a.bus_code, b.naturedesc, a.cap_inv, a.last_yr, a.transaction,a.linepaid",
                        "where owner_id=$owner_id and business_id=$business_id and
                        a.bus_code = $getn[0]
			and a.bus_code=b.natureid and active = 1 $retstr");	
			$getcap=mysql_fetch_assoc($getcapv);
			if (strtolower($getcap[transaction])=="new") {
				$getin = $getcap[cap_inv];
				$labelme = 'Capital Investment:';
			} else {
				$getin = $getcap[last_yr];
				$labelme = 'Gross Sales:';
			}

//print labels

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
/*
		$getyears = SelectMultiTable($dbtype,$dbLink,
                                " ebpls_buss_tfo c",
                                "c.counter",
                                "where c.tfoid = $getd[tfoid]");
                                                                                                                             
                $getyr  = FetchArray($dbtype,$getyears);

		if ($getyr[counter]==1) {
			$nyorder = 'asc';
		} else {
			$nyorder = 'desc';
		}
*/
                $getyears = SelectMultiTable($dbtype,$dbLink,
                                "tempassess a,  ebpls_buss_tfo c",
                                "c.or_print,a.date_create,c.tfodesc, c.counter",
                                "where a.tfoid=c.tfoid and a.tfoid = $getd[tfoid] and a.active=0 
				 and date_create like '$yearnow%' order by a.date_create desc limit 1");
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

                if ($getyr[or_print] > $bill_date) {
//			if ($getyr[counter]==0) {
                        $varx = $varx - 1;
//			}
                print "<input type=hidden name=minus_hm value=1>";
                }

	
                if ($getyr[or_print] <= $bill_date || $getyr[or_print]=='') {

	
	$nat_id = $getn[0];

        if ($getd[indicator]==2 and $stt=='New') { //if formula
                $indi=1;
                $basis = $getn[2];
			if ($getd[basis]==3) {
				$basis = $inputy[$wopi];
				//$basis=0; //dito ay input value
			}

		                if ($getd[mode]==2) { //complex
			include_once "class/TaxFeeOtherChargesClass.php";
			$searchme = new TaxFee;
			$searchme->CountTaxFeeComplex($getd[taxfeeid]);
                        $how_many = $searchme->outnumrow;
			$loop=0;
		//sub X0
		$complex_formula =str_replace("X0",$basis,strtoupper($getd[amtformula]));
		$gTFO = new TaxFee;
		
		if ($sassesscomplex==1) {
		
		while ($loop<$how_many) {
			$loop++;
			$gTFO->FetchTaxFeeArray($searchme->outselect);
			$get_varx = $gTFO->outarray;
			$gTempAssess = new TaxFee;
			
		$gTempAssess->ReplaceValue($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
		$replace_var = $gTempAssess->outarray;
		  $getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					and a.active=0 and a.tfoid=b.tfoid and a.tfoid='$get_varx[complex_tfoid]' and
	       					a.natureid='$getn[0]'");   
                     $havemat = mysql_num_rows($getyears);
					  $getyr  = mysql_fetch_assoc($getyears);	
							
                        
                $gTempAssess->ReplaceValue($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
                $replace_var = $gTempAssess->outarray;
                
                				if ($havemat>0) { //have prev record
								//check if will bill
								$howmanydec = $havemat/$getyr[counter];
								$isdeci = strpos($howmanydec,".");
								
									if ($havemat % $getyr[counter]<>0) {
												$replace_var[compval] = 0;
									}
								}
		
		
	        $complex_formula = str_replace($get_varx[var_complex],$replace_var[compval],$complex_formula);
                }
                
      	} else {  
	      	
	      	 while ($loop<$how_many) {
			$loop++;
			$gTFO->FetchTaxFeeArray($searchme->outselect);
			$get_varx = $gTFO->outarray;
			$gTempAssess = new TaxFee;
			
		$gTempAssess->ReplaceValueDef($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
		$replace_var = $gTempAssess->outarray;
		
					if ($replace_var[compval]>0) {
							$repval = $replace_var[compval];
					} else {
							$repval = $replace_var[defamt];
							$havegar = strpos(strtolower(trim($replace_var[tfodesc])),'garbage');


								if ($g_zone==0) {
									if ($havegar>-1) {
										$exemptedfee = $exemptedfee + $repval;
										$repval=0;
										
									}
								}
							
						$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
			                        ebpls_buss_tfo c","a.*",
									"where a.business_id=$business_id and
									a.business_category_code=b.business_category_code and
									c.tfoid='$replace_var[tfoid]' and b.tfoid='$replace_var[tfoid]' and
									b.active=1");
						$getfeex = NumRows($dbtype,$getex);
						if ($getfeex>0) {
							$exemptedfee = $exemptedfee + $repval;
							$repval=0;
							
						}
							
							
							
							
						}
	        $complex_formula = str_replace($get_varx[var_complex],$repval,$complex_formula);
                }
                
        }   
                
                
                @eval("\$totind=$is_dec$complex_formula;");
		$show_complex = $getd[amtformula];
		$getd[amtformula]='complex formula: ';
				
				} else { //normal formula
					 //formula replace
	                $formula_rep = str_replace("X0",$basis,strtoupper($getd[amtformula]));
			@eval("\$totind=$is_dec$formula_rep;");
				}
		$rtag ='';

        } elseif ($getd[indicator]==2 and $stt=='ReNew' || $stt=='Retire') {
                $indi=1;
                $basis = $getn[3];

		 if ($getd[basis]==3) {
			 $basis = $inputy[$wopi];
                             //   $basis=0; //dito ay input value
                 }


		if ($getd[mode]==2) {

		 include_once "class/TaxFeeOtherChargesClass.php";
                        $searchme = new TaxFee;
                        $searchme->CountTaxFeeComplex($getd[taxfeeid]);
                        $how_many = $searchme->outnumrow;
                        $loop=0;
                //sub X0
                $complex_formula =str_replace("X0",$basis,strtoupper($getd[amtformula]));
                $gTFO = new TaxFee;
//                 while ($loop<$how_many) {
//                         $loop++;
//                         $gTFO->FetchTaxFeeArray($searchme->outselect);
//                         $get_varx = $gTFO->outarray;
//                         $gTempAssess = new TaxFee;
//                 $gTempAssess->ReplaceValue($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
//                 $replace_var = $gTempAssess->outarray;
//                 
//                 $complex_formula = str_replace($get_varx[var_complex],$replace_var[compval],$complex_formula);
//                 }

$gTFO = new TaxFee;
          if ($sassesscomplex==1) {      
                while ($loop<$how_many) {
                        $loop++;
                        $gTFO->FetchTaxFeeArray($searchme->outselect);
                        $get_varx = $gTFO->outarray;
                        $gTempAssess = new TaxFee;
                $gTempAssess->ReplaceValue($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
                $replace_var = $gTempAssess->outarray;
                
                
                 $getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					and a.active=0 and a.tfoid=b.tfoid and a.tfoid='$get_varx[complex_tfoid]' and
	       					a.natureid='$getn[0]'");   
                     $havemat = mysql_num_rows($getyears);
					  $getyr  = mysql_fetch_assoc($getyears);	
							
                        
                $gTempAssess->ReplaceValue($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
                $replace_var = $gTempAssess->outarray;
                
                				if ($havemat>0) { //have prev record
								//check if will bill
								$howmanydec = $havemat/$getyr[counter];
								$isdeci = strpos($howmanydec,".");
								
									if ($havemat % $getyr[counter]<>0) {
												$replace_var[compval] = 0;
									}
								}
                
                
                $complex_formula = str_replace($get_varx[var_complex],$replace_var[compval],$complex_formula);
               // echo $complex_formula."<BR>";
                }
               // echo $complex_formula."<BR>";
                         
      	} else {  
	      	
	      	 while ($loop<$how_many) {
			$loop++;
			$gTFO->FetchTaxFeeArray($searchme->outselect);
			$get_varx = $gTFO->outarray;
			$gTempAssess = new TaxFee;
			
		$gTempAssess->ReplaceValueDef($get_varx[complex_tfoid],$owner_id,$business_id,$getn[0]);
		$replace_var = $gTempAssess->outarray;
		
					if ($replace_var[compval]>0) {
							$repval = $replace_var[compval];
					} else {
							$repval = $replace_var[defamt];
							
							$havegar = strpos(strtolower(trim($replace_var[tfodesc])),'garbage');


								if ($g_zone==0) {
									if ($havegar>-1) {
										$exemptedfee = $exemptedfee + $repval;
										$repval=0;
										
									}
								}
								
								$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
			                        ebpls_buss_tfo c","a.*",
									"where a.business_id=$business_id and
									a.business_category_code=b.business_category_code and
									c.tfoid='$replace_var[tfoid]' and b.tfoid='$replace_var[tfoid]' and
									b.active=1");
						$getfeex = NumRows($dbtype,$getex);
						if ($getfeex>0) {
							$exemptedfee = $exemptedfee + $replace_var[defamt];
							$repval=0;
							
						}
						/////////////////
							$havemat=0;
						$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
							       	a.owner_id='$owner_id' and a.business_id='$business_id' 
							       	and date_create not like '$yearnow1%'
							       	and a.tfoid=b.tfoid and a.tfoid='$replace_var[tfoid]' order by date_create asc");
							       					
						$yearsi = mysql_query("select  a.*  from tempassess a, ebpls_buss_tfo b where
							       	a.owner_id='$owner_id' and a.business_id='$business_id' and date_create not like '$yearnow1%'
							       	 and a.tfoid=b.tfoid and a.tfoid='$replace_var[tfoid]' ");							
							
						$havemat = mysql_num_rows($yearsi);
				
          $getyr  = mysql_fetch_assoc($getyears);
			if ($havemat>0) { //have prev record
			//check if will bill
			@$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
			
			if ($isdeci>0) { // will not bill
	
				$repval=0;

			}
		}	
						///////////////////////	
						}
	        $complex_formula = str_replace($get_varx[var_complex],$repval,$complex_formula);
	      
                }
        }  


               // echo $complex_formula."===";
                @eval("\$totind=$is_dec$complex_formula;");
            	$compval = $totind;
                $show_complex = $getd[amtformula];

		/*
			$taxfeeid = $getd[2];
			$fr = $d."(".$xv.$getd[1];*/
			$getd[amtformula]='complex formula: ';
//                        require 'includes/complex.php';
                } else {
		$formula_rep = str_replace("X0",$basis,strtoupper($getd[amtformula]));
                @eval("\$totind=$is_dec$formula_rep;");
		}
		$rtag ='';
         } elseif ($getd[indicator]==3) {

		if ($stt=='New') {
			if ($getd[basis]==3) {
				$basis = $inputy[$wopi];
				//$basis = 0; //dito ay input value
			} else {
				$basis = $getn[2];
			}
		} else {

			if ($getd[basis]==3) {
								$basis = $inputy[$wopi];
                               // $basis = 0; //dito ay input value
                        } else {
				$basis=$getn[3];
			}
		}

		$getrange = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxrange",
				"rangeamount, rangelow",
				"where taxfeeid=$getd[taxfeeid] and rangelow = $basis");
		$haveex = NumRows($dbtype,$getrange);
		
		if ($haveex<>1) {

                $getrange = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxrange",
				"rangeamount",
				"where taxfeeid=$getd[taxfeeid] and rangelow <= $basis and
                                rangehigh >= $basis");
                $lookrange = NumRows($dbtype,$getrange);

                        if ($lookrange==0 || $lookrange=='') {
                                $getrange =  SelectMultiTable($dbtype,$dbLink,
						"ebpls_buss_taxrange",
						"rangeamount",
						"where taxfeeid=$getd[taxfeeid] 
						 order by rangeid desc limit 1");
                        }
		}
                $range = FetchRow($dbtype,$getrange);
                        if (is_numeric($range[0])) {
                                $totind=$range[0];
				$rtag ='range';
				$compvalrange=$totind;
				;
                        } else {
			$getd[amtformula]=$range[0];
			$formula_rep = str_replace("X0",$basis,strtoupper($getd[amtformula]));
			
	                @eval("\$totind=$is_dec$formula_rep;");
			$compvalrange=$totind;

                        }
                       
		$rtag ='range';
        } else {
                $indi=0;
                $basis=1;
                $totind = $getd[amtformula];
		$rtag ='';
        }
include'includes/minimum_compute.php';
$getd[tfodesc] = addslashes($getd[tfodesc]);
$chkiffee = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo","where tfodesc='$getd[tfodesc]'");
$chkiffee = FetchArray($dbtype,$chkiffee);
if ($chkiffee[taxfeetype]<>1){
	$totfee=$totfee+$totind;
	
}

$getd[tfodesc]=stripslashes($getd[tfodesc]);


if (is_numeric($getd[amtformula])) {
	$nfgetd = number_format($getd[amtformula],2);
} else {
	$nfgetd = $getd[amtformula];
}
$ttnf = $totind;
$ttn=number_format($ttnf,2);
if ($getd[mode]==2) {
//$nfgetd='complex formula';
$nfgetd = $getd[amtformula];
}

if ($rtag=='range') {
	$nfgetd='';
	$getd[amtformula]=$range[0];
	$ttn=number_format($compvalrange,2);
	$totind = $compvalrange;
	$compval = $totind;
}

//check for exempted fees
//$getd[7] tfoid


$havegar = strpos(strtolower(trim($getd[tfodesc])),'garbage');


if ($g_zone==0) {
	if ($havegar>-1) {
		$exemptedfee = $exemptedfee + $getd[amtformula];
		$usemin = 'Not in Garbage Zone';
		$nfgetd = '0.00';
		$rtag='';
		$ttn = '0.00';
		$getd[amtformula]=0;
		$ttnf=0;
		$totind=0;
		$chngcompval = 1;
	}
}

$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
                        ebpls_buss_tfo c","a.*",
			"where a.business_id=$business_id and
			a.business_category_code=b.business_category_code and
			c.tfoid=$getd[tfoid] and b.tfoid=$getd[tfoid] and
			b.active=1");
$getfeex = NumRows($dbtype,$getex);
if ($getfeex>0) {
	$exemptedfee = $exemptedfee + $getd[amtformula];
	$usemin = 'Fee Exempted';
	$nfgetd = '0.00';
	$rtag='';
	$ttn = '0.00';
	$getd[amtformula]=0;
	$ttnf=0;
	$totind=0;
	$chngcompval = 1;
}
if ($sassesscomplex=='1') {

$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					and a.active=0 and a.tfoid=b.tfoid and a.tfoid='$getd[tfoid]' and
	       					a.natureid='$getn[0]'");
} 		       					
      					
	       					
          $getyr  = mysql_fetch_assoc($getyears);
		  $havemat = mysql_num_rows($getyears);
		$bill_date = date('Y') - date('Y',strtotime($getyr[date_create])); //get last bill year

		if ($havemat>0) { //have prev record
			//check if will bill
			$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
			
			if ($havemat % $getyr[counter]<>0) {
				$exemptedfee = $exemptedfee + $totind;
				$totind=0;		
			}
}
$totind = round($totind,2);


$grandamt = $grandamt + $totind;
$robertgrand = $robertgrand + $totind;
$usemin='';
$havemayor = strpos(strtolower($getd[tfodesc]),'mayor');
  
if ($getd[taxfeetype]<>1 || $havemayor>-1) {
	$totmpf = $totmpf + $totind;
        $feecompute =  $feecompute + $totind;
	$grandamt = $grandamt - $totind;
	$nyotfee = $feecompute + $nyotfee;
  }
//delete if existing
/*        $result = DeleteQuery($dbtype,$dbLink,"tempassess",
			"assid='$varx' and owner_id=$owner_id 
			and business_id=$business_id
			and active=1 and transaction='$stat'");
*/
//save to tempassess
if ($indi==0) {


	if ($rtag<>'range') {
		$compval = $basis*$getd[amtformula];
	} else {
		$compval=$compvalrange;
		$totind=$compval;
		
			if ($totind<$chkcp) { //use minimum
				$totind=$chkcp;
				$compval=$chkcp;
			}

	}
		if ($compval=='') {
		$formula_rep = str_replace("X0",$basis,strtoupper($getd[amtformula]));
                @eval("\$compval=$is_dec$formula_rep;");		
		}

	if ($chngcompval==1) {
		$compval=0;
		$chngcompval=0;
	}	

 



} else {
if ($getd[amtformula]<>'complex formula') {
	if ($chk1[0]<>1) { //use min
	
		if (!is_numeric($getd[amtformula])) {
		$formula_rep = str_replace("X0",$basis,strtoupper($getd[amtformula]));
                @eval("\$compval=$is_dec$formula_rep;");
		}
	} else {
		$compval = $getd[amtformula];
	}
} else {

//complex code
//eval ("\$compval=$fr$ff$outamt$addcp;");
}
 




}
if ($sassesscomplex=='') {
 $getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					and a.active=0 and a.tfoid=b.tfoid and a.tfoid='$getd[tfoid]'");
} else {
$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					and a.active=0 and a.tfoid=b.tfoid and a.tfoid='$getd[tfoid]' and
	       					a.natureid='$getn[0]'");
} 		       					
      					
	       					
          $getyr  = mysql_fetch_assoc($getyears);
		  $havemat = mysql_num_rows($getyears);
		$bill_date = date('Y') - date('Y',strtotime($getyr[date_create])); //get last bill year

		if ($havemat>0) { //have prev record
			//check if will bill
			$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
			
			//if ($isdeci>0) { // will not bill
			if ($havemat % $getyr[counter]<>0) {
			
$compval = 0;

	$result = InsertQuery($dbtype,$dbLink,"tempassess",
	  	      "(assid, owner_id, business_id, natureid, taxfeeid, 
			multi, amt, formula, compval, tfoid,active, transaction,date_create)",
            	      "$varx,$owner_id, $business_id,$getn[0],
	               $getd[taxfeeid],$basis,0,$compval,$compval,
		       $getd[tfoid],1, '$stat','$inputdate'");



			} else { //will bill
			

			        
	$result = InsertQuery($dbtype,$dbLink,"tempassess",
	  	      "(assid, owner_id, business_id, natureid, taxfeeid, 
			multi, amt, formula, compval, tfoid,active, transaction,date_create)",
            	      "$varx,$owner_id, $business_id,$getn[0],
	               $getd[taxfeeid],$basis,0,'$getd[amtformula]',$compval,
		       $getd[tfoid],1, '$stat','$inputdate'");
			    
				
			}
		} else { //new record
		
			 
			         



	$result = InsertQuery($dbtype,$dbLink,"tempassess",
	  	      "(assid, owner_id, business_id, natureid, taxfeeid, 
			multi, amt, formula, compval, tfoid,active, transaction,date_create)",
            	      "$varx,$owner_id, $business_id,$getn[0],
	               $getd[taxfeeid],$basis,0,'$getd[amtformula]',$compval,
		       $getd[tfoid],1, '$stat','$inputdate'");
	 
				
		}

	
$varx++;	
	
	
	// end assessment sana ok
	
	}
	
	
}



}

}


$gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag=FetchArray($dbtype,$gettag);



if ($gettag[sassess]=='' and $yearnow1<>$currentyearna) {
	$currentyearna = $yearnow1;
$resultf = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo",
			"where tfoindicator='1' and tfostatus='A' and taxfeetype<>'1' ");

$cntfee = NumRows($dbtype,$resultf);
$feetype = 1;

	while ($getf=FetchRow($dbtype,$resultf))
	{
		$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, 
			fee_exempt b, ebpls_buss_tfo c","a.*",
                        "where a.business_id=$business_id and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$getf[0] and b.tfoid=$getf[0] and
                        b.active=1");
		$getfeex = NumRows($dbtype,$getex);
		if ($getfeex>0) {
			$exemptedfee = $exemptedfee + $getf[6]; 
		        $usemin = 'Fee Exempted ';
		        $getf[6]=0;
		}
		
	$havegar = strpos(strtolower($getf[1]),'garbage');


if ($g_zone==0) {
	if ($havegar>-1) {
		$exemptedfee = $exemptedfee + $getd[amtformula];
		$usemin = 'Not in Garbage Zone ';
		$rtag='';
        $getf[6]=0;
        
        $totind=0;
	}
}	
$havemat=0;

$getys = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id' 
	       					 and a.tfoid=b.tfoid and a.tfoid='$getf[0]' order by date_create desc");
$gety  = mysql_fetch_assoc($getys);	  

					
$yearsi = mysql_query("select  a.*  from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id' 
	       					and a.date_create not like '$yearnow1%'
	       					 and a.tfoid=b.tfoid and a.tfoid='$getf[0]'");							
							
		$havemat = mysql_num_rows($yearsi);
				
   
	if ($havemat>0 and $PROCESS<>"COMPUTE" and $usemin=='') { //have prev record
			//check if will bill
			@$howmanydec = $havemat/$gety[counter];
			$isdeci = strpos($howmanydec,".");
		
			if ($isdeci>0) { // will not bill
		
$getf[6] = 0;

$result = InsertQuery($dbtype,$dbLink,"tempassess",
			            		"(assid, owner_id, business_id, natureid, 
						 taxfeeid, multi, amt, formula, compval,
				                 tfoid,active, transaction,date_create)",
			            		"$varx,$owner_id, $business_id,'',
					         '',1,0,'$getf[6]',0, $getf[0],1,
					         '$stat','$inputdate'");



			} else { //will bill
		
			    if ($PROCESS<>'COMPUTE' and  $itemID_=='6204') {
	
			         $result = InsertQuery($dbtype,$dbLink,"tempassess",
			            		"(assid, owner_id, business_id, natureid, 
						 taxfeeid, multi, amt, formula, compval,
				                 tfoid,active, transaction,date_create)",
			            		"$varx,$owner_id, $business_id,'',
					         '',1,$getf[6],'$getf[6]',$getf[6], $getf[0],1,
					         '$stat','$inputdate'");
			        $varx++;
				}
			}
		} else { //new record
		
			   if ($PROCESS<>'COMPUTE') {
		
			         $result = InsertQuery($dbtype,$dbLink,"tempassess",
			            		"(assid, owner_id, business_id, natureid, 
						 taxfeeid, multi, amt, formula, compval,
				                 tfoid,active, transaction,date_create)",
			            		"$varx,$owner_id, $business_id,'',
					         '',1,'$getf[6]','$getf[6]',$getf[6], $getf[0],1,
					         '$stat','$inputdate'");
			        $varx++;
			        } else {
					$geth = mysql_query("select * from tempassess where owner_id='$owner_id' and business_id='$business_id'
										and active=1 and tfoid='$getf[0]' order by date_create desc");
					$getputa = mysql_fetch_assoc($geth);
					$getf[6]=$getputa[compval];
				}
				
		}
       
	




	$regfee = $regfee + $getf[6];
	$totfee = $totfee+$getf[6];
	
	$usemin='';
	}



} //sassess


//echo "$grandamt  $totfee  <BR>";
//get prev pin

$getp = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise_permit ","where owner_id =$owner_id 
			and business_id =$business_id and active = 1");
$getp = FetchArray($dbtype,$getp);
$pin = $getp[pin];

//deactivate previous record



$gtp = mysql_query("update ebpls_business_enterprise_permit  set active = 0 where
						owner_id ='$owner_id' and business_id ='$business_id'");


//will create permit record
$res = InsertQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit","
				(business_id, owner_id, for_year,
				application_date,input_by, transaction, 
				paid, steps, pin, active)",
                		"$business_id, $owner_id,'$yearnow1', 
				'$inputdate', '$usern', 'ReNew', 
				0,'For Assessment', '$pin', 1");


$wopi++;



$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
	                             "owner_id ='$owner_id' and business_id ='$business_id'");

//$gopi++;
//}

$robertFee = $totfee - $exemptedfee;  // ok

if ($gettag[sassess]=='') {
	$robertTax = $grandamt - $exemptedfee ;
} else {
	$robertTax = $robertgrand - $robertFee;	
}

//echo "$grandamt     $robertTax ===$robertFee <BR>";
include "includes/business_penalty_delayed.php";

$grandamt = 0;
	$totfee=0;
	$exemptot=0;
	$totind=0;
	$regfee=0;
$exemptedfee=0;
} //end wopi
} // end cango
//sakit na ulo ko
$gtp = mysql_query("update ebpls_business_enterprise_permit  set active = 0 where
						owner_id ='$owner_id' and business_id ='$business_id'");
// $gtp = mysql_query("update ebpls_business_enterprise_permit  set active = 1 where
// 						owner_id ='$owner_id' and business_id ='$business_id' order by for_year desc limit 1");						
$yeary = $yearnow - 1	  ;   
$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=0",
	                             "owner_id ='$owner_id' and business_id ='$business_id'");
$wil = UpdateQuery($dbtype,$dbLink,"tempbusnature","active=1",
	                             "owner_id ='$owner_id' and business_id ='$business_id' and date_create like '$yeary%'");

	                             
	                             
$wil = UpdateQuery($dbtype,$dbLink,"tempassess","active=0",
	                             "owner_id ='$owner_id' and business_id ='$business_id'");

                         
$wil = UpdateQuery($dbtype,$dbLink,"tempassess","active=1",
	                             "owner_id ='$owner_id' and business_id ='$business_id' and date_create like '$yeary%'");
	                             
	                             
//will create permit record
$res = InsertQuery($dbtype,$dbLink,"ebpls_business_enterprise_permit","
				(business_id, owner_id, for_year,
				application_date,input_by, transaction, 
				paid, steps, pin, active)",
                		"$business_id, $owner_id,'$yearnow', 
				now(), '$usern', '$stat', 
				0,'For Assessment', '$pin', 1");
	                             
	                             ?>

<body onload="alert('Assessment Process Completed'); parent.location='index.php?part=4&class_type=Permits&itemID_=1221&owner_id=<?php echo $owner_id; ?>&com=<? echo $stat;?>&permit_type=Business&stat=<? echo $stat;?>&business_id=<?php echo $business_id; ?>&busItem=Business';"></body>

<?php

} //end comass
?>



<table border=0 cellspacing=0 cellpadding=0   width='100%'>
		<tr><td align="center" valign="center" class='header'  width='100%'> Business Enterprise Permit (Delayed Application)</td></tr>
		<tr><td align="center" valign="center" class='normal' height=10>&nbsp;</td></tr>
		<tr>
			<td align="center" valign="center">
			  <form name="_FRM" method="POST" action="" onSubmit="">
<input type=hidden  name =stat value='<?php echo $stat;?>'>				
          <table border=0 cellspacing=0 cellpadding=0 width='90%'>
            <tr> 
              <td align="center" valign="top" class='header2' colspan=4 > 
                Owner Information</td>
            </tr>
            <tr> 
	        <td align="right" valign="top" class='normal' colspan=4 width=20%>&nbsp;</td>
            </tr>
            <tr> 
              <td align="right" valign="top" class='normal' colspan=1> &nbsp; 
                 </td>
              <td align="left" valign="top" class='normal' colspan=3 width=20%>&nbsp;
					
<input type='hidden' name='owner_id' maxlength=25  value="<?php echo $owner_id; ?>"> 
              </td>
            </tr>
            <tr> 
<?php require_once "includes/owner_info.php";?>
</tr>
</table><br>

<table border="0" width="51%" id="table1">
<tr><td colspan=4 align="center" valign="center" class='header2' width='100%'> Line of Business </td></tr>
<?php
  //get bus nature
  $lope=0;
  $gr=0;
  				while ($lope<$delayed) {
	  				
?>
<tr><td></td><td >Gross Sales for <?php echo $yearnow - $delayed + $lope; ?></td></tr>		
<?php
$yearko = $yearnow - $delayed + $lope;
$yearar = $yearar.($yearnow- $delayed + $lope).",";
  $lope++;
  				
                $getnat =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id
                                  and active = 1");
                                  
	                while ($getit = FetchArray($dbtype,$getnat)){
					$getit[bus_nature]=stripslashes($getit[bus_nature]);
					
						//check if have inputted value
						$getinput = mysql_query("select * from ebpls_buss_taxfeeother where
												natureid = '$getit[bus_code]' and basis=3 and taxtype=2");
						$getin = mysql_num_rows($getinput);
						$getwe = mysql_fetch_assoc($getinput);
							
					
					?>
					<tr>
					<td><?php echo $getit[bus_nature]; ?>
					<input type=hidden name=yearko[<?php echo $gr; ?>] value=<?php echo $yearko; ?>>
					<input type=hidden name=tempbus[<?php echo $gr; ?>] value=<?php echo $getit[tempid]; ?>>
					<input type=hidden name=dnature[<?php echo $gr; ?>] value="<?php echo $getit[bus_code]; ?>"></td>
					<td><input type=text name=gross[<?php echo $gr; ?>] value=""> </td>
					<?php
						if ($getin>0) {
					?>
					<td width=10%><input type=text name=inputy[<?php echo $gr; ?>] size=5 value=0> </td>
					<td >&nbsp; <?php echo $getwe[uom]; ?></td>
					<?php
						} else {
					?>
					<td><input type=hidden name=inputy[<?php echo $gr; ?>] value=0></td>
					<?php
						}
						?>
					</tr>
					<?php
					$gr++;
					}
				}
				
?>
<tr><td></td></tr>
<input type=hidden name=yearar value="<?php echo $yearar; ?>">
<input type=hidden name=gr value="<?php echo $gr; ?>">
<input type=hidden name=comm_ass>
<tr><td colspan=2 align="center"><Input type=button name=asse value="Save and Assess" onclick='_FRM.comm_ass.value=1; _FRM.submit();'>&nbsp; <input type=button value="Cancel" onclick='history.go(-1);'></td></tr>
</table>

if ($gethuh[indicator]==2) { //compute formula
				if ($gethuh[mode]==2) { //complex
					include_once "../class/TaxFeeOtherChargesClass.php";
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
					
					$tempamount = $totind;
					$show_complex = $gethuh[amtformula];
					$gethuh[amtformula]='complex formula: ';



				} elseif ($gethuh[mode]==1 || $gethuh[mode]==0) { //normal 
					$formula_rep = str_replace("X0",$gethuh[multi],strtoupper($gethuh[amtformula]));
					@eval("\$totind=$is_dec$formula_rep;");
					//echo "VooDoo $totind <br>";
					$tempamount = $totind;
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
					$tempamount = $totind;
				} else {
					$gethuh[amtformula]=$range[0];
					$formula_rep = str_replace("X0",$gethuh[multi],strtoupper($range[0]));
					@eval("\$totind=$is_dec$formula_rep;");
					$tempamount = $totind;
				}
				$totind = round($totind,2);

			} else { //constant
				$totind = $gethuh[multi] * $gethuh[amtformula];
				$tempamount = $totind;
			}
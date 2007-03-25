<?php
include_once "class/TaxFeeOtherChargesClass.php";

$FindDup = new TaxFee;
$FindDup->FindDuplicate($natureid,$tfotype,$TaxType);
$duplicate = $FindDup->outnumrow;


if ($duplicate>0) {
?>
        <body onload='alert("Existing Record Found! Duplicate Entries Not Allowed");'></body>
<?php
} else {


if ($Indicator==1) {
        $nConstant = new TaxFee;
                //constant
                        $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,'','$AmountFormula',now(),
                                    $TaxType,'$uom',$MinAmount";
                        $nConstant->InsertNewTaxFee($sValues);

} elseif($Indicator==2) { //formula
        $nFormula = new TaxFee;
           if ($Fmode==1) { //Normal formula

                                $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,$Fmode,'$AmountFormula',now(),
                                    $TaxType,'$uom',$MinAmount";
                                $nFormula->InsertNewTaxFee($sValues);
          } elseif ($Fmode==2) {
                                $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,$Fmode,'$orig_complex',now(),
                                    $TaxType,'$uom',$MinAmount";
                                $nFormula->InsertNewTaxFee($sValues);
                                $added=1;
                                $tax_fee_id = $nFormula->outid;
                                
                                        //save to complex
                                $loop = 0;
                                
                                $staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
										"sassess","");
								$prefset = FetchArray($dbtype,$staxfee);
								$sassesscomplex = $prefset['sassess']; // per estab
								
                                $rrc = mysql_query("select * from ebpls_buss_complex where
                                				complex_taxfeeid='$aTAXFEEid' order by var_complex");
                                $howmanycomplex=mysql_num_rows($rrc);
                                 if ($sassess=='1') { 
                                	while ($rrp = mysql_fetch_assoc($rrc)) {
                                        $loop++;
                
                                        $nip = mysql_query("select * from ebpls_buss_taxfeeother where taxfeeid='$rrp[complex_tfoid]'");
                                        $rip = mysql_fetch_assoc($nip);
                                        $rip = $rip[tfo_id];
                                       
                                        $nip = mysql_query("select * from ebpls_buss_taxfeeother where natureid='$natureid' and
                                        			tfo_id='$rip' and taxtype='$TaxType'");
                                        		
                                        $rip = mysql_fetch_assoc($nip);
                                        $rip = $rip[taxfeeid];
                                        
                                        $sValues = "'',$tax_fee_id,'$rip',
                                                   'X$loop',now()";
                                        $nFormula->InsertNewComplex($sValues);

                               		 }
                            	} else {
	                            	$comp=0;
	                            	
	                            	while ($rrp = mysql_fetch_assoc($rrc)) {
		                            	$complex_tfoid = $rrp[complex_tfoid];
		                            	$com_var = $rrp[var_complex];
		                            	
		                            	//check if default fee
		                            	
		                            	$tp = mysql_query("select * from ebpls_buss_tfo
		                            				where tfoid='$complex_tfoid' and
		                            				tfoindicator=1 and taxfeetype<>1");
		                            				
		                            	$cp = mysql_num_rows($tp);
		                            	if ($cp>0) {                          	
		                            			                            	
		                            	$sValues = "'',$tax_fee_id,'$complex_tfoid',
                                                   '$com_var',now()";
                                        } else {
	                                        
	                                        $tp = mysql_query("select * from ebpls_buss_taxfeeother
	                                        			where taxfeeid='$complex_tfoid'"); //source
	                                        $co = mysql_fetch_assoc($tp);
	                                        $tfoid = $co[tfo_id];
	                                        $taxtype = $co[taxtype];
	                                        $tp = mysql_query("select * from ebpls_buss_taxfeeother
	                                        			where natureid='$natureid' and
	                                        			tfo_id='$tfoid' and taxtype='$taxtype'"); 
	                                        $co = mysql_fetch_assoc($tp);
	                                        $ntaxid = $co[taxfeeid];
	                                        $sValues = "'',$tax_fee_id,'$ntaxid',
                                                   '$com_var',now()";
	                                        
                                   		}
                                        $nFormula->InsertNewComplex($sValues);
                               		 }
		                            	
                                	
								}

	}

} elseif ($Indicator==3) {
        //go go power ranger
        $nRange = new TaxFee;
                                $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,'','$AmountFormula',now(),
                                    $TaxType,'$uom',$MinAmount";
                                $nRange->InsertNewTaxFee($sValues);
                                $tax_fee_id = $nRange->outid;
                                $loop=0;


                                while ($loop<$howmanyrange)
                                {
                                $sValues = "'',$tax_fee_id,'$rlow[$loop]','$rhigh[$loop]',
                                   '$rvalue[$loop]',now()";
                                $nRange->InsertNewRange($sValues);

                                $loop++;
                                }


}
}

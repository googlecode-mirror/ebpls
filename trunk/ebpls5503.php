<?php
include_once "class/TaxFeeOtherChargesClass.php";


$ice = mysql_query("select * from ebpls_buss_taxfeeother where taxfeeid='$aTAXFEEid'");
$vnyz = mysql_fetch_assoc($ice);


$asdtax = $vnyz['taxtype'];
if ($TaxType != "") {
	$asdtax = $TaxType;
}

if ($usepred=='on') {
	$nyek='checked';
} else {
	$nyek='';
}

//$no_duplicate=1;

if ($saveme==123) {
	if ($usepred=='on') {
		$TaxType=5;
	}
$FindDup = new TaxFee;
$FindDup->FindDuplicate($natureid,$tfotype,$TaxType);
$duplicate = $FindDup->outnumrow;
} else {
$duplicate=0;
}

if ($duplicate>0 and $aTAXFEEid=='') {
	
echo "Existing Record Found! Duplicate Entries Not Allowed.";
} else { 



if ($chkrenew=='on') {
	$rencheck='checked';
	$check_ren = 1;
}

if ($chkretire=='on') {
        $retcheck='checked';
        $check_ret = 1;
}

if ($saveme==123) {
	if ($Indicator==1) {
	$nConstant = new TaxFee;
		//constant
		
		if ($aTAXFEEid=='') {
			if ($AmountFormula == "" || $AmountFormula <= 0) {
				?>
			<body onload='javascript:alert ("Invalid Amount!!"); _FRM.AmountFormula.focus();'></body>
			<?
			} else {
				if ($usepred=='on') {
					$TaxType=5;
				}
		
				
			$sValues = "'',$natureid, $tfotype, $Basis,
				    $Indicator,'','$AmountFormula',now(),
				    $TaxType,'$uom',$MinAmount";
			$nConstant->InsertNewTaxFee($sValues);
		
			if ($Basis<>'3') {
                            $Basis=2;
                        }

	
			if ($check_ren==1) {
			
				    $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,'','$AmountFormula',now(),
                                    '2','$uom',$MinAmount";
                        $nConstant->InsertNewTaxFee($sValues);
			}

			if ($check_ret==1) {

                                    $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,'','$AmountFormula',now(),
                                    '3','$uom',$MinAmount";
                        $nConstant->InsertNewTaxFee($sValues);
                        }

			$added=1;
			
			?>
			
			<body onmouseover='SaveFoc();'></body>
			<?
			}
		} else {
			$sValues = "tfo_id=$tfotype, basis=$Basis, indicator='$Indicator',
				    amtformula='$AmountFormula',taxtype=$TaxType,
				    uom = '$uom', min_amt=$MinAmount";
			$sWhere = "taxfeeid=$aTAXFEEid";
			$nConstant->UpdateTaxFee($sValues,$sWhere);


			$updated=1;
			
			?>
		
			<body onmouseover='UpFoc();'></body>
			<?
		}
			

	} elseif($Indicator==2) { //formula
	$nFormula = new TaxFee;
	   if ($Fmode==1) { //Normal formula

		//formula check
		$formula_check = str_replace("X0","1",strtoupper($AmountFormula));
		
		@eval("\$isvalid=$formula_check;");
		if ($isvalid=='') {
			echo "<font color=red>Invalid Formula. Please Check</font>";
		?>
		<!--<body onload='alert("Invalid Formula. Please Check!");'></body>-->
		<?php
		$notvalid=2;
		}

			if ($isvalid<>'') {
				if ($aTAXFEEid=='') {
					
					if ($usepred=='on') {
					$TaxType=5;
				}
					
                	        $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,$Fmode,'$AmountFormula',now(),
                                    $TaxType,'$uom',$MinAmount";
                        	$nFormula->InsertNewTaxFee($sValues);
				if ($Basis<>'3') {
	                            $Basis=2;
        	                }


				if ($check_ren==1) {
				$sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,$Fmode,'$AmountFormula',now(),
                                    '2','$uom',$MinAmount";
                                $nFormula->InsertNewTaxFee($sValues);

                        	}

                        	if ($check_ret==1) {
				$sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,$Fmode,'$AmountFormula',now(),
                                    '3','$uom',$MinAmount";
                                $nFormula->InsertNewTaxFee($sValues);

                        	}

				$added=1;
				?>
			
			<body onmouseover='SaveFoc();'></body>
			<?
        			} else {
                	        $sValues = "tfo_id=$tfotype, basis=$Basis, indicator='$Indicator',
                                    amtformula='$AmountFormula',taxtype=$TaxType,uom='$uom',
                                    min_amt=$MinAmount";
                        	$sWhere = "taxfeeid=$aTAXFEEid";
	                        $nFormula->UpdateTaxFee($sValues,$sWhere);


				$updated=1;
				?>
			<body onmouseover='UpFoc();'></body>
			<?
	        	        }
			}
	  } elseif ($Fmode==2) {
		//formula check
		$loop = 0;
		$complex_formula = strtoupper($complex_formula);
		$orig_complex = $complex_formula;
		while ($loop<$howmanycomplex+1) {
			//may ginawa ako dito -Robert
			$complex_formula = str_replace("X".$loop,$loop+1,$complex_formula);
			$loop++;
		}
                @eval("\$isvalid=$complex_formula;");
                
		$look_x = strpos($complex_formula,"X");
                if ($isvalid=='' ||  $look_x >0) {
               echo "<font color=red>Invalid Formula. Please Check</font>";
                $notvalid=2;
		$isvalid='';
                }
                                                                                                 
                        if ($isvalid<>'') {
                                if ($aTAXFEEid=='') {
	                                if ($usepred=='on') {
					$TaxType=5;
				}
                                $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,$Fmode,'$orig_complex',now(),
                                    $TaxType,'$uom',$MinAmount";
                                $nFormula->InsertNewTaxFee($sValues);
                                $added=1;
				$tax_fee_id = $nFormula->outid;
				if ($Basis<>'3') {
	                            $Basis=2;
        	                }

				
				if ($check_ren==1) {
				$sValues = "'',$natureid, $tfotype, '2',
                                    $Indicator,$Fmode,'$orig_complex',now(),
                                    '2','$uom',$MinAmount";
                                $nFormula->InsertNewTaxFee($sValues);
                                $tax_fee_id_ren = $nFormula->outid;
                                
                                
                                
				}

				if ($check_ret==1) {
                                $sValues = "'',$natureid, $tfotype, '2',
                                    $Indicator,$Fmode,'$orig_complex',now(),
                                    '3','$uom',$MinAmount";
                                $nFormula->InsertNewTaxFee($sValues);
                                $tax_fee_id_ret = $nFormula->outid;
                
              }


					//save to complex
				$loop = 0;
				while ($loop<$howmanycomplex) {
					$loop++;
					if ($ivar[$loop]=='') {
						$ivar[$loop]='X1';
					}	
					$sValues = "'',$tax_fee_id,'$complex_tfo[$loop]',
						   '$ivar[$loop]',now()";
					$nFormula->InsertNewComplex($sValues);
					
					if ($check_ren==1) {
						
						//get renew
						$rt = mysql_query("select * from ebpls_buss_taxfeeother where
											taxfeeid = '$complex_tfo[$loop]' and natureid='$natureid'");
						$cntrs = mysql_num_rows($rt);
						if ($cntrs==0) { //tfoid ito
							$yer = mysql_query("select * from ebpls_buss_tfo where
													tfoid = '$complex_tfo[$loop]' and tfoindicator=1");
							$yr = mysql_Fetch_assoc($yer);
							$ntx = $yr['tfoid'];
													
						} else {
						$rs = mysql_fetch_assoc($rt);
						$nid = $rs['natureid'];
						$tid = $rs['tfo_id'];
						$ind = $rs['indicator'];
						$ne = mysql_query("select * from ebpls_buss_taxfeeother where
											natureid = '$nid' and tfo_id='$tid'  and taxtype=2");
											$er = mysql_fetch_assoc($ne);
											
											$ntx = $er['taxfeeid'];
						}
						
					$sValues = "'',$tax_fee_id_ren,'$ntx',
                                                   '$ivar[$loop]',now()";
                                                 
                                        $nFormula->InsertNewComplex($sValues);
                  }                  
                    if ($check_ret==1) {  
	                    
	                    //get rentire
	                  	//get renew
						$rt = mysql_query("select * from ebpls_buss_taxfeeother where
											taxfeeid = '$complex_tfo[$loop]' and natureid='$natureid'");
						$cntrs = mysql_num_rows($rt);
						if ($cntrs==0) { //tfoid ito
							$yer = mysql_query("select * from ebpls_buss_tfo where
													tfoid = '$complex_tfo[$loop]' and tfoindicator=1");
							$yr = mysql_Fetch_assoc($yer);
							$ntx = $yr['tfoid'];
													
						} else {
						$rs = mysql_fetch_assoc($rt);
						$nid = $rs['natureid'];
						$tid = $rs['tfo_id'];
						$ind = $rs['indicator'];
						$ne = mysql_query("select * from ebpls_buss_taxfeeother where
											natureid = '$nid' and tfo_id='$tid'  and taxtype=3");
											$er = mysql_fetch_assoc($ne);
											
											$ntx = $er['taxfeeid'];
						}
						 
                       $sValues = "'',$tax_fee_id_ret,'$ntx',
                                                   '$ivar[$loop]',now()";
                                        $nFormula->InsertNewComplex($sValues);  
                                    }                 
					
				}
				
				
				$complex_formula=$orig_complex;
				?>
			
			<body onmouseover='SaveFoc();'></body>
			<?
                                } else {
								//echo $tfo_id."VooDoo";
                                $sValues = "tfo_id='$tfotype', basis='$Basis', indicator='$Indicator',
									mode='$Fmode',
                                    amtformula='$orig_complex',taxtype='$TaxType',
                                    uom='$uom',min_amt=$MinAmount";
                                $sWhere = "taxfeeid=$aTAXFEEid";
                                $nFormula->UpdateTaxFee($sValues,$sWhere);

				 //update complex
                                $loop = 0;
				$nFormula->DeleteTax("ebpls_buss_complex","complex_taxfeeid='$aTAXFEEid'");
                                while ($loop<$howmanycomplex) {
                                        $loop++;
					$searchme = new TaxFee;
					if ($ivar[$loop]=='') {
                                                $ivar[$loop]='X1';
                                        }
				/*	if ($compid[$loop]<>'') {
					$searchme->GetComplex($compid[$loop]);
					$have_rec = $searchme->outnumrow;
					} else {
					$have_rec=0;
					}
					if ($have_rec>0) {
					$sValues = "complex_tfoid='$complex_tfo[$loop]'";
                                        $sWhere = "compid=$compid[$loop]";
					$nFormula->UpdateComplex($sValues,$sWhere);
					} else { //if have new record
				*/
                                        $sValues = "'',$aTAXFEEid,'$complex_tfo[$loop]',
                                                   '$ivar[$loop]',now()";
                                        $nFormula->InsertNewComplex($sValues);
                                        //}
				}
                                $complex_formula=$orig_complex;
                                $updated=1;
                                ?>
			<body onmouseover='UpFoc();'></body>
			<?
                                }
			}

 	}	
		
	} elseif ($Indicator==3) {
	//go go power ranger
	$nRange = new TaxFee;

			$loop=0;
                        while ($loop<$howmanyrange) {
				if ($notvalid==2) {
					$loop = $howmanyrange;
				} else {
					if ($rlow[$loop]=='' || is_numeric($rlow[$loop]) == false) {
						?>
                        <body onload='javascript: alert("Invalid Range Data. Please Check!"); _FRM.rlow[<? echo $loop;?>].focus();'></body>
						<?php
						$notvalid=2;
					}
					/*if ($rlow[$loop] > $rhigh[$loop]) {
						?>
                        <body onload='javascript: alert("Invalid Range Data. Please Check!"); _FRM.rlow[<? echo $loop;?>].focus();'></body>
						<?php
						$notvalid=2;
					}*/
					/*	
					if ($rvalue[$loop]=='' || is_numeric($rvalue[$loop]) == false || $rvalue[$loop] <= 0) {
					 ?>
                                <body onload='javascript: alert("Invalid Range Data. Please Check!"); _FRM.rvalue[<? echo $loop;?>].focus();'></body>
                                <?php
					$notvalid=2;
					}
					if (strlen($rvalue[$loop]) > $formulalength) {
					 ?>
                                <body onload='javascript: alert("Invalid Range Data. Please Check!"); _FRM.rvalue[<? echo $loop;?>].focus();'></body>
                                <?php
					$notvalid=2;
					}*/
					if (strlen($rlow[$loop]) > $formulalength) {
					 ?>
                                <body onload='javascript: alert("Invalid Range Data. Please Check!"); _FRM.rlow[<? echo $loop;?>].focus();'></body>
                                <?php
					$notvalid=2;
					}
					if (strlen($rhigh[$loop]) > $formulalength) {
					 ?>
                                <body onload='javascript: alert("Invalid Range Data. Please Check!"); _FRM.rhigh[<? echo $loop;?>].focus();'></body>
                                <?php
					$notvalid=2;
					}

					if ($rhigh[$loop]==''  || is_numeric($rhigh[$loop]) == false) {
						if ($loop<>$howmanyrange-1) {
						 ?>
                                <body onload='javascript: alert("Invalid Range Data. Please Check!"); _FRM.rhigh[<? echo $loop;?>].focus();'></body>
                                <?php	
						$notvalid=2;
						}
					}
					
		                        if (!is_numeric($rvalue[$loop]) and $notvalid<>2) {
					//formula check
					$chk_formula=strtoupper($rvalue[$loop]);
			                $formula_check = str_replace("X0","1",$chk_formula);
        			        @eval("\$isvalid=$formula_check;");
	                			if ($isvalid=='') {
        	        	?>
	        	        <body onload='alert("Invalid Formula. Please Check!"); _FRM.<? echo $rvalue[$loop];?>.focus();'></body>
        	        	<?php
	                			$notvalid=2;
		        	        	} else {
						$notvalid=0;
						}
						
					}
				}
			$loop++;
			}
		if ($aTAXFEEid=='') {
			if ($notvalid==0) {
				if ($usepred=='on') {
					$TaxType=5;
				}
	                        $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,'','$AmountFormula',now(),
        	                    $TaxType,'$uom',$MinAmount";
                	        $nRange->InsertNewTaxFee($sValues);
				$tax_fee_id = $nRange->outid;
				$loop=0;

				if ($check_ren==1) {
				$sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,'','$AmountFormula',now(),
                                    '2','$uom',$MinAmount";
                                $nRange->InsertNewTaxFee($sValues);
                                $tax_fee_id_ren = $nRange->outid;
				}

				if ($check_ret==1) {
                                $sValues = "'',$natureid, $tfotype, $Basis,
                                    $Indicator,'','$AmountFormula',now(),
                                    '3','$uom',$MinAmount";
                                $nRange->InsertNewTaxFee($sValues);
                                $tax_fee_id_ret = $nRange->outid;
                                }


				while ($loop<$howmanyrange) 
				{
				$sValues = "'',$tax_fee_id,'$rlow[$loop]','$rhigh[$loop]',
				   '$rvalue[$loop]',now()";
				$nRange->InsertNewRange($sValues);

				if ($check_ren==1) {
				$sValues = "'',$tax_fee_id_ren,'$rlow[$loop]','$rhigh[$loop]',
                                   '$rvalue[$loop]',now()";
                                $nRange->InsertNewRange($sValues);
				}

				if ($check_ret==1) {
                                $sValues = "'',$tax_fee_id_ret,'$rlow[$loop]','$rhigh[$loop]',
                                   '$rvalue[$loop]',now()";
                                $nRange->InsertNewRange($sValues);
                                }


				$loop++;
				}
				?>
		
			<body onmouseover='SaveFoc();'></body>
			<?
			}
	
                        $added=1;
                } else {
						if ($notvalid <> '2') {
                        $sValues = "tfo_id=$tfotype, basis=$Basis, indicator='$Indicator',
                                    amtformula='$AmountFormula',taxtype=$TaxType,
                                    uom='$uom',min_amt=$MinAmount";
                        $sWhere = "taxfeeid=$aTAXFEEid";
                        $nRange->UpdateTaxFee($sValues,$sWhere);
			
			$searchme = new TaxFee;
			$loop=0;
                                while ($loop<$howmanyrange)
                                {
	                        if ($rangeid[$loop]<>'') {
        	                    $searchme->GetRange($rangeid[$loop]);
                	            $have_rec = $searchme->outnumrow;
                        	} else {
	                            $have_rec=0;
        	                }
                	            if ($have_rec>0) {
					$sValues = "rangelow='$rlow[$loop]',
						    rangehigh='$rhigh[$loop]',
						    rangeamount='$rvalue[$loop]'";
					$sWhere = "rangeid=$rangeid[$loop]";
					$nRange->UpdateRange($sValues,$sWhere);		
				    } else {
					$sValues = "'',$aTAXFEEid,'$rlow[$loop]',
						'$rhigh[$loop]','$rvalue[$loop]',now()";
	                                $nRange->InsertNewRange($sValues);
				    }
				$loop++;
				}
                        $updated=1;
                        ?>
			<body onmouseover='UpFoc();'></body>
			<?
					}
                }

	}
		
		
}

}//end if no duplicate


	if ($added==1) {
		$added=0;
?>
	     <body onload='alert("Record Successfully Added");'></body>
<?php
        }

	if ($updated==1) {
                $updated=0;
?>
             <body onload='alert("Record Successfully Updated");'></body>
<?php
        }



$dataRecord_ = mysql_query("SELECT * FROM ebpls_buss_preference",$link2db);
		
if (mysql_affected_rows($link2db)==1 ){
$dataRecord__=mysql_fetch_array($dataRecord_);
//$niSPermit=$dataRecord__[spermit];
$niSAssess=$dataRecord__[sassess]; // for business enterprise LGU operation only...
}
//else {
//$niSAssess=0;	
//}
?>

<table width=90%>
<tr>
<td width=30%>&nbsp;&nbsp;Taxes/Fees/Other Charges</td>
<td>&nbsp;
<?php

if ($aTAXFEEid<>'') {
		$gTaxFee = new TaxFee;
		$gTaxFee->GetTaxFee($aTAXFEEid);
		$getTax = $gTaxFee->outarray;
		$tfotype = $getTax[tfo_id];

		$TaxType1= $getTax[taxtype];
		
		if ($TaxType==$TaxType1 and $TaxType<>'') {
			$TaxType=$TaxType1;
		} else {
			$TaxType=$TaxType1;
		}

		$Basis1= $getTax[basis];

		if ($Basis<>$Basis1  and $Basis<>'') {
			$Basis1=$Basis;
		} else {
			$Basis=$Basis1;
                }

		$Indicator1= $getTax[indicator];
		if ($Indicator<>$Indicator1 and $Indicator<>'') {
			$Indicator1=$Indicator;
		} else {
			 $Indicator=$Indicator1;
                }

			if ($Basis==3) {
				$uom=$getTax[uom];
			}
			if ($Indicator==2) {

				$Fmode1 = $getTax[mode];
				if ($Fmode<>$Fmode1 and $Fmode<>'') {
					$Fmode1=$Fmode;
				} else {
					 $Fmode=$Fmode1;
                                }


					if ($Fmode==2) {
						$cntComplex =  new TaxFee;
						$cntComplex->CountTaxFeeComplex($aTAXFEEid);
						?>
						<input type=hidden name=varflag value=<?php echo $cntComplex->outnumrow; ?>>
						<?php
						if ($variable=='') { 
						$variable = $cntComplex->outnumrow;
						} elseif ($varflag<>$variable) {
						$variable=$variable;
						}
						$varval=1;
						$complex_formula = $getTax[amtformula];
						$editme = "234";
						$queryc = $cntComplex->outselect;
					}
			} elseif ($Indicator==3) {
				$rangeval=1;
				$cntRange =  new TaxFee;
                                $cntRange->CountTaxFeeRange($aTAXFEEid);
                                ?>
<input type =hidden name=varflag value=<?php echo $cntRange->outnumrow; ?>>
                                <?php
				if ($AmountFormula=='') {
                                    $AmountFormula = $cntRange->outnumrow;
                                } elseif ($varflag<>$AmountFormula) {
                                    $AmountFormula=$AmountFormula;
                                }
				$editme="246";
				$query=$cntRange->outselect;

			}
		if ($notvalid<>2 and $Indicator<>3) {
		$AmountFormula= $getTax[amtformula];
		}
		$MinAmount= $getTax[min_amt];
}
if ($MinAmount=='') {
        $MinAmount=0;
}

	
	if ($niSAssess==1){
		//echo "00000000000000";
//	$listoption = mysql_query("SELECT * FROM ebpls_buss_tfo WHERE taxfeetype = '$bussEvent' and tfoindicator=0 and tfostatus='A' ORDER BY taxfeetype ",$link2db);

	 echo get_select_data($dbLink,'tfotype','ebpls_buss_tfo ','tfoid','tfodesc',$tfotype,'true',"taxfeetype = '$bussEvent' and tfoindicator=0 and tfostatus='A' ORDER BY taxfeetype",'');



	}
	else {
//		echo "11111111111111111 $tfotype";

//	$listoption = mysql_query("SELECT * FROM ebpls_buss_tfo WHERE taxfeetype = '$bussEvent' and tfoindicator=0 and tfostatus='A' ORDER BY taxfeetype ",$link2db);
echo get_select_data($dbLink,'tfotype','ebpls_buss_tfo ','tfoid','tfodesc',$tfotype,'true',"taxfeetype = '$bussEvent' and tfoindicator=0 and tfostatus='A' ORDER BY taxfeetype",'');
	}
	
/*	if (mysql_affected_rows($link2db)>0 ){
		$myrow=1;
		$ctrrow=0;
		while (list($taxfeetype,$typedesc) = mysql_fetch_row($listoption)){
			if ($tfotype==$taxfeetype) {
				$st = 'selected';
			} else {
				$st='';
			}
			echo "<option value=$taxfeetype $st>$typedesc";
		$myrow++;
		}
//		echo "<option value=$ni_taxfeetype SELECTED>$ni_typedesc";
	}
*/
?>
</td>
</tr>

<!---- transaction ---->
<tr>
<td width=15%>&nbsp;&nbsp;Transaction</td>
<td>&nbsp;
<?php
$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"predcomp, sassess","");
$prefset = FetchArray($dbtype,$staxfee);	

$predcomp = $prefset['predcomp'];
$sassess = $prefset['sassess'];

if ($asdtax==1 || $asdtax==5){
	$type1 = 'selected';
	$type2 = '';
	$type3 = '';
	if ($asdtax==5) {
	$nyek='checked';
	}
	
	
	
} elseif ($asdtax==2){
	$type2 = 'selected';
        $type1 = '';
        $type3 = '';
} elseif ($asdtax==3){
	$type3 = 'selected';
        $type2 = '';
        $type1 = '';
} else {
	$type3 = '';
        $type2 = '';
        $type1 = '';
}
?>
<select name=TaxType onchange='_FRM.submit();'>
<option value='1' <?php echo $type1; ?>>New</option>
<option value='2' <?php echo $type2; ?>>Renew</option>
<option value='3' <?php echo $type3; ?>>Retire</option>
</select>

<?php

	if ($predcomp==1 and $assess=='' and $asdtax=='' || $asdtax==5 || $asdtax==1 ) {
?>
&nbsp; <input type=checkbox name="usepred" <?php echo $nyek; ?>> &nbsp; Use this for preceding quarter
<?php
	}
	?>


</td>
</tr>
<?php
	if ($TaxType==1 || $TaxType=='' and $aTAXFEEid=='') {
?>
<tr>
<td width=15%>&nbsp;&nbsp;Use also in:</td>
<td>&nbsp;<input type=checkbox name=chkrenew <?php echo $rencheck; ?>>ReNew &nbsp; 
<input type=checkbox name=chkretire <?php echo $retcheck; ?>>Retire</td>
</tr>
<?php
	}
?>
<tr>
<td width=15%>&nbsp;&nbsp;Basis</td>
<td>&nbsp;
<?php
if ($Basis==1){
	$option1 = 'selected';
	$option2='';
	$option3='';
	$vBasis = 'Capital Investment';
} elseif ($Basis==2){
	$option2 = 'selected';
        $option1='';
        $option3='';
	$vBasis = 'Gross Sales';
} elseif ($Basis==3){
	$option3 = 'selected';
        $option2='';
        $option1='';
    	$vBasis = 'Inputted Value';

} else {
	$option1='';
        $option2='';
        $option3='';

}

?>
<input type=hidden name=savfoc>
<input type=hidden name=bas_change>
<select name=Basis onchange='_FRM.bas_change.value=1; _FRM.submit();'>
<option value=1 <?php echo $option1; ?>>Capital Investment</option>
<option value=2 <?php echo $option2; ?>>Gross Sales</option>
<option value=3 <?php echo $option3; ?>>Inputted Value</option>
</select>
</td>
</tr>

<tr>
<td width=15%>&nbsp;&nbsp;Indicator</td>
<td>&nbsp;
<?php
if ($Indicator==1){
		$indicator1='selected';
		$indicator2='';
		$indicator3='';
} elseif ($Indicator==2){
		$indicator2='selected';
		$indicator1='';
		$indicator3='';
} elseif ($Indicator==3){
		$indicator3='selected';
		$indicator2='';
		$indicator1='';
} else{
		$indicator1='';
		$indicator2='';
		$indicator3='';
}
	
?>
<select name=Indicator onchange='_FRM.submit();'>
<option value=1 <?php echo $indicator1; ?>>Constant</option>
<option value=2 <?php echo $indicator2; ?>>Formula</option>
<option value=3 <?php echo $indicator3; ?>>Range</option>
</select>
</td>
</tr>

<?php 
	if ($Indicator==1 || $Indicator=='') { //constant
		$amtforlabel = 'Amount';
	} elseif ($Indicator==2) {
		$amtforlabel = 'Formula';
	} elseif ($Indicator==3) {
		$amtforlabel = 'No. of range';
	}
	
	if ($Indicator==2)  {
?>
<tr>
<td width=15%>&nbsp;&nbsp;Mode</td>
<td>&nbsp;
<?php
if ($Fmode==1){
		$imode1='selected';
		$imode2='';
} elseif ($Fmode==2){
		$imode2='selected';
		$imode1='';
} else {
		$imode1='';
		$imode2='';
}
?>

<select name=Fmode onchange='_FRM.submit();'>
<option value=1 <?php echo $imode1; ?>>Normal</option>
<option value=2 <?php echo $imode2; ?>>Complex</option>
</select>
</td>
</tr>
<?php
	} //amtforlabel==formula;

	if ($Indicator==3 || $Fmode==1 || $Fmode=='' || $Fmode==0 || $Indicator==1) {
		if ($AmountFormula>$maxrange and $Indicator==3) {
			$AmountFormula=$maxrange;
		} 

?>
<!-- amt/formula -->

<tr>
<td>&nbsp;&nbsp;<?php echo $amtforlabel; ?></td>
<td>&nbsp;
<input type=text name=AmountFormula size=20 value="<?php echo $AmountFormula;?>">
<input type=hidden name=rangeval value=<?php echo $rangeval; ?>>
<?php
		if ($Indicator==3) {
?>
	&nbsp;<input type=button name=rangebut value='Display Range' onclick='javascript: VerifyRange();'>
<?php
		}
	
?>
</td>
</tr>
<?php } elseif ($Fmode==2 and $Indicator==2) {
?>
<tr>
<td>&nbsp;&nbsp;No. of Variables</td>
<td>&nbsp;
<input type=text name=variable size=20 value="<?php echo $variable;?>">
<input type=hidden name=varval value=<?php echo $varval; ?>>
&nbsp;<input type=button name=valbut value='Display Variable' onclick='_FRM.varval.value=1;_FRM.submit();'>
<?php
	 }
	
?>
<tr>
<td width=15%>&nbsp;&nbsp;Minimum Amount</td>
<td>&nbsp;&nbsp;<input type=text name=MinAmount size=20 value="<?php echo $MinAmount;?>"></td>
</tr>


</tr>
<?php 
	if ($Basis==3) {
?>
<tr>
<td>&nbsp;&nbsp;Unit of Measure</td>
<td>&nbsp;&nbsp;<input type=text name=uom size=20 value="<?php echo $uom; ?>"></td>
</tr>
<?php
	}

	if ($varval==1 and $Fmode==2 and $Indicator==2) {
		
?>
		<table border=0 width=50% align=left><br>
		<tr>
		<td align=right>Variable</td><td>Tax/Fee/Other</td>
		</tr>
<?php
		$varme = 0 ;
			while ($varme < $variable) 
			{
				$varme++;
?>
				<tr><td align=right>
				<?php echo "X$varme"; ?> =
				<input type=hidden name=ivar[<?php echo $varme; ?>] value=<?php echo "X$varme"; ?>>
				</td><td>
			<?php
	if ($editme==234) {		
		$complexTFO = new TaxFee;
		$complexTFO->FetchTaxFeeArray($queryc);
		$comp_tfo=$complexTFO->outarray;
		
		$complex_tfo[$varme] = $comp_tfo[complex_tfoid];
		
	?>
		<input type=hidden name=compid[<?php echo $varme; ?>] value=<?php echo $comp_tfo[compid]; ?>>
	<?php
	}
	$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"sassess","");
$prefset = FetchArray($dbtype,$staxfee);
$sassess = $prefset['sassess']; // per estab

if ($sassess==1) {
       echo get_select_data($dbLink,'complex_tfo['.$varme.']','ebpls_buss_tfo a, ebpls_buss_taxfeeother b','taxfeeid','tfodesc',$complex_tfo[$varme],'true',"a.tfoid= b.tfo_id and b.natureid=$natureid and a.tfostatus='A' and  b.taxtype=$TaxType order by taxfeeid",'');
} else {
  echo get_select_complex_reg($dbLink,'complex_tfo['.$varme.']','ebpls_buss_tfo a, ebpls_buss_taxfeeother b','taxfeeid','tfodesc',$complex_tfo[$varme],'true',"a.tfoid= b.tfo_id and b.natureid=$natureid and a.tfostatus='A' and  b.taxtype=$TaxType  order by taxfeeid",'');
}
        ?>
				</td>
				</tr>
<?php
			}
		if ($complex_formula=='') {
			$complex_formula="X0";
		}
?>		
		<tr><td>Formula</td>
		<td><input type=text name=complex_formula value="<?php echo $complex_formula; ?>">
		</table>
<?php	
	}
		
	if ($Indicator==2) {
?>
		<table border=0 width=50% align=left><br>
		<tr><td>Legend:</td></tr>
		<tr>
		<td><font color=red>X0 = <?php echo $vBasis; ?></font></td>
		</tr>
		</table>
<?php
	}
	
	if ($Indicator==3 and $rangeval==1) {
		if (strlen($AmountFormula) > 3) {
			?>
			<body onload='javascript:alert ("Invalid range data, please check!!");'></body>
			<?
		} else {
			
?>
		<table border=0 width=50% align=center><br>
		<tr>
		<td>Range Number</td><td>Lower Limit</td><td>Higher Limit</td><td>Value</td>
		</tr>
<?php
		$rangeme = 0 ;
			while ($rangeme < $AmountFormula) 
			{
			if ($editme==246) {
			$lRange = new TaxFee;
	                $lRange->FetchTaxFeeArray($query);
        	        $range_list=$lRange->outarray;
                	$range_id[$rangeme] = $range_list[rangeid];
			?>
                <input type=hidden name=rangeid[<?php echo $rangeme; ?>] value=<?php echo $range_id[$rangeme]; ?>>
<?php
			$rlow[$rangeme] = $range_list[rangelow];
			$rhigh[$rangeme] = $range_list[rangehigh];
			$rvalue[$rangeme] = $range_list[rangeamount]; 	
			}

				if ($rangeme==0) {
						
			?>
				<tr><td>
				<?php echo $rangeme + 1 ; ?></td>
				<td>
				<input type=text name=rlow[<?php echo $rangeme; ?>] value=0 readonly>
				</td><td>
				<input type=text name=rhigh[<?php echo $rangeme; ?>] value="<?php echo $rhigh[$rangeme]; ?>" <?php echo $readme2; ?>>
				</td>
				<td>
				<input type=text name=rvalue[<?php echo $rangeme; ?>] value="<?php echo $rvalue[$rangeme]; ?>"> 
				</td>
				</tr>
			<?php
				} elseif ($rangeme==$AmountFormula-1) {
			?>
				<tr><td>
				<?php echo $rangeme + 1 ; ?></td>
				<td>
				<input type=text name=rlow[<?php echo $rangeme; ?>] value=<?php echo $rlow[$rangeme]; ?>>
				</td><td>
				<input type=text name=rhigh[<?php echo $rangeme; ?>] value='' readonly>
				</td>
				<td>
				<input type=text name=rvalue[<?php echo $rangeme; ?>] value="<?php echo $rvalue[$rangeme]; ?>"> 
				</td>
				</tr>
				<?php
				} else {
				?>
				<tr><td>
				<?php echo $rangeme + 1 ; ?></td>
				<td>
				<input type=text name=rlow[<?php echo $rangeme; ?>] value=<?php echo $rlow[$rangeme]; ?>>
				</td><td>
				<input type=text name=rhigh[<?php echo $rangeme; ?>] value=<?php echo $rhigh[$rangeme]; ?>>
				</td>
				<td>
				<input type=text name=rvalue[<?php echo $rangeme; ?>] value="<?php echo $rvalue[$rangeme]; ?>"> 
				</td>
				</tr>
				<?php
				}
				?>
				
<?php
				$rangeme++;
			}
		}
	}
?>
		<input type=hidden name=howmanycomplex value=<?php echo $varme; ?>>
		<input type=hidden name=howmanyrange value=<?php echo $rangeme; ?>>
		</table>
<tr>
<td colspan=2><hr size=2 width=100%></td>
</tr>


<tr>
<td colspan=2><input type=button name=ISubmitTax value="Save" onclick='VerifyEntry();'>
<input type=button name=iCancelRange value="Cancel" onClick='history.go(-1)'>
&nbsp <a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=<?php echo $bussEvent;?>&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Add New</a> | 
<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=1&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Taxes</a> | 
<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=2&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Fees</a> | 
<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=3&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Other Charges</a> |
<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=2&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Regulatory Fees</a> | 
<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=4&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Special Fees</a> | 
<a href=<?php echo $PHP_SELF;?>?natureid=<?php echo $natureid;?>&action_=1&actionID=1&part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&natureaction=Edit>Back to list</a></td>
</tr>
<input type=hidden name=saveme>
<script language='Javascript'>
function VerifyRange()
{
	var _FRM = document._FRM;
	if (_FRM.AmountFormula.value == "" || _FRM.AmountFormula.value == 0) {
		alert ("Input Valid Range Data!!");
		_FRM.AmountFormula.focu();
		_FRM.AmountFormula.select();
		return false;
	}
	_FRM.rangeval.value=1;
	_FRM.submit();
	return true;
}
function VerifyEntry()
{
		var _FRM = document._FRM;
		
		if (_FRM.Indicator.value==1) {
				
				if (_FRM.AmountFormula.value=='' || isNaN(_FRM.AmountFormula.value)) {
						alert ("Please enter valid amount!");
						_FRM.AmountFormula.focus();
						return false;
				}
				
				if (_FRM.MinAmount.value=='' || isNaN(_FRM.MinAmount.value)) {
						alert ("Please enter valid minimum amount!");
						_FRM.MinAmount.focus();
						return false;
				}
				
				if (_FRM.Basis.value==3) {
						if (_FRM.uom.value=='') {
						alert ("Please valid Unit of Measure!");
						_FRM.uom.focus();
						return false;
						}
				}
		}

		if (_FRM.Indicator.value==2) {
			if (_FRM.Mode==1) {
				if (_FRM.AmountFormula.value=='' || isNaN(_FRM.AmountFormula.value)) {
                                                alert ("Please enter valid formula!");
                                                _FRM.AmountFormula.focus();
                                                return false;
                                }
			} else {
				if (_FRM.MinAmount.value=='' || isNaN(_FRM.MinAmount.value)) {
                                                alert ("Please enter valid minimum amount!");
                                                _FRM.MinAmount.focus();
                                                return false;
                                }
			}
		}

		if (_FRM.Indicator.value==3) {
			
			 if (_FRM.AmountFormula.value=='' || isNaN(_FRM.AmountFormula.value) || _FRM.AmountFormula.value.length > 3) {
                                                alert ("Please enter valid range value!");
                                                _FRM.AmountFormula.focus();
                                                return false;
                         }
        	
			 if (_FRM.MinAmount.value=='' || isNaN(_FRM.MinAmount.value) || _FRM.MinAmount.value.length > 11) {
                                                alert ("Please enter valid minimum amount!");
                                                _FRM.MinAmount.focus();
                                                return false;
                         }
		}
			_FRM.saveme.value='123';
			_FRM.submit();
	 		return true;		
		
}
</script>


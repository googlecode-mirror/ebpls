<?php
//$nSurchargeAmount = 0;
//$nInterestAmount = 0;
//$nbacktax = 0;
$getgross =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =1
                                   and date_create like '$yearnow%'");
             $gross =mysql_num_rows($getgross);
             
if ($gross==0) {
	             ?>
	             <body onload="alert('Tax payer have not yet entered gross sales for this year. \n Payment schedule is inaccurate and will not be displayed ');"></body>
	             <?php
} else {
?>

<table border=1 align =center width=100% cellspacing=0 class=sub>
<tr>
<td align=center >PAYMENT MODE: <?php echo $pmode; ?></td>
<td align=center width=20%>PAYMENT DATE:</td>
<td align=center width=20%>AMOUNT/PARTICULARS</td>
<td align=center width=20%>STATUS</th>
<?php
	if ($itemID_==2212) {
?>
	<td width=20%>PAYMENT TYPE</td>
<?php
	}
?>
</tr>
<?php

$yeartoday = date('Y');
$montoday = date('m');
$addend = "$yeartoday-";

if (strtolower($pmode)=='quarterly') {
	if ($montoday >= 1 and $montoday <= 3) {
		$qqtrcnt = 1;
	} elseif ($montoday >= 4 and $montoday <= 6) {
		$qqtrcnt = 2;
	} elseif ($montoday >= 7 and $montoday <= 9) {
		$qqtrcnt = 3;
	} elseif ($montoday >= 10 and $montoday <= 12) {
		$qqtrcnt = 4;
	}
	if ($qqtrcnt == '1') {
		$getpendedrenewal = $getrenew['qtrdue1'];
	} elseif ($qqtrcnt == '2') {
		$getpendedrenewal = $getrenew['qtrdue2'];
	} elseif ($qqtrcnt == '3') {
		$getpendedrenewal = $getrenew['qtrdue3'];
	} elseif ($qqtrcnt == '4') {
		$getpendedrenewal = $getrenew['qtrdue4'];
	}
}
if (strtolower($pmode)=='semi-annual') {
	if ($montoday >= 1 and $montoday <= 6) {
		$ssemcnt = 1;
	} elseif ($montoday >= 7 and $montoday <= 12) {
		$ssemcnt = 2;
	}
	if ($ssemcnt == '1') {
		$getpendedrenewal = $getrenew['semdue1'];
	} elseif ($ssemcnt == '2') {
		$getpendedrenewal = $getrenew['semdue2'];
	}
}
if (strtolower($pmode)=='annual') {
	$getpendedrenewal = $getpended['renewaldate'];
}

$getstartdate = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise",
                  "where owner_id = $owner_id and business_id=$business_id and retire=0");
$getstart = FetchArray($dbtype,$getstartdate);
$startdate = $getstart[business_start_date];

$renewaldate = "$addend$getpendedrenewal";
$renewaldate = strtotime($renewaldate);
$renewaldate = date('Y-m-d', $renewaldate);
$nStartDate = date('Y-m-d', strtotime($startdate));

//get cash first
$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b",
                        "sum(a.total_amount_paid) as cash",
                        "where a.or_no=b.or_no and b.trans_id=$owner_id and
                        b.or_entry_type='CASH' and
                        b.payment_id=$business_id and b.transaction='$stat'");
$getcash=FetchRow($dbtype,$getcas);

$getclear = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a,
                        ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c","sum(a.check_amount)",
                        "where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='CHECK' and a.check_status='CLEARED' and
                        c.transaction='$istat' and
                        c.trans_id=$owner_id and c.payment_id=$business_id");
$totcheck = FetchRow($dbtype,$getclear);
$taonnatinngayon = date('Y');
$buwannatinngayon = date('m');

if (strtolower($pmode) == "quarterly") {
	if ($buwannatinngayon >= 1 and $buwannatinngayon <= 3) {
		$a1 = 1;
		$a2 = 3;
		$monthcounter = 1;
	} elseif ($buwannatinngayon >= 4 and $buwannatinngayon <= 6) {
		$a1 = 4;
		$a2 = 6;
		$monthcounter = 2;
	} elseif ($buwannatinngayon >= 7 and $buwannatinngayon <= 9) {
		$a1 = 7;
		$a2 = 9;
		$monthcounter = 3;
	} elseif ($buwannatinngayon >= 10 and $buwannatinngayon <= 12) {
		$a1 = 10;
		$a2 = 12;
		$monthcounter = 4;
	}
	$scounter =$qqtrcnt;
} elseif (strtolower($pmode) == "semi-annual") {
	if ($buwannatinngayon >= 1 and $buwannatinngayon <= 6) {
		$a1 = 1;
		$a2 = 6;
		$monthcounter = 1;
	} elseif ($buwannatinngayon >= 7 and $buwannatinngayon <= 12) {
		$a1 = 7;
		$a2 = 12;
		$monthcounter = 2;
	}
	$scounter =$ssemcnt;
} elseif (strtolower($pmode) == "annual") {
	$a1 = 1;
	$a2 = 12;
	$scounter =1;
}

//echo "thth".$qtrcnt."VooDoo";
if ($monthcounter <= $scounter) {
$getsurchargepaid = @mysql_query("select sum(penalty), sum(surcharge), sum(backtax), sum(taxes) from comparative_statement a, ebpls_transaction_payment_or_details b, ebpls_transaction_payment_or c  where a.owner_id = '$owner_id' and a.business_id = '$business_id' and a.for_year = '$taonnatinngayon' and a.month >= $a1 and a.month <= $a2 and a.or_no = c.payment_code and b.or_no = c.or_no and b.transaction != 'Retire'");
//echo "select sum(penalty), sum(surcharge), sum(backtax), sum(taxes) from comparative_statement where owner_id = '$owner_id' and business_id = '$business_id' and for_year = '$taonnatinngayon' and month >= $a1 and month <= $a2";

$getsumnun = @mysql_fetch_row($getsurchargepaid);
$getsumnun1 = $getsumnun[0] + $getsumnun[1] + $getsumnun[2];
$paidtaxes = $getsumnun[3];

} else {
	$getsumnun1 = 0;
}

$totalcash = $getcash[0];
$totalcheck = $totcheck[0];
$totalpayment =$totalcash+$totalcheck;

$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
                "staxesfees,swaivetax, predcomp","");
$prefset = FetchArray($dbtype,$staxfee);

$startd = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise",
                " business_start_date","where business_id='$business_id' and owner_id='$owner_id'");
$startdate = FetchArray($dbtype,$startd);
$startdate =strtotime($startdate['business_start_date']);


$predcomp = $prefset['predcomp'];

if ($predcomp==1 and $stat=='New') {
$exemptedfee=0;
}

$totaltaxfee = $grandamt;
$totalfee = $totfee - $exemptedfee;
$totalexptax = $exemptot;
$totaltax = ($totaltaxfee - $totalfee);
$totalexemptedfee = $exemptedfee;
$totalremain = ($totaltaxfee) - $totalpayment;



echo "<br>Total Tax/Fee = $totaltaxfee <br>
	   Total Tax = $totaltax <br>
	   Total Fee = $totalfee <BR>
	   Total exempted tax = $exemptot <br>
	   Total exempted fee = $totalexemptedfee <br>
	   Total cash payment = $totalcash <BR>
	   Total check payment = $totalcheck <BR>
	   Total payment = $totalpayment <BR> 
	   Balance =<BR>";



$businessyr = date('Y', $startdate);
$currentyr = date('Y');

	if ($businessyr==$currentyr and $stat=='New') {
		$businessmo = date('m', $startdate);
	
			if (strtolower($pmode)=='quarterly') {
				if ($businessmo<=3) { // no waive
					$getqtr0 = 0;
					$getqtr = 0;
					$predqtr = 1;
				} 
				
				if ($businessmo > 3 ) { // 1st Q waive
					$getqtr1 = 1;
					$getqtr = 1;
					$predqtr = 2;
				} 
				if ($businessmo > 6 ) { // 2nd Q waive
					$getqtr2 = 2;
					$getqtr = 2;
					$predqtr = 3;
				} 
				if ($businessmo > 9 ) { //3rd Q waive
					$getqtr3 = 3;
					$getqtr = 3;
					$predqtr = 4;
				}
				
			} elseif (strtolower($pmode)=='semi-annual') {
				if ($businessmo<=6) { // no waive
					$getsem = 0;
					$predqtr = 1;
				} else { // 1st Q waive
					$getsem = 1;
					$predqtr = 2;
				}
			}
	} else {
		$getqtr=0;
		$getsem=0;
		$predqtr = 1;
	}
		

	


$staxfee = $prefset['staxesfees']; // separate computation of taxes and fees
$swaive = $prefset['swaivetax']; // waiving of tax   
	   

if (strtolower($pmode)=='quarterly') {
	
	if ($staxfee == '' )  { //Separate computation of tax/fee = ''
	
	
	
	
		if ($swaive=='') { // will not waive tax	
		
		$divtax = $totaltax / 4;
		$divfee = $totalfee / 4;
		
		} else { //will waive tax
		$waivedtax = ($totaltax / 4) * $getqtr; // remaining tax to pay
		$divtax = ($totaltax-$waivedtax) / (4 - $getqtr); // distribute tax
		$divfee = $totalfee / (4 - $getqtr); // fees per payment
		} // end scenario waive tax
		
		$qtrcnt = 0;
		while ($qtrcnt<4) {
			
			$qtrcnt++;
			if (strtolower($pmode) =="quarterly") {
			        $gtcount = $qtrcnt;
			}
			if ($scounter != $gtcount) {
			        $getsumnun1 = 0;
			}

			$payd = mysql_query("select * from ebpls_buss_penalty1");
			$paydue = mysql_fetch_assoc($payd);
			$qtrdue = 'qtrdue'.$qtrcnt;
			$paydue = $paydue[$qtrdue];
			$pendue = $paydue."-".date('Y');
			$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
			
			
			//get payment status
			$pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$qtrcnt' and ts like '$yearnow%' and transaction != 'Retire' order by ts desc");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			
			if ($paymade >= 1) {
				$paystat = $qtrcnt;
			} else {
				$paystat = 0;
			}
			$nbong = 0;
		// ano ang silbi ng getsumnun1???	
				$isbad = ($divtax + $divfee + $getsumnun1) - $paymade;
				
				$nbong = 1;
			
			
	//		echo "$divtax + $divfee + $getsumnun1) - $paymade; <br>";
			//$isbad = ($divtax + $divfee) - $paymade;
			$NgTotalTaxFee = $divtax + $divfee;
			
			
			if ($qtrcnt==1 ) {
					
				$paylist = 'First Quarter Payment';
				 if ($paystat==1 and $haveaddpay<>1) {
				
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
			    } elseif ($paystat==1 and $haveaddpay==1) {
				    
                     $payst = 'UNPAID';
                     $nbong = 1;
				}	else {
					
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				
			} elseif ($qtrcnt==2) {
				
				$paylist = 'Second Quarter Payment';
				 if ($paystat==2 and $haveaddpay<>2) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==2) {
					 $payst = 'UNPAID';
					$nbong = 1;
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				
			} elseif ($qtrcnt==3) {
				$paylist = 'Third Quarter Payment';
				 if ($paystat==3 and $haveaddpay<>3) {
					 $payst = 'PAID';
					$counterpaid = $counterpaid + 1;
				} elseif ($paystat==3 and $haveaddpay==3) {
                     $payst = 'UNPAID';
                    $nbong = 1; 	
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
				     
			     }
				
			} elseif ($qtrcnt==4) {
				$paylist = 'Fourth Quarter Payment';
				 if ($paystat==4 and $haveaddpay<>4) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==4 and $haveaddpay==4) {
					
                     $payst = 'UNPAID';
                    $nbong = 1; 	 
			     } else {
				     
				     $payst = 'UNPAID';
				     $payme=1;
				     
			     }
			} else {
				$payst = 'PAID';
				$redire=1;
			}
			
		
			
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				$unpaid=0;
			}
			
			if ($payst=='UNPAID') {
			$amt2pay = $divtax + $divfee;
		
			} else {
				
				if ($isbad >= 1 and $qtrcnt == $haveaddpay) {
					$amt2pay=$isbad;
					$payst = "UNPAID";
					$unpaid=1;
				} else {
					$amt2pay = 0;
				}
			}
			if ($isbad > 0 and $qtrcnt == $haveaddpay) {
				$payst = "UNPAID";
				$amt2pay = $isbad;
			}
			
			if ($swaive<>'') {
				
				     	if ($getqtr1==1 and $fpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$fpay = 1;
					     	$unpaid=0;
					     	
				     	} elseif ($getqtr2==2 and $secpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$secpay = 2;
					     	$unpaid=0;
				     	} elseif ($getqtr3==3 and $thpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$thpay = 3;
					     	$unpaid=0;
					     	
				     	}
				     	
			}
			
			$qtrcntp[$qtrcnt] = $payst;
			
			?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
			
 			if ($qtrcnt>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				if ($nStartDate < $renewaldate) {
					$nrmv = $qtrcnt - 1;
					if ($qtrcntp[$nrmv] != 'TAX WAIVED' and $qtrcnt != $haveaddpay) {
						
						require "includes/business_penalty.php";
					}
				}
			} elseif ($stat<>'New') {
				if ($qtrcntp[$nrmv] != 'TAX WAIVED' and $qtrcnt != $haveaddpay) {
				require "includes/business_penalty.php";
				}
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $unpaid==1) {
			?>
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash</a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			?>
			</tr>
			<?php
		
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
		} //end while qtr
		?>
			<tr>
			<tr>
			<td ></td>
			<td align=right width=20%>Total (Php) &nbsp; </td>
			<td align=right width=20%><?php echo number_format($totalamt2pay,2); ?></td>
			<td align=center width=20%></td>
			<?php
				if ($itemID_==2212) {
			?>
				<td align=center width=20%></td>
			<?php
			}
			?>
			</tr>
			<?php
	} else { // separate computation tax/fee==1 
		$divfee=$totalfee;



		
		if ($swaive=='') { // will not waive tax	
		$divtax = $totaltax / 4;
		
		} else { //will waive tax
		$waivedtax = ($totaltax / 4) * $getqtr; // remaining tax to pay
		$divtax = ($totaltax-$waivedtax) / (4 - $getqtr); // distribute tax
		
		} // end scenario waive tax
		
		$qtrcnt = 0;
		while ($qtrcnt<4) {
			$qtrcnt++;
			$payd = mysql_query("select * from ebpls_buss_penalty1");
			$paydue = mysql_fetch_assoc($payd);
			$qtrdue = 'qtrdue'.$qtrcnt;
			$paydue = $paydue[$qtrdue];
			$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
			
			//get payment status
			$pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$qtrcnt' and ts like '$yearnow%' and transaction != 'Retire'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			
			$pays = mysql_query("select * from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$qtrcnt' and ts like '$yearnow%' and transaction != 'Retire'");
			$ridi = mysql_num_rows($pays);
			
			
			if ($paymade >= 1) {
				$paystat = $qtrcnt;
				
			} else {
				$paystat = 0;
			}
			
			if ($predcomp == '1' and $haveaddpay==1 and $stat=='New') {
				
				$isbad = (($divtax * 4) + $divfee) - $paymade;
				
			} elseif ($predcomp == '1' and $haveaddpay != 1 and $stat=='New') {
				$isbad = (($divtax * 4) + $divfee);
				
			} else {
				$isbad = ($divtax + $divfee + $getsumnun1) - $paymade;
			}
			$NgTotalTaxFee = $divtax + $divfee;
		
			//ano quarter na
				$watqtr = date('m') / 4;
			
			if ($predcomp=='0') {
						if ($qtrcnt==1 ) {
								
								$paylist = 'First Quarter Payment';
								 if ($paystat==1 and $haveaddpay<>1) {
									
									 $payst = 'PAID';
									 $counterpaid = $counterpaid + 1;
							    } elseif ($paystat==1 and $haveaddpay==1) {
				                     $payst = 'UNPAID';
				                     $unpaid=1;
								}	else {
								     $payst = 'UNPAID';
								     $payme=1;
								     $unpaid=1;
							     }
								
							} elseif ($qtrcnt==2  ) {
								$paylist = 'Second Quarter Payment';
								 if ($paystat==2 and $haveaddpay<>2) {
									 $payst = 'PAID';
									 $counterpaid = $counterpaid + 1;
								} elseif ($paystat==2 and $haveaddpay==2) {
				                     $payst = 'UNPAID';
				                     $unpaid=1;	 
							     } else {
								     $payst = 'UNPAID';
								     $payme=1;
								     $unpaid=1;
							     }
								
							} elseif ($qtrcnt==3 ) {
								
								$paylist = 'Third Quarter Payment';
								
								 if ($paystat==3 and $haveaddpay<>3) {
									 $payst = 'PAID';
									$counterpaid = $counterpaid + 1;
									
								} elseif ($paystat==3 and $haveaddpay==3) {
				                     $payst = 'UNPAID';
				                     $unpaid=1;	
							     } else {
								  
								     $payst = 'UNPAID';
								     $payme=1;
								     $unpaid=1;
							     }
							
							} elseif ($qtrcnt==4) {
								$paylist = 'Fourth Quarter Payment';
								
								 if ($paystat==4 and $haveaddpay<>4) {
									 $payst = 'PAID';
									 $counterpaid = $counterpaid + 1;
								} elseif ($paystat==4 and $haveaddpay==4 ) {
				                     $payst = 'UNPAID';
				                     $unpaid=1;	 
							     } else {
								     $payst = 'UNPAID';
								     $payme=1;
								   $unpaid=1;
								   
							     }
							} else {
								$payst = 'PAID';
								$redire=1;
							}
			
			} else {
				
							if ($qtrcnt==1 ) {
								
								$paylist = 'First Quarter Payment';
								 if ($paystat==1 and $haveaddpay<>1) {
									
									 $payst = 'PAID';
									 $counterpaid = $counterpaid + 1;
							    } elseif ($paystat==1 and $haveaddpay==1) {
				                     $payst = 'UNPAID';
				                    
								}	else {
								     $payst = 'UNPAID';
								     $payme=1;
							     }
								
							} elseif ($qtrcnt==2 and $watqtr>=1 and $watqtr<1.75 ) {
								$paylist = 'Second Quarter Payment';
								 if ($paystat==2 and $haveaddpay<>2) {
									 $payst = 'PAID';
									 $counterpaid = $counterpaid + 1;
								} elseif ($paystat==2 and $haveaddpay==2) {
				                     $payst = 'UNPAID';
				                     	 
							     } else {
								     $payst = 'UNPAID';
								     $payme=1;
							     }
								
							} elseif ($qtrcnt==3 and $watqtr>=1.75 and $watqtr<2.5 ) {
								
								$paylist = 'Third Quarter Payment';
								
								 if ($paystat==3 and $haveaddpay<>3) {
									 $payst = 'PAID';
									$counterpaid = $counterpaid + 1;
									
								} elseif ($paystat==3 and $haveaddpay==3) {
				                     $payst = 'UNPAID';
				                     	
							     } else {
								  
								     $payst = 'UNPAID';
								     $payme=1;
								     
							     }
								
							} elseif ($qtrcnt==4 and $watqtr >=2.5) {
								$paylist = 'Fourth Quarter Payment';
								
								 if ($paystat==4 and $haveaddpay<>4) {
									 $payst = 'PAID';
									 $counterpaid = $counterpaid + 1;
								} elseif ($paystat==4 and $haveaddpay==4 ) {
				                     $payst = 'UNPAID';
				                     	 
							     } else {
								     $payst = 'UNPAID';
								     $payme=1;
								   
								   
							     }
							} else {
								$payst = 'PAID';
								$redire=1;
							}
			
			}
			
			
			if ($predcomp==1 and $paystat>0 and $stat=='New') {
				
				//ano quarter na
				$watqtr = date('m') / 4;
				$payd = mysql_query("select * from ebpls_buss_penalty1");
				
					$paydue = mysql_fetch_assoc($payd);
					
				if ($watqtr< 1 and $q==1) {
				$paylist = 'First Quarter Payment';
									
				$paydue = $paydue['qtrdue1'];
				$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
							if ($paystat==1 and $haveaddpay<>1) {
											
											 $payst = 'PAID';
											 $counterpaid = $counterpaid + 1;
							} elseif ($paystat==1 and $haveaddpay==1) {
						                     $payst = 'UNPAID';
						                     
							}	else {
										     $payst = 'UNPAID';
										     $payme=1;
							}
				} elseif ($watqtr>=1 and $watqtr<1.75 and $q==2) {
					$paylist = 'Second Quarter Payment';
					$paydue = $paydue['qtrdue2'];
				
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 	if ($paystat==2 and $haveaddpay<>2) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
						} elseif ($paystat==2 and $haveaddpay==2 and $q==3) {
                     $payst = 'UNPAID';
                     	 
			     		} else {
				     $payst = 'UNPAID';
				     $payme=1;
			     		}
				} elseif ($watqtr>=1.75 and $watqtr<2.5 and $q==3) {
					$paylist = 'Third Quarter Payment';
					$paydue = $paydue['qtrdue3'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					
					 if ($paystat==3 and $haveaddpay<>3) {
				
					 $payst = 'PAID';
					
					 $counterpaid = $counterpaid + 1;
					 } elseif ($paystat==3 and $haveaddpay==3) {
                     $payst = 'UNPAID';
                     	 
			     	 } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     	 }
				} else {
					
					$paylist = 'Fourth Quarter Payment';
					$paydue = $paydue['qtrdue4'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 if ($paystat==4 and $haveaddpay<>4) {
					
					 $counterpaid = $counterpaid + 1;
					 } elseif ($paystat==4 and $haveaddpay==4) {
                     $payst = 'UNPAID';
                     	 
			     	 } else {
				     	
				     	if ($q==5) {
					     					     	
					     	$payst = 'PAID';
				     		$wiw=1;
				    
			     		} else {
				     		 $payst = 'UNPAID';
				     $payme=1;
			     		}
			     		
			     	 }
				}
			}
		
			
		
				if ($qtrcnt==1 ) {
				$paylist = 'First Quarter Payment';
			}	elseif ($qtrcnt==2) {
				$paylist = 'Second Quarter Payment';
			} elseif ($qtrcnt==3) {
				$paylist = 'Third Quarter Payment';
			} else {
				$paylist = 'Fourth Quarter Payment';
			}
	
			if ($payst=='UNPAID') {
			$amt2pay = $divtax + $divfee + $newtax;
			
			//echo "$divtax + $divfee + $newtax;";
			} else {
				
				if ($haveaddpay==1) {
					$amt2pay=0 + $newtax;
				} else {
					$amt2pay=0;
					
				}
			}
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				
				if ($isbad >= 1 and $qtrcnt == $haveaddpay) {
					
					$amt2pay=$isbad;
					$payst = "UNPAID";
					$unpaid=0;
					
				} else {
					$amt2pay = 0;
				}
				
			}
		
			if ($isbad > 0 and $qtrcnt == $haveaddpay) {
				$payst = "UNPAID";
				$amt2pay = $isbad;
			}
			
			$divfee=0; // fee already added
			if ($swaive<>'') {
				
				    
				     	if ($getqtr1==1 and $fpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$fpay = 1;
					     	$divfee=$totalfee;
					     	$unpaid=0;
					     	
				     	} elseif ($getqtr2==2 and $secpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$secpay = 2;
					     	$divfee=$totalfee;
					     	$unpaid=0;
				     	} elseif ($getqtr3==3 and $thpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$thpay = 3;
					     	$divfee=$totalfee;
					     	$unpaid=0;
					     	
				     	}
				     	
			}
			
			$qtrcntp[$qtrcnt] = $payst;
			
		
			if ($predcomp<>1 || $stat=='ReNew') {
			
			?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			
			if ($haveaddpay==1 and $qtrcnt==$paystat) {
               $payst = 'UNPAID';
               $unpaid=1;
               $haveaddpay=0;
           }
		
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($qtrcnt>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				if ($nStartDate < $renewaldate) {
					$nrmv = $qtrcnt - 1;
					if ($qtrcntp[$nrmv] != 'TAX WAIVED' and $qtrcnt != $haveaddpay) {
						require "includes/business_penalty.php";
					}
				}
			} elseif ($stat<>'New') {
				if ($qtrcntp[$nrmv] != 'TAX WAIVED' and $qtrcnt != $haveaddpay) {
				require "includes/business_penalty.php";
				}
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			
		
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $unpaid==1) {
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash</a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			?>
			</tr>
			<?php
			} else { //predcomp==1
			//dito mo ayusin
		
			if ($wiw==1) {
				$payst='PAID';
			}
			$nbong = 2;
		if ($q=='') {
			$q=1;
		}
		if ($getqtr==0) {
			$nilan = $ilan+$pilan;
		} else {
			if ($nilan=='') {
			$nilan = $ilan + $getqtr;
			} else {
			$nilan = $ilan + $pilan;
			
			}
		}
		
		
			if ($totalpayment<>$isbad and $haveaddpay>=1) {
				$nilan=0;
			}
			if ($nilan<>$pilan ) {
			
					if ($displayed=='' and $payst=='UNPAID' and $q==$qtrcnt) { 
						
					if ($counterpaid>0) {
						$totalfee=0;
					}
					
					//$amt2pay = $totaltax + $totalfee;
				?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?> </td>
			<td align=right width=20%><?php
			$checkbus = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                business_id='$business_id'  and recpaid=1
                                and date_create like '$yearnow%' order by tempid asc");
$getbss = mysql_fetch_assoc($checkbus);
$geb = $getbss[bus_code];
$cntbu = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                business_id='$business_id' and recpaid=1 and
                                bus_code = '$geb'
                                and date_create like '$yearnow%' order by tempid desc");
$cntb = mysql_num_rows($cntbu);
             if ($getqtr==0) {
             $nilan = $ilan+$pilan+$cntb;
                  
			} else {
		
				$nilan=$q;
			}
			
			if ($predqtr==$q) {
				$nilan=$predqtr;
			}
			if ($q==4) {
			$qstat=$paystat+$ilan;
			} else {
				$qstat=$q;
			}
		
			
			
			if ($q<>5 and $q==$nilan ) {
				
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($q>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
				$watyrsd = date('Y', strtotime($nStartDate));
				if ($watyrsd<>$yearnow) { //new rec
					$nStartDate = date('Y-m-d');
				}
			
 				if (strtotime($nStartDate) > strtotime($yearnow."-".$paydue)) {
	 				
					$nrmv = $qtrcnt - 1;
					$ntyear = date('Y');
					$seeifpaid = mysql_query("select * from tempbusnature  where bus_code='$busid' and owner_id = '$owner_id' and business_id = '$business_id' and date_create like '$yearnow%' and recpaid='1'");
					$seeifpaid1 = mysql_num_rows($seeifpaid);
					
					if ($seeifpaid1 > 0) {
						
						require "includes/business_penalty.php";
					}
				} elseif (strtotime($yearnow."-".$paydue)<strtotime($renewaldate)) {
					
					$nrmv = $qtrcnt - 1;
					$ntyear = date('Y');
					$seeifpaid = mysql_query("select * from tempbusnature  where bus_code='$busid' and owner_id = '$owner_id' and business_id = '$business_id' and date_create like '$yearnow%' and recpaid='1'");
					$seeifpaid1 = mysql_num_rows($seeifpaid);
					
					if ($seeifpaid1 > 0) {
						
						require "includes/business_penalty.php";
					}
				}
				
				
			} elseif ($stat != 'New') {
				
				require "includes/business_penalty.php";
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			} else {
				
				if ($haveaddpay==1) {
					
					$amt2pay = $totalremain;
					echo number_format($amt2pay,2);
					$unpaid=1;
					$redire='';
				} else {
			
					if ($q<4) {
				echo "Need gross sales";
				$payst = "Need gross sales";
				$unpaid=="123";
					$amt2pay=0;
// 					} elseif ($q==4) {
// 					$payst = "PAID";
// 					$unpaid=="123";
// 					$amt2pay=0;
					}
				}
				
			}
		
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php 
				if ($itemID_==2212 and $amt2pay>0 ) {
					
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $q; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $q; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			?>
			</tr>
			<?php
			$displayed=1;
			$displayamt = $amt2pay;
			$pass=1;
			} else {
				
				if ($pass=='') {
				?>
			<tr>
			<td ></td>
			<td  width=20%></td>
			<td align=right width=20%>0.00</td>
			<td align=center width=20%><?php echo $payst; ?></td>
			</tr>
			<?php 
				$pass=1;
					}
			}
			} 
			}
			if ($predcomp<>1 || $stat=='ReNew') {
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
			
			} else {
			$totalamt2pay=$displayamt;
		
			}	
			
		
		} //end while qtr
		?>
			
			<tr>
			<td ></td>
			<td align=right width=20%>Total (Php) &nbsp; </td>
			<td align=right width=20%><?php echo number_format($totalamt2pay,2); ?></td>
			<td align=center width=20%></td>
			<?php
				if ($itemID_==2212) {
			?>
				<td align=center width=20%></td>
			<?php
			}
			?>
			</tr>
			<?php
	
	
	
	} // end scenario quarterly
} elseif (strtolower($pmode)=='semi-annual') { //

	if ($staxfee == '' )  { //Separate computation of tax/fee = ''
	
		if ($swaive=='') { // will not waive tax	
		$divtax = $totaltax / 2;
		$divfee = $totalfee / 2;
		} else { //will waive tax
		$waivedtax = ($totaltax / 2) * $getsem; // remaining tax to pay
		$divtax = ($totaltax-$waivedtax) / (2 - $getsem); // distribute tax
		$divfee = $totalfee / (2 - $getsem); // fees per payment
	
		} // end scenario waive tax
		
		$semcnt = 0;
		while ($semcnt<2) {
			
			$semcnt++;
			if (strtolower($pmode) =="semi-annual") {
			        $gtcount = $semcnt;
			}
			if ($scounter != $gtcount) {
			        $getsumnun1 = 0;
			}

			$payd = mysql_query("select * from ebpls_buss_penalty1");
			$paydue = mysql_fetch_assoc($payd);
			$semdue = 'semdue'.$semcnt;
			$paydue = $paydue[$semdue];
			$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
			
			//get payment status
			$pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$semcnt' and ts like '$yearnow%' and transaction != 'Retire'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			if ($paymade >= 1) {
				$paystat = $semcnt;
			} else {
				$paystat = 0;
			}
			
			
				$isbad = ($divtax + $divfee + $getsumnun1) - $paymade;
			
			//$isbad = ($divtax + $divfee) - $paymade;
			$NgTotalTaxFee = $divtax + $divfee;
			
					
			if ($semcnt==1 ) {
				$paylist = 'First Semi-Annual Payment';
				 if ($paystat==1 and $haveaddpay<>1) {
					
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
			    } elseif ($paystat==1 and $haveaddpay==1) {
		                     $payst = 'UNPAID';
                     
				}	else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				
			} elseif ($semcnt==2) {
				$paylist = 'Second Semi-Annual Payment';
				 if ($paystat==2 and $haveaddpay<>2) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==2) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
			
			} 
			
			
			
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				if ($isbad >= 1) {
					if ($semcnt == '1' and $haveaddpay == '2') {
						$amt2pay=0;
                                                $payst = "PAID";
                                                $unpaid=0;
					} else {
						$amt2pay=$isbad;
						$payst = "UNPAID";
						$unpaid=1;
					}
				} else {
					$amt2pay = 0;
				}
			}
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				$unpaid=0;
			}
			if ($isbad > 0) {
				if ($semcnt == '1' and $haveaddpay == '2') {
                                                $amt2pay=0;
                                                $payst = "PAID";
                                                $unpaid=0;
                                } else {
					$payst = "UNPAID";
					$amt2pay = $isbad;
				}
			}
			if ($swaive<>'') {
				
				     
				     	if ($getsem==1 and $fpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$fpay = 1;
					     	$unpaid=0;
					     	
				     		     	
				     	}
				     	
			}
			$semcntp[$semcnt] = $payst;
			
			?>
		
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($semcnt>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				if ($nStartDate < $renewaldate) {
					$nrmv = $semcnt - 1;
					if ($semcntp[$nrmv] != 'TAX WAIVED' and $semcnt != $haveaddpay) {

						require "includes/business_penalty.php";
					}
				}
			} elseif ($stat != 'New') {
//voni
				if ($semcntp[$nrmv] != 'TAX WAIVED' and $semcnt != $haveaddpay) {
					require "includes/business_penalty.php";
				}
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			
		
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
	//		echo "$unpaid==1";
				if ($itemID_==2212 and $unpaid==1) {
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $semcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $semcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			?>
			</tr>
			<?php
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
		} //end while sem
		?>
			<tr>
			<tr>
			<td ></td>
			<td align=right width=20%>Total (Php) &nbsp; </td>
			<td align=right width=20%><?php echo number_format($totalamt2pay,2); ?></td>
			<td align=center width=20%></td>
			<?php
				if ($itemID_==2212) {
			?>
				<td align=center width=20%></td>
			<?php
			}
			?>
			</tr>
			<?php
	} else { // separate computation tax/fee==1 
	//sem anu
		$divfee=$totalfee;
		if ($swaive=='') { // will not waive tax	
		$divtax = $totaltax / 2;
		
		} else { //will waive tax
		$waivedtax = ($totaltax / 2) * $getsem; // remaining tax to pay
		$divtax = ($totaltax-$waivedtax) / (2 - $getsem); // distribute tax
		
		} // end scenario waive tax
		
		$semcnt = 0;
		while ($semcnt<2) {
			$semcnt++;
			$payd = mysql_query("select * from ebpls_buss_penalty1");
			$paydue = mysql_fetch_assoc($payd);
			$semdue = 'semdue'.$semcnt;
			$paydue = $paydue[$semdue];
			$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
			
			//get payment status
			$pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$semcnt' and ts like '$yearnow%' and transaction != 'Retire'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			if ($paymade >= 1) {
				$paystat = $semcnt;
			} else {
				$paystat = 0;
			}
			
				if ($predcomp == '1' and $haveaddpay>=1 and $stat=='New') {
				
				$isbad = (($divtax * 2) + $divfee) - $paymade;
				$nbong = 3;
				
			} elseif ($predcomp == '1' and $haveaddpay < 1 and $stat=='New') {
				$isbad = (($divtax * 2) + $divfee);
				$nbong = 2;
			} else {
				$isbad = ($divtax + $divfee + $getsumnun1) - $paymade;	
				
			} 
			
			
		//	$isbad = ($divtax + $divfee) - $paymade;
			$NgTotalTaxFee = $divtax + $divfee;
			
				$watqtr = date('m') / 2;	
			
				if ($predcomp=='0') {
						if ($semcnt==1 ) {
				$paylist = 'First Semi-Annual Payment';
				 if ($paystat==1 and $haveaddpay<>1) {
					
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
			    } elseif ($paystat==1 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     
				}	else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				
			} elseif ($semcnt==2) {
				$paylist = 'Second Semi-Annual Payment';
				 if ($paystat==2 and $haveaddpay<>2) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==2) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
			
			} 
			
			} else {
				
				if ($semcnt==1 and $watqtr<=6) {
				$paylist = 'First Semi-Annual Payment';
				
				 if ($paystat==1 and $haveaddpay<>1) {
					
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
			    } elseif ($paystat==1 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     
				}	else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				
			} elseif ($semcnt==2 ) {
				$paylist = 'Second Semi-Annual Payment';
				 if ($paystat==2 and $haveaddpay<>2) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==2) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
			
			} 
				
				
				
			
			}
				
				
				
			
			if ($semcnt==1 ) {
				$paylist = 'First Semi-Annual Payment';
			}	else {
				$paylist = 'Second Semi-Annual Payment';
			} 
				
				
				
			
				
				
				
				
						
			
			
			if ($predcomp==1 and $paystat>0 and $stat=='New') {
				$watqtr = date('m') / 2;
				$payd = mysql_query("select * from ebpls_buss_penalty1");
					$paydue = mysql_fetch_assoc($payd);
				if ($watqtr<= 3 and $q==1) {
					$paylist = 'First Semi-Annual Payment';
					
					$paydue = $paydue['sem1'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					if ($paystat==1 and $haveaddpay<>1) {
					
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
			     } elseif ($paystat==1 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     
				}	else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
			     
			     
				} else {
					$paylist = 'First Semi-Annual Payment';
					
					$paydue = $paydue['sem1'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					if ($paystat==2 and $haveaddpay<>1) {
					
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
			     } elseif ($paystat==2 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     
				}	else {
				     	 
				     	if ($q==5) {
					     				     	
					     	$payst = 'PAID';
				     		$wiw=1;
				    
			     		} else {
				     		 $payst = 'UNPAID';
				     $payme=1;
			     		}
			     }
				}
				
				
			}
			
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				
				if ($isbad >= 1 and $unpaiddisplay=='') {
					$amt2pay=$isbad;
					$payst = "UNPAID";
					$unpaid=1;
					
				} else {
					$amt2pay = 0;
					$unpaid = '';
					$payst = "PAID";
				}
			}
			if ($isbad > 0) {
				$payst = "UNPAID";
				$amt2pay = $isbad;
				
			}
			

			
			$divfee=0; // fee already added
			if ($swaive<>'') {
				
				     				     	
				     	if ($getsem==1 and $fpay=='') {
					     	$payst='TAX WAIVED';
					     	$amt2pay='0.00';
					     	$fpay = 1;
					     	$divfee=$totalfee; //reset coz taz is waived
					       	$unpaid=0;
				     	}
				     	
			}
			$semcntp[$semcnt] = $payst;
			
			if ($predcomp<>1 || $stat=='ReNew') {
			?>
			
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($semcnt>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				$watyrsd = date('Y', strtotime($nStartDate));
				if ($watyrsd<>$yearnow) { //new rec
					$nStartDate = date('Y-m-d');
				}
				
 				if ($nStartDate < $renewaldate) {
	 				
					$nrmv = $semcnt - 1;
					if ($semcntp[$nrmv] != 'TAX WAIVED') {
						require "includes/business_penalty.php";
					}
				} elseif (strtotime($yearnow."-".$paydue)<strtotime($renewaldate)) {
					
					$nrmv = $qtrcnt - 1;
					$ntyear = date('Y');
					$seeifpaid = mysql_query("select * from tempbusnature  where bus_code='$busid' and owner_id = '$owner_id' and business_id = '$business_id' and date_create like '$yearnow%' and recpaid='1'");
					$seeifpaid1 = mysql_num_rows($seeifpaid);
					
					if ($seeifpaid1 > 0) {
						
						require "includes/business_penalty.php";
					}
				}
			} elseif ($stat!='New') {
				 if ($semcntp[$nrmv] != 'TAX WAIVED' and $semcnt != $haveaddpay) {
				
				require "includes/business_penalty.php";
				}
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			
		
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $unpaid==1) {
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $semcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $semcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			?>
			</tr>
			<?php
			} else { //predcomp==1
			if ($wiw==1) {
				$payst='PAID';
			}
			if ($getqtr==0) {
			$nilan = $ilan+$pilan;
		} else {
			if ($nilan=='') {
			$nilan = $ilan + $getqtr;
			} else {
			$nilan = $ilan + $pilan;
			
			}
		}
		
		
			if ($totalpayment<>$isbad) {
				$nilan=0;
			}
			if ($totalpayment<>$isbad) {
			
					if ($displayed=='' and $payst=='UNPAID' and $q==$semcnt) { 
					if ($counterpaid>0) {
						$totalfee=0;
					}
					$amt2pay = $totaltax + $totalfee;
				?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			if ($getqtr==0) {
			$nilan = $ilan+$pilan;
			} else {
				$nilan=$q;
			}
			if ($predqtr==$q) {
				$nilan=$predqtr;
			}
			if ($q<>5 and $q==$nilan) {
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($q>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				if ($nStartDate < $renewaldate) {
					$nrmv = $semcnt - 1;
					if ($semcntp[$nrmv] != 'TAX WAIVED') {
						require "includes/business_penalty.php";
					}
				}
			} elseif ($stat<>'New') {
				
				require "includes/business_penalty.php";
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			} else {
				if ($haveaddpay==1) {
					$amt2pay = $totalremain;
					echo number_format($amt2pay,2);
					$unpaid=1;
					$redire='';
				} else {
				echo "Need gross sales";
				$payst = "Need gross sales";
				$unpaid=="123";
				$amt2pay=0;
				}
			}
		
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $unpaid==1 and $redire=='') {
					
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $semcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $semcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			?>
			</tr>
			<?php
			$displayed=1;
			$displayamt = $amt2pay;
			}
			} 
			}
			if ($predcomp<>1 || $stat=='ReNew') {
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
			} else {
			$totalamt2pay=$displayamt;
			}		
			
		} //end while sem
		?>
			<tr>
			<tr>
			<td ></td>
			<td align=right width=20%>Total (Php) &nbsp; </td>
			<td align=right width=20%><?php echo number_format($totalamt2pay,2); ?></td>
			<td align=center width=20%></td>
			<?php
				if ($itemID_==2212) {
			?>
				<td align=center width=20%></td>
			<?php
			}
			?>
			</tr>
			<?php
	
	
	}
	
}  elseif (strtolower($pmode)=='annual') { 
	
	$payd = mysql_query("select * from ebpls_buss_penalty");
	$paydue = mysql_fetch_assoc($payd);
	$paydue = $paydue['renewaldate'];
	if (strtolower($pmode) =="annual") {
	        $gtcount = 1;
	}
	if ($scounter != $gtcount) {
        	$getsumnun1 = 0;
	}



	if ($businessyr==$currentyr and $stat=='New') {
	
		$businessmo = date('m', $startdate);
				if ($businessmo<=3) { // no waive
					$getqtr = 1;
				} elseif ($businessmo > 3 and $businessmo <= 6) { // 1st Q waive
					$getqtr = .75;
				} elseif ($businessmo > 6 and $businessmo <= 9) { // 2nd Q waive
					$getqtr = .5;
				} elseif ($businessmo > 9 ) { //3rd Q waive
					$getqtr = .25;
				}
	} else {
		$getqtr=1;
	}


if ($stat=='New') {
$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='New' and active=1 and recpaid=0");
$havnew = mysql_num_rows($checknew);

	if ($havnew>0) { //may bago na line
		$nat=0;
		while ($newnew = mysql_fetch_assoc($checknew)) {
			$nature[$nat] = $newnew[bus_code];
			$nat++;
		
		}
	}	
$checknew12 = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='New' and active=1 and recpaid =1");
$havnew12 = mysql_num_rows($checknew12);

	if ($havnew12>0) { //may bago na line
		$wagpenalty = 1;
	} else {
		$wagpenalty = 0;
	}

	//check kung bayad na luma
	
	$checkluma = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						recpaid='1' and active=1");
$bayadluma = mysql_num_rows($checkluma);
	if ($bayadluma>0) { //bayad na
		
		$wagpenalty = 1;
	} else {
		$wagpenalty = 0;
	}
		//pano pag may bagong line na hindi pa bayad luma na late na.. bwiset na tax payer na yan
		if ($havnew>0) {
			//total lahat ng tax nya
			$bis=0;
			
			while ($bis<$nat) {
			$biset = mysql_query("select * from tempassess 
						where owner_id='$owner_id' and business_id='$business_id' and
						natureid='$nature[$bis]' and active=1 and date_create like '$yearnow%'");
				while ($amtbis =mysql_fetch_assoc($biset)) {
					$getfeeid = $amtbis[tfoid];
					$gettotfee = mysql_query("select * from ebpls_buss_tfo where
												tfoid='$getfeeid'");
					$gid = mysql_fetch_assoc($gettotfee);
					if ($gid[taxfeetype]==1) {
						$newtax = $newtax + $amtbis[compval];
					} else {
						$newfee = $newfee + $amtbis[compval];
					}
				}
			
			   //check line date
        $cline = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                business_id='$business_id' and bus_code='$nature[$bis]'");
        $gdate = mysql_fetch_assoc($cline);
        $gd = $gdate['date_create'];
        $gy = date('Y', strtotime($gd));
        $gs = $gdate['transaction'];
        if ($gy==$currentyr and $gs=='New') {

                $linemo = date('m', strtotime($gd));
                                if ($linemo<=3) { // no waive
                                        $getqtr1 = 1;
                                } elseif ($linemo > 3 and $linemo <= 6) { // 1st Q waive
                                        $getqtr1 = .75;
                                } elseif ($linemo > 6 and $linemo <= 9) { // 2nd Q waive
                                        $getqtr1 = .5;
                                } elseif ($linemo > 9 ) { //3rd Q waive
                                        $getqtr1 = .25;
                                }
        } else {
                $getqtr1=1;
        }
			$newtax = $newtax * $getqtr1;	
	
			$bis++;			
			}
		}
$otax = $totaltax;
}







	
	if ($swaive=='') { // will not waive tax	
	$amt2pay = ($totaltax + $totalfee);
 	} else {
	$amt2pay = ($totaltax * $getqtr)  + $totalfee;
	$stax = $otax * $getqtr;
	$otax = $otax - $stax;
	$totalremain = $totalremain - $stax;

	}

  //get payment status
                        $pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
                                                                        trans_id = '$owner_id' and payment_id='$business_id' and ts like '$yearnow%' and transaction != 'Retire'");
                        $paystat = mysql_fetch_row($pays);
                        $paymade = $paystat[0];

                        if ($paymade >= 1) {
                                $paystat = 1;
                        } else {
                                $paystat = 0;
                        }
                        $isbad = $totaltaxfee - ($paymade + $otax ) ;
                        $NgTotalTaxFee = $totaltaxfee;
                                 if ($paystat==1) {
                                         $payst = 'PAID';

                             } else {
                                     $payst = 'UNPAID';
                                     $unpaid=1;
                             }



	
	if ($payst=='PAID') {
		if ($isbad >= 1) {
			$amt2pay=$isbad;
			$payst = "UNPAID";
			$unpaid=1;
		} else {
			$amt2pay = 0;
		}
	}
	?>
			<tr>
			<td > Annual Payment</td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php echo number_format($amt2pay,2);
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($stat<>'New') {
				
				require "includes/business_penalty.php";
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $unpaid==1) {
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','1','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>','<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','1','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			
			?>
			</tr>
			<?
				if ($amt2pay==0 and $totalremain>0) { //additional payment
				$payst='UNPAID';
				$amt2pay = $totalremain;
				?>
			<tr>
			<td > Additional Payment</td>
			<td  width=20%></td>
			<td align=right width=20%><?php echo number_format($amt2pay,2);?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212) {
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','2','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>','<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','2','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
				
				}
				}
			
			
			
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
		
		?>
			<tr>
			<tr>
			<td ></td>
			<td align=right width=20%>Total (Php) &nbsp; </td>
			<td align=right width=20%><?php echo number_format($totalamt2pay,2); ?></td>
			<td align=center width=20%></td>
			<?php
				if ($itemID_==2212) {
			?>
				<td align=center width=20%></td>
			<?php
			}
			?>
			</tr>
			<?php
	
} //end scenario for payment modes








?>






<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function PaymentCommand(cmd,amt,paymde,paypart,or,natid,pens,bustax,busfee, buspen, busint,nbacktax)
{
	var trans_id
        var x,y,w,h
        trans_id =  document._FRM.trans_id.value;
        // center on screen
        if ( cmd == 'CASH' ) {
                w = 400
                h = screen.height - 100
        } else if ( cmd == 'CHECK' ) {
                w = 600
                h = screen.height - 100
        }  else if ( cmd == 'CASHVIEW' ) {
                w = 400
                h = screen.height - 100
        }  else if ( cmd == 'CHECKVIEW' ) {
                w = 600
                h = screen.height - 100
        }  else if ( cmd == 'CHECKSTATUS' ) {
                w = 600
                h = screen.height - 200
        }



        x = screen.width/2 - w/2
        y = screen.height/2 - h/2
        strOption = 'toolbar=0,location=0,directories=0,menubar=0,resizable=0,scrollbars=1,status=1,width=' + w + ',height=' + h + ',screenX=' + x + ',screenY=' + y
      window.open ("ebplspay.php?&nature_id="+ natid + "&or1=" + or +"&paymde="+ paymde + "&owner_id=<?php echo $owner_id; ?>&permit_type=Business&istat=<?php echo $stat; ?>&pensked="+ pens +"&class_type=Permits&business_id=<?php echo $business_id; ?>&paypart="+ paypart + "&amtpay="+ amt + "&cmd=" + cmd + "&trans_id=" + trans_id + "&fee=" +  busfee + "&ntax=" +  bustax + "&penamt=" + buspen + "&surcharge=" + busint + "&exemption=<?php echo $totalexptax;?>&pmode=<?php echo $pmode;?>&paympart=" + paymde + "&sbacktax=" + nbacktax, cmd, strOption);

}

</script>

<?php
}
?>

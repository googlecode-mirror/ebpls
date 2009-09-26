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
$addend = "$yeartoday-";
if (strtolower($pmode)=='quarterly') {
	if ($qtrcnt == '1') {
		$getpendedrenewal = $getrenew['qtrdue1'];
	} elseif ($qtrcnt == '2') {
		$getpendedrenewal = $getrenew['qtrdue2'];
	} elseif ($qtrcnt == '3') {
		$getpendedrenewal = $getrenew['qtrdue3'];
	} elseif ($qtrcnt == '4') {
		$getpendedrenewal = $getrenew['qtrdue4'];
	}
}
if (strtolower($pmode)=='semi-annual') {
	if ($semcnt == '1') {
		$getpendedrenewal = $getrenew['semdue1'];
	} elseif ($semcnt == '2') {
		$getpendedrenewal = $getrenew['semdue2'];
	}
}
if (strtolower($pmode)=='annual') {
	$getpendedrenewal = $getpended['renewaldate'];
}
$renewaldate = "$addend$getpendedrenewal";
$renewaldate = strtotime($renewaldate);
$renewaldate = date('Y-m-d', $renewaldate);
$nStartDate = date('Y-m-d', $startdate);
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

$totalcash = $getcash[0];
$totalcheck = $totcheck[0];
$totalpayment =$totalcash+$totalcheck;

$totaltaxfee = $grandamt;
$totalfee = $totfee ;
$totalexptax = $exemptot;
$totaltax = ($totaltaxfee - $totalfee);

$totalremain = $totaltaxfee - $totalpayment;

echo $redire."==";

echo "<br>Total Tax/Fee = $totaltaxfee <br>
	   Total Tax = $totaltax <br>
	   Total Fee = $totalfee <BR>
	   Total exempted tax = $exemptot <br>
	   Total cash payment = $totalcash <BR>
	   Total check payment = $totalcheck <BR>
	   Total payment = $totalpayment <BR> 
	   Balance =  <BR>";

$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"staxesfees,swaivetax, predcomp","");
$prefset = FetchArray($dbtype,$staxfee);	

$startd = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise",
		" business_start_date","where business_id='$business_id' and owner_id='$owner_id'");
$startdate = FetchArray($dbtype,$startd);   
$startdate =strtotime($startdate['business_start_date']); 


$predcomp = $prefset['predcomp'];



$businessyr = date('Y', $startdate);
$currentyr = date('Y');

	if ($businessyr==$currentyr and $stat=='New') {
		$businessmo = date('m', $startdate);
			if (strtolower($pmode)=='quarterly') {
				if ($businessmo<=3) { // no waive
					$getqtr0 = 0;
					$getqtr = 0;
				} 
				if ($businessmo > 3 ) { // 1st Q waive
					$getqtr1 = 1;
					$getqtr = 1;
				} 
				if ($businessmo > 6 ) { // 2nd Q waive
					$getqtr2 = 2;
					$getqtr = 2;
				} 
				if ($businessmo > 9 ) { //3rd Q waive
					$getqtr3 = 3;
					$getqtr = 3;
				}
			} elseif (strtolower($pmode)=='semi-annual') {
				if ($businessmo<=6) { // no waive
					$getsem = 0;
				} else { // 1st Q waive
					$getsem = 1;
				}
			}
	} else {
		$getqtr=0;
		$getsem=0;
	}
		

//check if have new line 

	$checkline = mysql_query("select * from tempbusnature where owner_id='$owner_id'
								and business_id='$business_id' and recpaid=1 ");
	
	$checkhavepay = mysql_num_rows($checkline);
	
	$checkline = mysql_query("select * from tempbusnature where owner_id='$owner_id'
								and business_id='$business_id' and recpaid=0 and bus_code
								not in (select bus_code from tempbusnature where owner_id='$owner_id'
								and business_id='$business_id' and recpaid=1)");
	$checkhavennew = mysql_num_rows($checkline);
	
	$getline = mysql_fetch_assoc($checkline);
	$getlinedate = $getline['date_create'];
	
	if ($checkhavepay>0) {
		$checkhave=$checkhavennew;
	}
	$getline = mysql_fetch_assoc($checkline);
	$getlinedate = $getline['date_create'];
	
	if ($checkhave>0) {
		
		
		
		
 		if (strtolower($pmode)=='quarterly') {

				$lineqtr = date('m',strtotime($getlinedate)) /4;
				$watqtr = date('m') / 4;
				if ($watqtr< 1) {
					$qtrnow = 1;
					$qtrc = $qtr1;
					$haveaddpay=1;
				
				} elseif ($watqtr>=1 and $watqtr<1.75) {
					$qtrnow=2;
					$qtrc = $qtr2;
					$haveaddpay=1;
					
				} elseif ($watqtr>=1.75 and $watqtr<2.5) {
					$qtrnow=3;
					$qtrc = $qtr3;
					$haveaddpay=1;
				} else {
					$qtrnow=4;
					$qtrc = $qtr4;
					$haveaddpay=1;
				}
		
				
				if ($lineqtr< 1) {
					$qtrline = 1;
					
				} elseif ($lineqtr>=1 and $lineqtr<1.75) {
					$qtrline=2;
					
				} elseif ($lineqtr>=1.75 and $lineqtr<2.5) {
					$qtrline=3;
					
				} else {
					$qtrline=4;
					
				}
				
				
				
				
		} elseif (strtolower($pmode)=='semi-annual') {
				$lineqtr = date('m',strtotime($getlinedate)) /2;
				$watqtr = date('m') / 2;
				if ($watqtr<= 3) {
					$qtrnow = 1;
					$qtrc = $sem1;
					$haveaddpay=1;
				} else {
					$qtrnow=2;
					$qtrc = $sem2;
					$haveaddpay=1;
				}
				
				if ($lineqtr<= 3) {
					$qtrline = 1;
					
				} else {
					$qtrline=2;
					
				}
				
				
				
		} 	
	

				
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
			$payd = mysql_query("select * from ebpls_buss_penalty1");
			$paydue = mysql_fetch_assoc($payd);
			$qtrdue = 'qtrdue'.$qtrcnt;
			$paydue = $paydue[$qtrdue];
			$pendue = $paydue."-".date('Y');
			$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
			
			
			//get payment status
			$pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$qtrcnt' and ts like '$yearnow%'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			if ($paymade >= 1) {
				$paystat = $qtrcnt;
			} else {
				$paystat = 0;
			}
			$nbong = 0;
		   if ($predcomp == '1' and $haveaddpay==1) {
				
				$isbad = (($divtax * 4) + $divfee) - $paymade;
				
			} elseif ($predcomp == '1' and $haveaddpay != 1) {
				$isbad = (($divtax * 4) + $divfee);
				$nbong = 2;
			} else {
				$isbad = ($divtax + $divfee) - $paymade;
				$nbong = 1;
			}
			
			
			//$isbad = ($divtax + $divfee) - $paymade;
			$NgTotalTaxFee = $divtax + $divfee;
			
			
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
				
			} elseif ($qtrcnt==2) {
				$paylist = 'Second Quarter Payment';
				 if ($paystat==2 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				
			} elseif ($qtrcnt==3) {
				$paylist = 'Third Quarter Payment';
				 if ($paystat==3 and $haveaddpay<>1) {
					 $payst = 'PAID';
					$counterpaid = $counterpaid + 1;
				} elseif ($paystat==3 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
				     
			     }
				
			} elseif ($qtrcnt==4) {
				$paylist = 'Fourth Quarter Payment';
				 if ($paystat==4 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==4 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
				     
			     }
			} else {
				$payst = 'PAID';
				$redire=1;
			}
			
			if ($predcomp==1) {
				//ano quarter na
				$watqtr = date('m') / 4;
				$payd = mysql_query("select * from ebpls_buss_penalty1");
					$paydue = mysql_fetch_assoc($payd);
				if ($watqtr< 1) {
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
				} elseif ($watqtr>=1 and $watqtr<1.75) {
					$paylist = 'Second Quarter Payment';
					$paydue = $paydue['qtrdue2'];
				
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 if ($paystat==2 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				} elseif ($watqtr>=1.75 and $watqtr<2.5) {
					$paylist = 'Third Quarter Payment';
					$paydue = $paydue['qtrdue3'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 if ($paystat==3 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==3 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				} else {
					$paylist = 'Fourth Quarter Payment';
					$paydue = $paydue['qtrdue4'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 if ($paystat==4 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==4 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				}
			}
			
			
			
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				$unpaid=0;
			}
			
// 			if ($counterpaid<>'' and $payst <> 'PAID') { //over under payment
// 			$divpay = (($divtax*4) + ($divfee*4)) - $totalpayment;
// 			$amt2pay = $divpay/(4-$counterpaid);
// 		 	} 
			if ($payst=='UNPAID') {
			$amt2pay = $divtax + $divfee;
			} else {
				if ($isbad >= 1) {
					$amt2pay=$isbad;
					$payst = "UNPAID";
					$unpaid=1;
				} else {
					$amt2pay = 0;
				}
			}
			if ($isbad > 0) {
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
			if ($predcomp<>1) {
			
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
					if ($qtrcntp[$nrmv] != 'TAX WAIVED') {
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
			
			
	
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $unpaid==1) {
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
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
			
				if ($displayed=='' and $payst=='UNPAID') { 
					if ($counterpaid>0) {
						$totalfee=0;
					}
					$amt2pay = $totaltax + $totalfee;
				?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			
					if ($haveaddpay==1) {
				$amt2pay = $divtax; 
				$payst = 'UNPAID';
				$unpaid=1;
				
			}	
			
			if ($redire=='') {
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
			
			
			
			
 			if ($qtrcnt>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				if ($nStartDate < $renewaldate) {
					$nrmv = $qtrcnt - 1;
					if ($qtrcntp[$nrmv] != 'TAX WAIVED') {
						
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
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
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
			if ($predcomp<>1) {
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
			} else {
			$totalamt2pay=$displayamt;
			}	
			
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

// 		
// 		 if ($haveaddpay==1) {
// 			$newtax = ((($totaltaxfee - $totalfee) - (($totalpayment-$totalfee)*4)))/4;
// 			$newfee = ((($totaltaxfee - $totaltax - (($totalpayment-$totalfee)*4)));
// 			$payst = 'UNPAID';
// 			 $unpaid=1;
//          }

		
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
									payment_part='$qtrcnt' and ts like '$yearnow%'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			
			$pays = mysql_query("select * from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$qtrcnt' and ts like '$yearnow%'");
			$ridi = mysql_num_rows($pays);
			
			
			if ($paymade >= 1) {
				$paystat = $qtrcnt;
			} else {
				$paystat = 0;
			}
			if ($predcomp == '1' and $haveaddpay==1) {
				
				$isbad = (($divtax * 4) + $divfee) - $paymade;
				
			} elseif ($predcomp == '1' and $haveaddpay != 1) {
				$isbad = (($divtax * 4) + $divfee);
			} else {
				$isbad = ($divtax + $divfee) - $paymade;
			}
			$NgTotalTaxFee = $divtax + $divfee;
			
			
			
			
			
			
			
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
				
			} elseif ($qtrcnt==2) {
				$paylist = 'Second Quarter Payment';
				 if ($paystat==2 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				
			} elseif ($qtrcnt==3) {
				$paylist = 'Third Quarter Payment';
				 if ($paystat==3 and $haveaddpay<>1) {
					 $payst = 'PAID';
					$counterpaid = $counterpaid + 1;
				} elseif ($paystat==3 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
				     
			     }
				
			} elseif ($qtrcnt==4) {
				$paylist = 'Fourth Quarter Payment';
				 if ($paystat==4 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==4 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
				     
			     }
			} else {
				$payst = 'PAID';
				$redire=1;
			}
			
			
			
			if ($predcomp==1) {
				//ano quarter na
				$watqtr = date('m') / 4;
				$payd = mysql_query("select * from ebpls_buss_penalty1");
					$paydue = mysql_fetch_assoc($payd);
				if ($watqtr< 1) {
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
				} elseif ($watqtr>=1 and $watqtr<1.75) {
					$paylist = 'Second Quarter Payment';
					$paydue = $paydue['qtrdue2'];
				
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 if ($paystat==2 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				} elseif ($watqtr>=1.75 and $watqtr<2.5) {
					$paylist = 'Third Quarter Payment';
					$paydue = $paydue['qtrdue3'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 if ($paystat==3 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==3 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				} else {
					$paylist = 'Fourth Quarter Payment';
					$paydue = $paydue['qtrdue4'];
					$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
					 if ($paystat==4 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==4 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				}
			}
		
			
			
			
// 			if ($counterpaid<>'' and $payst <> 'PAID') { //over under payment
// 			$divpay = (($divtax*4) + ($divfee*4)) - $totalpayment;
// 			$amt2pay = $divpay/(4-$counterpaid);
// 		 	} 
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
				
				if ($isbad >= 1) {
					$amt2pay=$isbad;
					$payst = "UNPAID";
					$unpaid=0;
					
				} else {
					$amt2pay = 0;
				}
			}
			
			if ($isbad > 0) {
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
			
			
			if ($predcomp<>1) {
			
			?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			
			if ($haveaddpay==1) {
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
					if ($qtrcntp[$nrmv] != 'TAX WAIVED') {
						
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
			
		
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $unpaid==1) {
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
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
			
					if ($displayed=='' and $payst=='UNPAID') { 
					if ($counterpaid>0) {
						$totalfee=0;
					}
					
					//$amt2pay = $totaltax + $totalfee;
				?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
				
			if ($redire=='') {
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($qtrcnt>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				if ($nStartDate < $renewaldate) {
					$nrmv = $qtrcnt - 1;
					if ($qtrcntp[$nrmv] != 'TAX WAIVED') {
						
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
				echo "Need gross sales";
				$payst = "Need gross sales";
				$unpaid=="123";
				$amt2pay=0;
				}
				
			}
		
			
			
			?></td>
			<td align=center width=20%><?php echo $payst; ?></td>
			<?php
				if ($itemID_==2212 and $amt2pay>0) {
					
			?>
			
			
				<td align=center width=20%>
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
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
			if ($predcomp<>1) {
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
			} else {
			$totalamt2pay=$displayamt;
			}	
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
			$payd = mysql_query("select * from ebpls_buss_penalty1");
			$paydue = mysql_fetch_assoc($payd);
			$semdue = 'semdue'.$semcnt;
			$paydue = $paydue[$semdue];
			$paydue = $paydue. " ".date('l', strtotime($paydue."-".date('Y'))); 
			
			//get payment status
			$pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and
									payment_part='$semcnt' and ts like '$yearnow%'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			if ($paymade >= 1) {
				$paystat = $semcnt;
			} else {
				$paystat = 0;
			}
			
				if ($predcomp == '1' and $haveaddpay==1) {
				
				$isbad = (($divtax * 2) + $divfee) - $paymade;
				
			} elseif ($predcomp == '1' and $haveaddpay != 1) {
				$isbad = (($divtax * 2) + $divfee);
			} else {
				$isbad = ($divtax + $divfee) - $paymade;
			}
			
			
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
				 if ($paystat==2 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
			
			} 
			
			
			if ($predcomp==1) {
				$watqtr = date('m') / 2;
				$payd = mysql_query("select * from ebpls_buss_penalty1");
					$paydue = mysql_fetch_assoc($payd);
				if ($watqtr<= 3) {
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
				     $payst = 'UNPAID';
				     $payme=1;
			     }
				}
				
				
			}
			
// 			
// 			if ($counterpaid<>'' and $payst <> 'PAID' and $haveaddpay<>1) { //over under payment
// 			$divpay = (($divtax*4) + ($divfee*4)) - $totalpayment;
// 			$amt2pay = $divpay/(4-$counterpaid);
// 		 	} 
// 			if ($payst=='UNPAID') {
// 			$amt2pay = $divtax + $divfee;
// 			} else {
// 			$amt2pay=0;
// 			}
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				if ($isbad >= 1) {
					$amt2pay=$isbad;
					$payst = "UNPAID";
					$unpaid=1;
				} else {
					$amt2pay = 0;
				}
			}
			if ($isbad > 0) {
				$payst = "UNPAID";
				$amt2pay = $isbad;
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
				if ($predcomp<>1) {
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
					if ($semcntp[$nrmv] != 'TAX WAIVED') {

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
			
				if ($displayed=='' and $payst=='UNPAID') { 
					if ($counterpaid>0) {
						$totalfee=0;
					}
					$amt2pay = $totaltax + $totalfee;
				?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			
			
			if ($redire=='') {
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($semcnt>1 and $stat=='New') {
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
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
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
			if ($predcomp<>1) {
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
									payment_part='$semcnt' and ts like '$yearnow%'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			if ($paymade >= 1) {
				$paystat = $semcnt;
			} else {
				$paystat = 0;
			}
			
				if ($predcomp == '1' and $haveaddpay==1) {
				
				$isbad = (($divtax * 2) + $divfee) - $paymade;
				
			} elseif ($predcomp == '1' and $haveaddpay != 1) {
				$isbad = (($divtax * 2) + $divfee);
			} else {
				$isbad = ($divtax + $divfee) - $paymade;	
				
			} 
			
			
		//	$isbad = ($divtax + $divfee) - $paymade;
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
				 if ($paystat==2 and $haveaddpay<>1) {
					 $payst = 'PAID';
					 $counterpaid = $counterpaid + 1;
				} elseif ($paystat==2 and $haveaddpay==1) {
                     $payst = 'UNPAID';
                     	 
			     } else {
				     $payst = 'UNPAID';
				     $payme=1;
			     }
			
			} 
			if ($predcomp==1) {
				$watqtr = date('m') / 2;
				$payd = mysql_query("select * from ebpls_buss_penalty1");
					$paydue = mysql_fetch_assoc($payd);
				if ($watqtr<= 3) {
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
				     $payst = 'UNPAID';
				     $payme=1;
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
			
// 			if ($counterpaid<>'' and $payst <> 'PAID' and $haveaddpay<>1) { //over under payment
// 			$divpay = (($divtax*4) + ($divfee*4)) - $totalpayment;
// 			$amt2pay = $divpay/(4-$counterpaid);
// 			
// 		 	} 
// 		 	echo $amt2pay;
// 			if ($payst=='UNPAID') {
// 			$amt2pay = $divtax + $divfee;
// 			} else {
// 			$amt2pay=0;
// 			}
			
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
			
			if ($predcomp<>1) {
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
					if ($semcntp[$nrmv] != 'TAX WAIVED') {
						require "includes/business_penalty.php";
					}
				}
			} elseif ($stat!='New') {
				
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
			
				if ($displayed=='' and $payst=='UNPAID') { 
					if ($counterpaid>0) {
						$totalfee=0;
					}
					$amt2pay = $totaltax + $totalfee;
				?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			
			
			if ($redire=='') {
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			if ($semcnt>1 and $stat=='New') {
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
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','<?php echo $qtrcnt; ?>','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
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
			if ($predcomp<>1) {
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
	
	//get payment status
			$pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details where
									trans_id = '$owner_id' and payment_id='$business_id' and ts like '$yearnow%'");
			$paystat = mysql_fetch_row($pays);
			$paymade = $paystat[0];
			if ($paymade >= 1) {
				$paystat = $semcnt;
			} else {
				$paystat = 0;
			}
			$isbad = ($divtax + $divfee) - $paymade;
			$NgTotalTaxFee = $divtax + $divfee;
			
				 if ($paystat==1) {
					 $payst = 'PAID';
					 
			     } else {
				     $payst = 'UNPAID';
				     $unpaid=1;
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
	}
	
	if ($swaive=='') { // will not waive tax	
	$amt2pay = ($totaltax + $totalfee);
 	} else {
	$amt2pay = ($totaltax * $getqtr)  + $totalfee;
	
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


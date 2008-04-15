<?php
/*
	Purpose: Generates Payment Schedule at bottom of Assessment Screen.
	Inputs: owner_id$ and business_id$ and currentyear; previous payments and assessment
	Outputs: Screen of payment schedule
	Prerequisites: 
	Prepared by:
	
Modification History:
2008.04.04: Corrected Malformed HTML at end of output by adding <table> ... </table> 
			around Total (Php) to correct display problem (5 places: Ln 585, 1420, 1781, 2363, 2752) by Ron Crabtree
2008.04.15	Attempting to correct amount differs with assessment screen.  Also tightened up code to improve readability.	
*/
*/
//$nSurchargeAmount = 0;
//$nInterestAmount = 0;
//$nbacktax = 0;
$getgross =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =1
                                   and date_create like '$yearnow%'");
$gross =mysql_num_rows($getgross);
             
if ($gross==0) {
	    ?>
	    <body onLoad="alert('Tax payer have not yet entered gross sales for this year. \n 
		Payment schedule is inaccurate and will not be displayed ');"></body>
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
	if ($montoday >= 1 and $montoday <= 3) {  $qqtrcnt = 1; }
	elseif ($montoday >= 4 and $montoday <= 6) { $qqtrcnt = 2; }
	elseif ($montoday >= 7 and $montoday <= 9) { $qqtrcnt = 3; }
	elseif ($montoday >= 10 and $montoday <= 12) { $qqtrcnt = 4; }
	if ($qqtrcnt == '1') { $getpendedrenewal = $getrenew['qtrdue1']; } 
	elseif ($qqtrcnt == '2') { $getpendedrenewal = $getrenew['qtrdue2']; } 
	elseif ($qqtrcnt == '3') { $getpendedrenewal = $getrenew['qtrdue3']; } 
	elseif ($qqtrcnt == '4') { $getpendedrenewal = $getrenew['qtrdue4']; }
}
elseif (strtolower($pmode)=='semi-annual') {
	if ($montoday >= 1 and $montoday <= 6) { $ssemcnt = 1; }
	elseif ($montoday >= 7 and $montoday <= 12) {$ssemcnt = 2; }
	if ($ssemcnt == '1') { $getpendedrenewal = $getrenew['semdue1']; }
	elseif ($ssemcnt == '2') { $getpendedrenewal = $getrenew['semdue2']; }
}
elseif (strtolower($pmode)=='annual') {
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

//get cash payments first
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
		$a1 = 1; $a2 = 3; $monthcounter = 1;
	} elseif ($buwannatinngayon >= 4 and $buwannatinngayon <= 6) {
		$a1 = 2; $a2 = 6; $monthcounter = 2;
	} elseif ($buwannatinngayon >= 7 and $buwannatinngayon <= 9) {
		$a1 = 3; $a2 = 9; $monthcounter = 3;
	} elseif ($buwannatinngayon >= 10 and $buwannatinngayon <= 12) {
		$a1 = 4; $a2 = 12; $monthcounter = 4;
	}
	$scounter =$qqtrcnt;
} elseif (strtolower($pmode) == "semi-annual") {
	if ($buwannatinngayon >= 1 and $buwannatinngayon <= 6) {
		$a1 = 1; $a2 = 6; $monthcounter = 1;
	} elseif ($buwannatinngayon >= 7 and $buwannatinngayon <= 12) {
		$a1 = 2; $a2 = 12; $monthcounter = 2;
	}
	$scounter =$ssemcnt;
} elseif (strtolower($pmode) == "annual") {
	$a1 = 1; $a2 = 12; $scounter =1;
}

//echo "thth".$qtrcnt."VooDoo";
if ($monthcounter <= $scounter) {
$getsurchargepaid = @mysql_query("select sum(penalty), sum(surcharge), sum(backtax), sum(taxes) from comparative_statement a, ebpls_transaction_payment_or_details b, ebpls_transaction_payment_or c  where a.owner_id = '$owner_id' and a.business_id = '$business_id' and a.for_year = '$taonnatinngayon' and a.payment_number = '$a1' and a.or_no = c.payment_code and b.or_no = c.or_no and b.transaction != 'Retire'");

//echo "select sum(penalty), sum(surcharge), sum(backtax), sum(taxes) from comparative_statement a, ebpls_transaction_payment_or_details b, ebpls_transaction_payment_or c  where a.owner_id = '$owner_id' and a.business_id = '$business_id' and a.for_year = '$taonnatinngayon' and a.payment_number = '$a1' and a.or_no = c.payment_code and b.or_no = c.or_no and b.transaction != 'Retire'";

	$getsumnun = @mysql_fetch_row($getsurchargepaid);
	$getsumnun1 = $getsumnun[0] + $getsumnun[1] + $getsumnun[2];
	$paidtaxes = $getsumnun[3];

} else {
	$getsumnun1 = 0;
}

$getsumnun2 = $getsumnun1;
$totalcash = $getcash[0];
$totalcheck = $totcheck[0];
$totalpayment =$totalcash+$totalcheck;

$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference", "staxesfees,swaivetax, predcomp","");
$prefset = FetchArray($dbtype,$staxfee);

$startd = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise",
                "business_start_date","where business_id='$business_id' and owner_id='$owner_id'");
$startdate = FetchArray($dbtype,$startd);
$startdate =strtotime($startdate['business_start_date']);

$getsassess = @mysql_query("select * from ebpls_buss_preference");
$getsassess1 = @mysql_fetch_assoc($getsassess);
$Istaxfee = $getsassess1['staxesfees'];
$predcomp = $prefset['predcomp'];

if ($predcomp==1 and $stat=='New') { $exemptedfee=0; }

$totaltaxfee = $grandamt;
$totalfee = $totfee - $exemptedfee;

$totalexptax = $exemptot;

if ($getsassess1['sassess'] == '1') {
	$totaltax = $totaltaxfee - $totalfee;
} else {
	$totaltax = ($totaltaxfee - ($totalfee));
}

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
					$getqtr0 = 0; $getqtr = 0; $predqtr = 1; } 
				elseif ($businessmo > 3 ) { // 1st Q waive
					$getqtr1 = 1; $getqtr = 1; $predqtr = 2; }
				elseif ($businessmo > 6 ) { // 2nd Q waive
					$getqtr2 = 2; $getqtr = 2; $predqtr = 3; }
				elseif ($businessmo > 9 ) { //3rd Q waive
					$getqtr3 = 3; $getqtr = 3; $predqtr = 4; }

				echo $newtax;

		} elseif (strtolower($pmode)=='semi-annual') {
				if ($businessmo<=6) { // no waive
					$getsem = 0; $predqtr = 1; }
				else { // 1st Q waive
					$getsem = 1;
					$predqtr = 2; }
		}
	} else {
		$getqtr=0; 
		$getsem=0;
		$predqtr = 1;
	}
		
if ($stat == "New") {
	
$gettqqtr = 4 - $getqtr;
}
$staxfee = $prefset['staxesfees']; // separate computation of taxes and fees
$swaive = $prefset['swaivetax']; // waiving of tax
	   

if (strtolower($pmode)=='quarterly') {
if ($stat == "ReNew") {
$getqtr = 0;
$gettqqtr = 4;
}
	
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
		$divfeeor = $divfee;
		while ($qtrcnt<4) {
			$divfee = $divfeeor;
			$getsumnun1 = $getsumnun2;
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
		
			$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1");
			$havnew = mysql_num_rows($checknew);
			$isnew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
															business_id='$business_id' and date_create like '$yearnow%' and
															transaction='$stat' and active=1 and recpaid=1");
			$inew = mysql_num_rows($isnew);
			
			if ($inew > 0) {
				
					if ($havnew>0 and $plspass=='' and $inew>0 and $swaive !='') { //may bago na line
						$nat=0;
						$xxo = 1;
						while ($newnew = mysql_fetch_assoc($checknew)) {
							$nature[$nat] = $newnew[bus_code];
							$nat++;
							$getmnth[$xxo] = date('m', strtotime($newnew[date_create]));
							if ($havnew>0){
								if ($getmnth[$xxo] > 0 and $getmnth[$xxo] <=3) {
									$getqqtr21 = 1; $getqqtr = 0;
								} elseif ($getmnth[$xxo] > 3 and $getmnth[$xxo] <=6) {
									$getqqtr21 = 2; $getqqtr = 1;
								} elseif ($getmnth[$xxo] > 6 and $getmnth[$xxo] <=9) {
									$getqqtr21 = 3; $getqqtr = 2;
								} elseif ($getmnth[$xxo] > 9 and $getmnth[$xxo] <=12) {
									$getqqtr21 = 4; $getqqtr = 3;
							}
							$nbsmonth = date('m');
							$getqqtr222 = $qtrcnt;
							$bis=0;
							while ($bis<$nat) {
								$biset = mysql_query("select * from tempassess
												  where owner_id='$owner_id' and business_id='$business_id' and
												  natureid='$nature[$bis]' and active=1 and date_create like '$yearnow%'");
								$newtax = 0;
								$newfee = 0;
								while ($amtbis =mysql_fetch_assoc($biset)) {
										$getfeeid = $amtbis[tfoid];
										$gettotfee = mysql_query("select * from ebpls_buss_tfo where tfoid='$getfeeid'");
										$gid = mysql_fetch_assoc($gettotfee);
										if ($gid[taxfeetype]==1) {
											$newtax = $newtax + $amtbis[compval];
										} else {
											$newfee = $newfee + $amtbis[compval];
										}
								}
								$bis++;
							}
					
							$divfee = (abs($divfee * $gettqqtr)) - $newfee;
							$newfee = $newfee /(4-$getqqtr);
						
							if ($getqqtr222 >= $getqqtr21) {
								$divfee = ($divfee / $gettqqtr) + $newfee;
							}else {
								$divfee = ($divfee / $gettqqtr);
							}
							$newtax = $newtax * ($ndsearch[0] / 100);
							$newtax = $newtax / 4;
						//echo "$divfee $newfee <br>";
						}
						$xxo++;
					}
				}
			}			
				//	echo "$newtax ===ungas== $divtax + $divfee + $getsumnun1) - $paymade; <br>";
			$isbad = ($divtax + $divfee + $getsumnun1) - $paymade;
			
			$nbong = 1;
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
			if ($amt2pay < 1) {
				$unpaid = 0;
				if ($payst == "UNPAID") {
					$payst = "PAID";
				}
			}
			
			?>
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php 
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
			$tempStartDate = date('Y-m-d', strtotime($nStartDate) + (60*60*24*30));
			$tempDateToday = date('Y-m-d');
 			//if ($qtrcnt>1 and $stat=='New') {
			if ($stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				//if ($nStartDate < $renewaldate) {
				
				if ($tempDateToday > $tempStartDate) {
					$nrmv = $qtrcnt - 1;
					if ($qtrcntp[$nrmv] != 'TAX WAIVED') {
						if ($paidtaxes <= 0) {
							require "includes/business_penalty.php";
						}
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
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
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
			</tr></table>
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
			
			$getsumnun1 = $getsumnun2;
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
			
			//siningit ko weird von 4/24/07
			
					$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1 and recpaid=0");
$havnew = mysql_num_rows($checknew);
$isnew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='$stat' and active=1 and recpaid=1");
$inew = mysql_num_rows($isnew);
if ($inew > 0) {
        if ($havnew>0 and $plspass=='' and $inew>0 and $swaive !='') { //may bago na line
                $nat=0;
		$xxo = 1;
                while ($newnew = mysql_fetch_assoc($checknew)) {
                        $nature[$nat] = $newnew[bus_code];
                        $nat++;
                        
			$getmnth[$xxo] = date('m', strtotime($newnew[date_create]));
			if ($havnew>0){
			if ($getmnth[$xxo] > 0 and $getmnth[$xxo] <=3) {
				$getqqtr21 = 1;
				$getqqtr = 0;
			} elseif ($getmnth[$xxo] > 3 and $getmnth[$xxo] <=6) {
				
				$getqqtr21 = 2;
                                $getqqtr = 1;
                        } elseif ($getmnth[$xxo] > 6 and $getmnth[$xxo] <=9) {
				$getqqtr21 = 3;
                                $getqqtr = 2;
                        } elseif ($getmnth[$xxo] > 9 and $getmnth[$xxo] <=12) {
				$getqqtr21 = 4;
                                $getqqtr = 3;
                        }
			$nbsmonth = date('m');
                        $getqqtr222 = $qtrcnt;
				$bis=0;
				while ($bis<$nat) {
                        $biset = mysql_query("select * from tempassess
                                                where owner_id='$owner_id' and business_id='$business_id' and
                                                natureid='$nature[$bis]' and active=1 and date_create like '$yearnow%'");
				$newtax = 0;
				$newfee = 0;
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
				$bis++;
				}
				//echo $newtax."VooDoo<br>";
		//echo "$getqqtr21 == $getqqtr222 == $gettqqtr <> $divfee+$newfee-$newtax VooDoo<br>";
		//echo "$divfee = (abs($divfee * $gettqqtr)) - $newfee;VooDoo<br>";
		if ($Istaxfee == '1') {
			if ($getqqtr21 > 1) {
				$divfee = $divfee + $newfee;
			} else {
				$divfee = $divfee;
			}
		} else {
		        $divfee = (abs($divfee * $gettqqtr)) - $newfee;
		}
				//$divtax = abs($divtax - $newtax);
			//echo $divfee."Brong<br>";
			//$newfee = $newfee /(4-$getqqtr);
			
			//echo "$getqqtr222 >= $getqqtr21 <BR>";
			
			if ($getqqtr222 >= $getqqtr21) {
				//echo "($getqqtr222 / $getqqtr21)<br>";
				//$divfee = $divfee - $newfee;
				//$divtax = $divtax - $newtax;
				
				if ($pass!=1) {
				$divfee = $divfee;
				$pass=1;
				} else {
				$divfee=0;
				}
				
			}
			else {
				if ($Istaxfee == '1') {
					$divfee = $divfee;
				} else {
			        $divfee = ($divfee / $gettqqtr);
				}
			        
                        }
                        $newtax = $newtax - ($newtax * ($ndsearch[0] / 100));
						
			$newtax = $newtax / 4;
			//echo $divfee."VooDoo<br>";
			}
		$xxo++;
		}
		} else {  //Akin ito -Bong
			$nat=0;
			$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1 and recpaid=1");
			while ($newnew = mysql_fetch_assoc($checknew)) {
                        $nature[$nat] = $newnew[bus_code];
                        $nat++;
                        
			$getmnth[$xxo] = date('m', strtotime($newnew[date_create]));
			$getmnth1[$xxo] = date('Y-m', strtotime($newnew[date_create]));
			if ($getmnth[$xxo] > 0 and $getmnth[$xxo] <=3) {
				$getqqtr21 = 1;
				$getqqtr = 0;
			} elseif ($getmnth[$xxo] > 3 and $getmnth[$xxo] <=6) {
				
				$getqqtr21 = 2;
                                $getqqtr = 1;
                        } elseif ($getmnth[$xxo] > 6 and $getmnth[$xxo] <=9) {
				$getqqtr21 = 3;
                                $getqqtr = 2;
                        } elseif ($getmnth[$xxo] > 9 and $getmnth[$xxo] <=12) {
				$getqqtr21 = 4;
                                $getqqtr = 3;
                        }
			$nbsmonth = date('m');
                        $getqqtr222 = $qtrcnt;
			if ($qtrcnt == 1) {
				$htnom = "03";
			} elseif ($qtrcnt == 2) {
				$htnom = "06";
			} elseif ($qtrcnt == 3) {
				$htnom = "09";
			} elseif ($qtrcnt == 4) {
				$htnom = "12";
			}
			$raey = date('Y');
			$raeyhtnom = "$raey-$htnom-30";
			$bis=0;
				while ($bis<$nat) {
                        $biset = mysql_query("select * from tempassess
                                                where owner_id='$owner_id' and business_id='$business_id' and
                                                natureid='$nature[$bis]' and active=1 and date_create < '$getmnth[$xxo]'");
				$newtax = 0;
				$newfee = 0;
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
				$bis++;
				}
			if ($getqqtr222 <= $getqqtr21) {
				//echo "$getqqtr222 <= $getqqtr21<br>";
				$divfee = $divfee - $newfee;
				$divtax = $divtax - $newtax;
			}
			}
		}
}	
		//
		//echo $divfee."VooDoo<br>";	
			
			
			if ($predcomp == '1' and $haveaddpay==1 and $stat=='New') {
				
				$isbad = (($divtax * 4) + $divfee) - $paymade;
				
			} elseif ($predcomp == '1' and $haveaddpay != 1 and $stat=='New') {
				$isbad = (($divtax * 4) + $divfee);
				
			} else {
				//echo "$isbad = ($divtax + $divfee + $getsumnun1) - $paymade; VooDoo<br>";
				$isbad = ($divtax + $divfee + $getsumnun1) - $paymade;
			}
			$NgTotalTaxFee = $divtax + $divfee;
			
		//nadale batang bata	kunyari lang
		//echo "$qtrcnt < $getqqtr21 <BR>";
		if ($qtrcnt < $getqqtr21) {
				$isbad=0;
			}
		//
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
				
			//echo "$newtax ===ungas== $divtax + $divfee + $getsumnun1) - $paymade; <br>";
			//echo "$predcomp==1 and $paystat>0 and $stat=='New' <BR>";
			if ($predcomp==1 and $paystat>=0 and $stat=='New') {
				
				//ano quarter na
				$watqtr = date('m') / 4;
				$payd = mysql_query("select * from ebpls_buss_penalty1");
				
					$paydue = mysql_fetch_assoc($payd);
		
				if ($watqtr< 1 and $q==1 || $q=='') {
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
				
				if ($isbad >= 1){// and $qtrcnt == $haveaddpay) {
					
					$amt2pay=$isbad;
					$payst = "UNPAID";
					$unpaid=0;
					
				} else {
					$amt2pay = 0;
				}
				
			}
		
			if ($isbad > 0){// and $qtrcnt == $haveaddpay) {
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
			if ($amt2pay>0 and $unpaiddisplay != 1) {
				$unpaid=1;
			}	
			if ($amt2pay < 1) {
				$unpaid = 0;
				if ($payst == "UNPAID") {
					$payst = "PAID";
				}
			}
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			$tempStartDate = date('Y-m-d', strtotime($nStartDate) + (60*60*24*30));
			$tempDateToday = date('Y-m-d');
 			//if ($qtrcnt>1 and $stat=='New') {
			if ($stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				//if ($nStartDate < $renewaldate) {
					
				if ($tempDateToday > $tempStartDate) {
					
					$nrmv = $qtrcnt - 1;
					if ($qtrcntp[$nrmv] != 'TAX WAIVED') {
						if ($paidtaxes <= 0) {
							require "includes/business_penalty.php";
						}
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
			$pilan=1;
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
	$amt2pay= $grandamt;	
			if ($q<>5 ) {
				
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
	 				$haveaddpay=1;
					$nrmv = $qtrcnt - 1;
					$ntyear = date('Y');
					$seeifpaid = mysql_query("select * from tempbusnature  where bus_code='$busid' and owner_id = '$owner_id' and business_id = '$business_id' and date_create like '$yearnow%' and recpaid='1'");
					$seeifpaid1 = mysql_num_rows($seeifpaid);
					
					if ($seeifpaid1 > 0) {
						
						if ($paidtaxes <= 0) {
							require "includes/business_penalty.php";
						}
					}
				} elseif (strtotime($yearnow."-".$paydue)<strtotime($renewaldate)) {
					$haveaddpay=1;		
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
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
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
			</tr></table>
			<?php
	
	
	
	} // end scenario quarterly
} elseif (strtolower($pmode)=='semi-annual') { //
	if ($staxfee == '' )  { //Separate computation of tax/fee = ''
	
		if ($swaive=='') { // will not waive tax	
		$divtax = $totaltax / 2;
		$divfee = $totalfee / 2;
		$gettqqtr = 2;
		} else { //will waive tax
		$waivedtax = ($totaltax / 2) * $getsem; // remaining tax to pay
		$divtax = ($totaltax-$waivedtax) / (2 - $getsem); // distribute tax
		$divfee = $totalfee / (2 - $getsem); // fees per payment
		$gettqqtr = 2 - $getsem;
	
		} // end scenario waive tax
if ($stat != "New") {
        $gettqqtr = 2;
}		
		$semcnt = 0;
		$divfeeor = $divfee;
		while ($semcnt<2) {
		$divfee = $divfeeor;
			$getsumnun1 = $getsumnun2;
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
                        $checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1 and recpaid=0");
$havnew = mysql_num_rows($checknew);
$checknew121 = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1");
$havnew121 = mysql_num_rows($checknew121);

 $isnew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='$stat' and active=1 and recpaid=1");
$inew = mysql_num_rows($isnew);
if ($inew > 0) {
        if ($havnew121>0 and $plspass=='' and $swaive !='') { //may bago na line
                $nat=0;
                $xxo = 1;
                while ($newnew = mysql_fetch_assoc($checknew121)) {
                        $nature[$nat] = $newnew[bus_code];
                        $nat++;
                        $getmnth[$xxo] = date('m', strtotime($newnew[date_create]));
						//echo $getmnth[$xxo]." $xxo $newnew[date_create]  Robert";
           
                        if ($havnew121>0){
                        if ($getmnth[$xxo] > 0 and $getmnth[$xxo] <=6) {
                                $getqqtr21 = 1;
                                $getqqtr = 0;
								$merongadd11 = 1;
								$tanggalyear = "$yearnow-06";
								$checknew12111 = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create > '$tanggalyear%' and
                                                transaction='New' and active=1");
								while ($checknew121111 = @mysql_fetch_assoc($checknew12111)) {
									$getmnth12 = date('m', strtotime($checknew121111[date_create]));
									if ($getmnth12 > 6 and $getmnth12 <=12) {
										$biset = mysql_query("select * from tempassess
                                                where owner_id='$owner_id' and business_id='$business_id' and
                                                natureid='$checknew121111[bus_code]' and active=1 
                                                and date_create like '$yearnow%'");
										$tanggaltax = 0;
										while ($amtbis = @mysql_fetch_assoc($biset)) {
											$gettotfee35 = mysql_query("select * from ebpls_buss_tfo where
                                                                                                tfoid='$amtbis[tfoid]'");
											$gid34 = mysql_fetch_assoc($gettotfee35);
											if ($gid34[taxfeetype]==1) {
													$tanggaltax = $tanggaltax + $amtbis[compval];
											}
										}
										
									}
								}
								$tanggaltax = $tanggaltax * ($ndsearch[0] / 100);
								$tanggaltax = $tanggaltax / 2;
								
                        } elseif ($getmnth[$xxo] > 6 and $getmnth[$xxo] <=12) {
                                $getqqtr21 = 2;
                                $getqqtr = 1;
								$merongadd11 = 2;
                        }
						$divtax = $divtax - $tanggaltax;
                        $nbsmonth = date('m');
                        $getqqtr222 = $semcnt;
                                //get fee
                                $bis=0;
                                while ($bis<$nat) {
				if ($stat == "New") {
                        $biset = mysql_query("select * from tempassess
                                                where owner_id='$owner_id' and business_id='$business_id' and
                                                natureid='$nature[$bis]' and active=1
                                                and date_create like '$yearnow%' and transaction = 'New'");
                        } else {
                        $biset = mysql_query("select * from tempassess
                                                where owner_id='$owner_id' and business_id='$bu
siness_id' and
                                                natureid='$nature[$bis]' and active=1
                                                and date_create like '$yearnow%'");
                        }
                                $newtax = 0;
                                $newfee = 0;
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
                                $bis++;
                                }
						
						
                        $divfee = (abs($divfee * $gettqqtr)) - $newfee;
						
                        
                        $newfee = $newfee /(2-$getqqtr);
						//echo "$getqqtr222 >= $getqqtr21 Robert<br>";
                        if ($getqqtr222 == $getqqtr21) {
                                $divfee = ($divfee / $gettqqtr) + $newfee;
                        }else {
                                $divfee = ($divfee / $gettqqtr);
                        }
                        
                        $newtax = $newtax * ($ndsearch[0] / 100);
                        $newtax = $newtax / 2;
					//	echo "($divfee * $gettqqtr) - $newfee $newtax $divtax $tanggaltax VooDoo";
                       
                    	
                        
                        }
                $xxo++;
                }
		} else {
			$newtax=0;
			$op = ($divtax-$newtax);
		}
	}
//echo "($divtax * $divfee) - $newfee $newtax VooDoo";
               
                     //    $isbad = ($divtax + $divfee  + $newtax+ $getsumnun1) - $paymade; //ok sa bayad lahat then add line second Q
                          $isbad = (($divtax-$newtax) + $divfee  + $newtax+ $getsumnun1) - $paymade;//ok sa bayad first Q then add line first Q
                         $nbong = 1;

//			echo "  $isbad =ee ($divtax + $divfee  + $newtax + $getsumnun1) - $paymade; <BR>";
			
			
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
			if (is_array($amt2pay)) {
				$amt2pay = 0;
			}
			if ($unpaiddisplay=='' and $payst == 'UNPAID') {
				$unpaid=1;
			} else {
				$unpaid=0;
			}
			
			if ($isbad > 0) {
				//echo $merongadd11."VooDoo";
				if ($semcnt == '1' and $merongadd11 == '2') {
                                                $amt2pay=0;
                                                $payst = "PAID";
                                                $unpaid=0;
                                } else {
					$payst = "UNPAID";
					
					//$isbad = ($divtax + $divfee  + $newtax+ $getsumnun1) - $paymade; //ok sa bayad lahat then add line second Q
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
			if ($predcomp == '1') {
				if ($semcnt==2 and $unpaid=0) {
					$paylist = 'Second Semi-Annual Payment';
					$amt2pay='0.00';
				}
			}
			if ($amt2pay < 1) {
				$unpaid = 0;
				if ($payst == "UNPAID") {
					$payst = "PAID";
				}
			}
			?>
		
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php echo number_format($amt2pay,2); 
			//will add penalty to divtax
			$ramt2 = $amt2pay;
 			$tempStartDate = date('Y-m-d', strtotime($nStartDate) + (60*60*24*30));
			$tempDateToday = date('Y-m-d');
 			//if ($semcnt>1 and $stat=='New') {
			if ($stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				//if ($nStartDate < $renewaldate) {
					
				if ($tempDateToday > $tempStartDate) {
					$nrmv = $semcnt - 1;
					if ($semcntp[$nrmv] != 'TAX WAIVED') {

						if ($paidtaxes <= 0) {
							require "includes/business_penalty.php";
						}
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
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
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
			</tr></table>
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
		if ($stat != 'New') {
			$gettqqtr = 2;
		}
		$semcnt = 0;
		while ($semcnt<2) {
			$getsumnun1 = $getsumnun2;
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
   $checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1 and recpaid=0 order by tempid asc");
$havnew = mysql_num_rows($checknew);
$checknew121 = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1 order by tempid asc");
$havnew121 = mysql_num_rows($checknew121);

 $isnew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
                                                business_id='$business_id' and date_create like '$yearnow%' and
                                                transaction='New' and active=1 and recpaid=1 order by tempid asc");
$inew = mysql_num_rows($isnew);

if ($inew > 0) {
	
	
        if ($havnew121>0 and $plspass=='' and $swaive !='') { //may bago na line
		
        
                $nat=0;
                $xxo = 1;
                while ($newnew = mysql_fetch_assoc($checknew121)) {
					
                        $nature[$nat] = $newnew[bus_code];
                        $nat++;
                        $getmnth[$xxo] = date('m', strtotime($newnew[date_create]));
						if ($havnew121>0){
							
                        if ($getmnth[$xxo] > 0 and $getmnth[$xxo] <=6) {
                                $getqqtr21 = 1;
                                $getqqtr = 0;
                        } elseif ($getmnth[$xxo] > 6 and $getmnth[$xxo] <=12) {
                                $getqqtr21 = 2;
                                $getqqtr = 1;
                        }
                        //Dito icheck for semi
                        
						$nbsmonth = date('m');
                        $getqqtr222 = $semcnt;
                        
                                //get fee
                                $bis=0;
                                
                                while ($bis<$nat and $getqqtr21 == $getqqtr222) {
	                                if ($stat == "New") {
                        $biset = mysql_query("select * from tempassess where owner_id='$owner_id' and business_id='$business_id' and natureid='$nature[$bis]' and active=1
                                                and date_create like '$yearnow%' and transaction = 'New'");
                        } else {
                        $biset = mysql_query("select * from tempassess where owner_id='$owner_id' and business_id='$business_id' and
                                                natureid='$nature[$bis]' and active=1
                                                and date_create like '$yearnow%'");
                        }
						/*$biset = mysql_query("select * from tempassess
                                                where owner_id='$owner_id' and business_id='$business_id' and
                                                natureid='$nature[$bis]' and active=1 
                                                and date_create like '$yearnow%' and transaction like 'New'");*/
                                $newtax = 0;
                                $newfee = 0;
                                
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
                                $bis++;
                                }
                                
            
			if ($divfee > 0) {
				
	                        $divfee = $divfee - $newfee;
			}

                        
                        //$newfee = $newfee /(2-$getqqtr);
                       
                        if ($getqqtr222 == $getqqtr21) {
							
                                $divfee = $divfee + $newfee;
                               // $divfee =  $newfee;
                        }else {
	                        //echo "Bong";
                                $divfee = ($divfee / $getqqtr21);
                        }
						 //echo "$getqqtr21 == $getqqtr222 $divfee VooDoo<br>";
                        $newtax = $newtax / 2;
                        
                        }
                $xxo++;
				
                }
				
		} else {
			$newtax=0;
		
		}
		
	}

               
				if ($predcomp == '1' and $haveaddpay>=1 and $stat=='New') {
				
				$isbad = (($divtax * 2) + $divfee + $newtax ) - $paymade;
				$nbong = 3;
				
			} elseif ($predcomp == '1' and $haveaddpay < 1 and $stat=='New') {
				$isbad = (($divtax * 2) + $divfee);
				$nbong = 2;
			} else {
				
				$isbad = ($divtax + $divfee + $getsumnun1 ) - $paymade;	
				
			} 
			//echo "$semcnt==1 and $getqqtr21==2 <br>";
			if ($semcnt==1 and $getqqtr21==2) {//2nd q apply new line
				$isbad=0;
				
			}
		
		//echo "$isbad $divtax=$divfee==$getsumnun1===$newtax====$paymade";	
		/*if ($amt2pay == 0) {
			$amt2pay = $divtax + $divfee;
			echo $amt2pay."VooDoo<br>";	
		}*/
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
			if ($amt2pay < 1) {
				$unpaid = 0;
				if ($payst == "UNPAID") {
					$payst = "PAID";
				}
			}
			
			//Semi-Dito
			if ($predcomp<>1 || $stat=='ReNew') {
			?>
			
			<tr>
			<td > <?php echo $paylist; ?></td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php
			//if ($getqqtr21 == $semcnt) {
			//	$amt2pay = $amt2pay + $newfee;
			//}
			
			echo number_format($amt2pay,2); 
			//will add penalty to divtax
			if ($amt2pay <= 0) {
				$unpaid = 0;
			}
			$ramt2 = $amt2pay;
 			if ($semcnt>1 and $stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				$watyrsd = date('Y', strtotime($nStartDate));
				if ($watyrsd<>$yearnow) { //new rec
					$nStartDate = date('Y-m-d');
				}
				$tempStartDate = date('Y-m-d', strtotime($nStartDate) + (60*60*24*30));
				$tempDateToday = date('Y-m-d');
 				//if ($nStartDate < $renewaldate) {
				if ($tempDateToday > $tempStartDate) {
	 			
					$nrmv = $semcnt - 1;
					
					if ($semcntp[$nrmv] != 'TAX WAIVED') {
						if ($paidtaxes <= 0) {
							require "includes/business_penalty.php";
						}
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
			$tempStartDate = date('Y-m-d', strtotime($nStartDate) + (60*60*24*30));
			$tempDateToday = date('Y-m-d');
 			//if ($q>1 and $stat=='New') {
			if ($stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				//if ($nStartDate < $renewaldate) {
				if ($tempDateToday > $tempStartDate) {
					$nrmv = $semcnt - 1;
					if ($semcntp[$nrmv] != 'TAX WAIVED') {
						if ($paidtaxes <= 0) {
							require "includes/business_penalty.php";
						}
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
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
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
			</tr></table>
			<?php
	}
}
elseif (strtolower($pmode)=='annual') { 
	
	$payd = mysql_query("select * from ebpls_buss_penalty");
	$paydue = mysql_fetch_assoc($payd);
	$paydue = $paydue['renewaldate'];

	if (strtolower($pmode) =="annual") { gtcount = 1; }
	if ($scounter != $gtcount) { $getsumnun1 = 0; }

	if ($businessyr==$currentyr and $stat=='New') {
		$businessmo = date('m', $startdate);
		if ($businessmo<=3) { $getqtr = 1; } // no waive
		elseif ($businessmo > 3 and $businessmo <= 6) { $getqtr = .75; }// 1st Q waive
		elseif ($businessmo > 6 and $businessmo <= 9) { $getqtr = .5; } // 2nd Q waive
		elseif ($businessmo > 9 ) { $getqtr = .25; }//3rd Q waive
	} else {
		$getqtr=1;
	}

//if ($stat=='New') {
	$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='New' and active=1 and recpaid=0");
/*$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='New' and active=1 and recpaid=0");*/
	$havnew = mysql_num_rows($checknew);

	if ($havnew>0) { //may bago na line
		$nat=0;
		while ($newnew = mysql_fetch_assoc($checknew)) {
			$nature[$nat] = $newnew[bus_code];
			$ghetdate = $newnew[date_create];
			$nat++;
		
		}
	}	
	$checknew12 = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='$stat' and active=1 and recpaid =1");
	$havnew12 = mysql_num_rows($checknew12);

	if ($havnew12>0) { $wagpenalty = 1; }
	else { $wagpenalty = 0; }

	//check kung bayad na luma
	
	$checkluma = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						recpaid='1' and active=1");
	$bayadluma = mysql_num_rows($checkluma);
	if ($bayadluma>0) { $wagpenalty = 1; } //bayad na
	else { $wagpenalty = 0;	}
	
	//pano pag may bagong line na hindi pa bayad luma na late na.. bwiset na tax payer na yan
	if ($havnew>0) {
		//total lahat ng tax nya
		$bis=0;
			//icheck annual
		while ($bis<$nat) {
			$biset = mysql_query("select * from tempassess 
						where owner_id='$owner_id' and business_id='$business_id' and
						natureid='$nature[$bis]' and active=1 and date_create like '$yearnow%'");
			while ($amtbis =mysql_fetch_assoc($biset)) {
					$getfeeid = $amtbis[tfoid];
					$gettotfee = @mysql_query("select * from ebpls_buss_tfo where
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
                if ($linemo<=3) { $getqtr1 = 1; }// no waive
                elseif ($linemo > 3 and $linemo <= 6) { $getqtr1 = .75; } // 1st Q waive
				elseif ($linemo > 6 and $linemo <= 9) { $getqtr1 = .5;  } // 2nd Q waive
				elseif ($linemo > 9 ) { $getqtr1 = .25; }//3rd Q waive
        } else {
                //$getqtr1=1;
				$gghetdate = date('m', strtotime($ghetdate));
				if ($gghetdate >0 and $gghetdate <=3) { $getqtr1 = 1; }
				elseif ($gghetdate >4 and $gghetdate <=6) { $getqtr1 = .75; }  
				elseif ($gghetdate >7 and $gghetdate <=9) { $getqtr1 = .5;	}  
				elseif ($gghetdate >10 and $gghetdate <=12) { $getqtr1 = .25; } 
				else  {	$getqtr1 = .1; }
        }
        
        if ($swaive !='') {
	        $newtax = $newtax - ($newtax * ($ndsearch[0] / 100));	
			$nbAwas = $newtax * (1 - $getqtr1);
			$newtax = $newtax * $getqtr1;
		} else {
			$newtax = $newtax;
		}
			
		$bis++;			
		}
		} else { // Gawa ko lang to sana gumana -Bong
		$checknew = mysql_query("select * from tempbusnature where owner_id='$owner_id' and
						business_id='$business_id' and date_create like '$yearnow%' and
						transaction='New' and active=1 and recpaid=1");
		$havnew = mysql_num_rows($checknew);

	if ($havnew>0) { 
		$nat=0;
		while ($newnew = mysql_fetch_assoc($checknew)) {
			$nature[$nat] = $newnew[bus_code];
			$ghetdate = $newnew[date_create];
			$nat++;
		
		}
	}
			$bis=0;
			//icheck annual
			$newtax1 = 0;
			$newfee1 = 0;
			while ($bis<$nat) {
			$biset = mysql_query("select * from tempassess 
						where owner_id='$owner_id' and business_id='$business_id' and
						natureid='$nature[$bis]' and active=1 and date_create like '$yearnow%'");
				while ($amtbis =mysql_fetch_assoc($biset)) {
					$getfeeid = $amtbis[tfoid];
					$gettotfee = @mysql_query("select * from ebpls_buss_tfo where
												tfoid='$getfeeid'");
					$gid = mysql_fetch_assoc($gettotfee);
					if ($gid[taxfeetype]==1) {
						$newtax1 = $newtax1 + $amtbis[compval];
					} else {
						$newfee1 = $newfee1 + $amtbis[compval];
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
                //$getqtr1=1;
				$gghetdate = date('m', strtotime($ghetdate));
				if ($gghetdate >0 and $gghetdate <=3) {
					$getqtr1 = 1;
				} elseif ($gghetdate >4 and $gghetdate <=6) {
					$getqtr1 = .75;
				}  elseif ($gghetdate >7 and $gghetdate <=9) {
					$getqtr1 = .5;
				}  elseif ($gghetdate >10 and $gghetdate <=12) {
					$getqtr1 = .25;
				} else  {
					$getqtr1 = .1;
					
				}
        }
        	if ($swaive !='') {
	        $newtax1 = $newtax1 - ($newtax1 * ($ndsearch[0] / 100));	
			$nbAwas1 = $newtax1 * (1 - $getqtr1);
			$nbAwas = $newtax1 * (1 - $getqtr1);
			$newtax1 = $newtax1 * $getqtr1;
			} else {
			$newtax1 = $newtax1;
			}
			
			$bis++;			
			}
		}
$otax = $totaltax;
//}

	if ($swaive=='') { // will not waive tax	
		$amt2pay = ($totaltax + $totalfee);
 	} else {
		$amt2pay = (($totaltax * $getqtr)  + ($totalfee)) - $nbAwas1;
		$stax = $otax * $getqtr;
		$otax = $otax - $stax;
		$totalremain = $totalremain - $stax;
	}

  //get payment status
      $pays = mysql_query("select sum(amount_due) from ebpls_transaction_payment_or_details 
	  						where trans_id = '$owner_id' and payment_id='$business_id' 
								and ts like '$yearnow%' and transaction != 'Retire'");
                        $paystat = mysql_fetch_row($pays);
                        $paymade = $paystat[0];
						$paidtaxes = $paymade;

                        if ($paymade >= 1) {
                                $paystat = 1;
                        } else {
                                $paystat = 0;
                        }
                        $isbad = $totaltaxfee - ($paymade + $otax ) ;
 //                  		echo "$havnew>0 and $bayadluma>0";
                        if ($havnew>0 and $bayadluma>0) {
	                     $isbad = $newtax + $newfee;
                        }
                       
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
	if ($amt2pay < 1) {
				$unpaid = 0;
				if ($payst == "UNPAID") {
					$payst = "PAID";
				}
	}
	?>
			<tr>
			<td > Annual Payment</td>
			<td  width=20%><?php echo $paydue; ?></td>
			<td align=right width=20%><?php echo number_format($amt2pay,2);
			//will add penalty to divtax
			$ramt2 = $amt2pay;
			$tempStartDate = date('Y-m-d', strtotime($nStartDate) + (60*60*24*30));
			$tempDateToday = date('Y-m-d');
 			//if ($q>1 and $stat=='New') {
			if ($stat=='New') {
				$currdate = date('m-d')."-".date('Y');
 				//if ($nStartDate < $renewaldate) {
				if ($tempDateToday > $tempStartDate) {
					if ($paidtaxes <= 0) {
						require "includes/business_penalty.php";
					}
				}
			} 
			if ($stat != "New") {
				$divtax = $totaltax;
				require "includes/business_penalty.php";
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
				
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','1','','','','<?php echo $nbAwas; ?>','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>','<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Cash </a>| 
				
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','1','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>
				Check</a>
				
				
				</td>
			<?php
			$unpaiddisplay='1';
			
			}
			
			?>
			</tr>
			<?php
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
			if ($stat<>'New') {
				require "includes/business_penalty.php";
			}
			if ($stat != "New") {
				include_once "includes/backtax_compute.php";
			}
			include "includes/busgrand.php";
			include "includes/total_compute.php";
			
			if ($itemID_==2212) {
			?>
				<td align=center width=20%>
				<a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $amt2pay; ?>','1','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>','<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>Cash </a>| 
				<a href='#' onClick="javascript:PaymentCommand('CHECK','<?php echo $amt2pay; ?>','1','','','','','<?php echo $nBusTax; ?>','<?php echo $nBusFee; ?>', '<?php echo $nSurchargeAmount; ?>','<?php echo $nInterestAmount; ?>','<?php echo $nbacktax; ?>')" class='subnavwhite'>Check</a>
				</td>
			<?php
				}
			}
			$totalamt2pay = $totalamt2pay + round($amt2pay,2);
			?>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
			<td ></td>
			<td align=right width=20%>Total (Php) &nbsp; </td>
			<td align=right width=20%>
				<?php echo number_format($totalamt2pay,2); ?></td>
			<td align=center width=20%></td>
			<?php
				if ($itemID_==2212) {
			?>
				<td align=center width=20%></td>
			<?php
				}
			?>
			</tr> </table>
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
      window.open ("ebplspay.php?&nature_id="+ natid + "&or1=" + or +"&paymde="+ paymde + "&owner_id=<?php echo $owner_id; ?>&permit_type=Business&istat=<?php echo $stat; ?>&pensked="+ pens +"&class_type=Permits&business_id=<?php echo $business_id; ?>&paypart="+ paypart + "&amtpay="+ amt + "&cmd=" + cmd + "&trans_id=" + trans_id + "&fee=" +  busfee + "&ntax=" +  bustax + "&penamt=" + buspen + "&surcharge=" + busint + "&exemption=<?php echo $totalexptax;?>&pmode=<?php echo $pmode;?>&paympart=" + paymde + "&sbacktax=" + nbacktax + "&nbbawas=" + pens, cmd, strOption);

}

</script>

<?php
}
?>

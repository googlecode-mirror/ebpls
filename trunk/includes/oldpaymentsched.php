<?php
$htag = 'Payment Schedule';
require "includes/headerassess.php";
//get deadline and penalty

if ($grdmt=='') {
	$grdmt=$grandamt;
}
$prefer = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_penalty", 
			"order by id desc limit 1");
$pref = FetchArray($dbtype,$prefer);
$pen = $pref[rateofpenalty];
$pensked =  SelectDataWhere($dbtype,$dbLink,"ebpls_buss_penalty1","");
$pens = FetchRow($dbtype,$pensked);
$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"staxesfees,sassess,swaivetax","");
$feeset = FetchRow($dbtype,$staxfee); //fee payment full
$gid = SelectDataWhere($dbtype,$dbLink,"bus_grandamt",
                "where business_id=$business_id and
		 transaction = '$istat' and  
                 owner_id=$owner_id and active=1 order by gid desc limit 1");
        $haveexist = NumRows($dbtype,$gid);
                if ($haveexist<>0) {
                        $mt = FetchRow($dbtype,$gid);
                        $btax = $mt[3];
			$plag = 1;
			$plagtax = $mt[3];
                }
// na waive

if ($totfee=='') {
	$totfee=0;
}
$twe = $grdmt;
// if ($itemID_<>4212) {
 	
 $grandamt = $twe;
 $vart=$twe;
// }

if ($stat=='New') {
	if ($btax<$twe) {
		$btax=$twe;
		$plagtax=$twe;
	}
}
$btax = $grandamt;
$rtax = $btax-$totalpaidtax;

//if ($tabs<>0) { //if payments made
$rtax = $tabs;
//}


//if ($itemID_==4212){
$rtax=$grandamt;
//} 
if ($plag==1) {
$rtax = $plagtax;
}

if ($uselp==1) {
$rtax=$grdt;
}

$totfee=$nyotfee+$regfee;
$annualfee = $totfee;
$qtrfee = $totfee;
	if ($feeset[0]==1) {
		if (strtolower($pmode)<>'annual'){
		$subtax = $vart-$totfee;
		
		} else {
		$subtax = $rtax;	
		}
	} elseif ($feeset[1]=='1') {
		$subtax=$vart;
		
	} else {
		$subtax=$vart;
	
	}

$subtax= round($subtax ,2); //total taxes and fees
$nyetotaltax= round($subtax - ($exemptot + $totfee),2); //total taxes
$totfee= round($totfee,2); // total fees

$exemptot = round($exemptot,2); // total exempted taxes


//get cash first
$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b",
                        "a.ts_create, concat(a.payment_code,'/',a.or_no),
                        a.total_amount_paid, a.payment_officer, a.or_no, a.total_amount_due,
                        a.payment_code",
                        "where a.or_no=b.or_no and b.trans_id=$owner_id and
                        b.or_entry_type='CASH' and
                        b.payment_id=$business_id and b.transaction='$istat'");
while ($getcash=FetchRow($dbtype,$getcas))
{
	if (date('Y',strtotime($getcash[0]))==date('Y')) {
		$totcash = $totcash+$getcash[2];
		$cnthave++;
	}
}

$getclear = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a,
                        ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c","a.check_amount, a.ts_clear",
                        "where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='CHECK' and a.check_status='CLEARED' and
                        c.transaction='$istat' and
                        c.trans_id=$owner_id and c.payment_id=$business_id");
while ($getcheck = FetchRow($dbtype,$getclear)) 
{
	if (date('Y',strtotime($getcheck[1]))==date('Y')) {
                $totcheck = $totcheck+$getcheck[0];
                $cnthave++;
        }
}
$totpay =$totcash+$totcheck;
$totpay = round($totpay,2);

if ($totpay>0) { //have payment
	if ($totpay>$subtax) {
		$subtax = $subtax + $totpay;
		$maybacktax =1;
		//$totpay=0; // may backtax;
	}
	
	if ($spay[spayment]==1) {
		
	$subtax = ($subtax+$totfee)  - $totpay; // work in underpayment staxesfees=1
	} else {
		
		if ($feeset[staxesfees]==1) {
 	$subtax = ($subtax+$totfee)  - $totpay; // staxesfees==1 per estab settings under/over pay
 	
			} else {
				
				if ($feeset[1]==1) {
					if ($feeset[0]==1) {
					$subtax = ($subtax+$totfee)  - $totpay; //per line over/under pay staxesfees==1
					} else {
					$subtax = $subtax - $totpay;//per line over/under pay staxesfees==0
					}
				} else {	
				
			$subtax = $subtax - $totpay; // staxesfees==0 per estab settings/per line under/over pay	
				}
			}
 	}
}

	if ($cnthave==0) {
		if (strtolower($pmode)=='quarterly') {
		$cnthave=1;
		$watever = 1;
		} 

	}
			if (strtolower($pmode)=='quarterly') {
				$ps = 4;
			
				if ($gettag[sassess]=='' and $cnthave==0) {
				$divtax=$subtax/4;
				
				} else {		

					if ($cnthave>=3) {
						
					$divtax=$subtax;
					} else {
						if ($cnthave==0) {
						$divtax=$subtax/4*$cthve;
						} else {
						
						if ($spay[spayment]=='') {
							$divtax=$subtax/4;
						} 
					
							if ($cnthave>0 and $watever<>1) {
								if ($maybacktax==1) {
									$subtax = ($subtax/4) * (4-$cnthave);
									
								}
								$divtax=$subtax/(4-$cnthave);
								
							}
							// commentko muna para ata sa per linepayment to
							
						}
	
					}
				}
				$divtax = round($divtax,2);
				$i=4;
				$paymode = array('1'=>'First','Second','Third','Fourth');	

					if ($feeset[0]==1) {
		   	                $amt2pay = array('1'=>$divtax+$totfee,'2'=>$divtax, $divtax, $divtax);
		   	                
					} else {
					$amt2pay = array('1'=>$divtax,'2'=>$divtax, $divtax, $divtax);
					}
			}elseif (strtolower($pmode)=='semi-annual'){
				if ($maybacktax==1) {
									$subtax = ($subtax/2) * (2-$cnthave);
									
				}
				
				if ($gettag[0]=='') {
					
                                $divtax=$subtax/2;
                                $ps=2;
                                  } else {

					$ps = 2;
					if ($cnthave>=1) {
					$divtax=$subtax;
					} else {
					$divtax=$subtax/2;
					}
				}
				$i=2;
				$paymode = array('1'=>'First','Second');
				
					if ($feeset[0]==1) {
	                                $amt2pay = array('1'=>$divtax+$totfee,$divtax);
					} else {
						
					$amt2pay = array('1'=>$divtax,$divtax);
					}
			}else { //ANNUAL
				 $ps = 4;
                 $i=1;
                 if ($itemID_==2212 || $itemID_==4212 || $itemID_==5212) {
	                
			if ($subtax<>0) {
	         	$totfee=0;
			}
             	}
				if ($subtax=='') {
				$subtax=$gentax;
				}
				$divtax = $subtax;
                                $paymode = array('1'=>'First');
                                $amt2pay = array('1'=>$divtax+$totfee);
			}
	

?>
<br>
<table border=1 align =center width=100% cellspacing=0 class=sub>
<tr>
<th>PAYMENT MODE: <?php echo $pmode; ?></th>
<th width=20%>PAYMENT DATE:</th>
<th width=20%>AMOUNT/PARTICULARS</th>
<th width=20%>STATUS</th>
<?php
	if ($itemID_==2212) {
?>
	<th width=20%>PAYMENT TYPE</td>
<?php
	}
?>
</tr>
<?php
$si=0;
while($si<$i)
{
?>
<tr>
<td width=20%><?php echo $paymode[$si+1]; ?>&nbsp; Payment</td>
<td align=center width=20%><?php echo date('l',strtotime($pens[$ps])).'&nbsp;';   echo $pens[$ps];?> </td>
<td align=right width=20%><?php 
if ($stat=='New') {
$staxfee1 = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
                "swaivetax","");
$feeset1 = FetchRow($dbtype,$staxfee1);

	if ($feeset1[0]==1) {

	$getstartdate = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise",
				"where owner_id = $owner_id and
				business_id=$business_id and retire=0");
	$getsd = FetchArray($dbtype,$getstartdate);
	//echo  $getsd[business_start_date];
	$getyr = date('Y',strtotime($getsd[business_start_date]));
	$getsd = date('m',strtotime($getsd[business_start_date]));
	
	//if ($getyr<=date('Y')) {
	//$tfrm=0;
	//} else {
	$tfrm = $getsd/3;	//time frame
	//}
	//echo $tfrm."sssssssss";
	$totalwaive=0;
	if ($ndsearch[0]==100 and $feeset[0]=='') {
		$addme = $amt2pay[$si+1];
	}
	
	
	   if (strtolower($pmode)=='quarterly' ) {	
		   if ($tfrm > 0 and $feeset[0]=='') {
				$addme = $totfee/4; //ok to sa new na staxfee==''
			}
		if ($tfrm<=2 and $tfrm>1) { //1st Q waive
			if ($haveal<>1) {
			$totalwaive=$totalwaive+$amt2pay[$si+1];
			$amt2pay[$si+1]=0 +$addme;
			$haveal = 1;
			$iswev = 1;
			} 

		}
		if ($tfrm<=3 and $tfrm>2) {//2nd Q waive
			if ($haveal<>2) {
			$totalwaive=$totalwaive+$amt2pay[$si+1];
                        $amt2pay[$si+1]=0 +$addme;
                        $haveal = $haveal + 1;
                        $iswev = 1;
			}
                }
		if ($tfrm<=4 and $tfrm>3) { //3nd Q waive
                        if ($haveal<>3) {
			$totalwaive=$totalwaive+$amt2pay[$si+1];
                        $amt2pay[$si+1]=0 +$addme;
                        $haveal = $haveal + 1;
                        $iswev = 1;
                        }
                }


		if ($addedfee=='' and $amt2pay[$si+1]<>0 and $haveal>0) {
			if ($feeset[0]=='') {
				$totfee=0;
			}
                                $amt2pay[$si+1]=$amt2pay[$si+1]+$totfee;
                                $addedfee=1;
                                
                        }
		
	   } elseif (strtolower($pmode)=='semi-annual' ) {
		   
		$tfrm = $getsd/2;
		
		if ($tfrm > 0 and $feeset[0]=='') {
			
				$addme = $totfee/2; //ok to sa new na staxfee==''
		} else if ($tfrm > 0 and $feeset[0]=='1') {
			
				$addme = $totfee; //ok to sa new na staxfee=='1'
		}
                if ($tfrm<=6 and $tfrm>1) { //1st Q waive
               
                        if ($haveal<>1) {
                        $totalwaive=$totalwaive+$amt2pay[$si+1];
                        $amt2pay[$si+1]=0  +$addme;
                        $haveal = 1;
                        $iswev = 1;
                        }
                                                                                                 
                }
                if ($tfrm<=12 and $tfrm>6) {//2nd Q waive
                        if ($haveal<>2) {
                        $totalwaive=$totalwaive+$amt2pay[$si+1];
                        $amt2pay[$si+1]=0 +$addme;
                        $haveal = $haveal + 1;
                        $iswev = 1;
                        }
                }
	
	   } else {
		  
		   
		  $tfrm=$getsd/4;
		 
		if ($tfrm>=1) {
			//echo $annualfee;
			$tfrm = number_format($tfrm,0);
			$amt2pay[$si+1] = (($amt2pay[$si+1] - $annualfee)/4)*(4-$tfrm);
			$amt2pay[$si+1] = $amt2pay[$si+1] + $annualfee;
			$tfrm= $tfrm-1;
		//	$wve = "<font color=red>Removed $tfrm/4 of Total</font><BR>";
			$wve='';
		}
	   }
	} else {


	}

}
$gethis = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details","ts",
		"where trans_id=$owner_id and payment_id=$business_id 
		 and transaction='$stat' and payment_part=$si+1 and
		 or_entry_type<>'CHECK'");
//$getc = NumRows($dbtype,$gethis);
$getc=0;
while ($getca = FetchRow($dbtype,$gethis))
{
        if (date('Y',strtotime($getca[0]))==date('Y')) {
                $getc = $getc+1;
        }
}


                                                                                                 
$gethis = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details a, 
			ebpls_transaction_payment_check b", "a.payment_part, a.ts",
                        "where a.trans_id=$owner_id and a.payment_id=$business_id 
			 and a.transaction='$stat' and a.payment_part=$si+1 and 
			 a.or_entry_type='CHECK' and b.check_status='CLEARED'
                         and a.or_no=b.or_no");
$getch=0;
while ($getche = FetchRow($dbtype,$gethis))
{
        if (date('Y',strtotime($getche[1]))==date('Y')) {
                $getch = $getch+1;
        }
}

//$getch = NumRows($dbtype,$gethis);

$gethis = $getc+$getch;
if ($gethis==0) {
	
	
		if ($iswev==1) {
			$afee=0;
		} else {
			//$afee=$annualfee;
			
			$annualfee=0;
			if (strtolower($pmode)=='quarterly' and $feeset[0]=='1') { //staxfee==1
// 				$minusfee = $qtrfee/4;
// 				echo $amt2pay[$si+1]." $minusfee $afee dsadsadasd";
			} else {
				
				$afee = $qtrfee/4; //staxfee==0
				
			}
		}

if ($ndsearch[0]==100 and $feeset[0]=='') { //100% exemption //fees equal divide
$onehundred=1;
$iswev=0;
					$afee=0;
} elseif ($ndsearch[0]==100 and $feeset[0]=='1') { //100% exemption //fees equal divide
$amt2pay[$si+1] = $totfee;
$onehundred=1;
$totfee=0;
$iswev=0;
} else {
	$afee=0; //ok for new app
}

echo $wve.number_format(($amt2pay[$si+1]-$minusfee) + $afee,2);

$wve='';
$paym = ($amt2pay[$si+1]-$minusfee) + $afee;
$penda = $pens[$ps];
if ($istat=='ReNew' || $istat=='Retire' ) {
	include'backtax.php';
	if ($istat=='Retire'and $notpaid > 0) {
		$gettotas = SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
					"sum(grandamt)",  
					"where owner_id=$owner_id and business_id=$business_id 
					and active=0 and transaction<>'$stat' 
					and transaction<>''");
		$top = FetchRow($dbtype,$gettotas);
		$top1 = $top[0];
 		$gettotas = SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
					"sum(totpenamt)",  
					"where owner_id=$owner_id and business_id=$business_id 
					and active=0 and transaction<>'$stat' 
					and transaction<>''");
                $top = FetchRow($dbtype,$gettotas);
                $top2 = $top[0];
		$gettotas = SelectMultiTable($dbtype,$dbLink,"bus_grandamt",
					"sum(si)",  
					"where owner_id=$owner_id and business_id=$business_id 
					and active=0 and transaction<>'$stat' 
					and transaction<>''");
                $top = FetchRow($dbtype,$gettotas);
                $top3 = $top[0];
		$topp=$top1+$top2+$top3;
		//total payments made
		$gettot = SelectMultiTable($dbtype,$dbLink,
				"ebpls_transaction_payment_or_details","sum(amount_due)",
                        	"where trans_id =$owner_id and payment_id=$business_id");
		$toa=FetchRow($dbtype,$gettot);
		$toa=$toa[0];
		$notpaid = $topp - $toa;
		$baktax = $notpaid;
//                echo "<BR>Back Tax:".number_format($notpaid,2);

require "includes/penalty.php";
if ($istat<>'Retire') {
?>
	<tr><td colspan=2>Total Tax:</td>
	<td align=right width=20%>
	<font color="lightblue">
	<?php $grandamt = $paym +  $baktax;
              echo   $tax = number_format($paym + $baktax,2);
              $amt2pay[$si+1] = $grandamt;?></td></font>

<?php
}
	
	}

}

if ($istat=='ReNew') { // || $istat=='Retire') {

require "includes/penalty.php";
}
} else {
echo "0.00";
$amt2pay[$si+1]=0;
}

?></td>


<?php

//check if no more line to pay
$hvl = SelectDataWhere($dbtype,$dbLink,"tempbusnature","where owner_id = $owner_id and
			business_id=$business_id and linepaid=0");
$hvl = NumRows($dbtype,$hvl);
//getpayhistory
if ($balance=='') {
	$balance=$tabs;
}
if ($balance>=0) {
//echo $cnthave."-".$gethis."<BR>";
	if ($iswev==1){
  		//$gethis=1;
	} 

	
	
	if ($gethis==0 and $hvl>0 and $amt2pay[$si+1]>0 ) {
		
		if ($mayunpaid==1) {
			$unpaid=2;
	 } else {
		$mayunpaid = 1;
		$unpaid=1;
	}

	?>
		<td width=20% align=center> UnPaid </td>
			<?php
			if ($itemID_<>4212) {
				if ($statpin=='' and $unpaid==1) {
			?>
			<?php if ($itemID_==2212) { ?>
		<td width=20% align=center>
			<?php 
		
			if ($itemID_==2212 and $amt2pay[$si+1]>0) { ?>
				<a href='#' onClick="javascript:PaymentCommand('CASH',<?php echo $amt2pay[$si+1]; ?>,'<?php $paympart=$paymode[$si+1]; echo $paymode[$si+1]; ?>',<?php echo $si+1; ?>,'','','<?php echo $pens[$ps];?>','','')" class='subnavwhite'>
				Cash </a>| 
				<?php 
					if ($stat<>'Retire') { ?>
				<a href='#' onClick="javascript:PaymentCommand('CHECK',<?php echo $amt2pay[$si+1]; ?>,'<?php $paympart=$paymode[$si+1]; echo $paymode[$si+1]; ?>',<?php echo $si+1; ?>,'','','<?php echo $pens[$ps];?>','','')" class='subnavwhite'>
				Check</a>
			<?php
				} 
			 } 
		 }?>
		</td>

	<?php 			}
			}
		} else {
			if ($iswev==1 ) { 
				$estatus = 'Waived';
				$iswev=0;
				$estatus='';
			} else {
				if ($onehundred<>1) {
				$estatus = 'Paid';
				} else {
					$estatus='';
				}
			}
	?>
		<td width=20% align=center><?php echo $estatus; ?></td>
	<?php } 
	
} else {
$mayunpaid=0;
?>
<td width=20% align=center>Paid</td>
<?php
 
}
?>

</tr>
<?php

$totalpaymentsked = $totalpaymentsked + round(($amt2pay[$si+1]-$minusfee) + $afee,2);
//echo $totalpaymentsked."<br>";
$bus_grandamt = $totalpaymentsked;
$si++;
$ps++;




//get cash first
$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b",
                        "a.ts_create, concat(a.payment_code,'/',a.or_no),
                        a.total_amount_paid, a.payment_officer, a.or_no, a.total_amount_due,
                        a.payment_code",
                        "where a.or_no=b.or_no and b.trans_id=$owner_id and
                        b.or_entry_type='CASH' and
                        b.payment_id=$business_id and b.transaction='$istat'");
$totcash=0;
while ($getcash=FetchRow($dbtype,$getcas))
{
$totcash = $totcash+$getcash[2];
}


$getclear = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a,
                        ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c","sum(a.check_amount)",
                        "where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='CHECK' and a.check_status='CLEARED' and
                        c.transaction='$istat' and
                        c.trans_id=$owner_id and c.payment_id=$business_id");
$totcheck = FetchRow($dbtype,$getclear);
$totpay =$totcash+$totcheck[0];
$addpayment = $gentax-$totpay;

}

?>

<?php 
	if ($addpayment>0) {
?>

<tr>
<td>&nbsp;</td><td align=center>Additional Payments:</td>
<td align=right><?php echo number_format($addpayment,2); ?></td><td align=center>&nbsp;</td>
<td align =center>
  <a href='#' onClick="javascript:PaymentCommand('CASH','<?php echo $addpayment; ?>','<?php $paympart=$paymode[$si+1]; echo $paymode[$si+1]; ?>',<?php echo $si+1; ?>,'','','<?php echo $pens[$ps];?>','','')" class='subnavwhite'>
                                Select</a>
</td>
</tr>
<?php
}

	if ($stat=='Retire' and $gentax==0 and $itemID_<>'4212') {

?>
<!--
<tr>
<td>&nbsp;</td><td align=center></td>
<td align=right></td><td align=center>&nbsp;</td>
<td align =center>
  <a href='retire_process.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>' class='subnavwhite'>
                                Retire</a>
</td>
</tr>
-->
<?php



	}
?>
<tr>
<td>&nbsp;</td><td>Total Amount Due</td><td align=right><?php echo number_format($totalpaymentsked,2); ?></td><td>&nbsp;</td></tr>

<?php
//echo $totalpaymentsked."==";
$chkinmayor = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_tfo",
		"tfodesc","where tfodesc like '%mayor%'");
$chkinmayor = FetchRow($dbtype,$chkinmayor);
?>

</table>
<script language='Javascript' src='javascripts/default.js'></script>
<script language="Javascript">
function PaymentCommand(cmd,amt,paymde,paypart,or,natid,pens,recno,feeline)
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
        window.open ("ebplspay.php?&nature_id="+ natid + "&or1=" + or +"&recno=" + recno + "&paymde="+ paymde + "&owner_id=<?php echo $owner_id; ?>&permit_type=Business&istat=<?php echo $istat; ?>&pensked="+ pens +"&class_type=Permits&business_id=<?php echo $business_id; ?>&paypart="+ paypart + "&amtpay="+ amt + "&cmd=" + cmd + "&trans_id=" + trans_id + "&feelne=" + feeline + "&fee=<?php echo $feecompute1;?>&penamt=<?php echo $penamt;?>&surcharge=<?php echo $surcharge;?>&exemption=<?php echo $Exemption=$totfeeexempt+$exemptedfee;?>&pmode=<?php echo $pmode;?>&paympart=<?php echo $paympart;?>&sbacktax=<?php echo $baktax;?>", cmd, strOption);

}

</script>










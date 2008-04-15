<?php
/*
	Purpose: To calculate Business Permit Fees, Taxes and Other Charges (TFO) for a specific business
	Inputs: Online inputted values on assessment screen, reference data from several tables
	Outputs: Generates tempassess table with results of calculation
	Prepared by:
	
Modification History:
2008.04.15: Corrected Complex Formula where NULL caused improper processing (at line 810)
	
*/
					
//Delete Per Stab default

//check if have new line 
	$tempdatenow = date('Y');

	$checkline = mysql_query("select * from tempbusnature where owner_id='$owner_id'
								and business_id='$business_id' and date_create like '$tempdatenow%' and transaction = 'New'");
	
	$checkhavepay = mysql_num_rows($checkline);
	
	$checkline = mysql_query("select * from tempbusnature where owner_id='$owner_id'
								and business_id='$business_id' and bus_code
								not in (select bus_code from tempbusnature where owner_id='$owner_id'
								and business_id='$business_id' and recpaid = '1') and date_create like '$tempdatenow%' and transaction = 'New'");
	$checkhavennew = mysql_num_rows($checkline);
	$getline = mysql_fetch_assoc($checkline);
	$getlinedate = $getline['date_create'];
	
	if ($checkhavepay>0) {
		$checkhave=$checkhavennew;
	}
// 	$getline = mysql_fetch_assoc($checkline);
// 	$getlinedate = $getline['date_create'];
// 	
	if ($checkhave>0) {
		
		
		
		
 		if (strtolower($pmode)=='quarterly') {

				$lineqtr = date('m',strtotime($getlinedate)) /4;
				$watqtr = date('m') / 4;
			//	echo "$lineqtr   $watqtr";
				if ($watqtr< 1) {
					$qtrnow = 1;
					$qtrc = $qtr1;
				
				} elseif ($watqtr>=1 and $watqtr<1.75) {
					$qtrnow=2;
					$qtrc = $qtr2;
					
				} elseif ($watqtr>=1.75 and $watqtr<2.5) {
					$qtrnow=3;
					$qtrc = $qtr3;
				} else {
					$qtrnow=4;
					$qtrc = $qtr4;
				}
		
				
				if ($lineqtr< 1) {
					$qtrline = 1;
					$haveaddpay = 1;
					
				} elseif ($lineqtr>=1 and $lineqtr<1.75) {
					$qtrline=2;
					$haveaddpay = 2;
					
				} elseif ($lineqtr>=1.75 and $lineqtr<2.5) {
					$qtrline=3;
					$haveaddpay = 3;
					
				} else {
					$qtrline=4;
					$haveaddpay = 4;
					
				}
				
			
				
				
		} elseif (strtolower($pmode)=='semi-annual') {
				$lineqtr = date('m',strtotime($getlinedate));
				$watqtr = date('m') / 2;
				if ($watqtr<= 3) {
					$qtrnow = 1;
					$qtrc = $sem1;
				} else {
					$qtrnow=2;
					$qtrc = $sem2;
				}
				
				if ($lineqtr<= 6) {
					$qtrline = 1;
					 $haveaddpay=1;
					
				} else {
					$qtrline=2;
					 $haveaddpay=2;
					
				}
				
				
				
		} elseif (strtolower($pmode)=='annual') {
                                        $qtrnow = 1;
                                        $qtrc = $sem1;
                                        $qtrline = 1;
                                         $haveaddpay=1;



                }

	

				
	}




$noregfee=$noregfee;

$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"sassess, predcomp","");
$prefset = FetchArray($dbtype,$staxfee);
$sassesscomplex = $prefset['sassess']; // per estab
$predcomp = $prefset['predcomp'];
$tempechodate = date('Y');
/*if ($sassesscomplex == 0 || $sassesscomplex == "") {
	$getdefault = @mysql_query("select * from ebpls_buss_tfo where tfoindicator = '1'");
	while ($getdefault1 = @mysql_fetch_assoc($getdefault)) {
		$deletetemp = @mysql_query("delete from tempassess where owner_id = '$owner_id' and business_id = '$business_id' and tfoid = '$getdefault1[tfoid]' and active = '1' and transaction = '$stat' and date_create like '$tempechodate%'");
	}
}*/
//Delete Per Stab default
if ($predcomp==1 and $stat=='New') {
$startd = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise",
		" business_start_date","where business_id='$business_id' and owner_id='$owner_id'");
$startdate = FetchArray($dbtype,$startd);   
$startdate =strtotime($startdate['business_start_date']); 


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
	
	
	
	if (strtolower($pmode)=='quarterly') {
		//get one nature
	
		$getone =mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id' and date_create like '$yearnow%' order by tempid ");
		$milan = mysql_num_rows($getone);
		$geto = mysql_fetch_assoc($getone);
		$busid = $geto['bus_code'];
		
		//cnt it
		$cntone = mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id' and date_create like '$yearnow%' and bus_code='$busid' and recpaid=0 order by tempid desc");
		$ilan = mysql_num_rows($cntone);
		
		$cntone = mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id' and date_create like '$yearnow%' and bus_code='$busid' and recpaid=1 order by tempid desc");
		$pilan = mysql_num_rows($cntone);
		
		$watqtr = date('m') / 4;
	
// $pilan =$pilan + $getqtr;
// $ilan = $ilan + $getqtr;

//ilan = 0 get new gross
//ilan = 1 pay wat qtr
			if ($watqtr< 1 and $ilan==0) {
				$q=1;
			} elseif ($watqtr>=1 and $watqtr<1.75 and $ilan==1) {
				$rec=1;
				$q=2;
			} elseif ($watqtr>=1.75 and $watqtr<2.5  and $ilan==1) {
				$rec=1;
				$q=3;
				
			} elseif ($watqtr>2.5  and $ilan==1) {
				$rec=1;
				$q=4;			
			} else {
				
				if ($ilan==0) {
					if ($watqtr>=1 and $watqtr<1.75 and $pilan<=1) {
					
						$q=2;
					} elseif ($watqtr>=1.75 and $watqtr<2.5 and $pilan<=2) {
						
						$q=3;
					} elseif ($watqtr>=2.5   and $pilan<=3) {
					
						$q=4;			
					} else {
						$q=5;
					}
					
					$rec=0;
				} else {
					
					if ($ilan>1) {
					if ($watqtr< 1 ) {
					$q=1;
					$rec=0;
					} elseif ($watqtr>=1 and $watqtr<1.75 and $pilan<=1) {
					
						$q=2;
						$rec=0;
					} elseif ($watqtr>=1.75 and $watqtr<2.5 and $pilan<=2) {
						
						$q=3;
						$rec=0;
					} elseif ($watqtr>2.5 and $pilan<=3) {
					
						$q=4;
						$rec=0;			
					} else {
						
					$rec=0;
					$q=5;
					}
				
					}
				}
			}

//get last payment
		$getlast = mysql_query("select * from ebpls_transaction_payment_or_details where
								trans_id='$owner_id' and payment_id='$business_id' and 
								ts like '$yearnow%' order by ts desc");
		$getla = mysql_fetch_assoc($getlast);
		$getla = $getla[ts];
		$watqtr2 = date('m', strtotime($getla)) / 4;
		
		if ($watqtr2< 1) {
			$tq = 1;
		} elseif ($watqtr2>=1 and $watqtr2<1.75 ) {
			$tq=2;
		} elseif ($watqtr2>=1.75 and $watqtr2<2.5) {
			$tq=3;
		} else {
			$tq=4;
		}
	} elseif (strtolower($pmode)=='semi-annual') {
		//get one nature
	
		$getone =mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id' and date_create like '$yearnow%' order by tempid ");
		$geto = mysql_fetch_assoc($getone);
		$busid = $geto['bus_code'];
		
		//cnt it
		$cntone = mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id' and date_create like '$yearnow%' and bus_code='$busid' and recpaid=0 order by tempid desc");
		$ilan = mysql_num_rows($cntone);
		
		$cntone = mysql_query("select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id' and date_create like '$yearnow%' and bus_code='$busid' and recpaid=1 order by tempid desc");
		$pilan = mysql_num_rows($cntone);
		
		$watqtr = date('m') / 2;

//ilan = 0 get new gross
//ilan = 1 pay wat qtr
if ($watqtr<= 3) {
		$q=1;
	} else {
	$q=2;			
	}

			if ($watqtr<= 3 and $ilan==1) {
				$q=1;
			
			} elseif ($watqtr>3  and $ilan==1) {
				$rec=1;
				$q=2;			
			} else {
				
				if ($ilan==0) {
					
					if ($watqtr<=3 and $pilan<=1) {
						
						$q=1;
					
					} elseif ($watqtr>3  and $pilan<=2) {
						
						$q=2;			
					} else {
						$q=5;
					}
					
					$rec=0;
				} else {
				$rec=0;
				$q=5;
				}
			}

//get last payment
		$getlast = mysql_query("select * from ebpls_transaction_payment_or_details where
								trans_id='$owner_id' and payment_id='$business_id' and 
								ts like '$yearnow%' order by ts desc");
		$getla = mysql_fetch_assoc($getlast);
		$getla = $getla[ts];
		$watqtr2 = date('m', strtotime($getla)) / 2;
		
		if ($watqtr2<= 6) {
			$tq = 1;
		
		} else {
			$tq=2;
		}
			
		 }
		
	
		
		 if ($q>1 and $q<>5 and $rec==0 and $itemID_=='4212' and $stat=='New' and $q<>$tq) {
	
	?>
	<script language='javascript'>
	function NeedGross()
	{
		doyou = confirm("Want to input gross sales for next payment?");
		
		if (doyou==true) {
		parent.location="index.php?part=4&class_type=Permits&itemID_=0104&owner_id=<?php echo $owner_id; ?>&stat=<?php echo $stat; ?>&permit_type=Business&business_id=<?php echo $business_id; ?>&busItem=Business&noregfee=1&orignat=<?php echo $orignat; ?>";
		} 
	}
	</script>
	
	<body onload="NeedGross();"></body>
<?php	
			} 
			
			
}

//get garbage zone

if ($invest_up==1) {
	$i=0;
	while ($i<$chcap+1 ) {
	$i++;	
	$newi = "new_cap$i";
	$newinv = $$newi;
	$gettype = mysql_query("select * from tempassess where
					owner_id=$owner_id and business_id=$business_id 
				and active=1  and
				natureid='$natcap[$i]'");
						
		while ($gett = mysql_fetch_assoc($gettype)) {
			$taxid = $gett[taxfeeid];
			$stup = mysql_query("select * from ebpls_buss_taxfeeother where
									taxfeeid = '$taxid'") or die ("s");
			$getbasis = mysql_fetch_assoc($stup);
			$getbas = $getbasis[basis];			
			if ($getbas<>3) {			
			$res=mysql_query("update tempassess set multi='$newinv' where 	owner_id=$owner_id and business_id=$business_id 
						and active=1  and taxfeeid='$taxid' and
						natureid='$natcap[$i]' and multi='$oldcap[$i]'") or die ("G");
			}	
		}
	}	
}


$getbar = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, ebpls_barangay b",
			"b.g_zone",
                        "where a.owner_id=$owner_id and a.business_id=$business_id 
			and a. business_barangay_code=b.barangay_code");
$getbara = FetchRow($dbtype,$getbar);
$g_zone=$getbara[0];


if ($stat=='New') {
	$tftype=1;
	if ($newpred==1) {
		$tftype=5;
	}
} elseif ($stat=='ReNew') {
        $tftype=2;
} else {
	$tfttype=3;
}
//update old assess

if ($com=='assess' and $itemID_==4212 and $tftnum==1) {
$yearold = $yearnow - 1;
$getgross =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =1
                                   and date_create like '$yearnow%'");
             $gross =mysql_num_rows($getgross);
           
if ($gross==0) {
	             ?>
	             <body onload="alert('Tax payer have not yet entered gross sales for this year.'); parent.location='index.php?part=4&class_type=Permits&permit_type=Business&busItem=Business&itemID_=4212&mtopsearch=SEARCH&delass=1&owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id; ?>&stat=<?php echo $stat; ?>'"></body>
	             <?php
}	

$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 0","owner_id='$owner_id' and
                 business_id='$business_id'  
		 and date_create like '$yearold%'");

$ui = DeleteQuery($dbtype,$dbLink,"tempassess",
                 "owner_id=$owner_id and
                 business_id=$business_id and active=1 and date_create like '$yearnow%' and transaction='$stat' ");

             				
} else {
	
$getgross =SelectDataWhere($dbtype,$dbLink,"tempbusnature",
                                  " where owner_id=$owner_id and business_id=$business_id and active =1
                                  and date_create like '$yearnow%'");
             $gross =mysql_num_rows($getgross);
             
             if ($gross>0) {	
	
$yearold = $yearnow - 1;
$ui = UpdateQuery($dbtype,$dbLink,"tempassess",
                "active = 0","owner_id='$owner_id' and
                 business_id='$business_id'  
		 and date_create like '$yearold%'");	
	 }

if ($com=='edit' || $itemID_<>4212) {
$PROCESS='COMPUTE';
}

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

$getnat = SelectMultiTable($dbtype,$dbLink,"tempbusnature a, ebpls_buss_nature b",
			"a.bus_code, b.naturedesc, a.cap_inv, a.last_yr, a.transaction,a.linepaid",
                        "where owner_id=$owner_id and business_id=$business_id 
			and a.bus_code=b.natureid and active = 1 $retstr order by a.tempid asc");
while ($getn = FetchRow($dbtype,$getnat)){
$capt++;

	    $checktempass = mysql_query("select * from tempassess where natureid='$getn[0]' and
	                					owner_id = '$owner_id' and business_id='$business_id' and
	                					active=1") or die(mysql_error());;
	                $tempass = mysql_num_rows($checktempass);
	                
	                if ($tempass==0 and $itemID_==4212 and $gross>0) {
		                $PROCESS='';
		             
	                } else {
		                $PROCESS='COMPUTE';
		               
	                }
	$stt=$getn[4];
	if ($stt=='New') {
        	$tftype=1;
        	if ($newpred==1) {
				$tftype=5;
			}
	} elseif ($stt=='ReNew') {
        	$tftype=2;
	} elseif ($stt=='Retire') {
        	$tftype=3;
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

?>
<table border=0 align =left width=100%><br>
<input type=hidden name=trancap[<?php echo $capt; ?>] value="<?php echo $getcap[transaction]; ?>">
<input type=hidden name=natcap[<?php echo $capt; ?>] value=<?php echo $getn[0]; ?>>
<tr><td align=left>LINE OF BUSINESS: &nbsp; <b><?php echo $getn[1]; ?> </b>
<?php if ($itemID_==4212) { ?>
<input type=hidden name=oldcap[<?php echo $capt; ?>] value="<?php echo $getin; ?>">
&nbsp;<?php echo $labelme; ?> &nbsp; <input type=text onkeyup="CheckCap(new_cap<?php echo $capt; ?>,'<?php echo $getin; ?>');" name=new_cap<?php echo $capt; ?> value="<?php echo $getin; ?>">
<?php } ?>
</td>
</tr>
</table><br>
<table border=0 align =center width=100% cellspacing=0><br>
<td align =center bgcolor=gray width=25%>TAXES/FEES</td>
<td align =center bgcolor=gray width=25%>BASE VALUE/INPUT</td>
<td align =center bgcolor=gray width=25%>AMOUNT/FORMULA</td>
<td align =center bgcolor=gray width=25%>TAX DUE</td></tr>
</table>
<table border=1 align =center width=100% cellspacing=0>
<?php
$cnt = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_taxfeeother a, ebpls_buss_tfo c",
                        "where natureid='$getn[0]' and a.taxtype='$tftype'
                         and c.tfoid=a.tfo_id $tft");
$cnt1 = NumRows($dbtype,$cnt);//get total count of tax per natureid
$lop1 =0;
                                                                                                 
$getd1 = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_taxfeeother a,
                        ebpls_buss_nature b, ebpls_buss_tfo c",
                        "c.tfodesc, a.amtformula, a.taxfeeid, b.naturedesc, a.indicator,
                        a.basis, a.mode,c.taxfeetype, c.tfoid, a.uom, a.min_amt",
                        "where a.natureid=$getn[0] and b.natureid='$getn[0]'
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
                                "where a.tfoid=c.tfoid and a.tfoid = '$getd[tfoid]' and a.active=0 
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
	            	
	                
	                $checktempass = mysql_query("select * from tempassess where natureid='$getn[0]'
	                					and owner_id='$owner_id' and business_id='$business_id' and
	                					active=1 and date_create like '$yearnow%'") or die (mysql_error());
	                $checktp = mysql_num_rows($checktempass);
	                if ($checktp==0 and $itemID_==4212 and $gross>0) {
		                $PROCESS='';
		                
	                }

			if ($PROCESS=='COMPUTE' and $stat=='Retire') {
				$temp = SelectMultiTable($dbtype,$dbLink,
                                "tempassess a, ebpls_buss_taxfeeother b, ebpls_buss_tfo c",
                                "a.natureid, a.taxfeeid, a.multi,
                                 c.tfodesc, b.amtformula,b.indicator,
                                 b.mode, b.uom, c.tfoid,a.date_create",
                                "where a.assid='$varx' and b.taxtype='$tftype'
                                 and a.owner_id=$owner_id and a.business_id=$business_id and
                                 a.active = 1 and a.transaction = '$stat' and
                                 a.natureid=b.natureid and a.taxfeeid=b.taxfeeid and a.natureid='$getn[0]'
                                 and b.tfo_id=c.tfoid $tft");
				$letme = NumRows($dbtype, $temp);
					if ($letme=='0') {
						$PROCESS='';
					}
			}

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
                                 a.natureid=b.natureid and a.taxfeeid=b.taxfeeid and a.natureid='$getn[0]'
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
                
                
                
                $complex_formula = str_replace("$get_varx[var_complex]",$replace_var[compval],$complex_formula);
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
										$nyenye = 1;
										
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
							$nyenye=1;
							
						}
						/////////////////
							$havemat=0;
						$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
							       	a.owner_id='$owner_id' and a.business_id='$business_id' and a.active=0
							       	and a.tfoid=b.tfoid and a.tfoid='$replace_var[tfoid]' order by date_create asc");
							       					
						$yearsi = mysql_query("select  a.*  from tempassess a, ebpls_buss_tfo b where
							       	a.owner_id='$owner_id' and a.business_id='$business_id' and active=0
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
				
						$to = strpos($complex_formula,"X1");
						$newvar=substr($get_varx[var_complex],1);
					
		$formula = substr_replace($complex_formula,$repval,strpos($complex_formula,$get_varx[var_complex]));
//2008.04.15 Added NULL handling because of error in Capas.
	        $complex_formula = str_replace("$get_varx[var_complex]",$repval==NULL?0:$repval,$complex_formula);
//	        $complex_formula = str_replace("$get_varx[var_complex]",$repval,$complex_formula);
	  //  echo "$formula ---$to<BR>";
	$cnto++;  
		$vari[$cnto] = $get_varx[var_complex];
		$rp[$cnto] = $repval;
                }
        } 
		 
		$yo = 0;
		if ($cnto>9) {
			$eks = strpos($complex_formula,"X10");
			$dfor =  substr($complex_formula,0,$eks-1);
			$orig = $dfor;
			while($yo<9) {
				$yo++;
			
				$dfor = str_replace($vari[$yo],$rp[$yo],$dfor);
				
			}
			$complex_formula = str_replace($orig,$dfor,$complex_formula);
			
			while($yo<$cnto) {
				$yo++;
			
				$complex_formula = str_replace($vari[$yo],$rp[$yo],$complex_formula);
				
			}
			
			
		//	} else {
		//		$complex_formula = str_replace($vari,$rp,$complex_formula);
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
		<td align=right><input type=hidden  name=x[$varx] size=5 value=$gethuh[multi] $lockit >$btc </td>";
		} else {	


		 if ($itemID_<>4212) {
		  print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                  <td align=right>$gethuh[uom] &nbsp <input type=hidden  name=x[$varx] size=5 value=$gethuh[multi] $lockit >
                  $gethuh[multi]</td>";
                 } else {
              	print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                <td align=right>$gethuh[uom] &nbsp <input type=text  name=x[$varx] size=5 value=$gethuh[multi] $lockit ></td>";
		}

		 //print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                //<td align=right>$gethuh[uom] &nbsp <input type=text  name=x[$varx] size=5 value=$gethuh[multi] $lockit ></td>";
        $tbut=1;
		}


	} elseif ($getd[basis]==3) {
		if ($itemID_<>4212) {
  print "<tr><td align=left width=25%>$gethuh[tfodesc]</td>
                <td align=right>$gethuh[uom] &nbsp <input type=hidden  name=x[$varx] size=5 value=$gethuh[multi] $lockit >
		$gethuh[multi]</td>";		
		} else {
	      print "<tr><td align=left width=25%>$gethuh[tfodesc] </td>
                <td align=right>$gethuh[uom] &nbsp <input type=text  name=x[$varx] size=5 value=$gethuh[multi] $lockit ></td>";			
		}
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


$havegar = strpos(strtolower($getd[tfodesc]),'garbage');


if ($g_zone==0) {
	if ($havegar>-1) {
		$exemptedfee = $exemptedfee + $getd[amtformula];
		$usemin = 'Not in Garbage Zone';
		$rtag='';
        $gethuh[amtformula]=0;
        $taxdue='0.00';
        $totind=0;
	}
}


//dito retire
$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, fee_exempt b,
                        ebpls_buss_tfo c","a.*",
                        "where a.business_id=$business_id and
                        a.business_category_code=b.business_category_code and
                        c.tfoid='$gethuh[tfoid]' and b.tfoid='$gethuh[tfoid]' and
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
//last ito
if ($sassesscomplex=='1') {

$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					and a.active=0 and a.tfoid=b.tfoid and a.tfoid='$getd[tfoid]' and
	       					a.natureid='$getn[0]'");
} 		       					
      					
	       					
          $getyr  = mysql_fetch_assoc($getyears);
		  $havemat = mysql_num_rows($getyears);
		$bill_date = date('Y') - date('Y',@strtotime($getyr[date_create])); //get last bill year

		if ($havemat>0) { //have prev record
			//check if will bill
			@$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
			
			
			if ($havemat % $getyr[counter]<>0) {
			
			$exemptedfee = $exemptedfee + $totind;
			$totind = 0;
			}
}
$totind = round($totind,2);
$taxdue = number_format($totind,2);
$tt = $tt + $totind;
$grandamt = $tt;
if (is_numeric($gethuh[amtformula])) {
$gethuh[amtformula]=number_format($gethuh[amtformula],2);
}

print "<td align=right  width=25%>
        <input type=hidden name=natureid[$varx] value=$gethuh[natureid]>
        <input type=hidden name=z[$varx] value=$gethuh[amtformula]>$usemin1 &nbsp; 
	$gethuh[amtformula] $show_complex 
	<input type=hidden name=taxfeeid[$varx] value=$gethuh[taxfeeid]></td>
        <td align=right width=25%>
	<input type=hidden name=y$varx value=$totind size=5 readonly>$usemin &nbsp; $taxdue</td>
        </tr>";



$usemin='';
$havemayor = strpos(strtolower($getd[tfodesc]),'mayor');
//echo $getd[taxfeetype]."<br>";
if ($getd[taxfeetype]<>1 || $havemayor>-1) {
        $feecompute1 = $feecompute1 + $totind;
	$tt = $tt - $totind;
	$grandamt = $tt;
	$nyotfee = $totind + $nyotfee;
	$nRMVFee = $nRMVFee + $totind;
  }

$result1 = UpdateQuery($dbtype,$dbLink,"tempassess", "compval='$totind'", "owner_id='$owner_id'
                        and business_id='$business_id' and natureid='$gethuh[natureid]'
                        and taxfeeid='$gethuh[taxfeeid]' and active=1");


/* ============================*/




} else { //put to tempassess;

$nat_id = $getn[0];

        if ($getd[indicator]==2 and $stt=='New') { //if formula
                $indi=1;
                $basis = $getn[2];
			if ($getd[basis]==3) {
				$basis=0;
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
	        $complex_formula = str_replace("$get_varx[var_complex]",$replace_var[compval],$complex_formula);
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
	        $complex_formula = str_replace("$get_varx[var_complex]",$repval,$complex_formula);
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
                                $basis=0;
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
//                 //voni
//                 
//                 $complex_formula = str_replace("$get_varx[var_complex]",$replace_var[compval],$complex_formula);
//                 }

       $gTFO = new TaxFee;
          if ($sassesscomplex==1) {      
                while ($loop<$how_many) {
                        $loop++;
                        $gTFO->FetchTaxFeeArray($searchme->outselect);
                        $get_varx = $gTFO->outarray;
                        $gTempAssess = new TaxFee;
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
                
                
                $complex_formula = str_replace("$get_varx[var_complex]",$replace_var[compval],$complex_formula);
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
							$exemptedfee = $exemptedfee + $replace_var[defamt];
							$repval=0;
							
						}
						/////////////////
							$havemat=0;
						$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
							       	a.owner_id='$owner_id' and a.business_id='$business_id' and a.active=0
							       	and a.tfoid=b.tfoid and a.tfoid='$replace_var[tfoid]' order by date_create asc");
							       					
						$yearsi = mysql_query("select  a.*  from tempassess a, ebpls_buss_tfo b where
							       	a.owner_id='$owner_id' and a.business_id='$business_id' and active=0
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
	        $complex_formula = str_replace("$get_varx[var_complex]",$repval,$complex_formula);
                }
        }  









               // echo $complex_formula."===";
                @eval("\$totind=$is_dec$complex_formula;");
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
                        } else {
			$getd[amtformula]=$range[0];
			$formula_rep = str_replace("X0",$basis,strtoupper($getd[amtformula]));
			
	                @eval("\$totind=$is_dec$formula_rep;");
			$compvalrange=$totind;

                        }

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
if ($getd[indicator]==2 or $getd[indicator]==3) {
	if ($getd[basis]<>3) {
	$formated_basis = number_format($basis,2);
	print "<tr><td align=left  width=25%>$getd[tfodesc] </td><td align=right>
        <input type=hidden  name=x[$varx] size=5 value=$basis $lockit>$formated_basis </td>";

	} else {

	print "<tr><td align=left  width=25%>$getd[tfodesc] </td><td align=right>$getd[uom] &nbsp
        <input type=text  name=x[$varx] size=5 value=0 $lockit></td>";
	$tbut=1;
	$basis = 0;
	$totind=0;
	}
} elseif ($getd[basis]==3) {
	print "<tr><td align=left  width=25%>$getd[tfodesc] </td><td align=right>$getd[uom] &nbsp
        <input type=text  name=x[$varx] size=5 value=$basis $lockit></td>";
	$tbut=1;
} else {
	print "<tr><td align=left  width=25%>$getd[tfodesc] </td><td align=right>
        <input type=hidden  name=x[$varx] size=5 value=$basis $lockit>$basis </td>";
}

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
	$compval=$totind;  	
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
			c.tfoid='$getd[tfoid]' and b.tfoid='$getd[tfoid]' and
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
//$totind = round($ttn,2); //dating ganito
$totind = round($totind,2);
$grandamt = $grandamt + $totind;
print "
        <td align=right  width=25%>
        <input type=hidden name=natureid[$varx] value=$getn[0]>
	<input type=hidden name=z[$varx] value=$getd[amtformula]>$usemin1 &nbsp; $nfgetd $show_complex
	&nbsp; $rtag  
        <input type=hidden name=taxfeeid[$varx] value=$getd[taxfeeid]></td>
        <td align=right width=25%>
	<input type=hidden name=y$varx value=$ttnf size =5 readonly>$usemin &nbsp;
	$ttn  </td>
        </tr>
";
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
//last ito
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
		       $getd[tfoid],1, '$stat',now()");



			} else { //will bill
			
	
			    if ($PROCESS<>'COMPUTE') {
			        
	$result = InsertQuery($dbtype,$dbLink,"tempassess",
	  	      "(assid, owner_id, business_id, natureid, taxfeeid, 
			multi, amt, formula, compval, tfoid,active, transaction,date_create)",
            	      "$varx,$owner_id, $business_id,$getn[0],
	               $getd[taxfeeid],$basis,0,'$getd[amtformula]',$compval,
		       $getd[tfoid],1, '$stat',now()");
			    
				}
			}
		} else { //new record
		
			    if ($PROCESS<>'COMPUTE') {
			         


	$result = InsertQuery($dbtype,$dbLink,"tempassess",
	  	      "(assid, owner_id, business_id, natureid, taxfeeid, 
			multi, amt, formula, compval, tfoid,active, transaction,date_create)",
            	      "$varx,$owner_id, $business_id,$getn[0],
	               $getd[taxfeeid],$basis,0,'$getd[amtformula]',$compval,
		       $getd[tfoid],1, '$stat',now()");
	 
				}
		}







// 	$result = InsertQuery($dbtype,$dbLink,"tempassess",
// 	  	      "(assid, owner_id, business_id, natureid, taxfeeid, 
// 			multi, amt, formula, compval, tfoid,active, transaction,date_create)",
//             	      "$varx,$owner_id, $business_id,$getn[0],
// 	               $getd[taxfeeid],$basis,0,'$getd[amtformula]',$compval,
// 		       $getd[tfoid],1, '$stat',now()");
// 	 

}
$gtotind=$gtotind+$totind;
}


if ($gettag[sassess]=='1') {
$feecompute=0;
}
$rt = ($rt + $totind) - $feecompute;
$df=$df+=1;
$varx++;

$add2fee=$feecompute + $add2fee;
$feecompute='';
}//if ($PROCESS=='COMPUTE') {


if ($tftnum==4) {
	$tsf=$tsf+$totind;
}
/*
if ($gettag[sassess]=='') {
	$tftnum=4;
	}
}
*/
?>
</table>
<table border=1 align=center width=100% cellpadding=1 cellspacing = 1 >
<tr>
<?php

	if ($tbut==1  and $itemID_=='4212') {
?>

<td width=25%><input type=submit name='PROCESS' value='COMPUTE'></td>
	<?php
	} else {
?>
<td width=25%>&nbsp;</td>
<?php
	}

if ($gettag[sassess]=='') {
	$ftg = 'Tax/Fee';
} else {
	$ftg = 'Assessment';
}

if ($getn[5]==1) {
		$gtotind=0;
}



if ($tftnum==1) {
?>
<td width=50%>Total <?php echo $ftg; ?> for <?php echo $getn[1]; ?>:</td>
<td width=25% align= right bgcolor=red><font color=white>
<?php $grd = $grd+ $gtotind; echo number_format($gtotind,2); ?></font>&nbsp;
</tr>

<tr>
<?php

//begin line payment

$gettag = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag = FetchArray($dbtype,$gettag);
                                                                                                               

if ($gettag[spayment]<>''){



if ($itemID_==2212) {
		$havepay = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details",
			"payment_part, linepaid",
			"where trans_id = $owner_id and payment_id=$business_id 
			and or_entry_type='CASH' and transaction='$istat' 
			order by or_detail_id");
$cntcash = NumRows($dbtype,$havepay);
$havecheck = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details a,
				ebpls_transaction_payment_check b",
				"a.payment_part, a.linepaid",
				"where a.trans_id = $owner_id and a.payment_id=$business_id
				 and a.transaction='$istat' and a.or_entry_type='CHECK'
				 and a.or_no=b.or_no and b.check_status='CLEARED'
	                         order by or_detail_id ");
$cntcheck = NumRows($dbtype,$havecheck);
$cnthave = $cntcash+$cntcheck;
$linepd = FetchRow($dbtype,$havepay);
$ppart = $cnthave+1;
$ttamt =$gtotind;
$rempay=0;
        if ($cnthave<>0 ) { //and $linepd==0) {
	        
	 $haveline =  SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or_details",
			"payment_part, linepaid",
			"where trans_id = $owner_id and payment_id=$business_id
			 and linepaid=1 and transaction = '$stat'
                         order by or_detail_id");
	 $linehave = NumRows($dbtype,$haveline);

			   	if ($pmode=='QUARTERLY') {
	                $rempay=4-$cnthave;
					$divi = 4;
					
        	    }elseif ($pmode=='SEMI-ANNUAL'){
                	        $rempay=2-$cnthave+$linehave;
				$divi = 2;
				} else {
				$divi = 1;
				}

				if ($cnthave>3) {
					
		$gid = SelectDataWhere($dbtype,$dbLink,"bus_grandamt",
                                "where business_id=$business_id and active = 0 and
                                owner_id=$owner_id order by gid desc limit 1");
	        $haveexist = NumRows($dbtype,$gid);

                	if ($haveexist<>0) {
                        $mt = FetchRow($dbtype,$gid);
                        $grdmt = $mt[3];
			$ttamt = $grdmt-$totalpaidtax;

	               }

				} elseif  ($cnthave>=1 and $linehave==0 ) {
					
		$gid = SelectDataWhere($dbtype,$dbLink,"bus_grandamt",
                                "where business_id=$business_id and active = 0 and
                                owner_id=$owner_id order by gid desc limit 1");
                $haveexist = NumRows($dbtype,$gid);
                        if ($haveexist<>0) {
                        $mt = FetchRow($dbtype,$gid);
                        $grdmt = $mt[3];
			$ttamt = $grdmt-$totalpaidtax; 
						}
						if ($pmode=='SEMI-ANNUAL') {
							$ttamt = $ttamt/2;
						} else {
							 $ttamt = $origtax/$divi*$rempay;
						}
		//echo $grandamt."--------";
				} else {

				if ($pmode=='ANNUAL') {
				$divi = 1;
				$rempay=1;
				}
		
		$ttamt = $ttamt/$divi*$rempay;
				}
			//	echo "$ttamt/$divi*$rempay";
	} else {
		
		$amt2pay[$si+1] = $gtotind;
		
			$getstartdate = SelectDataWhere($dbtype,$dbLink,"ebpls_business_enterprise",
								"where owner_id = $owner_id and
								business_id=$business_id and retire=0");
			$getsd = FetchArray($dbtype,$getstartdate);
			$getsd = date('m',strtotime($getsd[business_start_date]));
			$tfrm = $getsd/3;	//time frame
			$totalwaive=0;
	
	   if (strtolower($pmode)=='quarterly' ) {	
			if ($tfrm<=2 and $tfrm>1) { //1st Q waive
				if ($haveal<>1) {
			$totalwaive=$totalwaive+$amt2pay[$si+1];
			$amt2pay[$si+1]=$gtotind - ($amt2pay[$si+1]/4);
			//echo $gtotind." - (".$amt2pay[$si+1]."/4)";
				} 

			}
	   if ($tfrm<=3 and $tfrm>2) {//2nd Q waive
			if ($haveal<>2) {
			$totalwaive=$totalwaive+$amt2pay[$si+1];
            $amt2pay[$si+1]=$gtotind - ($amt2pay[$si+1]/2);
           
			}
        }
        
		if ($tfrm<=4 and $tfrm>3) { //3nd Q waive
                        if ($haveal<>3) {
			$totalwaive=$totalwaive+$amt2pay[$si+1];
            $amt2pay[$si+1]=$gtotind - ($amt2pay[$si+1]/3);
             
                        }
        }


       ///sakit na ulo ko 
     
        
        
       ////////////////////////// 
      

	}
	$ttamt = $amt2pay[$si+1];

	
	
	}

$gettag = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag = FetchArray($dbtype,$gettag);
                                                                                                               

if ($itemID_==2212){
	if ($gettag[sassess]<>''){
		if ($stt<>'New') {
		$uselp = 1;
		include"includes/exemption.php";
			if ($spay[spayment]==1) {
			include"includes/linepen.php";
		   	$gentax=$ttamt;	
			}
		}
	}

}


if ( $itemID_==2212  and $cnthave<3 and $statpin=='') {
	
	
	
	
?>
	<tr>
<?php
	if ($getn[5]=='0') {

		if ($feecomputeline=='') {
			$feecomputeline=0;
		}	

?>
		<td></td>
        <td align=right width=50%>Pay This Line Only By:</td>
        <td align=right width=25%>
	<a class=subnavwhite href='#' onClick="javascript:PaymentCommand('CASH',<?php echo $ttamt; ?>,'Per Line',<?php echo $ppart; ?>,<?php echo $ppart; ?>,<?php echo $getn[0]; ?>,'','',<?php echo $feecomputeline; ?>)">
	Cash</a>&nbsp;
	<a class=subnavwhite href='#' onClick="javascript:PaymentCommand('CHECK',<?php echo $ttamt; ?>,'Per Line',<?php echo $ppart; ?>,<?php echo $ppart; ?>,<?php echo $getn[0]; ?>,'','',<?php echo $feecomputeline; ?>)">
	Check</a>
</td> </tr>
<?php
		} else {


				$getorn=SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b",
			"a.or_no, b.or_entry_type", 
			"where b.trans_id=$owner_id and b.payment_id=$business_id and
			b.nat_id=$getn[0] and b.linepaid=1 and a.or_no=b.or_no 
			and a.trans_id=b.trans_id");
				$getorn=FetchRow($dbtype,$getorn);

?>
	 			<td></td><td align=right></td>
        			 <td align=right>
	<a class=subnavwhite href="ebplsreceipt.php?owner_id=<?php echo $owner_id; ?>&business_id=<?php echo $business_id;?>&or_no=<?php echo $getorn[0]; ?>&cmd=<?php echo $getorn[1]; ?>&paymde=Per Line&nature_id=<?php echo $getn[0]; ?>">Re-Print OR</a>
</td> </tr>
<?php
	}

$feecomputeline=0;

?>

</tr><td></td></tr>
</tr>
<?php
	
}


}



}
////// end line payment

?>

</table>







<?php
$gtotind=0;
} elseif ($tftnum==4) {
?>
<td width=50%>Total Special Fees:</td>
<td width=25% align= right bgcolor=red><font color=white>
<?php $grd = $tsf; echo number_format($tsf,2); ?></font>&nbsp;
</tr></table>
<?php
}
$rt = 0;
$tbut=0;
}
}
?>
<table border =0 width=100%>
<?php
$grandamt=$grd;
if ($tftnum<>4) {
	if ($reportito == '1') {
		include'../includes/exemption.php';
	} else {
include'includes/exemption.php';
	}
}
if ($tftnum<>4) {
?>
<tr><td></td><td></td><td></td><td><hr></td></tr>
<tr><td width=25%></td><td width=25%>&nbsp;</td>
<td align=right width=25%>Total :</td><td align=right width=25% bgcolor=black>
<font color=white>Php &nbsp;<?php echo $ga = number_format($grandamt,2); ?></font></td></tr>
</table>
<?php
//$grandamt=0;
} else {
?>
<tr><td></td><td></td><td></td><td><hr></td></tr>
<tr><td width=25%></td><td width=25%>&nbsp;</td>
<td align=right width=25%>Total Fee :</td><td align=right width=25% bgcolor=black>
<font color=white>Php &nbsp;<?php echo $ga = number_format($grandamt,2); ?></font></td></tr>
</table>
<?php
}
$ota1 = $grandamt;

if ($itemID_==22212) {
if ($gettag[sassess]=='') {
                                        $grdmt = $ota+$totfee;
                                        $tabs = abs($grdmt-$totalpaidtax);
                                        $grandamt = $grdmt;
                                }

?>
<table align=right border=0 width = 100%><br>
<tr>
<td width=25%>&nbsp;</td>
<td width=25%></td>
<td width=25% align=right>Total Assessed Value:</td>
<td align=right bgcolor=blue>
<font color=white>
<input type=hidden name=amtpay value=<?php echo $grandamt;?>>
<b>Php <?php $smstot = number_format($grandamt,2); echo number_format($grandamt,2);?></b></font>
</td>
</tr>
</table><br><br>
<?php
}

?>

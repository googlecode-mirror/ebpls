<?php
//require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
$permit_type='Business';
global $ThUserData;
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
require "includes/num2words.php";
//get lgu name
$getlgu = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$getlgu = FetchRow($dbtype,$getlgu);
$stax = $getlgu[16];
$iPredComp = $getlgu[29];

if ($cmd=='CASHVIEW') {
	$cmd = 'CASH';
} elseif ($cmd=='CHECKVIEW' || $cmd=='CHECKSTATUS') {
	$cmd= 'CHECK';
}
if ($cmd=='CASH') {

$getchek = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c, ebpls_owner d",
			"'','','', b.total_amount_due,
                        b.ts_create, b.payment_code,
                        concat(d.owner_last_name,', ', d.owner_first_name,
                        ' ', d.owner_middle_name), b.or_no", 
                        "where b.or_no=c.or_no and
                        c.or_entry_type='$cmd' and c.trans_id=$owner_id 
			and d.owner_id = c.trans_id and c.payment_id=$business_id 
			and b.or_no='$or_no'");

} else {
$getchek =  SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a, 
			ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c, ebpls_owner d",
			"a.check_no, a.check_issue_date, a.check_name, a.check_amount,
                        b.ts_create, b.payment_code,
			concat(d.owner_last_name,', ', d.owner_first_name,
			' ', d.owner_middle_name), b.or_no", 
			"where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='$cmd' and c.trans_id=$owner_id and 
			d.owner_id = c.trans_id 
                        and c.payment_id=$business_id and a.or_no='$or_no'");
}
//echo $or_no
$getit=FetchRow($dbtype,$getchek);
$amt2words = makewords($getit[3]);
$or_number2 = $getit[5];
if ($getit[3]==0) { 
$amt2words = makewords($amtpay);
$getit[3] = number_format($amtpay,2);
}
//$pos1 = strpos($amt2words, " And "); 
$slen = strlen($amt2words);// - $pos1;
$amt2words1 = substr($amt2words, 0,42 );
$amt2words2 = substr($amt2words, 42);
//get bus nature
$acp = mysql_query("select * from ebpls_business_enterprise_permit where
						owner_id='$owner_id' and business_id='$business_id' and
						active = 1");
$ac=mysql_fetch_assoc($acp);
//bus name
$getbn = mysql_query("select * from ebpls_business_enterprise where owner_id='$owner_id'
						and businesS_id='$business_id'");
$getbh = mysql_fetch_assoc($getbn);
$busname = $getbh[business_name];
$pmode = $getbh["business_payment_mode"];
$expcode =  $getbh["business_category_code"];

$getexemp = mysql_query("select * from ebpls_business_category where business_category_code='$expcode'");
$gt = mysql_fetch_assoc($getexemp);
$taxex = $gt["tax_exemption"];
$fname = mysql_query("select concat(d.owner_last_name,', ', d.owner_first_name,
                        ' ', d.owner_middle_name) as own from ebpls_owner d where owner_id='$owner_id'");
$fn = mysql_fetch_row($fname);
$gettfo = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo",
			"where  tfoindicator!='1'");
$gettfo1 = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo",
			"where  tfoindicator='1'");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,48,'Control #'.$getit[7],'0','2','R');
$pdf->Cell(50,7,$tdate,'0','2','R');//current date
$pdf->Cell(35,7,$getlgu[4],'0','2');//municipality
$pdf->Cell(35,7,'AP - '.$ac['pin'],'0','1');//payor
$pdf->Cell(35,7,$fn[0],'0','2');//payor
$pdf->Cell(35,7,$busname,'0','2');//payor
$pdf->Cell(50,10,'','0','2');//space
$nc=0;
//check if may pay na >1
$rv = mysql_query("select * from ebpls_transaction_payment_or_details where
		trans_id=$owner_id and payment_id=$business_id and transaction='$stat'");
$we = mysql_num_rows($rv);
//while ($nc<8) {
	while ($gettf = @mysql_fetch_assoc($gettfo)) {
	
	$xxxo = 0;
		$getbus = SelectDataWhere($dbtype,$dbLink,"tempbusnature",
			"where owner_id=$owner_id
			and business_id=$business_id and active=1 and recpaid=0");
		$mul = mysql_num_rows($getbus);
		//echo $mul;
		if ($mul==0) {
			$getbus = SelectDataWhere($dbtype,$dbLink,"tempbusnature",
			"where owner_id=$owner_id
			and business_id=$business_id and active=1 and recpaid=1");
			$mul = mysql_num_rows($getbus);
		}
		
		
		while ($getb = @mysql_fetch_assoc($getbus)) {
			$gettfoo = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_taxfeeother",
			"where natureid='$getb[bus_code]' ");
			$natid= $getb[bus_code];
			
			while ($gettt = @mysql_fetch_assoc($gettfoo)) {
				
				if ($gettt[tfo_id] == $gettf[tfoid]) {
					$xxxo = 1;
					$tgoid = $gettf[tfoid];
					
					
					$datei =date('m', strtotime($getb["date_create"]));
						if (strtolower($pmode)=='quarterly') {
							if ($datei >= 1 and $datei <= 3) {
								$dvi = 4;
							} elseif ($datei >= 4 and $datei <= 6) {
								$dvi = 4;
								if ($iPredComp == '1') {
									$dvi = 1;
								}
							} elseif ($datei >= 7 and $datei <= 9) {
								$dvi = 4;
								if ($iPredComp == '1') {
									$dvi = 1;
								}
							} elseif ($datei >= 10 and $datei <= 12) {
								$dvi = 4;
								if ($iPredComp == '1') {
									$dvi = 1;
								}
							}
							
						}
						if (strtolower($pmode)=='semi-annual') {
							if ($datei >= 1 and $datei <= 6) {
								$dvi = 2;
							} elseif ($datei >= 7 and $datei <= 12) {
								$dvi = 2;
								if ($iPredComp == '1') {
									$dvi = 1;
								}
							}
							
						}
						if (strtolower($pmode)=='annual') {
							$dvi=1;
						}
					//echo "$stax==1 and $we==1 <br>";
						if ($stax==1 and $we==1 ) {
							if ($gettf["taxfeetype"]!=1) {
								$dvi=1;
								$te = 1;
														
							}
							
						} elseif ($stax==1 and $we>1) { 
							if ($gettf["taxfeetype"]!=1) {
								$dvi=1;
								if ($getb["linepaid"]!=0) {
								$xxxo=0;
								}
								
							}	
						
						}
				}
			}
		}
		
	//working in multi line	sabay nag apply
	
		if ($xxxo == '1') {
			
			$getamt = mysql_query("select * from tempassess where owner_id='$owner_id'
			and business_id='$business_id' and tfoid='$tgoid' and
			natureid = '$natid' and transaction='$stat'");
			$gety = mysql_fetch_assoc($getamt);
				$wewet = mysql_query("select * from ebpls_buss_tfo where tfoid='$tgoid'");
				$wewe = mysql_fetch_assoc($wewet);
				$wattype = $wewe["taxfeetype"];
				if ($wattype==1) {
				 $dtax = $gety["compval"] - (($gety["compval"] * ($taxex/100)));
				//$dtax = $gety["compval"];
				$getant = mysql_query("select sum(compval) from tempassess where owner_id='$owner_id' and business_id='$business_id' and tfoid = '$tgoid' and transaction='$stat' and active = '1'");
				$gettaax = @mysql_fetch_row($getant);
				$dtax = $gettaax[0];
				} else {
					$getfeex = mysql_query("select * from fee_exempt where business_category_code = '$expcode' and
														tfoid = '$tgoid' and active='1'");
					$getfe = mysql_num_rows($getfeex);
				
					if ($getfe > 0 ) {
						$dtax=0;
					} else {
						$dtax = $gety["compval"];
					}
				}
				//echo "$diptax = ($dtax*$mul)/$dvi";
				if ($gettf['taxfeetype'] == '1') {
					$diptax = ($dtax)/$dvi;
				} else {
					$diptax = ($dtax*$mul)/$dvi;
				}
					
				if ($diptax < 0) {
					$diptax = 0;
				}
			$dt = $dt + $diptax;	
			$pdf->Cell(75,5,$gettf['tfodesc'],'0','0');//desc
			$pdf->SetX(10);
			$pdf->Cell(75,5,number_format($diptax,2),'0','1','R');//totalamount
			
		}
		
	}

	if ($paymde!='Per Line' and $we==1 ) {
		while ($gettfo11 = @mysql_fetch_assoc($gettfo1)) {
		//add by von , fee exempt resibo	4/24/07
			
			
		$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, 
			fee_exempt b, ebpls_buss_tfo c","a.*",
                        "where a.business_id=$business_id and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$gettfo11[tfoid] and b.tfoid=$gettfo11[tfoid] and
                        b.active=1");
		$getfeex = NumRows($dbtype,$getex);
				if ($getfeex>0) {
					$feeexemptme = 1;
				}
			$havegar = strpos(strtolower($gettfo11[tfodesc]),'garbage');

			$gzone = mysql_query("select * from ebpls_business_enterprise a, ebpls_barangay b where
								a.owner_id='$owner_id' and a.business_id='$business_id' and
								a.business_barangay_code=b.barangay_code");
			$gz = mysql_fetch_assoc($gzone);
				if ($gz["g_zone"]==0) {
					if ($havegar>-1) {
						$feeexemptme = 1;
					}
				}	
		
		$havemat=0;
		$getyears = mysql_query("select  a.*, b.* from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id' and a.active=0
	       					 and a.tfoid=b.tfoid and a.tfoid='$gettfo11[tfoid]' order by date_create asc");
	       					
		$yearsi = mysql_query("select  a.*  from tempassess a, ebpls_buss_tfo b where
	       					a.owner_id='$owner_id' and a.business_id='$business_id'
	       					 and a.tfoid=b.tfoid and a.tfoid='$gettfo11[tfoid]'");							
		$havemat = mysql_num_rows($yearsi);
				
          $getyr  = mysql_fetch_assoc($getyears);
		$bill_date = date('Y') - date('Y',@strtotime($getyr[date_create])); //get last bill year

		if ($havemat>0 and $PROCESS<>"COMPUTE" and $usemin=='') { //have prev record
			//check if will bill
		@$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
		
			if ($isdeci>0 and $haveaddpay==$watqtr) { // will not bill
			$feeexemptme=1;
			
			}
		} else { //new record

			if ($haveaddpay==$watqtr) {
				$haveaddpay='';
			}
				//check if will bill
		@$howmanydec = $havemat/$getyr[counter];
			$isdeci = strpos($howmanydec,".");
			if ($isdeci>0 and $haveaddpay==$watqtr) { // will not bill
			$feeexemptme=1;
			}
			   
		}

		// hangang dito
		
				if ($feeexemptme != 1) {
					$pdf->Cell(75,5,$gettfo11['tfodesc'],'0','0');//desc
					$pdf->SetX(10);
					
						@$diptax = $gettfo11['defamt']/$dvi;
							
						if ($diptax < 0) {
							$diptax = 0;
						}
					$dt = $dt + $diptax;	
					
					$pdf->Cell(75,5,number_format($diptax,2),'0','1','R');//totalamount
					
				}
		}
	}
	
	if ($stax=='') {
		while ($gettfo11 = @mysql_fetch_assoc($gettfo1)) {
			
				$getfeex = mysql_query("select * from fee_exempt where business_category_code = '$expcode' and
														tfoid = '$gettfo11[tfoid]' and active='1'");
					$getfe = mysql_num_rows($getfeex);
				
					if ($getfe > 0 ) {
						$dtax=0;
					} else {
						$dtax = $getfo11["defamt"];
					}
				}
				
				$diptax = $dtax/$dvi;
				$dt = $dt + $diptax;
			
				if ($diptax < 0) {
					$diptax = 0;
				}
			
			$pdf->Cell(75,5,$getfo11['tfodesc'],'0','0');//desc
			$pdf->SetX(10);
			$pdf->Cell(75,5,number_format($diptax,2),'0','1','R');//totalamount
			
			
		
	}

//$nc++;
//}


$updatebusnature=UpdateQuery($dbtype,$dbLink,"tempbusnature",
                 "linepaid=5,recpaid=1","owner_id=$owner_id and business_id=$business_id and active='1'");
$getsur = @mysql_query("select * from comparative_statement where or_no = '$or_number2'");
//echo "select * from comparative_statement where or_no = '$or_number2'";
$getSur = @mysql_fetch_assoc($getsur);
if ($getSur['penalty'] > 0) {
	$pdf->Cell(40,5,'Surcharge',0,0,'L');
	$pdf->Cell(35,5,$getSur['penalty'],0,1,'R');
	$dt = $dt + $getSur['penalty'];
}
if ($getSur['surcharge'] > 0) {
	$pdf->Cell(40,5,'Interest',0,0,'L');
	$pdf->Cell(35,5,$getSur['surcharge'],0,1,'R');
	$dt = $dt + $getSur['surcharge'];
}
if ($getSur['backtax'] > 0) {
	$pdf->Cell(40,5,'BackTax',0,0,'L');
	$pdf->Cell(35,5,$getSur['backtax'],0,1,'R');
	$dt = $dt + $getSur['backtax'];
}
$pdf->Cell(75,5,number_format($dt,2),'0','2','R');//totalamount
$amt2words = makewords($dt);
$pdf->MultiCell(75,5,$amt2words,'0');//amt in words
//$pdf->Cell(75,5,$amt2words2,'0','2');//amt in words
$pdf->Output();
?>

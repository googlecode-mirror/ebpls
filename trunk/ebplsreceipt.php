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

$getit=FetchRow($dbtype,$getchek);
$amt2words = makewords($getit[3]);
if ($getit[3]==0) { 
$amt2words = makewords($amtpay);
$getit[3] = number_format($amtpay,2);
}
//$pos1 = strpos($amt2words, " And "); 
$slen = strlen($amt2words);// - $pos1;
$amt2words1 = substr($amt2words, 0,42 );
$amt2words2 = substr($amt2words, 42);
//get bus nature

//bus name
$getbn = mysql_query("select * from ebpls_business_enterprise where owner_id='$owner_id'
						and businesS_id='$business_id'");
$getbh = mysql_fetch_assoc($getbn);
$busname = $getbh[business_name];

/*if ($paymde=='Per Line') {

$getbus = SelectMultiTable($dbtype,$dbLink,"tempbusnature a ,ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c","a.*",
 			"where a.owner_id=$owner_id
                        and a.business_id=$business_id and a.active=1 and
                        b.or_no=c.or_no and c.or_entry_type='$cmd' and
                        c.trans_id=$owner_id and a.bus_code = c.nat_id and
                        a.bus_code='$nature_id' and
                        c.payment_id=$business_id and b.or_no='$or_no'");
} else {
$getbus = SelectDataWhere($dbtype,$dbLink,"tempbusnature",
			"where owner_id=$owner_id
			and business_id=$business_id and active=1");
}*/
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
$pdf->Cell(35,7,$getit[6],'0','2');//payor
$pdf->Cell(35,7,$busname,'0','2');//payor
$pdf->Cell(50,10,'','0','2');//space
$nc=0;
while ($nc<8) {
	while ($gettf = @mysql_fetch_assoc($gettfo)) {
	
	$xxxo = 0;
		$getbus = SelectDataWhere($dbtype,$dbLink,"tempbusnature",
			"where owner_id=$owner_id
			and business_id=$business_id and active=1");
		while ($getb = @mysql_fetch_assoc($getbus)) {
			$gettfoo = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_taxfeeother",
			"where natureid='$getb[bus_code]'");
			while ($gettt = @mysql_fetch_assoc($gettfoo)) {
				
				if ($gettt[tfo_id] == $gettf[tfoid]) {
					$xxxo = 1;
				}
			}
		}
		if ($xxxo == '1') {
			$pdf->Cell(75,5,$gettf['tfodesc'],'0','2');//amount
		}
	}
	if ($paymde!='Per Line') {
		while ($gettfo11 = @mysql_fetch_assoc($gettfo1)) {
			$pdf->Cell(75,5,$gettfo11['tfodesc'],'0','2');//amount
		}
	}
$nc++;
}
$pdf->Cell(75,5,number_format($getit[3],2),'0','2','R');//totalamount
$pdf->Cell(75,5,$amt2words1,'0','2');//amt in words
$pdf->Cell(75,5,$amt2words2,'0','2');//amt in words
$pdf->Output();
?>

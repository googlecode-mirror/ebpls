<?php
//require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
$permit_type='Business';
global $ThUserData;
require_once "includes/variables.php";
include("lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');
require "includes/num2words.php";
//get lgu name

$getowner = @mysql_query("select * from ebpls_owner where owner_id = '$owner_id'");
$getOwner = @mysql_fetch_assoc($getowner);
$getbusiness = @mysql_query("select * from ebpls_business_enterprise where business_id = '$business_id'");
$getBusiness = @mysql_fetch_assoc($getbusiness);
$getbusinesspermit = @mysql_query("select * from ebpls_business_enterprise_permit where business_id = '$business_id' and owner_id = '$owner_id'");
$getBusinesspermit = @mysql_fetch_assoc($getbusinesspermit);
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,30,'','0','2','R');
//$pdf->Cell(50,30,'Municipality of ','0','2','R');
$pdf->Cell(30,7,$tdate,'0','2','R');//current date
$pdf->Cell(35,10,'','0','1');//municipality
$pdf->Cell(35,5,'AP - '.$getBusinesspermit['pin'],'0','1');
if ($getOwner['owner_last_name'] != "") {
	$pdf->Cell(35,7,$getOwner['owner_first_name'].' '.substr($getOwner['owner_middle_name'],0,1).' '.$getOwner['owner_last_name'],'0','2');//payor
} else {
	$pdf->Cell(35,7,$getOwner['owner_legal_entity'],'0','2');//payor
}
$pdf->Cell(35,7,$getBusiness['business_name'],'0','2');//payor
$pdf->Cell(50,10,'','0','2');//space

$gettfo = @mysql_query("select * from ebpls_buss_tfo");
$nc = 0;
$iTotAmount = 0;
while ($GetTFO = @mysql_fetch_assoc($gettfo)) {
	$getdet = @mysql_query("select sum(amount) from ebpls_payment_details where or_no = '$cn' and tfoid = '$GetTFO[tfoid]' order by payment_details_id ASC");
	$getDet = @mysql_fetch_row($getdet);
	if ($getDet[0] > 0) {
		$pdf->Cell(70,5,$GetTFO['tfodesc'],'0','0');//desc
		$pdf->SetX(10);
		$pdf->Cell(75,5,number_format($getDet[0],2),'0','1','R');
		$iTotAmount = $iTotAmount + $getDet[0];//totalamount
		$nc++;
	}
}
$getsib = @mysql_query("select * from ebpls_payment_details where or_no = '$cn' and payment_details != ''");
while ($GetSIB = @mysql_fetch_assoc($getsib)) {
	$pdf->Cell(70,5,$GetSIB['payment_details'],'0','0');//desc
	$pdf->SetX(10);
	$pdf->Cell(75,5,number_format($GetSIB['amount'],2),'0','1','R');
	$iTotAmount = $iTotAmount + $GetSIB['amount'];//totalamount
	$nc++;
}
while ($nc < 11) { //spaces
	$pdf->Cell(75,5,'','0','0');
	$pdf->SetX(10);
	$pdf->Cell(75,5,'','0','1','R');
	$nc++;
}

$pdf->SetFont('Arial','B',10);
$pdf->Cell(75,5,number_format($iTotAmount,2),'0','2','R');// grand amount
$pdf->SetFont('Arial','',10);
$amt2words = makewords($iTotAmount);
$pdf->MultiCell(75,5,$amt2words,'0');//amt in words
$pdf->Output();
?>

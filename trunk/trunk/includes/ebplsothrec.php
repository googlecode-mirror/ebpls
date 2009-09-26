<?php
//require_once("lib/ebpls.lib.php");
//require_once("lib/ebpls.utils.php");
//$dbLink = get_db_connection();
global $ThUserData;
require "includes/variables.php";
require "includes/num2words.php";
include("lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
//get lgu name
$getlgu = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$getlgu = FetchRow($dbtype,$getlgu);

$getchek = SelectMultiTable($dbtype,$dbLink,"temppayment a, $owner d",
			"a.*, concat(d.owner_last_name,', ', d.owner_first_name,
                        ' ', d.owner_middle_name)","where a.owner_id=$owner_id and
			a.permit_type='$permit_type' and permit_status='$stat' 
			and a.owner_id=d.owner_id and a.or_no='$or_no'");
$getit=FetchRow($dbtype,$getchek);
$amt2words = makewords($getit[1]);

$pos1 = strpos($amt2words, " And "); 
$slen = strlen($amt2words);// - $pos1;
$amt2words1 = substr($amt2words, 0,$pos1 );
$amt2words2 = substr($amt2words, $pos1);
//get bus nature
$datengay = date('Y');
                                                                                                               
$getbus = SelectMultiTable($dbtype,$dbLink,"ebpls_fees_paid",
			"fee_desc, fee_amount, multi_by",
			"where owner_id=$owner_id and
                        permit_type='$permit_type' and permit_status='$stat' and input_date like '$datengay%' and active='1'");

define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,48,'Control #'.$getit[8],'0','2','R');
$pdf->Cell(50,7,$tdate,'0','2','R');//current date
$pdf->Cell(35,7,$getlgu[4],'0','2');//municipality
$pdf->Cell(35,7,$getit[9],'0','2');//payor
$pdf->Cell(50,10,'Payment For '.$permit_type.' Permit','0','2');//space
$nc=0;
while ($nc<8) {
	while ($getb = FetchRow($dbtype,$getbus))
		{
		$pdf->Cell(75,5,$getb[0],'0','0');//desc
		$Tot = $getb[1] * $getb[2];
		$pdf->Cell(75,5,number_format($Tot,2),'0','1');//amount
		}
$nc++;
}
$getotheram = mysql_query("select * from ebpls_other_penalty_amount  where permit_id = '$getit[0]'");
$getotham = mysql_fetch_assoc($getotheram);
if ($getotham[amount] != "" or $getotham[amount] != 0) {
	$pdf->Cell(75,5,'Surcharge/Interest','0','0');//desc
	$pdf->Cell(75,5,number_format($getotham[amount],2),'0','1');//amouna
}
if ($getotham[bt] != "" or $getotham[bt] != 0) {
        $pdf->Cell(75,5,'Backtax','0','0');//desc
        $pdf->Cell(75,5,number_format($getotham[bt],2),'0','1');//amount
}
$pdf->Cell(75,5,number_format($getit[1],2),'0','2','R');//totalamount
$pdf->Cell(75,5,$amt2words1,'0','2');//amt in words
$pdf->Cell(75,5,$amt2words2,'0','2');//amt in words
$pdf->Output();
?>

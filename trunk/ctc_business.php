<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include'includes/variables.php';
include'lib/multidbconnection.php';
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
//$dbLink = get_db_connection();

$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

	$result=mysql_query("select ctc_code, ctc_place_issued, ctc_date_issued, ctc_for_year,
	ctc_company, ctc_business_id, ctc_tin_no, ctc_organization_type, ctc_place_of_incorporation,
	ctc_business_nature, ctc_additional_tax1, ctc_additional_tax2, ctc_tax_interest, ctc_company_address, 
	ctc_incorporation_date, ctc_tax_due, ctc_acct_code, ctc_basic_tax 
	from ebpls_ctc_business where ctc_code = '$ctc_code'") or die(mysql_error());
    $resulta=mysql_fetch_row($result); 

$pdf->SetX(0);
$pdf->Cell(70,10,'',0,0,'C');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(40,10,'',0,0,'C');
$pdf->Cell(50,10,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(0);
$pdf->Cell(15,3,'',0,0,'C');
$pdf->Cell(80,3,'',0,0,'L');
$pdf->Cell(20,3,'',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(45,3,'',0,1,'C');

$pdf->SetX(0);
$pdf->Cell(15,3,$resulta[3],0,0,'C');
$pdf->Cell(50,3,$resulta[1],0,0,'L');
$pdf->Cell(65,3,$resulta[2],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(0);
$pdf->Cell(95,3,'',0,0,'L');
$pdf->Cell(65,3,'',0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(0);
$pdf->Cell(95,3,$resulta[4],0,0,'L');
$pdf->Cell(65,3,$resulta[6],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(0);
$pdf->Cell(95,3,'',0,0,'L');
$pdf->Cell(65,3,'',0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(0);
$pdf->Cell(95,3,$resulta[13],0,0,'L');
$pdf->Cell(65,3,$resulta[14],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->Cell(80,3,'',0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(0);
$pdf->Cell(80,3,$resulta[7],0,0,'L');
$pdf->Cell(80,3,$resulta[8],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(0);
$pdf->Cell(80,3,$resulta[9],0,0,'L');
$pdf->Cell(40,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,1,'L');

$pdf->SetFont('Arial','B',6);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,1,'R');


$bs=number_format($resulta[17],2);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,3,'',0,0,'R');
$pdf->Cell(20,3,$bs,0,1,'R');

$rp=((int)($resulta[10]/5000)*2);
$rp=number_format($rp,2);
$resulta[10]=number_format($resulta[10],2);

$pdf->Cell(40,3,'',0,1,'R');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,3,$resulta[10],0,0,'R');
$pdf->Cell(20,3,$rp,0,1,'R');

$gr=((int)($resulta[11]/5000)*2);
$gr=number_format($gr,2);
$resulta[11]=number_format($resulta[11],2);

$pdf->Cell(40,3,'',0,1,'R');
$pdf->SetFont('Arial','B',4);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,3,$resulta[11],0,0,'R');
$pdf->Cell(20,3,$gr,0,1,'R');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(20,3,'',0,1,'R');

$total=number_format($resulta[15],2);

$pdf->SetFont('Arial','B',4);
$pdf->SetX(0);
$pdf->Cell(80,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,3,$total,0,1,'R');

$interest=number_format($resulta[12],2);

$pdf->Cell(40,3,'',0,1,'R');
$pdf->SetX(0);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(80,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,3,$interest,0,1,'R');

$grandtotal=number_format($resulta[15]+$resulta[12],2);

$pdf->Cell(40,3,'',0,1,'R');
$pdf->SetX(0);
$pdf->SetFont('Arial','B',4);
$pdf->Cell(80,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(20,3,$grandtotal,0,1,'R');

$pdf->Output();


?>

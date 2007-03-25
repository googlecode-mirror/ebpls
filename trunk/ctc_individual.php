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

	$result=mysql_query("select ctc_code, concat(ctc_last_name, ', ', ctc_first_name, ' ',ctc_middle_name),
	ctc_birth_date, ctc_address, ctc_gender, ctc_civil_status, ctc_for_year,
	ctc_citizenship, ctc_icr_no, ctc_height, ctc_weight, ctc_tin_no, ctc_place_issued,
	ctc_date_issued, ctc_place_of_birth, ctc_basic_tax, ctc_additional_tax1, ctc_additional_tax2,
	ctc_additional_tax3, ctc_tax_interest, ctc_tax_exempted, ctc_tax_due, ctc_occupation
	from ebpls_ctc_individual where ctc_code='$ctc_code'") or die(mysql_error());
    $resulta=mysql_fetch_row($result); 

$pdf->SetX(5);
$pdf->Cell(70,10,'',0,0,'C');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(40,10,'',0,0,'C');
$pdf->Cell(50,10,'',0,1,'C');

$pdf->SetFont('Arial','',4);
$pdf->SetX(5);
$pdf->Cell(15,3,'',0,0,'C');
$pdf->Cell(80,3,'',0,0,'L');
$pdf->Cell(20,3,'',0,0,'L');
$pdf->SetFont('Arial','B',6);
$pdf->Cell(45,3,'',0,1,'C');

$pdf->SetX(5);
$pdf->Cell(15,3,$resulta[6],0,0,'C');
$pdf->Cell(80,3,$resulta[12],0,0,'L');
$pdf->Cell(65,3,$resulta[13],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(5);
$pdf->Cell(95,3,'',0,0,'L');
$pdf->Cell(65,3,'',0,1,'L');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(95,3,$resulta[1],0,0,'L');
$pdf->Cell(65,3,$resulta[11],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(5);
$pdf->Cell(95,3,'',0,0,'L');
$pdf->Cell(65,3,'',0,1,'L');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(95,3,$resulta[3],0,0,'L');
$pdf->Cell(65,3,$resulta[4],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(5);
$pdf->Cell(40,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,1,'L');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(40,3,$resulta[7],0,0,'L');
$pdf->Cell(40,3,$resulta[8],0,0,'L');
$pdf->Cell(40,3,$resulta[14],0,0,'L');
$pdf->Cell(40,3,$resulta[9],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,0,'L');
$pdf->Cell(40,3,'',0,1,'L');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(80,3,$resulta[5],0,0,'L');
$pdf->Cell(40,3,$resulta[2],0,0,'L');
$pdf->Cell(40,3,$resulta[10],0,1,'L');

$pdf->SetFont('Arial','',4);
$pdf->SetX(5);
$pdf->Cell(40,3,'',0,0,'L');
$pdf->Cell(40,3,$resulta[22],0,0,'L');
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,1,'C');

$pdf->SetFont('Arial','B',6);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,$resulta[15],0,1,'R');

$pdf->SetFont('Arial','B',6);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,1,'R');

$gr=(int)($resulta[16]/1000);
$resulta[16]=number_format($resulta[16],2);
$gr=number_format($gr,2);

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,$resulta[16],0,0,'R');
$pdf->Cell(40,3,$gr,0,1,'R');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,1,'R');

$sgr=(int)($resulta[17]/1000);
$resulta[17]=number_format($resulta[17],2);
$sgr=number_format($sgr,2);

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,$resulta[17],0,0,'R');
$pdf->Cell(40,3,$sgr,0,1,'R');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,'',0,1,'R');

$rp = (int)($resulta[18]/1000);
$resulta[18]=number_format($resulta[18],2);
$rp=number_format($rp,2);

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(80,3,'',0,0,'L');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,$resulta[18],0,0,'R');
$pdf->Cell(40,3,$rp,0,1,'R');

$total=number_format($resulta[21],2);

$pdf->SetFont('Arial','B',4);
$pdf->SetX(5);
$pdf->Cell(40,29,'',0,0,'C');
$pdf->SetFont('Arial','',4);
$pdf->Cell(40,3,'',0,0,'L');
$pdf->SetFont('Arial','B',4);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,$total,0,1,'R');

$interest=number_format($resulta[19],2);

$pdf->SetX(45);
$pdf->Cell(40,11,'',0,0,'C');
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,$interest,0,1,'R');

$grand_total=number_format($resulta[21]+$resulta[19],2);

$pdf->SetFont('Arial','B',4);
$pdf->SetX(85);
$pdf->Cell(40,3,'',0,0,'C');
$pdf->Cell(40,3,$grand_total,0,1,'R');

$pdf->SetFont('Arial','B',4);
$pdf->SetX(85);
$pdf->Cell(80,5,'',0,1,'L');

$pdf->SetX(45);
$pdf->Cell(40,10,'',0,0,'C');
$pdf->Cell(80,10,'',0,1,'C');

$pdf->SetX(45);
$pdf->Cell(40,5,'',0,0,'C');
$pdf->Cell(80,5,'',0,1,'C');

$pdf->Output();


?>

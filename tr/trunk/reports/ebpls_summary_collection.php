<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");

$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta1=mysql_fetch_row($result);
   
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(200,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(200,5,$resulta1[0],0,1,'C');
$pdf->Cell(200,5,$resulta1[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(200,5,$resulta1[2],0,2,'C');
$pdf->Cell(200,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(200,5,'SUMMARY OF COLLECTION',0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(200,5,'AS OF',0,1,'C');

$pdf->Line(5,45,205,45);

$pdf->SetFont('Arial','',10);
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'',0,0,'C');
$pdf->Cell(50,5,'ESTIMATES',1,0,'C');
$pdf->Cell(50,5,'COLLECTION',1,0,'C');
$pdf->Cell(50,5,'BALANCE TO COLLECT',1,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'A.  TAX REVENUE',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(50,5,'   - Business Tax',1,0,'L');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(50,5,'   - Community Tax',1,0,'L');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'B.  Fees & Charges',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(50,5,'   - Business Permit',1,0,'L');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(50,5,'   - Garbage',1,0,'L');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'C.  Miscellaneous',1,0,'L');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(50,5,'D.  Total Collection',1,0,'L');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(50,5,'',1,1,'C');

//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);
          
$pdf->Cell(200,5,'',0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Prepared By :',0,0,'L');
$pdf->Cell(172,5,'Noted By :',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Comparative Annual Report' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,'',0,0,'C');
$pdf->Cell(172,5,$getsignatories1[gs_pos],0,1,'L');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[2],1,0,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','B',10);
//$pdf->Cell(100,5,'Recommend Approval:',1,0,'L');
//$pdf->Cell(100,5,'Approved:',1,1,'L');

//$pdf->Cell(200,5,'',0,1,'C');
//$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(100,5,'',1,0,'C');
//$pdf->Cell(100,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(100,5,'',1,0,'C');
//$pdf->Cell(100,5,$resulta[2],1,1,'C');

//$pdf->Cell(200,5,'',0,1,'C');
//$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','',8);
//$pdf->Cell(25,5,'Date Printed:',0,0,'L');
//$pdf->SetFont('Arial','BU',8);
//$pdf->Cell(175,5,date('F d Y'),0,1,'L');

$report_desc='Summary of Collection';
//include 'report_signatories_footer.php';

$pdf->Output();

?>

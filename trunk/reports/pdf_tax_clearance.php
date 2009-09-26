<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     

$dbLink = get_db_connection();

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$resulta[0],0,1,'C');
$pdf->Cell(190,5,$resulta[1],0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190,5,$resulta[2],0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(190,5,'TAX CLEARANCE CERTIFICATE',0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');

					$result=mysql_query("select a.business_permit_code, a.application_date, b.business_name, b.business_street, a.transaction,
					concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
					concat(c.owner_street, ' ', c.owner_city_code, ' ', owner_province_code), a.business_permit_id, d.cap_inv, d.last_yr,
					b.employee_male, b.employee_female, d.bus_nature, c.owner_barangay_code, b.business_phone_no
					from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
					where a.business_permit_code ='$permit_number' and a.owner_id = b.owner_id and a.owner_id = c.owner_id and c.owner_id = d.owner_id") 
					or die(mysql_error());
          $resulta=mysql_fetch_row($result);

$petsa = date("F d Y");
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,5,'RENEWAL/NEW',0,1,'L');
$pdf->Cell(25,5,'PERMIT NO:',0,0,'L');
$pdf->SetFont('Arial','U',8);
$pdf->Cell(50,5,$resulta[0],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(170,5,'DATE:',0,0,'R');
$pdf->SetFont('Arial','U',8);
$pdf->Cell(20,5,$petsa,0,1,'L');

$pdf->Cell(190,5,'',0,0,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,5,'TO WHOM IT MAY CONCERN:',0,1,'L');

$pdf->Cell(190,5,'',0,0,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(45,5,'This is to certify that the applicant ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resulta[5],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'a resident of Brgy. ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(65,5,$resulta[14],0,1,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(38,5,', engaged in the business of ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resulta[13],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(38,5,' under the Trade Name/Style ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(59,5,$resulta[2],0,1,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(15,5,'located at ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(70,5,$resulta[3],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(30,5,' with telephone no. ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(25,5,$resulta[15],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(45,5,' has no liability with the Office ',0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(45,5,'as per records available.',0,1,'L');

$pdf->Cell(190,5,'',0,1,'C');

$pdf->Cell(190,5,'CLEARED IN ACCORDANCE WITH CERTAIN FEE CLEARED AS TO BUSINESS AND PROVISION OF MARKETCODE ORD. 399, S. OF 1993 ',0,1,'L');
$pdf->Cell(190,5,'OTHERWISE KNOWN AS THE LOCAL REVENUE CODE OF ILOILO CITY CLEARED AS TO REAL PROPERTY TAXES',0,1,'L');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

					$result=mysql_query("select sign1, sign2, sign3, sign4, pos1, pos2, pos3, pos4
					from permit_templates") or die(mysql_error());
          $resulta=mysql_fetch_row($result);
          
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(95,5,$resulta[0],0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(95,5,$resulta[4],0,0,'C');
$pdf->Cell(95,5,'APPROVED:',0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','BU',10);
$pdf->Cell(95,5,'',0,0,'C');
$pdf->Cell(95,5,$resulta[3],0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,'COMPUTATION OF TAXES, FEES, AND OTHER CHARGES: ',0,1,'L');

$pdf->Cell(63,5,'KINDS OF TAXES',1,0,'C');
$pdf->Cell(63,5,'GROSS SALES',1,0,'C');
$pdf->Cell(63,5,'TAX DUE',1,1,'C');




$pdf->Output();

?>
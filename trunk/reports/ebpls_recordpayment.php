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
    $resulta=mysql_fetch_row($result);
   
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(200,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(200,5,$resulta[0],0,1,'C');
$pdf->Cell(200,5,$resulta[1],0,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','BU',16);
$pdf->Cell(200,5,'ORDER OF PAYMENT',0,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5); 
$pdf->Cell(15,5,'DATE: ',0,0,'L');
$pdf->SetFont('Arial','U',10);
$pdf->Cell(20,5,date('F d Y'),0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5); 
$pdf->Cell(15,5,'The Provincial Treasurer',0,1,'L');

$pdf->SetX(5); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(45,5,$resulta[1],0,0,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,5,'Province',0,1,'L');

$pdf->SetX(5); 
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,'-------------------------------------',0,1,'L');

$pdf->SetX(5); 

				
/*	$result=mysql_query("select a.business_permit_id, a.application_date, b.business_name, b.business_street, 
	a.transaction, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
	concat(c.owner_street, ' ', c.owner_city_code, ' ', c.owner_province_code), a.business_permit_id, d.cap_inv, 
	d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_payment_mode
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
	where a.business_permit_code ='$permit_num' and a.business_id = b.business_id and a.owner_id = c.owner_id 
	and b.business_id = d.business_id") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

    $result1=mysql_query("select a.business_permit_id, a.application_date, b.business_name, b.business_street, 
	a.transaction, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
	concat(c.owner_street, ' ', c.owner_city_code, ' ', c.owner_province_code), a.business_permit_id, d.cap_inv, 
	d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_payment_mode, a.active
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
	where a.business_permit_code ='$permit_num' and a.business_id = b.business_id and a.owner_id = c.owner_id 
	and b.business_id = d.business_id and a.active = 1") or die(mysql_error());
    $resulta1=mysql_fetch_row($result1);
*/

$pdf->Cell(200,5,'',0,2,'C');
	
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(200,5,'TAXES AND FEES:',1,1,'L');


$pdf->Cell(200,5,'',0,2,'C');
$pdf->Cell(200,5,'',0,2,'C');


//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);
        
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'Recommend Approval:',0,0,'L');
$pdf->SetX(105);
$pdf->Cell(100,5,'Approved:',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(100,5,'',0,0,'C');
//$pdf->SetX(105);
//$pdf->Cell(100,5,$resulta[0],0,1,'C');
//$pdf->SetFont('Arial','B',10);

//$pdf->SetX(5);
//$pdf->Cell(100,5,'',0,0,'C');
//$pdf->SetX(105);
//$pdf->Cell(100,5,$resulta[2],0,0,'C');

$report_desc='Order of Payment';
include 'report_signatories_footer.php';

$pdf->Output();



?>

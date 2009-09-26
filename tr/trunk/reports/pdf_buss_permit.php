<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     

$dbLink = get_db_connection();

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(270,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(270,5,$resulta[0],0,1,'C');
$pdf->Cell(270,5,$resulta[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(270,5,$resulta[2],0,2,'C');
$pdf->Cell(270,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(270,5,'BUSINESS PERMIT/LICENSE',0,2,'C');
				
          $result=mysql_query("select a.business_permit_code, a.application_date, b.business_name, b.business_street, a.transaction,
					concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
					concat(c.owner_street, ' ', c.owner_city_code, ' ', owner_province_code), a.business_permit_id, d.cap_inv, d.last_yr,
					b.employee_male, b.employee_female, d.bus_nature
					from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
					where a.business_permit_code ='$permit_number' and a.owner_id = b.owner_id and a.owner_id = c.owner_id and c.owner_id = d.owner_id") 
					or die(mysql_error());
          $resulta=mysql_fetch_row($result);

          //'$permit_number' 
          
$pdf->Cell(270,5,'',0,2,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Application No.:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[0],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Type of Application:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$resulta[4],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Application Date:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[1],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Business Trade Name:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[2],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Business Address:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[3],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Owner:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[5],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Gender:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$resulta[6],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Home Address:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[7],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Type of Ownership:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[7],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Community Tax No:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,'',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Issued on:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,'',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Issued at:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,'',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Last Years Gross:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$resulta[10],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Main Business Activity:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,$resulta[13],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Capital:',1,0,'L');
//$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,$resulta[9],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'No. of Employees:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(30,5,'Male',1,0,'L');
$pdf->Cell(30,5,$resulta[11],1,0,'C');
$pdf->Cell(30,5,'Female',1,0,'L');
$pdf->Cell(30,5,$resulta[12],1,0,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',0,1,'L');

$pdf->Cell(270,5,'',0,2,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(270,5,'I hereby declare under oath that the above data are true and correct to the best of my knowledge and belief. ',1,1,'L');

$pdf->Cell(270,5,'',0,2,'C');
$pdf->Cell(270,5,'',0,2,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,5,'Particulars',1,0,'C');
$pdf->Cell(40,5,'Fees',1,0,'C');
$pdf->Cell(40,5,'Business Tax',1,0,'C');
$pdf->Cell(40,5,'OR No.',1,0,'C');
$pdf->Cell(40,5,'Date of Payment',1,0,'C');
$pdf->Cell(40,5,'Remarks',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,5,'Mayors Permit',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,5,'Garbage Fee',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,5,'Fire Fund Fee',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,5,'Medical Fee',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,5,'Others',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,0,'C');
$pdf->Cell(40,5,'',1,1,'C');

					$result=mysql_query("select sign1, sign2, sign3, sign4, pos1, pos2, pos3, pos4
					from permit_templates") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

$pdf->Cell(270,5,'',0,2,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(135,5,'Recommend Approval:',1,0,'L');
$pdf->Cell(135,5,'Approved:',1,1,'L');

$pdf->Cell(270,5,'',0,2,'C');
$pdf->Cell(270,5,'',0,2,'C');

$pdf->SetFont('Arial','BU',10);
$pdf->Cell(135,5,$resulta[0],1,0,'C');
$pdf->Cell(135,5,$resulta[3],1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(135,5,$resulta[4],1,0,'C');
$pdf->Cell(135,5,$resulta[7],1,1,'C');

$pdf->Output();



?>

			

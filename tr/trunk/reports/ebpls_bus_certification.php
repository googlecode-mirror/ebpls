<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     

$dbLink = get_db_connection();

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta1=mysql_fetch_row($result);
   
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$resulta1[0],0,1,'C');
$pdf->Cell(190,5,$resulta1[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,$resulta1[2],0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'BUSINESS CERTIFICATION',0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

//date('m/d/Y', $date_from table)
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(40,5,'     I HEREBY CERTIFY ',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(160,5,' that my business establishment is not located in a government lot, road right-of-way, sidewalk or ',0,1,'L'); 
$pdf->SetX(5);
$pdf->Cell(200,5,'any other public place. ',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->Cell(200,5,'    In the event that my business is located in any of the foregoing places, I hereby manifest that I have secured the necessary ',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(200,5,' written approval of the government agency concerned, as shown, by the herein attached permit acquired therefrom.',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(40,5,'     I FURTHER CERTIFY',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(160,5,' that my business establishment is not squatting in any private land. ',0,1,'L'); 

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->Cell(200,5,'     I am issuing this certification under penalties of perjury. ',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$getit = mysql_query("select * from ebpls_business_enterprise_permit where
			business_permit_code = '$permit_num'");
$getd = mysql_fetch_row($getit);
$owner_id = $getd[3];
$business_id = $getd[2];

	$result=mysql_query("select a.business_name, 
	concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name)
	from ebpls_business_enterprise a, ebpls_owner b
	where a.owner_id = b.owner_id and a.owner_id =$owner_id and 
	a.business_id=$business_id") or die(mysql_error());
    $resulta=mysql_fetch_row($result);


$pdf->SetFont('Arial','B',10);    
$pdf->Cell(95,5,$resulta[1],0,0,'C');
$pdf->Cell(95,5,$resulta[0],0,1,'C');

$pdf->Cell(95,5,'----------------------------------------------------------------------',0,0,'C');
$pdf->Cell(95,5,'----------------------------------------------------------------------',0,1,'C');

$pdf->SetFont('Arial','B',10);    
$pdf->Cell(95,5,'NAME OF APPLICANT/SIGNATURE',0,0,'C');
$pdf->Cell(95,5,'BUSINESS NAME',0,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);    
$pdf->SetX(10);
$pdf->Cell(95,5,'     SUBSCRIBED AND SWORN to before me this day of ',0,0,'L');
$pdf->SetFont('Arial','BU',10);    
$pdf->Cell(40,5,date("dS F Y"),0,0,'C'); 
$pdf->SetFont('Arial','',10);    
$pdf->Cell(15,5,' at the City of ',0,0,'C'); 
$pdf->SetFont('Arial','BU',10);    
$pdf->Cell(35,5,$resulta1[0],0,1,'C'); 
$pdf->SetFont('Arial','',10);   
 
$pdf->Cell(200,5,',Philippines.  Affiant exhibited to me his/her Community Tax Cert. No. ____________________',0,1,'L');
$pdf->Cell(200,5,',issued on _______________________ at  ______________________________. ',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->Cell(200,5,'Doc. No. :______________',0,1,'L');
$pdf->Cell(200,5,'Page No.:______________',0,1,'L');
$pdf->Cell(200,5,'Book No.:______________',0,1,'L');
$pdf->Cell(200,5,'Series of :______________',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->Cell(190,5,'NOTARY PUBLIC:___________',0,1,'R');
$pdf->Cell(190,5,'Until December 31, 20____',0,1,'R');
$pdf->Cell(190,5,'PTR & No.:_______________',0,1,'R');
$pdf->Cell(190,5,'Issued on :______________',0,1,'R');

$pdf->Output();



?>

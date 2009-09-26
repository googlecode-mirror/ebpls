<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     

$dbLink = get_db_connection();

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->image('peoplesmall.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$resulta[0],0,1,'C');
$pdf->Cell(190,5,$resulta[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,$resulta[2],0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'FISHERY PERMIT/LICENSE',0,2,'C');
				
	$result=mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name), 
	concat(a.owner_house_no, ' ', a.owner_street, ' ', a.owner_barangay_code, ' ', a.owner_zone_code, ' ', a.owner_district_code, ' ',
	a.owner_city_code, ' ', a.owner_province_code, ' ', a.owner_zip_code), a.owner_gender,
	b.transaction, b.for_year, c.crew, c.boat_name, b.ebpls_fishery_permit_application_date
	from ebpls_fish_owner a, ebpls_fishery_permit b, fish_boat c
	where a.owner_id = b.owner_id and ebpls_fishery_permit_code ='$permit_num'") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
         
$pdf->Cell(190,5,'',0,2,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Permit No.:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
//$pdf->Cell(75,5,$resulta[0],1,0,'L');
$pdf->Cell(75,5,'',1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Type of Application:',1,0,'L');
$pdf->SetX(165);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,$resulta[3],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Application Date:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,$resulta[7],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
//$pdf->Cell(40,5,$resulta[14],1,1,'L');
$pdf->Cell(40,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Boat Name:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(155,5,$resulta[6],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Business Address:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
//$pdf->Cell(155,5,$resulta[3],1,1,'L');
$pdf->Cell(155,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Owner:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,$resulta[0],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Gender:',1,0,'L');
$pdf->SetX(165);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,$resulta[2],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Home Address:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(155,5,$resulta[1],1,1,'L');
//$pdf->Cell(155,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Number of Crew/s:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,$resulta[5],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Validity Period:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
$pdf->Cell(40,5,$resulta[4],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'',0,0,'L');
$pdf->SetX(50);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,'',0,0,'L');
$pdf->SetX(125);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetX(175);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,5,'',0,1,'L');

//$pdf->SetFont('Arial','',10);
//$pdf->Cell(50,5,'',0,1,'L');

$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',0,1,'L');

$pdf->Cell(200,5,'',0,2,'C');
	
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(200,5,'TAXES AND FEES:',1,1,'L');
$pdf->Cell(200,5,'',0,2,'C');
$pdf->Cell(200,5,'',0,2,'C');

//new signatories table
	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'Recommend Approval:',0,0,'L');
$pdf->SetX(105);
$pdf->Cell(100,5,'Approved:',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(100,5,'',0,0,'C');
$pdf->SetX(105);
$pdf->Cell(100,5,$resulta[0],0,1,'C');
$pdf->SetFont('Arial','B',10);

$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'C');
$pdf->SetX(105);
$pdf->Cell(100,5,$resulta[2],0,0,'C');

$pdf->Output();



?>

<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include"../includes/variables.php";
include_once("../lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

		$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);

   
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
//$pdf->image('peoplesmall.jpg',10,5,33);
$pdf->Image('../images/ebpls_logo.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$getprov[0],0,1,'C');
$pdf->Cell(190,5,$getlgu[0],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,'Office of the Mayor',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'PEDDLER PERMIT/LICENSE',0,1,'C');
if ($reportpermit == '1') {
	$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	b.peddlers_business_name, b.for_year, b.application_date, 
	concat(a.owner_street, ' ', d.zone_desc, ' ', e.barangay_desc, ' ', f.district_desc, ' ', 
	g.city_municipality_desc, ' ', h.province_desc, a.owner_zip_code), b.merchandise_sold, b.peddlers_permit_code, b.for_year, b.transaction  
	from ebpls_owner a, ebpls_peddlers_permit b, 
	ebpls_zone d,  ebpls_barangay e, ebpls_district f, ebpls_city_municipality g, ebpls_province h 
	where a.owner_id=b.owner_id and a.owner_zone_code = d.zone_code and a.owner_barangay_code=e.barangay_code and a.owner_district_code=f.district_code and a.owner_city_code=g.city_municipality_code and a.owner_province_code=h.province_code and b.peddlers_permit_code ='$permit_num' or a.owner_last_name = '$owner_last' and b.active = '1' order by peddlers_permit_id  desc limit 1") 
	or die(mysql_error());
} else {
	$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	b.peddlers_business_name, b.for_year, b.application_date, 
	concat(a.owner_street, ', ', d.zone_desc, ', ', e.barangay_desc, ', ', f.district_desc, ', ', 
	g.city_municipality_desc, ', ', h.province_desc,' ', a.owner_zip_code), b.merchandise_sold, b.for_year, b.transaction 
	from ebpls_owner a, ebpls_peddlers_permit b, 
	ebpls_zone d,  ebpls_barangay e, ebpls_district f, ebpls_city_municipality g, ebpls_province h 
	where a.owner_id=b.owner_id and a.owner_zone_code = d.zone_code and a.owner_barangay_code=e.barangay_code and a.owner_district_code=f.district_code and a.owner_city_code=g.city_municipality_code and a.owner_province_code=h.province_code and b.peddlers_permit_code ='$permit_num' and b.active = '1'") 
	or die(mysql_error());
}
	$resulta=mysql_fetch_row($result);
	
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(50,5,'TO WHOM IT MAY CONCERN:',0,1,'L');

$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(40,5,'This is to certify that',0,0,'L');
$pdf->SetX(50);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(150,5,$resulta[0],0,1,'C');
$pdf->SetX(50);
$pdf->Cell(150,5,'-----------------------------------------------------------------------------------------------------------------------------',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->SetX(10);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,'of ');
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$resulta[4]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,' is hereby granted to operate his/her peddling activity.',0,1,'L');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');


$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(60,5,'NAME OF BUSINESS',1,0,'C');
$pdf->Cell(60,5,'MERCHANDISE SOLD',1,0,'C');
$pdf->Cell(40,5,'APPLICATION DATE',1,0,'C');
$pdf->Cell(40,5,'VALID FOR',1,1,'C');

//put values here
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(60,5,$resulta[1],1,0,'C');
$pdf->Cell(60,5,$resulta[5],1,0,'C');
$pdf->Cell(40,5,$resulta[3],1,0,'C');
$pdf->Cell(40,5,$resulta[2],1,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
if ($reportpermit == '1') {
$ntrans = $resulta[8];
} else {
$ntrans = $resulta[7];
}

$getfees = @mysql_query("select * from ebpls_peddlers_fees where permit_type = '$ntrans'");
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'FEE',1,0,'C');
$pdf->Cell(40,5,'AMOUNT',1,1,'C');

//put values here
$ntotalfees = 0;
while ($fetchfees = @mysql_fetch_assoc($getfees)) {
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(5);
	$pdf->Cell(100,5,$fetchfees[fee_desc],1,0,'L');
	$pdf->Cell(40,5,number_format($fetchfees[fee_amount],2),1,1,'R');
	$ntotalfees = $ntotalfees + $fetchfees[fee_amount];
}
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,"TOTAL",1,0,'R');
$pdf->Cell(40,5,number_format($ntotalfees,2),1,1,'R');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');


$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);                                                                                                       
                                                                                                                                                                                                                       
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(100,5,'Issued for all legal intents and/or registration purpose this',0,0,'L');
$pdf->SetX(110);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(50,5,date("F dS Y"),0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,' with permit number ',0,0,'L');
$pdf->SetFont('Arial','B',10);
if ($reportpermit == '1') {
$pdf->Cell(35,5,$resulta[6] ,0,0,'L');
} else {
$pdf->Cell(35,5,$permit_num ,0,0,'L');
}
$pdf->Cell(55,5,'Valid until December 31, ',0,0,'R');
$pdf->SetFont('Arial','BU',15);

if ($reportpermit == '1') {
$pdf->Cell(10,5,$resulta[7],0,0,'C');
} else {
$pdf->Cell(10,5,$resulta[6],0,1,'C');
}
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'L');
$pdf->SetX(135);
$pdf->Cell(70,5,'Approved:',0,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$getsignatories = @mysql_query("select * from report_signatories where report_file='Peddlers Permit' and sign_type='1'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'L');
$pdf->SetX(135);
$pdf->Cell(70,5,$getsignatories1[gs_name],0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'L');
$pdf->SetX(135);
$pdf->Cell(70,5,$getsignatories1[gs_pos],0,1,'C');
//include 'report_signatories_footer.php';

$pdf->Output();

?>


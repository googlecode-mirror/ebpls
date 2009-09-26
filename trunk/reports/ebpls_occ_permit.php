<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include'../includes/variables.php';
include_once("../lib/multidbconnection.php");
																								
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

//$dbLink = get_db_connection();
$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov); 
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->Image('../images/ebpls_logo.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$getprov[0],0,1,'C');
$pdf->Cell(190,5,$getlgu[0],0,2,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'Office of the Mayor',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'OCCUPATIONAL PERMIT/LICENSE',0,1,'C');

if ($reportpermit == '1') {
$result=mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', 
			a.owner_last_name),
			b.for_year, b.occ_position_applied,c.business_name,
			c.business_id , b.occ_permit_code, a.owner_id, a.owner_citizenship, a.owner_birth_date, a.owner_gender, b.transaction, a.owner_tin_no  
			from ebpls_owner a, ebpls_occupational_permit b, 
			ebpls_business_enterprise c 
			where b.occ_permit_code  = '$permit_num' or a.owner_last_name = '$owner_last' and a.owner_id = b.owner_id and
			b.business_id=c.business_id and b.active='1' limit 1") 
	or die(mysql_error());

} else {
	
$result=mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', 
			a.owner_last_name),
			b.for_year, b.occ_position_applied,c.business_name,
			c.business_id, b.occ_permit_code, a.owner_id, a.owner_citizenship, a.owner_birth_date, a.owner_gender, b.transaction, a.owner_tin_no 
			from ebpls_owner a, ebpls_occupational_permit b, 
			ebpls_business_enterprise c
			where b.occ_permit_code  ='$permit_num' and a.owner_id = b.owner_id and
			b.business_id=c.business_id and b.active='1'")	or die (mysql_error());
			
}
    $resulta=mysql_fetch_row($result);
	$stat = $resulta[10];
$busadd = mysql_query("select a.business_street, concat(c.barangay_desc,', ', d.district_desc,', ', e.city_municipality_desc,', ', f.province_desc,' ', a.business_zip_code) 
from ebpls_business_enterprise a, ebpls_barangay c, ebpls_district d, ebpls_city_municipality e, ebpls_province f where a.business_id = '$resulta[4]' and a.business_barangay_code = c.barangay_code and a.business_district_code = d.district_code and a.business_city_code = e.city_municipality_code and a.business_province_code = f.province_code");
$ownadd = mysql_query("select concat(a.owner_street,', ', b.zone_desc,', ', c.barangay_desc,', ', d.district_desc,', ', e.city_municipality_desc,', ', 
f.province_desc) from ebpls_owner a, ebpls_zone b, ebpls_barangay c, ebpls_district d, ebpls_city_municipality e, ebpls_province f where a.owner_id = '$resulta[6]' and a.owner_zone_code = b.zone_code and a.owner_barangay_code = c.barangay_code and a.owner_district_code = d.district_code and a.owner_city_code = e.city_municipality_code and a.owner_province_code = f.province_code");
$getzon = mysql_query("select a.zone_desc from ebpls_zone a, ebpls_business_enterprise b where b.business_zone_code = a.zone_code and b.business_id = '$resulta[4]'");
$getzone = mysql_fetch_assoc($getzon);
if ($getzone[zone_desc] == "") {
	$nzonei = "";
} else {
	$nzonei = "$getzone[zone_desc], ";
}
$ownadds = mysql_fetch_row($ownadd);
$busadds = mysql_fetch_row($busadd);
          //$permit_num
          //000000000000029
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);

$pdf->SetFont('Arial','',10);
$pdf->Write(5,"Is hereby granted to ");
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$resulta[0]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5," residing at ");
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$ownadds[0]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5," to work as ");
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$resulta[2]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5," at ");
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$resulta[3]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5," located at ");
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$busadds[1].", ".$nzonei.$busadds[1]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,".");

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'Other Pertinent Information',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->SetX(5);
$pdf->Cell(40,5,'Date of Birth',1,0,'L');
$pdf->Cell(40,5,'Age',1,0,'L');
$pdf->Cell(40,5,'Nationality',1,0,'L');
$pdf->Cell(40,5,'TIN',1,0,'L');
$pdf->Cell(40,5,'Gender',1,1,'L');
$pdf->SetX(5);


$nage = mysql_query("select datediff(now(), '$resulta[8]') /365 as age");

$nAge = mysql_fetch_row($nage);
$nAge = number_format($nAge[0],0);

$b_day=substr($resulta[8], 0, 10);    
$pdf->Cell(40,5,$b_day,1,0,'L');
$pdf->Cell(40,5,$nAge,1,0,'L');
$pdf->Cell(40,5,$resulta[7],1,0,'L');
$pdf->Cell(40,5,$resulta[11],1,0,'L');
$pdf->Cell(40,5,$resulta[9],1,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->SetX(5);
$pdf->Cell(200,5,'subject, however, to all existing laws and city ordinances governing the same.',0,1,'L');
$pdf->SetX(10);
$pdf->Cell(43,5,'Valid until December 31, ',0,0,'L');
$pdf->SetFont('Arial','BU',15);
$pdf->Cell(10,5,$resulta[1],0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,' unless sooner revoked.',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(5);
$pdf->Cell(40,5,'Issuance Date:',0,0,'L');
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(40,5,date("F d Y"),0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,' with permit number ',0,0,'L');
$pdf->SetFont('Arial','B',10);
if ($reportpermit == '1') {
$pdf->Cell(25,5,$resulta[5] ,0,0,'L');
} else {
	$pdf->Cell(25,5,$permit_num ,0,0,'L');
}
    
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;


$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(100,5,'TAXES AND FEES',1,0,'L');
$pdf->Cell(40,5,'AMOUNT',1,1,'L');
                                                                                                                                                            
if ($reportpermit == '1') {   
$getid = mysql_query("select owner_id, transaction from ebpls_occupational_permit
                where occ_permit_code  ='$resulta[5]' and active = '1'");
} else {
$getid = mysql_query("select owner_id, transaction from ebpls_occupational_permit
                where occ_permit_code  ='$permit_num' and active = '1'");
}

$getd = mysql_fetch_row($getid);
$owner_id = $getd[0];
$stat = $getd[1];
                                                                                                                                                            
                                                                                                                                                            
$resultf = mysql_query("select fee_desc, fee_amount from ebpls_fees_paid where owner_id='$owner_id' and
                        permit_type='Occupational' and permit_status = '$stat'");
                                                                                                                                                            
 $i = 1;
        //$pdf->SetY($Y_Table_Position);
		$nTot = 0;
        while ($busline=mysql_fetch_row($resultf))
        {
                $pdf->SetX(5);
                $pdf->Cell(100,5,$busline[0],1,0,'L');
                $pdf->Cell(40,5,number_format($busline[1],2),1,0,'R');
                                                                                                                                                            
                $i++;
				$nTot = $nTot + $busline[1];
                $pdf->SetY($pdf->GetY()+5);
        }
		$pdf->SetX(5);
                $pdf->Cell(100,5,'TOTAL',1,0,'R');
                $pdf->Cell(40,5,number_format($nTot,2),1,0,'R');



//$pdf->SetY(-18);
$pdf->SetX(5);
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'L');
$pdf->SetX(135);
$pdf->Cell(70,5,'Approved:',0,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$getsignatories = @mysql_query("select * from report_signatories where report_file='Occupational Permit' and sign_type='1'");
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


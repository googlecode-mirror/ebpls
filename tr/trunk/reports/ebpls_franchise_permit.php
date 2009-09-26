<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     

$dbLink = get_db_connection();

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resultas=mysql_fetch_row($result);
   
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->image('peoplesmall.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$resultas[1],0,1,'C');
$pdf->Cell(190,5,$resultas[0],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,$resultas[2],0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'FRANCHISE PERMIT/LICENSE',0,1,'C');
				
	$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number, c.motorized_operator_permit_id,
	concat(a.owner_house_no, ' ', a.owner_street, ' ', d.barangay_desc, ' ', a.owner_city_code, ' ', 
	a.owner_province_code, ' ', a.owner_zip_code)
	from ebpls_mtop_owner a, ebpls_motorized_vehicles b, ebpls_motorized_operator_permit c,
	ebpls_barangay d, ebpls_district e, ebpls_city_municipality f, ebpls_province g, ebpls_zone h, ebpls_zip i
	where a.owner_id=b.motorized_operator_id and a.owner_id=c.owner_id and a.owner_barangay_code = d.barangay_code order by b.motorized_motor_model asc") 
	or die(mysql_error());
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
$pdf->Cell(200,5,'',0,1,'C');
$pdf->SetX(5);
$pdf->SetFont('Arial','',10);
$pdf->Cell(5,5,'of',0,0,'L');
$pdf->SetX(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(100,5,$resulta[12],0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->SetX(110);
$pdf->Cell(25,5,', CITY/MUNICIPALITY OF ',0,0,'L');
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(100,5,$resultas[0],0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(100,5,'is authorized to operate.',0,1,'C');
$pdf->SetX(5);
$pdf->Cell(100,5,'Motorized Tricycle for hire.',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(80,5,'MAKE',1,0,'C');
$pdf->SetX(85);
$pdf->Cell(40,5,'MOTOR NUMBER',1,0,'C');
$pdf->SetX(125);
$pdf->Cell(40,5,'CHASSIS NUMBER',1,0,'C');
$pdf->SetX(165);
$pdf->Cell(35,5,'PLATE NUMBER',1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(80,5,$resulta[1],1,0,'C');
$pdf->SetX(85);
$pdf->Cell(40,5,$resulta[2],1,0,'C');
$pdf->SetX(125);
$pdf->Cell(40,5,$resulta[3],1,0,'C');
$pdf->SetX(165);
$pdf->Cell(35,5,$resulta[4],1,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');


$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(200,5,'TAXES AND FEES:',1,1,'L');


$getid = mysql_query("select owner_id, transaction
                 from ebpls_motorized_operator_permit
                where motorized_operator_permit_id ='$permit_num' and active = 1");
$getd = mysql_fetch_row($getid);
$owner_id = $getd[0];
$stat = $getd[1];


$resultf = mysql_query("select fee_desc, fee_amount
			 from ebpls_fees_paid where owner_id=$owner_id and
			permit_type='franchise'");
                                                                                                               
 $i = 1;
        //$pdf->SetY($Y_Table_Position);
        while ($busline=mysql_fetch_row($resultf))
        {
                $pdf->SetX(5);
                $pdf->Cell(120,5,$busline[0],1,0,'L');
                $pdf->SetX(125);
                $pdf->Cell(40,5,'',1,0,'R');
                $pdf->SetX(165);
                $pdf->Cell(40,5,number_format($busline[1],2),1,0,'R');
                                                                                                               
                $i++;
                $pdf->SetY($pdf->GetY()+5);
        }                                                                                                              

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(100,5,'Issued for all legal intents and/or registration purpose this',0,0,'L');
$pdf->SetX(110);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(50,5,date("F dS Y"),0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

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



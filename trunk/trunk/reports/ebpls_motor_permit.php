<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../includes/variables.php");

include_once("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$statu = $stat;
//$dbLink = get_db_connection();

		$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resultsa=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resultsa[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resultsa[1]'");
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
$pdf->Cell(190,5,strtoupper($permit_type).' PERMIT/LICENSE',0,1,'C');
				
	if ($reportpermit == '1') {
		if ($permit_type=='Motorized') {
			$cntid = 'motorized_permit_code';
			$permittable = 'ebpls_motorized_operator_permit';
		}
		if ($permit_type=='Franchise') {
			$cntid = 'franchise_permit_code';
			$permittable = 'ebpls_franchise_permit';
		}
		if ($owner_last != "" and $permit_num == "") {
			$lastwhere = "a.owner_last_name = '$owner_last'";
		} elseif ($owner_last == "" and $permit_num != "") {
			$lastwhere = "c.$cntid = '$permit_num'";
		} elseif ($owner_last != "" and $permit_num != "") {
			$lastwhere = "c.$cntid = '$permit_num' or a.owner_last_name = '$owner_last'";
		}
		
		$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number, c.$cntid,
	concat(a.owner_street, ', ', d.zone_desc, ', ', e.barangay_desc, ', ', f.district_desc, ', ', g.city_municipality_desc, ', ', 
	h.province_desc, ' ', a.owner_zip_code), a.owner_id, c.transaction, c.for_year
	from ebpls_owner a, ebpls_motorized_vehicles b, $permittable c,
	ebpls_zone d, ebpls_barangay e, ebpls_district f, ebpls_city_municipality g, ebpls_province h, ebpls_zip i
	where $lastwhere and a.owner_id=b.motorized_operator_id and a.owner_id=c.owner_id and a.owner_barangay_code = e.barangay_code and a.owner_district_code = f.district_code and a.owner_city_code = g.city_municipality_code and a.owner_province_code = h.province_code and c.active = '1' 
	 limit 1") 
	or die("dasdas".mysql_error());
	} else {
		
	$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number, c.$cntid,
	concat(a.owner_street, ', ', d.zone_desc, ', ', e.barangay_desc, ', ', f.district_desc, ', ', g.city_municipality_desc, ', ', 
	h.province_desc, ' ', i.zip_desc), c.for_year
	from $owner a, ebpls_motorized_vehicles b, $permittable c,
ebpls_zone d, ebpls_barangay e, ebpls_district f, ebpls_city_municipality g, ebpls_province h, ebpls_zip i
	where a.owner_id=b.motorized_operator_id and a.owner_id=c.owner_id and a.owner_barangay_code = e.barangay_code and a.owner_district_code = f.district_code and a.owner_city_code = g.city_municipality_code and a.owner_province_code = h.province_code  and c.active = '1' 
	and $incode ='$permit_num'") 
	or die($permit_type."dasdas".mysql_error());
	}
	$resulta=mysql_fetch_row($result);
	      //$permit_num 
if ($reportpermit == '1') {  
	$nValidity = $resulta[15];
} else {
	$nValidity = $resulta[13];
}

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(50,5,'TO WHOM IT MAY CONCERN:',0,1,'L');

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Write(5,'This is to certify that ');
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$resulta[0]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,' of ');
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$resulta[12]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,', is authorized to operate ');
$pdf->SetFont('Arial','B',10);
$pdf->Write(5,ucfirst($permit_type));
$pdf->SetFont('Arial','',10);
$pdf->Write(5,' Tricycle for hire.');

$pdf->Cell(200,5,'',0,1,'C');

if ($permit_type=='Franchise') {
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(50,5,$resulta[1],1,0,'C');
$pdf->SetX(85);
$pdf->Cell(50,5,$resulta[2],1,0,'C');
$pdf->SetX(125);
$pdf->Cell(50,5,$resulta[3],1,0,'C');
$pdf->SetX(165);
$pdf->Cell(50,5,$resulta[4],1,1,'C');
$x=1;
} else {
$checkrentype = @mysql_query("select * from ebpls_motorized_penalty where permit_type = '$permit_type'");
$checkrentype1 = @mysql_fetch_assoc($checkrentype);

if ($checkrentype1['renewaltype'] == '2' and $stat =="ReNew") {
	$nRetire = "and b.retire = '5'";
} else {
	$nRetire = "";
}
if ($reportpermit == '1') {
	$result2 = mysql_query("select 
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number
	from ebpls_motorized_vehicles b , ebpls_motorized_operator_permit c
	where b.motorized_operator_id='$resulta[13]' 
	and c.motorized_permit_code ='$resulta[11]' and c.active='1' $nRetire") 
	or die($permit_type."dasdas1".mysql_error());
} else {
$result2 = mysql_query("select 
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number, b.route
	from ebpls_motorized_vehicles b , ebpls_motorized_operator_permit c
	where b.motorized_operator_id='$owner_id' 
	and $incode ='$permit_num' and c.active='1' $nRetire") 
	or die($permit_type."dasdas".mysql_error());
}
$getvehcount = mysql_num_rows($result2);
$pdf->Cell(50,5,'',0,1,'L');
$ntresult2 = $result2;
$ntresult3 = $result2;
$getveht = mysql_num_rows($result2);
$pdf->SetFont('Arial','B',10);
$nYY = $pdf->GetY();
$pdf->SetX(5);
$pdf->Cell(50,5,'MOTOR MODEL/BRAND',1,0,'C');
$pdf->Cell(50,5,'MOTOR NUMBER',1,0,'C');
$pdf->Cell(50,5,'CHASSIS NUMBER',1,0,'C');
$pdf->Cell(50,5,'PLATE NUMBER',1,1,'C');

while($result3=mysql_fetch_assoc($result2)) {
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(50,5,$result3[motorized_motor_model],1,0,'L');
$pdf->Cell(50,5,$result3[motorized_motor_no],1,0,'L');
$pdf->Cell(50,5,$result3[motorized_chassis_no],1,0,'L');
$pdf->Cell(50,5,$result3[motorized_plate_no],1,1,'L');
}

$pdf->Cell(50,5,'',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(50,5,'BODY NUMBER',1,0,'C');
$pdf->Cell(50,5,'BODY COLOR',1,0,'C');
$pdf->Cell(100,5,'ROUTE',1,1,'C');
//echo $ntresult2;
if ($reportpermit == '1') {
	$ntresult2 = mysql_query("select 
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number
	from ebpls_motorized_vehicles b , ebpls_motorized_operator_permit c
	where b.motorized_operator_id='$resulta[13]' 
	and c.motorized_permit_code ='$resulta[11]' and c.active='1' $nRetire") 
	or die($permit_type."dasdas1".mysql_error());
} else {
$ntresult2 = mysql_query("select 
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number, b.route
	from ebpls_motorized_vehicles b , ebpls_motorized_operator_permit c
	where b.motorized_operator_id='$owner_id' 
	and $incode ='$permit_num' and c.active='1' $nRetire") 
	or die($permit_type."dasdas".mysql_error());
}
while( $result4 = mysql_fetch_assoc($ntresult2) ) {
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(50,5,$result4[motorized_body_no],1,0,'L');
$pdf->Cell(50,5,$result4[body_color],1,0,'L');
$pdf->Cell(100,5,$result4[route],1,1,'L');
}
$pdf->Cell(50,5,'',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(50,5,'LINE TYPE',1,0,'C');
$pdf->Cell(75,5,'LTO REGISTRATION NO',1,0,'C');
$pdf->Cell(75,5,'CERTIFICATE OF REGISTRATION NO',1,1,'C');
if ($reportpermit == '1') {
	$ntresult3 = mysql_query("select 
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number
	from ebpls_motorized_vehicles b , ebpls_motorized_operator_permit c
	where b.motorized_operator_id='$resulta[13]' 
	and c.motorized_permit_code ='$resulta[11]' and c.active='1' $nRetire") 
	or die($permit_type."dasdas1".mysql_error());
} else {
$ntresult3 = mysql_query("select 
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number, b.route
	from ebpls_motorized_vehicles b , ebpls_motorized_operator_permit c
	where b.motorized_operator_id='$owner_id' 
	and $incode ='$permit_num' and c.active='1' $nRetire") 
	or die($permit_type."dasdas".mysql_error());
}
while($result5 = mysql_fetch_assoc($ntresult3)) {
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(50,5,$result5[linetype],1,0,'L');
$pdf->Cell(75,5,$result5[lto_number],1,0,'L');
$pdf->Cell(75,5,$result5[cr_number],1,1,'L');
}




	
	
		
}
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');


$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(100,5,'TAXES AND FEES',1,0,'C');
$pdf->Cell(40,5,'AMOUNT',1,1,'C');

/*
$getid = mysql_query("select owner_id, transaction from ebpls_motorized_operator_permit
                where motorized_operator_permit_id ='$permit_num' and active = 1");
*/
if ($reportpermit == '1') {
$getid = mysql_query("select owner_id, transaction from $permittable
                where motorized_permit_code ='$resulta[11]'") or die($incode."dasd".mysql_error());            
               //000000000000007
} else {
$getid = mysql_query("select owner_id, transaction from $permittable
                where motorized_permit_code ='$permit_num'") or die($incode."dasd".mysql_error());            
               //000000000000007
}
$getd = mysql_fetch_row($getid);
$owner_id = $getd[0];

$stat = $getd[1];
$i=1;
$inputdate = date('Y');
if ($reportpermit == '1') {
$resultf = mysql_query("select *  from ebpls_fees_paid where owner_id = '$owner_id' and permit_type='Motorized' and input_date like '$inputdate%' and permit_status = '$statu'")or die(mysql_error());
} else {
$resultf = mysql_query("select *  from ebpls_fees_paid where owner_id = '$owner_id' and permit_type='Motorized' and input_date like '$inputdate%' and permit_status = '$statu'")or die(mysql_error());
}
        //$pdf->SetY($Y_Table_Position);
		$nTot = 0;
        while ($busline=mysql_fetch_assoc($resultf))
        {
                $pdf->SetX(5);
                $pdf->Cell(100,5,$busline[fee_desc],1,0,'L');
                $pdf->Cell(40,5,number_format($busline[fee_amount] ,2),1,0,'R');
                                                                                                               
                $i++;
				$nTot = $nTot + ($busline[fee_amount]);
                $pdf->SetY($pdf->GetY()+5);
        }
		$pdf->SetX(5);
                $pdf->Cell(100,5,'TOTAL',1,0,'R');
                $pdf->Cell(40,5,number_format($nTot,2),1,0,'R');
                                                                                                               
                                                                                                               

                                                                                                               
                                                                                                               



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
if ($reportpermit =='1') {
	$pdf->Cell(35,5,$resulta[11] ,0,0,'L');
} else {
$pdf->Cell(35,5,$permit_num ,0,0,'L');
}
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'Valid until December 31, ',0,0,'R');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(25,5,$nValidity,0,1,'L');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

//new signatories table

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(130,5,'',0,0,'L');
$pdf->Cell(70,5,'Approved:',0,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$getsignatories = @mysql_query("select * from report_signatories where report_file='$permit_type Permit' and sign_type='1'");
$getsignatories1 = @mysql_fetch_assoc($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_assoc($getsignatories);
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(130,5,'',0,0,'L');
$pdf->Cell(70,5,$getsignatories1[gs_name],0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(130,5,'',0,0,'L');
$pdf->Cell(70,5,$getsignatories1[gs_pos],0,1,'C');

//include 'report_signatories_footer.php';

$pdf->Output();

?>


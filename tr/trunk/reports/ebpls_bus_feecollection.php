<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("lib/phpFunctions-inc.php");

$dbLink = get_db_connection();
$e = strrpos($owner_last,"-");//$owner_last is date
$l =strlen($owner_last);
$dateprev = substr($owner_last, $l-$e);
$dateprev = $dateprev;
$datenext = $dateprev + 1;

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
$pdf=new FPDF('L','mm','Legal');
$pdf->AddPage();
$pdf->image('peoplesmall.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(340,5,$resulta[0],0,1,'C');
$pdf->Cell(340,5,$resulta[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(340,5,$resulta[2],0,2,'C');
$pdf->Cell(340,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(340,5,'BUSINESS TAX COLLECTION',0,1,'C');

/*	$result=mysql_query("select b.business_name, a.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', 
	c.owner_last_name), e.bus_nature, concat(b.business_street, ' ', b.business_barangay_code, ' ', b.business_zone_code, ' ', b.business_district_code, ' ', b.business_city_code), d.or_date, d.or_no, 
	d.total_amount_paid/(select count(d.or_no) from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, ebpls_transaction_payment_or d,
	tempbusnature e
	where a.owner_id = b.owner_id and a.owner_id = c.owner_id and b.owner_id = d.trans_id 
	and b.business_id = e.business_id and d.or_date like '$dateprev%'), 
	d.or_no, d.or_date, b.business_category_code, d.ts_create
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, ebpls_transaction_payment_or d,
	tempbusnature e
	where a.owner_id = b.owner_id and a.owner_id = c.owner_id and b.owner_id = d.trans_id 
	and b.business_id = e.business_id and d.or_date like '$dateprev%' limit 10") or die(mysql_error());
*/
/*	$result = mysql_query("select a.business_name, b.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ',
	c.owner_last_name), concat(a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code, ' ', a.business_district_code, ' ', a.business_city_code),
	d.for_year, d.or_no, d.fees/(select count(d.or_no) from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, comparative_statement d,
	tempbusnature e
	where a.owner_id = b.owner_id and a.owner_id = c.owner_id and b.owner_id = d.owner_id 
	and b.business_id = e.business_id and d.for_year='$dateprev%'), d.ts from ebpls_business_enterprise a, ebpls_business_enterprise_permit b, ebpls_owner c,
	comparative_statement d, tempbusnature e  where a.owner_id = b.owner_id and a.owner_id = c.owner_id and a.owner_id=d.owner_id and d.business_id=a.business_id and a.business_id=e.business_id and a.owner_id=e.owner_id and d.for_year='$dateprev'") or die(mysql_error());*/
	
	$result = mysql_query("select a.business_name, b.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), concat(a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code, ' ', a.business_district_code, ' ', a.business_city_code), d.for_year, d.or_no, d.fees from ebpls_business_enterprise a, ebpls_business_enterprise_permit b, ebpls_owner c, comparative_statement d where d.for_year='$dateprev' and a.owner_id=d.owner_id and b.owner_id=d.owner_id and c.owner_id=d.owner_id") or die(mysql_error());
	
	$number_of_rows = mysql_num_rows($result);
    while ($resultx=mysql_fetch_row($result))

	{
    	$row1 = $resultx[0];
    	$row2 = $resultx[1];
		$row3 = $resultx[2];
		$row4 = $resultx[3];
		$row5 = $resultx[4];
		$row6 = $resultx[5];
		$row7 = number_format($resultx[6],2);
		
    	$column_code1 = $column_code1.$row1."\n";
    	$column_code2 = $column_code2.$row2."\n";
    	$column_code3 = $column_code3.$row3."\n";
    	$column_code4 = $column_code4.$row4."\n";
    	$column_code5 = $column_code5.$row5."\n";
    	$column_code6 = $column_code6.$row6."\n";
    	$column_code7 = $column_code7.$row7."\n";
    	$column_code8 = $column_code8.$row8."\n";
//     	$column_code9 = $column_code7.$row7."\n";
//     	$column_code10 = $column_code8.$row8."\n";
    				
	}

$pdf->SetLineWidth(2);
$pdf->Line(0,45,360,45);
$pdf->SetLineWidth(0);
					
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(35,5,'NAME OF BUSINESS',1,0,'C');
$pdf->SetX(40);
$pdf->Cell(30,5,'PERMIT NUMBER',1,0,'C');
$pdf->SetX(70);
$pdf->Cell(60,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(130);
$pdf->Cell(70,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->SetX(200);
$pdf->Cell(20,5,'YEAR',1,0,'C');
$pdf->SetX(220);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
$pdf->SetX(235);
$pdf->Cell(20,5,'AMOUNT PAID',1,0,'C');

 
$pdf->SetFont('Arial','',6);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
$pdf->MultiCell(35,5,$column_code1,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(40);
$pdf->MultiCell(30,5,$column_code2,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(70);
$pdf->MultiCell(60,5,$column_code3,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(130);
$pdf->MultiCell(70,5,$column_code4,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(200);
$pdf->MultiCell(20,5,$column_code5,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(220);
$pdf->MultiCell(15,5,$column_code6,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(235);
$pdf->MultiCell(20,5,$column_code7,1,'R');

$i = 0;
$ymulti=5*$number_of_rows;
$pdf->SetY($Y_Table_Position);
while ($i < $number_of_rows)
{
    $pdf->SetX(5);
    $pdf->MultiCell(250,5,'',1);
    $i = $i +1;
} 

//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

$Y_Table_Position = $Y_Table_Position + 25 + $ymulti;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Approved By:',1,0,'L');
$pdf->Cell(172,5,'Noted By:',1,1,'L');

$pdf->Cell(350,5,'',0,2,'C');
$pdf->Cell(350,5,'',0,2,'C');

$report_desc='Business Fee Collection';
include 'report_signatories_footer2.php';

//$pdf->SetFont('Arial','BU',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,$resulta[0],1,0,'C');
//$pdf->Cell(172,5,$resulta[3],1,1,'C');

//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,$resulta[2],1,0,'C');
//$pdf->Cell(172,5,$resulta[7],1,1,'C');

$pdf->Output();

?>

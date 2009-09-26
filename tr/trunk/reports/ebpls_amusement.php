<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("lib/phpFunctions-inc.php");

$dbLink = get_db_connection();

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
$pdf->Cell(340,5,'AMUSEMENT TAX COLLECTION',0,1,'C');

	$result=mysql_query("select a.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', 
	c.owner_last_name), b.business_name, e.bus_nature, b.business_street, e.cap_inv, e.last_yr, d.total_amount_paid, 
	d.or_no, d.or_date, b.business_category_code
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, ebpls_transaction_payment_or d,
	tempbusnature e
	where a.owner_id = b.owner_id and a.owner_id = c.owner_id and b.owner_id = d.trans_id 
	and b.business_id = e.business_id limit 10") or die(mysql_error());
					
	$number_of_rows = mysql_numrows($result);
    while ($resulta=mysql_fetch_row($result))

	{
    	$row1 = $resulta[0];
    	$row2 = $resulta[1];
		$row3 = $resulta[2];
		$row4 = $resulta[3];
		$row5 = $resulta[4];
		$row6 = number_format($resulta[5],',','.','.');
		$row7 = number_format($resulta[6],',','.','.');
		$row8 = $resulta[7];
		$row9 = $resulta[8];
		$row10 = substr($resulta[9],0,10);
		$row11 = $resulta[10];
		
    	$column_code1 = $column_code1.$row1."\n";
    	$column_code2 = $column_code2.$row2."\n";
    	$column_code3 = $column_code3.$row3."\n";
    	$column_code4 = $column_code4.$row4."\n";
    	$column_code5 = $column_code5.$row5."\n";
    	$column_code6 = $column_code6.$row6."\n";
    	$column_code7 = $column_code7.$row7."\n";
    	$column_code8 = $column_code8.$row8."\n";
    	$column_code9 = $column_code9.$row9."\n";
    	$column_code10 = $column_code10.$row10."\n";
    	$column_code11 = $column_code11.$row11."\n";
    				
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
$pdf->Cell(20,5,'PERMIT NUMBER',1,0,'C');
$pdf->SetX(60);
$pdf->Cell(60,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(120);
$pdf->Cell(30,5,'BUSINESS NATURE',1,0,'C');
$pdf->SetX(150);
$pdf->Cell(60,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->SetX(210);
$pdf->Cell(20,5,'1ST QUARTER',1,0,'C');
$pdf->SetX(230);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
$pdf->SetX(245);
$pdf->Cell(20,5,'2ND QUARTER',1,0,'C');
$pdf->SetX(265);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
$pdf->SetX(280);
$pdf->Cell(20,5,'3RD QUARTER',1,0,'C');
$pdf->SetX(300);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
$pdf->SetX(315);
$pdf->Cell(20,5,'4TH QUARTER',1,0,'C');
$pdf->SetX(335);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
 
$pdf->SetFont('Arial','',6);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
$pdf->MultiCell(35,5,$column_code1,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(40);
$pdf->MultiCell(20,5,$column_code2,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(60);
$pdf->MultiCell(60,5,$column_code3,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(120);
$pdf->MultiCell(30,5,$column_code4,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(60,5,$column_code5,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(210);
$pdf->MultiCell(20,5,$column_code6,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(230);
$pdf->MultiCell(15,5,$column_code7,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(245);
$pdf->MultiCell(20,5,$column_code8,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(265);
$pdf->MultiCell(15,5,$column_code9,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(280);
$pdf->MultiCell(20,5,$column_code10,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(300);
$pdf->MultiCell(15,5,$column_code11,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(315);
$pdf->MultiCell(20,5,$column_code11,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(335);
$pdf->MultiCell(15,5,$column_code11,1);

$i = 1;
$pdf->SetY($Y_Table_Position);
while ($i < $number_of_rows)
{
    $pdf->SetX(5);
    $pdf->MultiCell(345,5,'',1);
    $i = $i +1;
} 

//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

$Y_Table_Position = $Y_Table_Position + 20;
          
//$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Approved By:',1,0,'L');
$pdf->Cell(172,5,'Noted By:',1,1,'L');

$pdf->Cell(350,5,'',0,2,'C');
$pdf->Cell(350,5,'',0,2,'C');

$report_desc='List of Amusement Establishment';
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

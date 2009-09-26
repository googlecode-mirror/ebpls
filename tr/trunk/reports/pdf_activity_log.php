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
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$resulta[0],0,1,'C');
$pdf->Cell(190,5,$resulta[1],0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190,5,$resulta[2],0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(190,5,'ACTIVITY LOG',0,1,'C');

$pdf->SetLineWidth(2);
$pdf->Line(0,45,360,45);
$pdf->SetLineWidth(0);
					
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

					$result=mysql_query("select userid, userlevel, username, action, remoteip, lastupdated
	        from ebpls_activity_log where lastupdated between '$date1' and '$date2' ") or die(mysql_error());
          
          $number_of_rows = mysql_numrows($result);
          while ($resulta=mysql_fetch_row($result));
					{
    					$row1 = $resulta[0];
    					$row2 = $resulta[1];
							$row3 = $resulta[2];
							$row4 = $resulta[3];
							$row5 = $resulta[4];
							$row6 = $resulta[5];
							
    					$column_code1 = $column_code.$row1."\n";
    					$column_code2 = $column_code.$row2."\n";
    					$column_code3 = $column_code.$row3."\n";
    					$column_code4 = $column_code.$row4."\n";
    					$column_code5 = $column_code.$row5."\n";
    					$column_code6 = $column_code.$row6."\n";
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
$pdf->Cell(10,5,'USER ID',1,0,'C');
$pdf->SetX(15);
$pdf->Cell(35,5,'USER LEVEL',1,0,'C');
$pdf->SetX(50);
$pdf->Cell(70,5,'USERNAME',1,0,'C');
$pdf->SetX(120);
$pdf->Cell(40,5,'ACTION',1,0,'C');
$pdf->SetX(160);
$pdf->Cell(20,5,'REMOTE IP',1,0,'C');
$pdf->SetX(180);
$pdf->Cell(25,5,'DATE UPDATED',1,0,'C');

$pdf->SetFont('Arial','',6);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
$pdf->MultiCell(10,5,$column_code1,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(15);
$pdf->MultiCell(35,5,$column_code2,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(50);
$pdf->MultiCell(70,5,$column_name3,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(120);
$pdf->MultiCell(40,5,$column_name4,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(160);
$pdf->MultiCell(20,5,$column_name5,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(180);
$pdf->MultiCell(25,5,$column_name6,1);
$pdf->SetY($Y_Table_Position);

$pdf->Output();

?>
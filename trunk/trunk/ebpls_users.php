<?php                                 

require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("lib/phpFunctions-inc.php");

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
$pdf->Cell(190,5,'USER LISTING',0,1,'C');    

$pdf->SetLineWidth(2);
$pdf->Line(0,45,360,45);
$pdf->SetLineWidth(0);
					
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

/*					$result=mysql_query("select userid, userlevel, username, action, remoteip, lastupdated
	        from ebpls_activity_log where lastupdated between '$date1' and '$date2' ") or die(mysql_error());
*/
          $result=mysql_query("select id, level, username from ebpls_user") or die(mysql_error());   
      
          $number_of_rows = mysql_numrows($result);
          while($resulta=mysql_fetch_row($result))
					{
    					$row1 = $resulta[0];
    					$row2 = $resulta[1];
							$row3 = $resulta[2];
							
    					$column_code1 = $column_code1.$row1."\n";
    					$column_code2 = $column_code2.$row2."\n";
    					$column_code3 = $column_code3.$row3."\n";
    					
					}

$pdf->SetLineWidth(2);
$pdf->Line(0,45,360,45);
$pdf->SetLineWidth(0);
					
//$pdf->Cell(270,5,'',0,1,'C');
//$pdf->Cell(270,5,'',0,1,'C');

$Y_Label_Position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_Position);
$pdf->SetX(45);
$pdf->Cell(10,5,'USER ID',1,0,'C');
$pdf->SetX(55);
$pdf->Cell(35,5,'USER LEVEL',1,0,'C');
$pdf->SetX(90);
$pdf->Cell(70,5,'USERNAME',1,0,'C');

$pdf->SetFont('Arial','',6);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(45);
$pdf->MultiCell(10,5,$column_code1,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(55);
$pdf->MultiCell(35,5,$column_code2,1,'C');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(90);
$pdf->MultiCell(70,5,$column_code3,1,'C');
$pdf->SetY($Y_Table_Position);

	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($i < $number_of_rows)
	{
  	  $pdf->SetX(45);
    	$pdf->MultiCell(115,5,'',1);
    	$i = $i +1;
	} 

$pdf->Output();

?>  
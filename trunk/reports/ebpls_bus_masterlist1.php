<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("lib/phpFunctions-inc.php");

class PDF extends FPDF
{

var $prov;
var $lgu;
var $office;

	function setLGUinfo($p='', $l='', $o='') {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
	}

//Page header
	function Header()
	{
	    //Logo
	    //$this->Image('logo_pb.png',10,8,33);
	    //Arial bold 15
	    
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'MASTERLIST OF BUSINESS ESTABLISHMENT',0,1,'C');
	$this->SetLineWidth(2);
	$this->Line(0,45,360,45);
	$this->SetLineWidth(0);

	$this->Cell(270,5,'',0,1,'C');
	$this->Cell(270,5,'',0,1,'C');
	
}

//Page footer
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    //Arial italic 8
	    $this->SetFont('Arial','I',8);
	    //Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
} // end of PDF class

$dbLink = get_db_connection();

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
//    $resulta=mysql_fetch_row($result);
$resulta[0]="Zamboanga City";
$resulta[1]="Zamboanga del Sur";
$resulta[2]="Mayor's Office";

//echo $resulta[0].$resulta[1].$resulta[2];
//return;    
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($resulta[0],$resulta[1],$resulta[2]);
$pdf->AddPage();
$pdf->AliasNbPages();

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(15,5,'PERMIT NO.',1,0,'C');
$pdf->SetX(20);
$pdf->Cell(50,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(70);
$pdf->Cell(50,5,'BUSINESS NAME',1,0,'C');
$pdf->SetX(120);
$pdf->Cell(30,5,'BUSINESS NATURE',1,0,'C');
$pdf->SetX(150);
$pdf->Cell(60,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->SetX(210);
$pdf->Cell(30,5,'CAPITAL INVESTMENT',1,0,'C');
$pdf->SetX(240);
$pdf->Cell(30,5,'GROSS SALES',1,0,'C');
$pdf->SetX(270);
$pdf->Cell(20,5,'AMOUNT PAID',1,0,'C');
$pdf->SetX(290);
$pdf->Cell(15,5,'O.R. NO.',1,0,'C');
$pdf->SetX(305);
$pdf->Cell(20,5,'PAYMENT DATE',1,0,'C');
$pdf->SetX(325);
$pdf->Cell(25,5,'OWNERSHIP TYPE',1,0,'C');

$result=mysql_query("select ' ', business_name, ' ' , business_street, ' '  from ebpls_business_enterprise") or die(mysql_error());

$number_of_rows = mysql_numrows($result);

$i = 1;
$pdf->SetY($Y_Table_Position);
while ($resulta=mysql_fetch_row($result))
{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

	$pdf->SetX(5);
	$pdf->Cell(15,5,$resulta[0],1,0,'L');
	$pdf->SetX(20);
	$pdf->Cell(50,5,$resulta[1],1,0,'L');
	$pdf->SetX(70);
	$pdf->Cell(50,5,$resulta[2],1,0,'L');
	$pdf->SetX(120);
	$pdf->Cell(30,5,$resulta[3],1,0,'L');
	$pdf->SetX(150);
	$pdf->Cell(60,5,$resulta[4],1,0,'C');
	$pdf->SetX(210);
	$pdf->Cell(30,5,$resulta[5],1,0,'C');
	$pdf->SetX(240);
	$pdf->Cell(30,5,$resulta[6],1,0,'C');
	$pdf->SetX(270);
	$pdf->Cell(20,5,$resulta[7],1,0,'C');
	$pdf->SetX(290);
	$pdf->Cell(15,5,$resulta[8],1,0,'C');
	$pdf->SetX(305);
	$pdf->Cell(20,5,$resulta[9],1,0,'C');
	$pdf->SetX(325);
	$pdf->Cell(25,5,$resulta[10],1,0,'C');
	$i++;
	$pdf->SetY($pdf->GetY()+5);
} 

//new signatories table
	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(270,5,'',0,1,'C');

$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Recommend Approval:',1,0,'L');
$pdf->Cell(173,5,'Approved:',1,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(172,5,'',1,0,'C');
$pdf->Cell(172,5,$resulta[0],1,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,'',1,0,'C');
$pdf->Cell(172,5,$resulta[2],1,0,'C');

$pdf->Output();

?>
			
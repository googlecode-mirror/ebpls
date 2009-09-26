<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");

class PDF extends FPDF
{
var $prov;
var $lgu;
var $office;
var $y0;

function AcceptPageBreak()
{
    //Method accepting or not automatic page break
    if($this->y<2)
    {
        //Set ordinate to top
        $this->SetY($this->y0);
        //Keep on page
        return false;
    }
    else
    {
        return true;
    }
}
	
//Page header
	function Header()
	{
	    //Logo
	    //$this->Image('logo_pb.png',10,8,33);
	    //Arial bold 15
	
	$this->Image('../images/ebpls_logo.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'ANNUAL COMPARATIVE REPORT',0,1,'C');
	$this->SetFont('Arial','BU',12);
	$this->Ln(22);
	
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
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
    $getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
   
$pdf=new FPDF('P','mm','Legal');
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$getlgu[0],0,1,'C');
$pdf->Cell(190,5,$getprov[0],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'BUSINESS APPLICATION',0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

//date('m/d/Y', $date_from table)
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(40,10,'Owner Name : ___________________________________________',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(40,10,'Address : _________________________________________________________________________________________',0,1,'L');
$pdf->SetX(22);
$pdf->Cell(40,10,'_________________________________________________________________________________________',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(40,10,'Age : __________',0,0,'L');
$pdf->SetX($pdf->GetX()+20);
$pdf->Cell(60,10,'Nationality : __________________',0,0,'L');
$pdf->SetX($pdf->GetX()+20);
$pdf->Cell(40,10,'TIN : __________________',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(40,10,'Business Name : _____________________________________________________',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(40,10,'Address : _________________________________________________________________________________________',0,1,'L');
$pdf->SetX(22);
$pdf->Cell(40,10,'_________________________________________________________________________________________',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(80,10,'Capital Investment : ________________________',0,0,'L');
$pdf->SetX($pdf->GetX()+20);
$pdf->Cell(80,10,'Gross Receipts : ________________________',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(80,10,'Type of Ownership : ________________________',0,0,'L');
$pdf->SetX($pdf->GetX()+20);
$pdf->Cell(80,10,'Exemption : ________________________',0,1,'L');

$pdf->Output();



?>

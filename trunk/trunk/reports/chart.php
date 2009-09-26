<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$criteria="$brgy_name $owner_last $trans $cap_inv $last_yr";
class PDF extends FPDF
{
var $prov;
var $lgu;
var $office;
var $y0;

	function setLGUinfo($p='', $l='', $o='') {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
//		echo 'setLGUinfo'.$this->prov;
	}

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
	$this->Cell(340,5,'LIST OF BUSINESS ESTABLISHMENT',0,1,'C');
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


	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
    
	
	

//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($resulta[0],$resulta[1],$resulta[2]);
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',10);
$pdf->SetY(40);
$pdf->SetX(10);
$pdf->Cell(25,5,'SEARCH CRITERIA: ',0,0,'L');
$pdf->SetX(50);
$pdf->Cell(100,5,$criteria,0,1,'L');

$Y_Label_position = 50;
$Y_Table_Position = 55;


$pdf->SetY(67);
$pdf->SetX(50);
$pdf->Cell(25,5,'Q1',0,0,'L');

$pdf->SetY(77);
$pdf->SetX(50);
$pdf->Cell(25,5,'Q2',0,0,'L');

$pdf->SetY(87);
$pdf->SetX(50);
$pdf->Cell(25,5,'Q3',0,0,'L');

$pdf->SetY(97);
$pdf->SetX(50);
$pdf->Cell(25,5,'Q4',0,0,'L');

$pdf->SetY(107);
$pdf->SetX(53);
$pdf->Cell(25,5,'0',0,0,'L');

$pdf->SetY(107);
$pdf->SetX(63);
$pdf->Cell(25,5,'5',0,0,'L');

$pdf->SetY(107);
$pdf->SetX(73);
$pdf->Cell(25,5,'10',0,0,'L');

$pdf->SetY(107);
$pdf->SetX(83);
$pdf->Cell(25,5,'15',0,0,'L');

$pdf->SetY(107);
$pdf->SetX(93);
$pdf->Cell(25,5,'20',0,0,'L');

$pdf->SetY(107);
$pdf->SetX(103);
$pdf->Cell(25,5,'25',0,0,'L');

$pdf->SetY(120);
$pdf->SetX(53);
$pdf->Cell(25,5,'1:100,000',0,0,'L');


$pdf->SetLineWidth(2);
$pdf->SetDrawColor(255,0,0);
$pdf->Line(60, 70, 70, 70);

$pdf->SetY(67);
$pdf->SetX(70 + 3);
$pdf->Cell(25,5,'(total tax collected)',0,0,'L');

$pdf->SetDrawColor(0,255,0);
$pdf->Line(60, 80, 80, 80);

$pdf->SetY(77);
$pdf->SetX(80 + 3);
$pdf->Cell(25,5,'(total tax collected)',0,0,'L');

$pdf->SetDrawColor(0,0,255);
$pdf->Line(60, 90, 90, 90);

$pdf->SetY(87);
$pdf->SetX(90 + 3);
$pdf->Cell(25,5,'(total tax collected)',0,0,'L');


$pdf->SetDrawColor(255,255,0);
$pdf->Line(60, 100, 100, 100);

$pdf->SetY(97);
$pdf->SetX(100 + 3);
$pdf->Cell(25,5,'(total tax collected)',0,0,'L');



$pdf->SetLineWidth(1);
$pdf->SetDrawColor(0,0,0);
$pdf->Line(57, 60, 57, 105);
$pdf->Line(57, 105, 150, 105);


$pdf->Output();

?>


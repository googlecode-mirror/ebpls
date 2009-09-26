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
	
	$this->Image('peoplesmall.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'LISTING OF TRICYCLE BY BARANGAY',0,1,'C');
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

$dbLink = get_db_connection();

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
    
//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($resulta[0],$resulta[1],$resulta[2]);
$pdf->AddPage();
$pdf->AliasNbPages();

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(15,5,'SEQ. NO.',1,0,'C');
$pdf->SetX(20);
$pdf->Cell(35,5,'PERMIT NO.',1,0,'C');
$pdf->SetX(55);
$pdf->Cell(80,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(135);
$pdf->Cell(50,5,'BARANGAY',1,0,'C');
$pdf->SetX(185);
$pdf->Cell(40,5,'MOTOR MODEL',1,0,'C');
$pdf->SetX(225,0);
$pdf->Cell(40,5,'MOTOR NUMBER',1,0,'C');
$pdf->SetX(265);
$pdf->Cell(40,5,'BODY NUMBER',1,0,'C');
$pdf->SetX(305);
$pdf->Cell(40,5,'PLATE NUMBER',1,0,'C');

	$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	b.motorized_motor_model, b.motorized_motor_no, b.motorized_chassis_no, b.motorized_plate_no, b.motorized_body_no, 
	b.body_color, b.lto_number, b.route, b.linetype, b.cr_number, c.motorized_operator_permit_id, 
	a.owner_barangay_code
	from ebpls_mtop_owner a, ebpls_motorized_vehicles b, ebpls_motorized_operator_permit c
	where a.owner_id=b.motorized_operator_id and a.owner_id=c.owner_id order by a.owner_barangay_code asc") 
	or die(mysql_error());
	

	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($resulta=mysql_fetch_row($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(15,5,$i,1,0,'L');
		$pdf->SetX(20);
		$pdf->Cell(35,5,$resulta[11],1,0,'L'); //permit #
		$pdf->SetX(55);
		$pdf->Cell(80,5,$resulta[0],1,0,'L');  	//owner
		$pdf->SetX(135);
		$pdf->Cell(50,5,$resulta[12],1,0,'L');	//brgy
		$pdf->SetX(185);
		$pdf->Cell(40,5,$resulta[1],1,0,'C');	//motor model
		$pdf->SetX(225);
		$pdf->Cell(40,5,$resulta[2],1,0,'C');	//motor #
		$pdf->SetX(265);
		$pdf->Cell(40,5,$resulta[5],1,0,'C');	//body #
		$pdf->SetX(305);
		$pdf->Cell(40,5,$resulta[4],1,0,'C');	//plate #
		$i++;
		$pdf->SetY($pdf->GetY()+5);
	} 
	
/*
$i = 1;
$pdf->SetY($Y_Table_Position);
while ($i < $number_of_rows)
{
    $pdf->SetX(1);
    $pdf->MultiCell(349,5,$i,1);
    $i = $i +1;   
} */

//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Recommend Approval:',1,0,'L');
$pdf->Cell(172,5,'Approved:',1,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[2],1,0,'C');

$report_desc='Masterlist of Motor Vehicle By Barangay';
include 'report_signatories_footer1.php';

$pdf->Output();

?>



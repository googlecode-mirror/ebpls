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
	
		$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'YEARLY LISTING OF REGISTRANTS',0,1,'C');
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
$pdf->Cell(10,5,'SEQ. NO.',1,0,'C');
$pdf->Cell(100,5,'NAME OF OWNER',1,0,'C');
$pdf->Cell(80,5,'BUSINESS NAME',1,0,'C');
$pdf->Cell(50,5,'BUSINESS NATURE',1,0,'C');
$pdf->Cell(100,5,'BUSINESS ADDRESS',1,0,'C');

//	$result=mysql_query("select ' ', business_name, ' ' , business_street, ' '  from ebpls_business_enterprise") or die(mysql_error());

	$result = mysql_query("select a.business_name, concat(a.business_lot_no, ' ', a.business_street, ' ', 
	a.business_city_code, ' ', a.business_province_code, ' ', a.business_zip_code), a.employee_male, a.employee_female,
	concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name), c.business_permit_id, c.active,
	d.bus_nature, d.cap_inv, d.last_yr
	from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c, 
	tempbusnature d, ebpls_transaction_payment_or e
	where b.owner_id = a.owner_id and a.business_id = c.business_id and a.business_id = d.business_id 
	and c.transaction = 'NEW'") 
	or die(mysql_error());
	$number_of_rows = mysql_numrows($result);
	
	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($resulta=mysql_fetch_row($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(10,5,$i,1,0,'L');
		$pdf->Cell(100,5,$resulta[0],1,0,'L');
		$pdf->Cell(80,5,$resulta[4],1,0,'L');
		$pdf->Cell(50,5,$resulta[7],1,0,'L');
		$pdf->Cell(100,5,$resulta[1],1,0,'L');
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

$report_desc='List of Registrants (Yearly)';
include 'report_signatories_footer1.php';

$pdf->Output();

?>

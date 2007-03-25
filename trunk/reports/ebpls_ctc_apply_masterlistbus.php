<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
$criteria=$brgy_name.$owner_last.$bus_name.$trans.$bus_nature.$cap_inv.$last_yr;
class PDF extends FPDF
{
var $prov;
var $lgu;
var $office;
var $df;
var $dt;
var $y0;

	function setLGUinfo($p='', $l='', $o='') {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
//		echo 'setLGUinfo'.$this->prov;
	}
	function setDateRange($x='', $y='') {
		$this->df = $x;
		$this->dt = $y;
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
	$this->Cell(340,5,$this->lgu,0,1,'C');
	$this->Cell(340,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'CORPORATE CTC APPLICATION MASTERLIST',0,1,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','',12);
	$this->Cell(340,5,'FROM '.$this->df.' TO '.$this->dt,0,1,'C');
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

include'../includes/variables.php';

include_once("../lib/multidbconnection.php");

$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

        $result=@mysql_query("select lguname, lguprovince from ebpls_buss_preference")
        or die(mysql_error());
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'Office of the Treasurer');
$pdf->AddPage();
$pdf->AliasNbPages();

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(50,5,'BUSINESS NAME',1,0,'C');
$pdf->Cell(120,5,'ADDRESS',1,0,'C');
$pdf->Cell(50,5,'ASSESSED VALUE OF REAL PROPERTY',1,0,'C');
$pdf->Cell(50,5,'GROSS RECEIPTS',1,0,'C');
$pdf->Cell(30,5,'TOTAL PAID',1,0,'C');
$date_from = str_replace("/", "", $date_from);
$idate = strtotime($date_from);
//$idate = $idate - (60*60*24);
$date_from = date('Y-m-d', $idate);
$date_to = str_replace("/", "", $date_to);
$xdate = strtotime($date_to);
$xdate = $xdate + (60*60*24);
$date_to = date('Y-m-d', $xdate);

	$result=mysql_query("select ctc_company, ctc_company_address, ctc_tax_due, ctc_tax_interest, ctc_additional_tax1, ctc_additional_tax2 from ebpls_ctc_business where ctc_date_issued between '$date_from' and '$date_to' order by ctc_company asc") 
    or die(mysql_error());
       
    //$resulta=mysql_fetch_row($result);
    
    $i = 1;
	$pdf->SetY($Y_Table_Position);
	$total_ = 0;
	while ($resulta=mysql_fetch_row($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(50,5,$resulta[0],1,0,'L');  	//ctc number
		$pdf->Cell(120,5,$resulta[1],1,0,'L');	//company name
		$pdf->Cell(50,5,number_format($resulta[4],2),1,0,'R');	//address
		$pdf->Cell(50,5,number_format($resulta[5],2),1,0,'R');	//date issued
		$pdf->Cell(30,5,number_format($resulta[2]+$resulta[3],2),1,0,'R');	//incorpotation date
		$total_ = $total_ + $resulta[4] + $resulta[5];	
		$i++;
		$pdf->SetY($pdf->GetY()+5);
		$total_ = $total_ + $resulta[2] + $resulta[3];
	}

	$result1=mysql_query("select sum(ctc_tax_due), sum(ctc_tax_interest) from ebpls_ctc_business where ctc_date_issued between '$datefrom' and '$date_to'") 
	or die(mysql_error());
    $resulta1=mysql_fetch_row($result1);
	$result2=mysql_query("select ctc_tax_due from ebpls_ctc_business where ctc_date_issued between '$datefrom' and '$date_to'") 
	or die(mysql_error());
    $resulta2=mysql_num_rows($result2);
        
    $pdf->SetX(5);
	$pdf->Cell(220,5,'',0,0,'L');	
	$pdf->Cell(50,5,'TOTAL:',0,0,'C');	
	$pdf->Cell(30,5,number_format($total_,2),1,1,'R');
	$pdf->SetX(5);
	$pdf->Cell(220,5,'',0,0,'L');	
	$pdf->Cell(50,5,'ITEM COUNT:',0,0,'C');	
	$pdf->Cell(30,5,$resulta2,0,1,'R');
    
	
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

          
$pdf->Cell(270,15,'',0,1,'C');
$pdf->Cell(270,15,'',0,1,'C');

$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(252,5,'Prepared By:',0,0,'L');
$pdf->Cell(173,5,'Noted By :',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usern'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='CTC Business Application Masterlist' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(252,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(173,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(252,5,'',0,0,'C');
$pdf->Cell(173,5,$getsignatories1[gs_pos],0,1,'L');
$report_desc='CTC Business Application Masterlist';
//include 'report_signatories_footer1.php';

$pdf->Output();

?>


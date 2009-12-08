<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

$criteria=$brgy_name.$owner_last.$bus_name.$trans.$bus_nature.$cap_inv.$last_yr;
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
/*--------------------------------------------------------------
FREDERICK -> changed the cell width of the ff: from 340 to 195:
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'COLLECTIONS SUMMARY',0,1,'C');

Additionally:               */
//changed letter case
	$this->Cell(195,5,'Republic of the Philippines',0,1,'C');

//change $this->prov and $this->lgu    SEE: change made on lines 121 & 122
//added the words "Province of & MUNICIPALITY OF"
	$this->Cell(195,5,'Province of '.$this->prov,0,1,'C');
	$this->Cell(195,5,'MUNICIPALITY OF '.strtoupper($this->lgu),0,2,'C');
//added blank space
	$this->Cell(195,5,'',0,1,'C');

	$this->SetFont('Arial','B',14);
//changed to ALL CAPS
	$this->Cell(195,5,strtoupper($this->office),0,2,'C');
	$this->Cell(195,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(195,5,'COLLECTIONS SUMMARY',0,1,'C');
	$this->SetFont('Arial','BU',12);
	$this->Ln(22);
	
}
//-------------------------------------------------------------------------
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

//$dbLink = get_db_connection();
$e = strrpos($owner_last,"-");//$owner_last is date
$l =strlen($owner_last);
$dateprev = substr($owner_last, $l-$e);
$dateprev = $dateprev;
$datenext = $dateprev + 1;
$lgu=mysql_query("select * from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_array($lgu);


	$result=@mysql_query("select * from ebpls_buss_tfo where taxfeetype='$taxtype'") 
	or die(mysql_error());
//    $resulta=mysql_fetch_array($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[lguname]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[lguprovince]'");
$getprov = @mysql_fetch_row($getprov);
	
//$pdf=new FPDF('L','mm','Legal');
/*-------------------------------------------------------------------
FREDERICK -> change Paper Orientation from Landscape to Portrait
$pdf=new PDF('L','mm','Legal'); */
$pdf=new PDF('P','mm','Legal');
//change arrangement of $getlgu & $getprov   SEE: function setLGUinfo on line 19
//$pdf->setLGUinfo($getlgu[0],$getprov[0],$resulat[2]);
$pdf->setLGUinfo($getprov[0],$getlgu[0],'Office of the Treasurer');
//---------------------------------------------------------------------
$pdf->AddPage();
$pdf->AliasNbPages();

$Y_Label_position = 50;
$Y_Table_Position = 55;
$resultpp=@mysql_query("select * from ebpls_buss_taxfeetype where taxfeetype='$taxtype'")
        or die(mysql_error());
$gettype = @mysql_fetch_array($resultpp);
//header
$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
/*====================================================
FREDERICK -> change X Coordinate from 5 to 55:
/$pdf->SetX(5); */
$pdf->SetX(55);
//====================================================
$pdf->Cell(50,5,$gettype[typedesc],1,0,'C');
/*====================================================
FREDERICK -> removed this X Coordinate
/$pdf->SetX(55);
=====================================================*/
$pdf->Cell(55,5,'COLLECTION',1,1,'C');

//second line
$totalamount = 0;
while ($getfees=mysql_fetch_array($result)) {
$Yx++;
$Yxx=$Yx*5;
$pdf->SetY($Y_Label_position+$Yxx);
/*=========================================
FREDERICK -> change X coordinate
/$pdf->SetX(5); */
$pdf->SetX(55);
//=========================================
$pdf->Cell(50,5,$getfees[tfodesc],1,0,'L');
$getamount = mysql_query("select sum(compval) from tempassess where tfoid='$getfees[tfoid]' and date_create between '$date_from' and '$date_to' and active='1'");
$getamount = mysql_fetch_row($getamount);
/*=========================================
FREDERICK -> removed this X Coordinate
$pdf->SetX(55); */
//=========================================
$pdf->Cell(55,5,number_format($getamount[0],2),1,0,'R');
$totalamount = $totalamount + $getamount[0];
}
$totalamount = number_format($totalamount,2);
/*=========================================
FREDERICK -> change addtl Y coordinate from 10 to 6
$pdf->SetY($Y_Label_position+$Yxx+10); */
$pdf->SetY($Y_Label_position+$Yxx+6);
/* changed this X Coordinate from 5 to 55
$pdf->SetX(5); */
$pdf->SetX(55);
//=========================================
$pdf->Cell(50,5,'Total',1,0,'L');
/*=========================================
FREDERICK -> removed this X coordinate
$pdf->SetX(55);
===========================================*/
$pdf->Cell(55,5,$totalamount,1,0,'R');
/*=========================================
FREDERICK -> change cell width of the ff: from 270 to 195          
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C'); */
$pdf->Cell(195,5,'',0,1,'C');
$pdf->Cell(195,5,'',0,1,'C');
//$pdf->SetY(-18);
/* change X coordinate from 5to 25
$pdf->SetX(5); */
$pdf->SetX(25);
//===========================================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'Prepared By :',0,0,'L');

$pdf->SetX(125);
$pdf->Cell(50,5,'Noted By :',0,1,'L');
/*==========================================
FREDERICK -> change cell width of the ff: from 270 to 195    
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C'); */
$pdf->Cell(195,5,'',0,1,'C');
$pdf->Cell(195,5,'',0,1,'C');
//===========================================
$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Comparative Annual Report' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
/*====================================================
FREDERICK -> change X coordinate from 5 to 25
$pdf->SetX(5); */
$pdf->SetX(25);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(75,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
//add this X coordinate:
$pdf->SetX(125);
$pdf->Cell(75,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
$pdf->SetX(125);
//$pdf->Cell(75,5,'',0,0,'C');
$pdf->Cell(75,5,$getsignatories1[gs_pos],0,1,'L');
//=====================================================
//$pdf->SetFont('Arial','B',10);
//$pdf->Cell(172,5,'Recommend Approval:',1,0,'L');
//$pdf->Cell(172,5,'Approved:',1,1,'L');

//$pdf->Cell(270,5,'',0,1,'C');
//$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[2],1,0,'C');

$report_desc='Collection Summary';
include '../report_signatories_footer1.php';

$pdf->Output();

?>

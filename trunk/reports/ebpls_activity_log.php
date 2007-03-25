<?php
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");

//echo '$dbtype='.$dbtype.$connecttype.$dbhost.$dbuser.$dbpass.$dbname.'<br />';
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
	$this->Cell(340,5,$this->lgu,0,1,'C');
	$this->Cell(340,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'ACTIVITY LOG',0,1,'C');
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


	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference")
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);

if ($cap_inv2 == "" || $cap_inv2 == 0) {
	$cap_inv2 = 9999999999999;
}
//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'');
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',10);
$pdf->SetY(40);
$pdf->SetX(10);
$pdf->Cell(25,5,'',0,0,'L');
$pdf->SetX(50);
$pdf->Cell(100,5,'',0,1,'L');

$Y_Label_position = 50;
$Y_Table_Position = 55;
$pdf->SetFont('Arial','B',6);
$pdf->SetY(50);
$pdf->SetX(5);
$pdf->Cell(10,5,'USER ID',1,0,'C');
$pdf->SetX(15);
$pdf->Cell(30,5,'USER LEVEL',1,0,'C');
$pdf->SetX(45);
$pdf->Cell(20,5,'USERNAME',1,0,'C');
$pdf->SetX(65);
$pdf->Cell(20,5,'REMOTE IP',1,0,'C');
$pdf->SetX(85);
$pdf->Cell(25,5,'DATE UPDATED',1,0,'C');
$pdf->SetX(110);
$pdf->Cell(210,5,'QUERY STRING',1,1,'C');
$date_from = str_replace("/", "", $date_from);
$idate = strtotime($date_from);
//$idate = $idate - (60*60*24);
$date_from = date('Y-m-d', $idate);
$date_to = str_replace("/", "", $date_to);
$xdate = strtotime($date_to);
$xdate = $xdate + (60*60*24);
$date_to = date('Y-m-d', $xdate);
$result=mysql_query("select userid, userlevel, username, action, remoteip, lastupdated,querystring from ebpls_activity_log where
          username like '$owner_last%' and lastupdated between '$date_from' and '$date_to'") or die(mysql_error());

          $number_of_rows = mysql_numrows($result);
          while($resulta=mysql_fetch_row($result))
					{
						if ($resulta[1] == "" || $resulta[1] == "0") {
							$nUserLevel = "CTC Officer";
						} elseif ($resulta[1] == "1") {
							$nUserLevel = "Application Officer";
						} elseif ($resulta[1] == "2") {
							$nUserLevel = "Assessment Officer";
						} elseif ($resulta[1] == "3") {
							$nUserLevel = "Payment Officer";
						} elseif ($resulta[1] == "4") {
							$nUserLevel = "Approving Officer";
						} elseif ($resulta[1] == "5") {
							$nUserLevel = "Releasing Officer";
						} elseif ($resulta[1] == "6") {
							$nUserLevel = "eBPLS Administrator";
						} elseif ($resulta[1] == "7") {
							$nUserLevel = "Root Administrator";
						}

$pdf->SetFont('Arial','',6);
$pdf->SetX(5);
$pdf->Cell(10,5,$resulta[0],1,0,'C');
$pdf->SetX(15);
$pdf->Cell(30,5,$nUserLevel,1,0,'C');
$pdf->SetX(45);
$pdf->Cell(20,5,$resulta[2],1,0,'C');
$pdf->SetX(65);
$pdf->Cell(20,5,$resulta[4],1,0,'C');
$pdf->SetX(85);
$pdf->Cell(25,5,$resulta[5],1,0,'C');
$pdf->SetX(110);
$pdf->Cell(210,5,$resulta[6],1,1,'L');
					}

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Prepared By :',0,0,'L');
$pdf->Cell(172,5,'Noted By :',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Blacklisted Business Establishment' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,'',0,0,'C');
$pdf->Cell(172,5,$getsignatories1[gs_pos],0,1,'L');

$report_desc='Business Establishment';
//include '../report_signatories_footer1.php';

$pdf->Output();

?>


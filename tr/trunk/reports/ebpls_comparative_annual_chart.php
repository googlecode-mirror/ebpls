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
	$this->Cell(340,5,'COMPARATIVE ANNUAL REPORT (CHART)',0,1,'C');
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

$e = strrpos($owner_last,"-");//$owner_last is date
$l =strlen($owner_last);
$dateprev = substr($owner_last,0,4);
$dateprev = $dateprev;
$datenext = $dateprev + 1;

    //taxes
        $sumtax1 = mysql_query("select sum(taxes) from comparative_statement where for_year='$dateprev' and
                                                        paid = 0");
        $sumtax1 = mysql_fetch_row($sumtax1);
                                                                                                                             
        $sumtax2 = mysql_query("select sum(taxes) from comparative_statement where for_year='$datenext' and
                                                        paid = 0");
        $sumtax2 = mysql_fetch_row($sumtax2);

	
	$graphtax1 = $sumtax1[0] / 100000;

	$graphtax2 = $sumtax2[0] / 100000;
                                                                                                                             
        //fees
        $sumfee1 = mysql_query("select sum(fees) from comparative_statement where for_year='$dateprev' and
                                                        paid = 0");
        $sumfee1 = mysql_fetch_row($sumfee1);
                                                                                                                             
        $sumfee2 = mysql_query("select sum(fees) from comparative_statement where for_year='$datenext' and
                                                        paid = 0");
        $sumfee2 = mysql_fetch_row($sumfee2);
                                                                                                                             
        $graphfee1 = $sumfee1[0] / 100000;

        $graphfee2 = $sumfee2[0] / 100000;


	//interest
        $sumint1 = mysql_query("select sum(surcharge) from comparative_statement where for_year='$dateprev' and
                                                        paid = 0");
        $sumint1 = mysql_fetch_row($sumint1);
                                                                                                                             
        $sumint2 = mysql_query("select sum(surcharge) from comparative_statement where for_year='$datenext' and
                                                        paid = 0");
        $sumint2 = mysql_fetch_row($sumint2);
                                                                                                                             
        $graphint1 = $sumint1[0] / 100000;

        $graphint2 = $sumint2[0] / 100000;


	//penalty
        $sumpen1 = mysql_query("select sum(penalty) from comparative_statement where for_year='$dateprev' and
                                                        paid = 0");
        $sumpen1 = mysql_fetch_row($sumpen1);
                                                                                                                             
        $sumpen2 = mysql_query("select sum(penalty) from comparative_statement where for_year='$datenext' and
                                                        paid = 0");
        $sumpen2 = mysql_fetch_row($sumpen2);
                                                                                                                             
	$graphpen1 = $sumpen1[0] / 100000;

        $graphpen2 = $sumpen2[0] / 100000;


        //backtax
        $sumbak1 = mysql_query("select sum(backtax) from comparative_statement where for_year='$dateprev' and
                                                        paid = 0");
        $sumbak1 = mysql_fetch_row($sumbak1);
                                                                                                                             
        $sumbak2 = mysql_query("select sum(backtax) from comparative_statement where for_year='$datenext' and
                                                        paid = 0");
        $sumbak2 = mysql_fetch_row($sumbak2);
                                                                                                                             
	$graphbak1 = $sumbak1[0] / 100000;

        $graphbak2 = $sumbak2[0] / 100000;


$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov); 
	
	

//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($getlgu[0],$getprov[0],$resulta[2]);
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


$pdf->SetY(67);
$pdf->SetX(50);
$pdf->Cell(25,5,'Taxes',0,0,'L');

$pdf->SetY(87);
$pdf->SetX(50);
$pdf->Cell(25,5,'Fees',0,0,'L');

$pdf->SetY(107);
$pdf->SetX(50);
$pdf->Cell(25,5,'Surcharge',0,0,'L');

$pdf->SetY(127);
$pdf->SetX(50);
$pdf->Cell(25,5,'Interest',0,0,'L');

$pdf->SetY(147);
$pdf->SetX(50);
$pdf->Cell(25,5,'Backtax',0,0,'L');
                                                                                                                             
$pdf->SetY(167);
$pdf->SetX(80);
$pdf->Cell(25,5,'0',0,0,'L');

$pdf->SetY(167);
$pdf->SetX(90);
$pdf->Cell(25,5,'5',0,0,'L');

$pdf->SetY(167);
$pdf->SetX(100);
$pdf->Cell(25,5,'10',0,0,'L');

$pdf->SetY(167);
$pdf->SetX(110);
$pdf->Cell(25,5,'15',0,0,'L');

$pdf->SetY(167);
$pdf->SetX(120);
$pdf->Cell(25,5,'20',0,0,'L');

$pdf->SetY(167);
$pdf->SetX(130);
$pdf->Cell(25,5,'25',0,0,'L');

$pdf->SetY(167);
$pdf->SetX(140);
$pdf->Cell(25,5,'30',0,0,'L');

$pdf->SetY(167);
$pdf->SetX(150);
$pdf->Cell(25,5,'35',0,0,'L');
                                                                                                                             
$pdf->SetY(167);
$pdf->SetX(160);
$pdf->Cell(25,5,'40',0,0,'L');
                                                                                                                             
$pdf->SetY(167);
$pdf->SetX(170);
$pdf->Cell(25,5,'45',0,0,'L');
                                                                                                                             
$pdf->SetY(167);
$pdf->SetX(180);
$pdf->Cell(25,5,'50',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(190);
$pdf->Cell(25,5,'55',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(200);
$pdf->Cell(25,5,'60',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(210);
$pdf->Cell(25,5,'65',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(220);
$pdf->Cell(25,5,'70',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(230);
$pdf->Cell(25,5,'75',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(240);
$pdf->Cell(25,5,'80',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(250);
$pdf->Cell(25,5,'85',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(260);
$pdf->Cell(25,5,'90',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(270);
$pdf->Cell(25,5,'95',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(280);
$pdf->Cell(25,5,'100',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(290);
$pdf->Cell(25,5,'105',0,0,'L');


$pdf->SetY(167);
$pdf->SetX(300);
$pdf->Cell(25,5,'110',0,0,'L');

$pdf->SetY(180);
$pdf->SetX(80);
$pdf->Cell(25,5,'1:200,000',0,0,'L');

if ($graphtax1 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(255,0,0);
	$pdf->Line(80, 70, 80 + $graphtax1, 70);
}

$pdf->SetY(67);
$pdf->SetX(80 + $graphtax1 + 3);
$pdf->Cell(25,5,'Year '.$dateprev.' ('. number_format($sumtax1[0],2).')',0,0,'L');

if ($graphtax2 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(255,100,0);
	$pdf->Line(80, 75, 80 + $graphtax2, 75);
}
                                                                                                                             
$pdf->SetY(72);
$pdf->SetX(80 + $graphtax2 + 3);
$pdf->Cell(25,5,'Year '.$datenext.' ('. number_format($sumtax2[0],2).')',0,0,'L');
        
if ($graphfee1 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(0,255,0);
	$pdf->Line(80, 90, 80 + $graphfee1, 90);
}

$pdf->SetY(87);
$pdf->SetX(80 + $graphfee1 + 3);
$pdf->Cell(25,5,'Year '.$dateprev.' ('. number_format($sumfee1[0],2).')',0,0,'L');

if ($graphfee2 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(100,255,0);
	$pdf->Line(80, 95, 80 + $graphfee2, 95);
}
                                                                                                                             
$pdf->SetY(92);
$pdf->SetX(80 + $graphfee2 + 3);
$pdf->Cell(25,5,'Year '.$datenext.' ('. number_format($sumfee2[0],2).')',0,0,'L');

if ($graphpen1 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(0,0,255);
	$pdf->Line(80, 110, 80 + $graphpen1, 110);
}

$pdf->SetY(107);
$pdf->SetX(80 + $graphpen1 + 3);
$pdf->Cell(25,5,'Year '.$dateprev.' ('. number_format($sumpen1[0],2).')',0,0,'L');

if ($graphpen2 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(0,100,255);
	$pdf->Line(80, 115, 80 + $graphpen2, 115);
}
                                                                                                                             
$pdf->SetY(112);
$pdf->SetX(80 + $graphpen2 + 3);
$pdf->Cell(25,5,'Year '.$datenext.' ('. number_format($sumpen2[0],2).')',0,0,'L');
                                                                                                                             
if ($graphint1 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(155,155,155);
	$pdf->Line(80, 130, 80 + $graphint1, 130);
}

$pdf->SetY(127);
$pdf->SetX(80 + $graphint1 + 3);
$pdf->Cell(25,5,'Year '.$dateprev.' ('. number_format($sumint1[0],2).')',0,0,'L');

if ($graphint2 > 0) {
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(55,155,55);
	$pdf->Line(80, 135, 80 + $graphint2, 135);
}
                                                                                                                             
$pdf->SetY(132);
$pdf->SetX(80 + $graphint2 + 3);
$pdf->Cell(25,5,'Year '.$datenext.' ('. number_format($sumint2[0],2).')',0,0,'L');
 
if ($graphbak1 > 0) { 
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(255,255,0);
	$pdf->Line(80, 150, 80 + $graphbak1, 150);
}
                                                                                                                             
$pdf->SetY(147);
$pdf->SetX(80 + $graphbak1 + 3);
$pdf->Cell(25,5,'Year '.$dateprev.' ('. number_format($sumbak1[0],2).')',0,0,'L');
 
 if ($graphbak2 > 0) { 
	$pdf->SetLineWidth(2);
	$pdf->SetDrawColor(255,55,250);
	$pdf->Line(80, 155, 80 + $graphbak2, 155);
}
                                                                                                                             
$pdf->SetY(152);
$pdf->SetX(80 + $graphbak2 + 3);
$pdf->Cell(25,5,'Year '.$datenext.' ('. number_format($sumbak2[0],2).')',0,0,'L');
                                                                                                                             
                                                                                                                             
$pdf->SetLineWidth(1);
$pdf->SetDrawColor(0,0,0);
$pdf->Line(77, 60, 77, 160);
$pdf->Line(77, 160, 300, 160);


$pdf->Output();

?>


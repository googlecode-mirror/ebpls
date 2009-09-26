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
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'QUARTERLY COMPARATIVE REPORT',0,1,'C');
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

//$dbLink = get_db_connection();
$e = strrpos($owner_last,"-");//$owner_last is date
$l =strlen($owner_last);
$dateprev = substr($owner_last, 0,4);
$dateprev = $dateprev;
$datenext = $dateprev + 1;

$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
$resulta=mysql_fetch_row($result);
   
    //taxes
if ($iQrt == '1') {
	$nMonth1 = "01";
	$MonthDisplay1 = "January";
	$nMonth2 = "02";
	$MonthDisplay2 = "February";
	$nMonth3 = "03";
	$MonthDisplay3 = "March";
} elseif ($iQrt == '2') {
	$nMonth1 = "04";
	$MonthDisplay1 = "April";
	$nMonth2 = "05";
	$MonthDisplay2 = "May";
	$nMonth3 = "06";
	$MonthDisplay3 = "June";
} elseif ($iQrt == '3') {
	$nMonth1 = "07";
	$MonthDisplay1 = "July";
	$nMonth2 = "08";
	$MonthDisplay2 = "August";
	$nMonth3 = "09";
	$MonthDisplay3  = "September";
} elseif ($iQrt == '4') {
	$nMonth1 = "10";
	$MonthDisplay1 = "October";
	$nMonth2 = "11";
	$MonthDisplay2 = "November";
	$nMonth3 = "12";
	$MonthDisplay3 = "December";
}
$sumtaxq1 = mysql_query("select sum(taxes) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth1'");
$sumtaxq1 = mysql_fetch_row($sumtaxq1);						

$sumtaxq2 = mysql_query("select sum(taxes) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth2'");
$sumtaxq2 = mysql_fetch_row($sumtaxq2);						

$sumtaxq3 = mysql_query("select sum(taxes) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth3'");
$sumtaxq3 = mysql_fetch_row($sumtaxq3);						

$sumtaxq1n = mysql_query("select sum(taxes) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth1'");
$sumtaxq1n = mysql_fetch_row($sumtaxq1n);						

$sumtaxq2n = mysql_query("select sum(taxes) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth2'");
$sumtaxq2n = mysql_fetch_row($sumtaxq2n);						

$sumtaxq3n = mysql_query("select sum(taxes) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth3'");
$sumtaxq3n = mysql_fetch_row($sumtaxq3n);						

//fees
$sumfeeq1 = mysql_query("select sum(fees) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth1'");
$sumfeeq1 = mysql_fetch_row($sumfeeq1);						

$sumfeeq2 = mysql_query("select sum(fees) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth2'");
$sumfeeq2 = mysql_fetch_row($sumfeeq2);						

$sumfeeq3 = mysql_query("select sum(fees) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth3'");
$sumfeeq3 = mysql_fetch_row($sumfeeq3);						

$sumfeeq1n = mysql_query("select sum(fees) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth1'");
$sumfeeq1n = mysql_fetch_row($sumfeeq1n);						

$sumfeeq2n = mysql_query("select sum(fees) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth2'");
$sumfeeq2n = mysql_fetch_row($sumfeeq2n);						

$sumfeeq3n = mysql_query("select sum(fees) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth3'");
$sumfeeq3n = mysql_fetch_row($sumfeeq3n);						

//penalty
$sumpenq1 = mysql_query("select sum(penalty) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth1'");
$sumpenq1 = mysql_fetch_row($sumpenq1);						

$sumpenq2 = mysql_query("select sum(penalty) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth2'");
$sumpenq2 = mysql_fetch_row($sumpenq2);						

$sumpenq3 = mysql_query("select sum(penalty) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth3'");
$sumpenq3 = mysql_fetch_row($sumpenq3);						

$sumpenq1n = mysql_query("select sum(penalty) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth1'");
$sumpenq1n = mysql_fetch_row($sumpenq1n);						

$sumpenq2n = mysql_query("select sum(penalty) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth2'");
$sumpenq2n = mysql_fetch_row($sumpenq2n);						

$sumpenq3n = mysql_query("select sum(penalty) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth3'");
$sumpenq3n = mysql_fetch_row($sumpenq3n);						

//surcharge
$sumintq1 = mysql_query("select sum(surcharge) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth1'");
$sumintq1 = mysql_fetch_row($sumintq1);						

$sumintq2 = mysql_query("select sum(surcharge) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth2'");
$sumintq2 = mysql_fetch_row($sumintq2);						

$sumintq3 = mysql_query("select sum(surcharge) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth3'");
$sumintq3 = mysql_fetch_row($sumintq3);						

$sumintq1n = mysql_query("select sum(surcharge) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth1'");
$sumintq1n = mysql_fetch_row($sumintq1n);						

$sumintq2n = mysql_query("select sum(surcharge) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth2'");
$sumintq2n = mysql_fetch_row($sumintq2n);						

$sumintq3n = mysql_query("select sum(surcharge) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth3'");
$sumintq3n = mysql_fetch_row($sumintq3n);						

//backtax
$sumbakq1 = mysql_query("select sum(backtax) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth1'");
$sumbakq1 = mysql_fetch_row($sumbakq1);						

$sumbakq2 = mysql_query("select sum(backtax) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth2'");
$sumbakq2 = mysql_fetch_row($sumbakq2);						

$sumbakq3 = mysql_query("select sum(backtax) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month = '$nMonth3'");
$sumbakq3 = mysql_fetch_row($sumbakq3);						

$sumbakq1n = mysql_query("select sum(backtax) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth1'");
$sumbakq1n = mysql_fetch_row($sumbakq1n);						

$sumbakq2n = mysql_query("select sum(backtax) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth2'");
$sumbakq2n = mysql_fetch_row($sumbakq2n);						

$sumbakq3n = mysql_query("select sum(backtax) from comparative_statement where for_year='$datenext' and
						paid = 0 and month = '$nMonth3'");
$sumbakq3n = mysql_fetch_row($sumbakq3n);						


//total
$totalq1 = $sumtaxq1[0]+$sumfeeq1[0]+$sumpenq1[0]+$sumintq1[0]+$sumbakq1[0];
$totalq2 = $sumtaxq2[0]+$sumfeeq2[0]+$sumpenq2[0]+$sumintq2[0]+$sumbakq2[0];
$totalq3 = $sumtaxq3[0]+$sumfeeq3[0]+$sumpenq3[0]+$sumintq3[0]+$sumbakq3[0];
$totalq1n = $sumtaxq1n[0]+$sumfeeq1n[0]+$sumpenq1n[0]+$sumintq1n[0]+$sumbakq1n[0];
$totalq2n = $sumtaxq2n[0]+$sumfeeq2n[0]+$sumpenq2n[0]+$sumintq2n[0]+$sumbakq2n[0];
$totalq3n = $sumtaxq3n[0]+$sumfeeq3n[0]+$sumpenq3n[0]+$sumintq3n[0]+$sumbakq3n[0];

//difference
$difftaxq1 = $sumtaxq1n[0]-$sumtaxq1[0];
$difftaxq2 = $sumtaxq2n[0]-$sumtaxq2[0];
$difftaxq3 = $sumtaxq3n[0]-$sumtaxq3[0];
$difffeeq1 = $sumfeeq1n[0]-$sumfeeq1[0];
$difffeeq2 = $sumfeeq2n[0]-$sumfeeq2[0];
$difffeeq3 = $sumfeeq3n[0]-$sumfeeq3[0];
$diffpenq1 = $sumpenq1n[0]-$sumpenq1[0];
$diffpenq2 = $sumpenq2n[0]-$sumpenq2[0];
$diffpenq3 = $sumpenq3n[0]-$sumpenq3[0];
$diffintq1 = $sumintq1n[0]-$sumintq1[0];
$diffintq2 = $sumintq2n[0]-$sumintq2[0];
$diffintq3 = $sumintq3n[0]-$sumintq3[0];
$diffbakq1 = $sumbakq1n[0]-$sumbakq1[0];
$diffbakq2 = $sumbakq2n[0]-$sumbakq2[0];
$diffbakq3 = $sumbakq3n[0]-$sumbakq3[0];
$difftotq1 = $totalq1n-$totalq1;
$difftotq2 = $totalq2n-$totalq2;
$difftotq3 = $totalq3n-$totalq3;

//percentage
if ($sumtaxq1[0]==0) {
	$percentdifftaxq1 = ($difftaxq1/1)*100;
} else {
	$percentdifftaxq1 = ($difftaxq1/$sumtaxq1[0])*100;
}
if ($sumtaxq2[0]==0) {
	$percentdifftaxq2 = ($difftaxq2/1)*100;
} else {
	$percentdifftaxq2 = ($difftaxq2/$sumtaxq2[0])*100;
}
if ($sumtaxq3[0]==0) {
	$percentdifftaxq3 = ($difftaxq3/1)*100;
} else {
	$percentdifftaxq3 = ($difftaxq3/$sumtaxq3[0])*100;
}
if ($sumfeeq1[0]==0) {
	$percentdifffeeq1 = ($difffeeq1/1)*100;
} else {
	$percentdifffeeq1 = ($difffeeq1/$sumfeeq1[0])*100;
}
if ($sumfeeq2[0]==0) {
	$percentdifffeeq2 = ($difffeeq2/1)*100;
} else {
	$percentdifffeeq2 = ($difffeeq2/$sumfeeq2[0])*100;
}
if ($sumfeeq3[0]==0) {
	$percentdifffeeq3 = ($difffeeq3/1)*100;
} else {
	$percentdifffeeq3 = ($difffeeq3/$sumfeeq3[0])*100;
}
if ($sumpenq1[0]==0) {
	$percentdiffpenq1 = ($diffpenq1/1)*100;
} else {
	$percentdiffpenq1 = ($diffpenq1/$sumpenq1[0])*100;
}
if ($sumpenq2[0]==0) {
	$percentdiffpenq2 = ($diffpenq2/1)*100;
} else {
	$percentdiffpenq2 = ($diffpenq2/$sumpenq2[0])*100;
}
if ($sumpenq3[0]==0) {
	$percentdiffpenq3 = ($diffpenq3/1)*100;
} else {
	$percentdiffpenq3 = ($diffpenq3/$sumpenq3[0])*100;
}
if ($sumintq1[0]==0) {
	$percentdiffintq1 = ($diffintq1/1)*100;
} else {
	$percentdiffintq1 = ($diffintq1/$sumintq1[0])*100;
}
if ($sumintq2[0]==0) {
	$percentdiffintq2 = ($diffintq2/1)*100;
} else {
	$percentdiffintq2 = ($diffintq2/$sumintq2[0])*100;
}
if ($sumintq3[0]==0) {
	$percentdiffintq3 = ($diffintq3/1)*100;
} else {
	$percentdiffintq3 = ($diffintq3/$sumintq3[0])*100;
}
if ($sumbakq1[0]==0) {
	$percentdiffbakq1 = ($diffbakq1/1)*100;
} else {
	$percentdiffbakq1 = ($diffbakq1/$sumbakq1[0])*100;
}
if ($sumbakq2[0]==0) {
	$percentdiffbakq2 = ($diffbakq2/1)*100;
} else {
	$percentdiffbakq2 = ($diffbakq2/$sumbakq2[0])*100;
}
if ($sumbakq3[0]==0) {
	$percentdiffbakq3 = ($diffbakq3/1)*100;
} else {
	$percentdiffbakq3 = ($diffbakq3/$sumbakq3[0])*100;
}
if ($totalq1==0) {
	$percentdifftotq1 = ($difftotq1/1)*100;
} else {
	$percentdifftotq1 = ($difftotq1/$totalq1)*100;
}
if ($totalq2==0) {
	$percentdifftotq2 = ($difftotq2/1)*100;
} else {
	$percentdifftotq2 = ($difftotq2/$totalq2)*100;
}
if ($totalq3==0) {
	$percentdifftotq3 = ($difftotq3/1)*100;
} else {
	$percentdifftotq3 = ($difftotq3/$totalq3)*100;
}

//formatting
$sumtaxq1[0]=number_format($sumtaxq1[0],2);
$sumtaxq2[0]=number_format($sumtaxq2[0],2);
$sumtaxq3[0]=number_format($sumtaxq3[0],2);
$sumfeeq1[0]=number_format($sumfeeq1[0],2);
$sumfeeq2[0]=number_format($sumfeeq2[0],2);
$sumfeeq3[0]=number_format($sumfeeq3[0],2);
$sumpenq1[0]=number_format($sumpenq1[0],2);
$sumpenq2[0]=number_format($sumpenq2[0],2);
$sumpenq3[0]=number_format($sumpenq3[0],2);
$sumintq1[0]=number_format($sumintq1[0],2);
$sumintq2[0]=number_format($sumintq2[0],2);
$sumintq3[0]=number_format($sumintq3[0],2);
$sumbakq1[0]=number_format($sumbakq1[0],2);
$sumbakq2[0]=number_format($sumbakq2[0],2);
$sumbakq3[0]=number_format($sumbakq3[0],2);
$sumtaxq1n[0]=number_format($sumtaxq1n[0],2);
$sumtaxq2n[0]=number_format($sumtaxq2n[0],2);
$sumtaxq3n[0]=number_format($sumtaxq3n[0],2);
$sumfeeq1n[0]=number_format($sumfeeq1n[0],2);
$sumfeeq2n[0]=number_format($sumfeeq2n[0],2);
$sumfeeq3n[0]=number_format($sumfeeq3n[0],2);
$sumpenq1n[0]=number_format($sumpenq1n[0],2);
$sumpenq2n[0]=number_format($sumpenq2n[0],2);
$sumpenq3n[0]=number_format($sumpenq3n[0],2);
$sumintq1n[0]=number_format($sumintq1n[0],2);
$sumintq2n[0]=number_format($sumintq2n[0],2);
$sumintq3n[0]=number_format($sumintq3n[0],2);
$sumbakq1n[0]=number_format($sumbakq1n[0],2);
$sumbakq2n[0]=number_format($sumbakq2n[0],2);
$sumbakq3n[0]=number_format($sumbakq3n[0],2);
$totalq1=number_format($totalq1,2);
$totalq2=number_format($totalq2,2);
$totalq3=number_format($totalq3,2);
$totalq1n=number_format($totalq1n,2);
$totalq2n=number_format($totalq2n,2);
$totalq3n=number_format($totalq3n,2);
$difftaxq1 = number_format($difftaxq1,2);
$difftaxq2 = number_format($difftaxq2,2);
$difftaxq3 = number_format($difftaxq3,2);
$difffeeq1 = number_format($difffeeq1,2);
$difffeeq2 = number_format($difffeeq2,2);
$difffeeq3 = number_format($difffeeq3,2);
$diffpenq1 = number_format($diffpenq1,2);
$diffpenq2 = number_format($diffpenq2,2);
$diffpenq3 = number_format($diffpenq3,2);
$diffintq1 = number_format($diffintq1,2);
$diffintq2 = number_format($diffintq2,2);
$diffintq3 = number_format($diffintq3,2);
$diffbakq1 = number_format($diffbakq1,2);
$diffbakq2 = number_format($diffbakq2,2);
$diffbakq3 = number_format($diffbakq3,2);
$difftotq1 = number_format($difftotq1,2);
$difftotq2 = number_format($difftotq2,2);
$difftotq3 = number_format($difftotq3,2);
$percentdifftaxq1 = number_format($percentdifftaxq1,2);
$percentdifftaxq2 = number_format($percentdifftaxq2,2);
$percentdifftaxq3 = number_format($percentdifftaxq3,2);
$percentdifffeeq1 = number_format($percentdifffeeq1,2);
$percentdifffeeq2 = number_format($percentdifffeeq2,2);
$percentdifffeeq3 = number_format($percentdifffeeq3,2);
$percentdiffpenq1 = number_format($percentdiffpenq1,2);
$percentdiffpenq2 = number_format($percentdiffpenq2,2);
$percentdiffpenq3 = number_format($percentdiffpenq3,2);
$percentdiffintq1 = number_format($percentdiffintq1,2);
$percentdiffintq2 = number_format($percentdiffintq2,2);
$percentdiffintq3 = number_format($percentdiffintq3,2);
$percentdiffbakq1 = number_format($percentdiffbakq1,2);
$percentdiffbakq2 = number_format($percentdiffbakq2,2);
$percentdiffbakq3 = number_format($percentdiffbakq3,2);
$percentdifftotq1 = number_format($percentdifftotq1,2);
$percentdifftotq2 = number_format($percentdifftotq2,2);
$percentdifftotq3 = number_format($percentdifftotq3,2);

$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);

//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($getlgu[0],$getprov[0],$resulta[2]);
$pdf->AddPage();
$pdf->AliasNbPages();

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(30,5,'DETAILS',1,0,'C');
$pdf->SetX(35);
$pdf->Cell(90,5,'YEAR 1 ('.$dateprev.')',1,0,'C');
$pdf->Cell(90,5,'YEAR 2 ('.$datenext.')',1,0,'C');
$pdf->Cell(30,5,'% DIFFERENCE',1,0,'C');
$pdf->Cell(90,5,'AMOUNT DIFFERENCE',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'',1,0,'C');
$pdf->Cell(30,5,$MonthDisplay1,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay2,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay3,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay1,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay2,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay3,1,0,'C');
$pdf->Cell(10,5,$MonthDisplay1,1,0,'C');
$pdf->Cell(10,5,$MonthDisplay2,1,0,'C');
$pdf->Cell(10,5,$MonthDisplay3,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay1,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay2,1,0,'C');
$pdf->Cell(30,5,$MonthDisplay3,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'TAXES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(30,5,$sumtaxq1[0],1,0,'C');
$pdf->Cell(30,5,$sumtaxq2[0],1,0,'C');
$pdf->Cell(30,5,$sumtaxq3[0],1,0,'C');
$pdf->Cell(30,5,$sumtaxq1n[0],1,0,'C');
$pdf->Cell(30,5,$sumtaxq2n[0],1,0,'C');
$pdf->Cell(30,5,$sumtaxq3n[0],1,0,'C');
$pdf->Cell(10,5,$percentdifftaxq1,1,0,'C');
$pdf->Cell(10,5,$percentdifftaxq2,1,0,'C');
$pdf->Cell(10,5,$percentdifftaxq3,1,0,'C');
$pdf->Cell(30,5,$difftaxq1,1,0,'C');
$pdf->Cell(30,5,$difftaxq2,1,0,'C');
$pdf->Cell(30,5,$difftaxq3,1,1,'C');


$pdf->SetX(5);
$pdf->Cell(30,5,'FEES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(30,5,$sumfeeq1[0],1,0,'C');
$pdf->Cell(30,5,$sumfeeq2[0],1,0,'C');
$pdf->Cell(30,5,$sumfeeq3[0],1,0,'C');
$pdf->Cell(30,5,$sumfeeq1n[0],1,0,'C');
$pdf->Cell(30,5,$sumfeeq2n[0],1,0,'C');
$pdf->Cell(30,5,$sumfeeq3n[0],1,0,'C');
$pdf->Cell(10,5,$percentdifffeeq1,1,0,'C');
$pdf->Cell(10,5,$percentdifffeeq2,1,0,'C');
$pdf->Cell(10,5,$percentdifffeeq3,1,0,'C');
$pdf->Cell(30,5,$difffeeq1,1,0,'C');
$pdf->Cell(30,5,$difffeeq2,1,0,'C');
$pdf->Cell(30,5,$difffeeq3,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'PENALTIES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(30,5,$sumpenq1[0],1,0,'C');
$pdf->Cell(30,5,$sumpenq2[0],1,0,'C');
$pdf->Cell(30,5,$sumpenq3[0],1,0,'C');
$pdf->Cell(30,5,$sumpenq1n[0],1,0,'C');
$pdf->Cell(30,5,$sumpenq2n[0],1,0,'C');
$pdf->Cell(30,5,$sumpenq3n[0],1,0,'C');
$pdf->Cell(10,5,$percentdiffpenq1,1,0,'C');
$pdf->Cell(10,5,$percentdiffpenq2,1,0,'C');
$pdf->Cell(10,5,$percentdiffpenq3,1,0,'C');
$pdf->Cell(30,5,$diffpenq1,1,0,'C');
$pdf->Cell(30,5,$diffpenq2,1,0,'C');
$pdf->Cell(30,5,$diffpenq3,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'SURCHARGES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(30,5,$sumintq1[0],1,0,'C');
$pdf->Cell(30,5,$sumintq2[0],1,0,'C');
$pdf->Cell(30,5,$sumintq3[0],1,0,'C');
$pdf->Cell(30,5,$sumintq1n[0],1,0,'C');
$pdf->Cell(30,5,$sumintq2n[0],1,0,'C');
$pdf->Cell(30,5,$sumintq3n[0],1,0,'C');
$pdf->Cell(10,5,$percentdiffintq1,1,0,'C');
$pdf->Cell(10,5,$percentdiffintq2,1,0,'C');
$pdf->Cell(10,5,$percentdiffintq3,1,0,'C');
$pdf->Cell(30,5,$diffintq1,1,0,'C');
$pdf->Cell(30,5,$diffintq2,1,0,'C');
$pdf->Cell(30,5,$diffintq3,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'BACK TAX',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(30,5,$sumbakq1[0],1,0,'C');
$pdf->Cell(30,5,$sumbakq2[0],1,0,'C');
$pdf->Cell(30,5,$sumbakq3[0],1,0,'C');
$pdf->Cell(30,5,$sumbakq1n[0],1,0,'C');
$pdf->Cell(30,5,$sumbakq2n[0],1,0,'C');
$pdf->Cell(30,5,$sumbakq3n[0],1,0,'C');
$pdf->Cell(10,5,$percentdiffbakq1,1,0,'C');
$pdf->Cell(10,5,$percentdiffbakq2,1,0,'C');
$pdf->Cell(10,5,$percentdiffbakq3,1,0,'C');
$pdf->Cell(30,5,$diffbakq1,1,0,'C');
$pdf->Cell(30,5,$diffbakq2,1,0,'C');
$pdf->Cell(30,5,$diffbakq3,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'TOTAL',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(30,5,$totalq1,1,0,'C');
$pdf->Cell(30,5,$totalq2,1,0,'C');
$pdf->Cell(30,5,$totalq3,1,0,'C');
$pdf->Cell(30,5,$totalq1n,1,0,'C');
$pdf->Cell(30,5,$totalq2n,1,0,'C');
$pdf->Cell(30,5,$totalq3n,1,0,'C');
$pdf->Cell(10,5,$percentdifftotq1,1,0,'C');
$pdf->Cell(10,5,$percentdifftotq2,1,0,'C');
$pdf->Cell(10,5,$percentdifftotq3,1,0,'C');
$pdf->Cell(30,5,$difftotq1,1,0,'C');
$pdf->Cell(30,5,$difftotq2,1,0,'C');
$pdf->Cell(30,5,$difftotq3,1,1,'C');


//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
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
$getsignatories = @mysql_query("select * from report_signatories where report_file='Comparative Quarterly Report' and sign_type='3'");
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

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[2],1,0,'C');

$report_desc='Comparative Quarterly Report';
//include 'report_signatories_footer1.php';

$pdf->Output();

?>

<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("lib/phpFunctions-inc.php");
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
	
	$this->Image('peoplesmall.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'SEMI-ANNUAL COMPARATIVE REPORT',0,1,'C');
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

$dbLink = get_db_connection();
$e = strrpos($owner_last,"-");//$owner_last is date
$l =strlen($owner_last);
$dateprev = substr($owner_last, $l-$e);
$dateprev = $dateprev;
$datenext = $dateprev + 1;

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
    //taxes
$sumtaxq1 = mysql_query("select sum(taxes) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month<7");
$sumtaxq1 = mysql_fetch_row($sumtaxq1);						

$sumtaxq2 = mysql_query("select sum(taxes) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month>6");
$sumtaxq2 = mysql_fetch_row($sumtaxq2);						

$sumtaxq1n = mysql_query("select sum(taxes) from comparative_statement where for_year='$datenext' and
						paid = 0 and month<7");
$sumtaxq1n = mysql_fetch_row($sumtaxq1n);						

$sumtaxq2n = mysql_query("select sum(taxes) from comparative_statement where for_year='$datenext' and
						paid = 0 and month>6");
$sumtaxq2n = mysql_fetch_row($sumtaxq2n);						

//fees
$sumfeeq1 = mysql_query("select sum(fees) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month<7");
$sumfeeq1 = mysql_fetch_row($sumfeeq1);						

$sumfeeq2 = mysql_query("select sum(fees) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month>6");
$sumfeeq2 = mysql_fetch_row($sumfeeq2);						

$sumfeeq1n = mysql_query("select sum(fees) from comparative_statement where for_year='$datenext' and
						paid = 0 and month<7");
$sumfeeq1n = mysql_fetch_row($sumfeeq1n);						

$sumfeeq2n = mysql_query("select sum(fees) from comparative_statement where for_year='$datenext' and
						paid = 0 and month>6");
$sumfeeq2n = mysql_fetch_row($sumfeeq2n);						

//penalty
$sumpenq1 = mysql_query("select sum(penalty) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month<7");
$sumpenq1 = mysql_fetch_row($sumpenq1);						

$sumpenq2 = mysql_query("select sum(penalty) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month>6");
$sumpenq2 = mysql_fetch_row($sumpenq2);						

$sumpenq1n = mysql_query("select sum(penalty) from comparative_statement where for_year='$datenext' and
						paid = 0 and month<7");
$sumpenq1n = mysql_fetch_row($sumpenq1n);						

$sumpenq2n = mysql_query("select sum(penalty) from comparative_statement where for_year='$datenext' and
						paid = 0 and month>6");
$sumpenq2n = mysql_fetch_row($sumpenq2n);						

//surcharge
$sumintq1 = mysql_query("select sum(surcharge) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month<7");
$sumintq1 = mysql_fetch_row($sumintq1);						

$sumintq2 = mysql_query("select sum(surcharge) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month>6");
$sumintq2 = mysql_fetch_row($sumintq2);						

$sumintq1n = mysql_query("select sum(surcharge) from comparative_statement where for_year='$datenext' and
						paid = 0 and month<7");
$sumintq1n = mysql_fetch_row($sumintq1n);						

$sumintq2n = mysql_query("select sum(surcharge) from comparative_statement where for_year='$datenext' and
						paid = 0 and month>6");
$sumintq2n = mysql_fetch_row($sumintq2n);						

//backtax
$sumbakq1 = mysql_query("select sum(backtax) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month<7");
$sumbakq1 = mysql_fetch_row($sumbakq1);						

$sumbakq2 = mysql_query("select sum(backtax) from comparative_statement where for_year='$dateprev' and
						paid = 0 and month>6");
$sumbakq2 = mysql_fetch_row($sumbakq2);						

$sumbakq1n = mysql_query("select sum(backtax) from comparative_statement where for_year='$datenext' and
						paid = 0 and month<7");
$sumbakq1n = mysql_fetch_row($sumbakq1n);						

$sumbakq2n = mysql_query("select sum(backtax) from comparative_statement where for_year='$datenext' and
						paid = 0 and month>6");
$sumbakq2n = mysql_fetch_row($sumbakq2n);						

//total
$totalq1 = $sumtaxq1[0]+$sumfeeq1[0]+$sumpenq1[0]+$sumintq1[0]+$sumbakq1[0];
$totalq2 = $sumtaxq2[0]+$sumfeeq2[0]+$sumpenq2[0]+$sumintq2[0]+$sumbakq2[0];
$totalq1n = $sumtaxq1n[0]+$sumfeeq1n[0]+$sumpenq1n[0]+$sumintq1n[0]+$sumbakq1n[0];
$totalq2n = $sumtaxq2n[0]+$sumfeeq2n[0]+$sumpenq2n[0]+$sumintq2n[0]+$sumbakq2n[0];

//difference
$difftaxq1 = $sumtaxq1n[0]-$sumtaxq1[0];
$difftaxq2 = $sumtaxq2n[0]-$sumtaxq2[0];
$difffeeq1 = $sumfeeq1n[0]-$sumfeeq1[0];
$difffeeq2 = $sumfeeq2n[0]-$sumfeeq2[0];
$diffpenq1 = $sumpenq1n[0]-$sumpenq1[0];
$diffpenq2 = $sumpenq2n[0]-$sumpenq2[0];
$diffintq1 = $sumintq1n[0]-$sumintq1[0];
$diffintq2 = $sumintq2n[0]-$sumintq2[0];
$diffbakq1 = $sumbakq1n[0]-$sumbakq1[0];
$diffbakq2 = $sumbakq2n[0]-$sumbakq2[0];
$difftotq1 = $totalq1n-$totalq1;
$difftotq2 = $totalq2n-$totalq2;

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

//formatting
$sumtaxq1[0]=number_format($sumtaxq1[0],2);
$sumtaxq2[0]=number_format($sumtaxq2[0],2);
$sumfeeq1[0]=number_format($sumfeeq1[0],2);
$sumfeeq2[0]=number_format($sumfeeq2[0],2);
$sumpenq1[0]=number_format($sumpenq1[0],2);
$sumpenq2[0]=number_format($sumpenq2[0],2);
$sumintq1[0]=number_format($sumintq1[0],2);
$sumintq2[0]=number_format($sumintq2[0],2);
$sumbakq1[0]=number_format($sumbakq1[0],2);
$sumbakq2[0]=number_format($sumbakq2[0],2);
$sumtaxq1n[0]=number_format($sumtaxq1n[0],2);
$sumtaxq2n[0]=number_format($sumtaxq2n[0],2);
$sumfeeq1n[0]=number_format($sumfeeq1n[0],2);
$sumfeeq2n[0]=number_format($sumfeeq2n[0],2);
$sumpenq1n[0]=number_format($sumpenq1n[0],2);
$sumpenq2n[0]=number_format($sumpenq2n[0],2);
$sumintq1n[0]=number_format($sumintq1n[0],2);
$sumintq2n[0]=number_format($sumintq2n[0],2);
$sumbakq1n[0]=number_format($sumbakq1n[0],2);
$sumbakq2n[0]=number_format($sumbakq2n[0],2);
$totalq1=number_format($totalq1,2);
$totalq2=number_format($totalq2,2);
$totalq1n=number_format($totalq1n,2);
$totalq2n=number_format($totalq2n,2);
$difftaxq1 = number_format($difftaxq1,2);
$difftaxq2 = number_format($difftaxq2,2);
$difffeeq1 = number_format($difffeeq1,2);
$difffeeq2 = number_format($difffeeq2,2);
$diffpenq1 = number_format($diffpenq1,2);
$diffpenq2 = number_format($diffpenq2,2);
$diffintq1 = number_format($diffintq1,2);
$diffintq2 = number_format($diffintq2,2);
$diffbakq1 = number_format($diffbakq1,2);
$diffbakq2 = number_format($diffbakq2,2);
$difftotq1 = number_format($difftotq1,2);
$difftotq2 = number_format($difftotq2,2);
$percentdifftaxq1 = number_format($percentdifftaxq1,2);
$percentdifftaxq2 = number_format($percentdifftaxq2,2);
$percentdifffeeq1 = number_format($percentdifffeeq1,2);
$percentdifffeeq2 = number_format($percentdifffeeq2,2);
$percentdiffpenq1 = number_format($percentdiffpenq1,2);
$percentdiffpenq2 = number_format($percentdiffpenq2,2);
$percentdiffintq1 = number_format($percentdiffintq1,2);
$percentdiffintq2 = number_format($percentdiffintq2,2);
$percentdiffbakq1 = number_format($percentdiffbakq1,2);
$percentdiffbakq2 = number_format($percentdiffbakq2,2);
$percentdifftotq1 = number_format($percentdifftotq1,2);
$percentdifftotq2 = number_format($percentdifftotq2,2);
    
	
	

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
$pdf->Cell(30,5,'DETAILS',1,0,'C');
$pdf->SetX(35);
$pdf->Cell(105,5,'YEAR 1 ('.$dateprev.')',1,0,'C');
$pdf->SetX(140);
$pdf->Cell(105,5,'YEAR 2 ('.$datenext.')',1,0,'C');
$pdf->SetX(245);
$pdf->Cell(50,5,'% DIFFERENCE',1,0,'C');
$pdf->SetX(295);
$pdf->Cell(50,5,'AMOUNT DIFFERENCE',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'',1,0,'C');
$pdf->Cell(52.5,5,'JAN-JUN',1,0,'C');
$pdf->Cell(52.5,5,'JUL-DEC',1,0,'C');
$pdf->Cell(52.5,5,'JAN-JUN',1,0,'C');
$pdf->Cell(52.5,5,'JUL-DEC',1,0,'C');
$pdf->Cell(25,5,'JAN-JUN',1,0,'C');
$pdf->Cell(25,5,'JUL-DEC',1,0,'C');
$pdf->Cell(25,5,'JAN-JUN',1,0,'C');
$pdf->Cell(25,5,'JUL-DEC',1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'TAXES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(52.5,5,$sumtaxq1[0],1,0,'C');
$pdf->Cell(52.5,5,$sumtaxq2[0],1,0,'C');
$pdf->Cell(52.5,5,$sumtaxq1n[0],1,0,'C');
$pdf->Cell(52.5,5,$sumtaxq2n[0],1,0,'C');
$pdf->Cell(25,5,$percentdifftaxq1,1,0,'C');
$pdf->Cell(25,5,$percentdifftaxq2,1,0,'C');
$pdf->Cell(25,5,$difftaxq1,1,0,'C');
$pdf->Cell(25,5,$difftaxq2,1,1,'C');


$pdf->SetX(5);
$pdf->Cell(30,5,'FEES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(52.5,5,$sumfeeq1[0],1,0,'C');
$pdf->Cell(52.5,5,$sumfeeq2[0],1,0,'C');
$pdf->Cell(52.5,5,$sumfeeq1n[0],1,0,'C');
$pdf->Cell(52.5,5,$sumfeeq2n[0],1,0,'C');
$pdf->Cell(25,5,$percentdifffeeq1,1,0,'C');
$pdf->Cell(25,5,$percentdifffeeq2,1,0,'C');
$pdf->Cell(25,5,$difffeeq1,1,0,'C');
$pdf->Cell(25,5,$difffeeq2,1,1,'C');


$pdf->SetX(5);
$pdf->Cell(30,5,'PENALTIES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(52.5,5,$sumpenq1[0],1,0,'C');
$pdf->Cell(52.5,5,$sumpenq2[0],1,0,'C');
$pdf->Cell(52.5,5,$sumpenq1n[0],1,0,'C');
$pdf->Cell(52.5,5,$sumpenq2n[0],1,0,'C');
$pdf->Cell(25,5,$percentdiffpenq1,1,0,'C');
$pdf->Cell(25,5,$percentdiffpenq2,1,0,'C');
$pdf->Cell(25,5,$diffpenq1,1,0,'C');
$pdf->Cell(25,5,$diffpenq2,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'SURCHARGES',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(52.5,5,$sumintq1[0],1,0,'C');
$pdf->Cell(52.5,5,$sumintq2[0],1,0,'C');
$pdf->Cell(52.5,5,$sumintq1n[0],1,0,'C');
$pdf->Cell(52.5,5,$sumintq2n[0],1,0,'C');
$pdf->Cell(25,5,$percentdiffintq1,1,0,'C');
$pdf->Cell(25,5,$percentdiffintq2,1,0,'C');
$pdf->Cell(25,5,$diffintq1,1,0,'C');
$pdf->Cell(25,5,$diffintq2,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'BACK TAX',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(52.5,5,$sumbakq1[0],1,0,'C');
$pdf->Cell(52.5,5,$sumbakq2[0],1,0,'C');
$pdf->Cell(52.5,5,$sumbakq1n[0],1,0,'C');
$pdf->Cell(52.5,5,$sumbakq2n[0],1,0,'C');
$pdf->Cell(25,5,$percentdiffbakq1,1,0,'C');
$pdf->Cell(25,5,$percentdiffbakq2,1,0,'C');
$pdf->Cell(25,5,$diffbakq1,1,0,'C');
$pdf->Cell(25,5,$diffbakq2,1,1,'C');

$pdf->SetX(5);
$pdf->Cell(30,5,'TOTAL',1,0,'L');
$pdf->SetX(35);
$pdf->Cell(52.5,5,$totalq1,1,0,'C');
$pdf->Cell(52.5,5,$totalq2,1,0,'C');
$pdf->Cell(52.5,5,$totalq1n,1,0,'C');
$pdf->Cell(52.5,5,$totalq2n,1,0,'C');
$pdf->Cell(25,5,$percentdifftotq1,1,0,'C');
$pdf->Cell(25,5,$percentdifftotq2,1,0,'C');
$pdf->Cell(25,5,$difftotq1,1,0,'C');
$pdf->Cell(25,5,$difftotq2,1,1,'C');


//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Recommend Approval:',1,0,'L');
$pdf->Cell(173,5,'Approved:',1,1,'L');

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

$report_desc='Comparative Semi-Annual Report';
include 'report_signatories_footer1.php';3

$pdf->Output();

?>

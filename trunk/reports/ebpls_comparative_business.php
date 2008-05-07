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
var $yearnow;
var $yearnext;
var $yearnextnext;
var $y0;

	function setLGUinfo($p='', $l='', $o='') {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
//		echo 'setLGUinfo'.$this->prov;
	}
	function setYears($x='', $y='', $z='') {
		$this->yearnow = $x;
		$this->yearnext = $y;
		$this->yearnextnext = $z;
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
	$this->SetFont('Arial','B',16);
	$this->Cell(340,5,'ANNUAL COMPARATIVE STATEMENT OF BUSINESS ESTABLISHMENTS',0,1,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,'REGISTERED AND RENEWED',0,1,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,'FOR THE YEARS '.$this->yearnow.', '.$this->yearnext.'& '.$this->yearnextnext,0,1,'C');
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

$e = strrpos($date_from,"-");//$owner_last is date
$l =strlen($date_from);
$dateprev = substr($date_from,0,4);
$dateprev = $dateprev;
$datenext = $dateprev + 1;
$datenextnext = $dateprev + 2;


	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
    //taxes
	
//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
$pdf->setLGUinfo($getlgu[0],$getprov[0],$resulta[2]);
$pdf->setYears($dateprev,$datenext,$datenextnext);
$pdf->AddPage();
$pdf->AliasNbPages();

$getnat = @mysql_query("select * from ebpls_buss_nature");

$Y_Label_position = 50;
$Y_Table_Position = 55;
//header
$dateprinted = date('Y-m-d');
$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(340,5,$dateprinted,0,1,'R');
$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position + 10);
$pdf->SetX(5);
$pdf->Cell(100,10,'LINE OF BUSINESS',1,0,'C');
$pdf->SetX(105);
$pdf->Cell(60,5,$dateprev,1,0,'C');
$pdf->SetX(165);
$pdf->Cell(60,5,$datenext,1,0,'C');
$pdf->SetX(225);
$pdf->Cell(60,5,$datenextnext,1,0,'C');
$pdf->SetY($Y_Label_position + 15);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'C');
$pdf->SetX(105);
$pdf->Cell(30,5,'New',1,0,'C');
$pdf->SetX(135);
$pdf->Cell(30,5,'Renew',1,0,'C');
$pdf->SetX(165);
$pdf->Cell(30,5,'New',1,0,'C');
$pdf->SetX(195);
$pdf->Cell(30,5,'Renew',1,0,'C');
$pdf->SetX(225);
$pdf->Cell(30,5,'New',1,0,'C');
$pdf->SetX(255);
$pdf->Cell(30,5,'Renew',1,1,'C');
$yloop = 0;
while ($getnats = @mysql_fetch_assoc($getnat))
{
	$pdf->SetY($Y_Label_position + 20 + $yloop);
	$pdf->SetX(5);
	$pdf->Cell(100,5,$getnats[naturedesc],1,0,'L');
	$get1new = @mysql_query("select * from tempbusnature a, ebpls_business_enterprise_permit b 
	where a.bus_nature = '$getnats[naturedesc]' and a.business_id = b.business_id and a.owner_id = b.owner_id and 
	b.for_year = '$dateprev' and a.transaction = 'New' and b.transaction='New'");
	$get1renew = @mysql_query("select * from tempbusnature a, ebpls_business_enterprise_permit b 
	where a.bus_nature = '$getnats[naturedesc]' and a.business_id = b.business_id and a.owner_id = b.owner_id and 
	b.for_year = '$dateprev' and a.transaction = 'Renew' and b.transaction='Renew'");
	$get1new = @mysql_num_rows($get1new);
	$get1renew = @mysql_num_rows($get1renew);
	if ($get1new == "") {
		$get1new = '0';
	}
	if ($get1renew == "") {
		$get1renew = '0';
	}
	$pdf->Cell(30,5,$get1new,1,0,'R');
	$pdf->Cell(30,5,$get1renew,1,0,'R');
	$get2new = @mysql_query("select * from tempbusnature a, ebpls_business_enterprise_permit b 
	where a.bus_nature = '$getnats[naturedesc]' and a.business_id = b.business_id and a.owner_id = b.owner_id and 
	b.for_year = '$datenext' and a.transaction = 'New' and b.transaction='New'");
	$get2renew = @mysql_query("select * from tempbusnature a, ebpls_business_enterprise_permit b 
	where a.bus_nature = '$getnats[naturedesc]' and a.business_id = b.business_id and a.owner_id = b.owner_id and 
	b.for_year = '$datenext' and a.transaction = 'Renew' and b.transaction='Renew'");
	$get2new = @mysql_num_rows($get2new);
	$get2renew = @mysql_num_rows($get2renew);
	if ($get2new == "") {
		$get2new = '0';
	}
	if ($get2renew == "") {
		$get2renew = '0';
	}
	$pdf->Cell(30,5,$get2new,1,0,'R');
	$pdf->Cell(30,5,$get2renew,1,0,'R');
	$get3new = @mysql_query("select * from tempbusnature a, ebpls_business_enterprise_permit b 
	where a.bus_nature = '$getnats[naturedesc]' and a.business_id = b.business_id and a.owner_id = b.owner_id and 
	b.for_year = '$datenextnext' and a.transaction = 'New' and b.transaction='New'");
	$get3renew = @mysql_query("select * from tempbusnature a, ebpls_business_enterprise_permit b 
	where a.bus_nature = '$getnats[naturedesc]' and a.business_id = b.business_id and a.owner_id = b.owner_id and 
	b.for_year = '$datenextnext' and a.transaction = 'Renew' and b.transaction='Renew'");
	$get3new = @mysql_num_rows($get3new);
	$get3renew = @mysql_num_rows($get3renew);
	if ($get3new == "") {
		$get3new = '0';
	}
	if ($get3renew == "") {
		$get3renew = '0';
	}
	$pdf->Cell(30,5,$get3new,1,0,'R');
	$pdf->Cell(30,5,$get3renew,1,0,'R');
	$yloop = $yloop + 5;
	if ($yloop > 120) {
		$yloop = 0;
		$pdf->AddPage();
	}
}
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');
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
$getsignatories = @mysql_query("select * from report_signatories where report_file='Business Establishment Comparative' and sign_type='3'");
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

$report_desc='Comparative Annual Report';
//include '../report_signatories_footer1.php';

$pdf->Output();

?>

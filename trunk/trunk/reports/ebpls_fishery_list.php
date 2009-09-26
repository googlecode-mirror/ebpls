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
	
	$this->Image('../images/ebpls_logo.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->lgu,0,1,'C');
	$this->Cell(340,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'FISHERY REGISTRY',0,1,'C');
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
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(25,5,'PERMIT #',1,0,'C');
$pdf->Cell(80,5,'OWNER',1,0,'C');
$pdf->Cell(100,5,'ADDRESS',1,0,'C');
$pdf->Cell(50,5,'BOAT NAME',1,0,'C');
$pdf->Cell(25,5,'# OF CREW',1,0,'C');
$pdf->Cell(25,5,'REGISTRATION #',1,0,'C');
$pdf->Cell(25,5,'NATIONALITY',1,0,'C');

	if ($brgy_name == "") {
		$brgy_name = "$brgy_name%";
	} else {
		$brgy_name = "$brgy_name";
	}
$date_from = str_replace("/", "", $date_from);
$idate = strtotime($date_from);
//$idate = $idate - (60*60*24);
$date_from = date('Y-m-d', $idate);
$date_to = str_replace("/", "", $date_to);
$xdate = strtotime($date_to);
$xdate = $xdate + (60*60*24);
$date_to = date('Y-m-d', $xdate);
	$result = mysql_query ("select distinct (b.ebpls_fishery_permit_code) as pid,
concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name) as fulln, a.owner_citizenship,
concat(a.owner_street, ' ', f.barangay_desc, ' ',
g.city_municipality_desc, ' ', h.province_desc, ' ',
a.owner_zip_code) as owner_add,
d.boat_name, d.crew, d.reg_no, d.engine_type 
from ebpls_owner a, ebpls_fishery_permit b, fish_assess c, fish_boat d, 
ebpls_barangay f , ebpls_city_municipality g , ebpls_province h where
b.active=1 and a.owner_id = b.owner_id and c.owner_id = b.owner_id and d.owner_id = a.owner_id
and f.barangay_code = a.owner_barangay_code and g.city_municipality_code = a.owner_city_code 
and h.province_code = a.owner_province_code and c.owner_id = a.owner_id and
b. ebpls_fishery_permit_application_date between '$date_from' and '$date_to'
and a.owner_barangay_code like '$brgy_name'");


	$i = 1;
	$pdf->SetY($Y_Table_Position);
	$pdf->SetFont('Arial','',6);
	$total_ = 0;
	while ($resulta=mysql_fetch_array($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(25,5,$resulta[pid],1,0,'C');
		$pdf->Cell(80,5,$resulta[fulln],1,0,'C');
$pdf->Cell(100,5,$resulta[owner_add],1,0,'C');
$pdf->Cell(50,5,$resulta[boat_name],1,0,'C');
$pdf->Cell(25,5,$resulta[crew],1,0,'C');
$pdf->Cell(25,5,$resulta[reg_no],1,0,'C');
$pdf->Cell(25,5,$resulta[owner_citizenship],1,0,'C');
		$qute=0;
		$i++;
		$total_++;
		$pdf->SetY($pdf->GetY()+5);
	} 
$pdf->SetX(5);
		$pdf->Cell(20,5,'',0,0,'C');
$pdf->Cell(60,5,'',0,0,'C');
$pdf->Cell(80,5,'',0,0,'C');
$pdf->Cell(25,5,'',0,0,'C');
$pdf->Cell(25,5,'',0,0,'C');
$pdf->Cell(25,5,'',0,0,'C');
$pdf->Cell(25,5,'',0,0,'C');
$pdf->Cell(25,5,'',0,0,'C');
$pdf->Cell(25,5,'TOTAL :',0,0,'C');
$pdf->Cell(25,5,$total_.' REGISTRY',0,1,'C');

$pdf->SetX(5);
$pdf->Cell(10,5,'',0,0,'L');
$pdf->SetX(15);
$pdf->Cell(25,5,'',0,0,'L');
$pdf->SetX(40);
$pdf->Cell(55,5,'',0,0,'L');
$pdf->SetX(95);
$pdf->Cell(60,5,'',0,0,'L');
$pdf->SetX(155);
$pdf->Cell(90,5,'',0,1,'L');
//$pdf->SetX(305);

          
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
$getsignatories = @mysql_query("select * from report_signatories where report_file='Fishery Registry' and sign_type='3'");
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


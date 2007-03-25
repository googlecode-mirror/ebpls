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
	$this->Cell(340,5,'LIST OF PEDDLERS',0,1,'C');
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
$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(10,5,'NO.',1,0,'C');
$pdf->SetX(15);
$pdf->Cell(25,5,'PERMIT NO.',1,0,'C');
$pdf->SetX(40);
$pdf->Cell(75,5,'NAME OF OWNER',1,0,'C');
$pdf->Cell(85,5,'ADDRESS',1,0,'C');
$pdf->Cell(65,5,'BUSINESS NAME',1,0,'C');
$pdf->Cell(50,5,'MERCHANDISE',1,0,'C');
//$pdf->SetX(155);
//$pdf->Cell(60,5,'LINE OF BUSINESS',1,0,'C');

//$pdf->Cell(30,5,'GROSS RECEIPTS',1,0,'C');
//$pdf->SetX(305);

/*
	$result = mysql_query("select distinct (c.business_permit_id), a.business_name, 
	concat(a.business_lot_no, ' ', a.business_street, ' ',
	a.business_city_code, ' ', a.business_province_code, ' ', a.business_zip_code),
	concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) 
	from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c 
	where b.owner_id = a.owner_id and a.business_id = c.business_id and 
	c.active=1 and b.owner_last_name like '$owner_last%' and
	a.business_name like '$buss_name%' and 
	c.transaction like '$trans%' 
	and a.business_barangay_code  like '$brgy_name%'
	"); 
*/
	if ($brgy_name == "") {
		$brgy_name = "$brgy_name%";
	} else {
		$brgy_name = "$brgy_name";
	}
	$result = mysql_query ("select distinct (c.peddlers_permit_code) as pid,
concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
concat(b.owner_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
b.owner_zip_code) as owner_add, b.owner_id , c.peddlers_business_name, c.merchandise_sold
from ebpls_owner b, ebpls_peddlers_permit c,
ebpls_barangay f , ebpls_city_municipality g , ebpls_province h where
c.active=1 and b.owner_id = c.owner_id and f.barangay_code = b.owner_barangay_code and g.city_municipality_code = b.owner_city_code 
and h.province_code = b.owner_province_code and c.owner_id = b.owner_id and 
b.owner_last_name like '$owner_last%' and c.application_date between '$date_from' and '$date_to'
and b.owner_barangay_code like '$brgy_name%' order by owner_last_name");


	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($resulta=mysql_fetch_array($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(10,5,$i,1,0,'L');
		$pdf->SetX(15);
		$pdf->Cell(25,5,$resulta[pid],1,0,'L');
		$pdf->SetX(40);
		$pdf->Cell(75,5,$resulta[fulln],1,0,'L');
		$pdf->Cell(85,5,$resulta[owner_add],1,0,'L');
		$pdf->Cell(65,5,$resulta[peddlers_business_name],1,0,'L');
		$pdf->Cell(50,5,$resulta[merchandise_sold],1,0,'L');
		$qute=0;
		$i++;
		$pdf->SetY($pdf->GetY()+5);
	} 

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
$getsignatories = @mysql_query("select * from report_signatories where report_file='Occupational Masterlist' and sign_type='3'");
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


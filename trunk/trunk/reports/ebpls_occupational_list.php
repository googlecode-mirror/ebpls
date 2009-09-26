<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$criteria="$brgy_name $owner_last $trans $cap_inv $last_yr";
$idate = strtotime($date_from);

$date_from = date('Y-m-d', $idate);

$xdate = strtotime($date_to);

$date_to = date('Y-m-d', $xdate);
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
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'OCCUPATIONAL REGISTRY',0,1,'C');
	$this->SetFont('Arial','B',12);
	
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
$pdf->SetFont('Arial','B',8);
$pdf->SetY($Y_Label_position);
$pdf->Cell(5,5,'As of '.$date_from." to ".$date_to,0,1,'L');
$pdf->SetX(5);

$pdf->Cell(80,5,'TAXPAYER NAME/ADDRESS',1,0,'C');
$pdf->Cell(35,5,'OCCUPATION',1,0,'C');
$pdf->Cell(25,5,'DATE OF BIRTH',1,0,'C');
$pdf->Cell(10,5,'AGE',1,0,'C');
$pdf->Cell(10,5,'SEX',1,0,'C');
$pdf->Cell(80,5,'EMPLOYER/ADDRESS',1,0,'C');
$pdf->Cell(35,5,'OR NO/PAYMENT DATE',1,0,'C');
$pdf->Cell(30,5,'AMOUNT',1,0,'C');
$pdf->Cell(15,5,'VALIDITY',1,1,'C');


	if ($brgy_name == "") {
		$brgy_name = "$brgy_name%";
	} else {
		$brgy_name = "$brgy_name";
	}

$idate = strtotime($date_from);

$date_from = date('Y-m-d', $idate);

$xdate = strtotime($date_to);

$date_to = date('Y-m-d', $xdate);

			$result = mysql_query ("select distinct (c.occ_permit_code) as pid,
		c.occ_position_applied, concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
		concat(b.owner_street, ' ', i.barangay_desc, ' ',j.city_municipality_desc, ' ', k.province_desc, ' ',
		b.owner_zip_code) as owner_add, a.business_name, b.owner_gender, b.owner_birth_date, b.owner_citizenship,
		concat(a.business_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
		b.owner_zip_code) as business_add, b.owner_id, c.for_year
		from ebpls_business_enterprise a, ebpls_owner b, ebpls_occupational_permit c,
		ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_barangay i , ebpls_city_municipality j , ebpls_province k where
		c.active=1 and c.business_id = a.business_id and b.owner_id = c.owner_id
		and i.barangay_code = b.owner_barangay_code and j.city_municipality_code = b.owner_city_code 
		and k.province_code = b.owner_province_code and c.owner_id = b.owner_id and 
		f.barangay_code = a.business_barangay_code and g.city_municipality_code = a.business_city_code 
		and h.province_code = a.business_province_code and 
		b.owner_last_name like '$owner_last%' and b.owner_gender like '$sex%' and 
		c.occ_permit_application_date between '$date_from 00:00:00' and '$date_to 23:59:59'
		and b.owner_barangay_code like '$brgy_name%' order by owner_last_name");


	$i = 1;
	
	while ($resulta=mysql_fetch_array($result))
	{
    $pdf->SetX(5);
$age = mysql_query("select datediff(now(),'$resulta[owner_birth_date]') /365 as age");
$age = mysql_fetch_row($age);
$age = $age[0];
$pdf->Cell(80,5,$resulta[fulln]." / ".$resulta[owner_add],1,0,'C');
$pdf->Cell(35,5,$resulta[occ_position_applied],1,0,'C');
$pdf->Cell(25,5,substr($resulta[owner_birth_date],0,10),1,0,'C');
$pdf->Cell(10,5,$age,1,0,'C');
$pdf->Cell(10,5,$resulta[owner_gender],1,0,'C');
$pdf->Cell(80,5,$resulta[business_name]." / ".$resulta[business_add],1,0,'C');
$getor = mysql_query("select * from ebpls_transaction_payment_or_details a, 
						ebpls_transaction_payment_or b where a.trans_id = '$resulta[owner_id]' 
						and a.permit_type ='Occupational'");
$getor = mysql_fetch_assoc($getor);

$pdf->Cell(35,5,$getor[payment_code]." / ".substr($getor[or_date],0,10),1,0,'C');
$pdf->Cell(30,5,'AMOUNT',1,0,'C');
$pdf->Cell(15,5,number_format($getor[total_amount_paid],2),1,1,'C');
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
$getsignatories = @mysql_query("select * from report_signatories where report_file='Occupational Registry' and sign_type='3'");
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


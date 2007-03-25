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
var $fx;
var $fy;
var $y0;

	function setLGUinfo($p='', $l='', $o='') {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
//		echo 'setLGUinfo'.$this->prov;
	}
	function setYear($x='', $y='') {
		$this->fx = $x;
		$this->fy = $y;
		
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
	$this->Cell(340,5,'LIST OF BUSINESS REQUIREMENT DELINQUENT',0,1,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,'FROM '.$this->fx.' TO '.$this->fy,0,1,'C');
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
$pdf->setLGUinfo($getlgu[0],$getprov[0],'Office of the Treasurer');
$pdf->setYear($date_from,$date_to);
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',10);
$pdf->SetY(40);
$pdf->SetX(10);
$pdf->Cell(25,5,'',0,0,'L');
$pdf->SetX(50);
$pdf->Cell(100,5,$criteria,0,1,'L');

$Y_Label_position = 50;
$Y_Table_Position = 55;
if ($trans <> 'New' and $trans <> '') {
	$CAPGRO = "GROSS RECEIPTS";
} else {
	$CAPGRO = "CAPITAL INVESTMENT";
}
$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$dateprinted = date('Y-m-d');
$pdf->SetX(5);
$pdf->Cell(340,5,$dateprinted,0,1,'R');
$pdf->SetX(5);
$pdf->Cell(30,5,'APPLICATION DATE',1,0,'C');
$pdf->Cell(50,5,'BUSINESS NAME',1,0,'C');
$pdf->Cell(100,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->Cell(60,5,'OWNER NAME',1,0,'C');
$pdf->Cell(100,5,'OWNER ADDRESS',1,1,'C');

/*
	$result = mysql_query("select distinct (c.business_permit_id), a.business_name, 
	concat(a.business_lot_no, ' ', a.business_street, ' ',
	a.business_city_code, ' ', a.business_province_code, ' ', a.business_zip_code),
	concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) 
	from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c 
	where b.owner_id = a.owner_id and a.business_id = c.business_id and 
	c.active=1 and a.business_barangay_code  like '$brgy_name% and a.blacklist == '1'
	"); 
*/
$date_from = str_replace("/", "", $date_from);
$idate = strtotime($date_from);
//$idate = $idate - (60*60*24);
$date_from = date('Y-m-d', $idate);
$date_to = str_replace("/", "", $date_to);
$xdate = strtotime($date_to);
$xdate = $xdate + (60*60*24);
$date_to = date('Y-m-d', $xdate);
	$result = mysql_query ("select distinct (c.business_permit_code) as pid, a.business_name,
        concat(a.business_lot_no, ' ', a.business_street, ' ', f.barangay_desc, ' ',
        g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln, b.owner_id, 
	b.owner_id, a.business_id, c.application_date 
        from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
	tempbusnature d, ebpls_buss_nature e , ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, 
	havereq i where
        d.active=1 and a.business_barangay_code = f.barangay_code and g.city_municipality_code = a.business_city_code 
        and h.province_code = a.business_province_code and b.owner_id = a.owner_id and a.business_id = c.business_id and
        c.active=1 and b.owner_id = d.owner_id and
	a.business_id=d.business_id
	and c.application_date between '$date_from' and '$date_to'
        and a.business_barangay_code  like '$brgy_name%' and a.owner_id = i.owner_id and a.business_id = i.business_id and i.active = '0'");
	$i = 1;
	//$pdf->SetY($Y_Table_Position);
	while ($resulta=mysql_fetch_assoc($result))
	{
		$getownadd = @mysql_query("select * from ebpls_owner a, ebpls_zone b, ebpls_barangay c, 
		ebpls_district d, ebpls_city_municipality e, ebpls_province f where a.owner_id = '$resulta[owner_id]' and 
		a.owner_zone_code = b.zone_code and a.owner_barangay_code = c.barangay_code and 
		a.owner_district_code = d.district_code and a.owner_city_code = e.city_municipality_code and 
		a.owner_province_code = f.province_code");
		$getownadd = mysql_fetch_assoc($getownadd);
		$pdf->SetX(5);
    	$pdf->Cell(30,5,$resulta[application_date],1,0,'C');
		$pdf->Cell(50,5,$resulta[business_name],1,0,'C');
		$pdf->Cell(100,5,$resulta[bus_add],1,0,'C');
		$pdf->Cell(60,5,$resulta[fulln],1,0,'C');
		$pdf->Cell(100,5,$getownadd[owner_street_no] . ' ' . $getownadd[zone_desc] . ' ' . $getownadd[barangay_desc] . ' ' . $$getownadd[district_desc] . ' ' . $getownadd[city_municipality_desc] . ' ' . $getownadd[province_desc],1,1,'C');

		$pdf->SetY($pdf->GetY()+5);
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
$getsignatories = @mysql_query("select * from report_signatories where report_file='List of Business Requirement Delinquent' and sign_type='3'");
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


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
	$this->Cell(340,5,$this->lgu,0,1,'C');
	$this->Cell(340,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'Audit Trail',0,1,'C');
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

$date_from = str_replace("/", "", $date_from);
$idate = strtotime($date_from);
//$idate = $idate - (60*60*24);
$date_from = date('Y-m-d', $idate);
$date_to = str_replace("/", "", $date_to);
$xdate = strtotime($date_to);
$xdate = $xdate + (60*60*24);
$date_to = date('Y-m-d', $xdate);


	
$lupa = mysql_query("select * from ebpls_business_enterprise_permit where application_date between '$date_from' and '$date_to' and business_id>0") 
or die (mysql_error());
//$pdf=new FPDF('L','mm','Legal');

$pdf=new PDF('L','mm','A4');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'');

while ($g=mysql_fetch_assoc($lupa)) {
$pdf->AddPage();
$pdf->AliasNbPages();
$owner_id = $g[owner_id];
$business_id=$g[business_id];
$stat = $g[transaction];

$getdata = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, 
				ebpls_business_enterprise_permit b, ebpls_owner c",
			"a.business_payment_mode, 
			concat(c.owner_last_name,',',c.owner_first_name,' ',c.owner_middle_name)
			as fullname, 
			concat(c.owner_house_no,
			c.owner_street,c.owner_barangay_code,
			c.owner_zone_code,c.owner_district_code,
			c.owner_city_code,c.owner_province_code,
			c.owner_zip_code) as address, 
			c.owner_gender,
			c.owner_phone_no, a.business_name, 
			concat(a.business_lot_no,' ', a.business_street,' ',   
			a.business_barangay_code,' ', a.business_zone_code,' ',    
			a.business_barangay_name,' ', a.business_district_code,' ',   
			a.business_city_code,' ', a.business_province_code,' ',  
			a.business_zip_code) as bizadd,
			b.transaction",
			"where a.owner_id = b.owner_id and a.business_id = b.business_id and 
			c.owner_id=$owner_id and
			a.owner_id = $owner_id and a.business_id = $business_id and 
			b.transaction='$stat' and b.active=1");
$getid = FetchArray($dbtype,$getdata);


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
$pdf->Cell(20,5,'Tax Payer:',1,0,'C');
$pdf->Cell(35,5,$getid[fullname],1,0,'C');
$pdf->Cell(20,5,'Business Name',1,0,'C');
$pdf->Cell(40,5,$getid[business_name],1,1,'C');

    
$chkbacktax = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$chkbacktax = FetchArray($dbtype,$chkbacktax);
if ($chkbacktax[sbacktaxes]=='1' and $stat=='Retire') {
$tftnum=1;
$tft='';

//require "includes/rpt_assessment.php";

} else {
$gettag=SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$gettag=FetchArray($dbtype,$gettag);
	if ($gettag[sassess]=='') {
$tftnum = 1;
        //$tft = ' and c.taxfeetype=1'; // or c.taxfeetype=4';
       $tft = ''; // or c.taxfeetype=4';
        $totexempt=0;
        require "../includes/rpt_assessment.php";
$total_tax_compute = $grandamt;
$howmany = $df;
$tftnum=4;
        //$tft = ' and c.taxfeetype=4';// and c.taxfeetype=4';
		$tft = '';// and c.taxfeetype=4';
      
        $totexempt=0;
        //require "includes/rpt_assessment.php";
$total_sf_compute = $grandamt;
$howmany = $df+$howmany;
                                                                                                                             
        //$tft =' and c.taxfeetype<>1';// or c.taxfeetype<>4';
		$tft ='';// or c.taxfeetype<>4';
        
        //require "includes/feeassess.php";

	} else {
$tftnum=1;
$tft='';
$htag = 'Assessment';
$com='approve';

//require "includes/rpt_assessment.php";

	}
}







      
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

}
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


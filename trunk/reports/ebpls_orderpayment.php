<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

$bgy = mysql_query("select * from ebpls_barangay where  barangay_code='$brgy_name'");
$bgy = mysql_fetch_assoc($bgy);
$brgy_name = $bgy[barangay_desc];

$criteria="$brgy_name $owner_last $trans $cap_inv $last_yr";
class PDF extends FPDF
{
var $prov;
var $lgu;
var $office;
var $y0;
var $datef;
var $datet;

	function setDateRange($x='', $y='') {
		$this->datef = $x;
		$this->datet = $y;
//		echo 'setLGUinfo'.$this->prov;
	}
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
	$this->Cell(200,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(200,5,$this->lgu,0,1,'C');
	$this->Cell(200,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(200,5,$this->office,0,2,'C');
	$this->Cell(200,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(200,5,'TAX ORDER OF PAYMENT',0,1,'C');

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
	

//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('P','mm','A4');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'');


$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',10);
$pdf->SetY(40);
$pdf->SetX(10);
$pdf->Cell(25,5,'',0,0,'L');

$Y_Label_position = 45;
$Y_Table_Position = 60;

$dateprinted = date('Y-m-d');
$pdf->SetFont('Arial','B',12);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
//$pdf->Cell(270,5,$dateprinted,0,1,'R');
$pdf->SetFont('Arial','B',12);
$pdf->SetX(5);
       
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

	if ($owner_last_name<>'' and $owner_first_name<>'' and $business_name=='') {

			$result = mysql_query ("select concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
		concat(b.owner_street, ' ', i.barangay_desc, ' ',j.city_municipality_desc, ' ', k.province_desc, ' ',
		b.owner_zip_code) as owner_add, 
		a.business_name, a.owner_id, a.business_id,
		concat(a.business_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
		b.owner_zip_code) as business_add
		from ebpls_business_enterprise a, ebpls_owner b,
		ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_barangay i , ebpls_city_municipality j , ebpls_province k where
		a.owner_id=b.owner_id and b.owner_first_name='$owner_first_name' and b.owner_last_name='$owner_last_name'
		and i.barangay_code = b.owner_barangay_code and j.city_municipality_code = b.owner_city_code 
		and k.province_code = b.owner_province_code and 
		f.barangay_code = a.business_barangay_code and g.city_municipality_code = a.business_city_code 
		and h.province_code = a.business_province_code ");
	} elseif ($owner_last_name<>'' and $owner_first_name=='' and $business_name=='') {

			$result = mysql_query ("select concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
		concat(b.owner_street, ' ', i.barangay_desc, ' ',j.city_municipality_desc, ' ', k.province_desc, ' ',
		b.owner_zip_code) as owner_add, 
		a.business_name, a.owner_id, a.business_id,
		concat(a.business_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
		b.owner_zip_code) as business_add
		from ebpls_business_enterprise a, ebpls_owner b,
		ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_barangay i , ebpls_city_municipality j , ebpls_province k where
		a.owner_id=b.owner_id  and b.owner_last_name='$owner_last_name'
		and i.barangay_code = b.owner_barangay_code and j.city_municipality_code = b.owner_city_code 
		and k.province_code = b.owner_province_code and
		f.barangay_code = a.business_barangay_code and g.city_municipality_code = a.business_city_code 
		and h.province_code = a.business_province_code ");	
		
	} elseif ($owner_last_name=='' and $owner_first_name<>'' and $business_name=='') {

			$result = mysql_query ("select concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
		concat(b.owner_street, ' ', i.barangay_desc, ' ',j.city_municipality_desc, ' ', k.province_desc, ' ',
		b.owner_zip_code) as owner_add, 
		a.business_name, a.owner_id, a.business_id,
		concat(a.business_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
		b.owner_zip_code) as business_add
		from ebpls_business_enterprise a, ebpls_owner b,
		ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_barangay i , ebpls_city_municipality j , ebpls_province k where
		a.owner_id=b.owner_id  and b.owner_first_name='$owner_first_name'
		and i.barangay_code = b.owner_barangay_code and j.city_municipality_code = b.owner_city_code 
		and k.province_code = b.owner_province_code and 
		f.barangay_code = a.business_barangay_code and g.city_municipality_code = a.business_city_code 
		and h.province_code = a.business_province_code ");		
	} elseif ($owner_last_name=='' and $owner_first_name=='' and $business_name<>'') {

			$result = mysql_query ("select concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
		concat(b.owner_street, ' ', i.barangay_desc, ' ',j.city_municipality_desc, ' ', k.province_desc, ' ',
		b.owner_zip_code) as owner_add, 
		a.business_name, a.owner_id, a.business_id,
		concat(a.business_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
		b.owner_zip_code) as business_add
		from ebpls_business_enterprise a, ebpls_owner b,
		ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_barangay i , ebpls_city_municipality j , ebpls_province k where
		a.owner_id=b.owner_id and a.business_name='$business_name'
		and i.barangay_code = b.owner_barangay_code and j.city_municipality_code = b.owner_city_code 
		and k.province_code = b.owner_province_code and  
		f.barangay_code = a.business_barangay_code and g.city_municipality_code = a.business_city_code 
		and h.province_code = a.business_province_code ");
	} else  {

			$result = mysql_query ("select concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
		concat(b.owner_street, ' ', i.barangay_desc, ' ',j.city_municipality_desc, ' ', k.province_desc, ' ',
		b.owner_zip_code) as owner_add, 
		a.business_name, a.owner_id, a.business_id,
		concat(a.business_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
		b.owner_zip_code) as business_add
		from ebpls_business_enterprise a, ebpls_owner b,
		ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_barangay i , ebpls_city_municipality j , ebpls_province k where
		a.owner_id=b.owner_id and a.business_name='$business_name' and b.owner_first_name='$owner_first_name' and b.owner_last_name='$owner_last_name'
		and i.barangay_code = b.owner_barangay_code and j.city_municipality_code = b.owner_city_code 
		and k.province_code = b.owner_province_code and 
		f.barangay_code = a.business_barangay_code and g.city_municipality_code = a.business_city_code 
		and h.province_code = a.business_province_code ");	
		
	}
	
	
while ($getd = mysql_fetch_assoc($result)) {
$pdf->AddPage();
$pdf->SetX(10);
$pdf->Cell(25,5,$getd[business_name],0,1,'L');
$pdf->Cell(25,5,$getd[fulln],0,1,'L');
$pdf->Cell(25,5,$getd[owner_add],0,1,'L');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(25,5,'Please pay the following',0,1,'L');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(25,5,'Business Taxes',0,1,'L');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->SetFont('Arial','',12);
$owner_id = $getd['owner_id'];
$business_id=$getd['business_id'];
	$gettemp =mysql_query("select * from  tempassess a, ebpls_buss_tfo b where a.owner_id = '$owner_id' and a.business_id='$business_id' and a.tfoid=b.tfoid and b.taxfeetype=1 and date_create like '$yearnow%'");

while ($gettax = mysql_fetch_assoc($gettemp)) {

$pdf->Cell(25,4,$gettax[tfodesc],0,0,'L');
$pdf->Cell(55,4,$gettax[compval],0,1,'R');
$tottax = $tottax + $gettax[compval];
}
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(25,4,'Total Tax',0,0,'L');
$pdf->Cell(55,4, number_format($tottax,2) ,0,1,'R');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(25,5,'Fees/Charges',0,1,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell(270,5,'',0,1,'C');
	$gettemp =mysql_query("select * from  tempassess a, ebpls_buss_tfo b where a.owner_id = '$owner_id' and a.business_id='$business_id' and a.tfoid=b.tfoid and b.taxfeetype<>1 and date_create like '$yearnow%'");
while ($getfee = mysql_fetch_assoc($gettemp)) {

$pdf->Cell(25,4,$getfee[tfodesc],0,0,'L');
$pdf->Cell(55,4,$getfee[compval],0,1,'R');
$totfee = $totfee + $getfee[compval];
}
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(25,4,'Total Fee',0,0,'L');
$pdf->Cell(55,4, number_format($totfee,2) ,0,1,'R');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(25,5,'Surcharge',0,1,'L');
$pdf->SetFont('Arial','',12);
$pdf->Cell(270,5,'',0,1,'C');
	$gettemp =mysql_query("select * from  bus_grandamt a where a.owner_id = '$owner_id' and a.business_id='$business_id' and ts like '$yearnow%'");
$getsi = mysql_fetch_assoc($gettemp);

$pdf->Cell(25,4,'Surcharge',0,0,'L');
$pdf->Cell(55,4,$getsi[totpenamt],0,1,'R');
$pdf->Cell(25,4,'Interest',0,0,'L');
$pdf->Cell(55,4,$getsi[si],0,1,'R');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(25,4,'Total Surcharge',0,0,'L');
$totsi = $getsi[totpenamt] + $getsi[si] ;
$pdf->Cell(55,4, number_format($totsi,2),0,1,'R');
}
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(25,5,'Total',0,0,'L');
$topay = number_format($tottax + $totfee + $totsi,2);
$pdf->Cell(55,4,$topay,0,1,'R');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Prepared By :',0,0,'L');


$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_assoc($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Business Masterlist' and sign_type='3'");
$getsignatories1 = @mysql_fetch_assoc($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_assoc($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$datetoday = date('Y - m - d');
$pdf->Cell(172,5,$datetoday,0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_pos],0,1,'L');
$pdf->Output();

?>

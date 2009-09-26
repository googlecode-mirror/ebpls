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
	$this->Cell(340,5,'LIST OF BUSINESS ESTABLISHMENT',0,1,'C');
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


	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
    
	
	

//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($resulta[0],$resulta[1],$resulta[2]);
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',10);
$pdf->SetY(40);
$pdf->SetX(10);
$pdf->Cell(25,5,'SEARCH CRITERIA: ',0,0,'L');
$pdf->SetX(50);
$pdf->Cell(100,5,$criteria,0,1,'L');

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(10,5,'SEQ. NO.',1,0,'C');
$pdf->SetX(15);
$pdf->Cell(25,5,'PERMIT NO.',1,0,'C');
$pdf->SetX(40);
$pdf->Cell(55,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(95);
$pdf->Cell(60,5,'BUSINESS NAME',1,0,'C');
//$pdf->SetX(155);
//$pdf->Cell(60,5,'LINE OF BUSINESS',1,0,'C');
$pdf->SetX(155);
$pdf->Cell(90,5,'BUSINESS ADDRESS',1,0,'C');
//$pdf->SetX(220);
$pdf->Cell(30,5,'LINE OF BUSINESS',1,0,'C');
//$pdf->SetX(270);
$pdf->Cell(30,5,'CAPITAL INVESTMENT',1,0,'C');
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
	$result = mysql_query ("select distinct (c.business_permit_code) as pid, a.business_name,
        concat(a.business_lot_no, ' ', a.business_street, ' ',
        a.business_city_code, ' ', a.business_province_code, ' ', a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
	b.owner_id, a.business_id
        from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
	tempbusnature d, ebpls_buss_nature e where
        d.active=1 and d.bus_code like '$business_nature_code%'
        and d.cap_inv between '$cap_inv1' and '$cap_inv2'
        and d.bus_code = e.natureid and e.psiccode like '$psic%'
        and b.owner_id = a.owner_id and a.business_id = c.business_id and
        c.active=1 and b.owner_last_name like '$owner_last%' and
        c.transaction like '$trans%' and b.owner_id = d.owner_id and
	a.business_id=d.business_id
	and c.application_date between '$date_from' and '$date_to'
        and a.business_barangay_code  like '$brgy_name%'");


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
		$pdf->Cell(55,5,$resulta[fulln],1,0,'L');
		$pdf->SetX(95);
		$pdf->Cell(60,5,$resulta[business_name],1,0,'L');
		$pdf->SetX(155);
		$pdf->Cell(90,5,$resulta[bus_add],1,0,'L');
		$getlineb = mysql_query("select * from tempbusnature a, ebpls_buss_nature b where
                                a.owner_id=$resulta[owner_id] and
                                a.business_id=$resulta[business_id] and
				a.bus_code=b.natureid and 
				a.bus_code like '$business_nature_code%'
				and a.cap_inv between '$cap_inv1' and '$cap_inv2' order by tempid");
/*
		$getlineb = mysql_query("select * from tempbusnature a, ebpls_buss_nature b where
				a.owner_id=$resulta[owner_id] and 
				a.business_id=$resulta[business_id] and a.active=1 
				and a.bus_code='$business_nature_code' 
				and a.cap_inv like '$cap_inv%' and a.last_yr like '$last_yr%'
				and a.bus_code = b.natureid and b.psiccode like '$psic%'");*/
		while ($getlyn = mysql_fetch_array($getlineb)) {	

		if ($qute==1) {
		$pdf->SetX(245);
		$pdf->SetFont('Arial','B',4);
		$pdf->Cell(30,5,$getlyn[naturedesc],1,0,'L');
	//	$pdf->SetX(270);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(30,5,number_format($getlyn[cap_inv],2),1,1,'R');
	//	$pdf->SetX(305);
		$totcal = $totcal + $getlyn[cap_inv];
		} else {
		$qute=1;
	//	$pdf->SetX(245);
                $pdf->SetFont('Arial','B',4);
                $pdf->Cell(30,5,$getlyn[bus_nature],1,0,'L');
        //      $pdf->SetX(270);
                $pdf->SetFont('Arial','B',6);
                $pdf->Cell(30,5,$getlyn[cap_inv],1,1,'R');
        //      $pdf->SetX(305);
                $totcal = $totcal + $getlyn[cap_inv];
		}
		}
		$qute=0;
		$i++;
		$pdf->SetY($pdf->GetY()+5);
	} 

$pdf->SetX(5);
$pdf->Cell(10,5,'',1,0,'L');
$pdf->SetX(15);
$pdf->Cell(25,5,'',1,0,'L');
$pdf->SetX(40);
$pdf->Cell(55,5,'',1,0,'L');
$pdf->SetX(95);
$pdf->Cell(60,5,'',1,0,'L');
$pdf->SetX(155);
$pdf->Cell(90,5,'',1,0,'L');
$pdf->SetX(245);
$pdf->Cell(30,5,'Total Investments' ,1,0,'L');
//$pdf->SetX(270);
$pdf->Cell(30,5,number_format($totcal,2),1,0,'R');
//$pdf->SetX(305);

          
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Recommend Approval:',1,0,'L');
$pdf->Cell(173,5,'Approved:',1,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$report_desc='Business Establishment By Barangay';
include '../report_signatories_footer1.php';

$pdf->Output();

?>


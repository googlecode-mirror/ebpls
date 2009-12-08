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
	$this->Cell(340,5,'Republic of the Philippines',0,1,'C');
/*------------------------------------------------------------
frederick >>> changed these:
	$this->Cell(340,5,$this->lgu,0,1,'C');
	$this->Cell(340,5,$this->prov,0,2,'C');
to these: 																						*/
	$this->Cell(340,5,'Province of '.$this->prov,0,1,'C');
	$this->Cell(340,5,'MUNICIPALITY OF '.strtoupper($this->lgu),0,2,'C');
//SEE change made on lines 111 & 113
//-------------------------------------------------------------
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'MASTER LIST OF BUSINESS ESTABLISHMENTS',0,1,'C');
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'From : '.$this->datef.' To : '.$this->datet,0,1,'C');
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
/*-----------------------------------------------
frederick >> incorrect arrangement, SEE function setLGUinfo on line 30
changed this:
$pdf->setLGUinfo($getlgu[0],$getprov[0],'');
to this:                                  */
$pdf->setLGUinfo($getprov[0],$getlgu[0],'');
//-----------------------------------------------
$pdf->setDateRange($date_from,$date_to);
$pdf->AddPage();
$pdf->AliasNbPages();

$pdf->SetFont('Arial','B',10);
$pdf->SetY(40);
$pdf->SetX(10);
$pdf->Cell(25,5,'',0,0,'L');
$pdf->SetX(50);
$pdf->Cell(100,5,$criteria,0,1,'L');

$Y_Label_position = 45;
$Y_Table_Position = 55;

$dateprinted = date('Y-m-d');
$pdf->SetFont('Arial','B',8);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(340,5,'',0,1,'R');
$pdf->SetFont('Arial','B',6);
$pdf->SetX(5);
$pdf->Cell(10,5,'NO.',1,0,'C');

$pdf->Cell(25,5,'PERMIT NO.',1,0,'C');

$pdf->Cell(150,5,'BUSINESS NAME/ADDRESS/OWNER',1,0,'C');

$pdf->Cell(60,5,'LINE OF BUSINESS',1,0,'C');
//$pdf->SetX(270);
$pdf->Cell(35,5,'CAPITAL INVESTMENTS',1,0,'C');
$pdf->Cell(35,5,'ANNUAL GROSS SALES',1,1,'C');
//$pdf->Cell(30,5,'GROSS RECEIPTS',1,0,'C');
//$pdf->SetX(305);

	if ($brgy_name == "") {
		$brgy_name = "$brgy_name%";
	} else {
		$brgy_name = "$brgy_name";
	}
	if ($trans == 'Retire') {
		$result = mysql_query ("select distinct (c.business_permit_code) as pid, a.business_name,
        concat(a.business_lot_no, ' ', a.business_street, ' ', f.barangay_desc, ' ',
        g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
	b.owner_id, a.business_id
        from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
	tempbusnature d, ebpls_buss_nature e , ebpls_barangay f , ebpls_city_municipality g , ebpls_province h where
        d.bus_code like '$business_nature_code%'
        and d.cap_inv between '$cap_inv1' and '$cap_inv2'
        and d.bus_code = e.natureid and a.business_barangay_code = f.barangay_code and g.city_municipality_code = a.business_city_code 
        and h.province_code = a.business_province_code and e.psiccode like '$psic%'
        and b.owner_id = a.owner_id and a.business_id = c.business_id and
        b.owner_last_name like '$owner_last%' and
        c.transaction like '$trans%' and b.owner_id = d.owner_id and
	a.business_id=d.business_id
	and c.application_date between '$date_from 00:00:00' and '$date_to 23:59:59'
        and a.business_barangay_code  like '$brgy_name' order by a.business_name ASC");
	} else {
	$result = mysql_query ("select distinct (c.business_permit_code) as pid, a.business_name,
        concat(a.business_lot_no, ' ', a.business_street, ' ', f.barangay_desc, ' ',
        g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
	b.owner_id, a.business_id
        from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
	tempbusnature d, ebpls_buss_nature e , ebpls_barangay f , ebpls_city_municipality g , ebpls_province h where
        d.active=1 and d.bus_code like '$business_nature_code%'
        and d.cap_inv between '$cap_inv1' and '$cap_inv2'
        and d.bus_code = e.natureid and a.business_barangay_code = f.barangay_code and g.city_municipality_code = a.business_city_code 
        and h.province_code = a.business_province_code and e.psiccode like '$psic%'
        and b.owner_id = a.owner_id and a.business_id = c.business_id and
        c.active=1 and b.owner_last_name like '$owner_last%' and
        c.transaction like '$trans%' and b.owner_id = d.owner_id and
	a.business_id=d.business_id
	and c.application_date between '$date_from 00:00:00' and '$date_to 23:59:59'
        and a.business_barangay_code  like '$brgy_name' order by a.business_name ASC");
	}
	


	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($re=mysql_fetch_assoc($result))
	{
	$pdf->SetX(5);
		$pdf->Cell(10,5,$i,1,0,'L');
		
/*======================================================
frederick >> to view permit number, changed this
	$pdf->Cell(25,5,$re[business_permit_code],1,0,'L');
to this:                                              */
		$pdf->Cell(25,5,$re[pid],1,0,'L');
//=======================================================
		$pdf->Cell(150,5,$re[business_name]."/".$re[bus_add]."/".$re[fulln],1,0,'L');
		
		if ($trans == 'Retire') {
			$getline = mysql_query ("select * from tempbusnature a, ebpls_buss_nature b
								 where a.owner_id='$re[owner_id]' and
								 a.business_id='$re[business_id]' and a.bus_code=b.natureid and retire ='1'");
		} else {
			$getline = mysql_query ("select * from tempbusnature a, ebpls_buss_nature b
								 where a.owner_id='$re[owner_id]' and
								 a.business_id='$re[business_id]' and a.active=1
								 and a.bus_code=b.natureid");
		}
		
		while ($getl = mysql_fetch_assoc($getline)) {
			
		$pdf->SetX(190);
		$pdf->Cell(60,5,$getl[naturedesc],1,0,'L');
			if ($getl[transaction]=='New') {
				$getcap = mysql_query ("select * from tempbusnature a, ebpls_buss_nature b
								 where a.owner_id='$re[owner_id]' and
								 a.business_id='$re[business_id]' and a.bus_code='$getl[bus_code]'
								 and a.bus_code=b.natureid order by tempid");
				$getcap = mysql_fetch_assoc($getcap);
				$totcap = $totcap + $getcap[cap_inv];
			} else {
				$totgross = $totgross + $getl[last_yr];
			}
		$pdf->Cell(35,5,number_format($getcap[cap_inv],2),1,0,'R');
		$pdf->Cell(35,5,number_format($getl[last_yr],2),1,1,'R');	
		}
		
    $i++;	
	} 

$pdf->SetX(5);
$pdf->Cell(10,5,'',0,0,'L');

$pdf->Cell(25,5,'',0,0,'L');

$pdf->Cell(150,5,'',0,0,'L');

$pdf->Cell(60,5,'Total Investments' ,0,0,'L');
$pdf->Cell(35,5,number_format($totcap,2),0,0,'R');
$pdf->Cell(35,5,number_format($totgross,2),0,0,'R');
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

$report_desc='Business Establishment';
//include '../report_signatories_footer1.php';

$pdf->Output();

?>

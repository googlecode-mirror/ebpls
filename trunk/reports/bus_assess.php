<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     

$permit_type='Business';
include'../includes/variables.php';

//$dbLink = get_db_connection();
include_once("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);


$ddt ="b.business_id =$business_id and b.owner_id=$owner_id";




	
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
	$this->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(190,5,$this->lgu,0,1,'C');
	$this->Cell(190,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(190,5,$this->office,0,2,'C');
	$this->Cell(30,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(190,5,'APPLICATION TO ENGAGE IN BUSINESS',0,1,'C');
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

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
//$pdf=new FPDF('P','mm','A4');
$pdf=new PDF('P','mm','A4');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'');
$pdf->AddPage();
$pdf->AliasNbPages();


$getdata = mysql_query("select * from ebpls_owner a, ebpls_barangay b, ebpls_zone c,
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.owner_barangay_code=b.barangay_code and
			a.owner_zone_code=c.zone_code and a.owner_district_code=d.district_code and
			a.owner_city_code=e.city_municipality_code and a.owner_province_code=f.province_code and
			a.owner_city_code=g.upper");    

		
			
			
$getda=FetchArray($dbtype,$getdata);


if ($getda[zone_desc]=='') {
	
	$getdata = mysql_query("select * from ebpls_owner a, ebpls_barangay b, 
						ebpls_district d, ebpls_city_municipality e, ebpls_province f,
						ebpls_zip g where owner_id = $owner_id and a.owner_barangay_code=b.barangay_code and
			 a.owner_district_code=d.district_code and
			a.owner_city_code=e.city_municipality_code and a.owner_province_code=f.province_code and
			a.owner_city_code=g.upper");  
			 $getda=FetchArray($dbtype,$getdata); 
}
		
			
	



$add = "$getda[owner_street] $getda[barangay_desc] $getda[zone_desc] ,$getda[district_desc] 
		$getda[city_municipality_desc], $getda[province_desc], $getda[zip_code]";


	$result=mysql_query("select a.business_permit_code, a.application_date, 
				b.business_name, a.transaction, 
				a.business_permit_id, 
				b.employee_male, b.employee_female,
				concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name) as fulln,
				b.business_payment_mode, business_occupancy_code, 
				concat(b.business_lot_no, ' ', b.business_street, ' ', f.barangay_desc, ' ',
        		g.city_municipality_desc, ' ', h.province_desc, ' ', b.business_zip_code) as bus_add
        		,c.owner_civil_status, 
        		c.owner_birth_date,
				c.owner_citizenship, 
				b.business_sec_reg_no , 
				b.business_dti_reg_no ,
				b.business_dti_reg_date,
				c.owner_tin_no, i.business_category_desc, i.tax_exemption
				from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, 
				ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_business_category i
				where $ddt and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_barangay_code = f.barangay_code and g.city_municipality_code = b.business_city_code 
        		and h.province_code = b.business_province_code and
        		b.business_category_code=i.business_category_code") or die(mysql_error());
    $resulta=mysql_fetch_assoc($result);


          


$pdf->SetFont('Arial','',10);


$pdf->SetFont('Arial','BU',12);
$pdf->SetX(5);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'SWORN DECLARATION',0,1,'C');
$pdf->Cell(40,5,'',0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Transaction Type: ',0,0,'L');
$pdf->Cell(145,5,$stat,0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'Owner/Proprietor: ',0,0,'L');
$pdf->Cell(145,5,$resulta[fulln],0,1,'L');

$pdf->SetX(5);
$pdf->Cell(45,5,'Resident of Applicant: ',0,0,'L');
$pdf->Cell(145,5,$add,0,1,'L');
$pdf->SetX(5);

$tdate = date('Y-m-d');
$agem = mysql_query("select round(datediff('$tdate','$resulta[owner_birth_date]')/365)");
$agem = mysql_fetch_row($agem);


$pdf->Cell(25,5,"Age: ",0,0,'L');
$pdf->Cell(25,5,$agem[0],0,0,'L');
$pdf->SetX(50);
$pdf->Cell(25,5,'Status: ',0,0,'L');
$pdf->Cell(25,5,$resulta[owner_civil_status],0,0,'L');
$pdf->SetX(100);
$pdf->Cell(25,5,'Citizenship: ',0,0,'L');
$pdf->Cell(25,5,$resulta[owner_citizenship],0,1,'L');
$pdf->SetX(5);
$pdf->Cell(25,5,'TIN: ',0,0,'L');
$pdf->Cell(25,5,$resulta[owner_tin_no],0,0,'L');
$pdf->SetX(50);
$pdf->Cell(35,5,'Ownership Type: ',0,0,'L');
$pdf->Cell(25,5,$resulta[business_category_desc],0,0,'L');
$pdf->SetX(130);
$pdf->Cell(25,5,'Exemption: ',0,0,'L');
$pdf->Cell(25,5,$resulta[tax_exemption].'%',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'Name of Business: ',0,0,'L');
$pdf->Cell(145,5,$resulta[business_name],0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'Address of Business: ',0,0,'L');
$pdf->Cell(145,5,$resulta[bus_add],0,1,'L');






$pdf->Cell(105,5,'Line of Business ',1,0,'L');
$pdf->Cell(45,5,'Capital Investment',1,0,'C');
$pdf->Cell(45,5,'Gross Sales',1,1,'C');

$getline = mysql_query ("select * from tempbusnature a, ebpls_buss_nature b
								 where a.owner_id='$owner_id' and
								 a.business_id='$business_id' and a.active=1
								 and a.bus_code=b.natureid");
		while ($getl = mysql_fetch_assoc($getline)) {
			
		
		$pdf->Cell(105,5,$getl[naturedesc],0,0,'L');
			
				$getcap = mysql_query ("select * from tempbusnature a, ebpls_buss_nature b
								 where a.owner_id='$owner_id' and
								 a.business_id='$business_id' and a.bus_code='$getl[bus_code]'
								 and a.bus_code=b.natureid order by tempid");
								 
				$getcap = mysql_fetch_assoc($getcap);
				$totcap = $totcap + $getcap[cap_inv];
			
				$totgross = $totgross + $getl[last_yr];
		
		$pdf->Cell(45,5,number_format($getcap[cap_inv],2),0,0,'R');
		$pdf->Cell(45,5,number_format($getl[last_yr],2),0,1,'R');	
		}
		

$pdf->Cell(95,5,' ',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'No. of Employees: Male=',0,0,'L');
$pdf->Cell(43,5,$resulta[employee_male]. ' Female= '.$resulta[employee_female],0,0,'L');
$pdf->SetX(90);
$pdf->Cell(45,5,'DTI Reg. No.: ',0,0,'L');
$pdf->Cell(25,5,$resulta[business_dti_reg_no],0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'',0,0,'L');
$pdf->Cell(45,5,'',0,0,'L');
$pdf->SetX(90);
$pdf->Cell(45,5,'SEC No.: ',0,0,'L');
$pdf->Cell(25,5,$resulta[business_sec_reg_no],0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'',0,0,'L');
$pdf->Cell(45,5,'',0,0,'L');
$pdf->SetX(90);
$pdf->Cell(45,5,'DTI Expiration Date: ',0,0,'L');
$pdf->Cell(25,5,$resulta[business_dti_reg_date],0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'',0,0,'L');
$pdf->Cell(45,5,'',0,1,'L');

$pdf->SetX(15);
$pdf->Cell(190,5,'I declared under penalties of perjury that this declaration has been made in good faith, verifies',0,1,'C');
$pdf->SetX(5);
$pdf->Cell(190,5,'by me and to the best of my knowledge and belief is true and correct pursuant to the provision',0,1,'C');
$pdf->SetX(5);
$pdf->Cell(190,5,'of the Tax Code of '.$getlgu[0].", ".$getprov[0],0,1,'C');

$pdf->SetX(5);
$pdf->Cell(45,5,'',0,1,'L');
$pdf->Cell(45,5,'',0,1,'L');
$pdf->Cell(45,5,'',0,1,'L');
$pdf->Cell(45,5,'',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(45,5,'Signature of Taxpayer',0,0,'C');
$pdf->SetX(50);
$pdf->Cell(45,5,'CTC No.',0,0,'C');
$pdf->SetX(100);
$pdf->Cell(45,5,'Place of Issue',0,0,'C');
$pdf->SetX(150);
$pdf->Cell(45,5,'Date',0,1,'C');
$pdf->Cell(45,5,'',0,1,'L');
$pdf->Cell(45,5,'',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Prepared By :',0,1,'L');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_assoc($getuser);

$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,$getuser[firstname].' '.$getuser[lastname],0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$datetoday = date('Y - m - d');
$pdf->Cell(172,5,$datetoday,0,0,'L');


$pdf->Cell(45,5,'',0,1,'L');
$pdf->Cell(45,5,'',0,1,'L');

$pdf->SetX(5);
$pdf->Cell(45,5,'COMPUTATIONS: ',0,0,'C');
$grandtot=0;
$lineb = mysql_query("select * from tempbusnature where owner_id=$owner_id and
                        business_id=$business_id and active=1");
while ($p=mysql_fetch_array($lineb)){

$pdf->Cell(45,5,'',0,1,'L');

$pdf->SetX(15);
$pdf->Cell(45,5,$p[bus_nature],1,1,'C');

$linebus = mysql_query("select a.*, b.*, c.* from tempbusnature a, tempassess b, ebpls_buss_tfo c 
			where a.owner_id=$owner_id and
                        a.business_id=$business_id and a.active=1  and a.bus_code=b.natureid and
                        a.business_id=b.business_id and a.owner_id=b.owner_id 
			and b.active=1 and b.tfoid=c.tfoid and a.bus_code='$p[bus_code]'");

	while ($g=mysql_fetch_array($linebus)) {
	$pdf->SetX(15);
	$pdf->Cell(45,5,$g[tfodesc],0,0,'L');
	$pdf->SetX(60);
	$pdf->Cell(30,5,number_format($g[compval],2),0,1,'R');
	$totme = $totme + $g[compval];
	}
	$totm= $totm + $totme;
	$grandtot = $grandtot + $totme;
	$pdf->SetX(15);
        $pdf->Cell(45,5,"TOTAL Php ",0,0,'R');
        $pdf->SetX(60);
        $pdf->Cell(30,5,number_format($totme,2),0,1,'R');
        $totme = 0;	

}


$pdf->Cell(45,5,'',0,1,'L');
$pdf->Cell(45,5,'',0,1,'L');
//$grandtot = $gtot;

if ($grandtot<>$totm) {
$pdf->SetX(15);
$pdf->Cell(45,5,"GRAND TOTAL Php ",0,0,'R');
$pdf->SetX(60);
$pdf->Cell(30,5,number_format($grandtot,2),0,1,'R');
}
//$pdf->Cell(45,5,'',0,1,'L');



//$intloop++;
$intloop = $loppage+1;



$pdf->Output();
?>

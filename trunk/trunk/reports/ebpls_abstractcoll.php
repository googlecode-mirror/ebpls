<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

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
	$this->Cell(680,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(680,5,$this->lgu,0,1,'C');
	$this->Cell(680,5,$this->prov,0,2,'C');
	$this->SetFont('Arial','B',12);
	$this->Cell(680,5,$this->office,0,2,'C');
	$this->Cell(680,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(680,5,'ABSTRACT OF COLLECTION REPORT' ,0,1,'C');
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
$pdf=new PDF('L','mm','ledger');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'');


$pdf->AddPage();
$pdf->AliasNbPages();
$Y_Label_position = 42;
$Y_Table_Position = 46;
$pdf->SetFont('Arial','B',12);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(680,5,'From '.$date_from. ' to '.$date_to ,0,1,'C');

$Y_Label_position = 50;
$Y_Table_Position = 55;


$pdf->SetFont('Arial','B',10);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(50,5,'NAME OF PAYOR',1,0,'C');
$pdf->Cell(240,5,'BUSINESS/ADDRESS/NATURE',1,0,'C');
$pdf->Cell(40,5,'OR #',1,0,'C');
// $pdf->SetX(55);
// 	$v = 0;
// 	$xx = 55;

$staxfee = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_preference",
		"staxesfees","");
$prefset = FetchArray($dbtype,$staxfee);			
	//	$result = mysql_query("select tfodesc from ebpls_buss_tfo limit 8") 
$sassess = $prefset[staxesfees];	

  $result = mysql_query("select a.tfoid, a.tfodesc from ebpls_buss_tfo a, rpt_temp_abs b
			where a.tfoid=b.tfoid ") ;
	while ($resulta=mysql_fetch_row($result))
	{
		$v++;
		$tfoid[$v] = $resulta[0];
		$pdf->Cell(30,5,$resulta[1],1,0,'C'); //tax/fee name
		

	}
$pdf->Cell(10,5,'',0,1,'C')	;
$result = mysql_query ("select concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
		concat(b.owner_street, ' ', i.barangay_desc, ' ',j.city_municipality_desc, ' ', k.province_desc, ' ',
		b.owner_zip_code) as owner_add, 
		a.business_name, a.owner_id, a.business_id,
		concat(a.business_street, ' ', f.barangay_desc, ' ',g.city_municipality_desc, ' ', h.province_desc, ' ',
		b.owner_zip_code) as business_add
		from ebpls_business_enterprise a, ebpls_owner b,
		ebpls_barangay f , ebpls_city_municipality g , ebpls_province h, ebpls_barangay i , ebpls_city_municipality j , ebpls_province k where
		a.owner_id=b.owner_id 
		and i.barangay_code = b.owner_barangay_code and j.city_municipality_code = b.owner_city_code 
		and k.province_code = b.owner_province_code and 
		f.barangay_code = a.business_barangay_code and g.city_municipality_code = a.business_city_code 
		and h.province_code = a.business_province_code ");
		
while ($gd = mysql_fetch_assoc($result)) {
	$owner_id = $gd['owner_id'];
	$business_id=$gd['business_id'];
	//check if line is paid
		$lp = mysql_query("select * from ebpls_transaction_payment_or_details a, ebpls_transaction_payment_or b where a.trans_id='$owner_id' and a.payment_id='$business_id' and a.ts between '$date_from 00:00:00' and '$date_to 23:59:59' and a.or_no = b.or_no");
		//echo "select * from tempbusnature where owner_id='$owner_id' and business_id='$business_id' and recpaid='1'";
		//$lp = mysql_query("select * from tempbusnature a, ebpls_buss_nature b,   ebpls_transaction_payment_or_details c where owner_id='$owner_id' and business_id='$business_id' and recpaid='1' and a.bus_code=b.natureid and
		//a.owner_id=c.trans_id and a.business_id=c.payment_id and c.ts between  '$date_from 00:00:00' and '$date_to 23:59:59'");
		while ($gp = mysql_fetch_assoc($lp)) {
			//get details
				$f=0;
	
				$pdf->SetX(5);
				$pdf->Cell(50,5,$gd[fulln],1,0,'C');
				$pdf->Cell(240,5,$gd[business_name]."/".$gd[business_add],1,0,'L');
				
				//get or
				$getor = mysql_query("select * from ebpls_transaction_payment_or_details a, ebpls_transaction_payment_or b where a.trans_id='$owner_id' and a.payment_id='$business_id' and a.or_no=b.or_no");
				$getor = mysql_fetch_assoc($getor);
				$RX = $pdf->GetX();
				$pdf->Cell(40,5,'',1,0,'C');
				//$pdf->Cell(40,5,$getor[or_no],1,0,'C');
				//get paytax/fee
				$gettax = mysql_Query("select * from tempassess a where a.owner_id='$owner_id' and a.business_id='$business_id'");
				$dfv = 0;
				while ($gett = mysql_fetch_assoc($gettax)) {
				//while ($dfv < $v) {
					$dfv++;
					//display
					$f++;
					
					$disp = mysql_Query("select sum(amount) from ebpls_payment_details a, rpt_temp_abs b where a.owner_id='$owner_id' and a.business_id='$business_id' and a.tfoid='$tfoid[$f]' and a.tfoid = b.tfoid and or_no = '$gp[payment_code]' order by or_no asc");
					//echo "select * from ebpls_payment_details a where a.owner_id='$owner_id' and a.business_id='$business_id' and a.tfoid='$tfoid[$f]' <br>";
					//$disp = mysql_Query("select * from tempassess a where a.owner_id='$owner_id' and a.business_id='$business_id' and a.natureid='$gp[bus_code]' and a.tfoid='$tfoid[$f]'");
					$cntm = mysql_num_rows($disp);
					if ($tfoid[$f]<>'') {
						
						if ($cntm==0 and $sassess==1) {
							//reg fee
								$disp = mysql_Query("select sum(amount) from ebpls_payment_details a, rpt_temp_abs b where a.owner_id='$owner_id' and a.business_id='$business_id' and a.tfoid='$tfoid[$f]' and a.tfoid = b.tfoid and or_no = '$gp[payment_code]' order by or_no asc");
								//$disp = mysql_Query("select * from tempassessz a where a.owner_id='$owner_id' and a.business_id='$business_id' and a.tfoid='$tfoid[$f]'");
						} 
						
						$dis = mysql_fetch_row($disp);
						$pdf->Cell(30,5,number_format($dis[0],2),1,0,'R'); //tax/fee name
						
						$newv[$f] = $newv[$f] + $dis[0];
					}
				}
				$pdf->SetX($RX);
				$pdf->Cell(40,5,$gp[payment_code],0,0,'C');
				
				
				
				
				
				
			$pdf->Cell(10,5,'',0,1,'C')	;	
				
		}
}

$pdf->SetX(5);
$pdf->Cell(50,5,'',1,0,'C');
$pdf->Cell(240,5,'',1,0,'L');
$pdf->Cell(40,5,'Total',1,0,'L');
$r = 0;
while ($r < $v) {
	$r++;
	$pdf->Cell(30,5,number_format($newv[$r],2),1,0,'R'); //tax/fee name
}
$pdf->Cell(10,5,'',0,1,'C')	;							
			
                                                                                                 
//$pdf->SetY(-18);
$pdf->SetX(5);
$pdf->Cell(10,5,'',0,1,'C')	;	                                                                                                
$pdf->SetFont('Arial','B',10);
$pdf->Cell(472,5,'Prepared By :',0,0,'L');
$pdf->Cell(172,5,'Noted By :',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Top Business Establishment' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(472,5,'',0,0,'C');
$pdf->Cell(172,5,$getsignatories1[gs_pos],0,1,'L');


$report_desc='Abstract of Collection';
//include 'report_signatories_footer1.php';

//Robert

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[2],1,0,'C');

$pdf->Output();

?>



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

//$criteria="$brgy_name $owner_last $trans $cap_inv $last_yr";
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
	$this->Cell(200,5,'NOTICE OF BUSINESS TAX COLLECTION',0,1,'C');
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
$pdf=new PDF('P','mm','A4');

//$pdf->SetX(305);

	if ($brgy_name == "") {
		$brgy_name = "$brgy_name%";
	} else {
		$brgy_name = "$brgy_name";
	}
	$result = mysql_query ("select distinct (c.business_permit_code) as pid, 
	a.business_name,
        concat(a.business_lot_no, ' ', a.business_street, ' ', f.barangay_desc, ' ',
        g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
	b.owner_id, a.business_id, 	concat(b. owner_street, ' ', f.barangay_desc, ' ',
        g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as own_add, 
        a.business_payment_mode, b.owner_gender 
    from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
	    ebpls_barangay f , ebpls_city_municipality g , ebpls_province h 
	where
        a.business_barangay_code = f.barangay_code 
        and g.city_municipality_code = a.business_city_code 
        and h.province_code = a.business_province_code
        and 
        b.owner_barangay_code = f.barangay_code 
        and g.city_municipality_code = b.owner_city_code 
        and h.province_code = b.owner_province_code 
        and b.owner_id = a.owner_id and a.business_id = c.business_id and
        c.active=1  
	and c.application_date between '$date_from 00:00:00' and '$date_to 23:59:59'
    order by a.business_name ASC");
//get penalty dates
    $getpen = mysql_Query("select * from ebpls_buss_penalty1");
    $pensked = mysql_fetch_assoc($getpen);
    $qtr1 = $pensked[qtrdue1];
    $qtr2 = $pensked[qtrdue2];
    $qtr3 = $pensked[qtrdue3];
    $qtr4 = $pensked[qtrdue4];
    $sem1 = $pensked[semdue1];
    $sem2 = $pensked[semdue2];
    $getpen = mysql_Query("select * from ebpls_buss_penalty");
    $pensked = mysql_fetch_assoc($getpen);
	$ann = $pensked[renewaldate];
	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($re=mysql_fetch_assoc($result))
	{
		
		
		if (strtolower($re[business_payment_mode])=='quarterly') {
				$watqtr = date('m', strtotime($date_to)) /4;
			
				if ($watqtr< 1) {
					$qtrnow = 1;
					$qtrc = $qtr1;
				} elseif ($watqtr>=1 and $watqtr<1.75) {
					$qtrnow=2;
					$qtrc = $qtr2;
				} elseif ($watqtr>=1.75 and $watqtr<2.5) {
					$qtrnow=3;
					$qtrc = $qtr3;
				} else {
					$qtrnow=4;
					$qtrc = $qtr4;
				}
		} elseif (strtolower($re[business_payment_mode])=='semi-annual') {
				$watqtr = date('m') /4;
				
				if ($watqtr<= 3) {
					$qtrnow = 1;
					$qtrc = $sem1;
				} else {
					$qtrnow=2;
					$qtrc = $sem2;
				}
		} elseif (strtolower($re[business_payment_mode])=='annual') {
				
					$qtrnow = 1;
					$qtrc = $ann;
				
		}		
			
			//check if have payment
				$hispay = mysql_query("select * from ebpls_transaction_payment_or_details where
									trans_id='$re[owner_id]' and payment_id='$re[business_id]' and
									payment_part='$qtrnow'");
				$hispay = mysql_num_rows($hispay);
				//check if no payment compare with penalty date
				if ($hispay==0) {
					$pendate =date('Y-m-d',strtotime(date('Y',strtotime($date_to))."-".$qtrc));
					$pd = mysql_query("select datediff('$date_to','$pendate')/365 as pdate");
					$pd = mysql_fetch_assoc($pd);
					$pd = $pd[pdate];
					$yearnow=date('Y');
					if ($pd > 0) {
						//delinquent
			
							$pdf->setLGUinfo($getlgu[0],$getprov[0],'');

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
							$pdf->Cell(340,5,$dateprinted,0,1,'R');
							$pdf->SetFont('Arial','B',10);
							$pdf->SetX(5);
/* start mulitcell */

							$pdf->SetX(15);		
							$pdf->Cell(70,5,strtoupper($re[business_name]),0,1,'L');
							$pdf->SetX(15);
							$pdf->MultiCell(70,5,strtoupper($re[bus_add]),0,'L');
							$pdf->SetX(15);		
							$pdf->Cell(70,5,strtoupper($re[fulln]),0,1,'L');
							$pdf->SetX(15);
							$pdf->MultiCell(70,5,strtoupper($re[own_add]),0,'L');
							$pdf->SetX(15);
							if ($re[owner_gender]=='M') {
								$grit = 'Sir,';
							} else {
								$grit = 'Madam,';
							}
							$yearnow = date('Y', strtotime($date_to));
						
							//get amount paid
							$hispay = mysql_query("select sum(b.total_amount_paid) as totp
									 from ebpls_transaction_payment_or_details a,
									 	  ebpls_transaction_payment_or b where
									a.trans_id='$re[owner_id]' and a.payment_id='$re[business_id]' 
									and a.or_no=b.or_no and b.or_date like '$yearnow%'");
						
							$hpa = mysql_fetch_assoc($hispay);
							
							$gettot = mysql_query("select sum(grandamt) as totgrand from
											bus_grandamt where owner_id='$re[owner_id]' and 
											business_id='$re[business_id]' and
											ts ='$yearnow' ");
											
											//and	paypart='$qtrnow'
							$getto = mysql_fetch_assoc($gettot);
							$unpaid = $getto[totgrand] - $hpa[totp];
							
							$lasttran = mysql_query("select b.or_date 
									 from ebpls_transaction_payment_or_details a,
									 	  ebpls_transaction_payment_or b where
									a.trans_id='$re[owner_id]' and a.payment_id='$re[business_id]' 
									and a.or_no=b.or_no and b.or_date like '$yearnow%' order by b.or_no desc");
							
							$lasttran = mysql_fetch_assoc($lasttran);
							$lasttran = date('d M. Y', strtotime($lasttran[or_date]));
							
							
							$pdf->Cell(10,15,$grit,0,1,'L');
							$pdf->SetX(25);
							$pdf->MultiCell(170,5,"Please be informed that based on our records on file, you 
							are delinquent in the payment of business taxes, fees and charges. 
							Your last payment transaction was made on ".$lasttran,0,'L');
							$pdf->Cell(10,5,'',0,1,'L');
							$pdf->SetX(25);
							//$pdf->MultiCell(170,5,"MAG BAYAD KA HINAYUPAK KA!!!!!!!!!!!",0,'L');
							
														
					
					}
				}
			
				
		
		
		




//$pdf->Cell(30,5,'GROSS RECEIPTS',1,0,'C');		
$pdf->Cell(10,5,'',0,1,'L');		
$pdf->Cell(10,5,'',0,1,'L');
		
    $i++;	
	} 


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

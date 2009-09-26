<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$criteria="$brgy_name $cap_inv $last_yr";
$list_option =$list_option;
class PDF extends FPDF
{
var $prov;
var $lgu;
var $office;
var $y0;
var $list_op;

	function setLGUinfo($p='', $l='', $o='',$lop) {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
		$this->list_op='TOP BUSINESS ESTABLISHMENTS BY '.$lop;
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
	$this->SetFont('Arial','BU',14);
	$this->Cell(340,5,$this->list_op,0,1,'C');
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

if ($range2 == "" || $range2 == 0) {
	$range2 = 9999999999999;
}

	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
      $getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
 
    
	
	

//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($getlgu[0],$getprov[0],'Office of the Treasurer',strtoupper($list_option));
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
$pdf->SetY($Y_Label_position-10);
$dateprinted = date('Y-m-d');
$pdf->SetX(5);
$pdf->Cell(340,5,$dateprinted,0,1,'R');
$pdf->SetX(5);
$pdf->Cell(10,5,'SEQ. NO.',1,0,'C');
$pdf->SetX(15);
$pdf->Cell(25,5,'PERMIT NO.',1,0,'C');
$pdf->SetX(40);
$pdf->Cell(55,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(95);
$pdf->Cell(60,5,'BUSINESS NAME',1,0,'C');
$pdf->SetX(155);
$pdf->Cell(90,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->Cell(30,5,strtoupper($list_option),1,0,'C');
$pdf->Cell(30,5,'TAX DUE',1,0,'C');
$pdf->Cell(30,5,'TOTAL PAYMENT',1,0,'C');

$date_from = str_replace("/", "-", $date_from);
$date_to = str_replace("/", "-", $date_to);
if ($brgy_name != "") {
	$brgy_name = "$brgy_name";
} else {
	$brgy_name = "$brgy_name%";
}
if (strtolower($list_option)=='capital investment') {
		$nnquery = "i.cap_inv between '$range1' and '$range2'";
		$qorder = "i.cap_inv";
                                
		} else {
		$nnquery = "i.last_yr between '$range1' and '$range2'";
		$qorder = "i.last_yr";
		 }
		 
	$result = mysql_query ("select distinct (c.business_permit_code) as pid, a.business_name,        concat(a.business_lot_no, ' ', a.business_street, ' ', f.barangay_desc, ' ',
        g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
        b.owner_id, a.business_id, i.cap_inv, i.last_yr 
        from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
        ebpls_barangay f , ebpls_city_municipality g , ebpls_province h , tempbusnature i where
        a.business_barangay_code = f.barangay_code and g.city_municipality_code = a.business_city_code 
        and h.province_code = a.business_province_code
        and b.owner_id = a.owner_id and a.business_id = c.business_id and
        c.active=1 and c.application_date between '$date_from 00:00:00' and '$date_to 23:59:59'
        and a.business_barangay_code  like '$brgy_name' and i.business_id = a.business_id and i.owner_id = b.owner_id and 
		$nnquery order by $qorder DESC limit $list_limit");
	

	

	$i = 1;
	$pdf->SetY($Y_Table_Position);
	$totinv1 = 0;
	while ($resulta=mysql_fetch_assoc($result))
	{
		 $totdue=0;
                $totfee=0;
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
		if (strtolower($list_option)=='capital investment') {
			$totinv =  $resulta[cap_inv];
		} else {
			$totinv =  $resulta[last_yr];
		}
		$totinv1 =  $totinv1 + $totinv;
		$pdf->Cell(30,5,number_format($totinv,2),1,0,'R');
		
		$getlineb = mysql_query("select sum(a.compval) as tax_due 
				from tempassess a where a.owner_id=$resulta[owner_id] and
                                a.business_id=$resulta[business_id] and
                                a.active=1 and natureid<>'' and taxfeeid<>''");

                $resultb = mysql_fetch_assoc($getlineb);
               
         $totdue = $resultb[tax_due];
		$gtotdue = $gtotdue + $totdue;       
                
                
                
	$dec= SelectDataWhere($dbtype,$dbLink,"ebpls_buss_preference","");
$sas = FetchArray($dbtype,$dec);
$sas = $sas[sassess];
if ($sas=='') {
	$resultf = SelectDataWhere($dbtype,$dbLink,"ebpls_buss_tfo",
			"where tfoindicator='1' and tfostatus='A' and taxfeetype<>'1' and 
			tfodesc not in (select tfodesc from 
			ebpls_buss_tfo where tfodesc like 'garbage%')");

$cntfee = NumRows($dbtype,$resultf);
$feetype = 1;
while ($getf=FetchRow($dbtype,$resultf))
	{
		$getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, 
			fee_exempt b, ebpls_buss_tfo c","a.*",
                        "where a.business_id=$resulta[business_id] and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$getf[0] and b.tfoid=$getf[0] and
                        b.active=1");
		$getfeex = NumRows($dbtype,$getex);
		if ($getfeex>0) {
			$exemptedfee = $exemptedfee + $getf[6]; 
		        $usemin = 'Fee Exempted ';
		        $getf[6]=0;
		}


    $regfee = $regfee + $getf[6];
	$totfee = $totfee+$getf[6];
	$usemin='';
	}               
 ///garbage fee in place

$resultf = SelectMultiTable($dbtype,$dbLink,"ebpls_buss_tfo a, ebpls_business_enterprise b,
                        ebpls_barangay c","a.*",
			"where a.tfoindicator='1' and
                        a.tfostatus='A' and a.taxfeetype='2' and
                        a.tfodesc like 'garbage%' and
			b.business_id=$resulta[business_id] and b.owner_id=$resulta[owner_id] and
			b.business_barangay_code=c.barangay_code and 
			c.g_zone=1");
        while ($getf=FetchRow($dbtype,$resultf))
        {
                $getex = SelectMultiTable($dbtype,$dbLink,"ebpls_business_enterprise a, 
			fee_exempt b, ebpls_buss_tfo c"," a.*",
                        "where a.business_id=$resulta[business_id] and
                        a.business_category_code=b.business_category_code and
                        c.tfoid=$getf[0] and b.tfoid=$getf[0] and
                        b.active=1");
                $getfeex = NumRows($dbtype,$getex);
                if ($getfeex>0) {
                        $exemptedfee = $exemptedfee + $getf[6];
                        $usemin = 'Fee Exempted ';
                        $getf[6]=0;
                }

	 
	$totfee = $totfee+$getf[6];
	        $usemin='';


	}
	              
		$totdue = $totdue + $totfee;
		$gtotdue = $gtotdue + $totfee;
		
}
		$pdf->Cell(30,5,number_format($resultb[tax_due] + $totfee ,2),1,0,'R');
		
		$getlineb = mysql_query("select a.transaction
                                from ebpls_business_enterprise_permit a where a.owner_id=$resulta[owner_id] and
                                a.business_id=$resulta[business_id] and
                                a.active=1");
		$istat = mysql_fetch_assoc($getlineb);
		$istat = $istat[transaction];

		$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b",
                        "sum(a.total_amount_paid) as tot",
                        "where a.or_no=b.or_no and b.trans_id=$resulta[owner_id] and
                        b.or_entry_type='CASH' and
                        b.payment_id=$resulta[business_id] and b.transaction='$istat'");
		$getcash = FetchRow($dbtype,$getcas);
		$totcash = $getcash[0];
		
		$getclear = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_check a,
                        ebpls_transaction_payment_or b,
                        ebpls_transaction_payment_or_details c","sum(a.check_amount)",
                        "where a.or_no=b.or_no and a.or_no=c.or_no and b.or_no=c.or_no and
                        c.or_entry_type='CHECK' and a.check_status='CLEARED' and
                        c.transaction='$istat' and
                        c.trans_id=$resulta[owner_id] and c.payment_id=$resulta[business_id]");
		$totcheck = FetchRow($dbtype,$getclear);
		$totcheck = $totcheck[0];

	
		$totpay = $totcheck + $totcash;
		$gtotpay = $gtotpay + $totpay;
		$pdf->Cell(30,5,number_format($totpay,2),1,0,'R');
	
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
$pdf->Cell(90,5,'' ,1,0,'L');
$pdf->Cell(30,5,'',1,0,'R');
$pdf->Cell(30,5,'',1,0,'R');
$pdf->Cell(30,5,'',1,0,'R');

$pdf->SetX(5);
$pdf->Cell(10,5,'',1,0,'L');
$pdf->SetX(15);
$pdf->Cell(25,5,'',1,0,'L');
$pdf->SetX(40);
$pdf->Cell(55,5,'',1,0,'L');
$pdf->SetX(95);
$pdf->Cell(60,5,'',1,0,'L');
$pdf->SetX(155);
$pdf->Cell(90,5,'Total ' ,1,0,'R');
$pdf->Cell(30,5,number_format($totinv1,2),1,0,'R');
$pdf->Cell(30,5,number_format($gtotdue,2),1,0,'R');
$pdf->Cell(30,5,number_format($gtotpay,2),1,0,'R');

//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(270,5,'',0,1,'C');
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
$getsignatories = @mysql_query("select * from report_signatories where report_file='Top Business Establishment' and sign_type='3'");
$getsignatories1 = @mysql_fetch_assoc($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_assoc($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,'',0,0,'C');
$pdf->Cell(172,5,$getsignatories1[gs_pos],0,1,'L');


$report_desc='Top Business Establishment';
include '../report_signatories_footer1.php';

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

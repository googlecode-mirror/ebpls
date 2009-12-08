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
/* ---------------------------------------------------------------
frederick >>> change these:

	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->lgu,0,1,'C');
	$this->Cell(340,5,$this->prov,0,2,'C');

to this:      */
	$this->Cell(340,5,'Republic of the Philippines',0,1,'C');
	$this->Cell(340,5,'Province of '.$this->prov,0,1,'C');
	$this->Cell(340,5,'MUNICIPALITY OF '.strtoupper($this->lgu),0,2,'C');
//SEE: change made to lines 102 & 104

//add blank space
	$this->Cell(340,5,'',0,1,'C');

	$this->SetFont('Arial','B',14);
//change this to uppercase
//	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,strtoupper($this->office),0,2,'C');
//---------------------------------------------------------------------------
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'BUSINESS ESTABLISHMENTS WITHOUT PERMIT',0,1,'C');
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

//$dbLink = get_db_connection();

	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
    $getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);    
//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
/*------------------------------------------------------------------------
frederick >>> change this:
$pdf->setLGUinfo($getlgu[0],$getprov[0],'Office of the Treasurer');
to this:              */
$pdf->setLGUinfo($getprov[0],$getlgu[0],'Office of the Treasurer');
//--------------------------------------------------------------------------
$pdf->AddPage();
$pdf->AliasNbPages();

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$dateprinted = date('Y-m-d');
$pdf->SetX(5);
$pdf->Cell(340,5,$dateprinted,0,1,'R');
$pdf->SetX(5);
$pdf->Cell(10,5,'SEQ. NO.',1,0,'C');

$pdf->Cell(55,5,'NAME OF OWNER',1,0,'C');

$pdf->Cell(60,5,'BUSINESS NAME',1,0,'C');

$pdf->Cell(80,5,'BUSINESS ADDRESS',1,0,'C');

$pdf->Cell(30,5,'CAPITAL INVESTMENT',1,0,'C');

$pdf->Cell(30,5,'GROSS LAST YEAR',1,0,'C');
$pdf->Cell(40,5,'OR NUMBER/PAYMENT DATE',1,0,'C');

$pdf->Cell(25,5,'APPLICATION DATE',1,0,'C');

$pdf->Cell(10,5,'TYPE',1,1,'C');

//	$result=mysql_query("select ' ', business_name, ' ' , business_street, ' '  from ebpls_business_enterprise") or die(mysql_error());

//if paid flag = 1 else flag = 0 lagay ka condition dito
$flag = 1;
$date_from = str_replace("/", "-", $date_from);
$date_to = str_replace("/", "-", $date_to);
if ($paid == '0' || $paid =='1') {
	$is_paid = "and c.paid='$paid'";
} else {
	$is_paid = "";
}



	$result = mysql_query("select a.business_name, concat(a.business_lot_no, ' ', a.business_street, ' ', f.barangay_desc, ' ', g.city_municipality_desc, ' ', h.province_desc, ' ', a.business_zip_code) as busadd, 
	concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln, 
	c.business_permit_id, c.active, c.owner_id, c.business_id, c.application_date, c.transaction
	from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c, 
	 ebpls_barangay f , ebpls_city_municipality g , ebpls_province h
	where b.owner_id = a.owner_id and a.business_id = c.business_id and a.business_barangay_code = f.barangay_code and g.city_municipality_code = a.business_city_code 
        and h.province_code = a.business_province_code 
	and c.business_permit_id not in (select business_permit_id from ebpls_business_enterprise_permit where released='1') $is_paid and c.active = '1' and c.application_date between '$date_from 00:00:00' and '$date_to 23:59:59' and a.business_barangay_code like '$brgy_name%'") 
	or die(mysql_error());
	$number_of_rows = mysql_numrows($result);
	
	
	
	$i = 1;
	//$pdf->SetY($Y_Table_Position);
	while ($resulta=mysql_fetch_assoc($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(10,5,$i,1,0,'L');
		
		$pdf->Cell(55,5,$resulta[fulln],1,0,'L');
	
		$pdf->Cell(60,5,$resulta[business_name],1,0,'L');
		
		$pdf->Cell(80,5,$resulta[busadd],1,0,'L');
		
		$getline = mysql_query ("select * from tempbusnature a
								 where a.owner_id='$resulta[owner_id]' and
								 a.business_id='$resulta[business_id]' and a.active=1
								 ");

/*--------------------------------------------------------------------------------
frederick >>> found out that this script is located below/after the $getcap query
This is the reason why the array $getl[bus_code] resulted empty and
the capital investment did not appear in the report,
therefore, this has been moved here:             */
			$getl = mysql_fetch_assoc($getline);
//----------------------------------------------------------------------------------
				$getcap = mysql_query ("select * from tempbusnature a
								 where a.owner_id='$resulta[owner_id]' and
								 a.business_id='$resulta[business_id]' and a.bus_code='$getl[bus_code]'
								  order by tempid");
								 
				$getcap = mysql_fetch_assoc($getcap);
				
				$totcap = $totcap + $getcap[cap_inv];
				$totgross = $totgross + $getl[last_yr];
			
		$pdf->Cell(30,5,number_format($getcap[cap_inv],2),1,0,'R');
		$pdf->Cell(30,5,number_format($getl[last_yr],2),1,0,'R');	
		
		
		
		$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b",
                        "a.payment_code, a.or_date",
                        "where a.or_no=b.or_no and b.trans_id=$resulta[owner_id] and
                        b.or_entry_type='CASH' and
                        b.payment_id=$resulta[business_id] and b.transaction='$resulta[transaction]' order by a.or_no desc");
		$getcash = FetchArray($dbtype,$getcas);
			
		
		$pdf->Cell(40,5,$getcash[payment_code]."/".substr($getcash[or_date],0,11),1,0,'C');

		$pdf->Cell(25,5,substr($resulta[application_date],0,11),1,0,'C');

		$pdf->Cell(10,5,$resulta[transaction],1,1,'C');
		
		
		$i++;
		
	} 
	
/*
$i = 1;
$pdf->SetY($Y_Table_Position);
while ($i < $number_of_rows)
{
    $pdf->SetX(1);
    $pdf->MultiCell(349,5,$i,1);
    $i = $i +1;   
} */

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
$pdf->Cell(250,5,'Recommend Approval:',0,0,'L');
$pdf->Cell(173,5,'Approved:',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='List of Establishment Without Permit' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(250,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,'',0,0,'C');
$pdf->Cell(172,5,$getsignatories1[gs_pos],0,1,'L');

$report_desc='Business Establishment Without Permit';
//include '../report_signatories_footer1.php';

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







<?php          /*                        
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("lib/phpFunctions-inc.php");

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
	
	//$this->Image('peoplesmall.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'MASTERLIST OF BUSINESS ESTABLISHMENT',0,1,'C');
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

$dbLink = get_db_connection();

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
    
//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($resulta[0],$resulta[1],$resulta[2]);
$pdf->AddPage();
$pdf->AliasNbPages();

	//JUST FOR LISTING
	//$result=mysql_query("select ' ', business_name, ' ' , business_street, ' '  from ebpls_business_enterprise") or die(mysql_error());
	
	
	$result=mysql_query("select a.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', 
	c.owner_last_name), b.business_name, e.bus_nature, b.business_street, e.cap_inv, e.last_yr, d.total_amount_paid, 
	d.or_no, d.or_date, b.business_category_code
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, 
	ebpls_transaction_payment_or d, tempbusnature e
	where a.owner_id = b.owner_id and a.owner_id = c.owner_id and b.owner_id = d.trans_id 
	and b.business_id = e.business_id") 
	or die(mysql_error()); 
	
	$result=mysql_query("select a.business_permit_id, a.application_date, b.business_name, b.business_street, 
	a.transaction, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
	concat(c.owner_street, ' ', c.owner_city_code, ' ', c.owner_province_code), a.business_permit_id, d.cap_inv, 
	d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_payment_mode, c.owner_id, e.or_no,
	e.or_date from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d,
	ebpls_transaction_payment_or e
	where a.business_id = b.business_id and a.owner_id = c.owner_id	and a.business_id = d.business_id 
	and c.owner_id = d.owner_id and b.owner_id = e.trans_id") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
	$number_of_rows = mysql_numrows($result);
    while ($resulta=mysql_fetch_row($result))

	{
    	$row1 = $resulta[0]; //permit number
    	$row2 = $resulta[5]; //owner
		$row3 = $resulta[2]; //busines name
		$row4 = $resulta[13]; //nature
		$row5 = $resulta[3]; //bus address
		$row6 = $resulta[8]; //cap invest
		$row7 = $resulta[9]; //gross
		$row8 = $resulta[7]; //amount paid
		$row9 = $resulta[16]; //or_no
		$row10 = $resulta[17]; //payment date
		//$row10 = substr($resulta[17],0,10);
		$row11 = $resulta[10]; //ownership type
					
    	$column_code1 = $column_code1.$row1."\n";
    	$column_code2 = $column_code2.$row2."\n";
    	$column_code3 = $column_code3.$row3."\n";
    	$column_code4 = $column_code4.$row4."\n";
    	$column_code5 = $column_code5.$row5."\n";
    	$column_code6 = $column_code6.$row6."\n";
    	$column_code7 = $column_code7.$row7."\n";
    	$column_code8 = $column_code8.$row8."\n";
    	$column_code9 = $column_code9.$row9."\n";
    	$column_code10 = $column_code10.$row10."\n";
    	$column_code11 = $column_code11.$row11."\n";
    				
	}

$pdf->SetLineWidth(2);
$pdf->Line(0,45,360,45);
$pdf->SetLineWidth(0);
					
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(15,5,'PERMIT NO.',1,0,'C');
$pdf->SetX(20);
$pdf->Cell(50,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(70);
$pdf->Cell(50,5,'BUSINESS NAME',1,0,'C');
$pdf->SetX(120);
$pdf->Cell(30,5,'BUSINESS NATURE',1,0,'C');
$pdf->SetX(150);
$pdf->Cell(60,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->SetX(210);
$pdf->Cell(30,5,'CAPITAL INVESTMENT',1,0,'C');
$pdf->SetX(240);
$pdf->Cell(30,5,'GROSS SALES',1,0,'C');
$pdf->SetX(270);
$pdf->Cell(20,5,'AMOUNT PAID',1,0,'C');
$pdf->SetX(290);
$pdf->Cell(15,5,'O.R. NO.',1,0,'C');
$pdf->SetX(305);
$pdf->Cell(20,5,'PAYMENT DATE',1,0,'C');
$pdf->SetX(325);
$pdf->Cell(25,5,'OWNERSHIP TYPE',1,0,'C');

$pdf->SetFont('Arial','',6);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
$pdf->MultiCell(15,5,$column_code1,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(20);
$pdf->MultiCell(50,5,$column_code2,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(70);
$pdf->MultiCell(50,5,$column_code3,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(120);
$pdf->MultiCell(30,5,$column_code4,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(60,5,$column_code5,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(210);
$pdf->MultiCell(30,5,$column_code6,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(240);
$pdf->MultiCell(30,5,$column_code7,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(270);
$pdf->MultiCell(20,5,$column_code8,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(290);
$pdf->MultiCell(15,5,$column_code9,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(305);
$pdf->MultiCell(20,5,$column_code10,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(325);
$pdf->MultiCell(25,5,$column_code11,1);
//$pdf->MultiCell(349,5,$i,1);
$pdf->Ln(20);

$i = 1;
$pdf->SetY($Y_Table_Position);
while ($i < $number_of_rows)
{
    $pdf->SetX(1);
    $pdf->MultiCell(349,5,$i,1);
    $i = $i +1;   
} 

//new signatories table
	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(270,5,'',0,1,'C');

$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Recommend Approval:',1,0,'L');
$pdf->Cell(173,5,'Approved:',1,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(172,5,'',1,0,'C');
$pdf->Cell(172,5,$resulta[0],1,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,'',1,0,'C');
$pdf->Cell(172,5,$resulta[2],1,0,'C');

//$pdf->SetFont('Arial','B',10);
//$pdf->Cell(135,5,$resulta[2],1,0,'C');
//$pdf->Cell(135,5,$resulta[2],1,1,'C');
/*
$pdf->SetY(-18);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Inspected By:',1,0,'L');
$pdf->Cell(173,5,'Noted By:',1,1,'L');

$pdf->Cell(350,5,'',0,2,'C');
$pdf->Cell(350,5,'',0,2,'C');

$pdf->SetFont('Arial','BU',10);
$pdf->SetX(5);
$pdf->Cell(172,5,$resulta[0],1,0,'C');
$pdf->Cell(173,5,$resulta[3],1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,$resulta[4],1,0,'C');
$pdf->Cell(173,5,$resulta[7],1,1,'C');
*/

//$pdf->Output();

?>

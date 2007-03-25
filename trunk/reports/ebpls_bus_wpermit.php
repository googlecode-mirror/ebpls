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
var $paid;

	function setLGUinfo($p='', $l='', $o='',$lop) {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
		if ($lop==1) {
			$lop='PAID';
		}elseif ($lop==0) {
			$lop='UNPAID';
		} else {
			$lop='';
		}
		$this->paid = $lop;
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
	$this->Cell(340,5,$this->paid.' BUSINESS ESTABLISHMENTS',0,1,'C');
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
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($getlgu[0],$getprov[0],$resulta[2],"$paid");
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
$pdf->Cell(35,5,'NAME OF OWNER',1,0,'C');
$pdf->Cell(40,5,'BUSINESS NAME',1,0,'C');
$pdf->Cell(90,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->Cell(25,5,'CAPITAL INVESTMENT',1,0,'C');
$pdf->Cell(25,5,'GROSS LAST YEAR',1,0,'C');
$pdf->Cell(25,5,'OR NUMBER',1,0,'C');
$pdf->Cell(25,5,'PAYMENT DATE',1,0,'C');
$pdf->Cell(25,5,'APPLICATION DATE',1,0,'C');
$pdf->Cell(20,5,'TRANSACTION',1,1,'C');

$date_from = str_replace("/", "", $date_from);
$idate = strtotime($date_from);
//$idate = $idate - (60*60*24);
$date_from = date('Y-m-d', $idate);
$date_to = str_replace("/", "", $date_to);
$xdate = strtotime($date_to);
$xdate = $xdate + (60*60*24);
$date_to = date('Y-m-d', $xdate);
	$result = mysql_query ("select distinct(a.business_name), 
	concat(a.business_lot_no, ' ', a.business_street, ' ',
        a.business_city_code, ' ', a.business_province_code, ' ',
        a.business_zip_code) as bus_add,
        concat(b.owner_first_name, ' ', b.owner_middle_name, ' ', b.owner_last_name) as fulln,
        b.owner_id, a.business_id,
	c.transaction 
        from ebpls_business_enterprise a, ebpls_owner b, ebpls_business_enterprise_permit c,
        tempbusnature d, ebpls_buss_nature e 
	where
        d.active=1 and d.bus_code = e.natureid
        and b.owner_id = a.owner_id and a.business_id = c.business_id and
        c.active=1 and b.owner_id = d.owner_id and
        a.business_id=d.business_id and b.owner_last_name like '$owner_last%'
        and c.application_date between '$date_from' and '$date_to'
        and a.business_barangay_code  like '$brgy_name%' and paid='$paid' 
        "); 




	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($resulta=mysql_fetch_array($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(10,5,$i,1,0,'L');
		$pdf->Cell(35,5,$resulta[fulln],1,0,'L');
		$pdf->Cell(40,5,$resulta[business_name],1,0,'L');
		$pdf->Cell(90,5,$resulta[bus_add],1,0,'L');
                $getlineb = mysql_query("select a.cap_inv as invest from tempbusnature a,
                                ebpls_buss_nature b where a.owner_id=$resulta[owner_id] and
                                a.business_id=$resulta[business_id] and
                                a.bus_code=b.natureid 
                                order by tempid");
		$resultb = mysql_fetch_array($getlineb);
		$pdf->Cell(25,5,$resultb[invest],1,0,'R');
                $getlineb = mysql_query("select a.last_yr as invest from tempbusnature a,
                                ebpls_buss_nature b where a.owner_id=$resulta[owner_id] and
                                a.business_id=$resulta[business_id] and
                                a.bus_code=b.natureid and a.active=1 
                                order by tempid desc");
                
                $resultb = mysql_fetch_array($getlineb);
		$pdf->Cell(25,5,$resultb[invest],1,0,'R');

		$getcas = SelectMultiTable($dbtype,$dbLink,"ebpls_transaction_payment_or a,
                        ebpls_transaction_payment_or_details b, 
			ebpls_business_enterprise_permit c",
                        "a.ts_create as pay_date, concat(a.payment_code,'/',a.or_no),
                        a.total_amount_paid, a.payment_officer, a.or_no, a.total_amount_due,
                        a.payment_code as or_num",
                        "where a.or_no=b.or_no and b.trans_id=$resulta[owner_id] and
                        b.or_entry_type='CASH' and
                        b.payment_id=$resulta[business_id] and
			b.payment_id=c.business_id and
			b.trans_id=c.owner_id and c.active=1 and	 
			b.transaction='$resulta[transaction]'");
                $geta=FetchArray($dbtype,$getcas);

		$getapp = mysql_query("select * from ebpls_business_enterprise_permit
			where owner_id = $resulta[owner_id] and 
			business_id =$resulta[business_id] and
			active=1");
		$getapp = mysql_fetch_array($getapp);

		$pdf->Cell(25,5,$geta[or_num],1,0,'L');
		$pdf->Cell(25,5,$geta[pay_date],1,0,'L');
		$pdf->Cell(25,5,$getapp[application_date],1,0,'L');
                $pdf->Cell(20,5,$resulta[transaction],1,0,'L');
		$i++;
		$pdf->SetY($pdf->GetY()+5);
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
$pdf->Cell(172,5,'Recommend Approval:',0,0,'L');
$pdf->Cell(173,5,'Approved:',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$report_desc='Business Establishment Without Permit (Paid)';
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

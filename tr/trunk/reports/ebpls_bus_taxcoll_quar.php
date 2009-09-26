<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

$e = strrpos($owner_last,"-");//$owner_last is date
$l =strlen($owner_last);
$dateprev = substr($owner_last,0,4);
$dateprev = $dateprev;
$datenext = $dateprev + 1;

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
$pdf=new FPDF('L','mm','Legal');
$pdf->AddPage();
$pdf->image('../images/ebpls_logo.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(340,5,$resulta[0],0,1,'C');
$pdf->Cell(340,5,$resulta[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(340,5,$resulta[2],0,2,'C');
$pdf->Cell(340,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(340,5,'BUSINESS TAX COLLECTION QUARTERLY',0,1,'C');


$pdf->SetLineWidth(2);
$pdf->Line(0,45,360,45);
$pdf->SetLineWidth(0);
					
$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(10);
$pdf->Cell(35,5,'NAME OF BUSINESS',1,0,'C');
$pdf->Cell(20,5,'PERMIT NUMBER',1,0,'C');
$pdf->Cell(60,5,'NAME OF OWNER',1,0,'C');
$pdf->Cell(70,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->Cell(20,5,'1ST QUARTER',1,0,'C');
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');


$pdf->Cell(20,5,'2ND QUARTER',1,0,'C');
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');

$pdf->Cell(20,5,'3RD QUARTER',1,0,'C');
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');

$pdf->Cell(20,5,'4TH QUARTER',1,0,'C');
$pdf->Cell(15,5,'OR NUMBER',1,1,'C');

//first quarter
        $taxq1 = mysql_query("select a.business_name, b.business_permit_code,
                        concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name) as fulln,
                        concat(a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code, ' ',
                        a.business_district_code, ' ', a.business_city_code) as bus_name,
			c.owner_id from ebpls_business_enterprise a, ebpls_business_enterprise_permit b,
                        ebpls_owner c, comparative_statement d
                        where d.for_year='$dateprev' and a.owner_id=d.owner_id and
                        b.owner_id=d.owner_id and c.owner_id=d.owner_id and d.business_id=a.business_id and
			d.business_id=b.business_id and b.active=1 
                        ");



$pdf->SetFont('Arial','',6);
//$pdf->SetY($Y_Table_Position);

while ($taxqn1=mysql_fetch_array($taxq1)) {
$number_of_rows++;
$pdf->Cell(35,5,$taxqn1[business_name],1,0,'C');

$pdf->Cell(20,5,$taxqn1[business_permit_code],1,0,'C');
$pdf->Cell(60,5,$taxqn1[fulln],1,0,'L');
$pdf->SetFont('Arial','B',4);
$pdf->Cell(70,5,$taxqn1[bus_name],1,0,'L');
$pdf->SetFont('Arial','B',6);

//2nd quarter
         $taxq2 = mysql_query("select d.or_no, d.taxes from
                        ebpls_business_enterprise a, ebpls_business_enterprise_permit b,
                        ebpls_owner c, comparative_statement d
                        where d.for_year='$dateprev' and
                        a.owner_id=d.owner_id and
                        b.owner_id=d.owner_id and
                        c.owner_id=d.owner_id and
                        d.business_id=a.business_id and
                        d.business_id=b.business_id and  b.active=1 and
                        c.owner_id = '$taxqn1[owner_id]' and
                        d.month between 0 and 4 and d.month<>4");

$taxn2 = mysql_fetch_array($taxq2);

$pdf->Cell(20,5,$taxn2[taxes],1,0,'R');

$pdf->Cell(15,5,$taxn2[or_no],1,0,'R');



//$pdf->Cell(20,5,$taxqn1[taxes],1,0,'R');
//$pdf->Cell(15,5,$taxqn1[or_no],1,0,'R');


//2nd quarter
	 $taxq2 = mysql_query("select d.or_no, d.taxes from 
			ebpls_business_enterprise a, ebpls_business_enterprise_permit b,
                        ebpls_owner c, comparative_statement d
			where d.for_year='$dateprev' and 
			a.owner_id=d.owner_id and
                        b.owner_id=d.owner_id and 
			c.owner_id=d.owner_id and 
			d.business_id=a.business_id and
                        d.business_id=b.business_id and  b.active=1 and 
			c.owner_id = '$taxqn1[owner_id]' and 
			d.month between 3 and 7 and d.month<>3 and d.month<>7");

$taxn2 = mysql_fetch_array($taxq2);

$pdf->Cell(20,5,$taxn2[taxes],1,0,'R');

$pdf->Cell(15,5,$taxn2[or_no],1,0,'R');



//3rd quarter
         $taxq2 = mysql_query("select d.or_no, d.taxes from
                        ebpls_business_enterprise a, ebpls_business_enterprise_permit b,
                        ebpls_owner c, comparative_statement d
                        where d.for_year='$dateprev' and
                        a.owner_id=d.owner_id and
                        b.owner_id=d.owner_id and
                        c.owner_id=d.owner_id and
                        d.business_id=a.business_id and
                        d.business_id=b.business_id and  b.active=1 and
                        c.owner_id = '$taxqn1[owner_id]' and
                        d.month between 6 and 10 and d.month<>6 and d.month<>10");

$taxn2 = mysql_fetch_array($taxq2);

$pdf->Cell(20,5,$taxn2[taxes],1,0,'R');

$pdf->Cell(15,5,$taxn2[or_no],1,0,'R');


//4th quarter
         $taxq2 = mysql_query("select d.or_no, d.taxes from
                        ebpls_business_enterprise a, ebpls_business_enterprise_permit b,
                        ebpls_owner c, comparative_statement d
                        where d.for_year='$dateprev' and
                        a.owner_id=d.owner_id and
                        b.owner_id=d.owner_id and
                        c.owner_id=d.owner_id and
                        d.business_id=a.business_id and
                        d.business_id=b.business_id and b.active=1 and
                        c.owner_id = '$taxqn1[owner_id]' and
                         d.month between 9 and 13 and d.month<>9");

$taxn2 = mysql_fetch_array($taxq2);

$pdf->Cell(20,5,$taxn2[taxes],1,0,'R');

$pdf->Cell(15,5,$taxn2[or_no],1,1,'R');




}
$pdf->Cell(15,5,'',0,1,'R');
	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

$Y_Table_Position = $Y_Table_Position + 5;
          
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Approved By:',1,0,'L');
$pdf->Cell(172,5,'Noted By:',1,1,'L');

$pdf->Cell(350,5,'',0,2,'C');
$pdf->Cell(350,5,'',0,2,'C');

$pdf->SetFont('Arial','BU',10);
$pdf->SetX(5);
$pdf->Cell(172,5,$resulta[0],1,0,'C');
$pdf->Cell(172,5,$resulta[3],1,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,$resulta[2],1,0,'C');
$pdf->Cell(172,5,$resulta[7],1,1,'C');

$pdf->Output();

?>


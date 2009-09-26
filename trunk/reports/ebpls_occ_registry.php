<?php                                  
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
	
	$this->Image('peoplesmall.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'OCCUPATIONAL REGISTRY',0,1,'C');
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

$Y_Label_position = 50;
$Y_Table_Position = 55;

$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(15,5,'SEQ. NO.',1,0,'C');
$pdf->SetX(20);
$pdf->Cell(35,5,'PERMIT NO.',1,0,'C');
$pdf->SetX(55);
$pdf->Cell(75,5,'APPLICANT',1,0,'C');
$pdf->SetX(130);
$pdf->Cell(70,5,'ADDRESS',1,0,'C');
$pdf->SetX(200);
$pdf->Cell(50,5,'POSITION',1,0,'C');
$pdf->SetX(250);
$pdf->Cell(50,5,'EMPLOYER',1,0,'C');
$pdf->SetX(300);
$pdf->Cell(50,5,'LOCATION',1,0,'C');


	$result=mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	b.for_year, b.occ_position_applied, b.occ_employer, b.occ_employer_trade_name,
	concat(b.occ_employer_lot_no, ' ', b.occ_employer_street, ' ', b.occ_employer_barangay_code, ' ',
	b.occ_employer_zone_code, ' ',	b.occ_employer_district_code, ' ', b.occ_employer_city_code, ' ', 
	b.occ_employer_province_code, b.occ_employer_zip_code), b.occ_permit_id, b.occ_employer_barangay_code
	from ebpls_occu_owner a, ebpls_occupational_permit b where a.owner_id = b.owner_id 
	order by a.owner_last_name") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
	

	$i = 1;
	$pdf->SetY($Y_Table_Position);
	while ($resulta=mysql_fetch_row($result))
	{
    	$pdf->SetX(1);
    	//$pdf->MultiCell(349,5,$i,1);

		$pdf->SetX(5);
		$pdf->Cell(15,5,$i,1,0,'L');
		$pdf->SetX(20);
		$pdf->Cell(35,5,$resulta[6],1,0,'L'); //permit #
		$pdf->SetX(55);
		$pdf->Cell(75,5,$resulta[0],1,0,'L');  	//applicant
		$pdf->SetX(130);
		$pdf->Cell(70,5,$resulta[5],1,0,'L');	//address
		$pdf->SetX(200);
		$pdf->Cell(50,5,$resulta[2],1,0,'C');	//position
		$pdf->SetX(250);
		$pdf->Cell(50,5,$resulta[3],1,0,'C');	//employer
		$pdf->SetX(300);
		$pdf->Cell(50,5,$resulta[7],1,0,'L');	//location
		$i++;
		$pdf->SetY($pdf->GetY()+5);
	} 

//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Recommend Approval:',1,0,'L');
$pdf->Cell(172,5,'Approved:',1,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[2],1,0,'C');

$report_desc='Occupational Registry';
include 'report_signatories_footer1.php';

$pdf->Output();

?>





<?php/* 
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");                                                                                                                        
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//require_once("includes/eBPLS_header.php");
                                                                                                                                                                                                                                     
//--- get connection from DB
$dbLink = get_db_connection();
?>

<?php
echo date("F dS Y h:i:s A");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
  <title>REGISTRY OF OCCUPATIONAL PERMIT</title>
  <meta name="Author" content=" Pagoda, Ltd. ">
  <link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body>

<?php

					$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<h4 align="center"> Republic of the Philippines </h4>
<h4 align="center"> <?php echo $resulta[1]; ?> </h4>
<h4 align="center"> <?php echo $resulta[0]; ?> </h4>
<h4 align="center"> <?php echo $resulta[2]; ?> </h4>
<h4 align="center"><u> REGISTRY OF OCCUPATIONAL  PERMIT </u></h4>
<h4 align="center"> As of _______________________ </h4>

<hr>

<table border="1" cellpadding="1" cellspacing="1" width="1000">
  <tbody>
    <tr>
      <td align="center"><b> Name </b></td>
      <td align="center"><b> Age </b></td>
      <td align="center"><b> Address </b></td>
      <td align="center"><b> Name of Business Establishment </b></td>
      <td align="center"><b> Occupation </b></td>
      <td align="center"><b> Expiry Date </b></td>
      <td align="center"><b> OR No. </b></td>
      <td align="center"><b> Date </b></td>
      <td align="center"><b> Amount </b></td>
    </tr>
    
    <?php
				
        	// unang gawang tama
	        $result=mysql_query("select ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '0.00'
	        from ebpls_business_enterprise") or die(mysql_error());
          while ($resulta=mysql_fetch_row($result)){
          
          /* sample/test ko lng
	        $result=mysql_query("select a.owner_id, a.business_name, a.business_city_code, a.business_type_code, 
          a.business_street, a.business_capital_investment, a.business_last_yrs_dec_gross_sales, b.fee_amount,
          b.input_date from ebpls_business_enterprise a, ebpls_fees_paid b where a.owner_id = b.owner_id and 
          a.business_create_ts = '2004-12-28'") or die(mysql_error());
          while ($resulta=mysql_fetch_row($result)){  
	          	          
					print "<tr>\n";
					foreach ($resulta as $field )
					print "<td>&nbsp;$field&nbsp</td>\n";
					print "</tr>";
					
				}
		?>
			
  </tbody>
</table>
<br>
<br>
<br>
<br>

<?php

	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

?>

<table style="width: 1000px" border="0" cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
    	<td align="left", width="500"><b> Inspected By: <br> <br> <br> <br> <br> </b></td>
    	<td align="left", width="500"><b> Noted By: <br> <br> <br> <br> <br> </b></td>
    </tr>
    <tr>
    	<td	align="center"><u><b> <?php echo $resulta[4]; ?> </u> </b> </td>
    	<td align="center"> <u><b> <?php echo $resulta[4]; ?> </u> </b> </td>
    </tr>
    <tr>
    	<td align="center"> <?php echo $resulta[4]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
    	<td align="center"> <?php echo $resulta[6]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
    </tr>
    <tr>
    	<td align="left", width="500"><b> Approved By: <br> <br> <br> <br> <br> </b></td>	
    </tr>
    <tr>
    	<td align="center"><b><u> <?php echo $resulta[0]; ?> </b></u> <br> <?php echo $resulta[2]; ?> <br> <br> <br> <br> <br> </td>
    </tr>
    <tr> 
    	<td align="left"> Date printed: &nbsp; &nbsp; <?php echo date("F d Y"); ?> </td>
   </tbody>
</table>

<br>
<br>
<?php

require_once("includes/eBPLS_footer.php");
*/
?>

</body>
</html>

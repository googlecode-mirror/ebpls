<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("lib/phpFunctions-inc.php");

$dbLink = get_db_connection();
$e = strrpos($owner_last,"-");//$owner_last is date
$l =strlen($owner_last);
$dateprev = substr($owner_last, $l-$e);
$dateprev = $dateprev;
$datenext = $dateprev + 1;

	$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
$pdf=new FPDF('L','mm','Legal');
$pdf->AddPage();
$pdf->image('peoplesmall.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(340,5,$resulta[0],0,1,'C');
$pdf->Cell(340,5,$resulta[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(340,5,$resulta[2],0,2,'C');
$pdf->Cell(340,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(340,5,'BUSINESS TAX COLLECTION QUARTERLY',0,1,'C');

/*	$result=mysql_query("select a.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', 
	c.owner_last_name), b.business_name, e.bus_nature, b.business_street, e.cap_inv, e.last_yr, d.total_amount_paid, 
	d.or_no, d.or_date, b.business_category_code
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, ebpls_transaction_payment_or d,
	tempbusnature e
	where a.owner_id = b.owner_id and a.owner_id = c.owner_id and b.owner_id = d.trans_id 
	and b.business_id = e.business_id limit 10") or die(mysql_error());*/

	$taxq1 = mysql_query("select a.business_name, b.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), concat(a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code, ' ', a.business_district_code, ' ', a.business_city_code), d.for_year, d.or_no, d.taxes from ebpls_business_enterprise a, ebpls_business_enterprise_permit b, ebpls_owner c, comparative_statement d where d.for_year='$dateprev' and a.owner_id=d.owner_id and b.owner_id=d.owner_id and c.owner_id=d.owner_id and d.month>=1 and d.month<=3") or die(mysql_error());
	$taxq2 = mysql_query("select a.business_name, b.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), concat(a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code, ' ', a.business_district_code, ' ', a.business_city_code), d.for_year, d.or_no, d.taxes from ebpls_business_enterprise a, ebpls_business_enterprise_permit b, ebpls_owner c, comparative_statement d where d.for_year='$dateprev' and a.owner_id=d.owner_id and b.owner_id=d.owner_id and c.owner_id=d.owner_id and d.month>=4 and d.month<=6") or die(mysql_error());
	$taxq3 = mysql_query("select a.business_name, b.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), concat(a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code, ' ', a.business_district_code, ' ', a.business_city_code), d.for_year, d.or_no, d.taxes from ebpls_business_enterprise a, ebpls_business_enterprise_permit b, ebpls_owner c, comparative_statement d where d.for_year='$dateprev' and a.owner_id=d.owner_id and b.owner_id=d.owner_id and c.owner_id=d.owner_id and d.month>=7 and d.month<=9") or die(mysql_error());
	$taxq4 = mysql_query("select a.business_name, b.business_permit_code, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), concat(a.business_street, ' ', a.business_barangay_code, ' ', a.business_zone_code, ' ', a.business_district_code, ' ', a.business_city_code), d.for_year, d.or_no, d.taxes from ebpls_business_enterprise a, ebpls_business_enterprise_permit b, ebpls_owner c, comparative_statement d where d.for_year='$dateprev' and a.owner_id=d.owner_id and b.owner_id=d.owner_id and c.owner_id=d.owner_id and d.month>=10 and d.month<=12") or die(mysql_error());
	while ($taxq1n=mysql_fetch_row($taxq1) or $taxq2n=mysql_fetch_row($taxq2) or $taxq3n=mysql_fetch_row($taxq3) or $taxq4n=mysql_fetch_row($taxq4))

	{
		if ($taxq1n[0]=='') {
			if ($taxq2n[0]=='') {
				if ($taxq3n[0]=='') {
    				$row1 = $taxq4n[0];
				} else { 
					$row1 = $taxq3n[0];
				}
			} else {
				$row1 = $taxq2n[0];
			}
		} else {
			$row1 = $taxq1n[0];
		}
    	if ($taxq1n[1]=='') {
			if ($taxq2n[1]=='') {
				if ($taxq3n[1]=='') {
    				$row2 = $taxq4n[1];
				} else { 
					$row2 = $taxq3n[1];
				}
			} else {
				$row2 = $taxq2n[1];
			}
		} else {
			$row2 = $taxq1n[1];
		}
		if ($taxq1n[2]=='') {
			if ($taxq2n[2]=='') {
				if ($taxq3n[2]=='') {
    				$row3 = $taxq4n[2];
				} else { 
					$row3 = $taxq3n[2];
				}
			} else {
				$row3 = $taxq2n[2];
			}
		} else {
			$row3 = $taxq1n[2];
		}
		if ($taxq1n[3]=='') {
			if ($taxq2n[3]=='') {
				if ($taxq3n[3]=='') {
    				$row4 = $taxq4n[3];
				} else { 
					$row4 = $taxq3n[3];
				}
			} else {
				$row4 = $taxq2n[3];
			}
		} else {
			$row4 = $taxq1n[3];
		}
		$row5 = number_format($taxq1n[6],2);
		$row6 = $taxq1n[5];
		$row7 = number_format($taxq2n[6],2);
		$row8 = $taxq2n[5];
		$row9 = number_format($taxq3n[6],2);
		$row10 = $taxq3n[5];
		$row11 = number_format($taxq4n[6],2);
		$row12 = $taxq4n[5];
		
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
    	$column_code12 = $column_code12.$row12."\n";	
    	
    	$number_of_rows=$number_of_rows+1;			
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
$pdf->Cell(35,5,'NAME OF BUSINESS'.$number_of_rows,1,0,'C');
$pdf->SetX(40);
$pdf->Cell(20,5,'PERMIT NUMBER',1,0,'C');
$pdf->SetX(60);
$pdf->Cell(60,5,'NAME OF OWNER',1,0,'C');
$pdf->SetX(120);
$pdf->Cell(70,5,'BUSINESS ADDRESS',1,0,'C');
$pdf->SetX(190);
$pdf->Cell(20,5,'1ST QUARTER',1,0,'C');
$pdf->SetX(210);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
$pdf->SetX(225);
$pdf->Cell(20,5,'2ND QUARTER',1,0,'C');
$pdf->SetX(245);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
$pdf->SetX(260);
$pdf->Cell(20,5,'3RD QUARTER',1,0,'C');
$pdf->SetX(280);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
$pdf->SetX(295);
$pdf->Cell(20,5,'4TH QUARTER',1,0,'C');
$pdf->SetX(315);
$pdf->Cell(15,5,'OR NUMBER',1,0,'C');
 
$pdf->SetFont('Arial','',6);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
$pdf->MultiCell(35,5,$column_code1,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(40);
$pdf->MultiCell(20,5,$column_code2,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(60);
$pdf->MultiCell(60,5,$column_code3,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(120);
$pdf->MultiCell(70,5,$column_code4,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(190);
$pdf->MultiCell(20,5,$column_code5,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(210);
$pdf->MultiCell(15,5,$column_code6,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(225);
$pdf->MultiCell(20,5,$column_code7,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(245);
$pdf->MultiCell(15,5,$column_code8,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(260);
$pdf->MultiCell(20,5,$column_code9,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(280);
$pdf->MultiCell(15,5,$column_code10,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(295);
$pdf->MultiCell(20,5,$column_code11,1,'R');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(315);
$pdf->MultiCell(15,5,$column_code12,1,'R');

$i = 0;
$pdf->SetY($Y_Table_Position);
$ymulti=5*$number_of_rows;;
while ($i < $number_of_rows)
{
    $pdf->SetX(5);
    $pdf->MultiCell(325,5,'',1);
    $i = $i +1;
} 

	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

$Y_Table_Position = $Y_Table_Position + 25 + $ymulti;
          
$pdf->SetY($Y_Table_Position);
$pdf->SetX(5);
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



<?php /*
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
  <title>BUSINESS TAX COLLECTION QUARTERLY</title>
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
<h4 align="center"><u> BUSINESS  TAX COLLECTION QUARTERLY</u></h4>

<hr>

<br>
<table border="1" cellpadding="1" cellspacing="1" width="1400">
  <tbody>
    <tr>
      <td align="center"><b> Name of Business </b></td>
      <td align="center"><b> Permit Number </b></td>
      <td align="center"><b> Owner's Name </b></td>
      <td align="center"><b> Nature of Business </b></td>
      <td align="center"><b> Business Address </b></td>
      <td align="center"><b> 1st Q. </b></td>
      <td align="center"><b> O.R. Number </b></td>
      <td align="center"><b> 2nd Q. </b></td>
      <td align="center"><b> OR No. </b></td>
      <td align="center"><b> 3rd Q </b></td>
      <td align="center"><b> OR No. </b></td>
      <td align="center"><b> 4th Q </b></td>
      <td align="center"><b> OR No. </b></td>
      <td align="center"><b> Total Amount Due </b></td>
    </tr>
    <?php
					
	         // unang gawang tama
	        $result=mysql_query("select business_name, ' ', ' ', ' ', business_street, '0.00', ' ', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'
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

					$result=mysql_query("select sign1, sign2, sign3, sign4, pos1, pos2, pos3, pos4
					from permit_templates") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<table style="width: 1000px" border="0" cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
    	<td align="left", width="500"><b> Prepared By: <br> <br> <br> <br> <br> </b></td>
    	<td align="left", width="500"><b> Noted By: <br> <br> <br> <br> <br> </b></td>
    </tr>
    <tr>
    	<td	align="center"><u><b> <?php echo $resulta[0]; ?> </u> </b> </td>
    	<td align="center"> <u><b> <?php echo $resulta[3]; ?> </u> </b> </td>
    </tr>
    <tr>
    	<td align="center"> <?php echo $resulta[4]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
    	<td align="center"> <?php echo $resulta[7]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
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
-->

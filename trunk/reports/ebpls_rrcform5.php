<?php 
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");                                                                                                                        
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//require_once("includes/eBPLS_header.php");     
require('ebpls-php-lib/html2pdf_lib/fpdf.php');                                                                                                                                                                                                                                

//--- get connection from DB
$dbLink = get_db_connection();
?>

<?php
echo date("F dS Y h:i:s A");
?>


<?php

/* define('FPDF_FONTPATH','font/');
require('fpdf.php');

$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output(); 
*/

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
  <title>RRC Form 5</title>
  <meta name="Author" content=" Pagoda, Ltd. ">
  <link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body>

RRC Form 5
<br>
<br>

<?php

					$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<h4 align="center"> Republic of the Philippines </h4>
<h4 align="center"> <?php echo $resulta[1]; ?> </h4>
<h4 align="center"> <?php echo $resulta[0]; ?> </h4>
<h4 align="center"> <?php echo $resulta[2]; ?> </h4>

<br>
<br>
Computation Statement for the collection of Professional Tax per Chapter III, Article E, Section 3.E.01 of Provincial Tax Ordinance No.
2001-02, Otherwise known as the Revised Revenue Code of Province of _________________:
<br>
<br>
<table style="width: 980px; height: 100px;" align="center" border="0" cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td><b> Name: &nbsp; ______________________ </b></td>
      <td><b> License Number: &nbsp; ______________________ </b></td>
    </tr>
    <tr>
      <td><b> Address: &nbsp; ______________________ </b></td>
      <td><b> Date Registration: &nbsp; ______________________ </b></td>
    </tr>
    <tr>
      <td><b> Period Covered: &nbsp; ______________________ </b></td>
      <td><b> Date Registration: &nbsp; ______________________ </b></td>
    </tr>
    <tr>
      <td align="right"><b> Professional Tax </b></td>
      <td><b> &nbsp; P _____________________ </b></td>
    </tr>
    <tr>
      <td align="right"><b> Surcharge (25%) </b></td>
      <td><b> &nbsp; P _____________________ </b></td>
    </tr>
    <tr>
      <td align="right"><b> Interest (2% per Month) </b></td>
      <td><b> &nbsp; P _____________________ </b></td>
    </tr>
    <tr>
      <td align="right"> <font color="red"><b> Total </b><br>
      </font> &nbsp; </td>
      <td><b> &nbsp; P _____________________</b><br>
&nbsp;&nbsp;&nbsp;&nbsp;==================== </td>
    </tr>
	</tbody>
</table>

<table style="width: 980px; height: 100px;" align="center" border="0" cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td>
      <br>
      <br>
			<b>Certified Correct: </b>
			<br>
      <br>
      </td>
      <td> &nbsp; </td>
      <td> <br>
      <br>
			<b>Approved: </td></b>
    </tr>
    <tr>
      <td>________________________ </td>
      <td> &nbsp; </td>
      <td> ________________________ </td>
    </tr>
    <tr>
      <td>LTOII </td>
      <td> &nbsp; </td>
      <td> Provincial Treasurer </td>
    </tr>
  </tbody>
</table>
<br>
<br>
<br>
Paid under OR No. ______________________ dated ____________________________ <br>
Renewable on or before 31st of January for the suceeding year and every year thereafter to avoid penalties.

<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>

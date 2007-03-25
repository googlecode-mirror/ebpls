<?php 
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');
$permit_type='Fishery';
include"../includes/variables.php";
include_once("../lib/multidbconnection.php");

$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

//--- get connection from DB
?>

<?php
echo date("F dS Y h:i:s A");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
  <title>BUSINESS PROFILE</title>
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
<h4 align="center"><u> BUSINESS PROFILE </u></h4>
<h4 align="right">Date: </h4> 

<hr>

<br>

<?php

					$result=mysql_query("select owner_id, business_name, business_city_code, business_type_code, business_street, 
          business_capital_investment, business_last_yrs_dec_gross_sales from ebpls_business_enterprise") or die(mysql_error());
          while ($resulta=mysql_fetch_row($result));
        
?>

<table align="center" border="0" cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td align="left" width="300"><b><u> Name of Owner: </b></u></td>
      <td align="left" width="250"> _______________________ </td>
    </tr>
    <tr>
      <td width="1000"><b><u> Business/Trade Name: </b></u></td>
      <td> _______________________ </td>
      <td width="1000"><b><u> Type of Ownership: </b></u></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td width="1000"><b><u> Business Address: </b></u></td>
      <td> _______________________ </td>
      <td width="1000"><b><u> Business Status: </b></u></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b><u> Address: </b></u></td>
      <td> _______________________ </td>
      <td><b><u> Specific Business Product or Service </b></u></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b><u> Tel. No.: </b></u></td>
      <td> _______________________ </td>
      <td><b><u> Civil Status: </b></u></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b><u> Date of Birth: </b></u></td>
      <td> _______________________ </td>
      <td><b><u> Citizenship: </b></u></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b><u> Capital Investment: </b></u></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b><u> Permit No.: </b></u></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b><u> Validity: </b></u></td>
      <td> _______________________ </td>
    </tr>
  </tbody>
</table>
<br>
<br>

<?php
	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

?>

<table style="width: 1000px" border="0" cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
    	<td align="left", width="500"><b> Approved By: <br> <br> <br> <br> <br> </b></td>
    	<td align="left", width="500"><b> Noted By: <br> <br> <br> <br> <br> </b></td>
    </tr>
    <tr>
    	<td	align="center"><u><b> <?php echo $resulta[0]; ?> </u> </b> </td>
    	<td align="center"> <u><b> <?php echo $resulta[3]; ?> </u> </b> </td>
    </tr>
    <tr>
    	<td align="center"> <?php echo $resulta[2]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
    	<td align="center"> <?php echo $resulta[7]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
    </tr>
    <tr> 
    	<td align="left"> Date printed: &nbsp; &nbsp; <?php echo date("F d Y"); ?> </td>
   </tbody>
</table>

<br>
<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>

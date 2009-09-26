<?php 
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
  <title>RRC Form 7</title>
  <meta name="Author" content=" Pagoda, Ltd. ">
  <link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body>

RRC Form 7
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
Computation Statement for the collection of Annual fixed per delivery truck or van or any vehicle used by any individual manufacturers,
producers, wholesalers, dealers or retailers in the delivery of certain products per Chapter III, Article G, Section 3.G.01 of Provincial Tax
Ordinance No. 2001-02, Otherwise known as the Revised Revenue Code of Province of _________________:
<br>
<br>
<table style="width: 990px; height: 449px;" align="left" border="0"
 cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td><b> Name: &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ___________________________ </b></td>
      <td><b> "STICKER NOT TRANSFERABLE" </b></td>
    </tr>
    <tr>
      <td><b> Address: &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; ___________________________</b></td>
      <td><b> Sticker Number: &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ___________________________ </b></td>
    </tr>
    <tr>
      <td><b> TIN: &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ___________________________ </b></td>
      <td><b> Registration No: &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ___________________________ </b></td>
    </tr>
    <tr>
      <td><b> Period Covered: &nbsp; &nbsp; ___________________________ </b></td>
      <td><b> Plate Number: &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ___________________________ </b></td>
    </tr>
    <tr>
      <td><b> Style of Business: &nbsp; ___________________________ </b></td>
      <td><b> Route: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ___________________________ </b></td>
    </tr>
    <tr>
      <td align="right"><b> Annual Fixed tax per delivery truck or van </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td align="right"><b> Governor's Permit Fee </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td align="right"><b> Sanitary Inspection Fee </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td align="right"><b> Sticker Fee </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td align="right"><b> Secretary's Fee </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td align="right"><b> Surcharge (25%) </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td align="right"><b> Interest (2% per Month) </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td align="right"> <font color="red"><b> Total </b><br>
      </font> &nbsp; </td>
      <td> _______________________ <br>
      			===================== </td>
    </tr>
    <tr>
    	<td> &nbsp; </td>
    	<td> &nbsp; </td>
    </tr>
    <tr>
      <td align="left"><b> Certified Correct: <br><br></td></b>
      <td align="left"><b> Approved: <br><br></td></b>
    </tr>
    <tr>
      <td align="center">________________________________________________ </td>
      <td align="center">________________________________________________ </td>
    </tr>
    <tr>
      <td align="center">LTOII </td>
      <td align="center"> Provincial Treasurer </td>
    </tr>
  </tbody>
</table>
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;
Paid under OR No. ______________________ dated
____________________________ <br>
Renewable on or before 31st of January for the suceeding year and every year thereafter to avoid penalties.

<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>

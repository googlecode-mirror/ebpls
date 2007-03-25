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
  <title>SAND AND GRAVEL TAX</title>
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
<h4 align="center"><u> SAND AND GRAVEL TAX </u></h4>

<br>
<br>
Computation Statement for the collection of Sand and Gravel Tax for the
extraction, removal or collection of ordinary stone, sand and gravel
and other similar materials from public and private lands of the
government. <br>
<br>
<font size="1">
Chapter III, Article D, Sect. D.01 of Provincial tax Ordinace No.
2001-2002 (Revised Revenue Code of Province of _______________) </font>
<br>
<br>
<table style="width: 955px; height: 403px;" align="left" border="0"
 cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td width="300"><b> Name: </b></td>
      <td width="300"> ____________________________________________ </td>
      <td width="300">&nbsp; </td>
    </tr>
    <tr>
      <td width="300"><b> Address: </b></td>
      <td width="300"> ____________________________________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td width="300"><b> Date: </b></td>
      <td width="300"> ____________________________________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td width="300"><b> Volume of Materials Extracted: </b></td>
      <td width="300"> ____________________________________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td width="300"><b> Place of Extraction:: </b></td>
      <td width="300"> ____________________________________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td width="300"><b> Delivery Site: </b></td>
      <td width="300"> ____________________________________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td width="300"><b> Purpose: </b></td>
      <td width="300"> ____________________________________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td align="right" width="300"><b> Sand and Gravel (__) per cu. m </b></td>
      <td width="300"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _____________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td align="right" width="300"><b> Surcharge (25%) </b></td>
      <td width="300">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _____________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td align="right" width="300"><b> Interest (2% per Month) </b></td>
      <td width="300">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _____________________ </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td align="right" width="300"> <font color="red"><b> Total </b><br>
      </font> &nbsp; </td>
      <td width="300">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _____________________<br>
&nbsp;&nbsp;&nbsp;&nbsp;======================= </td>
      <td width="300"> &nbsp; </td>
    </tr>
    <tr>
      <td width="300"><br>
      <br>
			<b> Certified Correct: </b>
      <br>
      <br>
      </td>
      <td width="300"> &nbsp; </td>
      <td width="300"> <br>
      <br>
			<b> Approved: </td></b>
    </tr>
    <tr>
      <td width="300">________________________
      </td>
      <td width="300"> &nbsp; </td>
      <td width="300"> ________________________ </td>
    </tr>
    <tr>
      <td width="300">LTOII
      </td>
      <td width="300"> &nbsp; </td>
      <td width="300"> Provincial Treasurer </td>
    </tr>
  </tbody>
</table>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
Note: SUbject to the submission of Monthly Extraction Report <br>
Paid under OR No. ______________________ dated
____________________________ <br>

<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>

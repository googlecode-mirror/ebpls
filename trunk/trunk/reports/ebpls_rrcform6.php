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
  <title>RRC Form 6</title>
  <meta name="Author" content=" Pagoda, Ltd. ">
  <link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body>

RRC Form 6
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
Computation Statement for the collection of Permit/Sanitary Inspection
Fee from Operators of Theaters, Cinemas, Video Houses, Disco Pubs,
Concert Halls, Circuses, Carnivals, Boxing Stadia, Bowling Alleys,
Billiard Pools, Beaches Resorts, Cockpits and other similar places of
Amusement, including traveling/transcient places of amusement per
Charter IV, Article A E, Section 4.A.01.c of Provincial Tax Ordinance
No. 2001-02, Otherwise known as the Revised Revenue Code of Province of
_________________:
<br>
<br>
<table style="width: 985px; height: 380px;" align="left" border="0"
 cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td width="300"><b> Name: </b></td>
      <td width="300"> ______________________________________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td width="300"><b> Address: </b></td>
      <td width="300"> ______________________________________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td width="300"><b> TIN: </b></td>
      <td width="300"> ______________________________________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td width="300"><b> Period Covered: </b></td>
      <td width="300"> ______________________________________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td width="300"><b> Kind of Amusement Place: </b></td>
      <td width="300"> ______________________________________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td align="center" width="300"><b> Governor's Permit Fee </b></td>
      <td width="300"> _______________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td align="center" width="300"><b> Sanitary Inspection Fee </b></td>
      <td width="300"> _______________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td align="center" width="300"><b> Surcharge (25%) </b></td>
      <td width="300"> _______________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td align="center" width="300"><b> Interest (2% per Month) </b></td>
      <td width="300"> _______________________ </td>
      <td width="300"> &nbsp;</td>
    </tr>
    <tr>
      <td align="center" width="300"> <font color="red"><b> Total </b><br>
      </font> &nbsp; </td>
      <td width="300"> _______________________ <br>
===================== </td>
      <td width="300"> &nbsp;</td>
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
Paid under OR No. ______________________ dated
____________________________ <br>
Renewable every January 1 - 20 for the suceeding year and every year
thereafter to avoid penalties.

<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>

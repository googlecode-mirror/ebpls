<?php 
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");                                                                                                                        
require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
//require_once("includes/eBPLS_header.php");
require('ebpls-php-lib/html2pdf_lib/fpdf.php');
   
//$dbLink = get_db_connection();
include("includes/variables.php");
include("lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

?>

<?php
echo date("F dS Y h:i:s A");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
  <title>ABSTRACT OF GENERAL COLLECTION</title>
  <meta name="Author" content=" Pagoda, Ltd. ">
  <link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body>

<?php
// Prints something like: Wednesday
//echo date("l");

// Prints something like: Wednesday 15th of January 2003 05:51:38 AM
//echo date("F dS Y h:i:s A");

// Prints: July 1, 2000 is on a Saturday
//echo "July 1, 2000 is on a " . date("l", mktime(0, 0, 0, 7, 1, 2000));
//?> 

<?php

					$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<h4 align="center"> Republic of the Philippines </h4>
<h4 align="center"> <?php echo $resulta[1]; ?> </h4>
<h4 align="center"> <?php echo $resulta[0]; ?> </h4>
<h4 align="center"> <?php echo $resulta[2]; ?> </h4>
<h4 align="center"><u> ABSTRACT OF GENERAL COLLECTION </u></h4>

<hr>

<table border="1" cellpadding="1" cellspacing="1" width="2250">
  <tbody>
    <tr>
      <td align="center"><b> Name of Payor </b></td>
      <td align="center"><b> O.R. No. </b></td>
      <td align="center"><b> Date of Payment </b></td>
      <td align="center"><b> Amount of Payment </b></td>
      <td align="center"><b> Municipal Business Tax </b></td>
      <td align="center"><b> Mayor's Permit Fee </b></td>
      <td align="center"><b> Garbage Fee </b></td>
      <td align="center"><b> Application Fee </b></td>
      <td align="center"><b> Signboard Fee </b></td>
      <td align="center"><b> Inspection Fee </b></td>
      <td align="center"><b> Peddler's Tax </b></td>
      <td align="center"><b> Fish Market Collection </b></td>
      <td align="center"><b> Franchise Fee </b></td>
      <td align="center"><b> Sticker Fee </b></td>
      <td align="center"><b> Authetication Fee </b></td>
      <td align="center"><b> Police Clearance </b></td>
      <td align="center"><b> Mayor's Clearance </b></td>
      <td align="center"><b> Fire Inspection Fee (PD1185) </b></td>
      <td align="center"><b> Community Tax </b></td>
      <td align="center"><b> Basic Tax </b></td>
      <td align="center"><b> Terminal Fee </b></td>
      <td align="center"><b> Miscellaneous Income </b></td>
      <td align="center"><b> Fishery Rental </b></td>
    </tr>
    
    	 <?php
				
	        $result=mysql_query("select business_name, ' ' , ' ', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00',
	        '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00' from ebpls_business_enterprise") or die(mysql_error());
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
/*
	$result=mysql_query("select sign1, sign2, sign3, sign4, pos1, pos2, pos3, pos4
	from permit_templates") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
*/
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

?>
</body>
</html>

<!-- <SCRIPT LANGUAGE="JAVASCRIPT">
  function popupwin() 
        { 
       winpopup=window.open('myviewentry.php?login_id=203715&valueof_day=2&valueof_month=02&valueof_year=2005','popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,toolbar=no,screenX=100,screenY=0,left=100,top=0');
        };  
        
     </script>  -->

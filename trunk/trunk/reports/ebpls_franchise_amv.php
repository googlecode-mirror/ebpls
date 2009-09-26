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
  <title>FRANCHISE OF AUTHORIZED MOTORIZED VEHICLE FOR HIRE</title>
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
<h4 align="center"><u> AUTHORIZED MOTORIZED/FRANCHISED VEHICLE FOR HIRE </u></h4>

<hr>
<h4 align="right"> Body No.: __________________ </h4>
<br>
<table style="width: 884px; height: 179px;" align="center" border="0"
 cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td><b> Operator: </b></td>
      <td> _______________________ </td>
      <td> <br>
      </td>
      <td> <br>
      </td>
      <td><b> Address: </b></td>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b> Make: </b></td>
      <td> _______________________ </td>
      <td> <br>
      </td>
      <td> <br>
      </td>
      <td><b> Color: </td></b>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b> Motor No.: </td></b>
      <td> _______________________ </td>
      <td><b> Chassis No.: </td></b>
      <td> _______________________ </td>
      <td><b> MTOP No.: </td></b>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b> Route: </td></b>
      <td> _______________________ </td>
      <td> <br>
      </td>
      <td> <br>
      </td>
      <td><b> Expiry Date: </td></b>
      <td> _______________________ </td>
    </tr>
    <tr>
      <td><b> Plate No: </td></b>
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

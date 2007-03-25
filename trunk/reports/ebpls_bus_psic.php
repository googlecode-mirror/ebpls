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
  <title>MASTERLIST OF BUSINESS ESTABLISHMENT BY PSIC CODE</title>
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
<h4 align="center"><u> MASTERLIST OF BUSINESS ESTABLISHMENT BY PSIC CODE </u></h4>

<table border="0" cellpadding="1" cellspacing="1" width="1000">
  <tbody>
    <tr>
      <td>
      <hr></td>
    </tr>
  </tbody>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="1100">
  <tbody>
    <tr>
      <td>
      <table border="1" cellpadding="1" cellspacing="1" width="1000">
        <tbody>
          <tr>
            <td align="center" width="50"><b> PSIC Code </b></td>
            <td align="center" width="80"><b> Business Name </b></td>
            <td align="center" width="80"><b> Business Address </b></td>
           </tr>
          
        	
          <?php
				
        		$result=mysql_query("select ' ', business_name, business_street from ebpls_business_enterprise") or die(mysql_error());
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
      <br>
      </td>
    </tr>
  </tbody>
</table>
<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>


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
  <title>REGISTRY OF MOTORIZED VEHICLE</title>
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
<h4 align="center"><u> REGISTRY OF MOTORIZED VEHICLES </u></h4>

<hr>

<table border="1" cellpadding="1" cellspacing="1" width="1000">
  <tbody>
    <tr>
      <td align="center"><b> Year </b></td>
      <td align="center"><b> Amount Paid </b></td>
      <td align="center"><b> Date </b></td>
      <td align="center"><b> O.R. Number </b></td>
      <td align="center"><b> Sticker Number v</td>
      <td align="center"><b> Gov. Permit No. </td>
    </tr>
    
    <?php
				
        	$result=mysql_query("select distinct e.for_year, d.total_amount_paid, ' ', c.or_no, ' ', ' '
           from ebpls_mtop_owner a, ebpls_fees_paid b, ebpls_transaction_payment_or_details c, ebpls_transaction_payment_or d, 
           ebpls_motorized_operator_permit e
           where a.owner_id = b.owner_id and b.owner_id = c.trans_id and a.owner_id=c.trans_id and c.or_no = d.or_no 
           and e.owner_id=a.owner_id") or die (mysql_error());
           $tot = 0;
          
          while ($resulta=mysql_fetch_row($result)){
	        
        	/*
          $result=mysql_query("select owner_id, concat(owner_first_name, ' ', owner_middle_name, ' ', owner_last_name) as name
          from ebpls_mtop_owner") or die(mysql_error());
          while ($resulta=mysql_fetch_row($result)){  */
	          
	        print "<tr>\n";
					foreach ($resulta as $field )
					print "<td>&nbsp;$field&nbsp</td>\n";
				  $tot = $tot+=$resulta[1];
					print "</tr>";
				  
			  	}
			  					
					$tot=round($tot,2);
					print "<tr>\n";
					print "<td> &nbsp; </td>";
					print "<td align='center'> $tot</td>";
					print "<td> &nbsp; </td>";
					print "<td> &nbsp; </td>";
					print "<td> &nbsp; </td>";
					print "<td> &nbsp; </td>";
					
				?>
					
  </tbody>
</table>
<br>
<table border="0" cellpadding="1" cellspacing="1" width="1000">
  <tbody>
    <tr>
      <td class="normal" align="left" valign="top">&nbsp; <font
 size="1">
      <table style="width: 683px; height: 64px;" border="0"
 cellpadding="1" cellspacing="1">
        <tbody>
          <tr>
            <td align="left"> Prepared By: <br>
_______________________ <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp; Position </td>
          </tr>
        </tbody>
      </table>
      </font></td>
      <td class="normal" align="left" valign="top">&nbsp; <font
 size="1">
      <table border="0" cellpadding="1" cellspacing="1" width="80">
        <tbody>
          <tr>
            <td align="left"> Noted By: <br>
_______________________ <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp; Position </td>
          </tr>
        </tbody>
      </table>
      </font></td>
    </tr>
  </tbody>
</table>
<br>
<br>

<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>

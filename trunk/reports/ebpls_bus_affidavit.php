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
  <title>eBPLS AFFIDAVIT</title>
  <meta name="Author" content=" Development Academy of the Philippines ">
  <link href="includes/eBPLS.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" src="includes/eBPLS.js"></script>
</head>
<body>


<?php

					$result=mysql_query("select lgumunicipality from ebpls_buss_preference") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<h4> Republic of the Philippines </h4>
<h4> <?php echo $resulta[0]; ?> </h4>
<h4> X-----------------------------X </h4>

<?php

					$result=mysql_query("select a.business_permit_code, a.application_date, b.business_name, b.business_street, a.transaction,
					concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender, c.owner_civil_status,
					concat(c.owner_street, ' ', c.owner_city_code, ' ', owner_province_code) 
					from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c
					where a.business_permit_code = '$permit_number' and a.owner_id = b.owner_id and b.owner_id = c.owner_id") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<h4 align="right"><font size="2"> PERMIT NO. <?php echo $resulta[0]; ?> </font></h4>
<h4 align="center"> AFFIDAVIT </h4>
<br>
<br>

<?php

					$result=mysql_query("select a.business_permit_code, a.application_date, b.business_name, b.business_street, a.transaction,
					concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender, c.owner_civil_status,
					concat(c.owner_street, ' ', c.owner_city_code, ' ', owner_province_code) 
					from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c
					where a.business_permit_code = '$permit_number' and a.owner_id = b.owner_id and b.owner_id = c.owner_id") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I, <u><?php echo $resulta[5]; ?></u> &nbsp;  of legal age, <u><?php echo $resulta[7]; ?></u>, 
resident of&nbsp; <u><?php echo $resulta[8]; ?> </u>, Philippines after having been duly sworn to in accordance with law do
hereby declare
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; That I am the owner/manager/authorized agent of business firm under the Trade Name/Style
"<u><?php echo $resulta[2]; ?></u>" who is applying for new/renewal permit to possess/engage in <br>
______________________________________________________________________ at <br>
________________________________________________________________, Philippines. 
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; That I hereby declare under oath that the present Capital Investment in the said business is
________________________________________________ (P_______________________). 
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The breakdown of the said Capital is/are follows: 
<br>
<br>
<table align="left" border="0" cellpadding="1" cellspacing="1" width="980">
		<tbody>
    	 <tr>
      	<td width="150">&nbsp;</td>
        	<td> KIND OF BUSINESS </td><td> CAPITAL INVESTMENT </td>
        </tr>
	<tr><br></tr>
       

	<?php
		$getbiz = mysql_query("select bus_nature, cap_inv from tempbusnature
					where owner_id=$owner_id and business_id=$business_id")
			  or die ("getbiz");

		while ($getit = mysql_fetch_row($getbiz))
			{			
		print "  <tr>
        	  	 <td width=150>&nbsp;</td>
          	  	 <td> $getit[0]</td><td> P $getit[1] </td>
       		  	 </tr>";
			}
	?>
      </tbody>
</table>
<br>
<br>
<br>
<br>
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; That I am also aware that this permit should be surrendered in this Office if any business should cease from
operation 
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; That this affidavit is executed in connection with the payment of taxes, feed specified for in Ordinance
No. 399, series of 1993 pursuant to the provision of RA 7160. 
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IN WITNESS WHEREOF, I hereunto sign this affidavit in this _________ day of ______________ 
20__ in the City of __________, Philippines. 
<br>
<br>
<br>
<table style="width: 843px; height: 128px;" align="left"  border="0" cellpadding="1" cellspacing="1">
			<tbody>
      	<tr>
        	<td width="700">&nbsp;</td>
          <td> &nbsp; </td>
          <td align="center"> __________________________ <br> 
          AFFIANT <br> __________________________ <br>
					TITLE<br>
          </td>
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
<table style="width: 843px; height: 128px;" align="left"  border="0" cellpadding="1" cellspacing="1">
			<tbody>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SUBSCRIBED AND SWORN to before me this ______ day of ____________________ 20__ at the City of 
__________, Philippines. </td>
      <td style="vertical-align: top;"><br>
      </td>
    </tr>
    <tr>
      <td> <br>
      <br>
      <br>
Doc. No.: &nbsp;___________ <br>
Page No.: &nbsp;___________ <br>
Book No.: ___________ <br>
Series of : &nbsp;___________ <br>
      <br>
      <br>
      </td>
      <td style="vertical-align: top;"><br>
      </td>
    </tr>
    <tr>
      <td align="right"> NOTARY PUBLIC
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
Until December 31, 20__ <br>
PTR &amp;&nbsp; No.: ___________ <br>
Issued on __________ &nbsp;&nbsp;<br>
      <br>
      <br>
      <br>
      </td>
      <td style="vertical-align: top;"><br>
      </td>
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

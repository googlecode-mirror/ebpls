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
  <title>APPLICATION FOR FISHING PERMIT</title>
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
<h4 align="center"><u> APPLICATION FOR FISHING PERMIT </u></h4>

<br>
<br>
<table align="left" border="0" cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td align="left" width="300"><b> Permit No.: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> <br>
      </td>
      <td align="center" width="200"> <br>
_____________________ <br>
Date </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"> <input type="checkbox"> New
&nbsp;&nbsp; <input type="checkbox"> Renewal &nbsp;&nbsp; </td>
      <td width="200"> <br>
      </td>
      <td align="right" width="200"> &nbsp; </td>
      <td align="right" width="200"> &nbsp;</td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 1. Name: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> &nbsp; </td>
      <td align="right" width="200"> &nbsp;</td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 2. Postal Address: </b></td>
      <td width="200"> _____________________ </td>
      <td align="left" width="200"><br>
      </td>
      <td align="right" width="200"> &nbsp;</td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 3. Date of Birth: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"><b> 4. Place of Birth:</b></td>
      <td width="200"> _____________________ </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 5. Civil Status: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"><b> 6. Sex: </b></td>
      <td align="right" width="200"> _____________________ </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 7. Nationality: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> &nbsp; </td>
      <td align="right" width="200"> &nbsp;</td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 8. Local Name of Fishing Gear: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"><b> 9. In English: </b></td>
      <td align="right" width="200"> _____________________ </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 10. Number of Units: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> <br>
      </td>
      <td align="right" width="200"> <br>
      </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 11. Assessed Value of Fishing Gear: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> <br>
      </td>
      <td align="right" width="200"> <br>
      </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 12. Size of Fishing gear/Mesh Size: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"><b> 13. Size of Area </b></td>
      <td align="right" width="200"> _____________________ </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 14. Number of Crew: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> <br>
      </td>
      <td align="right" width="200"> <br>
      </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 15. Name of Boat/Banca </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> <br>
      </td>
      <td align="right" width="200"> <br>
      </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"> <br>
      </td>
      <td width="200"> Motorized [ ] <br> A. Registered [ ] </td>
      <td align="right" width="200"> Non Motorized [ ] <br> B. Not Registered [ ] </td>
      <td align="right" width="200"> <br>
      </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 16. Registration No.: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> <br>
      </td>
      <td align="right" width="200"> <br>
      </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 17. Average Fish Catch at Present: </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"><b> 18. For the Last Two Years: </b></td>
      <td align="right" width="200"> _____________________ </td>
      <td width="100">&nbsp; </td>
    </tr>
    <tr>
      <td align="left" width="200"><b> 19. Location </b></td>
      <td width="200"> _____________________ </td>
      <td align="right" width="200"> <br>
      </td>
      <td align="right" width="200"> <br>
      </td>
      <td width="100">&nbsp; </td>
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
<br>
<br>
<br>
<br>
<br>
<i>
I declared under penaties of the perjury that the information stated
above are true and correct.
</i><br>
<br>
To be filled up by the Licensing Officer
<br>
<br>
<table border="0" cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td> R.C. No.: __________________<br>
Issued on __________________<br>
Issued at __________________<br>
      </td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
      <td align="center"> ________________________________________ <br>
(Name and Signature of Applicant) </td>
    </tr>
  </tbody>
</table>
<br>
<br>
<hr>
<table style="width: 992px; height: 24px;" border="0" cellpadding="1"
 cellspacing="1">
  <tbody>
    <tr>
      <td align="center" width="700"> INSPECTION REPORT </td>
    </tr>
  </tbody>
</table>
Remarks: ___________________________________________________________<br>
Recommendation: _____________________________________________________<br>
Valid Until: __________________________________________________________<br>

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
    	<td	align="center"><u><b> <?php echo $resulta[4]; ?> </u> </b> </td>
    	<td align="center"> <u><b> <?php echo $resulta[4]; ?> </u> </b> </td>
    </tr>
    <tr>
    	<td align="center"> <?php echo $resulta[2]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
    	<td align="center"> <?php echo $resulta[6]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
    </tr>
    <tr>
    	<td align="left", width="500"><b> Approved By: <br> <br> <br> <br> <br> </b></td>	
    </tr>
    <tr>
    	<td align="center"><b><u> <?php echo $resulta[3]; ?> </b></u> <br> <?php echo $resulta[7]; ?> <br> <br> <br> <br> <br> </td>
    </tr>
    <tr> 
    	<td align="left"> Date printed: &nbsp; &nbsp; <?php echo date("F d Y"); ?> </td>
   </tbody>
</table>

<?php

require_once("includes/eBPLS_footer.php");

?>
</body>
</html>

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
  <title>APPLICATION FOR OCCUPATIONAL PERMIT</title>
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
<h4 align="center"><u> APPLICATION FOR OCCUPATIONAL PERMIT </u></h4>

<table align="left" border="0" cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td align="left" width="300"> <br>
      </td>
      <td width="200"> <br>
      </td>
      <td align="right" width="200"> Serial No: </td>
      <td align="center" width="200"> <br>
_____________________ </td>
      <td width="100">&nbsp; </td>
    </tr>
  </tbody>
</table>
<br>
<br>
<br>
<b>The Honorable Mayor: </b><br>
<br>
Sir/Madam, I the undersigned, have the honor to apply for a permit
(new/renewal) to work as <u>(Occupation) </u> at the <u>(Name of
Employer)</u> located at <u> (House No. Street Name., Barangay) </u>.
<br>
<br>
Indicated hereunder are pertinent information required:
<table align="center" border="0" cellpadding="1" cellspacing="1">
</table>
<table style="width: 962px; height: 188px;" align="left" border="1"
 cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td><b> Name of Applicant (Family Name, First Name, Middle Name) </b></td>
      <td width="60"> <br>
      </td>
      <td width="60"> <br>
      </td>
      <td width="60"> <br>
      </td>
      <td><b> Sex </b></td>
    </tr>
    <tr>
      <td> &nbsp; </td>
      <td> <br>
      </td>
      <td> <br>
      </td>
      <td> <br>
      </td>
      <td> <input type="checkbox"> Male &nbsp;&nbsp; <input type="checkbox"> Female </td>
    </tr>
    <tr>
      <td><b> Address of Applicant (House No, Street Name, Brgy, City) <br> &nbsp; </b></td>
      <td> <br>
      </td>
      <td> <br>
      </td>
      <td> <br>
      </td>
      <td><b> Residence Tel. No. <br> &nbsp; </b></td>
    </tr>
    <tr>
      <td><b> Date of Birth (MM-DD-YYYY) <br> &nbsp; </b></td>
      <td><b> Place of Birth <br> &nbsp; </b></td>
      <td><b> Height (Meter) <br> &nbsp; </b></td>
      <td><b> Weight (KG) <br> &nbsp; </b></td>
      <td><b> Complexion <br> &nbsp; </b></td>
    </tr>
    <tr>
      <td><b> Citizenship (If Alien) <br> &nbsp; </b></td>
      <td><b> Civil Status <br> &nbsp; </td>
     	<td><input type="checkbox"> Single &nbsp; </td>
     	<td><input type="checkbox"> Married &nbsp; </td>
     	<td><input type="checkbox"> Widower &nbsp; </td>
    </tr>
    <tr>
    	<td> &nbsp; </td>
    	<td> &nbsp; </td>
     	<td><input type="checkbox"> Seperated &nbsp; </td>
     	<td><input type="checkbox"> Divorced &nbsp; </td></td>
     	<td> &nbsp; </td>
      </tr>
    <tr>
    	<td><b> Highest Educational Attainment <br> &nbsp; </b></td>
    	<td><input type="checkbox"> Elementary &nbsp; </td>
     	<td><input type="checkbox"> Highschool &nbsp; </td>
     	<td><input type="checkbox"> College &nbsp; </td>
     	<td><input type="checkbox"> Vocational &nbsp; </td>
    </tr>
    </tbody>
</table>
<br>
<table style="width: 962px; height: 10px;" align="left" border="1"
 cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
    	<td width="172"><b> Institution <br> &nbsp; </b></td>
    	<td width="188"> &nbsp; </td>
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
<i>I declared under penaties of the perjury that the information stated
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
<hr>
<table style="width: 990px; height: 24px;" border="0" cellpadding="1"
 cellspacing="1">
  <tbody>
    <tr>
      <td align="center" width="700"><b> INSPECTION REPORT </b> <br><br> </td>
    </tr>
  </tbody>
</table>
Remarks: ___________________________________________________________<br>
Recommendation: _____________________________________________________<br>
Valid Until: __________________________________________________________<br>

<br>
<br>
<br>
<br>

<?php

					$result=mysql_query("select sign1, sign2, sign3, sign4, pos1, pos2, pos3, pos4
					from permit_templates") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<table style="width: 1000px" border="0" cellpadding="1" cellspacing="1">
	<tbody>
		<tr>
    	<td align="left", width="500"><b> Inspected By: <br> <br> <br> <br> <br> </b></td>
    	<td align="left", width="500"><b> Noted By: <br> <br> <br> <br> <br> </b></td>
    </tr>
    <tr>
    	<td	align="center"><u><b> <?php echo $resulta[0]; ?> </u> </b> </td>
    	<td align="center"> <u><b> <?php echo $resulta[2]; ?> </u> </b> </td>
    </tr>
    <tr>
    	<td align="center"> <?php echo $resulta[4]; ?> <br> <br> <br> <br> <br> <br> <br> <br> </td>
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

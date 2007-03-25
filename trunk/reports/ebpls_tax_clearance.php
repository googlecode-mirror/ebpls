<?php                                  
require_once("lib/ebpls.utils.php");
include("lib/phpFunctions-inc.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     

$dbLink = get_db_connection();

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$resulta[0],0,1,'C');
$pdf->Cell(190,5,$resulta[1],0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190,5,$resulta[2],0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(190,5,'TAX CLEARANCE CERTIFICATE',0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');

	/*
	$result=mysql_query("select a.business_permit_code, a.application_date, b.business_name, b.business_street, 
	a.transaction, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
	concat(c.owner_street, ' ', c.owner_city_code, ' ', owner_province_code), a.business_permit_id, d.cap_inv, 
	d.last_yr, b.employee_male, b.employee_female, d.bus_nature, c.owner_barangay_code, b.business_phone_no
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
	where a.business_permit_code ='000000000000028' and a.owner_id = b.owner_id and a.owner_id = c.owner_id 
	and c.owner_id = d.owner_id") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);  */
    
    $result=mysql_query("select a.business_permit_id, a.application_date, b.business_name, b.business_street, 
	a.transaction, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
	concat(c.owner_street, ' ', c.owner_city_code, ' ', c.owner_province_code), a.business_permit_id, d.cap_inv, 
	d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_payment_mode, c.owner_barangay_code,
	b.business_branch, b.business_contact_no 
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
	where a.business_permit_code =$permit_num and a.business_id = b.business_id and a.owner_id = c.owner_id 
	and b.business_id = d.business_id") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
    
//$permit_num
//000000000000028
    
$petsa = date("F d Y");
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,5,'RENEWAL/NEW',0,1,'L');
$pdf->Cell(25,5,'PERMIT NO:',0,0,'L');
$pdf->SetFont('Arial','U',8);
$pdf->Cell(50,5,$resulta[0],0,1,'L');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(170,5,'DATE:',0,0,'R');
$pdf->SetFont('Arial','U',8);
$pdf->Cell(20,5,$petsa,0,1,'L');

$pdf->Cell(190,5,'',0,0,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,5,'TO WHOM IT MAY CONCERN:',0,1,'L');

$pdf->Cell(190,5,'',0,0,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->Cell(45,5,'This is to certify that the applicant ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resulta[5],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(25,5,'a resident of Brgy. ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(65,5,$resulta[15],0,1,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(38,5,', engaged in the business of ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(50,5,$resulta[13],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(38,5,' under the Trade Name/Style ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(59,5,$resulta[2],0,1,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(15,5,'located at ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(70,5,$resulta[16],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(30,5,' with telephone no. ',0,0,'L');
$pdf->SetFont('Arial','BU',8);
$pdf->Cell(25,5,$resulta[17],0,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Cell(45,5,' has no liability with the Office ',0,1,'L');
$pdf->SetFont('Arial','',8);
$pdf->Cell(45,5,'as per records available.',0,1,'L');

$pdf->Cell(190,5,'',0,1,'C');

$pdf->Cell(190,5,'CLEARED IN ACCORDANCE WITH CERTAIN FEE CLEARED AS TO BUSINESS AND PROVISION OF MARKETCODE ORD. 399, S. OF 1993 ',0,1,'L');
$pdf->Cell(190,5,'OTHERWISE KNOWN AS THE LOCAL REVENUE CODE OF ILOILO CITY CLEARED AS TO REAL PROPERTY TAXES',0,1,'L');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
          
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(95,5,$resulta[0],0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(95,5,$resulta[2],0,0,'C');
//$pdf->Cell(95,5,'APPROVED:',0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','BU',10);
$pdf->Cell(95,5,'',0,0,'C');
$pdf->Cell(95,5,$resulta[3],0,1,'C');

$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,'COMPUTATION OF TAXES, FEES, AND OTHER CHARGES: ',0,1,'L');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(120,5,'TAXES/FEES/CHARGES',1,0,'L');
$pdf->SetX(125);
$pdf->Cell(40,5,'GROSS/CAPITAL ',1,0,'C');
$pdf->SetX(165);
$pdf->Cell(40,5,'AMOUNT',1,0,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(200,5,'',0,1,'C');

//000000000000028
//$permit_num

$getid = mysql_query("select owner_id, business_id, transaction
		 from ebpls_business_enterprise_permit 
		where business_permit_code =$permit_num");
$getd = mysql_fetch_row($getid);
$owner_id = $getd[0];
$business_id = $getd[1];
$stat = $getd[2];
	$linebus = mysql_query("select * from tempbusnature where owner_id=$owner_id and
			business_id=$business_id and active=1") or die (mysql_error());

	$number_of_rows = mysql_numrows($linebus);
	
	$i = 1;
	//$pdf->SetY($Y_Table_Position);
	while ($busline=mysql_fetch_row($linebus))
	{
		$pdf->SetX(5);
		$pdf->Cell(120,5,$busline[2],1,0,'L');
		$pdf->SetX(125);
		$pdf->Cell(40,5,$busline[3],1,0,'R');
		$pdf->SetX(165);
		$pdf->Cell(40,5,$busline[4],1,0,'R');
		$i++;
		$pdf->SetY($pdf->GetY()+5);
	} 

$gettax = mysql_query("select a.compval, b.tfodesc from tempassess a, ebpls_buss_tfo b where a.tfoid = b.tfoid and a.owner_id = $owner_id and a.business_id=$business_id");

 $i = 1;
        //$pdf->SetY($Y_Table_Position);
        while ($busline=mysql_fetch_row($gettax))
        {
                $pdf->SetX(5);
                $pdf->Cell(120,5,$busline[1],1,0,'L');
                $pdf->SetX(125);
                $pdf->Cell(40,5,'',1,0,'R');
                $pdf->SetX(165);
                $pdf->Cell(40,5,number_format($busline[0],2),1,0,'R');
                                                                                                 
                $i++;
                $pdf->SetY($pdf->GetY()+5);
        }



$gettag=mysql_query("select sassess from ebpls_buss_preference") or die ("gettag");
$gettag=mysql_fetch_row($gettag);
$pmode = $list[2];
$lockit = '';
if ($gettag[0]=='') {
/// PER ESTAB ASSESS

$resultf = mysql_query("select * from ebpls_buss_tfo where tfoindicator='1' and
                        tfostatus='A' and taxfeetype='2'") or die("--");
                                                                                                 
 $i = 1;
        //$pdf->SetY($Y_Table_Position);
        while ($busline=mysql_fetch_row($resultf))
        {
                $pdf->SetX(5);
                $pdf->Cell(120,5,$busline[1],1,0,'L');
                $pdf->SetX(125);
                $pdf->Cell(40,5,'',1,0,'R');
				$pdf->SetX(165);
                $pdf->Cell(40,5,number_format($busline[6],2),1,0,'R');
                $i++;
                $pdf->SetY($pdf->GetY()+5);
        }
}

	$resultf = mysql_query("select a.compval, b.tfodesc, b.defamt from tempassess a, ebpls_buss_tfo b 
			   where a.tfoid = b.tfoid and a.owner_id = $owner_id and a.business_id=$business_id");
	$resultamt=mysql_fetch_row($resultf);
              
$pdf->SetX(5);
$pdf->Cell(120,5,'TOTAL AMOUNT PAID: ',1,0,'R');
$pdf->SetX(125);
$pdf->Cell(40,5,'',1,0,'R');
$pdf->SetX(165);
$pdf->Cell(40,5,number_format($resultamt[2],2),1,0,'R');

$pdf->Output();

?>





<?php 
/*
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
<h3 align="center"><u> TAX CLEARANCE CERTIFICATE</u></h3>



<h4 align = "left"> RENEWAL/NEW </h4>
<h4 align="left"> PERMIT NO. ______ </h4>
<h4 align="right"> DATE: <u><?php echo date("F d Y"); ?></u> </h4>

<font><br><b>TO WHOM IT MAY CONCERN:</b><br><br></font><div align="justify"><font><br>This is to certify that the applicant _______________ a resident of Brgy. _________________, engaged in the business of 
______________________, <br>
under the Trade Name/Style ______________ located at  __________ with Tel. No. _______________ has no liability with the Office as 
per records available. 
<br>
<br>

<font>CLEARED IN ACCORDANCE WITH CERTAIN FEE CLEARED AS TO BUSINESS AND PROVISION OF MARKETCODE ORD. 399, S. OF 1993 <br>
OTHERWISE KNOWN AS THE LOCAL REVENUE CODE OF ILOILO CITY CLEARED AS TO REAL PROPERTY TAXES<br><br><br>


<?php

					$result=mysql_query("select sign1, sign2, sign3, sign4, pos1, pos2, pos3, pos4
					from permit_templates") or die(mysql_error());
          $resulta=mysql_fetch_row($result);

?>

<table align="center"; width="980", height="100", border="0">
<tbody>
	<tr>
		<td	align="center"><u><b> <?php echo $resulta[0]; ?> </u> </b> <br> <?php echo $resulta[5]; ?> </td>
		<td align="center"> APPROVED: <br></td>
	</tr>
	<tr>
		<td align="center">   <br></td>
		<td	align="center"><u><b> <?php echo $resulta[2]; ?> </u> </b> </td>
</tbody>
</table>
<br>
<br>		
<font><b> COMPUTATION OF TAXES, FEES, AND OTHER CHARGES </b><br>
For _______________ Quarter 20 ____

<table style="width: 985px; height: 30px;" border="1">
  <tbody>
    <tr>
      <td><b> KINDS OF TAXES </b></td>
      <td><b> GROSS SALES </b></td>
      <td><b> TAX DUE </b></td>
    </tr>
  </tbody>
</table>
</font>
<div align="right"><font>TAX DUE: __________</font></div>
<font>Date computes _____________ Computed by_______________. the above
taxes, fees and other charges were paid under OR No. ___________. Dated
___________ in the amount ______________.
NOTE: the collectors must fill up the copy of this certification.
</font>
<?php

require_once("includes/eBPLS_footer.php");


*/
?>


<?php                                  
require_once("lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('ebpls-php-lib/html2pdf_lib/fpdf.php');     

$dbLink = get_db_connection();

		$result=mysql_query("select lgumunicipality, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
   
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->image('peoplesmall.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$resulta[0],0,1,'C');
$pdf->Cell(190,5,$resulta[1],0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,$resulta[2],0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'INDIVIDUAL RECORD OF PAYMENT',0,1,'C');
				
	$result=mysql_query("select a.business_permit_id, a.application_date, b.business_name, b.business_street, 
	a.transaction, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
	concat(c.owner_street, ' ', c.owner_city_code, ' ', c.owner_province_code), a.business_permit_id, d.cap_inv, 
	d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_payment_mode
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
	where a.business_permit_code ='$permit_num' and a.business_id = b.business_id and a.owner_id = c.owner_id 
	and b.business_id = d.business_id") or die(mysql_error());
    $resulta=mysql_fetch_row($result);

    $result1=mysql_query("select a.business_permit_id, a.application_date, b.business_name, b.business_street, 
	a.transaction, concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), c.owner_gender,
	concat(c.owner_street, ' ', c.owner_city_code, ' ', c.owner_province_code), a.business_permit_id, d.cap_inv, 
	d.last_yr, b.employee_male, b.employee_female, d.bus_nature, b.business_payment_mode, a.active
	from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
	where a.business_permit_code ='$permit_num' and a.business_id = b.business_id and a.owner_id = c.owner_id 
	and b.business_id = d.business_id and a.active = 1") or die(mysql_error());
    $resulta1=mysql_fetch_row($result1);
          
$pdf->Cell(190,5,'',0,2,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Permit No.:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,$resulta[0],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Type of Application:',1,0,'L');
$pdf->SetX(165);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,$resulta[4],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Application Date:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,$resulta[1],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Payment Mode:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
$pdf->Cell(40,5,$resulta[14],1,1,'L');
$pmode = strtolower($resulta[14]);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Business Trade Name:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(155,5,$resulta[2],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Business Address:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(155,5,$resulta[3],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Owner:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,$resulta[5],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Gender:',1,0,'L');
$pdf->SetX(165);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,$resulta[6],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Home Address:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(155,5,$resulta[7],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'Type of Ownership:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,$resulta[7],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Community Tax No:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
$pdf->Cell(40,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,0,'',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Issued on:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
$pdf->Cell(40,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(75,5,'',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
$pdf->Cell(40,5,'Issued at:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
$pdf->Cell(40,5,'',1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'',0,0,'L');
$pdf->SetX(50);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,5,'',0,0,'L');
$pdf->SetX(125);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetX(175);
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,5,'',0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(45,5,'No. of Employees:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
$pdf->Cell(30,5,'Male',1,0,'L');
$pdf->SetX(80);
$pdf->Cell(30,5,$resulta[11],1,0,'C');
$pdf->SetX(110);
$pdf->Cell(30,5,'Female',1,0,'L');
$pdf->SetX(140);
$pdf->Cell(30,5,$resulta[12],1,0,'C');
$pdf->SetX(170);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,5,'',0,1,'L');
//$pdf->SetFont('Arial','',10);
//$pdf->Cell(50,5,'',0,1,'L');

$pdf->Cell(50,5,'',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'',0,1,'L');

$pdf->Cell(200,5,'',0,2,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(120,5,'LINE OF BUSINESS',1,0,'L');
$pdf->SetX(125);
$pdf->Cell(40,5,'CAPITAL INVESTMENT',1,0,'C');
$pdf->SetX(165);
$pdf->Cell(40,5,'GROSS LAST YEAR',1,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(200,5,'',0,1,'C');


$getid = mysql_query("select owner_id, business_id, transaction
		 from ebpls_business_enterprise_permit 
		where business_permit_code ='$permit_num'");
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

$pdf->Cell(200,5,'',0,2,'C');
	
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(120,5,'TAXES AND FEES:',1,0,'L');
$pdf->Cell(40,5,'PERIOD COVERED:',1,0,'C');
$pdf->Cell(40,5,'AMOUNT:',1,1,'C');


$gettax = mysql_query("select a.compval, b.tfodesc, c.payment_part, b.taxfeetype
					 from tempassess a, ebpls_buss_tfo b, ebpls_transaction_payment_or_details c
					 where a.tfoid = b.tfoid and a.owner_id = $owner_id and a.business_id=$business_id
					 and a.owner_id=c.trans_id and a.business_id=c.payment_id and a.transaction=c.transaction
					 and a.active=1 and c.transaction='New'");

 $i = 1;
        //$pdf->SetY($Y_Table_Position);
        while ($busline=mysql_fetch_row($gettax))
        {
	        $periodico = $busline[2];
	        //dito ka maglagay ng code kung gagawin mong descriptive un payment mode, ok!?!?!?!
	        if ($pmode == 'annual') {
		        	$paymentmode = 'One Year';
		        	$divider = 1;
    		}
    		if ($pmode == 'semi-annual' and $periodico == '1') {
		        	$paymentmode = '1st half';
		        	$divider = 2;
	    	} elseif ($pmode == 'semi-annual' and $periodico == '2') {
		    		$paymentmode = '2nd half';
		    		$divider = 2;
    		}
    		if ($pmode == 'quarterly' and $periodico == '1') {
		        	$paymentmode = '1st Quarter';
		        	$divider=4;
	    	}elseif ($pmode == 'quarterly' and $periodico == '2') {
		        	$paymentmode = '2nd Quarter';
		        	$divider=4;
	    	}elseif ($pmode == 'quarterly' and $periodico == '3') {
		        	$paymentmode = '3rd Quarter';
		        	$divider=4;
	    	}elseif ($pmode == 'quarterly' and $periodico == '4') {
		        	$paymentmode = '4th Quarter';
		        	$divider=4;
	    	}
	    	
			
			
			    	
	    	
	    	$pdf->SetX(5);
            $pdf->Cell(120,5,$busline[1],1,0,'L');
			$pdf->SetX(125);
			//$pdf->Cell(40,5,$busline[2],1,0,'C');
            $pdf->Cell(40,5,$paymentmode,1,0,'C');
            $pdf->SetX(165);
            $pdf->Cell(40,5,number_format($busline[0]/$divider,2),1,0,'R');
                                                                                                 
                $i++;
                $pdf->SetY($pdf->GetY()+5);
        }



$gettag=mysql_query("select sassess, staxesfees from ebpls_buss_preference") or die ("gettag");
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
	        //pang fees lang	
						//$busline[6]=$busline[6]/$divider;
						if ($gettag[1]<>'') {
							$paymentmode='1st Quarter';
							$divider = 1;
						} else {
						$paymentmode=$paymentmode;
						//$divider = 1;
						}
	        
                $pdf->SetX(5);
                $pdf->Cell(120,5,$busline[1],1,0,'L');
                $pdf->SetX(125);
                $pdf->Cell(40,5,$paymentmode,1,0,'C');
				$pdf->SetX(165);
                $pdf->Cell(40,5,number_format($busline[6]/$divider,2),1,0,'R');

                $i++;
                $pdf->SetY($pdf->GetY()+5);
        }


}



$pdf->Cell(200,5,'',0,2,'C');
$pdf->Cell(200,5,'',0,2,'C');


$pdf->Cell(200,5,'NOTE:',0,1,'L');
$pdf->Cell(200,5,'     Please disregard this notice if payment has been made.',0,1,'L');


//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//      $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'Recommend Approval:',0,0,'L');
$pdf->SetX(105);
$pdf->Cell(100,5,'Approved:',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(100,5,'',0,0,'C');
//$pdf->SetX(105);
//$pdf->Cell(100,5,$resulta[0],0,1,'C');
//$pdf->SetFont('Arial','B',10);

//$pdf->SetX(5);
//$pdf->Cell(100,5,'',0,0,'C');
//$pdf->SetX(105);
//$pdf->Cell(100,5,$resulta[2],0,0,'C');

$report_desc='Individual Payment Record';
include 'report_signatories_footer.php';

$pdf->Output();



?>

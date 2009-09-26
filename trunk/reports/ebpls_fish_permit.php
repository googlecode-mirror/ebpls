<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
$permit_type='Fishery';
include"../includes/variables.php";
include_once("../lib/multidbconnection.php");
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

		$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error());
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
//$pdf->image('',10,5,33);
$pdf->Image('../images/ebpls_logo.jpg',10,5,33);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
$pdf->Cell(190,5,$getprov[0],0,1,'C');
$pdf->Cell(190,5,$getlgu[0],0,2,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,'Office of the Mayor',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',16);
$pdf->Cell(190,5,'FISHERY PERMIT/LICENSE',0,1,'C');

if ($reportpermit == '1') {
$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	concat(a.owner_street, ', ', d.zone_desc, ', ', e.barangay_desc, ', ', f.district_desc, ', ', g.city_municipality_desc, ', ', 
	h.province_desc, ' ', a.owner_zip_code), a.owner_id, b.transaction, b.for_year, b.ebpls_fishery_permit_code
	from ebpls_owner a, ebpls_fishery_permit b, ebpls_zone d, ebpls_barangay e, ebpls_district f, ebpls_city_municipality g, ebpls_province h, ebpls_zip i
	where a.owner_id=b.owner_id and b.active = '1' and b.ebpls_fishery_permit_code ='$permit_num' or a.owner_last_name = '$owner_last' limit 1") 
	or die("1".mysql_error());
} else {
	$result = mysql_query("select concat(a.owner_first_name, ' ', a.owner_middle_name, ' ', a.owner_last_name),
	concat(a.owner_street, ', ', d.zone_desc, ', ', e.barangay_desc, ', ', f.district_desc, ', ', g.city_municipality_desc, ', ', 
	h.province_desc, ' ', a.owner_zip_code), a.owner_id, b.transaction, b.for_year, b.ebpls_fishery_permit_code
	from $owner a, $permittable b, ebpls_zone d, ebpls_barangay e, ebpls_district f, ebpls_city_municipality g, ebpls_province h, ebpls_zip i
	where a.owner_id=b.owner_id and $incode ='$permit_num' and b.active = '1'") 
	or die($permittype.mysql_error());
}
	$resulta=mysql_fetch_row($result);
$permit_num = $resulta[5];
$nValidity = $resulta[4];

//$permit_num 


$pdf->Cell(190,5,'',0,1,'C');
$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(50,5,'TO WHOM IT MAY CONCERN:',0,1,'L');

$pdf->Cell(190,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(40,5,'This is to certify that',0,0,'L');
$pdf->SetX(50);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(150,5,$resulta[0],0,1,'C');
$pdf->SetX(50);
$pdf->Cell(150,5,'-----------------------------------------------------------------------------------------------------------------------------',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->SetX(10);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,'of ');
$pdf->SetFont('Arial','BU',10);
$pdf->Write(5,$resulta[1]);
$pdf->SetFont('Arial','',10);
$pdf->Write(5,' is hereby granted to operate his/her fishing activity.',0,1,'L');
$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

if ($reportpermit == '1') {
	$fish= mysql_query("select boat_name, crew, engine_type, engine_cap, reg_no from fish_boat where owner_id='$resulta[2]'") 
	or die("12".mysql_error());
} else {
$fish= mysql_query("select boat_name, crew, engine_type, engine_cap, reg_no from fish_boat where owner_id='$owner_id'") 
	or die("12".mysql_error());
}

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(60,5,'NAME OF FISHING BOAT',1,0,'C');
$pdf->SetX(65);
$pdf->Cell(40,5,'NO. OF CREW(S)',1,0,'C');
$pdf->SetX(105);
$pdf->Cell(60,5,'ENGINE TYPE/CAPACITY',1,0,'C');
$pdf->SetX(165);
$pdf->Cell(35,5,'REGISTRATION NO.',1,1,'C');
while ($fish1=mysql_fetch_row($fish)) 
{
//put values here
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(60,5,$fish1[0],1,0,'C');
$pdf->Cell(40,5,$fish1[1],1,0,'C');
$pdf->Cell(60,5,$fish1[2]."/".$fish1[3],1,0,'C');
$pdf->Cell(35,5,$fish1[4],1,1,'C');
}

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');
$owner_id=$resulta[2];
$stat = $resulta[3];
if ($reportpermit == '1') {
$getboat = mysql_query("select * from fish_boat where owner_id='$resulat[2]'") or die('3'.mysql_error);
} else {
	$getboat = mysql_query("select * from fish_boat where owner_id=$owner_id");
}
while ($getb = mysql_fetch_row($getboat))
{
$getfee = mysql_query("select amt,range_lower, range_higher
                        from boat_fee where boat_type='$getb[4]' and
                        range_lower<$getb[5] and range_higher>=$getb[5] and
                        transaction='$stat' and active = 1") or die(mysql_error());
$getnum=mysql_num_rows($getfee);
        if ($getnum==0) {
                $getfee = mysql_query("select amt
                        from boat_fee where boat_type='$getb[4]' and
                        range_lower<=$getb[5] and range_higher=0 and
                        transaction='$stat' and active = 1") or die(mysql_error());
        }
$getfee = mysql_fetch_row($getfee);
$ttfee = $ttfee+$getfee[0]; 
}
$ttfee = number_format($ttfee,2);

if (reportpermit == '1') {
$getboat = SelectDataWhere($dbtype,$dbLink,"fish_assess","where owner_id=$resulta[2]") or die('4'.mysql_error);
} else {
	$getboat = SelectDataWhere($dbtype,$dbLink,"fish_assess","where owner_id=$owner_id");
}

while ($getb = FetchArray($dbtype,$getboat))
{
$getfee = SelectDataWhere($dbtype,$dbLink,"culture_fee",
			"where culture_id='$getb[culture_id]' and
		    active = '1'");

$getnum = FetchArray($dbtype,$getfee);
       
		if ($getnum[fee_type]=='3') {
			
				$getfee = SelectDataWhere($dbtype,$dbLink,"culture_range",
					"where culture_id='$getb[culture_id]' and
		             range_lower<=$getb[amt] and range_higher>=$getb[amt] ");
				$getnum = NumRows($dbtype,$getfee);
		        	if ($getnum==0) {
			
	            		$getfee =  SelectDataWhere($dbtype,$dbLink,"culture_range",
                        "where culture_id='$getb[culture_id]' and
                        range_lower<=$getb[amt] and range_higher=0");
                	}
				$getfee1 = FetchArray($dbtype,$getfee);
				$getfee1 = $getfee1[amt];

    	} elseif ($getnum[fee_type]=='1') { //constant
		$getfee1 = $getnum[const_amt];
	} elseif ($getnum[fee_type]=='2') { //formula
	eval("\$getfee1=$getb[amt]$getnum[formula_amt];");
	}
$ttfee1 = $ttfee1+$getfee1; 
}




$getot = mysql_query("select * from ebpls_fishery_fees where permit_type='$stat' and active=1") or die('5'.mysql_error);
$getact = mysql_query("select sum(amt) from fish_assess where owner_id=$owner_id
                        and transaction='$stat' and active = 1") or die (mysql_error());
        $getact = mysql_fetch_row($getact);
        $tfee1 = $getact[0];
$tfee1=number_format($tfee1,2);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(200,5,'',0,1,'L');
$pdf->SetX(5);
$pdf->Cell(100,5,'TAX/FEE DEFINITION',1,0,'C');
$pdf->Cell(40,5,'AMOUNT',1,1,'C');
$ff = 0;
$pdf->SetX(5);
$pdf->Cell(100,5,'Boat Registration Fee',1,0,'L');
$pdf->Cell(40,5,$ttfee,1,1,'R');
$pdf->SetX(5);
$pdf->Cell(100,5,'Fish Activities Fee',1,0,'L');
$pdf->Cell(40,5,number_format($ttfee1,2),1,1,'R');
$ff = $ff + $ttfee + $ttfee1;
while ($getj = mysql_fetch_row($getot))
{
	
$pdf->SetX(5);
$pdf->Cell(100,5,$getj[1],1,0,'L');
$pdf->Cell(40,5,number_format($getj[2],2),1,1,'R');
$ff = $ff + $getj[2];
}
$pdf->SetX(5);
$pdf->Cell(100,5,'TOTAL',1,0,'R');
$pdf->Cell(40,5,number_format($ff,2),1,1,'R');
/*
$getid = mysql_query("select owner_id, transaction from ebpls_motorized_operator_permit
                where motorized_operator_permit_id ='$permit_num' and active = 1");

$getid = mysql_query("select owner_id, transaction from ebpls_motorized_operator_permit
                where motorized_operator_permit_id ='$permit_num'");            
               //000000000000007
$getd = mysql_fetch_row($getid);
$owner_id = $getd[0];
$stat = $getd[1];


                                                                                                               
 $i = 1;
        $pdf->SetY($Y_Table_Position);
        while ($busline=mysql_fetch_row($getfee))
        {
                $pdf->SetX(5);
                $pdf->Cell(120,5,$getfee[0],1,0,'L');
                $pdf->SetX(125);
                $pdf->Cell(40,5,'',1,0,'R');
                $pdf->SetX(165);
                $pdf->Cell(40,5,number_format($getfee[0],2),1,0,'R');
                                                                                                               
                $i++;
                $pdf->SetY($pdf->GetY()+5);
        }
*/

$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->SetX(10);
$pdf->Cell(100,5,'Issued for all legal intents and/or registration purpose this',0,0,'L');
$pdf->SetX(110);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(30,5,date("F dS Y"),0,1,'L');
$pdf->SetX(10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(40,5,' with permit number ',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,5,$permit_num ,0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,5,'Valid until December 31, ',0,0,'R');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(25,5,$nValidity.".",0,0,'L');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//    $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'L');
$pdf->SetX(135);
$pdf->Cell(70,5,'Approved:',0,1,'C');

$pdf->Cell(200,5,'',0,1,'C');
$pdf->Cell(200,5,'',0,1,'C');

$getsignatories = @mysql_query("select * from report_signatories where report_file='Fishery Permit' and sign_type='1'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'L');
$pdf->SetX(135);
$pdf->Cell(70,5,$getsignatories1[gs_name],0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'L');
$pdf->SetX(135);
$pdf->Cell(70,5,$getsignatories1[gs_pos],0,1,'C');

$pdf->Output();

?>


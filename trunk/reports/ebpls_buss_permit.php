<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     

$permit_type='Business';
include'../includes/variables.php';

//$dbLink = get_db_connection();
include_once("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

if ($reportpermit == '1') {
if ($owner_last <> "") {
$getinfo = @mysql_query("select owner_id from ebpls_owner where owner_last_name = '$owner_last' limit 1");
$getinfo1 = @mysql_fetch_row($getinfo);
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where owner_id = '$getinfo1[0]' ORDER BY business_permit_id DESC limit 1");
}
if ($bus_name <> "") {
$getinfo = @mysql_query("select business_id from ebpls_business_enterprise where business_name = '$bus_name' limit 1");
$getinfo1 = @mysql_fetch_row($getinfo);
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where business_id = '$getinfo1[0]' ORDER BY business_permit_id DESC limit 1");
//echo "select business_id from ebpls_business_enterprise where business_name = '$bus_name' limit 1";
}
if ($permit_num <> "") {
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where business_permit_code = '$permit_num' ORDER BY business_permit_id DESC limit 1");
}
if ($pin <> "") {
$getinfo = @mysql_query("select owner_id, business_id, business_permit_code from ebpls_business_enterprise_permit where pin = '$pin' ORDER BY business_permit_id DESC limit 1");
}
$getinformation = @mysql_fetch_assoc($getinfo);
$permit_num = $getinformation[business_permit_code];
$owner_id = $getinformation[owner_id];
$business_id = $getinformation[business_id];
$isrel = 1;
//echo $permit_num;
}
$getformat = mysql_query("select spermit from ebpls_buss_preference");
$getformat = mysql_fetch_row($getformat);
$getformat = $getformat[0];
$intloop=0;
$xfg=1;

if ($isrel==1) {
$ddt ="a.business_permit_code ='".$permit_num."'";

} else {
$ddt ="b.business_id =".$business_id." and b.owner_id=".$owner_id;
}

if ($getformat=='') {
	$loppage=1;
	
} else {

/*
$getid = mysql_query("select owner_id, business_id, transaction
                 from ebpls_business_enterprise_permit
                where business_permit_code ='$permit_num'");
$getd = mysql_fetch_row($getid);
$owner_id = $getd[0];
$business_id = $getd[1];
*/

	$loopbus = mysql_query("select * from tempbusnature where owner_id=$owner_id and
                        business_id=$business_id and active=1") or die ("lll".mysql_error());
	$loppage=mysql_num_rows($loopbus);


}
$pdf=new FPDF('P','mm','A4');
	
if ($getformat=='1') { //allow single line per permit



	
while ($intloop<$loppage) {

		while ($ltb=mysql_fetch_row($loopbus)) {
		$tempid = $ltb[0];	
		

		$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error()."d");
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
//$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->image('../images/ebpls_logo.jpg',10,5,33);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'Republic of the Philippines',0,1,'C');
$pdf->Cell(190,5,$getprov[0],0,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,$getlgu[0],0,1,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,'',0,0,'C');
$pdf->Cell(190,5,'Office of the Mayor',0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetX(170);
$pdf->Cell(30,30," ",1,1,'C');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,5,"MAYOR'S PERMIT",0,0,'C');

	$result=mysql_query("select a.business_permit_code, a.application_date, 
				b.business_name, b.business_street, a.transaction, 
				concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), 
				c.owner_gender,	concat(c.owner_street, ' ', c.owner_city_code, ' ', 
				c.owner_province_code), a.business_permit_id, d.cap_inv, 
				d.last_yr, b.employee_male, b.employee_female, d.bus_nature, 
				b.business_payment_mode, business_occupancy_code, concat(b.business_lot_no, ' ', 
				b.business_street, ' ', b.business_city_code, ' ', b.business_province_code, ' ', 
				b.business_zip_code),c.owner_civil_status from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
				where $ddt and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id") or die(mysql_error()."fff");
    $resulta=mysql_fetch_row($result);

    $result1=mysql_query("select a.business_permit_code, a.application_date, b.business_name, 
			  b.business_street, a.transaction, 
			  concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', 
			  c.owner_last_name), c.owner_gender,
			  concat(c.owner_street, ' ', c.owner_city_code, ' ', 
			  c.owner_province_code), a.business_permit_id, d.cap_inv, 
			  d.last_yr, b.employee_male, b.employee_female, 
			  d.bus_nature, b.business_payment_mode, a.active
		      	  from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, 
			  ebpls_owner c, tempbusnature d
		          where $ddt and 
			  a.business_id = b.business_id and a.owner_id = c.owner_id 
			  and b.business_id = d.business_id ") or die(mysql_error()."dddd");
    $resulta1=mysql_fetch_row($result1);
	$getadd=mysql_query("select concat(c.owner_street, ' ', e.city_municipality_desc, ' ', 
				f.province_desc) from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d, 
				ebpls_city_municipality e, ebpls_province f
				where $ddt and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and c.owner_city_code = e.city_municipality_code and 
				c.owner_province_code = f.province_code") or die(mysql_error()."fff");
    $getadd=mysql_fetch_row($getadd);
	$getbadd=mysql_query("select concat(b.business_lot_no, ' ', 
				b.business_street, ' ', e.city_municipality_desc, ' ', f.province_desc, ' ', 
				b.business_zip_code) from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d,
				ebpls_city_municipality e, ebpls_province f
				where $ddt and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and b.business_city_code = e.city_municipality_code and 
				b.business_province_code = f.province_code") or die(mysql_error()."fff");
    $getbadd=mysql_fetch_row($getbadd);
          
if ($isrel==1) {

$mainp = $permit_num;
$d = strrpos($mainp,'-');//get sequence
$f = strlen($mainp);//lenght of peermit
$bodyp = substr($mainp,0,$f-$d-1);//permit format
$sequence = substr($mainp,$f-$d-1);//add 1 to sequence
                                                                                                               
        //get first
        if ($fgh==''){
                $sequence = '1'.$sequence;
		$sequence = $sequence - $loppage;
                $fgh='1';
        } else {
		$sequence = '1'.$sequence;
	}
                                                                                                               
$sequence = '1'.$sequence + 1;
$sequence = substr($sequence, 2,13); // sequence number
$permitcode = $bodyp.$sequence;
$permit_num=$permitcode;
}
$backpos = strpos($permit_num,'-');
$backpos = strpos($permit_num,'-',$backpos);
$rr = $permit_num;
$per_num=substr($rr,$backpos+1);
$per_num = date('Y').'-'.substr($per_num,6);
//$permit_num=date('Y').'-'.$permit_num;
$pdf->Cell(190,5,'',0,2,'C');

$pdf->SetFont('Arial','B',12);
$pdf->SetX(5);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(45,5,'Permit No.: '.$per_num,0,0,'L');
$pdf->SetX(170);
$pdf->Cell(40,5,'Date: '.date('M d, Y'),0,0,'L');
$pdf->SetX(165);
$pdf->SetFont('Arial','',10);
//$pdf->Cell(40,5,$resulta[4],0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
//$pdf->Cell(45,5,'Application Date:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
//$pdf->Cell(75,5,$resulta[1],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
//$pdf->Cell(40,5,'Payment Mode:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
//$pdf->Cell(40,5,$resulta[14],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(190,5,'',0,2,'C');
//$pdf->Cell(45,5,'Business Trade Name:',1,0,'L');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','B',20);
$pdf->Cell(190,5,strtoupper($resulta[2]),0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'BUSINESS NAME',0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(190,5,'situated at '.strtoupper($getbadd[0]).' this Municipality',0,1,'C');
//$pdf->SetFont('Arial','',10);
//$pdf->SetX(50);
//$pdf->Cell(155,5,$resulta[3],1,1,'L');

$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(190,5,"Pursuant to the provision of Revenue Code of the Municipality of ".$getlgu[0]." MAYOR'S PERMIT",0,1,'L');
$pdf->Cell(190,5,'is hereby granted',0,1,'L');
$pdf->SetFont('Arial','B',10);
//$pdf->Cell(40,5,'',0,1,'L');
$pdf->SetFont('Arial','B',15);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,strtoupper($resulta[5]),0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell(190,5,'(Name of Applicant)',0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');

//$pdf->Cell(190,5,'',0,2,'C');
//$pdf->SetFont('Arial','',12);
//$pdf->Cell(190,5,'situated at '.$resulta[16],0,1,'C');
//$pdf->SetFont('Arial','BU',12);
//$pdf->Cell(190,5,$resulta[7],0,1,'C'); 

if ($resulta[6]=='M') {
	$tag = 'Mr.';
} else {
	if ($resulta[15]=='Single') {
		$tag='Ms.';
	} else {
		$tag='Mrs.';
	}
}

$pdf->SetFont('Arial','',12);
$pdf->Cell(190,5,'registered proprietor owner/manager of which is',0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(190,5,"$tag $resulta[5]",0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(190,5,'with legal residence/office/s at '.strtoupper($getadd[0]).' to operate/construct/install: ',0,'C');



	$sqltb = ' and tempid ='.$tempid;


	$linebus = mysql_query("select * from tempbusnature where owner_id=$owner_id and
			business_id=$business_id and active=1 $sqltb") or die ($sqltb.mysql_error()."daaa");

	$number_of_rows = mysql_numrows($linebus);
	
	$i = 1;
	//$pdf->SetY($Y_Table_Position);
$pdf->Cell(190,3,'',0,1,'C');
	while ($busline=mysql_fetch_row($linebus))
	{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(190,5,$busline[2],0,1,'C');
//		$pdf->SetX(125);
//		$pdf->Cell(40,5,$busline[3],1,0,'R');
//		$pdf->SetX(165);
//		$pdf->Cell(40,5,$busline[4],1,0,'R');
		$i++;
		$pdf->SetY($pdf->GetY()+5);
	}
$pdf->SetFont('Arial','',12); 
$pdf->Cell(190,5,'subject to existing laws, ordinances; rules and regulations.',0,1,'C');
$pdf->SetFont('Arial','I',9);
$pdf->Cell(190,5,'O.K as to business requirements',0,1,'C');

$pdf->Cell(200,5,'',0,2,'C');


$getorno = mysql_query("select a.payment_code, b.ts 
			from ebpls_transaction_payment_or a, ebpls_transaction_payment_or_details b
			where a.or_no=b.or_no and b.payment_id='$business_id' and b.trans_id='$owner_id' 
			order by a.or_no desc limit 1");
$getor = mysql_fetch_row($getorno);
$or_no = $getor[0];


//new signatories table
$getsignatories = mysql_query("select * from report_signatories where report_file='$report_desc' and sign_type=1") or die(mysql_error());
$getsignatories1 = mysql_fetch_array($getsignatories);
$sign_id = $getsignatories1[sign_id];
	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id = '$sign_id'") or die(mysql_error()."dasd");
    $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
//$pdf->Cell(100,5,'Recommend Approval:',0,0,'L');
$pdf->SetX(105);
//$pdf->Cell(100,5,'Approved:',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
//$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,5,'',0,0,'C');
$pdf->SetX(105);
$pdf->Cell(100,5,strtoupper($resulta[0]),0,1,'C');
$pdf->SetFont('Arial','B',10);

$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'C');
$pdf->SetX(105);
$pdf->Cell(100,5,$resulta[1],0,0,'C');



$pdf->SetFont('Arial','',8);
$pdf->SetX(15);
$pdf->Cell(25,25,'DOC. STAMPS',1,0,'C');
$pdf->SetFont('Arial','',12);
$pdf->SetX(60);
$pdf->Cell(190,5,'O.R. No.: ',0,0,'L');
$pdf->SetFont('Arial','B',12);
$pdf->SetX(78);
$pdf->Cell(190,5,$or_no,0,1,'L');
$pdf->SetFont('Arial','',12);
$pdf->SetX(60);
$pdf->Cell(190,5,'SERIES : ',0,0,'L');
$pdf->SetFont('Arial','B',12);
$pdf->SetX(78);
$pdf->Cell(190,5,substr($getor[1],0, 4),0,1,'L');


$pdf->Cell(190,5,'',0,1,'L');
$pdf->Cell(190,5,'',0,1,'L');
$pdf->Cell(190,5,'',0,1,'L');
$pdf->SetX(15);
$pdf->SetFont('Arial','',8);
$pdf->Cell(190,5,'Not valid without seal',0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,5,'(NOTE: This permit must be displayed in a conspicuous place within the establisment and must',0,1,'C');
$pdf->Cell(190,5,'likewise be renewed after every end of the quarter/semester/year. This permit is not valid if',0,1,'C');
$pdf->Cell(190,5,'Official Receipt number is not indicated hereon.)',0,1,'C');
$pdf->Cell(190,5,'VALID UP TO DECEMBER 31, '.date('Y'),0,1,'C');




$intloop++;

if ($isrel==0) {
$mainp = $permit_num;
$d = strrpos($mainp,'-');//get sequence
$f = strlen($mainp);//lenght of peermit
$bodyp = substr($mainp,0,$f-$d-1);//permit format
$sequence = substr($mainp,$f-$d-1);//add 1 to sequence
$sequence = '1'.$sequence + 1;
$sequence = substr($sequence, 1,11); // sequence number
$permitcode = $bodyp.$sequence;
$permit_num=$permitcode;
                                                                                                                                                            
//get pin
  $getpin = mysql_query("select pin, pmode,transaction from $permittable where
                         owner_id = $owner_id and business_id = $business_id 
                         and transaction<>'' and
			 pin<>'' order by business_permit_id desc limit 1");
  $pint = mysql_fetch_row($getpin);
  $pin = $pint[0];
  $pmde = $pint[1];
  $stat=$pint[2];

                                                                                                                                                            
                               
 $updateit = mysql_query("update $permittable set active = 0 
                        where owner_id = $owner_id and business_id=$business_id 
			")
                        or die (mysql_error());
                                                                                                                                                            
 $res = mysql_query ("insert into $permittable 
			($incode,business_id, owner_id, for_year
                        ,application_date,input_by, transaction, 
			paid, released, steps, pin, 
			active,pmode)
                      values 
			('$permit_num', $business_id, $owner_id, '$currdate[year]', 
			now(), '$usern', '$stat', 
			1, 1, 'Release','$pin', 
			1,'$pmde')")
	             or die ("permitdie".mysql_error());
}


	}

}
$pdf->Output();

if ($isrel<>1) {
if ($stat=='') {
	$stat='New';
}
$delextra = mysql_query("delete from $permittable where business_id=$business_id and
			owner_id=$owner_id order by business_permit_id desc limit 1") 
			or die (";;;".mysql_error());

$updateit = mysql_query("update $permittable set active = 0 
                        where owner_id = $owner_id and business_id=$business_id
                        ")
                        or die (mysql_error());

$updlast  = mysql_query("update $permittable set active = 1, released = 1, paid = 1,
			pmode='$pmde',transaction='$stat'
			 where business_id=$business_id and
                        owner_id=$owner_id order by business_permit_id desc limit 1")
                        or die ("sss".mysql_error());

}

} else { //single permit all lines
	
//###########################################################

$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") or die(mysql_error()."d");
    $resulta=mysql_fetch_row($result);
$getlgu = @mysql_query("select city_municipality_desc from ebpls_city_municipality where city_municipality_code = '$resulta[0]'");
$getlgu = @mysql_fetch_row($getlgu);
$getprov = @mysql_query("select province_desc from ebpls_province where province_code = '$resulta[1]'");
$getprov = @mysql_fetch_row($getprov);
//$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->image('../images/ebpls_logo.jpg',10,5,33);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'Republic of the Philippines',0,1,'C');
$pdf->Cell(190,5,$getprov[0],0,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,5,$getlgu[0],0,1,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,'',0,0,'C');
$pdf->Cell(190,5,'Office of the Mayor',0,2,'C');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetX(170);
$pdf->Cell(30,30," ",1,1,'C');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,5,"MAYOR'S PERMIT",0,0,'C');

	$result=mysql_query("select a.business_permit_code, a.application_date, 
				b.business_name, b.business_street, a.transaction, 
				concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', c.owner_last_name), 
				c.owner_gender,	concat(c.owner_street, ' ', c.owner_city_code, ' ', 
				c.owner_province_code), a.business_permit_id, d.cap_inv, 
				d.last_yr, b.employee_male, b.employee_female, d.bus_nature, 
				b.business_payment_mode, business_occupancy_code, concat(b.business_lot_no, ' ', 
				b.business_street, ' ', b.business_city_code, ' ', b.business_province_code, ' ', 
				b.business_zip_code),c.owner_civil_status from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d
				where $ddt and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id") or die(mysql_error()."fff");
    $resulta=mysql_fetch_row($result);

    $result1=mysql_query("select a.business_permit_code, a.application_date, b.business_name, 
			  b.business_street, a.transaction, 
			  concat(c.owner_first_name, ' ', c.owner_middle_name, ' ', 
			  c.owner_last_name), c.owner_gender,
			  concat(c.owner_street, ' ', c.owner_city_code, ' ', 
			  c.owner_province_code), a.business_permit_id, d.cap_inv, 
			  d.last_yr, b.employee_male, b.employee_female, 
			  d.bus_nature, b.business_payment_mode, a.active
		      	  from ebpls_business_enterprise_permit a, ebpls_business_enterprise b, 
			  ebpls_owner c, tempbusnature d
		          where $ddt and 
			  a.business_id = b.business_id and a.owner_id = c.owner_id 
			  and b.business_id = d.business_id ") or die(mysql_error()."dddd");
    $resulta1=mysql_fetch_row($result1);
	$getadd=mysql_query("select concat(c.owner_street, ' ', e.city_municipality_desc, ' ', 
				f.province_desc) from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d, 
				ebpls_city_municipality e, ebpls_province f
				where $ddt and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and c.owner_city_code = e.city_municipality_code and 
				c.owner_province_code = f.province_code") or die(mysql_error()."fff");
    $getadd=mysql_fetch_row($getadd);
	$getbadd=mysql_query("select concat(b.business_lot_no, ' ', 
				b.business_street, ' ', e.city_municipality_desc, ' ', f.province_desc, ' ', 
				b.business_zip_code) from ebpls_business_enterprise_permit a, 
				ebpls_business_enterprise b, ebpls_owner c, tempbusnature d,
				ebpls_city_municipality e, ebpls_province f
				where $ddt and 
				a.business_id = b.business_id and a.owner_id = c.owner_id 
				and b.business_id = d.business_id and b.business_city_code = e.city_municipality_code and 
				b.business_province_code = f.province_code") or die(mysql_error()."fff");
    $getbadd=mysql_fetch_row($getbadd);
          
if ($isrel==1) {

$mainp = $permit_num;
$d = strrpos($mainp,'-');//get sequence
$f = strlen($mainp);//lenght of peermit
$bodyp = substr($mainp,0,$f-$d-1);//permit format
$sequence = substr($mainp,$f-$d-1);//add 1 to sequence
                                                                                                               
        //get first
        if ($fgh==''){
                $sequence = '1'.$sequence;
		$sequence = $sequence - $loppage;
                $fgh='1';
        } else {
		$sequence = '1'.$sequence;
	}
                                                                                                               
$sequence = '1'.$sequence + 1;
$sequence = substr($sequence, 2,13); // sequence number
$permitcode = $bodyp.$sequence;
$permit_num=$permitcode;
}
$backpos = strpos($permit_num,'-');
$backpos = strpos($permit_num,'-',$backpos);
$rr = $permit_num;
$per_num=substr($rr,$backpos+1);
$per_num = date('Y').'-'.substr($per_num,6);
//$permit_num=date('Y').'-'.$permit_num;
$pdf->Cell(190,5,'',0,2,'C');

$pdf->SetFont('Arial','B',12);
$pdf->SetX(5);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(45,5,'Permit No.: '.$per_num,0,0,'L');
$pdf->SetX(170);
$pdf->Cell(40,5,'Date: '.date('M d, Y'),0,0,'L');
$pdf->SetX(165);
$pdf->SetFont('Arial','',10);
//$pdf->Cell(40,5,$resulta[4],0,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
//$pdf->Cell(45,5,'Application Date:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(50);
//$pdf->Cell(75,5,$resulta[1],1,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(125);
//$pdf->Cell(40,5,'Payment Mode:',1,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->SetX(165);
//$pdf->Cell(40,5,$resulta[14],1,1,'L');

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(190,5,'',0,2,'C');
//$pdf->Cell(45,5,'Business Trade Name:',1,0,'L');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','B',20);
$pdf->Cell(190,5,strtoupper($resulta[2]),0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'BUSINESS NAME',0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(190,5,'situated at '.strtoupper($getbadd[0]).' this Municipality',0,1,'C');
//$pdf->SetFont('Arial','',10);
//$pdf->SetX(50);
//$pdf->Cell(155,5,$resulta[3],1,1,'L');

$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(190,5,"Pursuant to the provision of Revenue Code of the Municipality of ".$getlgu[0]." MAYOR'S PERMIT",0,1,'L');
$pdf->Cell(190,5,'is hereby granted',0,1,'L');
$pdf->SetFont('Arial','B',10);
//$pdf->Cell(40,5,'',0,1,'L');
$pdf->SetFont('Arial','B',15);
$pdf->Cell(190,5,'',0,2,'C');
$pdf->Cell(190,5,strtoupper($resulta[5]),0,1,'C');
$pdf->SetFont('Arial','',11);
$pdf->Cell(190,5,'(Name of Applicant)',0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');

//$pdf->Cell(190,5,'',0,2,'C');
//$pdf->SetFont('Arial','',12);
//$pdf->Cell(190,5,'situated at '.$resulta[16],0,1,'C');
//$pdf->SetFont('Arial','BU',12);
//$pdf->Cell(190,5,$resulta[7],0,1,'C'); 

if ($resulta[6]=='M') {
	$tag = 'Mr.';
} else {
	if ($resulta[15]=='Single') {
		$tag='Ms.';
	} else {
		$tag='Mrs.';
	}
}

$pdf->SetFont('Arial','',12);
$pdf->Cell(190,5,'registered proprietor owner/manager of which is',0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','BU',12);
$pdf->Cell(190,5,"$tag ".strtoupper($resulta[5]),0,1,'C');
$pdf->Cell(190,5,'',0,2,'C');
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(190,5,'with legal residence/office/s at '.strtoupper($getadd[0]).' to operate/construct/install: ',0,'C');
$linebus = mysql_query("select * from tempbusnature where owner_id=$owner_id and
			business_id=$business_id and active=1") or die ($sqltb.mysql_error());

	$number_of_rows = mysql_numrows($linebus);
	
	$i = 1;
	//$pdf->SetY($Y_Table_Position);
	while ($busline=mysql_fetch_row($linebus))
	{
		$pdf->SetFont('Arial','B',12);
		$pdf->SetX(5);
		$pdf->Cell(190,5,$busline[2],0,0,'C');

		$i++;
		$pdf->SetY($pdf->GetY()+5);
	}


$pdf->SetFont('Arial','',12); 
$pdf->Cell(190,5,'subject to existing laws, ordinances; rules and regulations.',0,1,'C');
$pdf->SetFont('Arial','I',9);
$pdf->Cell(190,5,'O.K as to business requirements',0,1,'C');

$pdf->Cell(200,5,'',0,2,'C');


$getorno = mysql_query("select a.payment_code, b.ts 
			from ebpls_transaction_payment_or a, ebpls_transaction_payment_or_details b
			where a.or_no=b.or_no and b.payment_id='$business_id' and b.trans_id='$owner_id' 
			order by a.or_no desc limit 1");
$getor = mysql_fetch_row($getorno);
$or_no = $getor[0];


//new signatories table
$getsignatories = mysql_query("select * from report_signatories where report_file='$report_desc' and sign_type=1") or die(mysql_error());
$getsignatories1 = mysql_fetch_array($getsignatories);
$sign_id = $getsignatories1[sign_id];
	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id = '$sign_id'") or die(mysql_error()."dasd");
    $resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;
          
$pdf->Cell(200,5,'',0,1,'C');

//$pdf->SetY(-18);
$pdf->SetX(5);

$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
//$pdf->Cell(100,5,'Recommend Approval:',0,0,'L');
$pdf->SetX(105);
//$pdf->Cell(100,5,'Approved:',0,1,'L');

$pdf->Cell(200,5,'',0,1,'C');
//$pdf->Cell(200,5,'',0,1,'C');

$pdf->SetX(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(100,5,'',0,0,'C');
$pdf->SetX(105);
$pdf->Cell(100,5,strtoupper($resulta[0]),0,1,'C');
$pdf->SetFont('Arial','B',10);

$pdf->SetX(5);
$pdf->Cell(100,5,'',0,0,'C');
$pdf->SetX(105);
$pdf->Cell(100,5,$resulta[1],0,0,'C');



$pdf->SetFont('Arial','',8);
$pdf->SetX(15);
$pdf->Cell(25,25,'DOC. STAMPS',1,0,'C');
$pdf->SetFont('Arial','',12);
$pdf->SetX(60);
$pdf->Cell(190,5,'O.R. No.: ',0,0,'L');
$pdf->SetFont('Arial','B',12);
$pdf->SetX(78);
$pdf->Cell(190,5,$or_no,0,1,'L');
$pdf->SetFont('Arial','',12);
$pdf->SetX(60);
$pdf->Cell(190,5,'SERIES : ',0,0,'L');
$pdf->SetFont('Arial','B',12);
$pdf->SetX(78);
$pdf->Cell(190,5,substr($getor[1],0, 4),0,1,'L');


$pdf->Cell(190,5,'',0,1,'L');
$pdf->Cell(190,5,'',0,1,'L');
$pdf->Cell(190,5,'',0,1,'L');
$pdf->SetX(15);
$pdf->SetFont('Arial','',8);
$pdf->Cell(190,5,'Not valid without seal',0,1,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(190,5,'(NOTE: This permit must be displayed in a conspicuous place within the establisment and must',0,1,'C');
$pdf->Cell(190,5,'likewise be renewed after every end of the quarter/semester/year. This permit is not valid if',0,1,'C');
$pdf->Cell(190,5,'Official Receipt number is not indicated hereon.)',0,1,'C');
$pdf->Cell(190,5,'VALID UP TO DECEMBER 31, '.date('Y'),0,1,'C');




$intloop++;

if ($isrel==0) {
$mainp = $permit_num;
$d = strrpos($mainp,'-');//get sequence
$f = strlen($mainp);//lenght of peermit
$bodyp = substr($mainp,0,$f-$d-1);//permit format
$sequence = substr($mainp,$f-$d-1);//add 1 to sequence
$sequence = '1'.$sequence + 1;
$sequence = substr($sequence, 1,11); // sequence number
$permitcode = $bodyp.$sequence;
$permit_num=$permitcode;
                                                                                                                                                            
//get pin
  $getpin = mysql_query("select pin, pmode,transaction from $permittable where
                         owner_id = $owner_id and business_id = $business_id 
                         and transaction<>'' and
			 pin<>'' order by business_permit_id desc limit 1");
  $pint = mysql_fetch_row($getpin);
  $pin = $pint[0];
  $pmde = $pint[1];
  $stat=$pint[2];

                                                                                                                                                            
                               
 $updateit = mysql_query("update $permittable set active = 0 
                        where owner_id = $owner_id and business_id=$business_id 
			")
                        or die (mysql_error());
                                                                                                                                                            
 $res = mysql_query ("insert into $permittable 
			($incode,business_id, owner_id, for_year
                        ,application_date,input_by, transaction, 
			paid, released, steps, pin, 
			active,pmode)
                      values 
			('$permit_num', $business_id, $owner_id, '$currdate[year]', 
			now(), '$usern', '$stat', 
			1, 1, 'Release','$pin', 
			1,'$pmde')")
	             or die ("permitdie".mysql_error());
}


	}


$pdf->Output();

if ($isrel<>1) {
if ($stat=='') {
	$stat='New';
}
$delextra = mysql_query("delete from $permittable where business_id=$business_id and
			owner_id=$owner_id order by business_permit_id desc limit 1") 
			or die (";;;".mysql_error());

$updateit = mysql_query("update $permittable set active = 0 
                        where owner_id = $owner_id and business_id=$business_id
                        ")
                        or die (mysql_error());

$updlast  = mysql_query("update $permittable set active = 1, released = 1, paid = 1,
			pmode='$pmde',transaction='$stat'
			 where business_id=$business_id and
                        owner_id=$owner_id order by business_permit_id desc limit 1")
                        or die ("sss".mysql_error());

}
?>

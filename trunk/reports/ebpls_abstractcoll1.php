<?php                                  
require_once("../lib/ebpls.utils.php");
define('FPDF_FONTPATH','font/');
require('../ebpls-php-lib/html2pdf_lib/fpdf.php');     
include("../lib/phpFunctions-inc.php");
include("../includes/variables.php");
include("../lib/multidbconnection.php");
                                                                                                 
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

class PDF extends FPDF
{
var $prov;
var $lgu;
var $office;
var $y0;

	function setLGUinfo($p='', $l='', $o='') {
		$this->prov = $p;
		$this->lgu = $l;
		$this->office = $o;
//		echo 'setLGUinfo'.$this->prov;
	}

function AcceptPageBreak()
{
    //Method accepting or not automatic page break
    if($this->y<2)
    {
        //Set ordinate to top
        $this->SetY($this->y0);
        //Keep on page
        return false;
    }
    else
    {
        return true;
    }
}
	
//Page header
	function Header()
	{
	    //Logo
	    //$this->Image('logo_pb.png',10,8,33);
	    //Arial bold 15
	
	$this->Image('../images/ebpls_logo.jpg',10,8,33);
	$this->SetFont('Arial','B',12);
	$this->Cell(340,5,'REPUBLIC OF THE PHILIPPINES',0,1,'C');
	$this->Cell(340,5,$this->prov,0,1,'C');
	$this->Cell(340,5,$this->lgu,0,2,'C');
	$this->SetFont('Arial','B',14);
	$this->Cell(340,5,$this->office,0,2,'C');
	$this->Cell(340,5,'',0,2,'C');	
	$this->SetFont('Arial','BU',16);
	$this->Cell(340,5,'ABSTRACT COLLECTION REPORT' ,0,1,'C');
	$this->Ln(22);
	}
//Page footer
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    //Arial italic 8
	    $this->SetFont('Arial','I',8);
	    //Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
} // end of PDF class

//$dbLink = get_db_connection();

	$result=mysql_query("select lguname, lguprovince, lguoffice from ebpls_buss_preference") 
	or die(mysql_error());
    $resulta=mysql_fetch_row($result);
    
//$pdf=new FPDF('L','mm','Legal');
$pdf=new PDF('L','mm','Legal');
$pdf->setLGUinfo($resulta[0],$resulta[1],$resulta[2]);
$pdf->AddPage();
$pdf->AliasNbPages();
$Y_Label_position = 42;
$Y_Table_Position = 46;
$pdf->SetFont('Arial','B',12);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(340,5,'From '.$date_from. ' to '.$date_to ,0,1,'C');

$Y_Label_position = 50;
$Y_Table_Position = 55;


$pdf->SetFont('Arial','B',6);
$pdf->SetY($Y_Label_position);
$pdf->SetX(5);
$pdf->Cell(50,5,'NAME OF PAYOR',1,1,'C');
$pdf->SetX(55);
	$v = 0;
	$xx = 55;

		
	//	$result = mysql_query("select tfodesc from ebpls_buss_tfo limit 8") 
	

  $result = mysql_query("select a.tfoid, a.tfodesc from ebpls_buss_tfo a, rpt_temp_abs b
			where a.tfoid=b.tfoid order by a.tfodesc asc ") 
	or die(mysql_error());
	while ($resulta=mysql_fetch_row($result))
	{
		$v++;
		$tfoid = $resulta[0];
		$pdf->SetY($Y_Label_position);
		$pdf->SetX($xx);
		$pdf->Cell(35,5,$resulta[1],1,0,'C'); //tax/fee name
		$xx=$xx+35;
	}
	
	$pdf->SetY(55);
	$xx = 55;
	
	
	$v=0;
//	 $result = mysql_query("select tfoid, tfodesc from ebpls_buss_tfo where tfodesc<>'' order by tfodesc asc ") 
	 $result = mysql_query("select a.tfoid, a.tfodesc from ebpls_buss_tfo a, rpt_temp_abs b
                        where a.tfoid=b.tfoid order by a.tfodesc asc ")

	or die(mysql_error());
	while ($resulta=mysql_fetch_row($result))
	{
		$v++;
		
		$tfoid = $resulta[0];
		if ($v<>1) {
		$posxx = $xx + (35*($v-1));
	  } else {
		$posxx=$xx;
	  }
		
	//get owner_id and business_id
	
	
	
	$getids = mysql_query("select * from tempassess order by assid asc");
	
		while ($getit = mysql_fetch_row($getids)){
			$own=$getit[1];
			$biz=$getit[2];
			$assid = $getit[0];
		
					$gettag=mysql_query("select sassess from ebpls_buss_preference") or die ("gettag");
					$gettag=mysql_fetch_row($gettag);
						if ($gettag[0]<>'') {
	
						$bus=mysql_query("select a.business_name from ebpls_business_enterprise a, 
						ebpls_transaction_payment_or_details b 
						where a.business_id = $biz and  
						a.owner_id = $own and b.trans_id = $own and b.ts between '$date_from' 
						and '$date_to' ");
						$bus = mysql_fetch_row($bus);
						//, c.tfodesc, c.defamt, 
						//b.amt, b.multi, b.formula, b.compval, f.indicator
						//from ebpls_business_enterprise a, tempassess b, ebpls_buss_tfo c, rpt_temp_abs d,
						//ebpls_transaction_payment_or_details e, ebpls_buss_taxfeeother f 
						//where a.business_id = b.business_id and b.tfoid=c.tfoid and b.active=1 and
						//b.owner_id=$own and b.business_id=$biz and b.assid=$assid and
						//c.tfoid=d.tfoid and e.trans_id=b.owner_id and e.payment_id=b.business_id and
						//f.taxfeeid = b.taxfeeid and e.ts between '$date_from' and '$date_to' and
						//b.tfoid=$tfoid  order by c.tfodesc asc ") 
						//or die("1".mysql_error());
						if ($bus <> "") {
							$pdf->Cell(50,5,$bus[0],0,1,'L');
						}
  							/*while ($resb=mysql_fetch_row($res))
							{
										
								if ($resb[5]<>'complex formula') {
									if (is_numeric($resb[5])) {
										if ($resb[7]==3) {
											$totamti = $resb[6];
										} else {
											$totamti = $resb[4]*$resb[5];
										}
									} else {
										$totamti = str_replace('X0', $resb[4], $resb[5]);
									//eval("\$totamti=$newvalue;");
									}
								} else {
									$totamti = $resb[6];
								}
								$totamti = number_format($totamti);
    								$pdf->SetX(5);
										$pdf->Cell(50,5,$resb[0],0,0,'L');
									//	$posxx=$posxx+35;
										$pdf->SetX($posxx);
										$pdf->Cell(35,5,$totamti,0,1,'L');
										$osxx=$osxx+35;
										//$pdf->SetX($posxx);
										//$pdf->Cell(35,5,$resb[1].$tfoid,1,1,'L');
									}
						} else {//per estab ass


						$res=mysql_query("select a.business_name, c.tfodesc, c.defamt   , b.amt
						, b.multi, b.formula, b.compval, f.indicator
                                                from ebpls_business_enterprise a, tempassess b, ebpls_buss_tfo c, rpt_temp_abs d,
                                                ebpls_transaction_payment_or_details e, ebpls_buss_taxfeeother f
                                                where a.business_id = b.business_id and b.tfoid=c.tfoid
                                                and b.owner_id=$own and b.business_id=$biz and b.assid=$assid and b.active=1 and
                                                c.tfoid=d.tfoid and c.taxfeetype=2 and e.trans_id=b.owner_id and e.payment_id=b.business_id and
                                                f.taxfeeid = b.taxfeeid and e.ts between '$date_from' and '$date_to' and
                                                b.tfoid=$tfoid  order by c.tfodesc asc limit 1")
                                                or die(mysql_error());

							 while ($resb=mysql_fetch_row($res))
                                                                        {
                                                                                                                 
								 if ($resb[5]<>'complex formula') {
                                                                        if (is_numeric($resb[5])) {
                                                                                if ($resb[7]==3) {
                                                                                        $totamti = $resb[6];
                                                                                } else {
                                                                                        $totamti = $resb[4]*$resb[5];
                                                                                }
                                                                        } else {
	                                                                        $totamti = str_replace('X0', $resb[4], $resb[5]);
                                                                        //eval("\$totamti=$new_value;");
                                                                        }
                                                                } else {
                                                                        $totamti = $resb[6];
                                                                }
                                                                $totamti = number_format($totamti);
                                           
                                                                $pdf->SetX(5);
                                                                                $pdf->Cell(50,5,$resb[0],1,0,'L');
                                                                        //      $posxx=$posxx+35;
                                                                                $pdf->SetX($posxx);
                                                                                $pdf->Cell(35,5,$totamti,1,1,'L');
                                                                                $osxx=$osxx+35;
                                                                                //$pdf->SetX($posxx);
                                                                                //$pdf->Cell(35,5,$resb[1].$tfoid,1,1,'L');




                                                                        }*/





						}


	
	}		
	$xx=55;			
}

//new signatories table
//	$result=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id =1") or die(mysql_error());
//	$resulta=mysql_fetch_row($result);

//$Y_Table_Position = $Y_Table_Position + 20;

$pdf->SetY($pdf->GetY()+5);
$pdf->Cell(270,5,'',0,1,'C');
                                                                                                 
//$pdf->SetY(-18);
$pdf->SetX(5);
                                                                                                 
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,'Prepared By :',0,0,'L');
$pdf->Cell(172,5,'Noted By :',0,1,'L');

$pdf->Cell(270,5,'',0,1,'C');
$pdf->Cell(270,5,'',0,1,'C');

$getuser = @mysql_query("select * from ebpls_user where username = '$usernm'") or die(mysql_error());
$getuser = @mysql_fetch_array($getuser);
$getsignatories = @mysql_query("select * from report_signatories where report_file='Top Business Establishment' and sign_type='3'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$getsignatories = @mysql_query("select * from global_sign where sign_id='$getsignatories1[sign_id]'");
$getsignatories1 = @mysql_fetch_array($getsignatories);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(172,5,$getuser[firstname].' '.$getuser[lastname],0,0,'L');
$pdf->Cell(172,5,$getsignatories1[gs_name],0,1,'L');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(172,5,'',0,0,'C');
$pdf->Cell(172,5,$getsignatories1[gs_pos],0,1,'L');


$report_desc='Abstract of Collection';
//include 'report_signatories_footer1.php';

//Robert

//$pdf->SetX(5);
//$pdf->SetFont('Arial','BU',10);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[0],1,1,'C');
//$pdf->SetFont('Arial','B',10);
//$pdf->SetX(5);
//$pdf->Cell(172,5,'',1,0,'C');
//$pdf->Cell(172,5,$resulta[2],1,0,'C');

$pdf->Output();

?>



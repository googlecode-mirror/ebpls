<?php 
$Y_site=($pdf->GetY()-15);
                                                                                                                                                            
$pdf->SetY($Y_site);
                                                                                                                                                            
$getsignatories = mysql_query("select * from report_signatories where report_file='$report_desc' and sign_type=2") or die(mysql_error());
while ($getsignatories1 = mysql_fetch_array($getsignatories)) {
$getsignid = $getsignatories1[sign_id];
$result0=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id='$getsignid'") or die("1".mysql_error());
$resulta0=mysql_fetch_row($result0);
$pdf->SetY($pdf->GetY()+20);
$pdf->SetX(5);
$pdf->SetFont('Arial','BU',10);
$pdf->Cell(100,5,$resulta0[0],0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(5);
$pdf->Cell(100,5,$resulta0[2],0,0,'C');
}
$pdf->SetY($Y_site);
$getsignatories = mysql_query("select * from report_signatories where report_file='$report_desc' and sign_type=1") or die(mysql_error());
while ($getsignatories1 = mysql_fetch_array($getsignatories)) {
$getsignid = $getsignatories1[sign_id];
$result1=mysql_query("select gs_name, gs_pos, gs_office from global_sign where sign_id='$getsignid'") or die("1".mysql_error());
$resulta=mysql_fetch_row($result1);
$pdf->SetY($pdf->GetY()+20);
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(105);
$pdf->Cell(100,5,$resulta[0],0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->SetX(105);
$pdf->Cell(100,5,$resulta[2],0,0,'C');
                                                                                                                                                            
}

?>

<table width=90% align=center>
<tr>
<td colspan=9 align=right><i><b>Legend Indicator:</b></i><font size=1 color="#ff0033"> 1=Constant | 2=Formula </font>&nbsp </td>
</tr>

<tr bgcolor="#EEEEEE">
<td width=5%>No.</td>
<td width=20% align=center>Date of Renewal</td>
<td width=5% align=center>Surcharge</td>
<td width=5% align=center>Interest</td>
<td width=5% align=center>Surcharge Mode</td>
<td width=5% align=center>Due Dates Mode</td>
<td width=5% align=center>Indicator</td>
<td width=5% align=center>Status</td>
<td width=20% align=center>Action</td>
</tr>

<?php
	$listmyid = "SELECT * FROM $tbl_current ORDER BY id DESC ";
	$Dbresult=th_query($listmyid);
	while($dtarow 	= @mysql_fetch_array($Dbresult)){
			$valueof_remarks=substr($dtarow[remarks],0,22);
			$myrow++;	
			include'tablecolor-inc.php';
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td>$myrow</td>";
			echo "<td align=center>$dtarow[renewaldate]</td>";
			echo "<td align=right>$dtarow[rateofpenalty]</td>";
			
			echo "<td align=right>$dtarow[rateofinterest]</td>";
			switch ($dtarow[optsurcharge]){
			case MON:$optSurcharge='Monthly';break;	
			case QTR:$optSurcharge='Quarterly';break;	
			case SEM:$optSurcharge='Semi-Annual';break;	
			case ANN:$optSurcharge='Annual';break;	
			}
			echo "<td>$optSurcharge</td>";
			switch ($dtarow[optduedates]){
			case MON:$optDueDates='Monthly';break;	
			case QTR:$optDueDates='Quarterly';break;	
			case SEM:$optDueDates='Semi-Annual';break;	
			case ANN:$optDueDates='Annual';break;	
			}
			echo "<td>$optDueDates</td>";
			
			echo "<td align=center>$dtarow[indicator]</td>";
			echo "<td align=center>$dtarow[status]</td>";
			//if ($dtarow[status]==A){
			echo "<td align=center><a href=index.php?part=4&class_type=Preference&selMode=ebpls_npenalty&action_=7&itemEvent=1&data_item=0&valueof_id=$dtarow[id]&com=edit>Edit</a></td>";
			//}
			//else {
			//echo "<td align=center>$valueof_remarks</td>";	
			//}
			echo "</tr>";
		}
?>

</table>
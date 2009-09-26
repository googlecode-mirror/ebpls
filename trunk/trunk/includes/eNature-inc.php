	<?php
//	$listmyid = "SELECT * FROM $tbl_current where naturestatus='A' ORDER BY naturedesc ";
	
//	$Dbresult=th_query($listmyid);
//	while($dtarow 	= @mysql_fetch_array($Dbresult)){
if ($reftype==bus) {	
			$myrow++;	
			include'tablecolor-inc.php';
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td>&nbsp;$myrow</td>";
			//echo "<td>$dtarow[natureid]</td>";
			echo "<td>$get_info[1]</td>";
			//if ($get_info[natureoption]=='L'){
			//echo "<td align=center><img src=../images/pin.gif width=15 height=15/></td>";
			echo "<td align=center>$get_info[4]</td>";
			//}
			//elseif ($get_info[natureoption]=='C'){
			//echo "<td align=center>-</td>";
			//}
			//else {
			//echo "<td align=center></td>";
			//}
	echo "<td align=center><a href=index.php?natureid=$get_info[0]&action_=1&actionID=1&part=$part&class_type=Preference&pref_type=Business&selMode=$selMode&natureaction=Edit&orderbyasde=1&reftype=bus>Edit</a> |";
?>
	<a href='#' onclick='DelNature(<?php echo $get_info[0]; ?>);'>Delete</a>
<?php
//	      <a href=$PHP_SELF?natureid=$get_info[0]&natureaction=Delete&part=$part&class_type=Preference&pref_type=Business&selMode=$selMode&orderbyasde=1&reftype=bus>DeActivate</a></td>";//$natureid
	echo "</tr>";
} elseif ($reftype==req) {

} elseif ($reftype==tfo) {		
//	$Dbresult=th_query($listmyid);
//	while($dtarow 	= @mysql_fetch_array($Dbresult)){
			$myrow++;	
			include'tablecolor-inc.php';
			$defFormat=number_format($get_info[6],2);
			echo "<tr bgcolor=$varcolor>"; 
			echo "<td valign=top>&nbsp;$myrow</td>";
			echo "<td valign=top>$get_info[1]</td>";
			echo "<td valign=top align=right>$defFormat</td>";
			if ($get_info[3]==0) {
				$rindicator='Normal';
			} else {
				$rindicator='Default';
			}
			echo "<td valign=top align=center>$rindicator</td>";			
			
			switch ($get_info[4]){
			 case 1:$itypedesc='TAX'; break;
			 case 2:$itypedesc='FEE'; break;			 
			 case 3:$itypedesc='OTHERS'; break;
			 case 4:$itypedesc='SPECIAL'; break;
			}
									
			echo "<td valign=top align=center>$itypedesc</td>";
			if ($get_info[2]=='A') {
				echo "<td valign=top align=center> <a href=$PHP_SELF?tfoid=$get_info[0]&action_=3&class_type=$class_type&pref_type=Business&actionID=1&part=$part&class_type=$class_type&selMode=$selMode&tfoaction=Edit&orderbyasde=1&reftype=tfo>Edit</a> |  <a href=$PHP_SELF?tfoid=$get_info[0]&class_type=$class_type&pref_type=Business&tfoaction=Delete&part=$part&selMode=$selMode&action_=5&orderbyasde=1&reftype=tfo>DeActivate</a></td>";
			} else {
				echo "<td valign=top align=center> <a href=$PHP_SELF?tfoid=$get_info[0]&action_=3&class_type=$class_type&pref_type=Business&actionID=1&part=$part&class_type=$class_type&selMode=$selMode&tfoaction=Edit&orderbyasde=1&reftype=tfo>Edit</a> |  <a href=$PHP_SELF?tfoid=$get_info[0]&class_type=$class_type&pref_type=Business&tfoaction=Activate&part=$part&selMode=$selMode&action_=5&orderbyasde=1&reftype=tfo>Activate</a></td>";
			}
			echo "</tr>";
//		}
}
//		}
		
		?>

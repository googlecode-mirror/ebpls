<?php
echo "<tr ><td class=header2 colspan=2 align=center>Fee Requirements</td></tr>";
if (empty($bussEvent)){
echo "<tr><td colspan=2>";	
include'ebpls5504.php';
echo "</td></tr>";	
?>

<tr>
<td colspan=2>&nbsp;&nbsp;<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=2&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Add More...</a></td>
</tr>

<?php
}
else {
echo "<tr><td colspan=2>";	
include'ebpls5503.php';	
echo "</td></tr>";	

}

?>

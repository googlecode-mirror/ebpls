<?php
echo "<tr><td class=header2 colspan=2 align=center>Required taxes, fees and other charges</td></tr>";

if (empty($bussEvent)){
echo "<tr><td colspan=2>";	
include'ebpls5504.php';
echo "</td></tr>";	
?>

<tr>
<td colspan=2><!--<a href=<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=<?php echo $bussEvent;?>&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>>Add More...</a>-->
<input type=button name=addnew value="Add New" onclick="parent.location='<?php echo $PHP_SELF;?>?part=<?php echo $part;?>&class_type=Preference&pref_type=Business&selMode=<?php echo $selMode;?>&action_=1&actionID=1&bussEvent=<?php echo $bussEvent;?>&natureid=<?php echo $natureid;?>&natureaction=<?php echo $natureaction;?>';">
 
<input type=button name=copyfrom value="Copy From" onclick="CopyFrom(<?php echo $natureid;?>)">

</td>
</tr>
<script language='javascript'>
function CopyFrom(natid)
{
	
        w = 700
        h = screen.height - 100
       
        x = screen.width/2 - w/2
        y = screen.height/2 - h/2
        strOption = 'toolbar=0,location=0,directories=0,menubar=0,resizable=0,scrollbars=1,status=1,width=' + w + ',height=' + h + ',screenX=' + x + ',screenY=' + y
        window.open ("copyfrom.php?&natureid="+ natid,'',strOption);
}
</script>       

<?php
}
else {
echo "<tr><td colspan=2>";	
include'ebpls5503.php';	
echo "</td></tr>";	
}
?>

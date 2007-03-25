<?php
/*
if ($owner_id=='') {
   $owner_id=$id;
}
*/
if ($owner_id<>'') {
 $getown = mysql_query("select owner_last_name, owner_first_name, owner_middle_name from $owner
                        where owner_id=$owner_id") or die("d".mysql_error());
                                                                                                               
                        $geto = mysql_fetch_row($getown);
                        $owner_last_name =$geto[0];
                        $owner_first_name =$geto[1];
                        $owner_middle_name =$geto[2];
}
?>
<tr>
<input type=hidden name=frmedit value='<?php echo $frmedit; ?>'>

	<td align="right" valign="top" class='normal' width=20%>Taxpayer's Name : </td>
	<td align="left" valign="top" class='normal' width=30%>
		<input type='hidden' name='owner_first_name' maxlength=60 class='text180'  value="<?php echo stripslashes($owner_first_name); ?>" readonly>

		<input type='hidden' name='owner_middle_name' maxlength=60 class='text180' value="<?php echo stripslashes($owner_middle_name); ?>" readonly>

		<input type='hidden' name='owner_last_name' maxlength=60 class='text180' value="<?php echo stripslashes($owner_last_name); ?>" readonly>


		<?php echo stripslashes($owner_first_name).' '.stripslashes($owner_middle_name).' '.stripslashes($owner_last_name);; ?>
	</td>
</tr> 
<?php
if ($permit_type<>'Business' and $owner_id=='') {
?>

</table>
<?php
}
?>
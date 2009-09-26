<?php
//require_once("ebpls-php-lib/ebpls.ctc.class.php");
//require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");
//require_once("setup/setting.php");
//$dbLink = get_db_connection();


//--- chk the sublevels
/*if(   ! is_valid_sublevels(29) )
 {
 	setUrlRedirect('index.php?part=999');
	
 } 
*/

//?>
<form method="POST" action="index.php?part=4&itemID_=1218&busItem=CTC&class_type=CTC&permit_type=CTC&item_id=CTC">

<br>
<div align="center">
<b>     Community Tax Certificate Report </b>
<br><br>
Type: &nbsp<select name="ctctype">
<option value="individual">Individual</option>
<option value="business">Business</option>
</select>
</div>
<br>
<br>
<br>
<table border=0 cellspacing=1 cellpadding=1 width='500' align="center">
<!---<form method="POST" action="ebpls1218.php">--->
        <td align="left" valign="top" class='normal' width = 200>
 	<b>List Of Registrants :</b> 
        </td>

	<td align = left valign=top class=normal width=100>
	Monthly
	</td>
        <td align="left" valign="top" class='normal' width=100>
        <select name="searchmonth1" class="small">
        <option value="01">January</option>
        <option value="02">February</option>
        <option value="03">March</option>
        <option value="04">April</option>
        <option value="05">May</option>
        <option value="06">June</option>
        <option value="07">July</option>
        <option value="08">August</option>
        <option value="09">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
        </select>
        </td>

        <td align="left" valign="top" class='normal' width = 50>
        <input type="submit" value="SUBMIT" name="button1">
        </td>

<!---<</form>-->
</table>
<br>
<table border=0 cellspacing=1 cellpadding=1 width='500' align="center">
<!---<<form method="POST" action="ebpls1218.php">-->
        <td align="left" valign="top" class='normal' width=200 >
        </td>

        <td align="left" valign="top" class='normal' width =100>
        Quarterly
        </td>

        <td align="left" valign="top" class='normal'  width = 100>
        <select name="searchmonth2" class="small">
        <option value="01">Jan-March</option>
        <option value="02">April-June</option>
        <option value="03">July-Sept</option>
        <option value="04">Oct-Dec</option>
        
        </select>
        </td>

        <td align="left" valign="top" class='normal' width = 50>
        <input type="submit" value="SUBMIT" name="button2">
        </td>

<!---<</form>-->
</table>




<br>
<table border=0 cellspacing=1 cellpadding=1 width='500' align="center">
<!---<<form method="POST" action="ebpls1218.php">-->

      	<td align="left" valign="top" class='normal' width=200>
	<b>Abstract of CTC Issued:</b>
	</td>

	<td align=left valign=top class=normal width=100>
	Monthly
	</td>


	<td align="left" valign="top" class='normal' width=100>
	<select name="searchmonth3" class="small">
	<option value="01">January</option>
	<option value="02">February</option>
	<option value="03">March</option>
	<option value="04">April</option>
	<option value="05">May</option>
	<option value="06">June</option>
	<option value="07">July</option>
	<option value="08">August</option>
        <option value="09">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
	</select>
	</td>

	<td align="left" valign="top" class='normal' width =50>
	<input type="submit" value="SUBMIT" name="button3">
	</td>

<!---<</form>-->
</table>
<br>
<table border=0 cellspacing=1 cellpadding=1 width='500' align="center">
<!---<<form method="POST" action="ebpls1218.php">-->
        <td align="left" valign="top" class='normal' width=200 >
        </td>

        <td align="left" valign="top" class='normal' width =100>
        Quarterly
        </td>

        <td align="left" valign="top" class='normal'  width = 100>
        <select name="searchmonth4" class="small">
        <option value="01">Jan-March</option>
        <option value="02">April-June</option>
        <option value="03">July-Sept</option>
        <option value="04">Oct-Dec</option>

        </select>
        </td>

        <td align="left" valign="top" class='normal' width = 50>
        <input type="submit" value="SUBMIT" name="button4">
        </td>

<!---<</form>-->
</table>


<br>
<br>
</form>


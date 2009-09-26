<!-- Accepts ColorScheme Inputs --->

<?php

//require_once "./includes/eBPLS_header.php";
require_once "includes/config.php";
require_once("lib/ebpls.lib.php");
require_once("lib/ebpls.utils.php");


$handle = fopen("./includes/menu_pref.js", "r");
while (!feof($handle)) {
	$buffer = fgets($handle, 1024);
	if (substr($buffer,0,24) == 'var scheme = new Array(') {
		$value = substr($buffer,24);
	}
	$buffer1 = substr($buffer,48);
	$value = substr($buffer1,0,strpos($buffer1,')')) . ",";
	//echo  $value . "<br>\n";   
}

fclose($handle);

$itemValue = substr($value,0,strpos($buffer1,','));
$itemList = substr($value,strpos($buffer1,',')+1);

$itemArray = array();
for ($i = 0; $i <= 10; $i++) {
	if (substr($itemValue,0,1) == '"') {
		$itemValue = substr($itemValue,1);
		$itemValue = substr($itemValue,0,strpos($itemValue,'"'));
	}
	$itemArray[$i] = $itemValue;  
	$itemValue = substr($itemList,0,strpos($itemList,','));
	$itemList = substr($itemList,strpos($itemList,',')+1); 
	 
}

//--- chk the sublevels
/*if(   ! is_valid_sublevels(170))
{
 	setUrlRedirect('index.php?part=999');
	
} 
*/

echo "<font face='arial'>\n";
echo "<table border=0 align='center'>\n";
echo "<tr><td colspan=2 class='header2' align=center><b>Color Scheme Preferences</b></td></tr>\n";
echo "<form action='".getURI(eBPLS_CHANGE_COLOR_PROCESS)."' method='post'>";
echo "<tr><td>Background Color: </td><td><input type='text' name='ebpls_bgColor' size='10' value=".$itemArray[0]."></td></tr>\n";
echo "<tr><td>Menu Font Size: </td><td><input type='text' name='ebpls_fontSize' size='10' value=".$itemArray[1]."></td></tr>\n";
echo "<tr><td>Menu Font Weight: </td><td><input type='text' name='ebpls_fontWeight' size='10' value=".$itemArray[2]."></td></tr>\n";
echo "<tr><td>Menu Font Face: </td><td><input type='text' name='ebpls_fontFamily' size='10' value=".$itemArray[3]."></td></tr>\n";
echo "<tr><td>Menu Font Color: </td><td><input type='text' name='ebpls_fontColor' size='10' value=".$itemArray[4]."></td></tr>\n";
echo "<tr><td>Menu Font Color HiLight: </td><td><input type='text' name='ebpls_fontColorHilite' size='10' value=".$itemArray[5]."></td></tr>\n";
echo "<tr><td>Menu Border Color: </td><td><input type='text' name='ebpls_bgColorBorder' size='10' value=".$itemArray[6]."></td></tr>\n";
echo "<tr><td>Menu Border Size: </td><td><input type='text' name='ebpls_menuBorder' size='10' value=".$itemArray[7]."></td></tr>\n";
echo "<tr><td>Menu Item Border Size: </td><td><input type='text' name='ebpls_menuItemBorder' size='10' value=".$itemArray[8]."></td></tr>\n";
echo "<tr><td>Menu Background Color: </td><td><input type='text' name='ebpls_menuItemBgColor' size='10' value=".$itemArray[9]."></td></tr>\n";
echo "<tr><td>Menu Background Color HiLight: </td><td><input type='text' name='ebpls_menuHiliteBgColor' size='10' value=".$itemArray[10]."></td></tr>\n";
echo "<tr align=center><td><input type='submit' name='butt' value='Load Default Values'></td><td>";
echo "<input type='submit' name='butt' value='Submit'> </td></tr>";
echo "</form>\n";
echo "</table>\n";
echo "<font>\n";

//require_once "./includes/eBPLS_footer.php";
?> 


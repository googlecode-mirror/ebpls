<table border=1 width=100% align=center cellspacing=0 cellpadding=0>

<tr><td bgcolor="#DDDDDD" align=center><font color="#333366" size=1>Food for thought</font></td></tr>
<tr bgcolor="#ffffff"><td  align=center><font color="#333366"><i>
<?php
/***************************
* phpMyQuote               *
* Written By: Pongles      *
* Licence: GPL             *
****************************/

$strFile = "includes/quotes.dat";

srand((double)microtime() * 100);
$rgFile = file($strFile);
$iOne = sizeof($rgFile);
$iTwo = rand(0,$iOne-1);
$strQuote = $rgFile[$iTwo];

echo $strQuote;
?></i>
</font>
</td>
</tr>

</table>

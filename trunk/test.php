<?php
$X0=101500;
$AmountFormula = "(X0*.25)>=30000?30000:X0*.25;";
$formula_check = str_replace("X0","90000",strtoupper($AmountFormula));
		
		eval("\$isvalid=$formula_check");

echo $isvalid."<br>";

//$d = md5($d);
//$f = $md5(strip_tags($d));
?>
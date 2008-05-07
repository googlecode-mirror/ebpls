<?php
/*	Purpose: Sets alternating rows background to white and gray
Modification History:
2008.05.06 RJC Handle undefined current row.
*/
$ctrrow = isset($ctrrow) ? $ctrrow : 0 ; 
if ($ctrrow==0){
$varcolor="#FFFFFF";
$ctrrow++;
}
elseif ($ctrrow==1){
$varcolor="#eeeeee";
$ctrrow=0;
}
?>

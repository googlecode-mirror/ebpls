<?php
//2008.05.06 RJC Define undefined variables
$data = $GLOBALS['HTTP_SERVER_VARS']['QUERY_STRING'];
$data .= "\n".@$_COOKIE["logger"];

$item_id = isset($item_id) ? $item_id : 0; //2008.05.06
$bbo = isset($bbo) ? $bbo : 0 ;
$com = isset($com) ? $com : 0 ;

setCurrentActivity($usern,$data);
?>
<body onfocus="GenerateLog('<?php echo $item_id; ?>','<?php echo $selMode; ?>','<?php echo $com; ?>','<?php echo $bbo; ?>');"></body>

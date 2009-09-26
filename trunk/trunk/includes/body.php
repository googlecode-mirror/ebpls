<?php 
//require_once'lib/ebpls.lib.php';
//require_once("lib/ebpls.utils.php");                                                                                                                        
//require_once("ebpls-php-lib/utils/ebpls.search.funcs.php");
require_once("includes/eBPLS_header.php");
require_once "includes/variables.php";                                                                                                                                                                                                                                     
//--- get connection from DB
//$dbLink = get_db_connection();

?>

<?php

echo $buss_select.$buss_report.$permit_num;

if ($buss_select<>'' and $bus_report=='View Report' and $permit_num<>'') {

?>

<?php
}
?>

<script language="JavaScript" src="javascripts/default.js"></script>
<script language="javascript">
function PM(x, pn)
        {
	      //  alert (x.value);
       //winpopup=window.open('ebpls_buss_permit.php','popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,toolbar=no,screenX=100,screenY=0,left=100,top=0');
                                                                                                                                                            
         winpopup = window.open(x.value + '?permit_num=' + pn.value ,'popup','height=500,width=800,menubar=no,scrollbars=yes,status=no,screenX=100,screenY=0,left=100,top=0');
                                                                                                                                                            
        };
                                                                                                                                                            
</script>




<div align="center">



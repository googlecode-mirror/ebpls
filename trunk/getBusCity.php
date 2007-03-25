<?php
include_once("includes/variables.php");
include_once("lib/multidbconnection.php");
include_once "lib/ebpls.utils.php";
                                                                                                                             
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);


if(isset($_GET['business_district_code'])){
                                                                                                                             
                                                                                                                             
        echo "obj.options[obj.options.length] = new Option('-Please Select Barangay-','');\n";
        $resultset = mysql_query("SELECT * FROM ebpls_barangay where upper='$_GET[business_district_code]'") or die(mysql_error()."dd");
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $K      = $datarow["barangay_code"];
                $V      = $datarow["barangay_desc"];
        echo "obj.options[obj.options.length] = new Option('$V','$K');\n";
        }
}

if(isset($_GET['business_barangay_code'])){
                                                                                                                             
                                                                                                                             
        echo "obj.options[obj.options.length] = new Option('-Please Select Barangay-','');\n";
        $resultset = mysql_query("SELECT * FROM ebpls_zone where upper='$_GET[business_barangay_code]'") or die(mysql_error()."dd");
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $K      = $datarow["zone_code"];
                $V      = $datarow["zone_desc"];
        echo "obj.options[obj.options.length] = new Option('$V','$K');\n";
        }
}





?>

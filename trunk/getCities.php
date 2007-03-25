<?php
include_once("includes/variables.php");
include_once("lib/multidbconnection.php");
include_once "lib/ebpls.utils.php";
                                                                                                                             
$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

if(isset($_GET['owner_province_code']) || isset($_GET['main_office_prov'])){
 
	if ($_GET['main_office_prov']=='') {
		$prov = $_GET['owner_province_code'];
	} else {
		$prov = $_GET['main_office_prov'];
	}
 
    
	echo "obj.options[obj.options.length] = new Option('-Please Select City-','');\n";
	$resultset = mysql_query("SELECT * FROM `ebpls_city_municipality`where upper='$prov'") or die(mysql_error()."dd");
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $K      = $datarow["city_municipality_code"];
                $V      = $datarow["city_municipality_desc"];
	echo "obj.options[obj.options.length] = new Option('$V','$K');\n";
        }

}

if(isset($_GET['owner_city_code']) || isset($_GET['business_main_offc_city_code'])){
                                                                                                                             
        if ($_GET['business_main_offc_city_code']=='') {
                $prov = $_GET['owner_city_code'];
        } else {
                $prov = $_GET['business_main_offc_city_code'];
        }

                                                                                                                             
                                                                                                                             
        echo "obj.options[obj.options.length] = new Option('-Please Select District-','');\n";
        $resultset = mysql_query("SELECT * FROM ebpls_district where upper='$prov'") or die(mysql_error()."dd");
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $K      = $datarow["district_code"];
                $V      = $datarow["district_desc"];
        echo "obj.options[obj.options.length] = new Option('$V','$K');\n";
        }
}

if(isset($_GET['owner_city_code1'])){


	$getzip = SelectDataWhere($dbtype,$dbLink,"ebpls_zip",
                        "where upper = '$owner_city_code'");
        $owner_zip = FetchArray($dbtype,$getzip);
	 echo "obj.options[obj.options.length] = new Option('$owner_zip[zip_desc]','$owner_zip[zip_desc]');\n";

                                                                                                                             
                                                                                                                             
}

if(isset($_GET['owner_district_code']) || isset($_GET['business_main_offc_district_code'])){
                                                                                                                             
        if ($_GET['business_main_offc_district_code']=='') {
                $prov = $_GET['owner_district_code'];
        } else {
                $prov = $_GET['business_main_offc_district_code'];
        }

                                                                                                                             
                                                                                                                             
        echo "obj.options[obj.options.length] = new Option('-Please Select Barangay-','');\n";
        $resultset = mysql_query("SELECT * FROM ebpls_barangay where upper='$prov'") or die(mysql_error()."dd");
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $K      = $datarow["barangay_code"];
                $V      = $datarow["barangay_desc"];
        echo "obj.options[obj.options.length] = new Option('$V','$K');\n";
        }
}
if(isset($_GET['owner_barangay_code']) || isset($_GET['business_main_offc_barangay_code'])){
                                                                                                                             
        if ($_GET['business_main_offc_barangay_code']=='') {
                $prov = $_GET['owner_barangay_code'];
        } else {
                $prov = $_GET['business_main_offc_barangay_code'];
        }

                                                                                                                             
                                                                                                                             
        echo "obj.options[obj.options.length] = new Option('-Please Select Zone-','');\n";
        $resultset = mysql_query("SELECT * FROM ebpls_zone where upper='$prov'") or die(mysql_error()."dd");
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $K      = $datarow["zone_code"];
                $V      = $datarow["zone_desc"];
        echo "obj.options[obj.options.length] = new Option('$V','$K');\n";
        }
}


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
                                                                                                                             
                                                                                                                             
        echo "obj.options[obj.options.length] = new Option('-Please Select Zone-','');\n";
        $resultset = mysql_query("SELECT * FROM ebpls_zone where upper='$_GET[business_barangay_code]'") or die(mysql_error()."dd");
        while($datarow  = @mysql_fetch_array($resultset))
        {
                $K      = $datarow["zone_code"];
                $V      = $datarow["zone_desc"];
        echo "obj.options[obj.options.length] = new Option('$V','$K');\n";
        }
}



?>

<?
include("../includes/variables.php");
include("../lib/multidbconnection.php");
//echo $dbtype."=".$dbhost."=".$dbuser."=".$dbpass."=".$dbname;
//$dbLink =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
$link = mysql_connect('localhost', 'ebpls', 'ebpls');
$idCon1 = mysql_select_db('ebplsajax',$link);
echo $link;
for ($i = 1; $i < 5001; $i++) {
$x = 7000+$i;
echo "insert into ebpls_motorized_vehicles values ('$x', '$x', 'Sample Model $i', 'Sample Motor No $i', 'Sample Chassis No $i', 'Plate No $i', 'Body No $i', 'ADMIN', '1', 'Route $i', 'Line $i', now(), now(), 'Franchise', 'Red', '123', '123', '', ''";
	$insertquery = mysql_query("insert into ebpls_motorized_vehicles ('$x', '$x', 'Sample Model $i', 'Sample Motor No $i', 'Sample Chassis No $i', 'Plate No $i', 'Body No $i', 'ADMIN', '1', 'Route $i', 'Line $i', now(), now(), 'Franchise', 'Red', '123', '123', '', '0'");
	$insertquery = mysql_query("insert into ebpls_franchise_permit values ('$x', '', '$x', '', '', '', '', '2006', now(), '', '', 'NEW', 'For Assessment', '', '0'");


	 $insertquery = mysql_query("insert into ebpls_owner values ('$x', 'Sample First Name $i', 'Sample Middle Name $i', 'Sample Last Name $i', '', 'Street $i', 'MA-ERMITA-ERMITA', '', 'MA-ERMITA', 'MA', 'METRO NAMILA', '2200', 'FILIPINO', 'Single', 'M', '', '', '', '', '', '', '', now(), now(), 'BOBBET', '', ''");
}
?>

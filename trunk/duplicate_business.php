<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Duplicate Business Establishments</title>
<style type="text/css">
<!--
.style2 {
	font-size: 12px;
	font-style: italic;
	color: #666666;
}
-->
</style>
</head>

<body>
List of Business Business Establishments that may have a possible duplicate business name, <br />
<span class="style2">Note: The records below shows business name that have the same first 10 characters starting fom the left.</span> <br />
<div align="center">
  <p>
    <?php 

// LEO RENTON
// set database server access variables: 
$host = "localhost"; 
$user = "root"; 
$pass = "elguebpls"; 
$db = "ebpls"; 

// open connection 
$connection = mysql_connect($host, $user, $pass) or die ("Unable to connect!"); 

// select database 
mysql_select_db($db) or die ("Unable to select database!"); 

// create query 
// LEO RENTON
$query = "SELECT * FROM ebpls_business_enterprise a WHERE (SELECT count(*) FROM ebpls_business_enterprise b WHERE lcase(left(a.business_name,10)) = lcase(left(b.business_name,10))) > 1 order by a.business_name"; 

// execute query 
$result = mysql_query($query) or die ("Error in query: $query. ".mysql_error()); 

// see if any rows were returned 
if (mysql_num_rows($result) > 0) { 
    // yes 
    // print them one after another 
    //echo "<table cellpadding=10 border=1 class=\"txtwhite\">"; 
	echo "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" class=\"txtwhite\">";
	echo " <tr bgcolor=\"#CCFF99\">
    <td> ID</td>
    <td> Business Name</td>
	<td> Branch</td>
    <td> Street</td>
	<td> Brgy Code</td>
	<td> Contact</td>		
	<td> SEC</td>
	<td> TIN</td>
  </tr>";

    while($row = mysql_fetch_row($result)) { 
        echo "<tr>"; 
        echo "<td>".$row[0]."</td>"; 
        echo "<td>" . $row[2]."</td>"; 
		echo "<td>".$row[3]."</td>";
        echo "<td>" . $row[6]."</td>"; 
		echo "<td>".$row[7]."</td>";
		echo "<td>".$row[14]."</td>";		
		echo "<td>".$row[22]."</td>";		
		echo "<td>".$row[23]."</td>";		

/*         
        echo "<td>".$row[3]."</td>"; 
        echo "<td>" . $row[4]."</td>"; 
        echo "<td>".$row[5]."</td>"; 
        echo "<td>".$row[6]."</td>"; 
        echo "<td>" . $row[7]."</td>"; 
        echo "<td>".$row[8]."</td>"; 
        echo "<td>".$row[9]."</td>"; 
        echo "<td>" . $row[10]."</td>"; 
        echo "<td>".$row[11]."</td>"; 
        echo "<td>".$row[12]."</td>"; 
        echo "<td>" . $row[13]."</td>"; 
        echo "<td>".$row[14]."</td>";		
        echo "<td>".$row[15]."</td>"; 
        echo "<td>".$row[16]."</td>"; 
        echo "<td>" . $row[17]."</td>"; 
        echo "<td>".$row[18]."</td>"; 
        echo "<td>".$row[19]."</td>"; 
        echo "<td>" . $row[20]."</td>"; 
        echo "<td>".$row[21]."</td>"; 
        echo "<td>".$row[22]."</td>"; 
        echo "<td>" . $row[23]."</td>"; 
        echo "<td>".$row[24]."</td>"; 
        echo "<td>".$row[25]."</td>"; 
  */
        echo "</tr>"; 
    } 
    echo "</table>"; 
} 
else { 
    // no 
    // print status message 
    echo "No rows found!"; 
} 

// free result set memory 
mysql_free_result($result); 

// close connection 
mysql_close($connection); 

?>
</div>
</body>
</html>

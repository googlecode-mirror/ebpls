<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Duplicate Owners</title>
</head>

<body>
List of Business Owners that have duplicate FIRSTNAME and LASTNAME
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
$query = "SELECT * FROM ebpls_owner a WHERE (SELECT count(*) FROM ebpls_owner b WHERE a.owner_first_name = b.owner_first_name and a.owner_last_name = b.owner_last_name) > 1 order by a.owner_first_name"; 

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
    <td> Firstname</td>
    <td> Middle</td>
    <td> Lastname</td>
    <td> Legal Entity</td>
    <td> House No.</td>
    <td> Street</td>
    <td> Brgy</td>
    <td> Zone</td>
    <td> Distrct</td>
    <td> City/Mun</td>
    <td> Province</td>
    <td> Zip</td>
    <td> Citezenship</td>
    <td> Civil Status</td>
    <td> Gender</td>
    <td> Tin</td>
    <td> ICR</td>
    <td> Phone</td>
    <td> GSM</td>
    <td> Email</td>
    <td> Others</td>
    <td> Birth</td>
    <td> RegDate</td>
    <td> Lastupdated</td>
    <td> Updated By</td>
	
  </tr>";

    while($row = mysql_fetch_row($result)) { 
        echo "<tr>"; 
        echo "<td>".$row[0]."</td>"; 
        echo "<td>" . $row[1]."</td>"; 
        echo "<td>".$row[2]."</td>"; 
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

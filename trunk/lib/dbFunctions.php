<?php

Function OpenDB($dbtype, $connectType = "c", $connect, $username = "", $password = "",$dbName)
    {
    Switch ($dbtype) {
    Case "mssql":
    If ($connectType == "c") {
    $idCon = mssql_connect($connect, $username, $password);
    } Else {
    $idCon = mssql_pconnect($connect, $username, $password);
    }
    mssql_select_db($dbName);
    Break;
    Case "mysql":
    If ($connectType == "p") {
    $idCon = mysql_pconnect($connect, $username, $password);
    } Else {
    $idCon = mysql_connect($connect, $username, $password);
    }
    $idCon1 = mysql_select_db($dbName,$idCon);
    Break;
    Case "pg":
    If ($connectType == "c") {
    $idCon = pg_connect($connect . " user=" . $username . " password=" . $password . " dbname=" . $dbName);
    } Else {
    $idCon = pg_pconnect($connect . " user=" . $username . " password=" . $password . " dbname=" . $dbName);
    }
    Break;
    Default:
    $idCon = 0;
    Break;
    }
    Return $idCon;
    }


Function QueryDB($query)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

    Switch ($dbtype) {
    Case "mssql":
    $r = mssql_query($query, $dbLinkFunc);
    Break;
    Case "mysql":
    $r = mysql_query($query, $dbLinkFunc);
    Break;
    Case "pg":
    $r = pg_exec($idCon, $dbLinkFunc);
    Break;
    Default:
    $r = False;
    Break;
	}
    return $r;

}

Function InsertQueryDB($tblName,$fields,$values)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
    Switch ($dbtype) {
    Case "mssql":
    $r = mssql_query("insert into $tblName $fields values ($values)", $dbLinkFunc);
    Break;
    Case "mysql":
//echo "insert into $tblName $fields values ($values)";
    $r = mysql_query("insert into $tblName $fields values ($values)", $dbLinkFunc)
	or die("Insert Error: ".mysql_error());
    $r = mysql_insert_id();
    Break;
    Case "pg":
    $r = pg_exec($dbLinkFunc,"insert into $tblName $fields values ($values)");
    Break;
    Default:
    $r = False;
    Break;
        }
    return $r;

}

Function UpdateQueryDB($tblName,$values,$where)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
    Switch ($dbtype) {
    Case "mssql":
    $r = mssql_query("update $tblName set $values where $where", $dbLinkFunc);
    Break;
    Case "mysql":
//echo "update $tblName set $values where $where";

    $r = mysql_query("update $tblName set $values where $where", $dbLinkFunc)
	or die("Update Error: ".mysql_error());
    Break;
    Case "pg":
    $r = pg_exec($dbLinkFunc, "update $tblName set $values where $where");
    Break;
    Default:
    $r = False;
    Break;
        }
    return $r;

}

Function DeleteQueryDB($tblName,$where)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
    Switch ($dbtype) {
    Case "mssql":
    $r = mssql_query("delete from $tblName where $where", $dbLinkFunc);
    Break;
    Case "mysql":
    $r = mysql_query("delete from $tblName where $where", $dbLinkFunc)
	or die("Delete Error: ".mysql_error());
    Break;
    Case "pg":
    $r = pg_exec($dbLinkFunc, "delete from $tblName where $where");
    Break;
    Default:
    $r = False;
    Break;
        }
    return $r;

}

Function FetchRowDB($query)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
 Switch ($dbtype)
    {
    Case "mssql":
    $r = mssql_fetch_row($query);
    Break;
    Case "mysql":
    $r = mysql_fetch_row($query);
    Break;
    Case "pg":
    $r = pg_fetch_row($query);
    Break;
    Default:
    $r = False;
    Break;
    }
 return $r;

}

Function FetchArrayDB($query)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);

 Switch ($dbtype) {
    Case "mssql":
    $r = mssql_fetch_assoc($query);
    Break;
    Case "mysql":
    $r = mysql_fetch_assoc($query);
    Break;
    Case "pg":
    $r = pg_fetch_assoc($query);
    Break;
    Default:
    $r = False;
    Break;
        }
        return $r;
}


Function FetchArrayPayerListDB($query,$permit_type)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
 Switch ($dbtype) {
    Case "mssql":
    $r = mssql_fetch_assoc($query);
    Break;
    Case "mysql":
//    $r = mysql_fetch_assoc($query);
  while ($r = mysql_fetch_assoc($query)){
include'tablecolor-inc.php';
                $fullname = $r[owner_last_name].', '.
                            $r[owner_first_name].' '.
                            $r[owner_middle_name];
                $fullname=stripslashes($fullname);
print "
                <tr bgcolor='$varcolor'>
                <td>&nbsp;$fullname&nbsp</td>
                <td>
                <a class=subnavwhite href='index.php?part=4&itemID_=1221&addbiz=Select&owner_id=$r[owner_id]&permit_type=$permit_type&busItem=$permit_type&mainfrm=Main&stat=New'>
                Attach</a></td>
                </tr>
	";
	}

    Break;
    Case "pg":
    $r = pg_fetch_assoc($query);
    Break;
    Default:
    $r = False;
    Break;
        }
        return $r;
}


Function FetchArrayBusinessListDB($query,$new_owner,$gpin)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
 Switch ($dbtype) {
    Case "mssql":
    $r = mssql_fetch_assoc($query);
    Break;
    Case "mysql":

while ($get_info = mysql_fetch_assoc($query)){
        include'tablecolor-inc.php';
        $address = $get_info[business_lot_no].' '.$get_info[business_street].
                   ' '.$get_info[business_barangay_name].' '.$get_info[business_city_code].
                   ' '.$get_info[business_province_code];

        $business_name=stripslashes($get_info[business_name]);
        $business_branch=stripslashes($get_info[business_branch]);
        $address=stripslashes($address);
	print "
        <tr bgcolor='$varcolor'>
        <td>&nbsp;$business_name&nbsp;</td>
        <td>&nbsp;$business_branch&nbsp;</td>
        <td>&nbsp;$address&nbsp;</td>
        <td><a class=subnavwhite href='index.php?part=4&itemID_=1221&addbiz=Select&owner_id=$new_owner&business_id=$get_info[business_id]&permit_type=Business&atachit=1&genpin=$gpin'>
        Attach</a></td>
        </tr>
	";
        } //end while



    Break;
    Case "pg":
    $r = pg_fetch_assoc($query);
    Break;
    Default:
    $r = False;
    Break;
        }
        return $r;
}




Function ResultDB($result, $RowNumber)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
    Switch ($dbtype) {
    Case "mssql":
    $r = mssql_result($result, $RowNumber);
    Break;
    Case "mysql":
    $r = mysql_result($result, $RowNumber);
    Break;
    Case "pg":
    $r = pg_result($result, $RowNumber);
    Break;
    Default:
    $r = False;
    Break;
    }
    Return $r;
    }


Function NumRowsDB($result)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
    Switch ($dbtype) {
    Case "mssql":
    $r = mssql_num_rows($result);
    Break;
    Case "mysql":
    $r = mysql_num_rows($result);
    Break;
    Case "pg":
    $r = pg_num_rows($result);
    Break;
    Default:
    $r = False;
    Break;
    }
    Return $r;
    }


Function SelectDataWhereDB($tblName,$where)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
 Switch ($dbtype)
    {
    Case "mssql":
    $r = mssql_query("select * from $tblName $where",$dbLinkFunc);
    Break;
   Case "mysql":
	//echo "select * from $tblName $where <br>";
    $r = mysql_query("select * from $tblName $where",$dbLinkFunc)
		or die ("Select Error: ".mysql_error());
    Break;
    Case "pg":
    $r = pg_exec("select * from $tblName $where",$dbLinkFunc);
    Break;
    Default:
    $r = False;
    Break;
    }
 Return $r;
}

Function SelectMultiTableDB($tblName,$fields,$where)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
 Switch ($dbtype)
    {
    Case "mssql":
    $r = mssql_query("select $fields from $tblName $where",$dbLinkFunc);
    Break;
    Case "mysql":
    $r = mysql_query("select $fields from $tblName $where",$dbLinkFunc)
		or die ("Select Error: ".mysql_error());
    Break;
    Case "pg":
    $r = pg_exec("select $fields from $tblName $where",$dbLinkFunc);
    Break;
    Default:
    $r = False;
    Break;
    }
 Return $r;
}

Function FetchArrayMotorListDB($query,$permit_type,$ownid,$stat)
{
include "includes/variables.php";
include_once("lib/multidbconnection.php");
$dbLinkFunc =Open($dbtype,$connecttype,$dbhost,$dbuser,$dbpass,$dbname);
 Switch ($dbtype) {
    Case "mssql":
    $r = mssql_fetch_assoc($query);
    Break;
    Case "mysql":
//    $r = mysql_fetch_assoc($query);
  while ($r = mysql_fetch_assoc($query)){
include'tablecolor-inc.php';
                $fullname = $r[owner_last_name].', '.
                            $r[owner_first_name].' '.
                            $r[owner_middle_name];
                $fullname=stripslashes($fullname);
print "
                <tr bgcolor='$varcolor'>
                <td>&nbsp;$r[motorized_motor_model]&nbsp</td>
                <td>&nbsp;$r[motorized_motor_no]&nbsp</td>
                <td>&nbsp;$r[motorized_chassis_no]&nbsp</td>
                <td>&nbsp;$r[motorized_plate_no]&nbsp</td>
                <td>&nbsp;$r[motorized_body_no]&nbsp</td>
                <td>&nbsp;$r[body_color]&nbsp</td>
                <td>&nbsp;$r[route]&nbsp</td>
                <td>&nbsp;$r[linetype]&nbsp</td>
                <td>&nbsp;$r[lto_number]&nbsp</td>
                <td>&nbsp;$r[cr_number]&nbsp</td>
                <td>
                <a class=subnavwhite href='index.php?vekatt=1&part=4&itemID_=1221&addbiz=Select&old_owner_id=$r[motorized_operator_id]&permit_type=$permit_type&busItem=$permit_type&mainfrm=Main&stat=$stat&owner_id=$ownid&mid=$r[motorized_motor_id]'>
                Attach</a></td>
                </tr>
	";
	}

    Break;
    Case "pg":
    $r = pg_fetch_assoc($query);
    Break;
    Default:
    $r = False;
    Break;
        }
        return $r;
}

?>

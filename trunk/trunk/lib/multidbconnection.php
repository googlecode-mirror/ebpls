<?php
Function Open($dbType, $connectType = "c", $connect, $username = "", $password = "",$dbName)
    {
    Switch ($dbType) {
    Case "mssql":
    If ($connectType == "c") {
    $idCon = mssql_connect($connect, $username, $password);
    } Else {
    $idCon = mssql_pconnect($connect, $username, $password);
    }
    mssql_select_db($dbName);
    Break;
    Case "mysql":
    If ($connectType == "c") {
    $idCon = @mysql_connect($connect, $username, $password);
    } Else {
    $idCon = @mysql_pconnect($connect, $username, $password);
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


Function Query1($dbType,$idCon,$query) 
{

    Switch ($dbType) {
    Case "mssql":
    $r = mssql_query($query, $idCon);
    Break;
    Case "mysql":
    $r = @mysql_query($query, $idCon)or die("Query Error: ".mysql_error());
    Break;
    Case "pg":
    $r = pg_exec($idCon, $query);
    Break;
    Default:
    $r = False;
    Break;
	}
    return $r;
		
}

Function InsertQuery($dbType,$idCon,$tblName,$fields,$values)
{
    
//echo "insert into $tblName $fields values ($values) <BR>";  
    Switch ($dbType) {
    Case "mssql":
    $r = mssql_query("insert into $tblName $fields values ($values)", $idCon);
    Break;
    Case "mysql":
//echo "insert into $tblName $fields values ($values) <BR>";
    $r = @mysql_query("insert into $tblName $fields values ($values)", $idCon);
//	or die("Insert Error: ".mysql_error());
    $r = mysql_insert_id();
    Break;
    Case "pg":
    $r = pg_exec($idCon,"insert into $tblName $fields values ($values)");
    Break;
    Default:
    $r = False;
    Break;
        }
    return $r;
                                                                                                 
}

Function UpdateQuery($dbType,$idCon,$tblName,$values,$where)
{
                                                                                                 
    Switch ($dbType) {
    Case "mssql":
    $r = mssql_query("update $tblName set $values where $where", $idCon);
    Break;
    Case "mysql":
//echo "update $tblName set $values where $where <BR>";
    $r = @mysql_query("update $tblName set $values where $where", $idCon)
	or die("Update Error: ".mysql_error());
    Break;
    Case "pg":
    $r = pg_exec($idCon, "update $tblName set $values where $where");
    Break;
    Default:
    $r = False;
    Break;
        }
    return $r;
                                                                                                 
}

Function DeleteQuery($dbType,$idCon,$tblName,$where)
{
                                                                                                 
    Switch ($dbType) {
    Case "mssql":
    $r = mssql_query("delete from $tblName where $where", $idCon);
    Break;
    Case "mysql":
//echo "delete from $tblName where $where";
    $r = @mysql_query("delete from $tblName where $where", $idCon)
	or die("Delete Error: ".mysql_error());
    Break;
    Case "pg":
    $r = pg_exec($idCon, "delete from $tblName where $where");
    Break;
    Default:
    $r = False;
    Break;
        }
    return $r;
                                                                                                 
}





Function FetchRow($dbType,$query)
{
 Switch ($dbType) 
    { 
    Case "mssql":
    $r = mssql_fetch_row($query);
    Break;
    Case "mysql":
    $r = @mysql_fetch_row($query);
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

Function FetchArray($dbType,$query)
{
 Switch ($dbType) {
    Case "mssql":
    $r = mssql_fetch_assoc($query);
    Break;
    Case "mysql":
    $r = @mysql_fetch_assoc($query);
    Break;
    Case "pg":
    $r = pg_fetch_array($query);
    Break;
    Default:
    $r = False;
    Break;
        }
        return $r;
}


 Function Result($dbType, $result, $RowNumber)
    {
    Switch ($dbType) {
    Case "mssql":
    $r = mssql_result($result, $RowNumber);
    Break;
    Case "mysql":
    $r = @mysql_result($result, $RowNumber);
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


Function NumRows($dbType, $result)
    {
    Switch ($dbType) {
    Case "mssql":
    $r = mssql_num_rows($result);
    Break;
    Case "mysql":
    $r = @mysql_num_rows($result);
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


Function SelectDataWhere($dbType,$idCon,$tblName,$where)
{
 Switch ($dbType) 
    {	
    Case "mssql":
    $r = mssql_query("select * from $tblName $where",$idCon);
    Break;
    Case "mysql":
//echo "select * from $tblName $where<br>";
    $r = @mysql_query("select * from $tblName $where",$idCon);
		
    Break;
    Case "pg":
    $r = pg_exec("select * from $tblName $where",$idCon);
    Break;
    Default:
    $r = False;
    Break;
    }
 Return $r;
}

Function SelectMultiTable($dbType,$idCon,$tblName,$fields,$where)
{
 Switch ($dbType)
    {
    Case "mssql":
    $r = mssql_query("select $fields from $tblName $where",$idCon);
    Break;
    Case "mysql":
//echo "select $fields from $tblName $where<br><br>";
    $r = @mysql_query("select $fields from $tblName $where",$idCon) 
		or die ("");
    $s = mysql_num_rows($r);
//echo "num rows = $s<br>";
    Break;
    Case "pg":
    $r = pg_exec("select $fields from $tblName $where",$idCon);
    Break;
    Default:
    $r = False;
    Break;
    }
 Return $r;
}



?>

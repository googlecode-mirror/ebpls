<?php
class City {
var 	$userID,
        $userName,
        $userPassword,
        $dbHost,
        $dbUser,
        $dbName,
        $dbPass,
        $dbUserTable,
	$dbType,
	$dbResultOut,
	$connectType;
                                                                                                 
	// This is the constructor function definition - it's possible to pass
    	// it values just like a normal function, but that isn't demonstrated here
    	// These variables will be set for each object that is created using this class
	function City() {
        	$this->dbHost = 'localhost';
        	$this->dbUser = 'ebpls';
        	$this->dbName = 'ebpls';
        	$this->dbPass = 'ebpls';
        	$this->dbUserTable = $GLOBALS['$tblName'];
		$this->dbType = $GLOBALS['dbUseType'];
		$this->dbconnectType = 'c';
    	}
	Function Query1($query) 
	{
	//	$idCon = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
	//	mysql_select_db($this->dbName);
    		$r = mysql_query($query);
		if(!$r) die("Query didn't work1. " . mysql_error());
		$this->dbResultOut = mysql_num_rows($r);
	//	mysql_close($idCon);
	}
	Function InsertQuery($tblName,$query)
	{
                $this->dbUserTable = $tblName;
	//	$idCon = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
        //      mysql_select_db($this->dbName);
    		$r = mysql_query($query);
		if(!$r) die("Query didn't work. " . mysql_error());
	//	mysql_close($idCon);
	}
	Function UpdateQuery($tblname, $query)
	{
		$this->dbUserTable = $tblName;
	//      $idCon = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
        //      mysql_select_db($this->dbName);
		$r = mysql_query($query) or die(mysql_error());
	}

	Function DeleteQuery($query)
	{
		$this->dbUserTable = $tblName;
    		$r = mysql_query($query);
	}	

	Function FetchRow($query)
	{
		$r = mysql_query($query);
                $this->dbResultOut = mysql_fetch_row($r);
	}

	Function FetchArray($query)
	{
    		$r = mysql_fetch_array($query);
	}


 	Function Result($result, $RowNumber)
    	{
    		$r = mysql_result($result, $RowNumber);
    	}


	Function NumRows($result)
    	{
        //        $idCon = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
        //        mysql_select_db($this->dbName);
    		$r = mysql_num_rows($result);
	//	mysql_close($idCon);
    	}


	Function SelectDataWhere($idCon,$tblName,$where)
	{

    		$r = mysql_query("select * from $this->dbUserTable $where",$idCon);
    	}

}
?>

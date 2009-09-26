<?php
class Ownership {
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
	function Ownership() {
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
		if(!$r) die("Query didn't work. " . mysql_error());
		$this->dbResultOut = mysql_num_rows($r);
	//	mysql_close($idCon);
	}
	Function InsertQuery($tblName,$query)
	{
                $this->dbUserTable = $tblName;
	//	$idCon = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
        //      mysql_select_db($this->dbName);
		$r = mysql_query("insert into $tblName values ($query)");
//    		$r = mysql_query($query);
		if(!$r) die("Query didn't work. " . mysql_error());
	//	mysql_close($idCon);
	}
	Function UpdateQuery($tblName,$ownership_code,$ownership_type,$usern, $exemption,$engcode)
	{
		$this->dbUserTable = $tblName;
	//      $idCon = mysql_connect($this->dbHost, $this->dbUser, $this->dbPass);
        //      mysql_select_db($this->dbName);
	
    			$r = mysql_query("update $this->dbUserTable set  business_category_code='$ownership_code', business_category_desc='$ownership_type', business_category_date_updated=now(), updated_by='$usern', tax_exemption='$exemption' where business_category_code='$engcode'") or die(mysql_error());
		
	}

	Function DeleteQuery($tblName,$where)
	{
		$this->dbUserTable = $tblName;
    		$r = mysql_query("delete from $this->dbUserTable where $where");
	}	

	Function FetchRow($query)
	{
    		$r = mysql_fetch_row($query);
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

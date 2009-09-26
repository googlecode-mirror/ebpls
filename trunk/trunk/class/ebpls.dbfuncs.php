<?php
	function ebpls_insert_data( $dbLink, $strTable, $strValues ) {
		
	$sqlInsert = "INSERT INTO $strTable values($strValues)";
	
	$res = @mysql_query($sqlInsert, $dbLink );

	$id = @mysql_insert_id ( $dbLink );
	return $id;

	}
	
	function ebpls_update_data ( $dbLink, $strTable, $strValues, $strWhere ) {

	$sqlUpdate = "UPDATE $strTable SET $strColumns WHERE $strWhereClause";

	$res = @mysql_query($sqlUpdate, $dbLink );

	}
?>
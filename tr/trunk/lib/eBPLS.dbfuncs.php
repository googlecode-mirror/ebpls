<?php
	function ebpls_insert_data( $dbLink, $strTable, $strValues ) {
		
	if ( $strValues!=NULL && count($strValues) > 0 ) {

		foreach ( $strValues as $key => $value  ) {
			$columns[] = $key;
			$values[] = "'$value'";
			
		}

		$strColumns = implode(",", $columns );
		$strValues = join( ",", $values );
	} else {

		set_db_error( NULL, "No Column values to insert provided");
		return ERROR_DB_FUNCS_INSERTFAILED;

	}
		
	$sqlInsert = "INSERT INTO $strTable values($strValues)";
	//echo $sqlInsert."VooDoo";
	
	$res = @mysql_query($sqlInsert, $dbLink );

	$id = @mysql_insert_id ( $dbLink );
	return $id;

	}
	
	function ebpls_update_data ( $dbLink, $strTable, $strValues, $strWhere ) {
		
	if ( is_array($strValues) ) {

		foreach ( $strValues as $key => $value  ) {

			if ( !is_null($value) ) {
				$set_cols[] = "$key = '$value'";
			}

		}

		$strColumns = implode(",", $set_cols );

	} else {

		set_db_error( NULL, "etoms_update_data invalid strValues value = $strValues");
		return -1;

	}


	if ( is_array($strWhere) ) {


		foreach ( $strWhere as $key => $value  ) {

			if ( is_array($value) ) {

				if ( strtoupper(trim($value[0])) == "IN" || strtoupper(trim($value[0])) == "NOT IN" ) {

					$set_where[] = " $key " . $value[0] . " " . $value[1] . " ";

				} else {
					$set_where[] = " $key " . $value[0] . " '" . $value[1] . "' ";
				}

			} else {

				$set_where[] = " $key = '$value' ";

			}
		}

		$strWhereClause = implode(" AND ", $set_where );

	} else {

		set_db_error( NULL, "etoms_update_data invalid strWhere value = $strWhere");
		return -1;

	}

	$sqlUpdate = "UPDATE $strTable SET $strColumns WHERE $strWhereClause";
	//echo $sqlUpdate;

	$res = @mysql_query($sqlUpdate, $dbLink );

	}

	function ebpls_delete_data ( $dbLink, $strTable, $strWhere ) {
	
	foreach ( $strWhere as $key => $value  ) {

		if ( is_array($value) ) {

			$set_where[] = " $key " . $value[0] . " '" . $value[1] . "'";

		} else {

			$set_where[] = " $key = '$value' ";

		}

	}

	$strWhereClause = implode(" AND ", $set_where );

	$sqlDelete = "DELETE FROM $strTable WHERE $strWhereClause";
	//echo $sqlDelete;
	
	$res = @mysql_query( $sqlDelete, $dbLink );

	}

	function ebpls_select_data ( $dbLink, $strTable, $strColumns, $strWhere = NULL, $strGroupBy = NULL, $strOrder = NULL, $strOrderKey = NULL,  $strLimitMax = NULL, $strLimitOffSet = NULL, $bGetPagingSql = false ) {
		
	if ( $strColumns != NULL && is_array($strColumns) && count($strColumns) > 0 ) {

		foreach ( $strColumns as $key => $value  ) {
			$set_cols[] = "$value";
		}

		$strSqlColumns = implode(",", $set_cols );

	} else {

		set_db_error( NULL, "No columns selected");
		return ERROR_DB_FUNCS_SELECTFAILED;

	}

	if ( $strWhere != NULL && count($strWhere) > 0 ) {

		foreach ( $strWhere as $key => $value  ) {

			if ( is_array($value) ) {

				if ( trim($value[0]) == "IN" || trim($value[0]) == "NOT IN" ) {
					$set_where[] = " $key " . $value[0] . " " . stripslashes($value[1]) . "";
				}else{
					$set_where[] = " $key " . $value[0] . " '" . $value[1] . "'";
				}

			} else {

				$set_where[] = " $key = '$value' ";

			}

		}

		$strSqlWhereClause = " WHERE " . implode(" AND ", $set_where );

	} else {


	}


	if ( !(null == $strGroupBy) &&  count($strGroupBy) > 0 ) {

		$set_group = implode(",", $strGroupBy );
		$strSqlGroup = " GROUP BY $set_group";

	}

	if ( !(null == $strOrder) && count($strOrder) > 0 ) {

		$set_order = implode(",", $strOrder );
		$strSqlOrder = " ORDER BY $set_order";

		if ( !(null == $strOrderKey) ) {

			if ( $strOrderKey != "ASC" && $strOrderKey != "DESC" ) {

				set_db_error ( NULL, "Invalid order key value $strOrderKey" );
				return -2;

			}

			$strSqlOrder .= " $strOrderKey ";

		}

	}

	if ( is_numeric($strLimitMax) && is_numeric($strLimitOffSet) ) {

		$strSqlLimit = " LIMIT $strLimitOffSet, $strLimitMax ";

	} else if ( is_numeric($strLimitMax) && !is_numeric($strLimitOffSet) ) {

		$strSqlLimit = " LIMIT $strLimitMax ";

	}
	//echo $strSqlColumns."VooDoo<br>";
	$strSqlWhereClause = isset($strSqlWhereClause) ? $strSqlWhereClause : ''; //2008.05.11
	$strSqlOrder = isset($strSqlOrder) ? $strSqlOrder : '';
	$strSqlLimit = isset($strSqlLimit) ? $strSqlLimit : '';
	$strSqlGroup = isset($strSqlGroup) ? $strSqlGroup : '';
	$sqlSelect = "SELECT $strSqlColumns FROM $strTable $strSqlWhereClause $strSqlGroup $strSqlOrder $strSqlLimit";
	//echo $sqlSelect;
	$res = @mysql_query($sqlSelect, $dbLink );
	//echo $res;
	return $res;
	
	}
	
	function ebpls_fetch_data($dbLink, $result) {
		
		$res = @mysql_fetch_assoc($result);
		
		return $res;
	
	}
	
	
	function is_not_empty( $value ) {

	if ( $value!=NULL && $value!="" ) {
		return 1;
	}

	return 0;

	}


	function ebpls_start_transaction( $dbLink ){

	global $gETOMSGlobalTransValue;

	if ( $gETOMSGlobalTransValue ) {

		ebpls_db_funcs_debug ( "etoms_start_transaction : gETOMSGlobalTransValue still set." );
		return;

	}

	$gETOMSGlobalTransValue = true;
	$res = @mysql_query( "START TRANSACTION", $dbLink );


	return 1;

	}
	
	function ebpls_select_data_bypage ( $dbLink, $strTable, $strColumns, $strWhere = NULL, $strGroupBy = NULL, $strOrder = NULL, $strOrderKey = NULL, $nPage = 1, $nMaxRecordPerPage = 20 ) {


	if ( $nPage <= 0 || $nMaxRecordPerPage <= 0 ) {

		set_db_error( NULL, "Invalid page values [pg=$nPage,maxrec=$nMaxRecordPerPage]" );
		return ERROR_DB_FUNCS_SELECTFAILED;

	}


	// build select sql
	$sqlArray = ebpls_select_data ( $dbLink, $strTable, $strColumns, $strWhere, $strGroupBy, $strOrder, $strOrderKey,  1, 1, true);

	$sqlCount = $sqlArray["count_sql"];
	$sqlSelect = $sqlArray["select_sql"];

	$res = @mysql_query($sqlCount, $dbLink );

	// count number of pages
	if ( $res && ( $row = mysql_fetch_array($res) ) ) {

		$nTotalRecords = $row[0];
		$nPageCount = floor($row[0]/$nMaxRecordPerPage);

		if ( ( $row[0]%$nMaxRecordPerPage ) > 0 )
		{

			$nPageCount++;

		}
	}

	// get offset using page number
	if ( $nPage == 1 ) {
		$pgOffset  = 0;
	} else {
		$pgOffset = ($nPage-1)*$nMaxRecordPerPage;
	}

	$res = ebpls_select_data ( $dbLink, $strTable, $strColumns, $strWhere, $strGroupBy, $strOrder, $strOrderKey,  $nMaxRecordPerPage, $pgOffset );

	if ( $res < 0 ) {

		set_db_error( $dbLink );
		return $res;

	} else {

		$page_record["total"] = $nTotalRecords;
		$page_record["count"] = count($res);
		$page_record["page"] = $nPage;
		$page_record["max_pages"] = $nPageCount;
		$page_record["page_count"] = $nPageCount;

		return array("result"=>$res, "page_info"=>$page_record);

	}

	}
?>

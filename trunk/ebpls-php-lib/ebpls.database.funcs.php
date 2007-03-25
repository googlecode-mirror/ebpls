<?
/************************************************************************************

Module : ebpls.database.funcs.php
Dependencies : None
Description :
	- generic MySQL builder functions
	- provides central functions for building sql statements
	- all necessary vlaue cleaning is done inside the sql builder functions

Created By : Stephen Lou B. Banal
Date Created : 3/1/2004 12:07AM

Last Updates :
	* 3/3/2004 6:42AM - stephen
		- added error constants
		-
	* 8/29/2004 4:11PM - stephen
		- added InnoDB transaction functions support
		- added USE INNODB command for InnoDB type of Mysql tables

Notes :
	- DO NOT USE THESE METHODS DIRECTLY, USE THE CLASS METHODS PROVIDED TO RETRIEVE DATA FROM DATABASE. - stephen
	- to disable debugging set constant DEBUG_DB_FUNCS to FALSE
	- need to add ebpls_bypage_select_data function (3/1/2004 12:09AM) - stephen

************************************************************************************/


$EBPLS_DB_FUNCS_ERROR = NULL;
define(DEBUG_DB_FUNCS, false );
define(ERROR_DB_FUNCS_INSERTFAILED,-100001);
define(ERROR_DB_FUNCS_UPDATEFAILED,-100002);
define(ERROR_DB_FUNCS_DELETEFAILED,-100003);
define(ERROR_DB_FUNCS_SELECTFAILED,-100004);
define(ERROR_DB_FUNCS_BEGINTRANSFAILED,-100005);
define(ERROR_DB_FUNCS_COMMITTRANSFAILED,-100006);
define(USE_INNODB, true );
$gEBPLSGlobalTransValue = false;

function ebpls_db_funcs_debug ( $str ) {

	if ( DEBUG_DB_FUNCS ) {

		echo "[" . date("Y-m-d H:i:s") . "] : " . htmlentities($str) . "<BR>";

	}

}

/**
 * Starts transaction.
 *
 **/
function ebpls_start_transaction( $dbLink ){

	global $gEBPLSGlobalTransValue;

	if ( $gEBPLSGlobalTransValue ) {

		ebpls_db_funcs_debug ( "ebpls_start_transaction : gEBPLSGlobalTransValue still set." );
		return;

	}

	$gEBPLSGlobalTransValue = true;
	$res = @mysql_query( "START TRANSACTION", $dbLink );

	if ( set_db_error ( $dbLink ) ) {

		return ERROR_DB_FUNCS_BEGINTRANSFAILED;

	}

	return 1;

}

/**
 *
 * Commits Current transaction
 *
 **/
function ebpls_commit_transaction( $dbLink ){

	global $gEBPLSGlobalTransValue;

	if ( !$gEBPLSGlobalTransValue ) {

		ebpls_db_funcs_debug ( "ebpls_end_transaction : gEBPLSGlobalTransValue still unset, call ebpls_start_transaction first." );
		return;

	}

	$res = @mysql_query( "COMMIT", $dbLink );

	if ( set_db_error ( $dbLink ) ) {

		return ERROR_DB_FUNCS_COMMITTRANSFAILED;

	}

	return 1;

}

/**
 * Rollback transaction
 *
 **/
function ebpls_rollback_transaction( $dbLink ){

	global $gEBPLSGlobalTransValue;

	if ( !$gEBPLSGlobalTransValue ) {

		ebpls_db_funcs_debug ( "ebpls_rollback_transaction : gEBPLSGlobalTransValue still unset, call ebpls_start_transaction first." );
		return;

	}

	$res = @mysql_query( "ROLLBACK", $dbLink );

	if ( set_db_error ( $dbLink ) ) {

		return ERROR_DB_FUNCS_COMMITTRANSFAILED;

	}

	return 1;

}

/**
 * Formats a string to be sql safe
 *
 **/
function ebpls_value_sql_clean ( &$value ) {

	// always check if automatic quotes setter is on
	if ( get_magic_quotes_gpc() == 1 )  return;

	if ( is_array($value) ) {


		foreach ( $value as $key =>$content ) {

			if ( is_array($content) ) {

				$value[$key][1] = addSlashes($content[1]);

			} else {

				$value[$key] = addSlashes($content);

			}

		}


	} else {

		if ( is_array($value) ) {

			$strTmp = $value[1];
			$value[1] = addslashes($strTmp);

		} else {

			$strTmp = $value;
			$value = addslashes($strTmp);

		}

	}

}

function set_db_error ( $dbLink, $str = NULL ) {

	global $EBPLS_DB_FUNCS_ERROR;

	if ( null != $dbLink ) {

		$error = @mysql_error ( $dbLink );

		if ( !($EBPLS_DB_FUNCS_ERROR = $error) ) {

			$EBPLS_DB_FUNCS_ERROR = NULL;

		}

	} else if (  null != $str ) {

		$EBPLS_DB_FUNCS_ERROR = $str;

	} else {

		$EBPLS_DB_FUNCS_ERROR = NULL;

	}

	return $EBPLS_DB_FUNCS_ERROR;

}

function get_db_error ( ) {

	global $EBPLS_DB_FUNCS_ERROR;

	return $EBPLS_DB_FUNCS_ERROR;

}


function ebpls_insert_data( $dbLink, $strTable, $strValues ) {

	global $gEBPLSGlobalTransValue;

	ebpls_value_sql_clean( $strValues );

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

	set_db_error( NULL, "No Column values to insert provided ($strValues)");

	$sqlInsert = "INSERT INTO $strTable ($strColumns) values($strValues)";

	ebpls_db_funcs_debug ( "ebpls_insert_data INSERT : $sqlInsert" );

	$res = @mysql_query($sqlInsert, $dbLink );

	if ( set_db_error ( $dbLink ) ) {

		return ERROR_DB_FUNCS_INSERTFAILED;

	} else {

		$id = @mysql_insert_id ( $dbLink );
		return $id;

	}

}

function ebpls_update_data ( $dbLink, $strTable, $strValues, $strWhere ) {


	if ( is_array($strValues) ) {

		ebpls_value_sql_clean( $strValues );

		foreach ( $strValues as $key => $value  ) {

			if ( !is_null($value) ) {
				$set_cols[] = "$key = '$value'";
			}

		}

		$strColumns = implode(",", $set_cols );

	} else {

		set_db_error( NULL, "ebpls_update_data invalid strValues value = $strValues");
		return -1;

	}


	if ( is_array($strWhere) ) {

		ebpls_value_sql_clean( $strWhere );

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

		set_db_error( NULL, "ebpls_update_data invalid strWhere value = $strWhere");
		return -1;

	}

	$sqlUpdate = "UPDATE $strTable SET $strColumns WHERE $strWhereClause";

	ebpls_db_funcs_debug ( "ebpls_update_data INSERT : $sqlUpdate" );

	$res = @mysql_query($sqlUpdate, $dbLink );

	if ( set_db_error ( $dbLink ) ) {

		return ERROR_DB_FUNCS_UPDATEFAILED;

	} else {

		return @mysql_affected_rows($dbLink);
		//return 1;

	}

}

function ebpls_delete_data ( $dbLink, $strTable, $strWhere ) {

	ebpls_value_sql_clean( $strWhere );

	foreach ( $strWhere as $key => $value  ) {

		if ( is_array($value) ) {

			$set_where[] = " $key " . $value[0] . " '" . $value[1] . "'";

		} else {

			$set_where[] = " $key = '$value' ";

		}

	}

	$strWhereClause = implode(" AND ", $set_where );

	$sqlDelete = "DELETE FROM $strTable WHERE $strWhereClause";

	ebpls_db_funcs_debug ( "ebpls_delete_data DELETE : $sqlDelete" );

	$res = @mysql_query( $sqlDelete, $dbLink );

	if ( set_db_error ( $dbLink ) ) {

		return ERROR_DB_FUNCS_DELETEFAILED;

	} else {

		return @mysql_affected_rows($dbLink);

	}

}


function ebpls_select_data ( $dbLink, $strTable, $strColumns, $strWhere = NULL, $strGroupBy = NULL, $strOrder = NULL, $strOrderKey = NULL,  $strLimitMax = NULL, $strLimitOffSet = NULL, $bGetPagingSql = false ) {

	ebpls_value_sql_clean( $strWhere );

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

	if ( $bGetPagingSql ) {

		$sqlCount = "SELECT count(*) FROM $strTable $strSqlWhereClause $strSqlGroup $strSqlOrder";
		$sqlSelect = "SELECT $strSqlColumns FROM $strTable $strSqlWhereClause $strSqlGroup $strSqlOrder";
		return array("count_sql" => $sqlCount, "select_sql"=>$sqlCount);

	}

	$sqlSelect = "SELECT $strSqlColumns FROM $strTable $strSqlWhereClause $strSqlGroup $strSqlOrder $strSqlLimit";

	ebpls_db_funcs_debug ( "ebpls_select_data SELECT : $sqlSelect" );

	$res = @mysql_query($sqlSelect, $dbLink );

	if ( set_db_error ( $dbLink ) ) {			
		
		return ERROR_DB_FUNCS_SELECTFAILED;

	} else {							

		while ( $row = mysql_fetch_array($res) ) {

			$select_records[] = $row;

		}

		return $select_records;

	}


}

/*
Note :
	- find function will return a two dimensional array
	- first element of array having key "page_info" contains all the information regarding the query
	- page_info elements
		total = number of total records of search
		max_pages = number of pages in search
		count = number of records on current page
		page = current page selected

	- second element of array having key "result" contains result of the search
*/
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

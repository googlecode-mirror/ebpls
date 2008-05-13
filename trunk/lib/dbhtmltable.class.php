<?php
/*
*   Author:
*   - Nil Angsioco
*   - http://www.biskwit.com/
*
*   Key Features:
*   - extends the HTML extended PHP functions
*   - parses SQL query statement as main parameter
*   - updates SQL query statement automatically
*   - allows most simple to complex SQL queries
*   - developed and tested under PHP 4.0.6
*   - supports MySQL DBMS
*   - supports transitional XHTML
*   - optional user-defined function(s) as parameter(s) for a more customizable
*     record display/formatting
*   - enable multiple instances and multiple database connections in one script
*   - all instances have independent column sorting and pagination in a script
*   - including a LIMIT clause in your SQL query statement will automatically
*     enable pagination (previous - next links)
*
*   Public Methods:
*   ->setDbHtmlTableProperties($strRowAltColour1, $strRowAltColour2, $strFontFamily, $strFontSize, $strTableBorderColour, $strTableWidth, $intTableCellspacing, $intTableCellpadding, $strEol);
*   ->setTextBeforeTable($strInput);
*   ->getDbHtmlTable($strSqlQuery, $arrColumnTitles, $arrFuncs, $arrSearchFields, $MySqlLinkId, $blnDebug);
*   ->getDbRecHtmlTable($strSqlQuery, $arrColumnTitles, $arrFuncs, $MySqlLinkId, $strColWidth1, $strColWidth2, $blnDebug);

Modification History:
2008.05.13 RJC Handle faulty argument passed to _getSqlClauseArgument line ~578 error
*/


require_once "lib/htmlext.class.php";
class DbHtmlTable extends HtmlExt
{
    var $_strTableBorderColour;
    var $_strTableWidth;
    var $_intTableCellspacing;
    var $_intTableCellpadding;
    var $_strRowAltColour1;
    var $_strRowAltColour2;
    var $_strFontFamily;
    var $_strFontSize;
    var $_strEol;
    var $_strCurrScript;
    var $_strCurrURI;
    var $_arrSqlQueryKeywords;
    var $_strTextBeforeTable;


    /**
     * Class Constructor and initializes result properties.
     *
     * @param  string  Row Alternate Colour 1 (e.g. '#FFFFFF')
     * @param  string  Row Alternate Colour 2 (e.g. '#D0E8FF')
     * @param  string  Font Face (e.g. 'ARIAL,HELVETICA,SANS-SERIF')
     * @param  string  Font Size (e.g. '2')
     * @param  string  HTML <table> border colour; will enable border="1" if
     *                 not null
     * @param  string  HTML <table> width (e.g. '90%', '100%', null)
     * @param  integer HTML <table> cellspacing (e.g. 1)
     * @param  integer HTML <table> cellpadding (e.g. 2)
     * @param  string  End of line characters (e.g. '\n', '\n\r')
     * @return boolean Always returns TRUE
     * @access public
     */
    function DbHtmlTable($strRowAltColour1 = "#FFFFFF",
                         $strRowAltColour2 = "#D0E8FF",
                         $strFontFamily = "ARIAL,HELVETICA,SANS-SERIF",
                         $strFontSize = "2",
                         $strTableBorderColour = null,
                         $strTableWidth = null,
                         $intTableCellspacing = 1,
                         $intTableCellpadding = 2,
                         $strEol = "\n")
    {
        $this->_strTableBorderColour = $strTableBorderColour;
        $this->_strTableWidth = $strTableWidth;
        $this->_intTableCellspacing = $intTableCellspacing;
        $this->_intTableCellpadding = $intTableCellpadding;
        $this->_strRowAltColour1 = $strRowAltColour1;
        $this->_strRowAltColour2 = $strRowAltColour2;
        $this->_strFontFamily = $strFontFamily;
        $this->_strFontSize = $strFontSize;
        $this->_strEol = $strEol;
        $this->_strCurrScript = $GLOBALS['HTTP_SERVER_VARS']['PHP_SELF'];
        $this->_strCurrURI = $GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'];
        $this->_strTextBeforeTable = null;
        $this->_arrSqlQueryKeywords = array(
            'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'INTO', 'VALUES', 'SET', 'FROM',
            'WHERE', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT', 'PROCEDURE', 'FOR UPDATE',
            'LOCK IN SHARE MODE'
        );
        return TRUE;
    }


    /**
     * Allows setting/changing current table properties
     *
     * @param  string  Row Alternate Colour 1 (e.g. '#FFFFFF')
     * @param  string  Row Alternate Colour 2 (e.g. '#D0E8FF')
     * @param  string  Font Face (e.g. 'ARIAL,HELVETICA,SANS-SERIF')
     * @param  string  Font Size (e.g. '2')
     * @param  string  HTML <table> border colour; will enable border="1" if
     *                 not null
     * @param  string  HTML <table> width (e.g. '90%', '100%', null)
     * @param  integer HTML <table> cellspacing (e.g. 1)
     * @param  integer HTML <table> cellpadding (e.g. 2)
     * @param  string  End of line characters (e.g. '\n', '\n\r')
     * @return boolean Always returns TRUE
     * @access public
     */
    function setDbHtmlTableProperties($strRowAltColour1 = "#FFFFFF",
                                      $strRowAltColour2 = "#D0E8FF",
                                      $strFontFamily = "ARIAL,HELVETICA,SANS-SERIF",
                                      $strFontSize = "2",
                                      $strTableBorderColour = null,
                                      $strTableWidth = null,
                                      $intTableCellspacing = 1,
                                      $intTableCellpadding = 2,
                                      $strEol = "\n")
    {
        $this->_strTableBorderColour = $strTableBorderColour;
        $this->_strTableWidth = $strTableWidth;
        $this->_intTableCellspacing = $intTableCellspacing;
        $this->_intTableCellpadding = $intTableCellpadding;
        $this->_strRowAltColour1 = $strRowAltColour1;
        $this->_strRowAltColour2 = $strRowAltColour2;
        $this->_strFontFamily = $strFontFamily;
        $this->_strFontSize = $strFontSize;
        $this->_strEol = $strEol;
        return TRUE;
    }


    /**
     * Set the text before the HTML <table> tag
     *
     * @param  string  Valid HTML code string to be inserted before the
     *                 HTML <table> tag
     * @return boolean Always return TRUE on execution
     * @access public
     */
    function setTextBeforeTable($strInput)
    {
		$this->_strTextBeforeTable = $strInput;
		return TRUE;
    }


    /**
     * Returns a string of HTML codes for rendering a table with a set of
     * values queried from an SQL database.
     * Requires an existing Database connection.
     *
     * @param  string   A valid MySQL query statement
     * @param  array    Array of string Column/Field titles
     * @param  array    Array of string function names of single-parametered
     *                  user-defined functions
     * @param  array    Array of string DB table column names subject as search
     *                  fields
     * @param  resource A valid MySQL connection resource identifier
     * @param  boolean  TRUE for debug mode and FALSE otherwise
     * @return string   HTML source of a table with data coming from an
     *                  SQL query result set
     * @access public
     */
    function getDbHtmlTable($strSqlQuery = null,
                            $arrColumnTitles = null,
                            $arrFuncs = null,
                            $arrSearchFields = null,
                            $MySqlLinkId = null,
                            $blnDebug = FALSE)
    {
        // local variable definitions
        static $intInstanceId = 1;
        $strSqlQuery = $this->_setSqlKeywordsToUpper($strSqlQuery);	// must call this before any query modification
        $strDisplay = $strDebug = "";
        $strEol = $this->_strEol;
        settype($arrFuncs, "array");
		$strTableWidth = ($this->_strTableWidth) ? "width=\"{$this->_strTableWidth}\"" : "";
        $strTableBorderColour = ($this->_strTableBorderColour) ? "border=\"1\" bordercolor=\"{$this->_strTableBorderColour}\"" : "";

        // add the search form if needed: must call this before pagination
        if (is_array($arrSearchFields)) {
            $strSearchCode = $this->_getSearchForm($strSqlQuery, $arrSearchFields, $intInstanceId);
        }

        // add column sorting: must call this before any query execution
        if ($GLOBALS["dhtorder_{$intInstanceId}"]) $strSqlQuery = $this->_setSqlClauseArgument($strSqlQuery, "ORDER BY", $GLOBALS["dhtorder_{$intInstanceId}"]);

        // add record offset/position and static record limit, set pagination string: must call this before the main sql query
        if (strpos($strSqlQuery, "LIMIT")) {
            $strPagination = $this->_getPagination($strSqlQuery, $intInstanceId, $MySqlLinkId);
        }

        // the "main" SQL query
        if ($blnDebug) $strDebug .= $this->setText("DbTable Query: $strSqlQuery") . "<br />$strEol";
        $result = (empty($MySqlLinkId)) ?
            mysql_query($strSqlQuery) :
            mysql_query($strSqlQuery, $MySqlLinkId);

        if ($result === FALSE) {

            $strDebug .= $this->setText("ERROR in QUERY: Illegal SQL Query<br />Please check your SQL query statement.") . "<br />$strEol";

        } else {

			// get field names in query
	        $arrSelectFieldNames = $this->_getFieldNames($result);
	        $intNumFields = count($arrSelectFieldNames);

            // table: search form
            if ($strSearchCode) {
                $strDisplay .= $this->setText($strSearchCode, TRUE);
            }
            $strDisplay .= $this->_strTextBeforeTable . $strEol;
            $strDisplay .= "<table $strTableWidth $strTableBorderColour cellspacing=\"{$this->_intTableCellspacing}\" cellpadding=\"{$this->_intTableCellpadding}\">$strEol";

            // table: column titles
            if ($intNumFields > 0) {
                $strDisplay .= "\t<tr>$strEol";
                $arrColumnTitles = (is_array($arrColumnTitles)) ? $arrColumnTitles : $arrSelectFieldNames;
                foreach($arrSelectFieldNames as $key => $val) {
                    $strDisplay .= "\t\t<td align=\"CENTER\" valign=\"MIDDLE\">$strEol";
                    $GLOBALS["dhtorder_{$intInstanceId}"] = isset($GLOBALS["dhtorder_{$intInstanceId}"]) ? $GLOBALS["dhtorder_{$intInstanceId}"] ; ''; //2008.05.13
                    $strSort = (strstr($GLOBALS["dhtorder_{$intInstanceId}"], $val) &&
                        strstr($GLOBALS["dhtorder_{$intInstanceId}"], ' ASC')) ? "DESC" : "ASC";
                    $strCurrColumnTitle = (empty($arrColumnTitles[$key])) ? $val : $arrColumnTitles[$key];
                    $strDisplay .= "\t\t\t<a href=\"" . $this->_strCurrScript . "?" . $this->updateQueryString("dhtorder_{$intInstanceId}", $val . " " . $strSort) . "\">" .
                        $this->setText($strCurrColumnTitle, TRUE, FALSE, TRUE) .
                        "</a>$strEol";
                    $strDisplay .= "\t\t</td>$strEol";
                }
                $strDisplay .= "\t</tr>$strEol";
            }

            // table: data
            $strCurrShade = $this->_strRowAltColour2;
            while($row = mysql_fetch_row($result)) {
                $arrLoopFuncs = $arrFuncs;
                $strCurrShade = ($strCurrShade == $this->_strRowAltColour1) ? $this->_strRowAltColour2 : $this->_strRowAltColour1;
                $strDisplay .= "\t<tr>$strEol";
                foreach($row as $val) {
                    $strCurrFunc = array_shift($arrLoopFuncs);
                    $strDisplay .= "\t\t<td bgcolor=\"$strCurrShade\" align=\"CENTER\" valign=\"MIDDLE\">$strEol";
                    if (function_exists($strCurrFunc)) {
                        // i always pass the vector $row as the second parameter to user-defined functions
                        $strDisplay .= "\t\t\t" . $this->setText($strCurrFunc($val, mysql_field_name($result, $key), $row)) . "$strEol";
                    } else {
                        $strDisplay .= "\t\t\t" . $this->setText($val) . "$strEol";
                    }
                    $strDisplay .= "\t\t</td>$strEol";
                }
                $strDisplay .= "\t</tr>$strEol";
            }

            // table: footer and pagination (if any)
            if (!empty($strPagination) && $intNumFields > 0) {
                $strDisplay .= "\t<tr>$strEol";
                $strDisplay .= "\t\t<td colspan=\"$intNumFields\" align=\"CENTER\" valign=\"MIDDLE\">$strEol";
                $strDisplay .= "\t\t\t" . $this->setText($strPagination, TRUE) . "$strEol";
                $strDisplay .= "\t\t</td>$strEol";
                $strDisplay .= "\t</tr>$strEol";
            }
            $strDisplay .= "</table>$strEol";

            mysql_free_result($result);

        }

        if ($blnDebug) $strDisplay = $strDebug . $strDisplay;
        $intInstanceId++;

        return $strDisplay;
    }


    /**
     * Returns a string of HTML code for rendering a table displaying a single
     * record from a query. (No built-in pagination)
     * Requires an existing Database connection.
     *
     * @param  string   A valid MySQL query statement
     * @param  array    Array of string Column/Field titles
     * @param  array    Array of string function names of single-parametered
     *                  user-defined functions
     * @param  resource A valid MySQL connection resource identifier
     * @param  boolean  TRUE for debug mode and FALSE otherwise
     * @return string   HTML source of a table with data coming from an
     *                  SQL query result set
     * @access public
     */
    function getDbRecHtmlTable($strSqlQuery = null,
                               $arrColumnTitles = null,
                               $arrFuncs = null,
                               $MySqlLinkId = null,
                               $strColWidth1 = null,
                               $strColWidth2 = null,
                               $blnDebug = FALSE)
    {
        // local variable definitions
        $strSqlQuery = $this->_setSqlKeywordsToUpper($strSqlQuery);
        $strDisplay = $strDebug = "";
        $strEol = $this->_strEol;
        settype($arrColumnTitles, "array");
        settype($arrFuncs, "array");
        $strTableWidth = ($this->_strTableWidth) ? "width=\"{$this->_strTableWidth}\"" : "";
        $strTableBorderColour = ($this->_strTableBorderColour) ? "border=\"1\" bordercolor=\"{$this->_strTableBorderColour}\"" : "";

        // the "main" SQL query
        $strSqlQuery = $this->_setSqlClauseArgument($strSqlQuery, "LIMIT", "1");
		if ($blnDebug) $strDebug .= $this->setText("DbTable Query: $strSqlQuery") . "<br />$strEol";
        $result = (empty($MySqlLinkId)) ?
            mysql_query($strSqlQuery) :
            mysql_query($strSqlQuery, $MySqlLinkId);

        if ($result === FALSE) {

            $strDebug .= $this->setText("ERROR in QUERY: Illegal SQL Query<br />Please check your SQL query statement.") . "<br />$strEol";

        } else {

            $row = mysql_fetch_row($result);

            $strDisplay .= $this->_strTextBeforeTable . $strEol;
            $strDisplay .= "<table $strTableWidth $strTableBorderColour cellspacing=\"{$this->_intTableCellspacing}\" cellpadding=\"{$this->_intTableCellpadding}\">$strEol";

            $strCurrShade = $this->_strRowAltColour2;
            $strColWidth1 = ($strColWidth1) ? "width=\"$strColWidth1\"" : "";
            $strColWidth2 = ($strColWidth2) ? "width=\"$strColWidth2\"" : "";
            foreach ($row as $key => $val) {
                $strCurrShade = ($strCurrShade == $this->_strRowAltColour1) ? $this->_strRowAltColour2 : $this->_strRowAltColour1;
                $strDisplay .= "\t<tr>$strEol";
                // Field/Column Title
                $strDisplay .= "\t\t<td bgcolor=\"$strCurrShade\" $strColWidth1 align=\"LEFT\" valign=\"MIDDLE\">$strEol";
                $strCurrUserTitle = array_shift($arrColumnTitles);
                $strCurrColumnTitle = ($strCurrUserTitle) ? $strCurrUserTitle : mysql_field_name($result, $key);
                $strDisplay .= "\t\t\t" . $this->setText($strCurrColumnTitle, TRUE) . "$strEol";
                $strDisplay .= "\t\t</td>$strEol";
                // Field/Column Record Value
                $strDisplay .= "\t\t<td bgcolor=\"$strCurrShade\" $strColWidth2 align=\"LEFT\" valign=\"MIDDLE\">$strEol";
                $strCurrFunc = array_shift($arrFuncs);
                if (function_exists($strCurrFunc)) {
                    // i always pass the vector $row as the second parameter to functions
                    $strDisplay .= "\t\t\t" . $this->setText($strCurrFunc($val, mysql_field_name($result, $key), $row)) . "$strEol";
                } else {
                    $strDisplay .= "\t\t\t" . $this->setText($val) . "$strEol";
                }
                $strDisplay .= "\t\t</td>$strEol";
                $strDisplay .= "\t</tr>$strEol";
            }

            $strDisplay .= "</table>$strEol";

            mysql_free_result($result);

        }

        if ($blnDebug) $strDisplay = $strDebug . $strDisplay;

        return $strDisplay;
    }


    /**
     * Gets all the field/column names used in the SQL SELECT statement
     *
     * @param  string  A valid MySQL result resource received as reference
     * @return array   Returns a vector array of all the field/column names
     *                 returned in a MySQL query.
     * @access private
     */
    function _getFieldNames(&$result)
    {
        for ($i = 0; $i < mysql_num_fields($result); $i++) {
            $arrFields[$i] = mysql_field_name($result, $i);
        }
        return $arrFields;
    }


    /**
     * Returns the HTML source for enabling records pagination and at the same
     * time altering the original SQL query outside this function.
     *
     * @param  string   A PHP variable having a valid MySQL query
     * @param  resource A valid MySQL connection resource identifier
     * @return string   HTML links for pagination PREV | NEXT ; returns FALSE
     *                  if the SQL query is illegal
     * @access private
     */
    function _getPagination(&$strSqlQuery, &$intInstanceId, $MySqlLinkId = null)
    {
		$strLimitStmt = $this->_getSqlClauseArgument($strSqlQuery, "LIMIT");
        if (strpos($strLimitStmt, ',')) {
            list($intOldOffset, $intPageLimit) = explode(',', $strLimitStmt, 2);
        } else {
            $intOldOffset = 0;
            $intPageLimit = $strLimitStmt;
            $strLimitStmt = $intOldOffset . ',' . $intPageLimit;
        }
        $intCurOffset = (empty($GLOBALS["dhtoffset_{$intInstanceId}"])) ? intval($intOldOffset) : intval($GLOBALS["dhtoffset_{$intInstanceId}"]);
        $strSqlQuery = $this->_setSqlClauseArgument($strSqlQuery, "LIMIT", $intCurOffset . "," . $intPageLimit);

        $intPageLimit = intval($intPageLimit);
        $strQuery = $this->_setSqlClauseArgument($strSqlQuery, "SELECT", "COUNT(*)");
        $strQuery = $this->_unsetSqlClause($strQuery, "ORDER BY");
        $strQuery = $this->_unsetSqlClause($strQuery, "LIMIT");
        $result = (empty($MySqlLinkId)) ?
            mysql_unbuffered_query($strQuery) :
            mysql_unbuffered_query($strQuery, $MySqlLinkId);

        if ($result === FALSE) {

            return FALSE;

        } else {

            list($intTotalRecords) = mysql_fetch_row($result);

            if ($intTotalRecords > 0) {
                $intTotalPages = ceil($intTotalRecords/$intPageLimit);
                $intCurrPage = ($intCurOffset / $intPageLimit) + 1;

                // Link to Previous Set of Records
                if ($intCurOffset >= $intPageLimit) {
                    $intNewOffset = $intCurOffset - $intPageLimit;
                    $strLinkPrev = "<a href=\"" . $this->_strCurrScript . "?" . $this->updateQueryString("dhtoffset_{$intInstanceId}", $intNewOffset) . "\">< Prev</a> &nbsp; ";
                }

                // Enumerated Jump-to/Goto Set of Records
                if ($intTotalPages > $intCurrPage + 5) {
                    $intPagesEnd = ($intCurrPage >= 5) ? $intCurrPage + 5 : 10;
                } else {
                    $intPagesEnd = $intTotalPages;
                }
                $intPagesStart = (($intPagesEnd - 9) < 1) ? 1 : $intPagesEnd - 9;
                for ($i = $intPagesStart; $i <= $intPagesEnd; $i++) {
                    $intNewOffset = ($i - 1) * $intPageLimit;
                    $arrGotoPages[] = ($i == $intCurrPage) ?
                        "$i" :
                        "<a href=\"" . $this->_strCurrScript . "?" . $this->updateQueryString("dhtoffset_{$intInstanceId}", $intNewOffset) . "\">$i</a>";
                }

                // Link to Next Set of Records
                if ($intCurOffset != $intPageLimit * ($intTotalPages - 1)) {
                    $intNewOffset = $intCurOffset + $intPageLimit;
                    $strLinkNext = " &nbsp; <a href=\"" . $this->_strCurrScript . "?" . $this->updateQueryString("dhtoffset_{$intInstanceId}", $intNewOffset) . "\">Next ></a>";
                }

                return $intTotalRecords . ' record(s) in ' . $intTotalPages . ' page(s)' . '<br />' .
                    $strLinkPrev . '( page: ' . implode(', ', (array)$arrGotoPages) . ' )' . $strLinkNext;
            } else {
                return 'No matching record.';
            }

        }
    }


    /**
     * Returns the HTML search <form> source, re-forms the SQL query for the
     * searh arguments, and over-writes the old querystring with the "search"
     * modified query string.
     *
     * @param  array   An array of searchable fields included in the SQL query
     * @return string  Returns the HTML search <form>
     * @access private
     */
    function _getSearchForm(&$strSqlQuery, &$arrSearchFields, &$intInstanceId)
    {

        global $HTTP_SERVER_VARS;
	$rty = $strSqlQuery;
	$rty = strpos($rty,'ebpls_user');
        $strEol = $this->_strEol;
        $strDisplay = "";
        // set the new action argument for <form>
        $strNewQueryString = $this->updateQueryString(
            array("dhtoffset_{$intInstanceId}", "dhtorder_{$intInstanceId}", "dhtfield_{$intInstanceId}", "dhtkey_{$intInstanceId}"),
            null
            );
        // set the <form> source
        $strDisplay .= "<form method=\"POST\" name=\"_FRM\" action=\"" . $this->_strCurrScript . "?" . $strNewQueryString . "\">$strEol";
        $strDisplay .= "Search <input type=\"TEXT\" name=\"dhtkey_{$intInstanceId}\" value=\"\" size=\"10\" /> in $strEol";
        $strDisplay .= "<select name=\"dhtfield_{$intInstanceId}\">$strEol";
        foreach ($arrSearchFields as $key => $val) {
            $strLabel = (is_numeric($key)) ? $val : $key;
            $strDisplay .= "<option value=\"$val\">$strLabel</option>$strEol";
        }
        $strDisplay .= "</select>$strEol";
        $strDisplay .= "<input type=\"SUBMIT\" name=\"dhtSubmit_{$intInstanceId}\" value=\" Go \" /><br />$strEol<br>";
	if ($rty>0) {
	$strDisplay .=  "<span><input type=\"HIDDEN\" name=\"frmThreadId\" value=\"\"><input type=\"BUTTON\" name=\"frmBtnAdd\" value=\"Add User\" onClick=\"javascript:
popwin('" . getFilename(eBPLS_PAGE_USER_ADD) . "?frmDomain=$strCurDomain', 'adduser');\"> &nbsp;
&nbsp;


<input type=\"BUTTON\" name=\"frmBtnEdit\" value=\"Edit User\" onClick=\"javascript: EditUserCheck();\"> &nbsp; &nbsp;
<input type=\"BUTTON\" name=\"BtnKick\" value=\"Kick User\" onClick=\"javascript: KickUser();\"> &nbsp; &nbsp;

<input type=\"SUBMIT\" name=\"BtnUnlock\" value=\"Unlock User\" onClick=\"javascript: UnlockUser();\">
<input type=\"BUTTON\" name=\"frmBtnDelete\" value=\"Delete User\" onClick=\"javascript: DeleteUserCheck();\">$strEol &nbsp; &nbsp;</span>";
	}                                          	
                                                       

      //  $strDisplay .= "</form>$strEol";
        // re-form the SQL query for the search parameter
        if (!empty($GLOBALS["dhtkey_{$intInstanceId}"])) {
            $strCurWhere = $this->_getSqlClauseArgument($strSqlQuery, "WHERE");
            $strCurKey = str_replace(array('*', '?'), array('%', '_'), $GLOBALS["dhtkey_{$intInstanceId}"]);
			if ($GLOBALS["dhtfield_{$intInstanceId}"] == 'userlevel') {
				if ($strCurKey == 'CTC Officer') {
					$strCurKey = '0';
				} elseif ($strCurKey == 'Application Officer') {
					$strCurKey = '1';
				} elseif ($strCurKey == 'Assessment Officer') {
					$strCurKey = '2';
				} elseif ($strCurKey == 'Payment Officer') {
					$strCurKey = '3';
				} elseif ($strCurKey == 'Approving Officer') {
					$strCurKey = '4';
				} elseif ($strCurKey == 'Releasing Officer') {
					$strCurKey = '5';
				} elseif ($strCurKey == 'eBPLS Administrator') {
					$strCurKey = '6';
				} elseif ($strCurKey == 'Root Administrator') {
					$strCurKey = '7';
				}
			}
            $strNewWhere = ($strCurWhere) ?
                $strCurWhere . " AND " . $GLOBALS["dhtfield_{$intInstanceId}"] . " LIKE '%$strCurKey%'" :
                $GLOBALS["dhtfield_{$intInstanceId}"] . " LIKE '$strCurKey'";
            $strSqlQuery = $this->_setSqlClauseArgument($strSqlQuery, "WHERE", $strNewWhere);
            $HTTP_SERVER_VARS['QUERY_STRING'] = $this->updateQueryString(
                array("dhtkey_{$intInstanceId}", "dhtfield_{$intInstanceId}"),
                array($GLOBALS["dhtkey_{$intInstanceId}"], $GLOBALS["dhtfield_{$intInstanceId}"])
                );
        }
		return $strDisplay;
    }


    /**
     * Sets SQL keywords to all-CAPS for later keyword parsing.
     *
     * @param  string  A valid MySQL query
     * @return string  Returns the SQL query statement with the SQL
     *                 keywords set to all-CAPS
     * @access private
     */
    function _setSqlKeywordsToUpper($strQueryStmt)
    {
    	$arrQueryTok = explode(' ', $strQueryStmt);
        for ($i = 0; $i < count($arrQueryTok); $i++) {
        	$strCurrVal = strtoupper($arrQueryTok[$i]);
        	if (in_array($strCurrVal, $this->_arrSqlQueryKeywords)) {
        		$arrQueryTok[$i] = $strCurrVal;
        	}
        }
        return implode(' ', $arrQueryTok);
    }


    /**
     * Returns the argument of the SQL clause keyword passed as the second
     * parameter; returns an empty string if clause keyword is not found.
     *
     * @param  string  A valid MySQL query
     * @param  string  A valid MySQL query clause keyword (e.g. FROM, WHERE,
     *                 GROUP BY, etc)
     * @return string  Returns the argument of the given clause keyword in the
     *                 given MySQL query
     * @access private
     */
    function _getSqlClauseArgument($strQueryStmt, $strKeyword)
    {
        if(!strpos($strQueryStmt, $strKeyword) {
        	trigger_error("No ".$strKeyword." in ".$strQueryStmt."\n",E_WARNING);
        	return '';
        }	
        list($str1, $str2) = explode($strKeyword, $strQueryStmt, 2);
        foreach($this->_arrSqlQueryKeywords as $val) {
            list($str2, $str1) = explode(' '.$val.' ', $str2, 2);
        }
        return trim($str2);
    }


    /**
     * Changes the argument of the SQL clause keyword passed as the second
     * parameter.
     *
     * @param  string  A valid MySQL query
     * @param  string  A valid MySQL query clause keyword (e.g. FROM, WHERE,
     *                 GROUP BY, etc)
     * @param  string  New argument for the clause in the second parameter
     * @return mixed   Returns the modified passed SQL query statement;
     *                 returns FALSE if $strKeyword is invalid
     * @access private
     */
    function _setSqlClauseArgument($strQueryStmt, $strKeyword, $strNewVal)
    {
        // the extra space prefix is needed for explode(): damn explode() it returns the needle/separator if it is found near string{0}: as of PHP 4.0.6
        $strQueryStmt = ' ' . $strQueryStmt;
        if (strpos($strQueryStmt, $strKeyword)) {
            // this block is for updating a current clause in the query
            list($strLeft, $strMid) = explode($strKeyword, $strQueryStmt, 2);
            $intFirstPos = $intMidLen = strlen($strMid);
            foreach($this->_arrSqlQueryKeywords as $val) {
                $intPos = strpos($strMid, ' '.$val.' ');
                if ($intPos) $intFirstPos = ($intPos < $intFirstPos) ? $intPos : $intFirstPos;
            }
            $strRight = ($intFirstPos >= $intMidLen) ? "" : substr($strMid, $intFirstPos);
            return trim($strLeft) . ' ' . $strKeyword . ' ' . $strNewVal . ' ' . $strRight;
        } elseif (in_array($strKeyword, $this->_arrSqlQueryKeywords)) {
            // this block is for setting a new clause in the statement
            $arrReversed = array_reverse($this->_arrSqlQueryKeywords);
            foreach ($arrReversed as $val) {
                if ($val == $strKeyword) break;
                if (strpos($strQueryStmt, ' '.$val.' ')) $strTrailKeyword = $val;
            }
            return ($strTrailKeyword) ?
                str_replace(" $strTrailKeyword ", " $strKeyword $strNewVal $strTrailKeyword ", $strQueryStmt) :
                $strQueryStmt . " $strKeyword $strNewVal";
        } else {
            return FALSE;
        }
    }


    /**
     * Removes the SQL clause from the SQL query statement
     *
     * @param  string  A PHP variable having a valid MySQL query
     * @param  string  A valid MySQL query clause keyword (e.g. FROM, WHERE,
     *                 GROUP BY, etc)
     * @return string  New SQL query statement with the given clause removed
     * @access private
     */
    function _unsetSqlClause(&$strQueryStmt, $strKeyword)
    {
        $strQueryStmt = ' ' . $strQueryStmt;
        list($strLeft, $strMid) = explode($strKeyword, $strQueryStmt, 2);
        $intFirstPos = $intMidLen = strlen($strMid);
        foreach($this->_arrSqlQueryKeywords as $val) {
            $intPos = strpos($strMid, ' '.$val.' ');
            if ($intPos) $intFirstPos = ($intPos < $intFirstPos) ? $intPos : $intFirstPos;
        }
        $strRight = ($intFirstPos >= $intMidLen) ? "" : substr($strMid, $intFirstPos);
        return trim($strLeft) . ' ' . trim($strRight);
    }
}
?>

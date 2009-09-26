<?php
/*
*	This file contain's functions that virtually extend PHP PEAR's Mail_mimeDecode::Mail_mimeDecode().
*	Author: Chikka
*	Date: 6/18/02 4:08PM
*	Note: "mailparse" functions are still experimental and not documented during the development of these functions
*/

	/**
	 * Strips HTML and PHP tags out of the String input
	 *
	 * @param  string  String input
	 * @param  boolean Flag to allow HTML's <BR> tag and
	 *                 exclude in stripping
	 * @return string  The String with stripped of tags
	 * @access public
	 */
	function getCleanBody($strBody, $blnAllowBR=1)
	{
		$arrBrVer = array ('<br>', '<br/>', '<br />');
		$strAllowedBR = ($blnAllowBR) ? "" : implode('', $arrBrVer);

		return str_replace($arrBrVer, "\n", strip_tags(trim($strBody), $strAllowedBR));
	}

	/**
	 * This function searches for the plain text part in an email.
	 * returns 0 or FALSE on failure.
	 *
	 * @param  array  Array of Email Object Parts
	 * @return string The plain text body of the email
	 * @access public
	 */
	function getTextPlainBodyFromParts(&$arrObjParts)
	{
		reset ($arrObjParts);

		do {
			list($key, $value) = each ($arrObjParts);
			$return = (empty($value->body)) ? 0 : $value->body;
		} while ($value->ctype_primary != 'text' && $value->ctype_secondary != 'plain');

		return getCleanBody($return);
	}

	/**
	 * Searches for unsent email messages in the E_Messages table.
	 * returns 0 or FALSE if no rows in result set.
	 *
	 * @param  string  Table name of the table to search
	 * @param  string  Field/Column name of the status flag
	 * @param  integer Status value for unsent messages
	 * @return array   Result rows returned in vector array
	 * @access public
	 */
	function getMessagesToSmsSend($strTable="E_Messages", $strStatCol="status", $intUnsentVal=0)
	{
		$strQuery = "SELECT * FROM $strTable WHERE $strStatCol = $intUnsentVal";
		$result = th_query($strQuery);
		if (mysql_num_rows($result)) {
			while ($row = mysql_fetch_assoc($result)) {
				$arrResult[] = $row;
			}
			return $arrResult;
		} else {
			return 0;
		}
	}

	/**
	 * Gets the corporate domain from the email address parameter
	 *
	 * @param  string  TxtHotline User Email Address
	 * @return string  corporate domain
	 * @access public
	 */
	function getDomainFromAddress($strEmailAddress)
	{
		$intStart = strpos($strEmailAddress, ".") + 1;
		$intKwordLen = strpos($strEmailAddress, "@") - $intStart;
		return ($intKwordLen > 0) ? substr($strEmailAddress, $intStart, $intKwordLen) : "";
	}

	/**
	 * Strips of the Less Than and Greater Than Symbols from input
	 *
	 * @param  string  Input
	 * @return string  stripped-off string input
	 * @access public
	 */
	function stripLTnGT($strInput)
	{
		$strInput = str_replace('"','',$strInput);
		$strInput = str_replace("'",'',$strInput);
		$strInput = str_replace('<','',$strInput);
		$strInput = str_replace('>','',$strInput);
		return trim($strInput);
	}

	/**
	 * Logs a found email to the 'th_message' table
	 *
	 * @param  string  From
	 * @param  string  Recipient
	 * @param  string  Email Body
	 * @return boolean A positive resource if mail is successfully logged and 0 otherwise
	 * @access public
	 */
	function logMailToDB($strFrom, $strRecipient, $strBody, $strMailId = null)
	{
		$strSetMailId = (empty($strMailId)) ? "" : "mailid = '$strMailId',";
		$strQuery = "SELECT id FROM th_client WHERE gsmnum = '$strRecipient' LIMIT 1";
		$result = th_query($strQuery);
		list($intThreadOwner) = mysql_fetch_row($result);
		mysql_free_result($result);
		if (empty($intThreadOwner)) return "THREADLESS_PUSH";
		$strQuery = "INSERT INTO th_message SET
			type = " . TH_MTYPE_EMAIL . ",
			threadowner = $intThreadOwner,
			source = '$strFrom',
			destination = '$strRecipient',
			body = '" . addslashes($strBody) . "',
			$strSetMailId
			dateadded = NOW(),
			lastupdated = NOW()
			";
		$result = th_query($strQuery);
		return $result;
	}

	/**
	 * updates the status of a specific mail (identified by messageid) as sent or "1"
	 *
	 * @param  string/array Messageid (for multiple messageids, use array)
	 * @param  integer      New status
	 * @return boolean      A positive resource if mail is successfully updated or 0 otherwise
	 * @access public
	 */
	function updateMailStatus($messageid, $intStatus=1, $strTable="E_Messages")
	{
		if (is_array($messageid)) {
			foreach ($messageid as $value) {
				$theMessageid[] = "'$value'";
			}
			$theMessageid = implode(',', $theMessageid);
		} else {
			$theMessageid = "'$messageid'";
		}
		$strQuery = "UPDATE $strTable SET
			status = $intStatus
			WHERE messageid IN($theMessageid)
			";
		$result = th_query($strQuery);
		return 1;
	}
?>

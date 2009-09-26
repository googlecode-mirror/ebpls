<?php
/*
*   - some HTML extended PHP functions
*   - real-world update of query strings
*   - should be XHTML compatible
*/


class HtmlExt
{
    var $_strFontFamily;
    var $_strFontSize;


    /**
     * Class Constructor and initializes result properties.
     *
     * @param  string  Font-Style or Font-Face
     *                 (e.g. 'ARIAL,HELVETICA,SANS-SERIF')
     * @param  integer Font-Size (1-7) defined in <font> tag (e.g. 7)
     * @return boolean Always returns TRUE
     * @access public
     */
    function HtmlExt($strFontFamily = null,
                     $strFontSize = null)
    {
        if (!empty($strFontFamily)) $this->_strFontFamily = $strFontFamily;
        if (!empty($strFontSize)) $this->_strFontSize = $strFontSize;
        return TRUE;
    }


    /**
     * Returns a HTML source of the formatted text
     *
     * @param  string  Text
     * @param  boolean Set text to BOLD
     * @param  boolean Set text to ITALIC
     * @param  boolean Set text UNDERLINEd
     * @param  integer Set current Font-Size
     * @param  string  Set current Font-Face
     * @return string  Returns the HTML formatted text
     * @access public
     */
    function setText($strText,
                     $blnTextBold = FALSE,
                     $blnTextItalic = FALSE,
                     $blnTextUnder = FALSE,
                     $strFontSize = null,
                     $strFontFamily = null)
    {
        if ($blnTextUnder) $strText = $this->setTextUnderline($strText);
        if ($blnTextItalic) $strText = $this->setTextItalic($strText);
        if ($blnTextBold) $strText = $this->setTextBold($strText);
        $strFontSize = ($strFontSize) ? $strFontSize : $this->_strFontSize;
        $strFontFamily = ($strFontFamily) ? $strFontFamily : $this->_strFontFamily;
        return "<font face=\"$strFontFamily\" size=\"$strFontSize\">" . $strText . '</font>';
    }


    /**
     * Sets the text to HTML Bold Text
     *
     * @param  string  Text
     * @return string  Returns the HTML formatted bold text
     * @access public
     */
    function setTextBold($strText)
    {
        return '<b>' . $strText . '</b>';
    }


    /**
     * Sets the text to HTML Italic Text
     *
     * @param  string  Text
     * @return string  Returns the HTML formatted italic text
     * @access public
     */
    function setTextItalic($strText)
    {
        return '<i>' . $strText . '</i>';
    }


    /**
     * Sets the text to HTML Underlined Text
     *
     * @param  string  Text
     * @return string  Returns the HTML formatted underlined text
     * @access public
     */
    function setTextUnderline($strText)
    {
        return '<u>' . $strText . '</u>';
    }


    /**
     * Updates the current query string
     *
     * @param  mixed   Query string variable(s) to update : (string or array)
     * @param  mixed   New value(s) for the query string variable(s)
     * @return string  Returns the updated query string; appends a new query
     *                 string variable if the first parameter is not found in
     *                 the query string
     * @access public
     */
    function updateQueryString($mxdUpdateVar, $mxdUpdateValue)
    {
        global $HTTP_SERVER_VARS;
        $arrVars = (strpos($HTTP_SERVER_VARS['QUERY_STRING'], '&amp;')) ?
            explode('&amp;', $HTTP_SERVER_VARS['QUERY_STRING']) :
            explode('&', $HTTP_SERVER_VARS['QUERY_STRING']);
        settype($mxdUpdateVar, "array");
        settype($mxdUpdateValue, "array");

        while ($val = array_shift($mxdUpdateVar)) {
            if (strpos($HTTP_SERVER_VARS['QUERY_STRING'], $val) === FALSE) {
                if (strlen(current($mxdUpdateValue))) $arrNewVars[] = $val . '=' . rawurlencode(current($mxdUpdateValue));
            } else {
                for ($i = 0; $i < count($arrVars); $i++) {
                    list($x, $y) = explode('=', $arrVars[$i]);
                    if ($x == $val) {
                        $y = rawurlencode(current($mxdUpdateValue));
                        if (strlen($y)) {
                            $arrVars[$i] = $x . '=' . $y;
                        } else {
                            array_splice($arrVars, $i, 1);
                        }
                    }
                }
            }
            if (!next($mxdUpdateValue)) reset($mxdUpdateValue);
        }

        if (empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
            $strReturn = implode('&amp;', (array)$arrNewVars);
        } elseif (empty($arrNewVars)) {
            $strReturn = implode('&amp;', (array)$arrVars);
        } else {
            $strReturn = implode('&amp;', $arrVars) . '&amp;' . implode('&amp;', $arrNewVars);
        }
        return $strReturn;
    }
}
?>

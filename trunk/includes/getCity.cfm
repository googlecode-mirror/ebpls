<cfif NOT IsDefined("url.zip") OR NOT IsNumeric(url.zip)>
<div class="badzip">Please enter a valid zip code.</div><cfabort>
</cfif>
<cfquery datasource="mysql_test" name="ziplookup">
SELECT city, state FROM zipcodes
WHERE zip = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.zip#">
</cfquery>
<cfif ziplookup.recordcount>
<div class="goodzip"><cfoutput>#ziplookup.city#, #ziplookup.state#</cfoutput></div>
<cfelse>
<div class="badzip">Zip code not found.</div>
</cfif>


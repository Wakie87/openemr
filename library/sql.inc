<?php
/**
* Sql functions/classes for OpenEMR.
*
* Includes classes and functions that OpenEMR uses
* to interact with SQL.
*
* LICENSE: This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://opensource.org/licenses/gpl-license.php>.
*
* @package   OpenEMR
* @link      http://www.open-emr.org
*/

error_reporting(E_WARNING);
ini_set("display_errors", 1);

require_once(dirname(__FILE__) . "/sqlconf.php");
require_once(dirname(__FILE__) . "/log.inc");
require_once(dirname(__FILE__) . "/Framework/src/Database/Database.php");

/**
* Standard sql query in OpenEMR.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. Will continue to
* be compatible with previous function calls that do
* not use binding.
* It will return a recordset object.
* The sqlFetchArray() function should be used to
* utilize the return object.
*
* @param  string  $statement  query
* @param  array   $params      binded variables array (optional)
* @return recordset
*/
function sqlStatement($statement, $params = null )
{

  $recordset = DB::run($statement, $params);
  return $recordset;
}

/**
* Specialized sql query in OpenEMR that skips auditing.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. Will continue to
* be compatible with previous function calls that do
* not use binding. It is equivalent to the
* sqlStatement() function, EXCEPT it skips the
* audit engine. This function should only be used
* in very special situations.
* It will return a recordset object.
* The sqlFetchArray() function should be used to
* utilize the return object.
*
* @param  string  $statement  query
* @param  array   $params      binded variables array (optional)
* @return recordset
*/
function sqlStatementNoLog($statement, $params = null )
{
  // Use adodb ExecuteNoLog with binding and return a recordset.
  $recordset = DB::runNoLog( $statement, $params );
  return $recordset;
}

/**
* sqlStatement() function wrapper for CDR engine in OpenEMR.
* Allows option to turn on/off auditing specifically for the
* CDR engine.
*
* @param  string  $statement  query
* @param  array   $params      binded variables array (optional)
* @return recordset/resource
*/
function sqlStatementCdrEngine($statement, $params = null )
{
  if ($GLOBALS['audit_events_cdr']) {
    return sqlStatement($statement, $params);
  }
  else {
    return sqlStatementNoLog($statement, $params);
  }
}

/**
* Returns a row (as an array) from a sql recordset.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection.
* It will act upon the object returned from the
* sqlStatement() function.
*
* @param recordset $r
* @return array
*/
function sqlFetchArray($r)
{
    if ($r === FALSE)
    return false;
    //ensure it's an object (ie. is set)
    if (!is_object($r))
    return false;
    return $r->Fetch();
}

/**
 * Wrapper for ADODB getAssoc
 *
 * @see http://adodb.org/dokuwiki/doku.php?id=v5:reference:connection:getassoc
 *
 * @param string $sql
 * @param string[] $bindvars
 * @param boolean $forceArray
 * @param boolean $first2Cols
 * @return array
 */
function sqlGetAssoc( $sql, $bindvars=false, $forceArray=false, $first2Cols=false ) {

  return $GLOBALS['adodb']['db']->getAssoc( $sql, $bindvars, $forceArray, $first2Cols  );

}

/**
* Standard sql insert query in OpenEMR.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. This function
* is specialized for insert function and will return
* the last id generated from the insert.
*
* @param  string   $statement  query
* @param  array    $params      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
function sqlInsert($statement, $params = null)
{

  $recordset = DB::run($statement, $params);
  // Return the correct last id generated using function
  //   that is safe with the audit engine.
  return getSqlLastID();
}

/**
* Specialized sql query in OpenEMR that only returns
* the first row of query results as an associative array.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection.
*
* @param  string  $statement  query
* @param  array   $params      binded variables array (optional)
* @return array
*/
function sqlQuery($statement, $params = null)
{
  $recordset = DB::run( $statement, $params );

  if (empty($recordset))
   return FALSE;
  $rez = $recordset->Fetch();
  if ($rez == FALSE)
    return FALSE;
  return $rez;
}

/**
* Specialized sql query in OpenEMR that bypasses the auditing engine
* and only returns the first row of query results as an associative array.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. It is equivalent to the
* sqlQuery() function, EXCEPT it skips the
* audit engine. This function should only be used
* in very special situations.
*
* @param  string  $statement  query
* @param  array   $params      binded variables array (optional)
* @return array
*/
function sqlQueryNoLog($statement, $params = null)
{
  $recordset = DB::runNoLog( $statement, $params );
  $rez = $recordset->Fetch();
  return $rez;
}

/**
* Specialized sql query in OpenEMR that ignores sql errors, bypasses the
* auditing engine and only returns the first row of query results as an
* associative array.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. It is equivalent to the
* sqlQuery() function, EXCEPT it skips the
* audit engine and ignores erros. This function should only be used
* in very special situations.
*
* @param  string  $statement  query
* @param  array   $params      binded variables array (optional)
* @return array
*/
function sqlQueryNoLogIgnoreError($statement, $params = null)
{
  $recordset = DB::runNoLog( $statement, $params );
  if ($recordset === FALSE) {
    // ignore the error and return FALSE
    return FALSE;
  }
  $rez = $recordset->Fetch();
  return $rez;
}

/**
* sqlQuery() function wrapper for CDR engine in OpenEMR.
* Allows option to turn on/off auditing specifically for the
* CDR engine.
*
* @param  string  $statement  query
* @param  array   $params      binded variables array (optional)
* @return array
*/
function sqlQueryCdrEngine($statement, $params = null )
{
  if ($GLOBALS['audit_events_cdr']) {
    return sqlQuery($statement,$params);
  }
  else {
    return sqlQueryNoLog($statement,$params);
  }
}

/**
* Specialized sql query in OpenEMR that skips auditing.
*
* This function should only be used in very special situations.
*
* @param  string  $statement  query
*/
function sqlInsertClean_audit($statement)
{
  $ret = DB::runNoLog($statement);
}

/**
* Function that will safely return the last ID inserted,
* and accounts for the audit engine.
*
* @return  integer Last ID that was inserted into sql
*/
function getSqlLastID() {
    return $GLOBALS['lastidado'] > 0 ? $GLOBALS['lastidado'] : DB::lastInsertId();
}

/**
* Function that will safely return the last error,
* and accounts for the audit engine.
*
* @param   string  $mode either adodb(default) or native_mysql
* @return  string        last mysql error
*/
function getSqlLastError() {
    return !empty($GLOBALS['last_mysql_error']) ? $GLOBALS['last_mysql_error'] : DB::errorInfo();
}

/**
 * Function that will safely return the last error no,
 * and accounts for the audit engine.
 *
 * @param   string  $mode either adodb(default) or native_mysql
 * @return  string        last mysql error no
 */
function getSqlLastErrorNo() {
    return !empty($GLOBALS['last_mysql_error_no']) ? $GLOBALS['last_mysql_error_no'] : DB::errorCode();
}

/**
* Function that will return an array listing
* of columns that exist in a table.
*
* @param   string  $table sql table
* @return  array
*/
function sqlListFields($table) {
  $sql = "SHOW COLUMNS FROM ". escape_table_name($table);
  $resource = sqlStatement($sql);
  $field_list = array();
  while($row = sqlFetchArray($resource)) {
    $field_list[] = $row['Field'];
  }
  return $field_list;
}

/**
* Returns the number of sql rows
*
* @param recordset $r
* @return integer Number of rows
*/
function sqlNumRows($r)
{
  return $r->rowCount();
}

/**
* @todo document use of the generate_id function
*/
function generate_id () {
  $database = $GLOBALS['adodb']['db'];
  return $database->GenID("sequences");
}

/**
* Simple wrapper for sqlInsert() function (deprecated).
*
* Function that will allow use of the adodb binding feature
* to prevent sql-injection.
*
* @deprecated
* @param  string   $statement  query
* @param  array    $params      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
function idSqlStatement($statement , $params = null )
{
  return sqlInsert($statement, $params);
}

/**
* Simple wrapper for sqlInsert() function (deprecated).
*
* Function that will allow use of the adodb binding feature
* to prevent sql-injection.
*
* @deprecated
* @param  string   $statement  query
* @param  array    $params      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
function sqlInsertClean($statement, $params = null )
{
  return sqlInsert($statement, $params);
}


/**
* Sql close connection function (deprecated)
*
* No longer needed since PHP does this automatically.
*
* @deprecated
* @return boolean
*/
function sqlClose()
{
  //----------Close our mysql connection
  $closed = $GLOBALS['adodb']['db']->close;
  return $closed;
}

/**
* Very simple wrapper function and not necessary (deprecated)
*
* Do not use.
*
* @deprecated
* @return connection
*/
function get_db() {
  return $GLOBALS['adodb']['db'];
}

/**
 * Generic mysql select db function
 * Used when converted to mysqli to centralize special circumstances.
 * @param string $database
 */
function generic_sql_select_db($database, $link = null)
{
  if (is_null($link))
    $link = $GLOBALS['dbh'];
  mysqli_select_db($link, $database);
}

/**
 * Generic mysql affected rows function
 * Used when converted to mysqli to centralize special circumstances.
 *
 */
function generic_sql_affected_rows()
{
  return mysqli_affected_rows($GLOBALS['dbh']);
}

/**
 * Generic mysql insert id function
 * Used when converted to mysqli to centralize special circumstances.
 *
				 */
function generic_sql_insert_id()
{
  return DB::lastInsertId();
}


/**
 * Begin a Transaction.
 */
function sqlBeginTrans()
{
    DB::beginTransaction();
}


/**
 * Commit a transaction
 */
function sqlCommitTrans($ok=true)
{
    DB::commit();
}


/**
 * Rollback a transaction
 */
function sqlRollbackTrans()
{
    DB::rollBack();
}

?>

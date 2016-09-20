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
 error_reporting(E_ALL);

require_once(dirname(__FILE__) . "/sqlconf.php");
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/adodb.inc.php");
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/drivers/adodb-mysqli.inc.php");
require_once(dirname(__FILE__) . "/log.inc");

class sql
{
    private $_connection;


    public function __construct()
    {

        $this->_connection =  database::getInstance();
        $this->dbh = $this->_connection->getConnection();
        $GLOBALS['dbh'] = $this->dbh->_connectionID;
    }
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
* @param  array   $binds      binded variables array (optional)
* @return recordset
*/
public function sqlStatement($statement, $binds=false )
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  // Use adodb Execute with binding and return a recordset.
  //   Note that the auditSQLEvent function is embedded
  //    in the Execute command.
  $recordset = $this->dbh->Execute( $statement, $binds );
  if ($recordset === FALSE) {
    $this->HelpfulDie("query failed: $statement", $this->getSqlLastError());
  }
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
* @param  array   $binds      binded variables array (optional)
* @return recordset
*/
public function sqlStatementNoLog($statement, $binds=false )
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  // Use adodb ExecuteNoLog with binding and return a recordset.
  $recordset = $this->dbh->ExecuteNoLog( $statement, $binds );
  if ($recordset === FALSE) {
    $this->HelpfulDie("query failed: $statement", $this->getSqlLastError());
  }
  return $recordset;
}

/**
* sqlStatement() function wrapper for CDR engine in OpenEMR.
* Allows option to turn on/off auditing specifically for the
* CDR engine.
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return recordset/resource
*/
public function sqlStatementCdrEngine($statement, $binds=false )
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  if ($GLOBALS['audit_events_cdr']) {
    return $this->sqlStatement($statement,$binds);
  }
  else {
    return $this->sqlStatementNoLog($statement,$binds);
  }
}

/**
* Returns a row (as an array) from a sql recordset.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection.
* It will act upon the object returned from the
* sqlStatement() function (and sqlQ() function).
*
* @param recordset $r
* @return array
*/
public function sqlFetchArray($r)
{
  //treat as an adodb recordset
  if ($r === FALSE)
    return false;
  if ($r->EOF)
    return false;
  //ensure it's an object (ie. is set)
  if (!is_object($r))
    return false;

    return $r->FetchRow();
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

  return $this->dbh->getAssoc( $sql, $bindvars, $forceArray, $first2Cols  );

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
* @param  array    $binds      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
public function sqlInsert($statement, $binds=false)
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;
  //Run a adodb execute
  // Note the auditSQLEvent function is embedded in the
  //   Execute function.
  $recordset = $this->dbh->Execute($statement, $binds);
  if ($recordset === FALSE) {
    $this->HelpfulDie("insert failed: $statement", $this->getSqlLastError());
  }
  // Return the correct last id generated using function
  //   that is safe with the audit engine.
  return $this->getSqlLastID();
}

/**
* Specialized sql query in OpenEMR that only returns
* the first row of query results as an associative array.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection.
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return array
*/
public function sqlQuery($statement, $binds=false)
{

  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;
  //$dbh = $this->dbh;
  $recordset = $this->dbh->Execute( $statement, $binds );

  if ($recordset === FALSE) {
    $this->HelpfulDie("query failed: $statement", $this->getSqlLastError());
  }
  if ($recordset->EOF)
   return FALSE;
  $rez = $recordset->FetchRow();
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
* @param  array   $binds      binded variables array (optional)
* @return array
*/
public function sqlQueryNoLog($statement, $binds=false)
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  $recordset = $this->dbh->ExecuteNoLog( $statement, $binds );

  if ($recordset === FALSE) {
    $this->HelpfulDie("query failed: $statement", $this->getSqlLastError());
  }
  if ($recordset->EOF)
   return FALSE;
  $rez = $recordset->FetchRow();
  if ($rez == FALSE)
    return FALSE;
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
* @param  array   $binds      binded variables array (optional)
* @return array
*/
public function sqlQueryNoLogIgnoreError($statement, $binds=false)
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  $recordset = $this->dbh->ExecuteNoLog( $statement, $binds );

  if ($recordset === FALSE) {
    // ignore the error and return FALSE
    return FALSE;
  }
  if ($recordset->EOF)
   return FALSE;
  $rez = $recordset->FetchRow();
  if ($rez == FALSE)
    return FALSE;
  return $rez;
}

/**
* sqlQuery() function wrapper for CDR engine in OpenEMR.
* Allows option to turn on/off auditing specifically for the
* CDR engine.
*
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return array
*/
public function sqlQueryCdrEngine($statement, $binds=false )
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  if ($GLOBALS['audit_events_cdr']) {
    return $this->sqlQuery($statement,$binds);
  }
  else {
    return $this->sqlQueryNoLog($statement,$binds);
  }
}

/**
* Specialized sql query in OpenEMR that skips auditing.
*
* This function should only be used in very special situations.
*
* @param  string  $statement  query
*/
public function sqlInsertClean_audit($statement)
{

  $ret = $this->dbh->ExecuteNoLog($statement);
  if ($ret === FALSE) {
    $this->HelpfulDie("insert failed: $statement", $this->getSqlLastError());
  }
}

/**
* Function that will safely return the last ID inserted,
* and accounts for the audit engine.
*
* @return  integer Last ID that was inserted into sql
*/
public function getSqlLastID() {
    return $GLOBALS['lastidado'] > 0 ? $GLOBALS['lastidado'] : $this->dbh->Insert_ID();
}

/**
* Function that will safely return the last error,
* and accounts for the audit engine.
*
* @param   string  $mode either adodb(default) or native_mysql
* @return  string        last mysql error
*/
public function getSqlLastError() {
    return !empty($GLOBALS['last_mysql_error']) ? $GLOBALS['last_mysql_error'] : $this->dbh->ErrorMsg();
}

/**
 * Function that will safely return the last error no,
 * and accounts for the audit engine.
 *
 * @param   string  $mode either adodb(default) or native_mysql
 * @return  string        last mysql error no
 */
public function getSqlLastErrorNo() {
    return !empty($GLOBALS['last_mysql_error_no']) ? $GLOBALS['last_mysql_error_no'] : $this->dbh->ErrorNo();
}

/**
* Function that will return an array listing
* of columns that exist in a table.
*
* @param   string  $table sql table
* @return  array
*/
public function sqlListFields($table) {
  $sql = "SHOW COLUMNS FROM ". add_escape_custom($table);
  $resource = $this->sqlQ($sql);
  $field_list = array();
  while($row = $this->sqlFetchArray($resource)) {
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
public function sqlNumRows($r)
{
  return $r->RecordCount();
}

/**
* Error function for OpenEMR sql functions
*
* @param string $statement
* @param string $sqlerr
*/
public function HelpfulDie ($statement, $sqlerr='')
{
  echo "<p><p><font color='red'>ERROR:</font> ".text($statement)."<p>";
  $logMsg="SQL Error with statement:".$statement;
  if ($sqlerr) {
    echo "Error: <font color='red'>".text($sqlerr)."</font><p>";
    $logMsg.="--".$sqlerr;
  }//if error
  $backtrace=debug_backtrace();
  for($level=1;$level<count($backtrace);$level++)
  {
      $info=$backtrace[$level];
      echo "<br>".text($info["file"]." at ".$info["line"].":".$info["function"]);
      if($level>1){
          echo "(".text(implode(",",$info["args"])).")";
      }
  }
  $logMsg.="==>".$backtrace[1]["file"]." at ".$backtrace[1]["line"].":".$backtrace[1]["function"];
  error_log($logMsg);

  exit;
}

/**
* @todo document use of the generate_id function
*/
public function generate_id () {
  $database = $this->dbh;
  return $database->GenID("sequences");
}

/**
* Deprecated function. Standard sql query in OpenEMR.
*
* Function that will allow use of the adodb binding
* feature to prevent sql-injection. Will continue to
* be compatible with previous function calls that do
* not use binding.
* It will return a recordset object.
* The sqlFetchArray() function should be used to
* utilize the return object.
*
* @deprecated
* @param  string  $statement  query
* @param  array   $binds      binded variables array (optional)
* @return recordset
*/
public function sqlQ($statement, $binds=false )
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  $recordset = $this->dbh->Execute( $statement, $binds ) or
    $this->HelpfulDie("query failed: $statement", $this->getSqlLastError());
  return $recordset;
}

/**
* Simple wrapper for sqlInsert() function (deprecated).
*
* Function that will allow use of the adodb binding feature
* to prevent sql-injection.
*
* @deprecated
* @param  string   $statement  query
* @param  array    $binds      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
public function idSqlStatement($statement , $binds=false )
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  return $this->sqlInsert($statement, $binds);
}

/**
* Simple wrapper for sqlInsert() function (deprecated).
*
* Function that will allow use of the adodb binding feature
* to prevent sql-injection.
*
* @deprecated
* @param  string   $statement  query
* @param  array    $binds      binded variables array (optional)
* @return integer  Last id generated from the sql insert command
*/
public function sqlInsertClean($statement, $binds=false )
{
  // Below line is to avoid a nasty bug in windows.
  if (empty($binds)) $binds = false;

  return $this->sqlInsert($statement, $binds);
}


/**
* Sql close connection function (deprecated)
*
* No longer needed since PHP does this automatically.
*
* @deprecated
* @return boolean
*/
public function sqlClose()
{
  //----------Close our mysql connection
  $closed = $this->dbh->close or
    $this->HelpfulDie("could not disconnect from mysql server link", $this->getSqlLastError());
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
public function get_db() {
  return $this->dbh;
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
public function generic_sql_affected_rows()
{
  return mysqli_affected_rows($GLOBALS['dbh']);
}

/**
 * Generic mysql insert id function
 * Used when converted to mysqli to centralize special circumstances.
 *
				 */
public function generic_sql_insert_id()
{
  return mysqli_insert_id($GLOBALS['dbh']);
}


/**
 * Begin a Transaction.
 */
public function sqlBeginTrans()
{
    $this->dbh->BeginTrans();
}


/**
 * Commit a transaction
 */
public function sqlCommitTrans($ok=true)
{
    $this->dbh->CommitTrans();
}


/**
 * Rollback a transaction
 */
public function sqlRollbackTrans()
{
    $this->dbh->RollbackTrans();
}

}
?>

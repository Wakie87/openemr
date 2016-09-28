<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'openemr');
define('DB_USER', 'openemr');
define('DB_PASS', 'openemr');
define('DB_CHAR', 'utf8');

require_once(dirname(__FILE__) . "/../../../log.inc");

class DB
{
    protected static $instance = null;

    protected function __construct() {}
    protected function __clone() {}

    public static function instance()
    {
        if (self::$instance === null)
        {
            $opt  = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => FALSE,
            );
            $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHAR;
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opt);
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    public static function run($sql, $args = [])
    {
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        if ($stmt === false) {
          $outcome = false;
          // Stash the error into last_mysql_error so it doesn't get clobbered when we insert into the audit log.
          $GLOBALS['last_mysql_error']=self::instance()->errorInfo();
        }
        else {
          $outcome = true;
        }
        $GLOBALS['lastidado']=self::instance()->lastInsertId();
        auditSQLEvent($sql,$outcome,$args);
        return $stmt;
    }

    public static function runNoLog($sql, $args = [])
    {
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }



}

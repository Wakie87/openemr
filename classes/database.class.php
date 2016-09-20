<?php
require_once(dirname(__FILE__) . "/../library/sqlconf.php");
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/adodb.inc.php");
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/drivers/adodb-mysqli.inc.php");

define(ADODB_FETCH_ASSOC, 2);

class database
{
    private $_connection;
    private static $_instance;

    // Constructor
    private function __construct()
    {
        global $sqlconf;
        $this->server = $sqlconf['host'];
        $this->login = $sqlconf['login'];
        $this->pass = $sqlconf['pass'];
        $this->port = $sqlconf['port'];
        $this->dbname = $sqlconf['dbase'];
        $this->collate = 'utf-8';
        $this->_connection = false;
        $this->user_database_connection();
    }

    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance()
    {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    // Get mysqli connection
    public function getConnection()
    {
        return $this->_connection;
    }


    public function user_database_connection()
    {
        $this->_dbh = $this->connect($this->server, $this->login, $this->pass, $this->port, $this->dbname);
        if (! $this->_dbh) {
            $this->error_message = "unable to connect to database as user: '$this->login'";
            return false;
        }
        if (! $this->set_sql_strict()) {
            $this->error_message = 'unable to set strict sql setting';
            return false;
        }
        if (! $this->set_collation()) {
            $this->error_message = 'unable to set sql collation';
            return false;
        }
        return true;
    }

    private function connect($server, $login, $pass, $port, $dbname='')
    {
        if (!defined('ADODB_FETCH_ASSOC')) {
            define('ADODB_FETCH_ASSOC', 2);
        }
        $this->_connection = NewADOConnection('mysqli_log');
        $this->_connection->clientFlags = 128;
        $this->_connection->port = $this->port;
        $this->_connection->PConnect($this->server, $this->login, $this->pass, $this->dbname);
        $this->_connection->SetFetchMode(ADODB_FETCH_ASSOC);

        return $this->_connection;
    }

    private function set_sql_strict()
    {
        // Turn off STRICT SQL
    return $this->_connection->Execute("SET sql_mode = ''");
    }

    private function set_collation()
    {
        if ($this->collate) {
            return $this->_connection->Execute("SET NAMES 'utf8'");
        }
        return true;
    }
}

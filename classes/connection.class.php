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
        //$host = $sqlconf['host'];
        $this->_connection = NewADOConnection('mysqli_log');
        $this->_connection->clientFlags = 128;
        $this->_connection->port = $sqlconf['port'];
        $this->_connection->PConnect($sqlconf['host'], $sqlconf['login'], $sqlconf['pass'], $sqlconf['dbase']);
        $this->_connection->SetFetchMode(ADODB_FETCH_ASSOC);
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


}

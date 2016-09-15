<?php
require_once(dirname(__FILE__) . "/../library/sqlconf.php");
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/adodb.inc.php");
require_once(dirname(__FILE__) . "/../vendor/adodb/adodb-php/drivers/adodb-mysqli.inc.php");

class database
{
    private $_connection;
    private static $_instance;
    // private $dbase;
    // private $login;
    // private $pass;
    // private $port;

    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance()
    {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct()
    {
        global $sqlconf;
        //$host = $sqlconf['host'];
        $this->_connection = NewADOConnection('mysqli_log');
        $this->_connection->clientFlags = 128;
        $this->_connection->port = $port;
        $this->_connection->PConnect($sqlconf['host'], $sqlconf['login'], $sqlconf['pass'], $sqlconf['dbase']);
        $this->_connection->SetFetchMode(ADODB_FETCH_ASSOC);

        //$GLOBALS['adodb']['db'] =  $this->_connection;


        // Error handling
        // if (!$GLOBALS['dbh']) {
        //   //try to be more helpful
        //   if ($host == "localhost") {
        //     echo "Check that mysqld is running.<p>";
        //   } else {
        //     echo "Check that you can ping the server '".text($host)."'.<p>";
        //   }//if local
        //   HelpfulDie("Could not connect to server!", getSqlLastError());
        //   exit;
        // }//if no connection
    }


    // Get mysqli connection
    public function getConnection()
    {
        return $this->_connection;
    }

}

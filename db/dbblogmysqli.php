<?php

require_once 'idb.php';
require_once 'dbCredentials.php';
require_once 'dbblog.php';

class blogmysqli extends blog {

    protected function fetch($statement) {
        return $statement->fetch_assoc();
    }
}

class DbMysqli implements iDb {
    private $mysqli;
    
    public function __construct() {
        $dbCredentials = new DbCredentials;
        $dbCredentials->getDbCredentialsFromXml();

        try {
            $this->mysqli = mysqli_connect($dbCredentials->host, $dbCredentials->login, $dbCredentials->password, $dbCredentials->dbname);
        } catch (Exception $ex) {
            die('Erreur : '.$ex->getMessage());
        }        
    }
    
    public function query($sql) {
        return new DbMysqliStatement($this->mysqli->query($sql));
    } 
    
    public function fetchAll($sql, $array, $objectName = null) {
        // TODO
    }   
    
    public function fetchColumn($sql) {
        // TODO
    }
    
    public function exec($sql, $array = null) {
        if($array === null) {
            $this->mysqli->real_query($sql);
        } else {
            $statement = $this->mysqli->prepare($sql);
            $statement->execute($array);
        }  
    }
}

class DbMysqliStatement implements IDbStatement {
    private $statement;
    
    public function __construct($statement) {
        $this->statement = $statement;
    }
    
    public function fetch() {
        return $this->statement->fetch_assoc();
    }
    
    public function closeCursor() {
        $this->statement->close();
    }
}

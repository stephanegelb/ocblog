<?php

require_once 'idb.php';
require_once 'dbCredentials.php';
require_once 'dbblog.php';

class blogpdo extends blog {
    protected function fetch($statement) {
        return $statement->fetch();
    }
}

class DbPDO implements IDb 
{
    private $pdo;

    public function __construct() {
        $dbCredentials = new DbCredentials;
        $connectionString = $dbCredentials->getDbCredentialsFromXml()->getConnectionString();

        try {
            $this->pdo = new PDO($connectionString, $dbCredentials->login, $dbCredentials->password);
        } catch (Exception $ex) {
            die('Erreur : '.$ex->getMessage());
        }        
    }
    
    public function __destruct() {
        $this->pdo = null;
    }
    
    public function query($sql) {
        return new DbPDOStatement($this->pdo->query($sql));
    }

    
    public function fetchAll($sql, $array, $objectName = null) {
        $data = null;
        $statement = $this->pdo->prepare($sql);
        $statement->execute($array);
        if(!is_null($objectName)) {
            $data = $statement->fetchAll(PDO::FETCH_CLASS, (string)$objectName);
        } else {
            $data = $statement->fetchAll();
        }
        $statement = null;
        return $data;
    }
    
    public function fetchColumn($sql) {
        $sth = $this->pdo->prepare($sql); 
        $sth->execute();
        $data = $sth->fetchColumn();
        return $data;           
    }
    
    public function exec($sql, $array = null) {
        if($array === null) {
            $this->pdo->exec($sql);
        } else {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($array);
        }     
    }
}

class DbPDOStatement implements IDbStatement {
    private $statement;
        
    public function __construct($statement) {
        $this->statement = $statement;
    }
    
    public function __destruct() {
        $this->statement = null;
    }
    
//    function execute($array) {
//        $this->statement->execute($array);
//    }

    public function fetch() {
        return $this->statement->fetch();
    }
    
    public function closeCursor() {
        $this->statement->closeCursor();
    }
}


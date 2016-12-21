<?php

//require_once 'idb.php';
//require_once 'dbCredentials.php';
//require_once 'dbblog.php';

class dbblogpdo extends dbblog {
}

class DbPDO implements idb 
{
    private $pdo;

    public function __construct() {
        $dbCredentials = new dbCredentials;
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

    public function fetchAll($sql, $array, $objectName = null) {
        $data = null;
        $statement = $this->pdo->prepare($sql);
        $statement->execute($array);
        if(!is_null($objectName)) {
            $data = $statement->fetchAll(PDO::FETCH_CLASS, (string)$objectName);
        } else {
            $data = $statement->fetchAll();
        }
        $statement->closeCursor();
        $statement = null;
        return $data;
    }
    
    public function fetchColumn($sql) {
        $sth = $this->pdo->prepare($sql); 
        $sth->execute();
        $data = $sth->fetchColumn();
        $sth->closeCursor();
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

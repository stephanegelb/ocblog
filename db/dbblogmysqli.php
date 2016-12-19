<?php

require_once 'idb.php';
require_once 'dbCredentials.php';
require_once 'dbblog.php';

class blogmysqli extends blog {
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
    
    public function __destruct() {
        try {
            $this->mysqli->close();
        } catch (Exception $ex) {
            die('Erreur : '.$ex->getMessage());
        }
    }
        
    public function fetchAll($sql, $array, $objectName = null) {
        // TODO
        $data = [];
        if(($result = $this->mysqli->query($sql))) {
            while($object = $result->fetch_object($objectName)) {
                array_push($data, $object);
            }
            $result->close();
        }
        return $data;
    }   
    
    public function fetchColumn($sql) {
        // TODO
        $array = $this->mysqli->query($sql)->fetch_array();
        return $array[0];
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

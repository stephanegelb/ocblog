<?php

class DbCredentials
{
    public $host;
    public $dbname;
    public $login;
    public $password;
    
    public function __construct() {
        $this->host = 'localhost';
        $this->dbname = 'test';
        $this->login = 'root';
        $this->password = '';        
    }
    
    public function getDbCredentialsFromJson($filename = null) {
        if($filename === null) {
            $filename = __DIR__.'\db.json';
        }
        
        if(file_exists($filename)) {
            try {
                $fileContent = file_get_contents($filename);
                $json = json_decode($fileContent, true);
                $this->host = $json['host'];
                $this->dbname = $json['dbname'];
                $this->login = $json['login'];
                $this->password = $json['password'];
            } catch (Exception $ex) {
                //die('Erreur : '.$ex->getMessage());
            }
        }

        return $this;
    }
    
    public function getDbCredentialsFromXml($filename = null) {
        if($filename === null) {
            $filename = __DIR__.'\db.xml';
        }

        if(file_exists($filename)) {
            $xml = simplexml_load_file($filename);

            $this->host = (string)$xml->host;
            $this->dbname = (string)$xml->dbname;
            $this->login = (string)$xml->login;
            $this->password = (string)$xml->password;
        }

        return $this;
    }
    
    public function getConnectionString(DbCredentials $dbCredentials = null) {
        //$connectionString = 'mysql:host='.$cred['host'].';dbname='.$cred['dbname'].';charset=utf8;';   
        //$dbCredentials = $dbCredentials === null ? $this : $dbCredentials;
        if($dbCredentials === null) {
            $dbCredentials = $this;
        }
        $connectionString = sprintf("mysql:host=%s;dbname=%s;charset=utf8", 
                $dbCredentials->host, $dbCredentials->dbname);
        return $connectionString;
    }
}

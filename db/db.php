<?php

class DbCredentials
{
    public function getDbCredentialsFromJson() {
        $dbCredentials = array(
            'host' => 'localhost', 
            'dbname' => 'test', 
            'login' => 'root', 
            'password' => 'azer'
        );

        $filename = 'db.json';
        if(file_exists($filename)) {
            try {
                $fileContent = file_get_contents($filename);
                $json = json_decode($fileContent, true);
                $dbCredentials['host'] = $json['host'];
                $dbCredentials['dbname'] = $json['dbname'];
                $dbCredentials['login'] = $json['login'];
                $dbCredentials['password'] = $json['password'];
            } catch (Exception $ex) {
                //die('Erreur : '.$ex->getMessage());
            }
        }

        return $dbCredentials;
    }
    
    public function getDbCredentialsFromXml() {
        $filename = 'db.xml';
        $xml = simplexml_load_file($filename);
        
        $dbCredentials = array(
            'host' => 'localhost', 
            'dbname' => 'test', 
            'login' => 'root', 
            'password' => 'azer'
        );

        $dbCredentials['host'] = (string)$xml->host;
        $dbCredentials['dbname'] = (string)$xml->dbname;
        $dbCredentials['login'] = (string)$xml->login;
        $dbCredentials['password'] = (string)$xml->password;
                
        return $dbCredentials;
    }
}

function getDb() {
    $dbCred = new DbCredentials;
    $cred = $dbCred->getDbCredentialsFromXml();
    $connectionString = 'mysql:host='.$cred['host'].';dbname='.$cred['dbname'].';charset=utf8;';   
    try {
        $db = new PDO($connectionString, $cred['login'], $cred['password']);
    } catch (Exception $ex) {
        die('Erreur : '.$ex->getMessage());
    }

    return $db;
}

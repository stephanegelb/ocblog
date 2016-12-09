<?php

include_once 'dbCredentials.php';

function getDb() {
    $dbCredentials = new DbCredentials;
    $connectionString = $dbCredentials->getDbCredentialsFromXml()->getConnectionString();

    try {
        $db = new PDO($connectionString, $dbCredentials->login, $dbCredentials->password);
    } catch (Exception $ex) {
        die('Erreur : '.$ex->getMessage());
    }

    return $db;
}

function fetchAll() {
    
}

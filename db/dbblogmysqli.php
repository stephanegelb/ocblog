<?php

//require_once 'idb.php';
//require_once 'dbCredentials.php';
//require_once 'dbblog.php';

class dbblogmysqli extends dbblog {
    function insertBillet(billet $billet) {
        $titre = str_replace("'", "''", $billet->titre);
        $contenu = str_replace("'", "''", $billet->contenu);
        $sql = sprintf("INSERT INTO billets (titre,contenu,date_creation) VALUES "
                . "('%s','%s',NOW())", $titre, $contenu);
        // TODO securiser les entrees pour ne pas mettre de html et/ou javascript dans les values
        $this->db->exec($sql);
    }
    function insertComment(comment $comment) {
        $auteur = str_replace("'", "''", $comment->auteur);
        $commentaire = str_replace("'", "''", $comment->commentaire);
        $sql = sprintf("INSERT INTO commentaires (id, id_billet, auteur, commentaire, date_commentaire) VALUES "
                . "(NULL,%d,'%s','%s',NOW())", $comment->id_billet, $auteur, $commentaire);
        // TODO securiser les entrees pour ne pas mettre de html et/ou javascript dans les values
        $this->db->exec($sql);
    }
}

class DbMysqli implements idb {
    private $mysqli;
    
    public function __construct() {
        $dbCredentials = new dbCredentials;
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
        // bricolage
        $newsql = $sql;
        if(count($array) >= 1) {
            $newsql = str_replace('?', $array[0], $sql);
        }
        // TODO securiser tout ca!
        
        if(($result = $this->mysqli->query($newsql))) {
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
            ;
            // pffffff, marche pas
//            $statement = $this->mysqli->prepare($sql);
//            if($statement != null) {
//                $this->execstatement($statement, $array);
//                $statement->close();  
//            }
        }  
    }
    
//    private function execstatement($statement, $array) {
//        for($i=0; $i<count($array); $i++) {
//            $type = gettype($array[$i]);
//            $dbtype = 's';
//            if($type == 'string') {
//                $dbtype = 's';
//            } else if($type == 'integer') {
//                $dbtype = 'i';
//            } 
//            //$dbtype = $this->gettypeforbind($array[$i]);
//            $statement->bind_param($dbtype, $array[$i]);
//        }
//        $statement->execute($array);
//    }
    
//    private function gettypeforbind($var) {
//        $ret = 's';
//        $type = gettype($var);
//        if($type == 'string') {
//            $ret = 's';
//        } else if($type == 'integer') {
//            $ret = 'i';
//        } 
//        return $ret;
//    }
}

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

function getBillets($db, $nombreDeBillets) {
    $billets = array();
    $parametreNbBillets = (int)$nombreDeBillets; // peut mieux faire // TODO
    $nbBillets = $parametreNbBillets <= 0 ? 5 : $parametreNbBillets;

    // On récupère les 5 derniers billets
    $statement = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr '
            . 'FROM billets ORDER BY date_creation DESC LIMIT 0, ' . $nbBillets;
    //$statement = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%y à %Hh%imin%ss\') AS date_creation_fr '
    //        . 'FROM billets ORDER BY date_creation DESC LIMIT 0, 10';
    $query = $db->query($statement);   
    
    $nbComments = getNbComments($db);
    
    while($data = $query->fetch()) {
        $billet = array(
            'id' => $data['id'],
            'titre' => htmlspecialchars($data['titre']),
            'contenu' => htmlspecialchars($data['contenu']),
            'date_creation' => $data['date_creation_fr'],
            'nbComments' => isset($nbComments[$data['id']]) ? $nbComments[$data['id']] : 0
        );
        array_push($billets, $billet);
    }
    $query->closeCursor();
    
    return $billets;
}

function getNbComments($db) {
    $nbComments = array();
    $sql = 'SELECT id_billet, count(*) as nombreCommentaires FROM commentaires group by id_billet';
    $q = $db->query($sql);
    while($d = $q->fetch()) {
        $nbComments[$d['id_billet']] = $d['nombreCommentaires'];
    }
    $q->closeCursor();  
    return $nbComments;
}

function getBillet($db, $idBillet) {
    // Récupération du billet
    $queryBillets = $db->prepare('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets WHERE id = ?');
    $queryBillets->execute(array($idBillet));
    $data = $queryBillets->fetch();    
    
    $billet = array(
        'id' => $data['id'],
        'titre' => htmlspecialchars($data['titre']),
        'contenu' => htmlspecialchars($data['contenu']),
        'date_creation' => $data['date_creation_fr']
    );
    
    return $billet;
}

function getComments($db, $idBillet) {
    // Récupération des commentaires
    $query = $db->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire');
    $query->execute(array($idBillet));

    $comments = array();
    while ($data = $query->fetch())
    {
        array_push($comments, array(
            'auteur' => htmlspecialchars($data['auteur']),
            'date_commentaire' => $data['date_commentaire_fr'],
            'commentaire' => htmlspecialchars($data['commentaire'])
        ));
    } // Fin de la boucle des commentaires
    $query->closeCursor();
    
    return $comments;
}

function insertCommentaire($db, $idBillet, $auteur, $commentaire) {
    $statement = 'INSERT INTO commentaires (id, id_billet, auteur, commentaire, date_commentaire) VALUES (NULL,?,?,?,now())';
    // TODO securiser les entrees pour ne pas mettre de html et/ou javascript dans les values
    $query = $db->prepare($statement);
    $query->execute(array($idBillet, $auteur, $commentaire));
}
<?php

function getBillets(PDO $db, $nombreDeBillets) {
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

function getNbComments(PDO $db) {
    $nbComments = array();
    $sql = 'SELECT id_billet, count(*) as nombreCommentaires FROM commentaires group by id_billet';
    $q = $db->query($sql);
    while($d = $q->fetch()) {
        $nbComments[$d['id_billet']] = $d['nombreCommentaires'];
    }
    $q->closeCursor();  
    return $nbComments;
}

function getBillet(PDO $db, $idBillet) {
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

function getComments(PDO $db, $idBillet) {
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

function insertCommentaire(PDO $db, $idBillet, $auteur, $commentaire) {
    $statement = 'INSERT INTO commentaires (id, id_billet, auteur, commentaire, date_commentaire) VALUES (NULL,?,?,?,now())';
    // TODO securiser les entrees pour ne pas mettre de html et/ou javascript dans les values
    $query = $db->prepare($statement);
    $query->execute(array($idBillet, $auteur, $commentaire));
}
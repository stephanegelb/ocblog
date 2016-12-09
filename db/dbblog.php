<?php

include 'iblog.php';

class blog implements iblog {
    private $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
    }
    
    function getBilletsArray($nombreDeBillets) {
        $billets = array();
        $parametreNbBillets = (int)$nombreDeBillets; // peut mieux faire // TODO
        $nbBillets = $parametreNbBillets <= 0 ? 5 : $parametreNbBillets;

        // On récupère les 5 derniers billets
        $statement = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr '
                . 'FROM billets ORDER BY date_creation DESC LIMIT 0, ' . $nbBillets;
        //$statement = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%y à %Hh%imin%ss\') AS date_creation_fr '
        //        . 'FROM billets ORDER BY date_creation DESC LIMIT 0, 10';
        $query = $this->db->query($statement);   

        $nbComments = $this->getNbComments();

        while($data = $query->fetch()) {
    //        $billet = array(
    //            'id' => $data['id'],
    //            'titre' => htmlspecialchars($data['titre']),
    //            'contenu' => htmlspecialchars($data['contenu']),
    //            'date_creation' => $data['date_creation_fr'],
    //            'nbComments' => isset($nbComments[$data['id']]) ? $nbComments[$data['id']] : 0
    //        );
            $billet = new billet();
            $billet->id = $data['id'];
            $billet->titre = htmlspecialchars($data['titre']);
            $billet->contenu = htmlspecialchars($data['contenu']);
            $billet->date_creation = $data['date_creation_fr'];
            $billet->nbComments = isset($nbComments[$data['id']]) ? $nbComments[$data['id']] : 0;

            array_push($billets, $billet);
        }
        $query->closeCursor();

        return $billets;
    }

    function getBilletsWithNbCmts($numberOfBillets) {
        $parametreNbBillets = (int)$numberOfBillets; // peut mieux faire // TODO
        $nbBillets = $parametreNbBillets <= 0 ? 5 : $parametreNbBillets;

    //    $statement = 'SELECT b.id as id, b.titre as titre, b.contenu as contenu, .'
    //            . 'DATE_FORMAT(b.date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr, .'
    //            . 'COUNT(commentaires.id_billets) as nbComments '
    //            . 'FROM billets as b LEFT JOIN commentaires GROUP BY commentaires.id_billets ORDER BY date_creation DESC LIMIT 0, ' . $nbBillets;
        $statement = 'SELECT b.id, b.titre, b.contenu, '
                . 'DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr '
                . 'FROM billets as b LEFT JOIN commentaires as c ON b.id = c.id_billet, '
                . 'COUNT(c.id_billets) as nbComments GROUP BY c.id_billets ORDER BY date_creation DESC LIMIT 0, ' . $nbBillets;
        $statement = 'select billets.*, '
                . 'count(commentaires.id_billet) as nbComments '
                . 'from billets as b left join commentaires on billets.id = commentaires.id_billet '
                . 'group by commentaires.id_billet ';   
        $statement = 'select billets.id, billets.titre, billets.contenu, '
                . 'DATE_FORMAT(billets.date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr, '
                . 'count(commentaires.id_billet) as nbComments '
                . 'from billets left join commentaires on billets.id = commentaires.id_billet '
                . 'group by commentaires.id_billet';

        $sth = $this->db->prepare($statement); 
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_CLASS, 'billet');
        return $data;
    }

    function getNbBillets() {
        $sql = 'select count(*) from billets';
        $sth = $this->db->prepare($sql); 
        $sth->execute();
        $data = $sth->fetchColumn();
        return $data;    
    }
    
    function getAllBillets() {
        $sql = 'select * from billets';
        $sth = $this->db->prepare($sql); 
        $sth->execute();
        $data = $sth->fetchAll(PDO::FETCH_CLASS, 'billet');
        return $data;    
    }

    //function getCommentsObj(PDO $pdo) {
    //    $statement = 'select * from commentaires';
    //    $sth = $pdo->prepare($statement); 
    //    $sth->execute();
    //    $data = $sth->fetchAll(PDO::FETCH_CLASS);
    //    return $data;    
    //}

    function getOneBillet($idBillet) {
        $sql = 'SELECT id, titre, contenu, '
                . 'DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr '
                . 'FROM billets WHERE id = ?';
        // Récupération du billet
        $query = $this->db->prepare($sql);
        $query->execute(array($idBillet));
        $data = $query->fetchAll(PDO::FETCH_CLASS, 'billet');
        return count($data) === 1 ? $data[0] : null;
    }
    
    function insertBillet(billet $billet) {
        $sql = 'INSERT INTO billets (titre,contenu,date_creation)'
                . ' VALUES (?,?,now())';
        $statement = $this->db->prepare($sql);
        $statement->execute(array($billet->titre,$billet->contenu));
    }
    
    function deleteBillet($idBillet) {
        $sql = "DELETE FROM billets WHERE id=".$idBillet;
        $this->db->exec($sql);
    }

    function getAllComments() {
        return $this->getComments();
    }
    
    function getNbComments() {
        $nbComments = array();
        $sql = 'SELECT id_billet, count(*) as nombreCommentaires FROM commentaires group by id_billet';
        $q = $this->db->query($sql);
        while($d = $q->fetch()) {
            $nbComments[$d['id_billet']] = $d['nombreCommentaires'];
        }
        $q->closeCursor();  
        return $nbComments;
    }
    
    function getOneComment($idComment) {
        // Récupération du billet
        $query = $this->db->prepare('SELECT id, id_billet, auteur, commentaire, '
                . 'DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr '
                . 'FROM billets WHERE id = ?');
        $query->execute(array($idComment));
        $data = $query->fetchAll(PDO::FETCH_CLASS, 'comment');
        return count($data) === 1 ? $data[0] : null;        
    }

    function getCommentsByBillet($idBillet) {
        return $this->getComments($idBillet);
    }
    
    // getComments
    // if idBillet is null, return all comments for all billets
    // if idBillet specified, return comments for the corresponding billet
    private function getComments($idBillet = null) {
        // Récupération des commentaires
        $sql = 'SELECT id, id_billet, auteur, commentaire, '
                . 'DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr '
                . 'FROM commentaires ';
        if($idBillet !== null) {
            $sql .= 'WHERE id_billet = ? ORDER BY date_commentaire';
        }
        $statement = $this->db->prepare($sql);
        $statement->execute(array($idBillet));
        $data = $statement->fetchAll(PDO::FETCH_CLASS, 'comment');
        return $data;
    }

    function insertComment(comment $comment) {
        $sql = 'INSERT INTO commentaires (id, id_billet, auteur, commentaire, date_commentaire) '
                . 'VALUES (NULL,?,?,?,now())';
        // TODO securiser les entrees pour ne pas mettre de html et/ou javascript dans les values
        $statement = $this->db->prepare($sql);
        $statement->execute(array($comment->id_billet, $comment->auteur, $comment->commentaire));
    }
       
    function deleteComment($idComment) {
        $sql = "DELETE FROM commentaires WHERE id=".$idComment;
        $this->db->exec($sql);
    }
}
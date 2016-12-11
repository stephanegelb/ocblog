<?php

include_once __DIR__.'\iblog.php';

abstract class blog implements iblog {
    protected $db;
    
    public function __construct(iDb $db) {
        $this->db = $db;
    }

    protected abstract function fetch($statement);
    
    function getBilletsArray($nombreDeBillets) {
        $billets = array();
        $parametreNbBillets = (int)$nombreDeBillets; // peut mieux faire // TODO
        $nbBillets = $parametreNbBillets <= 0 ? 5 : $parametreNbBillets;

        $nbCommentsByBillet = $this->getNbCommentsPerBillet();

        // On récupère les 5 derniers billets
        $sql = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr '
                . 'FROM billets ORDER BY date_creation DESC LIMIT 0, ' . $nbBillets;
        //$statement = 'SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%y à %Hh%imin%ss\') AS date_creation_fr '
        //        . 'FROM billets ORDER BY date_creation DESC LIMIT 0, 10';
        $statement = $this->db->query($sql);   
        while($data = $statement->fetch()) {
            $billet = new billet();
            $billet->id = $data['id'];
            $billet->titre = htmlspecialchars($data['titre']);
            $billet->contenu = htmlspecialchars($data['contenu']);
            $billet->date_creation = $data['date_creation_fr'];
            $billet->nbComments = isset($nbCommentsByBillet[$data['id']]) ? $nbCommentsByBillet[$data['id']] : 0;
            array_push($billets, $billet);
        }
        $statement->closeCursor();

        return $billets;
    }
    
    function getBilletsWithNbCmts($numberOfBillets) {
        $parametreNbBillets = (int)$numberOfBillets; // peut mieux faire // TODO
        $nbBillets = $parametreNbBillets <= 0 ? 5 : $parametreNbBillets;

    //    $statement = 'SELECT b.id as id, b.titre as titre, b.contenu as contenu, .'
    //            . 'DATE_FORMAT(b.date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr, .'
    //            . 'COUNT(commentaires.id_billets) as nbComments '
    //            . 'FROM billets as b LEFT JOIN commentaires GROUP BY commentaires.id_billets ORDER BY date_creation DESC LIMIT 0, ' . $nbBillets;
        $sql = 'SELECT b.id, b.titre, b.contenu, '
                . 'DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr '
                . 'FROM billets as b LEFT JOIN commentaires as c ON b.id = c.id_billet, '
                . 'COUNT(c.id_billets) as nbComments GROUP BY c.id_billets ORDER BY date_creation DESC LIMIT 0, ' . $nbBillets;
        $sql = 'select billets.*, '
                . 'count(commentaires.id_billet) as nbComments '
                . 'from billets as b left join commentaires on billets.id = commentaires.id_billet '
                . 'group by commentaires.id_billet ';   
        $sql = 'select billets.id, billets.titre, billets.contenu, '
                . 'DATE_FORMAT(billets.date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr, '
                . 'count(commentaires.id_billet) as nbComments '
                . 'from billets left join commentaires on billets.id = commentaires.id_billet '
                . 'group by commentaires.id_billet';

        $data = $this->db->fetchAll($sql, null, 'billet'); 
        return $data;
    }

    function getNbBillets() {
        $sql = 'select count(*) from billets';
        $data = $this->db->fetchColumn($sql); 
        return $data;    
    }
    
    function getAllBillets() {
        $sql = 'select * from billets';
        $data = $this->db->fetchAll($sql, null, 'billet');
        return $data;    
    }

    function getOneBillet($idBillet) {
        $sql = 'SELECT id, titre, contenu, '
                . 'DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr '
                . 'FROM billets WHERE id = ?';
        // Récupération du billet
        $data = $this->db->fetchAll($sql, array($idBillet), 'billet');
        return count($data) === 1 ? $data[0] : null;
    }
    
    function insertBillet(billet $billet) {
        $sql = 'INSERT INTO billets (titre,contenu,date_creation)'
                . ' VALUES (?,?,now())';
        $statement = $this->db->fetchAll($sql, array($billet->titre,$billet->contenu));
    }
    
    function deleteBillet($idBillet) {
        $sql = "DELETE FROM billets WHERE id=".$idBillet;
        $this->db->exec($sql);
    }

    function getAllComments() {
        return $this->getComments();
    }
    
    function getNbComments() {
        $sql = 'select count(*) from commentaires';
        $data = $this->db->fetchColumn($sql); 
        return $data;    
    }
    
    function getNbCommentsPerBillet() {
        $nbComments = array();
        $sql = 'SELECT id_billet, count(*) as nombreCommentaires FROM commentaires group by id_billet';
        $statement = $this->db->query($sql);
        while($d = $statement->fetch()) {
            $nbComments[$d['id_billet']] = $d['nombreCommentaires'];
        }
        $statement->closeCursor();  
        return $nbComments;
    }
    
    function getOneComment($idComment) {
        // Récupération du billet
        $sql = 'SELECT id, id_billet, auteur, commentaire, '
                . 'DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr '
                . 'FROM billets WHERE id = ?';
        $data = $this->db->fetchAll($sql, array($idComment), 'comment');
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
        $data = $this->db->fetchAll($sql, array($idBillet), 'comment');
        return $data;
    }

    function insertComment(comment $comment) {
        $sql = 'INSERT INTO commentaires (id, id_billet, auteur, commentaire, date_commentaire) '
                . 'VALUES (NULL,?,?,?,now())';
        // TODO securiser les entrees pour ne pas mettre de html et/ou javascript dans les values
        $this->db->exec($sql, array($comment->id_billet, $comment->auteur, $comment->commentaire));
    }
       
    function deleteComment($idComment) {
        $sql = "DELETE FROM commentaires WHERE id=".$idComment;
        $this->db->exec($sql);
    }
}
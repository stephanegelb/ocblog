<?php

//require_once __DIR__.'\iblog.php';

abstract class dbblog implements iblog {
    protected $db;
    
    public function __construct(iDb $db) {
        $this->db = $db;
    }

    function getBilletsWithNbCmts($numberOfBillets=-1, $offset=-1) {
        $sql = 'select billets.id, billets.titre, billets.contenu, '
                . 'DATE_FORMAT(billets.date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr, '
                . 'count(commentaires.id_billet) as nbComments '
                . 'from billets left join commentaires on billets.id = commentaires.id_billet '
                . 'group by billets.id, commentaires.id_billet DESC';
        
        if(isset($numberOfBillets) && is_int($numberOfBillets) && $numberOfBillets>-1) {
            if(isset($offset) && is_int($offset)) {
                $offset = $offset<0 ? 0 : $offset;
                $sql .= " LIMIT ".$offset.",".$numberOfBillets;
            }
        }

        $className = get_class(new billet());
        $data = $this->db->fetchAll($sql, null, $className); 
        return $data;
    }

    function getNbBillets() {
        $sql = 'select count(*) from billets';
        $data = $this->db->fetchColumn($sql); 
        return $data;    
    }
    
    function getAllBillets() {
        $sql = 'select * from billets';
        $className = get_class(new billet());
        $data = $this->db->fetchAll($sql, null, $className);
        return $data;    
    }

    function getOneBillet($idBillet) {
        $sql = 'SELECT billets.id, billets.titre, billets.contenu, '
                . 'DATE_FORMAT(billets.date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr, '
                . 'count(commentaires.id_billet) as nbComments '
                . 'from billets left join commentaires on billets.id = commentaires.id_billet '
                . 'where billets.id = ? '
                . 'group by billets.id, commentaires.id_billet DESC';
        // Récupération du billet
        $className = get_class(new billet());
        $data = $this->db->fetchAll($sql, array($idBillet), $className);
        return count($data) === 1 ? $data[0] : null;
    }
    
    function insertBillet(billet $billet) {
        $sql = 'INSERT INTO billets (titre,contenu,date_creation)'
                . ' VALUES (?,?,now())';
        $this->db->exec($sql, array($billet->titre,$billet->contenu));
    }
    
    function deleteBillet($idBillet) {
        $sql = "DELETE FROM billets WHERE id=?";
        $this->db->exec($sql, array($idBillet));
    }

    function getAllComments() {
        return $this->getComments();
    }
    
    function getNbComments() {
        $sql = 'select count(*) from commentaires';
        $data = $this->db->fetchColumn($sql); 
        return $data;    
    }
      
    function getOneComment($idComment) {
        // Récupération du billet
        $sql = 'SELECT id, id_billet, auteur, commentaire, '
                . 'DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr '
                . 'FROM commentaires WHERE id = ?';
        $className = get_class(new comment());
        $data = $this->db->fetchAll($sql, array($idComment), $className);
        return count($data) === 1 ? $data[0] : null;        
    }

    function getCommentsByBillet($idBillet, $numberOfComments = null, $offset = null) {
        return $this->getComments($idBillet,$numberOfComments,$offset);
    }
    
    // getComments
    // if idBillet is null, return all comments for all billets
    // if idBillet specified, return comments for the corresponding billet
    private function getComments($idBillet = null, $numberOfComments = null, $offset = null) {
        // Récupération des commentaires
        $sql = 'SELECT id, id_billet, auteur, commentaire, '
                . 'DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaire_fr '
                . 'FROM commentaires ';
        
        if($idBillet !== null) {
            $sql .= 'WHERE id_billet = '.$idBillet.' ORDER BY date_commentaire';
        }
        
        if(isset($numberOfComments) && is_int($numberOfComments) && $numberOfComments>-1) {
            if(isset($offset) && is_int($offset)) {
                $offset = $offset<0 ? 0 : $offset;
                $sql .= " LIMIT ".$offset.",".$numberOfComments;
            }
        }
        
        $className = get_class(new comment());
        $data = $this->db->fetchAll($sql, null, $className);
        return $data;
    }

    function insertComment(comment $comment) {
        $sql = 'INSERT INTO commentaires (id, id_billet, auteur, commentaire, date_commentaire) '
                . 'VALUES (NULL,?,?,?,now())';
        // TODO securiser les entrees pour ne pas mettre de html et/ou javascript dans les values
        $this->db->exec($sql, array($comment->id_billet, $comment->auteur, $comment->commentaire));
    }
       
    function deleteComment($idComment) {
        $sql = "DELETE FROM commentaires WHERE id=?";
        $this->db->exec($sql, array($idComment));
    }
}
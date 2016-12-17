<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class comment
{
    public $id;
    public $id_billet;
    public $auteur;
    public $commentaire;
    public $date_commentaire_fr;
    
    public function __construct($idBillet=null, $auteur=null, $commentaire=null) {
        if(!is_null($idBillet) && is_int($idBillet)) {
            $this->id_billet = $idBillet;
        }
        if($auteur!==null && strlen($auteur)>0) {
            $this->auteur = $auteur;
        }
        if($commentaire!==null && strlen($commentaire)>0) {
            $this->commentaire = $commentaire;
        }
    }
};
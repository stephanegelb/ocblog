<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class billet
{
    public $id;
    public $titre;
    public $contenu;
    public $date_creation_fr;
    public $nbComments;
    
    public function __construct($titre=null, $contenu=null) {
        if($titre!==null && strlen($titre)>0) {
            $this->titre = $titre;
        }
        if($contenu!==null && strlen($contenu)>0) {
            $this->contenu = $contenu;
        }
    }
};
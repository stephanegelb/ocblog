<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// TODO a revoir avec autoload et spl_autoload ou namespace ou kekchose
//require_once __DIR__.'\..\models\billet.php';
//require_once __DIR__.'\..\models\comment.php';

interface iblog {
    function getAllBillets();
    function getNbBillets();
    function getOneBillet($idBillet);
    //function getBilletsArray($numberOfBillets);
    function getBilletsWithNbCmts( $numberOfBillets=-1, $offset=-1);
    function insertBillet(billet $billet);
    function deleteBillet($idBillet);

    function getAllComments();
    function getNbComments();
    function getOneComment($idComment);
    function getCommentsByBillet($idBillet);
    function insertComment(comment $comment);  
    function deleteComment($idComment);
};

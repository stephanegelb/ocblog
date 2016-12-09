<?php

$idBillet = filter_input(INPUT_POST, 'idbillet');
$auteur = filter_input(INPUT_POST, 'auteur');
$commentaire = filter_input(INPUT_POST, 'commentaire');

if($idBillet != null && $auteur != null && $commentaire != null) {
    if(strlen($auteur) > 0 && strlen($commentaire) > 0) {
        include 'db/db.php';
        $db = getDb();
        include 'db/dbblog.php';
        $blog = new blog($db);
        
        $comment = new comment();
        $comment->id_billet = $idBillet;
        $comment->auteur = $auteur;
        $comment->commentaire = $commentaire;
        $blog->insertComment($comment);
    }
}

header('Location: commentaires.php?billet='.$idBillet);
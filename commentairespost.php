<?php

$idBillet = filter_input(INPUT_POST, 'idbillet');
$auteur = filter_input(INPUT_POST, 'auteur');
$commentaire = filter_input(INPUT_POST, 'commentaire');

if($idBillet != null && $auteur != null && $commentaire != null) {
    if(strlen($auteur) > 0 && strlen($commentaire) > 0) {
        // get db
        require('autoload.php');
        $blog = dbblogfactory::getdbblog();
        
        $comment = new comment();
        $comment->id_billet = (int)$idBillet;
        $comment->auteur = htmlspecialchars($auteur);
        $comment->commentaire = htmlspecialchars($commentaire);

        $blog->insertComment($comment);
    }
}

header('Location: commentaires.php?billet='.$idBillet);
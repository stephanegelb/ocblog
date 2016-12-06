<?php

$id = filter_input(INPUT_POST, 'id');
$auteur = filter_input(INPUT_POST, 'auteur');
$commentaire = filter_input(INPUT_POST, 'commentaire');

if($id != null && $auteur != null && $commentaire != null) {
    if(strlen($auteur) > 0 && strlen($commentaire) > 0) {
        include 'db/db.php';
        $db = getDb();
        include 'dbblog.php';
        insertCommentaire($db, $id, $auteur, $commentaire);
    }
}

header('Location: commentaires.php?billet='.$id);
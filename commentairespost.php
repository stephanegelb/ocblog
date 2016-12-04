<?php

$id = filter_input(INPUT_POST, 'id');
$auteur = filter_input(INPUT_POST, 'auteur');
$commentaire = filter_input(INPUT_POST, 'commentaire');

if($id != null && $auteur != null && $commentaire != null) {
    if(strlen($auteur) > 0 && strlen($commentaire) > 0) {
        include 'dbblog.php';
        
        $db = getDb();
        insertCommentaire($db, $id, $auteur, $commentaire);
    }
}

header('Location: commentaires.php?billet='.$id);
<!DOCTYPE html>
<?php

?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Mon blog</title>
	<link href="style.css" rel="stylesheet" /> 
    </head>
        
    <body>
        <h1>Mon super blog !</h1>
        <p><a href="index.php">Retour à la liste des billets</a></p>

        <?php
        include('dbblog.php');

        function afficheBillet($billet) {
            $chaine = '<div class="news">'
                    .'<h3>'.$billet['titre'].' <em>le '.$billet['date_creation'].'</em></h3>'
                    .'<p>'.nl2br($billet['contenu']).'</p>'
                    .'</div>';
            echo $chaine;
        }

        function afficheCommentaire($commentaire) {
            echo '<p><strong>'.$commentaire['auteur'].'</strong> le '.$commentaire['date_commentaire'].'</p>';
            echo '<p>'.$commentaire['commentaire'].'</p>';
        }
        
        $idbillet = (int)filter_input(INPUT_GET, 'billet');
        $db = getDb();
        $billet = getBillet($db, $idbillet);
        afficheBillet($billet);
        $commentaires = getComments($db, $idbillet);
        $nbCommentaires = count($commentaires);
        ?>

        <h2><?php echo $nbCommentaires ?> Commentaire<?php echo($nbCommentaires>1 ? 's' : ''); ?></h2>
        <?php
        if($nbCommentaires == 0) {
            echo('pas de commentaires pour ce billet');
        } else {
            foreach($commentaires as $commentaire) {
                afficheCommentaire($commentaire);
            }
        }
        ?>
        
        <h2>Nouveau commentaire sur billet <?php echo $idbillet; ?></h2>
        <form action="commentairespost.php" method="post">
            <p>
                <input type="hidden" name="id" id="id" value='<?php echo $idbillet; ?>'/>
                <label for="auteur">Auteur : </label><input type="text" name="auteur" id="auteur"/><br>
                <label for="commentaire">Commentaire : </label><input type="text" name="commentaire" id="commentaire"/><br>
                <input type="submit" value="Envoyer"/>
            </p>
        </form>
    </body>
</html>
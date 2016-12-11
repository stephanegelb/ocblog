<!DOCTYPE html>
<?php

?>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>Mon blog</title>
        <link rel='stylesheet' href='style.css'/> 
    </head>
        
    <body>
        <h1>Mon super blog !</h1>
        <p><a href="index.php">Retour à la liste des billets</a></p>

        <?php
        include('db/dbblogfactory.php');
        $blog = getdbblog();

        function displayBillet(billet $billet) {
            $chaine = '<div class="news">'
                    .'<h3>'.$billet->titre.' <em>le '.$billet->date_creation_fr.'</em></h3>'
                    .'<p>'.nl2br($billet->contenu).'</p>'
                    .'</div>';
            echo $chaine;
        }

        function displayComment(comment $comment) {
            echo '<p><strong>'.$comment->auteur.'</strong> le '.$comment->date_commentaire_fr.'</p>';
            echo '<p>'.$comment->commentaire.'</p>';
        }
        
        // display the billet specified by id
        $idbillet = (int)filter_input(INPUT_GET, 'billet');
        $billet = $blog->getOneBillet($idbillet);
        displayBillet($billet);
        
        $comments = $blog->getCommentsByBillet($idbillet);
        $nbComments = count($comments);
        $strComments = $nbComments.' Commentaire'.($nbComments>1 ? 's' : '');
        ?>

        <!--display the comments of this billet-->
        <h2><?php echo $strComments ?></h2>
        <?php
        if($nbComments == 0) {
            echo('pas de commentaires pour ce billet');
        } else {
//            foreach($commentaires as $commentaire) {
//                displayComment($commentaire);
//            }
        ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>auteur</th>
                        <th>date</th>
                        <th>commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($comments as $comment)
                    {
                    ?>
                    <tr>
                        <td><?php echo $comment->auteur;?></td>
                        <td><?php echo $comment->date_commentaire_fr;?><td>
                        <td><?php echo $comment->commentaire;?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
        ?>
        
        
        <?php
        // pagination
        $nbPages = $nbComments>0 ? floor($nbComments/5) + 1 : 1;
        if($nbPages > 1) 
        {
        ?>
        <ul class='pagination pagination-lg'>
            <?php for($page=1; $page<=$nbPages; $page++) { ?>
            <li><a href='#'><?php echo $page; ?></a></li>
            <?php } ?>
        </ul>
        <?php
        }
        ?>
        
        <!-- form to input a new comment-->
        <h2>Nouveau commentaire sur billet <?php echo $idbillet; ?></h2>
        <form action="commentairespost.php" method="post">
            <p>
                <input type="hidden" name="idbillet" id="idbillet" value='<?php echo $idbillet; ?>'/>
                <label for="auteur">Auteur : </label><input type="text" name="auteur" id="auteur"/><br>
                <label for="commentaire">Commentaire : </label><textarea name="commentaire" id="commentaire"></textarea><br>
                <input type="submit" value="Envoyer"/>
            </p>
        </form>
    </body>
</html>
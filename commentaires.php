<?php
session_start();
$idBillet = null;
$nbCommentsPerPage = 5;
$page = null;

define('BILLET', 'billet');
define('PAGE', 'p');
define('NB', 'nb');
define('NbCommentsPerPage', 'nbCommentsPerPage');

// billet id to display
$idbillet = (int)filter_input(INPUT_GET, BILLET);
// number of comments per page can be saved in session if user enter it manually in the url
if(isset($_SESSION[NbCommentsPerPage])) {
    $nbCommentsPerPage = intval($_SESSION[NbCommentsPerPage]);   
}
// the page num to display for the comments
if(isset($_GET['p'])) {
    $page = intval($_GET['p']);
}
// the number of comments to display per page
if(isset($_GET['nb'])) {
    $nb = intval($_GET['nb']);
    if($nb>0 && $nb != $nbCommentsPerPage) {
        $nbCommentsPerPage = $nb;
        $_SESSION[NbCommentsPerPage] = $nbCommentsPerPage;
    }
}
?>
<!DOCTYPE html>
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
        require('autoload.php');
        $blog = dbblogfactory::getdbblog();
        $billet = $blog->getOneBillet($idbillet);
        $offset = $page * $nbCommentsPerPage;
        $comments = $blog->getCommentsByBillet($idbillet, $nbCommentsPerPage, $offset);
        $nbComments = $billet->nbComments;
        $strNbComments = $nbComments.' Commentaire'.($nbComments>1 ? 's' : '');
        $nbPages = $nbComments>0 ? ceil($nbComments/$nbCommentsPerPage): 0;

//        function displayBillet(billet $billet) {
//            $chaine = '<div class="news">'
//                    .'<h3>'.$billet->titre.' <em>le '.$billet->date_creation_fr.'</em></h3>'
//                    .'<p>'.nl2br($billet->contenu).'</p>'
//                    .'</div>';
//            echo $chaine;
//        }

//        function displayComment(comment $comment) {
//            echo '<p><strong>'.$comment->auteur.'</strong> le '.$comment->date_commentaire_fr.'</p>';
//            echo '<p>'.$comment->commentaire.'</p>';
//        }
        
        // display the billet specified by id
        //displayBillet($billet);        
        ?>
        <div class="news">
            <h3><?php echo $billet->titre.' <em>le '.$billet->date_creation_fr.'</em>'; ?></h3>
            <p><?php echo nl2br($billet->contenu); ?></p>
        </div>

        <?php // display the comments of this billet
        ?>         
        <h2><?php echo $strNbComments ?></h2>
        <?php
        if($nbComments == 0) {
            echo('pas de commentaires pour ce billet');
        } else {
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
        if($nbPages > 1) 
        {
        ?>
        <ul class='pagination pagination-lg'>
            <?php 
            for($page=1; $page<=$nbPages; $page++) { 
                $link = sprintf('commentaires.php?%s=%d&%s=%d&%s=%d', 
                        BILLET, $idbillet, NB, $nbCommentsPerPage, PAGE, $page-1);                
            ?>
            <li><a href='<?php echo $link; ?>'><?php echo $page; ?></a></li>
            <?php 
            } 
            ?>
        </ul>
        <?php
        }
        ?>
        
        <!-- form to input a new comment-->
        <h2>Nouveau commentaire sur billet <?php echo $idbillet; ?></h2>
        <form action="commentairespost.php" method="post" onsubmit="return verification()">
            <p>
                <input type="hidden" name="idbillet" id="idbillet" value='<?php echo $idbillet; ?>'/>
                <label for="auteur">Auteur : </label><input type="text" name="auteur" id="auteur"/><br>
                <label for="commentaire">Commentaire : </label><textarea name="commentaire" id="commentaire"></textarea><br>
                <input type="submit" value="Envoyer"/>
            </p>
        </form>
        <script>
            function verification() {
                if($('#auteur').value().length === 0) {
                    return false;
                }
                if($('#commentaire').value().length === 0) {
                    return false;
                }
                return true;
            }
        </script>
    </body>
</html>
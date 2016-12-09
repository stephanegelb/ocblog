
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>Administration de mon blog</title>
        <link rel='stylesheet' href='style.css'/> 
    </head>
    <body>
        <div id="container-fluid">
            <h1>Administration de mon blog</h1>
            <?php
            $path = __FILE__;
            echo "filename and path of this file: ".$path;

            include_once('../db/db.php');
            $db = getDb();
            include_once('../db/dbblog.php');
            $blog = new blog($db);

            echo '<br>Nombre de billets dans la base : '.$blog->getNbBillets();
            ?>

            <h2>liste des billets avec comments</h2>
            <?php        
            $arrayBilletWithNbComments = $blog->getBilletsWithNbCmts(10);
            echo count($arrayBilletWithNbComments).' billets avec nbcomments<br>';
            echo '<div id="$arrayBilletWithNbComments" style="display:none;><br><pre>'; 
            echo print_r($arrayBilletWithNbComments); 
            echo '</pre><br></div>';
            ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>titre</th>
                        <th>contenu</th>
                        <th>date</th>
                        <th>nbComments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($arrayBilletWithNbComments as $billet)
                    {
                    ?>
                    <tr>
                        <td><?php echo $billet->id;?></td>
                        <td><?php echo $billet->titre;?></td>
                        <td><?php echo $billet->contenu;?></td>
                        <td><?php echo $billet->date_creation_fr;?><td>
                        <td><?php echo $billet->nbComments;?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <h2>liste des billets</h2>
            <?php
            $arrayBillets = $blog->getAllBillets();
            echo count($arrayBillets).' billets<br>';
            echo '<br><pre>'; print_r($arrayBillets); echo '</pre><br>';
            ?>

            <h2>liste des comments</h2>
            <?php
            $arrayComments = $blog->getAllComments();
            echo count($arrayComments).' comments<br>';
            echo '<div id="$arrayComments" style="display:none;"><br><pre>'; 
            print_r($arrayComments); 
            echo '</pre><br></div>';
            ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>id_billet</th>
                        <th>auteur</th>
                        <th>date</th>
                        <th>commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($arrayComments as $comment)
                    {
                    ?>
                    <tr>
                        <td><?php echo $comment->id;?></td>
                        <td><?php echo $comment->id_billet;?></td>
                        <td><?php echo $comment->auteur;?></td>
                        <td><?php echo $comment->date_commentaire_fr;?><td>
                        <td><?php echo $comment->commentaire;?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>            
            
        </div>
    </body>
</html>
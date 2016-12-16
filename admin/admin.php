<!DOCTYPE html>
<html>
    <?php $title = 'Administration de mon blog'; require_once 'include/head.php'; ?>
    <body>
        <?php require_once 'navbar.php'; ?>

        <div id="container-fluid">
            <h1>Administration de mon blog</h1>
            <?php
            $path = __FILE__;
            echo "filename and path of this file: ".$path.'<br>';

            // get db
            require('../db/dbblogfactory.php');
            $blog = getdbblog();

            echo '<br>Nombre de billets dans la base : '.$blog->getNbBillets();
            echo '<br>Nombre de comments dans la base : '.$blog->getNbComments();
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
            <?php include 'tablebillets.php'; ?>
            
            <h2>liste des comments</h2>
            <?php include 'tablecomments.php'; ?>
            
        </div>
    </body>
</html>
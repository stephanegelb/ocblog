<!DOCTYPE html>
<html>
    <head>
        <meta charset="iso-8859-1" />
        <title>Mon blog</title>
        <link rel='stylesheet' href='style.css'/> 
    </head>
    <body>
        <h1>Mon blog</h1>
        <p>Derniers billets du blog</p>
        <!--<p><a href="admin/admin.php">admin</a></p>-->
        
        <?php
        include('admin/linkadmin.php');
        $str = getLinkAdmin();
        echo $str;
        
        include('db/db.php');
        $db = getDb();        
        include('db/dbblog.php');
        $blog = new blog($db);
    
        $billets = $blog->getBilletsArray(4);
        
        foreach($billets as $billet)
        {
        ?>
        <div class="news">
            <?php 
                $idbillet = $billet->id; 
                $nbCommentaires = $billet->nbComments;
                $strCommentaires = $nbCommentaires.' Commentaire'.($nbCommentaires>1?"s":"");
            ?>
            <h3><?php echo utf8_decode($billet->titre); ?><em><?php echo $billet->date_creation_fr;?></em></h3>
            <p>
                <?php echo nl2br(utf8_decode($billet->contenu)); ?>
                <br>
                <em><a href="commentaires.php?billet=<?php echo $idbillet;?>"><?php echo $strCommentaires?></a></em>
            </p>
        </div>
        <?php
        }
        ?>
    </body>
</html>
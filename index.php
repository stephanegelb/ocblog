
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
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
        include('dbblog.php');

//        $dbCred = new DbCredentials;
//        $cred = $dbCred->getDbCredentialsFromJson();
//        print_r($cred); echo '<br>';
//        $cred = $dbCred->getDbCredentialsFromXml();
//        print_r($cred); echo '<br>';
    
        $billets = getBillets($db, 4);
        
        foreach($billets as $billet)
        {
        ?>
        <div class="news">
            <h3><?php echo $billet['titre']; ?><em><?php echo $billet['date_creation'];?></em></h3>
            <p>
                <?php echo nl2br($billet['contenu']); $idbillet = $billet['id']; $nbCommentaires = $billet['nbComments']?>
                <br>
                <em><a href="commentaires.php?billet=<?php echo $idbillet;?>"><?php echo $nbCommentaires?> Commentaire<?php echo($nbCommentaires>1?'s':'');?></a></em>
            </p>
        </div>
        <?php
        }
        ?>
    </body>
</html>
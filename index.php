<!DOCTYPE html>
<html>
    <head>
        <meta charset="iso-8859-1" />
        <title>Mon blog</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>        
        <link rel='stylesheet' href='style.css'/> 
    </head>
    <body>
        <!-- TODO http://www.w3schools.com/bootstrap/tryit.asp?filename=trybs_navbar_collapse&stacked=h -->
    
        <?php
        //        // show the link for the admin page
        //        include('admin/linkadmin.php');
        //        $str = getLinkAdmin();
        //        echo $str;

        // get db
        include('db/dbblogfactory.php');
        $blog = getdbblog();

        $nbBillets = $blog->getNbBillets();
        $nbBilletsToDisplay = 3;
        $billets = $blog->getBilletsWithNbCmts($nbBilletsToDisplay);
        ?>
        
        <div class="container">
            <h1>Mon blog</h1>
            <p>Derniers billets du blog - <a href="admin/admin.php">admin</a></p>
            <p>Le site contient actuellement <?php echo $nbBillets; ?> billets</p>
            <?php if($nbBillets != $nbBilletsToDisplay) { ?>
            <p>Affichage des <?php echo $nbBilletsToDisplay; ?> billets.</p>
            <?php } ?>
                   
            <?php
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
        </div>
    </body>
</html>
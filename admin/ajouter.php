<?php
$result = "";
if(isset($_POST['titre']) && isset($_POST['contenu'])) {
    require __DIR__.'/../autoload.php';
    $blog = dbblogfactory::getdbblog();
    
    $billet = new billet();
    $billet->titre = htmlspecialchars($_POST['titre']);
    $billet->contenu = htmlspecialchars($_POST['contenu']);
    $blog->insertBillet($billet);
    $result = 'billet '.$billet->titre.' inséré dans la base';
}
?>

<!DOCTYPE html>
<html>
    <?php $title = 'Administration de mon blog'; require_once __DIR__.'/include/head.php'; ?>
    <body>
        <?php require_once __DIR__.'/navbar.php'; ?>

        <div id="container-fluid">
            <form action="ajouter.php" method="post">
                <div class="form-group">
                    <label for="titre" class="control-label col-md-2">Titre : </label>
                    <input type="text" name="titre" id="titre" class="col-md-10"/>
                </div>
                <div class="form-group">
                    <label for="contenu" class="control-label col-md-2">Contenu : </label>
                    <textarea name="contenu" id="contenu" class="col-md-10"></textarea>
                </div>
                <input type="submit" value="Envoyer"/>
            </form>
            <div id="result"><?php echo $result; ?></div>
        </div>
    </body>
</html>

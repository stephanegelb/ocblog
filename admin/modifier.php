<?php
$result = "";
require __DIR__.'/../autoload.php';
$billet = new billet();

if(isset($_POST['titre']) && isset($_POST['contenu']) && isset($_POST['idbillet'])) {
    $blog = dbblogfactory::getdbblog();
    
    $billet->id = intval($_POST['idbillet']);
    $billet->titre = htmlspecialchars($_POST['titre']);
    $billet->contenu = htmlspecialchars($_POST['contenu']);
    if(strlen($billet->titre)>0 && strlen($billet->contenu)>0) {
        $blog->updateBillet($billet);
    }
    $result = 'billet '.$billet->titre.' mis à jour dans la base';
    header('Location: admin.php');
} else if(isset($_GET['billet'])) {
    $blog = dbblogfactory::getdbblog();
    
    $idBillet = intval($_GET['billet']);

    $billet = $blog->getOneBillet($idBillet);
} else {
    header('Location: admin.php');
}
?>

<!DOCTYPE html>
<html>
    <?php $title = 'Administration de mon blog - update'; require_once __DIR__.'/include/head.php'; ?>
    <body>
        <?php require_once __DIR__.'/navbar.php'; ?>

        <div id="container-fluid">
            <h1>Modification du billet <?php echo $idBillet; ?></h1>
            <form action="modifier.php" method="post">
                <div class="form-group">
                    <label for="titre" class="control-label col-md-2">Titre : </label>
                    <input type="text" name="titre" id="titre" class="col-md-10" value="<?php echo $billet->titre; ?>"/>
                </div>
                <div class="form-group">
                    <label for="contenu" class="control-label col-md-2">Contenu : </label>
                    <textarea name="contenu" id="contenu" class="col-md-10"><?php echo $billet->contenu; ?></textarea>
                </div>
                <input type="hidden" name="idbillet" value="<?php echo $idBillet; ?>"/>
                <input type="submit" value="Envoyer"/>
            </form>
            <div id="result"><?php echo $result; ?></div>
        </div>
        <script>
        </script>
    </body>
</html>

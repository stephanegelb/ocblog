<?php
// get db
require __DIR__.'/../autoload.php';
//$blog = dbblogfactory::getdbblog();


if(isset($_POST[dbblogfactory::USEPDOPOST])) {
    $value = $_POST[dbblogfactory::USEPDOPOST];
    if($value === 'true' || $value === 'false') {
        dbblogfactory::setUseDbCookie($value === 'true' ? true : false);
    }
}

if(isset($_GET['ForceSqli'])) {
    dbblogfactory::setUseDbCookie(false);
}

$boolPDO = dbblogfactory::isUsePDO();
$isUsePDO = $boolPDO ? 'true' : 'false';
$isUseSQLI = $boolPDO ? 'false' : 'true';
?>

<!DOCTYPE html>
<html>
    <?php $title = 'Administration de mon blog - PDO or mysqli'; require_once 'include/head.php'; ?>
    <body>
        <?php require_once 'navbar.php'; ?>

        <div id="container-fluid">
            <h1>Administration de mon blog - PDO or mysqli</h1>

            <form action="usePDO.php" method="post">
                <input type="radio" <?php echo 'name="'.USEPDOPOST.'" '; 
                    echo 'value="'.$isUsePDO.'" ';
                    echo $boolPDO ? 'checked="checked"' : ''; ?>>use PDO<br>
                <input type="radio" <?php echo 'name="'.USEPDOPOST.'" '; 
                    echo 'value="'.$isUseSQLI.'" ';
                    echo $boolPDO ? '' : 'checked="checked"'; ?>>use SQLI<br>
                <input type="submit" value="Envoyer">
            </form>      
        </div>
    </body>
</html>
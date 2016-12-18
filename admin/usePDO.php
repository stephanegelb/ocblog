<?php
// get db
require('../db/dbblogfactory.php');
//$blog = getdbblog();

if(isset($_POST[USEPDOPOST])) {
    $value = $_POST[USEPDOPOST];
    if($value === 'true' || $value === 'false') {
        setcookie(USEPDOCOOKIE, $value);
    }
}

$boolPDO = isUsePDO();
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
<!DOCTYPE html>
<html>
    <?php require_once 'include/head.php'; ?>
    <body>
        <?php 
        require_once 'navbar.php'; 
        require __DIR__.'/../autoload.php';
        $blog = dbblogfactory::getdbblog();
        ?>
        <div class='container'>
            <?php require_once 'tablebillets.php'; ?>
        </div>
    </body>
</html>
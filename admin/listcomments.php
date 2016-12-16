<!DOCTYPE html>
<html>
    <?php include_once 'include/head.php'; ?>
    <body>
        <?php 
        include_once 'navbar.php'; 
        include_once '/../db/dbblogfactory.php';
        $blog = getdbblog();
        ?>
        <div class='container'>
            <?php include_once 'tablecomments.php'; ?>
        </div>
    </body>
</html>
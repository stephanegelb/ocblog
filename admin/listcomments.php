<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>Administration de mon blog</title>
        <link rel='stylesheet' href='style.css'/> 
    </head>
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
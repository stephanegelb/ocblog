<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tests</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="../index.php">Mon blog</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active">
        <a href="../index.php" class="btn btn-default btn-sm">
          <span class="glyphicon glyphicon-home"></span> Home
        </a>
      </li>
      <li><a href="admin.php">Admin</a></li>
      <li><a href="#">Page 2</a></li>
      <li><a href="#">Page 3</a></li>
    </ul>
  </div>
</nav>
  
<div class="container">
  <h3>Inverted Navbar</h3>
  <p>An inverted navbar is black instead of gray.</p>
  
  <h3>Tests</h3>
  <form action="tests.php" method="post">
      <input type="radio" name="test" value="InsertBillet" checked="checked">Insert Billet<br>
      <input type="radio" name="test" value="InsertComment">Insert Comment<br>
      <input type="radio" name="test" value="GetNbBillets">GetNbBillets<br>
      <input type="radio" name="test" value="GetNbComments">GetNbComments<br>
      <input type="radio" name="test" value="GetOneBillet">GetOneBillet<br>
      <input type="radio" name="test" value="GetOneComment">GetOneComment<br>
      <input type="submit" value="Submit">      
  </form>
  
  <?php
  
  
  // tests db
  require __DIR__.'/../autoload.php';
  $blog = dbblogfactory::getdbblog();

  if(isset($_POST['test'])) {
      switch ($_POST['test']) {
          case 'InsertBillet': 
            $nbBilletsBefore = $db->getNbBillets(); 
            echo '<br>Nombre de billets avant insertion : ' . $nbBilletsBefore;

            echo '<br>Insertion d\'un billet';
            $billet = new billet('titre '.rand(100, 200).'', 'contenu du billet');
            $db->insertBillet($billet);

            $nbBilletsAfter = $db->getNbBillets();
            echo '<br>Nombre de billets après insertion : ' . $nbBilletsAfter;
            break;
        
          
          case 'InsertComment':
            $nbCommentsBefore = $db->getNbComments(); 
            echo '<br>Nombre de commentaires avant insertion : ' . $nbCommentsBefore;

            echo '<br>Insertion d\'un billet';
            $comment = new comment(1, 'auteur', 'commentaire'.rand(100, 200));
            $db->insertComment($comment);

            $nbCommentsAfter = $db->getNbComments();
            echo '<br>Nombre de commentaires après insertion : ' . $nbCommentsAfter;
            break;
          
        
          case 'GetNbBillets':
              $nbBillets = $db->getNbBillets();
              echo '<br>Nombre de billets : ' . $nbBillets;
              break;

          
          case 'GetNbComments':
              $nbComments = $db->getNbComments();
              echo '<br>Nombre de comments : ' . $nbComments;
              break;
          
          
          case 'GetOneBillet':
              $billet = $db->getOneBillet(1);
              echo '<br>'.var_dump($billet);
              break;
          
          
          case 'GetOneComment':
              $comment = $db->getOneComment(1);
              echo '<br>'.var_dump($comment);
              break;
      }
  }
  ?>
</div>

</body>
</html>
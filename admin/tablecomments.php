<?php
$arrayComments = $blog->getAllComments();
echo count($arrayComments).' comments<br>';
echo '<div id="$arrayComments" style="display:none;"><br><pre>'; 
print_r($arrayComments); 
echo '</pre><br></div>';
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>id</th>
            <th>id_billet</th>
            <th>auteur</th>
            <th>date</th>
            <th>commentaire</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($arrayComments as $comment)
        {
        ?>
        <tr>
            <td><?php echo $comment->id;?></td>
            <td><?php echo $comment->id_billet;?></td>
            <td><?php echo $comment->auteur;?></td>
            <td><?php echo $comment->date_commentaire_fr;?><td>
            <td><?php echo $comment->commentaire;?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>            

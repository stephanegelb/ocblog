<?php
$arrayBillets = $blog->getAllBillets();
echo count($arrayBillets).' billets<br>';
echo '<div id="$arrayBillets" style="display:none;"><br><pre>'; 
print_r($arrayBillets); 
echo '</pre><br></div>';
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>id</th>
            <th>titre</th>
            <th>contenu</th>
            <th>date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($arrayBillets as $billet)
        {
        ?>
        <tr>
            <td><?php echo $billet->id;?></td>
            <td><?php echo $billet->titre;?></td>
            <td><?php echo $billet->contenu;?></td>
            <td><?php echo $billet->date_creation_fr;?><td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>

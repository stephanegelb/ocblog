<?php
$arrayBillets = $blog->getAllBillets();
echo count($arrayBillets).' billets<br>';
echo '<div id="$arrayBillets" style="display:none;"><br><pre>'; 
print_r($arrayBillets); 
echo '</pre><br></div>';
?>
<table class="table table-hover" style="margin:15px;">
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

<button id="bt1" onclick="showPrintr()">Show print_r</button>
<div id="printr" style="display: none;"><?php echo '<pre>'; print_r($arrayBillets); echo '</pre>'; ?></div>
<button id="bt2" onclick="showVarDump()">Show var_dump</button>
<div id="vardump" style="display: none;"><?php var_dump($arrayBillets); ?></div>

<script>
    function showPrintr() {
//        var printr = document.getElementById('printr');
//        var bt = document.getElementById('bt1');
//        if(bt.textContent === 'Show print_r') {
//            bt.textContent = 'Hide print_r';
//            printr.style.display = 'block';
//        } else {
//            bt.textContent = 'Show print_r';
//            printr.style.display = 'none';
//        }
        showOrHide('printr', 'bt1', 'Show print_r', 'Hide print_r');
    };
    function showVarDump() {
        showOrHide('vardump', 'bt2', 'Show var_dump', 'Hide var_dump');
    };
    function showOrHide(elementId, btId, showText, hideText) {
        var printr = document.getElementById(elementId);
        var bt = document.getElementById(btId);
        if(bt.textContent === showText) {
            bt.textContent = hideText;
            printr.style.display = 'block';
        } else {
            bt.textContent = showText;
            printr.style.display = 'none';
        }
    }
</script>
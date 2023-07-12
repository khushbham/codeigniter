<h1>Couponcode: "<?php echo $kortingscode->kortingscode ?>"</h1>
<h2>Gegevens</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
    <tr>
        <th>Couponcode</th>
        <td><?php echo ucfirst($kortingscode->kortingscode) ?></td>
    </tr>
    <tr>
        <th>Percentage</th>
        <td><?php if(!empty($kortingscode->kortingscode_percentage)) echo $kortingscode->kortingscode_percentage . '%'; else echo 'X'; ?></td>
    </tr>
    <tr>
        <th>Vast bedrag</th>
        <td><?php if(!empty($kortingscode->kortingscode_vast_bedrdag)) echo ucfirst($kortingscode->kortingscode_vast_bedrdag); else echo 'X'; ?></td>
    </tr>
    <tr>
        <th>Startdatum</th>
        <td><?php if(!empty($kortingscode->kortingscode_startdatum) && $kortingscode->kortingscode_startdatum != '0000-00-00') echo date('d/m/Y', strtotime($kortingscode->kortingscode_startdatum)); else echo '...'; ?></td>
    </tr>
    <tr>
        <th>Einddatum</th>
        <td><?php if(!empty($kortingscode->kortingscode_einddatum) && $kortingscode->kortingscode_einddatum != '0000-00-00') echo date('d/m/Y', strtotime($kortingscode->kortingscode_einddatum)); else echo '...'; ?></td>
    </tr>
    <tr>
        <th>Limiet</th>
        <td><?php if(!empty($kortingscode->kortingscode_limiet)) echo $kortingscode->kortingscode_limiet; else echo 'X'; ?></td>
    </tr>
    <tr>
        <th>in3 aan</th>
        <td><?php if(!empty($kortingscode->kortingscode_in3)) echo 'Ja'; else echo 'Nee'; ?></td>
    </tr>
</table>
<h2>Gekoppeld aan</h2>
<h3>Kennismakingsworkshops</h3>
<?php if(sizeof($kennismakingsworkshops) > 0): ?>
    <table cellpadding="0" cellspacing="0" class="tabel">
        <tr>
            <th>Naam</th>
        </tr>
        <?php foreach($kennismakingsworkshops as $kennismakingsworkshop): ?>
            <tr>
                <td class="Naam"><?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p><em>Couponcode <?php echo $kortingscode->kortingscode ?> is nog niet gekoppeld aan een kennismakingsworkshop.</em></p>
<?php endif; ?>
<h3>Workshops</h3>
<?php if(sizeof($workshops) > 0): ?>
    <table cellpadding="0" cellspacing="0" class="tabel">
        <tr>
            <th>Naam</th>
        </tr>
        <?php foreach($workshops as $workshop): ?>
            <tr>
                <td class="Naam"><?php echo $workshop->workshop_titel ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p><em>Couponcode <?php echo $kortingscode->kortingscode ?> is nog niet gekoppeld aan een workshop.</em></p>
<?php endif; ?>
<h3>Producten</h3>
<?php if(sizeof($producten) > 0): ?>
    <table cellpadding="0" cellspacing="0" class="tabel">
        <tr>
            <th>Naam</th>
        </tr>
        <?php foreach($producten as $product): ?>
            <tr>
                <td class="Naam"><?php echo $product->product_naam ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p><em>Couponcode <?php echo $kortingscode->kortingscode ?> is nog niet gekoppeld aan een product.</em></p>
<?php endif; ?>

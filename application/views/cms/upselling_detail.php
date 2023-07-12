<h2>Gegevens</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
    <tr>
        <th>Workshop</th>
        <td><?php echo ucfirst($upselling->workshop_titel) ?></td>
    </tr>
    <tr>

<h3>Producten Prijzen</h3>
<?php if(sizeof($producten) > 0): ?>
    <table cellpadding="0" cellspacing="0" class="tabel">
        <tr>
            <th>Naam</th>
            <th>Originele prijs</th>
            <th>korting prijs</th>
        </tr>
        <?php foreach($producten as $product): ?>
            <tr>
                <td class="Naam"><?php echo $product->product_naam ?></td>
                <td class="originele_prijs"><?php echo $product->product_prijs ?></td>
                <td class="korting_prijs"><?php echo $product->upselling_prijs ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

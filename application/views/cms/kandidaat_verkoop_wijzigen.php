<h1>Kandidaat verkoop <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/kandidaat_verkoop/toevoegen/'); else echo base_url('cms/kandidaat_verkoop/wijzigen/'); ?>">
        <input type="checkbox" name="geen_checkbox" onClick="toggle(this)" />Geen workshops of producten<br/>
        <p><h2 style="padding-left:0">Workshops</h2></p>
        <table cellpadding="0" cellspacing="0" border="0" class="tabel">
            <tr>
                <th class="geselecteerd">Geselecteerd</th>
                <th class="Titel">Titel</th>
                <th class="Type">Type</th>
                <th class="Prijs">Prijs</th>
            </tr>
            <input type="checkbox" name="workshops_checkbox" onClick="toggle(this)" />Alle workshops<br/>
            <?php foreach($workshops as $workshop): ?>
                <tr>
                    <td class="geselecteerd"><input type="checkbox" name="geselecteerde_workshops[]" value="<?php echo $workshop->workshop_ID ?>" <?php if(!empty($workshop_connecties)) foreach($workshop_connecties as $workshop_connectie) { if($workshop_connectie->workshop_ID == $workshop->workshop_ID) { echo 'checked'; }} ?>></td>
                    <td class="Workshop_titel"><?php echo $workshop->workshop_titel ?></td>
                    <td class="Workshop_type"><?php echo $workshop->workshop_type ?></td>
                    <td class="workshop_prijs"><?php echo '€' . $workshop->workshop_prijs ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><h2 style="padding-left:0">Producten</h2></p>
        <table cellpadding="0" cellspacing="0" border="0" class="tabel">
            <tr>
                <th class="geselecteerd">Geselecteerd</th>
                <th class="Titel">Product</th>
                <th class="Type">Prijs</th>
            </tr>
            <input type="checkbox" name="producten_checkbox" onClick="toggle(this)" />Alle producten<br/>
            <?php foreach($producten as $product): ?>
                <tr>
                    <td class="geselecteerd"><input type="checkbox" name="geselecteerde_producten[]" value="<?php echo $product->product_ID ?>" <?php if (!empty($product_connecties)) foreach($product_connecties as $product_connectie) { if($product_connectie->product_ID == $product->product_ID) { echo 'checked'; }} ?>></td>
                    <td class="product"><?php echo $product->product_naam ?></td>
                    <td class="prijs"><?php echo '€' . $product->product_prijs ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p class="submit"><input type="submit" value="<?php echo ucfirst($actie) ?>" /> <a href="<?php echo base_url('cms/kortingscodes') ?>" title="Annuleren">Annuleren</a></p>
    </form>
</div>
<h1> korting <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/kortingscodes/upselling_toevoegen/'); else echo base_url('cms/kortingscodes/upselling_wijzigen/'.$item_ID); ?>">
        <p>
            <label for="item_workshop">Workshop</label>
            <select name="item_workshop" id="item_workshop">
                <option value="0">Selecteer workshop</option>
                <?php foreach($workshops as $workshop) { ?>
                    <option value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop_ID == $workshop->workshop_ID) echo 'selected'; ?>><?php echo $workshop->workshop_titel ?></option>
                <?php } ?>
            </select>
        </p>
        <p><h2 style="padding-left:0">Producten</h2></p>
        <table cellpadding="0" cellspacing="0" border="0" class="tabel">
            <tr>
                <th class="Titel">Product</th>
                <th class="Type">Prijs</th>
                <th class="Type">korting prijs</th>
            </tr>

            <?php
            $i = 0;
            foreach($producten as $product): ?>
                <tr>
                    <td class="product"><?php echo $product->product_naam ?></td>
                    <td class="prijs"><?php echo '€' . $product->product_prijs ?></td>
                    <input type="hidden" name="kortingen[<?php echo $i ?>][product_ID]" id="korting_product" value="<?php echo $product->product_ID ?>" />
                    <td><input type="text" name="kortingen[<?php echo $i ?>][prijs]" id="korting_prijs" value="<?php if(!empty($product->upselling_prijs)) { echo $product->upselling_prijs; } else { echo $product->product_prijs; } ?>" /></td>
                </tr>
            <?php $i++; endforeach; ?>
        </table>
        <p class="submit"><input type="submit" value="<?php echo ucfirst($actie) ?>" /> <a href="<?php echo base_url('cms/kortingscodes') ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/kortingscodes/upselling_verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
    </form>
</div>
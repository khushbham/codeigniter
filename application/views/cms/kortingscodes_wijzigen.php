<h1> Couponcode <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/kortingscodes/toevoegen/'); else echo base_url('cms/kortingscodes/wijzigen/'.$item_ID); ?>">
        <p><label for="item_kortingscode">Couponcode *</label><input type="text" name="item_kortingscode" id="item_kortingscode" value="<?php echo $item_kortingscode ?>" /><span class="feedback"><?php echo $item_kortingscode_feedback ?></span></p>
        <p><label for="korting_option">Korting type *</label><input type="radio" name="korting_option" id="korting_percentage" value="korting_percentage" onClick="korting_type()" <?php if(!empty($item_percentage) || (empty($item_vast_bedrag) && empty($item_percentage))) echo 'checked'; ?>/> Percentage <input type="radio" name="korting_option" id="korting_vast_bedrag" value="vast_bedrag"  onClick="korting_type()" <?php if(!empty($item_vast_bedrag)) echo 'checked'; ?> /> Vast bedrag</span></p>
        <div id="percentage" ><p><label for="item_percentage">Korting in %</label><input type="text" name="item_percentage" id="item_percentage" value="<?php echo $item_percentage ?>" /><span class="feedback"><?php echo $item_percentage_feedback ?></span></p></div>
        <div id="vast_bedrag"><p><label for="item_vast_bedrag">Korting in €</label><input type="text" name="item_vast_bedrag" id="item_vast_bedrag" value="<?php echo $item_vast_bedrag ?>" /><span class="feedback"><?php echo $item_vast_bedrag_feedback ?></span></p></div>
        <p><label for="item_limiet">Limiet (optioneel)</label><input type="text" name="item_limiet" id="item_limiet" value="<?php echo $item_limiet ?>" /></p>
        <p><label for="item_datum_dag">Startdatum *</label><input type="text" name="item_datum_dag" id="item_datum_dag" class="datum_smal" value="<?php echo $item_datum_dag ?>" /><input type="text" name="item_datum_maand" id="item_datum_maand" class="datum_smal" value="<?php echo $item_datum_maand ?>" /><input type="text" name="item_datum_jaar" id="item_datum_jaar" class="datum_breed" value="<?php echo $item_datum_jaar ?>" /><span class="feedback"><?php echo $item_datum_feedback ?></span></p>
        <p><label for="item_einddatum_dag">Einddatum (optioneel)</label><input type="text" name="item_einddatum_dag" id="item_einddatum_dag" class="datum_smal" value="<?php echo $item_einddatum_dag ?>" /><input type="text" name="item_einddatum_maand" id="item_einddatum_maand" class="datum_smal" value="<?php echo $item_einddatum_maand ?>" /><input type="text" name="item_einddatum_jaar" id="item_einddatum_jaar" class="datum_breed" value="<?php echo $item_einddatum_jaar ?>" /><span class="feedback"><?php echo $item_einddatum_feedback ?></span></p>
        <p>
            <label for="item_in3">in3 aan *</label>
            <input type="radio" name="item_in3" value="1" <?php if($item_in3 == 1) echo 'checked'; ?> /> Ja
            <input type="radio" name="item_in3" value="0" <?php if($item_in3 == 0) echo 'checked'; ?> /> Nee
        </p>

        <p><h2 style="padding-left:0">Kennismakingsworkshops</h2></p>
        <table cellpadding="0" cellspacing="0" border="0" class="tabel">
            <tr>
                <th class="geselecteerd">Geselecteerd</th>
                <th class="Titel">Titel</th>
                <th class="Prijs">Prijs</th>
            </tr>
            <input type="checkbox" name="kennismakingsworkshops_checkbox" onClick="toggle(this)" />Alle kennismakingsworkshops
            <?php foreach($kennismakingsworkshops as $kennismakingsworkshop): ?>
                <tr>
                    <td class="geselecteerd"><input type="checkbox" name="geselecteerde_kennismakingsworkshops[]" value="<?php echo $kennismakingsworkshop->kennismakingsworkshop_ID ?>" <?php if(!empty($connecties)) foreach($connecties as $connectie) { if($connectie->kennismakingsworkshop_ID == $kennismakingsworkshop->kennismakingsworkshop_ID) { echo 'checked'; }} ?>></td>
                    <td class="kennismakingsworkshop_titel"><?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></td>
                    <td class="kennismakingsworkshop_prijs"><?php echo '€' . $kennismakingsworkshop->kennismakingsworkshop_prijs ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

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
                <td class="geselecteerd"><input type="checkbox" name="geselecteerde_workshops[]" value="<?php echo $workshop->workshop_ID ?>" <?php if(!empty($connecties)) foreach($connecties as $connectie) { if($connectie->workshop_ID == $workshop->workshop_ID) { echo 'checked'; }} ?>></td>
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
                    <td class="geselecteerd"><input type="checkbox" name="geselecteerde_producten[]" value="<?php echo $product->product_ID ?>" <?php if (!empty($connecties)) foreach($connecties as $connectie) { if($connectie->product_ID == $product->product_ID) { echo 'checked'; }} ?>></td>
                    <td class="product"><?php echo $product->product_naam ?></td>
                    <td class="prijs"><?php echo '€' . $product->product_prijs ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p class="submit"><input type="submit" value="<?php echo ucfirst($actie) ?>" /> <a href="<?php echo base_url('cms/kortingscodes') ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/kortingscodes/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
    </form>
</div>
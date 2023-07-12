<h1>Bestelling <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/bestellingen/toevoegen/'); else echo base_url('cms/bestellingen/wijzigen/'.$item_ID); ?>">
        <p><label for="item_deelnemer">Deelnemer</label><?php echo $item_deelnemer ?></span></p>
        <?php
        if($item->aanmelding_ID != null)
        {
        $betaald_datum = $item->aanmelding_betaald_datum;
        }
        else
        {
        $betaald_datum = $item->bestelling_betaald_datum;
        ?>
        <p><label for="item_geslacht">Betaald</label><input type="radio" name="item_betaald" value="ja" <?php if($betaald_datum != '0000-00-00 00:00:00') echo 'checked'; ?> /> Ja <input type="radio" name="item_betaald" value="nee" <?php if($betaald_datum == '0000-00-00 00:00:00') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_betaald_feedback ?></span></p>
      <?php  } ?>

        <p><label for="item_adres">Afleveradres</label><input type="text" name="item_adres" id="item_adres" value="<?php echo $item_adres ?>" /><span class="feedback"><?php echo $item_adres_feedback ?></span></p>
        <p><label for="item_postcode">Postcode</label><input type="text" name="item_postcode_cijfers" id="item_postcode" value="<?php echo $item_postcode_cijfers ?>" maxlength="4" class="postcode_breed" /><input type="text" name="item_postcode_letters" value="<?php echo $item_postcode_letters ?>" maxlength="2" class="postcode_smal" /><span class="feedback"><?php echo $item_postcode_feedback ?></span></p>
        <p><label for="item_plaats">Plaats</label><input type="text" name="item_plaats" id="item_plaats" value="<?php echo $item_plaats ?>" /><span class="feedback"><?php echo $item_plaats_feedback ?></span></p>
        <p class="submit"><input type="submit" value="Bestelling <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/bestellingen/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/bestellingen/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
    </form>
</div>
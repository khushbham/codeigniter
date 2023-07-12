
<?php if($actie == 'wijzigen') { ?>
    <h1>Notities wijzigen van: <?php echo $item[0]->gebruiker_naam ?></h1>
<?php } else { ?>
    <h1>Notities toevoegen</h1>
<?php } ?>

<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php if($actie == 'toevoegen') { echo base_url('cms/notities/toevoegen/'); } else { echo base_url('cms/notities/wijzigen/'.$item[0]->gebruiker_ID); } ?> ">
        <?php if($actie == 'toevoegen'): ?>
        <p>
            <label for="item_deelnemer">Deelnemer</label>
            <select name="item_deelnemer" id="item_deelnemer">
                <option value="">Selecteer een deelnemer</option>
                <?php foreach($lege_deelnemers as $lege_deelnemer) { ?>
                    <option value="<?php echo $lege_deelnemer->gebruiker_ID ?>"><?php echo $lege_deelnemer->gebruiker_naam ?></option>
                <?php } ?>
            </select>
        </p>
        <?php endif; ?>
        <p><label for="item_notitie">Notitie </label><textarea name="item_notitie" id="item_notitie" class="opmaak"><?php if(!empty($item[0]->gebruiker_notities)) echo $item[0]->gebruiker_notities;  ?></textarea></p>

        <p class="submit"><input type="submit" value="Notitie <?php if($actie == 'toevoegen') { echo 'toevoegen'; } else { echo 'wijzigen'; } ?>" /> <a href="<?php echo base_url('cms/notities/') ?>" title="Annuleren">Annuleren</a></p>
    </form>
</div>
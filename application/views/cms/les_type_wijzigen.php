<h1>Type <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/lessen/type_toevoegen/'); else echo base_url('cms/lessen/type_wijzigen/'.$item_ID); ?>">
        <p><label for="item_soort">Soort *</label><input type="text" name="item_soort" id="item_soort" value="<?php echo $item_soort ?>" /><span class="feedback"><?php echo $item_soort_feedback ?></span></p>
        <p class="bestellen"><label for="item_beschikbaar">Les gelijk beschikbaar</label><input type="checkbox" name="item_beschikbaar" id="item_beschikbaar" <?php if($item_beschikbaar == 1) { echo 'checked'; } ?> value="1" /> Ja, deze les is gelijk beschikbaar</p>
        <p class="bestellen"><label for="item_weergeven">Les weergeven</label><input type="checkbox" name="item_weergeven" id="item_weergeven" <?php if($item_weergeven == 1) { echo 'checked'; } ?> value="1" /> Ja, ik wil deze weergeven op de publieke site</p>
        <p class="bestellen"><label for="item_gekoppeld_aan">Les gekoppeld aan</label><input type="checkbox" name="item_gekoppeld_aan" id="item_gekoppeld_aan" <?php if($item_gekoppeld_aan == 1) { echo 'checked'; } ?> value="1" /> Ja, deze les wordt gekoppeld aan een andere les en wanneer hij beschikbaar is is daarvan afhankelijk .</p>
        <p class="submit"><input type="submit" value="type soort <?php echo $actie ?>" /><a href="<?php echo base_url('cms/lessen') ?>" title="Annuleren">Annuleren</a></p>
    </form>
</div>
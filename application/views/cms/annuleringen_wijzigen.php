<h1> Annuleringsverzekering <?php echo $annulering->workshop_titel ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php echo base_url('cms/annuleringen/wijzigen/'.$workshop_ID); ?>">
        <p><label for="item_percentage">percentage *</label><input type="text" name="item_percentage" id="item_percentage" value="<?php echo $item_percentage ?>" />
        <p><label for="item_actief">Actief *</label><input type="radio" name="item_actief" value="Ja" <?php if($item_actief == 'Ja') echo 'checked'; ?> /> Ja <input type="radio" name="item_actief" value="Nee" <?php if($item_actief == 'Nee') echo 'checked'; ?> /> Nee </p>
        <p class="submit"><input type="submit" value="<?php echo ucfirst($actie) ?>" /> <a href="<?php echo base_url('cms/annuleringen') ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/annuleringen/verwijderen/'.$workshop_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
    </form>
</div>
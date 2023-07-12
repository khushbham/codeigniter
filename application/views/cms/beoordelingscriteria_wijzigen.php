<h1>Beoordelingscriteria <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/beoordelingscriteria/toevoegen/'); else echo base_url('cms/beoordelingscriteria/wijzigen/'.$item_ID); ?>">

		<p><label for="item_naam">Naam</label><input type="text" name="item_naam" id="item_naam" value="<?php echo $item_naam ?>" /></p>

		<p class="submit"><input type="submit" value="Beoordelingscriteria <?php echo $actie ?>" /> <a href="<?php echo base_url('cms/beoordelingscriteria/'.$item_ID) ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/beoordelingscriteria/verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
	</form>
</div>
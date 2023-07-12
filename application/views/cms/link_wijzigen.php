<h1>Uitnodigingslink <?php echo $actie ?></h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php if($actie == 'toevoegen') echo base_url('cms/uitnodigingen/link_toevoegen/'); else echo base_url('cms/uitnodigingen/link_wijzigen/'.$item_ID); ?>">
        <p>
            <label for="filter_workshop">Workshop</label>
            <select name="filter_workshop" id="filter_workshop" onchange="update_groep_keuze(this)">
                <option value="">Selecteer een workshop</option>

                <!-- Workshops -->

                <?php if(sizeof($workshops) > 0): ?>
                        <?php foreach($workshops as $workshop): ?>
                            <option value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop->workshop_ID == $item_workshop) echo 'selected="selected"'; ?>><?php echo $workshop->workshop_titel ?></option>
                        <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <span class="feedback"><?php echo $item_workshop_feedback ?></span>
        </p>
        <div id="filter_groep_div">
            <p>
                <label for="filter_groep">Groep</label>
                <select name="filter_groep" id="filter_groep">
                    <option value="">Selecteer een groep</option>
                    <option value="">---</option>

                    <!-- Groepen -->
                    <?php if(sizeof($groepen) > 0): ?>
                            <?php foreach($groepen as $groep): ?>
                                <option value="<?php echo $groep->groep_ID ?>" <?php if($groep->groep_ID == $item_groep) echo 'selected="selected"'; ?>><?php echo $groep->groep_naam ?></option>
                            <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </p>
        </div>
		<p><label for="item_limiet">Limiet (optioneel)</label><input type="text" name="item_limiet" id="item_limiet" value="<?php echo $item_limiet ?>" /></p>
        <p class="submit"><input type="submit" name="item_opslaan" id="item_opslaan" value="Opslaan" class="belangrijk" /> <a href="<?php echo base_url('cms/uitnodigingen/') ?>" title="Annuleren">Annuleren</a> <?php if($actie == 'wijzigen'): ?><a href="<?php echo base_url('cms/uitnodigingen/link_verwijderen/'.$item_ID) ?>" title="Verwijderen">Verwijderen</a><?php endif; ?></p>
    </form>
</div>
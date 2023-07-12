<h1>Beoordeling: <?php echo $beoordeling->les_titel ?></h1>

<table cellpadding="0" cellspacing="0" class="gegevens">
    <tr>
        <th>Nummer</th>
        <td><?php echo $beoordeling->les_beoordeling_ID ?></td>
    </tr>
    <tr>
        <th>Deelnemer</th>
        <td><a href="<?php echo base_url('cms/deelnemers/'.$beoordeling->gebruiker_ID) ?>" title="Bekijk deelnemer"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $beoordeling->gebruiker_naam ?></span></a></td>
    </tr>
    <tr>
        <th>Workshop</th>
        <td><a href="<?php echo base_url('cms/workshops/'.$beoordeling->workshop_ID) ?>" title="Bekijk workshop"><?php echo $beoordeling->workshop_titel ?></a></td>
    </tr>
    <tr>
        <th>Les</th>
        <td><a href="<?php echo base_url('cms/lessen/'.$beoordeling->les_ID) ?>" title="Les bekijken"><?php echo $beoordeling->les_titel ?></a></td>
    </tr>
    <tr>
        <th>Beoordeling</th>
        <td>
            <span>
                <a href="<?php echo base_url('cms/lessen/les_beoordelingen/'.$beoordeling->les_ID) ?>" title="Andere beoordelingen bekijken">
                    <div class="rating_fixed" onclick="window.location.href='<?php echo base_url('cms/lessen/les_beoordelingen/'.$beoordeling->les_ID) ?>'">
                        <?php for($i = 0; $i < 5; $i++) { ?>
                            <?php if(!empty($beoordeling->les_beoordeling)) { ?>
                                <?php if ($beoordeling->les_beoordeling > 0) {?>
                                    <label class="selected">
                                        <input type="radio" name="rating">
                                    </label>
                                <?php } else { ?>
                                    <label class="unselected">
                                        <input type="radio" name="rating">
                                    </label>
                                <?php } $beoordeling->les_beoordeling--; ?>
                            <?php } else { ?>
                                <label class="unselected">
                                    <input type="radio" name="rating">
                                </label>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </a>
            </span>
        </td>
    </tr>
    <tr>
        <th>Opmerking</th>
        <td><?php echo $beoordeling->les_opmerking ?></td>
    </tr>
</table>
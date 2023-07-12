<h1>Recente beoordelingen</h1>
<?php if(sizeof($recente_beoordelingen) > 0): ?>
    <table cellpadding="0" cellspacing="0" class="tabel">
        <tr>
            <th class="gebruiker">Naam</th>
            <th class="workshop">Workshop</th>
            <th class="Les">Les</th>
            <th class="boordeling">Beoordeling</th>
            <th></th>
        </tr>
        <?php foreach($recente_beoordelingen as $item): ?>
            <tr>
                <td class="gebruiker"><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Gebruiker bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
                <td class="workshop"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
                <td class="les"><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
                <td>
                    <a href="<?php echo base_url('cms/lessen/les_beoordelingen/'.$item->les_ID) ?>" title="Beoordelingen bekijken">
                        <span>
                            <div class="rating_fixed" onclick="window.location.href='<?php echo base_url('cms/lessen/les_beoordelingen/'.$item->les_ID) ?>//'">
                                <?php for($i = 0; $i < 5; $i++) { ?>
                                    <?php if(!empty($item->les_beoordeling)) { ?>
                                        <?php if ($item->les_beoordeling > 0) {?>
                                            <label class="selected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } else { ?>
                                            <label class="unselected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } $item->les_beoordeling--; ?>
                                    <?php } else { ?>
                                        <label class="unselected">
                                            <input type="radio" name="rating">
                                        </label>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </span>
                    </a>
                </td>
                <?php if(!empty($item->les_opmerking)) { ?>
                    <td class="bekijken"><a href="<?php echo base_url('cms/beoordelingen/detail/'.$item->les_beoordeling_ID) ?>" title="Opmerking bekijken">Bekijken</a></td>
                <?php } else { ?>
                    <td></td>
                <?php } ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p><em>Er zijn geen beoordelingen.</em></p>
<?php endif; ?>

<h1>Beoordelingen</h1>
<?php if(sizeof($beoordelingen) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="gebruiker">Naam</th>
            <th class="workshop">Workshop</th>
            <th class="Les">Les</th>
			<th class="boordeling">Beoordeling</th>
			<th></th>
        </tr>
		<?php foreach($beoordelingen as $item): ?>
			<tr>
				<td class="gebruiker"><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Gebruiker bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<td class="workshop"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
				<td class="les"><a href="<?php echo base_url('cms/lessen/'.$item->les_ID) ?>" title="Les bekijken"><?php echo $item->les_titel ?></a></td>
                <td>
                    <a href="<?php echo base_url('cms/lessen/les_beoordelingen/'.$item->les_ID) ?>" title="Beoordelingen bekijken">
                        <span>
                            <div class="rating_fixed" onclick="window.location.href='<?php echo base_url('cms/lessen/les_beoordelingen/'.$item->les_ID) ?>//'">
                                <?php for($i = 0; $i < 5; $i++) { ?>
                                    <?php if(!empty($item->les_beoordeling)) { ?>
                                        <?php if ($item->les_beoordeling > 0) {?>
                                            <label class="selected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } else { ?>
                                            <label class="unselected">
                                                <input type="radio" name="rating">
                                            </label>
                                        <?php } $item->les_beoordeling--; ?>
                                    <?php } else { ?>
                                        <label class="unselected">
                                            <input type="radio" name="rating">
                                        </label>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </span>
                    </a>
                </td>
                <?php if(!empty($item->les_opmerking)) { ?>
                    <td class="bekijken"><a href="<?php echo base_url('cms/beoordelingen/detail/'.$item->les_beoordeling_ID) ?>" title="Opmerking bekijken">Bekijken</a></td>
                <?php } else { ?>
                    <td></td>
                <?php } ?>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><em>Er zijn geen beoordelingen.</em></p>
<?php endif; ?>
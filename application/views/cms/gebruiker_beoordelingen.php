<h1><?php echo $gebruiker->gebruiker_naam ?> beoordelingen</h1>

<div id="nieuws">
    <h3>Workshop: <?php echo $workshop->workshop_titel ?></h3>
    <?php if(sizeof($beoordelingen) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel" data-items="lessen">
            <thead>
            <tr>
                <th>Les</th>
                <th>Beoordeling</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($beoordelingen as $item): ?>
                <tr data-item="<?php echo $item->gebruiker_beoordeling_ID ?>">
                    <td><?php echo $item->les_titel ?></a></td>
                    <td>
                        <div class="rating_fixed">
                            <?php for($i = 0; $i < 5; $i++) { ?>
                                <?php if(!empty($item->gebruiker_beoordeling)) { ?>
                                    <?php if ($item->gebruiker_beoordeling > 0) {?>
                                        <label class="selected">
                                            <input type="radio" name="rating">
                                        </label>
                                    <?php } else { ?>
                                        <label class="unselected">
                                            <input type="radio" name="rating">
                                        </label>
                                    <?php } $item->gebruiker_beoordeling--; ?>
                                <?php } else { ?>
                                    <label class="unselected">
                                        <input type="radio" name="rating">
                                    </label>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er staan geen gebruiker beoordelingen in de database.</p>
    <?php endif; ?>
</div>

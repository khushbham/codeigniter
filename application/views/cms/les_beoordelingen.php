<h1><?php echo $les->les_titel ?> beoordelingen</h1>

<div id="nieuws">
    <?php if(sizeof($beoordelingen) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel" data-items="lessen">
            <thead>
            <tr>
                <th>Deelnemer</th>
                <th>Beoordeling</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($beoordelingen as $item): ?>
                <tr data-item="<?php echo $item->les_beoordeling_ID ?>">
                    <td><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
                    <td>
                        <div class="rating_fixed">
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
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er staan geen les beoordelingen in de database.</p>
    <?php endif; ?>
</div>

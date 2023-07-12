<h1>Notities</h1>

    <div id="links">
        <a href="<?php echo base_url('cms/notities/toevoegen/') ?>" title="Nieuwe notitie" class="belangrijk">Nieuwe notitie</a>
        <a href="javascript:;" onclick="document.getElementById('notitie_form').submit();" title="Notities verwijderen" class="verwijderen">Notities verwijderen</a>
    </div>

<div id="nieuws">
    <form method="post" id="notitie_form" action="<?php echo base_url('cms/notities/verwijderen/') ?>">
    <?php if(sizeof($deelnemers) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th></th>
                <th>Gebruiker</th>
                <th>Notitie</th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
            </tr>
            </thead>
            <tbody>
            <p><input type="checkbox" name="notities_checkbox" onClick="toggle(this)"/>Alle notities</p>
            <?php foreach($deelnemers as $item): ?>
                <tr>
                    <td class="geselecteerd"><input type="checkbox" name="geselecteerde_notities[]" value="<?php echo $item->gebruiker_ID ?>"></td>
                    <td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Notitie bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
                    <td><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Notitie bekijken"><?php echo $item->gebruiker_notities ?></a></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/deelnemers/'.$item->gebruiker_ID) ?>" title="Notitie bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/notities/wijzigen/'.$item->gebruiker_ID) ?>" title="Notitie wijzigen">Wijzigen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er staan geen notities in de database.</p>
    <?php endif; ?>
</div>
</form>

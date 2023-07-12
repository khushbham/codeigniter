<h1>Annuleringsverzekering</h1>

<div id="annuleringen">
    <form method="post" id="annuleringen_form" action="<?php echo base_url('cms/annuleringen/annulering_verwijderen/') ?>">
    <?php if(sizeof($workshops) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th>#</th>
                <th>Workshop</th>
                <th>Percentage</th>
                <th>Actief</th>
                <th class="wijzigen"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($workshops as $item):
                ?>
                <tr>
                    <td title="ID"><?php echo $item->workshop_ID ?></td>
                    <td title="Workshop"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
                    <td title="Percentage"><?php if(!empty($item->annulering_percentage)) { echo $item->annulering_percentage . '%'; } else { echo "..."; } ?></td>
                    <td title="Actief"><?php if(!empty($item->annulering_actief)) { echo $item->annulering_actief; } else { echo "Nee"; } ?></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/annuleringen/wijzigen/'.$item->workshop_ID) ?>" title="Annuleringsverzekering wijzigen">Wijzigen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen Annuleringsverzekeringen gevonden.</p>
    <?php endif; ?>
    </form>
</div>
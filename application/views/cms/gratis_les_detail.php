<h1>Les: <?php echo $item[0]->les_titel ?></h1>

<p id="links">
    <a href="<?php echo base_url('cms/lessen/#gratis_workshop') ?>" title="Alle gratis lessen">Alle gratis lessen</a>
    <a href="<?php echo base_url('cms/lessen/gratis_les_wijzigen/'.$item[0]->les_ID) ?>" title="Les wijzigen" class="wijzigen">Les wijzigen</a>
    <a href="<?php echo base_url('cms/lessen/gratis_les_verwijderen/'.$item[0]->les_ID) ?>" title="Les verwijderen" class="verwijderen">Les verwijderen</a>
</p>

<h2>Gegevens</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
    <tr>
        <th>Titel</th>
        <td><?php echo ucfirst($item[0]->les_titel) ?></td>
    </tr>
    <tr>
        <th>Youtube video</th>
        <td>
            <p><?php echo $item[0]->les_youtube_link ?></p>
            <iframe src="<?php echo $item[0]->les_youtube_link ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
        </td>
    </tr>
    <tr>
        <th>tekst</th>
        <td><?php echo ucfirst($item[0]->les_tekst) ?></td>
    </tr>
    <tr>
        <th>publicatiedatum</th>
        <td><?php echo date('d/m/Y', strtotime($item[0]->les_publicatiedatum)) ?></td>
    </tr>
</table>

<h1>Blog</h1>

<p id="links">
    <a href="<?php echo base_url('cms/blog/toevoegen') ?>" title="Artikel toevoegen">Artikel toevoegen</a>
</p>

<div id="nieuws">
<h2>Uitgelicht</h2>
<?php if(sizeof($uitgelichte_blogs) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th class="datum">Datum</th>
                <th class="tijd">Tijd</th>
                <th>Titel</th>
                <th>Geschreven door</th>
                <th>Publicatie datum</th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
                <th class="verwijderen"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($uitgelichte_blogs as $item): ?>
                <tr <?php if($item->blog_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
                    <td class="datum"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php echo date('d/m/Y', strtotime($item->blog_datum)) ?></a></td>
                    <td class="tijd"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php echo date('H:i', strtotime($item->blog_datum)) ?></a></td>
                    <td><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php if($item->blog_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->blog_titel ?></a></td>
                    <td><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php if($item->blog_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->blog_deelnemer ?></a></td>
                    <td><?php if($item->blog_publicatiedatum != "0000-00-00 00:00:00") { echo date('d-m-Y', strtotime($item->blog_publicatiedatum)); } else { echo "X"; } ?></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/blog/wijzigen/'.$item->blog_ID) ?>" title="Artikel wijzigen">Wijzigen</a></td>
                    <td class="verwijderen"><a href="<?php echo base_url('cms/blog/verwijderen/'.$item->blog_ID) ?>" title="Artikel verwijderen">Verwijderen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er staan geen uitgelichte artikelen in de database.</p>
    <?php endif; ?>

<h2>Alle artikelen</h2>
    <?php if(sizeof($blogs) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th class="datum">Datum</th>
                <th class="tijd">Tijd</th>
                <th>Titel</th>
                <th>Geschreven door</th>
                <th>Publicatie datum</th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
                <th class="verwijderen"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($blogs as $item): ?>
                <tr <?php if($item->blog_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
                    <td class="datum"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php echo date('d/m/Y', strtotime($item->blog_datum)) ?></a></td>
                    <td class="tijd"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php echo date('H:i', strtotime($item->blog_datum)) ?></a></td>
                    <td><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php if($item->blog_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->blog_titel ?></a></td>
                    <td><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken"><?php if($item->blog_gepubliceerd == 'nee') echo 'CONCEPT: '; ?><?php echo $item->blog_deelnemer ?></a></td>
                    <td><?php if($item->blog_publicatiedatum != "0000-00-00 00:00:00") { echo date('d-m-Y', strtotime($item->blog_publicatiedatum)); } else { echo "X"; } ?></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/blog/'.$item->blog_ID) ?>" title="Artikel bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/blog/wijzigen/'.$item->blog_ID) ?>" title="Artikel wijzigen">Wijzigen</a></td>
                    <td class="verwijderen"><a href="<?php echo base_url('cms/blog/verwijderen/'.$item->blog_ID) ?>" title="Artikel verwijderen">Verwijderen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if($aantal_paginas > 1): ?>
            <div id="paginanummering">
                <p>
                    <?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
                        <?php if($i == $huidige_pagina): ?>
                            <a href="<?php echo base_url('cms/blog/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
                        <?php else: ?>
                            <a href="<?php echo base_url('cms/blog/pagina/'.$i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
                        <?php endif; ?>
                        <?php if($i < $aantal_paginas) echo ' |'; ?>
                    <?php endfor; ?>
                </p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p>Er staan geen artikelen in de database.</p>
    <?php endif; ?>
</div>
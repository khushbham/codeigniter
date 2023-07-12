<h1><?php echo $item->blog_titel ?></h1>

<p id="links">
    <a href="<?php echo base_url('cms/blog/') ?>" title="Blog">Alle artikelen</a>
    <a href="<?php echo base_url('cms/blog/wijzigen/'.$item->blog_ID) ?>" title="blog wijzigen" class="wijzigen">artikel wijzigen</a>
    <a href="<?php echo base_url('cms/blog/verwijderen/'.$item->blog_ID) ?>" title="blog verwijderen" class="verwijderen">artikel verwijderen</a>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
    <tr>
        <th>URL</th>
        <td><?php echo $item->blog_url ?></td>
    </tr>
    <tr>
        <th>Media</th>
        <td>
            <?php if(sizeof($media) > 0): ?>
                <table cellpadding="0" cellspacing="0" class="media">
                    <?php foreach($media as $bestand): ?>
                        <tr>
                            <?php
                            $item_media_link = '#';
                            if($bestand->media_type == 'pdf') { $media_src = base_url('images/pdf.png'); $item_media_link = base_url('/media/pdf/'.$bestand->media_src); }
                            elseif($bestand->media_type == 'afbeelding') { $media_src = base_url('media/afbeeldingen/thumbnail/'.$bestand->media_src); $item_media_link = base_url('/media/afbeeldingen/origineel/'.$bestand->media_src); }
                            elseif($bestand->media_type == 'video') { $media_src = '//view.vzaar.com/'.$bestand->media_src.'/thumb'; $item_media_link = '//view.vzaar.com/'.$bestand->media_src; }
                            elseif($bestand->media_type == 'mp3') { $media_src = base_url('images/mp3.png'); $item_media_link = base_url('/media/audio/'.$bestand->media_src); }
                            ?>
                            <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $bestand->media_titel ?>" /></a></td>
                            <td class="media_titel"><?php echo $bestand->media_titel ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th>Media tonen</th>
        <td><?php echo ucfirst($item->media_tonen) ?></td>
    </tr>
    <tr>
        <th>Media link</th>
        <td><?php if(!empty($item->media_link)) echo '<a href="'.$item->media_link.'" title="Bekijk video" target="_blank">'.$item->media_link.'</a>'; else echo '...'; ?></td>
    </tr>
    <tr>
        <th>Bericht</th>
        <td><?php if(!empty($item->blog_bericht)) echo $item->blog_bericht; else echo '...'; ?></td>
    </tr>
    <tr>
        <th>Geschreven door</th>
        <td><?php if(!empty($item->blog_deelnemer)) echo $item->blog_deelnemer; else echo '...'; ?></td>
    </tr>
    <tr>
        <th>Datum</th>
        <td><?php echo date('d-m-Y H:i', strtotime($item->blog_datum)) ?> uur</td>
    </tr>
    <tr>
        <th>Gepubliceerd</th>
        <td><?php echo ucfirst($item->blog_gepubliceerd) ?></td>
    </tr>
    <tr>
        <th>uitgelicht</th>
        <td><?php echo ucfirst($item->blog_uitgelicht) ?></td>
    </tr>
    <tr>
        <th>publicatie datum</th>
        <td><?php if($item->blog_publicatiedatum != "0000-00-00 00:00:00") { echo date('d-m-Y', strtotime($item->blog_publicatiedatum)); } else { echo "X"; } ?></td>
    </tr>
    <tr>
        <th>Meta title</th>
        <td><?php if(!empty($item->meta_title)) echo $item->meta_title; else echo '...'; ?></td>
    </tr>
    <tr>
        <th>Meta description</th>
        <td><?php if(!empty($item->meta_description)) echo $item->meta_description; else echo '...'; ?></td>
    </tr>
    <tr>
        <th>Meta Image</th>
        <td>
            <?php if(sizeof($meta_media) > 0): ?>
                <table cellpadding="0" cellspacing="0" class="media">
                    <?php foreach($meta_media as $bestand): ?>
                        <tr>
                            <?php
                            $item_media_link = '#';
                            if($bestand->media_type == 'pdf') { $media_src = base_url('images/pdf.png'); $item_media_link = base_url('/media/pdf/'.$bestand->media_src); }
                            elseif($bestand->media_type == 'afbeelding') { $media_src = base_url('media/afbeeldingen/thumbnail/'.$bestand->media_src); $item_media_link = base_url('/media/afbeeldingen/origineel/'.$bestand->media_src); }
                            elseif($bestand->media_type == 'video') { $media_src = '//view.vzaar.com/'.$bestand->media_src.'/thumb'; $item_media_link = '//view.vzaar.com/'.$bestand->media_src; }
                            elseif($bestand->media_type == 'mp3') { $media_src = base_url('images/mp3.png'); $item_media_link = base_url('/media/audio/'.$bestand->media_src); }
                            ?>
                            <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $bestand->media_titel ?>" /></a></td>
                            <td class="media_titel"><?php echo $bestand->media_titel ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </td>
    </tr>
</table>
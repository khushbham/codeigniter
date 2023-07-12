<h1>Placeholder instellen</h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php echo base_url('cms/lessen/placeholder_toevoegen/'.$les_ID); ?>">
        <p><label for="item_les">Les</label><?php echo $les->les_titel ?></p>
        <div id="media">
            <div id="media_label">
                <p><label for="item_media">Media</label></p>
            </div>
            <div id="media_container">
                <div id="media_lijst">
                    <table cellpadding="0" cellspacing="0" class="js-sorteren-alleen-media-bijwerken">
                        <tbody>
                        <?php $item_media = ''; ?>
                        <?php if($media > 0): foreach($media as $item): ?>
                            <tr data-media-id="<?php echo $item->media_ID ?>">
                                <?php
                                $item_media_link = '#';
                                if($item->media_type == 'pdf') { $media_src = base_url('images/pdf.png'); $item_media_link = base_url('/media/pdf/'.$item->media_src); }
                                elseif($item->media_type == 'afbeelding') { $media_src = base_url('media/afbeeldingen/thumbnail/'.$item->media_src); $item_media_link = base_url('/media/afbeeldingen/origineel/'.$item->media_src); }
                                elseif($item->media_type == 'video') { $media_src = '//view.vzaar.com/'.$item->media_src.'/thumb'; $item_media_link = '//view.vzaar.com/'.$item->media_src; }
                                elseif($item->media_type == 'mp3') { $media_src = base_url('images/mp3.png'); $item_media_link = base_url('/media/audio/'.$item->media_src); }
                                ?>
                                <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $item->media_titel ?>" /></a></td>
                                <td class="media_titel"><?php echo $item->media_titel ?></td>
                                <td class="media_ontkoppelen" onclick="ontkoppelen(this); "><span style="color:red; cursor:pointer;">X</span></td>
                            </tr>
                            <?php $item_media .= $item->media_ID.','; endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="media_acties">
                    <p><a href="#koppelen" title="Media koppelen aan les" class="koppelen">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a> | <a href="#ontkoppelen" title="Alle media ontkoppelen van de les" class="ontkoppelen">Alle media ontkoppelen</a></p>
                    <input type="hidden" name="item_media" id="item_media" value="<?php echo $item_media ?>" data-aantal="0" />
                </div>
            </div>
        </div>

        <p class="submit"><input type="submit" value="media wijzigen" /><a href="<?php echo base_url('cms/lessen/wijzigen/'.$les_ID) ?>" title="Annuleren">Annuleren</a></p>
    </form>
</div>
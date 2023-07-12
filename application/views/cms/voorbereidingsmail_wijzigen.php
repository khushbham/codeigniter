<h1>Voorbereidingsmail wijzigen</h1>
<div id="toevoegen_wijzigen" class="formulier">
    <form method="post" action="<?php echo base_url('cms/'. $actie .'/voorbereidingsmail/'.$item_ID); ?>">
        <p><label for="item_les">Les</label><?php echo $les_titel ?></p>
        <p><label for="item_email">Aanmelden tekst</label><textarea name="item_email" id="item_email" class="opmaak_simpel"><?php echo $item_email ?></textarea></p>
        <div id="media">
            <div id="media_label">
                <p><label for="item_media">Media</label></p>
            </div>
            <div id="media_container">
                <div id="media_lijst">
                    <table cellpadding="0" cellspacing="0">
                        <?php $item_media = ''; ?>
                        <?php if($media > 0): foreach($media as $item): ?>
                            <tr>
                                <?php
                                $item_media_link = '#';
                                if($item->media_type == 'pdf') { $media_src = base_url('images/pdf.png'); $item_media_link = base_url('/media/pdf/'.$item->media_src); }
                                elseif($item->media_type == 'afbeelding') { $media_src = base_url('media/afbeeldingen/thumbnail/'.$item->media_src); $item_media_link = base_url('/media/afbeeldingen/origineel/'.$item->media_src); }
                                elseif($item->media_type == 'video') { $media_src = '//view.vzaar.com/'.$item->media_src.'/thumb'; $item_media_link = '//view.vzaar.com/'.$item->media_src; }
                                elseif($item->media_type == 'mp3') { $media_src = base_url('images/mp3.png'); $item_media_link = base_url('/media/audio/'.$item->media_src); }
                                ?>
                                <td class="media_image"><a href="<?php echo $item_media_link ?>" target="<?php if($item_media_link != '#') echo '_blank'; ?>"><img src="<?php echo $media_src ?>" title="<?php echo $item->media_titel ?>" /></a></td>
                                <td class="media_titel"><?php echo $item->media_titel ?></td>
                            </tr>
                            <?php $item_media .= $item->media_ID.','; endforeach; endif; ?>
                    </table>
                </div>
                <div id="media_acties">
                    <p><a href="#koppelen" title="Media koppelen aan voorbereidingsmail" class="koppelen" data-soort="media" data-welkomstmail="true">Media koppelen</a> | <a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen aan mediabibliotheek (opent een nieuw tabblad / venster)" target="_blank">Media toevoegen</a></p>
                    <input type="hidden" name="item_media" id="item_media" value="<?php echo $item_media ?>" data-aantal="0" />
                </div>
            </div>
        </div>
        <p class="submit"><input type="submit" value="Voorbereidingsmail wijzigen" /><a href="<?php echo base_url('cms/'. $actie .'/wijzigen/'.$item_ID) ?>" title="Annuleren">Annuleren</a></p>
    </form>
    <?php if(sizeof($groepen) > 0): ?>
        <span id="voorbereidingsmail">
            <form method="post" action="<?php echo base_url('cms/lessen/voorbereidingsmail_versturen/'.$item_ID); ?>">
                    <label for="Groepen">Groep</label>
                    <select name="Groepen">
                        <option value="">Selecteer een Groep</option>
                        <?php if(sizeof($groepen) > 0): ?>
                            <?php foreach($groepen as $groep): ?>
                                <?php if($groep->les_voorbereidingsmail_verstuurd != 1): ?>
                                    <option value="<?php echo $groep->groep_ID ?>"><?php echo $groep->groep_naam ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <span class="feedback"><?php echo $groepen_feedback ?></span>
                  <span class="submit"><input id="voorbereidingsmail_versturen" type="submit" value="Voorbereidingsmail nu versturen"/></span>
            </form>
        </span>
    <?php elseif(sizeof($individuen) > 0): ?>
        <span id="voorbereidingsmail">
            <form method="post" action="<?php echo base_url('cms/lessen/voorbereidingsmail_versturen_individu/'.$item_ID); ?>">
                    <label for="deelnemers">Deelnemers</label>
                    <select name="deelnemers">
                        <option value="">Selecteer een deelnemer</option>
                        <?php if(sizeof($individuen) > 0): ?>
                            <?php foreach($individuen as $individu): ?>
                                <?php if($individu->les_voorbereidingsmail_verstuurd != 1 && $individu->gebruiker_rechten == 'deelnemer'): ?>
                                    <option value="<?php echo $individu->gebruiker_ID ?>"><?php echo $individu->gebruiker_naam ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <span class="feedback"><?php echo $individu_feedback ?></span>
                <span class="submit"><input id="voorbereidingsmail_versturen" type="submit" value="Voorbereidingsmail nu versturen"/></span>
            </form>
        </span>
    <?php endif; ?>
        </form>
</div>
<div id="berichten">
	<h1>Bericht</h1>
	<div id="buttons">
		<p>
            <?php if(!$bericht->bericht_no_reply) { ?>
			    <a href="<?php echo base_url('opdrachten/berichten/beantwoorden/'.$bericht->bericht_ID) ?>" title="Bericht beantwoorden" class="belangrijk">Beantwoorden</a>
            <?php } ?>
			<a href="<?php echo base_url('opdrachten/berichten') ?>" title="Annuleren">Annuleren</a>
			<a href="<?php echo base_url('opdrachten/berichten/verwijderen/'.$bericht->bericht_ID.'/bericht') ?>" title="Bericht verwijderen">Verwijderen</a>
		</p>
	</div>
	<div id="bericht">
		<div><div class="label">Van</div><div class="tekst"><?php echo $bericht->gebruiker_voornaam.' '.$bericht->gebruiker_tussenvoegsel.' '.$bericht->gebruiker_achternaam ?></div></div>
		<div><div class="label">Datum</div><div class="tekst"><?php echo toonDatum($bericht->bericht_datum) ?>, <?php echo toonTijd($bericht->bericht_datum) ?> uur</div></div>
		<div><div class="label">Onderwerp</div><div class="tekst"><?php echo $bericht->bericht_onderwerp ?></div></div>
		<div><div class="label">Tekst</div><div class="tekst"><?php echo $bericht->bericht_tekst ?></div></div>
	</div>

    <?php if(isset($bericht_media) && sizeof($bericht_media) > 0): ?>
        <div class="label">Bijlagen</div>
        <div id="media">
            <?php foreach($bericht_media as $item): ?>
                <div class="media">
                    <?php if($item->media_type == 'pdf'): ?>
                        <p class="pdf"><a href="<?php echo base_url('media/pdf/'.$item->media_src) ?>" title="Open de PDF in een nieuw tabblad / venster>" target="_blank"><?php echo $item->media_titel ?></a></p>
                    <?php elseif($item->media_type == 'afbeelding'): ?>
                        <p><img src="<?php echo base_url('media/afbeeldingen/groot/'.$item->media_src) ?>" alt="<?php echo $item->media_titel ?>" /></p>
                    <?php elseif($item->media_type == 'video'): ?>
                        <iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="460" height="270" id="vzvd-<?php echo $item->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $item->media_src ?>" src="//view.vzaar.com/<?php echo $item->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="460" height="270"></iframe>
                    <?php elseif($item->media_type == 'playlist'): ?>
                        <iframe allowFullScreen allowTransparency="true" class="vzaar video player" frameborder="0" width="460" height="420" id="vzpl-<?php echo $item->media_src ?>" mozallowfullscreen name="vzpl-<?php echo $item->media_src ?>" src="//view.vzaar.com/playlists/<?php echo $item->media_src ?>" title="vzaar video player" type="text/html" webkitAllowFullScreen width="460" height="420"></iframe>
                    <?php elseif($item->media_type == 'mp3'): ?>
                        <div id="audio">
                            <div class="audio">
                                <div class="audio_titel"><?php echo $item->media_titel ?></div>
                                <audio src="<?php echo base_url('media/audio/'.$item->media_src) ?>" preload="auto"></audio>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
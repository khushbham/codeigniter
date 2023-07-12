<div id="vragen">
    <h1>Meest gestelde vragen</h1>
    <?php if(sizeof($vragen)): ?>
        <ol>
            <?php foreach($vragen as $item): ?>
                <li><a href="#vraag<?php echo $item->vraag_ID ?>" title="Bekijk het antwoord op de vraag: <?php echo $item->vraag_titel; ?>"><?php echo $item->vraag_titel; ?></a></li>
            <?php endforeach; ?>
        </ol>
        <?php
        $nr = 1;
        foreach($vragen as $item):
            ?>
            <article id="vraag<?php echo $item->vraag_ID ?>">
                <div class="media">
                    <?php if($item->media_ID != ''): ?>
                        <?php if($item->media_type == 'afbeelding'): ?>
                            <p><img src="<?php echo base_url('media/afbeeldingen/thumbnail/'.$item->media_src) ?>" alt="<?php echo $item->media_titel ?>" /></p>
                        <?php elseif($item->media_type == 'video'): ?>
                            <iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="210" height="125" id="vzvd-<?php echo $item->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $item->media_src ?>" src="http://view.vzaar.com/<?php echo $item->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="210" height="125"></iframe>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="vraag_antwoord">
                    <h1><?php echo $nr.'. '.$item->vraag_titel; ?></h1>
                    <p><?php echo nl2br($item->vraag_antwoord); ?></p>
                    <?php if($item->media_ID != '' && $item->media_type == 'pdf'): ?>
                        <p><a href="<?php echo base_url('media/pdf/'.$item->media_src) ?>" title="PDF downloaden" target="_blank"><?php echo $item->media_titel ?></a></p>
                    <?php endif; ?>
                </div>
            </article>
            <?php
            $nr++;
        endforeach;
        ?>
    <?php endif; ?>
    <p class="contact">Niet beantwoord? <a href="<?php echo base_url('cursistenmodule/berichten/nieuw') ?>" title="Nieuw bericht">Stuur een bericht!</a></p>
</div>
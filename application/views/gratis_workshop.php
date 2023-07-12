<section id="introductie">
    <div id="tekst">
        <hgroup>
            <h1><?php if(!empty($content->pagina_titel)) echo $content->pagina_titel ?></h1>
            <h2><?php if(!empty($content->pagina_inleiding)) echo $content->pagina_inleiding ?></h2>
        </hgroup>
        <p><?php if(!empty($content->pagina_tekst)) echo nl2br($content->pagina_tekst) ?></p>
    </div>
    <div id="media">
        <?php if($content->media_ID != ''): ?>
            <?php if($content->media_type == 'afbeelding'): ?>
                <img src="<?php echo base_url('media/afbeeldingen/groot/'.$content->media_src) ?>" alt="<?php echo $content->media_titel ?>" />
            <?php else: ?>
                <iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="620" height="380" id="vzvd-<?php echo $content->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $content->media_src ?>" src="//view.vzaar.com/<?php echo $content->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="620" height="380"></iframe>
            <?php endif; ?>
        <?php else: ?>
            <p>Foto / video binnenkort</p>
        <?php endif; ?>
    </div>
</section>

<?php if (isset($_COOKIE['gratisworkshop']) && !empty($_COOKIE['gratisworkshop']) || $cookie == true) { ?>
    <?php if(!empty($gratis_lessen)) { ?>
        <?php foreach($gratis_lessen as $item) { ?>
            <?php if($item->les_publicatiedatum <= date("Y-m-d H:i:s")) { ?>
            <button class="accordion"><?php echo $item->les_titel ?></button>
            <div class="panel">
                <div class="panel_titel">
                    <h2><?php echo $item->les_titel ?></h2>
                </div>
                <div class="panel_tekst">
                   <p><?php echo $item->les_tekst ?></p>
                </div>
                <div class="youtube_video">
                    <iframe <iframe width="400" height="315" src="<?php echo $item->les_youtube_link ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                </div>
            </div>
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
        <p>Er zijn geen gratis lessen beschikbaar</p>
    <?php } ?>
<?php } else { ?>
    <p>Vul hier je emailadres in en krijg toegang tot de gratis lessen!</p>
    <section id="aanmelden">
        <form method="post" action="<?php echo base_url('gratis-workshop/'); ?>">
            <p>
                <label for="item_emailadres">E-mailadres *</label>
                <input type="text" name="item_emailadres" id="item_emailadres" value="<?php echo $item_emailadres ?>" />
                <span class="feedback"><?php echo $item_emailadres_feedback ?></span>
            </p>
            <p class="submit"><input type="submit" value="Inschrijven" /></p>
        </form>
    </section>
<?php } ?>



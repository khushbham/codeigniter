<h1>Workshops</h1>
<?php if(sizeof($workshops) > 0 ): ?>
	<div id="workshops">
		<h2>Je bent ingeschreven voor de volgende workshops:</h2>
		<?php foreach($workshops as $workshop): ?>
			<article>
				<a href="<?php echo base_url('cursistenmodule/workshop/'.$workshop->workshop_ID) ?>" title="<?php echo $workshop->workshop_titel ?>">
				<?php if($workshop->media_type == 'afbeelding'): ?>
					<img src="<?php echo base_url('media/afbeeldingen/origineel/'.$workshop->media_src) ?>" class="uitgelicht" alt="<?php echo $workshop->workshop_titel ?>" />
				<?php else: ?>
					<img src="<?php echo base_url('media/afbeeldingen/thumbnail/watermerk.jpg') ?>" class="uitgelicht" alt="<?php echo $workshop->workshop_titel ?>" />
				<?php endif; ?>
				</a>

				<hgroup>
					<h1><a href="<?php echo base_url('cursistenmodule/workshop/'.$workshop->workshop_ID) ?>" title="<?php echo $workshop->workshop_titel ?>"><?php echo $workshop->workshop_titel; ?></a></h1>
				</hgroup>
				<p class="inleiding"><?php if(!empty($workshop->workshop_inleiding)) echo $workshop->workshop_inleiding; else echo substr($workshop->workshop_bericht, 0, 150).'...'; ?></p>
			</article>
		<?php endforeach; ?>
	</div>
    <?php if($this->session->userdata('gebruiker_rechten') != "dummy") { ?>
	<div id="extra-workshops">
        <?php if(!empty($aanbevolen)) { ?>
		<h2>Misschien ben je ook geïnteresseerd in één van de volgende workshops?</h2>
		<?php foreach($aanbevolen as $aanbevolen_workshop ): ?>
		<?php if($aanbevolen_workshop->workshop_zichtbaar_cursist == 1){ ?>
			<?php $groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($aanbevolen_workshop->workshop_ID); ?>
                    <?php if($aanbevolen_workshop->workshop_niveau > $workshop_niveau || ($aanbevolen_workshop->workshop_ID == 41 || $aanbevolen_workshop->workshop_ID != 37 || $aanbevolen_workshop->workshop_ID == 33 || $aanbevolen_workshop->workshop_ID == 39 || $aanbevolen_workshop->workshop_ID == 35) || $aanbevolen_workshop->workshop_specialty = "ja") { ?>
                        <article>
                            <?php if($aanbevolen_workshop->media_type == 'afbeelding'): ?>
                                <img src="<?php echo base_url('media/afbeeldingen/origineel/'.$aanbevolen_workshop->media_src) ?>" class="uitgelicht" alt="<?php echo $aanbevolen_workshop->workshop_titel ?>" />
                            <?php else: ?>
                                <img src="<?php echo base_url('media/afbeeldingen/uitgelicht/watermerk.jpg') ?>" class="uitgelicht" alt="<?php echo $aanbevolen_workshop->workshop_titel ?>" />
                            <?php endif; ?>

                            <hgroup>
                                <h1><?php echo $aanbevolen_workshop->workshop_titel; ?></h1>
                            </hgroup>
                            <p class="inleiding"><?php if(!empty($aanbevolen_workshop->workshop_inleiding)) echo $aanbevolen_workshop->workshop_inleiding; else echo substr($workshop->workshop_bericht, 0, 150).'...'; ?> <br><a href="<?php echo base_url('workshops/'.$aanbevolen_workshop->workshop_url) ?>" title="Meer informatie">Meer informatie</a></p>

                            <?php if($aanbevolen_workshop->workshop_type == 'groep' || $aanbevolen_workshop->workshop_type == 'online'): ?>
                                <?php if(sizeof($groepen) > 0): ?>
                                    <?php if (!empty($aanbevolen_workshop->plekken_over) && $aanbevolen_workshop->plekken_over > 0 && $aanbevolen_workshop->plekken_over <= 3 && $aanbevolen_workshop->plekken_over != 1) { ?>
                                        <span class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $aanbevolen_workshop->plekken_over ?> plekken beschikbaar</span>
                                    <?php } elseif($aanbevolen_workshop->plekken_over == 1) { ?>
                                        <span class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $aanbevolen_workshop->plekken_over ?> plek beschikbaar</span>
                                    <?php } ?>
                                    <span class="aanmelden"><a href="<?php echo base_url('cursistenmodule/aanmelden/workshop/'.$aanbevolen_workshop->workshop_url) ?>" title="Aanmelden voor <?php echo $aanbevolen_workshop->workshop_titel; ?>" class="button-smal">Aanmelden workshop</a></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="aanmelden"><a href="<?php echo base_url('cursistenmodule/aanmelden/workshop/'.$aanbevolen_workshop->workshop_url) ?>" title="Aanmelden voor <?php echo $aanbevolen_workshop->workshop_titel; ?>" class="button-smal">Aanmelden workshop</a></span>
                            <?php endif; ?>
                        </article>
                    <?php } ?>
                <?php } ?>
		<?php endforeach; ?>
        <?php } ?>
	</div>
    <?php } ?>
<?php else: ?>
    <?php if($this->session->userdata('gebruiker_rechten') != "dummy") { ?>
	<p>Je volgt nog geen workshops.</p>
	<div id="extra-workshops">
    <?php if(!empty($aanbevolen)) { ?>
		<h2>Misschien ben je geïnteresseerd in één van de volgende workshops?</h2>
		<?php foreach($aanbevolen as $aanbevolen_workshop ): ?>
            <?php if($aanbevolen_workshop->workshop_zichtbaar_cursist == 1){ ?>
                <?php $groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($aanbevolen_workshop->workshop_ID); ?>
                <?php if($aanbevolen_workshop->workshop_niveau > $workshop_niveau || ($aanbevolen_workshop->workshop_ID == 41 || $aanbevolen_workshop->workshop_ID != 37 || $aanbevolen_workshop->workshop_ID == 33 || $aanbevolen_workshop->workshop_ID == 39 || $aanbevolen_workshop->workshop_ID == 35) || $aanbevolen_workshop->workshop_specialty = "ja") { ?>
                    <article>
                        <?php if($aanbevolen_workshop->media_type == 'afbeelding'): ?>
                            <img src="<?php echo base_url('media/afbeeldingen/uitgelicht/'.$aanbevolen_workshop->media_src) ?>" class="uitgelicht" alt="<?php echo $aanbevolen_workshop->workshop_titel ?>" />
                        <?php else: ?>
                            <img src="<?php echo base_url('media/afbeeldingen/uitgelicht/watermerk.jpg') ?>" class="uitgelicht" alt="<?php echo $aanbevolen_workshop->workshop_titel ?>" />
                        <?php endif; ?>

                        <hgroup>
                            <h1><?php echo $aanbevolen_workshop->workshop_titel ?></h1>
                        </hgroup>
                        <p class="inleiding"><?php if(!empty($aanbevolen_workshop->workshop_inleiding)) echo $aanbevolen_workshop->workshop_inleiding; else echo substr($workshop->workshop_bericht, 0, 150).'...'; ?><br><a href="<?php echo base_url('workshops/'.$aanbevolen_workshop->workshop_url) ?>" title="Meer informatie">Meer informatie</a></p>

                        <?php if($aanbevolen_workshop->workshop_type == 'groep' || $aanbevolen_workshop->workshop_type == 'online'): ?>
                            <?php if(sizeof($groepen) > 0): ?>
                                <?php if (!empty($aanbevolen_workshop->plekken_over) && $aanbevolen_workshop->plekken_over > 0 && $aanbevolen_workshop->plekken_over <= 3 && $aanbevolen_workshop->plekken_over != 1) { ?>
                                    <p class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $aanbevolen_workshop->plekken_over ?> plekken beschikbaar</p>
                                <?php } elseif($aanbevolen_workshop->plekken_over == 1) { ?>
                                    <p class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $aanbevolen_workshop->plekken_over ?> plek beschikbaar</p>
                                <?php } ?>
                                <span class="aanmelden"><a href="<?php echo base_url('cursistenmodule/aanmelden/workshop/'.$aanbevolen_workshop->workshop_url) ?>" title="Aanmelden voor <?php echo $aanbevolen_workshop->workshop_titel; ?>" class="button-smal">Aanmelden workshop</a></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="aanmelden"><a href="<?php echo base_url('cursistenmodule/aanmelden/workshop/'.$aanbevolen_workshop->workshop_url) ?>" title="Aanmelden voor <?php echo $aanbevolen_workshop->workshop_titel; ?>" class="button-smal">Aanmelden workshop</a></span>
                        <?php endif; ?>
                    </article>
                <?php } ?>
			<?php } ?>
		<?php endforeach; ?>
    <?php } ?>
	</div>
    <?php } ?>
<?php endif; ?>
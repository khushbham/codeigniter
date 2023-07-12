<section class="hero hero--workshops" class="clearfix">
	<div class="wrapper">
		<div class="intro">
			<hgroup>
				<h1 class="hide-mobile"><?php if (!empty($content->pagina_titel)) echo $content->pagina_titel ?></h1>
				<h2><?php if (!empty($content->pagina_inleiding)) echo $content->pagina_inleiding ?></h2>
			</hgroup>
			<p><?php if (!empty($content->pagina_tekst)) echo nl2br($content->pagina_tekst) ?></p>
		</div>
	</div>
</section>

<section class="workshops">
	<div class="wrapper">
		<?php if (sizeof($uitgelicht)) : ?>
			<?php foreach ($uitgelicht as $workshop) : ?>
				<?php if ($workshop->workshop_zichtbaar_publiek == 1) : ?>
					<?php $groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID); ?>
					<article>
						<a href="<?php echo base_url('workshops/' . $workshop->workshop_url) ?>" title="Meer informatie over <?php echo $workshop->workshop_titel; ?>">
							<?php if ($workshop->media_type == 'afbeelding') : ?>
								<img src="<?php echo base_url('media/afbeeldingen/origineel/' . $workshop->media_src) ?>" class="uitgelicht" alt="<?php echo $workshop->workshop_titel ?>" />
							<?php else : ?>
								<img src="<?php echo base_url('media/afbeeldingen/uitgelicht/watermerk.jpg') ?>" class="uitgelicht" alt="<?php echo $workshop->workshop_titel ?>" />
							<?php endif; ?>
						</a>
						<h3>
							<?php
							if (!empty($workshop->workshop_ondertitel)) {
								echo $workshop->workshop_ondertitel;
							} else {
								switch ($workshop->workshop_type) {
									case 'groep':
										echo 'Online en fysieke groepslessen';
										break;

									case 'online':
										echo 'Online groepslessen';
										break;

									case 'individueel':
										echo 'Online lessen individueel';
										break;
								}
							}
							?>
						</h3>
						<p class="workshop-intro"><?php if (!empty($workshop->workshop_inleiding)) echo $workshop->workshop_inleiding;
												else echo substr($workshop->workshop_bericht, 0, 150) . '...'; ?></p>
						<div class="workshop-meta">
							<span class="workshop-date">
								<?php if ($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online') : ?>
									<?php if (sizeof($groepen) > 0) : ?>
										Start <?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?> |
									<?php else : ?>
										Binnenkort nieuwe lesdata! |
									<?php endif; ?>
								<?php else : ?>
									<?php if ($workshop->workshop_startdatum != '0000-00-00 00:00:00' && strtotime($workshop->workshop_startdatum) > time()) : ?>
										Start <?php echo date('d-m-Y', strtotime($workshop->workshop_startdatum)) ?> |
									<?php else : ?>
										Direct beginnen |
									<?php endif; ?>
								<?php endif; ?>
								</span>
								<span class="workshop-price">Kosten: <?php echo $workshop->workshop_prijs; ?>,-</span>

							<?php if ($workshop->workshop_ID != 9 && $workshop->workshop_ID != 71 && $workshop->workshop_in3 != 0) : ?>
								<p>Of betaal in drie termijnen van <strong><?php echo '€' .  money_format('%.2n', $workshop->workshop_prijs / 3); ?></strong>.</p>
							<?php elseif (($workshop->workshop_ID == 9 || $workshop->workshop_niveau == 5) && $workshop->workshop_in3 != 0) : ?>
								<p>Of betaal in drie termijnen van <strong><?php echo '€' .  money_format('%.2n', $workshop->workshop_prijs / 3); ?></strong>.</p>
							<?php endif; ?>
							<a href="<?php echo base_url('workshops/' . $workshop->workshop_url) ?>" title="Meer informatie over <?php echo $workshop->workshop_titel; ?>" class="button button--orange">Meer informatie</a>
							<?php if (!empty($workshop->plekken_over) && $workshop->plekken_over > 0 && $workshop->plekken_over <= 3 && $workshop->plekken_over != 1) { ?>
								<span class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $workshop->plekken_over ?> plekken beschikbaar</span>
							<?php } elseif ($workshop->plekken_over == 1) { ?>
								<span class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $workshop->plekken_over ?> plek beschikbaar</span>
							<?php } ?>
						</div>
					</article>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</section>
<?php if (sizeof($vervolg_workshops) > 0) : ?>
	<section class="extra-workshops">
		<div class="wrapper">
			<h2>Andere uitdagende workshops</h2>
			<?php foreach ($vervolg_workshops as $workshop) : ?>
				<?php if ($workshop->workshop_zichtbaar_publiek == 1) : ?>
					<article>
						<?php if ($workshop->media_type == 'afbeelding') : ?>
							<a href="<?php echo base_url('workshops/' . $workshop->workshop_url) ?>" title="Meer informatie over <?php echo $workshop->workshop_titel; ?>"><img src="<?php echo base_url('media/afbeeldingen/origineel/' . $workshop->media_src) ?>" class="uitgelicht" alt="<?php echo $workshop->workshop_titel ?>" /></a>
						<?php else : ?>
							<a href="<?php echo base_url('workshops/' . $workshop->workshop_url) ?>" title="Meer informatie over <?php echo $workshop->workshop_titel; ?>"><img src="<?php echo base_url('media/afbeeldingen/uitgelicht/watermerk.jpg') ?>" class="uitgelicht" alt="<?php echo $workshop->workshop_titel ?>" /></a>
						<?php endif; ?>
						<h3><a href="<?php echo base_url('workshops/' . $workshop->workshop_url) ?>" title="Meer informatie over <?php echo $workshop->workshop_titel; ?>"><?php echo $workshop->workshop_titel; ?></a></h3>
					</article>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>
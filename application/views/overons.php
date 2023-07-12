<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title"><?php if (!empty($content->pagina_titel)) echo $content->pagina_titel ?></h1>
	</div>
</section>

<section class="over-ons">
	<div class="wrapper">
		<div class="intro">
			<h2><?php if (!empty($content->pagina_inleiding)) echo $content->pagina_inleiding ?></h2>
			<?php if (!empty($content->pagina_tekst)) echo nl2br($content->pagina_tekst) ?>
		</div>
		<div class="media">
			<?php if ($content->media_ID != '') : ?>
				<?php if ($content->media_type == 'afbeelding') : ?>
					<img src="<?php echo base_url('media/afbeeldingen/groot/' . $content->media_src) ?>" alt="<?php echo $content->media_titel ?>" />
				<?php else : ?>
					<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="620" height="380" id="vzvd-<?php echo $content->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $content->media_src ?>" src="//view.vzaar.com/<?php echo $content->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="620" height="380"></iframe>
				<?php endif; ?>
			<?php else : ?>
				<p>Foto / video binnenkort</p>
			<?php endif; ?>
		</div>
	</div>
</section>


<?php if (sizeof($docenten) > 0) : ?>
	<section id="docenten">
		<div class="wrapper">
			<div id="biografieen">
				<?php foreach ($docenten as $item) : ?>
					<?php if ($item->gebruiker_status == 'actief') : ?>
						<article id="<?php echo $item->docent_ID ?>">
							<span class="docent"><?php echo $item->docent_naam ?></span>
							<?php if ($item->media_ID != '') : ?>
								<?php if ($item->media_type == 'afbeelding') : ?>
									<img src="<?php echo base_url('media/afbeeldingen/medium/' . $item->media_src) ?>" alt="<?php echo $item->media_titel ?>" class="docent-media" />
								<?php else : ?>
									<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="380" height="235" id="vzvd-<?php echo $item->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $item->media_src ?>" src="//view.vzaar.com/<?php echo $item->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="380" height="235" class="docent-media"></iframe>
								<?php endif; ?>
							<?php endif; ?>
							<p><strong><?php echo nl2br($item->docent_inleiding) ?></strong></p>
							<?php echo $item->docent_tekst ?>
						</article>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<div id="namen">
				<h2 class="docenten-title">Ontmoet onze andere docenten</h2>
				<ul>
					<?php foreach ($docenten as $item) : ?>
						<?php if ($item->gebruiker_status == 'actief') : ?>
							<li><a href="#<?php echo $item->docent_ID ?>" title="Meer informatie over <?php echo $item->docent_naam ?>">

									<div class="docenten_media">
										<?php if ($item->media_ID != '') : ?>
											<?php if ($item->media_type == 'afbeelding') : ?>
												<img src="<?php echo base_url('media/afbeeldingen/medium/' . $item->media_src) ?>" alt="<?php echo $item->media_titel ?>" />
											<?php else : ?>
												<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="200" height="120" id="vzvd-<?php echo $item->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $item->media_src ?>" src="//view.vzaar.com/<?php echo $item->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="200" height="120"></iframe>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<?php echo $item->docent_naam ?>
								</a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>
<?php endif; ?>

<section class="over-localhost">
	<div class="wrapper">
		<div class="over-localhost-content">
			<h2><?php if (!empty($over->pagina_titel)) echo $over->pagina_titel ?></h2>
			<p><strong><?php if (!empty($over->pagina_inleiding)) echo $over->pagina_inleiding ?></strong></p>
			<p><?php if (!empty($over->pagina_tekst)) echo nl2br($over->pagina_tekst) ?></p>
		</div>

		<div class="over-localhost-sidebar">
		</div>
	</div>
</section>
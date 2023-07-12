<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title">Overzicht reacties</h1>
	</div>
</section>

<section class="reacties">
	<div class="wrapper">
		<?php if (sizeof($reacties) > 0) : ?>
			<?php foreach ($reacties as $reactie) : ?>
				<article>
					<div class="media">
						<p>
							<?php if ($reactie->media_ID != '') : ?>
								<?php if ($reactie->media_type == 'afbeelding') : ?>
									<a href="<?php echo base_url('reacties/' . $reactie->reactie_url) ?>" title="Lees verder: <?php echo $reactie->reactie_titel; ?>"><img src="<?php echo base_url('media/afbeeldingen/thumbnail/' . $reactie->media_src) ?>" alt="<?php echo $reactie->media_titel ?>" /></a>
								<?php else : ?>
									<a href="<?php echo base_url('reacties/' . $reactie->reactie_url) ?>" title="Lees verder: <?php echo $reactie->reactie_titel; ?>"><img src="//view.vzaar.com/<?php echo $reactie->media_src ?>/image" alt="<?php echo $reactie->media_titel ?>" /></a>
								<?php endif; ?>
							<?php else : ?>
								<a href="<?php echo base_url('reacties/' . $reactie->reactie_url) ?>" title="Lees verder: <?php echo $reactie->reactie_titel; ?>"><img src="<?php echo base_url('assets/images/watermerk.jpg') ?>" alt="<?php echo $reactie->reactie_titel ?>" /></a>
							<?php endif; ?>
						</p>
					</div>
					<div class="tekst">
						<h1><a href="<?php echo base_url('reacties/' . $reactie->reactie_url) ?>" title="Lees verder: <?php echo $reactie->reactie_titel; ?>">'<?php echo $reactie->reactie_titel; ?>'</a></h1>
						<p><?php echo strip_tags(trim(substr($reactie->reactie_bericht, 0, 200)) . '...'); ?><br /><a href="<?php echo base_url('reacties/' . $reactie->reactie_url) ?>" title="Lees verder: <?php echo $reactie->reactie_titel; ?>">> Lees verder</a></p>
					</div>
				</article>
			<?php endforeach; ?>
			<?php if ($aantal_paginas > 1) : ?>
				<div id="paginanummering">
					<p>
						<?php for ($i = 1; $i <= $aantal_paginas; $i++) : ?>
							<?php if ($i == $huidige_pagina) : ?>
								<a href="<?php echo base_url('reacties/pagina/' . $i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
							<?php else : ?>
								<a href="<?php echo base_url('reacties/pagina/' . $i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
							<?php endif; ?>
							<?php if ($i < $aantal_paginas) echo ' |'; ?>
						<?php endfor; ?>
					</p>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
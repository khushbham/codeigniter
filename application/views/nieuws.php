<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title">Overzicht nieuws</h1>
	</div>
</section>

<section class="nieuws">
	<div class="wrapper">

		<?php if (sizeof($nieuws) > 0) : ?>
			<?php foreach ($nieuws as $item) : ?>
				<article>
					<h1><a href="<?php echo base_url('nieuws/' . $item->nieuws_url) ?>" title="Lees verder: <?php echo $item->nieuws_titel; ?>"><?php echo $item->nieuws_titel; ?></a></h1>
					<div class="media">
						<p>
							<?php if ($item->media_ID != '') : ?>
								<?php if ($item->media_type == 'afbeelding') : ?>
									<a href="<?php echo base_url('nieuws/' . $item->nieuws_url) ?>" title="Lees verder: <?php echo $item->nieuws_titel; ?>"><img src="<?php echo base_url('media/afbeeldingen/thumbnail/' . $item->media_src) ?>" alt="<?php echo $item->media_titel ?>" /></a>
								<?php else : ?>
									<a href="<?php echo base_url('nieuws/' . $item->nieuws_url) ?>" title="Lees verder: <?php echo $item->nieuws_titel; ?>"><img src="//view.vzaar.com/<?php echo $item->media_src ?>/image" alt="<?php echo $item->media_titel ?>" /></a>
								<?php endif; ?>
							<?php else : ?>
								<a href="<?php echo base_url('nieuws/' . $item->nieuws_url) ?>" title="Lees verder: <?php echo $item->nieuws_titel; ?>"><img src="<?php echo base_url('/assets/images/watermerk.jpg') ?>" alt="<?php echo $item->nieuws_titel ?>" /></a>
							<?php endif; ?>
						</p>
					</div>
					<div class="tekst">
						<p><strong><?php $this->load->helper('tijdstip');
									echo veranderTijdstip($item->nieuws_datum) ?> -</strong> <?php if (!empty($item->nieuws_inleiding)) echo $item->nieuws_inleiding;
																																	else echo substr(strip_tags($item->nieuws_bericht . '...'), 0, 250); ?></p>
						<p><a href="<?php echo base_url('nieuws/' . $item->nieuws_url) ?>" title="Lees verder: <?php echo $item->nieuws_titel; ?>">> Lees verder</a></p>
					</div>
				</article>
			<?php endforeach; ?>
			<?php if ($aantal_paginas > 1) : ?>
				<div id="paginanummering">
					<p>
						<?php for ($i = 1; $i <= $aantal_paginas; $i++) : ?>
							<?php if ($i == $huidige_pagina) : ?>
								<a href="<?php echo base_url('nieuws/pagina/' . $i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
							<?php else : ?>
								<a href="<?php echo base_url('nieuws/pagina/' . $i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
							<?php endif; ?>
							<?php if ($i < $aantal_paginas) echo ' |'; ?>
						<?php endfor; ?>
					</p>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
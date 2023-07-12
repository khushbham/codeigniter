<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title">Meest gestelde vragen</h1>
	</div>
</section>

<section class="page">
	<div class="wrapper">
		<?php if (sizeof($vragen)) : ?>
			<ol>
				<?php foreach ($vragen as $item) : ?>
					<li><a href="#vraag<?php echo $item->vraag_ID ?>" title="Bekijk het antwoord op de vraag: <?php echo $item->vraag_titel; ?>"><?php echo $item->vraag_titel; ?></a></li>
				<?php endforeach; ?>
			</ol>
			<?php
			$nr = 1;
			foreach ($vragen as $item) :
			?>
				<article id="vraag<?php echo $item->vraag_ID ?>">
					<div class="media">
						<?php if ($item->media_ID != '') : ?>
							<?php if ($item->media_type == 'afbeelding') : ?>
								<img src="<?php echo base_url('media/afbeeldingen/thumbnail/' . $item->media_src) ?>" alt="<?php echo $item->media_titel ?>" />
							<?php else : ?>
								<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="210" height="125" id="vzvd-<?php echo $item->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $item->media_src ?>" src="//view.vzaar.com/<?php echo $item->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="210" height="125"></iframe>
							<?php endif; ?>
						<?php endif; ?>
					</div>
					<div class="vraag_antwoord">
						<h1><?php echo $nr . '. ' . $item->vraag_titel; ?></h1>
						<?php echo $item->vraag_antwoord ?>
					</div>
				</article>
			<?php
				$nr++;
			endforeach;
			?>
		<?php endif; ?>
		<p class="contact">Niet beantwoord? <a href="<?php echo base_url('contact') ?>" title="Contact">Stel een vraag!</a></p>
	</div>
</section>
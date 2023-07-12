<section class="hero hero--home" class="clearfix">
	<div class="wrapper">
		<div class="intro">
			<hgroup>
				<h1 class="hide-mobile"><?php if (!empty($content->pagina_titel)) echo $content->pagina_titel ?></h1>
				<h2><?php if (!empty($content->pagina_inleiding)) echo $content->pagina_inleiding ?></h2>
			</hgroup>
			<p><?php if (!empty($content->pagina_tekst)) echo nl2br($content->pagina_tekst) ?></p>
			<a href="<?php echo base_url('workshops') ?>" title="Bekijk het aanbod workshops" class="button button--lg button--orange">Bekijk de workshops!</a>
		</div>
	</div>
</section>

<section class="audio">
	<div class="wrapper audio--flex">
		<h2>Luister naar onze oud-cursisten!</h2>
		<div class="audio-player">
			<audio src="<?php echo $gegevens[5]->gegeven_waarde ?>" preload="auto"></audio>
		</div>
	</div>
</section>

<section class="spotlight">
	<div class="wrapper spotlight--flex">
		<div class="video">
			<?php if ($content->media_ID != '') : ?>
				<?php if ($content->media_type == 'afbeelding') : ?>
					<img src="<?php echo base_url('media/afbeeldingen/groot/' . $content->media_src) ?>" alt="<?php echo $content->media_titel ?>" />
				<?php else : ?>
					<div class="vzaar-video-embed">
						<div class="vzaar-video">
							<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" id="vzvd-<?php echo $content->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $content->media_src ?>" src="//view.vzaar.com/<?php echo $content->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen></iframe>
						</div>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<p>Foto / video binnenkort</p>
			<?php endif; ?>
		</div>

		<div class="review">
			<?php if (sizeof($reacties)) : ?>
				<?php foreach ($reacties as $item) : ?>
					<article>
						<h3 class="review-title"><a href="<?php echo base_url('reacties/' . $item->reactie_url) ?>" title="Lees verder: <?php echo $item->reactie_titel; ?>">'<?php echo $item->reactie_titel; ?>'</a></h3>
						<p class="review-body"><?php echo strip_tags(trim(substr($item->reactie_bericht, 0, 250)) . '...'); ?><br /><a href="<?php echo base_url('reacties/' . $item->reactie_url) ?>" title="Lees verder: <?php echo $item->reactie_titel; ?>">> Lees verder</a></p>
						<p class="review-author"><?php echo $item->reactie_deelnemer; ?></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
			<a href="<?php echo base_url('reacties') ?>" title="Meer reacties van cursisten" class="button button--orange">Bekijk meer reacties</a>
		</div>
	</div>
</section>

<?php if (!empty($demo_account)) : ?>
	<section id="inloggen">
		<form method="post" action="<?php echo current_url() ?>">
			<input type="hidden" name="inloggen_token" value="<?php echo $_SESSION['inloggen_token'] ?>" />
			<input type="hidden" name="inloggen_emailadres" id="inloggen_emailadres" value="DEMO@gmail.com" />
			<input type="hidden" name="inloggen_wachtwoord" value="DEMO" />
			<!--			<input type="submit" name="inloggen" value="Demo" />-->
		</form>
	</section>
<?php endif; ?>

<section class="recent">
	<div class="wrapper">

		<div class="recent-news">
			<?php if (sizeof($nieuws) > 0) : ?>
				<?php foreach ($nieuws as $item) : ?>
					<article>
						<h2 class="recent--title"><a href="<?php echo base_url('nieuws/' . $item->nieuws_url) ?>" title="Lees verder: <?php echo $item->nieuws_titel; ?>"><?php echo $item->nieuws_titel; ?></a></h2>
						<p><strong><?php $this->load->helper('tijdstip');
									echo veranderTijdstip($item->nieuws_datum) ?> -</strong> <?php if (!empty($item->nieuws_inleiding)) echo $item->nieuws_inleiding;
																								else substr(strip_tags($item->nieuws_bericht . '...'), 0, 250); ?><br /><a href="<?php echo base_url('nieuws/' . $item->nieuws_url) ?>" title="Lees verder: <?php echo $item->nieuws_titel; ?>">> Lees verder</a></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
			<a href="<?php echo base_url('nieuws') ?>" title="Meer nieuwsberichten" class="button button--orange">Meer nieuwsberichten</a>
		</div>

		<div class="recent-blog">
			<?php if (sizeof($blog) > 0) : ?>
				<?php foreach ($blog as $item) : ?>
					<article>
						<h2 class="recent--title"><a href="<?php echo base_url('blog/' . $item->blog_url) ?>" title="Lees verder: <?php echo $item->blog_titel; ?>"><?php echo $item->blog_titel; ?></a></h2>
						<p><strong><?php $this->load->helper('tijdstip');
									echo veranderTijdstip($item->blog_datum) ?> -</strong> <?php echo substr(strip_tags($item->blog_bericht . '...'), 0, 250); ?><br /><a href="<?php echo base_url('blog/' . $item->blog_url) ?>" title="Lees verder: <?php echo $item->blog_titel; ?>">> Lees verder</a></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
			<a href="<?php echo base_url('blog') ?>" title="Meer Blog berichten" class="button button--orange">Meer blog berichten</a>
		</div>
	</div>
</section>
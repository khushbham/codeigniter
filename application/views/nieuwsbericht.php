<div id="fb-root"></div>
<script>
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/nl_NL/all.js#xfbml=1&appId=251191148365733";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title">Nieuwsartikel</h1>
		<h2><?php echo $nieuwsbericht->nieuws_titel ?></h2>
	</div>
</section>

<section class="page">
	<div class="wrapper">
		<?php if ($nieuwsbericht->media_ID != '') : ?>
			<div id="media">
				<?php if ($nieuwsbericht->media_type == 'afbeelding') : ?>
					<img src="<?php echo base_url('media/afbeeldingen/groot/' . $nieuwsbericht->media_src) ?>" alt="<?php echo $nieuwsbericht->media_titel ?>" />
				<?php else : ?>
					<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="620" height="349" id="vzvd-<?php echo $nieuwsbericht->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $nieuwsbericht->media_src ?>" src="//view.vzaar.com/<?php echo $nieuwsbericht->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="620" height="349"></iframe>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<p><strong><?php $this->load->helper('tijdstip');
					echo veranderTijdstip($nieuwsbericht->nieuws_datum) ?> -</strong> <?php if (!empty($nieuwsbericht->nieuws_inleiding)) echo $nieuwsbericht->nieuws_inleiding;
																							else echo substr($nieuwsbericht->nieuws_bericht, 0, 150) . '...'; ?></p>
		<?php echo $nieuwsbericht->nieuws_bericht ?>
		<div id="share">
			<div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
			<div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a></div>
			<script>
				! function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0],
						p = /^http:/.test(d.location) ? 'http' : 'https';
					if (!d.getElementById(id)) {
						js = d.createElement(s);
						js.id = id;
						js.src = p + '://platform.twitter.com/widgets.js';
						fjs.parentNode.insertBefore(js, fjs);
					}
				}(document, 'script', 'twitter-wjs');
			</script>
		</div>
		<div id="nav_vorige_volgende">
			<p id="nav_volgende">
				<?php if ($volgende_bericht != null) : ?>
					<span><a href="<?php echo $volgende_bericht->nieuws_url ?>" title="Volgende nieuwsbericht: <?php echo $volgende_bericht->nieuws_titel ?>">
							<</a> </span> <a href="<?php echo $volgende_bericht->nieuws_url ?>" title="Volgende nieuwsbericht: <?php echo $volgende_bericht->nieuws_titel ?>"><?php echo $volgende_bericht->nieuws_titel ?>
						</a>
					<?php endif; ?>
			</p>
			<p id="nav_overzicht"><a href="<?php echo base_url('nieuws') ?>" title="Overzicht nieuwsberichten">Overzicht nieuwsberichten</a></p>
			<p id="nav_vorige">
				<?php if ($vorige_bericht != null) : ?>
					<span><a href="<?php echo $vorige_bericht->nieuws_url ?>" title="Vorige nieuwsbericht: <?php echo $vorige_bericht->nieuws_titel ?>">></a></span>
					<a href="<?php echo $vorige_bericht->nieuws_url ?>" title="Vorige nieuwsbericht: <?php echo $vorige_bericht->nieuws_titel ?>"><?php echo $vorige_bericht->nieuws_titel ?></a>
				<?php endif; ?>
			</p>
		</div>
	</div>
</section>
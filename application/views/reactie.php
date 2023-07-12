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
		<h1 class="hero-title">Reactie cursist <?php echo $reactie->reactie_deelnemer ?></h1>
	</div>
</section>

<section class="reactie">
	<div class="wrapper">
		<?php if ($reactie->media_ID != '' && $reactie->media_tonen != 'nee' && $reactie->media_type == 'afbeelding') : ?>
			<div id="media">
				<img src="<?php echo base_url('media/afbeeldingen/klein/' . $reactie->media_src) ?>" alt="<?php echo $reactie->media_titel ?>" />
			</div>
		<?php endif; ?>
		<div id="tekst">
			<h2>'<?php echo $reactie->reactie_titel ?>'</h2>
			<?php if ($reactie->media_ID != '' && $reactie->media_type == 'video') : ?>
				<div class="vzaar-video-embed">
					<div class="vzaar-video">
						<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" id="vzvd-<?php echo $reactie->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $reactie->media_src ?>" src="//view.vzaar.com/<?php echo $reactie->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen></iframe>
					</div>
				</div>
				<?php if ($reactie->media_link != '') : ?>
					<p><a href="<?php echo $reactie->media_link ?>" title="Bekijk de volledige video" target="_blank">Bekijk de volledige video</a></p>
				<?php endif; ?>
			<?php endif; ?>
			<?php echo $reactie->reactie_bericht ?>
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
			<p><a href="<?php echo base_url('reacties') ?>" title="Bekijk al de reacties" class="meer">> Bekijk al de reacties</a></p>
		</div>
	</div>
</section>
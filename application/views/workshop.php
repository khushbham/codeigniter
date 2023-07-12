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
		<h1 class="hero-title"><?php echo $workshop->workshop_titel ?></h1>
	</div>
</section>

<section class="workshop">
	<div class="wrapper">
		<div class="workshop-content">
			<?php echo $workshop->workshop_beschrijving ?>
			<div id="share">
				<div class="fb-share-button" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Flocalhost%2F&amp;src=sdkpreparse">Delen</a></div>
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
		</div>

		<div class="workshop-sidebar">
			<div class="workshop-details">
				<?php if ($workshop->media_ID != '') : ?>
					<div class="workshop-media">
						<?php if ($workshop->media_type == 'afbeelding') : ?>
							<img src="<?php echo base_url('media/afbeeldingen/medium/' . $workshop->media_src) ?>" alt="<?php echo $workshop->media_titel ?>" />
						<?php else : ?>
							<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="620" height="349" id="vzvd-<?php echo $workshop->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $workshop->media_src ?>" src="//view.vzaar.com/<?php echo $workshop->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="620" height="349"></iframe>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<p class="type">
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
				</p>
				<?php if ($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online') : ?>
					<?php if (sizeof($groepen) > 0) : ?>
						<p class="datum">Start <?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?></p>
					<?php else : ?>
						<p class="datum">Binnenkort nieuwe lesdata!</p>
					<?php endif; ?>
				<?php else : ?>
					<?php if ($workshop->workshop_startdatum != '0000-00-00 00:00:00' && strtotime($workshop->workshop_startdatum) > time()) : ?>
						<p class="datum">Start <?php echo date('d-m-Y', strtotime($workshop->workshop_startdatum)) ?></p>
					<?php else : ?>
						<p class="datum">Direct beginnen</p>
					<?php endif; ?>
				<?php endif; ?>
				<p class="kosten"><strong>Kosten: &euro; <?php echo $workshop->workshop_prijs ?>,-</strong></p>
				<?php if ($workshop->workshop_ID != 9 && $workshop->workshop_ID != 71 && $workshop->workshop_in3 != 0) : ?>
					<p style="font-weight: bold; color: #000;">Of betaal in drie termijnen van <?php echo '€' .  money_format('%.2n', $workshop->workshop_prijs / 3); ?>.</p>
				<?php elseif (($workshop->workshop_ID == 9 || $workshop->workshop_niveau == 5) && $workshop->workshop_in3 != 0) : ?>
					<p style="font-weight: bold; color: #000;">Of betaal in drie termijnen van <?php echo '€' .  money_format('%.2n', $workshop->workshop_prijs / 3); ?>.</p>
				<?php endif; ?>
				<?php if (($workshop->workshop_type == 'individueel' || sizeof($groepen) > 0) && $workshop->workshop_ID != 9 && $workshop->workshop_ID != 71) { ?>
					<?php if (!empty($plekken_over) && $plekken_over > 0 && $plekken_over <= 3 && $plekken_over != 1) { ?>
						<span class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $plekken_over ?> plekken beschikbaar</span>
					<?php } elseif ($plekken_over == 1) { ?>
						<span class="plekken_beschikbaar"><img src="<?php echo base_url('assets/images/wall-clock.png') ?>" /> Nog <?php echo $plekken_over ?> plek beschikbaar</span>
					<?php } ?>
					<a href="<?php echo $aanmeld_url ?>" title="Aanmelden voor de workshop <?php echo $workshop->workshop_titel ?>" class="button button--orange button--aanmelden">Aanmelden workshop</a>
				<?php } elseif ($workshop->workshop_ID != 9 && $workshop->workshop_ID != 71) { ?>
					<p>Wil je een e-mail ontvangen wanneer we een nieuwe <?php echo $workshop->workshop_titel ?> online hebben gezet? Vul dan hieronder je e-mailadres in!</p>
					<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
					<style type="text/css">
						#mc_embed_signup {
							clear: left;
							font: 14px Helvetica, Arial, sans-serif;
						}

						#mc_embed_signup #mc-embedded-subscribe {
							background-color: #ff9c00;
						}

						#mc_embed_signup #mc-embedded-subscribe:hover {
							background-color: #ff9c00;
						}
					</style>
					<div id="mc_embed_signup">
						<form action="//localhost.us7.list-manage.com/subscribe/post?u=d21c67b4cef8245552a13406b&amp;id=76cbccab02" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<div id="mc_embed_signup_scroll">
								<div class="mc-field-group">
									<label for="mce-EMAIL">E-mailadres </label>
									<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
								</div>
								<input type="hidden" value="<?php echo $workshop->workshop_titel ?>" name="WORKSHOP" class="" id="mce-WORKSHOP">
								<input type="hidden" value="geinteresseerd" name="SOORT" class="required" id="mce-SOORT">
								<input type="hidden" value="" name="DATUM" class="" id="mce-DATUM">
								<div id="mce-responses" class="clear">
									<div class="response" id="mce-error-response" style="display:none"></div>
									<div class="response" id="mce-success-response" style="display:none"></div>
								</div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
								<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_d21c67b4cef8245552a13406b_e19a6fa3e4" tabindex="-1" value=""></div>
								<div class="clear"><input type="submit" value="Houd mij op de hoogte" name="subscribe" id="mc-embedded-subscribe" class="button button--orange"></div>
							</div>
						</form>
					</div>
					<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
					<script type='text/javascript'>
						(function($) {
							window.fnames = new Array();
							window.ftypes = new Array();
							fnames[0] = 'EMAIL';
							ftypes[0] = 'email';
							fnames[1] = 'WORKSHOP';
							ftypes[1] = 'text';
						}(jQuery));
						var $mcj = jQuery.noConflict(true);
					</script>
					<!--End mc_embed_signup-->
				<?php } ?>
				<?php if ($plekken_over > 0 && $workshop->workshop_ID != 9 && $workshop->workshop_ID != 71) { ?><br>
					<?php if ($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online') : ?>
						<?php if (sizeof($groepen) > 0) : ?>
							<p class="andere-data-form" onclick="showVerhinderd()"><i>Komt <?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?> je niet uit? Klik dan hier!</i></p>
						<?php endif; ?>
					<?php else : ?>
						<?php if ($workshop->workshop_startdatum != '0000-00-00 00:00:00' && strtotime($workshop->workshop_startdatum) > time()) : ?>
							<p onclick="showVerhinderd()"><i>Komt <?php echo date('d-m-Y', strtotime($workshop->workshop_startdatum)) ?> je niet uit? Klik dan hier!</i></p>
						<?php endif; ?>
					<?php endif; ?>
					<!-- Begin MailChimp Signup Form -->
					<div id="verhinderd">
						<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
						<style type="text/css">
							#mc_embed_signup {
								background: #fff;
								clear: left;
								font: 14px Helvetica, Arial, sans-serif;
							}

							#mc_embed_signup #mc-embedded-subscribe {
								background-color: #18b5cf;
							}

							#mc_embed_signup #mc-embedded-subscribe:hover {
								background-color: #009dc6;
							}
						</style>
						<div id="mc_embed_signup">
							<form action="//localhost.us7.list-manage.com/subscribe/post?u=d21c67b4cef8245552a13406b&amp;id=76cbccab02" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
								<div id="mc_embed_signup_scroll">
									<div class="mc-field-group">
										<label for="mce-EMAIL">E-mailadres </label>
										<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
									</div>
									<input type="hidden" value="<?php echo $workshop->workshop_titel ?>" name="WORKSHOP" class="" id="mce-WORKSHOP">
									<input type="hidden" value="geinteresseerd" name="SOORT" class="required" id="mce-SOORT">
									<?php if ($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online') : ?>
										<?php if (sizeof($groepen) > 0) : ?>
											<input type="hidden" value="<?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?>" name="DATUM" class="" id="mce-DATUM">
										<?php endif; ?>
									<?php else : ?>
										<?php if ($workshop->workshop_startdatum != '0000-00-00 00:00:00' && strtotime($workshop->workshop_startdatum) > time()) : ?>
											<input type="hidden" value="<?php echo date('d-m-Y', strtotime($workshop->workshop_startdatum)) ?>" name="DATUM" class="" id="mce-DATUM">
										<?php endif; ?>
									<?php endif; ?>
									<div id="mce-responses" class="clear">
										<div class="response" id="mce-error-response" style="display:none"></div>
										<div class="response" id="mce-success-response" style="display:none"></div>
									</div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
									<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_d21c67b4cef8245552a13406b_e19a6fa3e4" tabindex="-1" value=""></div>
									<div class="clear"><input type="submit" value="Houd mij op de hoogte" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
								</div>
							</form>
						</div>
						<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
						<script type='text/javascript'>
							(function($) {
								window.fnames = new Array();
								window.ftypes = new Array();
								fnames[0] = 'EMAIL';
								ftypes[0] = 'email';
								fnames[1] = 'WORKSHOP';
								ftypes[1] = 'text';
							}(jQuery));
							var $mcj = jQuery.noConflict(true);
						</script>
					</div>
					<!--End mc_embed_signup-->
				<?php } ?>
				<p><strong>Locatie</strong><br />
					<?php echo $workshop->workshop_locatie ?></p>
				<p><strong>Duur</strong><br />
					<?php echo $workshop->workshop_duur ?></p>
				<?php if ($workshop->workshop_grootte_zichtbaar == 1 && ($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online')) : ?>
					<p><strong>Capaciteit</strong><br />
						<?php echo $workshop->workshop_capaciteit ?> deelnemers per groep</p>
				<?php endif; ?>
				<?php if (!empty($workshop->workshop_toelatingseisen)) : ?><p><strong>Toelatingseisen</strong><br /><?php echo $workshop->workshop_toelatingseisen ?></p><?php endif; ?>
				<?php if ($workshop->workshop_ID != 9 && $workshop->workshop_ID != 71) : ?>
					<?php if ($workshop->workshop_ID == '6') : ?>
						<a href="<?php echo base_url('aanmelden/intake/' . $workshop->workshop_url) ?>" title="Intake" class="button button--orange">Aanmelden intake</a>
						<p class="kosten_intake_stemtest">&euro; <?php echo $workshop->workshop_stemtest_prijs ?>,-</p>
						<p><strong>Intake</strong><br />Niet verplicht</p>
					<?php elseif ($workshop->workshop_stemtest == 'ja' && $workshop->workshop_ID != 9 && $workshop->workshop_ID != 71) : ?>
						<a href="<?php echo base_url('aanmelden/stemtest/' . $workshop->workshop_url) ?>" title="Stemtest verplicht" style="margin-bottom: 1em;" class="button button--orange">Doe de stemtest (&euro; <?php echo $workshop->workshop_stemtest_prijs ?>,-)</a>
					<?php endif; ?>
				<?php endif; ?>
				<?php if (!empty($workshop->workshop_inclusief)) : ?><p><strong>Inclusief</strong><br /><?php echo $workshop->workshop_inclusief ?></p><?php endif; ?>
				<?php if (!empty($workshop->workshop_exclusief)) : ?><p><strong>Exclusief</strong><br /><?php echo $workshop->workshop_exclusief ?></p><?php endif; ?>
			</div>

			<?php if ($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online') : ?>
				<div class="workshop-data">
					<p><strong>Overzicht lesdata</strong></p>
					<?php if (sizeof($groep_lessen) > 0) : ?>
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<?php
							$les = 1;
							$groep = 0;
							$groep_ID = 0;
							$aantal_lessen = 0;
							$temp_groep_ID = 0;

							if (!empty($groep_lessen)) {
								foreach ($groep_lessen as $item_les) {
									if ($temp_groep_ID == 0) {
										$temp_groep_ID = $item_les->groep_ID;
									}

									if ($temp_groep_ID == $item_les->groep_ID) {
										$aantal_lessen++;
										$temp_groep_ID = $item_les->groep_ID;
									}
								}
							}

							foreach ($groep_lessen as $groep_les) :

								$maanden = array('', 'januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');
								$groep_les_datum = explode(' ', $groep_les->groep_les_datum);
								$groep_les_datum = explode('-', $groep_les_datum[0]);
								$datum = intval($groep_les_datum[2]) . ' ' . $maanden[intval($groep_les_datum[1])];

								if ($groep_ID != $groep_les->groep_ID) {
									$groep++;
									$les = 1;
								}
							?>
								<?php if ($les == 1 && !empty($groep_les)) { ?>
									<tr>
										<td colspan="2"><?php if ($groep_les->groep_titel != "") echo $groep_les->groep_titel;
														else echo "Groep" ?></td>
									</tr>
								<?php } ?>
								<tr>
									<td class="les"><?php if ($aantal_lessen > 1) echo "Les " . $les . " |" ?> <?php echo $datum ?></td>
									<?php if (empty($groep_les->les_type)) { ?>
										<td class="locatie <?php echo strtolower($groep_les->les_locatie) ?>"><?php echo ucfirst($groep_les->les_locatie) ?></td>
									<?php } else { ?>
										<td class="locatie"><?php echo ucfirst($groep_les->les_type) ?></td>
									<?php } ?>
								</tr>
							<?php
								$les++;
								$groep_ID = $groep_les->groep_ID;
							endforeach;
							?>
						</table>
					<?php else : ?>
						<p><em>Geen lesdata bekend</em></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>



<script>
	fbq('track', 'ViewContent', {
		content_name: '<?php echo $workshop->workshop_titel ?>',
		content_category: 'Workshop',
		content_ids: [<?php echo $workshop->workshop_ID ?>],
		content_type: 'Product',
		value: <?php echo $workshop->workshop_prijs ?>,
		currency: 'EUR'
	});
</script>
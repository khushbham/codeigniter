<section class="hero" class="clearfix">
	<div class="wrapper">
		<?php if ($aanmelden_voor == 'intake') : ?>
			<h1 class="hero-title">Aanmelden intake <?php echo $workshop->workshop_titel ?></h1>
		<?php elseif ($aanmelden_voor == 'stemtest') : ?>
			<h1 class="hero-title">Aanmelden stemtest <?php echo $workshop->workshop_titel ?></h1>
		<?php elseif ($aanmelden_voor == 'kennismakingsworkshop') : ?>
			<h1 class="hero-title">Aanmelden kennismakingsworkshop <?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></h1>
		<?php else : ?>
			<h1 class="hero-title">Aanmelden <?php echo $workshop->workshop_titel ?></h1>
		<?php endif; ?>
	</div>
</section>

<section id="aanmelden">
	<div class="wrapper">

		<!-- STAPPEN -->

		<div id="stappen">
			<ol>
				<li><a href="<?php echo base_url('aanmelden/' . $aanmelden_voor . '/' . $workshop->workshop_url) ?>" title="Aanmelden">Aanmelden</a></li>
				<li class="active">Producten</li>
				<li>Bevestigen</li>
				<li>Betalen</li>
				<li>Aangemeld</li>
			</ol>
		</div>


		<!-- TITEL EN UITLEG -->

		<div id="uitleg">
			<h3><?php echo $content->pagina_inleiding ?></h3>
			<?php echo $content->pagina_tekst ?>
		</div>

		<!-- OVERZICHT PRODUCTEN -->

		<section id="producten">
			<form method="post" action="<?php echo base_url('aanmelden/producten') ?>">
				<?php if (sizeof($producten)) : ?>
					<?php foreach ($producten as $product) : ?>
						<article>
							<div class="clearfix">
								<div class="media">
									<?php if ($product->media_ID != '') : ?>
										<?php if ($product->media_type == 'afbeelding') : ?>
											<img src="<?php echo base_url('media/afbeeldingen/klein/' . $product->media_src) ?>" alt="<?php echo $product->media_titel ?>" />
										<?php else : ?>
											<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="300" height="185" id="vzvd-<?php echo $product->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $product->media_src ?>" src="//view.vzaar.com/<?php echo $product->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="300" height="185"></iframe>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								<div class="tekst">
									<h1><?php echo $product->product_naam; ?></a></h1>
									<p><?php echo $product->product_beschrijving ?></p>
								</div>
							</div>
							<div class="clearfix">
								<?php if (!empty($product->korting) && (!empty($product->upselling_korting))) { ?>
									<?php if ($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if (!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if (!empty($product->korting) || !empty($product->upselling_korting_prijs)) {
															echo 'Normaal: €';
														} ?><strong><?php echo money_format('%.2n', $product->product_prijs); ?></strong></p>
									<p class="prijs"><strong><?php echo 'Met Couponcode + bundelkorting: €' . money_format('%.2n', $product->korting_prijs); ?></strong></p>
								<?php } elseif (!empty($product->upselling_korting)) {  ?>
									<?php if ($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if (!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if (!empty($product->korting) || !empty($product->upselling_korting_prijs)) {
															echo 'Normaal: €';
														} ?><strong><?php echo money_format('%.2n', $product->product_prijs); ?></strong></p>
									<p class="prijs"><strong><?php echo 'Met bundelkorting: €' . money_format('%.2n', $product->upselling_korting_prijs); ?></strong></p>
								<?php } elseif (!empty($product->korting)) {  ?>
									<?php if ($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if (!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if (!empty($product->korting) || !empty($product->upselling_korting_prijs)) {
															echo 'Normaal: €';
														} ?><?php echo money_format('%.2n', $product->product_prijs); ?></p>
									<p class="prijs"><strong><?php echo 'Met korting: €' . money_format('%.2n', $product->korting_prijs); ?></strong></p>
								<?php } else { ?>
									<?php if ($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if (!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><strong>€<?php echo money_format('%.2n', $product->product_prijs); ?></strong></p>
								<?php } ?>
							</div>
						</article>
					<?php endforeach; ?>
				<?php endif; ?>
				<div id="afleveradres">
					<p><strong>Afleveradres invullen mits afwijkend van woonadres.</strong></p>
					<p><label for="aanmelden_afleveren_adres">Adres</label><input type="text" name="aanmelden_afleveren_adres" id="aanmelden_afleveren_adres" value="<?php echo $aanmelden_afleveren_adres ?>" /><?php if (!empty($aanmelden_afleveren_adres_feedback)) : ?><span class="feedback"><?php echo $aanmelden_afleveren_adres_feedback ?></span><?php endif; ?></p>
					<p><label for="aanmelden_afleveren_postcode">Postcode</label><input type="text" name="aanmelden_afleveren_postcode" id="aanmelden_afleveren_postcode" value="<?php echo $aanmelden_afleveren_postcode ?>" /><?php if (!empty($aanmelden_afleveren_postcode_feedback)) : ?><span class="feedback"><?php echo $aanmelden_afleveren_postcode_feedback ?></span><?php endif; ?></p>
					<p><label for="aanmelden_afleveren_plaats">Plaats</label><input type="text" name="aanmelden_afleveren_plaats" id="aanmelden_afleveren_plaats" value="<?php echo $aanmelden_afleveren_plaats ?>" /><?php if (!empty($aanmelden_afleveren_plaats_feedback)) : ?><span class="feedback"><?php echo $aanmelden_afleveren_plaats_feedback ?></span><?php endif; ?></p>
				</div>
				<p><input type="submit" value="Volgende stap" class="button" /></p>
			</form>
		</section>
	</div>
</section>
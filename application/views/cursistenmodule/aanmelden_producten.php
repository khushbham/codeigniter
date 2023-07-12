<style type="text/css">
	.volgende-stap{
		display: block;
	    background: url('<?php echo base_url(); ?>/assets/images/button-achtergrond-smal.png') 0 0 no-repeat;
	    width: 260px;
	    height: 55px;
	    margin: 20px 0 135px 405px;
	    padding: 0 20px 20px 0;
	    border: 0;
	    border-radius: 0;
	    outline: 0;
	    cursor: pointer;
	    font-size: 20px;
	    line-height: 55px;
	    text-align: center;
	    color: #fff;
	}
</style>
<section id="aanmelden">

	<!-- STAPPEN -->

	<div id="stappen">
		<ol>
			<li><a href="<?php echo base_url('cursistenmodule/aanmelden/'.$aanmelden_voor.'/'.$workshop->workshop_url) ?>" title="Aanmelden">Aanmelden</a></li>
			<li class="active">Producten</li>
			<li>Bevestigen</li>
			<li>Betalen</li>
			<li>Aangemeld</li>
		</ol>
	</div>


	<!-- TITEL EN UITLEG -->

	<div id="uitleg">
		<h1><?php echo $content->pagina_titel ?></h1>
		<?php if(empty($workshop->workshop_producten_tekst)) { ?>
			<h3><?php echo $content->pagina_inleiding ?></h3>
			<?php echo $content->pagina_tekst ?>
		<?php } else { ?>
			<?php echo $workshop->workshop_producten_tekst ?>
		<?php } ?>
	</div>

	<!-- OVERZICHT PRODUCTEN -->

	<section id="producten">
		<form method="post" action="<?php echo base_url('cursistenmodule/aanmelden/producten') ?>">
			<?php if(sizeof($producten)): ?>
				<?php foreach($producten as $product): ?>
					<?php if($product->product_huur == 0) { ?>
					<article>
						<div class="clearfix" style="padding-left: 0">
							<div class="media">
								<?php if($product->media_ID != ''): ?>
									<?php if($product->media_type == 'afbeelding'): ?>
										<img src="<?php echo base_url('media/afbeeldingen/klein/'.$product->media_src) ?>" alt="<?php echo $product->media_titel ?>" />
									<?php else: ?>
										<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="300" height="185" id="vzvd-<?php echo $product->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $product->media_src ?>" src="//view.vzaar.com/<?php echo $product->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="300" height="185"></iframe>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<div class="tekst">
								<h1><?php echo $product->product_naam; ?></a></h1>
								<p><?php echo $product->product_beschrijving ?></p>
							</div>
						</div>
						<div class="clearfix" style="padding-left: 0">
								<?php if(!empty($product->korting) && (!empty($product->upselling_korting))) { ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if(!empty($product->korting) || !empty($product->upselling_korting_prijs)) { echo 'Normaal: €'; } ?><strong><?php echo money_format('%.2n' ,$product->product_prijs); ?></strong></p>
									<p class="prijs"><strong><?php echo 'Met couponcode + bundelkorting: €' . money_format('%.2n' ,$product->korting_prijs); ?></strong></p>
								<?php } elseif(!empty($product->upselling_korting)) {  ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if(!empty($product->korting) || !empty($product->upselling_korting_prijs)) { echo 'Normaal: €'; } ?><strong><?php echo money_format('%.2n' ,$product->product_prijs); ?></strong></p>
									<p class="prijs"><strong><?php echo 'Met bundelkorting: €' . money_format('%.2n' ,$product->upselling_korting_prijs); ?></strong></p>
								<?php } elseif(!empty($product->korting)) {  ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if(!empty($product->korting) || !empty($product->upselling_korting_prijs)) { echo 'Normaal: €'; } ?><?php echo money_format('%.2n' ,$product->product_prijs); ?></p>
									<p class="prijs"><strong><?php echo 'Met korting: €' . money_format('%.2n' ,$product->korting_prijs); ?></strong></p>
								<?php } else { ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze erbij</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><strong>€<?php echo money_format('%.2n' ,$product->product_prijs); ?></strong></p>
								<?php } ?>
						</div>
					</article>



					<?php } else { ?>

					<!-- NORMAAL -->
						<article>
						<div class="clearfix" style="padding-left: 0">
							<div class="media">
								<?php if($product->media_ID != ''): ?>
									<?php if($product->media_type == 'afbeelding'): ?>
										<img src="<?php echo base_url('media/afbeeldingen/klein/'.$product->media_src) ?>" alt="<?php echo $product->media_titel ?>" />
									<?php else: ?>
										<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="300" height="185" id="vzvd-<?php echo $product->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $product->media_src ?>" src="//view.vzaar.com/<?php echo $product->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="300" height="185"></iframe>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<div class="tekst">
								<h1><?php echo $product->product_naam; ?></a></h1>
								<p><?php echo $product->product_beschrijving ?></p>
							</div>
						</div>
						<div class="clearfix" style="padding-left: 0">
								<?php if(!empty($product->korting) && (!empty($product->upselling_korting))) { ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze kopen</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if(!empty($product->korting) || !empty($product->upselling_korting_prijs)) { echo 'Normaal: €'; } ?><strong><?php echo money_format('%.2n' ,$product->product_prijs); ?></strong></p>
									<p class="prijs"><strong><?php echo 'Met couponcode + bundelkorting: €' . money_format('%.2n' ,$product->korting_prijs); ?></strong></p>
								<?php } elseif(!empty($product->upselling_korting)) {  ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze kopen</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if(!empty($product->korting) || !empty($product->upselling_korting_prijs)) { echo 'Normaal: €'; } ?><strong><?php echo money_format('%.2n' ,$product->product_prijs); ?></strong></p>
									<p class="prijs"><strong><?php echo 'Met bundelkorting: €' . money_format('%.2n' ,$product->upselling_korting_prijs); ?></strong></p>
								<?php } elseif(!empty($product->korting)) {  ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze kopen</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><?php if(!empty($product->korting) || !empty($product->upselling_korting_prijs)) { echo 'Normaal: €'; } ?><?php echo money_format('%.2n' ,$product->product_prijs); ?></p>
									<p class="prijs"><strong><?php echo 'Met korting: €' . money_format('%.2n' ,$product->korting_prijs); ?></strong></p>
								<?php } else { ?>
									<?php if($product->wanneer_beschikbaar == 'altijd') { ?>
										<p class="bestellen"><input type="checkbox" name="producten[]" onclick="removecheckhuur()" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> /> Ja, Ik wil deze kopen</p>
									<?php } else { ?>
										<p class="bestellen">Dit product is beschikbaar na de workshop.</p>
									<?php } ?>
									<p class="prijs"><strong>€<?php echo money_format('%.2n' ,$product->product_prijs); ?></strong></p>
								<?php } ?>
						</div>
					</article>

				<!-- HUUR -->
					<article>
						<div class="clearfix" style="padding-left: 0">
							<div class="media">
								<?php if($product->media_ID != ''): ?>
									<?php if($product->media_type == 'afbeelding'): ?>
										<img src="<?php echo base_url('media/afbeeldingen/klein/'.$product->media_src) ?>" alt="<?php echo $product->media_titel ?>" />
									<?php else: ?>
										<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="300" height="185" id="vzvd-<?php echo $product->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $product->media_src ?>" src="//view.vzaar.com/<?php echo $product->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="300" height="185"></iframe>
									<?php endif; ?>
								<?php endif; ?>
							</div>
							<div class="tekst">
								<h1><?php echo $product->product_naam . ' in bruikleen nemen'; ?></a></h1>
								<p><?php if(!empty($product->product_beschrijving_huur)) echo $product->product_beschrijving_huur; else echo $product->product_beschrijving; ?></p>
							</div>
						</div>
						<div class="clearfix" style="padding-left: 0" onclick="showBruikleen()">
							<p class="bestellen"><input type="checkbox"  name="huur_producten[]" id="producthuur-<?php echo $product->product_ID ?>"  value="<?php echo $product->product_ID ?>" /> Ik wil het product graag in bruikleen nemen</p>
							<p class="prijs"><strong><?php echo 'Borg: €' . money_format('%.2n' ,$product->product_borg); ?></strong></p>
						</div>
					</article>
					<?php } ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if($huur_producten) { ?>
				<p class="akkoord_container"><span class="akkoord" style="padding: 10px 0 50px 160px; font-size:18px"><input type="checkbox" name="huur_akkoord" id="huur_akkoord" <?php if ($huur_akkoord) echo 'checked'; ?> /> Ik ga akkoord met de <a href="<?php echo base_url('bruikleenovereenkomst') ?>" title="Bekijk de bruikleenovereenkomst in een nieuw tabblad / venster" target="_blank">bruikleenovereenkomst</a></span><?php if (!empty($huur_producten_feedback)) : ?><span class="feedback"><?php echo $huur_producten_feedback ?></span><?php endif; ?></p>
			<?php } ?>

			<p><?php if(!empty($aanmelden_producten_feedback)): ?><span class="feedback"><?php echo $aanmelden_producten_feedback ?></span><?php endif; ?></p>
			<div id="afleveradres">
				<p><strong>Afleveradres invullen mits afwijkend van woonadres.</strong></p>
				<p><label for="aanmelden_afleveren_adres">Adres</label><input type="text" name="aanmelden_afleveren_adres" id="aanmelden_afleveren_adres" value="<?php echo $aanmelden_afleveren_adres ?>" /><?php if(!empty($aanmelden_afleveren_adres_feedback)): ?><span class="feedback"><?php echo $aanmelden_afleveren_adres_feedback ?></span><?php endif; ?></p>
				<p><label for="aanmelden_afleveren_postcode">Postcode</label><input type="text" name="aanmelden_afleveren_postcode" id="aanmelden_afleveren_postcode" value="<?php echo $aanmelden_afleveren_postcode ?>" /><?php if(!empty($aanmelden_afleveren_postcode_feedback)): ?><span class="feedback"><?php echo $aanmelden_afleveren_postcode_feedback ?></span><?php endif; ?></p>
				<p><label for="aanmelden_afleveren_plaats">Plaats</label><input type="text" name="aanmelden_afleveren_plaats" id="aanmelden_afleveren_plaats" value="<?php echo $aanmelden_afleveren_plaats ?>" /><?php if(!empty($aanmelden_afleveren_plaats_feedback)): ?><span class="feedback"><?php echo $aanmelden_afleveren_plaats_feedback ?></span><?php endif; ?></p>
			</div>
			<!--<p><input type="submit" value="Volgende stap" class="button" /></p>-->
			<p><input type="submit" value="Volgende stap" class="button <?php if($huur_producten) { echo 'volgende-stap'; } ?>" /></p>
		</form>
	</section>
</section>

<?php if($huur_producten) { ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">

jQuery(document).ready(function () {
    jQuery('.volgende-stap').click(function() {
      checked = jQuery("input[type=checkbox]:checked").length;

      if(!checked) {
        alert("Maak een keuze tussen koop of bruikleen voor de thuisstudio om verder te gaan.");

        return false;
      }else{
      	$(this).attr('type','submit');
      }

    });
});
</script>
<?php } ?>

<div id="producten">
	<!-- TITEL EN UITLEG -->

	<div id="uitleg">
		<h1><?php echo $content->pagina_titel ?></h1>
		<h3><?php echo $content->pagina_inleiding ?></h3>
		<?php echo $content->pagina_tekst ?>
	</div>
	<!-- OVERZICHT PRODUCTEN -->
	<div id="producten">
		<form method="post" action="<?php echo base_url('cursistenmodule/bestellen') ?>">
			<?php if(sizeof($producten)): ?>
				<?php foreach($producten as $product): ?>
					<article>
						<?php if($product->media_ID != ''): ?>
							<?php if($product->media_type == 'afbeelding'): ?>
								<img src="<?php echo base_url('media/afbeeldingen/klein/'.$product->media_src) ?>" class="uitgelicht" alt="<?php echo $product->media_titel ?>" />
							<?php else: ?>
								<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="300" height="185" id="vzvd-<?php echo $product->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $product->media_src ?>" src="//view.vzaar.com/<?php echo $product->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="300" height="185"></iframe>
							<?php endif; ?>
						<?php endif; ?>

						<hgroup>
							<h1><?php echo $product->product_naam ?></a></h1>
						</hgroup>

						<div class="product-beschrijving">
							<div class="product-beschrijving-kort-js">
								<?php if(!empty($product->product_beschrijving)): ?>
									<?php echo substr($product->product_beschrijving, 0, 150).'...'; ?>
								<?php endif; ?>
							</div>

							<div class="product-beschrijving-lang-js">
								<?php if(!empty($product->product_beschrijving)): ?>
									<?php echo $product->product_beschrijving ?>
								<?php endif; ?>
							</div>
							<p><a href="#" class="product-meerinfo-js">Klik hier voor meer informatie</a></p>
						</div>

						<div class="clearfix">
							<?php 
							if($product->product_prijs_naderhand == 0){
							?>
							<p class="prijs"><strong>&euro; <?php echo $product->product_prijs; ?>,00</strong></p>
							<?php
							}else{
							?>
							<p class="prijs"><strong>&euro; <?php echo $product->product_prijs_naderhand; ?>,00</								strong></p>
							<?php
							}
							?>
							<?php if(!isset($product->laatste_les) || date('d-m-Y H:i:s', strtotime($product->laatste_les)) < date('d-m-Y H:i:s')) { ?>
							<p class="bestellen"><input type="checkbox" name="producten[]" id="product-<?php echo $product->product_ID ?>" value="<?php echo $product->product_ID ?>" <?php if(!empty($aanmelden_producten) && in_array($product->product_ID, $aanmelden_producten)) echo 'checked'; ?> />Bestellen</p>
							<?php } else { echo 'Dit product is na de workshop beschikbaar.';  } ?>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
			<p><input type="submit" value="Volgende stap" class="button" /></p>
		</form>
	</div>
</div>

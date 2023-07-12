<section id="bestellen">
	<!-- STAPPEN -->

	<div id="stappen">
		<ol>
			<li class="active"><a href="<?php echo base_url('cursistenmodule/bestellen') ?>" title="Afleveradres">Afleveradres</a></li>
			<li>Bevestigen</li>
			<li>Betalen</li>
			<li>Besteld</li>
		</ol>
	</div>
	<section id="producten">
		<form method="post" name="bestellen_formulier" action="<?php echo base_url('cursistenmodule/bestellen') ?>">
			<p><label for="bestellen_kortingscode">Couponcode</label><input type="text" name="bestellen_kortingscode" id="bestellen_kortingscode" value="<?php echo $bestellen_kortingscode ?>" /><?php if(!empty($bestellen_kortingscode_feedback)): ?><span class="feedback"><?php echo $bestellen_kortingscode_feedback ?></span><?php endif; ?></p><br>
	<!-- OVERZICHT PRODUCTEN -->


			<div id="afleveradres">
				<p><strong>Ander afleveradres? Vul deze dan hieronder in. Klik anders direct op 'volgende stap'.</strong></p>
				<p><label for="bestellen_afleveren_adres">Adres</label><input type="text" name="bestellen_afleveren_adres" id="bestellen_afleveren_adres" value="<?php echo $bestellen_afleveren_adres ?>" /><?php if(!empty($bestellen_afleveren_adres_feedback)): ?><span class="feedback"><?php echo $bestellen_afleveren_adres_feedback ?></span><?php endif; ?></p>
				<p><label for="bestellen_afleveren_postcode">Postcode</label><input type="text" name="bestellen_afleveren_postcode" id="bestellen_afleveren_postcode" value="<?php echo $bestellen_afleveren_postcode ?>" /><?php if(!empty($bestellen_afleveren_postcode_feedback)): ?><span class="feedback"><?php echo $bestellen_afleveren_postcode_feedback ?></span><?php endif; ?></p>
				<p><label for="bestellen_afleveren_plaats">Plaats</label><input type="text" name="bestellen_afleveren_plaats" id="bestellen_afleveren_plaats" value="<?php echo $bestellen_afleveren_plaats ?>" /><?php if(!empty($bestellen_afleveren_plaats_feedback)): ?><span class="feedback"><?php echo $bestellen_afleveren_plaats_feedback ?></span><?php endif; ?></p>
			</div>
			<p><input type="submit" value="Volgende stap" class="button" /></p>
		</form>
	</section>
</section>

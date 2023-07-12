<section id="aanmelden">
	<!-- STAPPEN -->

	<div id="stappen">
		<ol>
			<li><a href="<?php echo base_url('cursistenmodule/aanmelden/'.$aanmelden_voor.'/'.$workshop->workshop_url) ?>" title="Aanmelden">Aanmelden</a></li>
			<li class="active">Annuleringsverzekering</li>
			<li>Producten</li>
			<li>Bevestigen</li>
			<li>Betalen</li>
			<li>Aangemeld</li>
		</ol>
	</div>

	<!-- TITEL EN UITLEG -->

	<div id="uitleg">
		<h1><?php echo $content->pagina_titel ?></h1>
		<h3><?php echo $content->pagina_inleiding ?></h3>
		<?php echo $content->pagina_tekst ?>
		<p><a href="<?php echo base_url('aanmelden/annuleringsverzekering_detail'); ?>" target="_blank">Meer informatie</a></p>
	</div>

	<!-- OVERZICHT PRODUCTEN -->

	<section id="producten">
	<article>
		<form method="post" action="<?php echo base_url('cursistenmodule/aanmelden/annuleringsverzekering') ?>">
		<div class="clearfix" style="padding: 0;">
			<p class="bestellen">Wil je een annuleringsverzekering toevoegen aan je bestelling?</p><p class="prijs" style="padding: 0; float: left"><strong><?php echo 'Prijs: €' . money_format('%.2n' ,$annulering_prijs); ?></strong></p>
		</div>
			<p class="annulering_prijs"><strong><input type="radio" name="aanmelden_annuleringsverzekering" value="Ja" <?php if($aanmelden_annuleringsverzekering == 'Ja') echo 'checked'; ?> /> Ja <input type="radio" name="aanmelden_annuleringsverzekering" value="Nee" <?php if($aanmelden_annuleringsverzekering == 'Nee') echo 'checked'; ?> /> Nee </label></strong></p>
			<p><input type="submit" value="Volgende stap" class="button" /></p>
		</form>
		</article>
	</section>
</section>
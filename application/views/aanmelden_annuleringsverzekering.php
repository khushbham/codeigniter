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
		<h2><?php echo $content->pagina_titel ?></h2>
	</div>
</section>

<section id="aanmelden">
	<div class="wrapper">
		<!-- STAPPEN -->

		<div id="stappen">
			<ol>
				<li><a href="<?php echo base_url('aanmelden/' . $aanmelden_voor . '/' . $workshop->workshop_url) ?>" title="Aanmelden">Aanmelden</a></li>
				<li class="active">Annuleringsverzekering</li>
				<li>Producten</li>
				<li>Bevestigen</li>
				<li>Betalen</li>
				<li>Aangemeld</li>
			</ol>
		</div>


		<!-- TITEL EN UITLEG -->

		<div id="uitleg">
			<h3><?php echo $content->pagina_inleiding ?></h3>
			<?php echo $content->pagina_tekst ?>
			<p><a href="<?php echo base_url('aanmelden/annuleringsverzekering_detail'); ?>" target="_blank">Meer informatie</a></p>
		</div>

		<!-- OVERZICHT PRODUCTEN -->

		<section id="producten">
			<article>
				<form method="post" action="<?php echo base_url('aanmelden/annuleringsverzekering') ?>">
					<div class="clearfix">
						<p class="bestellen">Wil je een annuleringsverzekering toevoegen aan je bestelling?</p>
						<p class="prijs"><strong><?php echo 'Prijs: €' . money_format('%.2n', $annulering_prijs); ?></strong></p>
					</div>
					<p class="annulering_prijs"><strong><input type="radio" name="aanmelden_annuleringsverzekering" value="Ja" <?php if ($aanmelden_annuleringsverzekering == 'Ja') echo 'checked'; ?> /> Ja <input type="radio" name="aanmelden_annuleringsverzekering" value="Nee" <?php if ($aanmelden_annuleringsverzekering == 'Nee') echo 'checked'; ?> /> Nee </label></strong></p>
					<p><input type="submit" value="Volgende stap" class="button" /></p>
				</form>
			</article>
		</section>
	</div>
</section>
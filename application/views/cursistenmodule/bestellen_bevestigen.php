<section id="bestellen">

<!-- STAPPEN -->

<div id="stappen">
	<ol>
		<li><a href="<?php echo base_url('cursistenmodule/bestellen') ?>" title="Afleveradres">Afleveradres</a></li>
		<li class="active">Bevestigen</li>
		<li>Betalen</li>
		<li>Besteld</li>
	</ol>
</div>

<!-- OVERZICHT GEGEVENS EN BESTELLING -->

<h1>Bevestigen</h1>
<h3>Overzicht</h3>
<p>Hieronder zie je een overzicht van je gegevens en je bestelling.<br />Controleer deze nauwkeurig voordat je betaalt.</p>
<h3>Je gegevens</h3>
<div id="gegevens">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<th>Bedrijfsnaam</th>
			<td><?php if(!empty($bestellen_bedrijfsnaam)) echo $bestellen_bedrijfsnaam; else echo '-'; ?></td>
		</tr>
		<tr>
			<th>Naam</th>
			<td><?php echo $bestellen_voornaam ?> <?php echo $bestellen_tussenvoegsel ?> <?php echo $bestellen_achternaam ?></td>
		</tr>
		<tr>
			<th>Geslacht</th>
			<td><?php echo ucfirst($bestellen_geslacht) ?></td>
		</tr>
		<tr>
			<th>Geboortedatum</th>
			<td><?php echo $bestellen_geboortedatum_dag ?>-<?php echo $bestellen_geboortedatum_maand ?>-<?php echo $bestellen_geboortedatum_jaar ?></td>
		</tr>
		<tr>
			<th>Adres</th>
			<td><?php echo $bestellen_adres ?>, <?php echo $bestellen_postcode ?> <?php echo strtoupper($bestellen_plaats) ?></td>
		</tr>
		<?php if($bestellen_afleveren_adres != '' && sizeof($bestellen_producten) > 0): ?>
			<tr>
				<th>Afleveradres</th>
				<td><?php echo $bestellen_afleveren_adres ?>, <?php echo $bestellen_afleveren_postcode ?> <?php echo strtoupper($bestellen_afleveren_plaats) ?></td>
			</tr>
		<?php endif; ?>
		<tr>
			<th>Telefoonnummer</th>
			<td><?php if(!empty($bestellen_telefoon)) echo $bestellen_telefoon; else echo '-'; ?></td>
		</tr>
		<tr>
			<th>Mobiel nummer</th>
			<td><?php if(!empty($bestellen_mobiel)) echo $bestellen_mobiel; else echo '-'; ?></td>
		</tr>
		<tr>
			<th>E-mailadres</th>
			<td><?php echo $bestellen_emailadres ?></td>
		</tr>
	</table>
</div>
	<h3>Kies je betaalmethode</h3>
	<form method="POST" action="<?php echo base_url('cursistenmodule/bestellen/betaal_optie') ?>" name="betaling_opties">
		<table>
			<?php foreach($paymentList as $option) : ?>
            <?php if($option['id'] != 1813 || ($in3_aan != 0 && $option['id'] == 1813)): ?>
				<tr>
					<td>
						<label for="<?php echo $option['name'] ?>">
							<input type="radio" name="payment_option" id="<?php echo $option['name'] ?>" <?php if($option['id'] == 10) { echo "checked"; } ?>  value="<?php echo $option['id'] ?>"/>
							<?php foreach($pay_images as $img) : ?>
								<?php if($img['id'] == $option['id']) { ?>
									<img src="<?php echo $img['img'] ?>">
								<?php } ?>
							<?php endforeach; ?>
							<?php echo $option['name'] ?>
							<?php foreach($betaal_methodes as $methode) :
								if($option['id'] == 706 && $methode->pay_ID == 706) :
									echo "(+$methode->percentage%)";
								endif;
							endforeach; ?>
						</label>
					</td>
				</tr>
            <?php endif; ?>
			<?php endforeach; ?>
		</table>

<h3>Je bestelling</h3>
<div id="bestelling">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">

		<!-- PRODUCTEN -->
		<?php if(sizeof($bestellen_producten) > 0): ?>
			<?php
			foreach($bestellen_producten as $product_ID):
				$product = $this->producten_model->getProductByID($product_ID);
				?>
				<tr>
					    <td><?php echo $product->product_naam ?></td>
					<td class="prijs">
				<?php 
					if($product->product_prijs_naderhand != 0){
						$product_price = $product->product_prijs_naderhand;
					}else{
						$product_price =  $product->product_prijs;
					}
					echo money_format('%.2n' ,$product_price)
				?>
				</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(!empty($systeem_kortingen)): ?>
			<?php foreach($systeem_kortingen as $korting): ?>
				<tr>
					<td>Korting (<?php echo $korting['titel'] ?>)</td>
					<td class="prijs">-<?php echo money_format('%.2n' ,$korting['bedrag']) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php foreach($betaal_methodes as $methode) : ?>
			<?php if($methode->pay_ID == 706) : ?>
				<tr class="betaalmethode-creditcard">
					<td>Betaalmethode Visa Mastercard (+<?php echo $methode->percentage ?>%)</td>
					<td class="prijs" id="creditcard_prijs"><?php echo money_format('%.2n' ,$bestellen_bedrag) ?></td>
				</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		<tr class="totaal">
			<td>Totaal</td>
			<td class="prijs" id="totaal"><?php echo money_format('%.2n' ,$bestellen_bedrag) ?></td>
		</tr>
	</table>
</div>
	<p><input type="submit" class="button" value="Betalen" /></p>
</form>
</section>
<script>
    fbq('track', 'AddToCart', {
        content_ids: [<?php foreach($bestellen_producten as $ID) { echo $ID. ","; } ?>],
        content_type: 'product',
        content_category: 'product',
        value: <?php echo $bestellen_bedrag ?>,
        currency: 'EUR'
    });
</script>

<section id="aanmelden">

	<!-- STAPPEN -->

	<div id="stappen">
		<ol>
			<?php if($aanmelden_voor == 'workshop'): ?>
				<li><a href="<?php echo base_url('cursistenmodule/aanmelden/'.$aanmelden_voor.'/'.$workshop->workshop_url) ?>" title="Aanmelden">Aanmelden</a></li>
				<?php if($aanmelden_voor == 'workshop' && sizeof($producten) > 0): ?><li><a href="<?php echo base_url('cursistenmodule/aanmelden/producten') ?>" title="Producten">Producten</a></li><?php endif; ?>
				<li class="active">Bevestigen</li>
				<li>Betalen</li>
				<li>Aangemeld</li>
			<?php else: ?>
				<li><a href="<?php echo base_url('cursistenmodule/aanmelden/'.$aanmelden_voor.'/'.$workshop->workshop_url) ?>" title="Aanmelden">Aanmelden</a></li>
				<li class="active">Bevestigen</li>
				<li>Betalen</li>
				<li>Aangemeld</li>
			<?php endif; ?>
		</ol>
	</div>

	<!-- OVERZICHT GEGEVENS EN BESTELLING -->
	<?php
	echo @$_GET['status'] == 'canceled' ? '<h2>Je bestelling is geannuleerd, je kunt het nog opnieuw proberen.</h2>' : '<h1>Bevestigen</h1>';
	?>
	<h3>Overzicht</h3>
	<p>Hieronder zie je een overzicht van je gegevens en je bestelling.<br />Controleer deze nauwkeurig voordat je betaalt.</p>
	<h3>Je gegevens</h3>
	<div id="gegevens">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<?php if($aanmelden_voor == 'workshop' && ($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online')): ?>
				<tr>
					<th>Startdatum</th>
					<td><?php echo date('d-m-Y', strtotime($startdatum)) ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<th>Bedrijfsnaam</th>
				<td><?php if(!empty($aanmelden_bedrijfsnaam)) echo $aanmelden_bedrijfsnaam; else echo '-'; ?></td>
			</tr>
			<tr>
				<th>Naam</th>
				<td><?php echo $aanmelden_voornaam ?> <?php echo $aanmelden_tussenvoegsel ?> <?php echo $aanmelden_achternaam ?></td>
			</tr>
			<tr>
				<th>Geslacht</th>
				<td><?php echo ucfirst($aanmelden_geslacht) ?></td>
			</tr>
			<tr>
				<th>Geboortedatum</th>
				<td><?php echo $aanmelden_geboortedatum_dag ?>-<?php echo $aanmelden_geboortedatum_maand ?>-<?php echo $aanmelden_geboortedatum_jaar ?></td>
			</tr>
			<tr>
				<th>Adres</th>
				<td><?php echo $aanmelden_adres ?>, <?php echo $aanmelden_postcode ?> <?php echo strtoupper($aanmelden_plaats) ?></td>
			</tr>
			<?php if($aanmelden_afleveren_adres != '' && sizeof($aanmelden_producten) > 0): ?>
				<tr>
					<th>Afleveradres</th>
					<td><?php echo $aanmelden_afleveren_adres ?>, <?php echo $aanmelden_afleveren_postcode ?> <?php echo strtoupper($aanmelden_afleveren_plaats) ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<th>Telefoonnummer</th>
				<td><?php if(!empty($aanmelden_telefoon)) echo $aanmelden_telefoon; else echo '-'; ?></td>
			</tr>
			<tr>
				<th>Mobiel nummer</th>
				<td><?php if(!empty($aanmelden_mobiel)) echo $aanmelden_mobiel; else echo '-'; ?></td>
			</tr>
			<tr>
				<th>E-mailadres</th>
				<td><?php echo $aanmelden_emailadres ?></td>
			</tr>
		</table>
	</div>
	<h3>Kies je betaalmethode</h3>
	<form method="POST" action="<?php echo base_url('cursistenmodule/aanmelden/betaal_optie') ?>" name="betaling_opties">
		<table>
			<?php foreach($paymentList as $option) : ?>
                <?php if($option['id'] != 1813 || ($in3_aan != 0 && $option['id'] == 1813 && ($aanmelden_bedrag < 5000 || $workshop->workshop_niveau == 5))): ?>
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

				<!-- WORKSHOP / KENNISMAKINGSWORKSHOP / STEMTEST / INTAKE -->

				<?php if($aanmelden_voor == 'workshop'): ?>
					<tr>
						<td><?php echo $workshop->workshop_titel ?></td>
						<td class="prijs"><?php echo money_format('%.2n' , $workshop->workshop_prijs) ?></td>
					</tr>
				<?php elseif($aanmelden_voor == 'kennismakingsworkshop'): ?>
					<tr>
						<td><?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></td>
						<td class="prijs"><?php echo money_format('%.2n' , $kennismakingsworkshop->kennismakingsworkshop_prijs) ?></td>
					</tr>
				<?php else: ?>
					<tr>
						<td><?php echo ucfirst($aanmelden_voor) ?> <?php echo $workshop->workshop_titel ?></td>
						<td class="prijs"><?php echo money_format('%.2n' , $workshop->workshop_stemtest_prijs) ?></td>
					</tr>
				<?php endif; ?>

			<!-- PRODUCTEN -->

				<?php if($aanmelden_voor == 'workshop' && sizeof($producten) > 0 && !empty($aanmelden_producten) && sizeof($aanmelden_producten) > 0): ?>
					<?php
					foreach($aanmelden_producten as $product_ID):
						$product = $this->producten_model->getProductByID($product_ID);
						?>
						<tr>
							<td><?php echo $product->product_naam ?></td>
							<td class="prijs"><?php echo money_format('%.2n' , $product->product_prijs) ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<!-- HUUR PRODUCTEN -->

				<?php if($aanmelden_voor == 'workshop' && !empty($aanmelden_huur_producten) && sizeof($aanmelden_huur_producten) > 0): ?>
					<?php
					foreach($aanmelden_huur_producten as $product):
						?>
						<tr>
							<td><?php echo $product->product_naam . ' (borg)' ?></td>
							<td class="prijs"><?php echo money_format('%.2n' , $product->product_borg) ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>

				<!-- KORTING -->

				<?php
				if(!empty($aanmelden_kortingscode) && !empty($workshop_korting)):
					?>
					<tr>
						<td>Couponcode (<?php echo $aanmelden_kortingscode ?>)</td>
						<td class="prijs">-<?php echo money_format('%.2n' , $workshop_korting) ?></td>
					</tr>
				<?php endif; ?>

				<?php if(!empty($systeem_kortingen)): ?>
					<?php foreach($systeem_kortingen as $korting): ?>
                        <?php if(!empty($korting['bundelkorting'])) { ?>
                            <tr>
                                <td>Bundelkorting (<?php echo $korting['titel'] ?>)</td>
                                <td class="prijs">-<?php echo money_format('%.2n' ,$korting['bedrag']) ?></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td>Korting (<?php echo $korting['titel'] ?>)</td>
                                <td class="prijs">-<?php echo money_format('%.2n' ,$korting['bedrag']) ?></td>
                            </tr>
                        <?php } ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php foreach($betaal_methodes as $methode) : ?>
					<?php if($methode->pay_ID == 706) : ?>
						<tr class="betaalmethode-creditcard">
							<td>Betaalmethode Visa Mastercard (+<?php echo $methode->percentage ?>%)</td>
							<td class="prijs" id="creditcard_prijs"><?php echo money_format('%.2n' ,$aanmelden_bedrag) ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>

			<!-- ANNULERINGSVERZEKERING -->

			<?php if(!empty($annulering_prijs)): ?>
				<tr>
					<td>Annuleringsverzekering</td>
					<td class="prijs"><?php echo money_format('%.2n' , $annulering_prijs) ?></td>
				</tr>
			<?php endif; ?>
				<tr class="totaal">
					<td>Totaal</td>
					<td class="prijs" id="totaal"><?php echo money_format('%.2n' ,$aanmelden_bedrag) ?></td>
				</tr>
			</table>
		</div>
		<p><input type="submit" name="betalen" id="betalen" value="Betalen" /></p>
	</form>
</section>
<script>
    <?php if($aanmelden_voor == 'workshop') { ?>
    fbq('track', 'AddToCart', {
        content_ids: [<?php echo $workshop->workshop_ID ?> <?php if(!empty($aanmelden_producten)) foreach($aanmelden_producten as $ID) echo ",".$ID; ?>],
        content_type: 'product',
        content_category: 'Workshop',
        value: <?php echo $aanmelden_bedrag ?>,
        currency: 'EUR'
    });
    <?php } elseif($aanmelden_voor == 'kennismakingsworkshop') { ?>
    fbq('track', 'AddToCart', {
        content_ids: [<?php echo $kennismakingsworkshop->kennismakingsworkshop_ID ?><?php if(!empty($aanmelden_producten)) foreach($aanmelden_producten as $ID) echo ",".$ID; ?>],
        content_type: 'product',
        content_category: 'Kennismakingsworkshop',
        value: <?php echo $aanmelden_bedrag ?>,
        currency: 'EUR'
    });
    <?php } else { ?>
    fbq('track', 'AddToCart', {
        content_ids: [<?php echo $workshop->workshop_ID ?><?php if(!empty($aanmelden_producten)) foreach($aanmelden_producten as $ID) echo ",".$ID; ?>],
        content_type: 'product',
        content_category: 'Workshop',
        value: <?php echo $aanmelden_bedrag ?>,
        currency: 'EUR'
    });
    <?php } ?>
</script>
<section id="bestellen">

	<!-- STAPPEN -->

	<div id="stappen">
		<ol>
			<li>Afleveradres</li>
			<li>Bevestigen</li>
			<li>Betalen</li>
			<li class="active">Besteld</li>
		</ol>
	</div>

	<!-- BEVESTIGING -->
<?php if($this->session->userdata('gebruiker_rechten') != 'test') { ?>
	<p>Beste <?php echo $bestelling->gebruiker_voornaam ?>,</p>
	<p>Bedankt voor het bestellen van producten bij localhost. Hieronder van je een overzicht van jouw bestelling.</p>

	<p><strong>Adresgegevens</strong></p>
	<table cellpadding="10" cellspacing="0" width="100%" border="1">
		<tr>
			<td>Naam</td>
			<td><?php echo $bestelling->gebruiker_voornaam ?> <?php echo $bestelling->gebruiker_tussenvoegsel ?> <?php echo $bestelling->gebruiker_achternaam ?></td>
		</tr>
		<tr>
			<td>Adres</td>
			<td><?php echo $bestelling->bestelling_adres ?></td>
		</tr>
		<tr>
			<td>Postcode</td>
			<td><?php echo $bestelling->bestelling_postcode ?></td>
		</tr>
		<tr>
			<td>Plaats</td>
			<td><?php echo $bestelling->bestelling_plaats ?></td>
		</tr>
	</table>

	<p><strong>Producten</strong></p>
	<table cellpadding="10" cellspacing="0" width="100%" border="1">
		<?php foreach($bestelling_producten as $product): ?>
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
		<?php if(!empty($betaal_methode)): ?>
			<tr>
				<td>Betaalmethode</td>
				<td align="right">&euro; <?php echo money_format('%.2n' , $betaal_methode) ?></td>
			</tr>
		<?php endif; ?>
		<?php if(!empty($systeem_kortingen)): ?>
			<?php foreach($systeem_kortingen as $korting): ?>
				<tr>
					<td>Korting (<?php echo $korting['titel'] ?>)</td>
					<td class="prijs" align="right">- &euro;<?php echo money_format('%.2n' ,$korting['bedrag']) ?></td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
		<tr class="totaal">
			<td>Totaal</td>
			<td class="prijs" id="totaal" align="right"> &euro;<?php echo money_format('%.2n' ,$betaald_bedrag) ?></td>
		</tr>
	</table>

	<p>Met vriendelijke groet,</p>
	<p>localhost</p>
    <?php } else { ?>
        <p>Beste [naam],</p>
        <p>Bedankt voor het bestellen van producten bij localhost. Hieronder van je een overzicht van jouw bestelling.</p>

        <p><strong>Adresgegevens</strong></p>
        <table cellpadding="10" cellspacing="0" width="100%" border="1">
            <tr>
                <td>Naam</td>
                <td>[naam]</td>
            </tr>
            <tr>
                <td>Adres</td>
                <td>[adres]</td>
            </tr>
            <tr>
                <td>Postcode</td>
                <td>[postcode]</td>
            </tr>
            <tr>
                <td>Plaats</td>
                <td>[plaats]</td>
            </tr>
        </table>

        <p><strong>Producten</strong></p>
        <table cellpadding="10" cellspacing="0" width="100%" border="1">
                <tr>
                    <td>[product_naam]</td>
                    <td align="right">&euro; [product_prijs]</td>
                </tr>
                <tr>
                    <td>Betaalmethode</td>
                    <td align="right">&euro; [kosten_betaal_methode]</td>
                </tr>
                <tr>
                    <td>Korting ([korting_percentage])</td>
                    <td class="prijs" align="right">- &euro; [korting]</td>
                </tr>
            <tr class="totaal">
                <td>Totaal</td>
                <td class="prijs" id="totaal" align="right"> &euro; [totaal_prijs]</td>
            </tr>
        </table>

        <p>Met vriendelijke groet,</p>
        <p>localhost</p>

    LET OP DIT IS EEN TEST. ER IS GEEN BESTELLING AANGEMAAKT EN DAAROM IS SOMMIGE INFORMATIE NIET BESCHIKBAAR.<br>
    <?php } ?>
</section>
<script>
    fbq('track', 'Purchase', {
        content_name: '<?php if(sizeof($bestelling_producten) == 1) echo $bestelling_producten[0]->product_naam; else echo "producten"; ?>',
        content_category: 'Product',
        content_ids: [<?php $i=0; foreach($bestelling_producten as $producten) { echo $producten->product_ID; if($i < sizeof($bestelling_producten)) echo ","; }; ?>],
        content_type: 'Product',
        value: <?php echo $betaald_bedrag ?>,
        currency: 'EUR'
    });
</script>


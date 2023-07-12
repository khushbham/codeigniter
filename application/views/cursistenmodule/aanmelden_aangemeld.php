<section id="aanmelden">

	<!-- STAPPEN -->

	<div id="stappen">
		<ol>
			<li>Aanmelden</li>
			<li>Bevestigen</li>
			<li>Betalen</li>
			<li class="active">Aangemeld</li>
		</ol>
	</div>

	<!-- BEVESTIGING -->
    <?php if($this->session->userdata('gebruiker_rechten') != 'test') { ?>
	<?php if(!empty($_GET['orderStatusId']) && $_GET['orderStatusId'] == '50'): ?>
		<h1>Je betaling staat in de wacht</h1>
		<p>Beste <?php echo $aanmelding->gebruiker_voornaam ?>,</p>
		.
		<p>Je hebt je zojuist aangemeld voor de volgende workshop: "<?php echo $aanmelding->workshop_titel ?>".<br />
			Je betaling staat in de wacht, als dit een fout is neem dan contact met ons op.<br />
			Stuur je naam, achternaam en wat er is gebeurd.<br />
			<a href="mailto:info@localhost?subject=Betaling is nog niet afgerond.&body= Order id: <?php echo $_GET['orderId'] ?>">info@localhost</a></p>
		<p>Met vriendelijke groet,</p>
		<p>localhost</p>
	<?php elseif($aanmelding->aanmelding_type == 'workshop'): ?>
		<h1>Aangemeld voor workshop</h1>
		<p>Beste <?php echo $aanmelding->gebruiker_voornaam ?>,</p>
		<p>Je hebt je zojuist aangemeld voor de volgende workshop: "<?php echo $aanmelding->workshop_titel ?>".<br />
		<a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p>
		<p>Wij wensen je alvast veel plezier met je deelname.</p>
		<p>Met vriendelijke groet,</p>
		<p>localhost</p>
	<?php else: ?>
		<h1>Aangemeld voor <?php echo $aanmelding->aanmelding_type ?></h1>
		<p>Beste <?php echo $aanmelding->gebruiker_voornaam ?>,</p>
		<p>Je hebt je zojuist aangemeld voor de <?php echo $aanmelding->aanmelding_type ?> van de workshop: "<?php echo $aanmelding->workshop_titel ?>".<br />Binnen twee werkdagen zullen we contact met je opnemen voor het plannen van een afspraak.<br />
		<a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p>
		<p>Met vriendelijke groet,</p>
		<p>localhost</p>
	<?php endif; ?>
    <?php } else { ?>
        <h1>Aangemeld voor ...</h1>
        <p>Beste [naam],</p>
        <p>Je hebt je zojuist aangemeld voor de workshop: "[workshop_naam]".<br />
            <a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p>
        <p>Met vriendelijke groet,</p>
        <p>localhost</p><br />

        <p>LET OP DIT IS EEN TEST. ER IS GEEN AANMELDING AANGEMAAKT EN DAAROM IS BEPAALDE INFORMATIE NIET BESCHIKBAAR</p>
    <?php } ?>

</section>
<script>
    <?php if($aanmelding->aanmelding_type == 'workshop') { ?>
        fbq('track', 'Purchase', {
            content_name: '<?php echo $aanmelding->workshop_titel ?>',
            content_category: 'Workshop',
            content_ids: [<?php echo $aanmelding->workshop_ID ?><?php if(!empty($producten)) foreach($producten as $product) echo ",".$product->product_ID; ?>],
            content_type: 'Product',
            value: <?php echo $aanmelding->betaald_bedrag ?>,
            currency: 'EUR'
        });
        <?php } ?>
</script>
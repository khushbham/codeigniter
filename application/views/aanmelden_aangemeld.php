<div id="fb-root"></div>
<script>
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.9";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

<script>
	window.twttr = (function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0],
			t = window.twttr || {};
		if (d.getElementById(id)) return t;
		js = d.createElement(s);
		js.id = id;
		js.src = "https://platform.twitter.com/widgets.js";
		fjs.parentNode.insertBefore(js, fjs);

		t._e = [];
		t.ready = function(f) {
			t._e.push(f);
		};

		return t;
	}(document, "script", "twitter-wjs"));
</script>


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
				<li>Aanmelden</li>
				<?php if ($this->session->userdata('gebruiker_rechten') != 'test') : ?>
					<?php if ($aanmelding->aanmelding_type == 'workshop' && sizeof($producten) > 0) : ?><li>Producten</li><?php endif; ?>
				<?php endif; ?>
				<li>Bevestigen</li>
				<li>Betalen</li>
				<li class="active">Aangemeld</li>
			</ol>
		</div>

		<!-- BEVESTIGING -->
		<?php if ($this->session->userdata('gebruiker_rechten') != 'test') { ?>
			<?php if (!empty($_GET['orderStatusId']) && $_GET['orderStatusId'] == '50') : ?>
				<h1>Je betaling staat in de wacht</h1>
				<p>Beste <?php echo $aanmelding->gebruiker_voornaam ?>,</p>
				<p>Je hebt je zojuist aangemeld voor de volgende workshop: "<?php echo $aanmelding->workshop_titel ?>".<br />
					Je betaling staat in de wacht, als dit een fout is neem dan contact met ons op.<br />
					Stuur je naam, achternaam en wat er is gebeurd.<br />
					<a href="mailto:info@localhost?subject=Betaling is nog niet afgerond&body= Order id: <?php echo $_GET['orderId'] ?>">info@localhost</a></p>
				<p>Met vriendelijke groet,</p>
				<p>localhost</p>
			<?php elseif ($aanmelding->aanmelding_type == 'workshop') : ?>
				<h1>Aangemeld voor workshop</h1>
				<p>Beste <?php echo $aanmelding->gebruiker_voornaam ?>,</p>
				<p>Je hebt je zojuist aangemeld voor de volgende workshop: "<?php echo $aanmelding->workshop_titel ?>".<br />
					<a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p>
				<p>Wij wensen je alvast veel plezier met je deelname.</p>
				<p>Met vriendelijke groet,</p>
				<p>localhost</p>
			<?php elseif ($aanmelding->aanmelding_type == 'kennismakingsworkshop') : ?>
				<h1>Aangemeld voor kennismakingsworkshop</h1>
				<p>Beste <?php echo $aanmelding->gebruiker_voornaam ?>,</p>
				<p>Je hebt je zojuist aangemeld voor de volgende kennismakingsworkshop: "<?php echo $aanmelding->kennismakingsworkshop_titel ?>".<br />
					<a href="https://www.localhost/inschrijfvoorwaarden" target="_blank">Hier</a> vind je de inschrijfvoorwaarden die van toepassing zijn op jouw inschrijving/bestelling.</p>
				<p>Wij wensen je alvast veel plezier met je deelname.</p>
				<p>Met vriendelijke groet,</p>
				<p>localhost</p>
			<?php else : ?>
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
	</div>
</section>
<div id="share">
	<div class="fb-share-button" data-href="https://localhost/" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Flocalhost%2F&amp;src=sdkpreparse">Delen</a></div>
	<div class="twitter"><a class="twitter-share-button" href="https://twitter.com/share" data-size="large" data-text="Yes! Ik heb me net aangemeld voor een hele leuke workshop bij " data-url="https://localhost" data-related="twitterapi,twitter">
			Tweet
		</a></div>
</div>
<script>
	<?php if ($aanmelding->aanmelding_type == 'workshop') { ?>
		fbq('track', 'Purchase', {
			content_name: '<?php echo $aanmelding->workshop_titel ?>',
			content_category: 'Workshop',
			content_ids: [<?php echo $aanmelding->workshop_ID ?><?php if (!empty($producten)) foreach ($producten as $product) echo "," . $product->product_ID; ?>],
			content_type: 'Product',
			value: <?php echo $aanmelding->betaald_bedrag ?>,
			currency: 'EUR'
		});
	<?php } elseif ($aanmelding->aanmelding_type == 'kennismakingsworkshop') { ?>
		fbq('track', 'Purchase', {
			content_name: '<?php echo $aanmelding->kennismakingsworkshop_titel ?>',
			content_category: 'Kennismakingsworkshop',
			content_ids: [<?php echo $aanmelding->kennismakingsworkshop_ID ?><?php if (!empty($producten)) foreach ($producten as $product) echo "," . $product->product_ID; ?>],
			content_type: 'Product',
			value: <?php echo $aanmelding->betaald_bedrag ?>,
			currency: 'EUR'
		});
	<?php } elseif ($aanmelding->aanmelding_type == 'stemtest') { ?>
		fbq('track', 'Purchase', {
			content_name: '<?php echo $aanmelding->workshop_titel . " stemtest" ?>',
			content_category: 'Workshop',
			content_ids: [<?php echo $aanmelding->workshop_ID ?><?php if (!empty($producten)) foreach ($producten as $product) echo "," . $product->product_ID; ?>],
			content_type: 'Product',
			value: <?php echo $aanmelding->betaald_bedrag ?>,
			currency: 'EUR'
		});
	<?php } ?>
</script>

<?php if (!empty($_GET['orderStatusId']) && $_GET['orderStatusId'] != '50') : ?>
	<!-- Google Code for Inschrijving Workshop Conversion Page -->
	<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 1072350360;
		var google_conversion_language = "en";
		var google_conversion_format = "3";
		var google_conversion_color = "ffffff";
		var google_conversion_label = "ouZ3CNPr5WwQmImr_wM";
		var google_remarketing_only = false;
		/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1072350360/?label=ouZ3CNPr5WwQmImr_wM&amp;guid=ON&amp;script=0" />
		</div>
	</noscript>
<?php endif; ?>
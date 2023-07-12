<section class="hero hero--contact" class="clearfix">
	<div class="wrapper">
		<div class="intro">
			<hgroup>
				<h1>Lestheaters</h1>
			</hgroup>
			<div class="hero-grid">
				<div class="grid-item">
					<span class="location-title">Leiden</span>
					Middelstegracht 89u<br />
					2312 TT LEIDEN
				</div>
				<div class="grid-item">
					<span class="location-title">Utrecht</span>
					Ondiep Zuidzijde 6<br />
					3551 BW UTRECHT
				</div>
				<div class="grid-item">
					<span class="location-title">Amsterdam</span>
					Donauweg 10<br />
					1043 AJ AMSTERDAM
				</div>
				<div class="grid-item">
					<span class="subheading">Postadres</span>
					localhost<br />
					Antwoordnummer 12015<br />
					2300 VC LEIDEN
				</div>
				<div class="grid-item grid-item-telephone">
					<span class="subheading">Telefoon</span>
					<span class="telephone"><a href="tel:<?php echo str_replace('-', '', str_replace(' ', '', $gegevens[1]->gegeven_waarde)) ?>" title="Bel <?php echo $gegevens[1]->gegeven_waarde ?>"><?php echo $gegevens[1]->gegeven_waarde ?></a></span>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="contact">
	<div class="wrapper">

		<div id="vragen_contact">
			<section id="meest-gestelde-vragen">
				<h2>Meest gestelde vragen</h2>
				<?php if (sizeof($vragen)) : ?>
					<ul>
						<?php foreach ($vragen as $vraag) : ?>
							<li><a href="<?php echo base_url('contact/vragen#vraag' . $vraag->vraag_ID) ?>" title="Bekijk het antwoord op de vraag: <?php echo $vraag->vraag_titel; ?>"><?php echo $vraag->vraag_titel; ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<a href="<?php echo base_url('contact/vragen') ?>" title="Bekijk hier alle vragen" class="button button--orange">Bekijk hier alle vragen</a>
			</section>
			<section id="contactformulier">
				<?php if (!$contact_verzonden) : ?>
					<h2>Stel een vraag</h2>
					<form method="post" action="<?php echo base_url('contact#contactformulier') ?>">
						<div class="form-group"><label for="contact_naam">Naam</label><input type="text" name="contact_naam" id="contact_naam" value="<?php echo $contact_naam ?>" /></div>
						<?php if (!empty($contact_naam_feedback)) : ?><p class="feedback"><?php echo $contact_naam_feedback ?></p><?php endif; ?>
						<div class="form-group"><label for="contact_emailadres">E-mailadres</label><input type="text" name="contact_emailadres" id="contact_emailadres" value="<?php echo $contact_emailadres ?>" /></div>
						<?php if (!empty($contact_emailadres_feedback)) : ?><p class="feedback"><?php echo $contact_emailadres_feedback ?></p><?php endif; ?>
						<div class="form-group"><label for="contact_telefoon">Telefoonnummer</label><input type="text" name="contact_telefoon" id="contact_telefoon" value="<?php echo $contact_telefoon ?>" /></div>
						<?php if (!empty($contact_telefoon_feedback)) : ?><p class="feedback"><?php echo $contact_telefoon_feedback ?></p><?php endif; ?>
						<div class="form-group"><label for="contact_onderwerp">Onderwerp</label><input type="text" name="contact_onderwerp" id="contact_onderwerp" value="<?php echo $contact_onderwerp ?>" /></div>
						<?php if (!empty($contact_onderwerp_feedback)) : ?><p class="feedback"><?php echo $contact_onderwerp_feedback ?></p><?php endif; ?>
						<div class="form-group"><label for="contact_bericht">Stel je vraag</label><textarea name="contact_bericht" id="contact_bericht" rows="5"><?php echo $contact_bericht ?></textarea></div>
						<?php if (!empty($contact_bericht_feedback)) : ?><p class="feedback"><?php echo $contact_bericht_feedback ?></p><?php endif; ?>
						<input type="submit" name="contact_submit" id="contact_submit" value="Verzenden" class="button button--orange" />
						<?php if (!empty($contact_feedback)) : ?><p class="feedback"><em><?php echo $contact_feedback ?></em></p><?php endif; ?>
						<input type="text" id="website" name="website" />
					</form>
				<?php else : ?>
					<h1>Bericht verzonden</h1>
					<p class="verzonden">Beste <?php echo $contact_naam ?>, bedankt voor je bericht.<br />Binnen twee werkdagen zullen we je bericht beantwoorden.</p>
				<?php endif; ?>
			</section>
			<!-- <section class="newsletter">
				<h2>Blijf op de hoogte</h2>
				<a href="<?php echo $gegevens[4]->gegeven_waarde ?>" title="Aanmelden nieuwsbrief" class="button button--orange" target="_blank">Meld je aan voor de nieuwsbrief</a>
			</section> -->
		</div>
	</div>
</section>
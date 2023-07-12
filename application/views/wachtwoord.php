<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title">Wachtwoord vergeten?</h1>
	</div>
</section>

<section class="page">
	<div class="wrapper">
		<?php if (!$this->session->userdata('wachtwoord_verzonden')) : ?>
			<p>Geen probleem! Vul hieronder je e-mailadres in en we sturen je een nieuw wachtwoord.</p>
			<form method="post" action="<?php echo base_url('wachtwoord') ?>">
				<p><label for="gebruiker_emailadres">E-mailadres *</label><input type="text" name="gebruiker_emailadres" id="gebruiker_emailadres" value="<?php echo $gebruiker_emailadres ?>" /><span class="feedback"><?php echo $gebruiker_emailadres_feedback ?></span></p>
				<p><input type="submit" value="Wachtwoord opsturen" /></p>
			</form>
		<?php else : ?>
			<h1>Wachtwoord verzonden</h1>
			<p>Er is een nieuw wachtwoord gestuurd naar <?php echo $gebruiker_emailadres ?>.</p>
			<p>Groeten,<br />localhost</p>
		<?php endif; ?>
	</div>
</section>
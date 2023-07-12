<h1>Profiel</h1>
<div id="profiel">
	<div id="profiel_gegevens">
	<?php if(!empty($gebruiker_profiel_foto)) {  ?>
	<img class="preview" src="<?php echo base_url('media/uploads/') . $gebruiker_profiel_foto->media_src;?>" style="heigth:200px; width:200px; border-radius:25px" id="profiel_foto"/>
	<?php } ?>
		<p><?php echo $gebruiker_voornaam ?> <?php echo $gebruiker_tussenvoegsel ?> <?php echo $gebruiker_achternaam ?><?php if(!empty($gebruiker_bedrijfsnaam)) echo ', '.$gebruiker_bedrijfsnaam ?><br />
		<?php echo $gebruiker_adres ?><br />
		<?php if(!empty($gebruiker_postcode) && !empty($gebruiker_plaats)) { ?>
		<?php echo $gebruiker_postcode ?> <?php echo $gebruiker_plaats ?><?php } ?></p>
		<p>T <?php if(empty($gebruiker_telefoonnummer)) { echo "-"; } else { echo $gebruiker_telefoonnummer; } ?><br />
		M <?php if(empty($gebruiker_mobiel)) { echo "-"; } else { echo $gebruiker_mobiel; } ?></p>
		<p>E-mailadres <?php echo $gebruiker_emailadres ?></p>
	</div>
	<div id="profiel_buttons">
		<p><a class="button-orange" href="<?php echo base_url('cursistenmodule/profiel/wijzigen') ?>" title="Profiel wijzigen" class="button">Profiel wijzigen</a></p>
		<p><a class="button-orange" href="<?php echo base_url('cursistenmodule/profiel/wijzigen/wachtwoord') ?>" title="Wachtwoord wijzigen" class="button">Wachtwoord wijzigen</a></p>
		<p><a class="button-orange" href="<?php echo base_url('cursistenmodule/profiel/wijzigen/instellingen') ?>" title="Instellingen wijzigen" class="button">Instellingen wijzigen</a></p>
	</div>
</div>

<?php if ($this->session->userdata('gebruiker_profiel_foto') == 0 && !$this->session->userdata('beheerder_ID')) : ?>
	<div id="myModal" class="modal">
		<div class="modal-content" style="width:40%">
			<input style="float:right; margin:0 0 0 0" type="button" onclick="sluitModal()" value="X">
			<form method="post" enctype="multipart/form-data" action="<?php echo base_url('cursistenmodule/dashboard/uploadImage/') ?>">
				<h3>Maak je profiel persoonlijk en help onze docenten jou te herkennen!</h3>
				<div style="margin: 0 0 20px 20px;">
					<p style="margin-left: 0">Upload hier een recente foto van jezelf:</p>
					<input type='file' name="item_src_afbeelding" id="item_src_afbeelding" onchange="readURL(this);" /><br>
					<img class="preview" style="display: block; margin-left: auto; margin-right: auto;" id="profiel_foto" />
					<hr style="margin-bottom: 1em;" />
					<input class="button-orange" type="submit" name="save-changes" id="saveChanges" value="Bevestigen">
					<a href="#" class="text-decoration-none" onclick="sluitModal()">Ik doe dit liever een andere keer</a>
				</div>
			</form>
		</div>
	</div>
<?php endif; ?>
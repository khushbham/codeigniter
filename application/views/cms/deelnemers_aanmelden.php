<h1>Aanmelden voor workshop</h1>
<p>Voor welke workshop wil je <strong><?php echo $gebruiker->gebruiker_naam ?></strong> aanmelden?</p>
<?php if(sizeof($workshops) > 0): ?>
	<div class="formulier">
		<form method="post" action="">
			<?php foreach($workshops as $workshop): ?>
				<?php
				if(in_array($workshop->workshop_type, array('groep', 'online'))):
				$groepen = $this->groepen_model->getGroepenAanmeldenByWorkshopID($workshop->workshop_ID);
				if(sizeof($groepen) > 0):
				?>
					<p><input type="radio" name="workshop" value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop_ID == $workshop->workshop_ID) echo 'checked' ?> /> <?php echo $workshop->workshop_titel ?></p>
					<p class="aanmelden_groepen">
					<?php foreach($groepen as $groep): ?>
						<input type="radio" name="groep" value="<?php echo $groep->groep_ID ?>" <?php if($groep_ID == $groep->groep_ID) echo 'checked' ?> /> Startdatum <?php echo date('d/m/Y', strtotime($groep->groep_startdatum)) ?> - Groep <?php echo $groep->groep_naam ?><br />
					<?php endforeach; ?>
					</p>
				<?php
				endif;
				else:
				?>
					<p><input type="radio" name="workshop" value="<?php echo $workshop->workshop_ID ?>" <?php if($workshop_ID == $workshop->workshop_ID) echo 'checked' ?> /> <?php echo $workshop->workshop_titel ?></p>
				<?php endif; ?>
			<?php endforeach; ?>
			<p class="submit"><input type="submit" value="Aanmelden" /> <a href="<?php echo base_url('cms/deelnemers/'.$gebruiker->gebruiker_ID) ?>" title="Annuleren">Annuleren</a></p>
			<p><?php echo $feedback ?></p>
		</form>
	</div>
<?php endif; ?>
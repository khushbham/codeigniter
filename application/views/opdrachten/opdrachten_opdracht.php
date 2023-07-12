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

<head>
	<script src="https://www.localhost/assets/js/recorderjs/dist/recorder.js"></script>
</head>

<h1><?php echo $opdracht->opdracht_titel ?></h1>
<h2>Instructie</h2>
<?php echo $opdracht->opdracht_beschrijving ?>

<div class="c-audio-voorbeelden">
	<h2><?php echo $opdracht->opdracht_audio_titel ?></h2>
	<?php if (isset($media) && sizeof($media) > 0) : ?>
		<?php foreach ($media as $item) : ?>
			<div id="audio">
				<?php if ($item->media_type == 'wav' || $item->media_type == 'mp3') : ?>
					<div class="audio">
						<div class="audio_titel"><?php echo $item->media_titel ?></div>
						<audio src="<?php echo base_url('media/audio/' . $item->media_src) ?>" preload="auto"></audio>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	<?php else : ?>
		<em>Bij deze opdracht zijn geen audio bestanden toegevoegd.</em>
	<?php endif; ?>
</div>

<div class="c-pdf-bestanden">
	<h2>PDFs</h2>
	<?php if (isset($media) && sizeof($media) > 0) : ?>
		<div class="media">
		<?php foreach ($media as $item) : ?>
			<?php if ($item->media_type == 'pdf') : ?>
				<div class="c-pdf-bestand">
					<a href="<?php echo base_url('media/pdf/' . $item->media_src) ?>" title="Open de PDF in een nieuw tabblad / venster" target="_blank">
						<img src="https://localhost/assets/images/icon-pdf.png" alt="Open de PDF in een nieuw tabblad / venster">
					</a>
					<p class="document-pdf"><a href="<?php echo base_url('media/pdf/' . $item->media_src) ?>" title="Open de PDF in een nieuw tabblad / venster" target="_blank"><?php echo $item->media_titel ?></a></p>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		</div>
	<?php else : ?>
		<em>Bij deze opdracht zijn geen PDF's toegevoegd.</em>
	<?php endif; ?>
</div>

<div class="c-uploads">
	<div id="uploads">
		<h2>Uploads</h2>

		<?php if (empty($opdracht_ingestuurd)) : ?>
			<?php if ($opdracht->opdracht_uploads_aantal > 0 && $this->session->userdata('gebruiker_rechten') != 'dummy') : ?>
				<?php echo $opdracht->opdracht_uploads ?>
				<div id="mijnhuiswerk">

					<!-- Aantal WAV bestanden vermelden -->

					<?php if ($opdracht->opdracht_uploads_aantal == 1) : ?>
						<em>Voor deze opdracht kan 1 WAV bestand worden ingestuurd. Heb je het bestand toegevoegd en ben je tevreden over het resultaat? Dan kun je de opdracht insturen.</em><br><br>
					<?php else : ?>
						<em>Voor deze opdracht kunnen <?php echo $opdracht->opdracht_uploads_aantal ?> WAV bestanden worden ingestuurd. Heb je alle <?php echo $opdracht->opdracht_uploads_aantal ?> de bestanden toegevoegd? Dan kun je de opdracht insturen.</em><br><br>
					<?php endif; ?>

					<!-- Toegevoegde uploads tonen -->

					<?php if (sizeof($uploads) > 0) : ?>
						<div id="audio">
							<?php foreach ($uploads as $item) : ?>
								<div class="audio verwijderen">
									<div class="audio_titel"><?php echo ($item->bestand_titel != NULL ? $item->bestand_titel : $item->upload_titel) ?></div>
									<?php $opdracht_ID = $opdracht->opdracht_ID; ?>
									<div class="audio_verwijderen"><a href="<?php echo base_url('opdrachten/uploads/verwijderen/' . $item->upload_ID . '/' . $opdracht_ID) ?>" title="Upload verwijderen">Upload verwijderen</a></div>
									<audio src="<?php echo base_url('media/opdrachten/' . $item->upload_src) ?>" preload="auto"></audio>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php
						echo ini_get('upload-max-filesize'),'<br />',
						ini_get('post-max-size'),'<br />';
					?>

					<!-- Formulier tonen -->
						<form method="post" enctype="multipart/form-data" action="<?php echo base_url('opdracht/' . $opdracht->opdracht_url . '#mijnhuiswerk'); ?>">
							<?php if (sizeof($uploads) < $opdracht->opdracht_uploads_aantal) : ?>

								<!-- Huiswerk toevoegen -->
								<?php
									$gebruiker_ID = (!empty($this->session->userdata('gebruiker_ID'))) ? ($this->session->userdata('gebruiker_ID')) : ($this->session->userdata('beheerder_ID'));
									$gebruiker_geslacht = (!empty($this->session->userdata('gebruiker_geslacht'))) ? ($this->session->userdata('gebruiker_geslacht')) : 'nnb';
									$gebruiker_geboortedatum = (!empty($this->session->userdata('gebruiker_geboortedatum'))) ? ($this->session->userdata('gebruiker_geboortedatum')) : 'nnb';
									$upload_titel = $gebruiker_ID."-".$gebruiker_geslacht."-".$gebruiker_geboortedatum."-".date("Y-m-d")."-".(sizeof($uploads) + 1);
								?>
								<p><input type="hidden" name="upload_titel" id="upload_titel" value="<?php echo $upload_titel ?>" /></p>

								<div id="audio_src">
									<p><label for="upload_bestand">Bestand (WAV)</label><span id="browse"><input type="file" name="upload_bestand" id="upload_bestand" accept=".WAV" /><span class="feedback" style="float:right"></span></p>
								</div>

								<div id="feedback"><?php if (!empty($feedback)) : ?><p><?php echo $feedback ?></p><?php endif; ?></div>
								<p><input type="submit" name="uploaden" value="Bestand uploaden" id="uploaden" />
								<div id="toegevoegd"><?php echo sizeof($uploads) ?> / <?php echo $opdracht->opdracht_uploads_aantal ?> toegevoegd
									</p>
								</div>

							<?php else : ?>

								<!-- Opdracht insturen -->

								<p><input type="submit" name="insturen" value="Opdracht insturen" id="insturen" /></p>

							<?php endif; ?>
						</form>

					<?php endif; ?>

				<?php else : ?>

				<!-- Beoordeling afwachten -->
				<?php if (sizeof($uploads) > 0) : ?>
					<div id="audio">
						<?php foreach ($uploads as $item) : ?>
							<div class="audio">
								<div class="audio_titel"><?php echo ($item->bestand_titel != NULL ? $item->bestand_titel : $item->upload_titel) ?></div>
								<audio src="<?php echo base_url('media/opdrachten/' . $item->upload_src) ?>" preload="auto"></audio>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<h3>Resultaat in afwachting</h3>
				<p>De opdracht is ontvangen en wordt zo spoedig mogelijk nagekeken.</p>

			</div>
			<?php endif; ?>

			<?php if ($opdracht_beoordeeld) : ?>
			<div class="c-beoordeling">
				<h2>Beoordeling</h3>
				<div class="rating_fixed">
					<label class="<?php if ($inzending->opdracht_beoordeling >= 5) {	echo ' selected'; } ?>">
						<input type="radio" name="item_beoordeling" value="5" title="5 stars"> 5
					</label>
					<label class="<?php if ($inzending->opdracht_beoordeling >= 4) { echo ' selected'; } ?>">
						<input type="radio" name="item_beoordeling" value="4" title="4 stars"> 4
					</label>
					<label class="<?php if ($inzending->opdracht_beoordeling >= 3) { echo ' selected'; } ?>">
						<input type="radio" name="item_beoordeling" value="3" title="3 stars"> 3
					</label>
					<label class="<?php if ($inzending->opdracht_beoordeling >= 2) { echo ' selected'; } ?>">
						<input type="radio" name="item_beoordeling" value="2" title="2 stars"> 2
					</label>
					<label class="<?php if ($inzending->opdracht_beoordeling >= 1) { echo ' selected'; } ?>">
						<input type="radio" name="item_beoordeling" value="1" title="1 star"> 1
					</label>
				</div>
			</div>

			<?php if (!empty($opdracht_beoordeeld->opdracht_beoordeling_feedback)) : ?>
			<div class="c-feedback">
				<h2>Feedback</h2>
				<p><?php echo $opdracht_beoordeeld->opdracht_beoordeling_feedback ?></p>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
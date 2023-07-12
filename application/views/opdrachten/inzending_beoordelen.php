<head>
	<script src="https://www.localhost/assets/js/recorderjs/dist/recorder.js"></script>
</head>

<h1>Inzending beoordelen</h1>
<table cellpadding="0" cellspacing="0" class="details">
	<tr>
		<th>Ingestuurd</th>
		<td><?php echo date('d-m-Y', strtotime($inzending->opdracht_datum)) ?> om <?php echo date('H:i:s', strtotime($inzending->opdracht_datum)) ?> uur</td>
	</tr>
	<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
		<tr>
			<th>Deelnemer</th>
			<td><a href="<?php echo base_url('cms/deelnemers/'.$inzending->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php echo $inzending->gebruiker_naam ?></a></td>
		</tr>
		<tr>
			<th>Opdracht</th>
			<td><a href="<?php echo base_url('opdracht/'.$inzending->opdracht_url) ?>" title="Opdracht bekijken"><?php echo $inzending->opdracht_titel ?></a></td>
		</tr>
	<?php endif; ?>
	<tr>
		<th>Uploads</th>
		<td>
			<div id="audio">
				<?php foreach($uploads as $item): ?>
                        <div class="audio">
                            <div class="audio_titel"><?php echo $item->upload_titel ?></div>
                            <audio src="<?php echo base_url('media/opdrachten/'.$item->upload_src) ?>" preload="auto"></audio>
                        </div>
				<?php endforeach; ?>
			</div>
		</td>
	</tr>
</table>

<?php if($inzending->opdracht_beoordeling == ''): ?>
	<div class="formulier">
		<form method="post" enctype="multipart/form-data" action="<?php echo base_url('opdrachten/inzendingen/beoordelen/'.$inzending->opdracht_beoordeling_ID); ?>">
			<h2>Beoordeling</h2>
			<div class="rating" style="margin: 1em 0 1em 200px;">
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
			<p><label for="item_beoordeling_feedback">Feedback</label><textarea name="item_beoordeling_feedback" id="item_beoordeling_feedback" class="halfhoog"><?php echo $item_beoordeling_feedback ?></textarea><span class="feedback"><?php echo $item_beoordeling_feedback_error ?></span></p>

			<p class="submit"><input type="submit" value="Beoordelen" /></p>
	</div>
</form>
<?php elseif($inzending->opdracht_beoordeling != ''): ?>
	<h2>Beoordeling</h2>
	<div class="rating_fixed" style="margin-bottom: 1em;">
		<label class="<?php if ($inzending->opdracht_beoordeling >= 5) {	echo ' selected'; } ?>">
			<input type="radio" name="rating" value="5" title="5 stars"> 5
		</label>
		<label class="<?php if ($inzending->opdracht_beoordeling >= 4) { echo ' selected'; } ?>">
			<input type="radio" name="rating" value="4" title="4 stars"> 4
		</label>
		<label class="<?php if ($inzending->opdracht_beoordeling >= 3) { echo ' selected'; } ?>">
			<input type="radio" name="rating" value="3" title="3 stars"> 3
		</label>
		<label class="<?php if ($inzending->opdracht_beoordeling >= 2) { echo ' selected'; } ?>">
			<input type="radio" name="rating" value="2" title="2 stars"> 2
		</label>
		<label class="<?php if ($inzending->opdracht_beoordeling >= 1) { echo ' selected'; } ?>">
			<input type="radio" name="rating" value="1" title="1 star"> 1
		</label>
	</div>
	<?php if (!empty($inzending->opdracht_beoordeling_feedback)) : ?>
		<h3>Feedback</h3>
		<?php echo $inzending->opdracht_beoordeling_feedback ?>
	<?php else : ?>
		<em>Geen feedback</em>
	<?php endif; ?>
<?php endif; ?>
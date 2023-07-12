<h1><?php echo $item->media_titel ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/media/') ?>" title="Alle media">Alle media</a>
	<a href="<?php echo base_url('cms/media/wijzigen/'.$item->media_ID) ?>" title="Media wijzigen" class="wijzigen">Media wijzigen</a>
	<a href="<?php echo base_url('cms/media/verwijderen/'.$item->media_ID) ?>" title="Media verwijderen" class="verwijderen">Media verwijderen</a>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Type</th>
		<td><?php echo $item->media_type ?></td>
	</tr>
	<tr>
		<th>Titel</th>
		<td><?php echo $item->media_titel ?></td>
	</tr>
	<tr>
		<th>Uploaddatum</th>
		<td><?php if($item->media_datum != '0000-00-00 00:00:00') echo date('d-m-Y', strtotime($item->media_datum)); else echo '...'; ?></td>
	</tr>
	<tr>
		<th>Bestand</th>
		<td>
			<?php if($item->media_type == 'pdf'): ?>
				<a href="<?php echo base_url('media/pdf/'.$item->media_src) ?>" title="Open de PDF in een nieuw tabblad / venster" target="_blank"><?php echo $item->media_src ?></a>
			<?php elseif($item->media_type == 'afbeelding'): ?>
				<img src="<?php echo base_url('media/afbeeldingen/groot/'.$item->media_src) ?>" alt="<?php echo $item->media_titel ?>" />
			<?php elseif($item->media_type == 'video'): ?>
				<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="620" height="350" id="vzvd-<?php echo $item->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $item->media_src ?>" src="//view.vzaar.com/<?php echo $item->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="620" height="350"></iframe>
			<?php elseif($item->media_type == 'playlist'): ?>
				<iframe allowFullScreen allowTransparency="true" class="vzaar video player" frameborder="0" width="620" height="520" id="vzpl-<?php echo $item->media_src ?>" mozallowfullscreen name="vzpl-<?php echo $item->media_src ?>" src="//view.vzaar.com/playlists/<?php echo $item->media_src ?>" title="vzaar video player" type="text/html" webkitAllowFullScreen width="620" height="520"></iframe>
			<?php elseif($item->media_type == 'mp3'): ?>
				<a href="<?php echo base_url('media/audio/'.$item->media_src) ?>" title="Open de MP3 in een nieuw tabblad / venster" target="_blank"><?php echo $item->media_src ?></a>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th>Volledige URL</th>
		<td>
			<?php if($item->media_type == 'pdf'): ?>
				<?php echo base_url('media/pdf/'.$item->media_src) ?>
			<?php elseif($item->media_type == 'afbeelding'): ?>
				<?php echo base_url('media/afbeeldingen/groot/'.$item->media_src) ?>
			<?php elseif($item->media_type == 'video'): ?>
				//view.vzaar.com/<?php echo $item->media_src ?>
			<?php elseif($item->media_type == 'playlist'): ?>
				//view.vzaar.com/playlists/<?php echo $item->media_src ?>
			<?php elseif($item->media_type == 'mp3'): ?>
				<?php echo base_url('media/audio/'.$item->media_src) ?>
			<?php endif; ?>
		</td>
	</tr>
</table>
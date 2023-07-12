<h1>Media</h1>

<p id="links">
	<a href="<?php echo base_url('cms/media/toevoegen') ?>" title="Media toevoegen">Media toevoegen</a>
</p>

<p><strong>Filter:</strong>  <a class="sorteren" href="<?php echo base_url('cms/media/pagina/1') ?>" data-type="Alles">Geen</a> | <a class="sorteren" href="<?php echo base_url('cms/media/pagina/1/pdf') ?>" data-type="pdf">PDF</a> | <a class="sorteren" href="<?php echo base_url('cms/media/pagina/1/afbeelding') ?>" data-type="afbeelding">Afbeelding</a> | <a class="sorteren" href="<?php echo base_url('cms/media/pagina/1/video') ?>" data-type="video">Video</a> | <a class="sorteren" href="<?php echo base_url('cms/media/pagina/1/playlist') ?>" data-type="playlist">Playlist</a> | <a class="sorteren" href="<?php echo base_url('cms/media/pagina/1/mp3') ?>" data-type="mp3">MP3</a></p>
<input type="hidden" id="zoeken_media" value="" />
<div id="zoeken">
    <form method="post" action="<?php echo base_url('cms/zoeken') ?>">
        <div id="zoekveld">
            <p><input type="text" name="zoeken_term" id="zoeken_term" placeholder="Zoeken" autofocus="on" autocomplete="off" /></p>
        </div>
        <div id="zoekresultaten">
            <table cellpadding="0" cellspacing="0" border="0">
            </table>
</div>
    </form>
</div>
<div>
	<?php if(sizeof($media) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel">
			<tr>
				<th>Bestand</th>
				<th>Titel</th>
				<th>Uploaddatum</th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
				<th class="verwijderen"></th>
			</tr>
			<?php foreach($media as $item): ?>
				<tr>
					<td>
						<?php if($item->media_type == 'pdf'): ?>
							<a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="PDF details bekijken"><img src="<?php echo base_url('assets/images/pdf.png') ?>" alt="<?php echo $item->media_titel ?>" width="210" /></a>
						<?php elseif($item->media_type == 'afbeelding'): ?>
							<a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="Afbeelding details bekijken"><img src="<?php echo base_url('media/afbeeldingen/thumbnail/'.$item->media_src) ?>" alt="<?php echo $item->media_titel ?>" width="210" /></a>
						<?php elseif($item->media_type == 'video'): ?>
							<a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="Video details bekijken"><img src="//view.vzaar.com/<?php echo $item->media_src ?>/image" alt="<?php echo $item->media_titel ?>" width="210" /></a>
						<?php elseif($item->media_type == 'playlist'): ?>
							<a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="Playlist details bekijken"><img src="<?php echo base_url('assets/images/playlist.png') ?>" alt="<?php echo $item->media_titel ?>" width="210" /></a>
						<?php elseif($item->media_type == 'mp3'): ?>
							<a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="MP3 details bekijken"><img src="<?php echo base_url('assets/images/mp3.png') ?>" alt="<?php echo $item->media_titel ?>" width="210" /></a>
						<?php endif; ?>
					</td>
					<td><a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="Media bekijken"><?php echo $item->media_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="Media bekijken"><?php if($item->media_datum != '0000-00-00 00:00:00') echo  date('d-m-Y', strtotime($item->media_datum)); else echo '...'; ?></a></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/media/'.$item->media_ID) ?>" title="Media bekijken">Bekijken</a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/media/wijzigen/'.$item->media_ID) ?>" title="Media wijzigen">Wijzigen</a></td>
					<td class="verwijderen"><a href="<?php echo base_url('cms/media/verwijderen/'.$item->media_ID) ?>" title="Media verwijderen">Verwijderen</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php if($aantal_paginas > 1): ?>
			<div id="paginanummering">
				<p>
					<?php for($i = 1; $i <= $aantal_paginas; $i++): ?>
						<?php if($i == $huidige_pagina): ?>
							<a href="<?php echo base_url('cms/media/pagina/'.$i.'/'.$media_soort) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
						<?php else: ?>
							<a href="<?php echo base_url('cms/media/pagina/'.$i.'/'.$media_soort) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
						<?php endif; ?>
						<?php if($i < $aantal_paginas) echo ' |'; ?>
					<?php endfor; ?>
				</p>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<p>Er staat nog geen media in de database.</p>
	<?php endif; ?>
</div>
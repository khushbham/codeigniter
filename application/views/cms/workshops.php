<h1>Workshops</h1>

<p id="links">
	<a href="<?php echo base_url('cms/workshops/toevoegen') ?>" title="Workshop toevoegen">Workshop toevoegen</a>
	<a href="<?php echo base_url('cms/lessen/gratis_les_toevoegen') ?>" title="gratis les toevoegen">Gratis les toevoegen</a>
</p>
<ul class="tab">
	<li><a href="javascript:void(0)" id="tab_recent-workshops" class="tablinks <?php if($archief == false) echo 'active'; ?>"  onclick="openArchief(event, 'recent-workshops')">Workshops</a></li>
	<li><a href="javascript:void(0)" id="tab_archief" class="tablinks <?php if($archief == true) echo 'active'; ?>" onclick="openArchief(event, 'archief')">Archief</a></li>
</ul>
<input type="hidden" name="archief_open" id="archief_open" value="<?php echo $archief ?>">
<div id="workshops" style="padding:0 0 25px 0">
	<div id="recent-workshops" class="tabcontent">
	<?php if(sizeof($workshops) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="workshops">
			<thead>
				<tr>
					<th>ID</th>
					<th>Titel</th>
					<th>Afkorting</th>
					<th>Publieke site</th>
					<th>Cursisten site</th>
					<th class="type">Type</th>
					<th class="archiveren"></th>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($workshops as $item): ?>
					<tr data-item="<?php echo $item->workshop_ID ?>" <?php if(($item->workshop_gepubliceerd == 'ja') && ($item->workshop_uitgelicht == 'ja')) echo 'class="uitgelicht"'; ?> <?php if($item->workshop_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_ID ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_afkorting ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php if($item->workshop_zichtbaar_publiek) { echo "ja"; } else { echo "nee"; } ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php if($item->workshop_zichtbaar_cursist) { echo "ja"; } else { echo "nee"; } ?></a></td>
						<td class="type"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">
							<?php
							switch($item->workshop_type)
							{
								case 'groep':
								echo 'Online en fysieke groepslessen';
								break;

								case 'online':
								echo 'Online groepslessen';
								break;

								case 'individueel':
								echo 'Online lessen individueel';
								break;
							}
							?>
						</a></td>
						<td class="archiveren"><a href="<?php echo base_url('cms/workshops/archiveren/'.$item->workshop_ID) ?>" title="Workshop archiveren">archiveren</a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/workshops/wijzigen/'.$item->workshop_ID) ?>" title="Workshop wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/workshops/verwijderen/'.$item->workshop_ID) ?>" title="Workshop verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen standaard workshops in de database.</p>
	<?php endif; ?>
	<h2>Specialties</h2>
	<?php if(sizeof($specialties) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel sorteren" data-items="workshops">
			<thead>
				<tr>
					<th>ID</th>
					<th>Titel</th>
					<th>Afkorting</th>
					<th>Publieke site</th>
					<th>Cursisten site</th>
					<th class="type">Type</th>
					<td class="archiveren"></td>
					<th class="bekijken"></th>
					<th class="wijzigen"></th>
					<th class="verwijderen"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($specialties as $item): ?>
					<tr data-item="<?php echo $item->workshop_ID ?>" <?php if(($item->workshop_gepubliceerd == 'ja') && ($item->workshop_uitgelicht == 'ja')) echo 'class="uitgelicht"'; ?> <?php if($item->workshop_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_ID ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_afkorting ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php if($item->workshop_zichtbaar_publiek) { echo "ja"; } else { echo "nee"; } ?></a></td>
						<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php if($item->workshop_zichtbaar_cursist) { echo "ja"; } else { echo "nee"; } ?></a></td>
						<td class="type"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">
							<?php
							switch($item->workshop_type)
							{
								case 'groep':
								echo 'Online en fysieke groepslessen';
								break;

								case 'online':
								echo 'Online groepslessen';
								break;

								case 'individueel':
								echo 'Online lessen individueel';
								break;
							}
							?>
						</a></td>
						<td class="archiveren"><a href="<?php echo base_url('cms/workshops/archiveren/'.$item->workshop_ID) ?>" title="Workshop archiveren">Archiveren</a></td>
						<td class="bekijken"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">Bekijken</a></td>
						<td class="wijzigen"><a href="<?php echo base_url('cms/workshops/wijzigen/'.$item->workshop_ID) ?>" title="Workshop wijzigen">Wijzigen</a></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/workshops/verwijderen/'.$item->workshop_ID) ?>" title="Workshop verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen specialties in de database.</p>
	<?php endif; ?>
	</div>


<div id="archief" class="tabcontent">
	<?php if(sizeof($workshops_archief) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel js-sorteren" data-items="workshops">
			<thead>
			<tr>
				<th>ID</th>
				<th>Titel</th>
				<th>Afkorting</th>
				<th class="type">Type</th>
				<th class="dearchiveren"></th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
				<th class="verwijderen"></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($workshops_archief as $item): ?>
				<tr data-item="<?php echo $item->workshop_ID ?>" <?php if(($item->workshop_gepubliceerd == 'ja') && ($item->workshop_uitgelicht == 'ja')) echo 'class="uitgelicht"'; ?> <?php if($item->workshop_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_ID ?></a></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_afkorting ?></a></td>
					<td class="type"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">
							<?php
							switch($item->workshop_type)
							{
								case 'groep':
									echo 'Online en fysieke groepslessen';
									break;

								case 'online':
									echo 'Online groepslessen';
									break;

								case 'individueel':
									echo 'Online lessen individueel';
									break;
							}
							?>
						</a></td>
					<td class="dearchiveren"><a href="<?php echo base_url('cms/workshops/dearchiveren/'.$item->workshop_ID) ?>" title="Workshop dearchiveren">Dearchiveren</a></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">Bekijken</a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/workshops/wijzigen/'.$item->workshop_ID) ?>" title="Workshop wijzigen">Wijzigen</a></td>
					<td class="verwijderen"><a href="<?php echo base_url('cms/workshops/verwijderen/'.$item->workshop_ID) ?>" title="Workshop verwijderen">Verwijderen</a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen standaard workshops in het archief.</p>
	<?php endif; ?>
	<h2>Specialties</h2>
	<?php if(sizeof($specialties_archief) > 0): ?>
		<table cellpadding="0" cellspacing="0" class="tabel sorteren" data-items="workshops">
			<thead>
			<tr>
				<th>ID</th>
				<th>Titel</th>
				<th>Afkorting</th>
				<th class="type">Type</th>
				<th class="dearchiveren"></th>
				<th class="bekijken"></th>
				<th class="wijzigen"></th>
				<th class="verwijderen"></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach($specialties_archief as $item): ?>
				<tr data-item="<?php echo $item->workshop_ID ?>" <?php if(($item->workshop_gepubliceerd == 'ja') && ($item->workshop_uitgelicht == 'ja')) echo 'class="uitgelicht"'; ?> <?php if($item->workshop_gepubliceerd == 'nee') echo 'class="concept"'; ?>>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_ID ?></a></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
					<td><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken"><?php echo $item->workshop_afkorting ?></a></td>
					<td class="type"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">
							<?php
							switch($item->workshop_type)
							{
								case 'groep':
									echo 'Online en fysieke groepslessen';
									break;

								case 'online':
									echo 'Online groepslessen';
									break;

								case 'individueel':
									echo 'Online lessen individueel';
									break;
							}
							?>
						</a></td>
					<td class="dearchiveren"><a href="<?php echo base_url('cms/workshops/dearchiveren/'.$item->workshop_ID) ?>" title="workshop archiveren">Dearchiveren</a></td>
					<td class="bekijken"><a href="<?php echo base_url('cms/workshops/'.$item->workshop_ID) ?>" title="Workshop bekijken">Bekijken</a></td>
					<td class="wijzigen"><a href="<?php echo base_url('cms/workshops/wijzigen/'.$item->workshop_ID) ?>" title="Workshop wijzigen">Wijzigen</a></td>
					<td class="verwijderen"><a href="<?php echo base_url('cms/workshops/verwijderen/'.$item->workshop_ID) ?>" title="Workshop verwijderen">Verwijderen</a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Er staan geen specialties in het archief.</p>
	<?php endif; ?>
	</div>
</div>

<div id="gratis_workshop">
    <h2>Gratis workshop</h2>
    <p id="links">
        <a href="<?php echo base_url('cms/lessen/gratis_les_toevoegen') ?>" title="gratis les toevoegen">Gratis les toevoegen</a>
    </p>
    <?php if(sizeof($gratis_lessen) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th>Titel</th>
                <th>Youtube link</th>
                <th>Publicatie datum</th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
                <th class="verwijderen"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($gratis_lessen as $item): ?>
                <?php $gepubliceerd = strtotime($item->les_publicatiedatum) <= time(); ?>
                <tr <?php if(!$gepubliceerd) echo 'class="concept"'; ?>>
                    <td><a href="<?php echo base_url('cms/lessen/gratis_les_detail/'.$item->les_ID) ?>" title="Workshop bekijken"><?php echo $item->les_titel ?></a></td>
                    <td><a href="<?php echo base_url('cms/lessen/gratis_les_detail/'.$item->les_ID) ?>" title="Workshop bekijken"><?php echo $item->les_youtube_link ?></a></td>
                    <td class="datum"><a href="<?php echo base_url('cms/lessen/gratis_les_detail/'.$item->les_ID) ?>" title="Workshop bekijken"><?php echo date('d/m/Y', strtotime($item->les_publicatiedatum)) ?></a></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/lessen/gratis_les_detail/'.$item->les_ID) ?>" title="Les bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/lessen/gratis_les_wijzigen/'.$item->les_ID) ?>" title="Les wijzigen">Wijzigen</a></td>
                    <td class="verwijderen"><a href="<?php echo base_url('cms/lessen/gratis_les_verwijderen/'.$item->les_ID) ?>" title="Les verwijderen">Verwijderen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Er staan geen gratis lessen in de database.</p>
    <?php endif; ?>
</div>

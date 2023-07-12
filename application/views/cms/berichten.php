<div id="berichten">
	<h1>Berichten</h1>
	<p id="links">
		<a href="<?php echo base_url('cms/berichten/nieuw') ?>" title="Nieuw bericht" class="belangrijk">Nieuw bericht</a>
	</p>
	<ul class="tab">
		<li><a href="javascript:void(0)" id="tab_inbox" class="tablinks <?php if($verzonden == false) echo 'active'; ?>"  onclick="openInbox(event, 'inbox')">Inbox</a></li>
		<li><a href="javascript:void(0)" id="tab_verzonden" class="tablinks <?php if($verzonden == true) echo 'active'; ?>" onclick="openInbox(event, 'verzonden')">Verzonden</a></li>
	</ul>
	<input type="hidden" name="verzonden_open" id="verzonden_open" value="<?php echo $verzonden ?>">
	<div id="nieuws">
        <div id="inbox" class="tabcontent">
		<?php if(sizeof($berichten) > 0): ?>
            <p><input type="checkbox" name="inkomende_berichten_checkbox" onClick="toggle(this)"/>Alle inkomende berichten</p>
			<form method="post" action="<?php echo base_url('cms/berichten/verwijder_meerdere') ?>" id="formulier">
				<table cellpadding="0" cellspacing="0" class="tabel" width="">
					<thead>
					<tr>
						<th class="geselecteerd"></th>
						<th class="beantwoord"></th>
						<th class="nieuw"></th>
						<th class="afzender">Van</th>
						<th class="onderwerp">Onderwerp</th>
						<th class="datum">Datum</th>
						<th class="tijd">Tijd</th>
						<th class="verwijderen"></th>
					</tr>
					</thead>
					<tbody>
						<?php foreach($berichten as $bericht): ?>
							<tr>
								<td class="geselecteerd"><input class="inkomende_berichten_checkbox" type="checkbox" name="geselecteerde_berichten[]" value="<?php echo $bericht->bericht_ID ?>" ></td>
								<td class="beantwoord <?php echo $bericht->bericht_beantwoord ?>"></td>
								<td class="nieuw"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><div class="enveloppe <?php echo $bericht->bericht_nieuw ?>"><?php echo $bericht->bericht_nieuw ?></div></a></td>
								<td class="afzender"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php if($bericht->bericht_afzender_type == 'docent') echo '<strong>'; ?><?php echo $bericht->gebruiker_naam ?><?php if($bericht->bericht_afzender_type == 'docent') echo '</strong>'; ?></a></td>
								<td class="onderwerp"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo $bericht->bericht_onderwerp ?></a></td>
								<td class="datum"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo toonDatum($bericht->bericht_datum) ?></a></td>
								<td class="tijd"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo toonTijd($bericht->bericht_datum) ?></a></td>
								<td class="verwijderen"><a href="<?php echo base_url('cms/berichten/verwijderen/'.$bericht->bericht_ID) ?>" title="Bericht verwijderen">Verwijderen</a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p id="verwijderen">
					<input type="submit" class="verwijderen" value="Geselecteerde berichten verwijderen"/>
				</p>
			</form>
		<?php else: ?>
			<p>Je hebt geen berichten.</p>
		<?php endif; ?>
        </div>
        <div id="verzonden" class="tabcontent">
		<?php if(sizeof($verzonden_berichten) > 0): ?>
            <p><input type="checkbox" name="verzonden_berichten_checkbox" onClick="toggle(this)"/>Alle verzonden berichten</p>
			<form method="post" action="<?php echo base_url('cms/berichten/verwijder_meerdere') ?>" id="formulier">
				<table cellpadding="0" cellspacing="0" class="tabel">
					<thead>
					<tr>
						<th class="geselecteerd"></th>
						<th class="beantwoord"></th>
						<th class="afzender">Van</th>
						<th class="onderwerp">Onderwerp</th>
						<th class="datum">Datum</th>
						<th class="tijd">Tijd</th>
						<th class="nieuw">Gelezen</th>
						<th class="verwijderen"></th>
					</tr>
					</thead>
					<tbody>
						<?php foreach($verzonden_berichten as $bericht): ?>
							<tr>
								<td class="geselecteerd"><input class="verzonden_berichten_checkbox" type="checkbox" name="geselecteerde_berichten[]" value="<?php echo $bericht->bericht_ID ?>" ></td>
								<td class="beantwoord <?php echo $bericht->bericht_beantwoord ?>"></td>
								<td class="afzender"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php if($bericht->bericht_afzender_type == 'docent') echo '<strong>'; ?><?php echo $bericht->gebruiker_naam ?><?php if($bericht->bericht_afzender_type == 'docent') echo '</strong>'; ?></a></td>
								<td class="onderwerp"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo $bericht->bericht_onderwerp ?></a></td>
								<td class="datum"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo toonDatum($bericht->bericht_datum) ?></a></td>
								<td class="tijd"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><?php echo toonTijd($bericht->bericht_datum) ?></a></td>
								<?php if($bericht->bericht_nieuw == "ja") { ?>
									<td class="betaald"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><div class="enveloppe <?php echo $bericht->bericht_nieuw ?>"></div> <span class="nee"></span></a></td>
								<?php } else { ?>
									<td class="betaald"><a href="<?php echo base_url('cms/berichten/'.$bericht->bericht_ID) ?>" title="Bericht lezen"><div class="enveloppe <?php echo $bericht->bericht_nieuw ?>"></div> <span class="ja"></span></a></td>
								<?php } ?>
								<td class="verwijderen"><a href="<?php echo base_url('cms/berichten/verwijderen/'.$bericht->bericht_ID) ?>" title="Bericht verwijderen">Verwijderen</a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<p id="verwijderen">
					<input type="submit" class="verwijderen" value="Geselecteerde berichten verwijderen"/>
				</p>
			</form>
	<?php else: ?>
		<p>Je hebt geen berichten.</p>
	<?php endif; ?>
        </div>
    </div>
<br><br>
    <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
	<div id="templates">
		<h2>Templates</h2>
		<?php if(sizeof($templates) > 0): ?>
			<table cellpadding="0" cellspacing="0" class="tabel">
				<thead>
				<tr>
					<th>Titel</th>
					<th class="verwijderen"></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($templates as $item): ?>
					<tr>
						<td><?php echo $item->template_titel ?></td>
						<td class="verwijderen"><a href="<?php echo base_url('cms/berichten/template_verwijderen/'.$item->template_ID) ?>" title="template verwijderen">Verwijderen</a></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<p>Er staan geen templates in de database.</p>
		<?php endif; ?>
	</div>
    <?php endif; ?>
</div>

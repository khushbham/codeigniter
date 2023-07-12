<h1><?php echo $gebruiker->gebruiker_naam ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/beheerders/') ?>" title="Alle beheerders">Alle beheerders</a>
	<a href="<?php echo base_url('cms/beheerders/wijzigen/'.$gebruiker->gebruiker_ID) ?>" title="Beheerder wijzigen" class="wijzigen">Beheerder wijzigen</a>
	<a href="<?php echo base_url('cms/beheerders/verwijderen/'.$gebruiker->gebruiker_ID) ?>" title="Beheerder verwijderen" class="verwijderen">Beheerder verwijderen</a>
</p>

<h2>Gegevens</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Rol</th>
		<td><?php echo ucfirst($gebruiker->gebruiker_rechten) ?></td>
	</tr>
	<tr>
		<th>Bedrijfsnaam</th>
		<td><?php echo $gebruiker->gebruiker_bedrijfsnaam ?></td>
	</tr>
	<tr>
		<th>Geslacht</th>
		<td><?php echo ucfirst($gebruiker->gebruiker_geslacht) ?></td>
	</tr>
	<tr>
		<th>Geboortedatum</th>
		<td><?php echo date('d/m/Y', strtotime($gebruiker->gebruiker_geboortedatum)) ?></td>
	</tr>
	<tr>
		<th>Adres</th>
		<td><?php echo $gebruiker->gebruiker_adres ?></td>
	</tr>
	<tr>
		<th>Postcode</th>
		<td><?php echo $gebruiker->gebruiker_postcode ?></td>
	</tr>
	<tr>
		<th>Plaats</th>
		<td><?php echo $gebruiker->gebruiker_plaats ?></td>
	</tr>
	<tr>
		<th>Telefoonnummer</th>
		<td><?php echo $gebruiker->gebruiker_telefoonnummer ?></td>
	</tr>
	<tr>
		<th>Mobiel</th>
		<td><?php echo $gebruiker->gebruiker_mobiel ?></td>
	</tr>
	<tr>
		<th>E-mailadres</th>
		<td><?php echo $gebruiker->gebruiker_emailadres ?></td>
	</tr>
</table>
<h2>Instellingen</h2>
<table cellpadding="0" cellpadding="0" class="gegevens">
	<tr>
		<th>Instelling anoniem</th>
		<td><?php echo ucfirst($gebruiker->gebruiker_instelling_anoniem) ?></td>
	</tr>
	<tr>
		<th>Instelling e-mail updates</th>
		<td><?php echo ucfirst($gebruiker->gebruiker_instelling_email_updates) ?></td>
	</tr>
</table>
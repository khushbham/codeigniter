<h1><?php echo $uitnodiging->uitnodiging_onderwerp ?></h1>

<p id="links">
	<a href="<?php echo base_url('cms/uitnodigingen/') ?>" title="Alle automations">Alle automations</a>
	<a href="<?php echo base_url('cms/uitnodigingen/wijzigen/'.$uitnodiging->uitnodiging_ID) ?>" title="Automation wijzigen" class="wijzigen">Automation wijzigen</a>
	<a href="<?php echo base_url('cms/uitnodigingen/verwijderen/'.$uitnodiging->uitnodiging_ID) ?>" title="Automation verwijderen" class="verwijderen">Automation verwijderen</a>
</p>

<h2>Automation</h2>
<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Onderwerp</th>
		<td><?php echo ucfirst($uitnodiging->uitnodiging_onderwerp) ?></td>
	</tr>
	<tr>
		<th>Tekst</th>
		<td><?php echo $uitnodiging->uitnodiging_tekst ?></td>
	</tr>
	<tr>
		<th>Workshop</th>
		<td><?php echo $uitnodiging->workshop_titel ?></td>
	</tr>
	<tr>
		<th>Les</th>
		<td><?php echo $uitnodiging->les_titel ?></td>
	</tr>
	<tr>
		<th>Aantal dagen na afloop</th>
		<td><?php echo $uitnodiging->uitnodiging_dagen_na_afloop ?></td>
	</tr>
	<tr>
		<th>Limiet</th>
		<td><?php echo $uitnodiging->uitnodiging_limiet ?></td>
	</tr>
</table>

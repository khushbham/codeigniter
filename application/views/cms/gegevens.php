<h1>Gegevens</h1>

<p id="links">
	<a href="<?php echo base_url('cms/gegevens/wijzigen/') ?>" title="Gegevens wijzigen" class="wijzigen">Gegevens wijzigen</a>
	<?php if(!empty($demo->gebruiker_ID)): ?>
		<a href="<?php echo base_url('cms/gegevens/demo_wijzigen/'. $demo->gebruiker_ID) ?>" title="Demo account wijzigen" class="wijzigen">Demo account wijzigen</a>
	<?php else: ?>
		<a href="<?php echo base_url('cms/gegevens/demo_wijzigen/') ?>" title="Demo account toevoegen" class="toevoegen">Demo account toevoegen</a>
	<?php endif; ?>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<?php for($i = 0; $i < sizeof($gegevens); $i++): ?>
		<tr>
			<th><?php echo $gegevens[$i]->gegeven_naam ?></th>
			<td><?php echo $gegevens[$i]->gegeven_waarde ?></td>
		</tr>
	<?php endfor; ?>
</table>

<?php if(!empty($betaal_methodes)): ?>
	<h3>Betaalmethodes percentages</h3>
	<table cellpadding="0" cellspacing="0" class="gegevens">
		<?php for($i = 0; $i < sizeof($betaal_methodes); $i++): ?>
			<tr>
				<th><?php echo $betaal_methodes[$i]->naam ?></th>
				<td><?php echo $betaal_methodes[$i]->percentage ?></td>
			</tr>
		<?php endfor; ?>
	</table>
<?php endif; ?>

<h3>Ical links</h3>
<table cellpadding="0" cellspacing="0" class="gegevens">
    <tr>
        <th>Afspraken</th>
        <td><a href="<?php echo base_url('Ical/afspraken/SwW9ihEgRCuz8tP') ?>">Ical afspraken downloaden</a></td>
    </tr>
    <tr>
        <th>Groep lessen</th>
        <td><a href="<?php echo base_url('Ical/lessen/AGPW5JXVvVGovhK') ?>">Ical lessen downloaden</a></td>
    </tr>
</table>
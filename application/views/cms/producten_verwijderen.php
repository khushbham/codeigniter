<h1>Product verwijderen</h1>
<div id="verwijderen">
	<?php if(sizeof($bestellingen) > 0): ?>
		<?php if(sizeof($workshops) == 1): ?>
			<p>Het product kan niet worden verwijderd, omdat hij gekoppeld is aan een bestelling.<br />
			Verwijder eerst de desbetreffende bestelling voordat je het product kunt verwijderen.</p>
		<?php else: ?>
			<p>Het product kan niet worden verwijderd, omdat hij gekoppeld is aan meerdere bestellingen.<br />
			Verwijder eerst de desbetreffende bestellingen voordat je het product kunt verwijderen.</p>
		<?php endif; ?>
	<?php else: ?>
		<p>Weet u zeker dat u het product <strong><?php echo $item->product_naam ?></strong> wilt verwijderen?</p>
		<?php if(sizeof($workshops) > 0): ?>
			<?php if(sizeof($workshops) == 1): ?>
				<p>Let op! Er is één workshop waaraan het product is gekoppeld. Deze koppeling wordt verwijderd.</p>
			<?php else: ?>
				<p>Let op! Er zijn <?php echo sizeof($workshops) ?> workshops waaraan het product is gekoppeld. Deze koppelingen wordt verwijderd.</p>
			<?php endif; ?>
		<?php endif; ?>
		<p><a href="<?php echo base_url('cms/producten/verwijderen/'.$item->product_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/producten/wijzigen/'.$item->product_ID) ?>" title="Nee, annuleren">Nee</a></p>
	<?php endif; ?>
</div>
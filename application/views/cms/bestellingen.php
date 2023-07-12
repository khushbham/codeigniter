<?php 
 if($this->session->userdata('beheerder_rechten') == 'docent') :
 ?>
<style>
	#inhoud {
    padding: 20px 60px 163px 60px !important;
	}
</style>
<?php 
endif;
?>
<h1>Bestellingen</h1>
<div id="zoeken_bestellingen">
		<form method="POST" action="<?php echo base_url('cms/bestellingen') ?>" name="zoekfunctie">
			<div id="zoekveldBestellingen">
				<p class="submit"><input type="text" name="item_zoeken" id="item_zoeken" placeholder="Zoeken" autofocus="on" autocomplete="off" value="<?php echo $item_zoeken ?>" /><input style="float: right;" type="submit" name="submit" value="Zoeken"/></p>
			</div>
		</form>
	</div>
<form id="deelnemers_actie" method="post" action="<?php echo base_url('cms/bestellingen/export_verzonden/') ?>">
<div id="links">
	<p>
		<button name="actie" type="submit" value="exporteren">Bestellingen exporteren</button>
  		<button name="actie" type="submit" value="verzenden">Bestellingen verzonden</button>
	</p>
</div>
<?php if(!empty($zoek_lijst)): ?>
<h2>Resultaten</h2>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="geselecteerd"></th>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijd">Producten</th>
			<th>Deelnemer</th>
			<th>Groep</th>
			<th class="betaald">Betaald</th>
            <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' ||$this->session->userdata('beheerder_rechten') == 'docent'): ?>
			<th class="wijzigen"></th>
            <?php endif; ?>
		</tr>
		<?php foreach($zoek_lijst as $item): ?>
			<tr>
				<td class="geselecteerd"><input style="margin: 0px;" type="checkbox" name="geselecteerde_bestellingen[]" value="<?php echo $item->bestelling_ID ?>"></td>
				<td class="nummer"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $item->bestelling_ID ?></a></td>
				<?php
				if($item->aanmelding_ID != null)
				{
					$datum = $item->aanmelding_datum;
					$betaald_datum = $item->aanmelding_betaald_datum;
				}
				else
				{
					if(!empty($item->bestelling_datum) && !empty($item->bestelling_betaald_datum)) {
					$datum = $item->bestelling_datum;
					$betaald_datum = $item->bestelling_betaald_datum;
					} else {
						$datum = "";
						$betaald_datum = "";
					}
				}
				?>
				<td class="datum"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo date('d/m/Y', strtotime($datum)) ?></a></td>
				<td><a <?php if($this->session->userdata('beheerder_rechten') != 'docent'){ echo 'href='.base_url('cms/deelnemers/'.$item->gebruiker_ID); }  ?> title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<?php if(!empty($item->groep_ID)) { ?>
				<td><a href="<?php echo base_url('cms/groepen/'.$item->groep_ID) ?>" title="Groep bekijken"><?php echo $item->groep_naam ?></a></td>
				<?php } else { ?>
					<td>...</td>
				<?php } ?>
				<td class="betaald"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php if($betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></a></td>
                <?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
				<td class="wijzigen"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling wijzigen">Wijzigen</a></td>
                <?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>


<h2>Verzenden</h2>
<?php if(sizeof($bestellingen_verzenden) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="geselecteerd"></th>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijd">Producten</th>
			<th>Deelnemer</th>
			<th class="betaald">Betaald</th>
            <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
			<th class="wijzigen"></th>
            <?php endif; ?>
		</tr>
		<?php foreach($bestellingen_verzenden as $item): ?>
			<tr>
				<td class="geselecteerd"><input style="margin: 0px;" type="checkbox" name="geselecteerde_bestellingen[]" value="<?php echo $item->bestelling_ID ?>"></td>
				<td class="nummer"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $item->bestelling_ID ?></a></td>
				<?php
				if($item->aanmelding_ID != null)
				{
					$datum = $item->aanmelding_datum;
					$betaald_datum = $item->aanmelding_betaald_datum;
				}
				else
				{
					if(!empty($item->bestelling_datum) && !empty($item->bestelling_betaald_datum)) {
						$datum = $item->bestelling_datum;
						$betaald_datum = $item->bestelling_betaald_datum;
						} else {
							$datum = "";
							$betaald_datum = "";
						}
				}
				?>
				<td class="datum"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo date('d/m/Y', strtotime($datum)) ?></a></td>
				<td class="tijd" ><a class="productName_<?php if(isset($item->product_ID)){ echo $item->product_ID; } ?>" href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php if(isset($item->product_ID)){ echo $item->product_naam; } ?></a></td>
				<td><a <?php if($this->session->userdata('beheerder_rechten') != 'docent'){ echo 'href='.base_url('cms/deelnemers/'.$item->gebruiker_ID); }  ?> title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<td class="betaald"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php if($betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></a></td>
                <?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
				    <td class="wijzigen"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling wijzigen">Wijzigen</a></td>
                <?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><em>Er hoeven geen bestellingen te worden verzonden.</em></p>
<?php endif; ?>
</form>

<h2>Verzonden</h2>
<?php if(sizeof($bestellingen_verzonden) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijd">Producten</th>
			<th>Deelnemer</th>
			<th class="betaald">Betaald</th>
            <?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
			    <th class="wijzigen"></th>
            <?php endif; ?>
		</tr>
		<?php foreach($bestellingen_verzonden as $item): ?>
			<tr>
				<td class="nummer"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $item->bestelling_ID ?></a></td>
				<?php
				if($item->aanmelding_ID != null)
				{
					$betaald_datum = $item->aanmelding_betaald_datum;
				}
				else
				{
					$betaald_datum = $item->bestelling_betaald_datum;
				}
				?>
				<td class="datum"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo date('d/m/Y', strtotime($item->bestelling_verzonden_datum)) ?></a></td>
				<?php 
				if(sizeof($allproducts) > 0){
					foreach ($allproducts as $name) {
						if($name->product_ID == $item->product_ID){
							?>
							<td class="tijd"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $name->product_naam; ?></a></td>
							<?php
						}
					}
				}
				?>

				<td><a <?php if($this->session->userdata('beheerder_rechten') != 'docent'){ echo 'href='.base_url('cms/deelnemers/'.$item->gebruiker_ID); }  ?> title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<td class="betaald"><?php if($betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></td>
                <?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
				    <td class="wijzigen"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling wijzigen">Wijzigen</a></td>
                <?php endif; ?>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><em>Er zijn nog geen bestellingen verzonden.</em></p>
<?php endif; ?>

<h2>Huur verzenden</h2>
<?php if(sizeof($bestellingen_verzenden_huur) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijd">Producten</th>
			<th>Deelnemer</th>
			<th class="betaald">Betaald</th>
            <?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
			    <th class="wijzigen"></th>
            <?php endif; ?>
		</tr>
		<?php foreach($bestellingen_verzenden_huur as $item): ?>
			<?php if (!empty($item->bestellingen_huur)): ?>
			<?php if ($item->bestellingen_huur == 1): ?>
				<tr>
					<td class="nummer"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $item->bestelling_ID ?></a></td>
					<?php
					if($item->aanmelding_ID != null)
					{
						$datum = $item->aanmelding_datum;
						$betaald_datum = $item->aanmelding_betaald_datum;
					}
					else
					{
						if(!empty($item->bestelling_datum) && !empty($item->bestelling_betaald_datum)) {
							$datum = $item->bestelling_datum;
							$betaald_datum = $item->bestelling_betaald_datum;
							} else {
								$datum = "";
								$betaald_datum = "";
							}
					}
					?>
					<td class="datum"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo date('d/m/Y', strtotime($datum)) ?></a></td>
					<td class="tijd"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $item->product_naam; ?></a></td>
					<td><a <?php if($this->session->userdata('beheerder_rechten') != 'docent'){ echo 'href='.base_url('cms/deelnemers/'.$item->gebruiker_ID); }  ?> title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
					<td class="betaald"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php if($betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></a></td>
					<?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
						<td class="wijzigen"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling wijzigen">Wijzigen</a></td>
					<?php endif; ?>
				</tr>
			<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><em>Er hoeven geen bestellingen te worden verzonden.</em></p>
<?php endif; ?>

<h2>Huur verzonden</h2>
<?php if(sizeof($bestellingen_verzonden_huur) > 0): ?>
	<table cellpadding="0" cellspacing="0" class="tabel">
		<tr>
			<th class="nummer">#</th>
			<th class="datum">Datum</th>
			<th class="tijd">Producten</th>
			<th>Deelnemer</th>
			<th class="betaald">Betaald</th>
            <?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
			    <th class="wijzigen"></th>
            <?php endif; ?>
		</tr>
		<?php foreach($bestellingen_verzonden_huur as $item): ?>
			<?php if (!empty($item->bestellingen_huur)): ?>
			<?php if ($item->bestellingen_huur == 1): ?>
			<tr>
				<td class="nummer"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $item->bestelling_ID ?></a></td>
				<?php
				if($item->aanmelding_ID != null)
				{
					$betaald_datum = $item->aanmelding_betaald_datum;
				}
				else
				{
					$betaald_datum = $item->bestelling_betaald_datum;
				}
				?>
				<td class="datum"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo date('d/m/Y', strtotime($item->bestelling_verzonden_datum)) ?></a></td>
				<td class="tijd"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling bekijken"><?php echo $item->product_naam; ?></a></td>
				<td><a <?php if($this->session->userdata('beheerder_rechten') != 'docent'){ echo 'href='.base_url('cms/deelnemers/'.$item->gebruiker_ID); }  ?> title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $item->gebruiker_naam ?></span></a></td>
				<td class="betaald"><?php if($betaald_datum == '0000-00-00 00:00:00') echo '<span class="nee"></span>'; else echo '<span class="ja"></span>'; ?></td>
                <?php  if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support'): ?>
				    <td class="wijzigen"><a href="<?php echo base_url('cms/bestellingen/'.$item->bestelling_ID) ?>" title="Bestelling wijzigen">Wijzigen</a></td>
                <?php endif; ?>
			</tr>
			<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><em>Er zijn nog geen bestellingen verzonden.</em></p>
<?php endif; ?>

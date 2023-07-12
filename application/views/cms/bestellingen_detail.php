<?php 
if( $this->session->userdata('beheerder_rechten') == 'docent') :
?>
<style>
	#content h1  {
    font-size: 38px;
    font-weight: 900;
    line-height: 40px;
    color: #009DC6;
    margin: 0 0 25px 0;
}
#inhoud {
    padding: 0 60px;
    padding: 60px 20px 180px 20px !important;
}
</style>

<?php
endif;
?>
<h1 clasa="Bestelling">Bestelling wijzigen</h1>
<p id="links">
	<a href="<?php echo base_url('cms/bestellingen') ?>" title="Alle bestellingen">Alle bestellingen</a>
    <?php  if($this->session->userdata('beheerder_rechten') == 'admin'  || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'docent'): ?>
        <a href="<?php echo base_url('cms/bestellingen/wijzigen/'.$bestelling->bestelling_ID) ?>" title="Bestelling wijzigen" class="wijzigen">Bestelling wijzigen</a>
        <a href="<?php echo base_url('cms/bestellingen/verwijderen/'.$bestelling->bestelling_ID) ?>" title="Bestelling verwijderen" class="verwijderen">Bestelling verwijderen</a>
    <?php endif; ?>
</p>

<table cellpadding="0" cellspacing="0" class="gegevens">
	<tr>
		<th>Deelnemer</th>
		<td><?php echo $bestelling->gebruiker_naam ?></td>
	</tr>
	<tr>
		<th>Adres</th>
		<td><?php echo $bestelling->gebruiker_adres ?>, <?php echo $bestelling->gebruiker_postcode ?> <?php echo $bestelling->gebruiker_plaats ?></td>
	</tr>
	<tr>
		<th>Afleveradres</th>
		<td><?php echo $bestelling->bestelling_adres ?>, <?php echo $bestelling->bestelling_postcode ?> <?php echo $bestelling->bestelling_plaats ?></td>
	</tr>
	<?php
		if($bestelling->aanmelding_ID != null)
		{
			$betaald_datum = $bestelling->aanmelding_betaald_datum;
		}
		else
		{
			$betaald_datum = $bestelling->bestelling_betaald_datum;
		}
	?>
	<tr>
		<th>Betaald</th>
		<td><?php if($betaald_datum != '0000-00-00 00:00:00') echo 'Ja ('.date('d/m/Y', strtotime($betaald_datum)).')'; else echo 'Nee'; ?></td>
	</tr>
	<tr>
		<th>Verzonden</th>
		<td><?php if($bestelling->bestelling_verzonden_datum != '0000-00-00 00:00:00') echo 'Ja ('.date('d/m/Y', strtotime($bestelling->bestelling_verzonden_datum)).')'; else echo 'Nee'; ?></td>
	</tr>
</table>

<h2>Producten</h2>
<div id="toevoegen_wijzigen" class="formulier">
	<form method="post" action="<?php echo base_url('cms/bestellingen/'.$item_ID); ?>">
<div id="producten">
	<?php foreach($producten as $product): ?>
	<label for="item_verzonden">Verzonden</label>
		<div class="product">
			<p><?php echo $product->product_naam ?>
			<input type="radio" name="producten[<?php echo $product->bestelling_product_ID ?>]" id="item_verzonden_ja" value="1" <?php if($product->verzonden == 1) echo 'checked'; ?> /> Ja
			<input type="radio" name="producten[<?php echo $product->bestelling_product_ID ?>]" id="item_verzonden_nee" value="0" <?php if($product->verzonden == 0) echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_verzonden_feedback ?></span></p>
		</div>
	<?php endforeach; ?>
</div>
<?php if($this->session->userdata('beheerder_rechten') == 'admin' || $this->session->userdata('beheerder_rechten') == 'support' || $this->session->userdata('beheerder_rechten') == 'docent')  : ?>
		<p>
			<label for="item_verzonden">Verzonden</label>
			<input type="radio" name="item_verzonden" id="item_verzonden_ja" value="ja" <?php if($item_verzonden == 'ja') echo 'checked'; ?> /> Ja
			<input type="radio" name="item_verzonden" id="item_verzonden_nee" value="nee" <?php if($item_verzonden == 'nee') echo 'checked'; ?> /> Nee <span class="feedback"><?php echo $item_verzonden_feedback ?></span>
		</p>
		<p class="submit"><input type="submit" value="Bestelling wijzigen" /></p>
	</form>
</div>
<?php endif; ?>

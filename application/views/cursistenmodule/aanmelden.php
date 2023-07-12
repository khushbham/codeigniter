<section id="aanmelden">
	<!------------->
	<!-- STAPPEN -->
	<!------------->

	<div id="stappen">
		<ol>
			<li class="active">Aanmelden</li>
			<?php if($aanmelden_voor == 'workshop' && sizeof($producten) > 0): ?><li>Producten</li><?php endif; ?>
			<li>Bevestigen</li>
			<li>Betalen</li>
			<li>Aangemeld</li>
		</ol>
	</div>


	<!--------------------->
	<!-- TITEL EN UITLEG -->
	<!--------------------->

	<div id="uitleg">
		<?php if($aanmelden_voor == 'intake'): ?>
			<h1>Aanmelden intake <?php echo $workshop->workshop_titel ?></h1>
			<h3>Uitleg</h3>
			<?php echo $workshop->workshop_stemtest_tekst ?>
		<?php elseif($aanmelden_voor == 'stemtest'): ?>
			<h1>Aanmelden stemtest <?php echo $workshop->workshop_titel ?></h1>
			<h3>Uitleg</h3>
			<?php echo $workshop->workshop_stemtest_tekst ?>
		<?php else: ?>
			<h1>Aanmelden <?php echo $workshop->workshop_titel ?></h1>
			<?php echo $workshop->workshop_aanmelden_tekst ?>
		<?php endif; ?>
	</div>


	<!----------------->
	<!-- FORMULIEREN -->
	<!----------------->

	<!--
	MOGELIJKHEDEN:
	1. Aanmelden intake introductieworkshop
	2. Aanmelden workshop introductieworkshop -> Aanmelden met intake code (kort code)
	3. Aanmelden stemtest workshop
	4. Aanmelden workshop zonder stemtest
	5. Aanmelden workshop met stemtest -> Aanmelden met stemtest code (kort code)
	-->
	<form method="post" action="<?php echo base_url('cursistenmodule/aanmelden/'.$aanmelden_voor.'/'.$workshop->workshop_url.'#formulier') ?>" id="formulier">


		<!------------------->
		<!-- RADIO BUTTONS -->
		<!------------------->

		<!--
		MOGELIJKHEDEN:
		1. Aanmelden introductieworkshop d.m.v. intake code
		2. Aanmelden voor stemtest
		3. Aanmelden voor workshop zonder stemtest
		-->

		<?php if(($aanmelden_voor == 'workshop' && $workshop->workshop_ID == 6) || $aanmelden_voor == 'stemtest' || ($aanmelden_voor == 'workshop' && $workshop->workshop_stemtest == 'nee')): ?>

			<div id="formulier_vraag">
				<?php if($aanmelden_voor == 'workshop' && $workshop->workshop_ID == 6): ?>
					<h2>Heb je een intake gedaan?</h2>
				<?php endif; ?>
			</div>

			<input type="hidden" name="aanmelden_formulier" value="kort" />
		<?php elseif($aanmelden_voor == 'workshop' && $workshop->workshop_stemtest == 'ja'): ?>
			<input type="hidden" name="aanmelden_formulier" value="kort" />
		<?php else: ?>
			<input type="hidden" name="aanmelden_formulier" value="lang" />
		<?php endif; ?>



		<!----------------------------------------->
		<!-- LANG FORMULIER (VOLLEDIG AANMELDEN) -->
		<!----------------------------------------->

		<div id="formulier_lang" <?php if($aanmelden_formulier == 'kort') echo 'class="onzichtbaar"'; ?>>
			<!-- Veld: Startdata -->

			<?php if($aanmelden_voor == 'workshop' && in_array($workshop->workshop_type, array('groep', 'online'))): ?>
				<p>
					<label for="aanmelden_startdatum">Startdatum</label>
					<span class="startdatum">
					<?php if(sizeof($groepen) == 0):  ?>
						Geen startdata bekend, aanmelden niet mogelijk
					<?php elseif(sizeof($groepen) == 1): ?>
						<input type="hidden" name="aanmelden_startdatum" id="aanmelden_startdatum" value="<?php echo $groepen[0]->groep_ID ?>" /><?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?>
					<?php else: ?>
						<select name="aanmelden_startdatum" id="aanmelden_startdatum"><?php foreach($groepen as $groep): ?><option value="<?php echo $groep->groep_ID ?>" <?php if($aanmelden_startdatum == $groep->groep_ID) echo 'selected'; ?>><?php echo date('d-m-Y', strtotime($groep->groep_startdatum)) ?></option><?php endforeach; ?></select>
					<?php endif; ?>
					</span>
					<?php if(!empty($aanmelden_startdatum_feedback)): ?><span class="feedback"><?php echo $aanmelden_startdatum_feedback ?></span><?php endif; ?>
				</p>
			<?php endif; ?>

			<!-- Veld: Kortingscode -->
			<p><label for="aanmelden_kortingscode">Couponcode</label><input type="text" name="aanmelden_kortingscode" id="aanmelden_kortingscode" value="<?php echo $aanmelden_kortingscode ?>" /><?php if(!empty($aanmelden_kortingscode_feedback)): ?><span class="feedback"><?php echo $aanmelden_kortingscode_feedback ?></span><?php endif; ?></p>
		</div>



		<!--------------------------------------->
		<!-- KORTE FORMULIEREN (CODE/INLOGGEN) -->
		<!--------------------------------------->

		<div id="formulier_kort" <?php if($aanmelden_formulier == 'kort') echo 'class="zichtbaar"'; ?>>

			<?php if($aanmelden_voor == 'workshop' && $workshop->workshop_ID == 6): ?>
				<h3>Aanmelden met intake code</h3>
			<?php elseif($aanmelden_voor == 'workshop' && ($workshop->workshop_ID == 9 || $workshop->workshop_niveau == 5)): ?>
				<h3>Aanmelden met uitnodigingscode</h3>
			<?php elseif($aanmelden_voor == 'workshop' && $workshop->workshop_stemtest == 'ja'): ?>
				<h3>Aanmelden met uitnodigingscode</h3>
			<?php endif; ?>


			<!-- Veld: Startdata -->

			<?php if($aanmelden_voor == 'workshop' && in_array($workshop->workshop_type, array('groep', 'online'))): ?>
				<p>
					<label for="aanmelden_kort_startdatum">Startdatum</label>
					<span class="startdatum">
					<?php if(sizeof($groepen) == 0):  ?>
						Geen startdata bekend, aanmelden niet mogelijk
					<?php elseif(sizeof($groepen) == 1): ?>
						<input type="hidden" name="aanmelden_kort_startdatum" id="aanmelden_kort_startdatum" value="<?php echo $groepen[0]->groep_ID ?>" /><?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?>
					<?php else: ?>
						<select name="aanmelden_kort_startdatum" id="aanmelden_kort_startdatum"><?php foreach($groepen as $groep): ?><option value="<?php echo $groep->groep_ID ?>" <?php if($aanmelden_startdatum == $groep->groep_ID) echo 'selected'; ?>><?php echo date('d-m-Y', strtotime($groep->groep_startdatum)) ?></option><?php endforeach; ?></select>
					<?php endif; ?>
					</span>
					<?php if(!empty($aanmelden_startdatum_feedback)): ?><span class="feedback"><?php echo $aanmelden_startdatum_feedback ?></span><?php endif; ?>
				</p>
			<?php endif; ?>

			<!-- Veld: Intake / stemtest code -->

			<?php if($aanmelden_voor == 'workshop' && ($workshop->workshop_ID == 6 || $workshop->workshop_stemtest == 'ja')): ?>
				<?php if($workshop->workshop_ID != 6 && $workshop->workshop_ID != 9 && $workshop->workshop_ID != 71) { ?>
					<p><label>Stemtest gedaan?</label>
					<input type="radio" onchange="stemtestCodeToggle('Ja')" name="stemtest_code" id="stemtest_code" value="Ja" /> Ja <input type="radio" onchange="stemtestCodeToggle('Nee')" name="stemtest_code" id="stemtest_code" value="Nee" checked/> Nee</p>
				<?php } ?>
				<p>
					<?php if($workshop->workshop_ID == 6): ?>
						<label for="aanmelden_kort_code">Intake code *</label>
					<?php elseif($workshop->workshop_ID == 9 || $workshop->workshop_niveau == 5): ?>
						<label for="aanmelden_kort_code">Uitnodigingscode *</label>
						<style type="text/css">
							#aanmelden_kort_code { display:block !important }
						</style>
					<?php else: ?>
						<label id="stemtest_code_label" for="aanmelden_kort_code">Stemtest code </label>
					<?php endif; ?>
					<input type="text" name="aanmelden_kort_code" id="aanmelden_kort_code" value="<?php echo $aanmelden_code_oud ?>" /><?php if(!empty($aanmelden_code_feedback)): ?><span class="feedback"><?php echo $aanmelden_code_feedback ?></span><?php endif; ?>
				</p>
			<?php endif; ?>


			<!-- Veld: Kortingscode -->

			<?php if($aanmelden_voor == 'workshop'): ?>
				<p><label for="aanmelden_kort_kortingscode">Couponcode</label><input type="text" name="aanmelden_kort_kortingscode" id="aanmelden_kort_kortingscode" value="" /><?php if(!empty($aanmelden_kortingscode_feedback)): ?><span class="feedback"><?php echo $aanmelden_kortingscode_feedback ?></span><?php endif; ?></p>
			<?php endif; ?>

		</div>


		<!----------------------->
		<!-- AKKOORD EN SUBMIT -->
		<!----------------------->

		<p class="akkoord_container"><span class="akkoord"><input type="checkbox" name="aanmelden_akkoord" id="aanmelden_akkoord" <?php if($aanmelden_akkoord) echo 'checked'; ?> /> Ik ga akkoord met de <a href="<?php echo base_url('inschrijfvoorwaarden') ?>" title="Bekijk de inschrijfvoorwaarden in een nieuw tabblad / venster" target="_blank">inschrijfvoorwaarden</a></span><?php if(!empty($aanmelden_akkoord_feedback)): ?><span class="feedback"><?php echo $aanmelden_akkoord_feedback ?></span><?php endif; ?></p>
		<p><input type="submit" value="Volgende stap" /></p>
	</form>
</section>
<section class="hero" class="clearfix">
	<div class="wrapper">
		<?php if ($aanmelden_voor == 'intake') : ?>
			<h1 class="hero-title">Aanmelden intake <?php echo $workshop->workshop_titel ?></h1>
		<?php elseif ($aanmelden_voor == 'stemtest') : ?>
			<h1 class="hero-title">Aanmelden stemtest <?php echo $workshop->workshop_titel ?></h1>
		<?php elseif ($aanmelden_voor == 'kennismakingsworkshop') : ?>
			<h1 class="hero-title">Aanmelden kennismakingsworkshop <?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></h1>
		<?php else : ?>
			<h1 class="hero-title">Aanmelden <?php echo $workshop->workshop_titel ?></h1>
		<?php endif; ?>
	</div>
</section>

<section id="aanmelden">
	<div class="wrapper">

		<!------------->
		<!-- STAPPEN -->
		<!------------->

		<div id="stappen">
			<ol>
				<li class="active">Aanmelden</li>
				<?php if ($aanmelden_voor == 'workshop' && sizeof($producten) > 0) : ?><li>Producten</li><?php endif; ?>
				<li>Bevestigen</li>
				<li>Betalen</li>
				<li>Aangemeld</li>
			</ol>
		</div>


		<!--------------------->
		<!-- TITEL EN UITLEG -->
		<!--------------------->

		<div id="uitleg">
			<?php if ($aanmelden_voor == 'intake') : ?>
				<h3>Uitleg</h3>
				<?php echo $workshop->workshop_stemtest_tekst ?>
			<?php elseif ($aanmelden_voor == 'stemtest') : ?>
				<h3>Uitleg</h3>
				<?php echo $workshop->workshop_stemtest_tekst ?>
			<?php elseif ($aanmelden_voor == 'kennismakingsworkshop') : ?>
				<h3>Uitleg</h3>
				<?php echo $kennismakingsworkshop->kennismakingsworkshop_aanmelden_tekst ?>
			<?php else : ?>
				<h3>Uitleg</h3>
				<?php echo $workshop->workshop_aanmelden_tekst ?>
			<?php endif; ?>
		</div>


		<!----------------->
		<!-- FORMULIEREN -->
		<!----------------->

		<!--
	MOGELIJKHEDEN:
	1. Aanmelden intake introductieworkshop -> Volledig aanmelden (lang)
	2. Aanmelden workshop introductieworkshop -> Aanmelden met intake code (kort code) / volledig aanmelden (lang)
	3. Aanmelden stemtest workshop -> Aanmelden d.m.v. logingegevens (kort login) / volledig aanmelden (lang)
	4. Aanmelden workshop zonder stemtest -> Aanmelden d.m.v. logingegevens (kort login) / volledig aanmelden (lang)
	5. Aanmelden workshop met stemtest -> Aanmelden met stemtest code (kort code)
	6. Aanmelden kennismakingsworkshop -> volledig aanmelden (lang)
	-->

		<?php if ($aanmelden_voor == 'kennismakingsworkshop') : ?>
			<form method="post" action="<?php echo base_url('aanmelden/' . $aanmelden_voor . '/' . date('d-m-Y', strtotime($kennismakingsworkshop->kennismakingsworkshop_datum)) . '#formulier') ?>" id="formulier">
			<?php else : ?>
				<form method="post" action="<?php echo base_url('aanmelden/' . $aanmelden_voor . '/' . $workshop->workshop_url . '#formulier') ?>"  onsubmit="leeftijdcheck()"; id="formulier">
				<?php endif; ?>

				<!------------------->
				<!-- RADIO BUTTONS -->
				<!------------------->

				<!--
		MOGELIJKHEDEN:
		1. Aanmelden introductieworkshop d.m.v. intake code / volledig aanmelden
		2. Aanmelden voor stemtest d.m.v. logingegevens / volledig aanmelden
		3. Aanmelden voor workshop zonder stemtest d.m.v. logingegevens / volledig aanmelden
		4. Aanmelden voor kennismakingsworkshop d.m.v volledig aanmelden
		-->
		<?php if (($workshop->workshop_type == 'groep' || $workshop->workshop_type == 'online') && !empty($groep_lessen)) : ?>
				<div class="workshop-data">
					<p><strong>Overzicht lesdata</strong></p>
					<?php if (sizeof($groep_lessen) > 0) : ?>
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<?php
							$les = 1;
							$groep = 0;
							$groep_ID = 0;
							$aantal_lessen = 0;
							$temp_groep_ID = 0;

							if (!empty($groep_lessen)) {
								foreach ($groep_lessen as $item_les) {
									if ($temp_groep_ID == 0) {
										$temp_groep_ID = $item_les->groep_ID;
									}

									if ($temp_groep_ID == $item_les->groep_ID) {
										$aantal_lessen++;
										$temp_groep_ID = $item_les->groep_ID;
									}
								}
							}

							foreach ($groep_lessen as $groep_les) :

								$maanden = array('', 'januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');
								$groep_les_datum = explode(' ', $groep_les->groep_les_datum);
								$groep_les_datum = explode('-', $groep_les_datum[0]);
								$datum = intval($groep_les_datum[2]) . ' ' . $maanden[intval($groep_les_datum[1])];
							?>
								<?php if ($les == 1 && !empty($groep_les)) { ?>
									<tr>
										<td colspan="2"><?php if ($groep_les->groep_titel != "") echo $groep_les->groep_titel;
														else echo "Groep" ?></td>
									</tr>
								<?php } ?>
								<tr>
									<td class="les"><?php if ($aantal_lessen > 1) echo "Les " . $les . " |" ?> <?php if($datum != 0) { echo $datum; } else { echo 'Gelijk beschikbaar'; } ?></td>
									<?php if (empty($groep_les->les_type)) { ?>
										<td class="locatie <?php echo strtolower($groep_les->les_locatie) ?>"><?php echo ucfirst($groep_les->les_locatie) ?></td>
									<?php } else { ?>
										<td class="locatie <?php echo strtolower($groep_les->les_type) ?>"><?php echo ucfirst($groep_les->les_type) ?></td>
									<?php } ?>
								</tr>
							<?php
								$les++;
								$groep_ID = $groep_les->groep_ID;
							endforeach;
							?>
						</table>
					<?php else : ?>
						<p><em>Geen lesdata bekend</em></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

				<?php if (($aanmelden_voor == 'workshop') || $aanmelden_voor == 'stemtest' || ($aanmelden_voor == 'workshop' && $workshop->workshop_stemtest == 'ja') || !empty($code)) : ?>

					<div id="formulier_vraag">
						<?php if ($aanmelden_voor == 'workshop' && $workshop->workshop_ID == 6) : ?>
							<h2>Heb je een intake gedaan?</h2>
						<?php else : ?>
							<h2>Heb je eerder al een workshop, stemtest of kennismakingsworkshop bij ons gevolgd?</h2>
						<?php endif; ?>
						<p><input type="radio" name="aanmelden_formulier" id="aanmelden_formulier_kort" value="kort" <?php if ($aanmelden_formulier == 'kort') echo 'checked' ?> /> Ja <input type="radio" name="aanmelden_formulier" id="aanmelden_formulier_lang" value="lang" <?php if ($aanmelden_formulier == 'lang') echo 'checked' ?> /> Nee</p>
					</div>

				<?php elseif ($aanmelden_voor == 'workshop' && $workshop->workshop_stemtest == 'ja') : ?>
					<input type="hidden" name="aanmelden_formulier" value="kort" />
				<?php else : ?>
					<input type="hidden" name="aanmelden_formulier" value="lang" />
				<?php endif; ?>



				<!----------------------------------------->
				<!-- LANG FORMULIER (VOLLEDIG AANMELDEN) -->
				<!----------------------------------------->

				<div id="formulier_lang" <?php if ($aanmelden_formulier == 'kort') echo 'class="onzichtbaar"'; ?>>

					<h3>Meld je aan als nieuwe deelnemer</h3>

					<!-- Veld: Startdata -->

					<?php if ($aanmelden_voor == 'workshop' && in_array($workshop->workshop_type, array('groep', 'online'))) : ?>
						<p>
							<label for="aanmelden_startdatum">Startdatum</label>
							<span class="startdatum">
								<?php if (empty($groepen)):  ?>
									Geen startdata bekend, aanmelden niet mogelijk
									<?php elseif (!is_object($groepen)) : ?>
										<input type="hidden" name="aanmelden_startdatum" id="aanmelden_startdatum" value="<?php echo $groepen[0]->groep_ID ?>" /><?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?>
									<?php elseif (is_object($groepen)) : ?>
										<input type="hidden" name="aanmelden_startdatum" id="aanmelden_startdatum" value="<?php echo $groepen->groep_ID ?>" /><?php echo date('d-m-Y', strtotime($groepen->groep_startdatum)) ?>
								<?php else : ?>
									<select name="aanmelden_startdatum" id="aanmelden_startdatum"><?php foreach ($groepen as $groep) : ?><option value="<?php echo $groep->groep_ID ?>" <?php if ($aanmelden_startdatum == $groep->groep_ID) echo 'selected'; ?>><?php echo date('d-m-Y', strtotime($groep->groep_startdatum)) ?></option><?php endforeach; ?></select>
								<?php endif; ?>
							</span>
							<?php if (!empty($aanmelden_startdatum_feedback)) : ?><span class="feedback"><?php echo $aanmelden_startdatum_feedback ?></span><?php endif; ?>
						</p>
					<?php endif; ?>

					<!-- Algemene velden -->

					<p>
						<label for="aanmelden_bedrijfsnaam">Bedrijfsnaam</label>
						<input type="text" name="aanmelden_bedrijfsnaam" id="aanmelden_bedrijfsnaam" value="<?php echo $aanmelden_bedrijfsnaam ?>" />
						<span class="feedback"><?php echo $aanmelden_bedrijfsnaam_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_voornaam">Voornaam *</label>
						<input type="text" name="aanmelden_voornaam" id="aanmelden_voornaam" value="<?php echo $aanmelden_voornaam ?>" />
						<span class="feedback"><?php echo $aanmelden_voornaam_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_tussenvoegsel">Tussenvoegsel</label>
						<input type="text" name="aanmelden_tussenvoegsel" id="aanmelden_tussenvoegsel" value="<?php echo $aanmelden_tussenvoegsel ?>" />
						<span class="feedback"><?php echo $aanmelden_tussenvoegsel_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_achternaam">Achternaam *</label>
						<input type="text" name="aanmelden_achternaam" id="aanmelden_achternaam" value="<?php echo $aanmelden_achternaam ?>" />
						<span class="feedback"><?php echo $aanmelden_achternaam_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_geslacht">Geslacht *</label>
						<span id="geslacht_container">
							<input type="radio" name="aanmelden_geslacht" id="aanmelden_geslacht" value="man" <?php if ($aanmelden_geslacht == 'man') echo 'checked'; ?> /> Man
							<input type="radio" name="aanmelden_geslacht" value="vrouw" <?php if ($aanmelden_geslacht == 'vrouw') echo 'checked'; ?> /> Vrouw
						</span>
						<span class="feedback"><?php echo $aanmelden_geslacht_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_geboortedatum_dag">Geboortedatum *</label>
						<input type="text" name="aanmelden_geboortedatum_dag" id="aanmelden_geboortedatum_dag" placeholder="DD" value="<?php echo $aanmelden_geboortedatum_dag ?>" maxlength="2" />
						<input type="text" name="aanmelden_geboortedatum_maand" id="aanmelden_geboortedatum_maand" placeholder="MM" value="<?php echo $aanmelden_geboortedatum_maand ?>" maxlength="2" />
						<input type="text" name="aanmelden_geboortedatum_jaar" id="aanmelden_geboortedatum_jaar" placeholder="JJJJ" value="<?php echo $aanmelden_geboortedatum_jaar ?>" maxlength="4" />
						<span class="feedback"><?php echo $aanmelden_geboortedatum_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_adres">Adres *</label>
						<input type="text" name="aanmelden_adres" id="aanmelden_adres" value="<?php echo $aanmelden_adres ?>" />
						<span class="feedback"><?php echo $aanmelden_adres_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_postcode">Postcode *</label>
						<input type="text" name="aanmelden_postcode" id="aanmelden_postcode" value="<?php echo $aanmelden_postcode ?>" maxlength="7" />
						<span class="feedback"><?php echo $aanmelden_postcode_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_plaats">Plaats *</label>
						<input type="text" name="aanmelden_plaats" id="aanmelden_plaats" value="<?php echo $aanmelden_plaats ?>" />
						<span class="feedback"><?php echo $aanmelden_plaats_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_telefoon">Telefoonnummer *</label>
						<input type="text" name="aanmelden_telefoon" id="aanmelden_telefoon" value="<?php echo $aanmelden_telefoon ?>" />
						<span class="feedback"><?php echo $aanmelden_telefoon_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_mobiel">Mobiel nummer</label>
						<input type="text" name="aanmelden_mobiel" id="aanmelden_mobiel" value="<?php echo $aanmelden_mobiel ?>" />
						<span class="feedback"><?php echo $aanmelden_mobiel_feedback ?></span>
					</p>
					<p>
						<label for="aanmelden_emailadres">E-mailadres *</label>
						<input type="text" name="aanmelden_emailadres" id="aanmelden_emailadres" value="<?php echo $aanmelden_emailadres ?>" />
						<span class="feedback"><?php echo $aanmelden_emailadres_feedback ?></span>
					</p>


					<!-- Veld: Kortingscode -->

					<?php if ($aanmelden_voor == 'workshop') : ?>
						<p><label for="aanmelden_kortingscode">Couponcode</label><input type="text" name="aanmelden_kortingscode" id="aanmelden_kortingscode" value="<?php echo $aanmelden_kortingscode ?>" /><?php if (!empty($aanmelden_kortingscode_feedback)) : ?><span class="feedback"><?php echo $aanmelden_kortingscode_feedback ?></span><?php endif; ?></p>
					<?php endif; ?>

				</div>



				<!--------------------------------------->
				<!-- KORTE FORMULIEREN (CODE/INLOGGEN) -->
				<!--------------------------------------->

				<div id="formulier_kort" <?php if ($aanmelden_formulier == 'kort') echo 'class="zichtbaar"'; ?>>

					<?php if ($aanmelden_voor == 'workshop' && $workshop->workshop_ID == 6) : ?>
						<h3>Aanmelden met intake code</h3>
					<?php elseif ($aanmelden_voor == 'workshop' && ($workshop->workshop_ID == 9 || $workshop->workshop_niveau == 5)) : ?>
						<h3>Aanmelden met uitnodigingscode</h3>
					<?php elseif ($aanmelden_voor == 'workshop' && $workshop->workshop_stemtest == 'ja') : ?>
						<h3>Aanmelden met uitnodigingscode</h3>
					<?php else : ?>
						<h3>Inloggen</h3>
					<?php endif; ?>


					<!-- Veld: Startdata -->

					<?php if ($aanmelden_voor == 'workshop' && in_array($workshop->workshop_type, array('groep', 'online'))) : ?>
						<p>
							<label for="aanmelden_kort_startdatum">Startdatum</label>
							<span class="startdatum">
							<?php if (empty($groepen)):  ?>
									Geen startdata bekend, aanmelden niet mogelijk
									<?php elseif (!is_object($groepen)) : ?>
										<input type="hidden" name="aanmelden_kort_startdatum" id="aanmelden_kort_startdatum" value="<?php echo $groepen[0]->groep_ID ?>" /><?php echo date('d-m-Y', strtotime($groepen[0]->groep_startdatum)) ?>
									<?php elseif (is_object($groepen)) : ?>
										<input type="hidden" name="aanmelden_kort_startdatum" id="aanmelden_kort_startdatum" value="<?php echo $groepen->groep_ID ?>" /><?php echo date('d-m-Y', strtotime($groepen->groep_startdatum)) ?>
								<?php else : ?>
									<select name="aanmelden_kort_startdatum" id="aanmelden_kort_startdatum"><?php foreach ($groepen as $groep) : ?><option value="<?php echo $groep->groep_ID ?>" <?php if ($aanmelden_startdatum == $groep->groep_ID) echo 'selected'; ?>><?php echo date('d-m-Y', strtotime($groep->groep_startdatum)) ?></option><?php endforeach; ?></select>
								<?php endif; ?>
							</span>
							<?php if (!empty($aanmelden_startdatum_feedback)) : ?><span class="feedback"><?php echo $aanmelden_startdatum_feedback ?></span><?php endif; ?>
						</p>
					<?php endif; ?>


					<!-- Veld: E-mailadres -->

					<p><label for="aanmelden_kort_emailadres">E-mailadres *</label><input type="text" name="aanmelden_kort_emailadres" id="aanmelden_kort_emailadres" value="<?php echo $aanmelden_emailadres ?>" /><?php if (!empty($aanmelden_emailadres_feedback)) : ?><span class="feedback"><?php echo $aanmelden_emailadres_feedback ?></span><?php endif; ?></p>


					<!-- Veld: Intake / stemtest code -->

					<?php if ($aanmelden_voor == 'workshop' && ($workshop->workshop_ID == 6 || $workshop->workshop_stemtest == 'ja')) : ?>
						<?php if ($workshop->workshop_ID != 6 && $workshop->workshop_ID != 9 && $workshop->workshop_ID != 71) { ?>
							<p><label>Stemtest gedaan?</label>
								<input type="radio" onchange="stemtestCodeToggle('Ja')" name="stemtest_code" id="stemtest_code" value="Ja" /> Ja <input type="radio" onchange="stemtestCodeToggle('Nee')" name="stemtest_code" id="stemtest_code" value="Nee" checked /> Nee</p>
						<?php } ?>
						<p>
							<?php if ($workshop->workshop_ID == 6) : ?>
								<label for="aanmelden_kort_code">Intake code *</label>
							<?php elseif ($workshop->workshop_ID == 9 || $workshop->workshop_niveau == 5) : ?>
								<label for="aanmelden_kort_code">Uitnodigingscode *</label>
								<style type="text/css">
									#aanmelden_kort_code {
										display: block !important
									}
								</style>
							<?php else : ?>
								<label id="stemtest_code_label" for="aanmelden_kort_code">Stemtest code </label>
							<?php endif; ?>
							<input type="text" name="aanmelden_kort_code" id="aanmelden_kort_code" value="<?php echo $aanmelden_code_oud ?>" /><?php if (!empty($aanmelden_code_feedback)) : ?><span class="feedback"><?php echo $aanmelden_code_feedback ?></span><?php endif; ?>
						</p>
					<?php endif; ?>


					<!-- Veld: Inloggen d.m.v. wachtwoord -->

					<?php if ($aanmelden_voor == 'stemtest' || ($aanmelden_voor == 'workshop' && $workshop->workshop_stemtest == 'nee')) : ?>
						<p><label for="aanmelden_kort_wachtwoord">Wachtwoord *</label><input type="password" name="aanmelden_kort_wachtwoord" id="aanmelden_kort_wachtwoord" value="<?php echo $aanmelden_wachtwoord ?>" /><?php if (!empty($aanmelden_wachtwoord_feedback)) : ?><span class="feedback"><?php echo $aanmelden_wachtwoord_feedback ?></span><?php endif; ?></p>
					<?php endif; ?>


					<!-- Veld: Kortingscode -->

					<?php if ($aanmelden_voor == 'workshop') : ?>
						<p><label for="aanmelden_kort_kortingscode">Couponcode</label><input type="text" name="aanmelden_kort_kortingscode" id="aanmelden_kort_kortingscode" value="<?php echo $aanmelden_kortingscode ?>" /><?php if (!empty($aanmelden_kortingscode_feedback)) : ?><span class="feedback"><?php echo $aanmelden_kortingscode_feedback ?></span><?php endif; ?></p>
					<?php endif; ?>

				</div>


				<!----------------------->
				<!-- AKKOORD EN SUBMIT -->
				<!----------------------->

				<p class="akkoord_container"><span class="akkoord"><input type="checkbox" name="aanmelden_akkoord" id="aanmelden_akkoord" <?php if ($aanmelden_akkoord) echo 'checked'; ?> /> Ik ga akkoord met de <a href="<?php echo base_url('inschrijfvoorwaarden') ?>" title="Bekijk de inschrijfvoorwaarden in een nieuw tabblad / venster" target="_blank">inschrijfvoorwaarden</a></span><?php if (!empty($aanmelden_akkoord_feedback)) : ?><span class="feedback"><?php echo $aanmelden_akkoord_feedback ?></span><?php endif; ?></p>
				<p><input type="submit" value="Volgende stap" /></p>
				</form>
	</div>
</section>
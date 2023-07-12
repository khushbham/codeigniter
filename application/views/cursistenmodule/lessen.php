<h1><?php if (!empty($workshop->workshop_titel)) {
		echo $workshop->workshop_titel;
	} else {
		echo "";
	} ?></h1>
<?php if ($this->session->userdata('workshop_soort') == 'normaal') { ?>
	<?php if (isset($lessen) && sizeof($lessen) > 0) : ?>
		<div id="lessen">

				<?php

						for ($i = 1; $i <= sizeof($lessen); $i++) :

							$les = $lessen[$i - 1];

							if ($i == $les_active) $class = 'active';
							elseif ($i < $les_active) $class = 'geweest';
							else $class = 'binnenkort';

							if ($groep) {
								$les_ID = $les->groep_les_ID;
								$les_datum = $les->groep_les_datum;
							} else {
								$les_ID = $les->individu_les_ID;
								$les_datum = $les->individu_les_datum;
							}

							// Les feedback ophalen

							$resultaat = $this->huiswerk_model->getResultaat($this->session->userdata('gebruiker_ID'), $les->les_ID);

							if ($resultaat == null) {
								if ($les->les_huiswerk_aantal == 0) {
									$status = 'Bij deze les zit geen opdracht';
									$status_class = 'geen-huiswerk';
								} else {
									if ($les_datum > date('Y-m-d H:i:s')) {
										$status = 'Binnenkort';
										$status_class = 'binnenkort';
									} else {
										$status = 'Insturen';
										$status_class = 'insturen';
									}
								}
							} else {
								if ($resultaat->resultaat_opnieuw_voldoende == 'nee') {
									$status = 'Onvoldoende';
									$status_class = 'opnieuw-onvoldoende';
								} elseif ($resultaat->resultaat_opnieuw_voldoende == 'ja') {
									$status = 'Voldoende';
									$status_class = 'opnieuw-voldoende';
								} else {
									if ($resultaat->resultaat_opnieuw > 0) {
										if ($resultaat->resultaat_opnieuw_ingestuurd_datum != '0000-00-00 00:00:00') {
											$status = 'Opnieuw ingestuurd';
											$status_class = 'opnieuw-ingestuurd';
										} else {
											$status = 'Opnieuw insturen';
											$status_class = 'opnieuw-insturen';
										}
									} else {
										if ($resultaat->resultaat_voldoende == 'nee') {
											$status = 'Onvoldoende';
											$status_class = 'onvoldoende';
										} elseif ($resultaat->resultaat_voldoende == 'ja') {
											$status = 'Voldoende';
											$status_class = 'voldoende';
										} else {
											if ($resultaat->resultaat_ingestuurd_datum != '0000-00-00 00:00:00') {
												$status = 'Ingestuurd';
												$status_class = 'ingestuurd';
											} else {
												$status = 'Insturen';
												$status_class = 'insturen';
											}
										}
									}
								}
							}

							?>
					<div class="les <?php echo $class ?>">
						<div class="clearfix">
							<div class="les--links">
								<h3><span class="locatie <?php echo $les->les_locatie ?>" title="<?php echo ucfirst($les->les_locatie) ?>"><?php echo ucfirst($les->les_locatie) ?></span>
									<?php if ($class == 'binnenkort') echo $les->les_titel;
												else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" class="text-decoration-none" title="Bekijk les ' . $les->les_titel . '">' . $les->les_titel . '</a>'; ?></h3>
								<?php echo $les->les_beschrijving ?>
							</div>
							<div class="les--rechts">
								<?php if ($class === 'active') { ?>
									<?php echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" class="button-orange" title="Bekijk les ' . $les->les_titel . '">Ga verder met les</a>' ?>
								<?php } elseif ($class === 'geweest') { ?>
									<?php echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" class="button-grey" title="Bekijk les ' . $les->les_titel . '">Bekijk les</a>' ?>
								<?php } elseif ($class === 'binnenkort') { ?>
									<em>Les binnenkort beschikbaar</em>
								<?php } ?>
							</div>
						</div>
						<?php if ($groep && $les->les_locatie != 'online') { ?>
							<div class="studioles clearfix">
								<div class="datum-tijd">
									<div>
										<?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
															echo "<strong>Datum: </strong>" . date('d/m/y', strtotime($les_datum));
														} else {
															echo "";
														} ?></div>
									<div><?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
																echo "<strong>Tijd: </strong>" . date('H:i', strtotime($les_datum));
																if ($groep && $les->groep_les_eindtijd != '00:00:00') echo ' - ' . date('H:i', strtotime($les->groep_les_eindtijd));
																echo " uur";
															} else {
																echo "";
															} ?></div>
								</div>
								<div class="adres"><?php if ($les->les_locatie_ID == 4) {
																		echo "<strong>Adres: </strong>" . $les->groep_les_adres . ", " . $les->groep_les_postcode . " " . $les->groep_les_plaats;
																	} else {
																		echo "<strong>Adres: </strong>" . $les->locatie_adres;
																	} ?>
									<div class="aanwezigheid" title="">
										<strong>Aanwezig: </strong>
										<select name="select-aanwezigheid" onchange="insertAanwezigheid(<?php echo $les->groep_les_ID ?>, <?php echo $this->session->userdata('gebruiker_ID') ?>, this.value)">
											<option value="ja">Ja</option>
											<option value="nee" <?php if (!empty($les->les_aanwezigheid)) {
																					echo "selected";
																				} ?>>Nee</option>
										</select>
									</div>
								</div>
								<div class="huiswerk-status">
									<div class="status"><strong>Opdrachten:</strong> <?php echo $status ?> <?php if ($les->les_huiswerk_aantal != 0) { ?><span class="<?php echo $status_class ?>" title="<?php echo $status ?>"></span><?php } ?></div>

									<div>
									<?php
										$docentID = (isset($les->groepsles_docent_ID)) ? $les->groepsles_docent_ID : $les->les_docent_ID;
										if (!empty($docentID)) {
										$docent = $this->docenten_model->getDocentByID($docentID);
									?>
									<strong>Docent:</strong> <?php echo $docent->docent_naam ?></strong>

									<?php } ?></div>
									<div>
									<?php if (!empty($les->technicus)) {?>
										<strong>Technicus:</strong> <?php echo $les->technicus ?>

									<?php } ?></div>


								</div>
							</div>
						<?php } else if($groep && $les->les_type_ID == 21)  { ?>
							<div class="studioles clearfix">
								<div class="datum-tijd">
									<div>
										<?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
															echo "<strong>Datum: </strong>" . date('d/m/y', strtotime($les_datum));
														} else {
															echo "";
														} ?></div>
									<div><?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
																echo "<strong>Tijd: </strong>" . date('H:i', strtotime($les_datum));
																if ($groep && $les->groep_les_eindtijd != '00:00:00') echo ' - ' . date('H:i', strtotime($les->groep_les_eindtijd));
																echo " uur";
															} else {
																echo "";
															} ?></div>
								</div>
								<div class="adres"><?php if(!empty($les->locatie_adres)) { if ($les->les_locatie_ID == 4) {
																		echo "<strong>Adres: </strong>" . $les->groep_les_adres . ", " . $les->groep_les_postcode . " " . $les->groep_les_plaats;
																	} else {
																		echo "<strong>Adres: </strong>" . $les->locatie_adres;
																	}
																 } ?>
									<div class="aanwezigheid" title="">
										<strong>Aanwezig: </strong>
										<select name="select-aanwezigheid" onchange="insertAanwezigheid(<?php echo $les->groep_les_ID ?>, <?php echo $this->session->userdata('gebruiker_ID') ?>, this.value)">
											<option value="ja">Ja</option>
											<option value="nee" <?php if (!empty($les->les_aanwezigheid)) {
																					echo "selected";
																				} ?>>Nee</option>
										</select>
									</div>
								</div>
								<div class="huiswerk-status">
									<div class="status"><strong>Opdrachten:</strong> <?php echo $status ?> <?php if ($les->les_huiswerk_aantal != 0) { ?><span class="<?php echo $status_class ?>" title="<?php echo $status ?>"></span><?php } ?></div>
									<div>
									<?php
										$docentID = (isset($les->groepsles_docent_ID)) ? $les->groepsles_docent_ID : $les->les_docent_ID;
										if (!empty($docentID)) {
										$docent = $this->docenten_model->getDocentByID($docentID);
									?>
									<strong>Docent:</strong> <?php echo $docent->docent_naam ?></strong>

									<?php } ?></div>
									<div>
									<?php if (!empty($les->technicus)) {?>
										<strong>Technicus:</strong> <?php echo $les->technicus ?>

									<?php } ?></div>
								</div>
							</div>
						<?php } ?>
					</div>
					<hr class="gradient">
				<?php
						endfor;
						?>


		<?php else : ?>
			<p>Er zijn nog geen lessen beschikbaar voor deze workshop.</p>
		<?php endif; ?>
		</div>
	<?php } else { ?>
		<!-- <div class="clearfix">
				<h2>Online Lessen</h2>
				<?php if (isset($lessen) && sizeof($lessen) > 0) : ?>
					<table cellpadding="0" cellspacing="0" border="0" class="lessen">
						<tr>
							<th class="locatie"></td>
							<th class="les">Les</td>
							<th class="datum">Datum</td>
							<th class="tijd">Tijd</td>
							<th class="status"></td>
						</tr>
						<?php

								for ($i = 1; $i <= sizeof($lessen); $i++) :

									$les = $lessen[$i - 1];

									if ($i == $les_active || ($les->groep_les_datum == "0000-00-00 00:00:00" && $les->les_locatie == "online")) $class = 'active';
									elseif ($i < $les_active) $class = 'geweest';
									else $class = 'binnenkort';

									if ($groep) {
										$les_ID = $les->groep_les_ID;
										$les_datum = $les->groep_les_datum;
									} else {
										$les_ID = $les->individu_les_ID;
										$les_datum = $les->individu_les_datum;
									}

									// Les feedback ophalen

									$resultaat = $this->huiswerk_model->getResultaat($this->session->userdata('gebruiker_ID'), $les->les_ID);

									if ($resultaat == null) {
										if ($les->les_huiswerk_aantal == 0) {
											$status = 'Bij deze les zit geen opdracht';
											$status_class = 'geen-huiswerk';
										} else {
											if ($les_datum > date('Y-m-d H:i:s')) {
												$status = 'Binnenkort';
												$status_class = 'binnenkort';
											} else {
												$status = 'Insturen';
												$status_class = 'insturen';
											}
										}
									} else {
										if ($resultaat->resultaat_opnieuw_voldoende == 'nee') {
											$status = 'Onvoldoende';
											$status_class = 'opnieuw-onvoldoende';
										} elseif ($resultaat->resultaat_opnieuw_voldoende == 'ja') {
											$status = 'Voldoende';
											$status_class = 'opnieuw-voldoende';
										} else {
											if ($resultaat->resultaat_opnieuw > 0) {
												if ($resultaat->resultaat_opnieuw_ingestuurd_datum != '0000-00-00 00:00:00') {
													$status = 'Opnieuw ingestuurd';
													$status_class = 'opnieuw-ingestuurd';
												} else {
													$status = 'Opnieuw insturen';
													$status_class = 'opnieuw-insturen';
												}
											} else {
												if ($resultaat->resultaat_voldoende == 'nee') {
													$status = 'Onvoldoende';
													$status_class = 'onvoldoende';
												} elseif ($resultaat->resultaat_voldoende == 'ja') {
													$status = 'Voldoende';
													$status_class = 'voldoende';
												} else {
													if ($resultaat->resultaat_ingestuurd_datum != '0000-00-00 00:00:00') {
														$status = 'Ingestuurd';
														$status_class = 'ingestuurd';
													} else {
														$status = 'Insturen';
														$status_class = 'insturen';
													}
												}
											}
										}
									}

									?>
						<?php if ($les->les_locatie == "online") { ?>
							<tr class="smal <?php echo $class ?>">
								<td class="locatie"><span class="<?php echo $les->les_locatie ?>" title="<?php echo ucfirst($les->les_locatie) ?>"><?php echo ucfirst($les->les_locatie) ?></span></td>
								<td class="les"><?php if ($class == 'binnenkort') echo $les->les_titel;
																else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" title="Bekijk les ' . $les->les_titel . '">' . $les->les_titel . '</a>'; ?></td>
								<td class="datum"><?php if ($les_datum != "0000-00-00 00:00:00") {
																		echo date('d/m/y', strtotime($les_datum));
																	} else {
																		echo "";
																	} ?></td>
								<td class="tijd"><?php if ($les_datum != "0000-00-00 00:00:00") {
																		echo  date('H:i', strtotime($les_datum));
																	} else {
																		echo "";
																	} ?></td>
								<td class="status"><?php if ($class == 'binnenkort') echo "";
																	else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" title="Bekijk les ' . $les->les_titel . '"><span class="' . $status_class . '" title="' . $status . '">' . $status . '</span></a>'; ?></td>
							</tr>
						<?php } ?>
						<?php
								endfor;
								?>
					</table>
					</div>
					<div>

					<?php else : ?>
					<p>Geen lessen gevonden</p>
					<?php endif; ?>
					<br>
			<div class="clearfix">
				<h2>Studio Lessen</h2>
				<?php if (isset($lessen) && sizeof($lessen) > 0) : ?>
					<table cellpadding="0" cellspacing="0" border="0" class="lessen">
						<tr>
							<th class="locatie"></td>
							<th class="les">Les</td>
							<th class="datum">Datum</td>
							<th class="tijd">Tijd</td>
							<th class="status"></td>
							<?php if ($groep) { ?>
								<th class="status">Adres</th>
							    <th class="status">Aanwezig</th>
                            <?php } ?>
						</tr>
						<?php

								for ($i = 1; $i <= sizeof($lessen); $i++) :

									$les = $lessen[$i - 1];

									if ($i == $les_active) $class = 'active';
									elseif ($i < $les_active) $class = 'geweest';
									else $class = 'binnenkort';

									if ($groep) {
										$les_ID = $les->groep_les_ID;
										$les_datum = $les->groep_les_datum;
									} else {
										$les_ID = $les->individu_les_ID;
										$les_datum = $les->individu_les_datum;
									}

									// Les feedback ophalen

									$resultaat = $this->huiswerk_model->getResultaat($this->session->userdata('gebruiker_ID'), $les->les_ID);

									if ($resultaat == null) {
										if ($les->les_huiswerk_aantal == 0) {
											$status = 'Bij deze les zit geen opdracht';
											$status_class = 'geen-huiswerk';
										} else {
											if ($les_datum > date('Y-m-d H:i:s')) {
												$status = 'Binnenkort';
												$status_class = 'binnenkort';
											} else {
												$status = 'Insturen';
												$status_class = 'insturen';
											}
										}
									} else {
										if ($resultaat->resultaat_opnieuw_voldoende == 'nee') {
											$status = 'Onvoldoende';
											$status_class = 'opnieuw-onvoldoende';
										} elseif ($resultaat->resultaat_opnieuw_voldoende == 'ja') {
											$status = 'Voldoende';
											$status_class = 'opnieuw-voldoende';
										} else {
											if ($resultaat->resultaat_opnieuw > 0) {
												if ($resultaat->resultaat_opnieuw_ingestuurd_datum != '0000-00-00 00:00:00') {
													$status = 'Opnieuw ingestuurd';
													$status_class = 'opnieuw-ingestuurd';
												} else {
													$status = 'Opnieuw insturen';
													$status_class = 'opnieuw-insturen';
												}
											} else {
												if ($resultaat->resultaat_voldoende == 'nee') {
													$status = 'Onvoldoende';
													$status_class = 'onvoldoende';
												} elseif ($resultaat->resultaat_voldoende == 'ja') {
													$status = 'Voldoende';
													$status_class = 'voldoende';
												} else {
													if ($resultaat->resultaat_ingestuurd_datum != '0000-00-00 00:00:00') {
														$status = 'Ingestuurd';
														$status_class = 'ingestuurd';
													} else {
														$status = 'Insturen';
														$status_class = 'insturen';
													}
												}
											}
										}
									}

									?>
							<?php if ($les->les_locatie != "online") { ?>
							<tr class="smal <?php echo $class ?>">
								<td class="locatie"><span class="<?php echo $les->les_locatie ?>" title="<?php echo ucfirst($les->les_locatie) ?>"><?php echo ucfirst($les->les_locatie) ?></span></td>
								<td class="les"><?php if ($class == 'binnenkort') echo $les->les_titel;
																else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" title="Bekijk les ' . $les->les_titel . '">' . $les->les_titel . '</a>'; ?></td>
								<td class="datum"><?php echo date('d/m/y', strtotime($les_datum)) ?></td>
								<td class="tijd"><?php echo date('H:i', strtotime($les_datum)) ?></td>
								<td class="status"><?php if ($class == 'binnenkort') echo "";
																	else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" title="Bekijk les ' . $les->les_titel . '"><span class="' . $status_class . '" title="' . $status . '">' . $status . '</span></a>'; ?></td>
								<?php if ($groep && $les->les_locatie != 'online') { ?>
									<td class="Adres"><?php if ($les->les_locatie_ID == 4) {
																				echo $les->groep_les_adres . ", " . $les->groep_les_postcode . " " . $les->groep_les_plaats;
																			} else {
																				echo $les->locatie_adres;
																			} ?></td>
                                    <td class="aanwezigheid" title="">
                                        <select name="select-aanwezigheid" onchange="insertAanwezigheid(<?php echo $les->groep_les_ID ?>, <?php echo $this->session->userdata('gebruiker_ID') ?>, this.value)">
                                            <option value="ja" >Ja</option>
                                            <option value="nee"<?php if (!empty($les->les_aanwezigheid)) {
																						echo "selected";
																					} ?>>Nee</option>
                                        </select>
                                    </td>
                                <?php } else { ?>
                                    <td></td>
                                <?php } ?>
							</tr>
								<?php } ?>
						<?php
								endfor;
								?>
					</table>
					</div>
					<?php else : ?>
					<p>Geen lessen gevonden</p>
					<?php endif; ?>





 TEST OMGEVING STUFF -->
		<div class="clearfix">
			<h2>Lessen</h2>
			<?php if (isset($lessen) && sizeof($lessen) > 0) : ?>

				<?php

						for ($i = 1; $i <= sizeof($lessen); $i++) :

							$les = $lessen[$i - 1];

							if ($i == $les_active) $class = 'active';
							elseif ($i < $les_active) $class = 'geweest';
							else $class = 'binnenkort';

							if ($groep) {
								$les_ID = $les->groep_les_ID;
								if (!empty($les->groep_les_datum)) {
									$les_datum = $les->groep_les_datum;
								}
							} else {
								$les_ID = $les->individu_les_ID;
								$les_datum = $les->individu_les_datum;
							}

							// Les feedback ophalen

							$resultaat = $this->huiswerk_model->getResultaat($this->session->userdata('gebruiker_ID'), $les->les_ID);

							if ($resultaat == null) {
								if ($les->les_huiswerk_aantal == 0) {
									$status = 'Bij deze les zit geen opdracht';
									$status_class = 'geen-huiswerk';
								} else {
									if ($les->groep_les_datum > date('Y-m-d H:i:s')) {
										$status = 'Binnenkort';
										$status_class = 'binnenkort';
									} else {
										$status = 'Insturen';
										$status_class = 'insturen';
									}
								}
							} else {
								if ($resultaat->resultaat_opnieuw_voldoende == 'nee') {
									$status = 'Onvoldoende';
									$status_class = 'opnieuw-onvoldoende';
								} elseif ($resultaat->resultaat_opnieuw_voldoende == 'ja') {
									$status = 'Voldoende';
									$status_class = 'opnieuw-voldoende';
								} else {
									if ($resultaat->resultaat_opnieuw > 0) {
										if ($resultaat->resultaat_opnieuw_ingestuurd_datum != '0000-00-00 00:00:00') {
											$status = 'Opnieuw ingestuurd';
											$status_class = 'opnieuw-ingestuurd';
										} else {
											$status = 'Opnieuw insturen';
											$status_class = 'opnieuw-insturen';
										}
									} else {
										if ($resultaat->resultaat_voldoende == 'nee') {
											$status = 'Onvoldoende';
											$status_class = 'onvoldoende';
										} elseif ($resultaat->resultaat_voldoende == 'ja') {
											$status = 'Voldoende';
											$status_class = 'voldoende';
										} else {
											if ($resultaat->resultaat_ingestuurd_datum != '0000-00-00 00:00:00') {
												$status = 'Ingestuurd';
												$status_class = 'ingestuurd';
											} else {
												$status = 'Insturen';
												$status_class = 'insturen';
											}
										}
									}
								}
							}

							?>
					<div class="les <?php echo $class ?>">
						<div class="clearfix">
							<div class="les--links">
								<h3><span class="locatie <?php echo $les->les_locatie ?>" title="<?php echo ucfirst($les->les_locatie) ?>"><?php echo ucfirst($les->les_locatie) ?></span>
									<?php if ($class == 'binnenkort') echo $les->les_titel;
												else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" class="text-decoration-none" title="Bekijk les ' . $les->les_titel . '">' . $les->les_titel . '</a>'; ?></h3>
								<?php echo $les->les_beschrijving ?>
							</div>
							<div class="les--rechts">
								<?php if ($class === 'active') { ?>
									<?php echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" class="button-orange" title="Bekijk les ' . $les->les_titel . '">Ga verder met les</a>' ?>
								<?php } elseif ($class === 'geweest') { ?>
									<?php echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" class="button-grey" title="Bekijk les ' . $les->les_titel . '">Bekijk les</a>' ?>
								<?php } elseif ($class === 'binnenkort') { ?>
									<em>Les binnenkort beschikbaar</em>
								<?php } ?>
							</div>
						</div>
						<?php if ($groep && $les->les_locatie != 'online') { ?>
							<div class="studioles clearfix">
								<div class="datum-tijd">
									<div>
										<?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
															echo "<strong>Datum: </strong>" . date('d/m/y', strtotime($les_datum));
														} else {
															echo "";
														} ?></div>
									<div><?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
																echo "<strong>Tijd: </strong>" . date('H:i', strtotime($les_datum));
																if ($groep && $les->groep_les_eindtijd != '00:00:00') echo ' - ' . date('H:i', strtotime($les->groep_les_eindtijd));
																echo " uur";
															} else {
																echo "";
															} ?></div>
								</div>
								<div class="adres"><?php if ($les->les_locatie_ID == 4) {
																		echo "<strong>Adres: </strong>" . $les->groep_les_adres . ", " . $les->groep_les_postcode . " " . $les->groep_les_plaats;
																	} else {
																		echo "<strong>Adres: </strong>" . $les->locatie_adres;
																	} ?>
									<div class="aanwezigheid" title="">
										<strong>Aanwezig: </strong>
										<select name="select-aanwezigheid" onchange="insertAanwezigheid(<?php echo $les->groep_les_ID ?>, <?php echo $this->session->userdata('gebruiker_ID') ?>, this.value)">
											<option value="ja">Ja</option>
											<option value="nee" <?php if (!empty($les->les_aanwezigheid)) {
																					echo "selected";
																				} ?>>Nee</option>
										</select>
									</div>
								</div>
								<div class="huiswerk-status">
									<div class="status"><strong>Opdrachten:</strong> <?php echo $status ?> <?php if ($les->les_huiswerk_aantal != 0) { ?><span class="<?php echo $status_class ?>" title="<?php echo $status ?>"></span><?php } ?></div>
									<div>
									<?php
										$docentID = (isset($les->groepsles_docent_ID)) ? $les->groepsles_docent_ID : $les->les_docent_ID;
										if (!empty($docentID)) {
										$docent = $this->docenten_model->getDocentByID($docentID);
									?>
									<strong>Docent:</strong> <?php echo $docent->docent_naam ?></strong>

									<?php } ?></div>
									<div>
									<?php if (!empty($les->technicus)) {?>
										<strong>Technicus:</strong> <?php echo $les->technicus ?>

									<?php } ?></div>
								</div>
							</div>
						<?php } else if($groep && $les->les_type_ID == 21)  { ?>
							<div class="studioles clearfix">
								<div class="datum-tijd">
									<div>
										<?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
															echo "<strong>Datum: </strong>" . date('d/m/y', strtotime($les_datum));
														} else {
															echo "";
														} ?></div>
									<div><?php if (!empty($les_datum) && $les_datum != "0000-00-00 00:00:00") {
																echo "<strong>Tijd: </strong>" . date('H:i', strtotime($les_datum));
																if ($groep && $les->groep_les_eindtijd != '00:00:00') echo ' - ' . date('H:i', strtotime($les->groep_les_eindtijd));
																echo " uur";
															} else {
																echo "";
															} ?></div>
								</div>
								<div class="adres"><?php if(!empty($les->locatie_adres)) { if ($les->les_locatie_ID == 4) {
																		echo "<strong>Adres: </strong>" . $les->groep_les_adres . ", " . $les->groep_les_postcode . " " . $les->groep_les_plaats;
																	} else {
																		echo "<strong>Adres: </strong>" . $les->locatie_adres;
																	}
																 } ?>
									<div class="aanwezigheid" title="">
										<strong>Aanwezig: </strong>
										<select name="select-aanwezigheid" onchange="insertAanwezigheid(<?php echo $les->groep_les_ID ?>, <?php echo $this->session->userdata('gebruiker_ID') ?>, this.value)">
											<option value="ja">Ja</option>
											<option value="nee" <?php if (!empty($les->les_aanwezigheid)) {
																					echo "selected";
																				} ?>>Nee</option>
										</select>
									</div>
								</div>
								<div class="huiswerk-status">
									<div class="status"><strong>Opdrachten:</strong> <?php echo $status ?> <?php if ($les->les_huiswerk_aantal != 0) { ?><span class="<?php echo $status_class ?>" title="<?php echo $status ?>"></span><?php } ?></div>
									<div>
									<?php
										$docentID = (isset($les->groepsles_docent_ID)) ? $les->groepsles_docent_ID : $les->les_docent_ID;
										if (!empty($docentID)) {
										$docent = $this->docenten_model->getDocentByID($docentID);
									?>
									<strong>Docent:</strong> <?php echo $docent->docent_naam ?></strong>

									<?php } ?></div>
									<div>
									<?php if (!empty($les->technicus)) {?>
										<strong>Technicus:</strong> <?php echo $les->technicus ?>

									<?php } ?></div>
								</div>
							</div>
						<?php } ?>
					</div>
					<hr class="gradient">

				<?php
						endfor;
						?>
		</div>
		<div>

		<?php else : ?>
			<p>Geen lessen gevonden</p>
		<?php endif; ?>
	<?php } ?>
		</div>
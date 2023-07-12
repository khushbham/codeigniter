<h1><?php if (!empty($workshop)) { ?><?php echo $workshop->workshop_titel ?><?php } ?></h1>
<?php if (sizeof($workshops) > 0 || sizeof($kennismakingsworkshops) > 0) : ?>
	<?php if ($this->session->userdata('gebruiker_rechten') != 'kandidaat') { ?>
		<?php $workshopID = $this->session->userdata('workshop_ID'); ?>
		<?php if (!empty($workshopID)) { ?>
			<?php if (sizeof($lessen) > 0) : ?>
				<?php if ($this->session->userdata('workshop_soort') == 'normaal') { ?>
					<div class="clearfix">
						<div id="komende-les">
							<?php if ($les_active > 0) : ?>
								<?php
								if (isset($lessen) && sizeof($lessen) > 0) :
									$les = $lessen[$les_active - 1];
								?>
									<h1><?php echo $les->les_titel ?></h1>
									<div class="meta"><?php if ($groep) echo date('d-m-y', strtotime($les->groep_les_datum));
														else echo date('d-m-y', strtotime($les->individu_les_datum)); ?> | <?php if ($groep) echo date('H.i', strtotime($les->groep_les_datum)) . " uur";
																															else echo date('H.i', strtotime($les->individu_les_datum)) . " uur"; ?></div>
									<div id="omschrijving">
										<h3>Omschrijving</h3>
										<?php echo $les->les_beschrijving ?>
										<p><a href="<?php if ($groep) echo base_url('cursistenmodule/lessen/' . $les->groep_les_ID);
													else echo base_url('cursistenmodule/lessen/' . $les->individu_les_ID) ?>" title="Lees verder">> Lees verder</a></p>
									</div>
									<?php if ($les->les_huiswerk_aantal > 0) : ?>
										<div id="huiswerk">
											<h3>Opdrachten</h3>
											<?php echo $les->les_huiswerk ?>
											<p><a href="<?php if ($groep) echo base_url('cursistenmodule/lessen/' . $les->groep_les_ID);
														else echo base_url('cursistenmodule/lessen/' . $les->individu_les_ID) ?>" title="Lees verder">> Lees verder</a></p>
										</div>
									<?php endif; ?>
								<?php endif; ?>
							<?php else : ?>
								<h1>Welkom in de Cursistenmodule</h1>
								<p>Op dit moment zijn er nog geen lessen beschikbaar.<br />Vanaf <?php if ($groep) echo date('d-m-y', strtotime($lessen[0]->groep_les_datum));
																									else echo date('d-m-y', strtotime($lessen[0]->individu_les_datum)); ?> vind je hier je eerste lesmateriaal.</p>
							<?php endif; ?>
						</div>

						<div id="agenda">
							<h1>Lessen</h1>
							<?php if (isset($lessen) && sizeof($lessen) > 0) : ?>
								<table cellpadding="0" cellspacing="0" border="0" class="lessen">
									<tr>
										<th class="locatie">
											</td>
										<th class="les-titel">Les</td>
										<th class="datum">Datum</td>
										<th class="tijd">Tijd</td>
										<th class="status">
											</td>
											<?php if ($groep) { ?>
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
										<tr class="smal <?php echo $class ?>">
											<td class="locatie"><span class="<?php echo $les->les_locatie ?>" title="<?php echo ucfirst($les->les_locatie) ?>"><?php echo ucfirst($les->les_locatie) ?></span></td>
											<td class="les-titel"><?php if ($class == 'binnenkort') echo $les->les_titel;
																	else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" title="Bekijk les ' . $les->les_titel . '">' . $les->les_titel . '</a>'; ?></td>
											<td class="datum"><?php if ($les->les_locatie != "online" || $les->les_type_ID == 21 || $les->les_type_ID == 22) { ?><?php echo date('d/m/y', strtotime($les_datum)) ?><?php } ?></td>
											<td class="tijd"><?php if ($les->les_locatie != "online" || $les->les_type_ID == 21 || $les->les_type_ID == 22) { ?><?php echo date('H:i', strtotime($les_datum)) ?><?php } ?></td>
											<td class="status"><?php if ($class == 'binnenkort') echo $les->les_titel;
																else echo '<a href="' . base_url('cursistenmodule/lessen/' . $les_ID) . '" title="Bekijk les ' . $les->les_titel . '"><span class="' . $status_class . '" title="' . $status . '">' . $status . '</span></a>'; ?></td>
											<?php if ($groep && $les->les_locatie != 'online') { ?>
												<td class="aanwezigheid" title="">
													<select name="select-aanwezigheid" onchange="insertAanwezigheid(<?php echo $les->groep_les_ID ?>, <?php echo $this->session->userdata('gebruiker_ID') ?>, this.value)">
														<option value="ja">Ja</option>
														<option value="nee" <?php if (!empty($les->les_aanwezigheid)) {
																				echo "selected";
																			} ?>>Nee</option>
													</select>
												</td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
										</tr>
									<?php
									endfor;
									?>
								</table>
							<?php else : ?>
								<p>Geen lessen gevonden</p>
							<?php endif; ?>
						<?php } else { ?><!-- UITGEBREIDE WORKSHOPS -->
							<br>

							<div class="link-wrapper">
								<a style="float:left" class="button-orange" onclick="openNav()">Bekijk beschikbare lessen</a><?php if (!empty($volgende_les)) { ?><?php if ($volgende_les->groep_les_datum <= date("Y-m-d H:i:s")) { ?> <a style="float:right" href="<?php echo base_url('cursistenmodule/lessen/' . $volgende_les->groep_les_ID); ?>" class="button-orange">Volgende les</a><?php } ?><?php } ?>
							</div>
							<div class="video-header">
								<div class="vimeo-video-container" <?php if (empty($les->les_video_url)) {
																		echo 'style="padding-bottom:0%"';
																	} ?>>
									<div id="mySidenav" class="sidenav">
										<?php if (!empty($lessen)) { ?>
											<?php foreach ($lessen as $item) { ?>
												<?php if ($item->groep_les_datum <= date("Y-m-d H:i:s")) { ?>
													<?php if ($les->groep_les_ID == $item->groep_les_ID) { ?>
														<div class="sidenav-item">
															<div class="highlight"></div>
															<a href="<?php echo base_url('cursistenmodule/lessen/' . $item->groep_les_ID); ?>">
																<p class="titel"><?php echo $item->les_titel ?></p>
																<?php echo $item->les_beschrijving ?>
															</a>
														</div>
													<?php } else { ?>
														<a href="<?php echo base_url('cursistenmodule/lessen/' . $item->groep_les_ID); ?>">
															<p class="titel"><?php echo $item->les_titel ?></p>
															<?php echo $item->les_beschrijving ?>
														</a>
													<?php } ?>
													<hr>
												<?php } ?>
											<?php } ?>
										<?php } ?>
									</div>

									<?php if (!empty($les->les_video_url)) { ?>
										<?php if ($les->les_type_ID == 21 || $les->les_type_ID == 22) { ?>
											<?php if ($les->les_video_type == "vimeo") { ?>
												<iframe class="vimeo-iframe" src="<?php echo "https://vimeo.com/event/" . $les->les_video_url[0] . "/embed" ?>" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
											<?php } elseif ($les->les_video_type == "vimeo_standaard") { ?>
												<?php foreach ($les->les_video_url as $url) { ?>
													<iframe class="vimeo-iframe <?php if (sizeof($les->les_video_url) > 1) { ?>mySlides<?php } ?>" src="<?php echo "https://player.vimeo.com/video/" . $url ?>" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
												<?php } ?>
											<?php } else { ?>
												<!-- ZOOM -->
												<iframe class="vimeo-iframe" src="<?php echo "https://zoom.us/wc/" . $les->les_video_url[0] . "/join?prefer=1&un=" . base64_encode($this->session->userdata('gebruiker_naam')) ?>" width="100%" height="100%" frameborder="0" sandbox="allow-forms allow-scripts" allow="microphone; camera; fullscreen"></iframe>
											<?php } ?>
										<?php } else { ?>
											<?php foreach ($les->les_video_url as $url) { ?>
												<iframe class="vimeo-iframe <?php if (sizeof($les->les_video_url) > 1) { ?>mySlides<?php } ?>" src="<?php echo "https://player.vimeo.com/video/" . $url ?>" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
											<?php } ?>
										<?php } ?>
								</div>
								<div class="slide-center">
									<?php if (sizeof($les->les_video_url) > 1) { ?>
										<div class="slide-section">
											<button class="slide-button" onclick="plusDivs(-1)">❮ Vorige</button>
											<button class="slide-button" onclick="plusDivs(1)">Volgende ❯</button>
										</div>
										<?php for ($i = 1; $i <= count($les->les_video_url); $i++) { ?>
											<button class="slide-button demo" onclick="currentDiv(<?php echo $i ?>)"><?php echo $i ?></button>
										<?php } ?>
									<?php } ?>
								<?php } elseif (!empty($placeholder)) { ?>
									<?php if ($placeholder[0]->media_type == 'pdf') : ?>
										<p class="pdf"><a href="<?php echo base_url('media/pdf/' . $placeholder[0]->media_src) ?>" title="Open de PDF in een nieuw tabblad / venster>" target="_blank"><?php echo $placeholder[0]->media_titel ?></a></p>
									<?php elseif ($placeholder[0]->media_type == 'afbeelding') : ?>
										<img src="<?php echo base_url('media/afbeeldingen/origineel/' . $placeholder[0]->media_src) ?>" alt="<?php echo $placeholder[0]->media_titel ?>" class="placeholder" />
									<?php elseif ($placeholder[0]->media_type == 'video') : ?>
										<iframe allowFullScreen allowTransparency="true" class="vzaar-video-player" frameborder="0" width="460" height="270" id="vzvd-<?php echo $placeholder[0]->media_src ?>" mozallowfullscreen name="vzvd-<?php echo $placeholder[0]->media_src ?>" src="//view.vzaar.com/<?php echo $placeholder[0]->media_src ?>/player" title="vzaar video player" type="text/html" webkitAllowFullScreen width="460" height="270"></iframe>
									<?php elseif ($placeholder[0]->media_type == 'playlist') : ?>
										<iframe allowFullScreen allowTransparency="true" class="vzaar video player" frameborder="0" width="460" height="420" id="vzpl-<?php echo $placeholder[0]->media_src ?>" mozallowfullscreen name="vzpl-<?php echo $placeholder[0]->media_src ?>" src="//view.vzaar.com/playlists/<?php echo $placeholder[0]->media_src ?>" title="vzaar video player" type="text/html" webkitAllowFullScreen width="460" height="420"></iframe>
									<?php elseif ($placeholder[0]->media_type == 'mp3') : ?>
										<div id="audio">
											<div class="audio">
												<div class="audio_titel"><?php echo $placeholder[0]->media_titel ?></div>
												<audio src="<?php echo base_url('media/audio/' . $placeholder[0]->media_src) ?>" preload="auto"></audio>
											</div>
										</div>
									<?php endif; ?>
								<?php } ?>
								</div>
							</div>


							<div id="uitgebreide_les" class="clearfix">
								<div class="video-header-omschrijving">
									<h3><?php echo $les->les_titel ?></h3>
									<?php if ($les->les_locatie != "online") { ?>
										<div class="meta">
											<?php
												if ($groep) echo date('d-m-y', strtotime($les->groep_les_datum));
												else echo date('d-m-y', strtotime($les->individu_les_datum));
											?>
											|
											<?php
												if ($groep) echo date('H.i', strtotime($les->groep_les_datum)) . " uur";
												else echo date('H.i', strtotime($les->individu_les_datum)) . " uur";
											?>
										</div>
										<div style="margin-bottom: 1em;">
											<?php if ($les->les_locatie_ID == 4) {
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


										<?php
											if (!empty($les->docent_ID)) {
											$docent = $this->docenten_model->getDocentByID($les->docent_ID);
										?>
											<div>
												<strong>Docent:</strong> <?php echo $docent->docent_naam ?></strong>
											</div>
										<?php } ?>


										<?php if (!empty($les->technicus)) {?>
											<div>
												<strong>Technicus:</strong> <?php echo $les->technicus ?>
											</div>
										<?php } ?>
										</div>

									<?php } ?>
									<?php echo $les->les_beschrijving ?>
									<div class="rating">
										<label class="<?php if ($beoordeling >= 5) {
															echo ' selected';
														} ?>">
											<input type="radio" name="rating" value="5" title="5 stars"> 5
										</label>
										<label class="<?php if ($beoordeling >= 4) {
															echo ' selected';
														} ?>">
											<input type="radio" name="rating" value="4" title="4 stars"> 4
										</label>
										<label class="<?php if ($beoordeling >= 3) {
															echo ' selected';
														} ?>">
											<input type="radio" name="rating" value="3" title="3 stars"> 3
										</label>
										<label class="<?php if ($beoordeling >= 2) {
															echo ' selected';
														} ?>">
											<input type="radio" name="rating" value="2" title="2 stars"> 2
										</label>
										<label class="<?php if ($beoordeling >= 1) {
															echo ' selected';
														} ?>">
											<input type="radio" name="rating" value="1" title="1 star"> 1
										</label>
									</div>
									<input type="hidden" name="gebruiker_ID" id="gebruiker_ID" value="<?php echo $this->session->userdata('gebruiker_ID'); ?>">
									<input type="hidden" name="les_ID" id="les_ID" value="<?php echo $les->les_ID ?>">
								</div>
							</div>

							<?php if ((isset($media) && sizeof($media) > 0) || !empty($les->les_typeform_url)) : ?>
								<h3>DOCUMENTEN</h3>
								<div class="les-documents">
									<?php if (isset($media) && sizeof($media) > 0) : ?>
										<?php foreach ($media as $item) : ?>
											<div class="media">
												<?php if ($item->media_type == 'pdf') : ?>
													<div class="les-document">
														<a href="<?php echo base_url('media/pdf/' . $item->media_src) ?>" title="Open de PDF in een nieuw tabblad / venster" target="_blank">
															<img src="<?php echo base_url('assets/images/icon-pdf.png') ?>" alt="Open de PDF in een nieuw tabblad / venster" />
														</a>
														<p class="document-pdf"><a href="<?php echo base_url('media/pdf/' . $item->media_src) ?>" title="Open de PDF in een nieuw tabblad / venster" target="_blank"><?php echo $item->media_titel ?></a></p>
													</div>
												<?php elseif ($item->media_type == 'mp3') : ?>
													<div id="audio">
														<div class="audio">
															<div class="audio_titel"><?php echo $item->media_titel ?></div>
															<audio src="<?php echo base_url('media/audio/' . $item->media_src) ?>" preload="auto"></audio>
														</div>
													</div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
									<?php if (!empty($les->les_typeform_url)) : ?>
										<div class="les-document">
											<a href="<?php echo $les->les_typeform_url ?>" target="_blank"><img class="les-quiz" src="<?php echo base_url('assets/images/icon-quiz.png') ?>" alt="Open de Quiz in een nieuw tabblad / venster" /></a>
											<p class="document-pdf"><a href="<?php echo $les->les_typeform_url ?>" title="Open de quiz in een nieuw tabblad / venster" target="_blank">Beantwoord de vragen</a></p>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>


						<?php if ($les->les_type_ID == 21 || $les->les_type_ID == 22) { ?>
							<?php if ($les->les_video_type == "vimeo" || $les->les_video_type == "vimeo_standaard") { ?>
								<div id="vimeo-chat">
									<button id="toggle-vimeo-chat" class="button-orange">
										<span class="closed">Open </span>
										<span class="open">Verberg </span>
										live chat bij deze les
									</button>
									<?php if ($les->les_video_type == "vimeo_standaard") { ?>
										<iframe id="vimeo-chat-iframe" src="<?php echo "https://vimeo.com/live-chat/" . $les->les_video_url[0] . "/" ?>" height="500" scrolling="no" width="100%" frameborder="0" allow="autoplay"></iframe>
									<?php } else { ?>
										<iframe id="vimeo-chat-iframe" src="<?php echo "https://vimeo.com/event/" . $les->les_video_url[0] . "/chat" ?>" height="500" scrolling="no" width="100%" frameborder="0" allow="autoplay"></iframe>
									<?php } ?>
								</div>
							<?php } else if ($les->les_video_type == "zoom") { ?>
								Zoom link: <a href="<?php echo "https://zoom.us/j/" . $les->les_video_url[0] ?>" target="_blank"><?php echo "https://zoom.us/j/" . $les->les_video_url[0] ?></a>
							<?php } ?>
						<?php } ?>

						<?php if ($les->les_huiswerk_aantal > 0) : ?>
							<div id="inhoud">
								<div id="uitgebreid_huiswerk" class="clearfix">
									<h3>Opdracht</h3>
									<?php echo $les->les_huiswerk ?>
									<div id="mijnhuiswerk">
										<h3>Mijn opdrachten</h3>

										<!-- Eerste keer insturen -->

										<?php if (empty($resultaat)) : ?>

											<!-- Aantal MP3 bestanden vermelden -->

											<?php if ($les->les_huiswerk_aantal == 1) : ?>
												<p>Voor deze les kan 1 MP3 bestand worden ingestuurd. Heb je het bestand toegevoegd en ben je tevreden over het resultaat? Dan kun je de opdracht insturen naar de docent. Let op, na het insturen kun je geen wijzigingen meer aanbrengen.</p>
											<?php else : ?>
												<p>Voor deze les kunnen <?php echo $les->les_huiswerk_aantal ?> MP3 bestanden worden ingestuurd. Heb je alle <?php echo $les->les_huiswerk_aantal ?> de bestanden toegevoegd en ben je tevreden over het resultaat? Dan kun je de opdracht insturen naar de docent. Let op, na het insturen kun je geen wijzigingen meer aanbrengen.</p>
											<?php endif; ?>

											<!-- Toegevoegd huiswerk tonen -->

											<?php if (sizeof($huiswerk) > 0) : ?>
												<div id="audio">
													<?php foreach ($huiswerk as $item) : ?>
														<div class="audio verwijderen">
															<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
															<?php if ($groep) $les_ID = $les->groep_les_ID;
															else $les_ID = $les->individu_les_ID; ?>
															<div class="audio_verwijderen"><a href="<?php echo base_url('cursistenmodule/huiswerk/verwijderen/' . $item->huiswerk_ID . '/' . $les_ID) ?>" title="Opdracht verwijderen">Opdracht verwijderen</a></div>
															<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="auto"></audio>
														</div>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>

											<!-- Formulier tonen -->
											<?php if ($huiswerk_aan == 'ja') { ?>
												<form method="post" enctype="multipart/form-data" action="<?php if ($groep) echo base_url('cursistenmodule/lessen/' . $les->groep_les_ID . '#mijnhuiswerk');
																											else echo base_url('cursistenmodule/lessen/' . $les->individu_les_ID . '#mijnhuiswerk'); ?>">
													<?php if (sizeof($huiswerk) < $les->les_huiswerk_aantal) : ?>

														<!-- Huiswerk toevoegen -->
														<input type="hidden" name="huiswerk_opnieuw" id="huiswerk_opnieuw" value="nee" />
														<p><label for="huiswerk_titel">Titel bestand</label><input type="text" name="huiswerk_titel" id="huiswerk_titel" placeholder="Type hier de titel van je huiswerkopdracht" value="<?php echo $huiswerk_titel ?>" /></p>
														<p><label for="Audio_option">Audio type</label><input type="radio" name="audio_option" id="audio_mp3" value="audio_src" onClick="audio_type()" checked /> Mp3 uploaden <input type="radio" name="audio_option" id="audio_opnemen" value="audio_opnemen" onClick="audio_type()" />Audio direct opnemen</span></p>
														<div id="audio_record"><?php
																				function getBrowser()
																				{
																					$u_agent = $_SERVER['HTTP_USER_AGENT'];
																					$bname = 'Unknown';
																					$platform = 'Unknown';
																					$version = "";
																					$ub = "";

																					//First get the platform?
																					if (preg_match('/linux/i', $u_agent)) {
																						$platform = 'linux';
																					} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
																						$platform = 'mac';
																					} elseif (preg_match('/windows|win32/i', $u_agent)) {
																						$platform = 'windows';
																					}

																					// Next get the name of the useragent yes seperately and for good reason
																					if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
																						$bname = 'Internet Explorer';
																						$ub = "MSIE";
																					} elseif (preg_match('/Firefox/i', $u_agent)) {
																						$bname = 'Mozilla Firefox';
																						$ub = "Firefox";
																					} elseif (preg_match('/Chrome/i', $u_agent)) {
																						$bname = 'Google Chrome';
																						$ub = "Chrome";
																					} elseif (preg_match('/Safari/i', $u_agent)) {
																						$bname = 'Apple Safari';
																						$ub = "Safari";
																					} elseif (preg_match('/Opera/i', $u_agent)) {
																						$bname = 'Opera';
																						$ub = "Opera";
																					} elseif (preg_match('/Netscape/i', $u_agent)) {
																						$bname = 'Netscape';
																						$ub = "Netscape";
																					}

																					if (!empty($ub)) {
																						$known = array('Version', $ub, 'other');
																						$pattern = '#(?<browser>' . join('|', $known) .
																							')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
																						if (!preg_match_all($pattern, $u_agent, $matches)) {
																							// we have no matching number just continue
																						}


																						// see how many we have
																						$i = count($matches['browser']);
																						if ($i != 1) {
																							if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
																								$version = $matches['version'][0];
																							} else {
																								$version = $matches['version'][1];
																							}
																						} else {
																							$version = $matches['version'][0];
																						}
																					}

																					if ($version == null || $version == "") {
																						$version = "?";
																					}

																					return array(
																						'userAgent' => $u_agent,
																						'name'      => $bname,
																						'short'		=> $ub,
																						'version'   => $version,
																						'platform'  => $platform,
																						'pattern'    => $pattern
																					);
																				}
																				$ua = getBrowser();

																				if (!empty($ua['short'])) {
																					if ($ua['short'] != 'Safari') { ?>
																	<label>Opnemen</label>
																	<span id="recordingslist">
																		<p>Klik op "opnemen" om direct te beginnen met opnemen. </p>
																		<p>Klik op "Stop" om de opname op te slaan en te beluisteren.</p>
																	</span>
																	<span id="opname_buttons">
																		<button type="button" id="button_opnemen" onclick="startRecording(this);">Opnemen</button>
																		<button type="button" id="button_stop" onclick="stopRecording(this);" disabled>Stop</button>
																		<input type="hidden" name="upload" id="ID" value="<?php if ($groep) echo $this->session->userdata('gebruiker_ID') . $les->groep_les_ID;
																															else echo $this->session->userdata('gebruiker_ID') . $les->individu_les_ID; ?>" />
																	</span>
																<?php } else { ?>
																	<p style="color: red">Geluid opnemen wordt niet ondersteund door uw browser.</p>
																<?php } ?></div>
													<?php } else { ?>
														<p style="color: red">Geluid opnemen wordt niet ondersteund door uw browser.</p>
													<?php } ?>
									</div>
									<div id="audio_src">
										<p><label for="huiswerk_bestand">Bestand (mp3)</label><span id="browse"><input type="file" name="huiswerk_bestand" id="huiswerk_bestand" accept=".mp3" /><span class="feedback" style="float:right"></span></p>
									</div>

									<div id="feedback"><?php if (!empty($feedback)) : ?><p><?php echo $feedback ?></p><?php endif; ?></div>
									<p><input type="submit" name="uploaden" value="Bestand uploaden" id="uploaden" />
										<div id="toegevoegd"><?php echo sizeof($huiswerk) ?> / <?php echo $les->les_huiswerk_aantal ?> toegevoegd
									</p>
								</div>

							<?php else : ?>

								<!-- Huiswerk insturen -->

								<input type="hidden" name="huiswerk_opnieuw" id="huiswerk_opnieuw" value="nee" />
								<p><input type="submit" name="insturen" value="Huiswerk insturen" id="insturen" /></p>

							<?php endif; ?>
							</form>
						<?php } else {
												echo "<p style='color:red'>" . $gegevens_tekst . "<p>";
											} ?>


						<!-- Tweede keer insturen -->

					<?php else : ?>

						<?php if ($resultaat->resultaat_opnieuw_ingestuurd_datum == '0000-00-00 00:00:00') : ?>

							<!---------------------->
							<!-- OPNIEUW INSTUREN -->
							<!---------------------->

							<!-- Toegevoegd huiswerk tonen -->

							<?php if (sizeof($huiswerk) > 0) : ?>
								<div id="audio">
									<?php foreach ($huiswerk as $item) : ?>
										<div class="audio">
											<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
											<?php if ($groep) $les_ID = $les->groep_les_ID;
														else $les_ID = $les->individu_les_ID; ?>
											<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="none"></audio>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<?php if ($resultaat->resultaat_beoordeeld_datum != '0000-00-00 00:00:00') : ?>

								<!-- Beoordeling tonen -->

								<?php if ($resultaat->resultaat_voldoende == 'ja') : ?>

									<!-- Voldoende -->

									<h3>Voldoende</h3>

									<?php if (!empty($resultaat->resultaat_feedback_tekst)) : ?>
										<p><?php echo nl2br($resultaat->resultaat_feedback_tekst) ?></p>
									<?php endif; ?>

									<div class="audio voldoende">
										<div class="audio_titel">Feedback docent</div>
										<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_feedback_src) ?>" preload="auto"></audio>
									</div>

								<?php else : ?>

									<!-- Onvoldoende -->

									<h3>Onvoldoende</h3>

									<?php if (!empty($resultaat->resultaat_feedback_tekst)) : ?>
										<p><?php echo nl2br($resultaat->resultaat_feedback_tekst) ?></p>
									<?php endif; ?>

									<div class="audio onvoldoende">
										<div class="audio_titel">Feedback docent</div>
										<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_feedback_src) ?>" preload="auto"></audio>
									</div>

									<h3>Opnieuw insturen</h3>

									<?php if ($huiswerk_aan == 'ja') { ?>
										<?php if ($resultaat->resultaat_opnieuw == 1) : ?>
											<p>Er moet één nieuwe opdracht worden ingestuurd.</p>
										<?php else : ?>
											<p>Er moeten <?php echo $resultaat->resultaat_opnieuw ?> nieuwe opdrachten worden ingestuurd voor een voldoende.</p>
										<?php endif; ?>

										<!-- Opnieuw toegevoegd huiswerk tonen -->

										<?php if (sizeof($opnieuw) > 0) : ?>
											<div id="audio">
												<?php foreach ($opnieuw as $item) : ?>
													<div class="audio verwijderen">
														<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
														<?php if ($groep) $les_ID = $les->groep_les_ID;
																	else $les_ID = $les->individu_les_ID; ?>
														<div class="audio_verwijderen"><a href="<?php echo base_url('cursistenmodule/huiswerk/verwijderen/' . $item->huiswerk_ID . '/' . $les_ID) ?>" title="Huiswerk verwijderen">Opdracht verwijderen</a></div>
														<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="auto"></audio>
													</div>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>

										<!-- Formulier tonen -->

										<form method="post" enctype="multipart/form-data" action="<?php if ($groep) echo base_url('cursistenmodule/lessen/' . $les->groep_les_ID . '#mijnhuiswerk');
																									else echo base_url('cursistenmodule/lessen/' . $les->individu_les_ID . '#mijnhuiswerk'); ?>">

											<?php if (sizeof($opnieuw) < $resultaat->resultaat_opnieuw) : ?>

												<!-- Huiswerk toevoegen -->

												<input type="hidden" name="huiswerk_opnieuw" id="huiswerk_opnieuw" value="ja" />
												<p><label for="huiswerk_titel">Titel bestand</label><input type="text" name="huiswerk_titel" id="huiswerk_titel" placeholder="Type hier de titel van je opdracht" value="<?php echo $huiswerk_titel ?>" /></p>
												<p><label for="Audio_option">Audio type</label><input type="radio" name="audio_option" id="audio_mp3" value="audio_src" onClick="audio_type()" checked /> Mp3 uploaden <input type="radio" name="audio_option" id="audio_opnemen" value="audio_opnemen" onClick="audio_type()" />Audio direct opnemen</span></p>
												<div id="audio_record"><?php
																		function getBrowser()
																		{
																			$u_agent = $_SERVER['HTTP_USER_AGENT'];
																			$bname = 'Unknown';
																			$platform = 'Unknown';
																			$version = "";
																			$ub = "";

																			//First get the platform?
																			if (preg_match('/linux/i', $u_agent)) {
																				$platform = 'linux';
																			} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
																				$platform = 'mac';
																			} elseif (preg_match('/windows|win32/i', $u_agent)) {
																				$platform = 'windows';
																			}

																			// Next get the name of the useragent yes seperately and for good reason
																			if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
																				$bname = 'Internet Explorer';
																				$ub = "MSIE";
																			} elseif (preg_match('/Firefox/i', $u_agent)) {
																				$bname = 'Mozilla Firefox';
																				$ub = "Firefox";
																			} elseif (preg_match('/Chrome/i', $u_agent)) {
																				$bname = 'Google Chrome';
																				$ub = "Chrome";
																			} elseif (preg_match('/Safari/i', $u_agent)) {
																				$bname = 'Apple Safari';
																				$ub = "Safari";
																			} elseif (preg_match('/Opera/i', $u_agent)) {
																				$bname = 'Opera';
																				$ub = "Opera";
																			} elseif (preg_match('/Netscape/i', $u_agent)) {
																				$bname = 'Netscape';
																				$ub = "Netscape";
																			}

																			if (!empty($ub)) {
																				$known = array('Version', $ub, 'other');
																				$pattern = '#(?<browser>' . join('|', $known) .
																					')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
																				if (!preg_match_all($pattern, $u_agent, $matches)) {
																					// we have no matching number just continue
																				}


																				// see how many we have
																				$i = count($matches['browser']);
																				if ($i != 1) {
																					if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
																						$version = $matches['version'][0];
																					} else {
																						$version = $matches['version'][1];
																					}
																				} else {
																					$version = $matches['version'][0];
																				}
																			}

																			if ($version == null || $version == "") {
																				$version = "?";
																			}

																			return array(
																				'userAgent' => $u_agent,
																				'name'      => $bname,
																				'short'		=> $ub,
																				'version'   => $version,
																				'platform'  => $platform,
																				'pattern'    => $pattern
																			);
																		}
																		$ua = getBrowser();

																		if (!empty($ua['short'])) {
																			if ($ua['short'] != 'Safari') { ?>
															<label>Opnemen</label>
															<span id="recordingslist">
																<p>Klik op "opnemen" om direct te beginnen met opnemen. </p>
																<p>Klik op "Stop" om de opname op te slaan en te beluisteren.</p>
															</span>
															<span id="opname_buttons">
																<button type="button" id="button_opnemen" onclick="startRecording(this);">Opnemen</button>
																<button type="button" id="button_stop" onclick="stopRecording(this);" disabled>Stop</button>
																<input type="hidden" name="upload" id="ID" value="<?php if ($groep) echo $this->session->userdata('gebruiker_ID') . $les->groep_les_ID;
																													else echo $this->session->userdata('gebruiker_ID') . $les->individu_les_ID; ?>" />
															</span>
														<?php } else { ?>
															<p style="color: red">Geluid opnemen wordt niet ondersteund door uw browser.</p>
														<?php } ?></div>
											<?php } else { ?>
												<p style="color: red">Geluid opnemen wordt niet ondersteund door uw browser.</p>
											<?php } ?>
							</div>
							<div id="audio_src">
								<p><label for="huiswerk_bestand">Bestand (mp3)</label><span id="browse"><input type="file" name="huiswerk_bestand" id="huiswerk_bestand" accept=".mp3" /><span class="feedback" style="float:right"></span></p>
							</div>

							<div id="feedback"><?php if (!empty($feedback)) : ?><p><?php echo $feedback ?></p><?php endif; ?></div>
							<p><input type="submit" name="uploaden" value="Bestand uploaden" id="uploaden" />
								<div id="toegevoegd"><?php echo sizeof($opnieuw) ?> / <?php echo $resultaat->resultaat_opnieuw ?> toegevoegd
							</p>
					</div>

				<?php else : ?>
					<!-- Huiswerk insturen -->

					<input type="hidden" name="huiswerk_opnieuw" id="huiswerk_opnieuw" value="ja" />
					<p><input type="submit" name="insturen" value="Huiswerk insturen" id="insturen" /></p>

				<?php endif; ?>

				</form>
			<?php } else {
															echo "<p style='color:red'>" . $gegevens_tekst . "<p>";
														} ?>
		<?php endif; ?>
	<?php else : ?>

		<!-- Beoordeling afwachten -->

		<h3>Resultaat in afwachting</h3>
		<p>De opdracht is ontvangen op <?php echo date('d-m-Y', strtotime($resultaat->resultaat_ingestuurd_datum)) ?> en wordt zo spoedig mogelijk door jouw docent nagekeken.</p>

	<?php endif; ?>

<?php else : ?>

	<!---------------------->
	<!-- OPNIEUW FEEDBACK -->
	<!---------------------->

	<!-- Toegevoegd huiswerk tonen -->

	<?php if (sizeof($huiswerk) > 0) : ?>
		<div id="audio">
			<?php foreach ($huiswerk as $item) : ?>
				<div class="audio">
					<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
					<?php if ($groep) $les_ID = $les->groep_les_ID;
														else $les_ID = $les->individu_les_ID; ?>
					<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="none"></audio>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<!-- Feedback onvoldoende tonen -->

	<h3>Onvoldoende</h3>

	<?php if (!empty($resultaat->resultaat_feedback_tekst)) : ?>
		<p><?php echo nl2br($resultaat->resultaat_feedback_tekst) ?></p>
	<?php endif; ?>

	<div class="audio onvoldoende">
		<div class="audio_titel">Feedback docent</div>
		<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_feedback_src) ?>" preload="none"></audio>
	</div>

	<!-- Opnieuw ingestuurd huiswerk tonen -->

	<h3>Opnieuw ingestuurd</h3>

	<?php if (sizeof($opnieuw) > 0) : ?>
		<div id="audio">
			<?php foreach ($opnieuw as $item) : ?>
				<div class="audio">
					<div class="audio_titel"><?php echo $item->huiswerk_titel ?></div>
					<?php if ($groep) $les_ID = $les->groep_les_ID;
														else $les_ID = $les->individu_les_ID; ?>
					<audio src="<?php echo base_url('media/huiswerk/' . $item->huiswerk_src) ?>" preload="none"></audio>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<!-- Feedback -->

	<?php if ($resultaat->resultaat_opnieuw_beoordeeld_datum != '0000-00-00 00:00:00') : ?>

		<!-- Feedback tonen -->

		<?php if ($resultaat->resultaat_opnieuw_voldoende == 'ja') : ?>

			<!-- Voldoende -->

			<h3>Voldoende</h3>

			<?php if (!empty($resultaat->resultaat_opnieuw_feedback_tekst)) : ?>
				<p><?php echo nl2br($resultaat->resultaat_opnieuw_feedback_tekst) ?></p>
			<?php endif; ?>

			<div class="audio voldoende">
				<div class="audio_titel">Feedback docent</div>
				<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_opnieuw_feedback_src) ?>" preload="auto"></audio>
			</div>

		<?php else : ?>

			<!-- Onvoldoende -->

			<h3>Onvoldoende</h3>

			<?php if (!empty($resultaat->resultaat_opnieuw_feedback_tekst)) : ?>
				<p><?php echo nl2br($resultaat->resultaat_opnieuw_feedback_tekst) ?></p>
			<?php endif; ?>

			<div class="audio onvoldoende">
				<div class="audio_titel">Feedback docent</div>
				<audio src="<?php echo base_url('media/huiswerk/' . $resultaat->resultaat_opnieuw_feedback_src) ?>" preload="auto"></audio>
			</div>

		<?php endif; ?>

	<?php else : ?>

		<!-- Beoordeling afwachten -->

		<h3>Resultaat in afwachting</h3>
		<p>De opdracht is ontvangen op <?php echo date('d-m-Y', strtotime($resultaat->resultaat_opnieuw_ingestuurd_datum)) ?> en wordt zo spoedig mogelijk door jouw docent nagekeken.</p>

	<?php endif; ?>

<?php endif; ?>

<?php endif; ?>
</div>
</div>
</div>
</div>
<?php endif; ?>


<?php } ?>
</div>
<?php else : ?>
	<p>Er zijn nog geen lessen beschikbaar voor deze workshop.</p>
<?php endif; ?>
</div>
</div>
<?php } ?>

<?php $kennismakingsworkshopID = $this->session->userdata('kennismakingsworkshop_ID'); ?>
<?php if (!empty($kennismakingsworkshopID)) { ?>
	<div class="clearfix">
		<h1><?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></h1>
		<div id="komende-les">
			<div id="omschrijving">
				<h3>Omschrijving</h3>
				<?php echo $kennismakingsworkshop->kennismakingsworkshop_omschrijving ?>
			</div>
		</div>
		<div id="agenda">
			<h1>Kennismakingsworkshop</h1>
			<table cellpadding="0" cellspacing="0" border="0" class="lessen">
				<tr>
					<th class="kennismakingsworkshop">Kennismakingsworkshop</td>
					<th class="datum">Datum</td>
					<th class="tijd">Tijd</td>
				</tr>
				<tr class="smal active">
					<td class="les-titel"><?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></td>
					<td class="datum"><?php echo date('d/m/y', strtotime($kennismakingsworkshop->kennismakingsworkshop_datum)) ?></td>
					<td class="tijd"><?php echo date('H:i', strtotime($kennismakingsworkshop->kennismakingsworkshop_datum)) ?></td>
				</tr>
			</table>
		</div>
		<?php if (!empty($media)) : ?>
			<div id="agenda">
				<div id="media">
					<h2>Kennismakingsworkshop stemopname</h2>
					<div id="kennismakingsworkshop_media">
						<table cellpadding="5" cellspacing="5">
							<th style="float:left; color: #000;">Audio</th>
							<?php if (sizeof($media) > 0) : ?>
								<div id="audio">
									<?php foreach ($media as $item) : ?>
										<?php if ($item->gebruiker_ID == $this->session->userdata('gebruiker_ID')) : ?>
											<tr>
												<td>
													<div class="audio">
														<div class="audio_titel"><?php echo $item->media_titel ?></div>
														<audio src="<?php echo base_url('media/kennismakingsworkshops/' . $item->media_src) ?>" preload="none"></audio>
													</div>
													<p class="links"><a href="<?php echo base_url('media/kennismakingsworkshops/' . $item->media_src) ?>" download="<?php echo $item->media_src ?>">Download</a></p>
												</td>
											</tr>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</table>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php } ?>
	</div>
	</div>
<?php } else { ?>
	<?php if (empty($workshop->workshop_cursistenmodule_tekst)) { ?>
		<div class="kandidaat_welkom_tekst">
			<p>Welkom bij de cursistenmodule!</p>
			<p>Hier op jouw persoonlijke dashboard zie je een overzicht van de komende les.</p>
			<p>Je kan in de cursistenmodule verschillende dingen doen zoals bijv. berichten versturen, onze producten bekijken, je profiel wijzigen en je aanmelden voor andere interessante workshops.</p>
			<p>Wij wensen je veel plezier!</p>
		</div>
	<?php } else {
			echo $workshop->workshop_cursistenmodule_tekst;
		} ?>
	<?php $workshopID = $this->session->userdata('workshop_ID'); ?>
	<?php if (!empty($workshopID)) { ?>
		<?php if (sizeof($lessen) > 0) : ?>
			<div class="clearfix">
				<div>
					<h1>Lessen</h1>
					<?php if (isset($lessen) && sizeof($lessen) > 0) : ?>
						<table cellpadding="0" cellspacing="0" border="0" class="lessen">
							<tr>
								<th class="locatie">
									</td>
								<th class="les">Les</td>
								<th class="datum">Datum</td>
								<th class="tijd">Tijd</td>
									<?php if ($groep) { ?>
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
							?>
								<tr class="smal <?php echo $class ?>">
									<td class="locatie"><span class="<?php echo $les->les_locatie ?>" title="<?php echo ucfirst($les->les_locatie) ?>"><?php echo ucfirst($les->les_locatie) ?></span></td>
									<td class="les-titel"><?php echo $les->les_titel ?></td>
									<td class="datum"><?php echo date('d/m/y', strtotime($les_datum)) ?></td>
									<td class="tijd"><?php echo date('H:i', strtotime($les_datum)) ?></td>
									<?php if ($groep && $les->les_locatie != 'online') { ?>
										<td class="aanwezigheid" title="">
											<select name="select-aanwezigheid" onchange="insertAanwezigheid(<?php echo $les->groep_les_ID ?>, <?php echo $this->session->userdata('gebruiker_ID') ?>, this.value)">
												<option value="ja">Ja</option>
												<option value="nee" <?php if (!empty($les->les_aanwezigheid)) {
																		echo "selected";
																	} ?>>Nee</option>
											</select>
										</td>
									<?php } else { ?>
										<td></td>
									<?php } ?>
								</tr>
							<?php
							endfor;
							?>
						</table>
					<?php else : ?>
						<p>Geen lessen gevonden</p>
					<?php endif; ?>
				<?php else : ?>
					<p>Er zijn nog geen lessen beschikbaar voor deze workshop.</p>
				<?php endif; ?>
			<?php } ?>

			<?php $kennismakingsworkshopID = $this->session->userdata('kennismakingsworkshop_ID'); ?>
			<?php if (!empty($kennismakingsworkshopID)) { ?>
				<div class="clearfix">
					<h1><?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></h1>
					<div id="komende-les">
						<div id="omschrijving">
							<h3>Omschrijving</h3>
							<?php echo $kennismakingsworkshop->kennismakingsworkshop_omschrijving ?>
						</div>
					</div>
					<div id="agenda">
						<h1>Kennismakingsworkshop</h1>
						<table cellpadding="0" cellspacing="0" border="0" class="lessen">
							<tr>
								<th class="kennismakingsworkshop">Kennismakingsworkshop</td>
								<th class="datum">Datum</td>
								<th class="tijd">Tijd</td>
							</tr>
							<tr class="smal active">
								<td class="les-titel"><?php echo $kennismakingsworkshop->kennismakingsworkshop_titel ?></td>
								<td class="datum"><?php echo date('d/m/y', strtotime($kennismakingsworkshop->kennismakingsworkshop_datum)) ?></td>
								<td class="tijd"><?php echo date('H:i', strtotime($kennismakingsworkshop->kennismakingsworkshop_datum)) ?></td>
							</tr>
						</table>
					</div>
					<?php if (!empty($media)) : ?>
						<div id="agenda">
							<div id="media">
								<h2>Kennismakingsworkshop stemopname</h2>
								<div id="kennismakingsworkshop_media">
									<table cellpadding="5" cellspacing="5">
										<th style="float:left; color: #000;">Audio</th>
										<?php if (sizeof($media) > 0) : ?>
											<div id="audio">
												<?php foreach ($media as $item) : ?>
													<?php if ($item->gebruiker_ID == $this->session->userdata('gebruiker_ID')) : ?>
														<tr>
															<td>
																<div class="audio">
																	<div class="audio_titel"><?php echo $item->media_titel ?></div>
																	<audio src="<?php echo base_url('media/kennismakingsworkshops/' . $item->media_src) ?>" preload="none"></audio>
																</div>
																<p class="links"><a href="<?php echo base_url('media/kennismakingsworkshops/' . $item->media_src) ?>" download="<?php echo $item->media_src ?>">Download</a></p>
															</td>
														</tr>
													<?php endif; ?>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php } ?>
				</div>
				</div>
			</div>
		<?php } ?>

		<br>
	<?php else : ?>
		<p>Je bent nog niet aangemeld voor een workshop of de iDEAL betaling is nog niet rond.</p>
	<?php endif; ?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media extends CI_Controller
{
	private $data = array();
	
	public function __construct()
	{
		parent::__construct();
		
		// Rechten controleren en aantal nieuwe items ophalen
		
		$this->load->library('algemeen');
		$this->algemeen->cms();
		if($this->session->userdata('beheerder_rechten') != 'admin' && $this->session->userdata('beheerder_rechten') != 'contentmanager') redirect('cms/rechten');
	}
	
	
	
	/* ============= */
	/* = OVERZICHT = */
	/* ============= */
	
	public function index()
	{
		$this->pagina();
	}
	
	public function pagina($p = 1, $media_soort = null)
	{
		$this->load->model('media_model');
		$aantal_items = $this->media_model->getMediaAantal($media_soort);
		$per_pagina = 20;
		$aantal_paginas = ceil($aantal_items / $per_pagina);
		$huidige_pagina = $p;
        $media = $this->media_model->getMedia($per_pagina, $huidige_pagina, $media_soort);
		
		// Controleren of de paginanummer te hoog is
		if($p > 1 && sizeof($media) == 0) redirect('cms/media');
		
		// PAGINA TONEN
		
		$this->data['media']	 			= $media;
		$this->data['media_soort']	 		= $media_soort;
		$this->data['aantal_paginas'] 		= $aantal_paginas;
		$this->data['huidige_pagina']		= $huidige_pagina;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/media';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ============ */
	/* = BEKIJKEN = */
	/* ============ */
	
	public function detail($item_ID = null)
	{
		if($item_ID == null) redirect('cms/media');
		
		$this->load->model('media_model');
		$item = $this->media_model->getMediaByID($item_ID);
		if($item == null) redirect('cms/media');
		$this->data['item'] = $item;
		
		
		// PAGINA LADEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/media_detail';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* ========================= */
	/* = TOEVOEGEN EN WIJZIGEN = */
	/* ========================= */
	
	public function toevoegen()
	{
		$this->_toevoegen_wijzigen('toevoegen');
	}
	
	public function wijzigen($item_ID = null)
	{
		if($item_ID == null) redirect('cms/media');
		$this->_toevoegen_wijzigen('wijzigen', $item_ID);
	}
	
	private function _resize($bestand_naam, $bestand_breedte, $bestand_hoogte, $width, $height, $folder)
	{
		// Resize
		
		$config['image_library'] 	= 'gd2';
		$config['source_image']		= './media/afbeeldingen/origineel/'.$bestand_naam;
		$config['create_thumb'] 	= false;
		$config['maintain_ratio'] 	= true;
		$config['new_image'] 		= './media/afbeeldingen/'.$folder.'/'.$bestand_naam;
		$config['quality'] 			= "100%";
		$config['width']			= $width;
		$config['height']			= $height;
		
		$dim = (intval($bestand_breedte) / intval($bestand_hoogte)) - ($config['width'] / $config['height']);
		$config['master_dim'] = ($dim > 0)? "height" : "width";
		
		$this->load->library('image_lib');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		// Crop
		
		$config['image_library'] = 'gd2';
		$config['source_image'] = './media/afbeeldingen/'.$folder.'/'.$bestand_naam;
		
		list($source_width, $source_height) = getimagesize($config['source_image']);
		
		$x_axis = 0;
		$y_axis = 0;
		
		if($source_height > $height)
		{
			$y_axis = ($source_height - $height) / 2;
		}
		elseif($source_width > $width)
		{
			$x_axis = ($source_width - $width) / 2;
		}
		
		$config['new_image'] = './media/afbeeldingen/'.$folder.'/'.$bestand_naam;
		$config['quality'] = "100%";
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['x_axis'] = $x_axis;
		$config['y_axis'] = $y_axis;
		 
		$this->image_lib->clear();
		$this->image_lib->initialize($config); 
		$this->image_lib->crop();
	}
	
	private function _toevoegen_wijzigen($actie, $item_ID = null)
	{
		$this->load->model('media_model');
		
		$item_type 		= 'pdf';
		$item_src		= '';
		$item_titel		= '';
		
		$item_type_feedback 	= '';
		$item_src_feedback 		= '';
		$item_titel_feedback 	= '';
		
		
		// FORMULIER VERZONDEN
		
		if(isset($_POST['item_type']))
		{
			$fouten 		= 0;
			$item_type 		= trim($_POST['item_type']);
			$item_titel		= trim($_POST['item_titel']);
			

			if(empty($item_titel))
			{
				$fouten++;
				$item_titel_feedback = 'Graag invullen';
			}
			else
			{
				if($actie == 'toevoegen') {
				if($item_type == 'pdf')
				{
					///////////////////
					// PDF TOEVOEGEN //
					///////////////////
					
					if($actie == 'toevoegen')
					{
						if($_FILES['item_src_pdf']['error'] > 0)
						{
							$fouten++;
								
							switch($_FILES['item_src_pdf']['error'])
							{
								case 1:
								$item_src_feedback = 'Het bestand is te groot';
								break;
								
								case 2:
								$item_src_feedback = 'Het bestand is te groot';
								break;
								
								case 3:
								$item_src_feedback = 'Het bestand is niet goed geüpload';
								break;
								
								case 4:
								$item_src_feedback = 'Graag selecteren';
								break;
								
								case 6:
								$item_src_feedback = 'Geen tijdelijke folder';
								break;
								
								case 7:
								$item_src_feedback = 'Kon bestand niet uploaden';
								break;
							}
						}
						else
						{
							$bestand_naam 				= $_FILES['item_src_pdf']['name'];
							$bestand_type 				= $_FILES['item_src_pdf']['type'];
							$bestand_grootte 			= $_FILES['item_src_pdf']['size'];
							$bestand_tijdelijke_naam 	= $_FILES['item_src_pdf']['tmp_name'];
							
							$bestand_type_extensie = explode('.', $bestand_naam);
							$bestand_type_extensie = strtolower(end($bestand_type_extensie));
							
							if($bestand_type_extensie == 'pdf')
							{
								if($bestand_grootte < 10000000)
								{
									if(!file_exists('./media/pdf/'.$bestand_naam))
									{
										if(move_uploaded_file($bestand_tijdelijke_naam, './media/pdf/'.$bestand_naam))
										{
											// TOEVOEGEN / UPDATEN
											
											$item_src = $bestand_naam;
                                            $vandaag = new DateTime();

											$data = array(
												'media_type' => $item_type,
												'media_src' => $item_src,
												'media_titel' => $item_titel,
                                                'media_datum' => $vandaag->format('Y/m/d H:i:s'),
											);

											$q = $this->media_model->insertMedia($data);
											
											if($q)
											{
												redirect('cms/media');
											}
											else
											{
												echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
											}
										}
										else
										{
											$fouten++;
											$item_src_feedback = 'Het bestand is niet geüpload';
										}
									}
									else
									{
										$fouten++;
										$item_src_feedback = 'Bestandsnaam bestaat al op de server';
									}
								}
								else
								{
									$fouten++;
									$item_src_feedback = 'PDF te groot (maximaal 10 MB)';
								}
							}
							else
							{
								$fouten++;
								$item_src_feedback = 'Selecteer een PDF';
							}
						}
					}
					else
					{
						// Titel van PDF updaten
						
						$data = array('media_titel' => $item_titel);
						$q = $this->media_model->updateMedia($item_ID, $data);
						if($q) redirect('cms/media/'.$item_ID);
						else echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
					}
				}
				elseif($item_type == 'afbeelding')
				{
					//////////////////////////
					// AFBEELDING TOEVOEGEN //
					//////////////////////////
					
					if($actie == 'toevoegen')
					{
						$this->load->library('image_lib');
						
						if($_FILES['item_src_afbeelding']['error'] > 0)
						{
							$fouten++;
								
							switch($_FILES['item_src_afbeelding']['error'])
							{
								case 1:
								$item_src_feedback = 'Het bestand is te groot';
								break;
								
								case 2:
								$item_src_feedback = 'Het bestand is te groot';
								break;
								
								case 3:
								$item_src_feedback = 'Het bestand is niet goed geupload';
								break;
								
								case 4:
								$item_src_feedback = 'Graag selecteren';
								break;
								
								case 6:
								$item_src_feedback = 'Geen tijdelijke folder';
								break;
								
								case 7:
								$item_src_feedback = 'Kon bestand niet uploaden';
								break;
							}
						}
						else
						{
							$bestand_types = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png');
							$bestand_extensies = array('gif', 'jpeg', 'jpg', 'png');
							
							$bestand_naam 				= $_FILES['item_src_afbeelding']['name'];
							$bestand_type 				= $_FILES['item_src_afbeelding']['type'];
							$bestand_grootte 			= $_FILES['item_src_afbeelding']['size'];
							$bestand_tijdelijke_naam 	= $_FILES['item_src_afbeelding']['tmp_name'];
							
							list($bestand_breedte, $bestand_hoogte, $type, $attr) = getimagesize($_FILES["item_src_afbeelding"]['tmp_name']);
							
							$bestand_type_extensie = explode('.', $bestand_naam);
							$bestand_type_extensie = strtolower(end($bestand_type_extensie));
							
							if(in_array($bestand_type, $bestand_types) && in_array($bestand_type_extensie, $bestand_extensies))
							{
								if($bestand_grootte < 10000000)
								{
									if(!file_exists('./media/afbeeldingen/origineel/'.$bestand_naam))
									{
										if(move_uploaded_file($bestand_tijdelijke_naam, './media/afbeeldingen/origineel/'.$bestand_naam))
										{
											// Afbeelding resizen en croppen
											
											$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 620, 380, 'groot');
											$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 620, 163, 'balk');
											$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 380, 235, 'medium');
											$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 300, 220, 'uitgelicht');
											$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 300, 185, 'klein');
											$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 210, 125, 'thumbnail');
											
											
											// TOEVOEGEN / UPDATEN
											
											$item_src = $bestand_naam;
                                            $vandaag = new DateTime();

											$data = array(
												'media_type' => $item_type,
												'media_src' => $item_src,
												'media_titel' => $item_titel,
                                                'media_datum' => $vandaag->format('Y/m/d H:i:s')
											);
											
											$q = $this->media_model->insertMedia($data);
											
											if($q)
											{
												redirect('cms/media');
											}
											else
											{
												echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
											}
										}
										else
										{
											$fouten++;
											$item_src_feedback = 'Het bestand is niet geupload';
										}
									}
									else
									{
										$fouten++;
										$item_src_feedback = 'Bestandsnaam bestaat al op de server';
									}
								}
								else
								{
									$fouten++;
									$item_src_feedback = 'Afbeelding te groot (maximaal 10 MB)';
								}
							}
							else
							{
								$fouten++;
								$item_src_feedback = 'Selecteer een afbeelding (gif/jpg/png)';
							}
						}
					}
					else
					{
						// Titel van afbeelding updaten
						
						$data = array('media_titel' => $item_titel);
						$q = $this->media_model->updateMedia($item_ID, $data);
						if($q) redirect('cms/media/'.$item_ID);
						else echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
					}
				}
				elseif($item_type == 'video')
				{
					/////////////////////
					// VIDEO TOEVOEGEN //
					/////////////////////
					
					$item_src = trim($_POST['item_src_video']);
					
					if(empty($item_src))
					{
						$fouten++;
						$item_src_feedback = 'Graag invullen';
					}
					
					if($fouten == 0)
					{
						// TOEVOEGEN / UPDATEN

                        $vandaag = new DateTime();

						$data = array(
							'media_type' => $item_type,
							'media_src' => $item_src,
							'media_titel' => $item_titel,
                            'media_datum' => $vandaag->format('Y/m/d H:i:s')
						);
						
						if($actie == 'toevoegen') $q = $this->media_model->insertMedia($data);
						else $q = $this->media_model->updateMedia($item_ID, $data);
						
						if($q)
						{
							if($actie == 'toevoegen') redirect('cms/media');
							else redirect('cms/media/'.$item_ID);
						}
						else
						{
							echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
						}
					}	
				}
				elseif($item_type == 'playlist')
				{
					////////////////////////
					// PLAYLIST TOEVOEGEN //
					////////////////////////
					
					$item_src = trim($_POST['item_src_playlist']);
					
					if(empty($item_src))
					{
						$fouten++;
						$item_src_feedback = 'Graag invullen';
					}
					
					if($fouten == 0)
					{
						// TOEVOEGEN / UPDATEN

                        $vandaag = new DateTime();

						$data = array(
							'media_type' => $item_type,
							'media_src' => $item_src,
							'media_titel' => $item_titel,
                            'media_datum' => $vandaag->format('Y/m/d H:i:s')
						);
						
						if($actie == 'toevoegen') $q = $this->media_model->insertMedia($data);
						else $q = $this->media_model->updateMedia($item_ID, $data);

						if($q)
						{
							if($actie == 'toevoegen') redirect('cms/media');
							else redirect('cms/media/'.$item_ID);
						}
						else
						{
							echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
						}
					}	
				}
				elseif($item_type == 'mp3')
				{
					///////////////////
					// MP3 TOEVOEGEN //
					///////////////////
					
					if($actie == 'toevoegen')
					{
						if($_FILES['item_src_mp3']['error'] > 0)
						{
							$fouten++;
								
							switch($_FILES['item_src_mp3']['error'])
							{
								case 1:
								$item_src_feedback = 'Het bestand is te groot';
								break;
								
								case 2:
								$item_src_feedback = 'Het bestand is te groot';
								break;
								
								case 3:
								$item_src_feedback = 'Het bestand is niet goed geüpload';
								break;
								
								case 4:
								$item_src_feedback = 'Graag selecteren';
								break;
								
								case 6:
								$item_src_feedback = 'Geen tijdelijke folder';
								break;
								
								case 7:
								$item_src_feedback = 'Kon bestand niet uploaden';
								break;
							}
						}
						else
						{
							$bestand_naam 				= $_FILES['item_src_mp3']['name'];
							$bestand_type 				= $_FILES['item_src_mp3']['type'];
							$bestand_grootte 			= $_FILES['item_src_mp3']['size'];
							$bestand_tijdelijke_naam 	= $_FILES['item_src_mp3']['tmp_name'];
							
							$bestand_type_extensie = explode('.', $bestand_naam);
							$bestand_type_extensie = strtolower(end($bestand_type_extensie));
							
							if($bestand_type_extensie == 'mp3')
							{
								if($bestand_grootte < 10000000)
								{
									if(!file_exists('./media/audio/'.$bestand_naam))
									{
										if(move_uploaded_file($bestand_tijdelijke_naam, './media/audio/'.$bestand_naam))
										{
											// TOEVOEGEN / UPDATEN
											
											$item_src = $bestand_naam;
											$vandaag = new DateTime();

											$data = array(
												'media_type' => $item_type,
												'media_src' => $item_src,
												'media_titel' => $item_titel,
                                                'media_datum' => $vandaag->format('Y/m/d H:i:s')
											);
											
											$q = $this->media_model->insertMedia($data);
											
											if($q)
											{
												redirect('cms/media');
											}
											else
											{
												echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
											}
										}
										else
										{
											$fouten++;
											$item_src_feedback = 'Het bestand is niet geüpload';
										}
									}
									else
									{
										$fouten++;
										$item_src_feedback = 'Bestandsnaam bestaat al op de server';
									}
								}
								else
								{
									$fouten++;
									$item_src_feedback = 'MP3 te groot (maximaal 10 MB)';
								}
							}
							else
							{
								$fouten++;
								$item_src_feedback = 'Selecteer een MP3';
							}
						}
					}
					else
					{
						// Titel van PDF updaten
						
						$data = array('media_titel' => $item_titel);
						$q = $this->media_model->updateMedia($item_ID, $data);
						if($q) redirect('cms/media/'.$item_ID);
						else echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
					}
				}	
			} else {
				if($actie == 'wijzigen') {
					if($item_type == 'pdf')
					{
						///////////////////
						// PDF Wijzigen //
						///////////////////
	
							if($_FILES['item_src_pdf']['error'] > 0)
							{
								$fouten++;
									
								switch($_FILES['item_src_pdf']['error'])
								{
									case 1:
									$item_src_feedback = 'Het bestand is te groot';
									break;
									
									case 2:
									$item_src_feedback = 'Het bestand is te groot';
									break;
									
									case 3:
									$item_src_feedback = 'Het bestand is niet goed geüpload';
									break;
									
									case 4:
									$item_src_feedback = 'Graag selecteren';
									break;
									
									case 6:
									$item_src_feedback = 'Geen tijdelijke folder';
									break;
									
									case 7:
									$item_src_feedback = 'Kon bestand niet uploaden';
									break;
								}
							}
							else
							{
								$bestand_naam 				= $_FILES['item_src_pdf']['name'];
								$bestand_type 				= $_FILES['item_src_pdf']['type'];
								$bestand_grootte 			= $_FILES['item_src_pdf']['size'];
								$bestand_tijdelijke_naam 	= $_FILES['item_src_pdf']['tmp_name'];
								
								$bestand_type_extensie = explode('.', $bestand_naam);
								$bestand_type_extensie = strtolower(end($bestand_type_extensie));
								
								if($bestand_type_extensie == 'pdf')
								{
									if($bestand_grootte < 10000000)
									{
										if(!file_exists('./media/pdf/'.$bestand_naam))
										{
											if(move_uploaded_file($bestand_tijdelijke_naam, './media/pdf/'.$bestand_naam))
											{
												// TOEVOEGEN / UPDATEN
												
												$item_src = $bestand_naam;
												$vandaag = new DateTime();
	
												$data = array(
													'media_type' => $item_type,
													'media_src' => $item_src,
													'media_titel' => $item_titel,
													'media_datum' => $vandaag->format('Y/m/d H:i:s'),
												);

												$q = $this->media_model->updateMedia($item_ID, $data);
												if($q) redirect('cms/media/'.$item_ID);
												else echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
											}
											else
											{
												$fouten++;
												$item_src_feedback = 'Het bestand is niet geüpload';
											}
										}
										else
										{
											$fouten++;
											$item_src_feedback = 'Bestandsnaam bestaat al op de server';
										}
									}
									else
									{
										$fouten++;
										$item_src_feedback = 'PDF te groot (maximaal 10 MB)';
									}
								}
								else
								{
									$fouten++;
									$item_src_feedback = 'Selecteer een PDF';
								}
							}
					}
					elseif($item_type == 'afbeelding')
					{
						//////////////////////////
						// AFBEELDING WIJZIGEN //
						//////////////////////////
						
							$this->load->library('image_lib');
							
							if($_FILES['item_src_afbeelding']['error'] > 0)
							{
								$fouten++;
									
								switch($_FILES['item_src_afbeelding']['error'])
								{
									case 1:
									$item_src_feedback = 'Het bestand is te groot';
									break;
									
									case 2:
									$item_src_feedback = 'Het bestand is te groot';
									break;
									
									case 3:
									$item_src_feedback = 'Het bestand is niet goed geupload';
									break;
									
									case 4:
									$item_src_feedback = 'Graag selecteren';
									break;
									
									case 6:
									$item_src_feedback = 'Geen tijdelijke folder';
									break;
									
									case 7:
									$item_src_feedback = 'Kon bestand niet uploaden';
									break;
								}
							}
							else
							{
								$bestand_types = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png');
								$bestand_extensies = array('gif', 'jpeg', 'jpg', 'png');
								
								$bestand_naam 				= $_FILES['item_src_afbeelding']['name'];
								$bestand_type 				= $_FILES['item_src_afbeelding']['type'];
								$bestand_grootte 			= $_FILES['item_src_afbeelding']['size'];
								$bestand_tijdelijke_naam 	= $_FILES['item_src_afbeelding']['tmp_name'];
								
								list($bestand_breedte, $bestand_hoogte, $type, $attr) = getimagesize($_FILES["item_src_afbeelding"]['tmp_name']);
								
								$bestand_type_extensie = explode('.', $bestand_naam);
								$bestand_type_extensie = strtolower(end($bestand_type_extensie));
								
								if(in_array($bestand_type, $bestand_types) && in_array($bestand_type_extensie, $bestand_extensies))
								{
									if($bestand_grootte < 10000000)
									{
										if(!file_exists('./media/afbeeldingen/origineel/'.$bestand_naam))
										{
											if(move_uploaded_file($bestand_tijdelijke_naam, './media/afbeeldingen/origineel/'.$bestand_naam))
											{
												// Afbeelding resizen en croppen
												
												$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 620, 380, 'groot');
												$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 620, 163, 'balk');
												$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 380, 235, 'medium');
												$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 300, 220, 'uitgelicht');
												$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 300, 185, 'klein');
												$this->_resize($bestand_naam, $bestand_breedte, $bestand_hoogte, 210, 125, 'thumbnail');
												
												
												// TOEVOEGEN / UPDATEN
												
												$item_src = $bestand_naam;
												$vandaag = new DateTime();
	
												$data = array(
													'media_type' => $item_type,
													'media_src' => $item_src,
													'media_titel' => $item_titel,
													'media_datum' => $vandaag->format('Y/m/d H:i:s')
												);
												
												$q = $this->media_model->updateMedia($item_ID, $data);
												if($q) redirect('cms/media/'.$item_ID);
												else echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
											}
											else
											{
												$fouten++;
												$item_src_feedback = 'Het bestand is niet geupload';
											}
										}
										else
										{
											$fouten++;
											$item_src_feedback = 'Bestandsnaam bestaat al op de server';
										}
									}
									else
									{
										$fouten++;
										$item_src_feedback = 'Afbeelding te groot (maximaal 10 MB)';
									}
								}
								else
								{
									$fouten++;
									$item_src_feedback = 'Selecteer een afbeelding (gif/jpg/png)';
								}
							}
					}
					elseif($item_type == 'video')
					{
						/////////////////////
						// VIDEO WIJZIGEN //
						/////////////////////
						
						$item_src = trim($_POST['item_src_video']);
						
						if(empty($item_src))
						{
							$fouten++;
							$item_src_feedback = 'Graag invullen';
						}
						
						if($fouten == 0)
						{
							// TOEVOEGEN / UPDATEN
	
							$vandaag = new DateTime();
	
							$data = array(
								'media_type' => $item_type,
								'media_src' => $item_src,
								'media_titel' => $item_titel,
								'media_datum' => $vandaag->format('Y/m/d H:i:s')
							);
							
							if($actie == 'toevoegen') $q = $this->media_model->insertMedia($data);
							else $q = $this->media_model->updateMedia($item_ID, $data);
							
							if($q)
							{
								if($actie == 'toevoegen') redirect('cms/media');
								else redirect('cms/media/'.$item_ID);
							}
							else
							{
								echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
							}
						}	
					}
					elseif($item_type == 'playlist')
					{
						////////////////////////
						// PLAYLIST WIJZIGEN //
						////////////////////////
						
						$item_src = trim($_POST['item_src_playlist']);
						
						if(empty($item_src))
						{
							$fouten++;
							$item_src_feedback = 'Graag invullen';
						}
						
						if($fouten == 0)
						{
							// TOEVOEGEN / UPDATEN
	
							$vandaag = new DateTime();
	
							$data = array(
								'media_type' => $item_type,
								'media_src' => $item_src,
								'media_titel' => $item_titel,
								'media_datum' => $vandaag->format('Y/m/d H:i:s')
							);
							
							if($actie == 'toevoegen') $q = $this->media_model->insertMedia($data);
							else $q = $this->media_model->updateMedia($item_ID, $data);
						}	
					}
					elseif($item_type == 'mp3')
					{
						///////////////////
						// MP3 WIJZIGEN //
						///////////////////
							if($_FILES['item_src_mp3']['error'] > 0)
							{
								$fouten++;
									
								switch($_FILES['item_src_mp3']['error'])
								{
									case 1:
									$item_src_feedback = 'Het bestand is te groot';
									break;
									
									case 2:
									$item_src_feedback = 'Het bestand is te groot';
									break;
									
									case 3:
									$item_src_feedback = 'Het bestand is niet goed geüpload';
									break;
									
									case 4:
									$item_src_feedback = 'Graag selecteren';
									break;
									
									case 6:
									$item_src_feedback = 'Geen tijdelijke folder';
									break;
									
									case 7:
									$item_src_feedback = 'Kon bestand niet uploaden';
									break;
								}
							}
							else
							{
								$bestand_naam 				= $_FILES['item_src_mp3']['name'];
								$bestand_type 				= $_FILES['item_src_mp3']['type'];
								$bestand_grootte 			= $_FILES['item_src_mp3']['size'];
								$bestand_tijdelijke_naam 	= $_FILES['item_src_mp3']['tmp_name'];
								
								$bestand_type_extensie = explode('.', $bestand_naam);
								$bestand_type_extensie = strtolower(end($bestand_type_extensie));
								
								if($bestand_type_extensie == 'mp3')
								{
									if($bestand_grootte < 10000000)
									{
										if(!file_exists('./media/audio/'.$bestand_naam))
										{
											if(move_uploaded_file($bestand_tijdelijke_naam, './media/audio/'.$bestand_naam))
											{
												// TOEVOEGEN / UPDATEN
												
												$item_src = $bestand_naam;
												$vandaag = new DateTime();
	
												$data = array(
													'media_type' => $item_type,
													'media_src' => $item_src,
													'media_titel' => $item_titel,
													'media_datum' => $vandaag->format('Y/m/d H:i:s')
												);
												
												$q = $this->media_model->updateMedia($item_ID, $data);
												if($q) redirect('cms/media/'.$item_ID);
												else echo 'Item '.$actie.' mislukt. Probeer het nog eens.';
											}
											else
											{
												$fouten++;
												$item_src_feedback = 'Het bestand is niet geüpload';
											}
										}
										else
										{
											$fouten++;
											$item_src_feedback = 'Bestandsnaam bestaat al op de server';
										}
									}
									else
									{
										$fouten++;
										$item_src_feedback = 'MP3 te groot (maximaal 10 MB)';
									}
								}
								else
								{
									$fouten++;
									$item_src_feedback = 'Selecteer een MP3';
								}
							}
					}	
				}
			}
		}
	}
	else
	{
		if($actie == 'wijzigen')
		{
			$item = $this->media_model->getMediaByID($item_ID);
			if($item == null) redirect('cms/media');
			
			$item_type 		= $item->media_type;
			$item_src	 	= $item->media_src;
			$item_titel	 	= $item->media_titel;
		}
	}
		
		
		// PAGINA TONEN
		
		$this->data['actie'] = $actie;
		
		$this->data['item_ID'] 			= $item_ID;
		$this->data['item_type'] 		= $item_type;
		$this->data['item_src'] 		= $item_src;
		$this->data['item_titel'] 		= $item_titel;
		
		$this->data['item_type_feedback'] 		= $item_type_feedback;
		$this->data['item_src_feedback'] 		= $item_src_feedback;
		$this->data['item_titel_feedback']	 	= $item_titel_feedback;
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/media_wijzigen';
		$this->load->view('cms/template', $pagina);
	}
	
	
	
	/* =============== */
	/* = VERWIJDEREN = */
	/* =============== */
	
	public function verwijderen($item_ID = null, $bevestiging = null)
	{
		if($item_ID == null) redirect('cms/media');
		
		$this->load->model('media_model');
		$item = $this->media_model->getMediaByID($item_ID);
		if($item == null) redirect('cms/media');
		$this->data['item'] = $item;
		
		
		// ITEM VERWIJDEREN
		
		if($bevestiging == 'ja')
		{
			if($item->media_type == 'pdf')
			{
				unlink('./media/pdf/'.$item->media_src);
			}
			elseif($item->media_type == 'afbeelding')
			{
				unlink('./media/afbeeldingen/origineel/'.$item->media_src);
				unlink('./media/afbeeldingen/groot/'.$item->media_src);
				unlink('./media/afbeeldingen/medium/'.$item->media_src);
				unlink('./media/afbeeldingen/klein/'.$item->media_src);
				unlink('./media/afbeeldingen/thumbnail/'.$item->media_src);
			}
			
			$q = $this->media_model->deleteMedia($item_ID);
			if($q) redirect('cms/media');
			else echo 'Het item kon niet worden verwijderd. Probeer het nog eens.';
		}
		
		
		// PAGINA TONEN
		
		$pagina['data'] = $this->data;
		$pagina['pagina'] = 'cms/media_verwijderen';
		$this->load->view('cms/template', $pagina);
	}
}
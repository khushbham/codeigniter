<?php

$url = explode('/', $_SERVER['REQUEST_URI']);

if (isset($url[2])) $pagina = $url[2];
else $pagina = '';

$site_titel = 'localhost';
$page_description = '';
$page_keywords = '';
$page_url = '';
$page_title = '';

$workshop = $this->workshops_model->getWorkshopByID($this->session->userdata('workshop_ID'));
$kennismakingsworkshops = $this->kennismakingsworkshop_model->getKennismakingsworkshopsByGebruikerID($this->session->userdata('gebruiker_ID'));
$workshops = $this->workshops_model->getWorkshopsByGebruikerID($this->session->userdata('gebruiker_ID'));

?>
<!DOCTYPE html>
<html lang="nl">

<head>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-KL3MNKM');</script>
	<!-- End Google Tag Manager -->

	<script async="async" src="https://static.mobilemonkey.com/js/288697674490224.js"></script>
	<meta charset="utf-8" />
	<title>Cursistenmodule localhost</title>
	<meta name="description" content="<?php echo $page_description ?>" />
	<meta name="keywords" content="<?php echo $page_keywords ?>" />
	<meta name="viewport" content="width=1100" />
	<meta property="og:url" content="<?php echo $page_url ?>" />
	<meta property="og:title" content="<?php echo $page_title ?>" />
	<meta property="og:site_name" content="<?php echo $site_titel ?>" />
	<meta property="og:description" content="<?php echo $page_description ?>" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/cursistenmodule.css?v23122020') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/images/favicon.ico') ?>" rel="shortcut icon" />
	<link href="<?php echo base_url('assets/images/localhost-icon-57.png') ?>" rel="apple-touch-icon-precomposed" sizes="57x57" />
	<link href="<?php echo base_url('assets/images/localhost-icon-72.png') ?>" rel="apple-touch-icon-precomposed" sizes="72x72" />
	<link href="<?php echo base_url('assets/images/localhost-icon-114.png') ?>" rel="apple-touch-icon-precomposed" sizes="114x114" />
	<link href="<?php echo base_url('assets/images/localhost-icon-144.png') ?>" rel="apple-touch-icon-precomposed" sizes="144x144" />
	<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>
	<script src="<?php echo base_url('assets/js/modernizr.custom.45618.js') ?>"></script>

</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KL3MNKM"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<div id="content">
		<header>
			<div id="inhoud">
				<div id="cursistenmodule">
				<img src="<?php echo base_url('assets/images/cursistenmodule-logo.png') ?>" class="logo" alt="localhost Cursistenmodule" />
				<?php if ($pagina != 'workshops'){ ?>
					<nav>
						<ul>
							<li><?php if(!empty($workshop)) { ?><a href="<?php echo site_url('cursistenmodule') ?>" class="workshop-titel" title="<?php echo $workshop->workshop_titel ?>"><?php echo $workshop->workshop_titel ?></a><span></span><?php } ?></li>
						</ul>
					</nav>
				<?php } ?>
				</div>
				<nav>
					<ul>
						<?php if ($this->session->userdata('kennismakingsworkshop_acc') == false) : ?>
							<li><a href="<?php echo site_url('cursistenmodule/workshops') ?>" title="Workshops" <?php if ($pagina == 'workshops') echo 'class="active"'; ?>>Jouw workshops</a><span></span></li>
						<?php endif; ?>
						<?php if ($this->session->userdata('ws_gevolgd_producten_aantal') > 0) : ?>
							<?php if ($this->session->userdata('kennismakingsworkshop_acc') == false && $this->session->userdata('gebruiker_rechten') != 'dummy') : ?>
								<span></span></li>
								<li><a href="<?php echo site_url('cursistenmodule/producten') ?>" title="Producten" <?php if ($pagina == 'producten') echo 'class="active"'; ?>>Producten</a><span></span></li>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ($this->session->userdata('gebruiker_rechten') != 'kandidaat') { ?>
										<li>
						<!-- Drop down menu -->
						<div class="dropdown">
								<a class="dropbtn" title="Lessen" href="<?php echo site_url('cursistenmodule/lessen') ?>" title="Lessen" <?php if ($pagina == 'lessen') echo 'class="active"'; ?>>
								Lesoverzicht<span></span>
								</a>

								<div class="dropdown-content">
										<?php foreach($workshops as $workshop): ?>
												<a href="<?php echo base_url('cursistenmodule/workshop/'.$workshop->workshop_ID .'/1') ?>" title="<?php echo $workshop->workshop_titel ?>"><?php echo $workshop->workshop_titel ?></a>
										<?php endforeach; ?>
								</div>
							</div>
						<?php } ?>
						<li>
							<!-- Drop down menu -->
							<div class="dropdown">
								<a class="dropbtn" title="Profiel"><?php echo $this->session->userdata('gebruiker_voornaam') ?>
									<span id="profile" <?php if ($this->session->userdata('nieuwe_berichten') && $this->session->userdata('nieuwe_berichten') > 0) : ?>class="has-badge"<?php endif; ?>></span>
								</a>

								<div class="dropdown-content">
									<?php if ($this->session->userdata('kennismakingsworkshop_acc') == false) : ?>
										<a href="<?php echo site_url('cursistenmodule/workshops') ?>" title="Workshops" <?php if ($pagina == 'workshops') echo 'class="active"'; ?>>Jouw workshops</a>
									<?php endif; ?>

									<?php if ($this->session->userdata('gebruiker_rechten') != 'dummy') : ?>
										<a href="<?php echo site_url('cursistenmodule/berichten') ?>" title="Berichten" <?php if ($pagina == 'berichten') echo 'class="active"'; ?>>Berichten <?php if ($this->session->userdata('nieuwe_berichten') && $this->session->userdata('nieuwe_berichten') > 0) : ?><span title="<?php echo $this->session->userdata('nieuwe_berichten') ?> nieuwe berichten" id="nieuw"><?php echo $this->session->userdata('nieuwe_berichten') ?></span><?php endif; ?></a>
									<?php endif; ?>

									<?php if ($this->session->userdata('gebruiker_rechten') != 'dummy') : ?>
										<a href="<?php echo site_url('cursistenmodule/resultaten') ?>" title="Resultaten" <?php if ($pagina == 'resultaten') echo 'class="active"'; ?>>Resultaten</a>
									<?php endif; ?>

									<a href="<?php echo site_url('cursistenmodule/vragen') ?>" title="Vragen" <?php if ($pagina == 'vragen') echo 'class="active"'; ?>>Vragen</a>
									<a href="<?php echo site_url('cursistenmodule/profiel') ?>">Profiel</a>
									<?php if ($this->session->userdata('beheerder_ID')) : ?>
										<a href="<?php echo site_url('cms/deelnemers/uitloggen/' . $this->session->userdata('gebruiker_ID')) ?>" title="Naar CMS">Naar CMS</a>
									<?php else : ?>
										<a href="<?php echo site_url('uitloggen') ?>" title="Uitloggen">Uitloggen</a>
									<?php endif; ?>
								</div>
							</div>
						</li>
					</ul>
				</nav>
			</div>
		</header>
		<div id="content">
			<div id="inhoud">
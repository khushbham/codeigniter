<?php

$url = explode('/', $_SERVER['REQUEST_URI']);

if (isset($url[2])) $pagina = $url[2];
else $pagina = '';

if (!isset($_GET['accept-cookies'])) {
	setcookie('accept-cookies', 'true', time() + 2592000);
}

$site_titel = 'localhost';
$page_url = '';
$page_title = 'Extra opdrachten - localhost';
if (isset($opdracht_titel) && !empty($opdracht_titel)) $page_title = $opdracht_titel;

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
	<title><?php echo $page_title ?></title>
	<meta name="robots" content="noindex">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/cursistenmodule.css?v23122020') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/css/opdrachten.css?v21012021') ?>" rel="stylesheet" />
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
					<a href="<?php echo site_url('opdrachten/') ?>">
						<img src="<?php echo base_url('assets/images/cursistenmodule-logo.png') ?>" class="logo" alt="localhost Cursistenmodule" />
					</a>
				</div>
				<nav>
					<ul>
						<?php if($this->session->userdata('beheerder_rechten') == 'admin'): ?>
							<li><a href="<?php echo site_url('opdrachten/opdrachten') ?>" title="Opdrachten" <?php if ($pagina == 'opdrachten') echo 'class="active"'; ?>>Experimenten</a><span></span></li>
							<li><a href="<?php echo site_url('opdrachten/inzendingen') ?>" title="Inzendingen" <?php if ($pagina == 'inzendingen') echo 'class="active"'; ?>>Inzendingen</a><span></span></li>
						<?php endif; ?>
						<li>
							<!-- Drop down menu -->
							<div class="dropdown">
								<a class="dropbtn" title="Profiel">
									<?php if ($this->session->userdata('gebruiker_voornaam')) : ?>
										<?php echo $this->session->userdata('gebruiker_voornaam') ?>
									<?php elseif ($this->session->userdata('beheerder_ID')) : ?>
										<?php echo $this->session->userdata('beheerder_voornaam') ?>
									<?php endif; ?>
									<span id="profile" <?php if ($this->session->userdata('nieuwe_berichten') && $this->session->userdata('nieuwe_berichten') > 0) : ?>class="has-badge"<?php endif; ?>></span>
								</a>

								<div class="dropdown-content">
									<a href="<?php echo site_url('opdrachten/profiel') ?>">Profiel</a>
									<?php if ($this->session->userdata('beheerder_ID') && $this->session->userdata('gebruiker_ID')) : ?>
										<a href="<?php echo site_url('cms/deelnemers/uitloggen/' . $this->session->userdata('gebruiker_ID')) ?>" title="Naar CMS">Naar CMS</a>
									<?php elseif ($this->session->userdata('beheerder_ID')) : ?>
										<a href="<?php echo site_url('cms') ?>" title="Naar CMS">Naar CMS</a>
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
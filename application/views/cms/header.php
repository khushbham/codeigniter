<?php

$url = explode('/', $_SERVER['REQUEST_URI']);
$pagina = '';
if(isset($url[2])) $pagina = $url[2];
$site_titel = 'localhost';
$page_description = '';
$page_keywords = '';
$page_url = '';
$page_title = '';

?>
<?php if($this->session->userdata('beheerder_rechten') != 'docent'): ?>
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
	<meta charset="utf-8" />
	<title>CMS localhost</title>
	<meta name="viewport" content="width=1100" />
	<link href='//fonts.googleapis.com/css?family=Ruda:400,700,900' rel='stylesheet' type='text/css' />
	<link href="<?php echo base_url('assets/css/cms.css?v24022021') ?>" rel="stylesheet" />
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


<div id="wrapper">
	<header>
		<div class="wrapper clearfix">
			<nav>
				<ul>
					<li><a href="<?php echo site_url('cms') ?>" title="Dashboard" <?php if($pagina == '') echo "class='active'"; ?>>Dashboard</a><span>|</span></li>
					<?php if($this->session->userdata('beheerder_rechten') == 'contentmanager'): ?>
					<li><a href="<?php echo site_url('cms/media') ?>" title="Media" <?php if($pagina == 'media') echo "class='active'"; ?>>Media</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/blog') ?>" title="Blog" <?php if($pagina == 'blog') echo "class='active'"; ?>>Blog</a><span>|</span></li>
					<?php else: ?>
					<li><a href="<?php echo site_url('cms/aanmeldingen') ?>" title="Aanmeldingen" <?php if($pagina == 'aanmeldingen') echo "class='active'"; ?>>Aanmeldingen</a><span>|</span></li>
					<li><a href="<?php echo site_url('cms/afspraken') ?>" title="Afspraken" <?php if($pagina == 'afspraken') echo "class='active'"; ?>>Afspraken</a><?php if($this->session->userdata('nieuwe_afspraken') && $this->session->userdata('nieuwe_afspraken') > 0): ?><a href="<?php echo site_url('cms/afspraken') ?>" id="nieuw"><?php echo $this->session->userdata('nieuwe_afspraken') ?></a><?php endif; ?><span>|</span></li>
					<li><a href="<?php echo site_url('cms/bestellingen') ?>" title="Bestellingen" <?php if($pagina == 'bestellingen') echo "class='active'"; ?>>Bestellingen</a><?php if($this->session->userdata('nieuwe_bestellingen') && $this->session->userdata('nieuwe_bestellingen') > 0): ?><a href="<?php echo site_url('cms/bestellingen') ?>" id="nieuw"><?php echo $this->session->userdata('nieuwe_bestellingen') ?></a><?php endif; ?><span>|</span></li>
					<li><a href="<?php echo site_url('cms/groepen') ?>" title="Groepen" <?php if($pagina == 'groepen') echo "class='active'"; ?>>Groepen</a><span>|</span></li>
					<li><a href="<?php echo site_url('cms/deelnemers') ?>" title="Deelnemers" <?php if($pagina == 'deelnemers') echo "class='active'"; ?>>Deelnemers</a><span>|</span></li>
					<li><a href="<?php echo site_url('cms/beoordelingen') ?>" title="beoordelingen" <?php if($pagina == 'beoordelingen') echo "class='active'"; ?>>Beoordelingen</a><span>|</span></li>
					<?php if($this->session->userdata('beheerder_rechten') == 'admin'): ?>
						<li><a href="<?php echo site_url('cms/huiswerk') ?>" title="Opdrachten" <?php if($pagina == 'huiswerk') echo "class='active'"; ?>>Opdrachten</a><?php if($this->session->userdata('nieuw_huiswerk') && $this->session->userdata('nieuw_huiswerk') > 0): ?><a href="<?php echo site_url('cms/huiswerk') ?>" id="nieuw"><?php echo $this->session->userdata('nieuw_huiswerk') ?></a><?php endif; ?><span>|</span></li>
						<li><a href="<?php echo site_url('cms/berichten') ?>" title="Berichten" <?php if($pagina == 'berichten') echo "class='active'"; ?>>Berichten</a><?php if($this->session->userdata('nieuwe_berichten') && $this->session->userdata('nieuwe_berichten') > 0): ?><a href="<?php echo site_url('cms/berichten') ?>" id="nieuw"><?php echo $this->session->userdata('nieuwe_berichten') ?></a><?php endif; ?><span>|</span></li>
						<li><a href="<?php echo site_url('cms/workshops') ?>" title="Workshops" <?php if($pagina == 'workshops') echo "class='active'"; ?>>Workshops</a><span>|</span></li>
						<li><a href="<?php echo site_url('/opdrachten') ?>" title="Experimenten" <?php if($pagina == 'experimenten') echo "class='active'"; ?>>Experimenten</a><span>|</span></li>
					<?php endif; ?>
					<?php if($this->session->userdata('beheerder_rechten') == 'support'): ?>
                        <li><a href="<?php echo site_url('cms/berichten') ?>" title="Berichten" <?php if($pagina == 'berichten') echo "class='active'"; ?>>Berichten</a><?php if($this->session->userdata('nieuwe_berichten') && $this->session->userdata('nieuwe_berichten') > 0): ?><a href="<?php echo site_url('cms/berichten') ?>" id="nieuw"><?php echo $this->session->userdata('nieuwe_berichten') ?></a><?php endif; ?><span>|</span></li>
                    <?php endif; ?>
                    <?php if($this->session->userdata('beheerder_rechten') == 'opleidingsmedewerker'): ?>
                        <li><a href="<?php echo site_url('cms/huiswerk') ?>" title="Opdrachten" <?php if($pagina == 'huiswerk') echo "class='active'"; ?>>Opdrachten</a><?php if($this->session->userdata('nieuw_huiswerk') && $this->session->userdata('nieuw_huiswerk') > 0): ?><a href="<?php echo site_url('cms/huiswerk') ?>" id="nieuw"><?php echo $this->session->userdata('nieuw_huiswerk') ?></a><?php endif; ?><span>|</span></li>
                        <li><a href="<?php echo site_url('cms/berichten') ?>" title="Berichten" <?php if($pagina == 'berichten') echo "class='active'"; ?>>Berichten</a><?php if($this->session->userdata('nieuwe_berichten') && $this->session->userdata('nieuwe_berichten') > 0): ?><a href="<?php echo site_url('cms/berichten') ?>" id="nieuw"><?php echo $this->session->userdata('nieuwe_berichten') ?></a><?php endif; ?><span>|</span></li>
                        <li><a href="<?php echo site_url('cms/notities') ?>" title="Notities" <?php if($pagina == 'notities') echo "class='active'"; ?>>Notities</a><span>|</span></li>
                    <?php endif; ?>
					<li><a href="<?php echo site_url('cms/lessen') ?>" title="Lessen" <?php if($pagina == 'lessen') echo "class='active'"; ?>>Lessen</a><span>|</span></li>
					<?php if($this->session->userdata('beheerder_rechten') == 'admin'): ?>
						<li><a href="<?php echo site_url('cms/producten') ?>" title="Producten" <?php if($pagina == 'producten') echo "class='active'"; ?>>Producten</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/paginas') ?>" title="Pagina's" <?php if($pagina == 'paginas') echo "class='active'"; ?>>Pagina's</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/gegevens') ?>" title="Gegevens" <?php if($pagina == 'gegevens') echo "class='active'"; ?>>Gegevens</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/nieuws') ?>" title="Nieuws" <?php if($pagina == 'nieuws') echo "class='active'"; ?>>Nieuws</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/reacties') ?>" title="Reacties" <?php if($pagina == 'reacties') echo "class='active'"; ?>>Reacties</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/docenten') ?>" title="Docenten" <?php if($pagina == 'docenten') echo "class='active'"; ?>>Docenten</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/notities') ?>" title="Notities" <?php if($pagina == 'notities') echo "class='active'"; ?>>Notities</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/vragen') ?>" title="Vragen" <?php if($pagina == 'vragen') echo "class='active'"; ?>>Vragen</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/media') ?>" title="Media" <?php if($pagina == 'media') echo "class='active'"; ?>>Media</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/beheerders') ?>" title="Beheerders" <?php if($pagina == 'beheerders') echo "class='active'"; ?>>Beheerders</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/uitnodigingen') ?>" title="Uitnodigingen" <?php if($pagina == 'uitnodigingen') echo "class='active'"; ?>>Uitnodigingen</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/kortingscodes') ?>" title="Verkoop" <?php if($pagina == 'kortingscodes') echo "class='active'"; ?>>Verkoop</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/Annuleringen') ?>" title="Annuleringen" <?php if($pagina == 'Annuleringen') echo "class='active'"; ?>>Annulering</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/blog') ?>" title="Blog" <?php if($pagina == 'blog') echo "class='active'"; ?>>Blog</a><span>|</span></li>
					<?php endif; ?>
					<?php endif; ?>
					<li><a href="<?php echo site_url('cms/profiel') ?>" title="Profiel" <?php if($pagina == 'profiel') echo "class='active'"; ?>>Profiel</a><span>|</span></li>
					<li><a href="<?php echo site_url('uitloggen') ?>" title="Uitloggen" <?php if($pagina == 'uitloggen') echo "class='active'"; ?>>Uitloggen</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<div id="content">
		<div id="inhoud">
<?php endif; ?>

<?php if($this->session->userdata('beheerder_rechten') == 'docent'): ?>
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

		<meta charset="utf-8" />
		<title>Docent localhost</title>
		<meta name="description" content="<?php echo $page_description ?>" />
		<meta name="keywords" content="<?php echo $page_keywords ?>" />
		<meta name="viewport" content="width=1100" />
		<meta property="og:url" content="<?php echo $page_url ?>" />
		<meta property="og:title" content="<?php echo $page_title ?>" />
		<meta property="og:site_name" content="<?php echo $site_titel ?>" />
		<meta property="og:description" content="<?php echo $page_description ?>" />
		<link href='http://fonts.googleapis.com/css?family=Ruda:400,700,900' rel='stylesheet' type='text/css' />
		<link href="<?php echo base_url('assets/css/cursistenmodule.css?v23122020') ?>" rel="stylesheet" />
		<link href="<?php echo base_url('assets/images/favicon.ico') ?>" rel="shortcut icon" />
		<link href="<?php echo base_url('assets/images/localhost-icon-57.png') ?>" rel="apple-touch-icon-precomposed" sizes="57x57" />
		<link href="<?php echo base_url('assets/images/localhost-icon-72.png') ?>" rel="apple-touch-icon-precomposed" sizes="72x72" />
		<link href="<?php echo base_url('assets/images/localhost-icon-114.png') ?>" rel="apple-touch-icon-precomposed" sizes="114x114" />
		<link href="<?php echo base_url('assets/images/localhost-icon-144.png') ?>" rel="apple-touch-icon-precomposed" sizes="144x144" />
		<script src="<?php echo base_url('assets/js/modernizr.custom.45618.js') ?>"></script>
	</head>
<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KL3MNKM"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->


	<div id="wrapper">
		<header>
			<div class="wrapper clearfix">
				<div id="cursistenmodule">
					<h1>Docent: <a href="<?php echo site_url('cms/profiel') ?>" title="Profiel"><?php echo $this->session->userdata('beheerder_naam') ?></a></h1>
				</div>
				<nav>
					<ul>
						<li><a href="<?php echo site_url('cms') ?>" title="Dashboard" <?php if($pagina == '') echo 'class="active"'; ?>>Dashboard</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/huiswerk') ?>" title="Workshops" <?php if($pagina == 'huiswerk') echo 'class="active"'; ?>>Opdrachten</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/lessen') ?>" title="Lessen" <?php if($pagina == 'lessen') echo 'class="active"'; ?>>Lessen</a><span>|</span></li>
						<li><a href="<?php echo site_url('cms/berichten') ?>" title="Berichten" <?php if($pagina == 'berichten') echo 'class="active"'; ?>>Berichten</a><?php if($this->session->userdata('nieuwe_berichten') && $this->session->userdata('nieuwe_berichten') > 0): ?><a href="<?php echo site_url('cms/berichten') ?>" title="<?php echo $this->session->userdata('nieuwe_berichten') ?> nieuwe berichten" id="nieuw"><?php echo $this->session->userdata('nieuwe_berichten') ?></a><?php endif; ?><span>|</span></li>
						<li><a href="<?php echo site_url('cms/vragen/vragen_docent') ?>" title="Vragen" <?php if($pagina == 'vragen_docent') echo 'class="active"'; ?>>Vragen</a>
							<?php if($this->session->userdata('ws_gevolgd_producten_aantal') > 0): ?>
							<span>|</span></li>
						<li><a href="<?php echo site_url('cms/producten') ?>" title="Producten" <?php if($pagina == 'producten') echo 'class="active"'; ?>>Producten</a><span>|</span></li>
						<?php endif; ?>
						<li></a><span>|</span><a href="<?php echo site_url('cms/bestellingen') ?>" title="Bestellingen" <?php if($pagina == 'bestellingen') echo "class='active'"; ?>>Bestellingen</a><?php if($this->session->userdata('nieuwe_bestellingen') && $this->session->userdata('nieuwe_bestellingen') > 0): ?><a href="<?php echo site_url('cms/bestellingen') ?>" id="nieuw"><?php echo $this->session->userdata('nieuwe_bestellingen') ?></a><?php endif; ?><span>|</span></li>
						<li id="uitloggen"><a href="<?php echo site_url('cms/profiel') ?>" title="Profiel" <?php if($pagina == 'profiel') echo 'class="active"'; ?>>Profiel</a><span>|</span></li>
						<?php if($this->session->userdata('beheerder_ID') && $this->session->userdata('beheerder_rechten') == 'admin'): ?>
							<li><a href="<?php echo site_url('cms/deelnemers/uitloggen/'.$this->session->userdata('gebruiker_ID')) ?>" title="Naar CMS">Naar CMS</a></li>
						<?php else: ?>
							<li><a href="<?php echo site_url('uitloggen') ?>" title="Uitloggen">Uitloggen</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</div>
		</header>
		<div id="content">
			<div id="inhoud">

<?php endif; ?>

<?php

$url = explode('/', $_SERVER['REQUEST_URI']);
$pagina = $url[1];
$page_title = 'localhost';
$page_description = '';
$page_image = "http://localhost/media/afbeeldingen/origineel/localhost.jpg";
$page_type = "website";

if (isset($meta_title) && !empty($meta_title)) $page_title = $meta_title;
if (isset($meta_description) && !empty($meta_description)) $page_description = $meta_description;
// Custom OG
if(isset($og_image) && !empty($og_image)) $page_image = $og_image;
if(isset($og_type) && !empty($og_type)) $page_type = $og_type;

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

	<meta charset="utf-8" />
	<title><?php echo $page_title ?></title>
	<meta name="description" content="<?php echo $page_description ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta property="og:title" content="<?php echo $page_title ?>" />
	<meta property="og:site_name" content="localhost" />
	<meta property="og:image" content="<?php echo $page_image; ?>">
	<meta property="og:image:alt" content="Een stemacteur spreekt in een microfoon tijdens de opleiding van localhost">
	<meta property="og:url" content="<?php echo current_url(); ?>">
	<meta property="og:type" content="<?php echo $page_type; ?>">
	<meta property="og:description" content="<?php echo $page_description ?>" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/website.css?v05112020') ?>" rel="stylesheet" />
	<link href="<?php echo base_url('assets/images/favicon.ico') ?>" rel="shortcut icon" />
	<link href="<?php echo base_url('assets/images/localhost-icon-57.png') ?>" rel="apple-touch-icon-precomposed" sizes="57x57" />
	<link href="<?php echo base_url('assets/images/localhost-icon-72.png') ?>" rel="apple-touch-icon-precomposed" sizes="72x72" />
	<link href="<?php echo base_url('assets/images/localhost-icon-114.png') ?>" rel="apple-touch-icon-precomposed" sizes="114x114" />
	<link href="<?php echo base_url('assets/images/localhost-icon-144.png') ?>" rel="apple-touch-icon-precomposed" sizes="144x144" />
	<script src="<?php echo base_url('assets/js/modernizr.custom.45618.js') ?>"></script>

	<script id="mcjs">
		! function(c, h, i, m, p) {
			m = c.createElement(h), p = c.getElementsByTagName(h)[0], m.async = 1, m.src = i, p.parentNode.insertBefore(m, p)
		}(document, "script", "https://chimpstatic.com/mcjs-connected/js/users/d21c67b4cef8245552a13406b/349bd20d9523b3676caab2e89.js");
	</script>

	<?php if (!empty($workshop)) { ?>
		<?php if ($workshop->workshop_zichtbaar_publiek == 0 && $workshop->workshop_zichtbaar_cursist == 0) { ?>
			<meta name="robots" content="noindex">
		<?php } ?>
	<?php } ?>
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KL3MNKM"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<header class="header">
		<div class="wrapper header--flex">
			<div class="logo">
				<a href="<?php echo base_url('') ?>" title="Home"><img src="<?php echo base_url('assets/images/logo-localhost.svg') ?>" width="247" height="47" /></a>
			</div>
			<nav class="main-menu">
				<ul>
					<li><a href="<?php echo base_url('') ?>" title="Home" <?php if ($pagina == '') echo 'class="active"' ?>>Home</a></li>
					<li><a href="<?php echo base_url('workshops') ?>" title="Workshops" <?php if ($pagina == 'workshops' || $pagina == 'aanmelden') echo 'class="active"' ?>>Workshops</a></li>
					<li><a href="<?php echo base_url('over-ons') ?>" title="Over ons" <?php if ($pagina == 'over-ons') echo 'class="active"' ?>>Over ons</a></li>
					<li><a href="<?php echo base_url('contact') ?>" title="Contact" <?php if ($pagina == 'contact') echo 'class="active"' ?>>Contact</a></li>
					<li><a href="<?php echo base_url('blog') ?>" title="Reacties" <?php if ($pagina == 'blog') echo 'class="active"' ?>>Blog</a></li>
				</ul>
			</nav>
			<?php if ($this->session->userdata('gebruiker_ID') || $this->session->userdata('beheerder_ID')) : ?>
				<nav class="authenticated-menu">
					<ul>
						<?php if ($this->session->userdata('gebruiker_ID')) : ?>
							<li><a href="<?php echo site_url('cursistenmodule') ?>" title="Naar cursistenmodule">Naar cursistenmodule</a></li>
						<?php else : ?>
							<li><a href="<?php echo site_url('cms') ?>" title="Naar CMS">Naar CMS</a></li>
						<?php endif; ?>

						<li><a href="<?php echo site_url('uitloggen') ?>" title="Uitloggen">Uitloggen</a></li>
					</ul>
				</nav>
			<?php else : ?>
				<section class="authenticate">
					<form method="post" action="<?php echo current_url() ?>">
						<div class="authenticate-form">
							<input type="hidden" name="inloggen_token" value="<?php echo $_SESSION['inloggen_token'] ?>" />
							<div class="input-wrap">
								<input type="text" name="inloggen_emailadres" id="inloggen_emailadres" placeholder="E-mailadres" value="<?php echo $_SESSION['inloggen_emailadres'] ?>" autocapitalize="off" />
								<input type="password" name="inloggen_wachtwoord" id="inloggen_wachtwoord" placeholder="Wachtwoord" />
								<div class="authenticate-feedback">
									<p id="feedback"><?php echo $_SESSION['inloggen_feedback'] ?></p>
									<p id="wachtwoord"><a href="<?php echo base_url('wachtwoord') ?>" title="Wachtwoord vergeten?">Wachtwoord vergeten?</a></p>
								</div>
							</div>
							<input type="submit" name="inloggen" value="Inloggen" />
						</div>
					</form>
				</section>
			<?php endif; ?>
		</div>
	</header>
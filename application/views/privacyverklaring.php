<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title"><?php if(!empty($content->pagina_titel)) echo $content->pagina_titel ?></h1>
	</div>
</section>

<section class="page">
	<div class="wrapper">
		<h2><?php if (!empty($content->pagina_inleiding)) echo $content->pagina_inleiding ?></h2>
		<?php if (!empty($content->pagina_tekst)) echo $content->pagina_tekst ?></p>
</section>
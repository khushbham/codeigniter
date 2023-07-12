<section class="hero" class="clearfix">
	<div class="wrapper">
		<h1 class="hero-title"><?php if (!empty($content->pagina_titel)) echo $content->pagina_titel ?></h1>
	</div>
</section>

<section class="page">
	<div class="wrapper">
		<h2><?php if (!empty($content->pagina_inleiding)) echo $content->pagina_inleiding ?></h2>

		<p><?php if (!empty($content->pagina_tekst)) echo nl2br($content->pagina_tekst) ?></p>

		<?php if ($this->session->userdata('gebruiker_ID') || $this->session->userdata('beheerder_ID')) { ?>
			<div style="padding:56.25% 0 0 0;position:relative; margin-bottom: 1em;"><iframe src=<?php echo "https://player.vimeo.com/video/" . $content->video_url ?> frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe></div>
			<?php if(!empty($content->chat_url)) { ?>
				<div id="vimeo-chat">
					<button id="toggle-vimeo-chat" class="button-orange">
						<span class="closed">Open </span>
						<span class="open">Verberg </span>
						live chat van Revue des Voix
					</button>
					<iframe id="vimeo-chat-iframe" src=<?php echo "https://vimeo.com/live-chat/" . $content->chat_url ?> height="600" scrolling="no" width="100%" frameborder="0" allow="autoplay"></iframe>
				</div>
			<?php } ?>
		<?php } else { ?>
			<link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
			<style type="text/css">
				#mc_embed_signup {
					background: #fff;
					clear: left;
					font: 14px Helvetica, Arial, sans-serif;
				}
			</style>
			<div id="mc_embed_signup">
				<h2>Laat hieronder je naam en e-mailadres achter en kijk direct naar Revue des Voix!</h2>
				<form action="https://localhost.us7.list-manage.com/subscribe/post-json?u=d21c67b4cef8245552a13406b&amp;id=76cbccab02&c=?" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					<div id="mc_embed_signup_scroll">
						<div class="mc-field-group">
							<label for="mce-EMAIL">E-mailadres <span class="asterisk">*</span>
							</label>
							<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
						</div>
						<div class="mc-field-group">
							<label for="mce-NAME">Naam <span class="asterisk">*</label>
							<input type="text" value="" name="NAME" class="required" id="mce-NAME">
						</div>
						<div id="mce-responses" class="clear">
							<div class="response" id="mce-error-response" style="display:none"></div>
							<div class="response" id="mce-success-response" style="display:none"></div>
						</div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_d21c67b4cef8245552a13406b_76cbccab02" tabindex="-1" value=""></div>
						<div class="clear"><input type="submit" value="Bekijk Revue de Voix!" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					</div>
				</form>
			</div>


			<div id="vimeoAfterSubmit" style="padding:56.25% 0 0 0;position:relative; display:none; margin-bottom: 1em;"><iframe src=<?php echo "https://player.vimeo.com/video/" . $content->video_url ?> frameborder="0" allow="autoplay; fullscreen" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe></div>
			<?php if(!empty($content->chat_url)) { ?>
			<div id="vimeo-chat" style="display:none;">
				<button id="toggle-vimeo-chat" class="button-orange">
					<span class="closed">Open </span>
					<span class="open">Verberg </span>
					live chat van Revue des Voix
				</button>
				<iframe id="vimeo-chat-iframe" src=<?php echo "https://vimeo.com/live-chat/" . $content->chat_url ?> height="600" scrolling="no" width="100%" frameborder="0" allow="autoplay"></iframe>
			</div>
				<?php } ?>
		<?php } ?>
	</div>
</section>
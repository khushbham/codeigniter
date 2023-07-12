		</div>
		</div>
		</div>
		<footer class="footer">
			<div class="wrapper">
				<div class="contact-info">
					<a href="mailto:<?php echo $gegevens[0]->gegeven_waarde ?>" title="E-mail <?php echo $gegevens[0]->gegeven_waarde ?>"><?php echo $gegevens[0]->gegeven_waarde ?></a>
					<span class="hide-mobile"> | </span>
					<a href="tel:<?php echo str_replace('-', '', str_replace(' ', '', $gegevens[1]->gegeven_waarde)) ?>" class="telefoonnummer-localhost" title="Bel <?php echo $gegevens[1]->gegeven_waarde ?>"><?php echo $gegevens[1]->gegeven_waarde ?></a>
					<div class="social">
						<a href="<?php echo $gegevens[2]->gegeven_waarde ?>" title="Volg ons via Twitter" target="_blank" id="twitter" class="social-icon">Twitter</a>
						<a href="<?php echo $gegevens[3]->gegeven_waarde ?>" title="Volg ons via Facebook" target="_blank" id="facebook" class="social-icon">Facebook</a></p>
					</div>
				</div>
				<div id="trust-pilot">
					<script type="text/javascript" src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
					<div class="trustpilot-widget" data-locale="nl-NL" data-template-id="53aa8807dec7e10d38f59f32" data-businessunit-id="573b28070000ff00058cfc45" data-style-height="150px" data-style-width="100%" data-theme="light">
						<a href="https://nl.trustpilot.com/review/localhost" target="_blank">Trustpilot</a>
					</div>
				</div>
				<nav class="footer-menu"><a href="<?php echo base_url('') ?>" title="Home">Home</a> | <a href="<?php echo base_url('workshops') ?>" title="Workshops">Workshops</a> | <a href="<?php echo base_url('revuedesvoix') ?>" title="Workshops">Revue des Voix</a> | <a href="<?php echo base_url('over-ons') ?>" title="Over ons">Over ons</a> | <a href="<?php echo base_url('reacties') ?>" title="Reacties">Reacties</a> | <a href="<?php echo base_url('nieuws') ?>" title="Nieuws">Nieuws</a> | <a href="<?php echo base_url('contact/vragen') ?>" title="Vragen">Vragen</a></nav>

				<div class="iamstudios">
					<a href="https://www.localhost/privacyverklaring" class="privacy_link">Privacyverklaring</a>
					<p>localhost is een geregistreerde merknaam van <a href="http://iam-studios.nl" title="Bezoek de website van iam studios b.v." target="_blank">iam studios b.v.</a></p>
				</div>
			</div>
		</footer>

		<script src="//code.jquery.com/jquery-latest.min.js"></script>
		<script src="https://cdn.tiny.cloud/1/07vzpoc5sz168frx4rz6bqk03kgyxfem27dxqw7et95rhgg1/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
		<script src="<?php echo base_url('assets/js/audiojs/audio.min.js') ?>"></script>
		<script src="<?php echo base_url('assets/js/localhost.js?v20082020') ?>"></script>

		<script type='text/javascript'>
			(function(jQuery) {
				jQuery.extend($.validator.messages, {
					required: "Dit is een verplicht veld.",
					remote: "Controleer dit veld.",
					email: "Vul hier een geldig e-mailadres in.",
					url: "Vul hier een geldige URL in.",
					date: "Vul hier een geldige datum in.",
					dateISO: "Vul hier een geldige datum in (ISO-formaat).",
					number: "Vul hier een geldig getal in.",
					digits: "Vul hier alleen getallen in.",
					creditcard: "Vul hier een geldig creditcardnummer in.",
					equalTo: "Vul hier dezelfde waarde in.",
					accept: "Vul hier een waarde in met een geldige extensie.",
					maxlength: $.validator.format("Vul hier maximaal {0} tekens in."),
					minlength: $.validator.format("Vul hier minimaal {0} tekens in."),
					rangelength: $.validator.format("Vul hier een waarde in van minimaal {0} en maximaal {1} tekens."),
					range: $.validator.format("Vul hier een waarde in van minimaal {0} en maximaal {1}."),
					max: $.validator.format("Vul hier een waarde in kleiner dan of gelijk aan {0}."),
					min: $.validator.format("Vul hier een waarde in groter dan of gelijk aan {0}.")
				});
			}(jQuery));
			// bind 'myForm' and provide a simple callback function
			const options = {
				target: '#mc-embedded-subscribe-form',
				url: 'https://localhost.us7.list-manage.com/subscribe/post-json?u=d21c67b4cef8245552a13406b&amp;id=76cbccab02&c=?',
				type: 'GET',
				dataType: 'jsonp',
				contentType: "application/json; charset=utf-8",
				success: showResponse
			}
			$("#mc-embedded-subscribe-form").validate({
				submitHandler: function(form) {
					$("#mc_embed_signup").hide();
					$("#vimeoAfterSubmit").show();
					$("#vimeo-chat").show();
					$(form).ajaxSubmit(options);
					return false;
				}
			});

			function showResponse(responseText, statusText, xhr, $form) {
				console.log('status: ' + statusText + '\n\nresponseText: \n' + responseText +
					'\n\nThe output div should have already been updated with the responseText.');
			}
		</script>

		<!--Start of Tawk.to Script--->
		<script type="text/javascript">
			var Tawk_API = Tawk_API || {},
				Tawk_LoadStart = new Date();
			(function() {
				var s1 = document.createElement("script"),
					s0 = document.getElementsByTagName("script")[0];
				s1.async = true;
				s1.src = 'https://embed.tawk.to/5731b3c0cba28dd313b3821d/default';
				s1.charset = 'UTF-8';
				s1.setAttribute('crossorigin', '*');
				s0.parentNode.insertBefore(s1, s0);
			})();
		</script>
		<!--End of Tawk.to Script -->

		</body>

		</html>
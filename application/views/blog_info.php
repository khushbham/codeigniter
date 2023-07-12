<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>


<section class="hero" class="clearfix">
    <div class="wrapper">
        <h1 class="hero-title"><?php echo $blog->blog_titel ?></h1>
        <?php echo '<p><i>' . date('d-m-Y', strtotime($blog->blog_datum)) . '</i></p>' ?>
    </div>
</section>

<section class="blogpost">
    <div class="wrapper">
        <div class="blogpost-content">

            <?php echo $blog->blog_bericht ?>
            <p><a href="<?php echo base_url('blog') ?>" title="Bekijk alle artikelen" class="meer">> Bekijk alle artikelen</a></p>
        </div>
        <div class="blogpost-sidebar">
            <p>Wil je op de hoogte worden gebracht wanneer er een nieuw artikel online staat? Vul dan hier je e-mailadres in!</p>
            <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
            <style type="text/css">
                #mc_embed_signup {
                    clear: left;
                }

                #mc_embed_signup #mc-embedded-subscribe {
                    background-color: #ff9c00;
                }

                #mc_embed_signup #mc-embedded-subscribe:hover {
                    background-color: #ff9c00;
                }
            </style>
            <div id="mc_embed_signup">
                <form action="//localhost.us7.list-manage.com/subscribe/post?u=d21c67b4cef8245552a13406b&amp;id=76cbccab02" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                        <div class="mc-field-group">
                            <label for="mce-EMAIL">E-mailadres </label>
                            <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                        </div>
                        <input type="hidden" value="blog" name="SOORT" class="required" id="mce-SOORT">
                        <input type="hidden" value="" name="DATUM" class="" id="mce-DATUM">
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div> <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_d21c67b4cef8245552a13406b_e19a6fa3e4" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Houd mij op de hoogte" name="subscribe" id="mc-embedded-subscribe" class="button button--orange"></div>
                    </div>
                </form>
            </div>
            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
            <script type='text/javascript'>
                (function($) {
                    window.fnames = new Array();
                    window.ftypes = new Array();
                    fnames[0] = 'EMAIL';
                    ftypes[0] = 'email';
                    fnames[1] = 'WORKSHOP';
                    ftypes[1] = 'text';
                }(jQuery));
                var $mcj = jQuery.noConflict(true);
            </script>
            <!--End mc_embed_signup-->
            <div id="share">
                <div class="fb-share-button" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Flocalhost%2F&amp;src=sdkpreparse">Delen</a></div>
                <div class="fb-like" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
                <div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a></div>
                <script>
                    ! function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0],
                            p = /^http:/.test(d.location) ? 'http' : 'https';
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = p + '://platform.twitter.com/widgets.js';
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, 'script', 'twitter-wjs');
                </script>
            </div>
        </div>

    </div>
</section>
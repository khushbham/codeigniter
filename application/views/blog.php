<section class="hero" class="clearfix">
    <div class="wrapper">
        <h1 class="hero-title">localhost blog</h1>
    </div>
</section>

<section class="blog">
    <div class="wrapper">
        <?php if (sizeof($uitgelichte_blogs) > 0) : ?>
            <h2 class="hero-subtitle">Uitgelichte artikelen</h2>
            <?php foreach ($uitgelichte_blogs as $blog) : ?>
                <article class="uitgelicht">
                    <div class="media">
                        <p>
                            <?php if ($blog->media_ID != '') : ?>
                                <?php if ($blog->media_type == 'afbeelding') : ?>
                                    <a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>"><img src="<?php echo base_url('media/afbeeldingen/thumbnail/' . $blog->media_src) ?>" alt="<?php echo $blog->media_titel ?>" /></a>
                                <?php else : ?>
                                    <a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>"><img src="http://view.vzaar.com/<?php echo $blog->media_src ?>/image" alt="<?php echo $blog->media_titel ?>" /></a>
                                <?php endif; ?>
                            <?php else : ?>
                                <a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>"><img src="<?php echo base_url('assets/images/watermerk.jpg') ?>" alt="<?php echo $blog->blog_titel ?>" /></a>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="tekst">
                        <h1><a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>">'<?php echo $blog->blog_titel; ?>'</a></h1>
                        <p><?php echo substr(strip_tags($blog->blog_bericht . '...'), 0, 250); ?><br /><a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>">> Lees verder</a></p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>

        <h2 class="hero-subtitle">Artikelen</h2>
        <?php if (sizeof($blogs) > 0) : ?>
            <?php foreach ($blogs as $blog) : ?>
                <article>
                    <div class="media">
                        <p>
                            <?php if ($blog->media_ID != '') : ?>
                                <?php if ($blog->media_type == 'afbeelding') : ?>
                                    <a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>"><img src="<?php echo base_url('media/afbeeldingen/thumbnail/' . $blog->media_src) ?>" alt="<?php echo $blog->media_titel ?>" /></a>
                                <?php else : ?>
                                    <a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>"><img src="http://view.vzaar.com/<?php echo $blog->media_src ?>/image" alt="<?php echo $blog->media_titel ?>" /></a>
                                <?php endif; ?>
                            <?php else : ?>
                                <a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>"><img src="<?php echo base_url('assets/images/watermerk.jpg') ?>" alt="<?php echo $blog->blog_titel ?>" /></a>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="tekst">
                        <h1><a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>">'<?php echo $blog->blog_titel; ?>'</a></h1>
                        <p><?php echo substr(strip_tags($blog->blog_bericht . '...'), 0, 250); ?><br /><a href="<?php echo base_url('blog/' . $blog->blog_url) ?>" title="Lees verder: <?php echo $blog->blog_titel; ?>">> Lees verder</a></p>
                    </div>
                </article>
            <?php endforeach; ?>
            <?php if ($aantal_paginas > 1) : ?>
                <div id="paginanummering">
                    <p>
                        <?php for ($i = 1; $i <= $aantal_paginas; $i++) : ?>
                            <?php if ($i == $huidige_pagina) : ?>
                                <a href="<?php echo base_url('blog/pagina/' . $i) ?>" title="Pagina <?php echo $i ?>" class="active"><?php echo $i ?></a>
                            <?php else : ?>
                                <a href="<?php echo base_url('blog/pagina/' . $i) ?>" title="Pagina <?php echo $i ?>"><?php echo $i ?></a>
                            <?php endif; ?>
                            <?php if ($i < $aantal_paginas) echo ' |'; ?>
                        <?php endfor; ?>
                    </p>
                </div>
            <?php endif; ?>
        <?php else : echo 'Er zijn geen artikelen beschikbaar' ?>

        <?php endif; ?>
    </div>
</section>
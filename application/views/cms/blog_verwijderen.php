<h1>Blog verwijderen</h1>
<div id="verwijderen">
    <p>Weet u zeker dat u het artikel <strong><?php echo $item->blog_titel ?></strong> van <strong><?php echo $item->blog_deelnemer ?></strong> wilt verwijderen?</p>
    <p><a href="<?php echo base_url('cms/blog/verwijderen/'.$item->blog_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/blog/wijzigen/'.$item->blog_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>
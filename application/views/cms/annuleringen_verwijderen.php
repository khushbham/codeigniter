<h1>Couponcode verwijderen</h1>
<div id="verwijderen">
    <p>Weet u zeker dat u de couponcode <strong><?php echo $item->kortingscode ?></strong> wilt verwijderen?</p>
    <p><a href="<?php echo base_url('cms/kortingscodes/verwijderen/'.$item->kortingscode_ID.'/ja') ?>" title="Ja, verwijderen">Ja</a> / <a href="<?php echo base_url('cms/kortingscodes/wijzigen/'.$item->kortingscode_ID) ?>" title="Nee, annuleren">Nee</a></p>
</div>
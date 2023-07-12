<h1>Export geschiedenis</h1>

<div id="exports">
    <?php if(!empty($exports)) { ?>
    <table cellpadding="0" cellspacing="0" class="tabel">
        <thead>
        <tr>
            <th class="nummer">#</th>
            <th>Titel</th
            <th class="verwijderen"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($exports as $item) { ?>
            <tr>
                <td class="nummer"><?php echo $item->export_ID ?></td>
                <td class="Titel"><a href="<?php echo base_url('cms/aanmeldingen/geschiedenisExport/'. $item->export_ID) ?>"><?php echo $item->export_naam ?></a></td>
                <td class="verwijderen"><a href="<?php echo base_url('cms/aanmeldingen/exportVerwijderen/'.$item->export_ID) ?>" title="Export verwijderen">export verwijderen</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <p>Er zijn geen exports beschikbaar</p>
    <?php } ?>
</div>
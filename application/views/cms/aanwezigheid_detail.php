<h1>Aanwezigheid: <?php echo $groep_les->les_titel ?></h1>

<div id="links">
    <a href="<?php echo base_url('cms/aanwezigheid/exporteren/'.$groep_les->groep_les_ID) ?>" title="Groep exporteren">Aanwezigheid exporteren</a>
    <a onclick="myApp.printTable(<?php echo $groep_les->groep_les_ID ?>)" title="Aanwezigheid printen">Aanwezigheid printen</a>
</div>
<div id="aanwezigheid" class="formulier">
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th>Naam</th>
                <th>Telefoonnummer</th>
                <th>Beoordeling</th>
                <th>Aanwezig</th>
                <th>Ingeschreven voor</th>
                <th>Uitgenodigd voor vervolg</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($cursisten) && sizeof($cursisten) > 0) { ?>
            <form method="post" enctype="multipart/form-data">
            <?php foreach($cursisten as $cursist): ?>
                <tr>
                    <td class="Naam"><a href="<?php echo base_url('cms/deelnemers/'.$cursist->gebruiker_ID) ?>" title="Deelnemer bekijken"><span class="<?php if($item->gebruiker_markering == 'ja') { echo 'mark'; } ?>"><?php echo $cursist->gebruiker_naam ?></span></a></td>
                    <td class="Telefoonnummer"><a href="<?php echo base_url('cms/deelnemers/'.$cursist->gebruiker_ID) ?>" title="Deelnemer bekijken"><?php if(empty($cursist->gebruiker_telefoonnummer) && empty($cursist->gebruiker_mobiel)) { echo '...'; } else { if(empty($cursist->gebruiker_mobiel)) echo $cursist->gebruiker_telefoonnummer; else echo $cursist->gebruiker_mobiel; } ?></a></td>
                    <td>
                        <div class="<?php echo $cursist->gebruiker_ID ?>">
                    <div class="rating_gebruiker">
                            <label class="<?php if($cursist->gebruiker_beoordeling >= 5) { echo ' selected'; } ?>">
                                <input onchange="updateRating($(this), this, <?php echo $cursist->gebruiker_ID ?>, <?php echo $groep_les->les_ID ?>)" type="radio" name="rating_gebruiker" value="5" title="5 stars"> 5
                            </label>
                            <label class="<?php if($cursist->gebruiker_beoordeling >= 4) { echo ' selected'; } ?>">
                                <input onchange="updateRating($(this), this, <?php echo $cursist->gebruiker_ID ?>, <?php echo $groep_les->les_ID ?>)" type="radio" name="rating_gebruiker" value="4" title="4 stars"> 4
                            </label>
                            <label class="<?php if($cursist->gebruiker_beoordeling >= 3) { echo ' selected'; } ?>">
                                <input onchange="updateRating($(this), this, <?php echo $cursist->gebruiker_ID ?>, <?php echo $groep_les->les_ID ?>)" type="radio" name="rating_gebruiker" value="3" title="3 stars"> 3
                            </label>
                            <label class="<?php if($cursist->gebruiker_beoordeling >= 2) { echo ' selected'; } ?>">
                                <input onchange="updateRating($(this), this, <?php echo $cursist->gebruiker_ID ?>, <?php echo $groep_les->les_ID ?>)" type="radio" name="rating_gebruiker" value="2" title="2 stars"> 2
                            </label>
                            <label class="<?php if($cursist->gebruiker_beoordeling >= 1) { echo ' selected'; } ?>">
                                <input onchange="updateRating($(this), this, <?php echo $cursist->gebruiker_ID ?>, <?php echo $groep_les->les_ID ?>)" type="radio" name="rating_gebruiker" value="1" title="1 star"> 1
                            </label>
                        </div>
                    </div>
                    </td>
                    <td class="Aanwezigheid">  <input type="radio" name="aanwezigheid[<?php echo  $cursist->gebruiker_ID ?>]" <?php if($cursist->aanwezig == "ja") { echo 'checked'; } ?> value="ja" title="ja"> ja  <input type="radio" name="aanwezigheid[<?php echo  $cursist->gebruiker_ID ?>]" <?php if($cursist->aanwezig == "nee") { echo 'checked'; } ?> value="nee" title="nee"> nee</td>
                    <td class="Workshops"><?php echo $cursist->groepen ?></td>
                    <td class="Uitgenodigd">  <input type="radio" name="uitgenodigd[<?php echo  $cursist->gebruiker_ID ?>]" <?php if($cursist->gebruiker_uitgenodigd_vervolg == "ja") { echo 'checked'; } ?> value="ja" title="ja"> ja  <input type="radio" name="uitgenodigd[<?php echo  $cursist->gebruiker_ID ?>]" <?php if($cursist->gebruiker_uitgenodigd_vervolg == "nee") { echo 'checked'; } ?> value="nee" title="nee"> nee</td>
                </tr>
                  <?php endforeach; ?>
            </tbody>
        </table>
        <p class="submit"><input style="float: right;" type="submit" name="aanwezigheid_opslaan" id="aanwezigheid_opslaan" value="Aanwezigheid opslaan" /></p>
    </form>
    <?php } else { ?>
    <tr><td>Geen cursisten beschikbaar</td><td></td><td></td><td></td></tr>
    </tbody>
    </table>
    <?php } ?>
</div>


<body>
	<div id="printable" hidden>
        <h2><?php echo $groep->groep_naam . ": " . $les->les_titel ?></h2>
        <p>Datum/Tijd: <?php echo $groep_les->groep_les_datum ?> <?php echo $groep_les->groep_les_eindtijd ?></p>

        <table id="tab">
            <th>Profiel foto</th>
            <th>Naam</th>
            <th>Telefoonnummer</th>
            <th>Aanwezigheid</th>

            <?php foreach($deelnemers as $cursist) { ?>
                <tr><td><img alt="Geen profiel foto" width="50" height="50" src="<?php echo $cursist->profiel_foto ?>" /></td><td><?php echo $cursist->gebruiker_naam ?></td><td><?php echo $cursist->gebruiker_telefoonnummer ?></td><td><?php echo $cursist->aanwezig ?></td></tr>
            <?php } ?>
        </table>
    </div>
</body>
<script>
    var myApp = new function () {
        this.printTable = function (el) {
            var restorepage = document.body.innerHTML;

            var tab = document.getElementById('printable');
            tab.hidden = false;
            var win = window.open('', '', 'height=700,width=700');
            win.document.write(tab.outerHTML);
            win.print();

            win.close();
            tab.hidden = true;
        }
    }
</script>
</html>
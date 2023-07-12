<h1>Couponcodes</h1>

<div id="links">
    <a href="<?php echo base_url('cms/kortingscodes/toevoegen') ?>" title="Kortingscode toevoegen">Couponcode toevoegen</a>
    <a href="javascript:;" onclick="document.getElementById('kortingscode_form').submit();" title="Couponcodes verwijderen" class="verwijderen">Couponcodes verwijderen</a>
</div>

<div id="kortingscodes">
    <form method="post" id="kortingscode_form" action="<?php echo base_url('cms/kortingscodes/kortingscodes_verwijderen/') ?>">
    <?php if(sizeof($kortingscodes) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th>Couponcode</th>
                <th>Korting op</th>
                <th>Korting</th>
                <th>Limiet</th>
                <th class="bekijken"></th>
                <th class="wijzigen"></th>
                <th class="verwijderen"></th>
            </tr>
            </thead>
            <tbody>
            <p><input type="checkbox" name="kortingscodes_checkbox" onClick="toggle(this)"/>Alle couponcodes</p>
            <?php
            foreach($kortingscodes as $item):
                ?>
                <tr>
                    <td class="geselecteerd"><input type="checkbox" name="geselecteerde_kortingscodes[]" value="<?php echo $item->kortingscode_ID ?>"></td>
                    <td title="ID"><?php echo $item->kortingscode_ID ?></td>
                    <td><a href="<?php echo base_url('cms/kortingscodes/'.$item->kortingscode_ID) ?>" title="Couponcode bekijken"><?php echo $item->kortingscode ?></a></td>
                    <td title="item_aantal"><?php if(!empty($item->item_aantal) && $item->item_aantal > 1) { echo $item->item_aantal . ' items'; } elseif (!empty($item->item_aantal) && $item->item_aantal == 1) { echo $item->item_aantal . ' item'; } ?></td>
                    <td title="korting"><?php if(!empty($item->kortingscode_percentage)) { echo $item->kortingscode_percentage . '%'; } elseif (!empty($item->kortingscode_vast_bedrag)) { echo '€' . $item->kortingscode_vast_bedrag; } ?></td>
                    <td title="limiet"><?php if(!empty($item->kortingscode_limiet)) echo $item->kortingscode_limiet; else echo 'X'; ?></td>
                    <td class="bekijken"><a href="<?php echo base_url('cms/kortingscodes/'.$item->kortingscode_ID) ?>" title="Couponcode bekijken">Bekijken</a></td>
                    <td class="wijzigen"><a href="<?php echo base_url('cms/kortingscodes/wijzigen/'.$item->kortingscode_ID) ?>" title="Couponcode wijzigen">Wijzigen</a></td>
                    <td class="verwijderen"><a href="<?php echo base_url('cms/kortingscodes/verwijderen/'.$item->kortingscode_ID) ?>" title="Couponcode verwijderen">Verwijderen</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen couponcodes gevonden.</p>
    <?php endif; ?>
    </form>
</div>

<h1>Kortingen / Upselling</h1>
<div id="links">
    <a href="<?php echo base_url('cms/kortingscodes/upselling_toevoegen') ?>" title="Korting toevoegen">Korting toevoegen</a>
</div>
<div id="upselling">
        <?php if(sizeof($upselling) > 0): ?>
            <table cellpadding="0" cellspacing="0" class="tabel">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Workshop</th>
                    <th class="bekijken"></th>
                    <th class="wijzigen"></th>
                    <th class="verwijderen"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($upselling as $item):
                    ?>
                    <tr>
                        <td title="ID"><?php echo $item->upselling_ID ?></td>
                        <td><a href="<?php echo base_url('cms/workshops/detail/'.$item->workshop_ID) ?>" title="workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
                        <td class="bekijken"><a href="<?php echo base_url('cms/kortingscodes/upselling_detail/'.$item->upselling_ID) ?>" title="korting bekijken">Bekijken</a></td>
                        <td class="wijzigen"><a href="<?php echo base_url('cms/kortingscodes/upselling_wijzigen/'.$item->upselling_ID) ?>" title="korting wijzigen">Wijzigen</a></td>
                        <td class="verwijderen"><a href="<?php echo base_url('cms/kortingscodes/upselling_verwijderen/'.$item->upselling_ID) ?>" title="korting verwijderen">Verwijderen</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Geen kortingen gevonden.</p>
        <?php endif; ?>
    </form>
</div>

<h1>Kandidaten verkoop opties</h1>
<div id="links">
    <a href="<?php echo base_url('cms/kandidaat_verkoop/toevoegen') ?>" title="verkoop toevoegen">verkoop opties opnieuw instellen</a>
    <a href="<?php echo base_url('cms/kandidaat_verkoop/wijzigen') ?>" title="verkoop toevoegen">verkoop opties wijzigen</a>
</div>

<h2>Workshops</h2>
<div id="verkoop_optie">
    <?php if(sizeof($kandidaat_workshops) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th>#</th>
                <th>Workshop</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($kandidaat_workshops as $item):
                ?>
                <tr>
                    <td title="ID"><?php echo $item->workshop_ID ?></td>
                    <td><a href="<?php echo base_url('cms/workshops/detail/'.$item->workshop_ID) ?>" title="workshop bekijken"><?php echo $item->workshop_titel ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen verkoop workshop opties gevonden.</p>
    <?php endif; ?>
    </form>
</div>

<h2>Producten</h2>
<div id="verkoop_optie">
    <?php if(sizeof($kandidaat_producten) > 0): ?>
        <table cellpadding="0" cellspacing="0" class="tabel">
            <thead>
            <tr>
                <th>#</th>
                <th>product</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($kandidaat_producten as $item):
                ?>
                <tr>
                    <td title="ID"><?php echo $item->product_ID ?></td>
                    <td><a href="<?php echo base_url('cms/workshops/detail/'.$item->product_ID) ?>" title="product bekijken"><?php echo $item->product_naam ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Geen verkoop workshop opties gevonden.</p>
    <?php endif; ?>
    </form>
</div>
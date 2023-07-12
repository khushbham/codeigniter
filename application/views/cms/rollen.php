<style type="text/css">
    .centre{
        text-align: center !important;
    }
    th{
        border:1px solid #d6d6d6;
        border-top: 1px solid #d6d6d6;
        padding:5px;
    }
    td.centre {
        border:1px solid #d6d6d6;
    }
</style>
<h1>Rollen</h1>
<!-------------->
<!-- GEGEVENS -->
<!-------------->

<table cellpadding="0" cellspacing="0" class="gegevens">
   <thead>
        <tr>
            <th></th>
            <th>Admin</th>
            <th>Deelnemer</th>
            <th>Support</th>
            <th>Docent</th>
            <th>Opleidingsmedewerker</th>
            <th>Kandidaat</th>
            <th>Content Manager</th>
        </tr>
    </thead>
    <tbody>
        <?php if($permissions){foreach($permissions as $val){ ?>
        <tr>
            <th class="centre"><?php echo $val->permission; ?></th>
            <td class="centre">
            <?php
                if($val->user){
                    $users = explode(',',$val->user);
                    foreach($users as $user){
                        if($user == 'Admin'){ ?>
                            <img src="<?php echo base_url('assets/images/correct.png'); ?>" alt='image' height="20px" />
                       <?php }
                    }
                }
            ?>
            </td>
            <td class="centre">
            <?php
                if($val->user){
                    $users = explode(',',$val->user);
                    foreach($users as $user){
                        if($user == 'Deelnemer'){ ?>
                            <img src="<?php echo base_url('assets/images/correct.png'); ?>" alt='image' height="20px" />
                       <?php }
                    }
                }
            ?>
            </td>
            <td class="centre">
            <?php
                if($val->user){
                    $users = explode(',',$val->user);
                    foreach($users as $user){
                        if($user == 'Support'){ ?>
                            <img src="<?php echo base_url('assets/images/correct.png'); ?>" alt='image' height="20px" />
                       <?php }
                    }
                }
            ?>
            </td>
            <td class="centre">
            <?php
                if($val->user){
                    $users = explode(',',$val->user);
                    foreach($users as $user){
                        if($user == 'Docent'){ ?>
                            <img src="<?php echo base_url('assets/images/correct.png'); ?>" alt='image' height="20px"  />
                       <?php }
                    }
                }
            ?>
            </td>
            <td class="centre">
            <?php
                if($val->user){
                    $users = explode(',',$val->user);
                    foreach($users as $user){
                        if($user == 'Opleidingsmedewerker'){ ?>
                            <img src="<?php echo base_url('assets/images/correct.png'); ?>" alt='image' height="20px"  />
                       <?php }
                    }
                }
            ?>
            </td>
            <td class="centre">
            <?php
                if($val->user){
                    $users = explode(',',$val->user);
                    foreach($users as $user){
                        if($user == 'kandidaat'){ ?>
                            <img src="<?php echo base_url('assets/images/correct.png'); ?>" alt='image' height="20px" />
                       <?php }
                    }
                }
            ?>
            </td>
            <td class="centre">
            <?php
                if($val->user){
                    $users = explode(',',$val->user);
                    foreach($users as $user){
                        if($user == 'contentmanager'){ ?>
                            <img src="<?php echo base_url('assets/images/correct.png'); ?>" alt='image' height="20px" />
                       <?php }
                    }
                }
            ?>
            </td>
        </tr>

        <?php   }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Admin</th>
            <th>Deelnemer</th>
            <th>Support</th>
            <th>Docent</th>
            <th>Opleidingsmedewerker</th>
            <th>Kandidaat</th>
            <th>Content Manager</th>
        </tr>
    </tfoot>
</table>




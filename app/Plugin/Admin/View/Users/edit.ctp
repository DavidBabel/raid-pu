<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-user"></i></div>
        <h5><?php echo __('Edit user');?> : <?php echo $this->data['User']['username'];?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('User', array('url' => '/admin/users/edit/'.$this->data['User']['id'], 'class' => 'span12'));?>
            <?php $displaySaveButton = false;?>
            <?php if($user['User']['can']['full_permissions'] || ($user['User']['can']['limited_admin'] && !$this->data['User']['can']['full_permissions'])):?>
                <div class="form-group">
                    <?php echo $this->Form->input('User.status', array('type' => 'select', 'options' => array(0  => __('Disabled'), 1 => __('Enabled')), 'label' => __('Account status'), 'class' => 'span5'));?>
                </div>
                <?php $displaySaveButton = true;?>
            <?php endif;?>
            
            <?php if($user['User']['can']['full_permissions']):?>
                <div class="form-group">
                    <?php echo $this->Form->input('User.role_id', array('type' => 'select', 'label' => __('Role & permissions'), 'options' => $rolesList, 'empty' => '', 'class' => 'span5'));?>
                    <?php if(!empty($roles)):?>
                        <div class="muted">
                            <h4><i class="fa fa-info-circle"></i> <?php echo __('Roles details');?></h4>
                            <ul class="unstyled">
                                <?php foreach($roles as $role):?>
                                    <li>
                                        <strong><?php echo $role['Role']['title'];?> :</strong>
                                        <?php echo $role['Role']['description'];?>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                        <?php $displaySaveButton = true;?>
                    <?php endif;?>
                </div>
            <?php endif;?>

            <?php if($user['User']['can']['full_permissions'] || ($user['User']['can']['limited_admin'] && !$this->data['User']['can']['full_permissions'])):?>
                <br />
                <div class="form-group">
                    <?php echo $this->Form->input('User.private_infos', array('type' => 'textarea', 'label' => __('Private player note'), 'class' => 'span5 wysiwyg'));?>
                </div>
            <?php endif;?>

            <?php if($displaySaveButton):?>
                <div class="form-group">
                    <?php echo $this->Form->input('User.id', array('type' => 'hidden'));?>
                    <?php echo $this->Form->submit(__('Save'), array('class' => 'btn btn-large btn-success pull-right'));?>               
                </div>
            <?php endif?>
        <?php echo $this->Form->end();?>

        <h3><?php echo __('Characters');?></h3>
        <table class="table table-bordered table-striped responsive">
            <thead>
                <tr>
                    <th><?php echo __('Title');?></th>                    
                    <th><?php echo __('Game');?></th>
                    <th><?php echo __('Classe');?></th>                    
                    <th><?php echo __('Race');?></th>                    
                    <th><?php echo __('Role');?></th>
                    <th><?php echo __('Level');?></th>
                    <th class="actions"><?php echo __('Actions');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($userInfos['Character'])):?>
                    <?php foreach($userInfos['Character'] as $character):?>
                        <tr>
                            <td><?php echo $character['title'];?></td>
                            <td><?php echo $character['Game']['title'];?></td>
                            <td>
                                <span style="color:<?php echo $character['Classe']['color'];?>">
                                    <?php if(!empty($character['Classe']['icon'])):?>
                                        <?php echo $this->Html->image($character['Classe']['icon'], array('width' => 24));?>
                                    <?php endif;?>
                                    <?php echo $character['Classe']['title'];?>
                                </span>
                            </td>
                            <td><?php echo $character['Race']['title'];?></td>
                            <td><?php echo $character['RaidsRole']['title'];?></td>
                            <td><?php echo $character['level'];?></td>
                            <td>
                            <?php if(!$character['status']):?>
                                <?php echo $this->Html->link('<i class="fa fa-check"></i>', '/admin/users/character_enable/'.$userInfos['User']['id'].'/'.$character['id'], array('class' => 'btn btn-success btn-mini tt', 'title' => __('Enable'), 'escape' => false))?>                        
                            <?php else:?>
                                <?php echo $this->Html->link('<i class="fa fa-minus-square-o"></i>', '/admin/users/character_disable/'.$userInfos['User']['id'].'/'.$character['id'], array('class' => 'btn btn-warning btn-mini tt delete', 'title' => __('Disable'), 'data-confirm' => __('Are you sure you want to disable the character %s ?', $character['title']), 'escape' => false))?>
                            <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
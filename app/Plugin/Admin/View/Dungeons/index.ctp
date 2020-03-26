<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-list "></i></div>
        <h5><?php echo __('Dungeons list');?></h5>
        <div class="toolbar">
            <ul class="nav">
                <li><?php echo $this->Html->link('<i class="fa fa-plus"></i> '.__('Add dungeon'), '/admin/dungeons/add', array('escape' => false));?></li>
            </ul>
        </div>
    </header>
    <div class="accordion-body body in collapse">
        <?php if(!empty($dungeonsWithoutGame)):?>
            <h4><?php echo __('Dungeons without game');?></h4>
            <table class="table table-bordered table-striped responsive">
                <thead>
                    <tr>
                        <th class="span6"><?php echo __('Title');?></th>                    
                        <th class="span2"><?php echo __('Players Size');?></th>
                        <th class="span2"><?php echo __('Required level');?></th>
                        <th class="actions span2"><?php echo __('Actions');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($dungeonsWithoutGame as $dungeon):?>
                        <tr>
                            <td>
                                <?php echo $dungeon['Dungeon']['title'];?>
                                <?php if(!empty($dungeon['Dungeon']['icon'])):?>
                                    <?php echo $this->Html->image($dungeon['Dungeon']['icon'], array('width' => 24));?>
                                <?php endif;?>
                            </td>
                            <td><?php echo $dungeon['RaidsSize']['size'];?></td>
                            <td><?php echo $dungeon['Dungeon']['level_required'];?></td>
                            <td class="actions">
                                <?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/admin/dungeons/edit/'.$dungeon['Dungeon']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                <?php echo $this->Html->link('<i class="fa fa-trash"></i>', '/admin/dungeons/delete/'.$dungeon['Dungeon']['id'], array('class' => 'btn btn-danger btn-mini tt delete', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete the dungeon %s ?', $dungeon['Dungeon']['title']), 'escape' => false))?>
                            </td>
                        </tr>                
                    <?php endforeach;?>
                </tbody>
            </table>
        <?php endif;?>

        <?php if(!empty($dungeons)):?>
            <?php $currentGame = null;?>
            <?php $tableOpen = false;?>
            <?php foreach($dungeons as $dungeon):?>
                <?php $gameId = $dungeon['Dungeon']['game_id'];?>
                <?php if($gameId != $currentGame || !$currentGame):?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
                    
                    <h4><?php echo $dungeon['Game']['title'];?></h4>
                    <table class="table table-bordered table-striped responsive">
                        <thead>
                            <tr>
                                <th class="span6"><?php echo __('Title');?></th>                    
                                <th class="span2"><?php echo __('Players Size');?></th>
                                <th class="span2"><?php echo __('Required level');?></th>
                                <th class="actions span2"><?php echo __('Actions');?></th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php $currentGame = $gameId;?>
                    <?php $tableOpen = true;?>
                <?php endif;?>
                            <tr>
                                <td>
                                    <?php echo $dungeon['Dungeon']['title'];?>
                                    <?php if(!empty($dungeon['Dungeon']['icon'])):?>
                                        <?php echo $this->Html->image($dungeon['Dungeon']['icon'], array('width' => 24));?>
                                    <?php endif;?>
                                </td>
                                <td><?php echo $dungeon['RaidsSize']['size'];?></td>
                                <td><?php echo $dungeon['Dungeon']['level_required'];?></td>
                                <td class="actions">
                                    <?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/admin/dungeons/edit/'.$dungeon['Dungeon']['id'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false))?>
                                    <?php echo $this->Html->link('<i class="fa fa-minus-square-o"></i>', '/admin/dungeons/disable/'.$dungeon['Dungeon']['id'], array('class' => 'btn btn-warning btn-mini tt delete', 'title' => __('Disable'), 'data-confirm' => __('Are you sure you want to disable the dungeon %s ?', $dungeon['Dungeon']['title']), 'escape' => false))?>
                                </td>
                            </tr>                
            <?php endforeach;?>
                    <?php if($tableOpen):?>
                            </tbody>
                        </table>
                    <?php endif;?>
        <?php else:?>
            <h3 class="muted"><?php echo __('You don\'t have any dungeon yet');?></h3>
        <?php endif;?>
    </div>
</div>
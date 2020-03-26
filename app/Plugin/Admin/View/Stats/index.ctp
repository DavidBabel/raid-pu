<?php $gameId = '';?>
<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-bar-chart "></i></div>
        <h5><?php echo __('Stats');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->Form->create('Stats', array('url' => '/admin/stats', 'class' => 'statsFilter'));?>
            <table class="table">
                <tr>
                <td class="span3">
                    <div class="form-group">
                        <?php echo $this->Form->input('Stats.game_id', array('type' => 'select', 'label' => __('Game'), 'options' => $gamesList, 'empty' => '', 'required' => true, 'class' => '', 'div' => false));?>
                    </div>
                </td>
                <td class="span3">
                    <div class="form-group">
                        <?php echo $this->Form->input('Stats.characters', array('type' => 'select', 'label' => __('Characters'), 'options' => array(0 => __('All'), 1 => __('Main only')), 'default' => '1', 'div' => false));?>
                    </div>
                </td>
                <td class="span2">
                    <div class="form-group">
                        <div class="input-append">
                            <?php echo $this->Form->input('Stats.start', array('type' => 'text', 'label' => __('From'), 'div' => false, 'class' => 'input-small startDate', 'placeholder' => __('From'), 'error' => false));?>
                            <span class="add-on"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </td>
                <td class="span2">
                    <div class="form-group">
                        <div class="input-append">
                            <?php echo $this->Form->input('Stats.end', array('type' => 'text', 'label' => __('To'), 'div' => false, 'class' => 'input-small endDate', 'placeholder' => __('To'), 'error' => false));?>
                            <span class="add-on"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </td>
                <td class="span1">
                    <?php echo $this->Form->submit(__('Filter'), array('class' => 'btn btn-success', 'div' => false));?>
                </td>
                </tr>
            </table>
        <?php echo $this->Form->end();?>


        <table class="table table-bordered table-striped responsive" id="datatable">
            <thead>
                <tr>
                    <th><?php echo __('Character');?></th>                    
                    <th><?php echo __('User');?></th>                    
                    <th><?php echo __('Classe');?></th>                    
                    <th><?php echo __('Role');?></th>                    
                    <th><?php echo __('% validated');?></th>
                    <th><?php echo __('% not validated');?></th>                    
                    <th><?php echo __('% absence');?></th>
                    <th><?php echo __('% unregistered');?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($characters)):?>
                    <?php foreach($characters as $character):?>
                        <tr>
                            <td><?php echo $character['Character']['title'];?></td>
                            <td><?php echo $character['User']['username'];?></td>
                            <td><span style="color:<?php echo $character['Classe']['color'];?>"><?php echo $character['Classe']['title'];?></span></td>
                            <td><?php echo $character['RaidsRole']['title'];?></td>
                            <td>
                                <?php $stat = $character['stats']['total'] > 0?round(($character['stats']['status_2'] / $character['stats']['total']) * 100, 2):0;?>
                                <?php echo $stat;?>%
                                <small class="muted">(<?php echo $character['stats']['status_2'];?> / <?php echo $character['stats']['total'];?>)</small>
                            </td>
                            <td>
                                <?php $stat = $character['stats']['total'] > 0?round(($character['stats']['status_1'] / $character['stats']['total']) * 100, 2):0;?>
                                <?php echo $stat;?>%
                                <small class="muted">(<?php echo $character['stats']['status_1'];?> / <?php echo $character['stats']['total'];?>)</small>
                            </td>
                            <td>
                                <?php $stat = $character['stats']['total'] > 0?round(($character['stats']['status_0'] / $character['stats']['total']) * 100, 2):0;?>
                                <?php echo $stat;?>%
                                <small class="muted">(<?php echo $character['stats']['status_0'];?> / <?php echo $character['stats']['total'];?>)</small>
                            </td>
                            <td>
                                <?php $stat = $character['stats']['events_total'] > 0?round(($character['stats']['events_unregistered'] / $character['stats']['events_total']) * 100, 2):0;?>
                                <?php echo $stat;?>%
                                <small class="muted">(<?php echo $character['stats']['events_registered'];?> / <?php echo $character['stats']['events_total'];?>)</small>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
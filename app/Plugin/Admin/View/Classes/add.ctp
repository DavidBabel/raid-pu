<?php $gamesList = !empty($gamesList)?$gamesList:array();?>
<div class="box dark">
    <header>
        <div class="icons"><i class="fa fa-shield"></i></div>
        <h5><?php echo __('Add Class');?></h5>
    </header>
    <div class="accordion-body body in collapse">
        <?php echo $this->element('addClasse', array('gamesList' => $gamesList, 'isNotAjax' => true));?>
    </div>
</div>
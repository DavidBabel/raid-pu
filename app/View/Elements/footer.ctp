<footer id="footer">
    <p><?php echo __('Powered by');?> <?php echo $this->Html->link('MushRaider', 'http://mushraider.com', array('target' => '_blank', 'escape' => false));?> <span class="version">(v<?php echo Configure::read('mushraider.version');?>)</span></p>
    <p>&copy; 2013 - <?php echo date('Y');?> <?php echo $this->Html->link('Mush', 'http://www.stephane-litou.com', array('target' => '_blank', 'escape' => false));?> <?php echo __('All Rights Reserved.');?></p>
    <p><?php echo __('MushRaider is a Raid Planner mainly designed for MMORPG.');?></p>
</footer>
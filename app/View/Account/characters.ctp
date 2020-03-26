<header>
    <h1><i class="fa fa-shield"></i> <?php echo __('My characters');?></h1>
</header>

<div class="row">
    <div class="span2">
        <?php echo $this->element('account_menu');?>
    </div>
    <div class="span9">
		<div>
			<h3 class="blockToggle"><?php echo $this->Html->link('<i class="fa fa-plus-square"></i> '.__('add new character'), '', array('escape' => false));?></h3>
			<?php echo $this->Form->create('Character', array('url' => '/account/characters', 'class' => 'hide'.(isset($showForm)?' show':'')));?>
			    <div class="form-group">
			        <?php echo $this->Form->input('Character.title', array('type' => 'text', 'required' => true, 'label' => __('Character Name'), 'class' => 'span5'));?>
			    </div>
			    <div class="form-group">
			    	<?php echo $this->Form->input('Character.game_id', array('type' => 'select', 'required' => true, 'label' => __('Game'), 'options' => $gamesList, 'data-error' => __('An error occur while loading'), 'empty' => '', 'class' => 'span5'));?>
			    </div>

			    <div id="objectsPlaceholder">
			    </div>

			    <div class="form-group">		    	
			    	 <?php echo $this->Form->submit(__('Add'), array('class' => 'btn btn-success'));?>		    	
			    </div>
			<?php echo $this->Form->end();?>
		</div>

		<?php if(!empty($characters)):?>
			<table class="table table-striped charactersList">
				<thead>
					<tr>
						<th><?php echo __('Game');?></th>
						<th><?php echo __('Name');?></th>
						<th><?php echo __('Level');?></th>
						<th><?php echo __('Class');?></th>
						<th><?php echo __('Race');?></th>
						<th><?php echo __('Role');?></th>
						<th><?php echo __('Main');?></th>
						<th><?php echo __('Actions');?></th>
					</tr>
				</thead>
				<tbody>
					<?php $lastGame = null;?>
					<?php foreach($characters as $character):?>
						<?php if($lastGame && $lastGame != $character['Game']['id']):?>
							<tr>
								<td colspan="8" class="bg-warning"></td>
							</tr>
						<?php endif;?>
						<tr>
							<td><?php echo $character['Game']['title'];?></td>
							<td><?php echo $character['Character']['title'];?></td>
							<td><?php echo $character['Character']['level'];?></td>
							<td style="color:<?php echo $character['Classe']['color'];?>">
								<?php if(!empty($character['Classe']['icon'])):?>
									<?php echo $this->Html->image($character['Classe']['icon'], array('width' => 24));?>
								<?php endif;?>
								<?php echo $character['Classe']['title'];?>
							</td>
							<td><?php echo $character['Race']['title'];?></td>
							<td><?php echo $character['RaidsRole']['title'];?></td>
							<td>
								<?php if($character['Character']['status']):?>
									<div class="niceCheckbox">
										<input type="radio" name="Character.main.<?php echo $character['Game']['id'];?>" value="<?php echo $character['Character']['id'];?>" <?php echo $character['Character']['main']?'checked="checked"':'';?> id="CharacterMain<?php echo $character['Game']['id'];?>_<?php echo $character['Character']['id'];?>" />
										<label for="CharacterMain<?php echo $character['Game']['id'];?>_<?php echo $character['Character']['id'];?>">&nbsp;</label>
									</div>
								<?php endif;?>
							</td>
							<td>
								<?php echo $this->Html->link('<i class="fa fa-pencil-square-o"></i>', '/account/characters/edit/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-info btn-mini tt', 'title' => __('Edit'), 'escape' => false));?>
								<?php if($character['Character']['status']):?>
									<?php echo $this->Html->link('<i class="fa fa-minus-square-o"></i>', '/account/characters/disable/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-warning btn-mini tt', 'title' => __('Disable'), 'escape' => false));?>
								<?php else:?>
									<?php echo $this->Html->link('<i class="fa fa-check"></i>', '/account/characters/enable/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-success btn-mini tt', 'title' => __('Enable'), 'escape' => false));?>
								<?php endif;?>
								<?php echo $this->Html->link('<i class="fa fa-trash"></i>', '/account/characters/delete/c:'.$character['Character']['id'].'-'.$character['Character']['slug'], array('class' => 'btn btn-danger btn-mini tt confirm', 'title' => __('Delete'), 'data-confirm' => __('Are you sure you want to completely delete your character %s ? (this can\'t be undone)', $character['Character']['title']), 'escape' => false))?>
							</td>
						</tr>
						<?php $lastGame = $character['Game']['id'];?>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php else:?>
			<p class="message404"><i class="fa fa-arrow-up"></i> <?php echo __('Add your first character');?> <i class="fa fa-arrow-up"></i></p>
		<?php endif;?>        
    </div>
</div>
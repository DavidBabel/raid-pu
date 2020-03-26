<table width="550" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top">
        <td width="550" valign="top" align="left">
            <table cellspacing="0" cellpadding="4" bgcolor="#313131" style="color:#ffffff;">
                <tr>
                    <td>
                        <h2><?php echo __('New event');?></h2>
                    </td>
                </tr>
            </table>

            <p><?php echo __('You receive this email because a new event has been created');?> :</p>
            <p><?php echo __('Title');?> : <strong><?php echo $event['Event']['title'];?></strong></p>
            <p><?php echo __('Description');?> : <?php echo $event['Event']['description'];?></p>
            <p><?php echo __('Game');?> : <?php echo $event['Game']['title'];?></p>
            <p><?php echo __('Dungeon');?> : <?php echo $event['Dungeon']['title'];?></p>
            <p><?php echo __('Invitation time');?> : <?php echo $this->Former->date($event['Event']['time_invitation']);?></p>
            <p><?php echo __('Event start');?> : <?php echo $this->Former->date($event['Event']['time_start']);?></p>
            <br />
            <p><?php echo __('To give your availability for this event please follow this link');?> : <?php echo $this->Html->link('http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$event['Event']['id'], 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'events/view/'.$event['Event']['id'], array('escape' => false));?></p>
            <br />
            <br />
            <p style="font-size:10px"><?php echo __('If you don\'t want to receive those emails anymore please change your settings in your');?> <?php echo $this->Html->link(__('MushRaider account'), 'http://'.$_SERVER['HTTP_HOST'].$this->webroot.'account/settings', array('escape' => false));?></p>
            
            <br />
            <br />
        </td>
    </tr>
</table>
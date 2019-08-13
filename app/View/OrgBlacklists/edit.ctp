<div class="orgBlacklist form">
<?php echo $this->Form->create('OrgBlacklist');?>
    <fieldset>
        <legend><?php echo __('Edit Event Blacklist Entries');?></legend>
        <p><?php echo __('List of all the event UUIDs that you wish to block from being entered.');?></p>
    <?php
        echo $this->Form->input('uuids', array(
                'type' => 'textarea',
                'label' => __('UUIDs'),
                'div' => 'input clear',
                'class' => 'input-xxlarge',
                'disabled' => 'disabled',
                'default' => $blockEntry['OrgBlacklist']['org_uuid']
        ));
        echo $this->Form->input('org_name', array(
                'div' => 'input clear',
                'class' => 'input-xxlarge',
                'label' => __('Creating organisation'),
                'default' => $blockEntry['OrgBlacklist']['org_name'],
        ));
        echo $this->Form->input('comment', array(
                'type' => 'textarea',
                'div' => 'input clear',
                'class' => 'input-xxlarge',
                'default' => $blockEntry['OrgBlacklist']['comment'],
        ));
    ?>
    </fieldset>
<?php
echo $this->Form->button(__('Edit'), array('class' => 'btn btn-primary'));
echo $this->Form->end();
?>
</div>
<?php
    echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'admin', 'menuItem' => 'orgBlacklistsAdd'));
?>

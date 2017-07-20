<?php ?>
<!-- File: app/View/ExemptionType/admin_add.ctp -->
<div class="top">
	<h1><?php echo __('Add %s Type', __('Exemption')); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create(); ?>
		    <fieldset>
		        <legend><?php echo __('Add %s Type', __('Exemption')); ?></legend>
		    	<?php
					echo $this->Form->input('shortname', array(
						'label' => __('Short Name'),
						'between' => __('Shows in the main menu, and lists.'),
						'div' => array('class' => 'forth'),
					));
					echo $this->Form->input('name', array(
						'label' => __('Normal Name'),
						'between' => __('A more descriptive name.'),
						'div' => array('class' => 'half'),
					));
					echo $this->Form->input('active', array(
						'between' => __('<br />'),
						'div' => array('class' => 'forth'),
						'type' => 'select',
						'options' => array(1 => __('Yes'), 0 => __('No')),
					));
					echo $this->Html->divClear();
					echo $this->Form->input('notify', array(
						'label' => __('Send Expiration Notification?'),
						'div' => array('class' => 'forth'),
						'type' => 'select',
						'options' => array(1 => __('Yes'), 0 => __('No')),
					));
					echo $this->Form->input('notification_email', array(
						'label' => __('Expiration Notification Email Addresses (seperate by comma)'),
						'div' => array('class' => 'threeforths'),
					));
					echo $this->Html->divClear();
					echo $this->Form->input('notification_details', array(
						'label' => __('Details/Inscructions to be included in the email.'),
					));
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Save')); ?>
	</div>
</div>
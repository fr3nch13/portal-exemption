<?php ?>
<!-- File: app/View/ExemptionSource/admin_edit.ctp -->
<div class="top">
	<h1><?php echo __('Edit %s Source', __('Exemption')); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create(); ?>
		    <fieldset>
		        <legend><?php echo __('Edit Exemption Source'); ?></legend>
		    	<?php
					echo $this->Form->input('id');
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
						'source' => 'select',
						'options' => array(1 => __('Yes'), 0 => __('No')),
					));
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Save')); ?>
	</div>
</div>
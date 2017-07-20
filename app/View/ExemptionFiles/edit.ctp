<?php 
// File: app/View/ExemptionFiles/add.ctp
?>
<div class="top">
	<h1><?php echo __('Edit %s', __('Exemption %s', __('Artifact'))); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create('ExemptionFile');?>
		    <fieldset>
		        <legend><?php echo __('Edit %s', __('Exemption %s', __('Artifact'))); ?></legend>
		    	<?php
					
					echo $this->Form->input('id');

					echo $this->Form->input('nicename', array(
						'label' => __('Friendly Name'),
						'div' => array('class' => 'half'),
					));
					
					echo $this->Wrap->divClear();
					
					echo $this->Form->input('notes', array(
						'label' => array(
							'text' => __('Notes'),
						),
					));
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Update %s', __('Exemption %s', __('Artifact')))); ?>
	</div>
</div>
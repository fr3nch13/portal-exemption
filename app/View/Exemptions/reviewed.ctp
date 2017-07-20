<?php ?>
<!-- File: app/View/Exemption/reviewed.ctp -->
<div class="top">
	<h1><?php echo __('Mark %s Reviewed', __('Exemption')); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create(); ?>
		    <fieldset>
		    	<?php
		    		echo $this->Form->input('id');
				echo $this->Form->input('review_status_id', array(
					'label' => __('Review Status'),
					'empty' => __('[ Not Reviewed ]'),
				));
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Save')); ?>
	</div>
</div>
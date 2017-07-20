<?php 
// File: app/View/Exemptions/multiselect_review_status.ctp
?>
<div class="top">
	<h1><?php echo __('Assign all selected %s to %s %s', __('Exemptions'), 'a', __('Review Status')); ?></h1>
</div>
<div class="center">
	<div class="posts form">
	<?php echo $this->Form->create('Exemption'); ?>
	    <fieldset>
	        <legend><?php echo __('Assign all selected %s to %s %s', __('Exemptions'), 'a', __('Review Status')); ?></legend>
	    	<?php
				echo $this->Form->input('Exemption.review_status_id', array(
					'empty' => __('[ None ]'),
					'label' => __('Review Status'),
				));
	    	?>
	    </fieldset>
	<?php echo $this->Form->end(__('Save')); ?>
	</div>
<?php
if(isset($selected_items) and $selected_items)
{
	$details = array();
	foreach($selected_items as $selected_item)
	{
		$details[] = array('name' => __('Item: '), 'value' => $selected_item);
	}
	echo $this->element('Utilities.details', array(
			'title' => __('Selected %s. Count: %s', __('Exemptions'), count($selected_items)),
			'details' => $details,
		));
}
?>
</div>

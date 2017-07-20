<?php 
// File: app/View/Exemptions/multiselect_ad_division.ctp
?>
<div class="top">
	<h1><?php echo __('Assign all selected %s to %s %s', __('AD Accounts'), 'a', __('Division')); ?></h1>
</div>
<div class="center">
	<div class="posts form">
	<?php echo $this->Form->create('AdAccount'); ?>
	    <fieldset>
	        <legend><?php echo __('Assign all selected %s to %s %s', __('AD Accounts'), 'a', __('Division')); ?></legend>
	    	<?php
				echo $this->Form->input('division_id', array(
					'empty' => __('[ None ]'),
					'label' => __('Division'),
					'searchable' => true,
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
			'title' => __('Selected %s. Count: %s', __('AD Accounts'), count($selected_items)),
			'details' => $details,
		));
}
?>
</div>

<?php
 ?>
<!-- File: app/View/Exemption/add.ctp -->
<div class="top">
	<h1><?php echo __('Edit %s', __('Exemption')); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create('Exemption', array('type' => 'file', 'id' => 'add_form')); ?>
		    <fieldset>
		        <legend><?php echo __('%s Information', __('Exemption')); ?></legend>
		        <h3><?php echo __('%s Information', __('Exemption')); ?></h3>
		    	<?php
		    		echo $this->Form->input('id');
				echo $this->Form->input('exemption_type_id', array(
					'label' => __('%s Type', __('Exemption')),
					'after' => __('The Short Name of the %s Type (example: PIV).', __('Exemption')),
					'div' => array('class' => 'forth'),
				));
				echo $this->Form->input('exemption_source_id', array(
					'label' => __('%s Source', __('Exemption')),
					'after' => __('The Short Name of the %s Source.', __('Exemption')),
					'div' => array('class' => 'forth'),
				));
				echo $this->Form->input('review_status_id', array(
					'label' => __('Review Status'),
					'empty' => __('[ Not Reviewed ]'),
					'div' => array('class' => 'forth'),
				));
				echo $this->Form->input('expiration_date', array(
					'type' => 'datetime',
					'div' => array('class' => 'forth'),
				));
				
				echo $this->Html->divClear();
				
				echo $this->Form->input('example_ticket', array(
					'label' => __('Sample Ticket'),
					'div' => array('class' => 'half'),
				));
				echo $this->Form->input('tickets', array(
					'label' => __('Other Ticket'),
					'div' => array('class' => 'half'),
					'type' => 'text',
				));
				echo $this->Form->input('request_details', array(
					'label' => __('%s Request Details', __('Exemption')),
				));
				
		    	?>
		    </fieldset>
		    <fieldset>
		        <h3><?php echo __('Account Information'); ?></h3>
		    	<?php
				echo $this->Form->input('primary_account_type', array(
					'label' => __('Which account is the primary account directly associated with this %s?', __('Exemption')),
					'options' => array(
						0 => __('AD Account'),
						1 => __('Associated Account'),
					),
				));
				
				echo $this->Html->divClear();
				
				echo $this->Form->input('ad_account', array(
					'label' => __('AD Account Username'),
					'div' => array('class' => 'half'),
					'id' => 'ad_account',
					'rel' => $this->Html->url(array(
						'controller' => 'ad_accounts',
						'action' => 'autocomplete',
					)),
				));
				
				echo $this->Form->input('assoc_account', array(
					'label' => __('Associated Account Username'),
					'after' => __('The Username for this exemption, if different from AD. (ex: the Admin Account name). '),
					'div' => array('class' => 'half', 'id' => 'assoc_account_div'),
					'id' => 'assoc_account',
					'rel' => $this->Html->url(array(
						'controller' => 'assoc_accounts',
						'action' => 'autocomplete',
					)),
				));
		    	?>
		    </fieldset>
		    <fieldset>
		        <h3><?php echo __('System Information'); ?></h3>
		        <?php
		        	echo $this->Form->input('serial_number', array(
					'div' => array('class' => 'forth'),
				));
		        	echo $this->Form->input('asset_tag', array(
					'div' => array('class' => 'forth'),
				));
		        	echo $this->Form->input('mac_address', array(
					'div' => array('class' => 'forth'),
				));
		        	echo $this->Form->input('location', array(
					'div' => array('class' => 'forth'),
				));
		        ?>
		    </fieldset>
		    <fieldset>
		        <?php
				echo $this->Tag->autocomplete();
		        ?>
		    </fieldset>
		<?php echo $this->Form->end(__('Save')); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function ()
{
	// add autocomplete
	$('#ad_account').autocomplete({
		serviceUrl: $('#ad_account').attr('rel'),
		preserveInput: true,
		onSearchStart: function(query) {
			// make the search value lowercase
			var value = $(this).val().toLowerCase();
			query.query = value;
			$(this).val(value);
		},
		onSelect: function(suggestion) {
			$(this).val(suggestion.data.value.toLowerCase());
		},
	});
	
	// add autocomplete
	$('#assoc_account').autocomplete({
		serviceUrl: $('#assoc_account').attr('rel'),
		preserveInput: true,
		groupBy: 'group',
		onSearchStart: function(query) {
			// make the search value lowercase
			var value = $(this).val().toLowerCase();
			query.query = value;
			$(this).val(value);
		},
		onSearchComplete: function (query, suggestions) {
		},
		onSelect: function(suggestion) {
			$(this).val(suggestion.data.value.toLowerCase());
			if (suggestion.record.AdAccount.hasOwnProperty('username')) {
				$('#ad_account').val(suggestion.record.AdAccount.username);
			}
		},
	});
});
//]]>
</script>
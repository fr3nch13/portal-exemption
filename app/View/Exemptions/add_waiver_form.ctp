<?php ?>
<!-- File: app/View/Exemption/add_waiver_form.ctp -->
<div class="top">
	<h1><?php echo __('Add %s from %s', __('Exemption'), __('Waiver PDF')); ?></h1>
</div>
<div class="center">
	<div class="form">
		<?php echo $this->Form->create('Exemption', array('type' => 'file', 'id' => 'add_form')); ?>
		    <fieldset>
		        <legend class="section"><?php echo __('PDF Waiver Form'); ?></legend>
		    	<?php
				$max_upload = (int)(ini_get('upload_max_filesize'));
				$max_post = (int)(ini_get('post_max_size'));
				$memory_limit = (int)(ini_get('memory_limit'));
				$upload_mb = min($max_upload, $max_post, $memory_limit);
				
				echo $this->Form->input('file', array(
					'type' => 'file',
					'between' => __('(Max file size is %sM).', $upload_mb),
					'label' => '',
				));
		    	?>
		    </fieldset>
		<?php echo $this->Form->end(__('Save')); ?>
	</div>
</div>
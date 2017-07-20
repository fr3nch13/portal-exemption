<?php 
// File: app/View/ExemptionFiles/exemption.ctp

$page_options = array();

$page_options[] = $this->Html->link(__('Add %s ', __('Exemption %s', __('Artifact'))), array('action' => 'add', $exemption['Exemption']['id']));

// content
$th = array(
	'ExemptionFile.filename' => array('content' => __('Filename'), 'options' => array('sort' => 'ExemptionFile.filename')),
	'ExemptionFile.nicename' => array('content' => __('Name'), 'options' => array('sort' => 'ExemptionFile.nicename')),
	'ExemptionFile.notes' => array('content' => __('Notes')),
	'User.name' => array('content' => __('Uploaded By'), 'options' => array('sort' => 'User.name')),
	'ExemptionFile.created' => array('content' => __('Uploaded'), 'options' => array('sort' => 'ExemptionFile.created')),
//	'ExemptionFile.modified' => array('content' => __('Modified'), 'options' => array('sort' => 'ExemptionFile.modified')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
);

$td = array();
foreach ($exemption_files as $i => $exemption_file)
{
	$actions = array(
		$this->Html->link(__('Download'), array('action' => 'download', $exemption_file['ExemptionFile']['id'])),
	);
	
	$actions[] = $this->Html->link(__('Edit'), array('action' => 'edit', $exemption_file['ExemptionFile']['id'], 'saa' => true));
	$actions[] = $this->Html->link(__('Delete'), array('action' => 'delete', $exemption_file['ExemptionFile']['id'], 'saa' => true), array('confirm' => 'Are you sure?'));
	
	$actions = implode(' ', $actions);
	
	$td[$i] = array(
		$this->Html->link($exemption_file['ExemptionFile']['filename'], array('action' => 'download', $exemption_file['ExemptionFile']['id'])),
		$exemption_file['ExemptionFile']['nicename'],
		$exemption_file['ExemptionFile']['notes'],
		$this->Html->link($exemption_file['User']['name'], array('controller' => 'users', 'action' => 'view', $exemption_file['User']['id'])),
		$this->Wrap->niceTime($exemption_file['ExemptionFile']['created']),
//		$this->Wrap->niceTime($exemption_file['ExemptionFile']['modified']),
		array(
			$actions, 
			array('class' => 'actions'),
		),
	);
}

echo $this->element('Utilities.page_index', array(
	'page_title' => __('All %s', __('Exemption %s', __('Artifact'))),
	'page_options' => $page_options,
	'search_placeholder' => __('Artifacts'),
	'th' => $th,
	'td' => $td,
));
<?php 
// File: app/View/ExemptionSources/index.ctp


$page_options = array(
	$this->Html->link(__('Add %s Source', __('Exemption')), array('action' => 'add')),
);

// content
$th = array(
	'ExemptionSource.shortname' => array('content' => __('Short Name'), 'options' => array('sort' => 'ExemptionSource.shortname')),
	'ExemptionSource.name' => array('content' => __('Normal Name'), 'options' => array('sort' => 'ExemptionSource.name')),
	'ExemptionSource.active' => array('content' => __('Active'), 'options' => array('sort' => 'ExemptionSource.active')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
);

$td = array();
foreach ($exemptionSources as $i => $exemptionSource)
{
	$active = array(
		$this->Form->postLink($this->Wrap->yesNo($exemptionSource['ExemptionSource']['active']), array('action' => 'toggle', 'active', $exemptionSource['ExemptionSource']['id'], 'admin' => true), array('confirm' => 'Are you sure?')), 
		array('class' => 'actions'),
	);
	$td[$i] = array(
		$exemptionSource['ExemptionSource']['shortname'],
		$exemptionSource['ExemptionSource']['name'],
		$active,
		array(
			$this->Html->link(__('Edit'), array('action' => 'edit', $exemptionSource['ExemptionSource']['id'])).
			$this->Html->link(__('Delete'), array('action' => 'delete', $exemptionSource['ExemptionSource']['id']),array('confirm' => 'Are you sure?')), 
			array('class' => 'actions'),
		),
	);
}

echo $this->element('Utilities.page_index', array(
	'page_title' => __('%s Sources', __('Exemption')),
	'page_options' => $page_options,
	'th' => $th,
	'td' => $td,
));
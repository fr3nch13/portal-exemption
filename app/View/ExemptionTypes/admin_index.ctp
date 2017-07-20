<?php 
// File: app/View/ExemptionTypes/index.ctp


$page_options = array(
	$this->Html->link(__('Add %s Type', __('Exemption')), array('action' => 'add')),
);

// content
$th = array(
	'ExemptionType.shortname' => array('content' => __('Short Name'), 'options' => array('sort' => 'ExemptionType.shortname')),
	'ExemptionType.name' => array('content' => __('Normal Name'), 'options' => array('sort' => 'ExemptionType.name')),
	'ExemptionType.active' => array('content' => __('Active'), 'options' => array('sort' => 'ExemptionType.active')),
	'ExemptionType.notify' => array('content' => __('Send Exp Email'), 'options' => array('sort' => 'ExemptionType.notify')),
	'ExemptionType.notification_email' => array('content' => __('Notification Email'), 'options' => array('sort' => 'ExemptionType.notification_email')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
);

$td = array();
foreach ($exemptionTypes as $i => $exemptionType)
{
	$active = array(
		$this->Form->postLink($this->Wrap->yesNo($exemptionType['ExemptionType']['active']), array('action' => 'toggle', 'active', $exemptionType['ExemptionType']['id'], 'admin' => true), array('confirm' => 'Are you sure?')), 
		array('class' => 'actions'),
	);
	$notify = array(
		$this->Form->postLink($this->Wrap->yesNo($exemptionType['ExemptionType']['notify']), array('action' => 'toggle', 'notify', $exemptionType['ExemptionType']['id'], 'admin' => true), array('confirm' => 'Are you sure?')), 
		array('class' => 'actions'),
	);
	$td[$i] = array(
		$exemptionType['ExemptionType']['shortname'],
		$exemptionType['ExemptionType']['name'],
		$active,
		$notify,
		$exemptionType['ExemptionType']['notification_email'],
		array(
			$this->Html->link(__('Edit'), array('action' => 'edit', $exemptionType['ExemptionType']['id'])).
			$this->Html->link(__('Delete'), array('action' => 'delete', $exemptionType['ExemptionType']['id']),array('confirm' => 'Are you sure?')), 
			array('class' => 'actions'),
		),
	);
}

echo $this->element('Utilities.page_index', array(
	'page_title' => __('%s Types', __('Exemption')),
	'page_options' => $page_options,
	'th' => $th,
	'td' => $td,
));